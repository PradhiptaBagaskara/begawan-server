<?php

defined('BASEPATH') or exit('No direct script access allowed');


use chriskacerguis\RestServer\RestController;

class Editpemilik extends RestController
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_userApi', 'userApi');
		$this->load->model('M_api2', 'api2');
		$this->load->model('M_api', 'api');

	}

	public function index_get()
	{
		$res = array(
			"status" => false,
			"msg" => "Terjadi Kesalahan!",
			"result" => null
		);

		$this->response($res);

	}

	public function index_post()
	{
		$auth = $this->post('auth_key');
		$username = $this->post('username');
		$pass = $this->post('password');
		$nama = $this->post('nama');

		$res = array(
			"status" => false,
			"msg" => "Terjadi Kesalahan!",
			"result" => null
		);
		if (!empty($auth)) {


			$cek = $this->api->cek_field("id", $auth, "user");
			$res = array(
				"status" => false,
				"msg" => "user tidak ditemukan",
				"result" => null
			);

			if ($cek > 0) {
				$cek_role = $this->api->cek_role($auth);
				$res = array(
					"status" => false,
					"msg" => "user tidak diijinkan",
					"result" => null
				);
				if ($cek_role == 2) {
					$res = array(
						"status" => false,
						"msg" => "wrong method!",
						"result" => array()
					);
					if ($pass != "") {
						$password = $this->api->password($pass);
						$this->api2->update("user", ["username" => $username, "password" => $password, "nama" => $nama], ["id" => $auth]);
					} else {
						$password = "no password";
						$this->api2->update("user", ["username" => $username, "nama" => $nama], ["id" => $auth]);

					}

					$this->db->select('nama,username,ifnull(device_token,"abcd") as device_token, created_date,role,id,foto,is_active,nomer,is_active');

					$this->db->where('id', $auth);
					$result = $this->db->get("user")->result();
					$res = array_shift($result);
					$res = array(
						"status" => true,
						"msg" => "Profil Berhasil di Update",
						"result" => $res
					);
					// }



				}
			}


		}
		$this->response($res);
	}

}

/* End of file Saldo.php */
/* Location: ./application/controllers/api/Saldo.php */


?>