<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>گزارش کارشناسی خودرو #{{ $report->id }}</title>
    <style>
        @font-face {
            font-family: 'Vazirmatn';
            src: url('{{ storage_path('app/public/fonts/vazir/Vazirmatn-Regular.ttf') }}') format('truetype'),
                 url('{{ public_path('fonts/vazir/Vazirmatn-Regular.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'Vazirmatn';
            src: url('{{ storage_path('app/public/fonts/vazir/Vazirmatn-Bold.ttf') }}') format('truetype'),
                 url('{{ public_path('fonts/vazir/Vazirmatn-Bold.ttf') }}') format('truetype');
            font-weight: bold;
            font-style: normal;
        }

        /* تنظیمات کلی */
        * {
            font-family: 'Vazirmatn', Tahoma, Arial, sans-serif !important;
            direction: rtl !important;
            text-align: right !important;
        }

        /* کلاس برای متون فارسی */
        .persian-text {
            font-family: 'Vazirmatn', Tahoma, Arial, sans-serif !important;
            direction: rtl !important;
            text-align: right !important;
            letter-spacing: 0 !important;
            word-spacing: -1px !important;
            font-feature-settings: "kern" 1, "liga" 1, "calt" 1 !important;
            font-kerning: normal !important;
        }

        body {
            direction: rtl;
            text-align: right;
            font-family: 'Vazirmatn', Tahoma, Arial, sans-serif;
            line-height: 1.8;
            background-color: white;
            padding: 20px;
            margin: 0;
            font-size: 14px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #eee;
        }

        .header h1, .header p {
            text-align: center !important;
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

        .footer p {
            text-align: center !important;
        }

        /* تنظیمات اضافی برای بهبود نمایش متن فارسی */
        h1, h2, h3, h4, h5, h6 {
            font-weight: bold;
            letter-spacing: 0;
            word-spacing: -1px;
        }

        p, div, span, strong {
            letter-spacing: 0;
            word-spacing: -1px;
        }

        /* تنظیمات برای جلوگیری از شکستن کلمات */
        .nowrap {
            white-space: nowrap;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="persian-text">گزارش کارشناسی خودرو</h1>
        <p class="persian-text">شماره گزارش: <span class="nowrap">{{ $report->id }}</span></p>
        <p class="persian-text">تاریخ: <span class="nowrap">{{ verta($report->created_at)->format('Y/m/d') }}</span></p>
    </div>

    <div class="info-section">
        <div class="info-grid">
            <div class="info-box">
                <h3 class="persian-text">اطلاعات مشتری</h3>
                <p class="persian-text">نام: <span class="nowrap">{{ $report->booking->customer->fullname }}</span></p>
                <p class="persian-text">شماره تماس: <span class="nowrap">{{ $report->booking->customer->phone }}</span></p>
            </div>
            <div class="info-box">
                <h3 class="persian-text">اطلاعات خودرو</h3>
                <p class="persian-text">مدل: <span class="nowrap">{{ $report->booking->car->name }}</span></p>
                <p class="persian-text">تاریخ کارشناسی: <span class="nowrap">{{ $report->date }}</span></p>
            </div>
        </div>
    </div>

    @foreach($reportOptions as $serviceName => $serviceDetails)
        <div class="service-section">
            <div class="service-header">
                <h3 class="persian-text">{{ str_replace('_', ' ', $serviceName) }}</h3>
            </div>
            
            <div class="service-details">
                @foreach($serviceDetails as $key => $value)
                    <div class="detail-item">
                        <strong class="persian-text">{{ $key }}:</strong>
                        <div class="persian-text">{{ $value }}</div>
                    </div>
                @endforeach
            </div>

            @if(isset($reportDescriptions[str_replace(' ', '_', $serviceName)]))
                <div class="description-box">
                    <strong class="persian-text">توضیحات تکمیلی:</strong>
                    <p class="persian-text">{{ $reportDescriptions[str_replace(' ', '_', $serviceName)] }}</p>
                </div>
            @endif
        </div>
    @endforeach

    @if(isset($reportDescriptions['description']))
        <div class="description-box">
            <h3 class="persian-text">توضیحات کلی</h3>
            <p class="persian-text">{{ $reportDescriptions['description'] }}</p>
        </div>
    @endif

    <div class="footer">
        <p class="persian-text">این گزارش به صورت خودکار تولید شده است</p>
        <p class="persian-text">تاریخ چاپ: <span class="nowrap">{{ verta()->format('Y/m/d H:i') }}</span></p>
    </div>
</body>
</html>