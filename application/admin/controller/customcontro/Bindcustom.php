<?php

namespace app\admin\controller\customcontro;

use app\common\controller\Backend;
use think\Db;
use app\admin\model\Custom;

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
	    	$where['admin_id'] = $this->auth->id;
	    	$vo = $this->model->where($where)->find();
	    	if(!empty($vo) && !empty($vo['custom_id'])){
	    		$custom_field = 'custom_id, custom_name, handler, phone';
	    		$total = count(explode(",", $vo['custom_id']));
				$list = Db::name('custom')->where('id', 'in', $vo['custom_id'])->field($custom_field)->select();
		    }else{
			    $total = 0;
			    $list = [];
		    }

		    $result = array("total" => $total, "rows" => $list);

		    return json($result);
	    }
	    return $this->view->fetch();
    }

    public function add(){
		if($this->request->isPost()){

		}

		$admin = $this->auth->id;
	    $field = 'id,pid,custom_name';
	    $ruleList = collection(Db::name('custom')->field($field)->select())->toArray();//客户列表
	    $vo = Db::name('admin_custom_bind')->where('admin','eq', $admin)->find();
	    if(!empty($vo) && !empty($vo['custom_id'])){
			$selected = explode(",", $vo['custom_id']);
	    }else{

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
	    return $this->view->fetch('allot');
    }


}
