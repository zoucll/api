<?php  
	namespace app\back\model;
	use think\Model;

	class Auth extends Model
	{
		// 获取分页数据
		public function search(){
			$data = array();
			$list = $this->paginate(5);
			
			//获取分页信息
			$page = $list->render();
			$totol = $this->count();
			$listRows=5;
			$page_tool = new \Page($totol,$listRows);
			$page = $page_tool->fpage(array(1,2,3,4,5,6,7,8));
			$data['page']=$page;
			$data['list']=$list;
			return $data;
		}
		//数组排序
		public function _resort($data,$parent_id=0,$level=0,$isclear=true){
			//声明一个数组,用于存储排好的数据
			static $res = array();
			if($isclear){
				$res=array();
			}
			foreach ($data as $k => $v) {
			//先取出顶级分类
				if($v['parent_id']==$parent_id){
					$v['level']=$level;
					$res[]=$v;
					$this->_resort($data,$v['auth_id'],$level+1,false);
				}
			}
			return $res;
		}
		//获取所有的权限
  	    public function getAuthTree1(){
	  		//将一个二维数组变成六维数组
	  		$temp = db('auth')->select();
	  		$arr =array();
	  		//第一个循环是为了取出顶级权限
	  		foreach ($temp as $k => $v) {
	  			//先取出顶级权限:
	  			if($v['parent_id']==0){
	  				//第二个循环是为了取出二级权限
	  				foreach ($temp as $k1 => $v1) {
	  					//取出二级权限.特点是顶级的主键id作为第二级的父id
	  					if($v['auth_id']==$v1['parent_id']){
	  						//取三级权限为了取出第三级权限
	  						foreach ($temp as $k2=> $v2) {
	  							//取出三级权限.特点是顶级的主键id作为第二级的父id
	  							if($v1['auth_id']==$v2['parent_id']){
	  								$v1['son'][]=$v2;
	  							}
	  						}
	  						$v['son'][]=$v1;
	  					}
	  				}
	  				$arr[]=$v;
	  			}
	  		}
	  		return $arr;
  	    } 

  	    public function getAuthTree(){
  	    	//四维转六维
  	    	//从数据库auth表中去除顶级权限=>parent_id=0
  	    	$temp = db('auth')->where('parent_id',0)->select();
  	    	//从数据库auth表中取出相应的二级权限的parent_id ==上一级权限auth_id
  	    	foreach ($temp as $k => $v) {
  	    		$temp[$k]['son']=db('auth')->where('parent_id',$v['auth_id'])->select();
  	    		//从数据库auth表中取出相应的三级权限的parent_id ==上一级权限auth_id
  	    		foreach ($temp[$k]['son'] as $k1 => $v1) {
  	    			$temp[$k]['son'][$k1]['son']=db('auth')->where('parent_id',$v1['auth_id'])->select();
  	    		}
  	    	}
  	    	// dump($temp);die;S
  	    	return $temp;
  	    }
	}


?>