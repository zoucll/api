<?php  
	namespace app\back\model;
	use think\Model;

	class Admin extends Model
	{
		public function search(){
			/*************搜索功能****************/
			$where = "";
			$where_count = "";
			//按照时间搜索
			$date_start = trim(input('date_start'));
			$date_end = trim(input('date_end'));
			if($date_start && !$date_end){
				$date_start = $date_start."00:00:00";
				$time_start = strtotime($date_start);

				$where.="a,add_time>=".$time_start." " ;
				$where_count.= "add_time".$time_start." ";
			}elseif(!$date_start && $date_end){
				$date_end = $date_end."23:59:59";
				$time_end = strtotime($date_end);

				$where.="a.add_time<='.$time_end.' ";
				$where_count.="add_time<=".$time_end." ";
			}elseif($date_start && $date_end){
				$date_start = $date_start."00:00:00";
				$time_start = strtotime($date_start);

				$date_end = $date_end."23:59:59";
				$time_end = strtotime($date_end);

				$where .="a.add_time between ".$time_start." and ".$time_end." ";
				$where_count.="add_time between ".$time_start." and ".$time_end." ";
			}
			// //按照用户名搜索
			// $admin_name = trim(input('admin_name'));
			// if($where){
			// 	$where.=" and admin_name like  '%".$admin_name."%' ";
			// 	$where_count.=" admin_name like '%".$admin_name."%' ";
			// }else{
			// 	$where.="admin_name like '%".$admin_name."%' ";
			// 	$where_count.="admin_name like '%".$admin_name."%' ";
			// }
            // var_dump($where);die;
			//分页数据
			$page = input('page')?input('page'):1;
			$listRows = 2;
			$offset =($page-1)*$listRows;
			// $sql =	"select a.admin_id,a.admin_name,a.admin_tel,a.admin_email,a.add_time,a.is_use
			// ,c.role_name from admin a left join admin_role b on a.admin_id = b.admin_id left join
			//  role c on b.role_id = c.role_id limit ".$offset.",".$listRows.' ';	
			$sql = " select a.admin_id,a.admin_name,a.admin_tel,a.admin_email,a.add_time,a.is_use,c.role_name ";
			$sql.= " from admin a ";
			$sql.= " left join admin_role b on a.admin_id = b.admin_id ";
			$sql.= " left join role c on b.role_id = c.role_id ";
			// $sql.= " group by a.admin_id ";	
			if($where){
				$sql.=" where ".$where;
			}
			$sql.= ' order by a.admin_id desc ';
			$sql.= "limit ".$offset.",".$listRows." ";
			$list= db()->query($sql);
			//分页信息
			$totol = $this ->where($where_count)->count();
			$page_tool = new \Page($totol,$listRows);
			$page = $page_tool->fpage(array(1,2,3,4,5,6,7,8));
			return array(
				'list'=>$list,
				'page'=>$page
			);
		}
		public function  login(){
			$res_name =$this->where('admin_name',input('admin_name'))->find();
			// dump($res_name);
			// dump($res_name['admin_id']);
			// dump($res_name['is_use']);
			if($res_name){
				//检查是否启用
				if($res_name['is_use']==1){
					//检查密码是否存在
					if($res_name['admin_pwd']==input('admin_pwd')){
						//将登陆管理员的信息写入session,为了记录登录状态
						session('admin_id',$res_name['admin_id']);
						session('admin_name',$res_name['admin_name']);
						return 3;//登录成功
					}else{
						return 2;//密码错误
					}
				}else{
					return 1;//账号被禁用
				}

			}else{
				return 0;//没有此账号
			}
		}
	}


?>