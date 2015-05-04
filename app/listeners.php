<?php
 
Event::listen(\Realtime\OrderUpdatedEventHandler::EVENT, '\Realtime\OrderUpdatedEventHandler');
Event::listen(\Realtime\MissionEndEventHandler::EVENT, '\Realtime\MissionEndEventHandler');

?>