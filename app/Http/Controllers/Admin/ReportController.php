<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    // Show reports dashboard
    public function index()
    {
        // Basic statistics
        $totalUsers = User::count();
        $totalOrders = Order::count();
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_amount');
        $totalProducts = Product::count();

        // Recent orders (last 30 days)
        $recentOrders = Order::with('user')
            ->where('created_at', '>=', now()->subDays(30))
            ->latest()
            ->take(10)
            ->get();

        // Monthly revenue for the last 12 months
        $monthlyRevenue = Order::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        // Order status distribution
        $orderStatusStats = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        return view('admin.reports.index', compact(
            'totalUsers',
            'totalOrders',
            'totalRevenue',
            'totalProducts',
            'recentOrders',
            'monthlyRevenue',
            'orderStatusStats'
        ));
    }

    // Generate detailed reports
    public function generate(Request $request)
    {
        $type = $request->get('type', 'overview');
        $startDate = $request->get('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        // Redirect to show with parameters
        return redirect()->route('admin.reports.show', [
            'type' => $type,
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);
    }

    // Show generated report
    public function show(Request $request)
    {
        $type = $request->get('type', 'overview');
        $startDate = $request->get('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $data = [];

        switch ($type) {
            case 'sales':
                $data = $this->getSalesReport($startDate, $endDate);
                break;
            case 'users':
                $data = $this->getUsersReport($startDate, $endDate);
                break;
            case 'orders':
                $data = $this->getOrdersReport($startDate, $endDate);
                break;
            default:
                $data = $this->getOverviewReport($startDate, $endDate);
        }

        return view('admin.reports.show', compact('data', 'type', 'startDate', 'endDate'));
    }

    private function getOverviewReport($startDate, $endDate)
    {
        return [
            'total_orders' => Order::whereBetween('created_at', [$startDate, $endDate])->count(),
            'total_revenue' => Order::whereBetween('created_at', [$startDate, $endDate])
                ->where('payment_status', 'paid')->sum('total_amount'),
            'new_users' => User::whereBetween('created_at', [$startDate, $endDate])->count(),
            'avg_order_value' => Order::whereBetween('created_at', [$startDate, $endDate])
                ->where('payment_status', 'paid')->avg('total_amount') ?? 0,
        ];
    }

    private function getSalesReport($startDate, $endDate)
    {
        return Order::with('user')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', 'paid')
            ->latest()
            ->get();
    }

    private function getUsersReport($startDate, $endDate)
    {
        return User::whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->get();
    }

    private function getOrdersReport($startDate, $endDate)
    {
        return Order::with('user')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->get();
    }
}