<?php  
  namespace app\back\controller;
  use think\Controller;

  class Brand extends Controller{
      public function lists(){
      	  $total = Db('brand')->count();//总条数
      	  $listRows = 1;
      	  $page_model = new \Page($total,$listRows);
      	  $page = $page_model->fpage(array(0,1,2,3,4,5,6,7,8));
      	  $list = Db('brand')->paginate(1);
          // print_r($list);die;print_r
      	  $count = Db('brand')->count();
      	  //把分页数据赋值给模板变量list
      	  $this->assign('list',$list);
      	  $this->assign('page',$page);
      	  $this->assign('count',$count);
      	  return $this->fetch('product-list');

      }
      public function add(){
        $brand_id=input('brand_id\d');
        if(request()->isPost()){
          $data=[
           'brand_name'=>input('brand_name'),
           'brand_sort'=>input('brand_sort'),
           'brand_logo'=>input('brand_logo'),
           'brand_descibe'=>input('brand_descibe')
          ];
            $res=db('brand')->insert($data);
            if($res){
                $this->success('添加成功','lists',3);
            }else{
                $this->error('添加失败','add',5);
            }
        }  
      	  return $this->fetch('product-add');
      }
      public function del(){
      	  $brand_id = input('brand_id')+0;
      	  $res = db('brand')->delete($brand_id);
      	  if($res){
      	  	  //设计成功后跳转页面的地址,默认的返回页面是$_SERVER['HTTP_REFERER'];
      	  	  $this->success("删除成功",'lists',3);
      	  }else{
      	  	  $this->error("删除失败",'lists',5);
      	  }
      	  return $this->fetch();
      }
      public function edit(){
        $brand_id=input('brand_id\d');
        if(request()->isPost()){
          $data=input();
          $data['brand_name']=input('brand_name');
          $res = Db('brand')->update($data);
          if($res){
            $this->success('修改成功','lists',3);
          }else{
            $this->error('修改失败','edit',5);
          }
        }else{
          $data = Db('brand')->find($brand_id);
          $this->assign('data',$data);
          return $this->fetch('product-edit');
        }  	  
      }
  }


?>