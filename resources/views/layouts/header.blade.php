<!-- Navigation -->
<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="{{ URL::to('/') }}">
            <img width="90" src="{{ asset('uploads/' . \App\Models\Facility::findOrFail(1)->logo ) }}"/>
        </a>
    </div>
    <!-- /.navbar-header -->
    <ul class="nav navbar-top-links navbar-left">
        <!-- /.dropdown-pdf -->
        @if (Auth::check())
            <li> {{ Auth::user()->username }} </li>
        @endif
    <!-- /.dropdown -->
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-user">
                @if(Entrust::ability('admin', 'facility-info'))
                    <li><a href="{{ URL::to('/facility/1/edit') }}"><i class="fa fa-gear fa-fw"></i> بيانات المنشاه</a>
                    </li>
                    <li><a href="{{ URL::to('/facilityopeningamount') }}"><i class="fa fa-gear fa-fw"></i> الرصيد
                            الافتتاحى</a>
                    </li>
                    <li><a href="{{ route('facilityTaxes.index') }}"><i class="fa fa-gear fa-fw"></i> الضريبة
                            المضافة</a>
                    </li>
                    <li class="divider"></li>
                @endif
                <li>
                    <a href="{{ url('/logout') }}"
                       onclick="event.preventDefault();
                               document.getElementById('logout-form').submit();">
                        <i class="fa fa-sign-out fa-fw"></i> خروج
                    </a>
                    <form id="logout-form"
                          action="{{ url('/logout') }}"
                          method="POST"
                          style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </li>
            </ul>
        </li>
        @if (isset($printRouteAction))
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-file-pdf-o fa-fw"></i> <i class="fa fa-caret-down"></i>
                </a>
                <ul class="dropdown-menu dropdown-user">
                    <li>
                        <a href="{{ route($printRouteAction) }}"
                           onclick="event.preventDefault();
                               window.location.href = this.href + '/' + window.location.search + '&withLetterHead=0';
                               ">
                            <i class="fa fa-sign-out fa-fw"></i> طباعة
                        </a>
                    </li>
                    <li>
                        <a href="{{ route($printRouteAction) }}"
                           onclick="event.preventDefault();
                               window.location.href = this.href + '/' + window.location.search + '&withLetterHead=1';
                               ">
                            <i class="fa fa-sign-out fa-fw"></i> طباعة بالليتر هد
                        </a>
                    </li>
                </ul>
            </li>
    @endif
    <!-- /.dropdown-user -->
    </ul>
    @include('layouts.sidebar')
</nav>
