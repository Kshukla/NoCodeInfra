var AWS = {

	Workbook: {
		selectors: {
			workbookItemContainer: jQuery("#workbook-items"),
			workbookItemsData: jQuery(".workbook-items-field"),
			wobGlobalItemsData: jQuery(".wb-global-items-field"),
			addCustomUrlButton: jQuery(".show-modal"),
			modal: jQuery("#showWorkbookModal"),
			document: jQuery("document"),
			addCustomUrlForm: "#workbook-add-custom-url",
			addModuleToWorkbookButton: ".add-module-to-workbook",
			removeWorkbookItemButton: "#workbook-items .remove-workbook-item",
			editWorkbookItemButton: "#workbook-items .edit-workbook-item",
			formUrl: "",
		},

		methods: {
			getNewId: function (str) {
				var arr = str.match(/"id":[0-9]+/gi);
				if (arr) {
					$.each(arr, function (index, item) {
						arr[index] = parseInt(item.replace('"id":', ''));
					});
					return Math.max.apply(Math, arr) + 1;
				}
				return 1;
			},

			findItemById: function (item, id) {
				if (item.id == id) {
					return item;
				}
				var found = false;
				var foundItem;
				if (item.children) {
					$.each(item.children, function (index, childItem) {
						foundItem = AWS.Workbook.methods.findItemById(childItem, id);
						if (foundItem) {
							found = true;
							return false;
						}
					});
				}
				if (found) {
					return foundItem;
				}
				return null;
			},

			addWorkbookItem: function (obj) {
				//Code for check max element exist
				if(AWS.Workbook.selectors.workbookItemContainer.find('li[data-key='+obj.key+']').length >= obj.max)
				return false;

				if(obj.after!=''){
					if(AWS.Workbook.selectors.workbookItemContainer.find('li[data-key='+obj.after+']').length ==0)
					return false;
				}
			
				
				var objData =  {};
				var fieldsObj = $.parseJSON(obj.fields);
				//var fieldsName = '';
				for(var i=0; i<fieldsObj.length;i++){
					// objData['field-'+i+'-title'] = fieldsObj[i].title;
					 objData[fieldsObj[i].name] = fieldsObj[i].value;
					// objData['field-'+i+'-type'] = fieldsObj[i].type;
					//fieldsName.push( fieldsObj[i].name );
					//fieldsName+=fieldsObj[i].name+(i!=fieldsObj.length-1?',':'');
				}
				//objData['fields'] = fieldsName;
				//console.log(fieldsName);
				
				//var parentDataId = $(this).attr('data-level',);
				var parentData = AWS.Workbook.selectors.workbookItemContainer.find('li[data-level='+(obj.level-1)+']');
				var parentDataId = parentData.attr('data-id');
				//alert(parentDataId);
				AWS.Workbook.selectors.workbookItemContainer.nestable('add', $.extend({
					"id": AWS.Workbook.methods.getNewId(AWS.Workbook.selectors.workbookItemsData.val()),
					"key": obj.key,
					"content": obj.name+(objData.rc_name?' ('+objData.rc_name+')':''),
					"name": obj.name,
					"level": obj.level,
				//	"fields": obj.fields,
					"url": obj.url,
					"url_type": obj.url_type,
					"open_in_new_tab": obj.open_in_new_tab,
					"parent_id":parentDataId,
				}, objData));
				AWS.Workbook.selectors.workbookItemsData.val(
					JSON.stringify(
						AWS.Workbook.selectors.workbookItemContainer.nestable('serialise')
					)
				);
				AWS.Workbook.methods.updateStatus();
				AWS.Workbook.methods.updateStyles();
				
				AWS.Workbook.selectors.workbookItemContainer.find("li.dd-item").each(function(i){
					var dataLevel = $(this).attr('data-level');
					if(dataLevel!=0){
						var parentLevel = $(this).parent('ol').parent("li.dd-item").attr('data-level');
						var comp = parseInt(parentLevel)+1;
						if(dataLevel!=comp){
							$(this).remove();
							AWS.Workbook.selectors.workbookItemsData.val(
								JSON.stringify(
									AWS.Workbook.selectors.workbookItemContainer.nestable('serialise')
								)
							);
						}
					}
				});
				
			},

			editWorkbookItem: function (obj) {
				var str_array = obj.fields_name.split(',');
				var objData =  {};
				for(var i = 0; i < str_array.length; i++) {
					objData[str_array[i]] = obj[str_array[i]];
				}
				var newObject = {
					"id": obj.id,
					"key": obj.key,
					"content": obj.name+(objData.rc_name?' ('+objData.rc_name+')':''),
					"name": obj.name,
					"level": obj.level,
					"url": obj.url,
					"url_type": obj.url_type,
					"open_in_new_tab": obj.open_in_new_tab,
				};
				newObject = $.extend(objData, newObject);
				
				var workbookItems = AWS.Workbook.selectors.workbookItemContainer.nestable('serialise');
				var itemData;
				$.each(workbookItems, function (index, item) {
					itemData = AWS.Workbook.methods.findItemById(item, id);
					if (itemData) {
						return false;
					}
				});
				if (itemData.children) {
					newObject.children = itemData.children;
				}

				AWS.Workbook.selectors.workbookItemContainer.nestable('replace', newObject);

				AWS.Workbook.selectors.workbookItemsData.val(
					JSON.stringify(
						AWS.Workbook.selectors.workbookItemContainer.nestable('serialise')
					)
				);
				AWS.Workbook.methods.updateStatus();
				AWS.Workbook.methods.updateStyles();
			},

			updateStatus: function () {
				$(".modules-list-item").each(function(){
					var afterEnable = true;
					var maxEnable = true;
					var afterId = $(this).find( "i" ).attr('data-after');
					if(afterId!=''){
						if(AWS.Workbook.selectors.workbookItemContainer.find('li[data-key='+afterId+']').length ==0){
							//$(this).find( "a" ).css({color: "#CCC"});
							afterEnable = false;
						}else{
							//$(this).find( "a" ).css({color: "#3097d1"});
						}
					}

					var keyComponent = $(this).find( "i" ).attr('data-key');
					var maxComponent = $(this).find( "i" ).attr('data-max');
					if(AWS.Workbook.selectors.workbookItemContainer.find('li[data-key='+keyComponent+']').length >= maxComponent)
						maxEnable = false;
					//else
						//$(this).find( "a" ).css({color: "#3097d1"});
					if(afterEnable==true && maxEnable==true)
						$(this).find( "a" ).css({color: "#3097d1"});
					else
						$(this).find( "a" ).css({color: "#CCC"});
						
				});
			},
			confirmRemove: function (dataMainKey) {
				var confirmed = false;
				var process = true;
				$(".modules-list-item i[data-after="+dataMainKey+"]").each(function(){
					var dataKey = $(this).attr('data-key');
					if(AWS.Workbook.selectors.workbookItemContainer.find('li[data-key='+dataKey+']').length >0){
						if(!confirmed){
							if(!confirm("This resource is contain some elements. Are you sure you want to delete this all?")){
								process = false;   
								return false;
							}
							confirmed = true;   
						}
						if(process){
							AWS.Workbook.selectors.workbookItemContainer.find("li[data-key="+dataKey+"]").each(function(){
								AWS.Workbook.selectors.workbookItemContainer.nestable('remove', jQuery(this).attr("data-id"));
								
								var dataChildKey = jQuery(this).attr("data-key");
								$(".modules-list-item i[data-after="+dataChildKey+"]").each(function(){
									AWS.Workbook.selectors.workbookItemContainer.find("li[data-key="+ $(this).attr('data-key')+"]").each(function(){
										AWS.Workbook.selectors.workbookItemContainer.nestable('remove', jQuery(this).attr("data-id"));
									});
								});	
							});
						}
					}
				});
				return process;
				/*AWS.Workbook.selectors.workbookItemsData.val(
					JSON.stringify(
						AWS.Workbook.selectors.workbookItemContainer.nestable('serialise')
					)
				);
				AWS.Workbook.methods.updateStatus();*/
			},

			updateStyles: function () {
				AWS.Workbook.selectors.workbookItemContainer.find("ol.dd-list").each(function(i){
					var dataLevel = $(this).attr('data-level');
					if(i!=0){
						$(this).css('marginLeft','8%');
					}
				});
			},
		},

		init: function () {
			this.addHandlers();
		},

		addHandlers: function () {
			var context = this;
			var formName = "_add_custom_url_form";

			this.selectors.workbookItemContainer.nestable({
				callback: function (l, e) {
					context.selectors.workbookItemsData.val(JSON.stringify($(l).nestable('serialise')));
				},
				json: this.selectors.workbookItemsData.val(),
				includeContent: true,
				scroll: false,
				maxDepth: 10
			});
			
			//Added for make enable/desable design
			context.methods.updateStatus();
			
			//added styles for make diff level of element
			context.methods.updateStyles();	
			jQuery(document).on("change", context.selectors.workbookItemContainer, function (e) {
				context.methods.updateStyles();	
			});
			
			this.selectors.addCustomUrlButton.click(function () {
				var title = context.selectors.addCustomUrlButton.attr("data-header");
				context.selectors.modal.find(".modal-title").html(title);
				context.selectors.modal.modal("show");

				callback = {
					success: function (request) {
						if (request.status >= 200 && request.status < 400) {
							// Success!
							context.selectors.modal.find(".modal-body").html(request.responseText);
							// jQuery(document).find(context.selectors.modal).find(".view-permission-block").remove();
							jQuery(document).find(context.selectors.addCustomUrlForm).removeClass("hidden");
						}
					},
					error: function (request) {
						//Do Something
					}
				};
				AWS.Utils.ajaxrequest(context.selectors.formUrl + "/" + formName, "get", {}, AWS.Utils.csrf, callback);
			});

			jQuery(document).on("submit", context.selectors.addCustomUrlForm, function (e) {
				e.preventDefault();
				var formData = jQuery(this).serializeArray().reduce(function (obj, item) {
					//added for multi select
					if($(document).find(context.selectors.modal).find(".mi-"+item.name).attr('multiple')){
						item.value = $(document).find(context.selectors.modal).find(".mi-"+item.name+' option:selected').toArray().map(item1 => item1.value).join();
					}
					//end
					obj[item.name] = item.value;
					return obj;
				}, {});

				if (formData.name.length > 0) {
					if (formData.id.length > 0) {
						context.methods.editWorkbookItem(formData);
					} else {
						context.methods.addWorkbookItem(formData);
					}
					context.selectors.modal.modal("hide");
				}

				callback = {
					success: function (request) {
						if (request.status >= 200 && request.status < 400) {
							$("#validate_response").text(request.responseText);
						}
					}
				}
				AWS.Utils.ajaxrequest(context.selectors.formUrl + "/validatewb", "post", {'items':AWS.Workbook.selectors.workbookItemsData.val()}, AWS.Utils.csrf, callback);
				
			});

			jQuery(document).on("click", context.selectors.addModuleToWorkbookButton, function () {
				var dataObj = {
					id: $(this).attr("data-id"),
					key: $(this).attr("data-key"),
					name: $(this).attr("data-name"),
					max: $(this).attr("data-max"),
					after: $(this).attr("data-after"),
					level: $(this).attr("data-level"),
					fields: $(this).attr("data-fields"),
					url: $(this).attr("data-url"),
					url_type: $(this).attr("data-url_type"),
					open_in_new_tab: $(this).attr("data-open_in_new_tab"),
				};
				context.methods.addWorkbookItem(dataObj);
			});

			jQuery(document).on("click", context.selectors.removeWorkbookItemButton, function () {
				if(!AWS.Workbook.methods.confirmRemove(jQuery(this).parents(".dd-item").first().attr("data-key"))){
					return false;
				}else{
					context.selectors.workbookItemContainer.nestable('remove', jQuery(this).parents(".dd-item").first().attr("data-id"));
					AWS.Workbook.selectors.workbookItemsData.val(
						JSON.stringify(
							AWS.Workbook.selectors.workbookItemContainer.nestable('serialise')
						)
					);
					AWS.Workbook.methods.updateStatus();
				}
			});

			jQuery(document).on("click", context.selectors.editWorkbookItemButton, function () {
				id = jQuery(this).parents(".dd-item").first().attr("data-id");
				var workbookItems = context.selectors.workbookItemContainer.nestable('serialise');
				var itemData;
				$.each(workbookItems, function (index, item) {
					itemData = context.methods.findItemById(item, id);
					if (itemData) {
						return false;
					}
				});
				if (itemData.id != undefined && itemData.id == id) {
					callback = {
						success: function (request) {
							if (request.status >= 200 && request.status < 400) {
								// Success!
								context.selectors.modal.find(".modal-body").html(request.responseText);
								context.selectors.modal.find(".modal-dialog .modal-content .modal-header .modal-title").html("Edit: " + itemData.name);
								$(document).find(context.selectors.modal).find(".mi-id").val(itemData.id);
								$(document).find(context.selectors.modal).find(".mi-key").val(itemData.key);
								$(document).find(context.selectors.modal).find(".mi-name").val(itemData.name);
								$(document).find(context.selectors.modal).find(".mi-level").val(itemData.level);
								$(document).find(context.selectors.modal).find(".mi-url").val(itemData.url);
								$(document).find(context.selectors.modal).find(".mi-url_type_" + itemData.url_type).prop("checked", true);
								if (itemData.open_in_new_tab == 1) {
									$(document).find(context.selectors.modal).find(".mi-open_in_new_tab").prop("checked", true);
								}

								var str_array = $(document).find(context.selectors.modal).find(".mi-fields_name").val().split(',');
								
								for(var i = 0; i < str_array.length; i++) {
									var elementSelector = $(document).find(context.selectors.modal).find(".mi-"+str_array[i]);
									//alert(elementSelector.attr('type'));
									if(elementSelector.attr('type') == 'checkbox'){
										if(itemData[str_array[i]])
											elementSelector.attr('checked','checked');
									}
									else if(elementSelector.attr('multiple')){
										if(itemData[str_array[i]]){
											if(itemData[str_array[i]].indexOf(',') != -1) 
												multiArr = itemData[str_array[i]].split(',');
											else
												multiArr = itemData[str_array[i]];
											elementSelector.val(multiArr);
										}
									}else
									elementSelector.val(itemData[str_array[i]]);
								}
								if($(document).find(context.selectors.modal).find(".mi-tags").length!=0){
									$("#tagsBox").show();
									loadTags();
								}
								if($(document).find(context.selectors.modal).find(".mi-ingress").length!=0){
									$("#ingressBox").show();
									loadIngress();
								}
								if($(document).find(context.selectors.modal).find(".mi-egress").length!=0){
									$("#egressBox").show();
									loadEgress();
								}
								if($(document).find(context.selectors.modal).find(".mi-storage").length!=0){
									$("#storageBox").show();
									loadStorage();
								}								
								$(document).find("#workbook-add-custom-url").removeClass("hidden");
								context.selectors.modal.modal("show");
							}
						},
						error: function (request) {
							//Do Something
						}
					};
					//'fields':itemData.fields,  
					AWS.Utils.ajaxrequest(context.selectors.formUrl + "/" + formName, "post", {'key':itemData.key, 'items':AWS.Workbook.selectors.workbookItemsData.val(), 'items_global':AWS.Workbook.selectors.wobGlobalItemsData.val()}, AWS.Utils.csrf, callback);
				}
			});
		}
	},

	Utils: {
		csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
		toggleClass: function (element, className) {
			if (element.classList) {
				element.classList.toggle(className);
			} else {
				var classes = element.className.split(' ');
				var existingIndex = classes.indexOf(className);

				if (existingIndex >= 0)
					classes.splice(existingIndex, 1);
				else
					classes.push(className);

				element.className = classes.join(' ');
			}
		},
		addClass: function (element, className) {
			if (element.classList)
				element.classList.add(className);
			else
				element.className += ' ' + className;
		},
		removeClass: function (el, className) {
			if (el.classList)
				el.classList.remove(className);
			else
				el.className = el.className.replace(new RegExp('(^|\\b)' + className.split(' ').join('|') + '(\\b|$)', 'gi'), ' ');
		},

		documentReady: function (callback) {
			if (document.attachEvent ? document.readyState === "complete" : document.readyState !== "loading") {
				callback();
			} else {
				document.addEventListener('DOMContentLoaded', callback);
			}
		},

		ajaxrequest: function (url, method, data, csrf, callback) {
			var request = new XMLHttpRequest();
			var loadingIcon = jQuery(".loading");
			if (window.XMLHttpRequest) {
				// code for modern browsers
				request = new XMLHttpRequest();
			} else {
				// code for old IE browsers
				request = new ActiveXObject("Microsoft.XMLHTTP");
			}
			request.open(method, url, true);

			request.onloadstart = function () {
				loadingIcon.show();
			};
			request.onloadend = function () {
				loadingIcon.hide();
			};
			request.setRequestHeader('X-CSRF-TOKEN', csrf);
			request.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
			if ("post" === method.toLowerCase() || "patch" === method.toLowerCase()) {
				request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
				data = this.jsontoformdata(data);
			}

			// when request is in the ready state change the details or perform success function
			request.onreadystatechange = function () {
				if (request.readyState === XMLHttpRequest.DONE) {
					// Everything is good, the response was received.
					request.onload = callback.success(request);
				}
			};
			request.onerror = callback.error;
			request.send(data);
		},

		// This should probably only be used if all JSON elements are strings
		jsontoformdata: function (srcjson) {
			if (typeof srcjson !== "object")
				if (typeof console !== "undefined") {
					return null;
				}
			u = encodeURIComponent;
			var urljson = "";
			var keys = Object.keys(srcjson);
			for (var i = 0; i < keys.length; i++) {
				urljson += u(keys[i]) + "=" + u(srcjson[keys[i]]);
				if (i < (keys.length - 1)) urljson += "&";
			}
			return urljson;
		},

	},

}
