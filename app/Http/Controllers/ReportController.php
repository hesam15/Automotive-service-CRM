<?php

namespace App\Http\Controllers;

use Dompdf\Exception;
use App\Models\Booking;
use App\Models\Options;
use App\Models\Reports;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Helpers\PersianConvertNumberHelper;
use Mpdf\Mpdf;

class ReportController extends Controller
{
    public function index(Booking $booking, Reports $report) {
        $report->date = (new PersianConvertNumberHelper($booking->date))->convertDateToPersinan()->value;

        if (!$report) {
            return redirect()->back()->with('error', 'گزارشی برای این رزرو یافت نشد');
        }
    
        return view('admin.reports.index', compact('booking', 'report'));
    }

    public function create(Booking $booking) {
        $booking->date = (new PersianConvertNumberHelper($booking->date))->convertDateToPersinan()->value;

        $options = Options::all();
        foreach ($options as $option) {
            $option->values = json_decode($option->values);
        }

        return view('admin.reports.create', compact('booking', 'options'));
    }

    public function store(Request $request, Booking $booking) {
        $allData = $request->all();
        $explanations = [];
        $options = [];

        if($booking->status == 'completed') {
            return redirect()->back()->with('error', 'گزارش این رزرو قبلا ثبت شده است.');
        }
    
        foreach ($allData as $key => $value) {
            if (!empty($value)) {
                if (str_ends_with($key, '_explanation')) {
                    $serviceName = str_replace('_explanation', '', $key);
                    $explanations[$serviceName] = $value;
                } elseif ($key === 'description') {
                    $explanations['description'] = $value;
                } elseif ($key === 'options') {
                    $options = $value;
                }
            }
        }
    
        $request->validate([
            'options' => 'required|array',
            'car_id' => 'required|exists:cars,id',
        ]);
    
        $report = Reports::create([
            'car_id' => $request->car_id,
            'booking_id' => $booking->id,
            'reports' => json_encode($options, JSON_UNESCAPED_UNICODE),
            'description' => !empty($explanations) ? json_encode($explanations, JSON_UNESCAPED_UNICODE) : null,
        ]);

        $booking->status = 'completed';
        $booking->save();
    
        return redirect()->route('report.index', ['booking' => $booking->id, 'report' => $report->id])->with('success', 'گزارش با موفقیت ثبت شد');
    }

// ReportController.php

public function print(Reports $report)
    {
        $reportOptions = json_decode($report->reports, true) ?? [];
        $reportDescriptions = json_decode($report->description, true) ?? [];
        $report->date = (new PersianConvertNumberHelper($report->booking->date))->convertDateToPersinan()->value;
        
        // تنظیمات mPDF
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];
        
        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];
        
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 25,
            'margin_bottom' => 25,
            'margin_header' => 0,
            'margin_footer' => 0,
            'useOTL' => 0xFF,
            'useKashida' => 75,
            'fontDir' => array_merge($fontDirs, [
                public_path('fonts'),
            ]),
            'fontdata' => array_merge($fontData, [
                'vazirmatn' => [
                    'R' => 'vazir/Vazirmatn-Regular.ttf',
                    'B' => 'vazir/Vazirmatn-Bold.ttf',
                    'useOTL' => 0xFF,
                    'useKashida' => 75,
                ],
            ]),
            'default_font' => 'vazirmatn',
            'tempDir' => storage_path('app/public/temp'),
        ]);

        // تنظیم هدر و فوتر
        $mpdf->SetHTMLHeader('
            <div style="border-bottom: 2px solid #3182ce; background: #ebf8ff; padding: 15px; text-align: center; margin-left: -50px; margin-right: -50px;">
                <strong style="font-size: 18px; color: #2c5282; margin-bottom: 5px;">گزارش کارشناسی خودرو</strong>
                <div style="font-size: 12px; color: #4a5568;">شماره گزارش: '.$report->id.' | تاریخ: '.verta($report->created_at)->format('Y/m/d').'</div>
            </div>
        ');

        $mpdf->SetHTMLFooter('
            <div style="border-top: 2px solid #3182ce; background: #ebf8ff; padding: 10px; text-align: center; margin-left: -50px; margin-right: -50px;">
                <strong style="font-size: 12px; color: #4a5568;">این گزارش به صورت خودکار تولید شده است</strong>
                <div style="font-size: 12px; color: #4a5568;">تاریخ چاپ: '.verta()->format('Y/m/d H:i').'</div>
            </div>
        ');

        $html = view('admin.reports.print', compact('report', 'reportOptions', 'reportDescriptions'))->render();
        $mpdf->WriteHTML($html);
        
        return $mpdf->Output("report-{$report->id}.pdf", \Mpdf\Output\Destination::DOWNLOAD);
    }
}
