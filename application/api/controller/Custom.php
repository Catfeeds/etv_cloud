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
use think\exception\PDOException;
use think\Log;

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
		return Db::name('custom')->where($where)->cache('custom_obj', 60)->field($field)->find();
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
		return Db::name('device_basics')->where($where)->field($field)->find();
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
			$where['wr.audit_status'] = 'egis';
			$cache_time = Config::get('api_cache_time');
			$welcome_cache_time = isset($cache_time['welcome'])? $cache_time['welcome']: 0;
			$field = 'wc.id, wc.title, wc.stay_set, wc.stay_time, wc.weigh, wc.audit_status, wr.filepath, wr.file_type, wr.size';
			$welcome_obj = Db::name('welcome_custom')
								->alias('wc')
								->cache($welcome_cache_time)
								->join('zxt_welcome_resource wr', 'wc.rid=wr.id')
								->where($where)
								->field($field)
								->select();
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
			$language_cache_time = isset($cache_time['language'])? $cache_time['language']: 0;
			$language_obj = Db::name('language_setting')->where($where)->cache($language_cache_time)->field($field)->find();
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
			if (!empty($return['results'])) {
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
				}catch (\PDOException $e){
					Log::write('插入天气出错,客户ID为:'.$custom_obj['id']);
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
			$notice_cache_time = isset($cache_time['notice'])? $cache_time['notice']: 0;
			$field = 'title, content, push_type, push_start_time, push_end_time';
			$notice_obj = Db::name('message_notice')->where($notice_cache_time)->field($field)->select();
			$this->success('Success', $notice_obj, 0);
		}
		$this->error(__('Invalid parameters'), null, -2);
	}

	public function jump_setting() {
		$custom_obj = $this->get_custom('id');
		if(!empty($custom_obj)){
			$cache_time = Config::get('api_cache_time');
			$jump_cache_time = isset($cache_time['jump'])? $cache_time['jump']: 0;
			//获取跳转设置
			$where['custom_id'] = $custom_obj['id'];
			$setting_obj = Db::name('jump_setting')->where($where)->field('play_set, save_set')->find();
			if(empty($setting_obj))
				$this->success('Success', null, 0);

			//获取跳转资源
			$where_resource['jc.custom_id'] = $custom_obj['id'];
			$where_resource['jc.status'] = 'normal';
			$where_resource['jr.audit_status'] = 'egis';
			$field = 'jc.audit_status, jr.filepath, jr.file_type, jr.size';
			$resource_obj = Db::name('jump_custom')
									->alias('jc')
									->where($where)
									->join('zxt_jump_resource jr', 'jc.rid=jr.id','LEFT')
									->field($field)
									->cache($jump_cache_time)
									->select();

			$setting_obj['resource'] = collection($resource_obj)->toArray();

			$this->success('Success', $setting_obj, 0);
		}
		$this->error(__('Invalid parameters'), null, -2);
	}
}