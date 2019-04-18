<?php

class Webservice_IndexController extends Zend_Controller_Action {
    
    function indexAction(){

    }

    function inserirAcessoAction(){
        try{
            $acessoCatracaModel = new Application_Model_AcessoCatraca();
            $horarioAcessoCatracaModel = new Application_Model_HorarioAcessoCatraca();
            $form = new Application_Form_AcessoCatraca();
            $form->setAction("/webservice/index/inserir-acesso");

            $this->view->form = $form;

            if($this->_request->isPost()){
                $dados = $this->_request->getPost();

                $idAcessoCatraca = $acessoCatracaModel->insert([
                    "entidade_identidade"=>$dados["entidade_identidade"],
                    "numeroCartao"=>$dados["numeroCartao"],
                    "ativo"=>$dados["ativo"]
                ]);           
                
                $horarioAcessoCatracaModel->insert([
                    "acessoCatraca_idAcessoCatraca"=>$idAcessoCatraca,
                    "horarioInicio"=>date("H:i",strtotime($dados["horarioInicio"])),
                    "horarioFim"=>date("H:i",strtotime($dados["horarioFim"])),
                    "dia"=>$dados["dia"],
                    "horarioAvulso"=> $dados["horarioAvulso"],  
                ]);
                
                $this->redirect("/webservice/index/index");
            }
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }

    function inserirHorarioAction(){
        try {
            $horarioAcessoCatracaModel = new Application_Model_HorarioAcessoCatraca();
            $acessoCatracaModel = new Application_Model_AcessoCatraca();

            $form = new Application_Form_HorarioAcesso();
            $form->setAction("/webservice/index/inserir-horario");
            $this->view->form = $form;


            if($this->_request->isPost()){
                $dados = $this->_request->getPost();
                $horarioAcessoCatracaModel->insert([
                    "acessoCatraca_idAcessoCatraca"=>$dados["acessoCatraca_idAcessoCatraca"],
                    "horarioInicio"=>date("H:i",strtotime($dados["horarioInicio"])),
                    "horarioFim"=>date("H:i",strtotime($dados["horarioFim"])),
                    "dia"=>$dados["dia"],
                    "horarioAvulso"=> $dados["horarioAvulso"],  
                ]);
                $acessoCatracaModel->update([
                    "dataAtualizacao"=>date("Y-m-d H:i:s")
                ], "idAcessoCatraca =".$dados["acessoCatraca_idAcessoCatraca"]);

                $this->redirect("/webservice/index/index");
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}