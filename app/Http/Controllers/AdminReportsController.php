<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Forum;
use App\Models\ForumReply;
use App\Models\OrderItem;
use App\Enums\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class AdminReportsController extends Controller
{
    /**
     * Display the reports dashboard.
     */
    public function index(Request $request)
    {
        $reportType = $request->get('type', 'overview');

        switch ($reportType) {
            case 'users':
                return $this->usersReport($request);
            case 'orders':
                return $this->ordersReport($request);
            case 'products':
                return $this->productsReport($request);
            case 'forums':
                return $this->forumsReport($request);
            case 'financial':
                return $this->financialReport($request);
            default:
                return $this->overviewReport($request);
        }
    }

    /**
     * Overview report with summary statistics.
     */
    private function overviewReport($request)
    {
        $dateFrom = $request->get('date_from', date('Y-m-01'));
        $dateTo = $request->get('date_to', date('Y-m-d'));

        $stats = [
            'total_users' => User::count(),
            'new_users' => User::whereBetween('created_at', [$dateFrom, $dateTo])->count(),
            'total_orders' => Order::count(),
            'completed_orders' => Order::where('status', 'completed')->count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'total_revenue' => Order::where('status', 'completed')->sum('total'),
            'period_revenue' => Order::where('status', 'completed')
                ->whereBetween('created_at', [$dateFrom, $dateTo])
                ->sum('total'),
            'total_products' => Product::count(),
            'active_products' => Product::where('status', 'available')->count(),
            'total_forums' => Forum::count(),
            'active_forums' => Forum::where('status', 'active')->count(),
        ];

        $chartData = [
            'orders_by_status' => Order::selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray(),
            'users_by_role' => User::selectRaw('role, COUNT(*) as count')
                ->groupBy('role')
                ->pluck('count', 'role')
                ->toArray(),
            'monthly_revenue' => Order::selectRaw("DATE_FORMAT(created_at, '%m') as month, SUM(total) as revenue")
                ->where('status', 'completed')
                ->whereRaw("YEAR(created_at) = ?", [date('Y')])
                ->groupBy('month')
                ->pluck('revenue', 'month')
                ->toArray(),
        ];

        return view('admin.reports.index', compact('stats', 'chartData', 'dateFrom', 'dateTo'));
    }

    /**
     * Users report.
     */
    private function usersReport($request)
    {
        $query = User::query();

        if ($request->has('role') && $request->role) {
            $query->where('role', $request->role);
        }

        $users = $query->with(['products', 'orders'])->paginate(20);

        $stats = [
            'total_users' => User::count(),
            'farmers' => User::where('role', UserRole::Farmer)->count(),
            'buyers' => User::where('role', UserRole::Buyer)->count(),
            'active_users' => User::where('is_active', true)->count(),
        ];

        return view('admin.reports.users', compact('users', 'stats'));
    }

    /**
     * Orders report.
     */
    private function ordersReport($request)
    {
        $query = Order::with(['user', 'orderItems.product']);

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(20);

        $stats = [
            'total_orders' => Order::count(),
            'completed_orders' => Order::where('status', 'completed')->count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'total_revenue' => Order::where('status', 'completed')->sum('total'),
        ];

        return view('admin.reports.orders', compact('orders', 'stats'));
    }

    /**
     * Products report.
     */
    private function productsReport($request)
    {
        $query = Product::with(['user', 'orderItems']);

        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        $products = $query->latest()->paginate(20);

        $stats = [
            'total_products' => Product::count(),
            'active_products' => Product::where('status', 'available')->count(),
            'total_categories' => Product::distinct('category')->count('category'),
        ];

        return view('admin.reports.products', compact('products', 'stats'));
    }

    /**
     * Forums report.
     */
    private function forumsReport($request)
    {
        $query = Forum::with(['user', 'replies']);

        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        $forums = $query->latest()->paginate(20);

        $stats = [
            'total_forums' => Forum::count(),
            'active_forums' => Forum::where('status', 'active')->count(),
            'flagged_forums' => Forum::where('is_flagged', true)->count(),
            'total_replies' => ForumReply::count(),
        ];

        return view('admin.reports.forums', compact('forums', 'stats'));
    }

    /**
     * Financial report.
     */
    private function financialReport($request)
    {
        $dateFrom = $request->get('date_from', date('Y-m-01'));
        $dateTo = $request->get('date_to', date('Y-m-d'));

        $stats = [
            'total_revenue' => Order::where('status', 'completed')->sum('total'),
            'period_revenue' => Order::where('status', 'completed')
                ->whereBetween('created_at', [$dateFrom, $dateTo])
                ->sum('total'),
            'total_orders' => Order::where('status', 'completed')->count(),
            'average_order_value' => Order::where('status', 'completed')->avg('total'),
        ];

        return view('admin.reports.financial', compact('stats', 'dateFrom', 'dateTo'));
    }

    /**
     * Export report data.
     */
    public function export(Request $request)
    {
        $type = $request->get('type', 'users');
        $format = $request->get('format', 'csv');

        switch ($type) {
            case 'users':
                return $this->exportUsers($format, $request);
            case 'orders':
                return $this->exportOrders($format, $request);
            case 'products':
                return $this->exportProducts($format, $request);
            case 'forums':
                return $this->exportForums($format, $request);
            default:
                return redirect()->back()->with('error', 'Invalid export type');
        }
    }

    /**
     * Export users data.
     */
    private function exportUsers($format, $request)
    {
        $users = User::all();

        if ($format === 'csv') {
            $filename = 'users_report_' . date('Y-m-d') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($users) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['ID', 'Name', 'Email', 'Role', 'Business Name', 'Active', 'Created At']);

                foreach ($users as $user) {
                    fputcsv($file, [
                        $user->id,
                        $user->name,
                        $user->email,
                        $user->role->value,
                        $user->business_name,
                        $user->is_active ? 'Yes' : 'No',
                        $user->created_at->format('Y-m-d H:i:s')
                    ]);
                }

                fclose($file);
            };

            return Response::stream($callback, 200, $headers);
        }

        return redirect()->back()->with('error', 'Unsupported export format');
    }

    /**
     * Export orders data.
     */
    private function exportOrders($format, $request)
    {
        $orders = Order::with(['user'])->get();

        if ($format === 'csv') {
            $filename = 'orders_report_' . date('Y-m-d') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($orders) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['ID', 'Customer', 'Status', 'Total Amount', 'Created At']);

                foreach ($orders as $order) {
                    fputcsv($file, [
                        $order->id,
                        $order->user->name,
                        $order->status,
                        $order->total,
                        $order->created_at->format('Y-m-d H:i:s')
                    ]);
                }

                fclose($file);
            };

            return Response::stream($callback, 200, $headers);
        }

        return redirect()->back()->with('error', 'Unsupported export format');
    }

    /**
     * Export products data.
     */
    private function exportProducts($format, $request)
    {
        $products = Product::with(['user'])->get();

        if ($format === 'csv') {
            $filename = 'products_report_' . date('Y-m-d') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($products) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['ID', 'Name', 'Category', 'Price', 'Stock', 'Farmer', 'Status', 'Created At']);

                foreach ($products as $product) {
                    fputcsv($file, [
                        $product->id,
                        $product->name,
                        $product->category,
                        $product->price,
                        $product->stock_quantity,
                        $product->user->name,
                        ucfirst($product->status),
                        $product->created_at->format('Y-m-d H:i:s')
                    ]);
                }

                fclose($file);
            };

            return Response::stream($callback, 200, $headers);
        }

        return redirect()->back()->with('error', 'Unsupported export format');
    }

    /**
     * Export forums data.
     */
    private function exportForums($format, $request)
    {
        $forums = Forum::with(['user'])->get();

        if ($format === 'csv') {
            $filename = 'forums_report_' . date('Y-m-d') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($forums) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['ID', 'Title', 'Category', 'Author', 'Status', 'Flagged', 'Views', 'Created At']);

                foreach ($forums as $forum) {
                    fputcsv($file, [
                        $forum->id,
                        $forum->title,
                        $forum->category,
                        $forum->user->name,
                        $forum->status,
                        $forum->is_flagged ? 'Yes' : 'No',
                        $forum->views,
                        $forum->created_at->format('Y-m-d H:i:s')
                    ]);
                }

                fclose($file);
            };

            return Response::stream($callback, 200, $headers);
        }

        return redirect()->back()->with('error', 'Unsupported export format');
    }
}