var app = angular.module('orderApp', ['btford.socket-io']);

app.factory('socket', function ($rootScope) {
    var socket = io.connect('http://127.0.0.1:3000/');
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
	
    socket.on('orders.update', function (data) {
    	var data = JSON.parse(data);
		$scope.orders = data.orders;
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
			    $scope.comboItemOpt[combo.id][item.id] = {};
			    for (var k in item.opts) {
				    var opt = item.opts[k];
				    $scope.comboItemOpt[combo.id][item.id][opt.id]= (item.pivot.optStr.indexOf(opt.name) != -1);
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
	};
	
	$scope.decrementItem = function(orderId, id, optStr) {
		decrementOrder('item', orderId, id, optStr);
	}
	$scope.decrementCombo = function(orderId, id) {
		decrementOrder('combo', orderId, id);
	}
	function decrementOrder(type, orderId, id, optStr) {
		util.ajax($scope.url + '/api/order/decrease', {
			type : type,
			orderId : orderId,
			id : id,
			optStr : optStr
		}, null, 'post');
	}
	
	$scope.initItemPrice = function(itemId, optId, optPrice) {
		$scope.iPrice[itemId] += ($scope.itemOpt[itemId][optId]) ? optPrice : 0;
	}
	
	$scope.changeItemPrice = function(itemId, optId, optPrice) {
		$scope.iPrice[itemId] += ($scope.itemOpt[itemId][optId]) ? optPrice : -1 * optPrice;
	}
	
	$scope.initComboPrice = function(comboId, itemId, optId, optPrice) {
		$scope.cPrice[comboId] += ($scope.comboItemOpt[comboId][itemId][optId]) ? optPrice : 0;
	}
	
	$scope.changeComboPrice = function(comboId, itemId, optId, optPrice) {
		$scope.cPrice[comboId] += ($scope.comboItemOpt[comboId][itemId][optId]) ? optPrice : -1 * optPrice;
	}
	
	$scope.getOrderPrice = function(order) {
		var price = 0;
		for (var i in order.items) {
			var item = order.items[i];
			price += (item.price + item.pivot.optPrice) * item.pivot.quantity;
		}
		for (var i in order.order_combos) {
			var orderCombo = order.order_combos[i];
			price += (orderCombo.combo.price + orderCombo.optPrice) * orderCombo.quantity;
		}
		return price;
	}
	
	$scope.paid = [];
	$scope.editPaid = function(orderId) {		
		if ($scope.paid[orderId] == null) {
			return;
		}
		util.ajax($scope.url + '/api/order/paid', {
			orderId : orderId,
			paid : $scope.paid[orderId]
		}, null, 'post');
	}
	$scope.remark = [];
	$scope.editRemark = function(orderId) {
		util.ajax($scope.url + '/api/order/remark', {
			orderId : orderId,
			remark : $scope.remark[orderId]
		}, null, 'post');
	}
});