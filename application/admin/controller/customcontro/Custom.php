<?php

namespace app\admin\controller\customcontro;

use app\common\controller\Backend;
use fast\Tree;
use think\Db;

/**
 * 
 *
 * @icon fa fa-circle-o
 */
class Custom extends Backend
{
    
    /**
     * Custom模型对象
     */
    protected $model = null;

    protected $modelValidate = true; //开启验证
    protected $modelSceneValidate = true;  //开启场景验证

    // 客户类型
    protected $custom_type_arr = [];

    //当前组别列表数据
    protected $customdata = [];

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Custom');

        // 客户类型
        $this->custom_type_arr = config('custom_type_arr');
        $this->view->assign('custom_type_arr', $this->custom_type_arr);

        $customList = collection($this->model->field('id,custom_name,pid')->select())->toArray();

        Tree::instance()->init($customList);
        $result = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(0),'custom_name');

        $groupName = [0=>__('None')];
        foreach ($result as $k => $v)
        {
            $groupName[$v['id']] = $v['custom_name'];
        }

        $this->customdata = $groupName;
        $this->view->assign('customdata', $this->customdata);
    }

	public function index()
	{
		if ($this->request->isAjax())
		{
			list($where, $sort, $order, $offset, $limit) = $this->buildparams();
			$total = $this->model
				->where($where)
				->order($sort, $order)
				->count();

			$list = $this->model
				->where($where)
				->order($sort, $order)
				->limit($offset, $limit)
				->select();

			$list = collection($list)->toArray();
			foreach($list as $key => $value){
				$vo = $this->model->where('id','eq',$value['pid'])->field('custom_name')->find();
				$list[$key]['parent_custom_name'] = $vo['custom_name'];
			}
			unset($vo);
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

		$skin_obj = Db::name('skin')->field('id,title')->select();
		$skin = [];
		if(!empty($skin_obj)){
			foreach ($skin_obj as $value){
				$skin[$value['id']] = $value['title'];
			}
		}

		$this->view->assign('skin', $skin);
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
            if($ids){
                $updatetime = time();
            }else{
                $createtime = $updatetime = time();
            }
            if ($params)
            {
                try
                {
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

	    $skin_obj = Db::name('skin')->field('id,title')->select();
	    $skin = [];
	    if(!empty($skin_obj)){
		    foreach ($skin_obj as $value){
			    $skin[$value['id']] = $value['title'];
		    }
	    }

	    $this->view->assign('skin', $skin);
        $this->view->assign("row", $row);
        return $this->view->fetch();
    }

}
