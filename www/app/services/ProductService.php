<?php
use \RedBeanPHP\R as R;

class ProductService extends BaseServiceReadBean{

    function save($code, $name, $description, $price){
        $product = R::dispense( 'products' );
        $product->code = $code;
        $product->name = $name;
        $product->description = $description;
        $product->price = $price;
        $product->create_date = date('Y-m-d H:i:s');
        $product->update_date = date('Y-m-d H:i:s');
        $id = R::store( $product );
        $newProduct = R::load( 'products', $id );
        return $newProduct;
    }

    function find($id){
        $product =  R::findOne( 'products', ' id = ? ', [ $id ] );
        return $product;
    }

    function update($id, $code, $name, $description, $price){
        $product = R::load( 'products', $id );
        $product->code = $code;
        $product->name = $name;
        $product->description = $description;
        $product->price = $price;
        $product->update_date = date('Y-m-d H:i:s');
        R::store($product);
        return $product;
    }

    function delete($id){
        $product = R::load( 'products', $id );
        R::trash( $product ); 
        return true;
    }

}