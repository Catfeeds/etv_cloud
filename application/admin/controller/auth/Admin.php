<?php

namespace app\admin\controller\auth;

use app\admin\model\AuthGroup;
use app\admin\model\AuthGroupAccess;
use app\common\controller\Backend;
use fast\Random;
use fast\Tree;
use think\Db;
use think\Exception;
use think\Loader;

/**
 * 管理员管理
 *
 * @icon fa fa-users
 * @remark 一个管理员可以有多个角色组,左侧的菜单根据管理员所拥有的权限进行生成
 */
class Admin extends Backend
{
    protected $noNeedLogin = ['edit'];
    protected $noNeedRight = ['edit'];

    protected $model = null;
    protected $childrenGroupIds = [];
    protected $childrenAdminIds = [];

    protected $modelValidate = true; //开启验证
    protected $modelSceneValidate = true;  //开启场景验证

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Admin');

        $this->childrenAdminIds = $this->auth->getChildrenAdminIds(true);
        $this->childrenGroupIds = $this->auth->getChildrenGroupIds(true);

        $groupList = collection(AuthGroup::where('id', 'in', $this->childrenGroupIds)->select())->toArray();

        Tree::instance()->init($groupList);
        $groupdata = [];
        if ($this->auth->isSuperAdmin())
        {
            $result = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(0));
            foreach ($result as $k => $v)
            {
                $groupdata[$v['id']] = $v['name'];
            }
        }
        else
        {
            $result = [];
            $groups = $this->auth->getGroups();
            foreach ($groups as $m => $n)
            {
                $childlist = Tree::instance()->getTreeList(Tree::instance()->getTreeArray($n['id']));
                $temp = [];
                foreach ($childlist as $k => $v)
                {
                    $temp[$v['id']] = $v['name'];
                }
                $result[__($n['name'])] = $temp;
            }
            $groupdata = $result;
        }

        $this->view->assign('groupdata', $groupdata);
        $this->assignconfig("admin", ['id' => $this->auth->id]);
    }

    /**
     * 查看
     */
    public function index()
    {
        if ($this->request->isAjax())
        {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField'))
            {
                return $this->selectpage();
            }
            $childrenGroupIds = $this->childrenGroupIds;
            $groupName = AuthGroup::where('id', 'in', $childrenGroupIds)
                    ->column('id,name');
            $authGroupList = AuthGroupAccess::where('group_id', 'in', $childrenGroupIds)
                    ->field('uid,group_id')
                    ->select();

            $adminGroupName = [];
            foreach ($authGroupList as $k => $v)
            {
                if (isset($groupName[$v['group_id']]))
                    $adminGroupName[$v['uid']][$v['group_id']] = $groupName[$v['group_id']];
            }
            $groups = $this->auth->getGroups();
            foreach ($groups as $m => $n)
            {
                $adminGroupName[$this->auth->id][$n['id']] = $n['name'];
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                    ->where($where)
                    ->where('id', 'in', $this->childrenAdminIds)
                    ->order($sort, $order)
                    ->count();

            $list = $this->model
                    ->where($where)
                    ->where('id', 'in', $this->childrenAdminIds)
                    ->field(['password', 'salt', 'token'], true)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();
            foreach ($list as $k => &$v)
            {
                $groups = isset($adminGroupName[$v['id']]) ? $adminGroupName[$v['id']] : [];
                $v['groups'] = implode(',', array_keys($groups));
                $v['groups_text'] = implode(',', array_values($groups));
            }
            unset($v);
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
                try
                {
                    //是否采用模型验证
                    if ($this->modelValidate)
                    {
                        $validate = Loader::validate('Admin');
                        if(!$validate->scene('add')->check($params)){
                            $this->error($validate->getError());
                        }
                    }
                    $params['pwd'] = $params['pwd_last'] = md5($params['password']);//获取未加盐的密码md5加密
                    $params['salt'] = Random::alnum();
                    $params['password'] = md5(md5($params['password']) . $params['salt']);
                    $params['avatar'] = '/assets/img/avatar.png'; //设置新管理员默认头像。
                    $result = $this->model->allowField(true)->save($params);
                    if ($result === false)
                    {
                        $this->error($this->model->getError());
                    }
                    $group = $this->request->post("group/a");

                    //过滤不允许的组别,避免越权
                    $group = array_intersect($this->childrenGroupIds, $group);
                    $dataset = [];
                    foreach ($group as $value)
                    {
                        $dataset[] = ['uid' => $this->model->id, 'group_id' => $value];
                    }
                    model('AuthGroupAccess')->saveAll($dataset);

                    //添加容量
	                $data_volumn['admin_id'] = $this->model->id;
	                $data_volumn['application_capacity'] = $params['volumn'];
	                Db::name('admin_capacity')->insert($data_volumn);
                }
                catch (\think\exception\PDOException $e)
                {
                    $this->error($e->getMessage());
                }
                $this->success();
            }
            $this->error();
        }
        return $this->view->fetch();
    }

    /**
     * 编辑
     */
    public function edit($ids = NULL)
    {
        $row = $this->model->get(['id' => $ids]);
        if (!$row)
            $this->error(__('No Results were found'));
        if ($this->request->isPost())
        {
            $scene_field = ['username','email','nickname','loginfailure'];
            $params = $this->request->post("row/a");
            if ($params)
            {
                if ($params['password'])
                {
                    if((md5($params['password']) == $row['pwd']) || (md5($params['password']) == $row['pwd_last']) ){
                        $this->error(__('The password is the same as the last two passwords'));
                    }
                    $params['pwd_edit'] = $params['password']; //获取未加密的初始密码做字符校验判断
                    $params['pwd_last'] = $row['pwd']; //将修改前密码(md5加密) 置换为 上一次修改的密码
                    $params['pwd'] = md5($params['password']); // md5加密保存此次修改密码 并保存
                    $params['salt'] = Random::alnum();
                    $params['password'] = md5(md5($params['password']) . $params['salt']);

                    array_push($scene_field, 'pwd_edit');
                }
                else
                {
                    unset($params['password'], $params['salt']);
                }
                if ($this->modelValidate)
                {
                    $validate = Loader::validate('Admin');
                    $validate->rule([
                        'username' => 'require|max:50|unique:admin,username,' . $row->id,
                    ]);
                    $validate->scene('edit', $scene_field);
                    if(!$validate->scene('edit')->check($params)){
                        $this->error($validate->getError());
                    }
                }

                $result = $row->allowField(true)->save($params);
                if ($result === false)
                {
                    $this->error($row->getError());
                }

                // 先移除所有权限
                model('AuthGroupAccess')->where('uid', $row->id)->delete();

                $group = $this->request->post("group/a");

                // 过滤不允许的组别,避免越权
                $group = array_intersect($this->childrenGroupIds, $group);

                $dataset = [];
                foreach ($group as $value)
                {
                    $dataset[] = ['uid' => $row->id, 'group_id' => $value];
                }
                model('AuthGroupAccess')->saveAll($dataset);
                $this->success();
            }
            $this->error();
        }
        $grouplist = $this->auth->getGroups($row['id']);
        $groupids = [];
        foreach ($grouplist as $k => $v)
        {
            $groupids[] = $v['id'];
        }
        $volumn_list = Db::name('admin_capacity')->where('admin_id','eq',$ids)->field('application_capacity')->find();
        $this->view->assign("row", $row);
        $this->view->assign("groupids", $groupids);
        $this->view->assign("volumn",$volumn_list['application_capacity']);
        return $this->view->fetch();
    }

    /**
     * 删除
     */
    public function del($ids = "")
    {
        if ($ids)
        {
            // 避免越权删除管理员
            $childrenGroupIds = $this->childrenGroupIds;
            $adminList = $this->model->where('id', 'in', $ids)->where('id', 'in', function($query) use($childrenGroupIds) {
                        $query->name('auth_group_access')->where('group_id', 'in', $childrenGroupIds)->field('uid');
                    })->select();
            if ($adminList)
            {
                $deleteIds = [];
                foreach ($adminList as $k => $v)
                {
                    $deleteIds[] = $v->id;
                }
                $deleteIds = array_diff($deleteIds, [$this->auth->id]);
                if ($deleteIds)
                {
                	Db::startTrans();
                	try{
		                $this->model->destroy($deleteIds);
		                model('AuthGroupAccess')->where('uid', 'in', $deleteIds)->delete();
		                Db::name('admin_capacity')->where('admin_id','in',$deleteIds)->delete();
	                }catch (\Exception $e){
						Db::rollback();
						$this->error();
	                }
	                Db::commit();
                    $this->success();
                }
            }
        }
        $this->error();
    }

    /**
     * 批量更新
     * @internal
     */
    public function multi($ids = "")
    {
        // 管理员禁止批量操作
        $this->error();
    }

    /**
     * 下拉搜索
     */
    protected function selectpage()
    {
        $this->dataLimit = 'auth';
        $this->dataLimitField = 'id';
        return parent::selectpage();
    }

}
