<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class VendorInvoiceController extends Controller
{
    public function index(Request $request)
    {
        $invoices = Invoice::byVendor($request->user()->id)
            ->with('purchaseOrder')
            ->latest()
            ->paginate(15);

        return response()->json($invoices);
    }

    public function show(Request $request, Invoice $invoice)
    {
        // Ensure vendor can only view their own invoices
        abort_if($invoice->vendor_id !== $request->user()->id, 403);

        return response()->json($invoice->load(['purchaseOrder', 'items']));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'purchase_order_id' => 'nullable|exists:purchase_orders,id',
            'issue_date'        => 'required|date',
            'due_date'          => 'required|date|after:issue_date',
            'items'             => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity'    => 'required|numeric|min:1',
            'items.*.unit_price'  => 'required|numeric|min:0',
            'notes'             => 'nullable|string',
        ]);

        $invoice = Invoice::create([
            ...$validated,
            'vendor_id'      => $request->user()->id,
            'invoice_number' => Invoice::generateNumber(),
            'subtotal'       => collect($validated['items'])->sum(fn($i) => $i['quantity'] * $i['unit_price']),
            'tax_amount'     => 0, // calculate as needed
            'total_amount'   => collect($validated['items'])->sum(fn($i) => $i['quantity'] * $i['unit_price']),
        ]);

        // Create invoice items
        foreach ($validated['items'] as $item) {
            $invoice->items()->create($item);
        }

        return response()->json(['message' => 'Invoice created.', 'invoice' => $invoice], 201);
    }

    public function downloadPDF (Request $request, Invoice $invoice)
    {
        // Ensure vendor can only download their own invoices
        abort_if($invoice->vendor_id !== $request->user()->id, 403);

        if (!$invoice->pdf_path || !file_exists(storage_path('app/' . $invoice->pdf_path))) {
            // Generate PDF
            $pdf = Pdf::loadView('invoices.pdf', ['invoice' => $invoice]);
            $pdfPath = 'invoices/' . $invoice->invoice_number . '.pdf';
            Storage::put('public/' . $pdfPath, $pdf->output());

            // Update invoice with PDF path
            $invoice->update(['pdf_path' => 'public/' . $pdfPath]);
        }

        return response()->download(storage_path('app/' . $invoice->pdf_path));
    }
}
