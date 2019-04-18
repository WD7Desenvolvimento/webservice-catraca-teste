<?php

class Application_Model_Entidade extends Zend_Db_Table_Abstract{

    protected $_name = "entidade";

    function getAllPersons(){
        return parent::fetchAll(["tipoPessoa"=>'1']);
    }
}