<?php

namespace app\admin\controller\general;

use app\common\controller\Backend;
use EasyWeChat\Core\Exception;
use think\db;
use think\Session;
use think\Hook;

/**
 * 附件管理
 *
 * @icon fa fa-circle-o
 * @remark 主要用于管理上传到又拍云的数据或上传至本服务的上传数据
 */
class Attachment extends Backend
{

    protected $model = null;

	protected $savekey = '';

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Attachment');

	    $admin_data = Session::get('admin');
	    $this->savekey = $admin_data['username'].'_'.$admin_data['id'];
	    $this->assign('savekey', $this->savekey);
    }

    /**
     * 查看
     */
    public function index()
    {
	    $admin_id = $this->auth->id;
	    $where_config['name'] = 'resource_attachment';
		$config_attachment = Db::name('config')->where($where_config)->field('value')->find();
	    $column_admin = json_decode($config_attachment['value'], true); //json_decode获取特殊账号及其对应查看账号列表值
	    if(!empty($column_admin)){
		    $admin_key = array_unique(array_keys($column_admin)); //获取特殊账号列表
		    if(in_array($admin_id, $admin_key)){
				if('*' == $column_admin[$admin_id]){
					$where_other = [];
				}else{
					$where_other['admin_id'] = array('in', $column_admin[$admin_id]);
				}
		    }else{
			    $where_other['admin_id'] = $admin_id;
		    }
	    }else{
		    $where_other['admin_id'] = $admin_id;
	    }

        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax())
        {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
	                ->where($where_other)
	                ->where($where)
                    ->order($sort, $order)
                    ->count();

            $list = $this->model
	                ->where($where_other)
	                ->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();
            $cdnurl = preg_replace("/\/(\w+)\.php$/i", '', $this->request->root());
            foreach ($list as $k => &$v)
            {
                $v['fullurl'] = ($v['storage'] == 'local' ? $cdnurl : $this->view->config['upload']['cdnurl']) . $v['url'];
            }
            unset($v);
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 选择附件
     */
    public function select()
    {
        if ($this->request->isAjax())
        {
            return $this->index();
        }
        return $this->view->fetch();
    }

    /**
     * 添加
     */
    public function add()
    {
        if ($this->request->isAjax())
        {
            $params = $this->request->post();
	        $where['sha1'] = $params['row']['sha1'];
	        $data['title'] = $params['row']['title'];
	        Db::startTrans();
	        try{
		        $this->model->where($where)->update($data);
	        }catch(Exception $e){

		        Db::rollback();

		        $attachmentFile = ROOT_PATH . '/public' . $params['url'];
		        if (is_file($attachmentFile))
		        {
			        @unlink($attachmentFile);
		        }
		        $this->error();
	        }
	        Db::commit();
	        $this->success();
        }
        return $this->view->fetch();
    }


	/**
	 * 附件删除方法
	 * @param null $ids
	 */
	public function del($ids=NULL)
    {
        if ($ids)
        {
            Hook::add('upload_delete', function($params) {
                $attachmentFile = ROOT_PATH . '/public' . $params['url'];
                if (is_file($attachmentFile))
                {
                    @unlink($attachmentFile);
                }
            });

            $attachmentlist = $this->model->where('id', 'in', $ids)->field('id,admin_id,url,filesize')->select();

	        Db::startTrans();
            foreach ($attachmentlist as $attachment)
            {
	            action('Changevolume/dec_capacity',
		            ['id'=>$attachment['admin_id'], 'volumn'=>round($attachment['filesize']/(1024*1024),3)]
	            );
	            $attach_result = $this->model->where('id="'.$attachment['id'].'"')->delete();
	            if(!$attach_result){
		            Db::rollback();
		            $this->error(__('Parameter %s can not be empty', 'ids'));
	            }
            }
	        Db::commit();
	        foreach($attachmentlist as $attachment){
		        \think\Hook::listen("upload_delete", $attachment);
	        }
	        $this->success();
        }
        $this->error(__('Parameter %s can not be empty', 'ids'));
    }

}
