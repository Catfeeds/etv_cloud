<?php

namespace app\admin\model;

use think\Model;

class TimingAppResource extends Model
{
    // 表名
    protected $name = 'timing_app_resource';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;

	public function timing_app_setting(){
		return $this->hasMany('timing_app_setting', 'id', 'id');
	}

    







}
