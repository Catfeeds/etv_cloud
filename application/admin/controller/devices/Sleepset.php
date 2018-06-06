<?php

namespace app\admin\controller\devices;

use app\admin\model\Custom;
use app\common\controller\Backend;

/**
 * 
 *
 * @icon fa fa-circle-o
 */
class Sleepset extends Backend
{

    // 登录账号绑定的客户ID列表
    protected $custom_ids = [0];

    protected $modelValidate = true;

    protected $modelSceneValidate = true;

    protected $admin_id = null;

    protected $noNeedLogin = []; //无需登录
    protected $noNeedRight = []; //无需验证

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('DeviceSleep');

        $this->admin_id = $this->auth->id;
    }

    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax())
        {
            $this->relationSearch = true;
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $Customlist = new Customlist();
            $customid_list = $Customlist->custom_id_device($this->admin_id);
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

}
