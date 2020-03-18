<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_api extends CI_Model {

	function rupiah($angka){
	
	$hasil_rupiah = "Rp " . number_format($angka,2,',','.');
	return $hasil_rupiah;
 
}

	function getUpdate()
	{
		// $tc = file_get_contents("https://raw.githubusercontent.com/PradhiptaBagaskara/begawan/master/app/release/output.json");
		$tc = file_get_contents(base_url()."/uploads/"."output.json");
		$ta = json_decode($tc,true);

		
		$arr = array();
		foreach ($ta as $key => $value) {
			$arr['outputName'] = $value['apkData']['outputFile'];
			$arr['versionCode'] = $value['apkData']['versionCode'];
			$arr['versionName'] = $value['apkData']['versionName'];
			$arr['hash'] = md5($value['apkData']['versionName']);
			$arr['updateRequired'] = false;
			$arr['downloadUrl'] = base_url()."/uploads/".$value['apkData']['outputFile'];

			// $arr['downloadUrl'] = "https://github.com/PradhiptaBagaskara/begawan/raw/master/app/release/".$value['apkData']['outputFile'];
		}
		$curl = curl_init($arr['downloadUrl']);
 
		//Set CURLOPT_FOLLOWLOCATION to TRUE so that our
		//cURL request follows any redirects.
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

		//We want curl_exec to return  the output as a string.
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		//Set CURLOPT_HEADER to TRUE so that cURL returns
		//the header information.
		curl_setopt($curl, CURLOPT_HEADER, true);

		//Set CURLOPT_NOBODY to TRUE to send a HEAD request.
		//This stops cURL from downloading the entire body
		//of the content.
		curl_setopt($curl, CURLOPT_NOBODY, true);

		//Execute the request.
		curl_exec($curl);

		//Retrieve the size of the remote file in bytes.
		$fileSize = curl_getinfo($curl, CURLINFO_CONTENT_LENGTH_DOWNLOAD);

		curl_close($curl);
		//Convert it into KB
		$fileSizeKB = number_format(round($fileSize / 1024)/1024, 2);
		$arr['size'] = $fileSizeKB."MB";
		$arr['bytes'] = $fileSize;

		return $arr;
			# code...
	}

function delete_dir($dirname) {
     if (is_dir($dirname)){$dir_handle = opendir($dirname);}
     if (!$dir_handle){return false;}
     while($file = readdir($dir_handle)) {
           if ($file != "." && $file != "..") {
                if (!is_dir($dirname."/".$file)){@unlink($dirname."/".$file);}
                else{
                     delete_directory($dirname.'/'.$file);
           }
       }
   }
     
     closedir($dir_handle);
     rmdir($dirname);
     mkdir($dirname);
     $file = fopen($dirname."/index.html", "w");
     fwrite($file, "");
     return true;
}


	function sendNotif($id, $token,$title, $body,$halaman){
		$url = "https://fcm.googleapis.com/fcm/send";
	    $token = $token;
	    $serverKey = 'AAAAjVYZDqE:APA91bGcCTrevxdsrr6z21lGuMKXH2ka3SyhxMFnZiP-v13nrRguVL0yZBio5LXXxM8dYPMQfuOiPetjKtHcuXqRBCgZPBmpLHcZda80Fod89FgIYr8jQhofhuhGcEzZQBcfhPEi5VE7';
	    // $title = "Notification title";
	    // $body = "Hello I am from Your php server";
	    $notification = array("sound" => "default", 'android_channel_id' => 'com.pt.begawanpolosoro');
	    $data = array("id_user" => $id, 'title' =>$title , 'message' => $body, "halaman" => $halaman);
	    $arrayToSend = array('to' => $token,'data' => $data);
	    $json = json_encode($arrayToSend);
	    $headers = array();
	    $headers[] = 'Content-Type: application/json';
	    $headers[] = 'Authorization: key='. $serverKey;
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
	    curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);

	    //Send the request
	    $response = curl_exec($ch);
	    //Close request
	    if ($response === FALSE) {
	    die('FCM Send Error: ' . curl_error($ch));
	    }else{
	    }
	    curl_close($ch);
	  //   return '<meta http-equiv="refresh"
   // content="0; url='.base_url().'">';
#126AAF
	}


	function password($pass = ""){
		$options = [
		    'cost' => 12,
		];
		return password_hash($pass, PASSWORD_BCRYPT, $options);
	}
	function cek_field($field="", $value="", $db=""){
		$this->db->select('count(*)');
		$this->db->where($field, $value);
		$cek = $this->db->get($db);
		$hasil = $cek->row_array();
		return $hasil['count(*)'];
	}


	function cek_pass($field="", $value="", $pass=""){
		$this->db->select('password');		
		$this->db->where($field, $value);
		$dbku = $this->db->get('user');
		$db_pass = $dbku->result_array();
		$isi = $db_pass[0]['password'];
		// return $isi;

		return password_verify($pass, $isi);
	}

	function get_username($value)
	{
		$t = explode(" ", $value);
		$ran = rand(10,99);
		return strtolower($t[0]).$ran;
		# code...
	}

	function cek_role($value)
	{
		$this->db->select('role');
		$this->db->from('user');
		$this->db->where('id', $value);
		return $this->db->get()->row('role');
	}



	function login($username, $pass){
		$key = $this->cek_field('username', $username, 'user');
		if ($key == "1") {
			return $this->cek_pass('username', $username, $pass);
		}else{
			return false;
		}
		return false;
	}

	function gen_uuid() {
    return sprintf( '%04x%04x%04x%04x%04x%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

        // 16 bits for "time_mid"
        mt_rand( 0, 0xffff ),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand( 0, 0x0fff ) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand( 0, 0x3fff ) | 0x8000,

        // 48 bits for "node"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
}





 	function upload_file($name_form='')
 	{
 		$config['upload_path'] = './uploads/';    
 		$config['allowed_types'] = 'jpg|png|jpeg';    
 		      
 		$this->load->library('upload', $config); 
 		// Load konfigurasi uploadnya    
 		if($this->upload->do_upload($name_form)){ 
 		// Lakukan upload dan Cek jika proses upload berhasil      
 		// Jika berhasil :      
 		$return = array('result' => 'success', 'file' => $this->upload->data(), 'error' => '');      
 		return $return;    
 		}else{      
 		// Jika gagal :      
 			$return = array('result' => 'failed', 'file' => '', 'error' => $this->upload->display_errors());
 			return $return;    
 		}
 	}




}

/* End of file M_api.php */
/* Location: ./application/models/M_api.php */



