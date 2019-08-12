<?php  
	namespace app\back\controller;
	use think\Controller;

	class Base extends Controller{
		//继承controller
		//检测是否登录
		public function __construct(){
			parent::__construct();//为了不覆盖controller的构造方法
			//检测是否登录
			if(!session('admin_id')){
				$this->error('请登录后,在访问','login/index',5);
			}

			$admin_id  = session('admin_id');
			$module_name = request()->module();
			$controller_name = request()->controller();
			$action_name = request()->action();

			if($controller_name == 'Index' || $admin_id==1){
				return true;
			}
			$sql=" select count(*) cnt " ;
			$sql.=" from admin_role a ";
			$sql.=" left join role_auth b on a.role_id = b.role_id ";
			$sql.=" left join auth c on b.auth_id = c.auth_id ";
			$sql.=" where admin_id = ".$admin_id." and module_name = '".$module_name."' and controller_name = '"
			.$controller_name."' and action_name = '".$action_name."'";

			$res = db()->query($sql);
			// dump($res[0]['cnt']);
			if($res[0]['cnt']==0){
				$this->error('你无权限访问该页面,请联系超级管理员','index/welcome',5);
			}
		}
	}

?>