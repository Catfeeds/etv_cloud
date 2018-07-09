<?php

namespace app\api\validate;

use think\Validate;

class Auditprocess extends Validate
{
	protected $rule = [
		'audit_type'    =>  'require|in:release_type',
		'audit_module'  =>  'require|in:module_welcome,module_jump,module_propaganda,module_popup,module_simplead',
		'audit_list_id' =>  'require|number',
		'audit_value'   =>  'require|in:release,no release',
		'custom_id'     =>  'require|max:32'
	];

	protected $message = [

	];

	protected $scene = [
		'not_column'        =>  ['audit_type', 'audit_module', 'audit_list_id', 'audit_value'],
		'column_resource'   =>  ['custom_id', 'audit_list_id', 'audit_value']
	];

}