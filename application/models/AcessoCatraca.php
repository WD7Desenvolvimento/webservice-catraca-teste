<?php 

class Application_Model_AcessoCatraca extends Zend_Db_Table_Abstract{
    protected $_name = "acessocatraca";

    function getListaAcessoCatraca(){
        $select = $this->select()->from("acessocatraca","*")->setIntegrityCheck(false);
        $select->joinInner("entidade","entidade_identidade = identidade",["nome"]);
        return parent::fetchAll($select);
    }
}