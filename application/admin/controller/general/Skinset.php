<?php

namespace app\admin\controller\general;

use app\common\controller\Backend;

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

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Skin');

        $this->assign('savekey', 'skin_upload');

    }

    public function upload(){

	    Config::set('default_return_type', 'json');
	    $file = $this->request->file('file');
	    if (empty($file)) {
		    $this->error(__('No file upload or server upload limit exceeded'));
	    }

	    //判断是否已经存在附件
	    $sha1 = $file->hash();

	    $upload = Config::get('upload');

	    preg_match('/(\d+)(\w+)/', $upload['maxsize'], $matches);
	    $type = strtolower($matches[2]);
	    $typeDict = ['b' => 0, 'k' => 1, 'kb' => 1, 'm' => 2, 'mb' => 2, 'gb' => 3, 'g' => 3];
	    $size = (int)$upload['maxsize'] * pow(1024, isset($typeDict[$type]) ? $typeDict[$type] : 0);
	    $fileInfo = $file->getInfo();
	    $suffix = strtolower(pathinfo($fileInfo['name'], PATHINFO_EXTENSION));
	    $suffix = $suffix ? $suffix : 'file';

	    $mimetypeArr = explode(',', $upload['mimetype']);
	    $typeArr = explode('/', $fileInfo['type']);
	    //验证文件后缀
	    if ($upload['mimetype'] !== '*' && !in_array($suffix, $mimetypeArr) && !in_array($fileInfo['type'], $mimetypeArr) && !in_array($typeArr[0] . '/*', $mimetypeArr)) {
		    $this->error(__('Uploaded file format is limited'));
	    }
	    $replaceArr = [
		    '{year}'     => date("Y"),
		    '{mon}'      => date("m"),
		    '{day}'      => date("d"),
		    '{hour}'     => date("H"),
		    '{min}'      => date("i"),
		    '{sec}'      => date("s"),
		    '{random}'   => Random::alnum(16),
		    '{random32}' => Random::alnum(32),
		    '{filename}' => $suffix ? substr($fileInfo['name'], 0, strripos($fileInfo['name'], '.')) : $fileInfo['name'],
		    '{suffix}'   => $suffix,
		    '{.suffix}'  => $suffix ? '.' . $suffix : '',
		    '{filemd5}'  => md5_file($fileInfo['tmp_name']),
	    ];
	    // 拓展上传文件保存路径
	    $specialpath = $this->request->get('filepath');
	    if($specialpath){
		    $savekey = $upload['rootpath'].$specialpath.'/{filemd5}{.suffix}';
	    }else{
		    $savekey = $upload['savekey'];
	    }
	    $savekey = str_replace(array_keys($replaceArr), array_values($replaceArr), $savekey);

	    $uploadDir = substr($savekey, 0, strripos($savekey, '/') + 1);
	    $fileName = substr($savekey, strripos($savekey, '/') + 1);
	    //
	    $splInfo = $file->validate(['size' => $size])->move(ROOT_PATH . '/public' . $uploadDir, $fileName);
	    if ($splInfo) {
		    $imagewidth = $imageheight = 0;
		    if (in_array($suffix, ['gif', 'jpg', 'jpeg', 'bmp', 'png', 'swf'])) {
			    $imgInfo = getimagesize($splInfo->getPathname());
			    $imagewidth = isset($imgInfo[0]) ? $imgInfo[0] : $imagewidth;
			    $imageheight = isset($imgInfo[1]) ? $imgInfo[1] : $imageheight;
		    }
		    // 获取上传人id
		    $session = $_SESSION;
		    $config = Config('session');
		    $pession_msg = $session[$config['prefix']]['admin'];
		    $admin_id = $pession_msg['id'];
		    $params = array(
			    'filesize'    => $fileInfo['size'],
			    'imagewidth'  => $imagewidth,
			    'imageheight' => $imageheight,
			    'imagetype'   => $suffix,
			    'imageframes' => 0,
			    'mimetype'    => $fileInfo['type'],
			    'url'         => $uploadDir . $splInfo->getSaveName(),
			    'uploadtime'  => time(),
			    'storage'     => 'local',
			    'sha1'        => $sha1,
			    'admin_id'    => $admin_id,
		    );
		    Db::startTrans();
		    $add_result = action('Changevolume/inc_capacity',
			    ['id'=>$admin_id, 'volumn'=>round($fileInfo['size']/(1024*1024),3)]
		    );
		    if(!$add_result){
			    $this->error(__('Update volumn fail'));
		    }
		    $attachment = model("attachment");
		    $attachment->data(array_filter($params));
		    try{
			    $attachment->save();
		    }catch(Exception $e){
			    $attachmentFile = ROOT_PATH . '/public' . $uploadDir . $splInfo->getSaveName();
			    if (is_file($attachmentFile))
			    {
				    @unlink($attachmentFile);
			    }
			    Db::rollback();
			    $this->error(__('Upload fail'));
		    }
		    Db::commit();
		    $this->success(__('Upload successful'), null, [
			    'url'   => $uploadDir . $splInfo->getSaveName(),
			    'sha1'  => $sha1
		    ]);
	    } else {
		    // 上传失败获取错误信息
		    $this->error($file->getError());
	    }
    }

    

}
