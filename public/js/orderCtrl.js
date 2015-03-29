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
		$scope.orders = JSON.parse(data);
		    	

    });
    
    $scope.itemOpt = {};
    $scope.comboItemOpt = {};
    $scope.initOpts = function(combos) {
	    for (var i in combos) {
	    	var combo = combos[i];
	    	$scope.comboItemOpt[combo.id] = {};
		    for (var j in combo.combo_items) {
			    var comboItem = combos[i].combo_items[j];
			    $scope.comboItemOpt[combo.id][comboItem.id] = {};
			    for (var k in comboItem.item.opts) {
				    var opt = comboItem.item.opts[k];
				    if (comboItem.optStr.indexOf(opt.name) != -1) {
					    $scope.comboItemOpt[combo.id][comboItem.id][opt.id] = true;
				    } else {
					    $scope.comboItemOpt[combo.id][comboItem.id][opt.id] = false;
				    }
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
		for (var comboItemId in $scope.comboItemOpt[id]) {
			var comboItem = $scope.comboItemOpt[id][comboItemId];
			optIds[comboItemId] = [];			
			for (optId in comboItem) {
				if (comboItem[optId]) {
					optIds[comboItemId].push(parseInt(optId));
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
	
	$scope.decreaseOrderItem = function(id) {
		decreaseOrder('item', id);
	}
	$scope.decreaseOrderCombo = function(id) {
		decreaseOrder('combo', id);
	}
	function decreaseOrder(type, id) {
		util.ajax($scope.url + '/api/order/decrease', {
			type : type,
			id : id
		}, null, 'post');
	}
	    
});