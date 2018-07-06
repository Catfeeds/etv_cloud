<?php

namespace app\admin\controller\logs;

use app\common\controller\Backend;
use think\Db;
use think\Exception;
use think\Log;

/**
 * 
 *
 * @icon fa fa-circle-o
 */
class Devicevisitlog extends Backend
{
    
    /**
     * DeviceVisitLog模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('DeviceVisitLog');

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
			$params = $this->request->get();
			$limit = isset($params['limit'])? $params['limit']: 10;
			$offset = isset($params['offset'])? $params['offset']: 0;
			$filter = isset($params['filter'])? json_decode($params['filter'], true): [];
			if (isset($filter['mac'])){
				$device_obj = Db::name('device_basics')->where('mac','eq',$filter['mac'])->field('id')->find();
				$partition_data = !empty($device_obj)? ['mac_id'=>$device_obj['id']]: ['mac_id'=>0];
			}else{
				$partition_data = [];
			}
			$rule = ['type'=>'mod', 'num'=>5];
			$total = $this->model
				->partition($partition_data,'mac_id',$rule)
				->count();

			$list = $this->model
				->partition($partition_data,'mac_id',$rule)
				->order('post_time desc')
				->limit($offset, $limit)
				->select();

			$list = collection($list)->toArray();
			$result = array("total" => $total, "rows" => $list);

			return json($result);
		}
		return $this->view->fetch();
	}
    
	public function delete()
	{
		$params = $this->request->post();
		if(empty($params)){
			echo 0;
		}
		Db::startTrans();
		$rule = ['type'=>'mod', 'num'=>5];
		foreach ($params['params'] as $key=>$value){
			try{
				$mix_data = explode("_",$value);
				$partition_data = ['mac_id'=>$mix_data['1']];
				Db::name('device_visit_log')->partition($partition_data, 'mac_id', $rule)->where('id','eq',$mix_data['0'])->delete();
			}catch (\Exception $e){
				Log::write('设备访问日志删除失败,错误原因如下: '.$e->getMessage());
				Log::save();
				Db::rollback();
				echo -1;
			}
		}
		Db::commit();
		echo 1;
	}
}
