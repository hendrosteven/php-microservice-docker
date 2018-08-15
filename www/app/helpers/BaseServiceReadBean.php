<?php
use \RedBeanPHP\R as R;
use \RedBeanPHP\OODB as OODB;
use \RedBeanPHP\ToolBox as ToolBox;

class BaseServiceReadBean {

    function __construct(){
        $f3 = Base::instance(); 
        R::setup( $f3->get('db_dns') . $f3->get('db_name'), $f3->get('db_user'), $f3->get('db_pass'));
        R::freeze( $f3->get('db_freeze') );

        $oldToolBox = R::getToolBox();
        $oldAdapter = $oldToolBox->getDatabaseAdapter();
        $uuidWriter = new UUIDWriterMySQL( $oldAdapter );
        $newRedBean = new OODB( $uuidWriter );
        $newToolBox = new ToolBox( $newRedBean, $oldAdapter, $uuidWriter );
        R::configureFacadeWithToolbox( $newToolBox );
    }

}