<?php

namespace app\admin\model;

use think\Model;

class AuditProcessLog extends Model
{
    // 表名
    protected $name = 'audit_process_log';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    
	public function admin() {
		return $this->belongsTo('admin','admin_id','id')->setEagerlyType(0);
	}


}
