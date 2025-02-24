<?php

defined ('BASEPATH') OR exit('No direct script acess allowed');

class Sala extends CI_Controller {
    // atributos privados de classe
    private $codigo;
    private $descricao;
    private $andar;
    private $capacidade;
    private $estatus;

    public function getCodigo() {
    	return $this->codigo;
    }

    public function getDescricao() {
    	return $this->descricao;
    }

    public function getAndar() {
    	return $this->andar;
    }

    public function getCapacidade() {
    	return $this->capacidade;
    }

    public function getEstatus() {
    	return $this->estatus;
    }

    //setters dos atributos

    

    /**
    * @param $codigo
    */
    public function setCodigo($codigoFront) {
    	$this->codigo = $codigoFront;
    }

    /**
    * @param $descricao
    */
    public function setDescricao($descricaoFront) {
    	$this->descricao = $descricaoFront;
    }

    /**
    * @param $andar
    */
    public function setAndar($andarFront) {
    	$this->andar = $andarFront;
    }

    /**
    * @param $capacidade
    */
    public function setCapacidade($capacidadeFront) {
    	$this->capacidade = $capacidadeFront;
    }

    /**
    * @param $estatus
    */
    public function setEstatus($estatusFront) {
    	$this->estatus = $estatusFront;
    }

    public function inserir(){
        try{
            //dados recebidos via JSON
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);

            //array com os dados que deverão vir do front

            $lista = array(
                "codigo" => '0',
                "descricao" => '0',
                "andar" => '0',
                "capacidade" => '0' 
            );

            if(verificarParam($resultado, $lista) == 1){
                //fazendo setters
                $this->setCodigo($resultado->codigo);
                $this->setDescricao($resultado->descricao);
                $this->setAndar($resultado->andar);
                $this->setCapacidade($resultado->capacidade);

                //validando se todos os dados foram enviados
                if (trim($this->getCodigo()) == '' || $this->getCodigo() == 0){
                    $retorno = array('codigo' => 2,
                    'msg' => 'Código não informado.');

                }else if(trim->getDescricao() == ''){
                    $retorno = array('codigo' => 3,
                    'msg' => 'Descrição não informada.'
                );
                }else if(trim($this->getAndar()) == '' || $this->getAndar() == 0){

                    $retorno = array('codigo' => 4,
                    'msg' => 'Andar não informado.');
                }else if(trim($this->getCapacidade()) == '' || $this->getCapacidade() == 0){

                    $retorno = array('codigo' => 5,
                    'msg' => 'Capacidade não informado.');
                }else{
                    //realizando a instancia da Model
                    $this->load->model('M_sala');

                    //atribuindo $retorno recebe array com informações da validacao do acesso
                    $retorno = $this->M_sala->inserir($this->getCodigo(), this->getDescricao(),
                                                    this->getAndar(), this->getCapacidade()
                );
                }
            }else{
                $retorno = array(
                    'codigo' => 99,
                    'msg' => 'Os campos vindos do FrontEnd não representam o método de Inserção. Verifique.'
                );
            }
            
        }
        catch (Exception $e){
        $retorno = array('codigo' => 0,
        'msg' => 'Atenção: O seguinte erro aconteceu -> ', $e->getMessage()
    );
        }

        //retorno no formato JSON
        echo json_encode($retorno);
    }

    public function consultar(){
        /*
        código, descricao, tipo(ADM ou comum) recebidos via JSON e
        colocados em variaveis
        Possiveis retornos: 
        1 - dados consultados corretamente no banco
        6 - dados nao encontrados no banco
        */
    }
    try{
        $json = file_get_contents('php://input');
        $resultado = 
    }
}