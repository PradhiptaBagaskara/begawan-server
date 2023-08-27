<?php

defined('BASEPATH') or exit('No direct script access allowed');


use chriskacerguis\RestServer\RestController;

class Version extends RestController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_api', 'api');
		//Do your magic here
	}
	public function index_get()
	{
		// $data = array_shift($this->a)
		$this->response(["result" => "unauthorized"]);

		$param = $this->get("param");
		if (!empty($param)) {
			if ($param == "default") {
				$this->response(["updateRequired" => false]);
			} elseif ($param = "cek") {
				$this->response($this->api->getUpdate());

			}
		}


	}

}

/* End of file Delete.php */
/* Location: ./application/controllers/api/Delete.php */

?>