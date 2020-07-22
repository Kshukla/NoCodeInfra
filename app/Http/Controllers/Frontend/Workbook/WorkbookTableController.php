<?php

namespace App\Http\Controllers\Frontend\Workbook;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\Workbook\ManageWorkbookRequest;
use App\Repositories\Frontend\Workbook\WorkbookRepository;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class WorkbookTableController.
 */
class WorkbookTableController extends Controller
{
    protected $workbooks;

    /**
     * @param \App\Repositories\Backend\Workbook\WorkbookRepository $workbooks
     */
    public function __construct(WorkbookRepository $workbooks)
    {
        $this->workbooks = $workbooks;
    }

    /**
     * @param \App\Http\Requests\Backend\Workbook\ManageWorkbookRequest $request
     *
     * @return mixed
     */
    public function __invoke(ManageWorkbookRequest $request)
    {
        return Datatables::of($this->workbooks->getForDataTable())
            ->escapeColumns(['name'])
		  
		   ->editColumn('name', function($workbooks) {
                    return '<a href="'.route('frontend.workbook.edit', $workbooks).'">'.$workbooks->name.'</a>';
                })
			
            ->addColumn('type', function ($workbooks) {
				if($workbooks->type=='aws')
					return '<img height="20px" src="'.asset('public/img/frontend/aws_logo.png').'">';//ucwords($workbooks->type);
				elseif($workbooks->type=='azure')
					return '<img height="20px" src="'.asset('public/img/frontend/azure_logo.png').'">';//ucwords($workbooks->type);
				elseif($workbooks->type=='gcp')
					return '<img height="20px" src="'.asset('public/img/frontend/gcp_logo.png').'">';//ucwords($workbooks->type);
					
            })
            ->addColumn('created_at', function ($workbooks) {
                return Carbon::parse($workbooks->created_at)->toDateTimeString();
            })
            /*->addColumn('updated_at', function ($workbooks) {
                return Carbon::parse($workbooks->updated_at)->toDateTimeString();
            })*/
			->addColumn('download', function ($workbooks) {
                return $workbooks->getDownloadButtonAttribute('view-frontend').$workbooks->getYamlDownloadButtonAttribute('view-frontend');
            })
            ->addColumn('actions', function ($workbooks) {
                return $workbooks->action_buttons;
            })
			->addColumn('delete', function ($workbooks) {
                return $workbooks->getDeleteButtonAttribute('view-frontend', 'frontend.workbook.destroy');
            })
			->rawColumns(['name'])
            ->make(true);
    }
}
