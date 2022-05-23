<!DOCTYPE html>
<html lang="ar">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title') - {{ config('app.name', 'Laravel') }}</title>
        <!-- fullCalendar -->
        <link rel="stylesheet" href="/plugins/fullcalendar/main.css">
        <link href="{{ mix('css/app.css') }}" rel="stylesheet">
        @yield('styles')
        @yield('styles-l1')
        @yield('styles-l2')
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
        @yield('script_taxes')
    </head>

    <body>
        <div id="wrapper" class="index">
            @include('layouts.header')
            <!-- content -->
            <div id="page-wrapper">
                @yield('content')
            </div>
            <!-- /#page-wrapper -->
        </div>
        <script src="{{ mix('js/app.js') }}"></script>
        <!-- fullCalendar 2.2.5 -->
        <script src="/plugins/moment/moment.min.js"></script>
        <script src="/plugins/fullcalendar/main.js"></script>
        <!-- /#wrapper -->
        @yield('scripts-l2')
        @yield('scripts')
    </body>

</html>
