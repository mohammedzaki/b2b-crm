@extends('layouts.login') 
@section('title', 'تسجيل الدخول') 
@section('content')
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <p class="login-panel text-center"><img src="{{ asset('images/most.png') }}" /></p>
        <div class=" panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">تسجيل الدخول</h3>
            </div>
            <div class="panel-body">
                <form role="form" method="POST" action="{{ route('login') }}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <!-- time start -->
                        <div align="center">
                            <table width="100%" cellpadding="$stylevar[cellpadding]" cellspacing="$stylevar[cellspacing]" border="0">
                                <tr>
                                    <td class="alt1">
                                        <div class="alt2Active" style="padding:6px; overflow:auto">
                                            <div id="postmenu_344900">
                                                <!-- clock hack -->
                                                <div id="clock">Loading...</div>
                                                <!-- / clock hack -->
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <!-- time end-->
                    </div>
                    <fieldset>
                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                            <input class="form-control" placeholder="اسم المستخدم" name="username" type="text" value="{{ old('username') }}" autofocus> 
                            @if ($errors->has('username'))
                            <label for="inputError" class="control-label">{{ $errors->first('username') }}</label>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <input class="form-control" placeholder="كلمة المرور" name="password" type="password" value=""> 
                            @if ($errors->has('password'))
                            <label for="inputError" class="control-label">{{ $errors->first('password') }}</label>
                            @endif
                        </div>
                        <div class="form-group">
                            <select class="form-control">
                                <option selected="">{{ \Carbon\Carbon::now()->year }}</option>
                            </select>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input name="remember" type="checkbox" {{ old('remember') ? 'checked' : '' }}>تذكرنى
                            </label>
                        </div>
                        <!-- Change this to a button or input when using this as a form -->
                        <button type="submit" class="btn btn-lg btn-success btn-block">دخول</button>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function refrClock() {
        var d = new Date();
        var s = d.getSeconds();
        var m = d.getMinutes();
        var h = d.getHours();
        var day = d.getDay();
        var date = d.getDate();
        var month = d.getMonth();
        var year = d.getFullYear();
        var days = new Array("الأحد", "الاثنين", "الثلاثاء", "الأربعاء", "الخميس", "الجمعة", "السبت");
        var months = new Array("يناير", "فبراير", "مارس", "ابريل", "مايو", "يونيو", "يوليو", "أغسطس", "سبتمبر", "اكتوبر", "نوفمبر", "ديسمبر");
        var am_pm;
        if (s < 10) {
            s = "0" + s
        }
        if (m < 10) {
            m = "0" + m
        }
        if (h > 12) {
            h -= 12;
            am_pm = "مساءً"
        } else {
            am_pm = "صباحاً"
        }
        if (h < 10) {
            h = "0" + h
        }
        document.getElementById("clock").innerHTML = days[day] + " " + date + " " + months[month] + " " + year + " - الساعة الآن " + h + ":" + m + ":" + s + " " + am_pm;
        setTimeout("refrClock()", 1000);
    }
    refrClock();
</script>
@endsection
