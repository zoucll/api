<?php  
    namespace app\back\controller;
    use think\Controller;
    use app\back\model\Sun as Model;

    class Sun extends Controller
    {
        public function  add(){
            if(request()->isPost()){
                $data=input();
                $res=db('auth1')->insert($data);
                if($res){
                    $this->success('添加成功','show');
                }else{
                    $this->error('添加失败','add',5);
                }
            }else{
                return $this->fetch('add');
            }
        }
        public function add1(){
            if(request()->isPost()){
                $data=input();
                $res=db('quan')->insert($data);
                if($res){
                    $this->success('添加成功','show1');
                }else{
                    $this->error('添加失败','add1',5);
                }
            }else{
                return $this->fetch('add1');
            }

        }
        public function  del(){
            $auth_id=input('auth_id/d');//name
            $res=db('auth1')->delete($auth_id);
            if($res){
                $this->success('删除成功','show');
            }else{
                $this->error('删除失败','show',5);
            }
        }
        public function  show(){
            // $totol = db('auth1')->
            // $auth_model = new AuthModel();
            // $data=$auth_model->asd();
            // $page = $data['page'];
            // $list=$data['list'];
            // $this->assign('page',$page);
            // $this->assign('list',$list);
            // return $this->fetch('show');
            $total = db('auth1')->count();
            $listRows=2;
            $page = $page_model=new \Page($total,$listRows);
            $page=$page_model->fpage();
            $list=db('auth1')->paginate($listRows);
            $this->assign('page',$page);
            $this->assign('list',$list);
            return $this->fetch('show');
        }
        public function show1(){
            $total = db('quan')->count();
            $listRows=2;
            $page = $page_model=new \Page($total,$listRows);
            $page=$page_model->fpage();
            $list=db('quan')->paginate($listRows);
            $this->assign('page',$page);
            $this->assign('list',$list);
            return $this->fetch('show1');
        }
    }


?>