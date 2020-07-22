var Global_WB = {

	Workbook: {
		selectors: {
			workbookItemContainer: jQuery("#wb-global-items"),
			workbookItemsData: jQuery(".wb-global-items-field"),
			addCustomUrlButton: jQuery(".show-modal"),
			modal: jQuery("#showWorkbookModal"),
			document: jQuery("document"),
			addCustomUrlForm: "#wb-global-edit-attr",
			addModuleToWorkbookButton: ".add-module-to-wb-global",
			removeWorkbookItemButton: "#wb-global-items .remove-workbook-item",
			editWorkbookItemButton: "#wb-global-items  .edit-workbook-item",
			formUrl: "",
			wbGlobalEditAttr:"wb-global-edit-attr",
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
						foundItem = Global_WB.Workbook.methods.findItemById(childItem, id);
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
				
				Global_WB.Workbook.selectors.workbookItemContainer.nestable('add', $.extend({
					"id": Global_WB.Workbook.methods.getNewId(Global_WB.Workbook.selectors.workbookItemsData.val()),
					"key": obj.key,
					"content": obj.name+(objData.rc_name?' ('+objData.rc_name+')':''),
					"name": obj.name,
				//	"fields": obj.fields,
					"url": obj.url,
					"url_type": obj.url_type,
					"open_in_new_tab": obj.open_in_new_tab,
				}, objData));
				Global_WB.Workbook.selectors.workbookItemsData.val(
					JSON.stringify(
						Global_WB.Workbook.selectors.workbookItemContainer.nestable('serialise')
					)
				);
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
					"url": obj.url,
					"url_type": obj.url_type,
					"open_in_new_tab": obj.open_in_new_tab,
				};
				newObject = $.extend(objData, newObject);
				
				var workbookItems = Global_WB.Workbook.selectors.workbookItemContainer.nestable('serialise');
				var itemData;
				$.each(workbookItems, function (index, item) {
					itemData = Global_WB.Workbook.methods.findItemById(item, id);
					if (itemData) {
						return false;
					}
				});
				if (itemData.children) {
					newObject.children = itemData.children;
				}

				Global_WB.Workbook.selectors.workbookItemContainer.nestable('replace', newObject);

				Global_WB.Workbook.selectors.workbookItemsData.val(
					JSON.stringify(
						Global_WB.Workbook.selectors.workbookItemContainer.nestable('serialise')
					)
				);
			}
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
				Global_WB.Utils.ajaxrequest(context.selectors.formUrl + "/" + formName, "get", {}, Global_WB.Utils.csrf, callback);
			});

			jQuery(document).on("submit", context.selectors.addCustomUrlForm, function (e) {
				e.preventDefault();
				var formData = jQuery(this).serializeArray().reduce(function (obj, item) {
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
			});

			jQuery(document).on("click", context.selectors.addModuleToWorkbookButton, function () {
				var dataObj = {
					id: $(this).attr("data-id"),
					key: $(this).attr("data-key"),
					name: $(this).attr("data-name"),
					fields: $(this).attr("data-fields"),
					url: $(this).attr("data-url"),
					url_type: $(this).attr("data-url_type"),
					open_in_new_tab: $(this).attr("data-open_in_new_tab"),
				};
				context.methods.addWorkbookItem(dataObj);
			});

			jQuery(document).on("click", context.selectors.removeWorkbookItemButton, function () {
				context.selectors.workbookItemContainer.nestable('remove', jQuery(this).parents(".dd-item").first().attr("data-id"));
				Global_WB.Workbook.selectors.workbookItemsData.val(
					JSON.stringify(
						Global_WB.Workbook.selectors.workbookItemContainer.nestable('serialise')
					)
				);
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
								$(document).find(context.selectors.modal).find(".mi-url").val(itemData.url);
								$(document).find(context.selectors.modal).find(".mi-url_type_" + itemData.url_type).prop("checked", true);
								if (itemData.open_in_new_tab == 1) {
									$(document).find(context.selectors.modal).find(".mi-open_in_new_tab").prop("checked", true);
								}

								var str_array = $(document).find(context.selectors.modal).find(".mi-fields_name").val().split(',');
								for(var i = 0; i < str_array.length; i++) {
									$(document).find(context.selectors.modal).find(".mi-"+str_array[i]).val(itemData[str_array[i]]);
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
								
								$(document).find("#"+Global_WB.Workbook.selectors.wbGlobalEditAttr).removeClass("hidden");
								context.selectors.modal.modal("show");
							}
						},
						error: function (request) {
							//Do Something
						}
					};

					//'fields':itemData.fields, 
					Global_WB.Utils.ajaxrequest(context.selectors.formUrl + "/" + formName, "post", {'key':itemData.key, 'items':Global_WB.Workbook.selectors.workbookItemsData.val(), 'form':Global_WB.Workbook.selectors.wbGlobalEditAttr}, Global_WB.Utils.csrf, callback);
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
