<?php

namespace app\admin\validate;

use think\Validate;
use think\Config;

class Admin extends Validate
{

    /**
     * 验证规则
     */
    protected $rule = [
        'username' => 'require|max:50|unique:admin',
	    'email'    => 'require|max:60',
	    'nickname' => 'require',
        'password' => ['require','min:8','check_pwd_user','regex'=>'((?=.*[a-z])(?=.*[A-Z])(?=.*\W).\S{7,})'],
	    'pwd_edit' => ['check_editpwd_user','regex'=>'((?=.*[a-z])(?=.*[A-Z])(?=.*\W).\S{7,})'],
	    'loginfailure' => 'number',
    ];

    /**
     * 提示消息
     */
    protected $message = [
	    'password.regex' => '密码请按照要求设置',
	    'pwd_edit.regex' => '修改密码请按照要求设置',
    ];

    /**
     * 字段描述
     */
    protected $field = [
    ];

    /**
     * 验证场景
     */
    protected $scene = [
        'add'  => ['username', 'email', 'nickname', 'password'],
    ];

    public function __construct(array $rules = [], $message = [], $field = [])
    {
        $this->field = [
            'username' => __('Username'),
            'nickname' => __('Nickname'),
            'password' => __('Password'),
            'email'    => __('Email'),
	        'pwd_edit' => __('Password'),
	        'pwd'      => __('Password'),
	        'pwd_last' => __('Password'),
        ];
        parent::__construct($rules, $message, $field);
    }

	/**
	 * 新增数据时判断密码是否含有多个账号字符
	 * @param 验证数据
	 * @param 验证规则
	 * @param 全部数据(数组)
	 * @return bool|string
	 */
	protected function check_pwd_user($params, $rule, $data){
		$username = str_split($data['username']);
		$password = $data['password'];
		$count = 0;
		foreach($username as $value){
			if(stristr($password, $value)){
				$count++;
			}
		}
		return $count>Config::get('allow_login_char_count')? '密码含有多个用户名字符': true;
	}

	/**
	 * 修改数据时判断密码是否含有多个账号字符
	 * @param 验证数据
	 * @param 验证规则
	 * @param 全部数据(数组)
	 * @return bool|string
	 */
	protected function check_editpwd_user($params, $rule, $data){
		$username = str_split($data['username']);
		$password = $data['pwd_edit'];
		$count = 0;
		foreach($username as $value){
			if(stristr($password, $value)){
				$count++;
			}
		}
		return $count>Config::get('allow_login_char_count')? '修改密码含有多个用户名字符'.$count: true;
	}

}
