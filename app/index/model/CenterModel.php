<?php
namespace app\index\model;

use think\Model;

class CenterModel extends Model{
	
	protected $table = 'jzb_user';
	
	public function getUserInfo($userid){
		$userInfo = $this->field('avatar,user_status,examine,cause')
						->where('id',$userid)
						->find();
		return $userInfo;
	}
}

