<?php
namespace app\index\controller;

use cmf\controller\UserBaseController;
use think\Db;
use app\index\model\CenterModel;

/**
 * 前端用户个人中心控制器
 */
class CenterController extends CenterBaseController{
	
	/**
	 * session中获取用户id
	 * 根据用户id查找	用户头像	用户状态
	 */
	public function index(){
		
		$data = CenterModel::getUserInfo($this->userid);
		
		$this->assign([
			'mobile'=>$this->mobile,
			'avatar'=>$data['avatar'],
			'user_status'=>$data['user_status'],
			'examine'=>$data['examine'],
			'cause'=>$data['cause'],
		]);
		
		return $this->fetch();
		
	}
	
	
	
}