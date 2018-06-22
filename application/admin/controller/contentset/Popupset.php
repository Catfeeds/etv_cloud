<?php

namespace app\admin\controller\contentset;

use app\common\controller\Backend;
use app\admin\controller\contentset\Customlist;
use think\Cache;
use think\Db;

/**
 * 弹窗设置
 *
 * @icon fa fa-circle-o
 */
class Popupset extends Backend
{
    
    /**
     * PopupSetting模型对象
     */
    protected $model = null;

    protected $modelValidate = true;

    protected $modelSceneValidate = true;

    protected $admin_id = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('PopupSetting');

	    $this->admin_id = $this->auth->id;
    }

	/**
	 * 查看
	 */
	public function index()
	{
		//设置过滤方法
		$this->request->filter(['strip_tags']);
		if ($this->request->isAjax())
		{
			$this->relationSearch = true;
			$this->searchFields = "custom.custom_id";

			$Customlist_class = new Customlist();
			$custom_id = $Customlist_class->custom_id($this->admin_id);
			$where_customid['zxt_popup_setting.custom_id'] = ['in', $custom_id];

			list($where, $sort, $order, $offset, $limit) = $this->buildparams();
			$total = $this->model
				->where($where)
				->with('custom')
				->order($sort, $order)
				->count();

			$list = $this->model
				->where($where)
				->with('custom')
				->with("resource")
				->order($sort, $order)
				->limit($offset, $limit)
				->select();

			//获取资源分配给客户的绑定关系
			$bind_obj = Db::name('popup_custom')->where('custom_id', 'in', $custom_id)->field('rid')->select();
			$rids = array_column($bind_obj, 'rid'); //绑定rid列表

			$cdnurl = preg_replace("/\/(\w+)\.php$/i", '', $this->request->root());
			foreach ($list as $k => &$v)
			{
				if(in_array($v['resource_id'], $rids)){ //判断资源是否被取消绑定
					if($v['ad_type'] == 'image' || $v['ad_type']=='video'){
						$v['fullurl'] = $cdnurl . $v['resource']['filepath'];
					}else{
						$v['fullurl'] = '';
					}
				}else{
					$v['fullurl'] = '';
				}

			}
			unset($v);

			$list = collection($list)->toArray();
			$result = array("total" => $total, "rows" => $list);

			return json($result);
		}
		return $this->view->fetch();
	}

	/**
	 * 添加
	 */
	public function add()
	{
		if ($this->request->isPost())
		{
			$params = $this->request->post("row/a");
			if ($params)
			{
				try
				{
					// 判断客户列表是否存在
					$custom_key = Cache::get($this->admin_id.'-popup-customlist');
					if(!in_array($params['custom_id'], $custom_key)){
						$this->error(__('Parameter error'));
					}

					//是否采用模型验证
					if ($this->modelValidate)
					{
						$name = basename(str_replace('\\', '/', get_class($this->model)));
						$validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.add' : true) : $this->modelValidate;
						$this->model->validate($validate);
					}
					//数据处理
					$params = $this->params_handle($params);
					$result = $this->model->allowField(true)->save($params);
					if ($result !== false)
					{
						Cache::rm($this->auth->id.'-popup-customlist');
						$this->success();
					}
					else
					{
						$this->error($this->model->getError());
					}
				}
				catch (\think\exception\PDOException $e)
				{
					$this->error($e->getMessage());
				}
			}
			$this->error(__('Parameter %s can not be empty', ''));
		}

		$this->get_option();
		return $this->view->fetch();
	}

	public function edit($ids = NULL){
		$row = $this->model->get($ids);
		if (!$row)
			$this->error(__('No Results were found'));

		if ($this->request->isPost())
		{
			$params = $this->request->post("row/a");
			if ($params)
			{
				try
				{
					if ($this->modelValidate)
					{
						$name = basename(str_replace('\\', '/', get_class($this->model)));
						$validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : true) : $this->modelValidate;
						$row->validate($validate);
					}

					//数据处理
					$params = $this->params_handle($params);
					$result = $row->allowField(true)->save($params);
					if ($result !== false)
					{
						$this->success();
					}
					else
					{
						$this->error($row->getError());
					}
				}
				catch (\think\exception\PDOException $e)
				{
					$this->error($e->getMessage());
				}
			}
			$this->error(__('Parameter %s can not be empty', ''));
		}
		$this->get_option($row);
		return $this->view->fetch();
	}

	/**
	 * 基础表单选项
	 */
	private function get_option($row = NULL){
		$Customlist_class = new Customlist();
		$customlist = $Customlist_class->custom_list($this->admin_id);
		if(empty($customlist))
			$this->error(__('You have no permission'));
		Cache::set($this->admin_id.'-popup-customlist', array_column($customlist, 'id'), 36000); //设置10小时缓存,用于判断客户ID
		$custom_lists = [];
		foreach($customlist as $cv){
			$custom_lists[$cv['id']] = $cv['custom_name'];//客户列表
		}

		$ad_type_info = config('ad_type'); //广告类型
		foreach($ad_type_info as &$ad_type_value){
			$ad_type_value = __($ad_type_value);
		}

		$repeat_set_info = config('repeat_set');  //重复设置
		foreach($repeat_set_info as &$repeat_set_value){
			$repeat_set_value = __($repeat_set_value);
		}

		$save_set_info = config('save_set'); //存储位置列表
		foreach($save_set_info as &$sav){
			$sav = __($sav);
		}

		$weekday_info = config('weekday');  //周期,星期几
		foreach($weekday_info as &$wv){
			$wv = __($wv);
		}

		$break_set_info = config('popup_break_set'); //退出设置
		foreach($break_set_info as &$bsv){
			$bsv = __($bsv);
		}

		$popup_position_set_info = config('popup_position_set');  //弹窗位置设置
		foreach($popup_position_set_info as &$psv){
			$psv = __($psv);
		}

		// 资源的处理
		if(!empty($row)){
			$resource = Db::name('popup_resource')->where('id', 'eq', $row['resource_id'])->field('filepath')->find();
			if($row['ad_type'] == 'video'){
				$row['video_resource_id'] = $row['resource_id'];
				$row['video_resource'] = $resource['filepath'];
				$row['image_resource_id'] = 0;
				$row['image_resource'] = '';
			}elseif($row['ad_type'] == 'image'){
				$row['image_resource_id'] = $row['resource_id'];
				$row['image_resource'] = $resource['filepath'];
				$row['video_resource_id'] = 0;
				$row['video_resource'] = '';
			}else{
				$row['image_resource'] = '';
				$row['video_resource'] = '';
				$row['image_resource_id'] = 0;
				$row['video_resource_id'] = 0;
			}

		}

		$this->view->assign('custom_lists', $custom_lists);
		$this->view->assign('ad_type_info', $ad_type_info);
		$this->view->assign('repeat_set_info', $repeat_set_info);
		$this->view->assign('save_set_info', $save_set_info);
		$this->view->assign('weekday_info', $weekday_info);
		$this->view->assign('break_set_info', $break_set_info);
		$this->view->assign('popup_position_set_info', $popup_position_set_info);
		$this->view->assign('row', $row);
	}

	/**
	 * 参数处理
	 */
	private function params_handle($params){
		$return = [];
		//重复设置 / 时间设置
		if($params['repeat_set'] == 'no-repeat'){
			$params['start_time'] = date('H:i:s', strtotime($params['no_repeat_date']) );
			$params['no_repeat_date'] = date('Y-m-d', strtotime($params['no_repeat_date']));
			$params['weekday'] = 0;
		}elseif($params['repeat_set'] == 'everyday'){
			$params['no_repeat_date'] = date("Y-m-d H:i:s");
			$params['weekday'] = '1,2,3,4,5,6,7';
		}elseif($params['repeat_set'] == 'm-f'){
			$params['no_repeat_date'] = date("Y-m-d");
			$params['weekday'] = '1,2,3,4,5';
		}elseif($params['repeat_set'] == 'user-defined'){
			$params['no_repeat_date'] = date("Y-m-d");
			$params['weekday'] = implode(",", $params['weekday']);
		}

		//资源ID
		if($params['ad_type'] == 'video'){
			$params['resource_id'] = $params['video_resource_id'];
		}elseif($params['ad_type'] == 'image'){
			$params['resource_id'] = $params['image_resource_id'];
		}else{
			$params['resource_id'] = 0;
		}

		return $params;
	}

	/**
	 * 资源列表
	 */
	public function select($custom_id="", $mimetype=""){

		if ($this->request->isAjax())
		{
			$this->relationSearch = true;
			$this->request->filter(['strip_tags']);
			$filter = $this->request->get("filter", '');
			$filter_decode = json_decode($filter, true);
			$where['zxt_popup_custom.custom_id'] = $filter_decode['custom_id'];
			$where['resource.file_type'] = array('eq', $filter_decode['mimetype']);

			$total = model('PopupCustom')
				->with('resource')
				->where($where)
				->count();

			$list = model('PopupCustom')
				->with('resource')
				->where($where)
				->select();
			$cdnurl = preg_replace("/\/(\w+)\.php$/i", '', $this->request->root());
			foreach ($list as $k => &$v)
			{
				$v['fullurl'] = $cdnurl . $v['resource']['filepath'];
			}
			unset($v);
			$result = array("total" => $total, "rows" => $list);

			return json($result);
		}

		$this->assignconfig('custom_id', $custom_id);
		$this->assignconfig('mimetype', $mimetype);
		return $this->view->fetch();
	}
}
