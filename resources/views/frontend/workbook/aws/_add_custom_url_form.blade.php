{{ Form::open(['class' => 'form-horizontal hidden', 'role' => 'form', 'method' => 'post', 'id' => empty($form)?'workbook-add-custom-url':$form ]) }}
{{ Form::hidden('key', null, ['class' => 'mi-key']) }}
{{ Form::hidden('fields_name', $fieldsName, ['class' => 'mi-fields_name']) }}
{{ Form::hidden('name', null, ['class' => 'mi-name']) }}
{{ Form::hidden('level', null, ['class' => 'mi-level']) }}
{{ Form::hidden('kms_key_data', $kms_key_data, ['id' => 'kms_key_data']) }}
    <!--<div class="form-group">
        {{ Form::label('name', trans('labels.frontend.workbooks.field.name'), ['class' => 'col-lg-3 col-md-3 col-sm-3 control-label required']) }}
        <div class="col-lg-9 col-md-9 col-sm-9">
          {{ Form::text('name', null, ['class' => 'form-control box-size mi-name', 'id' => '', 'placeholder' => trans('labels.frontend.workbooks.field.name'), 'required' => 'required']) }}
        </div>
    </div>-->
	@foreach($fields as $field)
		<div class="form-group">
			{{ Form::label($field['name'], $field['title'], ['class' => 'col-lg-3 col-md-3 col-sm-3 control-label '.(isset($field['required'])?$field['required']:'')]) }}
			<div class="col-lg-9 col-md-9 col-sm-9">
				  
	@switch($field['type'])
		@case('text')
			{{ Form::text($field['name'], null, $field['attr']) }}
			@break
		@case('number')
			{{ Form::number($field['name'], $field['value'], $field['attr']) }}
			@break
		@case('select')
			{{ Form::select($field['name'], $field['options'],'',  $field['attr']) }}
			@break
		@case('checkbox')
			{{ Form::checkbox($field['name'], $field['value'], $field['default'], $field['attr']) }}
			@break
		@case('textarea')
			{{ Form::textarea($field['name'], $field['value'], $field['attr']) }}
			@break
		@case('hidden')
			{{ Form::hidden($field['name'], null, $field['attr']) }}
			@break
		@case('textselect')
			
			<div class="data-list-input">
				{{ Form::select('', $field['options'],'',  $field['select_attr']) }}
				{{ Form::text($field['name'], null, $field['text_attr']) }}
			</div>
			
			
			@break			
			
		@default
			<span>Something went wrong, please try again</span>
	@endswitch
			</div>
		</div>
	@endforeach
    <!--<div class="form-group">
        {{ Form::label('url', trans('labels.frontend.workbooks.field.url'), ['class' => 'col-lg-3 col-md-3 col-sm-3 control-label']) }}
        <div class="col-lg-9 col-md-9 col-sm-9">
          {{ Form::text('url', null, ['class' => 'form-control box-size mi-url', 'placeholder' => trans('labels.frontend.workbooks.field.url')]) }}
        </div>
    </div>
    <div class="form-group">
        {{ Form::label('url', trans('labels.frontend.workbooks.field.url_type'), ['class' => 'col-lg-3 col-md-3 col-sm-3 control-label']) }}
        <div class="col-lg-9 col-md-9 col-sm-9 ">
          <div class="radio">
            <label class="radio-inline">{{ Form::radio('url_type', 'route', null, ['class' => 'mi-url_type_route']) }} {{ trans('labels.frontend.workbooks.field.url_types.route') }}</label>
            <label class="radio-inline">{{ Form::radio('url_type', 'static', true, ['class' => 'mi-url_type_static']) }} {{ trans('labels.frontend.workbooks.field.url_types.static') }}</label>
          </div>
          <div class="checkbox">
            {{ Form::hidden('open_in_new_tab', 0) }}
            <label>
              {{ Form::checkbox('open_in_new_tab', 1, false, ['class' => 'mi-open_in_new_tab']) }} {{ trans('labels.frontend.workbooks.field.open_in_new_tab') }}
            </label>
          </div>
        </div>
    </div>-->

    {{ Form::hidden('id', null, ['class' => 'mi-id']) }}
    <div class="box-body text-center">
            <div class="form-group">
                <div class="edit-form-btn">
                  {{ Form::reset(trans('buttons.general.cancel'), ['class' => 'btn btn-default btn-md', 'data-dismiss' => 'modal']) }}
                  {{ Form::submit(trans('buttons.general.save'), ['class' => 'btn btn-primary btn-md']) }}
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
{{ Form::close() }}

<table id="tagsBox" style="display:none;">
	<tr>
	  <td>
		<input type="button" onclick="addTags()" value="Add Tag" />
	  </td>
	</tr>
</table>	
<table id="ingressBox" style="display:none;">
	<tr>
	  <td>
		<input type="button" onclick="addIngress()" value="Add Ingress" />
	  </td>
	</tr>
</table>
<table id="egressBox" style="display:none;">
	<tr>
	  <td>
		<input type="button" onclick="addEgress()" value="Add Egress" />
	  </td>
	</tr>
</table>
<table id="storageBox" style="display:none;">
	<tr>
	  <td>
		<input type="button" onclick="addStorage()" value="Add Storage" />
	  </td>
	</tr>
	<tr id="egr_1">
	  <td>
		<input type="text" name="v_type_1" readonly value="Root" class="v_type">
		<input type="text" name="device_1" readonly value="/dev/xvda" class="device">
		<input type="text" name="snapshot_1" placeholder="snapshot" class="snapshot" onchange="saveStorage()" value="" autocomplete="off">&nbsp;&nbsp;
		<input type="number" name="size_1" placeholder="size(GiB)" class="size" onchange="saveStorage()" min="1" value="8" required="">&nbsp;&nbsp;
		<select name="volume_type_1" class="volume_type" style="width:50%" onchange="saveStorage()"><option value="gp2">General Purpose SSD (gp2)</option><option value="io1">Provisioned IOPS SSD (io1)</option><option value="standard">Magnetic (standard)</option></select>&nbsp;&nbsp;
		<input type="checkbox" name="del_on_term_1" checked class="del_on_term" value="1" onchange="saveStorage()">Delete on Termination&nbsp;&nbsp;
		<select name="encryption_1" class="encryption" style="width:50%" onchange="saveStorage()"><option value="">Not Encryption</option></select>
		</td>
	  <td></td>
	</tr>
</table>
{{ Html::script('js/frontend/workbook/aws/form-attribute.js') }}

<style>

/* specific customizations for the data-list */
div.data-list-input
{
	position: relative;
	padding: 5px 0 5px 0;
	width:100%;
}
select.data-list-input
{
	position: absolute;
	top: 0px;
	left: 0px;
}
input.data-list-input
{
	position: absolute;
	top: 0px;
	left: 0px;
	width:95%;
}
</style>