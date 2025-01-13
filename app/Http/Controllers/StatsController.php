<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\DB;


class StatsController extends Controller
{
    public function index()
    {
        // Fetch Best-Selling Items
        $bestSellingItems = DB::table('itemorder')
            ->select('items.name', DB::raw('SUM(itemorder.quantity) as total_sold'))
            ->join('items', 'itemorder.item_id', '=', 'items.id')
            ->groupBy('items.name')
            ->orderBy('total_sold', 'desc')
            ->get();

        // Fetch Total Orders
        $totalOrders = DB::table('orders')->count();

        // Fetch Total Revenue
        $totalRevenue = DB::table('orders')->sum('total_amount');

        // Fetch Monthly Revenue for Charts
        $monthlyRevenue = DB::table('orders')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('SUM(total_amount) as revenue'))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('revenue', 'month')
            ->toArray();

        // Prepare months (e.g., Jan, Feb, etc.)
        $months = array_map(function ($month) {
            return date('F', mktime(0, 0, 0, $month, 1));
        }, array_keys($monthlyRevenue));

        // Pass all data to the view
        return view('stats', [
            'bestSellingItems' => $bestSellingItems,
            'totalOrders' => $totalOrders,
            'totalRevenue' => $totalRevenue,
            'months' => $months,
            'monthlyRevenue' => array_values($monthlyRevenue),
        ]);
    }

}
