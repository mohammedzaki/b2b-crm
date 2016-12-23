@extends('layouts.app') 
@section('title', 'بيانات المنشاة') 
@section('content')
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">بيانات المنشاة</h1>
    </div>
</div>
<!-- /.row -->
<div class="row">
    
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    {{ Form::model($facility, 
            array(
                'files' => true,
                'route' => array('facility.update', $facility->id),
                'method' => 'put'
            )
        ) 
    }}
    <div class="col-lg-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                البيانات الاساسية
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">

                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                    {{ Form::label('name', 'اسم المنشأة') }} 
                    {{ Form::text('name', null, 
                        array(
                            'class' => 'form-control', 
                            'placeholder' => 'ادخل اسم المنشأة')
                        )
                    }}
                    @if ($errors->has('name'))
                    <label for="inputError" class="control-label">
                        {{ $errors->first('name') }}
                    </label>
                    @endif
                </div>

                <div class="form-group{{ $errors->has('manager') ? ' has-error' : '' }}">
                    {{ Form::label('manager', 'مدير المنشأة') }} 
                    {{ 
                        Form::text('manager', null,
                            array(
                                'class' => 'form-control',
                                'placeholder' => 'ادخل مدير المنشأة'
                            )
                        )
                    }}
                    @if ($errors->has('manager'))
                    <label for="inputError" class="control-label">
                        {{ $errors->first('manager') }}
                    </label>
                    @endif
                </div>

                <div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
                    {{ Form::label('type', 'الكيان القانونى') }}
                    {{ 
                        Form::select('type', 
                            array(
                                'individual' => 'فردي',
                                'joint' => 'تضامن',
                                'partnership' => 'توصية',
                                'limited_partnership' => 'توصية بسيطة ذات مسؤلية محدودة',
                                'stock' => 'مساهمة'
                            ), 
                            null,
                            array(
                                'class' => 'form-control', 
                            )
                        )
                    }}
                    @if ($errors->has('type'))
                    <label for="inputError" class="control-label">
                        {{ $errors->first('type') }}
                    </label>
                    @endif
                </div>

                <div class="form-group{{ $errors->has('tax_file') ? ' has-error' : '' }}">
                    {{ Form::label('tax_file', 'ملف ضريبى') }} 
                    {{ Form::text('tax_file', null, 
                        array(
                            'class' => 'form-control', 
                            'placeholder' => 'ادخل رقم الملف الضريبى')
                        )
                    }}
                    @if ($errors->has('tax_file'))
                    <label for="inputError" class="control-label">
                        {{ $errors->first('tax_file') }}
                    </label>
                    @endif
                </div>

                <div class="form-group{{ $errors->has('tax_card') ? ' has-error' : '' }}">
                    {{ Form::label('tax_card', 'بطاقة ضريبية') }} 
                    {{ Form::text('tax_card', null, 
                        array(
                            'class' => 'form-control', 
                            'placeholder' => 'ادخل رقم البطاقة الضريبية')
                        )
                    }}
                    @if ($errors->has('tax_card'))
                    <label for="inputError" class="control-label">
                        {{ $errors->first('tax_card') }}
                    </label>
                    @endif
                </div>

                <div class="form-group{{ $errors->has('trade_record') ? ' has-error' : '' }}">
                    {{ Form::label('trade_record', 'سجل تجارى') }} 
                    {{ Form::text('trade_record', null, 
                        array(
                            'class' => 'form-control', 
                            'placeholder' => 'ادخل رقم السجل التجارى')
                        )
                    }}
                    @if ($errors->has('trade_record'))
                    <label for="inputError" class="control-label">
                        {{ $errors->first('trade_record') }}
                    </label>
                    @endif
                </div>

                <div class="form-group{{ $errors->has('sales_tax') ? ' has-error' : '' }}">
                    {{ Form::label('sales_tax', 'ضريبة المبيعات') }} 
                    {{ Form::text('sales_tax', null, 
                        array(
                            'class' => 'form-control', 
                            'placeholder' => 'ادخل رقم ضريبة المبيعات')
                        )
                    }}
                    @if ($errors->has('sales_tax'))
                    <label for="inputError" class="control-label">
                        {{ $errors->first('sales_tax') }}
                    </label>
                    @endif
                </div>
                
                <div class="form-group{{ $errors->has('opening_amount') ? ' has-error' : '' }}">
                    {{ Form::label('opening_amount', 'الرصيد الافتتاحى') }} 
                    {{ Form::text('opening_amount', null, 
                        array(
                            'class' => 'form-control', 
                            'placeholder' => 'ادخل الرصيد الافتتاحي',
                            'readonly' => 'readonly')
                        )
                    }}
                    @if ($errors->has('opening_amount'))
                    <label for="inputError" class="control-label">
                        {{ $errors->first('opening_amount') }}
                    </label>
                    @endif
                </div>

                <div class="form-group{{ $errors->has('logo') ? ' has-error' : '' }}">
                    {{ Form::label('logo', 'رفع شعار الشركة') }}
                    
                    @if ($facility->logo)
                    <div>
                        <img width="200" src="{{ asset('uploads/'.$facility->logo) }}" />
                        <br /><br />
                    </div>
                    @endif

                    {{ Form::file('logo') }}
                    @if ($errors->has('logo'))
                    <label for="inputError" class="control-label">
                        {{ $errors->first('logo') }}
                    </label>
                    @endif
                </div>
                
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <div class="col-lg-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                بيانات الاتصال
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body companyinfodiv">

                <div class="form-group{{ $errors->has('country') ? ' has-error' : '' }}">
                    {{ Form::label('country', 'الدولة') }} 
                    {{ Form::text('country', null, 
                        array(
                            'class' => 'form-control', 
                            'placeholder' => 'ادخل اسم الدولة')
                        )
                    }}
                    @if ($errors->has('country'))
                    <label for="inputError" class="control-label">
                        {{ $errors->first('country') }}
                    </label>
                    @endif
                </div>

                <div class="col-lg-6 ">
                    <div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
                        {{ Form::label('city', 'المحافظة') }} 
                        {{ Form::text('city', null, 
                            array(
                                'class' => 'form-control', 
                                'placeholder' => 'ادخل اسم المحافظة')
                            )
                        }}
                        @if ($errors->has('city'))
                        <label for="inputError" class="control-label">
                            {{ $errors->first('city') }}
                        </label>
                        @endif
                    </div>
                </div>

                <div class="col-lg-6 ">
                    <div class="form-group{{ $errors->has('region') ? ' has-error' : '' }}">
                        {{ Form::label('region', 'المنطقة') }} 
                        {{ Form::text('region', null, 
                            array(
                                'class' => 'form-control', 
                                'placeholder' => 'ادخل اسم المنطقة')
                            )
                        }}
                        @if ($errors->has('region'))
                        <label for="inputError" class="control-label">
                            {{ $errors->first('region') }}
                        </label>
                        @endif
                    </div>
                </div>

                <div class="col-lg-12 clearboth form-group {{ $errors->has('address') ? ' has-error' : '' }}">
                    {{ Form::label('address', 'العنوان') }} 
                    {{ Form::text('address', null, 
                        array(
                            'class' => 'form-control', 
                            'placeholder' => 'ادخل عنوان المنشأة')
                        )
                    }}
                    @if ($errors->has('address'))
                    <label for="inputError" class="control-label">
                        {{ $errors->first('address') }}
                    </label>
                    @endif
                </div>

                <div class="col-lg-12 form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                    {{ Form::label('email', 'البريد الالكترونى') }} 
                    {{ Form::email('email', null, 
                        array(
                            'class' => 'form-control', 
                            'placeholder' => 'ادخل البريد الالكترونى')
                        )
                    }}
                    @if ($errors->has('email'))
                    <label for="inputError" class="control-label">
                        {{ $errors->first('email') }}
                    </label>
                    @endif
                </div>

                <div class="col-lg-12 form-group {{ $errors->has('website') ? ' has-error' : '' }}">
                    {{ Form::label('website', 'الموقع الالكترونى') }} 
                    {{ Form::text('website', null, 
                        array(
                            'class' => 'form-control', 
                            'placeholder' => 'ادخل الموقع الالكترونى')
                        )
                    }}
                    @if ($errors->has('website'))
                    <label for="inputError" class="control-label">
                        {{ $errors->first('website') }}
                    </label>
                    @endif
                </div>

            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <row class="col-lg-12" style="padding-bottom: 20px;">
        <div class="col-lg-6">
            <button class="btn btn-block btn-lg btn-success" type="submit">تعديل بيانات المنشأة</button>
        </div>
    </row>
    <!-- </form> -->
    {{ Form::close() }}
</div>
<!-- /.row -->

<script type="text/javascript">
    $(document).ready(function(){
        // $("#manager").select2({
        //     ajax: {
        //         url: "{{ URL::to('/getManagerList') }}",
        //         dataType: 'json',
        //         delay: 250,
        //         data: function (params) {
        //           return {
        //             q: params.term,
        //           };
        //         },
        //         processResults: function (data) {
        //           return {
        //             results: data.managers,
        //           };
        //         },
        //         cache: true
        //     },
        //     templateResult: function(data){
        //         return data.name;
        //     },
        //     templateSelection: function(data){
        //         return data.name;
        //     },
        //     dir: "rtl"
        // });
    });
</script>
@endsection