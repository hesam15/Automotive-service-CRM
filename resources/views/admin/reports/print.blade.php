<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>گزارش کارشناسی خودرو #{{ $report->id }}</title>
    <style>
        @font-face {
            font-family: 'Vazirmatn';
            src: url('{{ storage_path('app/public/fonts/vazir/Vazirmatn-Regular.ttf') }}') format('truetype');
            font-weight: normal;
        }

        * {
            font-family: 'Vazirmatn', Tahoma, Arial, sans-serif !important;
            direction: rtl;
        }

        body {
            background-color: white;
            font-size: 12px;
            color: #2d3748;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            
        }

        .container {
            padding: 15px;
        }

        .info-section {
            background-color: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            margin-bottom: 20px;
            overflow: hidden;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 12px;
            border: 1px solid #e2e8f0;
        }

        .info-table strong {
            color: #2c5282;
            display: inline-block;
            min-width: 120px;
            margin-left: 10px;
        }

        .service-section {
            background-color: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            margin-bottom: 20px;
            overflow: hidden;
        }

        .service-header {
            background-color: #ebf8ff;
            padding: 12px 15px;
            border-bottom: 1px solid #bee3f8;
        }

        .service-header h3 {
            color: #2c5282;
            margin: 0;
            font-size: 14px;
            font-weight: bold;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }

        .details-table td {
            padding: 8px;
            border: 1px solid #e2e8f0;
            vertical-align: top;
            width: 25%;
        }

        .detail-item {
            padding: 10px;
            border-radius: 6px;
        }

        .detail-item strong {
            color: #2c5282;
            display: block;
            margin-bottom: 5px;
            font-size: 11px;
            font-weight: bold;
        }

        .detail-item span {
            color: #4a5568;
            font-size: 11px;
            line-height: 1.4;
        }

        .description-box {
            background-color: #ebf8ff;
            padding: 12px 15px;
            margin: 15px;
            border: 1px solid #bee3f8;
            border-radius: 6px;
        }

        .description-box strong {
            color: #2c5282;
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        .description-box p {
            color: #4a5568;
            margin: 0;
            line-height: 1.6;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="info-section">
            <table class="info-table">
                <tr>
                    <td width="50%">
                        <strong>نام مشتری:</strong>
                        <span>{{ $report->booking->customer->fullname }}</span>
                    </td>
                    <td width="50%">
                        <strong>مدل خودرو:</strong>
                        <span>{{ $report->booking->car->name }}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>شماره تماس:</strong>
                        <span>{{ $report->booking->customer->phone }}</span>
                    </td>
                    <td>
                        <strong>تاریخ کارشناسی:</strong>
                        <span>{{ $report->date }}</span>
                    </td>
                </tr>
            </table>
        </div>

        @foreach($reportOptions as $serviceName => $serviceDetails)
            <div class="service-section">
                <div class="service-header">
                    <h3>{{ str_replace('_', ' ', $serviceName) }}</h3>
                </div>
                
                <table class="details-table">
                    @foreach(array_chunk($serviceDetails, 4, true) as $chunk)
                        <tr>
                            @foreach($chunk as $key => $value)
                                <td>
                                    <div class="detail-item">
                                        <strong>{{ $key }}:</strong>
                                        <span>{{ $value }}</span>
                                    </div>
                                </td>
                            @endforeach
                            @for($i = count($chunk); $i < 4; $i++)
                                <td></td>
                            @endfor
                        </tr>
                    @endforeach
                </table>

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
                <strong>توضیحات کلی:</strong>
                <p>{{ $reportDescriptions['description'] }}</p>
            </div>
        @endif
    </div>
</body>
</html>