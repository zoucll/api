<?php  
	namespace app\back\controller;
	use think\Controller;
	use think\Db;
	use think\Request;

	class Api extends Controller{
		public $sercet="12313213qweqewqewqew";
		//测试加入一条记录
		public function createSign(Request $request){
			// Db::query('insert into user (username,password) values(?,?)',['zhu',md5(123456)]);
			$params = request->param();
			//把传递的参数拼凑在一起
			$string = http_build_query($params);
			$sercet =this->sercet;

			$sign = md5($string.$sercet);
			echo $sign;exit;
		}
		//验证签名
		public function createSign(Request $request){
			$params = $request->param();

			if(！isset($params['sign'])||empty($params['sign'])){
				echo 'sign签名不能为空';exit;
			}
			$sign = $params['sign'];
			unset($params['sign']);

			$string = http_build_query($params);
			$sercet =this->sercet;

			$new_sign = md5($string.$sercet);//重新生成签名

			if($new_sign !==$sign){
				echo '签名不合法或者签名错误'；exit;
			}

			echo 'sign签名验证成功';
		}
		// 请求登录的接口换取token
		public function login(Request $request){
			$params = $request->param();//获取接口的请求的所有参数

			//用户名
			$username =$params['username'];
			$password =$params['password'];

			$user = Db::query('select * from user where username = ? and password = ?',[$username,md5($password)]);

			if(!empty($username)){
				$userId  = $user[0]['id'];
                 
				//更新在本地生成token值
				Db::query('update user set token = replace(uuid(),"-",""),expired_at = ? where id = ?',[time()+30,$userId]);
				$user = Db::query('select * from user where id=?',[$userId]);
			}
			$return = [
				'msg'=>'登录成功',
				'data'=>[
					'token'=>$user[0]['token']
				]
			];
			echo json_encode($return);exit;
		}
		//验证token的信息
		public function checktoken(Request $request){
			$params = $request->param();
			if(!isset($params['token'])|| empty($params['token'])){
				echo 'token不存在或者为空'
			}

			$token = $params['token'];
			$data = Db::query('select id,token,expired_at from user where token = ?',[token]);

			if(empty($data)){
				echo 'token值不合法';exit;
			}
			if($data[0]['expired_at']<time()){
				echo 'token已过期';exit;
			}
			echo 'token验证成功';
		}
		public function testApi(){

			$params = [
				'username'=>'zhu',
				'password'=>'123456'
			];
			$output = $this->httpcurl('http://www.tp5.com/back/api/login',true,$params);
			print_r($output);
		}
		//分装httpcurl函数
		//$url curl请求的地址
		//$isPost是否是post的请求
		//$data 如果post请求要传递的参数信息
		public function httpcurl($url,$isPost=false,$data){
			$curl = curl_init();
			//设置要请求的地址
			curl_setopt($curl,CURLOPT_URL,$url);
			if($isPost){
				//设置post的请求数据
				curl_setopt($curl,CURLOPT_POST, true);
				//传递post请求的数据
				
				curl_setopt($curl,CURLOPT_POSTFIELDS,$data);
			}
			return curl_exec($curl);
			
			return json_decode($output,true);
		}
		public function testpost(){
			$post = $_POST;
			return json($post);
		}
		//获取学生列表的接口
		public function getStudentsList(Request $request){
			//验证token值
			$params = $request->param();//接受所有的参数
			if(!isset($params['token'])||empty($params['token'])){
				$return = [
					'code'=>200,
					'msg'=>'成功'
				];
				return json($return);
			}
			$token = $params['token'];

			$data = Db::query('select id,token,expired_at,from user where token =?',[$token]);
			if(empty($data)){
				echo 'token值不合法';exit;
			}
			if($data[0]['expired_at']<time()){
				echo 'token已经过期';exit;
			}
		}
	}
 
?>