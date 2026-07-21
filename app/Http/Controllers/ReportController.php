<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\ProductPrice;
use App\Models\BillPayment;
use App\Models\User;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function products_report(Request $request)
    {
        $type = $request->get('type', 'active_products');
        $allowedTypes = ['active_products', 'stocked_products', 'product_prices'];

        if (!in_array($type, $allowedTypes)) {
            $type = 'active_products';
        }

        $productStatus = $request->get('product_status', 'active');

        $user = auth()->user();
        $tenantId = $user ? $user->tenant_id : null;

        $baseQuery = Product::with(['category', 'store'])
            ->where('archived', 'No')
            ->where('tenant_id', $tenantId);

        if ($productStatus === 'active') {
            $baseQuery->where('status', 'Active');
        } elseif ($productStatus === 'inactive') {
            $baseQuery->where('status', 'Inactive');
        }

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

        return view('reports.products', compact('reports', 'type', 'productStatus', 'title'));
    }

    public function products_pdf(Request $request)
    {
        $type = $request->get('type', 'active_products');
        $allowedTypes = ['active_products', 'stocked_products', 'product_prices'];

        if (!in_array($type, $allowedTypes)) {
            $type = 'active_products';
        }

        $productStatus = $request->get('product_status', 'active');

        $user = auth()->user();
        $tenantId = $user ? $user->tenant_id : null;

        $baseQuery = Product::with(['category', 'store'])
            ->where('archived', 'No')
            ->where('tenant_id', $tenantId);

        if ($productStatus === 'active') {
            $baseQuery->where('status', 'Active');
        } elseif ($productStatus === 'inactive') {
            $baseQuery->where('status', 'Inactive');
        }

        switch ($type) {
            case 'active_products':
                $reports = (clone $baseQuery)
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

        $title = ($productStatus === 'inactive' ? 'Inactive ' : '') . $title;

        $pdf = Pdf::loadView('reports.pdf_reports.products_report_pdf', compact(
            'reports',
            'type',
            'productStatus',
            'title'
        ));

        $fileName = 'product-report-' . $type . '-' . $productStatus . '.pdf';

        return $pdf->stream($fileName);
    }


    public function general_sales(Request $request, $start_date, $end_date)
    {
        try {

            $start_date = Carbon::parse($start_date)->startOfDay();
            $end_date = Carbon::parse($end_date)->endOfDay();

        } catch (\Exception $e) {

            abort(404, 'Invalid date format.');
        }

        $user = auth()->user();
        $tenantId = $user ? $user->tenant_id : null;
        $userId = $request->get('user_id');

        $paymentsQuery = BillPayment::with('store')
            ->where('archived', 'No')
            ->where('tenant_id', $tenantId)
            ->whereBetween('added_date', [$start_date, $end_date]);

        if ($userId) {
            $paymentsQuery->where('user_id', $userId);
        }

        $payments = $paymentsQuery->orderBy('added_date', 'desc')->get();

        $users = BillPayment::where('bills_payment.tenant_id', $tenantId)
            ->join('users', 'bills_payment.user_id', '=', 'users.user_id')
            ->distinct('bills_payment.user_id') // Distinct by user_id
            ->where('bills_payment.archived', 'No')
            ->orderBy('users.firstname', 'asc')
            ->get();
            // ->pluck('users.firstname', 'users.user_id', 'users.lastname');

        $title = 'SALES PAYMENT REPORT';

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

        $pdf = Pdf::loadView('reports.pdf_reports.sales_pdf', compact(
            'payments',
            'start_date',
            'end_date',
            'userId',
            'selectedUser',
            'title'
        ));

        $fileName = 'sales-payment-report-' . $start_date . '-to-' . $end_date . '.pdf';

        // return $pdf->download($fileName);
        return $pdf->stream($fileName);
    }

    public function stockAdjustments()
    {
        $user = auth()->user();
        $tenantId = $user ? $user->tenant_id : null;

        $adjustments = StockMovement::with(['product', 'store'])
            ->where('archived', 'No')
            ->where('tenant_id', $tenantId)
            ->where('stock_quantity', '<', 0)
            ->orderBy('added_date', 'desc')
            ->get();

        $title = 'Stock Adjustments Report';

        return view('reports.stock_adjustments', compact('adjustments', 'title'));
    }
}
