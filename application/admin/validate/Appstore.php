<?php

namespace app\admin\validate;

use think\Validate;

class Appstore extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
    	'type'      =>  'in:system,common',
	    'name'      =>  'require|max:40',
	    'version'   =>  'require|max:20',
	    'sha1'      =>  'require|length:40',
	    'package'   =>  'require|max:100',
	    'remarks'   =>  'max:256',
	    'filepath'  =>  'require|max:100',
	    'icon'      =>  'max:100',
	    'status'    =>  'in:hidden,normal',
	    'push_all'  =>  'in:true,false'
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
        'add'  => ['type', 'name', 'version', 'sha1', 'package', 'remarks', 'filepath', 'icon', 'status', 'push_all'],
        'edit' => ['type', 'name', 'version', 'sha1', 'package', 'remarks', 'filepath', 'icon', 'status', 'push_all'],
    ];
    
}
