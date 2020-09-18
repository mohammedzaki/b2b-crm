@extends("layouts.app")
@section("title", "تقرير عملية عميل - التقارير")
@section("content")

    <div class="row">
        {{ Form::open(["route" => $printRouteAction, "method" => "GET"]) }}
        <row class="col-lg-12 clearboth">
            <p class="text-center">
                {{ Form::hidden("ch_detialed", "1", null) }}
                {{ Form::checkbox("withLetterHead", "1", 1,
                            array(
                                "id" => "withLetterHead",
                                "class" => "checkbox_show_input"
                            )
                        ) }}
                {{ Form::label("withLetterHead", "طباعة الليتر هد") }}
                <br>
                <button class="btn btn-primary" type="submit">طباعة</button>
            </p>

        </row>
        {!! $reportHTML !!}
        {{ Form::close() }}
    </div>
@endsection