<div class="box-body">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="col-lg-8 col-md-8 col-sm-8">
                <div class="form-group">
                    {{ Form::label('name', trans('labels.frontend.workbooks.field.name'), ['class' => 'col-lg-2 col-md-2 col-sm-2 control-label required']) }}
					{{ Form::hidden('type', $type) }}
				   <div class="col-lg-10 col-md-10 col-sm-10">
                        {{ Form::text('name', null, ['class' => 'form-control box-size', 'placeholder' => trans('labels.frontend.workbooks.field.name'), 'required' => 'required']) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr/>
    <div class="row ">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="col-lg-4 col-md-4 col-sm-4 ">
                <div class="row">
                    <div class="col-lg-12">
						@php $i=0; @endphp
                        @foreach ($components as $key=>$component)
							@php $i++; @endphp
                            <div class="row modules-list-item">
                                <div class="col-lg-10">
                                    <span >{{ $component['name'] }}&nbsp;&nbsp;</span>
                                </div>
                                <div class="col-lg-2">
                                    <a href="javascript:void(0);"><i class="fa fa-plus add-module-to-workbook" data-id="{{ $i }}" data-key="{{ $key }}" data-name="{{ $component['name']}}" data-max="{{ $component['max']}}" data-after="{{ $component['after']}}" data-level="{{ $component['level']}}" data-fields="{{ json_encode($component['fields']) }}" ></i></a>
                                </div>
                            </div>
                        @endforeach
						
						@php $i=0; @endphp
                        @foreach ($global_component as $key=>$component)
							@php $i++; @endphp
                            <div class="row modules-list-item">
                                <div class="col-lg-10">
                                    <span >{{ $component['name'] }}&nbsp;&nbsp;</span>
                                </div>
                                <div class="col-lg-2">
                                    <a href="javascript:void(0);"><i class="fa fa-plus add-module-to-wb-global" data-id="{{ $i }}" data-key="{{ $key }}" data-name="{{ $component['name']}}" data-after="{{ $component['after']}}" data-fields="{{ json_encode($component['fields']) }}" ></i></a>
                                </div>
                            </div>
                        @endforeach
                        <!--<br/>
                        <button type="button" class="btn btn-info show-modal" data-form="_add_custom_url_form" data-header="Add Custom"><i class="fa fa-plus" ></i>&nbsp;&nbsp;Add Custom</button>-->
						{{ Form::hidden('items_global', empty($workbook->items_global) ? '{}' : $workbook->items_global, ['class' => 'wb-global-items-field']) }}
						<div class="well">
							<div id="wb-global-items" class="dd">
							</div>
						</div>						
					</div>
                </div>
            </div>
            <div class="col-lg-8 col-md-8 col-sm-8 ">
                 {{ Form::hidden('items', empty($workbook->items) ? '{}' : $workbook->items, ['class' => 'workbook-items-field']) }}
                <div class="well">
                    <div id="workbook-items" class="dd">
                    </div>
                </div>
				<div><p id="validate_response"></p></div>
            </div>
        </div>
    </div>
</div>
@section("after-styles")
     {!! Html::style('css/nestable2/jquery.nestable.workbook.css') !!}
@endsection
@section("after-scripts")
    {{ Html::script('js/frontend/workbook/jquery.nestable.js') }}
	{{ Html::script('js/frontend/workbook/aws/nestable-custom-global.js') }}
	{{ Html::script('js/frontend/workbook/aws/nestable-custom.js') }}
	<script type="text/javascript">
        AWS.Workbook.selectors.formUrl = "{{route('frontend.workbook.getform')}}";
        AWS.Workbook.init();
		Global_WB.Workbook.selectors.formUrl = "{{route('frontend.workbook.getform')}}";
		Global_WB.Workbook.init();
</script>
@endsection
