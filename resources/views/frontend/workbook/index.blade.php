@extends('frontend.layouts.app')
@section ('title', trans('navs.frontend.workbook.my_workbooks') . ' | ' . app_name())

@section('after-styles')
    {{ Html::style("css/backend/plugin/datatables/dataTables.bootstrap.min.css") }}
@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-edit"></i>{{ trans('navs.frontend.workbook.my_workbooks') }}
					
					<div class="btn-group pull-right">
					<a class="" href="{{route('frontend.workbook.create')}}"><i class="fa fa-plus"></i> {{trans('navs.frontend.workbook.create')}}</a>
					</div>
					<div class="clearfix"></div>
                </div>

                <div class="panel-body">



					
					<div class="table-responsive data-table-wrapper">
						<table id="workbooks-table" class="table table-condensed table-hover table-bordered">
							<thead>
								<tr>
									<th>{{ trans('labels.frontend.workbooks.table.name') }}</th>
									<th>{{ trans('labels.frontend.workbooks.table.type') }}</th>
									<th width="25%">{{ trans('labels.frontend.workbooks.table.createdat') }}</th>
									<th width="5%">{{ trans('labels.general.download') }}</th>
									<!--<th width="5%">{{ trans('labels.general.actions') }}</th>-->
									<th width="5%">{{ trans('labels.general.delete') }}</th>
								</tr>
							</thead>
							<!--<thead class="transparent-bg">
								<tr>
									<th>
										{!! Form::text('first_name', null, ["class" => "search-input-text form-control", "data-column" => 0, "placeholder" => trans('labels.backend.workbooks.table.name')]) !!}
										<a class="reset-data" href="javascript:void(0)"><i class="fa fa-times"></i></a>
									</th>
									<th></th>
									<th></th>
								</tr>
							</thead>-->
						</table>
					</div><!--table-responsive-->
				
				
				
				</div>
            </div><!-- panel -->

        </div><!-- col-md-10 -->
    </div><!--row-->
@endsection

@section('after-scripts')
    {{-- For DataTables --}}
    @include('includes.datatables')

    <script>
        $(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var dataTable = $('#workbooks-table').dataTable({
				paging: false,
				searching: false,
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route("frontend.workbook.get") }}',
                    type: 'post'
                },
                columns: [
                    {data: 'name', name: '{{config('access.workbooks_table')}}.name'},
					{data: 'type', name: '{{config('access.workbooks_table')}}.type'},
                    {data: 'created_at', name: '{{config('access.workbooks_table')}}.created_at'},
					{data: 'download', name: 'download', searchable: false, sortable: false},
				/*	{data: 'actions', name: 'actions', searchable: false, sortable: false},*/
					{data: 'delete', name: 'delete', searchable: false, sortable: false}
                ],
				columnDefs: [
					{ className: 'text-center', targets: [2,3] },
					{orderable: false, targets: [2]}
				],
                order: [[2, "asc"]],
                searchDelay: 500,
                dom: 'lBfrtip',
                buttons: {
                    buttons: [
                        /*{ extend: 'copy', className: 'copyButton',  exportOptions: {columns: [ 0, 1, 2, 3, 4 ]  }},
                        { extend: 'csv', className: 'csvButton',  exportOptions: {columns: [ 0, 1, 2, 3, 4 ]  }},
                        { extend: 'excel', className: 'excelButton',  exportOptions: {columns: [ 0, 1, 2, 3, 4 ]  }},
                        { extend: 'pdf', className: 'pdfButton',  exportOptions: {columns: [ 0, 1, 2, 3, 4 ]  }},
                        { extend: 'print', className: 'printButton',  exportOptions: {columns: [ 0, 1, 2, 3, 4 ]  }}*/
                    ]
                },
                language: {
                    @lang('datatable.strings')
                }
            });

            //Backend.DataTableSearch.init(dataTable);
        });
    </script>
@endsection
