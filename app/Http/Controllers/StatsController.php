<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\DB;


class StatsController extends Controller
{
    public function index(Request $request)
{
    // Get filter parameters
    $filterBy = $request->get('filter_by', 'all'); // all, day, month, year, or custom
    $startDate = $request->get('start_date');
    $endDate = $request->get('end_date');
    $date = now();
    


    // Check if startDate and endDate are valid
    if ($startDate && $endDate) {
        // Fetch Best-Selling Items
        $bestSellingItems = DB::table('itemorder')
            ->select('items.name', DB::raw('SUM(itemorder.quantity) as total_sold'))
            ->join('items', 'itemorder.item_id', '=', 'items.id')
            ->whereBetween('itemorder.created_at', [$startDate, $endDate]) // Apply the date filter here
            ->groupBy('items.name')
            ->orderBy('total_sold', 'desc')
            ->get();

        // Fetch Total Orders
        $totalOrders = DB::table('orders')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // Fetch Total Revenue
        $totalRevenue = DB::table('orders')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_amount');

        // Fetch New Customers
        $newCustomers = DB::table('users')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // Fetch Total Expense
        $totalExpense = DB::table('item_supplier')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('buyprice');

        // Fetch Monthly Revenue for Charts
        $monthlyRevenue = DB::table('orders')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('SUM(total_amount) as revenue'))
            ->whereBetween('created_at', [$startDate, $endDate]) // Apply the date filter here
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('revenue', 'month')
            ->toArray();
    } else {
        // If no date filter is applied, fetch all data
        $bestSellingItems = DB::table('itemorder')
            ->select('items.name', DB::raw('SUM(itemorder.quantity) as total_sold'))
            ->join('items', 'itemorder.item_id', '=', 'items.id')
            ->groupBy('items.name')
            ->orderBy('total_sold', 'desc')
            ->get();

        // Fetch Total Orders (all-time)
        $totalOrders = DB::table('orders')->count();

        // Fetch Total Revenue (all-time)
        $totalRevenue = DB::table('orders')->sum('total_amount');

        // Fetch New Customers (all-time)
        $newCustomers = DB::table('users')->count();

        // Fetch Total Expense (all-time)
        $totalExpense = DB::table('item_supplier')->sum('buyprice');

        // Fetch Monthly Revenue for Charts (all-time)
        $monthlyRevenue = DB::table('orders')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('SUM(total_amount) as revenue'))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('revenue', 'month')
            ->toArray();
    }

    // Prepare months (e.g., Jan, Feb, etc.)
    $months = array_map(function ($month) {
        return date('F', mktime(0, 0, 0, $month, 1));
    }, array_keys($monthlyRevenue));

    // Pass all data to the view
    return view('stats', [
        'bestSellingItems' => $bestSellingItems,
        'totalOrders' => $totalOrders,
        'totalRevenue' => $totalRevenue,
        'newCustomers' => $newCustomers,
        'totalExpense' => $totalExpense,
        'months' => $months,
        'monthlyRevenue' => array_values($monthlyRevenue),
        'filterBy' => $filterBy,
        'startDate' => $startDate,   // Pass the start date back to the view
        'endDate' => $endDate, 
       
    ]);
    
}

    


}
