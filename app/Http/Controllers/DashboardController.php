<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductSales;
use App\Models\ProductStock;
use App\Models\Stores;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $userId = $user->user_id;
        $tenantId = $user->tenant_id;

        $last = User::findOrFail($userId);
        $last->last_login = now();
        $last->save();

        $active_products = Product::where('archived', 'No')->where('tenant_id', $tenantId)->count();

        // Greeting based on current hour
        $currentHour = Carbon::now()->format('H');
        if ($currentHour >= 0 && $currentHour < 12) {
            $greeting_icon = asset('images/sunny.png');
            $greeting_text = 'Good Morning';
        } elseif ($currentHour >= 12 && $currentHour < 18) {
            $greeting_icon = asset('images/afternoon.png');
            $greeting_text = 'Good Afternoon';
        } else {
            $greeting_icon = asset('images/night.png');
            $greeting_text = 'Good Evening';
        }

        $greeting_name = trim($user->firstname);

        // Current year sales data grouped by store
        $startOfYear = Carbon::now()->startOfYear();
        $endOfYear = Carbon::now()->endOfYear();
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        $stores = Stores::where('tenant_id', $tenantId)
            ->where('archived', 'No')
            ->orderBy('store_name', 'asc')
            ->get();

        $storeSalesSeries = [];
        $storeColors = [
            'var(--bs-primary)',
            'var(--bs-success)',
            'var(--bs-warning)',
            'var(--bs-danger)',
            'var(--bs-info)',
            'var(--bs-secondary)',
        ];

        foreach ($stores as $index => $store) {
            $monthlyTotals = [];
            for ($month = 1; $month <= 12; $month++) {
                $total = ProductSales::where('store_id', $store->store_id)
                    ->where('tenant_id', $tenantId)
                    ->where('archived', 'No')
                    ->whereYear('transaction_time', Carbon::now()->year)
                    ->whereMonth('transaction_time', $month)
                    ->sum('total');
                $monthlyTotals[] = floatval($total);
            }

            $storeSalesSeries[] = [
                'name' => $store->store_name,
                'data' => $monthlyTotals,
                'color' => $storeColors[$index % count($storeColors)],
            ];
        }

        // Current year revenue total
        $currentYearRevenue = ProductSales::where('tenant_id', $tenantId)
            ->where('archived', 'No')
            ->whereYear('transaction_time', Carbon::now()->year)
            ->sum('total');

        // Low stock products (<= 10 in stock) for current tenant
        $lowStockProducts = ProductStock::with('product')
            ->where('tenant_id', $tenantId)
            ->where('archived', 'No')
            ->where('stock_quantity', '<=', 10)
            ->orderBy('stock_quantity', 'asc')
            ->limit(10)
            ->get();

        // Products near expiry (within 30 days, not yet expired)
        $nearExpiryProducts = ProductStock::with('product')
            ->where('tenant_id', $tenantId)
            ->where('archived', 'No')
            ->whereNotNull('expiry_date')
            ->whereDate('expiry_date', '>=', Carbon::today())
            ->whereDate('expiry_date', '<=', Carbon::today()->addDays(30))
            ->orderBy('expiry_date', 'asc')
            ->limit(10)
            ->get();

        return view('dashboard', compact(
            'active_products',
            'greeting_icon',
            'greeting_text',
            'greeting_name',
            'months',
            'storeSalesSeries',
            'currentYearRevenue',
            'lowStockProducts',
            'nearExpiryProducts',
            'stores'
        ));
    }
}
