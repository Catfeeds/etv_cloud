<?php

namespace app\admin\controller\contentset;

use app\admin\model\ColumnCustom;
use app\common\controller\Backend;
use app\admin\controller\contentset\Customlist;
use fast\Tree;
use think\Db;

/**
 * 客户栏目资源绑定管理
 *
 * @icon fa fa-circle-o
 */
class Columnset extends Backend
{
    
    /**
     * ColumnCustom模型对象
     */
    protected $model = null;

	protected $modelValidate = true;

	protected $noNeedLogin = []; //无需登录
	protected $noNeedRight = []; //无需鉴权

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('ColumnCustom');
    }

    /**
     * 查看
     */
    public function index()
    {
    	$Customlist_class = new Customlist();
	    $custom_id_list = $Customlist_class->custom_id_by_column($this->auth->id); //获取账号绑定的客户列表
	    $this->request->filter(['strip_tags']);
	    if ($this->request->isAjax())
	    {
		    if(!empty($custom_id_list)){  //判断自身绑定列表是否为空
		        $filter = $this->request->get("filter", '');
		        $filter_decode = json_decode($filter, true);  //获取动态更换客户ID
		        if(isset($filter_decode['custom_id'])){
			        if(in_array($filter_decode['custom_id'], $custom_id_list)){  //动态更换的客户ID鉴权
				        $first_custom_id = $filter_decode['custom_id'];
			        }else{
				        $first_custom_id = $custom_id_list['0'];
			        }
		        }else{
			        $first_custom_id = $custom_id_list['0'];
		        }
		        $list = $this->column_list($first_custom_id);
	        }else{
				$list = [];
	        }
	        $total = count($list);
	        $result = array("total" => $total, "rows" => $list);
	        return json($result);
        }

	    $custom = Db::name('custom')->where('id', 'in', $custom_id_list)->field('id,custom_name')->order('field(id,'.implode(",",$custom_id_list).')')->select();
	    $custom_list = [];
	    foreach($custom as $ck => $cv) {
		    $custom_list[$cv['id']] = $cv['custom_name'];
	    }
	    $this->view->assign('custom_list', $custom_list);
        return $this->view->fetch();
    }

	/**
	 * 获取栏目列表方法
	 * @$first_custom_id 客户ID
	 */
	public function column_list($first_custom_id){

		$column_custom_list = Db::name('column_custom')->where('custom_id', 'eq', $first_custom_id)->select();
		$rid_weigh_status = []; //rid 对应的排序和状态
		$rid_save_set = []; //rid对应的存储设定
		$ccid_list = []; //绑定表id列表
		foreach($column_custom_list as $column_value){
			$rid_weigh_status[$column_value['rid']]['column_weigh'] = $column_value['column_weigh']?json_decode($column_value['column_weigh'], true):null;
			$rid_weigh_status[$column_value['rid']]['column_status'] = $column_value['column_status']?json_decode($column_value['column_status'], true):null;
//			$rid_weigh_status[$column_value['rid']]['resource_status'] = $column_value['resource_status']?json_decode($column_value['resource_status'], true):null;
//			$rid_weigh_status[$column_value['rid']]['resource_weigh'] = $column_value['resource_weigh']?json_decode($column_value['resource_weigh'], true):null;
			$rid_weigh_status[$column_value['rid']]['column_audit_status'] = $column_value['column_audit_status']?json_decode($column_value['column_audit_status'], true):null;
			$rid_save_set[$column_value['rid']]['save_set'] = $column_value['save_set'];
			$ccid_list[$column_value['rid']]['id'] = $column_value['id'];
		}
		if(!empty($column_custom_list)){
			$column_id = array_column($column_custom_list, 'rid');  //客户绑定的栏目列表ID
			$column_where['fpid'] = ['in', $column_id];

			$list = collection(model('column')->where($column_where)->select())->toArray();

			$weigh_list = []; //排序列表
			$root_url = preg_replace("/\/(\w+)\.php$/i", '', $this->request->root());
			foreach($list as $k => &$v){
				//处理fullurl
				if(empty($v['filepath'])){
					$v['fullurl'] = '';
				}else{
					$v['fullurl'] = $root_url.$v['filepath'];
				}

				//处理存储位置
				if($v['level'] == 1){
					$v['save_set'] = $rid_save_set[$v['id']]['save_set'];
				}else{
					$v['save_set'] = 0;
				}

				//处理排序和状态
				$v['weigh'] = isset($rid_weigh_status[$v['fpid']]['column_weigh'][$v['id']])?$rid_weigh_status[$v['fpid']]['column_weigh'][$v['id']]:'100';
				$v['status'] = isset($rid_weigh_status[$v['fpid']]['column_status'][$v['id']])?$rid_weigh_status[$v['fpid']]['column_status'][$v['id']]:'hidden';
				$v['audit_status'] = isset($rid_weigh_status[$v['fpid']]['column_audit_status'][$v['id']])?$rid_weigh_status[$v['fpid']]['column_audit_status'][$v['id']]:'no release';
				array_push($weigh_list, $v['weigh']);

				//处理所属绑定表ID
				$list[$k]['ccid'] = $ccid_list[$v['fpid']]['id'];
			}
			unset($v);

			array_multisort($weigh_list, SORT_ASC, SORT_NUMERIC, $list); //按照weigh排序

			//树状处理
			$tree = Tree::instance();
			$tree->init($list, 'pid');
			$category_data = $tree->getTreeList($tree->getTreeArray(0), 'title');

			return $category_data;
		}else{
			return [];
		}

	}

	/**
	 * 启用禁用状态
	 */
	public function status(){
		$params = $this->request->param();
		$params = $params['params'];
		if ($params)
		{
			if(empty($params['ccid']) || empty($params['id']) || empty($params['status']))
				$this->error(__('Parameter error'));

			$column_custom_info = Db::name('column_custom')
							->where('id', 'eq', $params['ccid'])
							->field('rid, column_status')
							->find();
			if(empty($column_custom_info))
				$this->error(__('No results were found'));

			if(!empty($column_custom_info['column_status'])){
				$column_status = json_decode($column_custom_info['column_status'], true);
				$column_status[$params['id']] = $params['status'];
				$column_status = json_encode($column_status);
			}else{
				$column_status_id = Db::name('column')->where('fpid', 'eq', $column_custom_info['rid'])->field('id')->select();
				if(!empty($column_status_id)){
					$column_status = [];
					foreach($column_status_id as $status_value){
						if($params['id'] != $status_value['id']){
							$column_status[$status_value['id']] = 'hidden';
						}else{
							$column_status[$status_value['id']] = $params['status'];
						}
					}
					$column_status = json_encode($column_status);
				}else{
					$column_status = NULL;
				}
			}

			$count = $this->model->allowField(true)
								->where('id', 'eq', $params['ccid'])
								->update(['column_status'=>$column_status]);
			if ($count)
			{
				$this->success();
			}
			else
			{
				$this->error(__('No rows were updated'));
			}
		}
		$this->error(__('Parameter error'));
	}

	/**
	 * 编辑
	 */
	public function edit($ccid=NULL,$ids = NULL)
	{
		$column_custom_info = $this->model->get($ccid); //绑定列表信息
		if(!$column_custom_info)
			$this->error(__('No Results were found'));

		//判断权限
		$Customlist_class = new Customlist();
		$custom_id_list = $Customlist_class->custom_id_by_column($this->auth->id);
		if(!in_array($column_custom_info['custom_id'], $custom_id_list))
			$this->error(__('You have no permission'));

		if ($this->request->isPost())
		{
			$params = $this->request->post("row/a");
			if ($params)
			{
				try
				{
					if ($this->modelValidate)
					{
						$result = $this->validate($params, 'ColumnCustom.edit');
						if(true !== $result){
							$this->error($result);
						}
					}
					//数据处理
					$data = $this->edit_data_process($column_custom_info, $params, $ids);
					if(!$data)
						$this->error(__('Parameter error'));
					$result = $column_custom_info->allowField(true)->save($data);
					if ($result !== false)
					{
						$this->success();
					}
					else
					{
						$this->error();
					}
				}
				catch (\think\exception\PDOException $e)
				{
					$this->error($e->getMessage());
				}
			}
			$this->error(__('Parameter %s can not be empty', ''));
		}

		$get_column_field = 'id,admin_id,fpid,level';
		$row = model('column')->where('id', 'eq', $ids)->field($get_column_field)->find();
		if (!$row)
			$this->error(__('No Results were found'));

		$edit_data = []; //可修改数据
		// 获取栏目的状态
		if(!empty($column_custom_info['column_status'])){
			$column_status = json_decode($column_custom_info['column_status'], true);
			$edit_data['status'] = $column_status[$ids];
		}else{
			$edit_data['status'] = 'hidden';
		}
		$status_info = ['normal'=>__('normal'), 'hidden'=>__('hidden')]; //状态列表

		// 获取栏目的排序
		if(!empty($column_custom_info['column_weigh'])){
			$column_weigh = json_decode($column_custom_info['column_weigh'], true);
			$edit_data['weigh'] = $column_weigh[$ids];
		}else{
			$edit_data['weigh'] = NULL;
		}

		//获取栏目存储位置
		if($row['level'] != 1){
			$edit_data['save_set'] = 0;
		}else{
			$edit_data['save_set'] = $column_custom_info['save_set']; //获取栏目存储位置
		}
		$save_set_info = config('save_set'); //存储位置列表
		foreach($save_set_info as &$sav){
			$sav = __($sav);
		}

		$this->view->assign("row", $edit_data);
		$this->view->assign('save_at_info', $save_set_info);
		$this->view->assign('status', $status_info);
		return $this->view->fetch();
	}

	/**
	 * 栏目修改数据处理
	 */
	public function edit_data_process($column_custom_info=NULL, $params=NULL, $id=NULL){
		if(!$column_custom_info || !$params || !$id){
			return false;
		}
		$return = [];//返回数据
		//排序处理
		if(!empty($column_custom_info['column_weigh'])){
			$column_weigh = json_decode($column_custom_info['column_weigh'], true);
			$column_weigh[$id] = $params['weigh'];
			$return['column_weigh'] = json_encode($column_weigh);
		}else{
			$column_weigh_id = Db::name('column')->where('fpid', 'eq', $column_custom_info['rid'])->field("id")->select();
			if(!empty($column_weigh_id)){
				$column_weigh = [];
				foreach($column_weigh_id as $weigh_value){
					if($id != $weigh_value['id']){
						$column_weigh[$weigh_value['id']] = 100;
					}else{
						$column_weigh[$id] = $params['weigh'];
					}
				}
				$return['column_weigh'] = json_encode($column_weigh);
			}else{
				$return['column_weigh'] = NULL;
			}
		}
		//状态操作
		if(!empty($column_custom_info['column_status'])){
			$column_status = json_decode($column_custom_info['column_status'], true);
			$column_status[$id] = $params['status'];
			$return['column_status'] = json_encode($column_status);
		}else{
			$column_status_id = Db::name('column')->where('fpid', 'eq', $column_custom_info['rid'])->field("id")->select();
			if(!empty($column_status_id)){
				$column_status = [];
				foreach($column_status_id as $status_value){
					if($id != $status_value['id']){
						$column_status[$status_value['id']] = 'hidden';
					}else{
						$column_status[$id] = $params['status'];
					}
				}
				$return['column_status'] = json_encode($column_status);
			}else{
				$return['column_status'] = NULL;
			}
		}
		if($column_custom_info['rid'] == $id){
			$return['save_set'] = $params['save_set'];
		}
		return $return;
	}

	/**
	 * 栏目资源
	 */
	public function resources($ccid=NULL, $id=NULL){

		if ($this->request->isAjax()){
			$this->request->filter(['strip_tags']);
			$filter = $this->request->get("filter", '');
			$filter_decode = json_decode($filter, true);
			$ccid = $filter_decode['ccid'];
			$id = $filter_decode['id'];

			$column_custom_info = $this->model->get($ccid); //绑定关系表
			$this->request->filter(['strip_tags']);
			if(!empty($column_custom_info)){
				$resource_field = 'id,title,describe,resource,resource_type,size,audit_status as egis_status';
				$resources_list = Db::name('col_resource')->where('column_pid', 'eq', $id)->field($resource_field)->select();
				if(!empty($resources_list)){

					//资源状态
					if(!empty($column_custom_info['resource_status'])){
						$resource_status = json_decode($column_custom_info['resource_status'], true);
					}

					//排序状态
					if(!empty($column_custom_info['resource_weigh'])){
						$resource_weigh = json_decode($column_custom_info['resource_weigh'], true);
					}

					//发布状态
					if(!empty($column_custom_info['resource_audit_status'])){
						$resource_audit_status = json_decode($column_custom_info['resource_audit_status'], true);
					}

					$cdnurl = preg_replace("/\/(\w+)\.php$/i", '', $this->request->root()); //全链接
					foreach ($resources_list as $k => &$v)
					{
						//全链接
						if(trim($v['resource_type']) != 'url'){
							$v['fullurl'] = $cdnurl.$v['resource'];
						}else{
							$v['fullurl'] = $v['resource'];
						}

						//添加ccid
						$v['ccid'] = $ccid;

						//添加状态
						$v['status'] = isset($resource_status[$v['id']])?$resource_status[$v['id']]:'hidden';
						//添加排序
						$v['weigh'] = isset($resource_weigh[$v['id']])?$resource_weigh[$v['id']]:100;
						//添加发布状态
						$v['release_status'] = isset($resource_audit_status[$v['id']])?$resource_audit_status[$v['id']]:'no release';
					}
					unset($v);
				}
				array_multisort(array_column($resources_list, 'weigh'), SORT_ASC, $resources_list);
				$total = count($resources_list);
				$result = array("total" => $total, "rows" => $resources_list);
				return json($result);
			}else{
				$result = array("total" => 0, "rows" => NULL);
				return json($result);
			}
		}
		if(!$ccid || !$id)
			$this->error(__('Parameter error'));
		$this->assignconfig('ccid', $ccid);
		$this->assignconfig('id', $id);
		return $this->view->fetch();
	}

	/**
	 * 栏目资源状态
	 */
	public function resource_status(){
		$params = $this->request->param();
		$params = $params['params'];
		if ($params)
		{
			if(empty($params['ccid']) || empty($params['id']) || empty($params['status']))
				$this->error(__('Parameter error'));

			$column_custom_info = Db::name('column_custom')
				->where('id', 'eq', $params['ccid'])
				->field('rid, resource_status')
				->find();
			if(empty($column_custom_info))
				$this->error(__('No results were found'));

			if(!empty($column_custom_info['resource_status'])){
				$resource_status = json_decode($column_custom_info['resource_status'], true);
				$resource_status[$params['id']] = $params['status'];
				$resource_status = json_encode($resource_status);
			}else{
				$resource_status_id = Db::name('col_resource')->where('column_fpid', 'eq', $column_custom_info['rid'])->field('id')->select();

				if(!empty($resource_status_id)){
					$resource_status = [];
					foreach($resource_status_id as $status_value){
						if($params['id'] != $status_value['id']){
							$resource_status[$status_value['id']] = 'hidden';
						}else{
							$resource_status[$status_value['id']] = $params['status'];
						}
					}
					$resource_status = json_encode($resource_status);
				}else{
					$resource_status = NULL;
				}
			}

			$count = $this->model->allowField(true)
				->where('id', 'eq', $params['ccid'])
				->update(['resource_status'=>$resource_status]);
			if ($count)
			{
				$this->success();
			}
			else
			{
				$this->error(__('No rows were updated'));
			}
		}
		$this->error(__('Parameter error'));
	}

	/**
	 * 栏目资源排序
	 */
	public function resource_dragsort () {

		$ids = $this->request->post("ids");
		$ccid = $this->request->post('ccid');

		if(empty($ids) || empty($ccid))
			$this->error(__('Parameter error'));

		$column_custom_info = ColumnCustom::get($ccid);
		if(empty($column_custom_info))
			$this->error(__('No results were found'));

		$i = 1;
		$ids_arr = explode(",", $ids);
		if(!empty($column_custom_info->resource_weigh)){
			$resource_weigh = json_decode($column_custom_info->resource_weigh, true);
			//删除原来的key和value
			foreach($resource_weigh as $key => $value){
				if(in_array($key, $ids_arr)){
					unset($resource_weigh[$key]);
				}
			}
			//添加新的key和value
			foreach($ids_arr as $value){
				$new_resource_weigh[$value] = $i;
				++$i;
			}
			$resource_weigh = $resource_weigh+$new_resource_weigh;

		}else{
			foreach($ids_arr as $value){
				$resource_weigh[$value] = $i;
				++$i;
			}
		}
		$resource_weigh = json_encode($resource_weigh);
		$column_custom_info->resource_weigh = $resource_weigh;
		$column_custom_info->save();
		$this->success();
	}
}
