<?php
defined('BASEPATH') or exit('No direct script access allowed');


use chriskacerguis\RestServer\RestController;

class Register extends RestController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_api', 'api');
	}


	function index_get()
	{
		$this->response(array('msg' => false, 'result' => null));

	}

	public function index_post()
	{
		$email = $this->post('email');
		$username = $this->post('username');
		$password = $this->post('password');


		$uname = $this->api->cek_field('username', $username, 'user');
		$mail = 0;


		if ($uname == 0 && $mail == 0) {
			$res = $this->api->register($email, $username, $password);
			if ($res) {
				$this->response(
					array(
						"status" => true,
						"msg" => "created",
						"result" => null
					)
				);
			} else {
				$this->response(
					array(
						"status" => false,
						"msg" => "not created",
						"result" => null
					)
				);
			}
		} else {
			$this->response(
				array(
					"status" => false,
					"msg" => "username or email already exists!",
					"result" => null
				)
			);
		}


	}



}