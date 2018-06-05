<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/17
 * Time: 16:16
 */

namespace app\admin\validate;

use think\Validate;

class Custom extends Validate
{
	/**
	 * 验证规则
	 */
	protected $rule = [
		'title'     => 'require|max:32',
		'url'  => 'rquire|max:100',
		'sha1'      => 'require|length:40',
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
		'add'   =>  ['title', 'url', 'sha1'],
		'edit'  =>  ['title', 'url', 'sha1'],
	];

}