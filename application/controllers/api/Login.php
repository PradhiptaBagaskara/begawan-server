<?php
defined('BASEPATH') or exit('No direct script access allowed');


use chriskacerguis\RestServer\RestController;

class Login extends RestController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_api', 'api');
		$this->load->model('M_userApi', 'userApi');
	}


	function index_get()
	{
		$this->response(array('msg' => false, 'result' => null));

	}

	public function index_post()
	{
		$username = $this->post('username');
		$password = $this->post('password');
		$device = $this->post('device');


		$uname = $this->api->cek_field('username', $username, 'user');
		// $pass = $this->api->cek_pass('username', $username, $password);


		if ($uname == "1") {

			$var = $this->api->cek_pass('username', $username, $password);

			if ($var) {
				$this->db->where('is_active', '1');
				$this->db->where('username', $username);
				$result = $this->db->get("user")->result();
				$res = array_shift($result);
				$out = array(
					'status' => true,
					'msg' => 'success',
					'result' => $res
				);
				$this->response($out);
			} else {
				$this->response(
					array(
						"status" => false,
						"msg" => "Password Salah!",
						"result" => null
					)
				);
			}

		} else {
			$this->response(
				array(
					"status" => false,
					"msg" => "username tidak ditemukan!",
					"result" => null
				)
			);
		}


	}



}