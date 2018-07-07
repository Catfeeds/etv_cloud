<?php
/**
 * User: Lockin
 * Date: 2018/6/19 14:41
 * 客户资源接口类
 */

namespace app\api\controller;

use app\common\controller\Api;
use think\Cache;
use think\Config;
use think\Db;
use think\Debug;
use think\exception\PDOException;
use think\Log;

class Custom extends Api
{
	// 无需登录的接口,*表示全部
	protected $noNeedLogin = ['*'];

	protected $beforeActionList = [
		'check_params'      =>  ['except'=>'custom_list']
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
		return Db::name('custom')->where($where)->cache(30)->field($field)->find();
	}

	/**
	 * 获取设备基础信息
	 * @param mac
	 * @param field 查询字段
	 */
	protected function get_device($field='*'){
		$mac = $this->request->get('mac');
		$where['mac'] = $mac;
		$where['status'] = 'normal';
		return Db::name('device_basics')->where($where)->cache(30)->field($field)->find();
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
			$skin_cache_time = isset($cache_time['skin'])? $cache_time['skin']: 10;
			$skin_obj = []; //数据集合
			try{
				$skin_obj = Db::name('skin')
					->where('status', 'eq', 'normal')
					->cache($skin_cache_time)
					->select(); //缓存10分钟
			}catch (PDOException $e){
				Log::write('获取客户皮肤出错,错误信息为:'.$e->getMessage());
				Log::save();
				$this->error('获取客户皮肤出错', null, -5);
			}

			//判断返回集合是否为空,并将客户选定皮肤添加selected属性为true
			if(!empty($skin_obj)){
				foreach ($skin_obj as $key=>$value){
					if($custom_obj['skin_id'] == $value['id']){
						$skin_obj[$key]['selected'] = true;
					}else{
						$skin_obj[$key]['selected'] = false;
					}
				}
				$this->success('Success', $skin_obj, 0);
			}else{
				$this->success('Empty', null, 0);
			}
		}
		$this->error(__('Invalid parameters'), null, -2);
	}

	/**
	 * @获取客户的欢迎设置
	 * @param custom_id 客户编号
	 * @param mac Mac
	 * 备注说明: 返回的欢迎设置,资源需为已经审核通过的,未审核或审核不通过的不查询.
	 */
	public function welcome_setting(){
		$custom_obj = $this->get_custom('id');
		if(!empty($custom_obj)){
			$where['wc.status'] = 'normal';
			$where['wc.custom_id'] = $custom_obj['id'];
			$egis_status = $this->request->get('egis_status');
			if($egis_status != 'none'){
				$where['wr.audit_status'] = 'egis';
			}
			$cache_time = Config::get('api_cache_time');
			$welcome_cache_time = isset($cache_time['welcome'])? $cache_time['welcome']: 10;
			$field = 'wc.id, wc.title, wc.stay_set, wc.stay_time, wc.weigh, wc.audit_status as release_status, wr.filepath, wr.file_type, wr.size, wr.audit_status as egis_status';
			try{
				$welcome_obj = Db::name('welcome_custom')
					->alias('wc')
					->cache($welcome_cache_time)
					->join('zxt_welcome_resource wr', 'wc.rid=wr.id')
					->where($where)
					->field($field)
					->select();
			}catch (PDOException $e){
				Log::write('获取客户欢迎设置出错,错误信息为:'.$e->getMessage());
				Log::save();
				$this->error('获取客户欢迎设置出错', null, -5);
			}
			$this->success('Success', $welcome_obj, 0);
		}
		$this->error(__('Invalid parameters'), null, -2);
	}

	/**
	 * @获取客户的语言管理设置
	 * @param custom_id 客户编号
	 * @param mac Mac
	 */
	public function language_setting() {
		$custom_obj = $this->get_custom('id');
		if(!empty($custom_obj)){
			$where['custom_id'] = $custom_obj['id'];
			$where['status'] = 'normal';
			$field = 'title, language, appellation, wel_words, signature';
			$cache_time = Config::get('api_cache_time');
			$language_cache_time = isset($cache_time['language'])? $cache_time['language']: 10;
			try{
				$language_obj = Db::name('language_setting')->where($where)->cache($language_cache_time)->field($field)->find();
			}catch (PDOException $e){
				Log::write('获取客户管理设置出错,错误信息为:'.$e->getMessage());
				Log::save();
				$this->error('获取客户管理设置出错', null, -5);
			}
			$this->success('Success', $language_obj, 0);
		}
		$this->error(__('Invalid parameters'), null, -2);
	}

	/**
	 * @获取客户所在的城市天气
	 * @param custom_id 客户编号
	 * @param mac Mac
	 */
	public function weather() {
		$custom_obj = $this->get_custom('id,city_id');
		if(!empty($custom_obj)){
			//获取客户对应的城市信息
			$area_obj = Db::name('area')->where('id', 'eq', $custom_obj['city_id'])->find();

			//查询是否含有数据
			$weather_obj = Db::name('weather')->where('city_id', 'eq', $area_obj['id'])->find();
			if(!empty($weather_obj)){
				$weather_obj['image_day'] = '/uploads/weather/'.$weather_obj['code_day'].'.png';
				$weather_obj['image_night'] = '/uploads/weather/'.$weather_obj['code_night'].'.png';
				$this->success('Success', $weather_obj, 0);
			}

			/*心知天气方法处理与调用*/
			// 心知天气接口调用凭据
			$key = 'c9fhn149mbtychgh'; // 测试用 key，请更换成您自己的 Key
			$uid = 'U810D587DB'; // 测试用 用户 ID，请更换成您自己的用户 ID
			// 参数
			$api = 'https://api.seniverse.com/v3/weather/daily.json'; // 接口地址
			$location = $area_obj['name']; // 城市名称
			// 生成签名。文档：https://www.seniverse.com/doc#sign
			$param = [
				'ts' => time(),
				'ttl' => 300,
				'uid' => $uid,
			];
			$sig_data = http_build_query($param); // http_build_query 会自动进行 url 编码
			// 使用 HMAC-SHA1 方式，以 API 密钥（key）对上一步生成的参数字符串（raw）进行加密，然后 base64 编码
			$sig = base64_encode(hash_hmac('sha1', $sig_data, $key, TRUE));
			// 拼接 url 中的 get 参数。文档：https://www.seniverse.com/doc#daily
			$param['sig'] = $sig; // 签名
			$param['location'] = $location;
			$param['start'] = 0; // 开始日期。0 = 今天天气
			$param['days'] = 1; // 查询天数，1 = 只查一天
			$url = $api . '?' . http_build_query($param);// 构造url
			//curl调用
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			$output=curl_exec($ch);
			curl_close($ch);
			$return = json_decode($output,true);
			if (isset($return['results']) && !empty($return['results'])) {
				$data['city_id'] = $area_obj['id'];
				$data['city'] = $return['results'][0]['location']['name'];
				$data['date'] = $return['results'][0]['daily'][0]['date'];
				$data['low'] = $return['results'][0]['daily'][0]['low'];
				$data['high'] = $return['results'][0]['daily'][0]['high'];
				$data['code_day'] = $return['results'][0]['daily'][0]['code_day'];
				$data['code_night'] = $return['results'][0]['daily'][0]['code_night'];
				$data['text_day'] = $return['results'][0]['daily'][0]['text_day'];
				$data['text_night'] = $return['results'][0]['daily'][0]['text_night'];
				try{
					Db::name('weather')->strict(true)->insert($data,true);
				}catch (PDOException $e){
					Log::write('插入天气出错,客户ID为:'.$custom_obj['id'] .' 错误信息为:'.$e->getMessage());
					Log::save();
					$this->error('更新天气出错', null, -5);
				}
				$data['image_day'] = '/uploads/weather/'.$return['results'][0]['daily'][0]['code_day'].'.png';
				$data['image_night'] = '/uploads/weather/'.$return['results'][0]['daily'][0]['code_night'].'.png';
				$this->success('Success', $data, 0);
			}else{
				$this->error('Error', null, -5);
			}
		}
		$this->error(__('Invalid parameters'), null, -2);
	}

	/**
	 * @获取消息提醒(走马灯)
	 * @param custom_id 客户编号
	 * @param mac Mac编号
	 */
	public function notice() {
		$custom_obj = $this->get_custom('id');
		$device_obj = $this->get_device('id');
		if(!empty($custom_obj) && !empty($device_obj)){
			$where['custom_id'] = $custom_obj['id'];
			$where['mac_ids'] = ['like',$device_obj['id']];
			$cache_time = Config::get('api_cache_time');
			$notice_cache_time = isset($cache_time['notice'])? $cache_time['notice']: 10;
			$field = 'title, content, push_type, push_start_time, push_end_time';
			try{
				$notice_obj = Db::name('message_notice')->where($notice_cache_time)->field($field)->select();
			}catch (PDOException $e){
				Log::write('获取客户消息提醒出错,错误信息为:'.$e->getMessage());
				Log::save();
				$this->error('获取客户消息提醒出错', null, -5);
			}
			$this->success('Success', $notice_obj, 0);
		}
		$this->error(__('Invalid parameters'), null, -2);
	}

	/**
	 * @获取跳转设置
	 * @param custom_id 客户编号
	 * @param mac Mac编号
	 */
	public function jump_setting() {
		$custom_obj = $this->get_custom('id');
		if(!empty($custom_obj)){
			$cache_time = Config::get('api_cache_time');
			$jump_cache_time = isset($cache_time['jump'])? $cache_time['jump']: 10;
			$where['custom_id'] = $custom_obj['id'];
			$setting_obj = [];
			try{
				//获取跳转设置
				$setting_obj = Db::name('jump_setting')->where($where)->field('play_set, save_set')->find();
			}catch (PDOException $e){
				Log::write('获取客户跳转设置出错,错误信息为:'.$e->getMessage());
				Log::save();
				$this->error('获取客户跳转设置出错', null, -5);
			}
			if(empty($setting_obj))
				$this->success('Success', null, 0);

			//获取跳转资源
			$where_resource['jc.custom_id'] = $custom_obj['id'];
			$where_resource['jc.status'] = 'normal';
			$egis_status = $this->request->get('egis_status');
			if('none' != $egis_status){
				$where_resource['jr.audit_status'] = 'egis';
			}
			$field = 'jc.audit_status as release_status, jr.filepath, jr.file_type, jr.size, jr.audit_status as egis_status';
			try{
				$resource_obj = Db::name('jump_custom')
					->alias('jc')
					->where($where_resource)
					->join('zxt_jump_resource jr', 'jc.rid=jr.id','LEFT')
					->field($field)
					->cache($jump_cache_time)
					->select();
			}catch (PDOException $e){
				Log::write('获取客户跳转资源出错,错误信息为:'.$e->getMessage());
				Log::save();
				$this->error('获取客户跳转资源出错', null, -5);
			}
			$setting_obj['resource'] = collection($resource_obj)->toArray();

			$this->success('Success', $setting_obj, 0);
		}
		$this->error(__('Invalid parameters'), null, -2);
	}

	/**
	 * @获取宣传资源设置
	 * @param custom_id 客户编号
	 * @param mac Mac编号
	 */
	public function propagand_setting() {
		$custom_obj = $this->get_custom('id');
		if(!empty($custom_obj)){
			$propagand_obj = [];
			$cache_time = Config::get('api_cache_time');
			$propaganda_cache_time = isset($cache_time['propaganda'])? $cache_time['propaganda']: 10;
			$where['pc.custom_id'] = $custom_obj['id'];
			$where['pc.status'] = 'normal';
			$egis_status = $this->request->get('egis_status');
			if('none' != $egis_status){
				$where['pr.audit_status'] = 'egis';
			}
			$field = 'pr.title, pr.filepath, pr.size, pr.file_type, pr.audit_status as egis_status, pc.weigh, pc.save_set, pc.audit_status as release_status';
			try{
				$propagand_obj = Db::name('propaganda_custom')
					->alias('pc')
					->where($where)
					->cache($propaganda_cache_time)
					->join('zxt_propaganda_resource pr', 'pc.rid=pr.id', 'LEFT')
					->field($field)
					->select();
			}catch (PDOException $e){
				Log::write('获取客户轮播资源出错,错误信息为:'.$e->getMessage());
				Log::save();
				$this->error('获取客户轮播资源出错', null, -5);
			}
			$this->success('Success', $propagand_obj, 0);
		}
		$this->error(__('Invalid parameters'), null, -2);
	}

	/**
	 * @获取简易广告设置
	 * @param custom_id 客户编号
	 * @param mac Mac编号
	 */
	public function simplead_setting() {
		$custom_obj = $this->get_custom('id');
		if(!empty($custom_obj)){
			$where['sc.custom_id'] = $custom_obj['id'];
			$where['sc.status'] = 'normal';
			$egis_status = $this->request->get('egis_status');
			if('none' != $egis_status){
				$where['sr.audit_status'] = 'egis';
			}
			$cache_time = Config::get('api_cache_time');
			$simplead_cache_time = isset($cache_time['simplead'])? $cache_time['simplead']: 10;
			$field = 'sc.title, sc.url_to, sc.audit_status as release_status, sr.filepath, sr.file_type, sr.size, sr.audit_status as egis_status';
			try{
				$simplead_obj = Db::name('simplead_custom')
									->alias('sc')
									->where($where)
									->cache($simplead_cache_time)
									->join('zxt_simplead_resource sr', 'sc.rid=sr.id', 'LEFT')
									->field($field)
									->select();
			}catch (PDOException $e){
				Log::write('获取客户简易广告出错,错误信息为:'.$e->getMessage());
				Log::save();
				$this->error('获取客户简易广告出错', null, -5);
			}
			$this->success('Success', $simplead_obj, 0);
		}
		$this->error(__('Invalid parameters'), null, -2);
	}

	/**
	 * @获取栏目列表
	 * @param custom_id 客户编号
	 * @param mac MAC编号
	 */
	public function column_setting() {
		$custom_obj = $this->get_custom('id');
		if(!empty($custom_obj)){
			//获取缓存
			$column_cache = Cache::get('column_'.$custom_obj['id'].'_cache');
			if(!empty($column_cache)){
				$this->success('Success', $column_cache, 0);
			}

			$where_custom['custom_id'] = $custom_obj['id'];
			$basic_obj = Db::name('column_custom')->where($where_custom)
				->field('rid,save_set,column_weigh,column_status,column_audit_status')
				->select();
			//数据集合是否为空
			if(empty($basic_obj))
				$this->success('Success', null, 0);

			$selected_id_list = []; //选取ID集合
			$column_weigh_setting = [];  //栏目权重设置集合
			$first_column_save_setting = []; //一级栏目保存地址设置集合
			//循环处理数据
			foreach ($basic_obj as $key=>$value){
				//判断栏目状态,如果未设置,默认为禁用
				if(!empty($value['column_status'])){
					$selected_id_list[] = $value['rid'];
					//获取栏目启用状态的栏目id
					$one_column_status = json_decode($value['column_status'], true);
					foreach ($one_column_status as $column_id=>$status_value){
						if('normal' == $status_value){
							$selected_id_list[] = $column_id;
						}
					}
				}
				//栏目权重设置
				if(!empty($value['column_weigh'])){
					$one_column_weigh = json_decode($value['column_weigh'], true);
					foreach ($one_column_weigh as $column_id=>$weigh_value){
						$column_weigh_setting[$column_id] = $weigh_value;
					}
				}

				//一级栏目保存设置
				$first_column_save_setting[$value['rid']] = $value['save_set'];
			}

			//如果选取的ID集合为空
			if(empty($selected_id_list))
				$this->success('Success', null, 0);

			$where_column['id'] = ['in', $selected_id_list];
			$field_column = 'id,pid,fpid,level,column_type,title,filepath,language_type';
			$column_obj = Db::name('column')->where($where_column)
							->field($field_column)
							->select();

			foreach ($column_obj as $key=>$value){
				//栏目保存地址
				if('1' == $value['level']){
					$column_obj[$key]['save_set'] = $first_column_save_setting[$value['id']];
				}else{
					$column_obj[$key]['save_set'] = '';
				}
				//栏目权重设置
				$column_obj[$key]['weigh'] = isset($column_weigh_setting[$value['id']])?$column_weigh_setting[$value['id']]:100;

			}
			$cache_time = Config::get('api_cache_time');
			$column_cache_time = isset($cache_time['column'])? $cache_time['column']: 10;
			Cache::set('column_'.$custom_obj['id'].'_cache', $column_obj, $column_cache_time);
			$this->success('Success', $column_obj, 0);

		}
		$this->error(__('Invalid parameters'), null, -2);
	}

	/**
	 * @获取栏目资源
	 * @param custom_id 客户编号
	 * @param column_id 栏目ID
	 * @param mac MAC编号
	 */
	public function column_resource() {
		$column_id = $this->request->get('column_id');
		if(empty($column_id))
			$this->error(__('Parameter error'), [], -1);

		$custom_obj = $this->get_custom('id');
		if(!empty($custom_obj)){
			//读取缓存
			$resource_cache = Cache::get('resource_'.$column_id.'_cache');
			if(!empty($resource_cache))
				$this->success('Success', $resource_cache, 0);

			$where['column_pid'] = $column_id;
			$egis_status = $this->request->get('egis_status');
			if ('none' != $egis_status){
				$where['audit_status'] = 'egis';
			}
			$field = 'id,column_fpid,title,describe,resource_type,resource,size,audit_status as egis_status';
			try{
				$resource_obj = Db::name('col_resource')->where($where)->field($field)->select();
			}catch (PDOException $e){
				Log::write('获取栏目资源出错,错误信息为:'.$e->getMessage());
				Log::save();
				$this->error('获取栏目资源出错', null, -5);
			}
			if(empty($resource_obj))
				$this->success('Success', null, 0);

			$where_column['rid'] = $resource_obj[0]['column_fpid']; //根据一级栏目ID查询分配栏目表条件
			$column_custom_obj = Db::name('column_custom')->where($where_column)->field('id,resource_audit_status')->find();

			//读取栏目分配表数据为空,可能存在错误数据
			if(empty($column_custom_obj))
				$this->error('获取资源对应栏目信息出错', null, -4);

			//资源发布数据为空,默认为未发布
			if(empty($column_custom_obj['resource_audit_status'])){
				foreach ($resource_obj as $key=>$value){
					$resource_obj[$key]['release_status'] = 'no release';
				}
			}else{
				$audit_status_info = json_decode($column_custom_obj['resource_audit_status'], true);
				foreach ($resource_obj as $key=>$value){
					$resource_obj[$key]['release_status'] = isset($audit_status_info[$value['id']])?$audit_status_info[$value['id']]:'no release';
				}
			}
			$cache_time = Config::get('api_cache_time');
			$resource_cache_time = isset($cache_time['resource'])? $cache_time['resource']: 10;
			Cache::set('resource_'.$column_id.'_cache', $resource_obj, $resource_cache_time);
			$this->success('Success', $resource_obj, 0);

		}
		$this->error(__('Invalid parameters'), null, -2);
	}

	/**
	 * @获取弹窗设置
	 * @param custom_id 客户编号
	 * @param mac MAC编号
	 */
	public function popup_setting() {
		$custom_obj = $this->get_custom('id');
		if(!empty($custom_obj)){
			$popup_cache = Cache::get('popup_'.$custom_obj['id'].'_cache');
			if(!empty($popup_cache))
				$this->success('Success', $popup_cache, 0);

			//弹窗广告列表
			$where['custom_id'] = $custom_obj['id'];
			$where['status'] = 'normal';
			$field = 'ad_type, save_set, repeat_set, break_set, weekday, no_repeat_date, start_time, stay_time, position, words_tips,resource_id';
			try{
				$popup_setting_obj = Db::name('popup_setting')->where($where)->field($field)->select();
			}catch (PDOException $e){
				Log::write('获取弹窗设置错误,错误信息为:'.$e->getMessage());
				Log::save();
				$this->error('获取弹窗设置错误', null, -5);
			}
			if(empty($popup_setting_obj))
				$this->success('Success', null, 0);

			try{
				$bind_obj = Db::name('popup_custom')->where('custom_id', 'eq', $custom_obj['id'])->field('rid')->select();
			}catch (PDOException $e){
				Log::write('获取弹窗广告资源绑定错误,错误信息为:'.$e->getMessage());
				Log::save();
				$this->error('获取弹窗广告资源绑定错误', null, -5);
			}
			$rids = array_column($bind_obj, 'rid'); //绑定的资源列表

			$resource_list = [];  //资源列表
			if(!empty($rids)){
				//弹窗广告资源列表
				$resource_field = 'id, title, filepath, file_type, size';
				try{
					$resource_where['id'] = ['in', $rids];
					$resource_where['audit_status'] = 'egis';
					$resource_obj = Db::name('popup_resource')->where($resource_where)->field($resource_field)->select();
				}catch (PDOException $e){
					Log::write('获取弹窗资源错误,错误信息为:'.$e->getMessage());
					Log::save();
					$this->error('获取弹窗资源错误', null, -5);
				}
				if(!empty($resource_obj)){
					foreach ($resource_obj as $k=>$v){
						$resource_list[$v['id']] = $v;
					}
				}
			}

			foreach ($popup_setting_obj as $key=>&$value){
				$resource_id = explode(",", $value['resource_id']);
				foreach ($resource_id as $rid){
					if(0 != intval($rid)){
						$value['resource'][] = isset($resource_list[$rid])? $resource_list[$rid]: [];
					}else{
						$value['resource'] = [];
					}
				}
			}

			$cache_time = Config::get('api_cache_time');
			$popup_cache_time = isset($cache_time['popup'])? $cache_time['popup']: 10;
			Cache::set('popup_'.$custom_obj['id'].'_cache', $popup_setting_obj, $popup_cache_time);
			$this->success('Success', $popup_setting_obj, 0);
		}
		$this->error(__('Invalid parameters'), null, -2);
	}

	/**
	 * @获取定时APP设置
	 * @param custom_id 客户编号
	 */
	public function time_app_setting() {
		$device_obj = $this->get_device('id');
		$custom_obj = $this->get_custom('id');
		if(!empty($custom_obj) && !empty($device_obj)){
			$where['custom_id'] = $custom_obj['id'];
			$where['status'] = 'normal';
			$mac_id = $device_obj['id'];
			$field = "ts.title, ts.data_params, ts.repeat_set, ts.weekday, ts.no_repeat_date, ts.start_time, ts.end_time, ts.out_to, tr.title as app_title, tr.classname, tr.packagename";
			$list = Db::name('timing_app_setting')
				->alias('ts')
				->where($where)
				->where("find_in_set($mac_id,mac_ids)")
				->join('zxt_timing_app_resource tr','ts.app_id=tr.id','LEFT')
				->field($field)
				->select();

			$this->success('Success', $list, 0);
		}
		$this->error(__('Invalid parameters'), null, -2);
	}

	/**
	 * 获取客户列表
	 * @header custom_token value->zxt_custom_token
	 * @param custom_type
	 * @param offset
	 * @param rows
	 */
	public function custom_list() {
		$header_info = $this->request->header();
		if(!isset($header_info['custom_token']) || 'zxt_custom_token' != $header_info['custom_token']){
			$this->error(__('Parameter error'), [], -1);
		}
		$params = $this->request->get();
		$where = [];
		if(isset($params['custom_type'])){
			$where['custom_type'] = $params['custom_type'];
		}
		if(isset($params['status'])){
			$where['status'] = $params['status'];
		}
		$offset = isset($params['offset'])? $params['offset'] -1: 0;
		$rows = isset($params['rows'])? $params['rows']: 10;

		$field = 'custom_id, status, custom_name, full_name, custom_type, handler, phone';
		$list = Db::name('custom')->where($where)->field($field)->limit($offset, $rows)->select();
		$this->success('Success', $list, 0);
	}

	/**
	 * @获取客户信息
	 * @param custom_id 客户编号
	 * @mac Mac编号
	 */
	public function confirm_custom(){
		$params = $this->request->get();
		$where['c.custom_id'] = $params['custom_id'];
		$field = 'c.id, c.custom_id, c.status, c.custom_name, c.full_name, c.custom_type, c.handler, c.phone, zxt_area.mergename as area_name, c.detail_address, c.lng, c.lat';
		$obj = Db::name('custom')
			->alias('c')
			->where($where)
			->join('zxt_area','zxt_custom.area_id=zxt_area.id')
			->field($field)
			->find();
		$this->success('Success', $obj, 0);
	}

}