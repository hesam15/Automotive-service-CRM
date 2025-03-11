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
        $report->date = (new PersianConvertNumberHelper($report->booking->date))->convertDateToPersinan()->value;

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
            'status' => 'completed',
        ]);
    
        return redirect()->route('report.index', ['booking' => $booking->id, 'report' => $report->id])->with('success', 'گزارش با موفقیت ثبت شد');
    }

    public function print(Reports $report) {
        try {            
            $reportOptions = json_decode($report->reports, true) ?? [];
            $reportDescriptions = json_decode($report->description, true) ?? [];
            
            // تنظیمات mPDF
            $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
            $fontDirs = $defaultConfig['fontDir'];
            
            $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
            $fontData = $defaultFontConfig['fontdata'];
            
            // پیکربندی mPDF با تنظیمات فونت فارسی
            $mpdf = new \Mpdf\Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'useOTL' => 0xFF,
                'useKashida' => 75,
                'margin_left' => 10,
                'margin_right' => 10,
                'margin_top' => 10,
                'margin_bottom' => 10,
                'fontDir' => array_merge($fontDirs, [
                    public_path('fonts'),
                ]),
                'fontdata' => array_merge($fontData, [
                    'vazirmatn' => [
                        'R' => 'vazir/Vazirmatn-Regular.ttf',
                        'B' => 'vazir/Vazirmatn-Bold.ttf',
                        'useOTL' => 0xFF,    // فعال کردن تمام ویژگی‌های OpenType
                        'useKashida' => 75,  // استفاده از کشیده برای تنظیم فاصله
                    ],
                ]),
                'default_font' => 'vazirmatn',
                'tempDir' => storage_path('app/public/temp'),
            ]);
            
            // تنظیم جهت راست به چپ
            $mpdf->SetDirectionality('rtl');
            
            // فعال کردن دسترسی به منابع خارجی
            $mpdf->curlAllowUnsafeSslRequests = true;
            
            // رندر کردن ویو
            $html = view('admin.reports.print', compact(
                'report',
                'reportOptions',
                'reportDescriptions'
            ))->render();
            
            // نوشتن HTML در PDF
            $mpdf->WriteHTML($html);
            
            // دانلود PDF
            return response($mpdf->Output("report-{$report->id}.pdf", \Mpdf\Output\Destination::STRING_RETURN), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => "attachment; filename=report-{$report->id}.pdf",
            ]);
            
        } catch (\Exception $e) {
            return back()->with('error', 'خطا در ایجاد PDF: ' . $e->getMessage());
        }
    }
}
