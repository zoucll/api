<?php  
 namespace app\back\controller;
 use app\back\model\Auth as AuthModel;

 class Index extends Base{
 	public function index(){
 		//获取管理员角色名称
 		$admin_id = session('admin_id');
 		if($admin_id==1){
 			$role_name ='超级管理员';
 		}else{
 			$role_name = db('admin_role')->alias('a')->field('role_name')
	 		->join('role b','a.role_id = b.role_id','LEFT')
	 		->where('a.admin_id',$admin_id)
	 		->value('role_name');
 		}
 		//根据admin_id,获取当前管理员第一级权限
 		if($admin_id==1){
 			$auth_model =new AuthModel();
 			$temp = $auth_model->getAuthTree();
 		}else{	
	 		$map = [
	 			'admin_id'=>$admin_id,
	 			'parent_id'=>0
	 		];
 			$temp = db('admin_role')->alias('a')->join('role_auth b','a.role_id = b.role_id','left')
	 				->join('auth c','b.auth_id=c.auth_id','left')
	 				->where($map)->select();
 		}
 		
 		foreach ($temp as $k => $v) {
 			$map = [
 				'admin_id'=>$admin_id,
 				'parent_id'=>$v['auth_id']
 			];
 			$temp[$k]['son']=db('admin_role')->alias('a')
 			->join('role_auth b','a.role_id = b.role_id','left')
 			->join('auth c ','b.auth_id= c.auth_id','left')
 			->where($map)->select();

 		}
 		$this->assign('temp',$temp);
 		$this->assign('role_name',$role_name);
 		return $this->fetch();
 	}
 	public function welcome(){
 		return $this->fetch();
 	}
 	public function brand(){
 		return $this->fetch('product-brand');
 	}
 	public function role(){
 		return $this->fetch('admin-role');
 	}
 	
 }
?>