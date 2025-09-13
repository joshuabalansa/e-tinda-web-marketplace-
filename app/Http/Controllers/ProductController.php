<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::where('user_id', Auth::id())->latest()->paginate(10);
        return view('farmer.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('farmer.products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Build validation using config-driven limits
        $maxImageSizeBytes = (int) config('upload.max_image_size', 10 * 1024 * 1024); // bytes
        $maxImageSizeKb = (int) floor($maxImageSizeBytes / 1024); // Laravel expects KB for max rule

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price_per_unit' => 'required|numeric|min:0',
            'unit_type' => 'required|string|in:kg,g,lb,piece,dozen',
            'stock_quantity' => 'required|integer|min:0',
            'harvest_date' => 'required|date',
            'category' => 'required|string|in:Vegetables,Fruits,Grains,Dairy,Meat,Other',
            // max rule expects KB
            'image_url' => ['nullable','image','mimes:jpeg,png,jpg,gif,webp','max:'.$maxImageSizeKb],
            'status' => 'required|string|in:available,unavailable,out_of_stock'
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id();

        if ($request->hasFile('image_url')) {
            // Pre-check validity and size to return clearer error than generic upload failed
            $image = $request->file('image_url');
            if (! $image->isValid()) {
                Log::error('Product image upload failed on create: invalid upload', [
                    'error_code' => $image->getError(),
                ]);
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['image_url' => 'Image upload failed: ' . self::describeUploadError($image->getError())]);
            }
            if ($image->getSize() > $maxImageSizeBytes) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['image_url' => 'Image file size must be less than ' . round($maxImageSizeBytes / (1024 * 1024), 0) . 'MB. Current size: ' . round($image->getSize() / (1024 * 1024), 2) . 'MB']);
            }
            $imagePath = $request->file('image_url')->store('products', 'public');
            $data['image_url'] = $imagePath;
        }

        Product::create($data);

        return redirect()->route('farmer.products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        if ($product->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        return view('farmer.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        if ($product->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        return view('farmer.products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        if ($product->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Build validation using config-driven limits
        $maxImageSizeBytes = (int) config('upload.max_image_size', 10 * 1024 * 1024); // bytes
        $maxImageSizeKb = (int) floor($maxImageSizeBytes / 1024); // Laravel expects KB for max rule

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => '',
            'price_per_unit' => 'required|numeric|min:0',
            'unit_type' => 'required|string|in:kg,g,lb,piece,dozen',
            'stock_quantity' => 'required|integer|min:0',
            'harvest_date' => 'nullable|date',
            'category' => 'required|string|in:Vegetables,Fruits,Grains,Dairy,Meat,Other',
            'image_url' => ['nullable','image','mimes:jpeg,png,jpg,gif,webp','max:'.$maxImageSizeKb],
            'status' => 'required|string|in:available,unavailable,out_of_stock'
        ]);

        $data = $request->except('harvest_date');
        if ($request->filled('harvest_date')) {
            $data['harvest_date'] = $request->input('harvest_date');
        }

        if ($request->hasFile('image_url')) {
            $image = $request->file('image_url');
            if (! $image->isValid()) {

                Log::error('Product image upload failed on update: invalid upload', [
                    'product_id' => $product->id,
                    'error_code' => $image->getError(),
                ]);

                return redirect()->back()
                    ->withInput()
                    ->withErrors(['image_url' => 'Image upload failed: ' . self::describeUploadError($image->getError())]);
            }

            if ($image->getSize() > $maxImageSizeBytes) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['image_url' => 'Image file size must be less than ' . round($maxImageSizeBytes / (1024 * 1024), 0) . 'MB. Current size: ' . round($image->getSize() / (1024 * 1024), 2) . 'MB']);
            }
            // Delete old image
            if ($product->image_url) {
                Storage::disk('public')->delete($product->image_url);
            }

            $imagePath = $request->file('image_url')->store('products', 'public');
            $data['image_url'] = $imagePath;
        }

        $product->update($data);

        return redirect()->route('farmer.products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if ($product->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($product->image_url) {
            Storage::disk('public')->delete($product->image_url);
        }

        $product->delete();

        return redirect()->route('farmer.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    /**
     * Map PHP upload error codes to human-readable messages.
     */
    private static function describeUploadError(?int $code): string
    {
        switch ($code) {
            case UPLOAD_ERR_INI_SIZE:
                return 'File exceeds server limit (' . ini_get('upload_max_filesize') . ').';
            case UPLOAD_ERR_FORM_SIZE:
                return 'File exceeds the form limit.';
            case UPLOAD_ERR_PARTIAL:
                return 'File was only partially uploaded.';
            case UPLOAD_ERR_NO_FILE:
                return 'No file was uploaded.';
            case UPLOAD_ERR_NO_TMP_DIR:
                return 'Missing temporary folder on server.';
            case UPLOAD_ERR_CANT_WRITE:
                return 'Failed to write file to disk.';
            case UPLOAD_ERR_EXTENSION:
                return 'A PHP extension stopped the file upload.';
            default:
                return 'Unknown upload error.';
        }
    }
}
