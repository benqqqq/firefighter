var app = angular.module('orderApp', ['btford.socket-io']);

app.config(function($interpolateProvider) {
	$interpolateProvider.startSymbol('{[{');
	$interpolateProvider.endSymbol('}]}');
});

app.factory('socket', function ($rootScope) {
    var socket = io.connect(host + ':3000/');
    return {
        on: function (eventName, callback) {
            socket.on(eventName, function () {
                var args = arguments;
                $rootScope.$apply(function () {
                    callback.apply(socket, args);
                });
            });
        },
        emit: function (eventName, data, callback) {
            socket.emit(eventName, data, function () {
                var args = arguments;
                $rootScope.$apply(function () {
                    if (callback) {
                        callback.apply(socket, args);
                    }
                });
            })
        }
    };
});

app.controller("orderCtrl", function($scope, socket) {
	$scope.debug = false;
	
    socket.on('orders.update', function (data) {
    	var data = JSON.parse(data);    	
		$scope.orders = data.orders;
		$scope.refreshOrders();
		$scope.statistic = data.statistic;
		
		removeLoadingIcon();
		releaseUserMenuEvent();		
    });
	
	socket.on('mission.end', function (data) {
		var data = JSON.parse(data);
		if (data.missionId == $scope.missionId) {
			$('#message').html('即將在' + data.time + '分鐘後結束訂購');
			$('#messageModal').modal();	
		}
		
	});
	
    $scope.iPrice = {};
    $scope.cPrice = {};
    $scope.itemOpt = {};
    $scope.comboItemOpt = {};
    $scope.initStore = function(items, combos) {
    	$scope.items = items;
    	for (var i in items) {
	    	var item = items[i];
	    	$scope.iPrice[item.id] = item.price;
	    	$scope.itemOpt[item.id] = {};	    	
	    	for (var j in item.opts) {
		    	var opt = item.opts[j];
		    	$scope.itemOpt[item.id][opt.id] = (item.optStr.indexOf(opt.name) != -1);		    	
		    	$scope.initItemPrice(item.id, opt.id, opt.price);
	    	}
    	}
	    for (var i in combos) {
	    	var combo = combos[i];
	    	$scope.cPrice[combo.id] = combo.price;
	    	$scope.comboItemOpt[combo.id] = {};
		    for (var j in combo.items) {
			    var item = combos[i].items[j];
			    $scope.cPrice[combo.id] += item.price;
			    $scope.comboItemOpt[combo.id][item.id] = {};
			    for (var k in item.opts) {
				    var opt = item.opts[k];
				    $scope.comboItemOpt[combo.id][item.id][opt.id] = (item.pivot.optStr.indexOf(opt.name) != -1);
				    $scope.initComboPrice(combo.id, item.id, opt.id, opt.price);
			    }
		    }
	    }
    };
    
    $scope.orderItem = function(id, popTarget) {
    	var optIds = [];
    	if (typeof $scope.itemOpt[id] != 'undefined') {
	    	for (var i in $scope.itemOpt[id]) {
		    	if ($scope.itemOpt[id][i]) {
			    	optIds.push(parseInt(i));
		    	}
	    	}	    	
    	}    	
		order('item', id, optIds, popTarget);		
	};
	$scope.orderCombo = function(id, popTarget) {
		var optIds = {};
		for (var itemId in $scope.comboItemOpt[id]) {
			var item = $scope.comboItemOpt[id][itemId];
			optIds[itemId] = [];			
			for (optId in item) {
				if (item[optId]) {
					optIds[itemId].push(parseInt(optId));
				}				
			}
		}	
		order('combo', id, optIds, popTarget);		
	};
	
	function order(type, id, optIds, popTarget) {
		if ($scope.orderIsSent) {
			return;
		}
	
		var userId = ($scope.user) ? $scope.user.id : null;
		storeUserMenuEvent(popTarget);
		addLoadingIcon(popTarget);
		
		$.ajax({
			url : $scope.url + '/api/order/add',
			dataType : 'html',
			data:  {
				type : type,
				id : id,
				missionId : $scope.missionId,
				optIds : optIds,
				userId : userId
			},
			async : true,
			type: 'post',
			success : function(data) {
				if (data) {
					$('#message').html(data);
					$('#messageModal').modal();					
				}
			}
		});
	}
	
	$scope.orderIsSent = false;
	function storeUserMenuEvent(popTarget) {
		$scope.orderIsSent = true;
		$scope.popTarget = popTarget;
	}
	function releaseUserMenuEvent() {
		if ($scope.orderIsSent) {
			renewPopContent($scope.popTarget);
			$scope.orderIsSent = false;
		}
	}
	
	function addLoadingIcon(obj) {
		var loadingIcon = '<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>';
		$(obj).append(loadingIcon);
	}
	function removeLoadingIcon() {
		$('.glyphicon-refresh-animate').remove();
	}
	
	$scope.decrementItem = function(orderId, id, optStr) {
		decrementOrder('item', orderId, id, optStr);
	};
	$scope.decrementCombo = function(orderId, id) {
		decrementOrder('combo', orderId, id);
	};
	function decrementOrder(type, orderId, id, optStr) {
		var userId = ($scope.user) ? $scope.user.id : null;
		$.ajax({
			url : $scope.url + '/api/order/decrease', 
			dataType : 'html',
			data : {
				type : type,
				orderId : orderId,
				id : id,
				optStr : optStr,
				userId : userId,
				}, 
			success : function(data) {
				if (data) {
					$('#message').html(data);
					$('#messageModal').modal();					
				}
			}, 
			type: 'post'
		});
	};
	
	$scope.initItemPrice = function(itemId, optId, optPrice) {
		$scope.iPrice[itemId] += ($scope.itemOpt[itemId][optId]) ? optPrice : 0;
	};
	
	$scope.changeItemPrice = function(itemId, optId, optPrice) {
		$scope.iPrice[itemId] += ($scope.itemOpt[itemId][optId]) ? optPrice : -1 * optPrice;
	};
	
	$scope.initComboPrice = function(comboId, itemId, optId, optPrice) {
		$scope.cPrice[comboId] += ($scope.comboItemOpt[comboId][itemId][optId]) ? optPrice : 0;
	};
	
	$scope.changeComboPrice = function(comboId, itemId, optId, optPrice) {
		$scope.cPrice[comboId] += ($scope.comboItemOpt[comboId][itemId][optId]) ? optPrice : -1 * optPrice;
	};
	
	$scope.getOrderPrice = function(order) {
		var price = 0;		
		for (var i in order.items) {
			var item = order.items[i];
			price += (item.price + item.pivot.optPrice) * item.pivot.quantity;
		}
		for (var i in order.order_combos) {
			var orderCombo = order.order_combos[i];
			price += (orderCombo.combo.basePrice + orderCombo.combo.price + orderCombo.optPrice) * orderCombo.quantity;
		}
		price += order.deviation;
		return price;
	};
	
	$scope.editPaid = function(order) {		
		if (order.paid == null) {
			return;
		}
		$.ajax({
			url : $scope.url + '/api/order/paid',
			dataType : 'html',
			data: {
				orderId : order.id,
				paid : order.paid
			},
			async : true,
			type: 'post',
			success : function() {
				flashPop('.pop-input-paid');
			},
		});
	};
	
	$scope.editRemark = function(order) {
		$.ajax({
			url : $scope.url + '/api/order/remark',
			dataType : 'html',
			data: {
				orderId : order.id,
				remark : order.remark
			},
			async : true,
			type: 'post',
			success : function() {
				flashPop('.pop-input-remark');
			},
		});			

	};
	
	$scope.editDeviation = function(order) {		
		if (order.deviation == null) {
			return;
		}
		$.ajax({
			url : $scope.url + '/api/order/deviation',
			dataType : 'html',
			data: {
				orderId : order.id,
				deviation : order.deviation
			},
			async : true,
			type: 'post',
			success : function() {
				flashPop('.pop-input-deviation');
			},
		});
	};


	$scope.submitForm = function(url) {
		var form = document.getElementById('dataForm');
		$("#items").val(JSON.stringify($scope.items));
		$("#combos").val(JSON.stringify($scope.combos));
		$("#categories").val(JSON.stringify($scope.categories));
		form.action = url;
		form.submit();			
	};
	
	var newItemId = 0;
	$scope.newItem = function($event) {
		if ($scope.newItemName != "" && typeof $scope.newItemPrice == "number") {
			$scope.items.push({
				id : -1,
				name : $scope.newItemName,
				price : $scope.newItemPrice,
				isOrderable : true,
				newItemId : newItemId++
			});	
		}		
		$event.preventDefault();
	};
	$scope.setItemModal = function(item) {
		$scope.editName = item.name;
		$scope.editPrice = item.price;		
		$scope.tmp = item;
		$scope.editOpts = $.extend(true, [], item.opts);
		$scope.editOptStr = item.optStr;
	};
	
	$scope.defaultOpt = [];
	$scope.doSetModal = function() {
		var item = $scope.tmp;
		item.name = $scope.editName;
		item.price = $scope.editPrice;		
		item.opts = $scope.editOpts;
		
		var optStr = '';
		var optPrice = 0;
		for (var i in item.opts) {
			if ($scope.defaultOpt[i]) {
				var opt = item.opts[i];
				optStr += opt.name + " ";
				optPrice += opt.price;
			}
		}
		item.optStr = optStr;
		item.optPrice = optPrice;
		
		$scope.refreshCombos();
	};
	$scope.newOpt = function($event) {
		if ($scope.newOptName != "" && typeof $scope.newOptPrice == "number") {
			$scope.editOpts.push({
				id : -1,
				name : $scope.newOptName,
				price : $scope.newOptPrice
			});
		}
		$event.preventDefault();
	};
	$scope.remove = function(objs, obj) {
		objs.splice(objs.indexOf(obj), 1);
	};

	$scope.copyItem = function(item) {
		var newItem = $.extend(true, {}, item);
		delete newItem.$$hashKey;
		newItem.id = -1;
		newItem.newItemId = newItemId++;
		$scope.items.push(newItem);
	};
	
	$scope.copyCombo = function(combo) {
		var newCombo = $.extend(true, {}, combo);
		delete newCombo.$$hashKey;
		newCombo.id = -1;
		$scope.combos.push(newCombo);
	};

	$scope.isInStr = function(optStr, name) {
		return optStr.indexOf(name) != -1;	
	};
	
	$scope.newCombo = function($event) {
		if ($scope.newComboName != "" && typeof $scope.newComboPrice == "number") {
			
			$scope.combos.push({
				id : -1,
				name : $scope.newComboName,
				price : $scope.newComboPrice,
				editPrice : $scope.newComboPrice,
				basePrice : 0,
				baseOptPrice : 0
			});	
		}		
		$event.preventDefault();
	};	
	$scope.setComboModal = function(combo) {
		$scope.editName = combo.name;
		$scope.editPrice = combo.editPrice;
		
		$scope.editItems = $.extend(true, [], combo.items);
		$scope.tmp = combo;
	};
	
	$scope.$watch('newComboItemObj', function(oldValue, newValue) {
		$scope.newComboItem();
	});
	$scope.newComboItem = function() {
		// This function also be used for newCategoryItem (Logic are the same)
		if ($scope.newComboItemObj) {
			var isExist = ($.grep($scope.editItems, function(e) { 
				if (e.id != -1) {
					return e.id == $scope.newComboItemObj.id 	
				} else {
					return e.newItemId == $scope.newComboItemObj.newItemId;
				}				
			}).length > 0);
			if (!isExist) {
				var obj = $.extend(true, {}, $scope.newComboItemObj);
				obj.pivot = {optStr : ""};
				$scope.editItems.push(obj);	
			}			
		}		
	}
	$scope.defaultComboOpt = [];
	$scope.doSetComboModal = function() {
		var combo = $scope.tmp;
		combo.name = $scope.editName;		
		combo.items = $scope.editItems;
		
		combo.basePrice = 0;
		combo.baseOptPrice = 0;
		for (var i in combo.items) {
			var item = combo.items[i];
			var optStr = '';
			var optPrice = 0;
			for (var j in item.opts) {
				if ($scope.defaultComboOpt[i][j]) {
					var opt = item.opts[j];
					optStr += opt.name + " ";
					optPrice += opt.price;
				}
			}
			item.pivot.optStr = optStr;
			item.pivot.optPrice = optPrice;
			combo.basePrice += item.price;
			combo.baseOptPrice += optPrice;
		}
		combo.price = $scope.editPrice - combo.basePrice - combo.baseOptPrice;
		combo.editPrice = $scope.editPrice;
	};
	
	$scope.refreshComboPrice = function(combo) {
		combo.price = combo.editPrice - combo.basePrice - combo.baseOptPrice;
	};
	
	$scope.refreshCombos = function() {
		for (var i in $scope.combos) {
			var combo = $scope.combos[i];
			combo.basePrice = 0;
			combo.baseOptPrice = 0;			
			for (var j in combo.items) {
				// refresh item
				var oldItem = $.extend(true, {}, combo.items[j]);
				var trueItem = $.grep($scope.items, function(e) {return e.id == oldItem.id})[0];
				var pivot = $.extend(true, {}, oldItem.pivot);
				if (typeof trueItem == 'undefined') {
					// has been removed
					combo.items.splice(j, 1);
					continue;					
				}
				
				combo.items[j] = $.extend(true, {}, trueItem);
				combo.items[j].pivot = pivot;
				
				// refresh optStr, optPrice
				var optIds = getOptIdsInOptStr(oldItem);
				var optStr = '';
				var optPrice = 0;
				for (var k in combo.items[j].opts) {
					var opt = combo.items[j].opts[k];
					if (optIds.indexOf(opt.id) != -1) {
						optStr += opt.name + " ";
						optPrice += opt.price;
					}
				}
				combo.items[j].pivot.optStr = optStr;
				combo.items[j].pivot.optPrice = optPrice;				
				// refresh combo
				combo.basePrice += combo.items[j].price;
				combo.baseOptPrice += optPrice;
			}
			combo.price = combo.editPrice - combo.basePrice - combo.baseOptPrice;
		}
	}
	function getOptIdsInOptStr(item) {
		var optIds = [];
		for (var i in item.opts) {
			var opt = item.opts[i];
			if (item.pivot.optStr.indexOf(opt.name) != -1) {
				optIds.push(opt.id);
			}
		}
		return optIds;
	}
	
	$scope.storeUser = function() {
		if ($scope.user) {
			$.cookie('user', $scope.user.id, {path : '/order'});			
		}
	};
	$scope.loadUser = function() {
		return $.grep($scope.users, function(e) {return e.id == $.cookie('user')})[0];
	};
	
	
	$scope.refreshOrders = function() {
		if (!$scope.orders) {
			return;
		}
		if ($scope.user) {
			$scope.myOrder = $.grep($scope.orders, function(e) { return e.user_id == $scope.user.id });
			$scope.otherOrders = $.grep($scope.orders, function(e) { return e.user_id != $scope.user.id });	
		} else {
			$scope.myOrder = [];
			$scope.otherOrders = $scope.orders;
		}		
	}
	
	$scope.newCategory = function($event) {
		if ($scope.newCategoryName != "") {
			$scope.categories.push({
				id : -1,
				name : $scope.newCategoryName,
			});
		}		
		$event.preventDefault();
	};
	
	$scope.setCategoryModal = function(category) {
		$scope.editName = category.name;
		
		$scope.editItems = $.extend(true, [], category.items);
		$scope.tmp = category;
	};
	
	$scope.doSetCategoryModal = function() {
		var category = $scope.tmp;
		category.name = $scope.editName;		
		category.items = $scope.editItems;
	};

	
	$scope.editUser = function(user) {
		$scope.user = user;
		$scope.storeUser();
		$scope.refreshOrders();
	};
	
	$scope.categoryIsShow = {};
	$scope.categoryToggle = function(id) {		
		var layout = function() {
			$('.menu').isotope('layout');	
		};
	
		if ($scope.categoryIsShow[id]) {
			$('.menu-item-content-' + id).slideUp(layout);	
		} else {
			$('.menu-item-content-' + id).slideDown(layout);	
		}	
		$scope.categoryIsShow[id] = !$scope.categoryIsShow[id];			
	};
		
	function flashPop(target, time, opt) {
		var time = time | 500;
		if (opt) {
			$(target).popover(opt);
		} else {
			$(target).popover({
				trigger : 'manual'
			});
		}
		$(target).popover('show');
		setTimeout(function () {
			$(target).popover('hide');
		}, time);
	}
	
	function renewPopContent(target) {		
		flashPop(target, 3000, {
			trigger : 'manual',
			placement : 'top',
			title : '我的訂單',
			content : function() {
				var menu = '';
				for (var i in $scope.myOrder[0].items) {
					var item = $scope.myOrder[0].items[i];
					menu += "<p>" + item.name;
					menu += (item.pivot.optStr != ' ')? "<span class='badge'>" + item.pivot.optStr + "</span>" : '';
					menu += " * " + item.pivot.quantity + " = <span class='label label-primary'>"
								+ (item.price + item.pivot.optPrice) * item.pivot.quantity  + "$</span></p>";
				}
				for (var i in $scope.myOrder[0].order_combos) {
					var orderCombo = $scope.myOrder[0].order_combos[i];
					menu += "<p>" + orderCombo.combo.name + "(";
					for (var j in orderCombo.items) {
						var item = orderCombo.items[j];
						menu += item.name;
						menu += (item.pivot.optStr != ' ')? "<span class='badge'>" + item.pivot.optStr + "</span>" : '';
					}
					menu += ") * " + orderCombo.quantity + " = <span class='label label-primary'>"
							+ (orderCombo.combo.basePrice + orderCombo.combo.price + orderCombo.optPrice) * orderCombo.quantity + "$</span>";
				}
				return menu;
			}
		});
	}

	$scope.changeMissionStatus = function(obj) {		
		$.ajax({
			url : $scope.url + '/api/order/mission/' + $scope.missionId + '/status',
			dataType : 'html',
			data:  {								
				isEnding : !obj.checked
			},			
			type: 'post',
			success: function(data) {
				if (data) {
					$('#message').html(data);
					$('#messageModal').modal();					
				}
			}
		});				
	};
	$scope.mySortFunc = function(user) {
		if (isNaN(parseInt(user.serial))) {
			return 1000 + user.serial.charCodeAt(0);
		} else {
			return parseInt(user.serial);
		}
	};
	
	var insertInfo = {
		isInserting : false,
		category : null
	};
	$scope.startInsertItem = function(category) {
		if (!insertInfo.isInserting) {
			$('#categoryBtn-' + category.id).removeClass('btn-primary').addClass('btn-success');
			insertInfo.isInserting = true;
			insertInfo.category = category;			
			$('#item-table').removeClass('table-striped').addClass('table-hover');
			$('.itemRow').css('cursor', 'pointer');
		} else {
			$('#categoryBtn-' + category.id).removeClass('btn-success').addClass('btn-primary');	
			insertInfo.isInserting = false;			
			$('#item-table').removeClass('table-hover').addClass('table-striped');
			$('.itemRow').css('cursor', 'auto');
		}
	};
	$scope.insertItemToCategory = function(item) {		
		if (!insertInfo.isInserting) {
			return;
		}
		var category = insertInfo.category;
		var isExist = ($.grep(category.items, function(e) { 
			if (e.id != -1) {
				return e.id == item.id 	
			} else {
				return e.newItemId == item.newItemId;
			}				
		}).length > 0);
		if (!isExist) {
			var obj = $.extend(true, {}, item);
			obj.pivot = {optStr : ""};
			category.items.push(obj);
		}
	};
	
	copyInfo = {
		isCopying : false,
		item : null
	};
	$scope.startCopyItem = function(item) {
		if (!copyInfo.isCopying) {
			$('#itemBtn-' + item.id).removeClass('btn-primary').addClass('btn-success');
			copyInfo.isCopying = true;
			copyInfo.item = item;			
			$('#item-table').removeClass('table-striped').addClass('table-hover');
			$('.itemRow').css('cursor', 'pointer');
		} else {
			$('#itemBtn-' + item.id).removeClass('btn-success').addClass('btn-primary');	
			copyInfo.isCopying = false;			
			$('#item-table').removeClass('table-hover').addClass('table-striped');
			$('.itemRow').css('cursor', 'auto');
		}
	};
	$scope.copyItemTo = function(item) {		
		if (!copyInfo.isCopying) {
			return;
		}
		item.opts = $.extend(true, [], copyInfo.item.opts);
		item.optPrice = copyInfo.item.optPrice;
		item.optStr = copyInfo.item.optStr;
		for (var i in item.opts) {
			item.opts[i].id = -1;
		}
	};
	
	$('.optModal').on('hidden.bs.modal', function() {
 		var target = $('[data-target="#' +  $(this).attr('id') + '"]').parent('a'); 
		flashPop(target, 3000, {
			trigger : 'manual',
			placement : 'top',
			title : '注意 !',
			content : '如果您更改了選項，記得要點品項名稱才會新增到訂單'
		})
	});
	
	if (!window.mobileAndTabletcheck()) {
		$('.order-btn').popover({
			trigger : 'hover',
			title : '所有選項',
			html : true,
			placement : 'bottom',
			content : function() {
				var id = $(this).attr('data-item-id');
				var item = $.grep($scope.items, function(e) {
					return e.id == id;
				})[0];
				var html = '';
				for (var i in item.opts) {
					var opt = item.opts[i];
					var plusSign = (opt.price >= 0)? '+' : '';
					html += '<p>' + opt.name;
					html += ' <span class="label label-primary">' + plusSign + opt.price + '$</span>';
					html += '</p>';
				}
				return html;
			}
		});
	}
	
	$scope.endMission = function() {
		$.ajax({
			url : $scope.url + '/api/order/mission/' + $scope.missionId + '/end',
			data : {
				time : $scope.endMissionTime
			},
			type: 'post'			
		});
	}
});