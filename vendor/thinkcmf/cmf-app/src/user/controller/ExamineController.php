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


class ExamineController extends AdminBaseController{


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
			->field('id,idcard,user_id,user_login,mobile,img1,img2,create_time')
			->where('user_id',$id)
			->where('status',0)
			->find();
		$this->assign('data',$data);
		return $this->fetch();
	}
	
	/**
	 * 通过审核
	 */
	public function examine_pass(){
		$data = input('param.');
		if($data){
			$e_id = $data['id'];
			$u_id = $data['user_id'];
	// 		查询审核信息
			$e_info = db('examine')
					->field('user_id,user_login,mobile,idcard')
					->where('id',$e_id)
					->find();
			if($e_info&&$e_info['user_id']==$u_id){
				$bool = db('examine')
					->where('id',$e_id)
					->update(['status'=>1]);
					
				if($bool){
					$msg = '审核信息修改成功!';
				}else{
					$msg = '审核信息修改失败!';
				}
				$data_info = [
					'user_login'	=>	$e_info['user_login'],
					'idcard'		=>	$e_info['idcard'],
					'e_mobile'		=>	$e_info['idcard'],
					'user_status'	=>	1,
					'examine'		=>	2
				];
				$re = db('user')
					->where('id',$u_id)
					->update($data_info);
				if($re){
					$msg .= ' 用户信息修改成功!';
					return $this->success($msg,url('Examine/examineList'));
				}else{
					$msg .= ' 用户信息修改失败!';
					return $this->error($msg);
				}
				
			}
	// 		对比审核信息的uid和前端传来的uid 
	// 		对比成功后修改审核信息的状态
	// 		修改用户信息的mobile examine user_status user_login idcard字段
	
	
		}else{
			return $this->error('数据传入失败!');
		}

	}
	
	/**
	 * 审核不予通过
	 */
	public function examine_no_pass(){
// 		根据前端传来的uid修改对应的审核信息状态status
// 		根据前端传来的uid修改对应的用户信息
// 		修改字段有 user_status examine cause
		$data = input('param.');
		if($data){
			$cause = $data['cause'];
			$uid = $data['id'];
			$bool = db('examine')
				  ->where('user_id',$uid)
				  ->update(['status'=>2]);
			if($bool){
				$msg = '审核信息修改成功!';
			}else{
				$msg = '审核信息修改失败!';
			}
			$data_info = [
				'cause'			=>	$cause,
				'user_status'	=>	0,
				'examine'		=>	3
			];
			$re = db('user')
				->where('id',$uid)
				->update($data_info);
			if($re){
				$msg .= ' 用户信息修改成功!';
				return $this->success($msg,url('Examine/examineList'));
			}else{
				$msg .= ' 用户信息修改失败!';
				return $this->error($msg);
			}	
			
		}else{
			return $this->error('数据传入失败!');
		}
		
		
		
	}
	
}