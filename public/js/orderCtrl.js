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

app.controller("orderCtrl", function($scope, $compile, socket) {
	$scope.debug = false;
	
    socket.on('orders.update', function (data) {
    	var data = JSON.parse(data);    	
		$scope.myOrder = data.myOrder;
		$scope.otherOrders = data.otherOrders;		
		$scope.statistic = data.statistic;		    	
    });
 
    $scope.iPrice = {};
    $scope.cPrice = {};
    $scope.itemOpt = {};
    $scope.comboItemOpt = {};
    $scope.initStore = function(items, combos) {
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
    
    $scope.orderItem = function(id) {
    	var optIds = [];
    	if (typeof $scope.itemOpt[id] != 'undefined') {
	    	for (var i in $scope.itemOpt[id]) {
		    	if ($scope.itemOpt[id][i]) {
			    	optIds.push(parseInt(i));
		    	}
	    	}	    	
    	}    	
		order('item', id, optIds);
	};
	$scope.orderCombo = function(id) {
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
		order('combo', id, optIds);
	};
	function order(type, id, optIds) {
		util.ajax($scope.url + '/api/order/add', {
			type : type,
			id : id,
			missionId : $scope.missionId,
			optIds : optIds
		}, null, 'post');
	}
	
	$scope.decrementItem = function(orderId, id, optStr) {
		decrementOrder('item', orderId, id, optStr);
	};
	$scope.decrementCombo = function(orderId, id) {
		decrementOrder('combo', orderId, id);
	};
	function decrementOrder(type, orderId, id, optStr) {
		util.ajax($scope.url + '/api/order/decrease', {
			type : type,
			orderId : orderId,
			id : id,
			optStr : optStr
		}, null, 'post');
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
		return price;
	};
	
	$scope.paid = [];
	$scope.editPaid = function(orderId) {		
		if ($scope.paid[orderId] == null) {
			return;
		}
		util.ajax($scope.url + '/api/order/paid', {
			orderId : orderId,
			paid : $scope.paid[orderId]
		}, null, 'post');
	};
	$scope.remark = [];
	$scope.editRemark = function(orderId) {
		util.ajax($scope.url + '/api/order/remark', {
			orderId : orderId,
			remark : $scope.remark[orderId]
		}, null, 'post');
	};
	
	$scope.submitForm = function(url) {
		var form = document.getElementById('dataForm');
		$("#items").val(JSON.stringify($scope.items));
		$("#combos").val(JSON.stringify($scope.combos));
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
	$scope.remove = function($event, objs, obj) {
		objs.splice(objs.indexOf(obj), 1);
		$event.preventDefault();
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
				basePrice : 0,
				baseOptPrice : 0
			});	
		}		
		$event.preventDefault();
	};	
	$scope.setComboModal = function(combo) {
		$scope.editName = combo.name;
		$scope.editPrice = combo.price + combo.basePrice + combo.baseOptPrice;
		
		$scope.editItems = $.extend(true, [], combo.items);
		$scope.tmp = combo;
	};

	$scope.newComboItem = function($event) {
		if ($scope.newComboItemObj) {
			var obj = $.extend(true, {}, $scope.newComboItemObj);
			obj.pivot = {optStr : ""};
			$scope.editItems.push(obj);
		}
		
		$event.preventDefault();
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
	};
	
	$scope.refreshCombos = function() {
		for (var i in $scope.combos) {
			var combo = $scope.combos[i];
			var oriPrice = combo.price + combo.basePrice + combo.baseOptPrice;
			combo.basePrice = 0;
			combo.baseOptPrice = 0;			
			for (var j in combo.items) {
				// refresh item
				var oldItem = $.extend(true, {}, combo.items[j]);
				var trueItem = $.grep($scope.items, function(e) {return e.id == oldItem.id})[0];
				var pivot = $.extend(true, {}, oldItem.pivot);
				if (typeof trueItem == 'undefined') {
					// has been removed
					delete combo.items[j];
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
						optStr += opt.name;
						optPrice += opt.price;
					}
				}
				combo.items[j].pivot.optStr = optStr;
				combo.items[j].pivot.optPrice = optPrice;				
				// refresh combo
				combo.basePrice += combo.items[j].price;
				combo.baseOptPrice += optPrice;
			}
			combo.price = oriPrice - combo.basePrice - combo.baseOptPrice;
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
});
