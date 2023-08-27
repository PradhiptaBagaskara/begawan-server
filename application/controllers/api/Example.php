<?php
use Google\Service\Connectors\Oauth2ClientCredentials;

defined('BASEPATH') or exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */



// use namespace
use Google\Auth\OAuth2;
use chriskacerguis\RestServer\RestController;

// use Google\Auth\OAuth2;


/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class Example extends RestController
{

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('M_api', 'api');
    }

    private function getGoogleAccessToken()
    {

        $credentialsFilePath = APPPATH . '../begawanpolosoro.json'; //replace this with your actual path and file name
        $client = new \Google_Client();
        $client->setAuthConfig($credentialsFilePath);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        $client->fetchAccessTokenWithAssertion();
        $token = $client->getAccessToken();
        return $token['access_token'];
    }
    public function sendMessage()
    {

        $apiurl = 'https://fcm.googleapis.com/v1/projects/begawanpolosoro-1a915/messages:send';

        $headers = [
            'Authorization: Bearer ' . $this->getGoogleAccessToken(),
            'Content-Type: application/json'
        ];

        $notification_tray = [
            'title' => "Some title",
            'body' => "Some content",
        ];

        $in_app_module = [
            "title" => "Some data title (optional)",
            "body" => "Some data body (optional)",
        ];
        //The $in_app_module array above can be empty - I use this to send variables in to my app when it is opened, so the user sees a popup module with the message additional to the generic task tray notification.

        $message = [
            'message' => [
                'notification' => $notification_tray,
                'data' => $in_app_module,
            ],
        ];


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiurl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($message));

        $result = curl_exec($ch);

        if ($result === FALSE) {
            //Failed
            die('Curl failed: ' . curl_error($ch));
        }

        curl_close($ch);

    }

    public function index_get()
    {


        $this->response($this->getGoogleAccessToken(), RestController::HTTP_OK); // OK (200) being the HTTP response code

        // $data = $this->api->sendNotif("2", "/topics/transaksi", "john", "Melakukan Transaksi Baru dengan dana 10k", "0");
        // return $this->set_response($data, true, 200); // CREATED (201) being the HTTP response code

    }
}