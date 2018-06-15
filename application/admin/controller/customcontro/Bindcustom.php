<?php

namespace app\admin\controller\customcontro;

use app\common\controller\Backend;
use think\Db;
use app\admin\model\Custom;
use think\exception\PDOException;

/**
 * 客户绑定
 *
 * @icon fa fa-circle-o
 */
class Bindcustom extends Backend
{
    
    /**
     * AdminCustomBind模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('AdminCustomBind');

    }
    
    public function index()
    {
	    //设置过滤方法
	    $this->request->filter(['strip_tags']);
	    if ($this->request->isAjax())
	    {
		    $filter = $this->request->get("filter", '');
		    $filter_decode = json_decode($filter, true);  //获取动态更换账号ID
		    if(isset($filter_decode['admin_id'])){
				$where['admin_id'] = $filter_decode['admin_id'];
		    }else{
			    //取第一个
			    $Admin = Db::name('admin')->field('id,username')->select();
			    $where['admin_id'] = $Admin[0]['id'];
		    }
	    	$vo = $this->model->where($where)->find();
	    	if(!empty($vo) && !empty($vo['custom_id'])){
	    		$custom_field = 'id, custom_id, custom_name, handler, phone';
	    		$total = count(explode(",", $vo['custom_id']));
				$list = Db::name('custom')->where('id', 'in', $vo['custom_id'])->field($custom_field)->select();
		    }else{
			    $total = 0;
			    $list = [];
		    }

		    $result = array("total" => $total, "rows" => $list);

		    return json($result);
	    }

	    $Admin = Db::name('admin')->field('id,username')->select();
	    foreach ($Admin as $value){
	    	$admin_list[$value['id']] = $value['username'];
	    }
	    $this->view->assign('admin_list', $admin_list);
	    return $this->view->fetch();
    }

    public function bind($admin_id = NULL){

	   if(!$admin_id)
		   $this->error(__('Parameter %s can not be empty', ''));

		if($this->request->isPost()){
			$params = $this->request->post("row/a");
			if (isset($params['custom_id']) && !empty($params['custom_id']))
			{
				$custom_id_arr = explode(",", $params['custom_id']);
				foreach ($custom_id_arr as $value){
					if(!is_numeric($value)){
						$this->error(__('Parameter error'));
					}
				}

				$params['admin_id'] = $admin_id;
				try{
					Db::name('admin_custom_bind')->insert($params, true);
				}catch (PDOException $e){
					$this->error(__('Operation failed'));
				}
				$this->success();
			}
			$this->error(__('Parameter %s can not be empty', ''));
		}

	    $field = 'id,pid,custom_name';
	    $ruleList = collection(Db::name('custom')->field($field)->select())->toArray();//客户列表
	    $vo = Db::name('admin_custom_bind')->where('admin_id','eq', $admin_id)->find();
	    if(!empty($vo) && !empty($vo['custom_id'])){
			$selected = explode(",", $vo['custom_id']);
	    }else{
			$selected = [];
	    }

	    $nodeList = [];
	    foreach ($ruleList as $k => $v)
	    {
		    $state = array('selected' => (in_array($v['id'], $selected)? true: false));
		    $nodeList[] = array('id' => $v['id'],
			    'parent' => $v['pid'] ? $v['pid'] : '#',
			    'text' => $v['custom_name'],
			    'state' => $state);
	    }
	    unset($v);

	    $this->assign("nodeList", $nodeList);
	    return $this->view->fetch();
    }

    public function delete(){
	    $params = $this->request->param();
	    $params = $params['params'];
	    if($params){
			if(!isset($params['admin_id']) && empty($params['admin_id']) && !isset($params['custom_id']) && empty($params['custom_id'])){
				$this->error(__('Parameter error'));
			}
		    $Object = collection(Db::name('admin_custom_bind')->where('admin_id','eq', $params['admin_id'])->find())->toArray();
		    $original_custom_id = explode(",", $Object['custom_id']); //原始的客户ID集合
		    if(empty($original_custom_id))
		    	$this->success();

		    $delete_custom_id = explode(",", $params['custom_id']); //需删除的客户ID集合
		    $save_data['custom_id'] = implode(",", array_diff($original_custom_id, $delete_custom_id));  //保留的客户ID集合
		    $save_data['admin_id'] = $params['admin_id'];

		    try{
			    Db::name('admin_custom_bind')->insert($save_data, true);
		    }catch (\PDOException $e){
		    	$this->error(__('Operation failed'));
		    }
			$this->success();
	    }
	    $this->error(__('Parameter error'));
    }


}
