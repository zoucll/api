<?php  
	namespace app\back\controller;
	// use think\Controller;
	use app\back\model\Auth as AuthModel;


	class Auth extends Base
	{
		public function add(){
			if(request()->isPost()){
				//获取数据
				$data=input();
				//入库
				$data['add_time']=time();
				$res=db('auth')->insert($data);
				if($res){
					$this->success('添加成功','lists',3);
				}else{
					$this->error("添加失败",'add',5);
				}
			}else{
				//获取所有的权限
				$red=db('auth')->select();//在数据库中获取所有数据
				$auth_model = new AuthModel();
				$red = $auth_model->_resort($red);
				$this->assign('red',$red);
				return $this->fetch('admin-permission-add');
			}
			
		}
		public function edit(){
			if(request()->isPost()){
				$data=input();
				$data['add_time']=time();
				// var_dump($data);die;
				// unset($data['admin-role-save']);
				$res=db('auth')->update($data);
				if($res!==false){
					$this->success('修改成功','lists',3);
				}else{
					$this->error('修改失败',url('edit','$auth_id='.$data['auth_id']),5);
				}
			}else{
				//当前权限id数据的记录
				$auth_id = input('auth_id/d');
				// var_dump($auth_id);die;
				$auth=db('auth')->find($auth_id);
				//获取所有的权限
				$red=db('auth')->select();//在数据库中获取所有数据
				$auth_model = new AuthModel();
				$red = $auth_model->_resort($red);
				$this->assign('red',$red);
				$this->assign('auth',$auth);
				return $this->fetch('admin-permission-edit');
			}
			
		}
		public function del(){
			$auth_id=input('auth_id/d');
			$res=db('auth')->delete($auth_id);
			if($res){
				$this->success('删除成功','lists',3);
			}else{
				$this->error('删除失败','lists',5);
			}
		}
		public function lists(){
			$total = db('auth')->count();
            $listRows=5;
            $page_model=new \Page($total,$listRows);
            $page=$page_model->fpage(array(0,1,2,3,4,5,6,7,8));
            $list=db('auth')->paginate($listRows);
            $this->assign('page',$page);
            $this->assign('list',$list);
            return $this->fetch('admin-permission');
		}
		// public function dell(){
		// if (request()->isAjax()) {
  //              $new_id =rtrim($_POST['id'],',');
  //              $res=db('auth')->delete($new_id);
  //              if($res){
		// 		echo 1;
		// 	}else{
		// 		echo 2;
		// 	}
		//   }
		// }
		public function delete(){
		$data = input('id/a');//name值
		// dump($data);die;
			$res= Db('auth')->delete($data);
			if($res){
				$this->success('批量删除成功','lists');
			}else{
				$this->error('批量删除失败');
			}   
		}
	}


?>