<!doctype html>
<html>
    <head>
    	<title>勤務分配表</title>
    	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.2.26/angular.min.js"></script>
    	<script src="lib/angular-dragdrop.min.js"></script>
    	

    	<script src="js/util.js"></script>
    	<script src="js/formCtrl.js"></script>
    	<link rel="stylesheet" href="css/dayWork.css">
    	


    </head>
    <body ng-app='workApp' ng-controller='formCtrl' ng-mousedown="press = true" ng-mouseup="press = false">
    	<span ng-init='host = "<?php echo URL::to("/") ?>"'></span>
		<?php if($storable):?>
			<span ng-init='defaults = <?php echo $defaults ?>'></span>
			<span ng-init='init()'></span>						
			<span ng-init='loadNow()'></span>						
		<?php endif; ?>

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

			<?php if($storable):?>
				<div class="area-content">
					<a class="btn bck-orange bck-red-hover" href="<?php echo URL::to("dayWork/default")?>">修改預設值</a>
				</div>
			<?php endif; ?>
			
		</div>
    	
    	<div class='restArea'>
	    	<p>(橘框假別可自動產生註解)</p>
	    	<div class='droppableBlock remarkBlock' ng-model="dropRest7"
	    		ng-mouseenter="rest7Show = true" ng-mouseleave="rest7Show = false"
    			data-drop="true" data-jqyoui-options jqyoui-droppable="{onDrop : 'dropARest7'}">
    			<span>特休</span>
		    	<span class='block bck-gray' ng-repeat="person in rest7" 
		    		ng-model="rest7[$index]" ng-hide="!rest7[$index]" 
		    		data-drag="true" data-jqyoui-options="{revert: 'invalid'}" jqyoui-draggable="{animate:false}">{{ person }}</span>
			</div>
			
			<div class='droppableBlock remarkBlock' ng-model="dropRest8"
	    		ng-mouseenter="rest8Show = true" ng-mouseleave="rest8Show = false"
    			data-drop="true" data-jqyoui-options jqyoui-droppable="{onDrop : 'dropARest8'}">
    			<span>慰外假</span>
		    	<span class='block bck-gray' ng-repeat="person in rest8" 
		    		ng-model="rest8[$index]" ng-hide="!rest8[$index]" 
		    		data-drag="true" data-jqyoui-options="{revert: 'invalid'}" jqyoui-draggable="{animate:false}">{{ person }}</span>
			</div>
			
			<div class='droppableBlock remarkBlock' ng-model="dropRest9"
	    		ng-mouseenter="rest9Show = true" ng-mouseleave="rest9Show = false"
    			data-drop="true" data-jqyoui-options jqyoui-droppable="{onDrop : 'dropARest9'}">
    			<span>榮譽假</span>
		    	<span class='block bck-gray' ng-repeat="person in rest9" 
		    		ng-model="rest9[$index]" ng-hide="!rest9[$index]" 
		    		data-drag="true" data-jqyoui-options="{revert: 'invalid'}" jqyoui-draggable="{animate:false}">{{ person }}</span>
			</div>
			<p>
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
			<form method='post'>								
				
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
			<div class='outputAfter'>
				<ul>
					<li>檢查役男時數 > 8小時</li>
					<li>深救役男需填入車輛保養</li>
					<li>檢查隊員7-8, 8-9是否在車輛保養</li>
				</ul>
			</div>
				
			<div class='remarkArea'>
				<h3>出動梯次</h3>
				上班人員 : 
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
				<button class='btn bck-orange bck-red-hover' ng-click="insertRemark()">產生備註</button> (注意 ! 目前的備註內容將會被覆蓋)
				<p>目前可以產生 : 主管代理、役男返隊、特休榮譽假慰外假註解、勤務時間</p>
				<div class='outputAfter'>
					<ul>
						<li>其他有附註之內容需加註</li>						
					</ul>
				</div>
				<textarea ng-model='memoArticle'></textarea>
				
				<?php if($storable): ?>
					<h3>系統備註 (記錄一些轉換到派遣系統該注意的)</h3>
					<textarea ng-model='systemArticle' placeholder="例如 : 記得加上補外假人員至補修"></textarea>
				<?php endif?>
			</div>
			
			<button class='btn bck-red bck-red-hover' ng-click='outputForm()'>輸出為文字</button>
			<span class='btn bck-orange' ng-click="restart()">重置</span>			

			<span ng-show="output">複製以下文字至Console貼上</span>				
			<p class='output'>{{ output }}</p>
			
    	</div>    	
    	
    	<div class='analysisArea'>
    		<?php if($storable): ?>
	    	<h2 class='area-content'>選擇日期</h2>
	    	<div class='area-content dateChoose'>
		    	<input ng-model='dateY' type="number" ng-change='loadNow()'>年<br>
		    	<input ng-model='dateM' type="number" ng-change='loadNow()'>月
		    	<input ng-model='dateD' type="number" ng-change='loadNow()'>日
		    	<span class='btn bck-white bck-white-hover' ng-click='dateD = dateD - 1; loadNow()'>上一天</span>
		    	<span class='btn bck-white bck-white-hover' ng-click='dateD = dateD + 1; loadNow()'>下一天</span>
	    	</div>

	    	<div class='area-content storeArea'>
	    		密碼 : <input type='password' ng-model='password'>
	    		<button class='btn bck-orange' ng-click='storeDayWork()'>儲存</button>	    		
	    		<p>上次修改 :<br> {{ lastModifiedTime }}</p>
	    	</div>
	    	<?php endif; ?>
	    	
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