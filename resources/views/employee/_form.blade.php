<div class="col-lg-7">
    <div class="panel panel-default">
        <div class="panel-heading">
            بيانات الموظف
            <div class="pull-left">
                <label class="checkbox-inline text-danger">
                    {{ Form::checkbox('is_active') }}نشط
                </label>
            </div>
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">

            <div class="col-lg-12">
                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                    {{ Form::label('name', 'اسم الموظف') }} 
                    {{ Form::text('name', null, 
                        array(
                            'class' => 'form-control', 
                            'placeholder' => 'ادخل اسم الموظف')
                        )
                    }}
                    @if ($errors->has('name'))
                    <label for="inputError" class="control-label">
                        {{ $errors->first('name') }}
                    </label>
                    @endif
                </div>
            </div>
           
            <div class="col-lg-6">
                <div class="form-group{{ $errors->has('ssn') ? ' has-error' : '' }}">
                    {{ Form::label('ssn', 'الرقم القومى') }} 
                    {{ Form::text('ssn', null, 
                        array(
                            'class' => 'form-control', 
                            'placeholder' => 'ادخل الرقم القومى')
                        )
                    }}
                    @if ($errors->has('ssn'))
                    <label for="inputError" class="control-label">
                        {{ $errors->first('ssn') }}
                    </label>
                    @endif
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group{{ $errors->has('gender') ? ' has-error' : '' }}">
                    {{ Form::label('gender', 'الجنس') }}
                    {{ 
                        Form::select('gender', 
                            array(
                                'm' => 'ذكر',
                                'f' => 'انثى'
                            ), 
                            null,
                            array(
                                'class' => 'form-control', 
                            )
                        )
                    }}
                    @if ($errors->has('gender'))
                    <label for="inputError" class="control-label">
                        {{ $errors->first('gender') }}
                    </label>
                    @endif
                </div>
            </div>
            
            <div class="clearboth"></div>

            <div class="col-lg-6">
                <div class="form-group{{ $errors->has('martial_status') ? ' has-error' : '' }}">
                    {{ Form::label('martial_status', 'الحالة الاجتماعية') }}
                    {{ 
                        Form::select('martial_status', 
                            array(
                                'single' => 'اعزب',
                                'married' => 'متزوج',
                                'widowed' => 'ارمل',
                                'divorced' => 'مطلق'
                            ), 
                            null,
                            array(
                                'class' => 'form-control', 
                            )
                        )
                    }}
                    @if ($errors->has('martial_status'))
                    <label for="inputError" class="control-label">
                        {{ $errors->first('martial_status') }}
                    </label>
                    @endif
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group{{ $errors->has('department') ? ' has-error' : '' }}">
                    {{ Form::label('department', 'القسم') }}
                    {{ Form::text('department', null, 
                        array(
                            'class' => 'form-control', 
                            'placeholder' => 'ادخل القسم')
                        )
                    }}
                    @if ($errors->has('department'))
                    <label for="inputError" class="control-label">
                        {{ $errors->first('department') }}
                    </label>
                    @endif
                </div>
            </div>
            
            <div class="clearboth"></div>

            <div class="col-lg-6">
                <div class="form-group{{ $errors->has('birth_date') ? ' has-error' : '' }}">
                    {{ Form::label('birth_date', 'تاريخ الميلاد') }}
                    {{ Form::text('birth_date', null, 
                        array(
                            'id' => 'datepicker',
                            'class' => 'form-control', 
                            'placeholder' => 'ادخل تاريخ الميلاد')
                        )
                    }}
                    @if ($errors->has('birth_date'))
                    <label for="inputError" class="control-label">
                        {{ $errors->first('birth_date') }}
                    </label>
                    @endif
                    <script>
                    $(function() {
                      $( "#datepicker" ).datepicker({
                        changeMonth: true,
                        changeYear: true,
                        yearRange: "-60:-15",
                        dateFormat: 'yy-mm-dd'
                      });
                    });
                    </script>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="form-group{{ $errors->has('hiring_date') ? ' has-error' : '' }}">
                    {{ Form::label('hiring_date', 'تاريخ التعيين') }}
                    {{ Form::text('hiring_date', null, 
                        array(
                            'id' => 'datepicker2',
                            'class' => 'form-control', 
                            'placeholder' => 'ادخل تاريخ التعيين')
                        )
                    }}
                    @if ($errors->has('hiring_date'))
                    <label for="inputError" class="control-label">
                        {{ $errors->first('hiring_date') }}
                    </label>
                    @endif
                    <script>
                    $(function() {
                      $( "#datepicker2" ).datepicker({
                        changeMonth: true,
                        changeYear: true,
                        yearRange: "-20:+0",
                        dateFormat: 'yy-mm-dd'
                      });
                    });
                    </script>
                </div>
            </div>

            <div class="clearboth"></div>

            <div class="col-lg-6 ">
                <div class="form-group{{ $errors->has('job_title') ? ' has-error' : '' }}">
                    {{ Form::label('job_title', 'الوظيفة') }}
                    {{ Form::text('job_title', null, 
                        array(
                            'class' => 'form-control', 
                            'placeholder' => 'ادخل الوظيفة')
                        )
                    }}
                    @if ($errors->has('job_title'))
                    <label for="inputError" class="control-label">
                        {{ $errors->first('job_title') }}
                    </label>
                    @endif
                </div>
            </div>
            
            <div class="col-lg-6">
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
            </div>

            <div class="clearboth"></div>

            <div class="col-lg-6 ">
                <div class="form-group{{ $errors->has('daily_salary') ? ' has-error' : '' }}">
                    {{ Form::label('daily_salary', 'الراتب اليومى') }}
                    {{ Form::text('daily_salary', null, 
                        array(
                            'class' => 'form-control', 
                            'placeholder' => 'ادخل الراتب اليومى')
                        )
                    }}
                    @if ($errors->has('daily_salary'))
                    <label for="inputError" class="control-label">
                        {{ $errors->first('daily_salary') }}
                    </label>
                    @endif
                </div>
            </div>

            <div class="col-lg-6">
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
            </div>
            
            <div class="clearboth"></div>

            <div class="col-lg-6 ">
                <div class="form-group{{ $errors->has('working_hours') ? ' has-error' : '' }}">
                    {{ Form::label('working_hours', 'ساعات العمل') }}
                    {{ Form::text('working_hours', null, 
                        array(
                            'class' => 'form-control', 
                            'placeholder' => 'ادخل ساعات العمل')
                        )
                    }}
                    @if ($errors->has('working_hours'))
                    <label for="inputError" class="control-label">
                        {{ $errors->first('working_hours') }}
                    </label>
                    @endif
                </div>
            </div>

            <div class="col-lg-6"> 
                <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                    {{ Form::label('username', 'اسم المستخدم') }}
                    {{ Form::text('username', null, 
                        array(
                            'class' => 'form-control', 
                            'placeholder' => 'ادخل اسم المستخدم')
                        )
                    }}
                    @if ($errors->has('username'))
                    <label for="inputError" class="control-label">
                        {{ $errors->first('username') }}
                    </label>
                    @endif
                </div>
            </div>
            
            <div class="clearboth"></div>
            
            <div class="col-lg-6"> 
                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    {{ Form::label('password', 'كلمة المرور') }}
                    {{ Form::password('password', 
                        array(
                            'class' => 'form-control', 
                            'placeholder' => 'ادخل كلمة المرور')
                        )
                    }}
                    @if ($errors->has('password'))
                    <label for="inputError" class="control-label">
                        {{ $errors->first('password') }}
                    </label>
                    @endif
                </div>
            </div>

            <div class="col-lg-12 no-padding">
                <div class="legend">
                    {{ Form::checkbox('borrow_system', '1', null, 
                        array(
                            'id' => 'borrow_system',
                            'class' => 'checkbox_show_input'
                        )
                    ) }} 
                    {{ Form::label('borrow_system', 'نظام السلف') }}
                </div>
                <div class="hidden_input">
                    <div class="col-lg-6">
                        <div class="form-group">
                                {{ Form::radio('value') }}
                                قيمة
                                {{ Form::text('number', null, array(
                                    'class' => 'form-control',
                                    'style' => 'width:160px; display:inline;',
                                    'placeholder' => 'ادخل القيمة'
                                )) }} $
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            {{ Form::radio('value') }}
                                نسبة
                                {{ Form::text('percentage', null, array(
                                    'class' => 'form-control',
                                    'style' => 'width:160px; display:inline;',
                                    'placeholder' => 'ادخل النسبة'
                                )) }} %
                        </div>    
                    </div>
                </div>
            </div>

        </div>
        <!-- /.panel-body -->
    </div>
    <!-- /.panel -->
</div>
<div class="col-lg-5">
    <div class="panel panel-default">
        <div class="panel-heading">
           صلاحة شاشة النظام
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th style="width: 35px;">م</th>
                            <th style="width: 50px;">اختيار</th>
                            <th>شاشات النظام</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($permissions as $permission)
                            <tr role="row">
                                <td class="text-center">{{ $permission->id }}</td>
                                <td class="text-center">
                                @if(isset($selectedPermissions))
                                    {{ Form::checkbox('permissions[]', $permission->id, in_array($permission->id, $selectedPermissions)) }}
                                @else
                                    {{ Form::checkbox('permissions[]', $permission->id) }}
                                @endif
                                </td>
                                <td>{{ $permission->display_name }}</td>
                            </tr>
                        @empty
                            <tr>ﻻ يوجد صﻻحيات.</tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="form-group text-danger">
                <label class="checkbox-inline">
                    {{ Form::checkbox('can_not_use_program') }}عدم استخدام البرنامج
                </label>
            </div>
        </div>
        <!-- /.panel-body -->
    </div>
    <!-- /.panel -->
</div>
<row class="col-lg-12" style="padding-bottom: 20px;">
    <div class="col-lg-7">
        <button class="btn btn-lg btn-block btn-success" type="submit">
            @if(isset($model))
                تعديل بيانات موظف
            @else
                أضف موظف جديد
            @endif
        </button>
    </div>
</row>