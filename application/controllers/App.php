<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class App extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_api', 'api');
	}


	public function index()
	{
		$data = $this->api->getUpdate();
		// $this->load->view('download', $data);
	}

	public function download()
	{
		$data = $this->api->getUpdate();
		$this->load->view('download', $data);
		# code...
	}

}

/* End of file App.php */
/* Location: ./application/controllers/api/App.php */

 ?>