<?php 

class Application_Model_HorarioAcessoCatraca extends Zend_Db_Table_Abstract{
    protected $_name = "horarioacessocatraca";

    function getHorarios($idAcessoCatraca){
        return parent::fetchAll(["acessoCatraca_idAcessoCatraca"=>$idAcessoCatraca]);
    }
}