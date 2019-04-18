<?php
class Webservice_AcessoController extends Zend_Controller_Action{
    private $body;
    private $codigoescola;

    function init()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $body = $this->_request->getRawBody();
        $this->codigoescola = $this->_request->getHeader('codigoescola');

        if($body){                
          $this->body = Zend_Json::decode($body);
        }
    }

    function getAcessosAction(){
        $result = [];
        if($this->_request->isPost()){
          
            try {

                $codigoEscola = $this->codigoescola;
                if($codigoEscola!= CODIGOESCOLA)
                        throw new Exception("Escola não authenticada");

                $acessoCatraca = new Application_Model_AcessoCatraca();
                $horarioAcesso = new Application_Model_HorarioAcessoCatraca();
                $listaAcesso = $acessoCatraca->getListaAcessoCatraca();
               
               if(!empty($listaAcesso)){
                  
                    foreach($listaAcesso as $acesso){
                      
                        $arrayAcesso =  $acesso->toArray(); 
                        $listaHorario = $horarioAcesso->fetchAll("acessoCatraca_idAcessoCatraca=".$acesso->idAcessoCatraca)->toArray();
                        $listaHorario = array_map(function($horario){
                            $horario['horarioAvulso'] = (bool) $horario['horarioAvulso'];
                            return $horario;
                        },$listaHorario);
                        $arrayAcesso['horarios'] = $listaHorario;
                        $arrayAcesso['ativo'] = (bool) $arrayAcesso['ativo'];
                        $result['listaAcesso'][] = $arrayAcesso;                     
                    }
               }else{
                   throw new Exception("Erro ao buscar dados");
               }

               $this->getResponse()
                ->setHeader("Content-Type","application/json")
                ->setBody(json_encode($result));
            } catch (Exception $e) {
                $this->getResponse()
                ->setHeader("Content-type","application/json")
                ->setBody(json_encode(["erro"=>$e->getMessage()]))
                ->setHttpResponseCode(400);
            }
        }
    }
    function horarioAction(){
        echo date("Y-m-d H:i:s");
    }
    function getAcessoPorCartaoAction(){
        
        try{
            if($this->_request->isPost()){
                $codigoescola = $this->_request->getHeader("codigoescola");
                $cartao = $this->body['cartao'];

                if($codigoescola != CODIGOESCOLA)
                    throw new Exception("Código da escola inválido");
                
                if(!$cartao)
                    throw new Exception("Código do cartão não informado");

                $acessoCatraca = new Application_Model_AcessoCatraca();
                $horarioAcesso = new Application_Model_HorarioAcessoCatraca();

                $dadosAcesso = $acessoCatraca->fetchRow("numeroCartao =".$cartao);

                if(empty($dadosAcesso))
                    throw new Exception("Código do cartão inválido");


                $listaHorario = $horarioAcesso->fetchAll('acessocatraca_idAcessoCatraca ='.$dadosAcesso->idAcessoCatraca)->toArray();
                $dadosAcesso = $dadosAcesso->toArray();

                $listaHorario = array_map(function($horario){
                    $horario['horarioAvulso'] = (bool) $horario['horarioAvulso'];
                    return $horario;
                },$listaHorario);
                $dadosAcesso['ativo'] = (bool) $dadosAcesso['ativo'];
                $dadosAcesso['horarios'] = $listaHorario;
              
                $this->getResponse()
                ->setHeader("Content-Type","application/json")
                ->setBody(json_encode($dadosAcesso));
            
            }
        }catch(Exception $e){
            $this->getResponse()
            ->setHeader("Content-type","application/json")
            ->setBody(json_encode(["erro"=>$e->getMessage()]))
            ->setHttpResponseCode(400);
        }
    }

    function temAtualizacaoAction(){
        try{
            if($this->codigoescola != CODIGOESCOLA)
                throw new Exception("Código da escola inválido");

            
            if(!isset($this->body['ultimaBusca']))
                throw new Exception("Erro ao buscar dados, parametro 'ultimaBusca' não informado");

            $acessoCatraca = new Application_Model_AcessoCatraca();
            $ultimaBusca = $this->body['ultimaBusca'];
            $select = $acessoCatraca->select()->where("dataAtualizacao >= '{$ultimaBusca}'");
            $result = $acessoCatraca->fetchRow($select);
        
            $temAtualizacao = ["temAtualizacao"=>true];
            if(!$result){
                $temAtualizacao = ["temAtualizacao"=>false];
            }

            $this->getResponse()
            ->setHeader("Content-type","application/json")
            ->setBody(json_encode($temAtualizacao));
            
        }catch(Exception $e){
            $this->getResponse()
            ->setHeader("Content-type","application/json")
            ->setBody(json_encode(["erro"=>$e->getMessage()]))
            ->setHttpResponseCode(400);
        }
    }
}