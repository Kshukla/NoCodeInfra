<?php

namespace App\Http\Controllers\Frontend\Workbook;

use App\Helpers\Frontend\Common;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class WorkbookFormController extends Controller
{
    /**
     * Get the form for modal popup.
     *
     * @param string $formName
     * @param \App\Http\Requests\Backend\Menu\CreateMenuRequest
     *
     * @return \Illuminate\Http\Response
     */
    public function create($formName,Request $request)
    {
		$form = $request->input('form');
		
		//$fields = $request->input('fields');
		//$fields  = explode(",",$fields);

		$commonObj = new Common();
		$items = $request->input('items');
		$items_array = json_decode($items,true);
		$data_array = $commonObj->array_flatten($items_array, array());

		//validate workbook code on submit
		if($formName=='validatewb'){
			//$vpc_data = $commonObj->arr_search($data_array, 'key', 'VPC');
			//$vpc_cidr = $vpc_data[0]['ipv4_cidr_block'];
			
			$subnet_data = $commonObj->arr_search($data_array, 'key', 'subnet');
			$cidrNetworks =[];
			foreach($subnet_data as $sub_item){
				$cidrNetworks[] = $sub_item['ipv4_cidr_block'];
			}
			if(count($subnet_data)>=2){
				
				$newnet = "";//"192.168.10.0/25";
				//check_overlap
				$overlap = $commonObj->networks_overlap($cidrNetworks, $newnet);
				if($overlap){
					//echo "CIDR block is overlaping";
				}
			}
			exit;
		}

		$items_global = $request->input('items_global');
		$items_global_array = array();
		if($items_global){
			$items_global_array = json_decode($items_global,true);
			$items_global_array = $commonObj->array_flatten($items_global_array, array());
		}
		
		$kms_key_data = json_encode($commonObj->get_global_array('aws_kms_key', $items_global_array));		
		//print_r($kms_key_array);exit;
		
		$key = $request->input('key');
		if(empty($form))
		$fields = config('wb_aws.component.'.$key.'.fields');
		else
		$fields = config('wb_aws.global_component.'.$key.'.fields');
		$fieldsName = array_column($fields, 'name');
		$fieldsName = implode(",",$fieldsName);
			
		
		for($i=0;$i<count($fields); $i++){
			// For filter availability zone based on region
			if(isset($fields[$i]['belong']) && $fields[$i]['belong']!=''){
				$found_key = array_search($fields[$i]['belong'], array_column($data_array, 'key'));
				$region = $data_array[$found_key][$fields[$i]['belong']];
				$filtered_region = [];
				if($region){
					foreach($fields[$i]['options'] as $key=>$val){
						if(strpos($key, $region) !== false) {
							$filtered_region[$key] =  $val;
						}
					}
				}
				$fields[$i]['options'] = $filtered_region;
			}
			//For Global Attribute
			if(isset($fields[$i]['global']) && $fields[$i]['global']!=''){
				//echo $fields[$i]['global'];
				/*$keys = array_column($items_global_array, 'key');
				$global_indexids = [];
				foreach($keys as $key=>$val){
					if ($fields[$i]['global'] == $val)
						$global_indexids[] = $key;
				}
				$filteredData = array_filter($items_global_array, function($k) use ($global_indexids)  {
					return in_array($k, $global_indexids);
				}, ARRAY_FILTER_USE_KEY);
				foreach($filteredData as $item){
					if(isset($item['rc_name']))
					$fields[$i]['options'][$item['id']] = $item['rc_name'];
				}*/
				$fields[$i]['options'] = $commonObj->get_global_array($fields[$i]['global'], $items_global_array);
				//print_r($fields[$i]['options']);exit;
			}
		}	
		//print_r($items_global_array);exit;
//echo "<pre>";print_r($fields);exit;
        if (in_array($formName, ['_add_custom_url_form'])) {
            return view('frontend.workbook.aws.'.$formName,compact('fields', 'fieldsName','form', 'kms_key_data'));
        }

        return abort(404);
    }		
}
