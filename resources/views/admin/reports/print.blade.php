{{-- resources/views/admin/reports/print.blade.php --}}
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>گزارش کارشناسی خودرو #{{ $report->id }}</title>
    <style>
        @font-face {
            font-family: 'Vazirmatn';
            src: url({{ storage_path('fonts/Vazirmatn-Regular.ttf') }}) format('truetype');
            font-weight: normal;
        }
        
        body {
            font-family: 'Vazirmatn', sans-serif;
            line-height: 1.6;
            direction: rtl;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #eee;
        }

        .info-section {
            margin-bottom: 30px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .info-box {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
        }

        .service-section {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        .service-header {
            background-color: #f8f9fa;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 4px;
        }

        .service-details {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 15px;
        }

        .detail-item {
            background-color: #fff;
            padding: 10px;
            border: 1px solid #eee;
            border-radius: 4px;
        }

        .description-box {
            background-color: #f0f7ff;
            padding: 15px;
            border-radius: 4px;
            margin-top: 10px;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            padding-top: 20px;
            border-top: 2px solid #eee;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>گزارش کارشناسی خودرو</h1>
        <p>شماره گزارش: {{ $report->id }}</p>
        <p>تاریخ: {{ verta($report->created_at)->format('Y/m/d') }}</p>
    </div>

    <div class="info-section">
        <div class="info-grid">
            <div class="info-box">
                <h3>اطلاعات مشتری</h3>
                <p>نام: {{ $report->booking->customer->fullname }}</p>
                <p>شماره تماس: {{ $report->booking->customer->phone }}</p>
            </div>
            <div class="info-box">
                <h3>اطلاعات خودرو</h3>
                <p>مدل: {{ $report->booking->car->name }}</p>
                <p>تاریخ کارشناسی: {{ $report->date }}</p>
            </div>
        </div>
    </div>

    @foreach($reportOptions as $serviceName => $serviceDetails)
        <div class="service-section">
            <div class="service-header">
                <h3>{{ str_replace('_', ' ', $serviceName) }}</h3>
            </div>
            
            <div class="service-details">
                @foreach($serviceDetails as $key => $value)
                    <div class="detail-item">
                        <strong>{{ $key }}:</strong>
                        <div>{{ $value }}</div>
                    </div>
                @endforeach
            </div>

            @if(isset($reportDescriptions[str_replace(' ', '_', $serviceName)]))
                <div class="description-box">
                    <strong>توضیحات تکمیلی:</strong>
                    <p>{{ $reportDescriptions[str_replace(' ', '_', $serviceName)] }}</p>
                </div>
            @endif
        </div>
    @endforeach

    @if(isset($reportDescriptions['description']))
        <div class="description-box">
            <h3>توضیحات کلی</h3>
            <p>{{ $reportDescriptions['description'] }}</p>
        </div>
    @endif

    <div class="footer">
        <p>این گزارش به صورت خودکار تولید شده است</p>
        <p>تاریخ چاپ: {{ verta()->format('Y/m/d H:i') }}</p>
    </div>
</body>
</html>