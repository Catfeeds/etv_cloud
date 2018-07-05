<?php

namespace app\admin\controller\logs;

use app\common\controller\Backend;
use think\Db;

/**
 * 
 *
 * @icon fa fa-circle-o
 */
class Deviceparalog extends Backend
{
    
    /**
     * DeviceParaLog模型对象
     */
    protected $model = null;

	protected $searchFields = 'mac';

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('DeviceParaLog');

    }

	public function detail($id){
    	if(is_numeric($id)){
    		$obj = Db::name('device_para_log')->where('id','eq',$id)->field('before_info,after_info')->find();
    		echo json_encode(collection($obj)->toArray());
	    }
	    echo false;
	}

}
