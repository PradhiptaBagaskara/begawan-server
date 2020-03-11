<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Saldo extends REST_Controller {
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
		$res = array("status" => false,
						"msg" => "Terjadi Kesalahan!",
							"result" => null);
		if (!empty($auth)) {
			$cek = $this->api->cek_field("id", $auth, "user");
			$res = array("status" => false,
						"msg" => "user tidak ditemukan",
							"result" => null);
			if ($cek > 0) {
				$saldo = $this->api2->get("user",['id' => $auth]);
				$res = array("status" => true,
							"msg" => "success",
								"result" => array('saldo' => $saldo->saldo));
			}
			
		}
		$this->response($res);
		
	}

	public function index_post()
	{
		$auth = $this->post('auth_key');
		$id = $this->post('id');
		$postSaldo = $this->post('saldo');
		$res = array("status" => false,
						"msg" => "Terjadi Kesalahan!",
							"result" => null);
		$param = $this->post('param');
		if (!empty($auth) && !empty($param)) {


			$cek = $this->api->cek_field("id", $id, "user");
			$res = array("status" => false,
						"msg" => "user tidak ditemukan",
							"result" => null);
			if ($cek > 0) {

				$saldoParr = $this->api2->get("user",['id' => $id])->saldo;


				if ($param == "tambah") {
						$saldo = $saldoParr;

					if ($postSaldo != "0" && $postSaldo != "") {
						$saldo = $saldoParr + $postSaldo;
						$pengirimDana = $saldoParr-$postSaldo;
							# code...
						$this->api2->insert("khas_history", ["id_user" => $id, "id_pemodal" => $auth,
										"saldo_awal" => $saldoParr, "saldo_masuk" => $postSaldo, "saldo_total" => $saldo,
										"keterangan" => "Menambahkan Saldo"]);
						$this->api2->update("user", ["saldo" => $pengirimDana], ["id"=>$auth]);						
						$rupiah = $this->api->rupiah($postSaldo);
						$this->db->select('device_token, nama');
						$this->db->where('id', $id);
						$dt = $this->db->get('user');
						$noti = $this->api->sendNotif($id,$dt->row("device_token"), "Hi ".$dt->row('nama') ,"Saldo Khas Telah Di Tambahkan Senilai ". $rupiah,'0');			

					}

					# code...
				} elseif ($param == "kurang") {
					$saldo = $saldoParr - $postSaldo;
					$saldo = $this->api2->update("user", ["saldo" => $saldo], ['id' => $id]);


					# code...
				}

				$res = array("status" => true,
							"msg" => "update saldo success",
								"result" => null);
			}
			
		}
		$this->response($res);
	}

}

/* End of file Saldo.php */
/* Location: ./application/controllers/api/Saldo.php */


 ?>