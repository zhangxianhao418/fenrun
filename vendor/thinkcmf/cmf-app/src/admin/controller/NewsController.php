<?php

namespace app\admin\controller;

use cmf\controller\AdminBaseController;



class NewsController extends AdminBaseController
{
	/**
	 * 展现添加消息页面
	 */
	public function add(){
		return $this->fetch();
	}

	/**
	 * 实现添加消息
	 */
	public function doAdd(){
		$data = input('param.');
		if($data){
			$title = $data['title'];
			$content = $data['content'];
			$time = time();
			
			$data_info = [
				'title'		=> 	$data['title'],
				'content'	=> 	$data['content'],
				'create_time'		=>	time(),
				'update_time'		=>	time(),
			];
			
			$bool = db('news')->insert($data_info);
			if($bool){
				return $this->success('添加成功');
			}else{
				return $this->error('添加失败');

			}
			
			
		}else{
			return $this->error('数据传入失败!');
		}
	}



	/**
	 * 展现消息列表
	 */
	public function news_list(){
		
		$list = model('news')
			  ->field('id,title,content,create_time')
			  ->order('create_time DESC')
			  ->paginate(15);
		$this->assign('list',$list);
		return $this->fetch();
		
	}

	/**
	 * 消息详情
	 */
	
	public function view(){
		$id = input('param.id');
		if($id){
			$data = db('news')
				  ->field('id,title,content,create_time')
				  ->where('id',$id)
				  ->find();
			$this->assign('data',$data);
			return $this->fetch();
		}else{
			return $this->error('数据传入失败!');
		}
	}
	
	/**
	 * 修改消息页面
	 */

	public function edit(){
		$id = input('param.id');
		if($id){
			$data = db('news')
				  ->field('id,title,content')
				  ->where('id',$id)
				  ->find();
			$this->assign('data',$data);	  
				
			return $this->fetch();
		}else{
			return $this->error('数据传入失败!');
		}
		
	}

	/**
	 * 实现修改消息功能
	 */
	
	public function doEdit(){
		$data = input('param.');
		if($data){
			$id = $data['id'];
			$data_info = [
				'title'=>$data['title'],
				'content'=>$data['content'],
				'update_time'=>time(),
			];
			$bool = db('news')->where('id',$id)->update($data_info);
			if($bool){
				return $this->success('数据修改成功');
			}else{
				return $this->error('数据未修改');
			}
		}else{
			return $this->error('数据传入失败!');
		}
	}
	
	
	/**
	 * 实现删除消息
	 */
	public function delete(){
		$id = input('param.id');
		if($id){
			$bool = db('news')
				  ->where('id',$id)
				  ->delete();
			if($bool){
				return $this->success('数据删除成功');
			}else{
				return $this->error('数据未删除');
			}
		}else{
			return $this->error('数据传入失败!');
		}
	
	}
	
	
	

}