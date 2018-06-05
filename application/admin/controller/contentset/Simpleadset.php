<?php

namespace app\admin\controller\contentset;

use app\common\controller\Backend;

/**
 * 简易广告管理
 *
 * @icon fa fa-circle-o
 */
class Simpleadset extends Backend
{
    
    /**
     * SimpleadCustom模型对象
     */
    protected $model = null;

    // 登录账号绑定的客户ID列表
    protected $custom_ids = [0];

    protected $modelValidate = true;

    protected $modelSceneValidate = true;

    protected $admin_id = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('SimpleadCustom');

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
            $where_customid['zxt_simplead_custom.custom_id'] = ['in', $this->customlist_class->custom_id( $this->admin_id)];

            $this->relationSearch = true;
            $this->searchFields = "custom.custom_id";
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
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
	            ->with('resource')
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

	        $cdnurl = preg_replace("/\/(\w+)\.php$/i", '', $this->request->root());
	        foreach ($list as $k => &$v)
	        {
		        $v['fullurl'] = $cdnurl.$v['resource']['filepath'];
	        }
	        unset($v);

            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
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
                    //修改栏目判断权限
                    if(!in_array($row['custom_id'], $this->customlist_class->custom_id( $this->admin_id)))
                        $this->error(__('You have no permission'));

                    if ($this->modelValidate)
                    {
                        $name = basename(str_replace('\\', '/', get_class($this->model)));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : true) : $this->modelValidate;
                        $row->validate($validate);
                    }

                    $params['status'] = 'hidden';
                    $result = $row->allowField('title,url_to')->save($params);
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

        $this->view->assign("row", $row);
        return $this->view->fetch();
    }
    

}
