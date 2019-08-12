<?php  
	namespace app\back\controller;
	use think\Controllr;
	use think\Db;

	class Abc extends Controllr{
		public function add(){
			if(request()->isPost()){
				 $data = input();
				 $res =Db::name('auth1')->insert($data);
				 if($res){
				 	$this->success('添加成功','show');
				 }else{
				 	$this->error('添加失败','add');
				 }
			}else{
				return $this->fetch('add');
			}
		}
		public function show(){
			// $total=db('auth1')->count();
			// $listshow=2;
			// $page_model = new \Page($total,$listshow);
			// $page = $page_model->fpage(array(1,2,3,3,4,5,6,7,8));
			// $lists=db('auth1')->pageinate(2);
			// $this->assign('page',$page);
			// $this->assign('lists',$lists);
			// return $this->fetch('show');
			$ta = db('auth')->count();
			$listshow = 2;
			$page_model = new \Page($ta,$listshow);
			$page = $page_model->fpage(array(1,2,3,3,4,5,6,7,8));
			$lists = db('auth')->pageinate(2);
			$this->assign('page',$page);
			$this->assign('list',$list);
			return $this->fetch('show');
		}
	}


?>