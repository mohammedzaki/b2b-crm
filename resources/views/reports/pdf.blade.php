<!DOCTYPE html>
<html>
<head>

</head>
<body>

<pageheader name="pageHeader">
    @include('reports.header')
</pageheader>
<setpageheader name="pageHeader"></setpageheader>

<htmlpageheader name="pageHtmlHeader">
    @include('reports.header')
</htmlpageheader>
<sethtmlpageheader name="pageHtmlHeader"></sethtmlpageheader>

<htmlpagefooter name="pageFooter">
    @include('reports.footer')
</htmlpagefooter>
<sethtmlpagefooter name="pageFooter"></sethtmlpagefooter>

@yield('reportHTML')

</body>
</html>