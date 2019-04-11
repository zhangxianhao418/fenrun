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


class UserListController extends AdminBaseController{

	/**
	 * 推荐列表页面
	 */
	
	public function recommend(){
		
		$list = model('recommend')
			  ->field('id,mobile,u_mobile,create_time')
			  ->paginate(15);
		
		$this->assign('list',$list);
		return $this->fetch();
	}
	
}