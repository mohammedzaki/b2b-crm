<div class="col-lg-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                بيانات البنك
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                    {{ Form::label('name', 'اسم البنك') }}
                    {{ Form::text('name', null,
                        array(
                            'class' => 'form-control',
                            'placeholder' => 'ادخل اسم البنك')
                        )
                    }}
                    @if ($errors->has('name'))
                        <label for="inputError" class="control-label">
                            {{ $errors->first('name') }}
                        </label>
                    @endif
                </div>
                <div class="form-group{{ $errors->has('statement_number') ? ' has-error' : '' }}">
                    {{ Form::label('statement_number', 'رقم الحساب') }}
                    {{ Form::text('statement_number', null,
                        array(
                            'class' => 'form-control',
                            'placeholder' => 'ادخل رقم الحساب')
                        )
                    }}
                    @if ($errors->has('statement_number'))
                        <label for="inputError" class="control-label">
                            {{ $errors->first('statement_number') }}
                        </label>
                    @endif
                </div>
                <div class="form-group{{ $errors->has('branch_address') ? ' has-error' : '' }}">
                    {{ Form::label('branch_address', 'عنوان الفرع') }}
                    {{ Form::text('branch_address', null,
                        array(
                            'class' => 'form-control',
                            'placeholder' => 'ادخل عنوان الفرع')
                        )
                    }}
                    @if ($errors->has('branch_address'))
                        <label for="inputError" class="control-label">
                            {{ $errors->first('branch_address') }}
                        </label>
                    @endif
                </div>

            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->

        <div style="margin-bottom: 20px;">
            <button class="btn btn-lg btn-block btn-success" type="submit">
                @if(isset($model))
                    تعديل بيانات بنك
                @else
                    أضف بنك جديد
                @endif
            </button>
        </div>
    </div>

@section('scripts')
    <script>
        @if(isset($model))
        var empId = '{{ $bankProfile->id }}';
        $('#btnUpdateJobProfile').on({
            'click': function () {
                var link = "{{ route('bank-profile.cheque-book.create', ['bankId' => $bankProfile->id]) }}";
                window.location.href = link;
            }
        });
        @endif
        $(function () {
            $("#datepicker").datepicker({
                changeMonth: true,
                changeYear: true,
                yearRange: "-60:-15",
                dateFormat: 'yy-mm-dd'
            });
            $("#datepicker2").datepicker({
                changeMonth: true,
                changeYear: true,
                yearRange: "-20:+0",
                dateFormat: 'yy-mm-dd'
            });

            $('#can_not_use_program').click(function () {
                $(".hidden_input02").slideToggle(this.checked);
            });
            if ($('#can_not_use_program').is(':checked')) {
                $(".hidden_input02").slideToggle(this.checked);
                $("#username").val('');
                $("#password").val('');
            }
        });
    </script>
@endsection