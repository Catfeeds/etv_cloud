<?php

namespace app\admin\model;

use think\Model;

class MessageNotice extends Model
{
    // 表名
    protected $name = 'message_notice';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;

    public function custom(){
        return $this->belongsTo('Custom', 'custom_id', 'id')->setEagerlyType(0);
    }
    

    







}
