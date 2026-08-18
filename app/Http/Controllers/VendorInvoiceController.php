<?php

namespace App\Http\Controllers;

use App\Models\VendorInvoice;
use App\Services\VendorInvoiceService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VendorInvoiceController extends Controller
{
    public function index()
    {
        return view('vendor-invoices.index');
    }

    public function show(VendorInvoice $vendorInvoice)
    {
        $vendorInvoice->load(['purchase', 'supplier', 'company', 'paidBy']);

        return view('vendor-invoices.show', compact('vendorInvoice'));
    }

    public function markPaid(Request $request, VendorInvoice $vendorInvoice, VendorInvoiceService $service)
    {
        $dueAmount = max(0, (int) $vendorInvoice->amount - (int) $vendorInvoice->paid_amount);

        $validated = $request->validate([
            'payment_method' => ['required', Rule::in(['cash', 'bank_transfer', 'upi', 'cheque', 'card', 'other'])],
            'paid_amount' => ['required', 'integer', 'min:1', 'max:'.$dueAmount],
            'payment_reference' => ['nullable', 'string', 'max:255'],
            'paid_at' => ['required', 'date'],
            'payment_notes' => ['nullable', 'string', 'max:1000'],
        ], [
            'paid_amount.max' => 'Paid amount cannot be greater than the total due amount.',
        ]);

        $service->markPaid($vendorInvoice, $validated);

        return redirect()
            ->route('vendor-invoices.show', $vendorInvoice)
            ->with('success', 'Vendor invoice payment completed.');
    }
}
