<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PurchaseOrderController extends Controller
{
    // 1. Mengirim data PO ke frontend (berupa JSON)
    public function index()
    {
        // Mengambil data PO beserta relasi tender dan vendornya
        $purchaseOrders = PurchaseOrder::with(['tender', 'vendor'])->get();

        return response()->json([
            'status' => 'success',
            'data' => $purchaseOrders
        ]);
    }

    // 2. Fitur Cetak Dokumen PO (PDF)
    public function exportPDF($id)
    {
        $po = PurchaseOrder::with(['tender', 'vendor', 'items'])->findOrFail($id);

        $data = [
            'title' => 'SURAT PERINTAH KERJA (PURCHASE ORDER)',
            'date' => date('d F Y'),
            'po' => $po,
        ];

        $pdf = Pdf::loadView('reports.po', $data);
        $pdf->setPaper('A4', 'portrait');

        // Unduh otomatis
        return $pdf->download('PO_Tender_' . $po->tender_id . '.pdf');
    }

    public function UpdateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:draft,issued,completed,cancelled',
        ]);

        $po = PurchaseOrder::findOrFail($id);
        $po->update(['status' => $request->status]);

        return back()->with('success', 'Status Purchase Order berhasil diperbarui menjadi: ' . strtoupper($request->status));
    }
}
