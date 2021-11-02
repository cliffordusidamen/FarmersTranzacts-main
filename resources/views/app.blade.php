
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="/molla-template/images/icons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/molla-template/images/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/molla-template/images/icons/favicon-16x16.png">
    <link rel="mask-icon" href="/molla-template/images/icons/safari-pinned-tab.svg" color="#666666">
    <link rel="shortcut icon" href="/molla-template/images/icons/favicon.ico">
    <meta name="apple-mobile-web-app-title" content="Molla">
    <meta name="application-name" content="Molla">
    <meta name="msapplication-TileColor" content="#cc9966">
    <meta name="msapplication-config" content="/molla-template/images/icons/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">
    <link rel="stylesheet" href="/molla-template/vendor/line-awesome/line-awesome/line-awesome/css/line-awesome.min.css">
    <!-- Plugins CSS File -->
    <link rel="stylesheet" href="/molla-template/css/bootstrap.min.css">
    <link rel="stylesheet" href="/molla-template/css/plugins/owl-carousel/owl.carousel.css">
    <link rel="stylesheet" href="/molla-template/css/plugins/magnific-popup/magnific-popup.css">
    <link rel="stylesheet" href="/molla-template/css/plugins/jquery.countdown.css">
    <!-- Main CSS File -->
    <link rel="stylesheet" href="/molla-template/css/style.css">
    <link rel="stylesheet" href="/molla-template/css/plugins/nouislider/nouislider.css">
    <link rel="stylesheet" href="/molla-template/css/skins/skin-demo-13.css">
    <link rel="stylesheet" href="/molla-template/css/demos/demo-13.css">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">

    @routes
</head>

<body>
    @inertia

    <button id="scroll-top" title="Back to Top"><i class="icon-arrow-up"></i></button>

    <!-- Plugins JS File -->
    <script src="/molla-template/js/jquery.min.js"></script>
    <script src="/molla-template/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <script src="/molla-template/js/jquery.hoverIntent.min.js"></script>
    <script src="/molla-template/js/jquery.waypoints.min.js"></script>
    <script src="/molla-template/js/superfish.min.js"></script>
    <script src="/molla-template/js/owl.carousel.min.js"></script>
    <script src="/molla-template/js/bootstrap-input-spinner.js"></script>
    <script src="/molla-template/js/jquery.magnific-popup.min.js"></script>
    <script src="/molla-template/js/jquery.plugin.min.js"></script>
    <script src="/molla-template/js/jquery.countdown.min.js"></script>
    <script src="/molla-template/js/jquery.elevateZoom.min.js"></script>
    
    <!-- Main JS File -->
    <script src="/molla-template/js/main.js"></script>

    <script src="{{ mix('js/app.js') }}" defer></script>
</body>


<!-- molla/index-13.html  22 Nov 2019 09:59:31 GMT -->
</html>