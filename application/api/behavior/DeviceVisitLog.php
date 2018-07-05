<?php

namespace app\api\behavior;


use think\Log;

class DeviceVisitLog
{
	public function run(){
		\app\api\model\DeviceVisitLog::record();
	}
}