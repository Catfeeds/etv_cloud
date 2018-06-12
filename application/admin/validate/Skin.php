<?php

namespace app\admin\validate;

use think\Validate;

class Skin extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
    	'title'         =>  'require|max:40',
	    'apk_filepath'  =>  'max:100',
	    'sha1'          =>  'max:40',
	    'web_sign'      =>  'max:40',
	    'image_filepath'=>  'max:100',
	    'status'        =>  'in:hidden,normal'
    ];
    /**
     * 提示消息
     */
    protected $message = [
    ];
    /**
     * 验证场景
     */
    protected $scene = [
        'add'  => ['title', 'apk_filepath', 'sha1', 'web_sign', 'image_filepath', 'status'],
        'edit' => ['title', 'apk_filepath', 'sha1', 'web_sign', 'image_filepath', 'status'],
    ];
    
}
