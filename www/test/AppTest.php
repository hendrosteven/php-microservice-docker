<?php
require 'vendor/autoload.php';

use PHPUnit\Framework\TestCase;

$f3 = Base::instance();
$f3->config('config/config.ini');

class AppTest extends TestCase{
    private $productSvr;
    private $product;
 
    protected function setUp(){
        $this->productSvr = new ProductService();
    }
 
    protected function tearDown(){
        $this->productSvr = NULL;
        $this->product = NULL;
    }
 
    public function testService(){
        $this->product = $this->productSvr->save(
            "P002", 
            "Sample",
            "Keterangan", 
            100
        );
        //insert new product
        $this->assertEquals('P002', $this->product->code);

        //update product
        $this->product = $this->productSvr->update(
            $this->product->id,
            "P003",
            "Sample Edit", 
            "Keterangan Edit", 
            200
        );
        $this->assertEquals('P003', $this->product->code);

        //find a product
        $this->product = $this->productSvr->find($this->product->id);
        $this->assertEquals('P003', $this->product->code);
        
        //delete product
        $this->assertEquals(true, $this->productSvr->delete($this->product->id));
    }
}