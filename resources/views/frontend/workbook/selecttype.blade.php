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
                    <i class="fa fa-edit"></i>{{ trans('navs.frontend.workbook.workbook_type') }}
                </div>
                <div class="panel-body">
					
					
					<div class="col-6 form-horizontal">
						<div class="form-group">
							{{ Form::label('categories', trans('labels.backend.menus.field.type'), ['class' => 'col-md-4 control-label required']) }}
							<div class="col-md-6">
								{{ Form::select('type', $types, null, ['class' => 'form-control tags box-size', 'required' => 'required', 'id'=>'type']) }}
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								{{ Form::button(trans('labels.frontend.workbooks.create'), ['class' => 'btn btn-primary', 'id' => 'type-submit-btn', "onclick"=>"window.location = '".route('frontend.workbook.create')."/'+document.getElementById('type').value"]) }}
							</div>
						</div>
					</div>
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
