<div class="col-lg-6">
    <div class="panel panel-default">
        <div class="panel-heading">
            بيانات القرض
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
        	<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
        	    {{ Form::label('name', 'اسم القرض') }}
        	    {{ Form::text('name', null, 
        	        array(
        	            'class' => 'form-control', 
        	            'placeholder' => 'ادخل اسم القرض')
        	        )
        	    }}
        	    @if ($errors->has('name'))
        	    <label for="inputError" class="control-label">
        	        {{ $errors->first('name') }}
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
                تعديل بيانات قرض
            @else
                أضف قرض جديد
            @endif
        </button>
    </div>
</row>