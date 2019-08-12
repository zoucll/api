<?php  
   namespace app\back\controller;
   // use think\Controller;


   class Goods extends Base{
   	 public function lists(){
         //使用第三方分页
         $total = Db("tp506")->count();//总条数
         $listRows = 1;//每页的记录数
         $page_model = new \Page($total,$listRows);
         $page = $page_model->fpage(array(0,1,2,3,4,5,6,7,8));
         $list = Db("tp506")->paginate(1);
         $count = Db('tp506')->count();
         //把分页数据赋值给模板变量list
         $this->assign('list',$list);//$this->获取当前值
         $this->assign('page',$page);
         $this->assign('count',$count);
   	 	return $this->fetch('product-list');
   	 }
   	 public function add(){
         //手机新增数据和获取展示模板
         //dump(request()->isPost());
         if(request()->isPost()){
            $data = [
                 'goods_name'=>input('goods_name'),
                 'shop_price'=>input('shop_price'),
                 'market_price'=>input('market_price'),
                 'promote_price'=>input('promote_price'),
                 'pro_static_time'=>input('pro_static_time'),
                 'pro_end_time'=>input('pro_end_time'),
                 'keywords'=>input('keywords'),
                 'description'=>input('description'),
                 'goods_intro'=>input('editorValue')
             ];
             $file = input('file.goods_small_logo');
             // print_r($file);die;
             $info = $file->validate(['size'=>2*1024*1024,'ext'=>'jpg,png,gif'])->move(ROOT_PATH .'public'.'DS'.'uploads');
             if($info){
                //成功上传后 获取上传信息
                $data['goods_small_logo']=DS.'uploads'.DS.$info->getSaveName();
                // var_dump($data);die;
                $res=db('tp506')->insert($data);
                 if($res){
                     $this->success("添加成功",'lists',3);
                 }else{
                     $this->error("添加失败",'add',5);
                 }
             }else{
                //上传文件失败获取错误信息
                $this->error($file->getError(),'add',5);
             }

         }else{
            $cal = db('classify')->select();
            $info=$this->make_tree($cal);//生成一个树形数组
            //递归调用
            $str=$this->show_tree($info);
            $this->assign('info',$str);
            return $this->fetch('product-add');
         }
         // return $this->fetch('product-add');
   	 }
     //无限极分类,生成树形数组
     public function make_tree($list,$root = 0){
        $tree = array();
        $packData = array();
        ///将所有的分类id作为数组额key
        foreach ($list as $k => $v) {
            $packData[$V['id']]=$v;
        }
        //利用引用,将每个分类添加到父类child数组中
        foreach ($packData as $k => $v) {
            if($v['pid']==$root){
                $tree[] = &$packData[$k];
            }else{
                //找到父类
                $packData[$v['pid']]['child'][]=&$packData[$k];
            }
        }
        return $tree;
     }
     //递归调用
     public function show_tree($data,$flag=''){
        static $str = '';//静态变量,只有第一次才被初始化
        foreach ($data as $k => $v) {
            if(empty($v['child'])){
                $str.="<option value='".$v['id']."'>$flag".$v['title']."</option>";
            }else{
                $str.="<option value='".$v['id']."'>$flag".$v['title']."</option>";
                $this->show_tree($v['child'],$flag.'|-');
            }
        }
        return $str;
     }

   	 public function del(){
         $goods_id = input('goods_id')+0;
         $res=db("tp506")->delete($goods_id);
         if($res){
            //设置成功后跳转页面的地址,默认的返回页面是$_SERVER['HTTP_REFERER']
            $this->success('删除成功','lists',3);
         }else{
            //错误页面的默认跳转页面是返回前一页,通常不需要设置
            $this->error('删除失败','lists',5);
         }
   	 }
     public function edit(){
        $goods_id=input('goods_id/d');
        if(request()->isPost()){
            $data = input();
            $data['goods_intro']=input('editorValue');
            unset($data['editorValue']);
            unset($data['file']);
            $res=Db('tp506')->update($data);
            if($res){
                $this->success('修改成功','lists',3);
            }else{
                $this->error('修改失败',url('edit','goods_id='.$goods_id),5);
            }
        }else{
            $data=Db('tp506')->find($goods_id);
            $this->assign('data',$data);
            return $this->fetch('product-edit');
        }
        
     }
}

?>