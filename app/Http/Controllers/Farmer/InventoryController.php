<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $farmerId = Auth::id();

        // Get inventory records with products
        $inventoryRecords = Inventory::forFarmer($farmerId)
            ->with('product')
            ->orderBy('transaction_date', 'desc')
            ->paginate(15);

        // Get summary statistics
        $stats = [
            'total_products' => Product::where('user_id', $farmerId)->count(),
            'low_stock_products' => $this->getLowStockProducts($farmerId),
            'total_inventory_value' => Inventory::forFarmer($farmerId)->sum('total_value'),
            'recent_transactions' => Inventory::forFarmer($farmerId)
                ->where('transaction_date', '>=', Carbon::now()->subDays(7))
                ->count(),
        ];

        return view('farmer.inventory.index', compact('inventoryRecords', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $farmerId = Auth::id();
        $products = Product::where('user_id', $farmerId)
            ->where('status', 'available')
            ->orderBy('name')
            ->get();

        return view('farmer.inventory.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity_in' => 'nullable|integer|min:0',
            'quantity_out' => 'nullable|integer|min:0',
            'unit_cost' => 'nullable|numeric|min:0',
            'transaction_type' => 'required|in:adjustment,purchase,sale,loss',
            'notes' => 'nullable|string|max:1000',
            'transaction_date' => 'required|date',
            'reference_number' => 'nullable|string|max:100',
        ]);

        $farmerId = Auth::id();
        $product = Product::findOrFail($request->product_id);

        // Ensure farmer owns the product
        if ($product->user_id !== $farmerId) {
            return redirect()->back()->with('error', 'You can only manage inventory for your own products.');
        }

        $quantityIn = $request->quantity_in ?? 0;
        $quantityOut = $request->quantity_out ?? 0;
        $unitCost = $request->unit_cost ?? 0;
        $totalValue = $quantityIn * $unitCost;

        // Calculate new stock level
        $currentStock = $product->stock_quantity + $quantityIn - $quantityOut;

        // Create inventory record
        $inventory = Inventory::create([
            'farmer_id' => $farmerId,
            'product_id' => $request->product_id,
            'quantity_in' => $quantityIn,
            'quantity_out' => $quantityOut,
            'current_stock' => $currentStock,
            'unit_cost' => $unitCost,
            'total_value' => $totalValue,
            'transaction_type' => $request->transaction_type,
            'notes' => $request->notes,
            'transaction_date' => $request->transaction_date,
            'reference_number' => $request->reference_number,
        ]);

        // Update product stock
        $product->update(['stock_quantity' => $currentStock]);

        return redirect()->route('farmer.inventory.index')
            ->with('success', 'Inventory record created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Inventory $inventory)
    {
        // Ensure farmer owns this inventory record
        if ($inventory->farmer_id !== Auth::id()) {
            abort(403);
        }

        $inventory->load('product');
        return view('farmer.inventory.show', compact('inventory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inventory $inventory)
    {
        // Ensure farmer owns this inventory record
        if ($inventory->farmer_id !== Auth::id()) {
            abort(403);
        }

        $farmerId = Auth::id();
        $products = Product::where('user_id', $farmerId)
            ->where('status', 'available')
            ->orderBy('name')
            ->get();

        return view('farmer.inventory.edit', compact('inventory', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Inventory $inventory)
    {
        // Ensure farmer owns this inventory record
        if ($inventory->farmer_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity_in' => 'nullable|integer|min:0',
            'quantity_out' => 'nullable|integer|min:0',
            'unit_cost' => 'nullable|numeric|min:0',
            'transaction_type' => 'required|in:adjustment,purchase,sale,loss',
            'notes' => 'nullable|string|max:1000',
            'transaction_date' => 'required|date',
            'reference_number' => 'nullable|string|max:100',
        ]);

        $farmerId = Auth::id();
        $product = Product::findOrFail($request->product_id);

        // Ensure farmer owns the product
        if ($product->user_id !== $farmerId) {
            return redirect()->back()->with('error', 'You can only manage inventory for your own products.');
        }

        $quantityIn = $request->quantity_in ?? 0;
        $quantityOut = $request->quantity_out ?? 0;
        $unitCost = $request->unit_cost ?? 0;
        $totalValue = $quantityIn * $unitCost;

        // Calculate new stock level
        $currentStock = $product->stock_quantity + $quantityIn - $quantityOut;

        // Update inventory record
        $inventory->update([
            'product_id' => $request->product_id,
            'quantity_in' => $quantityIn,
            'quantity_out' => $quantityOut,
            'current_stock' => $currentStock,
            'unit_cost' => $unitCost,
            'total_value' => $totalValue,
            'transaction_type' => $request->transaction_type,
            'notes' => $request->notes,
            'transaction_date' => $request->transaction_date,
            'reference_number' => $request->reference_number,
        ]);

        // Update product stock
        $product->update(['stock_quantity' => $currentStock]);

        return redirect()->route('farmer.inventory.index')
            ->with('success', 'Inventory record updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inventory $inventory)
    {
        // Ensure farmer owns this inventory record
        if ($inventory->farmer_id !== Auth::id()) {
            abort(403);
        }

        $inventory->delete();

        return redirect()->route('farmer.inventory.index')
            ->with('success', 'Inventory record deleted successfully.');
    }

    /**
     * Get products with low stock
     */
    private function getLowStockProducts($farmerId)
    {
        return Product::where('user_id', $farmerId)
            ->where('stock_quantity', '<=', 10) // Assuming 10 is low stock threshold
            ->count();
    }
}