<?php  
 namespace app\back\controller;
 use think\Controller;
 use app\back\model\Admin as AdminModel;
 class Login extends Controller{
 	//登录
 	public function index(){
 		if(request()->isPost()){
 			//验证数据
 			$data = [
 				'admin_name'=>input('admin_name'),
 				'admin_pwd'=>input('admin_pwd'),
 				'captcha'=>input('captcha'),
 			];
 			$rule =[
 				['admin_name','require','账号不能为空'],
 				['admin_pwd','require|min:4|max:10','密码不能为空|密码是4_10位'],
 				['captcha','require|captcha','验证码不能为空|验证码错误']
 			];
 			$res = $this->validate($data,$rule);
 			if($res!==true){
 				$this->error($res,'index',6);die;
 			}
 			$admin_model = new AdminModel();
 			$res_login = $admin_model->login();
 			// dump($res_login);
 			// die;
 			if($res_login==0){
 				$this->error('账号不存在','index',5);
 			}elseif($res_login ==1){
 				$this->error('账号被禁用','请联系超级管理员','index',5);
 			}elseif($res_login ==2){
 				$this->error('密码错误','index',5);
 			}else{
 				//登录成功.跳转到首页
 				$this->redirect('index/index');
 			}
 		}else{
 			return view('login');
 		}
 	}
 	//退出
 	public function logout(){
 		//判断管理员是否登录,就是根据session中是否保存管理员的信息,一般保存admin_id和admin_name
 		//换句话说,只要处于登录状态那么session中一定有admin_id
 		//反过来说如果session中,说明处于登录状态
 		session(null);//清空session
 		$this->redirect('index');
 	}
 	//验证码
 }

?>