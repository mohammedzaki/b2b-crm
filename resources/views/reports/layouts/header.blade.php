@if ($withLetterHead)
    <div class="letterHead">
        <img src="var:letrImg">
    </div>
@endif
<div class="reportTitle" align="center">
    @yield('reportTitle')
</div>
<div class="reportHeader" style="margin-bottom: 10px">
    @yield('reportHeader')
</div>