<?php
namespace app\index\controller;

class CenterBaseController extends UserBaseController
{
	protected $mobile;
	protected $userid;
	
    public function initialize()
    {
        parent::initialize();
        $mobile = session('mobile');
        $userid = session('USER_ID');
		$this->$mobile = $mobile;
		$this->$userid = $userid;
		
    }
	
	
	
	

}