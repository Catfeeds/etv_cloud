<?php

namespace app\admin\model;

use think\Model;
use app\common\model\Attachment;

class PopupSetting extends Model
{
    // 表名
    protected $name = 'popup_setting';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;

    public function custom(){
        return $this->belongsTo('Custom', 'custom_id', 'id')->setEagerlyType(0);
    }

	public function resource(){
		return $this->belongsTo('Popup_resource', 'resource_id', 'id')->setEagerlyType(1);
	}
}
