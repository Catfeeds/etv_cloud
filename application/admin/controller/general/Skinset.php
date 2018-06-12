<?php

namespace app\admin\controller\general;

use app\common\controller\Backend;
use think\Config;

/**
 * 皮肤管理
 *
 * @icon fa fa-circle-o
 */
class Skinset extends Backend
{
    
    /**
     * Skin模型对象
     */
    protected $model = null;
	protected $noNeedRight = ['upload_apk','upload_image'];

	public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Skin');

        $this->assign('savekey', 'skin_upload');

    }

    public function index()
    {
	    //设置过滤方法
	    $this->request->filter(['strip_tags']);
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


		    $cdnurl = preg_replace("/\/(\w+)\.php$/i", '', $this->request->root());
		    foreach ($list as $k => &$v)
		    {
		    	if(!empty($v['image_filepath'])){
				    $v['image_url'] = $cdnurl.$v['image_filepath'];
			    }else{
					$v['image_url'] = '';
			    }

			    if(!empty($v['apk_filepath'])){
				    $v['apk_url'] = $cdnurl.$v['apk_filepath'];
			    }else{
				    $v['apk_url'] = '';
			    }
		    }
		    unset($v);

		    $list = collection($list)->toArray();
		    $result = array("total" => $total, "rows" => $list);

		    return json($result);
	    }
	    return $this->view->fetch();
    }

	/**
	 * 上传APK文件
	 */
	public function upload_apk()
	{
		Config::set('default_return_type', 'json');
		$file = $this->request->file('file');
		if (empty($file)) {
			$this->error(__('No file upload or server upload limit exceeded'));
		}

		$info = $file->validate(['ext'=>'apk'])->move(ROOT_PATH. '/public/uploads/skin_upload');
		if($info){
			$this->success(__('Upload successful'), null, [
				'url'   => '/uploads/skin_upload/' . $info->getSaveName(),
				'sha1'  => $info->hash()
			]);
		}else{
			// 上传失败获取错误信息
			$this->error($file->getError());
		}
	}

	/**
	 * 上传缩略图文件
	 */
	public function upload_image()
	{
		Config::set('default_return_type', 'json');
		$file = $this->request->file('file');
		if (empty($file)) {
			$this->error(__('No file upload or server upload limit exceeded'));
		}

		$image_type = Config::get('picture_type'); //图片类型
		$info = $file->validate(['ext'=>$image_type])->move(ROOT_PATH. '/public/uploads/skin_upload');
		if($info){
			$this->success(__('Upload successful'), null, [
				'url'   => '/uploads/skin_upload/' . $info->getSaveName()
			]);
		}else{
			// 上传失败获取错误信息
			$this->error($file->getError());
		}
	}

    

}
