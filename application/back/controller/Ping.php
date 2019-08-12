<?php  
	namespace app\back\controller;
	use think\Controller;

    class Pinglun1 extends Controller{
    	public function add(){
    		if(request()->isPost()){
    			$data=[
    				'content'=>input('content'),
    				'use_time'=>time()
				];
				$res=db('uer')->insert($data);
				if($res){
					$this->redirect('添加成功','lists');
				}else{
					$this->redirect('添加失败','lists');
				}
    		}
    	}
    	public function lists(){
    		$model = db('uer');
    		$id=mt_rand('1,3');
    		$only=$model->where('user_id='.$id)->select();
    		$liuyan=$_SERVER['HTTP_USERR_AGENT'];
    		$RES = EXPLODE('',$liuyan);
    		$obj=db('use');
    		$totol = $obj->count();
    		$listRows = 2;
    		$page_obj = new \Page($totol,$listRows);
    		$page_info = $page_obj->fpage(array(3,4,5,6,7,8));
    		$sql = 'select * from use order by use_id desc'.$page_obj->limit;
    		$info = $obj->query($sql);

    		$this->assign('list',$info);
    		$this->assign('page_info',$page_info);
    		$this->assign('liulan',$res);
    		$this->assign('only',$only);
    		return $this->fetch('lists');
    	}
    }

?>