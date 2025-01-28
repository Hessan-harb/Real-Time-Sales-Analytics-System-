<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    public function getAnalytics()
    {
        $totalRevenue = Order::sum(DB::raw('quantity * price'));
        $topProducts = Order::selectRaw('orders.product_id, products.name as product_name, SUM(orders.quantity) as total_sales')
            ->join('products', 'orders.product_id', '=', 'products.id')
            ->groupBy('orders.product_id', 'products.name')
            ->orderByDesc('total_sales')
            ->take(2)
            ->get();

        $recentRevenue = Order::where('created_at', '>=', now()->subMinute())
            ->sum(DB::raw('quantity * price'));
        $orderCount = Order::where('created_at', '>=', now()->subMinute())->count();
        return [
            'total_revenue' => $totalRevenue,
            'top_products' => $topProducts,
            'recent_revenue' => $recentRevenue,
            'order_count' => $orderCount,
        ];
    }
}
