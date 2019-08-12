<?php  
	namespace app\back\controller;
	// use think\Controller;
	use app\back\model\Auth as AuthModel;
	use think\Db;
	use app\back\model\Role as RoleModel;
	class Role extends Base
	{
		public function add(){
			if(request()->isPost()){
				$data=input();
				// dump($data);die;
				$role_data=array(
						'role_name'=>input('role_name'),
						'role_note'=>input('role_note'),
						'add_time'=>time() 
					);
				// dump($role_data)
				//角色表入库,然后返回最新的auth_id,用于绑定权限表
				$role_res = db('role')->insertGetId($role_data)+0;
				//关联表入库
				$auth_data = input('auth_id/a');
				// dump($auth_data);die;
				if($role_res){
					foreach ($auth_data as $k => $v) {
						$auth_insert =array(
								'role_id'=>$role_res,//将表中的数据赋值给他
								'auth_id'=>$v,//将循环的数据赋值给他
								'add_time'=>time()
							);
						$ra_res=db('role_auth')->insert($auth_insert);
					}
					$this->success('添加成功','lists',3);
				}else{
					$this_>error('添加失败','add',5);
				}
			}else{
				//获取所有的权限
				$auth_model = new AuthModel();
				$authTree = $auth_model->getAuthTree();
				// dump($authTree);die;
				$this->assign('authTree',$authTree);
				return $this->fetch('admin-role-add');
			}
			
		}
		public function edit(){
			if(request()->isPost()){
				$data =input();
				$role_data =[
					'role_id'=>input('role_id'),
					'role_name'=>input('role_name'),
					'role_note'=>input('role_note'),
					'add_time'=>time()
				];
				// 角色表入库
				$role_res = db('role')->update($role_data);
				// 关联表入库
				$auth_data = input('auth_id/a');
				if($role_res!==false){
					//先删除之前绑定的权限
					$res_role_auth=db('role_auth')->where('role_id','in',input('role_id'))->delete();
					if($res_role_auth!==false){
						foreach($auth_data as $k =>$v){
							$auth_insert = array(
								'role_id'=>input('role_id'),
								'auth_id'=>$v,
								'add_time'=>time()
							);
							
						}
						$res_da = db('role_auth')->insert($auth_insert);
						if($res_da){
							$this->success('修改成功',url('lists'));
						}else{
							$this->error('修改失败');
						}
					}
				}
			}else{
				$role_id =input('role_id/d');
				// dump($role_id);die;
				//获取角色信息
				$info = db('role')->find($role_id);
				$this->assign('info',$info);
				// dump($info);die;
				//获取角色绑定权限-----role_auth
				//第一种方法
				$cur_auth = db('role_auth')->field('auth_id')->where('role_id',$role_id)->select();
				//将二维数组转化为字符串
				$cur_auth_str = '';
				foreach ($cur_auth as $k => $v) {
					$cur_auth_str.=$v['auth_id'].',';
				}
				$this->assign('cur_auth_str',$cur_auth_str);
				//获取所有权限
				$auth_model = new AuthModel();
				$authTree = $auth_model->getAuthTree();
				$this->assign('authTree',$authTree);

				return $this->fetch('admin-role-edit');
			}
			
		}
		public function del(){
			$temp  =trim(input('role_id'));
			$role_id=ririm($temp,",");
			$res = db('role_auth')->where('role_id','in',$role_id)->delete();	
			if($res){
				$res_role = db('role')->where('role_id','in',$role_id)->delete();
				if($res_role){
					$this->success('删除成功','lists');
				}
			}
			$this->error('删除失败','lists');		
		}
		public function lists(){
			$role_model = new RoleModel();
			$data = $role_model->search();
			// var_dump($data);die;
			$this->assign('list',$data['list']);
			$this->assign('page',$data['page']);
			return view('admin-role');
		}
		// public function dell(){
		// 	$id=input('zao_id/a');
		// 	$res=RoleModel::destroy($id);
		// 	if($res){
		// 		$this->success('删除成功','lists');
		// 	}else{
		// 		$this->error('删除失败','lists',5);
		// 	}

		// }
	}


?>