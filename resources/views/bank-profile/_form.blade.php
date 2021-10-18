<div class="col-lg-6">
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
</div>
<row class="col-lg-12" style="padding-bottom: 20px;">
    <div class="col-lg-6">
        <button class="btn btn-lg btn-block btn-success" type="submit">
            @if(isset($model))
                تعديل بيانات بنك
            @else
                أضف بنك جديد
            @endif
        </button>
    </div>
</row>