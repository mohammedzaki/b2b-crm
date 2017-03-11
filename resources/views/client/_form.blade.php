<div class="col-lg-6">
    <div class="panel panel-default">
        <div class="panel-heading">
            بيانات العميل
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                {{ Form::label('name', 'اسم العميل') }} 
                {{ Form::text('name', null, 
        	        array(
        	            'class' => 'form-control', 
        	            'placeholder' => 'ادخل اسم العميل')
        	        )
                }}
                @if ($errors->has('name'))
                <label for="inputError" class="control-label">
                    {{ $errors->first('name') }}
                </label>
                @endif
            </div>

            <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                {{ Form::label('address', 'العنوان') }} 
                {{ Form::text('address', null, 
        	        array(
        	            'class' => 'form-control', 
        	            'placeholder' => 'ادخل العنوان')
        	        )
                }}
                @if ($errors->has('address'))
                <label for="inputError" class="control-label">
                    {{ $errors->first('address') }}
                </label>
                @endif
            </div>

            <div class="form-group{{ $errors->has('telephone') ? ' has-error' : '' }}">
                {{ Form::label('telephone', 'التليفون') }} 
                {{ Form::text('telephone', null, 
        	        array(
        	            'class' => 'form-control', 
        	            'placeholder' => 'ادخل التليفون')
        	        )
                }}
                @if ($errors->has('telephone'))
                <label for="inputError" class="control-label">
                    {{ $errors->first('telephone') }}
                </label>
                @endif
            </div>

            <div class="form-group{{ $errors->has('mobile') ? ' has-error' : '' }}">
                {{ Form::label('mobile', 'المحمول') }} 
                {{ Form::text('mobile', null, 
        	        array(
        	            'class' => 'form-control', 
        	            'placeholder' => 'ادخل المحمول')
        	        )
                }}
                @if ($errors->has('mobile'))
                <label for="inputError" class="control-label">
                    {{ $errors->first('mobile') }}
                </label>
                @endif
            </div>

            <div class="form-group{{ $errors->has('credit_limit') ? ' has-error' : '' }}">
                {{ Form::label('credit_limit', 'الحد اﻻئتماني') }} 
                {{ Form::text('credit_limit', null, 
        	        array(
        	            'class' => 'form-control', 
        	            'placeholder' => 'ادخل الحد اﻻئتماني')
        	        )
                }}
                @if ($errors->has('credit_limit'))
                <label for="inputError" class="control-label">
                    {{ $errors->first('credit_limit') }}
                </label>
                @endif
            </div>

            <div class="legend">
                {{ Form::checkbox('is_client_company', '1', null, 
                        array(
                            'id' => 'is_client_company',
                            'class' => 'checkbox_hide_input'
                        )
                    ) }}
                {{ Form::label('is_client_company', 'عميل شركة') }}

                @if ($errors->has('is_client_company'))
                <label for="inputError" class="control-label">
                    {{ $errors->first('is_client_company') }}
                </label>
                @endif
            </div>

            <div class="hidden_input">
                <div class="col-lg-8">
                    <div class="form-group{{ $errors->has('referral') ? ' has-error' : '' }}">
                        {{ Form::label('referral_id', 'اسم المندوب') }} 
                        {{ Form::select('referral_id', $employees, null,
            		        array(
            		            'class' => 'form-control')
            		        )
                        }}
                        @if ($errors->has('referral_id'))
                        <label for="inputError" class="control-label">
                            {{ $errors->first('referral_id') }}
                        </label>
                        @endif
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="form-group{{ $errors->has('referral_percentage') ? ' has-error' : '' }}">
                        {{ Form::label('referral_percentage', 'نسبة العمولة') }} 

                        <div class="input-group">
                            {{ Form::text('referral_percentage', null, 
                	        array(
                	            'class' => 'form-control', 
                	            'placeholder' => 'ادخل النسبة'
                	            )
                	        )
                            }} 
                            <span class="input-group-addon">
                                %
                            </span>    
                        </div><!-- /input-group -->
                        @if ($errors->has('referral_percentage'))
                        <label for="inputError" class="control-label">
                            {{ $errors->first('referral_percentage') }}
                        </label>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- /.panel-body -->
    </div>
    <!-- /.panel -->
</div>
<div class="col-lg-6">
    <div class="panel panel-default">
        <div class="panel-heading">
            بيانات الشخص المفوض
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
            <!-- authorized pepole -->
            <div class="authorized_people">
                @if($authorized)
                @for ($i = 0; $i < count($authorized); $i++)
                <div class="authorized_person">
                    <div class="btn btn-danger btn-sm pull-left delete"><i class="fa fa-times"></i> حذف</div>

                    @if(isset($model))
                    {{ Form::hidden('authorized['.$i.'][id]', $authorized[$i]['id']) }}
                    @endif

                    <div class="form-group{{ $errors->has('authorized.'.$i.'.name') ? ' has-error' : '' }}">
                        {{ Form::label(null, 'اسم الشخص المفوض') }} 
                        {{ Form::text('authorized['.$i.'][name]', $authorized[$i]['name'], 
                            array(
                                'class' => 'form-control', 
                                'placeholder' => 'ادخل الشخص المفوض')
                            )
                        }}
                        @if ($errors->has('authorized.'.$i.'.name'))
                        <label for="inputError" class="control-label">
                            {{ $errors->first('authorized.'.$i.'.name') }}
                        </label>
                        @endif
                    </div>

                    <div class="form-group{{ $errors->has('authorized.'.$i.'.jobtitle') ? ' has-error' : '' }}">
                        {{ Form::label(null, 'المسمى الوظيفي') }} 
                        {{ Form::text('authorized['.$i.'][jobtitle]', $authorized[$i]['jobtitle'], 
                            array(
                                'class' => 'form-control', 
                                'placeholder' => 'ادخل المسمى الوظيفي')
                            )
                        }}
                        @if ($errors->has('authorized.'.$i.'.jobtitle'))
                        <label for="inputError" class="control-label">
                            {{ $errors->first('authorized.'.$i.'.jobtitle') }}
                        </label>
                        @endif
                    </div>

                    <div class="form-group{{ $errors->has('authorized.'.$i.'.telephone') ? ' has-error' : '' }}">
                        {{ Form::label(null, 'التليفون') }} 
                        {{ Form::text('authorized['.$i.'][telephone]', $authorized[$i]['telephone'], 
                            array(
                                'class' => 'form-control', 
                                'placeholder' => 'ادخل التليفون')
                            )
                        }}
                        @if ($errors->has('authorized.'.$i.'.telephone'))
                        <label for="inputError" class="control-label">
                            {{ $errors->first('authorized.'.$i.'.telephone') }}
                        </label>
                        @endif
                    </div>

                    <div class="form-group{{ $errors->has('authorized.'.$i.'.email') ? ' has-error' : '' }}">
                        {{ Form::label(null, 'البريد اﻻلكتروني') }} 
                        {{ Form::email('authorized['.$i.'][email]', $authorized[$i]['email'], 
                            array(
                                'class' => 'form-control', 
                                'placeholder' => 'ادخل البريد اﻻلكتروني')
                            )
                        }}
                        @if ($errors->has('authorized.'.$i.'.email'))
                        <label for="inputError" class="control-label">
                            {{ $errors->first('authorized.'.$i.'.email') }}
                        </label>
                        @endif
                    </div>
                </div>
                @endfor
                @endif
            </div>
            <!-- authorized pepole -->
            <button id="add_authorized_people" class="btn btn-success"><i class="fa fa-plus-square"></i> أضف</button>
        </div>
        <!-- /.panel-body -->
    </div>
    <!-- /.panel -->
</div>
<row class="col-lg-12" style="padding-bottom: 20px;">
    <div class="col-lg-6">
        <button class="btn btn-lg btn-block btn-success" type="submit">
            @if(isset($model))
            تعديل بيانات عميل
            @else
            أضف عميل جديد
            @endif
        </button>
    </div>
</row>