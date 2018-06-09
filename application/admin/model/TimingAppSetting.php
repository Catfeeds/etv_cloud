<?php

namespace app\admin\model;

use think\Model;

class TimingAppSetting extends Model
{
    // 表名
    protected $name = 'timing_app_setting';

	public function custom(){
		return $this->belongsTo('Custom', 'custom_id', 'id')->setEagerlyType(0);
	}

}
