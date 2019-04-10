<?php
namespace app\index\controller;

use cmf\controller\UserBaseController;
use think\Db;
/**
 * Index模块下的用户控制器
 */
class UserController extends UserBaseController{
	
	 public function initialize(){
		 
	}
	/**
	 * 前端登录页面
	 */
	public function login()
	{
	    
	    
	
	    $user_id = session('USER_ID');
	    if (!empty($user_id)) {//已经登录
	        return redirect(url("index/Center/index"));
	    } else {
	        
	        return $this->fetch(":login");
	    }
	}
	
	/**
	 * 登录方法
	 */

	 public function doLogin()
	{
	    
	
	    $captcha = $this->request->param('captcha');
	    if (empty($captcha)) {
	        $this->error(lang('CAPTCHA_REQUIRED'));
	    }
	    //验证码
	    if (!cmf_captcha_check($captcha)) {
	        $this->error(lang('CAPTCHA_NOT_RIGHT'));
	    }
	
	    $mobile = $this->request->param("mobile");
	    if (empty($mobile)) {
	        $this->error(lang('MOBILE'));
	    }
	    $pass = $this->request->param("password");
	    if (empty($pass)) {
	        $this->error(lang('PASSWORD_REQUIRED'));
	    }
	    
	
	    $result = Db::name('user')->where('mobile',$mobile)->find();
		// var_dump($result);exit();
	
	    if (!empty($result) && $result['user_type'] == 2) {
	        if (cmf_compare_password($pass, $result['user_pass'])) {
	            if($result['user_status']!=1){
					$this->error('账号异常');
				}
	            //登入成功页面跳转
	            session('USER_ID', $result["id"]);
	            session('mobile', $result["mobile"]);
	            $result['last_login_ip']   = get_client_ip(0, true);
	            $result['last_login_time'] = time();
	            $token                     = cmf_generate_user_token($result["id"], 'web');
	            if (!empty($token)) {
	                session('token', $token);
	            }
	            Db::name('user')->update($result);
	            cookie("mobile", $mobile, 3600 * 24 * 30);
	            $this->success(lang('LOGIN_SUCCESS'), url("index/Center/index"));
	        } else {
	            $this->error(lang('PASSWORD_NOT_RIGHT'));
	        }
	    } else {
	        $this->error(lang('USERNAME_NOT_EXIST'));
	    }
	}
	
	/**
	 * 用户退出
	 */
	public function logout()
	{
	    session('USER_ID', null);
	    session('mobile', null);
	    return redirect(url('/', [], false, true));
	}
	
	/**
	 * 展现注册页面
	 */
	public function register(){
		
		return $this->fetch(':register');
		
	}
	
	/**
	 * 注册方法
	 */
	
	public function doRegister(){
		
		$user = input('post.');
		// var_dump($user);exit();
		$mobile = $user['mobile'];
		
		$bool = db('user')->where('mobile',$mobile)->find();
		if(is_null($bool)){
			if($user['pwd']==$user['repwd']){
				$data = [
					'mobile' =>$user['mobile'],
					'user_pass'=>cmf_password($user['pwd']),
					'user_type'=>2,
					'create_time'=>time(),
					'last_login_time'=>time(),
				];
				if(db('user')->insert($data)){
					return $this->success('注册成功',url('index/index/index'));
				} else{
					$message = '注册失败';
				}
			
			}else{
				$message = '两次输入密码不一致';
			
			}
			
			
		}else{
			$message = '该手机号已注册';
			
			
		}
		return $this->error($message);
	}
	
	/**
	 * 忘记密码页面
	 */
	public function forget(){
		return $this->fetch(':forget');
	}
	
	/**
	 * 忘记密码功能
	 */
	public function doForget(){
		
		    if ($this->request->isPost()) {
		
		        $data = $this->request->param();
		        
		        if (empty($data['pwd'])) {
		            $this->error("新密码不能为空！");
		        }
		
		        $mobile = $data['mobile'];
		
		        $admin = Db::name('user')->where("mobile", $mobile)->find();
		
		        $password    = $data['pwd'];
		        $rePassword  = $data['repwd'];
		
		        
		            if ($password == $rePassword) {
		
		              
		                    Db::name('user')->where('mobile', $mobile)->update(['user_pass' => cmf_password($password)]);
		                    $this->success("密码修改成功！");
		                
		            } else {
		                $this->error("密码输入不一致！");
		            }
		
		        
		    }
		
	}
	
	/**
	 * 短信接口
	 */
	
	public function duanxin(){
		
	}
	
}