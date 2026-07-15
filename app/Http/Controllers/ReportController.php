<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\ProductPrice;
use App\Models\BillPayment;
use App\Models\StockMovement;
use App\Models\User;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

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

    public function sales(Request $request, $start_date, $end_date)
    {
        try {
            $startDate = Carbon::parse($start_date)->startOfDay();
            $endDate = Carbon::parse($end_date)->endOfDay();
        } catch (\Exception $e) {
            abort(404, 'Invalid date format.');
        }

        $user = auth()->user();
        $tenantId = $user ? $user->tenant_id : null;
        $userId = $request->get('user_id');

        $paymentsQuery = BillPayment::with('store')
            ->where('archived', 'No')
            ->where('tenant_id', $tenantId)
            ->whereBetween('transaction_time', [$startDate, $endDate]);

        if ($userId) {
            $paymentsQuery->where('user_id', $userId);
        }

        $payments = $paymentsQuery->orderBy('transaction_time', 'desc')->get();

        $users = User::where('tenant_id', $tenantId)
            ->where('archived', 'No')
            ->orderBy('firstname', 'asc')
            ->get();

        $title = 'Sales Payment Report';

        return view('reports.sales', compact('payments', 'start_date', 'end_date', 'userId', 'users', 'title'));
    }

    public function salesPdf(Request $request, $start_date, $end_date)
    {
        try {
            $startDate = Carbon::parse($start_date)->startOfDay();
            $endDate = Carbon::parse($end_date)->endOfDay();
        } catch (\Exception $e) {
            abort(404, 'Invalid date format.');
        }

        $user = auth()->user();
        $tenantId = $user ? $user->tenant_id : null;
        $userId = $request->get('user_id');

        $paymentsQuery = BillPayment::with('store')
            ->where('archived', 'No')
            ->where('tenant_id', $tenantId)
            ->whereBetween('transaction_time', [$startDate, $endDate]);

        if ($userId) {
            $paymentsQuery->where('user_id', $userId);
        }

        $payments = $paymentsQuery->orderBy('transaction_time', 'desc')->get();

        $selectedUser = $userId ? User::find($userId) : null;
        $title = 'Sales Payment Report';

        $pdf = Pdf::loadView('reports.sales_pdf', compact(
            'payments',
            'start_date',
            'end_date',
            'userId',
            'selectedUser',
            'title'
        ));

        $fileName = 'sales-payment-report-' . $start_date . '-to-' . $end_date . '.pdf';

        return $pdf->download($fileName);
    }

    public function stockAdjustments(Request $request, $start_date = null, $end_date = null)
    {
        $user = auth()->user();
        $tenantId = $user ? $user->tenant_id : null;
        $userId = $request->get('user_id');
        $reportType = $request->get('report_type');

        $users = User::where('tenant_id', $tenantId)
            ->where('archived', 'No')
            ->orderBy('firstname', 'asc')
            ->get();

        $reportTypes = StockMovement::where('tenant_id', $tenantId)
            ->where('archived', 'No')
            ->whereNotNull('stocked_type')
            ->distinct()
            ->pluck('stocked_type')
            ->sort()
            ->values();

        if (!$start_date || !$end_date) {
            $title = 'Stock Adjustments Report';
            return view('reports.stock_adjustment', compact(
                'title',
                'start_date',
                'end_date',
                'userId',
                'reportType',
                'users',
                'reportTypes'
            ));
        }

        try {
            $startDate = Carbon::parse($start_date)->startOfDay();
            $endDate = Carbon::parse($end_date)->endOfDay();
        } catch (\Exception $e) {
            abort(404, 'Invalid date format.');
        }

        $adjustmentsQuery = StockMovement::with(['product', 'store', 'user'])
            ->where('archived', 'No')
            ->where('tenant_id', $tenantId)
            ->whereBetween('added_date', [$startDate, $endDate]);

        if ($userId) {
            $adjustmentsQuery->where('user_id', $userId);
        }

        if ($reportType) {
            $adjustmentsQuery->where('stocked_type', $reportType);
        }

        $adjustments = $adjustmentsQuery->orderBy('added_date', 'desc')->get();
        $title = 'Stock Adjustments Report';

        return view('reports.stock_adjustment', compact(
            'adjustments',
            'start_date',
            'end_date',
            'userId',
            'reportType',
            'users',
            'reportTypes',
            'title'
        ));
    }
}
