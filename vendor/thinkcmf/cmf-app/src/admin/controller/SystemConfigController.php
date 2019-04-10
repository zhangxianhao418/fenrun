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
namespace app\admin\controller;

use cmf\controller\HomeBaseController;
use think\Config;


class SystemConfigController extends HomeBaseController{
	

	/**
	 * 展现设置佣金比例页面
	 */
	public function commission(){
		
		$data = cmf_get_option('commission');
		$this->assign('data',$data);
		return $this->fetch();
	}
	
	
	/**
	 * 实现设置佣金比例
	 */
	public function doCommission(){
		
		$data = input('param.');
		if(!empty($data)){
			$bool = cmf_set_option('commission',['commission'=>$data['commission']]);
			if($bool){
				return $this->success('数据存储成功!');
			}else{
				return $this->error('数据存储失败!');
			}
		}else{
			return $this->error('数据传入失败!');
		}
	}
	
	/**
	 * 账户余额释放周期页面
	 */
	public function release(){
		$data = cmf_get_option('release');
		$this->assign('data',$data);
		return $this->fetch();
	}
	
	/**
	 * 实现设置账户余额释放周期
	 */
	public function doRelease(){
		
		$data = input('param.');
		if(!empty($data)){
			$bool = cmf_set_option('release',['release'=>$data['release']]);
			if($bool){
				return $this->success('数据存储成功!');
			}else{
				return $this->error('数据存储失败!');
			}
		}else{
			return $this->error('数据传入失败!');
		}
	}
	
	/**
	 * 设置买卖比例页面
	 */
	public function deal(){
		$data = cmf_get_option('deal');
		$this->assign('data',$data);
		return $this->fetch();
	}
	
	/**
	 * 实现设置买卖比例
	 */
	public function doDeal(){
		
		$data = input('param.');
		if(!empty($data)){
			$bool = cmf_set_option('deal',['deal'=>$data['deal']]);
			if($bool){
				return $this->success('数据存储成功!');
			}else{
				return $this->error('数据存储失败!');
			}
		}else{
			return $this->error('数据传入失败!');
		}
	}
	
	
	/**
	 * 设置团队的最多人数
	 */
	public function teamnum(){
		$teamnum_A = cmf_get_option('teamnum_A');
		$teamnum_B = cmf_get_option('teamnum_B');
		$teamnum_C = cmf_get_option('teamnum_C');
		$this->assign('teamnum_A',$teamnum_A);
		$this->assign('teamnum_B',$teamnum_B);
		$this->assign('teamnum_C',$teamnum_C);
		return $this->fetch();
	}
	
	/**
	 * 实现设置买卖比例
	 */
	public function doTeamnum(){
		
		$data = input('param.');
		// var_dump($data);exit();
		if(!empty($data)){
			$bool = cmf_set_option('teamnum_A',['teamnum_A'=>$data['teamnum_A']]);
			if($bool){
				$bool = cmf_set_option('teamnum_B',['teamnum_B'=>$data['teamnum_B']]);
				if($bool){
					$bool = cmf_set_option('teamnum_C',['teamnum_C'=>$data['teamnum_C']]);
					if($bool){
						return $this->success('数据存储成功!');
					}else{
						return $this->error('数据C存储失败!');
					}
				}else{
					return $this->error('数据B存储失败!');
				}
			}else{
				return $this->error('数据A存储失败!');
			}
			
			
			
		}else{
			return $this->error('数据传入失败!');
		}
	}
	
	
	
	
	
	
	
}