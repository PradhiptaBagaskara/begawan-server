<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Gaji extends REST_Controller {
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
		// $id = $this->get('id');
		$param = $this->get('param');
		$limit = $this->get('limit');
		// $res = array("status" => false,
		// 				"msg" => "Terjadi Kesalahan!",
		// 					"result" => null);
		if (!empty($auth)) {
			$cek = $this->api->cek_field("id", $auth, "user");
			$res = array("status" => false,
						"msg" => "user tidak ditemukan",
							"result" => "lolos");
			if ($cek > 0) {
				$role = $this->api->cek_role($auth);
				if ($role == 2) {
					$this->db->query("SET lc_time_names = 'id_ID'");
					$this->db->select('gaji.id, gaji.gaji,gaji.keterangan, date_format(gaji.created_date, "%d %M %Y") as created_date, user.username, user.nama,
					gaji.file_name, proyek.nama_proyek, if(gaji.id_pemilik = user.id, "",(select nama from user where id="'.$auth.'")) as nama_pengirim');
					$this->db->from('gaji');
					$this->db->join('proyek', 'gaji.id_proyek = proyek.id', 'left');
					$this->db->join('user', 'gaji.id_user = user.id', 'left');
					$this->db->order_by('created_date', 'desc');
					if (!empty($limit)) {
						$this->db->limit($limit);
					}
					$gaji = $this->db->get()->result();	
					$res = array("status" => true,
							"msg" => "success",
								"result" => $gaji);		
							
				}else{
					if ($param == "status") {
						$bulan = date("Y-m");
						setlocale (LC_TIME, 'id_ID');
						$date = strftime( "%B", time());
						$gaji = $this->api2->get("gaji", ["bulan"=>$bulan, "id_user"=>$auth]);
						$gajim  = array("is_gaji"=> true, "msg" => "Gaji Bulan ".$date." Sudah Diterima");
					if (!$gaji) {
						$gajim = array("is_gaji"=> false, "msg" => "Gaji Bulan ".$date." Belum Diterima ");;
					}
					$res = array("status" => true,
							"msg" => "success",
								"result" => $gajim);	
						# code...
					}elseif ($param == "all") {
						# code...
					$this->db->select('id_pemilik');
					$this->db->where('id_user', $auth);
					$p =$this->db->get("gaji")->row('id_pemilik');
					$this->db->query("SET lc_time_names = 'id_ID'");						
					
					$this->db->select('gaji.id,gaji.keterangan, gaji.gaji, date_format(gaji.created_date, "%d %M %Y") as created_date, user.username, user.nama,
					gaji.file_name, proyek.nama_proyek, if(gaji.id_pemilik = user.id, "",(select nama from user as userw where id="'.$p.'")) as nama_pengirim');
					$this->db->from('gaji');
					$this->db->join('proyek', 'gaji.id_proyek = proyek.id', 'left');
					$this->db->join('user', 'gaji.id_user = user.id', 'left');
					$this->db->where('gaji.id_user', $auth);
					if (!empty($limit)) {
						$this->db->limit($limit);
					}
					$this->db->order_by('created_date', 'desc');
					$gaji = $this->db->get()->result();	
					$gajim  = $gaji;
					if (!$gaji) {
						$gajim = null;
					}
					$res = array("status" => true,
							"msg" => "success",
								"result" => $gajim);	
					}else{
						$res = array("status" => false,
						"msg" => "Terjadi Kesalahan Parameter!",
							"result" => null);
					}

				}
				
			}
			
		}
		$this->response($res);
		
	}

	public function index_post()
	{
		$auth = $this->post('auth_key');
		$id = $this->post('id');
		$id_proyek = $this->post('id_proyek');
		$gaji = $this->post('gaji');
		$keterangan = $this->post('keterangan');

		$res = array("status" => false,
						"msg" => "Terjadi Kesalahan!",
							"result" => null);
		if (!empty($auth)) {


			$cek = $this->api->cek_field("id", $id, "user");
			$res = array("status" => false,
						"msg" => "user tidak ditemukan id: {$cek}-.$id",
							"result" => null);
			if ($cek > 0) {
				$role = $this->api->cek_role($auth);
				$res = array("status" => false,
						"msg" => "user tidak diizinkan",
							"result" => null);
				if ($role > 1) {
					$res = array("status" => false,
						"msg" => "file diperlukan",
							"result" => null);
					$fname = $this->api2->upload_file("file");
				if ($fname != null) {
					$res = array("status" => true,
						"msg" => "Gaji Berhasil dikirim",
							"result" => null);
					$bulan = date("Y-m");
					if ($keterangan == "") {
						$keterangan = "Tidak Ada Catatan";
					}

					$dataGaji = $this->api2->insert("gaji", ["id_user"=>$id,"id_proyek"=>$id_proyek,"file_name"=>$fname,"gaji" => $gaji, "keterangan" => $keterangan, "id_pemilik" => $auth, "bulan"=>$bulan]);
					$rupiah = $this->api->rupiah($gaji);

					$saldoParr = $this->userApi->get(['id' => $auth]);
					$sal = array_shift($saldoParr);
					$saldo = $sal->saldo - $gaji;
					$this->api2->update("user", ["saldo" => $saldo], ["id" => $auth]);



					$this->db->select('device_token, nama');
					$this->db->from('user');
					$this->db->where('id', $id);
					$dt = $this->db->get();

					$this->api2->insert("khas_history", ["id_user" => $id, 
								"id_pemodal" => $auth,
								"id_gaji" => $dataGaji,
								"saldo_awal" => $sal->saldo, 
								"saldo_masuk" => $gaji, 
								"saldo_total" => $saldo,
								"jenis" => "gaji",
								"keterangan" => $dt->row('nama')]);

					$noti = $this->api->sendNotif($id,$dt->row("device_token"), "Hi ".$dt->row('nama') ,"Gaji Telah Diterima Sebesar ". $rupiah,'0');	
				}				

				
					}
			}
			
		}
		$this->response($res);
	}

}

/* End of file Saldo.php */
/* Location: ./application/controllers/api/Saldo.php */


 ?>