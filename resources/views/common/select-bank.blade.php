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
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                اختر البنك
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                {{ Form::open($formConfig) }}
                <!-- Equivalent to... -->
                <div class="col-lg-6 ">
                    <div class="form-group{{ $errors->has('id') ? ' has-error' : '' }}">
                        {{ Form::label('id', 'اسم البنك') }}

                        {{ Form::select('bankId', $banks, $bankId,
                                    array(
                                    'id' => 'bankId',
                                    'name' => 'bankId',
                                    'class' => 'form-control',
                                    'placeholder' => '')) }}

                        @if ($errors->has('bankId'))
                            <label for="inputError" class="control-label">
                                {{ $errors->first('bankId') }}
                            </label>
                        @endif
                    </div>
                </div>
                <div class="col-lg-2 ">
                    <label>&MediumSpace;</label>
                    <button class="btn btn-success form-control" type="submit">عرض</button>
                </div>

                {{ Form::close() }}
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
</div>