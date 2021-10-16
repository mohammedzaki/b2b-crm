@extends("layouts.app")
@section("content")
    <div class="col-lg-12" id="printcontent">
        <div class="panel panel-default">
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="col-lg-12 no-padding">
                    <img src="/ReportsHtml/letr.png" class="letrHead" style="width: 100%; margin-bottom: 20px;" />
                </div>
                @yield('reportHeader')
                @yield('reportHTML')
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
@endsection

