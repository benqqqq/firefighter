<?php
 
namespace Realtime;
use Redis;
use Response;

class OrderUpdatedEventHandler {
 
    CONST EVENT = 'orders.update';
    CONST CHANNEL = 'orders.update';
 
    public function handle($data) {
        $redis = Redis::connection();
		$redis->publish(self::CHANNEL, $data); 
    }
}