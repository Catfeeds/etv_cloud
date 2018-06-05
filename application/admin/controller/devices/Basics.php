<?php

namespace app\admin\controller\devices;

use app\common\controller\Backend;
use app\admin\controller\devices\Customlist;
use think\Cache;
use think\Db;
use think\Exception;
use think\Log;

/**
 * 设备基础信息管理
 *
 * @icon fa fa-circle-o
 */
class Basics extends Backend
{
    
    /**
     * DeviceBasics模型对象
     */
    protected $model = null;

	// 登录账号绑定的客户ID列表
	protected $custom_ids = [0];

	protected $modelValidate = true;

	protected $modelSceneValidate = true;

	protected $admin_id = null;

	protected $orderFields = 'order';

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('DeviceBasics');

	    $this->customlist_class = new Customlist;

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
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();

	        $customid_list = $this->customlist_class->custom_id_device($this->admin_id);
	        if(!is_array($customid_list) && $customid_list == config('get all')){
		        $where_customid = [];
	        }else{
		        $where_customid['zxt_device_basics.custom_id'] = ['in', $customid_list];
	        }

            $total = $this->model
                ->where($where)
	            ->where($where_customid)
                ->with("custom")
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->where($where)
	            ->where($where_customid)
                ->with("custom")
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

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
				// 判断客户列表是否存在
				$custom_key = Cache::get($this->admin_id.'-device-basics-customlist');
				if(!in_array($params['custom_id'], $custom_key)){
					$this->error(__('Parameter error'));
				}
				try
				{
					//是否采用模型验证
					if ($this->modelValidate)
					{
						$name = basename(str_replace('\\', '/', get_class($this->model)));
						$validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.add' : true) : $this->modelValidate;
						$this->model->validate($validate);
					}
					$result = $this->model->allowField(true)->save($params);
					if ($result !== false)
					{
						Cache::rm($this->auth->id.'-device-basics-customlist');
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

		$customlist = $this->customlist_class->custom_list($this->admin_id);
		if(empty($customlist))
			$this->error(__('You have no permission'));

		Cache::set($this->admin_id.'-device-basics-customlist', array_column($customlist, 'id'), 36000); //设置10小时缓存,用于判断客户ID
		foreach($customlist as $cv){
			$custom_lists[$cv['id']] = $cv['custom_name'];//客户列表
		}

		$this->view->assign('custom_lists', $custom_lists);
		return $this->view->fetch();
	}

	/**
	 * 批量更新
	 */
	public function order($ids = "")
	{
		$ids = $ids ? $ids : $this->request->param("ids");
		if ($ids)
		{
			if ($this->request->has('params'))
			{
				parse_str($this->request->post("params"), $values);
				$values = array_intersect_key($values, array_flip(is_array($this->orderFields) ? $this->orderFields : explode(',', $this->orderFields)));
				if ($values)
				{
					$data = [];
					if($values['order'] == 'reboot'){
						$data['reboot_set'] = $values['order'];
						$data['reboot_set_time'] = time();
					}elseif($values['order'] == 'clean all' or $values['order'] == 'clean rom' or $values['order'] == 'clean sd'){
						$data['clean_set'] = $values['order'];
						$data['clean_set_time'] = time();
					}else{
						$this->error(__('Invalid parameters'));
					}
					$data['lately_order'] = $values['order'];
					Db::startTrans();
					try{
						Db::name('device_basics')->where('id', 'in', $ids)->update($data);
					}catch(\think\exception\PDOException $e){
						Log::write($e->getMessage());
						Log::save();
						Db::rollback();
						$this->error(__('Operation failed'));
					}
					Db::commit();
					$this->success();
				}
				else
				{
					$this->error(__('You have no permission'));
				}
			}
		}
		$this->error(__('Parameter %s can not be empty', 'ids'));
	}

	/**
	 * 设备详情
	 */
	public function detail($ids = "") {
		$where_mac = Db::name('device_basics')->where('id', 'eq', $ids)->field('mac')->find();
		if($where_mac){
			$row = Db::name('device_detail')->where($where_mac)->find();
		}else{
			$this->error(__('No results were found'));
		}
		$this->view->assign('row', $row);
		return $this->view->fetch();
	}

	/**
	 * 指令集
	 */
	public function directive($ids = "") {
		$row = Db::name('device_basics')->where('id', 'eq', $ids)->find();
		if($row){
			$row['reboot_set'] = isset($row['reboot_set'])?$row['reboot_set']:'no-set';
			$row['clean_set'] = isset($row['clean_set'])?$row['clean_set']:'no-set';
			$row['wifi_set'] = isset($row['wifi_set'])?$row['wifi_set']:'no-set';
			$row['sleep_set'] = isset($row['sleep_set'])?$row['sleep_set']:'no-set';
			$row['reboot_result'] = isset($row['reboot_result'])?$row['reboot_result']:'pending';
			$row['clean_result'] = isset($row['clean_result'])?$row['clean_result']:'pending';
			$row['wifi_result'] = isset($row['wifi_result'])?$row['wifi_result']:'pending';
			$row['sleep_result'] = isset($row['sleep_result'])?$row['sleep_result']:'pending';
			$row['reboot_set_time'] = isset($row['reboot_set_time'])?date("Y-m-d H:i:s", $row['reboot_set_time']):'';
			$row['reboot_result_time'] = isset($row['reboot_result_time'])?date("Y-m-d H:i:s", $row['reboot_result_time']):'';
			$row['clean_set_time'] = isset($row['clean_set_time'])?date("Y-m-d H:i:s", $row['clean_set_time']):'';
			$row['clean_result_time'] = isset($row['clean_result_time'])?date("Y-m-d H:i:s", $row['clean_result_time']):'';
			$row['wifi_set_time'] = isset($row['wifi_set_time'])?date("Y-m-d H:i:s", $row['wifi_set_time']):'';
			$row['wifi_result_time'] = isset($row['wifi_result_time'])?date("Y-m-d H:i:s", $row['wifi_result_time']):'';
		}else{
			$this->error(__('No results were found'));
		}
		$this->view->assign('row', $row);
		return $this->view->fetch();
	}

}
