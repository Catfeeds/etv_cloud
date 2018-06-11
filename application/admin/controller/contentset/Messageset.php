<?php

namespace app\admin\controller\contentset;

use app\admin\model\DeviceBasics;
use app\common\controller\Backend;
use app\admin\controller\contentset\Customlist;
use think\Cache;


/**
 * 
 *
 * @icon fa fa-circle-o
 */
class Messageset extends Backend
{
    
    /**
     * MessageNotice模型对象
     */
    protected $model = null;

	protected $modelValidate = true;

	protected $modelSceneValidate = true;

	protected $admin_id = null;

	protected $noNeedLogin = ['get_device_list_by_custom'];
	protected $noNeedRight = ['get_device_list_by_custom'];

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('MessageNotice');

	    $this->admin_id = $this->auth->id;
    }

    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax())
        {
	        $this->relationSearch = true;
	        $this->searchFields = "custom.custom_id";
	        list($where, $sort, $order, $offset, $limit) = $this->buildparams();

	        $Customlist = new Customlist();
	        $where_customid['zxt_message_notice.custom_id'] = ['in', $Customlist->custom_id($this->admin_id)];

	        $total = $this->model
                ->where($where)
		        ->where($where_customid)
                ->with('custom')
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->where($where)
	            ->where($where_customid)
	            ->with('custom')
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
				try
				{
					// 时间处理
					if('immediate' == $params['push_type']){
						$params['push_start_time'] = date("Y-m-d H:i:s");
					}
					//是否采用模型验证
					if ($this->modelValidate)
					{
						$name = basename(str_replace('\\', '/', get_class($this->model)));
						$validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.add' : true) : $this->modelValidate;
						$this->model->validate($validate);
					}
					if('user defined' != $params['push_type'])
						$params['push_start_time'] = '';
					$result = $this->model->allowField(true)->save($params);
					if ($result !== false)
					{
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

		$Customlist = new Customlist();
		$get_custom_list = $Customlist->custom_list($this->admin_id);
		Cache::set($this->admin_id.'-message-customlist', array_column($get_custom_list, 'id'), 36000); //设置10小时缓存,用于判断客户ID
		foreach($get_custom_list as $key => $v){
			$custom_lists[$v['id']] = $v['custom_name'];
		}
		$push_info = config('message_push_type');
		foreach($push_info as &$value){
			$value = __($value);
		}
		$this->view->assign('push_info', $push_info);
		$this->view->assign('custom_lists', $custom_lists);//客户列表
		return $this->view->fetch();
	}

	/**
	 * 编辑
	 */
	public function edit($ids = NULL)
	{
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
					$params['custom_id'] = $row['custom_id'];
					// 时间处理
					if('immediate' == $params['push_type']){
						$params['push_start_time'] = date("Y-m-d H:i:s");
					}
					//是否采用模型验证
					if ($this->modelValidate)
					{
						$name = basename(str_replace('\\', '/', get_class($this->model)));
						$validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : true) : $this->modelValidate;
						$row->validate($validate);
					}
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

		// 获取推送类型
		$push_info = config('message_push_type');
		foreach($push_info as &$value){
			$value = __($value);
		}

		//获取设备列表
		$where['custom_id'] = $row['custom_id'];
		$selected = explode(",", $row['mac_ids']);
		$nodeList = DeviceBasics::getDeviceTreeList($where, $selected);

		$this->view->assign("row", $row);
		$this->view->assign('push_info', $push_info);
		$this->view->assign("nodeList", $nodeList);
		return $this->view->fetch();
	}

	public function get_device_list_by_custom($custom_id){
		if(empty($custom_id)){
			$this->error(__('Unknown data format'));
		}
		$where['custom_id'] = $custom_id;
		return DeviceBasics::getDeviceTreeList($where);
	}


}
