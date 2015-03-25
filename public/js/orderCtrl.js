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
	$scope.orders = '123';
	
    socket.on('orders.update', function (data) {
		$scope.orders = data;
    	
/*         $scope.users=JSON.parse(data); */
    });
});