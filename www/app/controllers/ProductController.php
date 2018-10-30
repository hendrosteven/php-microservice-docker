<?php

class ProductController extends SecureRoute{

    private $productSvr;

    function __construct(){
        parent::__construct();
        $this->productSvr = new ProductService();
    }

    function insert(){
        $code = $this->post['code'];
        $name = $this->post['name'];
        $description = $this->post['description'];
        $price = $this->post['price'];

        $v = new Valitron\Validator(array('Code'=>$code,'Name'=> $name, 'Price'=> $price));
        $v->rule('required', ['Code','Name','Price']);   

        if ($v->validate()) {
            $this->cache->delete('ALL_PRODUCT');
            $this->data = [
                'success'=> true, 
                'payload'=> $this->productSvr->save($code, $name, $description, $price)
            ];
        }else{
            $this->data = [
                'success'=> false, 
                'payload'=> $v->errors()
            ];
        }
    }

    function findOne(){
        $id = $this->params['id'];
        $this->data = [
            'success' => true,
            'payload' => $this->productSvr->find($id)
        ];
    }

    function findAll(){
        if(!$this->cache->get('ALL_PRODUCT')){
            $products = $this->productSvr->findAll();
            $this->cache->set('ALL_PRODUCT', $products);

            $this->data = [
                'success' => true,
                'payload' => $products
            ];
        }else{
            $this->data = [
                'success' => true,
                'payload' => $this->cache->get('ALL_PRODUCT')
                ];
        }
    }

}