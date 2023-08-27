<?php

defined('BASEPATH') or exit('No direct script access allowed');


use chriskacerguis\RestServer\RestController;

class Saldo extends RestController
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
		$auth = $this->get('auth_key');
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
				$saldo = $this->api2->get("user", ['id' => $auth]);
				$this->db->select('sum(dana) as dana');
				$this->db->where('jenis', "utang");
				$this->db->where('id_user', $auth);
				$this->db->where('status', "belum lunas");
				$utang = $this->db->get("transaksi")->row("dana");
				if ($utang === NULL) {
					$utang = 0;
				}
				$res = array(
					"status" => true,
					"msg" => "success",
					"result" => array('saldo' => $saldo->saldo, "total_piutang" => $utang)
				);
			}

		}
		$this->response($res);

	}

	public function index_post()
	{
		$auth = $this->post('auth_key');
		$id = $this->post('id');
		$postSaldo = $this->post('saldo');
		$keterangan = $this->post('keterangan');


		$res = array(
			"status" => false,
			"msg" => "Terjadi Kesalahan!",
			"result" => null
		);
		$param = $this->post('param');
		if (!empty($auth) && !empty($param)) {


			$cek = $this->api->cek_field("id", $id, "user");
			$res = array(
				"status" => false,
				"msg" => "user tidak ditemukan",
				"result" => null
			);
			if ($cek > 0) {

				$saldoParr = $this->api2->get("user", ['id' => $id])->saldo;
				$sender = $this->api2->get("user", ['id' => $auth])->saldo;


				if ($param == "tambah") {
					$saldo = $saldoParr;

					if ($postSaldo != "0" && $postSaldo != "") {
						$saldo = $saldoParr + $postSaldo;
						$pengirimDana = $sender - $postSaldo;
						if (empty($keterangan) || $keterangan == "") {
							$keterangan = "Menambahkan Saldo";
						}
						$file = $this->api2->upload_file("file", "transaksi");
						# code...
						$this->api2->insert("khas_history", [
							"id_user" => $id,
							"id_pemodal" => $auth,
							"saldo_awal" => $saldoParr,
							"saldo_masuk" => $postSaldo,
							"saldo_total" => $saldo,
							"keterangan" => $keterangan,
							"file_name" => $file
						]);

						if ($id != $auth) {
							$this->api2->update("user", ["saldo" => $pengirimDana], ["id" => $auth]);
							$rupiah = $this->api->rupiah($postSaldo);
							$this->db->select('device_token, nama');
							$this->db->where('id', $id);
							$dt = $this->db->get('user');
							if ($dt->row("device_token") !== NULL) {
								try {
									$noti = $this->api->sendNotif($id, $dt->row("device_token"), "Hi " . $dt->row('nama'), "Saldo Khas Telah Di Tambahkan Senilai " . $rupiah, '0');

								} catch (Exception $e) {

								}
							}

						}
						$this->api2->update("user", ["saldo" => $saldo], ["id" => $id]);




					}

					# code...
				} elseif ($param == "kurang") {
					if ($postSaldo != "") {
						# code...
						$saldo = $saldoParr - $postSaldo;
						$pengirimDana = $sender + $postSaldo;
						if ($id != $auth) {
							$this->api2->update("user", ["saldo" => $pengirimDana], ["id" => $auth]);

						}

						$this->api2->update("user", ["saldo" => $saldo], ['id' => $id]);
						if ($keterangan == "") {
							$keterangan = "Mengurangi Saldo";
						}

						$this->api2->insert("khas_history", [
							"id_user" => $id,
							"id_pemodal" => $auth,
							"saldo_awal" => $saldoParr,
							"saldo_masuk" => $postSaldo,
							"saldo_total" => $saldo,
							"keterangan" => $keterangan
						]);
					}


					# code...
				}

				$res = array(
					"status" => true,
					"msg" => "Update Saldo Berhasil",
					"result" => null
				);
			}

		}
		$this->response($res);
	}

}

/* End of file Saldo.php */
/* Location: ./application/controllers/api/Saldo.php */


?>