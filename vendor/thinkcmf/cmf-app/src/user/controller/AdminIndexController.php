<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2019 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Powerless < wzxaini9@gmail.com>
// +----------------------------------------------------------------------

namespace app\user\controller;

use cmf\controller\AdminBaseController;
use think\Db;
use think\db\Query;
use app\user\model\PayModel;
use app\user\model\IncomeModel;

/**
 * Class AdminIndexController
 * @package app\user\controller
 *
 * @adminMenuRoot(
 *     'name'   =>'用户管理',
 *     'action' =>'default',
 *     'parent' =>'',
 *     'display'=> true,
 *     'order'  => 10,
 *     'icon'   =>'group',
 *     'remark' =>'用户管理'
 * )
 *
 * @adminMenuRoot(
 *     'name'   =>'用户组',
 *     'action' =>'default1',
 *     'parent' =>'user/AdminIndex/default',
 *     'display'=> true,
 *     'order'  => 10000,
 *     'icon'   =>'',
 *     'remark' =>'用户组'
 * )
 */
class AdminIndexController extends AdminBaseController
{

    /**
     * 后台本站用户列表
     * @adminMenu(
     *     'name'   => '本站用户',
     *     'parent' => 'default1',
     *     'display'=> true,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '本站用户',
     *     'param'  => ''
     * )
     */
    public function index()
    {
        $content = hook_one('user_admin_index_view');

        if (!empty($content)) {
            return $content;
        }

        $list = Db::name('user')
            ->where(function (Query $query) {
                $data = $this->request->param();
                if (!empty($data['uid'])) {
                    $query->where('id', intval($data['uid']));
                }

                if (!empty($data['keyword'])) {
                    $keyword = $data['keyword'];
                    $query->where('recommend|user_nickname|examine|mobile', 'like', "%$keyword%");
                }

            })
            ->order("create_time DESC")
            ->paginate(10);
        // 获取分页显示
        $page = $list->render();
        $this->assign('list', $list);
        $this->assign('page', $page);
        // 渲染模板输出
        return $this->fetch();
    }

    /**
     * 本站用户拉黑
     * @adminMenu(
     *     'name'   => '本站用户拉黑',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '本站用户拉黑',
     *     'param'  => ''
     * )
     */
    public function ban()
    {
        $id = input('param.id', 0, 'intval');
        if ($id) {
            $result = Db::name("user")->where(["id" => $id, "user_type" => 2])->setField('user_status', 0);
            if ($result) {
                $this->success("会员拉黑成功！", "adminIndex/index");
            } else {
                $this->error('会员拉黑失败,会员不存在,或者是管理员！');
            }
        } else {
            $this->error('数据传入失败！');
        }
    }

    /**
     * 本站用户启用
     * @adminMenu(
     *     'name'   => '本站用户启用',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '本站用户启用',
     *     'param'  => ''
     * )
     */
    public function cancelBan()
    {
        $id = input('param.id', 0, 'intval');
        if ($id) {
			Db::name("user")
			->where(["id" => $id, "user_type" => 2])
			->setField('user_status', 1);
			
            $this->success("会员启用成功！", '');
        } else {
            $this->error('数据传入失败！');
        }
    }
	
	
	/**
	 * 查看本站用户详情	支出记录
	 */
	public function pay(){
		$user_id = input('param.id', 0, 'intval');
		if($user_id){
			$userInfo 		=  Db::name("user")
							->field('id,mobile,user_login,balance,money,freeze')
							->where(['id'=>$user_id,"user_type" => 2])
							->find();
							
			$payInfo		= PayModel::where('user_id',$user_id)->paginate(15);
			
			
			$this->assign('userInfo',$userInfo);
			$this->assign('payInfo',$payInfo);
			
			return $this->fetch();
		}else{
			$this->error('数据传入失败！');
		}
	}
	
	/**
	 * 查看本站用户详情	收入记录
	 */
	public function income(){
		$user_id = input('param.id', 0, 'intval');
		if($user_id){
			$userInfo 		=  Db::name("user")
							->field('id,mobile,user_login,balance,money,freeze')
							->where(['id'=>$user_id,"user_type" => 2])
							->find();
							
			$incomeInfo 	= IncomeModel::where('user_id',$user_id)
							->paginate(15);
			
			
			$this->assign('userInfo',$userInfo);
			$this->assign('incomeInfo',$incomeInfo);
			
			return $this->fetch();
		}else{
			$this->error('数据传入失败！');
		}
	}
	
	/**
	 * 编辑本站用户页面
	 */
	public function edit(){
		$user_id = input('param.id', 0, 'intval');
		if($user_id){
			$userInfo 		=  Db::name("user")
							->field('id,initial,mobile,user_login,balance,money,freeze')
							->where(['id'=>$user_id,"user_type" => 2])
							->find();
			
			
			$this->assign('userInfo',$userInfo);
			
			
			return $this->fetch();
		}else{
			$this->error('数据传入失败！');
		}
		
	}
		
		
	/**
	 * 修改本站用户部分信息
	 */
	
	public function doEdit(){
		$data = input('param.');
		if(!empty($data)){
			$id = $data['id'];
			$money = $data['money'];
			
			if(isset($data['initial'])){
				$initial = $data['initial'];
			}else{
				$initial = 0;
			}
			$data_info = db('user')
						->field('money')
						->where('id',$id)
						->find();
						
			$money += $data_info['money'];
			
			$result = db('user')
					->where('id',$id)
					->update(['initial'=>$initial,'money'=>$money]);
			if($result){
				return $this->success('修改成功');
			}else{
				return $this->error('修改失败');
			}
			
		}else{
			$this->error('数据传入失败！');
		}
		
		
		
	}	
		
		
	/**
	 * 待审核用户列表页面
	 */	
	public function examineList(){
		$examine_list = model('user')
					->field('id,mobile,create_time,examine')
					->where('examine',0)
					->where('user_type',2)
					->paginate(15);
		$this->assign('examine_list',$examine_list);
		return $this->fetch();
	}
		
		
	/**
	 * 审核用户页面
	 */	
	public function examine(){
		$id = input('param.id', 0, 'intval');

// 		根据用户id查询用户提交的审核信息与图片
		$data = db('examine')
			field('id,user_login,mobile,img1,img2,create_time')
			->where('user_id',$id)
			->find();
		$this->assign('data',$data);
		return $this->fetch();
	}
		
		
		
		
		
		
		
		
		
		
}