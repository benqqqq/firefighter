var app = angular.module("workApp");

app.controller("dateCtrl", function($scope) {
	var today = new Date();
	$scope.dateY = today.getFullYear() - 1911;
	$scope.dateM = today.getMonth() + 1;
	$scope.dateD = today.getDate();
});