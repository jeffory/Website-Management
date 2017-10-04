<?php

namespace App\Helpers;

use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\View;

class PDFView
{
    public static function make($view, $data = [], $mergeData = [])
    {
        \Debugbar::disable();
        $pdf_options = new Options();
        $pdf_options->set('enable_html5_parser', true);
        $pdf_options->set('isRemoteEnabled', true);

        $pdf = new Dompdf($pdf_options);

        $data['pdf_mode'] = true;

        $view = View::make($view, $data, $mergeData);

        $pdf->loadHtml($view->render());
        $pdf->setPaper('A4');
        $pdf->setHttpContext(stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE
            ]
        ]));

        return $pdf;
    }

    public static function render($view, $data = [], $mergeData = [])
    {
        $pdf = static::make($view, $data, $mergeData);
        $pdf->render();
        return $pdf;
    }

    public static function output($view, $data = [], $mergeData = [])
    {

        $pdf = static::render($view, $data, $mergeData);

        return response($pdf->output())->withHeaders([
            'Content-Type' => 'application/pdf'
        ]);
    }
}