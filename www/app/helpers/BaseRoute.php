<?php

class BaseRoute {

    protected $f3;
    protected $data;
    protected $errorData = array();
    protected $logger;
    protected $account;  //current login account data
    protected $post; //request data
    protected $params;
    protected $cache;
    protected $start;
    protected $end;

    function __construct() {
        $f3 = Base::instance();  
        $this->f3 = $f3;     

        //init logger
        $this->logger = new Log("logs/". date("Y-m-d") . ".log"); 

        //init post data
        $this->post = json_decode($this->f3->get('BODY'),true);   

        //init parameters query
        $this->params = $this->f3->get('PARAMS');

        //init redis cache
        $client = new \Redis();
        $client->connect( $f3->get('redis_dns') );
        $this->cache = new \MatthiasMullie\Scrapbook\Adapters\Redis($client);

    }


    /** Execute before endpoint */
    public function beforeroute(){
        $url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $request_body = file_get_contents('php://input');
        $this->logger->write('[REQUEST PATH] '. $url);
        $this->logger->write('[DATA] '.$request_body);
        $this->start = $this->microtime_float();
    }

    /** Execute after endpoint */
    public function afterroute(){
        $this->logger->write('[RESPONSE]');
		if(isset($this->errorData['code'])){
            $this->logger->write(json_encode($this->errorData));
			API::error($this->errorData['code'], $this->errorData['message']);
		}else {            
            $this->logger->write(json_encode($this->data));
            $this->end = $this->microtime_float();
            $this->data['execution_time'] = $this->end - $this->start;
            API::success($this->data);        
		}
    }
    
    protected function microtime_float(){
        list($usec, $sec) = explode(" ", microtime(true));
        return ((float)$usec + (float)$sec);
    }

}
