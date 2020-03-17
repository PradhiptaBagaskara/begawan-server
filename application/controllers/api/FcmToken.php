<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Fcmtoken extends REST_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_userApi', 'userApi');
		$this->load->model('M_api2', 'api2');
		$this->load->model('M_api', 'api');

	}

	public function index_get()
	{
		$res = array("status" => false,
						"msg" => "Terjadi Kesalahan!",
							"result" => null);	
			
		
		$this->response($res);
		
	}

	public function index_post()
	{
		$auth = $this->post('auth_key');
		$token = $this->post('token');
	
		$res = array("status" => false,
						"msg" => "Terjadi Kesalahan!",
							"result" => null);
		if (!empty($auth)) {


			$cek = $this->api->cek_field("id", $auth, "user");
			$res = array("status" => false,
						"msg" => "user tidak ditemukan",
							"result" => null);

			if ($cek > 0) {
				$this->api2->update("user", ["device_token" => $token], ["id"=>$auth]);
				$res = array("status" => true,
						"msg" => "token update",
							"result" => null);
			}

			
		}
		$this->response($res);
	}

}

/* End of file Saldo.php */
/* Location: ./application/controllers/api/Saldo.php */


 ?>