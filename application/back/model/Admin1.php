<?php  
	namespace app\back\controller;
	use think\controller;
	use app\back\model\Admin as AdminModel;
	header('content-type:text/html;charset=utf-8');

	class Admin extends Controller{
		public function lists(){
			$admin_model = new AdminModel();
			$data = $admin_model->search();
			$this->assign('list',$data['list']);
			$this->assign('page',$data['page']);
            return $this->fetch('admin-list');
		}
		//用于修改启用状态
		public function ajaxEditIsUse(){
			$data = input('admin_id');
			echo $data;
		}
		public function add(){
			if(request()->isPost()){
				$data = array(
					
				);
			}
		}
	}

?>
