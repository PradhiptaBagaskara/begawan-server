<?php

defined('BASEPATH') or exit('No direct script access allowed');


use chriskacerguis\RestServer\RestController;

class Proyek extends RestController
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
				$role = $this->api->cek_role($auth);

				if ($role < 2) {
					$this->db->select('nama_proyek,id');
					$this->db->from('proyek');
					$data = $this->db->get()->result();
					$res = array(
						"status" => true,
						"msg" => "list proyek",
						"result" => $data
					);
				} else {


					$id = $this->get("id");


					if (!empty($id)) {
						$this->db->query("SET lc_time_names = 'id_ID'");

						$this->db->select('ifnull(sum(transaksi.dana), "0") as total_dana,ifnull(proyek.tgl_mulai, "belum di atur") as tgl_mulai, ifnull(proyek.tgl_selesai, "Belum di atur") as tgl_selesai, proyek.modal - ifnull(sum(transaksi.dana),"0") as sisa_modal ,proyek.id,proyek.nama_proyek,ifnull(proyek.keterangan, "Tidak Ada Catatan") as keterangan,proyek.modal, DATE_FORMAT(proyek.created_date, "%d %M %Y") as created_date');
						$this->db->from('proyek');
						$this->db->join('transaksi', 'transaksi.id_proyek = proyek.id', 'left');
						$this->db->where('proyek.id', $id);
						// $this->db->where('user.is_active', 1);
						$this->db->order_by('created_date', 'desc');
						$proyek = $this->db->get()->result();

						$tx = $this->api2->getTx(['id_proyek' => $id], 100);

					} else {
						$this->db->query("SET lc_time_names = 'id_ID'");

						$this->db->select('nama_proyek, id, DATE_FORMAT(proyek.created_date, "%d %M %Y") as created_date');

						$proyek = $this->db->get('proyek')->result();


						$tx = null;


					}

					$res = array(
						"status" => true,
						"msg" => "success",
						"transaksi" => $tx,
						"result" => $proyek
					);

				}

			}

		}
		$this->response($res);

	}

	public function index_post()
	{
		$auth = $this->post('auth_key');
		$nama_proyek = $this->post('nama_proyek');
		$modal = $this->post('modal');
		$par = $this->post('param');
		$ket = $this->post('keterangan');
		$id = $this->post("id");
		$aksi = $this->post('aksi');
		$tglMulai = $this->post('mulai');
		$tglEnd = $this->post('selesai');


		$res = array(
			"status" => false,
			"msg" => "Terjadi Kesalahan!",
			"result" => null
		);
		if (!empty($auth) && !empty($par)) {


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
				if ($cek_role >= 2) {
					$res = array(
						"status" => false,
						"msg" => "wrong method!",
						"result" => array()
					);
					if (empty($ket)) {
						$keterangan = "Tidak ada diskripsi";
					} else {
						$keterangan = $ket;
					}
					$pengirim = $this->api2->get("user", ['id' => $auth])->saldo;


					if ($par == "insert") {
						$data = array(
							"nama_proyek" => $nama_proyek,
							"modal" => $modal,
							"modal_awal" => $modal,
							"tgl_mulai" => $tglMulai,
							"tgl_selesai" => $tglEnd,
							"keterangan" => $keterangan
						);

						$insert = $this->api2->insert("proyek", $data);
						if ($insert) {
							$this->db->where('id', $insert);
							$ress = $this->db->get('proyek')->result();
							$res = array(
								"status" => true,
								"msg" => "proyek telah ditambahkan",
								"result" => array_shift($ress)
							);

							$saldoParr = $this->userApi->get(['id' => $auth]);
							$sal = array_shift($saldoParr);
							$saldo = $sal->saldo - $modal;
							$this->api2->update("user", ["saldo" => $saldo], ["id" => $auth]);

							$this->api2->insert("khas_proyek", [
								"id_proyek" => $insert,
								"id_pemodal" => $auth,
								"saldo_awal" => "0",
								"saldo_masuk" => $modal,
								"saldo_akhir" => $modal,
								"keterangan" => $keterangan
							]);


							$this->api2->insert("khas_history", [
								"id_user" => $auth,
								"id_pemodal" => $auth,
								"saldo_awal" => $sal->saldo,
								"saldo_masuk" => $modal,
								"saldo_total" => $saldo,
								"id_proyek" => $insert,
								"jenis" => "pekerjaan",
								"keterangan" => $keterangan
							]);
						}


					} elseif ($par == "update") {
						$data = array(
							"nama_proyek" => $nama_proyek,
							"tgl_mulai" => $tglMulai,
							"tgl_selesai" => $tglEnd,
							"keterangan" => $keterangan
						);

						$insert = $this->api2->update("proyek", $data, ["id" => $id]);
						$res = array(
							"status" => true,
							"msg" => "Data Berhasil di update",
							"result" => null
						);
					} elseif ($par == "nilai") {
						// $id = $this->post("id");
						$pekerjaan = $this->api2->get("proyek", ["id" => $id]);
						$modalAkhir = $pekerjaan->modal;
						$saldoParr = $this->userApi->get(['id' => $auth]);
						$sal = array_shift($saldoParr);

						$res = array(
							"status" => true,
							"msg" => "proyek update success",
							"result" => array()
						);
						if ($aksi == "tambah") {
							$modalAkhir = $pekerjaan->modal + $modal;
							$saldo = $sal->saldo - $modal;

							$kkt = "Menambahkan Nilai Pekerjaan";
						} elseif ($aksi == "kurang") {
							$modalAkhir = $pekerjaan->modal - $modal;
							$saldo = $sal->saldo + $modal;

							$kkt = "Mengurangi Nilai Pekerjaan";

						}

						$this->api2->update("proyek", ["modal" => $modalAkhir], ["id" => $id]);



						$this->api2->update("user", ["saldo" => $saldo], ["id" => $auth]);

						$this->api2->insert("khas_proyek", [
							"id_proyek" => $id,
							"id_pemodal" => $auth,
							"saldo_awal" => $pekerjaan->modal,
							"saldo_masuk" => $modal,
							"saldo_akhir" => $modalAkhir,
							"keterangan" => $keterangan
						]);


						$this->api2->insert("khas_history", [
							"id_user" => $auth,
							"id_pemodal" => $auth,
							"id_proyek" => $id,
							"saldo_awal" => $sal->saldo,
							"saldo_masuk" => $modal,
							"saldo_total" => $saldo,
							"jenis" => "pekerjaan",
							"keterangan" => $kkt
						]);
						$res = array(
							"status" => true,
							"msg" => "Nilai Berhasil di update",
							"result" => array()
						);
					} elseif ($par == "delete") {
						$this->db->select('file_name');
						$this->db->where('id_proyek', $id);
						$data = $this->db->get('transaksi')->result();
						foreach ($data as $key) {
							# code...
							@unlink("./uploads/" . $key->file_name);
						}
						$this->db->select('file_name');
						$this->db->where('id_proyek', $id);
						$da = $this->db->get('gaji')->result();
						foreach ($da as $k) {
							# code...
							@unlink("./uploads/" . $k->file_name);
						}

						$this->api2->delete("gaji", ["id_proyek" => $id]);

						$this->api2->delete("proyek", ["id" => $id]);
						$this->api2->delete("transaksi", ["id_proyek" => $id]);
						$this->api2->delete("khas_proyek", ["id_proyek" => $id]);
						$this->api2->delete("khas_history", ["id_proyek" => $id]);
						$res = array(
							"status" => true,
							"msg" => "Data Berhasil Dihapus",
							"result" => array()
						);
					}



				}
			}


		}
		$this->response($res);
	}

	public function index_put()
	{
		$id = $this->put("id");
		$auth = $this->put("auth_key");

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
					$this->api2->delete("proyek", ["id" => $id]);
					$this->api2->delete("transaksi", ["id_proyek" => $id]);
					$res = array(
						"status" => true,
						"msg" => "Data Berhasil Dihapus",
						"result" => null
					);
				}
			}
		}
		$this->response($res);

		# code...
	}

}

/* End of file Saldo.php */
/* Location: ./application/controllers/api/Saldo.php */


?>