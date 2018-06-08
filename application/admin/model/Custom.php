<?php

namespace app\admin\model;

use think\Model;

class Custom extends Model
{
    // 表名
    protected $name = 'custom';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    
    // 追加属性
    protected $append = [

    ];

    public static function getTreeList($selected = [], $where_column = [])
    {
	    $field = 'id,pid,custom_name';
        $ruleList = collection(self::where('id','in',$where_column)->field($field)->select())->toArray();
        $nodeList = [];

        foreach ($ruleList as $k => $v)
        {
            $state = array('selected' => (in_array($v['id'], $selected)? true: false));
            $nodeList[] = array('id' => $v['id'],
	            'parent' => $v['pid'] ? $v['pid'] : '#',
	            'text' => $v['custom_name'],
	            'state' => $state);
        }
	    unset($v);
        return $nodeList;
    }
    
	public function jump_setting() {
		return $this->hasMany('jump_setting', 'id', 'id');
	}

	public function welcome_custom() {
		return $this->hasMany('welcome_custom', 'id', 'id');
	}

	public function language_setting() {
		return $this->hasMany('language_setting', 'id', 'id');
	}

	public function propaganda_custom() {
		return $this->hasMany('propaganda_custom', 'id', 'id');
	}

	public function popup_setting(){
		return $this->hasMany('popup_setting', 'id', 'id');
	}

	public function simplead_custom() {
		return $this->hasMany('simplead_custom', 'id', 'id');
	}

	public function device_basics() {
		return $this->hasMany('device_basics', 'id', 'id');
	}

	public function message_notice() {
		return $this->hasMany('message_notice', 'id', 'id');
	}

}
