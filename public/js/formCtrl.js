var attandArticle = "第一梯次\\n\
信二52車 　指2    司14     瞄7     副18    救19    鑑\\n\
信二61車 　指3    司5      瞄8     副\\n\
信二91車 　司6    副17.D\\n\
第二梯次\\n\
信二32 51 71車 指　 司15   副B\\n\
第三梯次 92車\\n\
分隊通訊 A.E";
var memoArticle = "空氣複合瓶 可使用量21支  52車複合瓶6支 61車複合瓶8支 71車複合瓶7支\\n\
救生艇2艘 橡皮艇2艘  分隊通訊22時後燈火管制\\n\
20號公假(警大受訓)";


var app = angular.module("workApp", ['ngDragDrop']);

app.controller("formCtrl", function($scope, $http) {
	$scope.mapping = [
		{ name : '值班', id : 1, css : 'bck-green', str : '值'},
		{ name : '救護', id : 9, css : 'bck-red', str : '救' },
		{ name : '備勤', id : 10, css : 'bck-gray', str : '備'},
		{ name : '待命服勤', id : 11, css : 'bck-lightGray', str : '待'},
		{ name : '車輛裝備器材保養', id : 6, css : 'bck-orange', str : '車', alias : ['91W', '試車', '試水', '試梯', '其他裝備器材保養']},
		{ name : '消防安全檢查', id : 2, css : 'bck-olive', str : '安', 
			alias : ['消防安全設備', '檢修申報', '防火管理', '防燄規制', '會勘', '驗證', '危險物品管理', '市府稽查']},
		{ name : '防災宣導', id : 3, css : 'bck-navy', str : '宣', alias : ['防火', '防溺', '防CO', '防縱火巡邏']},
		{ name : '水源調查', id : 4, css : 'bck-blue', str : '水'},
		{ name : '搶救演練', id : 5, css : 'bck-maroon', str : '搶', 
			alias : ['救護情境演練( )', '防災演練', '體技能訓練', '裝備器材操作訓練', '其他應變演習及會議']},
		{ name : '其他勤務(在隊)', id : 7, css : 'bck-black', str : '在'},
		{ name : '其他勤務(離隊)', id : 8, css : 'bck-black', str : '離'},
		{ name : '不在隊', id : -1, css : 'bck-white', str : '-'}
	];
	var us = ['A', 'B', 'C', 'D', 'E'];
	
	
	$scope.changeWorkType = function(index) {
		$scope.workType = index;
		$scope.isFocus = [];
		$scope.isFocus[index] = 'focus';
	};
	
	$scope.dropASerial = function() {			
		$scope.serials.push($scope.dropSerial);
		if ($scope.rest1.indexOf($scope.dropSerial) == -1
			&& $scope.rest2.indexOf($scope.dropSerial == -1)) {
			$scope.initBtn($scope.dropSerial);				
		}		
		delete $scope.dropSerial;
		deleteAllNull();
	};
	
	$scope.dropARest1 = function() {
		if ($scope.rest1.indexOf($scope.dropRest1) == -1) {
			$scope.rest1.push($scope.dropRest1);				
		} else {
			$scope.rest1Class[$scope.dropRest1] = null;
		}
		deleteRest2Duplicate($scope.dropRest1);
		delete $scope.dropRest1;
		deleteAllNull();
	};	
	$scope.dropARest2 = function() {	//外宿
		if ($scope.rest2.indexOf($scope.dropRest2) == -1) {
			$scope.rest2.push($scope.dropRest2);				
		}		
		$scope.serials.push($scope.dropRest2);	
		initBtnWithTime($scope.dropRest2, 8, 17, true);		
		deleteRest1Duplicate($scope.dropRest2);
		delete $scope.dropRest2;
		deleteAllNull();
	};
	$scope.dropARest3 = function() {
		$scope.rest3.push($scope.dropRest3);
		deleteRest1Duplicate($scope.dropRest3);
		deleteRest2Duplicate($scope.dropRest3);
		delete $scope.dropRest3;
		deleteAllNull();
	};
	$scope.dropARest4 = function() {
		$scope.rest4.push($scope.dropRest4);
		deleteRest1Duplicate($scope.dropRest4);
		deleteRest2Duplicate($scope.dropRest4);
		delete $scope.dropRest4;
		deleteAllNull();
	};
	$scope.dropARest5 = function() {
		$scope.rest5.push($scope.dropRest5);
		deleteRest1Duplicate($scope.dropRest5);
		deleteRest2Duplicate($scope.dropRest5);
		delete $scope.dropRest5;
		deleteAllNull();
	};
	$scope.dropARest6 = function() {
		$scope.rest6.push($scope.dropRest6);
		deleteRest1Duplicate($scope.dropRest6);
		deleteRest2Duplicate($scope.dropRest6);
		delete $scope.dropRest6;
		deleteAllNull();
	};
	
	$scope.isInRest2 = function(key) {
		if (isInt(key)) {
			return $scope.rest2.indexOf(parseInt(key)) != -1;	
		} else {
			return $scope.rest2.indexOf(key) != -1;
		}
		
	}
	
	init();		    			
	
	
	function init() {
		createTimes();		
		createInfoStorage();
		insertMembers();		
		createBtnsStorage();
		$scope.changeWorkType(0);
	}
	
	function createTimes() {
		$scope.times = [];
		for (var i = 8; i < 24 + 8; ++i) {
			$scope.times.push(i % 24);
		}
	}
	
	function createInfoStorage() {
		$scope.serials = [];
		$scope.rest1 = [];
		$scope.rest2 = [];
		$scope.rest3 = [];
		$scope.rest4 = [];
		$scope.rest5 = [];
		$scope.rest6 = [];
		$scope.rest1Class = {};
		$scope.analysis = {
			usTimes : {'A' : 0, 'B' : 0, 'C' : 0, 'D' : 0, 'E' : 0},
			memberTimes : {}
		};
		for (var i = 1; i < 21; ++i) {
			$scope.analysis.memberTimes[i] = 0;
		}
	}
	
	function insertMembers() {
		for (var i = 1; i < 21; ++i) {
			$scope.rest1.push(i);
		}
		$scope.rest1 = $scope.rest1.concat(us);
	}
	
	function createBtnsStorage() {		
		$scope.btnCss = {};
		$scope.btnWorkId = {};
		$scope.btnStr = {};
		for (time in $scope.times) {			
			$scope.btnCss[time] = {};
			$scope.btnWorkId[time] = {};				
			$scope.btnStr[time] = {};
		}	
	}

	function deleteRest1Duplicate(serial) {
		var i = $scope.rest1.indexOf(serial);
		if (i != -1) {
			$scope.rest1.splice(i, 1);
			$scope.rest1Class[serial] = null;
		}
	}
	
	function deleteRest2Duplicate(serial) {
		var i = $scope.rest2.indexOf(serial);
		if (i != -1) {
			$scope.rest2.splice(i, 1);
		}
	}
	
	function deleteAllNull() {
		deleteNull($scope.rest1);
		deleteNull($scope.rest2);
		deleteNull($scope.rest3);
		deleteNull($scope.rest4);
		deleteNull($scope.rest5);
		deleteNull($scope.rest6);
		deleteNull($scope.serials);
	}

	function deleteNull(arr) {
		for (var i = 0; i < arr.length; ++i) {
			if (arr[i] == null) {
				arr.splice(i, 1);
				--i;
			}
		}
		return arr;
	}
	
	$scope.initBtn = function(serial) {
		initBtnWithTime(serial, 0, 23, true);
	};

	function initBtnWithTime(serial, start, end, restart) {	
		for (i in $scope.times) {
			var time = $scope.times[i];
			if (restart) {
				putToNone(time, serial);	
			}			
			if (time >= start && time <= end) {
				putToRest(time, serial);
				putToCar(time, serial);	
			}
		}
	}
	
	function putToRest(time, serial) {
		var workType = (belongsToStandBy(time, serial)) ? 2 : 3;	
		addWork(workType, time, serial);
	}
	
	function belongsToStandBy(time, serial) {
		if (!isInt(serial)) {
			return false;	
		} else if ((time > 6 && time < 12) || (time > 13 && time < 18)) {
			return true;
		} else {
			return false;
		}
	}
	
	function isInt(str) {
		return !isNaN(parseInt(str));
	}
	
	function putToCar(time, serial) {
		if (belongsToCar(time, serial)) {
			addWork(4, time, serial);	
		}		
	}
	
	function belongsToCar(time, serial) {
		return isInt(serial) && (time == 8 || time == 7);
	}
	
	function putToNone(time, serial) {
		addWork(11, time, serial);
	}

	$scope.changeWork = function(time, serial) {
		doChangeWork($scope.workType, time, serial);
	};

	function doChangeWork(workType, time, serial) {	    		
		if (isEmpty(time, serial)) {
			addWork(workType, time, serial);
		} else if (isSameWork(time, serial)) {	    				
			putToRest(time, serial);
		} else {
			addWork(workType, time, serial);
		}	
	};	
	
	function isEmpty(time, serial) {
		return typeof $scope.btnWorkId[time][serial] == 'undefined';
	}
	
	function isSameWork(time, serial) {
			return $scope.btnWorkId[time][serial] == $scope.mapping[$scope.workType].id
	}
	
	function addWork(workType, time, serial) {
		collectTimes($scope.btnWorkId[time][serial], $scope.mapping[workType].id, serial);
		$scope.btnCss[time][serial] = $scope.mapping[workType].css;
		$scope.btnWorkId[time][serial] = $scope.mapping[workType].id;
		$scope.btnStr[time][serial] = $scope.mapping[workType].str;
	}	
	
	function collectTimes(oldWorkId, newWorkId, serial) {
		if (!isInt(serial)) {
			doCollectTimes(oldWorkId, newWorkId, serial, $scope.analysis.usTimes); 	
		} else {
			doCollectTimes(oldWorkId, newWorkId, serial, $scope.analysis.memberTimes); 	
		}
	}
	
	function doCollectTimes(oldWorkId, newWorkId, serial, analysis) {
		if (oldWorkId != null && isRealWorkId(oldWorkId, serial)) {
			--analysis[serial];	
		}
		if (isRealWorkId(newWorkId, serial)) {
			++analysis[serial];	
		}
	}	    
		
	function isRealWorkType(workType, serial) {
		var restWorkId = [10, 11, -1];
		return restWorkId.indexOf($scope.mapping[workType].id) == -1;
	}
	function isRealWorkId(workId, serial) {
		var restWorkId = [10, 11, -1];
		return restWorkId.indexOf(workId) == -1;
	}
		
	$scope.changeBackTeam = function(person) {
		if ($scope.rest1Class[person] == null) {
			$scope.rest1Class[person] = 'rest1Block';	
			$scope.serials.push(person);	
			initBtnWithTime(person, 21, 23, true);
			initBtnWithTime(person, 0, 7, false);
		} else {
			$scope.rest1Class[person] = null;
			$scope.serials.splice($scope.serials.indexOf(person), 1);
		}
		
			
	};	
	
	$scope.hoverBlock = function(timeIndex, serialIndex) {
		$scope.timeClass = [];
		$scope.serialClass = [];
		$scope.timeClass[timeIndex] = 'bck-red';
		$scope.serialClass[serialIndex] = 'bck-red';
	};
	
	$scope.leaveBlock = function() {
		$scope.timeClass = [];
		$scope.serialClass = [];
	};
	
	$scope.hoverChangeWork = function(time, serial) {
		if ($scope.press) {
			$scope.changeWork(time, serial);
		}		
	};
	
	$scope.outputForm = function() {
		$scope.output = 
			"function fillWork(data) {	\
				for (var workType in data) {	\
					for (var time in data[workType]) {	\
						document.getElementById('_pln_' + time + '_' + workType).value = data[workType][time];	\
					}	\
				}	\
			}	\
			function fillOther(data, objId) {	\
				document.getElementById(objId).value = data;	\
			}";
		buildResult();
		$scope.output += "fillWork(" + JSON.stringify($scope.result) + ");";
		$scope.output += "fillOther('" + $scope.rest1.toString() + "', '_txtVTYPE_A');";
		$scope.output += "fillOther('" + $scope.rest2.toString() + "', '_txtVTYPE_B');";
		$scope.output += "fillOther('" + $scope.rest3.toString() + "', '_txtVTYPE_C');";
		$scope.output += "fillOther('" + $scope.rest4.toString() + "', '_txtVTYPE_D');";
		$scope.output += "fillOther('" + $scope.rest5.toString() + "', '_txtVTYPE_E');";
		$scope.output += "fillOther('" + $scope.rest6.toString() + "', '_txtVTYPE_F');";
		$scope.output += "fillOther('" + attandArticle + "', '_areATTEND');";
		$scope.output += "fillOther('" + memoArticle + "', '_areMEMO');";
	};
	
	function buildResult() {
		$scope.result = {};
		for (var i = 0; i < $scope.times.length; ++i) {
			var time = $scope.times[i];
			for (var j = 0; j < $scope.serials.length; ++j) {
				var serial = $scope.serials[j];
				var workId = $scope.btnWorkId[time][serial];
				if (workId == -1) {
					continue;
				}
				if ($scope.result[workId] == null) {
		    		$scope.result[workId] = {};
				}
				if ($scope.result[workId][time] == null) {
		    		$scope.result[workId][time] = [];
				}
				serial = isInt(serial) ? parseInt(serial) : serial;
				$scope.result[workId][time].push(serial);
			}
		}
		for (var workId in $scope.result) {
			for (var time in $scope.result[workId]) {
				$scope.result[workId][time].sort(mySortFunc);
			}
		}
		$scope.rest1.sort(mySortFunc);
		$scope.rest2.sort(mySortFunc);
		$scope.rest3.sort(mySortFunc);
		$scope.rest4.sort(mySortFunc);
		$scope.rest5.sort(mySortFunc);
		$scope.rest6.sort(mySortFunc);
				
		function mySortFunc(a, b) {
			if (typeof a == 'number' && typeof b == 'number') {
				return a - b 	
			} else if (typeof a == 'number') {
				return -1;
			} else if (typeof b == 'number'){
				return 1;
			} else {
				return (a > b) ? 1 : -1;
			}					
		}
	}
	$scope.restart = function() {
		$scope.output = '';
		$scope.result = {};
		init();
	}
	
	$scope.isInArea = function(i, serial) {
		var storage;
		switch(i) {
			case 0: 
				storage = $scope.serials;
				break;
			case 1:
				storage = $scope.rest1;
				break;
			case 2:
				storage = $scope.rest2;
				break;
			case 3:
				storage = $scope.rest3;
				break;
			case 4:
				storage = $scope.rest4;
				break;
			case 5:
				storage = $scope.rest5;
				break;
			case 6:
				storage = $scope.rest6;
				break;
		}
		if (isInt(serial)) {
			serial = parseInt(serial);
		}
		return (storage.indexOf(serial) != -1);
	}
	
});