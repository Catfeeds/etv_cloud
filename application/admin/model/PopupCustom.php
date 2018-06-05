<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/24
 * Time: 17:15
 */

namespace app\admin\model;

use think\model;

class PopupCustom extends model
{
	// 表名
	protected $name = 'popup_custom';

	public function resource(){
		return $this->belongsTo('Popup_resource', 'rid', 'id')->setEagerlyType(0);
	}
}