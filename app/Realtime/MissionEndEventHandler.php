<?php
 
namespace Realtime;
use Redis;
use Response;

class MissionEndEventHandler {
 
    CONST EVENT = 'mission.end';
    CONST CHANNEL = 'mission.end';
 
    public function handle($data) {
        $redis = Redis::connection();
		$redis->publish(self::CHANNEL, $data); 
    }
}