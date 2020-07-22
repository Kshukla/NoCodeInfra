<?php
$tags_field =	[
				'title' => 'Tags',
				'name' => 'tags',
				'type' => 'hidden',
				'value' => '',
				'attr' => ['class' => 'form-control box-size mi-tags', 'id' => 'tags']
			];
//	ingress	
$ingress_field =	[
				'title' => 'Ingress',
				'name' => 'ingress',
				'type' => 'hidden',
				'value' => '',
				'attr' => ['class' => 'form-control box-size mi-ingress', 'id' => 'ingress']
			];	
//	egress			
$egress_field =	[
			'title' => 'Egress',
			'name' => 'egress',
			'type' => 'hidden',
			'value' => '',
			'attr' => ['class' => 'form-control box-size mi-egress', 'id' => 'egress']
			];	

$storage_field =	[
			'title' => 'Storage',
			'name' => 'storage',
			'type' => 'hidden',
			'value' => '',
			'attr' => ['class' => 'form-control box-size mi-storage', 'id' => 'storage']
			];	
						
return [
    'component' => [
        'region' => [
			'name' => 'Region',
			'max' => 1,
			'after'=>'',
			'level'=>0,
			'fields' => [
				[
					'title' => 'Region Name',
					'name' => 'rc_name',
					'type' => 'text',
					'value' => '',
					'attr' => ['class' => 'form-control box-size mi-rc_name', 'id' => '', 'placeholder' => 'Region Name', 'required' => 'required']
				],
				[
					'title' => 'Region',
					'name' => 'region',
					'type' => 'select',
					'value' => '',
					'options' => ['us-east-1'=>'US East (N. Virginia)','us-west-2'=>'US West (Oregon)','us-west-1'=>'US West (N. California)' ,'eu-west-1'=>'EU (Ireland)' ,'eu-central-1'=>'EU (Frankfurt)' ,'ap-southeast-1'=>'Asia Pacific (Singapore)' ,'ap-northeast-1'=>'Asia Pacific (Tokyo)' ,'ap-southeast-2'=>'Asia Pacific (Sydney)' ,'ap-northeast-2'=>'Asia Pacific (Seoul)' ,'sa-east-1'=>'South America (São Paulo)' ,'cn-north-1'=>'China (Beijing)' ,'ap-south-1'=>'India (Mumbai)'],
					'attr' => ['class' => 'form-control box-size mi-region', 'id' => '', 'required' => 'required',]
				],
			],
		],
		'VPC' =>[
			'name' => 'VPC',
			'max' => 1,
			'after'=>'region',
			'level'=>1,
			'fields' => [
				[
					'title' => 'VPC Name',
					'name' => 'rc_name',
					'type' => 'text',
					'value' => '',
					'attr' => ['class' => 'form-control box-size mi-rc_name', 'id' => '', 'placeholder' => 'VPC Name', 'required' => 'required']
				],
				[
					'title' => 'IPV4 CIDR block',
					'name' => 'ipv4_cidr_block',
					'type' => 'text',
					'value' => '',
					'attr' => ['class' => 'form-control box-size mi-ipv4_cidr_block', 'id' => '', 'placeholder' => 'IPV4 CIDR block', 'valid_cidr'=>'1']
					
				],	
				[
					'title' => 'Tenancy',
					'name' => 'tenancy',
					'type' => 'select',
					'value' => 'Default',
					'options' => ['Default'=>'Default','Dedicated'=>'Dedicated'],
					'attr' => ['class' => 'form-control box-size mi-tenancy', 'id' => '']
				],	
				$tags_field,	
			],
		],
		'subnet' =>[
			'name' => 'Subnet',
			'max' => 50,
			'after'=>'VPC',
			'level'=>2,
			'fields' => [
				[
					'title' => 'Subnet Name',
					'name' => 'rc_name',
					'type' => 'text',
					'value' => '',
					'attr' => ['class' => 'form-control box-size mi-rc_name', 'id' => '', 'placeholder' => 'Subnet Name', 'required' => 'required']
				],
				[
					'title' => 'IPV4 CIDR block',
					'name' => 'ipv4_cidr_block',
					'type' => 'text',
					'value' => '',
					'attr' => ['class' => 'form-control box-size mi-ipv4_cidr_block', 'id' => '', 'placeholder' => 'IPV4 CIDR block', 'valid_cidr'=>'1']
				],	
				[
					'title' => 'Availability Zone',
					'name' => 'availability_zone',
					'type' => 'select',
					'belong' => 'region',
					'value' => '',
					'options' => [
									'us-east-1a'=>'us-east-1a','us-east-1b'=>'us-east-1b','us-east-1c'=>'us-east-1c', 'us-east-1d'=>'us-east-1d', 'us-east-1e'=>'us-east-1e', 'us-east-1f'=>'us-east-1f',
									'us-west-2a'=>'us-west-2a','us-west-2b'=>'us-west-2b','us-west-2c'=>'us-west-2c',
									'us-west-1a'=>'us-west-1a','us-west-1b'=>'us-west-1b',
									'eu-west-1a'=>'eu-west-1a','eu-west-1b'=>'eu-west-1b','eu-west-1c'=>'eu-west-1c',
									'eu-central-1a'=>'eu-central-1a','eu-central-1b'=>'eu-central-1b',
									'ap-southeast-1a'=>'ap-southeast-1a','ap-southeast-1b'=>'ap-southeast-1b',
									'ap-southeast-2a'=>'ap-southeast-2a','ap-southeast-2b'=>'ap-southeast-2b','ap-southeast-2c'=>'ap-southeast-2c',
									'ap-northeast-1a'=>'ap-northeast-1a','ap-northeast-1c'=>'ap-northeast-1c',
									'sa-east-1a'=>'sa-east-1a','sa-east-1b'=>'sa-east-1b','sa-east-1c'=>'sa-east-1c',
									'ap-south-1a'=>'ap-south-1a','ap-south-1b'=>'ap-south-1b',
					],
					'attr' => ['class' => 'form-control box-size mi-availability_zone', 'id' => '']
				],
				$tags_field,
			],
		],
		/*'security_group' =>[
			'name' => 'Security Group',
			'fields' => [
				[
					'title' => 'Name',
					'name' => 'sg_name',
					'type' => 'text',
					'value' => '',
					'attr' => ['class' => 'form-control box-size mi-sg_name', 'id' => '', 'placeholder' => 'Name', 'required' => 'required']
				],
				[
					'title' => 'Description',
					'name' => 'description',
					'type' => 'text',
					'value' => '',
					'attr' => ['class' => 'form-control box-size mi-description', 'id' => '', 'placeholder' => 'Description']
				],
				$ingress_field,
				$egress_field,		
				$tags_field,
			],
		],	*/	
		'EC2_instance_1' =>[
			'name' => 'EC2 Instance 1',
			'max' => 50,
			'after'=>'VPC',
			'level'=>3,
			'fields' => [
				[
					'title' => 'EC2 Instance Name',
					'name' => 'rc_name',
					'type' => 'text',
					'value' => '',
					'attr' => ['class' => 'form-control box-size mi-rc_name', 'id' => '', 'placeholder' => 'EC2 Instance Name', 'required' => 'required']
				],
				[
					'title' => 'AMI',
					'name' => 'ami',
					'type' => 'textselect',
					'value' => '',
					'options' => ['option1' => 'option1', 'option2' => 'option2', 'option3' => 'option3'],
					'text_attr' => ['class' => 'data-list-input form-control box-size mi-ami', 'id' => '', 'placeholder' => 'AMI', 'required' => 'required'],
					'select_attr' => ['class' => 'data-list-input form-control box-size']
				],
				[
					'title' => 'Security Group',
					'name' => 'sg_group',
					'type' => 'select',
					'value' => '',
					'global' => 'security_group', 
					'options' => [],
					//'options' => [''=>'Select Security Group'],
					'attr' => ['class' => 'form-control box-size mi-sg_group', 'id' => '', 'multiple' => 'multiple']
				],
				[
					'title' => 'Instance Type',
					'name' => 'instance_type',
					'type' => 'select',
					'value' => '',
					'options' => ['t1.micro'=>'t1.micro','t2.micro'=>'t2.micro','t3.micro'=>'t3.micro'],
					'attr' => ['class' => 'form-control box-size mi-instance_type', 'id' => '']
				],
				[
					'title' => 'IAM Role',
					'name' => 'iam_role_id',
					'type' => 'select',
					'value' => '',
					'global' => 'aws_iam_role', 
					'options' => [''=>'Select'],
					'attr' => ['class' => 'form-control box-size mi-iam_role_id', 'id' => '']
				],
				[
					'title' => 'Auto Assign Public IP',
					'name' => 'auto_public_ip',
					'type' => 'select',
					'value' => '',
					'options' => [''=>'Select','enable'=>'Enable','disable'=>'Disable'],
					'attr' => ['class' => 'form-control box-size mi-auto_public_ip', 'id' => '']
				],	
				$storage_field,
				$tags_field,
			],
		],
		'aws_s3_bucket' =>[
			'name' => 'S3 Bucket',
			'max' => 50,
			'after'=>'VPC',
			'level'=>3,
			'fields' => [
				[
					'title' => 'S3 Bucket Name',
					'name' => 'rc_name',
					'type' => 'text',
					'value' => '',
					'attr' => ['class' => 'form-control box-size mi-rc_name', 'id' => '', 'placeholder' => 'S3 Bucket Name', 'required' => 'required']
				],
				[
					'title' => 'Bucket',
					'name' => 'bucket',
					'type' => 'text',
					'value' => '',
					'attr' => ['class' => 'form-control box-size mi-bucket', 'id' => '', 'placeholder' => 'Bucket', 'required' => 'required']
				],
				[
					'title' => 'ACL',
					'name' => 'acl',
					'type' => 'select',
					'value' => 'private',
					'options' => ['private'=>'Private','public-read'=>'Public Read','public-read-write'=>'Public Read/Write', 'aws-exec-read'=>'Owner Full Control' ,'authenticated-read'=>'Owner Full Control+Authenticated Users Read' ,'bucket-owner-read'=>'Bucket Owner Read' ,'bucket-owner-full-control'=>'Bucket Owner Full Control' ,'log-delivery-write'=>'Log Delivery Read/Write'],
					'attr' => ['class' => 'form-control box-size mi-acl', 'id' => '']
				],
				[
					'title' => 'Versioning',
					'name' => 'versioning',
					'type' => 'checkbox',
					'value' => 'true',
					'default'=> false,
					'attr' => ['class' => 'mt-10 mi-versioning', 'id' => '']
				],
				[
					'title' => 'Server Access Logging',
					'name' => 'ser_acc_log',
					'type' => 'checkbox',
					'value' => 'true',
					'default'=> false,
					'attr' => ['class' => 'mt-10 mi-ser_acc_log', 'id' => 'ser_acc_log']
				],
				/*[
					'title' => 'Target Bucket',
					'name' => 'target_bucket',
					'type' => 'text',
					'value' => '',
					'attr' => ['class' => 'form-control box-size mi-target_bucket', 'id' => '', 'placeholder' => 'Target Bucket', 'required' => 'required', 'data-depend' => 'ser_acc_log']
				],		*/		
				[
					'title' => 'Object Level Logging',
					'name' => 'obg_lev_log',
					'type' => 'checkbox',
					'value' => 'true',
					'default'=> false,
					'attr' => ['class' => 'mt-10 mi-obg_lev_log']
				],
				[
					'title' => 'KMS Key',
					'name' => 'kms_key_id',
					'type' => 'select',
					'value' => '',
					'global' => 'aws_kms_key', 
					'options' => [''=>'Disable'],
					'attr' => ['class' => 'form-control box-size mi-kms_key_id', 'id' => '']
				],				
				$tags_field,
			],
		],			
    ],
	'global_component' => [
		'security_group' =>[
			'name' => 'Security Group',
			'after'=>'VPC',
			'fields' => [
				[
					'title' => 'Name',
					'name' => 'rc_name',
					'type' => 'text',
					'value' => '',
					'attr' => ['class' => 'form-control box-size mi-rc_name', 'id' => '', 'placeholder' => 'Name', 'required' => 'required']
				],
				[
					'title' => 'Description',
					'name' => 'description',
					'type' => 'text',
					'value' => '',
					'attr' => ['class' => 'form-control box-size mi-description', 'id' => '', 'placeholder' => 'Description']
				],
				$ingress_field,
				$egress_field,		
				$tags_field,
			],
		],	
		'aws_kms_key' =>[
			'name' => 'KMS Key',
			'after'=>'VPC',
			'fields' => [
				[
					'title' => 'Name',
					'name' => 'rc_name',
					'type' => 'text',
					'value' => '',
					'attr' => ['class' => 'form-control box-size mi-rc_name', 'id' => '', 'placeholder' => 'Name', 'required' => 'required']
				],
				[
					'title' => 'Description',
					'name' => 'description',
					'type' => 'text',
					'value' => '',
					'attr' => ['class' => 'form-control box-size mi-description', 'id' => '', 'placeholder' => 'Description']
				],
				[
					'title' => 'Delete Duration(Days)',
					'name' => 'deletion_days',
					'type' => 'number',
					'value' => '',
					'attr' => ['class' => 'form-control box-size mi-deletion_days', 'id' => '', 'placeholder' => 'Delete Duration(Days)', 'max'=>30, 'min'=>7]
				],
			],
		],		
		'aws_iam_role' =>[
			'name' => 'IAM Role',
			'after'=>'VPC',
			'fields' => [
				[
					'title' => 'Name',
					'name' => 'rc_name',
					'type' => 'text',
					'value' => '',
					'attr' => ['class' => 'form-control box-size mi-rc_name', 'id' => '', 'placeholder' => 'Name', 'required' => 'required']
				],
				[
					'title' => 'Policy JSON Document',
					'name' => 'policy_json',
					'type' => 'textarea',
					'value' => '',
					'attr' => ['class' => 'form-control box-size mi-policy_json jsonstring','id' => '', 'rows' => 4, 'cols' => 54, 'placeholder' => 'Policy JSON Document']
				],
			],
		],		
	],
];
