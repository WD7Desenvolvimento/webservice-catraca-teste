<?php

class Application_Form_AcessoCatraca extends Zend_Form{

    function init()
    {
        $this->setName("formAcessoCatraca");
        $entidadeModel = new Application_Model_Entidade();
        $listaEntidade = $entidadeModel->getAllPersons();
        $listaEntidadeFormatada = [];
        foreach($listaEntidade as $entidade) $listaEntidadeFormatada[$entidade->identidade] = $entidade->nome;

        $listaDiaSemana = [
            0=>"Domingo",
            1=>"Segunda feira",
            2=>"Terça feira",
            3=>"Quarta feira",
            4=>"Quinta feira",
            5=>"Sexta feira",
            6=>"Sábado"
        ];

        $entidade = $this->createElement("select","entidade_identidade",["label"=>"Entidade","multiOptions"=>$listaEntidadeFormatada]);
        $dia = $this->createElement("select","dia",["label"=>"Dia da Semana","multiOptions"=>$listaDiaSemana]);
        
        $ativo = $this->createElement("checkbox","ativo",["label"=>"Status","checked_value"=>1, "unchecked_value"=>0]);
        $horarioAvulso = $this->createElement("checkbox","horarioAvulso",["label"=>"Horario Avulso","checked_value"=>1, "unchecked_value"=>0]);
        
        $numeroCartao = $this->createElement("text","numeroCartao",["label"=>"Numero do cartão"]);
        $horarioInicio = $this->createElement("text","horarioInicio",["label"=>"Horario Inicial"]);
        $horarioFim = $this->createElement("text","horarioFim",["label"=>"Horario Final"]);
       
        $this->addDisplayGroup([$numeroCartao,$entidade,$ativo],"grupo1");
        $this->addDisplayGroup([$dia,$horarioInicio,$horarioFim,$horarioAvulso],"grupo2");

        $submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel('Cadastrar');
		$this->addElements(array($submit));
    }
}