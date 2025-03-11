<?php

namespace App\Http\Controllers;

use App\Helpers\PersianConvertNumberHelper;
use Carbon\Carbon;
use Dompdf\Exception;
use App\Models\Booking;
use App\Models\Options;
use App\Models\Reports;
use Barryvdh\DomPDF\Facade\PDF;
use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;
use SebastianBergmann\CodeCoverage\Report\Xml\Report;


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

    public function print($report) {
        try {
            $report = Reports::findOrFail($report);
            $report->load(['booking.customer', 'booking.car']);
            
            $reportOptions = json_decode($report->reports, true) ?? [];
            $reportDescriptions = json_decode($report->description, true) ?? [];
    
            // استفاده از $pdf به جای PDF::
            $pdf = Pdf::loadView('admin.reports.print', compact(
                'report',
                'reportOptions',
                'reportDescriptions'
            ));

            // Return the PDF for download
            return $pdf->download("report-{$report->id}.pdf");

        } catch (Exception $e) {
            return back()->with('error', 'خطا در ایجاد PDF: ' . $e->getMessage());
        }
    }
}
