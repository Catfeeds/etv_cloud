<?php

namespace app\admin\controller\contentset;

use app\common\controller\Backend;
use app\admin\controller\contentset\Customlist;
use think\Db;

/**
 * 宣传客户绑定管理
 *
 * @icon fa fa-circle-o
 */
class Propagandaset extends Backend
{
    
    /**
     * PropagandaCustom模型对象
     */
    protected $model = null;

    //客户类 可用于查询账号对应客户列表
    protected $customlist_class = null;

    protected $modelValidate = true;

    protected $modelSceneValidate = true;

	protected $admin_id = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('PropagandaCustom');

	    $this->customlist_class = new Customlist;

	    $this->admin_id = $this->auth->id;
    }

    /**
     * 查看
     */
    public function index()
    {
	    $this->relationSearch = true;
	    $this->searchFields = "custom.custom_id";
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax())
        {
	        $where_customid['zxt_propaganda_custom.custom_id'] = ['in', $this->customlist_class->custom_id($this->admin_id)];

            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                ->where($where)
	            ->where($where_customid)
	            ->with("custom")
	            ->with("resource")
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->where($where)
	            ->where($where_customid)
	            ->with("custom")
	            ->with("resource")
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
					//修改栏目的权限判断
					$custom_id_list = $this->customlist_class->custom_id($this->admin_id);
					if(!in_array($row['custom_id'], $custom_id_list))
						$this->error(__('You have no permission'));
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

		$save_set_info = config('save_set'); //存储位置列表
		foreach($save_set_info as &$sav){
			$sav = __($sav);
		}
		$this->view->assign("row", $row);
		$this->view->assign('save_at_info', $save_set_info);
		return $this->view->fetch();
	}

	/**
	 * 排序
	 */
	public function dragsort()
	{
		//排序的数组
		$ids = $this->request->post("ids");
		//拖动的记录ID
		$changeid = $this->request->post("changeid");
		//操作字段
		$field = $this->request->post("field");
		//操作的数据表
		$table = $this->request->post("table");
		//排序的方式
		$orderway = $this->request->post("orderway", 'strtolower');
		$orderway = $orderway == 'asc' ? 'ASC' : 'DESC';
		$sour = $weighdata = [];
		$ids = explode(',', $ids);
		$prikey = 'id';
		$pid = $this->request->post("pid");
		//限制更新的字段
		$field = in_array($field, ['weigh']) ? $field : 'weigh';

		// 如果设定了pid的值,此时只匹配满足条件的ID,其它忽略
		if ($pid !== '') {
			$hasids = [];
			$list = Db::name($table)->where($prikey, 'in', $ids)->where('pid', 'in', $pid)->field('id,pid')->select();
			foreach ($list as $k => $v) {
				$hasids[] = $v['id'];
			}
			$ids = array_values(array_intersect($ids, $hasids));
		}

		//直接修复排序
		$one = Db::name($table)->field("{$field},COUNT(*) AS nums")->group($field)->having('nums > 1')->find();
		if ($one) {
			$list = Db::name($table)->field("$prikey,$field")->order($field, $orderway)->select();
			foreach ($list as $k => $v) {
				Db::name($table)->where($prikey, $v[$prikey])->update([$field => $k + 1]);
			}
			$this->success();
		} else {
			$list = Db::name($table)->field("$prikey,$field")->where($prikey, 'in', $ids)->order($field, $orderway)->select();
			foreach ($list as $k => $v) {
				$sour[] = $v[$prikey];
				$weighdata[$v[$prikey]] = $v[$field];
			}
			$position = array_search($changeid, $ids);
			$desc_id = $sour[$position];    //移动到目标的ID值,取出所处改变前位置的值
			$sour_id = $changeid;
			$desc_value = $weighdata[$desc_id];
			$sour_value = $weighdata[$sour_id];
			//echo "移动的ID:{$sour_id}\n";
			//echo "替换的ID:{$desc_id}\n";
			$weighids = array();
			$temp = array_values(array_diff_assoc($ids, $sour));
			foreach ($temp as $m => $n) {
				if ($n == $sour_id) {
					$offset = $desc_id;
				} else {
					if ($sour_id == $temp[0]) {
						$offset = isset($temp[$m + 1]) ? $temp[$m + 1] : $sour_id;
					} else {
						$offset = isset($temp[$m - 1]) ? $temp[$m - 1] : $sour_id;
					}
				}
				$weighids[$n] = $weighdata[$offset];
				Db::name($table)->where($prikey, $n)->update([$field => $weighdata[$offset]]);
			}
			$this->success();
		}
	}

}
