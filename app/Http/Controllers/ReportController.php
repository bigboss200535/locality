<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\ProductPrice;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type', 'active_products');
        $allowedTypes = ['active_products', 'stocked_products', 'product_prices'];

        if (!in_array($type, $allowedTypes)) {
            $type = 'active_products';
        }

        $user = auth()->user();
        $tenantId = $user ? $user->tenant_id : null;

        $baseQuery = Product::with(['category', 'store'])
            ->where('archived', 'No')
            ->where('tenant_id', $tenantId);

        switch ($type) {
            case 'active_products':
                $reports = (clone $baseQuery)
                    ->where('status', 'Active')
                    ->orderBy('product_name', 'asc')
                    ->get();
                $title = 'Active Products Report';
                break;

            case 'stocked_products':
                $reports = (clone $baseQuery)
                    ->whereHas('stock', function ($query) {
                        $query->where('archived', 'No')
                              ->where('stock_quantity', '>', 0);
                    })
                    ->with([
                        'stock' => function ($query) {
                            $query->where('archived', 'No');
                        },
                        'price' => function ($query) {
                            $query->where('archived', 'No');
                        }
                    ])
                    ->orderBy('product_name', 'asc')
                    ->get();
                $title = 'Stocked Products Report';
                break;

            case 'product_prices':
                $reports = (clone $baseQuery)
                    ->whereHas('price', function ($query) {
                        $query->where('archived', 'No');
                    })
                    ->with(['price' => function ($query) {
                        $query->where('archived', 'No');
                    }])
                    ->orderBy('product_name', 'asc')
                    ->get();
                $title = 'Product Prices Report';
                break;
        }

        return view('reports.index', compact('reports', 'type', 'title'));
    }

    public function cash_sales(Request $request, $start_date)
    {

    }
}
