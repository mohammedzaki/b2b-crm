<div class="col-lg-6">
    <div class="panel panel-default">
        <div class="panel-heading">
            بيانات المورد
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
        	<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
        	    {{ Form::label('name', 'اسم المورد') }} 
        	    {{ Form::text('name', null, 
        	        array(
        	            'class' => 'form-control', 
        	            'placeholder' => 'ادخل اسم المورد')
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

        	<div class="form-group{{ $errors->has('debit_limit') ? ' has-error' : '' }}">
        	    {{ Form::label('debit_limit', 'حد المديونية') }} 
        	    {{ Form::text('debit_limit', null, 
        	        array(
        	            'class' => 'form-control', 
        	            'placeholder' => 'ادخل حد المديونية')
        	        )
        	    }}
        	    @if ($errors->has('debit_limit'))
        	    <label for="inputError" class="control-label">
        	        {{ $errors->first('debit_limit') }}
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
                تعديل بيانات مورد
            @else
                أضف مورد جديد
            @endif
        </button>
    </div>
</row>