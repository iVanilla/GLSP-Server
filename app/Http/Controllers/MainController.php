<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class MainController extends Controller
{
    private $Request;
    private $Response;
    private $JsonResponse;
    private $Headers;

    public function __construct() {
        // Init request and response.
        $this->Request = Request::createFromGlobals();
        $this->Response = new Response();
        $this->JsonResponse = new JsonResponse();

        // Get all headers from request.
        $this->Headers = $this->Request->headers->all();
        $ReqHeaders = ['bundle-version', 'client-version', 'os', 'platform-type'];
        // Check required headers.
        foreach ($ReqHeaders as $Header) {
            if (empty($this->Headers[$Header])) {
                $this->Response->setContent('Error: ' . $Header . ' is missing.')
                        ->setStatusCode(Response::HTTP_FORBIDDEN)
                        ->send();
            }
        }

        // Check the client version.
        if (version_compare($this->Headers['bundle-version'][0], 4.1, '<')
         || (version_compare($this->Headers['client-version'][0], 18.0, '<'))) {
             // The client is below 4.1.x version.
             $this->Response->setContent('Error: client is below than 4.1')
                    ->headers->set('Maintenance', '1');
             $this->Response->send();
        }

        // Check client OS and platform type -- 1 = iOS, 2 = Android.
        if (($this->Headers['os'][0] != 'iOS' && $this->Headers['os'][0] != 'Android')
         || ($this->Headers['platform-type'][0]!= 1 && $this->Headers['platform-type'][0] != 2)) {
            // The client isn't iOS or Android.
            $this->Response->setContent('Error: Invalid OS or platform type')
                    ->headers->set('Maintenance', '1');
            $this->Response->send();
        } 

        if (!defined('WebView')) {
            // Whoops, look like something went wrong.
        }
    }

    public function maintenance() {
        echo '<h1>Server Maintenance!</h1>';
    }

	/*
	 * MicroDL support
	 */
    public function microdl() {
    	// MicroDL for 4.x support.
        if (is_dir('/DLC')) {
            //
    		$Packages = scandir('/DLC');
    		if (count($Packages) > 0) {

    		} else {
    			
    		}
    	} else {
    		throw new Exception('Error: DLC folder not exists.', 1);	
    	}
    }

    public function auth() {
        if (!empty($this->Headers['authorize'][0])) {
           // Send a authorize key, USING BCRYPT ALGORITHM.
            $Token = base64_encode(password_hash(hash('sha512', microtime(true)), PASSWORD_BCRYPT));
            $this->JsonResponse->setData([
                        "response_data" => [
                            "authorize_token" => $Token
                        ],
                        "status_code" => 200
                    ])
                    ->send();
        } else {
            // No authorize!
            $this->Response->setContent('Error: Invalid authorize')
                    ->headers->set('Maintenance', '1');
            $this->Response->send();
        }
    }

    public function login() {
        // Check the X-Meaages-Code
        $XMCKeys = [
            'JP' => 'WytVirvyiab',
            'EN' => 'liumangtuzi',
            'CN' => 'pwcmuUADRP6A2DcirAo4K+ZLaFg1XEvDpG+Qc0+BjU8',
            'TW' => '8llll8llll8'
        ];
        
        $XMC = $this->Headers['x-message-code'][0];
        if (empty($XMC)) {
            // No XMC!
            $this->Response->setContent('Error: No X-Meaage-Code')
                    ->setStatusCode(Response::HTTP_FORBIDDEN)
                    ->send();
        } else if ($XMC != hash_hmac('sha1', $this->Request->request->get('request_data'), $XMCKeys['JP'])) {
            // XMC is invalid!
            $this->Response->setContent('Error: X-Meaage-Code is invalid')
                    ->setStatusCode(Response::HTTP_FORBIDDEN)
                    ->send();
        } else {
            // Register.
            $this->JsonResponse->setData([
                "response_data" => [
                    "error_code" => 407,
                ],
                "status_code" => 600
            ])->send();
        }

        // Check login_key and login_passwd.
        
        // Check update.
        /*$this->Response->setContent('Server-Version')
                ->headers->set('Server-Version: ' . $Headers['client-version'])
                ->send();*/
    }

    public function startup() {
        
    }

    public function startnoinv() {

    }
}
