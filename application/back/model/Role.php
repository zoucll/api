<?php  
  namespace app\back\model;
  use think\Model;
  use think\Db;

  class Role extends Model
  {
  	public function search(){
  		$page = input('page')?input('page'):1;
  		$listRows = 2;
  		$offset = ($page-1)*$listRows;//从哪里开始取值
  		// $sql ="select * a.role_id,a.role_name,a.role_note,group_concat(c.auth_name) auth_name ";
  		// $sql.="from role_id a ";
  		// $sql.="left join rle_auth b on a.role_id = b.role_id ";
  		// $sql.="left join auth c on b.auth_id = c.auth_id ";
  		// $sql.="group by a.role_id ";
  		$list=Db::query("select a.role_id,a.role_name,group_concat(c.auth_name) as go from role a 
  			       left join role_auth b on a.role_id = b.role_id 
  			       left join auth c on b.auth_id = c.auth_id group by a.role_id 
  			       limit $offset,$listRows");
      // dump($list);die;
			 $totol = $this->count();
			 $page_tool = new \Page($totol,$listRows);
			 $page = $page_tool->fpage(array(1,2,3,4,5,6,7,8));
			 return array(
			 	'list'=>$list,
			 	'page'=>$page
			 );
  	}
  }
?>