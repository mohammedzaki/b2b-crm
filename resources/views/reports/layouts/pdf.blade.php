<!DOCTYPE html>
<html>
<head>

</head>
<body>

<pageheader name="pageHeader">
    @include('reports.layouts.header')
</pageheader>
<setpageheader name="pageHeader"></setpageheader>

<htmlpageheader name="pageHtmlHeader">
    @include('reports.layouts.header')
</htmlpageheader>
<sethtmlpageheader name="pageHtmlHeader"></sethtmlpageheader>

<htmlpagefooter name="pageFooter">
    @include('reports.layouts.footer')
</htmlpagefooter>
<sethtmlpagefooter name="pageFooter"></sethtmlpagefooter>

<div class="reportHTML">
    @yield('reportHTML')
</div>

</body>
</html>