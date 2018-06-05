<?php

namespace app\admin\model;

use think\Model;

class PopupResource extends Model
{
    // 表名
    protected $name = 'popup_resource';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';

	public function popup_custom(){
		return $this->hasMany('popup_custom', 'id', 'id');
	}

}
