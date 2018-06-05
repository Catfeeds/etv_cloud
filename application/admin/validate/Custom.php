<?php

namespace app\admin\validate;

use think\Validate;

class Custom extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
		'pid'           =>  'require|number',
	    'custom_id'     =>  'require|max:32|alphaDash',
	    'custom_name'   =>  'require|max:64',
	    'full_name'    =>  'require|max:64',
	    'custom_type'   =>  'require|max:20',
	    'handler'       =>  'require|max:32',
	    'phone'         =>  'require|length:11|number',
	    'province_id'   =>  'require|number',
	    'city_id'       =>  'require|number',
	    'area_id'       =>  'require|number',
	    'detail_address'=>  'require',
	    'lng'           =>  ['require','regex'=>'[\-\+]?(0?\d{1,2}\.\d{1,5}|1[0-7]?\d{1}\.\d{1,5}|180\.0{1,5})'],
	    'lat'           =>  ['require','regex'=>'[\-\+]?([0-8]?\d{1}\.\d{1,5}|90\.0{1,5})'],
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
        'add'  => ['pid',
	        'custom_id',
	        'custom_name',
	        'group_name',
	        'custom_type',
	        'handler',
	        'phone',
            'province_id',
	        'city_id',
	        'area_id',
	        'detail_address',
	        'lng',
	        'lat',
        ],
	    'edit'  => ['pid',
		    'custom_name',
		    'group_name',
		    'custom_type',
		    'handler',
		    'phone',
		    'province_id',
		    'city_id',
		    'area_id',
		    'detail_address',
		    'lng',
		    'lat',
	    ],
    ];
    
}
