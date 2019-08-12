<?php  
  namespace app\home\controller;
  use think\Controller;

  class Goods extends Controller{
  	public function lists(){
  		return $this->fetch('list');
  	}

  	public function datail(){
  		return $this->fetch();
  	}
  }

?>