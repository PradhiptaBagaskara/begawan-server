<?php

defined('BASEPATH') or exit('No direct script access allowed');


use chriskacerguis\RestServer\RestController;

class Transaksi extends RestController
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
				$cek_role = $this->api->cek_role($auth);
				if ($cek_role > 1) {
					$saldo = $this->api2->getTx(null, 5);
					# code...
				} else {
					$saldo = $this->api2->getTx(['id_user' => $auth], 100);

				}
				$res = array(
					"status" => true,
					"msg" => "success",
					"result" => $saldo
				);
			}


		}
		$this->response($res);

	}

	public function index_post()
	{
		$auth = $this->post('auth_key');
		$dana = $this->post('dana');
		$nama = $this->post('nama');
		$keterangan = $this->post('keterangan');
		$jenis = $this->post('jenis');
		$id_proyek = $this->post('id_proyek');
		$file_form = "file";


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
				$fname = $this->api2->upload_file($file_form, "transaksi");
				if ($fname != null) {
					$saldoParr = $this->userApi->get(['id' => $auth]);
					$sal = array_shift($saldoParr);
					if ($jenis == "khas") {

						$saldo = $sal->saldo - $dana;
						$this->api2->update("user", ["saldo" => $saldo], ["id" => $auth]);
					}
					if ($keterangan == "") {
						$keterangan = "Tidak Ada Catatan";
					}


					$data = array(
						"id_user" => $auth,
						"id_proyek" => $id_proyek,
						"jenis" => $jenis,
						"keterangan" => $keterangan,
						"nama_transaksi" => $nama,
						"file_name" => $fname,
						"current_saldo" => $sal->saldo,
						"dana" => $dana
					);
					if ($jenis == "utang") {
						$data['status'] = "belum lunas";
					}
					try {
						$this->api->sendNotif($auth, "/topics/transaksi", $sal->nama, "Melakukan Transaksi Baru dengan dana {$dana}", "0");

					} catch (Exception $e) {

					}


					$this->api2->insert("transaksi", $data);

					$res = array(
						"status" => true,
						"msg" => "Transaksi Baru Telah Di Tambahkan",
						"result" => null
					);
				}
			}


		}
		$this->response($res);
	}

}

/* End of file Saldo.php */
/* Location: ./application/controllers/api/Saldo.php */


?>