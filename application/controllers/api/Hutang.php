<?php

defined('BASEPATH') or exit('No direct script access allowed');


use chriskacerguis\RestServer\RestController;

class Hutang extends RestController
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_userApi', 'userApi');
		$this->load->model('M_api', 'api');
		$this->load->model('M_api2', 'api2');

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
		$id = $this->post('id');
		$postSaldo = $this->post('jumlah');
		$param = $this->post('param');


		$res = array(
			"status" => false,
			"msg" => "Terjadi Kesalahan!",
			"result" => null
		);
		$param = $this->post('param');
		if (!empty($auth) && !empty($param)) {


			$cek = $this->api->cek_field("id", $auth, "user");
			$res = array(
				"status" => false,
				"msg" => "user tidak ditemukan",
				"result" => null
			);
			if ($cek > 0) {
				$res = array(
					"status" => true,
					"msg" => "Hutang Terlunasi",
					"result" => null
				);
				if ($param == "single") {
					$pelunas = $this->api2->get("user", ["id" => $auth]);
					$curent = $pelunas->saldo - $postSaldo;
					$this->api2->update("user", ["saldo" => $curent], ["id" => $auth]);
					$this->api2->update("transaksi", ["status" => "lunas"], ["id" => $id]);

					// $this->api2->update("transaksi", ["status" => "lunas"], ["id", "$id"] );
					$proyek_id = $this->api2->get("transaksi", ["id" => $id]);
					$this->api2->insert("khas_history", [
						"id_user" => $proyek_id->id_user,
						"id_pemodal" => $auth,
						"saldo_awal" => $pelunas->saldo,
						"saldo_masuk" => $postSaldo,
						"saldo_total" => $curent,
						"id_proyek" => $proyek_id->id_proyek,
						"id_transaksi" => $id,
						"jenis" => "piutang",
						"admin" => "1",
						"keterangan" => "Pelunasan Piutang"
					]);

					# code...
				} elseif ($param == "all") {
					// $pelunas = $this->api2->get("user",["id"=>$auth]);

					$tx = $this->api2->getAll("transaksi", ["id_user" => $id, "status" => "belum lunas"]);
					foreach ($tx as $val => $key) {
						$pelunas = $this->api2->get("user", ["id" => $auth]);

						$curent = $pelunas->saldo - $key->dana;
						$this->api2->update("user", ["saldo" => $curent], ["id" => $auth]);

						$this->api2->update("transaksi", ["status" => "lunas"], ["id" => $key->id]);

						$this->api2->insert("khas_history", [
							"id_user" => $key->id_user,
							"id_pemodal" => $auth,
							"saldo_awal" => $pelunas->saldo,
							"saldo_masuk" => $key->dana,
							"saldo_total" => $curent,
							"id_proyek" => $key->id_proyek,
							"jenis" => "piutang",
							"id_transaksi" => $key->id,

							"admin" => "1",
							"keterangan" => "Pelunasan Piutang"
						]);
					}
					$res = array(
						"status" => true,
						"msg" => "Hutang Terlunasi",
						"result" => null
					);


					# code...
				} else {
					$res = array(
						"status" => false,
						"msg" => "Terjadi Kesalahan!",
						"result" => null
					);
				}

			}
		}
		$this->response($res);
		# code...
	}
}