<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Notifications\InvoiceStatusNotification;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class AdminInvoiceController extends Controller
{
    public function index(Request $request)
    {
        $invoices = Invoice::with(['vendor', 'purchaseOrder'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->vendor_id, fn($q) => $q->where('vendor_id', $request->vendor_id))
            ->latest()
            ->paginate(15);

        return response()->json($invoices);
    }

    public function show(Invoice $invoice)
    {
        return response()->json($invoice->load(['vendor', 'purchaseOrder', 'items']));
    }

    public function updateStatus(Request $request, Invoice $invoice)
    {
        $request->validate([
            'status' => 'required|in:draft,sent,paid,overdue,cancelled',
        ]);

        $invoice->update([
            'status'    => $request->status,
            'paid_date' => $request->status === 'paid' ? now() : null,
        ]);

        // Notify vendor
        $invoice->vendor->notify(
            new InvoiceStatusNotification($invoice, $request->status)
        );

        return response()->json(['message' => 'Invoice status updated.', 'invoice' => $invoice]);
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return response()->json(['message' => 'Invoice deleted.']);
    }

    public function download(Invoice $invoice)
    {
        $pdf = Pdf::loadView('invoices.pdf', ['invoice' => $invoice->load(['vendor', 'items'])]);
        $pdfPath = 'invoices/' . $invoice->invoice_number . '.pdf';
        Storage::put($pdfPath, $pdf->output());

        // Update invoice with PDF path
        $invoice->update(['pdf_path' => $pdfPath]);

        return response()->download(storage_path('app/' . $pdfPath));
    }
}
