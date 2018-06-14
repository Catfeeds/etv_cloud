<?php

namespace app\admin\controller\devices;

use app\common\controller\Backend;
use app\admin\controller\devices\Common;
use think\Db;

/**
 * 
 *
 * @icon fa fa-circle-o
 */
class Sleepset extends Backend
{

    // 登录账号绑定的客户ID列表
    protected $custom_ids = [0];

    protected $isValidate = true; //是否进行验证

    protected $admin_id = null;

    protected $noNeedLogin = []; //无需登录
    protected $noNeedRight = []; //无需验证

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('DeviceSleep');

        $this->admin_id = $this->auth->id;
    }

	/**
     * 主页
     */
    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax())
        {

            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $Customlist_class = new Customlist();
            $customid_list = $Customlist_class->custom_id_device($this->admin_id);
            if(!is_array($customid_list) && $customid_list == config('get all')){
                $where_customid = [];
            }else{
                $where_customid['zxt_device_basics.custom_id'] = ['in', $customid_list];
            }

            $field = 'zxt_device_sleep.*,zxt_custom.custom_id,zxt_custom.custom_name';
            $url_prefix = preg_replace("/\/(\w+)\.php$/i", '', $this->request->root());

            $total = $this->model
                ->where($where)
                ->where($where_customid)
                ->join('zxt_device_basics', 'zxt_device_sleep.mac=zxt_device_basics.mac')
                ->join('zxt_custom', 'zxt_device_basics.custom_id=zxt_custom.id')
                ->field('zxt_device_sleep.id')
                ->count();

            $list = $this->model
                ->where($where)
                ->where($where_customid)
                ->join('zxt_device_basics', 'zxt_device_sleep.mac=zxt_device_basics.mac')
                ->join('zxt_custom', 'zxt_device_basics.custom_id=zxt_custom.id')
                ->field($field)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            foreach($list as &$value){
                $value['url_prefix'] = $url_prefix;
            }

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
                //验证
                if ($this->isValidate)
                {
                    $validate_result = $this->validate($params, 'DeviceSleep');
                    if(true !== $validate_result)
                        $this->error($validate_result);
                }
                Db::startTrans();
                try
                {
                    // 数据处理
                    $Common = new Common();
                    $basics_mac = $Common->maclist_by_custom_mac($params['custom_id'] , $params['mac_ids']);
                    if(false == $basics_mac)
                        $this->error(__('Choose device'));

                    $maclist = array_column($basics_mac, 'mac'); //MAC集合
                    foreach($maclist as $key=>$value){
                        $insert_data[$key]['mac'] = $value;
                        $insert_data[$key]['sleep_time_start'] = $params['sleep_time_start'];
                        $insert_data[$key]['sleep_time_end'] = $params['sleep_time_end'];
                        $insert_data[$key]['sleep_marked_word'] = $params['sleep_marked_word'];
                        $insert_data[$key]['sleep_countdown_time'] = $params['sleep_countdown_time'];
                        $insert_data[$key]['sleep_image'] = $params['sleep_image'];
                        $insert_data[$key]['status'] = $params['status'];
                    }

                    // 新增数据
                    Db::name('device_sleep')->insertAll($insert_data);
                    // 更新指令
                    if($params['status'] == 'normal' && !empty($maclist)){
                        $data_control['sleep_set'] = $data_control['lately_order'] = 'sleep set';
                        $where_control['mac'] = ['in', $maclist];
                        Db::name('device_basics')->where($where_control)->update($data_control);
                    }
                }
                catch (\think\exception\PDOException $e)
                {
                    Log::write($e->getMessage());
                    Log::save();
                    Db::rollback();
                    $this->error(__('Operation failed'));
                }
                Db::commit();
                $this->success();
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }

        $image_list = config('sleep_image_title');
        $url_prefix = preg_replace("/\/(\w+)\.php$/i", '', $this->request->root()).'/uploads/sleep_image/';
        $first_image = $url_prefix . current($image_list) .'.jpg';

        foreach($image_list as &$value){
            $value = __($value);
        }

        $this->view->assign('url_prefix', [$url_prefix]);
        $this->view->assign('image_list', $image_list);
        $this->view->assign('first_image', $first_image);
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
                //验证
                if ($this->isValidate)
                {
                    $validate_result = $this->validate($params, 'DeviceSleep');
                    if(true !== $validate_result)
                        $this->error($validate_result);
                }
                try
                {
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

        $url_prefix = preg_replace("/\/(\w+)\.php$/i", '', $this->request->root()).'/uploads/sleep_image/';
        $first_image = $url_prefix . $row['sleep_image'] .'.jpg';

        $image_list = config('sleep_image_title');
        foreach($image_list as &$value){
            $value = __($value);
        }

        $this->view->assign('url_prefix', [$url_prefix]);
        $this->view->assign('image_list', $image_list);
        $this->view->assign('first_image', $first_image);
        $this->view->assign("row", $row);
        return $this->view->fetch();
    }

    public function multi_edit($ids=NULL){

        if ($this->request->isPost())
        {
            $params = $this->request->post("row/a");
            if ($params)
            {
                //验证
                if ($this->isValidate)
                {
                    $validate_result = $this->validate($params, 'DeviceSleep');
                    if(true !== $validate_result)
                        $this->error($validate_result);
                }
                Db::startTrans();
                try
                {
                    // 更新数据
                    Db::name('device_sleep')->where('id', 'in', $ids)->update($params);
                    // 更新指令
                    if($params['status'] == 'normal' && !empty($ids)){
                        $maclist= Db::name('device_sleep')->where('id', 'in', $ids)->field('mac')->select();
                        $data_control['sleep_set'] = $data_control['lately_order'] = 'sleep set';
                        $where_control['mac'] = ['in', array_column($maclist, 'mac')];
                        Db::name('device_basics')->where($where_control)->update($data_control);
                    }
                }
                catch (\think\exception\PDOException $e)
                {
                    Log::write($e->getMessage());
                    Log::save();
                    Db::rollback();
                    $this->error(__('Operation failed'));
                }
                Db::commit();
                $this->success();
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }

        $image_list = config('sleep_image_title');
        $url_prefix = preg_replace("/\/(\w+)\.php$/i", '', $this->request->root()).'/uploads/sleep_image/';
        $first_image = $url_prefix . current($image_list) .'.jpg';

        foreach($image_list as &$value){
            $value = __($value);
        }

        $this->view->assign('url_prefix', [$url_prefix]);
        $this->view->assign('image_list', $image_list);
        $this->view->assign('first_image', $first_image);
        return $this->view->fetch();
    }

    /**
     * 生成查询所需要的条件,排序方式 定制化
     * @param mixed $searchfields 快速查询的字段
     * @param boolean $relationSearch 是否关联查询
     * @return array
     */
    public function buildparams($searchfields = null, $relationSearch = null)
    {
        $searchfields = is_null($searchfields) ? $this->searchFields : $searchfields;
        $relationSearch = is_null($relationSearch) ? $this->relationSearch : $relationSearch;
        $search = $this->request->get("search", '');
        $filter = $this->request->get("filter", '');
        $op = $this->request->get("op", '', 'trim');
        $sort = $this->request->get("sort", "id");
        $order = $this->request->get("order", "DESC");
        $offset = $this->request->get("offset", 0);
        $limit = $this->request->get("limit", 0);
        $filter = json_decode($filter, TRUE);
        $op = json_decode($op, TRUE);
        $filter = $filter ? $filter : [];
        $where = [];
        $tableName = '';
        if ($relationSearch)
        {
            if (!empty($this->model))
            {
                $tableName = $this->model->getQuery()->getTable() . ".";
            }
            $sort = stripos($sort, ".") === false ? $tableName . $sort : $sort;
        }
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds))
        {
            $where[] = [$tableName . $this->dataLimitField, 'in', $adminIds];
        }
        if ($search)
        {
            $searcharr = is_array($searchfields) ? $searchfields : explode(',', $searchfields);
            foreach ($searcharr as $k => &$v)
            {
                $v = stripos($v, ".") === false ? $tableName . $v : $v;
            }
            unset($v);
            $where[] = [implode("|", $searcharr), "LIKE", "%{$search}%"];
        }
        foreach ($filter as $k => $v)
        {
            $sym = isset($op[$k]) ? $op[$k] : '=';
            if (stripos($k, ".") === false)
            {
                if('custom_id' == $k || 'custom_name' == $k){
                    $k = 'zxt_custom.'.$k;
                }else{
                    $k = $tableName . $k;
                }
            }
            $v = !is_array($v) ? trim($v) : $v;
            $sym = strtoupper(isset($op[$k]) ? $op[$k] : $sym);
            switch ($sym)
            {
                case '=':
                case '!=':
                    $where[] = [$k, $sym, (string) $v];
                    break;
                case 'LIKE':
                case 'NOT LIKE':
                case 'LIKE %...%':
                case 'NOT LIKE %...%':
                    $where[] = [$k, trim(str_replace('%...%', '', $sym)), "%{$v}%"];
                    break;
                case '>':
                case '>=':
                case '<':
                case '<=':
                    $where[] = [$k, $sym, intval($v)];
                    break;
                case 'FINDIN':
                case 'FIND_IN_SET':
                    $where[] = "FIND_IN_SET('{$v}', `{$k}`)";
                    break;
                case 'IN':
                case 'IN(...)':
                case 'NOT IN':
                case 'NOT IN(...)':
                    $where[] = [$k, str_replace('(...)', '', $sym), is_array($v) ? $v : explode(',', $v)];
                    break;
                case 'BETWEEN':
                case 'NOT BETWEEN':
                    $arr = array_slice(explode(',', $v), 0, 2);
                    if (stripos($v, ',') === false || !array_filter($arr))
                        continue;
                    //当出现一边为空时改变操作符
                    if ($arr[0] === '')
                    {
                        $sym = $sym == 'BETWEEN' ? '<=' : '>';
                        $arr = $arr[1];
                    }
                    else if ($arr[1] === '')
                    {
                        $sym = $sym == 'BETWEEN' ? '>=' : '<';
                        $arr = $arr[0];
                    }
                    $where[] = [$k, $sym, $arr];
                    break;
                case 'RANGE':
                case 'NOT RANGE':
                    $v = str_replace(' - ', ',', $v);
                    $arr = array_slice(explode(',', $v), 0, 2);
                    if (stripos($v, ',') === false || !array_filter($arr))
                        continue;
                    //当出现一边为空时改变操作符
                    if ($arr[0] === '')
                    {
                        $sym = $sym == 'RANGE' ? '<=' : '>';
                        $arr = $arr[1];
                    }
                    else if ($arr[1] === '')
                    {
                        $sym = $sym == 'RANGE' ? '>=' : '<';
                        $arr = $arr[0];
                    }
                    $where[] = [$k, str_replace('RANGE', 'BETWEEN', $sym) . ' time', $arr];
                    break;
                case 'LIKE':
                case 'LIKE %...%':
                    $where[] = [$k, 'LIKE', "%{$v}%"];
                    break;
                case 'NULL':
                case 'IS NULL':
                case 'NOT NULL':
                case 'IS NOT NULL':
                    $where[] = [$k, strtolower(str_replace('IS ', '', $sym))];
                    break;
                default:
                    break;
            }
        }
        $where = function($query) use ($where) {
            foreach ($where as $k => $v)
            {
                if (is_array($v))
                {
                    call_user_func_array([$query, 'where'], $v);
                }
                else
                {
                    $query->where($v);
                }
            }
        };
        return [$where, $sort, $order, $offset, $limit];
    }

}
