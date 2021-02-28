<?php

namespace App\Helpers;

use Mpdf\Mpdf;

class PDFHelper
{
    /**
     * Generate pdf
     * @param Request $filePath
     * @param Request $html
     * 
     * @return Response pdf
     */
    public static function generatePdfFile($filePath, $html)
    {
        error_reporting(0);

        $mpdf = new Mpdf(['tempDir' => sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'mpdf', 'format' => 'A4', [190, 236]]);
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML($html);
        $mpdf->Output($filePath, \Mpdf\Output\Destination::FILE);
    }
}
