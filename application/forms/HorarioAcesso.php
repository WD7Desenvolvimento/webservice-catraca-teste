<?php 

class Application_Form_HorarioAcesso extends Zend_Form{
    
   function init()
   {
        $this->setName("horarioAcesso");
        $acessoCatraca = new Application_Model_AcessoCatraca();

        $listaDiaSemana = [
            1=>"Domingo",
            2=>"Segunda feira",
            3=>"Terça feira",
            4=>"Quarta feira",
            5=>"Quinta feira",
            6=>"Sexta feira",
            7=>"Sábado"
        ];
        $select = $acessoCatraca->select()->from("acessocatraca")->setIntegrityCheck(false);
        $select->joinInner("entidade","entidade_identidade = identidade","nome");
        $listaAcessoCatraca = $acessoCatraca->fetchAll($select);
        $listaAcessoCatracaFormatada =[];
        foreach($listaAcessoCatraca as $acesso) $listaAcessoCatracaFormatada[$acesso->idAcessoCatraca] = $acesso->nome;

        $acesso = $this->createElement("select","acessoCatraca_idAcessoCatraca",["label"=>"Acesso","multiOptions"=>$listaAcessoCatracaFormatada]);
        $dia = $this->createElement("select","dia",["label"=>"Dia da Semana","multiOptions"=>$listaDiaSemana]);
        $horarioAvulso = $this->createElement("checkbox","horarioAvulso",["label"=>"Horario Avulso","checked_value"=>1, "unchecked_value"=>0]);

        $horarioInicio = $this->createElement("text","horarioInicio",["label"=>"Horario Inicial"]);
        $horarioFim = $this->createElement("text","horarioFim",["label"=>"Horario Final"]);
    
        $this->addDisplayGroup([$acesso,$dia,$horarioInicio,$horarioFim, $horarioAvulso],"grupo1");
       
        $submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel('Cadastrar');
		$this->addElements(array($submit));
   }

}