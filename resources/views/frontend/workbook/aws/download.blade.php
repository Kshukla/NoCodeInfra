@php 
foreach($data_array as $item){
	if($item['key']=='region'){ 
		@endphp
		
provider "aws" {
  region  = "{{$item['region']}}"
  profile = "${var.aws_profile}"
}
		
		@php
	}
	if($item['key']=='VPC'){
		$vpc_id = $item['vpc_name'];
		@endphp
			
resource "aws_vpc" "{{$item['vpc_name']}}" {
 cidr_block           = "{{$item['ipv4_cidr_block']}}"
 enable_dns_hostnames = true
 enable_dns_support   = true

  tags = {
	Name = "wp_vpc"
  }
}
				
		@php
	}
	if($item['key']=='subnet'){
		@endphp
			
resource "aws_subnet" "{{$item['subnet_name']}}" {
  vpc_id     = "{{$vpc_id}}"
  cidr_block = "{{$item['ipv4_cidr_block']}}"

  tags = {
    Name = "Main"
  }
}


		@php
	}
	if($item['key']=='security_group'){
		@endphp
			
resource "aws_security_group" "{{$item['sg_name']}}" {
  name        = "{{$item['sg_name']}}"
  description = "{{$item['description']}}"
  vpc_id      = "{{$vpc_id}}"

  ingress {
    description = "TLS from VPC"
    from_port   = 443
    to_port     = 443
    protocol    = "tcp"
    cidr_blocks = [aws_vpc.main.cidr_block]
  }

  egress {
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["0.0.0.0/0"]
  }

  tags = {
    Name = "allow_tls"
  }
}

		@php
		}
		if($item['key']=='EC2_instance_1'){
			@endphp
					
resource "aws_instance" "web" {
  ami           = "{{$item['ami']}}"
  instance_type = "{{$item['instance_type']}}"

  tags = {
    Name = "HelloWorld"
  }
}

						
		@php		
	}
}
@endphp