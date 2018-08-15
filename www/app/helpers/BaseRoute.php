<?php

class BaseRoute {

    protected $f3;
    protected $data;
    protected $errorData = array();
    protected $logger;
    protected $account;  //current login account data
    protected $post; //request data
    protected $params;

    function __construct() {
        $f3 = Base::instance();  
        $this->f3 = $f3;     
        $this->logger = new Log("logs/". date("Y-m-d") . ".log"); 
        $this->post = json_decode($this->f3->get('BODY'),true);   
        $this->params = $this->f3->get('PARAMS');
    }

    public function beforeroute(){
        $url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $request_body = file_get_contents('php://input');
        $this->logger->write('[REQUEST PATH] '. $url);
        $this->logger->write('[DATA] '.$request_body);
    }

    public function afterroute(){
        $this->logger->write('[RESPONSE]');
		if(isset($this->errorData['code'])){
            $this->logger->write(json_encode($this->errorData));
			API::error($this->errorData['code'], $this->errorData['message']);
		}else {            
            $this->logger->write(json_encode($this->data));
            API::success($this->data);        
		}
	}

}
