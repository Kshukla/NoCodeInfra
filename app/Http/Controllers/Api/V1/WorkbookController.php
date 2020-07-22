<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\WorkbookResource;
use App\Models\Workbook\Workbook;
use App\Repositories\Frontend\Workbook\WorkbookRepository;
use Illuminate\Http\Request;
use Validator;
use App\Helpers\Frontend\Common;

class WorkbookController extends APIController
{
    protected $repository;

    /**
     * __construct.
     *
     * @param $repository
     */
    public function __construct(WorkbookRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Return the specified resource.
     *
     * @param Workbook $workbook
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Workbook $workbook)
    {
        return new WorkbookResource($workbook);
    }
	
    public function gettf(Workbook $workbook)
    {
		global $items_global_array;
		$commonObj = new Common();
		$items_array = json_decode($workbook->items,true);
		$data_array = $commonObj->array_flatten($items_array, array());
		
		$data_global_array = [];
		$items_global_array = json_decode($workbook->items_global,true);
		if(!empty($items_global_array))
		$data_global_array = $commonObj->array_flatten($items_global_array, array());
		$data_array = array_merge($data_global_array,$data_array);
		for($i=0; $i<count($data_array); $i++){
			$data_array[$i]['rc_name'] = $commonObj->clean_string($data_array[$i]['rc_name']);
			if(isset($data_array[$i]['sg_group'])){
				$sg_group_names = [];
				$sg_groups = explode(',',$data_array[$i]['sg_group']);
				foreach($sg_groups as $sg_group){
					$sg_data = $commonObj->arr_search($items_global_array, 'id', $sg_group);
					if(isset($sg_data[0]['rc_name']))
					$sg_group_names[] = $commonObj->clean_string($sg_data[0]['rc_name']);
				}
				$data_array[$i]['sg_group'] = $sg_group_names;
				//$sg_data = $commonObj->arr_search($items_global_array, 'id', $data_array[$i]['sg_group']);
				//$data_array[$i]['sg_group'] = $commonObj->clean_string($sg_data[0]['rc_name']);
			}
			if(isset($data_array[$i]['kms_key_id']) && $data_array[$i]['kms_key_id']!=''){
				$kms_data = $commonObj->arr_search($items_global_array, 'id', $data_array[$i]['kms_key_id']);
				if(isset($kms_data[0]['rc_name']))
					$data_array[$i]['kms_key_id'] = $commonObj->clean_string($kms_data[0]['rc_name']);
				else
					$data_array[$i]['kms_key_id'] = '';
			}			
		}
		//echo "<pre>";print_r($data_array);exit;
		$this->generatetf($data_array);
       // return new WorkbookResource($workbook);
    }	
    public function viewtf(Workbook $workbook)
    {
		global $items_global_array;
		$commonObj = new Common();
		$items_array = json_decode($workbook->items,true);
		$data_array = $commonObj->array_flatten($items_array, array());
		
		$data_global_array = [];
		$items_global_array = json_decode($workbook->items_global,true);
		if(!empty($items_global_array))
		$data_global_array = $commonObj->array_flatten($items_global_array, array());
		$data_array = array_merge($data_global_array,$data_array);
		for($i=0; $i<count($data_array); $i++){
			$data_array[$i]['rc_name'] = $commonObj->clean_string($data_array[$i]['rc_name']);
			if(isset($data_array[$i]['sg_group'])){
				$sg_group_names = [];
				$sg_groups = explode(',',$data_array[$i]['sg_group']);
				foreach($sg_groups as $sg_group){
					$sg_data = $commonObj->arr_search($items_global_array, 'id', $sg_group);
					if(isset($sg_data[0]['rc_name']))
					$sg_group_names[] = $commonObj->clean_string($sg_data[0]['rc_name']);
				}
				$data_array[$i]['sg_group'] = $sg_group_names;
				//$sg_data = $commonObj->arr_search($items_global_array, 'id', $data_array[$i]['sg_group']);
				//$data_array[$i]['sg_group'] = $commonObj->clean_string($sg_data[0]['rc_name']);
			}
			if(isset($data_array[$i]['kms_key_id']) && $data_array[$i]['kms_key_id']!=''){
				$kms_data = $commonObj->arr_search($items_global_array, 'id', $data_array[$i]['kms_key_id']);
				if(isset($kms_data[0]['rc_name']))
					$data_array[$i]['kms_key_id'] = $commonObj->clean_string($kms_data[0]['rc_name']);
				else
					$data_array[$i]['kms_key_id'] = '';
			}
		}
		//echo "<pre>";print_r($data_array);exit;?>
		
		 <pre>
			<code>
				<?php $this->generatetf($data_array) ?>
			</code>
		</pre>
		<?php
       // return new WorkbookResource($workbook);
    }	
    public function generatetf($data_array)
    {
		$commonObj = new Common();
		$vpc_data = $commonObj->arr_search($data_array, 'key', 'VPC');
		$vpc_id = $vpc_data[0]['rc_name'];
		foreach($data_array as $item){
		
			switch ($item['key']) {
				case "region":
				?>

provider "aws" {
  region  = "<?php echo $item['region']?>"
  profile = "${var.aws_profile}"
}
				<?php
					break;
				case "VPC":
				//$vpc_id = $item['rc_name'];
				?>
					
resource "aws_vpc" "<?php echo $item['rc_name']?>" {
 cidr_block           = "<?php echo $item['ipv4_cidr_block']?>"
 enable_dns_hostnames = true
 enable_dns_support   = true
 <?php $this->generatetags($item);?>

}
						
				<?php
					break;
				case "subnet":
			//	print_r($item);exit;
				?>
					
resource "aws_subnet" "<?php echo $item['rc_name']?>" {
  vpc_id     = "${aws_vpc.<?php echo $vpc_id?>.id}"
  cidr_block = "<?php echo $item['ipv4_cidr_block']?>"
  <?php if(isset($item['availability_zone'])){?>
availability_zone = "<?php echo $item['availability_zone']?>"
  <?php }?>
 <?php $this->generatetags($item);?>
 
}

				<?php
					break;
				case "security_group":
?>
					
resource "aws_security_group" "<?php echo $item['rc_name']?>" {
  name        = "<?php echo $item['rc_name']?>"
  description = "<?php echo $item['description']?>"
  vpc_id     = "${aws_vpc.<?php echo $vpc_id?>.id}"
  
<?php $this->generateingress($item);?>

<?php $this->generateegress($item);?>

 <?php $this->generatetags($item);?>
}

				<?php
					break;
				case "EC2_instance_1":
?>
							
resource "aws_instance" "<?php echo $item['rc_name']?>" {
  ami           = "<?php echo $item['ami']?>"
  <?php if(isset($item['instance_type']) && $item['instance_type']!=''){?>  
  instance_type = "<?php echo $item['instance_type']?>"
  <?php }?>
 <?php $this->generatetags($item);?>
<?php if(isset($item['sg_group'])){?>
 vpc_security_group_ids = [<?php $i = 0;$numItems = count($item['sg_group']); foreach($item['sg_group'] as $sg_group){?>"${aws_security_group.<?php echo $sg_group;?>.id}"<?php echo (++$i != $numItems)?',':''; } ?>]
  <?php }?>
}

 <?php $this->generatestorage($item);?>

				<?php	
					break;
				case "aws_kms_key":
?>
					
resource "aws_kms_key" "<?php echo $item['rc_name']?>" {
  description = "<?php echo $item['description']?>"
  deletion_window_in_days   = "<?php echo $item['deletion_days']?>"
}

				<?php
					break;					
				case "aws_s3_bucket":
?>
							
resource "aws_s3_bucket" "<?php echo $item['rc_name']?>" {
  bucket  = "<?php echo $item['bucket']?>"
  acl     = "<?php echo $item['acl']?>"

<?php if(isset($item['versioning'])){?>  
  versioning {
    enabled = true
  }
<?php }?> 
<?php if(isset($item['kms_key_id']) && $item['kms_key_id']!=''){?>  
  server_side_encryption_configuration {
    rule {
      apply_server_side_encryption_by_default {
        kms_master_key_id = "${aws_kms_key.<?php echo $item['kms_key_id'];?>.arn}"
        sse_algorithm     = "aws:kms"
      }
    }
  }
<?php }?> 
 <?php $this->generatetags($item);?>
}
				<?php					
					break;				
			}
		}

    }	
	public function generatetags($item){
		if(isset($item['tags']) && $item['tags']!=''){
		$tags_array = json_decode(str_replace("'", '"', $item['tags']),true);
		?>
tags = {
	<?php foreach($tags_array as $name=>$value){?>
	<?php echo $name?> = "<?php echo $value;?>"
	<?php }?>
}

<?php
		}
	}
	public function generateingress($item){
		if(isset($item['ingress']) && $item['ingress']!=''){
		$ingress_array = json_decode(str_replace("'", '"', $item['ingress']),true);
					//echo "<pre>";print_r($ingress_array);exit;
		foreach($ingress_array as $item){?>		
ingress = {
	from_port = "<?php echo $item['from_port'];?>"
	to_port = "<?php echo $item['to_port'];?>"
	protocol = "<?php echo $item['protocol'];?>"
	cidr_blocks = "<?php echo $item['cidr_blocks'];?>"
}
	<?php }
		}
	}
	public function generateegress($item){
		if(isset($item['egress']) && $item['egress']!=''){
		$egress_array = json_decode(str_replace("'", '"', $item['egress']),true);
		foreach($egress_array as $item){?>		
egress = {
	from_port = "<?php echo $item['from_port'];?>"
	to_port = "<?php echo $item['to_port'];?>"
	protocol = "<?php echo $item['protocol'];?>"
	cidr_blocks = "<?php echo $item['cidr_blocks'];?>"
}
	<?php }
		}
	}
	
	public function generatestorage($item){
		global $items_global_array;
		$commonObj = new Common();
		if(isset($item['storage']) && $item['storage']!=''){
		$storage_array = json_decode(str_replace("'", '"', $item['storage']),true);
		foreach($storage_array as $item_data){
			$ebs_volume = uniqid('attach_');
			?>		
resource "aws_ebs_volume" "<?php echo $ebs_volume;?>" {
  availability_zone = ""
  size              = <?php echo $item_data['size'];?>
  <?php if(isset($item_data['snapshot']) && $item_data['snapshot']!=''){?>   
  snapshot_id      = "<?php echo $item_data['snapshot'];?>"
  <?php }?>    
  type = "<?php echo $item_data['volume_type'];?>"
  <?php if(isset($item_data['encryption']) && $item_data['encryption']!=''){
	  $kms_data = $commonObj->arr_search($items_global_array, 'id', $item_data['encryption']);?>     
  encrypted = true
  kms_key_id = "<?php echo $commonObj->clean_string($kms_data[0]['rc_name']);?>"
<?php }?><?php if(isset($item_data['del_on_term']) && $item_data['del_on_term']){  ?>
  delete_on_termination = true
<?php }?>
}
resource "aws_volume_attachment" "ebs_att" {
  device_name = "<?php echo $item_data['device'];?>"
  volume_id   = "${aws_ebs_volume.<?php echo $ebs_volume;?>.id}"
  instance_id = "${aws_instance.<?php echo $item['rc_name']?>.id}"
}


	<?php }
		}
	}
}
