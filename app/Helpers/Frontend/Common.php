<?php

namespace App\Helpers\Frontend;

/**
 * Class Socialite.
 */
class Common
{
	
	public function array_flatten($array,$return) {
		for($x = 0; $x <= count($array); $x++) {
			if(!empty($array[$x])){
				$dataArray = $array[$x];
				unset($dataArray['children']);
				$return[] = $dataArray;
			}
			if(isset($array[$x]['children'])) {
				$return = $this->array_flatten($array[$x]['children'], $return);
			}
		}
		return $return;
	}	

	public function get_global_array($data_key,$items_array) {
		$keys = array_column($items_array, 'key');
		$global_indexids = [];

		foreach($keys as $key=>$val){
			if ($data_key == $val)
				$global_indexids[] = $key;
		}
		$filteredData = array_filter($items_array, function($k) use ($global_indexids)  {
			return in_array($k, $global_indexids);
		}, ARRAY_FILTER_USE_KEY);
		$data_array = array();
		foreach($filteredData as $item){
			if(isset($item['rc_name']))
			$data_array[$item['id']] = $item['rc_name'];
		}				
		return $data_array;
	}	
	
	public function clean_string($string) {
	   $string = str_replace(' ', '_', strtolower($string)); // Replaces all spaces with hyphens.
	   return preg_replace('/[^A-Za-z0-9\_]/', '', $string); // Removes special chars.
	}
	
	public function arr_search($array, $key, $value){
		$results = array();

		if (is_array($array)) {
			if (isset($array[$key]) && $array[$key] == $value) {
				$results[] = $array;
			}

			foreach ($array as $subarray) {
				$results = array_merge($results, $this->arr_search($subarray, $key, $value));
			}
		}

		return $results;
	}
	
    function check_overlap ($net1, $net2) {

        $mask1 = explode("/", $net1)[1];
        $net1 = explode("/", $net1)[0];
        $netArr1 = explode(".",$net1);

        $mask2 = explode("/", $net2)[1];
        $net2 = explode("/", $net2)[0];
        $netArr2 = explode(".",$net2);

        $newnet1 = $newnet2 = "";

        foreach($netArr1 as $num) {
            $binnum = decbin($num);
            $length = strlen($binnum);
            for ($i = 0; $i < 8-$length; $i++) {
                $binnum = '0'.$binnum;
            }
            $newnet1 .= $binnum;
        }

        foreach($netArr2 as $num) {
            $binnum = decbin($num);
            $length = strlen($binnum);
            for ($i = 0; $i < 8-$length; $i++) {
                $binnum = '0'.$binnum;
            }
            $newnet2 .= $binnum;
        }

        $length = min($mask1, $mask2);

        $newnet1 = substr($newnet1,0,$length);
        $newnet2 = substr($newnet2,0,$length);

        $overlap = 0;
        if ($newnet1 == $newnet2) $overlap = 1;

        return $overlap;
    }
    function networks_overlap ($networks, $newnet) {

        $overlap = false;
        foreach ($networks as $network) {
			foreach ($networks as $network_child) {
				$overlap = $this->check_overlap($network, $network_child);
				if ($overlap) return 1;
			}
        }
        return $overlap;        
    }	
}
