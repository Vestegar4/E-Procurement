<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tender;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;


class ReportController extends Controller
{
    public function exportBAHP($tenderId)
    {
        $tenders = Tender::findOrFail($tenderId);

        $data = [
            'title' => 'BAHP Report',
            'date' => date('d F Y'),
            'tenders' => $tenders,
        ];
        #memuat file php menjadi pdf
        $pdf = Pdf::loadView('reports.bahp', $data);
        #Atur ukuran kertas dan orientasi
        $pdf->setpaper('A4', 'portrait');
        #unduh otomatis
        return $pdf->download('bahp_report.pdf');
    }
}
