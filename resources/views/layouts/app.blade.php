<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" value="{{ csrf_token() }}"/>
    <title>Laravel & Vue CRUD Single Page Application (SPA) Tutorial - MyNotePaper</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
    <link href="{{ mix('css/_mixins.css') }}" type="text/css" rel="stylesheet"/>
    <link rel="stylesheet" href="{{ asset('gogo/font/iconsmind-s/css/iconsminds.css') }}" />
    <link rel="stylesheet" href="{{ asset('gogo/font/simple-line-icons/css/simple-line-icons.css') }}" />

    <link rel="stylesheet" href="{{ asset('gogo/css/vendor/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('gogo/css/vendor/bootstrap.rtl.only.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('gogo/css/vendor/fullcalendar.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('gogo/css/vendor/dataTables.bootstrap4.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('gogo/css/vendor/datatables.responsive.bootstrap4.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('gogo/css/vendor/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('gogo/css/vendor/glide.core.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('gogo/css/vendor/bootstrap-stars.css') }}" />
    <link rel="stylesheet" href="{{ asset('gogo/css/vendor/nouislider.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('gogo/css/vendor/bootstrap-datepicker3.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('gogo/css/vendor/component-custom-switch.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('gogo/css/main.css') }}" />
    <style>
        .bg-light {
            background-color: #eae9e9 !important;
        }
    </style>
</head>
<body id="app-container" class="menu-default show-spinner">

    <div id="app">
        <App></App>
    </div>


<script src="{{ mix('js/app.js') }}" type="text/javascript"></script>
<script src="{{ asset('gogo/js/vendor/jquery-3.3.1.min.js') }}"></script>
<script src="{{ asset('gogo/js/vendor/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('gogo/js/vendor/Chart.bundle.min.js') }}"></script>
<script src="{{ asset('gogo/js/vendor/chartjs-plugin-datalabels.js') }}"></script>
<script src="{{ asset('gogo/js/vendor/moment.min.js') }}"></script>
<script src="{{ asset('gogo/js/vendor/fullcalendar.min.js') }}"></script>
<script src="{{ asset('gogo/js/vendor/datatables.min.js') }}"></script>
<script src="{{ asset('gogo/js/vendor/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('gogo/js/vendor/glide.min.js') }}"></script>
<script src="{{ asset('gogo/js/vendor/progressbar.min.js') }}"></script>
<script src="{{ asset('gogo/js/vendor/jquery.barrating.min.js') }}"></script>
<script src="{{ asset('gogo/js/vendor/nouislider.min.js') }}"></script>
<script src="{{ asset('gogo/js/vendor/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('gogo/js/vendor/Sortable.js') }}"></script>
<script src="{{ asset('gogo/js/vendor/mousetrap.min.js') }}"></script>
<script src="{{ asset('gogo/js/dore.script.js') }}"></script>
<script src="{{ asset('gogo/js/scripts.js') }}"></script>
</body>
</html>
