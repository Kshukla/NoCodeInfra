/*$('[data-depend]').load('input', function() {
	var dependOn = $(this).attr('data-depend');
	var dependElem = $('#'+dependOn);
	if(dependElem.attr('type') == 'checkbox'){
		if(dependElem.is(':checked'))
			$(this).show();
		else	
			$(this).hide();
	}
});*/

$('select.data-list-input').focus(function() {
	$(this).siblings('input.data-list-input').focus();
});
$('select.data-list-input').change(function() {
	$(this).siblings('input.data-list-input').val($(this).val());
});
$('input.data-list-input').change(function() {
	$(this).siblings('select.data-list-input').val('');
});
	
	
$('.jsonstring').bind('input propertychange', function() {
	var jsonString = this.value;
	this.value = jsonString.replace(/"/g, "\'");
});

//its for default resource name of region
$('select[name="region"]').change(function(){
	$("#region_name").val( $(this). children("option:selected"). text());
});

check_valid_cidr();
function check_valid_cidr(){
	$('[valid_cidr]').bind('input', function() {
    var cidrVal = $(this).val();
	if(cidrVal){
		const re = /^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])(\/([0-9]|[1-2][0-9]|3[0-2]))$/;
		var res = re.test(cidrVal);
		if(!res)
			this.setCustomValidity('Please enter valid IPV4 CIDR block');
		else
			this.setCustomValidity('');
	}
	});
}

/********************** Start code for tags ***************************/
var tags = $("#tags").val();
$("#tags").after($("#tagsBox"));
function saveTags(){
	var tagsData =  {};
	$(".tagkey").each(function(index ){
		var tagkey = $(this).val();
		var tagvalue = $('.tagvalue').eq(index).val();
		if(tagkey!=''){
			tagsData[tagkey] = tagvalue;
		}
	});
	$("#tags").val(JSON.stringify(tagsData).replace(/"/g, "\'"));
	if($("#tags").val() == '{}'){
		$("#tags").val('');
	}
}
function loadTags(){
	var tags = $("#tags").val().replace(/\'/g, '"');
	if(tags!=''){
	var obj = JSON.parse(tags);
	for (var key in obj) {
	  if (obj.hasOwnProperty(key)) {
		var val = obj[key];
		//console.log(key+'='+val);
		addTags(key,val);
	  }
	}
	}
}
function addTags(t_key='', t_value='') {
  var table = document.getElementById("tagsBox");
  var rowlen = table.rows.length;
  var row = table.insertRow(rowlen);
  row.id = "tag_"+rowlen;
  var tagkey = ['tagkey'];
  var tagvalue = ['tagvalue'];
  for (i = 0; i < 2; i++) {
    var x = row.insertCell(i)
    if (i == 1) {
      x.innerHTML = "<input type='button' onclick='removeTags(\"" + row.id + "\")' value=Delete>"
    } else {
      x.innerHTML = "<input type='text' name='" + tagkey[i] +'_'+rowlen+ "' placeholder='Key' class='tagkey' onchange='saveTags()' value='"+t_key+"'>&nbsp;&nbsp;<input type='text' name='" + tagvalue[i] +'_'+rowlen+ "' placeholder='Value' class='tagvalue' onchange='saveTags()' value='"+t_value+"'>"
    }
  }
}
function removeTags(rowid) {
  var table = document.getElementById(rowid).remove();
  saveTags();
}
/********************** End code for tags ***************************/

/********************** Start code for ingress ***************************/
var ingress = $("#ingress").val();
var ingressBox = "#ingressBox";
$("#ingress").after($(ingressBox));
function saveIngress(){
	var finalData =  [];
	$(ingressBox+" .from_port").each(function(index ){
		var dataArray =  {};
		var from_port = $(this).val();
		var to_port = $(ingressBox+' .to_port').eq(index).val();
		var protocol = $(ingressBox+' .protocol').eq(index).val();
		var cidr_blocks = $(ingressBox+' .cidr_blocks').eq(index).val();
		if(from_port!=''){
			dataArray['from_port'] = from_port;
			dataArray['to_port'] = to_port;
			dataArray['protocol'] = protocol;
			dataArray['cidr_blocks'] = cidr_blocks;
			finalData.push(dataArray);
		}
	});
	$("#ingress").val(JSON.stringify(finalData).replace(/"/g, "\'"));
	if($("#ingress").val() == '{}'){
		$("#ingress").val('');
	}
}
function loadIngress(){
	var ingress = $("#ingress").val().replace(/\'/g, '"');
	if(ingress!=''){
	var obj = JSON.parse(ingress);
	for (var key in obj) {
	  if (obj.hasOwnProperty(key)) {
		var val = obj[key];
		addIngress(val.from_port,val.to_port,val.protocol,val.cidr_blocks);
	  }
	}
	}
}
function addIngress(from_port_val='', to_port_val='', protocol_val='', cidr_blocks_val='') {
  var table = document.getElementById("ingressBox");
  var rowlen = table.rows.length;
  var row = table.insertRow(rowlen);
  row.id = "ing_"+rowlen;
  var from_port = ['from_port'];
  var to_port = ['to_port'];
  var protocol = ['protocol'];
  var cidr_blocks = ['cidr_blocks'];
  for (i = 0; i < 2; i++) {
    var x = row.insertCell(i)
    if (i == 1) {
      x.innerHTML = "<input type='button' onclick='removeIngress(\""+ row.id + "\")' value=Delete>"
    } else {
      x.innerHTML = "<input type='text' name='" + from_port[i] +'_'+rowlen+ "' placeholder='from_port' class='from_port' onchange='saveIngress()' value='"+from_port_val+"' required>&nbsp;&nbsp;<input type='text' name='" + to_port[i] +'_'+rowlen+ "' placeholder='to_port' class='to_port' onchange='saveIngress()' value='"+to_port_val+"' required>&nbsp;&nbsp;";
	  //<input type='text' name='" + protocol[i] +'_'+rowlen+ "' placeholder='protocol' class='protocol' onchange='saveIngress()' value='"+protocol_val+"' required>
	  x.innerHTML += "<select name='" + protocol[i] +"_"+rowlen+ "' class='protocol' onchange='saveIngress()'><option value=''>Select Protocol</option><option value='all' "+(protocol_val=='all'?'selected':'')+">ALL</option><option value='tcp' "+(protocol_val=='tcp'?'selected':'')+">TCP</option><option value='udp' "+(protocol_val=='udp'?'selected':'')+">UDP</option></select>";
	  x.innerHTML += "&nbsp;&nbsp;<input type='text' name='" + cidr_blocks[i] +'_'+rowlen+ "' placeholder='cidr_blocks' class='cidr_blocks' onchange='saveIngress()' value='"+cidr_blocks_val+"' valid_cidr='1' required>";
	  check_valid_cidr();
	}
  }
}
function removeIngress(rowid) {
  var table = document.getElementById(rowid).remove();
  saveIngress();
}
/********************** End code for ingress ***************************/

/********************** Start code for egress ***************************/
var egress = $("#egress").val();
var egressBox = "#egressBox";
$("#egress").after($(egressBox));
function saveEgress(){
	var finalData =  [];
	$(egressBox+" .from_port").each(function(index ){
		var dataArray =  {};
		var from_port = $(this).val();
		var to_port = $(egressBox+' .to_port').eq(index).val();
		var protocol = $(egressBox+' .protocol').eq(index).val();
		var cidr_blocks = $(egressBox+' .cidr_blocks').eq(index).val();
		if(from_port!=''){
			dataArray['from_port'] = from_port;
			dataArray['to_port'] = to_port;
			dataArray['protocol'] = protocol;
			dataArray['cidr_blocks'] = cidr_blocks;
			finalData.push(dataArray);
		}
	});
	$("#egress").val(JSON.stringify(finalData).replace(/"/g, "\'"));
	if($("#egress").val() == '{}'){
		$("#egress").val('');
	}
}
function loadEgress(){
	var egress = $("#egress").val().replace(/\'/g, '"');
	if(egress!=''){
	var obj = JSON.parse(egress);
	for (var key in obj) {
	  if (obj.hasOwnProperty(key)) {
		var val = obj[key];
		addEgress(val.from_port,val.to_port,val.protocol,val.cidr_blocks);
	  }
	}
	}
}

function addEgress(from_port_val='', to_port_val='', protocol_val='', cidr_blocks_val='') {
  var table = document.getElementById("egressBox");
  var rowlen = table.rows.length;
  var row = table.insertRow(rowlen);
  row.id = "egr_"+rowlen;
  var from_port = ['from_port'];
  var to_port = ['to_port'];
  var protocol = ['protocol'];
  var cidr_blocks = ['cidr_blocks'];
  for (i = 0; i < 2; i++) {
    var x = row.insertCell(i)
    if (i == 1) {
      x.innerHTML = "<input type='button' onclick='removeIngress(\""+ row.id + "\")' value=Delete>"
    } else {
      x.innerHTML = "<input type='text' name='" + from_port[i] +'_'+rowlen+ "' placeholder='from_port' class='from_port' onchange='saveEgress()' value='"+from_port_val+"' required>&nbsp;&nbsp;<input type='text' name='" + to_port[i] +'_'+rowlen+ "' placeholder='to_port' class='to_port' onchange='saveEgress()' value='"+to_port_val+"' required>&nbsp;&nbsp;";
		// <input type='text' name='" + protocol[i] +'_'+rowlen+ "' placeholder='protocol' class='protocol' onchange='saveEgress()' value='"+protocol_val+"' required>
	  x.innerHTML += "<select name='" + protocol[i] +"_"+rowlen+ "' class='protocol' onchange='saveEgress()'><option value=''>Select Protocol</option><option value='all' "+(protocol_val=='all'?'selected':'')+">ALL</option><option value='tcp' "+(protocol_val=='tcp'?'selected':'')+">TCP</option><option value='udp' "+(protocol_val=='udp'?'selected':'')+">UDP</option></select>";
	  x.innerHTML += "&nbsp;&nbsp;<input type='text' name='" + cidr_blocks[i] +'_'+rowlen+ "' placeholder='cidr_blocks' class='cidr_blocks' onchange='saveEgress()' value='"+cidr_blocks_val+"' valid_cidr='1' required>";
	  check_valid_cidr();
	}
  }
}
function removeEgress(rowid) {
  var table = document.getElementById(rowid).remove();
  saveEgress();
}
/********************** End code for egress ***************************/

/********************** Start code for Storage ***************************/
var storage = $("#storage").val();
var storageBox = "#storageBox";
$("#storage").after($(storageBox));
function saveStorage(){
	var finalData =  [];
	$(storageBox+" .v_type").each(function(index ){
		var dataArray =  {};
		var v_type = $(this).val();
		var device = $(storageBox+' .device').eq(index).val();
		var snapshot = $(storageBox+' .snapshot').eq(index).val();
		var size = $(storageBox+' .size').eq(index).val();
		var volume_type = $(storageBox+' .volume_type').eq(index).val();
		var del_on_term = ($(storageBox+' .del_on_term').eq(index).is(':checked')?$(storageBox+' .del_on_term').eq(index).val():'');
		var encryption = $(storageBox+' .encryption').eq(index).val();
		if(v_type!=''){
			dataArray['v_type'] = v_type;
			dataArray['device'] = device;
			dataArray['snapshot'] = snapshot;
			dataArray['size'] = size;
			dataArray['volume_type'] = volume_type;
			dataArray['del_on_term'] = del_on_term;
			dataArray['encryption'] = encryption;
			finalData.push(dataArray);
		}
	});
	$("#storage").val(JSON.stringify(finalData).replace(/"/g, "\'"));
	if($("#storage").val() == '{}'){
		$("#storage").val('');
	}
}
function loadStorage(){
	var storage = $("#storage").val().replace(/\'/g, '"');
	if(storage!=''){
	var obj = JSON.parse(storage);
	var i=0;
	for (var key in obj) {
	  if (obj.hasOwnProperty(key)) {
		var val = obj[key];
		if(i==0){
			$('input[name="snapshot_1"]').val(val.snapshot);
			$('input[name="size_1"]').val(val.size);
			$('select[name="volume_type_1"]').val(val.volume_type);
			if(val.del_on_term==1)
				$('input[name="del_on_term_1"]').attr('checked','checked');
			else
				$('input[name="del_on_term_1"]').removeAttr('checked');
			$('select[name="encryption_1"]').val(val.encryption);
		}else{
			addStorage(val.v_type,val.device,val.snapshot,val.size,val.volume_type,val.del_on_term,val.encryption);
		}
		i++;
	  }
	}
	}
	
	
}
function addStorage(v_type_val='', device_val='', snapshot_val='', size_val='', volume_type_val='', del_on_term_val='', encryption_val='') {
  var table = document.getElementById("storageBox");
  var rowlen = table.rows.length;
  var row = table.insertRow(rowlen);
  row.id = "egr_"+rowlen;
  
  var v_type = ['v_type'];
  var device = ['device'];
  var snapshot = ['snapshot'];
  var size = ['size'];
  var volume_type = ['volume_type'];
  var del_on_term = ['del_on_term']; 
  var encryption = ['encryption'];

  for (i = 0; i < 2; i++) {
    var x = row.insertCell(i)
    if (i == 1) {
      x.innerHTML = "<input type='button' onclick='removeStorage(\""+ row.id + "\")' value=Delete>"
    } else {
      x.innerHTML = "<input type='text' name='" + v_type[i] +"_"+rowlen+ "' class='v_type' readonly value='EBS'>&nbsp;&nbsp;";
	  x.innerHTML += "<select name='" + device[i] +"_"+rowlen+ "' class='device' onchange='saveStorage()'><option></option><option value='/dev/sdb' "+(device_val=='/dev/sdb'?'selected':'')+">/dev/sdb</option><option value='/dev/sdc' "+(device_val=='/dev/sdc'?'selected':'')+">/dev/sdc</option><option value='/dev/sdd' "+(device_val=='/dev/sdd'?'selected':'')+">/dev/sdd</option><option value='/dev/sde' "+(device_val=='/dev/sde'?'selected':'')+">/dev/sde</option><option value='/dev/sdf' "+(device_val=='/dev/sdf'?'selected':'')+">/dev/sdf</option><option value='/dev/sdg' "+(device_val=='/dev/sdg'?'selected':'')+">/dev/sdg</option><option value='/dev/sdh' "+(device_val=='/dev/sdh'?'selected':'')+">/dev/sdh</option><option value='/dev/sdi' "+(device_val=='/dev/sdi'?'selected':'')+">/dev/sdi</option><option value='/dev/sdj' "+(device_val=='/dev/sdj'?'selected':'')+">/dev/sdj</option><option value='/dev/sdk' "+(device_val=='/dev/sdk'?'selected':'')+">/dev/sdk</option><option value='/dev/sdl' "+(device_val=='/dev/sdl'?'selected':'')+">/dev/sdl</option><option value='/dev/sdm' "+(device_val=='/dev/sdm'?'selected':'')+">/dev/sdm</option><option value='/dev/sdn' "+(device_val=='/dev/sdn'?'selected':'')+">/dev/sdn</option><option value='/dev/sdo' "+(device_val=='/dev/sdo'?'selected':'')+">/dev/sdo</option><option value='/dev/sdp' "+(device_val=='/dev/sdp'?'selected':'')+">/dev/sdp</option><option value='/dev/sdq' "+(device_val=='/dev/sdq'?'selected':'')+">/dev/sdq</option><option value='/dev/sdr' "+(device_val=='/dev/sdr'?'selected':'')+">/dev/sdr</option><option value='/dev/sds' "+(device_val=='/dev/sds'?'selected':'')+">/dev/sds</option><option value='/dev/sdt' "+(device_val=='/dev/sdt'?'selected':'')+">/dev/sdt</option><option value='/dev/sdu' "+(device_val=='/dev/sdu'?'selected':'')+">/dev/sdu</option><option value='/dev/sdv' "+(device_val=='/dev/sdv'?'selected':'')+">/dev/sdv</option><option value='/dev/sdw' "+(device_val=='/dev/sdw'?'selected':'')+">/dev/sdw</option><option value='/dev/sdx' "+(device_val=='/dev/sdx'?'selected':'')+">/dev/sdx</option><option value='/dev/sdy' "+(device_val=='/dev/sdy'?'selected':'')+">/dev/sdy</option><option value='/dev/sdz' "+(device_val=='/dev/sdz'?'selected':'')+">/dev/sdz</option></select>&nbsp;&nbsp;"
	  x.innerHTML += "<input type='text' name='" + snapshot[i] +'_'+rowlen+ "' placeholder='snapshot' class='snapshot' onchange='saveStorage()' value='"+snapshot_val+"'>&nbsp;&nbsp;";
	  x.innerHTML += "<input type='number' name='" + size[i] +'_'+rowlen+ "' placeholder='size(GiB)' class='size' onchange='saveStorage()' min='1' value='"+size_val+"' required>&nbsp;&nbsp;";	
	  x.innerHTML += "<select name='" + volume_type[i] +"_"+rowlen+ "' class='volume_type' style='width:50%' onchange='saveStorage()'><option value='gp2' "+(volume_type_val=='gp2'?'selected':'')+">General Purpose SSD (gp2)</option><option value='io1' "+(volume_type_val=='io1'?'selected':'')+">Provisioned IOPS SSD (io1)</option><option value='sc1' "+(volume_type_val=='sc1'?'selected':'')+">Cold HDD (sc1)</option><option value='st1' "+(volume_type_val=='st1'?'selected':'')+">Throughput Optimized Hdd (st1)</option><option value='standard' "+(volume_type_val=='standard'?'selected':'')+">Magnetic (standard)</option></select>&nbsp;&nbsp;";
	  x.innerHTML += "<input type='checkbox' name='" + del_on_term[i] +'_'+rowlen+ "' "+(del_on_term_val==1?'checked':'')+" class='del_on_term' onchange='saveStorage()' value='1'>Delete on Termination&nbsp;&nbsp;";	
	  x.innerHTML += "<select name='" + encryption[i] +"_"+rowlen+ "' class='encryption' style='width:50%' onchange='saveStorage()'><option value=''>Not Encryption</option></select>";

	load_kms_key_dd(encryption[i] +"_"+rowlen);
	if(encryption_val!='')
	$('select[name="'+encryption[i] +"_"+rowlen+'"]').val(encryption_val);
	
	$(".device").change(function() {
		if ($('select.device option[value="' + $(this).val() + '"]:selected').length > 1) {
			$(this).val('-1').change();
			this.setCustomValidity('You have already selected this option previously - please choose another.');
		}else{
			this.setCustomValidity('');
		}	
	});
	
	  check_valid_cidr();
	}
  }
}
function removeStorage(rowid) {
  var table = document.getElementById(rowid).remove();
  saveStorage();
}

load_kms_key_dd('encryption_1');
function load_kms_key_dd(elemName){
	var kms_key_json = JSON.parse($('#kms_key_data').val());
	$.each(kms_key_json,function(index,kms_name){
		$('select[name="'+elemName+'"]').append($("<option></option>").attr("value", index).text(kms_name));
	});  
}

setTimeout(function(){ saveStorage(); }, 3000);
/********************** End code for Storage ***************************/