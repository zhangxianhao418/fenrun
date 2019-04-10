<?php
namespace app\index\controller;

use cmf\controller\HomeBaseController;


/**
 * Index模块下的首页控制器
 */
class IndexController extends HomeBaseController{
	
	/**
	 * 展现首页
	 */
	public function index(){
		
		return $this->fetch(':index');
		
	}
	
	
	
	
}