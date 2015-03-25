<!doctype html>
<html>
    <head>
    	<title>勤務分配表</title>
    	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.2.26/angular.min.js"></script>
    	<script src="lib/angular-dragdrop.min.js"></script>
    	<script src="js/formCtrl.js"></script>
    	<script src="js/dateCtrl.js"></script>
    	<link rel="stylesheet" href="css/dayWork.css">
    	
    </head>
    <body ng-app='workApp' ng-controller='formCtrl' ng-mousedown="press = true" ng-mouseup="press = false">

		<div class='workTypeArea'>
			<h1 class='area-content'>選擇勤務</h1>
	    	<div class='area-content' ng-repeat="work in mapping">
	    		<span class='block' ng-class="work.css">{{ work.str }}</span>
	    		<span class='workItem' ng-click="changeWorkType($index)" ng-class="isFocus[$index]">	    			
	    			<span>{{ work.name }}</span>		    		
		    		<span class="popUp aliasPopUp" ng-show="work.alias">
		    			<p ng-repeat="alias in work.alias"> {{ alias }} </p>    			
		    		</span><br>
	    		</span>
	    	</div>
<!--
	    	<h2 class='area-content'>選擇日期</h2>
	    	<div class='area-content dateChoose' ng-controller='dateCtrl'>
		    	<input ng-model='dateY' type="number">年<br>
		    	<input ng-model='dateM' type="number">月
		    	<input ng-model='dateD' type="number">日
	    	</div>
-->
		</div>
    	
    	<div class='restArea'>
	    	
	    	<div class='droppableBlock' ng-model="dropRest2"
    			data-drop="true" data-jqyoui-options jqyoui-droppable="{onDrop : 'dropARest2'}">
    			<span>外宿</span>	
		    	<span class='block bck-gray' ng-repeat="person in rest2" 
		    		ng-model="rest2[$index]" ng-hide="!rest2[$index]" 
		    		data-drag="false" data-jqyoui-options="{revert: 'invalid'}" jqyoui-draggable="{animate:false}">{{ person }}</span>
			</div>

	    	
	    	<div class='droppableBlock' ng-model="dropRest3"
	    		ng-mouseenter="rest3Show = true" ng-mouseleave="rest3Show = false"
    			data-drop="true" data-jqyoui-options jqyoui-droppable="{onDrop : 'dropARest3'}">
    			<span>補休</span>	
    			<span class="popUp restPopUp" ng-show="rest3Show">(特休)</span>
		    	<span class='block bck-gray' ng-repeat="person in rest3" 
		    		ng-model="rest3[$index]" ng-hide="!rest3[$index]" 
		    		data-drag="true" data-jqyoui-options="{revert: 'invalid'}" jqyoui-draggable="{animate:false}">{{ person }}</span>
			</div>
			
	    	<div class='droppableBlock' ng-model="dropRest4"
	    		ng-mouseenter="rest4Show = true" ng-mouseleave="rest4Show = false"
    			data-drop="true" data-jqyoui-options jqyoui-droppable="{onDrop : 'dropARest4'}">
    			<span>休假</span>	
    			<span class="popUp restPopUp" ng-show="rest4Show">(慰勞假、慰外假)</span>
		    	<span class='block bck-gray' ng-repeat="person in rest4" 
		    		ng-model="rest4[$index]" ng-hide="!rest4[$index]" 
		    		data-drag="true" data-jqyoui-options="{revert: 'invalid'}" jqyoui-draggable="{animate:false}">{{ person }}</span>
			</div>

	    	
	    	<div class='droppableBlock' ng-model="dropRest5"
	    		ng-mouseenter="rest5Show = true" ng-mouseleave="rest5Show = false"
    			data-drop="true" data-jqyoui-options jqyoui-droppable="{onDrop : 'dropARest5'}">
    			<span>差假</span>	
    			<span class="popUp restPopUp" ng-show="rest5Show">(公假)</span>
		    	<span class='block bck-gray' ng-repeat="person in rest5" 
		    		ng-model="rest5[$index]" ng-hide="!rest5[$index]" 
		    		data-drag="true" data-jqyoui-options="{revert: 'invalid'}" jqyoui-draggable="{animate:false}">{{ person }}</span>
			</div>
	    	
	    	
	    	<div class='droppableBlock' ng-model="dropRest6"
	    		ng-mouseenter="rest6Show = true" ng-mouseleave="rest6Show = false"
    			data-drop="true" data-jqyoui-options jqyoui-droppable="{onDrop : 'dropARest6'}">
    			<span>事病假</span>	
    			<span class="popUp restPopUp" ng-show="rest6Show">(榮譽假)</span>
		    	<span class='block bck-gray' ng-repeat="person in rest6" 
		    		ng-model="rest6[$index]" ng-hide="!rest6[$index]" 
		    		data-drag="true" data-jqyoui-options="{revert: 'invalid'}" jqyoui-draggable="{animate:false}">{{ person }}</span>
			</div>
			
			
    		<div class='droppableBlock' ng-model="dropRest1"
    			ng-mouseenter="rest2Show = true" ng-mouseleave="rest2Show = false"
    			data-drop="true" data-jqyoui-options jqyoui-droppable="{onDrop : 'dropARest1'}">
    			<span>輪休</span>
    			<span class="popUp restPopUp" ng-show="rest2Show">(輪休改外宿)</span>
    			<span class='block' ng-repeat="person in rest1" >
			    	<span class='block bck-gray' 
			    		ng-mouseenter="rest1Show[person] = rest1Class[person]" ng-mouseleave="rest1Show[person] = false"
			    		ng-model="rest1[$index]" ng-hide="!rest1[$index]" ng-dblclick="changeBackTeam(person)" ng-class="rest1Class[person]"
			    		data-drag="!rest1Class[person]" 
			    		data-jqyoui-options="{revert: 'invalid'}" jqyoui-draggable="{animate:false}">{{ person }}</span>					
			    		<span class="popUp" ng-show="rest1Show[person]">21時返隊</span>	    
		    		
				</span>
			</div>
    	</div>

    	<div class='workFormArea'>
			<form method='post' ng-submit="outputForm()">
				<button class='btn bck-red bck-red-hover' type="submit">輸出</button>
				<span class='btn bck-orange' ng-click="restart()">重置</span>
				
				<span ng-show="output">複製以下文字至Console貼上</span>				
				<p class='output'>{{ output }}</p>				
				
				<div class='serialDroppable none-select' ng-model='dropSerial'
						data-drop="true" data-jqyoui-options jqyoui-droppable="{onDrop : 'dropASerial'}">
					<table>
						<tr>
							<th>番號</th>							
							<th class='serial serial bck-gray' ng-repeat="serial in serials"
					    		ng-model="serials[$index]" ng-hide="!serials[$index]" ng-class="serialClass[$index]"
					    		data-drag="true" data-jqyoui-options="{revert: 'invalid'}" 
					    		jqyoui-draggable="{animate:false}">{{ serials[$index] }}</th>
						</tr>
						
						<tr ng-repeat='time in times'>
							<td ng-class="timeClass[$index]">{{ time }} ~ {{ time + 1 }}</td>
							<td ng-repeat='serial in serials'>
								<span class='block' ng-mousedown="changeWork(time, serial)" ng-class="btnCss[time][serial]" 
									ng-hide="!serials[$index]" 
									ng-mouseenter="hoverBlock($parent.$index, $index); hoverChangeWork(time, serial)" 
									ng-mouseleave="leaveBlock()"
									ng-model="btnWorkId[time][serial]">{{ btnStr[time][serial] }}</span>
							</td>
						</tr>					
					</table>
				</div>
			</form>
			
			<div class='remarkArea'>
				<h3>出動梯次</h3>
				上班隊員、役男 : 
				<span ng-repeat="serial in serials">
					<span ng-class="serialColor[serial]">
					{{ serial }}
					</span>
					, 
				</span><br>
				(<span class='red'>紅色</span>為尚未填入以下任一車輛)					
				<div class='outputAfter'>
					<ul>
						<li>91車92車(1~15日91，16~31日92)</li>
					</ul>
				</div>
				<textarea ng-model='attandArticle' ng-change='checkWorkPeople()'></textarea>
				
				<h3>備註</h3>
				<button class='btn bck-orange bck-red-hover' ng-click="insertRemark()">轉換</button> (注意 ! 以下內容將會被覆蓋)
				<div class='outputAfter'>
					<ul>
						<li>特休、慰外、公假、榮譽假、任何有附註之內容需加註</li>						
					</ul>
				</div>
				<textarea ng-model='memoArticle'></textarea>
				<p>轉換功能目前包括 : 主管代理
				</p>
			</div>
    	</div>    	
    	
    	<div class='analysisArea'>
    		<h1 class='area-content'>統計</h1>
    		<div class='area-content'>
	    		<h3>役男時數</h3>
	    		<p ng-repeat="(key, value) in analysis.usTimes" ng-show="isInArea(0, key)">{{ key }} : {{ value }} 小時 
	    			<span ng-show="rest1Class[key]">(返隊)</span>
	    			<span ng-show="isInRest2(key)
	    			">(外宿)</span>
	    		</p>
<!--
	    		<h3>隊員時數</h3>
	    		<p ng-repeat="(key, value) in analysis.memberTimes" ng-show="isInArea(0, key)">{{ key }} : {{ value }} 小時 
	    			<span ng-show="rest1Class[key]">(返隊)</span>
	    			<span ng-show="isInRest2(key)">(外宿)</span>
	    		</p>
-->
    		</div>
    	</div>
    </body>
    
   
</html>