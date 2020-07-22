@extends('frontend.layouts.app')
@section ('title', trans('navs.frontend.workbook.edit') . ' | ' . app_name())
@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-edit"></i>{{ trans('navs.frontend.workbook.edit') }}
                </div>
                <div class="panel-body">
                    <!--{{ trans('strings.frontend.welcome_to', ['place' => app_name()]) }}-->
					{{ Form::model($workbook, ['route' => ['frontend.workbook.update', $workbook], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'PATCH', 'id' => 'edit-workbook', 'files' => false]) }}
					    <div class="box box-info">
							{{-- Including Form blade file --}}
							<div class="box-body">
								<div class="form-group">
									@include("frontend.workbook.aws.form")
									<div class="edit-form-btn text-center">
										{{ link_to_route('frontend.workbook.index', trans('buttons.general.cancel'), [], ['class' => 'btn btn-danger btn-md']) }}
										{{ Form::submit(trans('buttons.general.crud.update'), ['class' => 'btn btn-primary btn-md']) }}
										<div class="clearfix"></div>
									</div>
								</div>
							</div><!--box-->
						</div>
						{{ Form::close() }}
						@include("frontend.workbook.partials.modal")	
                </div>
            </div><!-- panel -->
        </div><!-- col-md-10 -->
    </div><!--row-->
@endsection