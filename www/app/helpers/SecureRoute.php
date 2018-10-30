<?php
use \Firebase\JWT\JWT;

class SecureRoute extends BaseRoute{
    /** 
    * Get hearder Authorization
    * */
    function getAuthorizationHeader(){
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        }else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            //print_r($requestHeaders);
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }

    /**
    * get access token from header
    * */
    function getBearerToken() {
        $headers = $this->getAuthorizationHeader();
        // HEADER: Get the access token from the header
        if (!empty($headers)) {
            $headersData = explode(" ", $headers);
            return $headersData[1];
        }else{
            return null;
        }
    }

    function validateToken($token) {
        try {
            $decoded = JWT::decode($token, $this->f3->get('key'), array('HS256'));
            $this->account = $decoded;
        } catch (Exception $e) {
            return false;
        }
        return true;
    }

    function beforeroute(){
        $url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $request_body = file_get_contents('php://input');
        $this->logger->write('[REQUEST PATH] '. $url);
        $this->logger->write('[DATA] '.$request_body);
        $this->start = $this->microtime_float();

        $token = $this->getBearerToken();
        if(isset($token)){
		    if (!$this->validateToken($token)) {
                $this->errorData['success'] = false;
                $this->errorData['payload'] = 'Invalid Token';

                $this->logger->write('[RESPONSE]');
                $this->logger->write(json_encode($this->errorData));

                API::error($this->errorData['success'], $this->errorData['payload']);
            }
        }else{
            $this->errorData['success'] = false;
            $this->errorData['payload'] = 'Please provide a token to access this resource';

            $this->logger->write('[RESPONSE]');
            $this->logger->write(json_encode($this->data));

            API::error($this->errorData['success'], $this->errorData['payload']);
        }
    }
    
}