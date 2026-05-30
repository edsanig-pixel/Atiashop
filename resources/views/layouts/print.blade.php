<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'چاپ سند')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            body {
                background: white;
                padding: 0;
                margin: 0;
            }
            .no-print {
                display: none;
            }
            .print-card {
                box-shadow: none !important;
                border-radius: 0 !important;
                border: none !important;
            }
            .break-inside-avoid {
                break-inside: avoid;
            }
            /* چاپ رنگی نباشد (اختیاری) */
            * {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
        .print-card {
            max-width: 1100px;
            margin: 20px auto;
            background: white;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans antialiased">
    <div class="print-card bg-white rounded-2xl shadow-xl overflow-hidden">
        @yield('content')
    </div>
    <div class="no-print text-center mt-6">
        <button onclick="window.print();" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-full shadow-md transition">
            🖨️ چــاپ فاکتور
        </button>
    </div>
</body>
</html>