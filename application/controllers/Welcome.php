<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// require APPPATH . '/libraries/Pdf.php';

class Welcome extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_api2', 'api2');
		$this->load->model('M_pdf', 'pdf');

		//Do your magic here
	}

	public function index()
	{
        // $this->load->view('index');
        // echo strftime("%A, %e %B %Y",strtotime("Thu Mar 12 18:51:52 GMT+07:00 2020"));
        $this->pdf->laporan_proyek('1');
        // $this->api2->insert("tgl", ["test" => "2020-03-09 19:10:71"]);
        // $this->db->insert('tgl', array("test" => strftime("%A, %e %B %Y",strtotime("2020/03/09 19:10:71"))));
        // $this->db->insert('tgl', array("test" => "2020-03-09"));
// header("Content-Type: application/json");

		// $t = explode(" ", "ahmad saphi i");
		// var_dump($t);
		// echo $this->api->cek_role("a48524be21c94d52b1338dd01b73b08a");
		// $this->db->select('last_insert_id()');
		// $this->db->from('user');
		// $this->db->where('id', "LAST_INSERT_ID()");
		// $token = $this->db->get()->result();
		// $today = date("Y-m-d");
		// $token = $this->db->query("select * from transaksi where created_date like '$today'")->result();

		// $this->db->select('id_user');
		// $this->db->where('bulan', date("Y-m"));
		// $gaji = $this->db->get("gaji")->result();
		// $list = array();
		// foreach ($gaji as  $value) {
		// 		$list[]=$value->id_user;
		// 	}
		// $this->db->select('user.*');
		// $this->db->from('user');
		// $this->db->join('gaji', 'user.id = gaji.id_user', 'left');
		// $this->db->where_not_in('user.id', $list);	
		// $data = $this->db->get()->result();
		// $token = $this->api2->getAll("transaksi", ["id" => "32e927bed2384fca88fd86458c2a9e30", 
		// 	'month(created_date)=' => $today]);
		// $token ="e3gT6ll4bYE:APA91bFf3LqQctzs4THGjxzNPIePhmxdXjUOysrSZCZS8TZJ70SSIu6XMNb8V7wOJfaR34VgY-_kI4HIzBbKcy37taLL0hBK5KOpy3rSIQcPnM8py0crUYhXnMVodrmbW0zGi5hYcKN1";
		// $token = "topics/Gaji";
		// $this->api->sendNotif("222", $token, "titel", "9000",2);
		// echo $token;
		// $this->db->select('nama,username,ifnull(device_token,"abcd") as device_token, created_date,role,id,foto,is_active,nomer,is_active');
		// 			$this->db->where('username','bon321');
		// 			$result = $this->db->get("user")->result();
		// var_dump($token);
    	// $proyek = $this->api2->get("proyek", ['id' => "2"]);
		// echo json_encode(array("data" => $proyek));


		   // setlocale (LC_TIME, 'id_ID');
		// $date = date_format(strtotime(($proyek->created_date)), "A, e B Y");
// 		$date = date("A, e B Y", strtotime(($proyek->created_date)));
// echo $date = strftime("%A, %e %B %Y",strtotime(($proyek->created_date)));
// echo $date;
		// $pass = $this->api->password("password");
		
		// $arrayName = array('nama' => "Super Admin",
		// 						'nomer'=> '085655082415',
		// 						'username' => "admin123",
		// 						'password' => $pass,
		// 						'saldo' => 0,
		// 						'role' => 2,
		// 						'id' => $this->gen_uuid() );
		// $this->db->insert('user', $arrayName);
		
		
	}


	
}
