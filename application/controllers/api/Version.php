<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Version extends REST_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_api', 'api');
		//Do your magic here
	}
	public function index_get()
	{
		// $data = array_shift($this->a)
		$param = $this->get("param");
		if (!empty($param)) {
			if ($param=="default") {
			$this->response(["updateRequired" => false]);
		}elseif ($param = "cek") {
			$this->response($this->api->getUpdate());
			
		}
		}
		else{
			$this->response(["result"=>"unauthorized"]);
		}
		
	}

}

/* End of file Delete.php */
/* Location: ./application/controllers/api/Delete.php */

 ?>