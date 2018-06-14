<?php

namespace app\admin\controller\contentset;

use app\common\controller\Backend;
use think\Cache;
use app\admin\controller\contentset\Customlist;

/**
 * 语言管理
 *
 * @icon fa fa-circle-o
 */
class Languageset extends Backend
{
    
    /**
     * LanguageSetting模型对象
     */
    protected $model = null;

	protected $modelValidate = true;

	protected $modelSceneValidate = true;

	protected $admin_id = null;

	public function _initialize()
    {
        parent::_initialize();
        $this->model = model('LanguageSetting');

	    $this->admin_id = $this->auth->id;
    }

	/**
	 * 查看
	 */
	public function index()
	{
		$this->relationSearch = true;
		$this->searchFields = "custom.custom_id";

		if ($this->request->isAjax())
		{
			$Customlist_class = new Customlist();
			$where_customid['zxt_language_setting.custom_id'] = ['in', $Customlist_class->custom_id($this->admin_id)];

			list($where, $sort, $order, $offset, $limit) = $this->buildparams();
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
	        //设置过滤方法
            $params = $this->request->post("row/a");
            if ($params)
            {
                try
                {
	                // 判断客户列表
	                $custom_key = Cache::get($this->admin_id.'-language-customlist');
	                if(!in_array($params['custom_id'], $custom_key)){
		                $this->error(__('Parameter error'));
	                }

                    if ($this->modelValidate)
                    {
                        $name = basename(str_replace('\\', '/', get_class($this->model)));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.add' : true) : $this->modelValidate;
                        $this->model->validate($validate);
                    }
                    $result = $this->model->allowField(true)->save($params);
                    if ($result !== false)
                    {
	                    Cache::rm($this->auth->id.'-language-customlist');
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

        $language_types = config('language_type');
        foreach($language_types as $k => &$v){
            $v = __($v);
        }
        unset($v);
	    $this->view->assign('language_types', $language_types); //语言类别

	    $Customlist_class = new Customlist();
	    $customlist = $Customlist_class->custom_list($this->admin_id);
	    Cache::set($this->admin_id.'-language-customlist', array_column($customlist, 'id'), 36000); //设置10小时缓存,用于判断客户ID
	    foreach($customlist as $ckey => $cv){
			$custom_lists[$cv['id']] = $cv['custom_name'];
	    }
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
	                //修改栏目的权限判断
	                $Customlist_class = new Customlist();
	                $custom_id_list = $Customlist_class->custom_id($this->admin_id);
	                if(!in_array($row['custom_id'], $custom_id_list))
		                $this->error(__('You have no permission'));
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

	    $language_types = config('language_type');
	    foreach($language_types as $k => &$v){
		    $v = __($v);
	    }
	    unset($v);
	    $this->view->assign('language_types', $language_types); //语言类别


        $this->view->assign("row", $row);
        return $this->view->fetch();
    }
    

}
