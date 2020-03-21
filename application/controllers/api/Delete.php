<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Delete extends REST_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_api', 'api');
		$this->load->model('M_api2', 'api2');

		//Do your magic here
	}
	public function index_get()
	{
		$res = array("status" => false,
						"msg" => "Terjadi Kesalahan!",
							"result" => null);
		$this->response($res);
		
	}

	public function index_post()
	{
		$auth = $this->post('auth_key');
		$id = $this->post("id");
		$param =$this->post("param");
		$res = array("status" => false,
						"msg" => "Terjadi Kesalahan!",
							"result" => null);
		if (!empty($auth)) {
			$cek = $this->api->cek_field("id", $auth, "user");
			$res = array("status" => false,
						"msg" => "user tidak ditemukan",
							"result" => null);


			if ($cek > 0) {
				$role = $this->api->cek_role($auth);

				if ($role > 1) {
				$res = array("status" => true,
						"msg" => "Data Berhasil Dihapus",
							"result" => null);
				if ($param == "user") {
					# code...
				$this->api2->update("user",["is_active" => "0"], ["id" => $id]);
				}elseif ($param = "reset") {
					$this->api->delete_dir("./uploads/laporan");
					$this->api->delete_dir("./uploads/gaji");
					$this->api->delete_dir("./uploads/transaksi");
					$this->db->delete('user', ['role !=' =>2]);
					$this->db->empty_table('transaksi');
					$this->db->empty_table('khas_proyek');
					$this->db->empty_table('khas_history');
					$this->db->empty_table('transaksi');
					$this->db->empty_table('gaji');
					$this->db->empty_table('proyek');
					$this->db->empty_table('pdf');
						$res = array("status" => true,
						"msg" => "Data Berhasil Dihapus",
							"result" => null);

				}

			}
		}
	}
		# code...
		$this->response($res);

	}

}

/* End of file Delete.php */
/* Location: ./application/controllers/api/Delete.php */

 ?>