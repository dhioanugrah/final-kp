<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Pr;
use Illuminate\Routing\Controller;

class PdfController extends Controller
{
    public function print($id)
    {
        $pr = Pr::with('prDetails.barang')->findOrFail($id);

        $pdf = Pdf::loadView('pdf.pr', compact('pr'))->setPaper('a4', 'portrait');
        return $pdf->download("pr-$pr->id.pdf");
    }
}
