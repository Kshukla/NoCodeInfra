<?php

namespace App\Http\Controllers\Frontend\Workbook;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\Workbook\CreateWorkbookRequest;
use App\Http\Requests\Frontend\Workbook\StoreWorkbookRequest;
use App\Http\Requests\Frontend\Workbook\EditWorkbookRequest;
use App\Http\Requests\Frontend\Workbook\UpdateWorkbookRequest;
use App\Http\Requests\Frontend\Workbook\DeleteWorkbookRequest;
use App\Http\Responses\RedirectResponse;
use App\Http\Responses\ViewResponse;
use App\Helpers\Frontend\Common;

use App\Models\Workbook\Workbook;
use App\Repositories\Frontend\Workbook\WorkbookRepository;
use GuzzleHttp\Client;
//use App\Models\Access\User\User;

/**
 * Class WorkbookController.
 */
class WorkbookController extends Controller
{
	protected $base_url_api;
    protected $workbook;
	protected $types;	
     public function __construct(WorkbookRepository $workbook)
    {
        $this->workbook = $workbook;
		 $this->base_url_api = 'http://workbook.local:8080/api/v1/';


        $this->types = [
            'aws'  => 'AWS',
            'azure' => 'Azure',
			'gcp' => 'GCP',
        ];
    }

   /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
		return new ViewResponse('frontend.workbook.index');
    }

    public function create($type=null,CreateWorkbookRequest $request)
    {
		if(!$type || !array_key_exists($type,$this->types)){
			return new RedirectResponse(route('frontend.workbook.selecttype'), ['flash_success' => '']);
		}
		if($type=='aws'){
		
			//$user = \Auth::user();
			//$data = User::with('roles.permissions')->with('permissions')->find($user->id);
			$components = config('wb_aws.component');
			$global_component = config('wb_aws.global_component');
			return new ViewResponse('frontend.workbook.aws.create',['components'=>$components, 'global_component'=>$global_component, 'type'=>$type]);
		}else
			return new RedirectResponse(route('frontend.workbook.selecttype'), ['flash_success' => 'Selected type feature is not available']);
    }
    public function selecttype()
    {
		return new ViewResponse('frontend.workbook.selecttype',['types'=>$this->types]);
    }
    public function store(StoreWorkbookRequest $request)
    {
        $this->workbook->create($request->except('_token'));
		return new RedirectResponse(route('frontend.workbook.index'), ['flash_success' => trans('strings.frontend.workbook.workbook_created')]);
    }

    public function edit(Workbook $workbook, EditWorkbookRequest $request)
    {
		$components = config('wb_aws.component');
		$global_component = config('wb_aws.global_component');
		$type = $workbook->type;
	   return view('frontend.workbook.aws.edit',compact('workbook', 'components','global_component','type'));
	   //return view('frontend.workbook.edit')->with('workbook', $this->workbook)->with('components',$components);
		// return new ViewResponse('frontend.workbook.edit',['workbook'=> $this->workbook, 'components'=>$components]);
    }

    public function update(Workbook $workbook, UpdateWorkbookRequest $request)
    {
        $this->workbook->update($workbook, $request->all());
		return new RedirectResponse(route('frontend.workbook.index'), ['flash_success' => trans('strings.frontend.workbook.workbook_updated')]);
    }

    public function destroy(Workbook $workbook, DeleteWorkbookRequest $request)
    {
        $this->workbook->delete($workbook);
		return new RedirectResponse(route('frontend.workbook.index'), ['flash_success' => trans('strings.frontend.workbook.workbook_deleted')]);
    }	
	public function download(Workbook $workbook)
    {
		$client = new Client(['base_uri' => $this->base_url_api]);
        $res = $client->request('GET', 'gettf/'.$workbook->id, [
								'headers' => [
									'Content-Type' => 'text/plain'
								]]);
		$contents= $res->getBody();
		/*$items_array = json_decode($workbook->items,true);
		$commonObj = new Common();
		$data_array = $commonObj->array_flatten($items_array, array());
		//$contents =  new ViewResponse('frontend.workbook.download',['data_array'=>$data_array]);
		$contents =  view('frontend.workbook.download',compact('data_array', $data_array));*/

		$filename = $workbook->name.'.txt';
		return response()->streamDownload(function () use ($contents) {
			echo $contents;
		}, $filename);
		exit;
	}
	public function yamldownload(Workbook $workbook)
    {
		$client = new Client(['base_uri' => $this->base_url_api]);
        $res = $client->request('GET', 'gettf/'.$workbook->id, [
								'headers' => [
									'Content-Type' => 'text/plain'
								]]);
		$contents= $res->getBody();
		/*$items_array = json_decode($workbook->items,true);
		$commonObj = new Common();
		$data_array = $commonObj->array_flatten($items_array, array());
		//$contents =  new ViewResponse('frontend.workbook.download',['data_array'=>$data_array]);
		$contents =  view('frontend.workbook.download',compact('data_array', $data_array));*/

		$filename = $workbook->name.'.yaml';
		return response()->streamDownload(function () use ($contents) {
			echo $contents;
		}, $filename);
		exit;
	}	
}
