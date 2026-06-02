<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Inventory;
use App\Models\ShiftNote;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ManagerController extends Controller
{
    /**
     * Display the manager dashboard page view.
     */
    public function index()
    {
        return view('manager');
    }

    /**
     * Get statistics for the dashboard in JSON format.
     */
    public function getStats()
    {
        $todayStart = Carbon::today();
        $todayEnd = Carbon::today()->endOfDay();
        $yesterdayStart = Carbon::yesterday();
        $yesterdayEnd = Carbon::yesterday()->endOfDay();

        // 1. TODAY'S SALES
        $todaySales = Order::whereBetween('created_at', [$todayStart, $todayEnd])
            ->whereIn('status', ['pending', 'completed'])
            ->sum('total');

        $yesterdaySales = Order::whereBetween('created_at', [$yesterdayStart, $yesterdayEnd])
            ->whereIn('status', ['pending', 'completed'])
            ->sum('total');

        // Trend calculation for Sales
        $salesTrendPercent = 0;
        $salesTrendDirection = 'up';
        if ($yesterdaySales > 0) {
            $salesTrendPercent = round((($todaySales - $yesterdaySales) / $yesterdaySales) * 100);
            $salesTrendDirection = $salesTrendPercent >= 0 ? 'up' : 'down';
            $salesTrendPercent = abs($salesTrendPercent);
        } else {
            // If yesterday had 0 sales and today has sales, trend is +100%
            $salesTrendPercent = $todaySales > 0 ? 100 : 0;
            $salesTrendDirection = 'up';
        }

        // 2. ORDERS TODAY
        $todayOrders = Order::whereBetween('created_at', [$todayStart, $todayEnd])
            ->whereIn('status', ['pending', 'completed'])
            ->count();

        $yesterdayOrders = Order::whereBetween('created_at', [$yesterdayStart, $yesterdayEnd])
            ->whereIn('status', ['pending', 'completed'])
            ->count();

        $ordersDiff = $todayOrders - $yesterdayOrders;
        $ordersTrendDirection = $ordersDiff >= 0 ? 'up' : 'down';
        $ordersDiffText = abs($ordersDiff) . ' ' . ($ordersDiff >= 0 ? 'more' : 'fewer') . ' than yesterday';

        // 3. AVERAGE ORDER VALUE (AOV)
        $todayAOV = $todayOrders > 0 ? ($todaySales / $todayOrders) : 0;
        $yesterdayAOV = $yesterdayOrders > 0 ? ($yesterdaySales / $yesterdayOrders) : 0;

        $aovDiff = round($todayAOV - $yesterdayAOV, 2);
        $aovTrendDirection = $aovDiff >= 0 ? 'up' : 'down';
        $aovDiffText = '₱' . abs($aovDiff) . ' vs yesterday';

        // 4. CHART DATA: LAST 7 DAYS SALES
        $chartLabels = [];
        $chartValues = []; // in thousands, e.g. 7.4
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dayStart = $date->copy()->startOfDay();
            $dayEnd = $date->copy()->endOfDay();

            $salesForDay = Order::whereBetween('created_at', [$dayStart, $dayEnd])
                ->whereIn('status', ['pending', 'completed'])
                ->sum('total');

            // Format label as Day name abbreviation (e.g. Mon, Tue, etc.)
            $chartLabels[] = $date->format('D');
            
            // Round to 1 decimal place in thousands, e.g. 7.2k
            $chartValues[] = round($salesForDay / 1000, 1);
        }

        // 5. TOP SELLING ITEMS TODAY (fallback to all-time if today is empty)
        $topItemsToday = OrderItem::select('product_name', DB::raw('SUM(quantity) as qty_sold'), DB::raw('SUM(item_total) as revenue'))
            ->whereHas('order', function ($query) use ($todayStart, $todayEnd) {
                $query->whereBetween('created_at', [$todayStart, $todayEnd])
                      ->whereIn('status', ['pending', 'completed']);
            })
            ->groupBy('product_name')
            ->orderBy('qty_sold', 'desc')
            ->limit(5)
            ->get();

        $isFallback = false;
        if ($topItemsToday->isEmpty()) {
            $isFallback = true;
            // Fallback to all-time top selling
            $topItemsToday = OrderItem::select('product_name', DB::raw('SUM(quantity) as qty_sold'), DB::raw('SUM(item_total) as revenue'))
                ->whereHas('order', function ($query) {
                    $query->whereIn('status', ['pending', 'completed']);
                })
                ->groupBy('product_name')
                ->orderBy('qty_sold', 'desc')
                ->limit(5)
                ->get();
        }

        // Get total revenue for share calculation
        $totalRevenueForShare = $topItemsToday->sum('revenue');

        $topItemsFormatted = [];
        $categoriesMap = [
            'Americano' => 'Coffee',
            'Cafe Latte' => 'Coffee',
            'Cafe Mocha' => 'Coffee',
            'Matcha Drink' => 'Non-Coffee',
            'Sweetened' => 'Lemonade',
            'Strawberry Drink' => 'Lemonade',
            'Strawberry' => 'Lemonade',
            'Chicken Ala King' => 'Rice Bowls',
            'Sweet Garlic Longganisa' => 'Rice Bowls',
            'Chicken Fried Rice' => 'Rice Bowls',
            'Cheezy Bacon' => 'Rice Bowls',
            'Beef Tapa' => 'Rice Bowls',
        ];

        foreach ($topItemsToday as $item) {
            $sharePercent = $totalRevenueForShare > 0 ? round(($item->revenue / $totalRevenueForShare) * 100) : 0;
            $topItemsFormatted[] = [
                'product_name' => $item->product_name,
                'category' => $categoriesMap[$item->product_name] ?? 'Beverage',
                'qty_sold' => (int) $item->qty_sold,
                'revenue' => (float) $item->revenue,
                'share_percent' => $sharePercent
            ];
        }

        // 6. INVENTORY ALERTS (from database)
        $outOfStockItems = Inventory::where('quantity', 0)->get(['item_name'])->pluck('item_name')->toArray();
        $lowStockItems = Inventory::where('quantity', '>', 0)
            ->whereColumn('quantity', '<=', 'min_threshold')
            ->get(['item_name', 'quantity'])
            ->toArray();

        // 7. UNRESOLVED SHIFT NOTES
        $unresolvedShiftNotesCount = ShiftNote::where('is_done', false)->count();

        return response()->json([
            'success' => true,
            'today_sales' => (float) $todaySales,
            'today_sales_trend' => [
                'percent' => $salesTrendPercent,
                'direction' => $salesTrendDirection,
            ],
            'today_orders' => $todayOrders,
            'today_orders_trend' => [
                'text' => $ordersDiffText,
                'direction' => $ordersTrendDirection,
            ],
            'avg_order_value' => round($todayAOV, 2),
            'avg_order_value_trend' => [
                'text' => $aovDiffText,
                'direction' => $aovTrendDirection,
            ],
            'chart_data' => [
                'labels' => $chartLabels,
                'values' => $chartValues,
            ],
            'top_items' => $topItemsFormatted,
            'is_top_items_fallback' => $isFallback,
            'inventory_alerts' => [
                'out_of_stock' => $outOfStockItems,
                'low_stock' => $lowStockItems,
            ],
            'unresolved_shift_notes_count' => $unresolvedShiftNotesCount
        ]);
    }
}
