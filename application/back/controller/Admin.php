<?php  
	namespace app\back\controller;
    // use think\Controller;
    use app\back\model\Admin as AdminModel;
    header("content-type:text/html;charset=utf-8");
    class Admin extends Base
    {
    	public function lists(){
            $admin_model = new AdminModel();
            $data = $admin_model->search();
             // var_dump($data);die;
            $this->assign('list',$data['list']);
            $this->assign('page',$data['page']);
    		return $this->fetch('admin-list');
    	}
        //即点即改
        public function ajaxEditIsUse(){
            // $data = input('admin_id');
            // echo $data;
            $admin_id = input('admin_id/d');
            //获取当前管理员的状态
            // var_dump($admin_id);
            $cur_is_use = db('admin')->where('admin_id',$admin_id)->value('is_use');
            if($cur_is_use){
                //禁用
                db('admin')->where('admin_id',$admin_id)->setField('is_use',0);
                echo 0;
            }else{
                db('admin')->where('admin_id',$admin_id)->setField('is_use',1);
                echo 1;
            }
        }
        public function add(){
            if(request()->isPost()){
                $data=array(
                    'admin_name'=>input('admin_name'),
                    'admin_pwd'=>input('password'),
                    'admin_pwd2'=>input('password2'),
                    'admin_sex'=>input('sex'),
                    'admin_tel'=>input('phone'),
                    'admin_email'=>input('email'),
                    // 'admin_note'=>input('admin_note'),
                    'is_use'=>1,
                    'add_time'=>time()
                );
                //验证数据
                $rule = [
                    ['admin_name','require|min:4','用户名不能为空|用户名至少4位'],
                    ['admin_pwd','require|min:6','密码不能为空|密码必须4-10位|密码必须4-10'],
                    ['admin_pwd2','require|confirm:admin_pwd','密码2不能为空|两次密码不一致'],
                ];
                $result = $this->validate($data,$rule);
                if(true !==$result){
                    //验证失败,输出错误信息
                    $this->error($result,'add',5);
                    die;
                }
                unset($data['admin_pwd2']);
                // 入库
                $res_admin=db('admin')->insertGetId($data);
                if($res_admin){
                    $admin_data = array(
                        'role_id'=>input('role_id'),
                        'admin_id'=>$res_admin,
                        'add_time'=>time()
                    );
                    $res=db('admin_role')->insert($admin_data);
                    if($res){
                        $this->success('添加成功','lists');
                    }
                }
                $this->error('添加失败','add');
            }else{
                $admin_id=input('admin_id/d');
                $info=db('admin')->find($admin_id);
                $this->assign('info',$info);
                //获取所有的值
                $roles=db('role')->select();
                $this->assign('roles',$roles);
                 return $this->fetch('admin-add');
            }
           
        }
    	public function del(){
            
    		return $this->fetch('admin-del');
    	}
    	public function edit(){
            $id = input('admin_id/d');
            if(request()->isPost()){
                $data_admin_role =[
                    'role_id'=>input('role_id'),
                    'add_time'=>time(),
                    'admin_id'=>input('admin_id')
                ];
                $data_admin=[
                    'admin_name'=>input('admin_name'),
                    'admin_pwd'=>input('password'),
                    'admin_pwd2'=>input('password2'),
                    'admin_sex'=>input('sex'),
                    'admin_tel'=>input('phone'),
                    'admin_email'=>input('email'),
                    'admin_note'=>input('admin_note')
                ];
                // dump($data_admin);die;
                 $rule = [
                    ['admin_name','require|min:4','用户名不能为空|用户名至少4位'],
                    ['admin_pwd','require|min:6','密码不能为空|密码必须4-10位|密码必须4-10'],
                    ['admin_pwd2','require|confirm:admin_pwd','密码2不能为空|两次密码不一致'],
                ];
                $result = $this->validate($data_admin,$rule);
                if(true !==$result){
                    //验证失败,输出错误信息
                    $this->error($result,url('edit','admin_id='.$input('admin_id')),5);
                    die;
                }
                unset($data_admin['admin_pwd2']);
                //入库
                $res=db('admin')->where('admin_id='.$id)->update($data_admin);
                if($res !==false){
                    $res_del = db('admin_role')->where('admin_id='.input('admin_id'))->delete();
                    if($res_del!==false){
                        //在插入数据
                        $res_insert = db('admin_role')->insert($data_admin_role);
                        if($res_insert){
                            $this->success('修改成功','lists');die;
                        }
                    }
                }
                $this->error($res,url('edit','admin_id='.$input('admin_id')),5);

            }else{
                //当前管理员admin_id
                $admin_id = input('admin_id/d');
                //当前管理员信息
                $info=db('admin')->find($admin_id);
                $this->assign('info',$info);
                //获取所有角色
                $roles = db('role')->select();
                $this->assign('roles',$roles);
                // 根据admin_id,获取当前管理员随队用得到role_id ----admin_role表
                $role_res=db('admin_role')->where('admin_id='.$admin_id)->value('role_id');
                $this->assign('role_res',$role_res);
                return $this->fetch('admin-edit');
            }
    		
    	}
        public function delete(){
            $data_admin = input('id/a');//获取id值
            $data = implode(',',$data_admin);//数组转字符串
            $res=AdminModel::destroy($data);
            if($res){
                $admin_res=db('admin_role')->where('admin_id','in',$data)->delete();
                if($admin_res){
                    $this->success('删除成功','lists');die;
                }
            }
            $this->error('删除失败','lists');
        }
    }
    
?>