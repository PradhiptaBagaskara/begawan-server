<?php
defined('BASEPATH') or exit('No direct script access allowed');


use chriskacerguis\RestServer\RestController;

class User extends RestController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_userApi', 'userApi');
		$this->load->model('M_api', 'api');
		$this->load->model('M_api2', 'api2');

	}


	function index_get()
	{
		$auth = $this->get('auth_key');
		$res = array(
			"status" => false,
			"msg" => "Terjadi Kesalahan!",
			"result" => null
		);
		if (!empty($auth)) {
			$res = array(
				"status" => false,
				"msg" => "Tidak ditemukan",
				"result" => null
			);
			$cek = $this->api->cek_field("id", $auth, "user");
			if ($cek > 0) {
				$res = array(
					"status" => false,
					"msg" => "Tidak diijinkan",
					"result" => null
				);
				$cek_level = $this->api->cek_role($auth);
				if ($cek_level >= 2) {
					if ($cek_level == 2) {
						# code...
						$data = $this->api2->getAll("user", ["is_admin =" => 0]);

					} else {
						$data = $this->api2->getAll("user", ["role <" => 2]);
						# code...
					}
					$res = array(
						"status" => true,
						"msg" => "success",
						"result" => $data
					);
				} elseif ($cek_level <= 1) {
					$this->db->where('role', 0);
					$data = $this->db->get('user')->result();
					$res = array(
						"status" => true,
						"msg" => "success pemodal",
						"result" => $data
					);
				}
			}


		}
		$this->response($res);

	}
	public function index_post()
	{

		$response = array(
			'status' => false,
			'msg' => 'authenticate required!',
			'result' => null
		);
		$auth = $this->post('auth_key');
		if (!empty($auth)) {
			$role = $this->api->cek_role($auth);

			if ($role >= 2) {
				$uname = $this->api->get_username($this->post('nama'));
				$arrayName = array(
					'nama' => $this->post('nama'),
					'saldo' => $this->post('saldo'),
					'username' => $uname,
					'password' => $this->api->password('123456'),
					'foto' => 'thumbnail.png',
					'role' => $this->post('role')

				);
				$uid = $this->userApi->insert($arrayName);
				$this->db->where('username', $uname);
				$result = $this->db->get("user")->result();
				$res = array_shift($result);
				$this->db->insert("khas_history", [
					"id_user" => $res->id,
					"id_pemodal" => $auth,
					"saldo_awal" => "0",
					"saldo_masuk" => $res->saldo,
					"saldo_total" => $res->saldo,
					"keterangan" => "Menambahkan Saldo"
				]);

				$pengirim = $this->api2->get("user", ['id' => $auth])->saldo;
				$pengirimDana = $pengirim - $res->saldo;
				$this->api2->update("user", ["saldo" => $pengirimDana], ["id" => $auth]);



				$response = array(
					'status' => true,
					'msg' => 'sukses',
					'result' => $res
				);
			}

		}

		$this->response($response);

		# code...
	}

	public function index_put()
	{
		$response = array(
			'status' => false,
			'msg' => 'authenticate required!',
			'result' => null
		);
		$auth = $this->post('auth_key');
		$nama = $this->post('nama');
		$password = $this->post('password');
		if (!empty($auth)) {
			if ($password != "") {
				$pass = $this->api->password($password);
				$this->api2->update("user", ["nama" => $nama, "password" => $pass], ["id" => $auth]);
				$su = "Nama dan Password Telah Terganti";

			} else {
				$this->api2->update("user", ["nama" => $nama], ["id" => $auth]);
				$su = "Nama Telah Terganti";
			}

			$this->db->select('*');
			$this->db->from('user');
			$this->db->where('id', $auth);
			$result = $this->db->get()->result();
			$res = array_shift($result);


			$response = array(
				'status' => true,
				'msg' => $su,
				'result' => $res
			);
			# code...
		}

		$this->response($response);

	}

}