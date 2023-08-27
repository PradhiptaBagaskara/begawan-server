<?php

defined('BASEPATH') or exit('No direct script access allowed');


use chriskacerguis\RestServer\RestController;

class Getpdf extends RestController
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_userApi', 'userApi');
		$this->load->model('M_api', 'api');
		$this->load->model('M_api2', 'api2');
		$this->load->model('M_pdf', 'pdf');

	}

	public function index_get()
	{
		$auth = $this->get('auth_key');
		$limit = $this->get('limit');
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
					$data = $this->userApi->get();
					$res = array(
						"status" => true,
						"msg" => "success",
						"result" => $data
					);
				} elseif ($cek_level == 1) {
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
		$auth = $this->post('auth_key');
		$id = $this->post('id');
		$param = $this->post('param');



		$res = array(
			"status" => false,
			"msg" => "Terjadi Kesalahan!",
			"result" => null
		);
		if (!empty($auth) && !empty($param)) {


			$cek = $this->api->cek_role($auth);
			$res = array(
				"status" => false,
				"msg" => "user tidak diizinkan",
				"result" => null
			);
			if ($cek > 1) {
				$ids = null;
				$fname = null;
				$res = array(
					"status" => false,
					"msg" => "permintaan laporan tidak ditemukan",
					"result" => null
				);
				$del = $this->api2->getAll("pdf");
				if ($del !== NULL) {
					# code...
					foreach ($del as $key) {
						# code...
						@unlink($key->file_name);
						$this->api2->delete("pdf", ["id" => $key->id]);
					}
				}

				if ($param == "proyek") {
					$cek_lap = $this->api->cek_field('id', $id, 'proyek');
					if ($cek_lap > 0) {
						$fname = $this->pdf->laporan_proyek($id);
						# code...
					}

				} elseif ($param == "user") {
					$cek_lap = $this->api->cek_field('id', $id, 'user');
					if ($cek_lap > 0) {
						$fname = $this->pdf->laporan_user($id);
					}

				}
				if ($fname != null) {
					# code...
					$name = $fname;
					$fileloc = "uploads/laporan/" . $fname;

					$ids = $this->api2->insert("pdf", ["id_user" => $auth, "file_name" => $fileloc, "nama_laporan" => $name]);
					$get = $this->api2->getAll("pdf", ["id" => $ids]);


					$res = array(
						"status" => true,
						"msg" => "Laporan telah dibuat",
						"result" => $get
					);
				}

			}

		}
		$this->response($res);


	}

	function index_put()
	{
		$auth = $this->post('auth_key');
		$id = $this->post('id');



		$res = array(
			"status" => false,
			"msg" => "Terjadi Kesalahan!",
			"result" => null
		);
		if (!empty($auth)) {


			$cek = $this->api->cek_role($auth);
			$res = array(
				"status" => false,
				"msg" => "user tidak ditemukan",
				"result" => null
			);
			if ($cek > 0) {

				$get = $this->api2->get("pdf", ["id" => $id]);
				@unlink("uploads/" . $get->file_name);
				$this->api2->delete("pdf", ["id" => $id]);


				$res = array(
					"status" => true,
					"msg" => "Laporan telah dihapus",
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