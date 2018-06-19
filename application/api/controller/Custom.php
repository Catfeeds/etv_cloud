<?php
/**
 * User: Lockin
 * Date: 2018/6/19 14:41
 * 客户资源接口类
 */

namespace app\api\controller;

use app\common\controller\Api;
use think\Config;
use think\Db;

class Custom extends Api
{
	// 无需登录的接口,*表示全部
	protected $noNeedLogin = ['*'];

	protected $beforeActionList = [
		//无值的话为当前控制器下所有方法的前置方法
		'check_params',

		// except表示不使用前置方法
//		'mytest'    =>  ['except'=>''],

		// only表示使用前置方法
//		'beforefunc'    =>  ['only'=>'mytest']
	];

	/**
	 * 判断参数
	 */
	protected function check_params(){
		$custom_id = $this->request->get('custom_id');
		$mac = $this->request->get('mac');
		if(empty($custom_id) || empty($mac)){
			$this->error(__('Parameter error'), [], -1);
		}
	}

	/**
	 * 获取客户信息
	 * @param custom_id
	 * @param field 查询字段
	 */
	protected function get_custom($field='*'){
		$custom_id = $this->request->get('custom_id');
		$where['custom_id'] = $custom_id;
		$where['status'] = 'normal';
		return Db::name('custom')->where($where)->cache('custom_obj', 300)->field($field)->find();
	}

	/**
	 * @获取客户的launcher皮肤
	 * @param custom_id 客户编号
	 * @param mac MAC
	 * @return 皮肤列表
	 */
	public function skin(){
		$custom_obj = $this->get_custom();
		if(!empty($custom_obj)){
			$cache_time = Config::get('api_cache_time');
			$skin_cache_time = isset($cache_time['skin'])? $cache_time['skin']: 0;
			$skin_obj = Db::name('skin')
							->where('status', 'eq', 'normal')
							->cache($skin_cache_time)
							->select(); //缓存10分钟
			if(!empty($skin_obj)){
				foreach ($skin_obj as $key=>$value){
					if($custom_obj['skin_id'] == $value['id']){
						$skin_obj[$key]['selected'] = true;
					}else{
						$skin_obj[$key]['selected'] = false;
					}
				}
			}
			$this->success('Success', $skin_obj, 0);
		}
		$this->error(__('Invalid parameters'));
	}

	public function welcome_setting(){
		$custom_obj = $this->get_custom('id');
		if(!empty($custom_obj)){
			$where['zxt_welcome_custom.status'] = 'normal';
			$where['zxt_welcome_custom.custom_id'] = $custom_obj['id'];
			$where['zxt_welcome_resource.audit_sstatus'] = 'egis';
			$cache_time = Config::get('api_cache_time');
			$welcome_cache_time = isset($cache_time['welcome'])? $cache_time['welcome']: 0;
			$field = 'zxt_welcome_custom.id, zxt_welcome_custom.title, zxt_welcome_custom.stay_set, zxt_welcome_custom.stay_time, zxt_welcome_custom.weigh, zxt_welcome_custom.audit_status, zxt_welcome_resource.filepath, zxt_welcome_resource.file_type, zxt_welcome_resource.size';
//			$field = '*';
			$welcome_obj = Db::name('welcome_custom')
								->alias('wc')
								->cache($welcome_cache_time)
								->join('zxt_welcome_resource wr', 'wc.rid=wr.id')
								->field($field)
								->select();
			$this->success('Success', $welcome_obj, 0);
		}
		$this->error(__('Invalid parameters'));
	}

}