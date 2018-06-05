<?php

namespace app\admin\validate;

use think\Validate;

class LanguageSetting extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
        'title'         =>  'require|max:40',
        'language'      =>  'check_language',
        'appellation'   =>  'require|max:80',
        'wel_words'     =>  'max:256',
        'signature'     =>  'max:60',
    ];
    /**
     * 提示消息
     */
    protected $message = [
	    'language'  =>  'Language type error',
    ];
    /**
     * 验证场景
     */

	protected function check_language($value, $rule, $data){
		if(in_array($value, ['englist', 'chinese'])){
			return true;
		}else{
			return false;
		}
	}

    protected $scene = [
        'add'  => ['title', 'language', 'appellation', 'wel_words', 'signature'],
        'edit' => ['title', 'language', 'appellation', 'wel_words', 'signature'],
    ];
    
}
