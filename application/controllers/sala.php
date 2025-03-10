<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Sala extends CI_Controller
{
    // atributos privados de classe
    private $codigo;
    private $descricao;
    private $andar;
    private $capacidade;
    private $estatus;

    public function getCodigo()
    {
        return $this->codigo;
    }

    public function getDescricao()
    {
        return $this->descricao;
    }

    public function getAndar()
    {
        return $this->andar;
    }

    public function getCapacidade()
    {
        return $this->capacidade;
    }

    public function getEstatus()
    {
        return $this->estatus;
    }

    //setters dos atributos


    public function setCodigo($codigoFront)
    {
        $this->codigo = $codigoFront;
    }


    public function setDescricao($descricaoFront)
    {
        $this->descricao = $descricaoFront;
    }


    public function setAndar($andarFront)
    {
        $this->andar = $andarFront;
    }


    public function setCapacidade($capacidadeFront)
    {
        $this->capacidade = $capacidadeFront;
    }


    public function setEstatus($estatusFront)
    {
        $this->estatus = $estatusFront;
    }

    public function inserir()
    {
        try {
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

            if (verificarParam($resultado, $lista) == 1) {
                //fazendo setters
                $this->setCodigo($resultado->codigo);
                $this->setDescricao($resultado->descricao);
                $this->setAndar($resultado->andar);
                $this->setCapacidade($resultado->capacidade);

                //validando se todos os dados foram enviados
                if (trim($this->getCodigo()) == '' || $this->getCodigo() == 0) {
                    $retorno = array(
                        'codigo' => 2,
                        'msg' => 'Código não informado.'
                    );
                } else if (trim($this->getDescricao()) == '') {
                    $retorno = array(
                        'codigo' => 3,
                        'msg' => 'Descrição não informada.'
                    );
                } else if (trim($this->getAndar()) == '' || $this->getAndar() == 0) {

                    $retorno = array(
                        'codigo' => 4,
                        'msg' => 'Andar não informado.'
                    );
                } else if (trim($this->getCapacidade()) == '' || $this->getCapacidade() == 0) {

                    $retorno = array(
                        'codigo' => 5,
                        'msg' => 'Capacidade não informado.'
                    );
                } else {
                    //realizando a instancia da Model
                    $this->load->model('M_sala');

                    //atribuindo $retorno recebe array com informações da validacao do acesso
                    $retorno = $this->M_sala->inserir(
                        $this->getCodigo(),
                        $this->getDescricao(),
                        $this->getAndar(),
                        $this->getCapacidade()
                    );
                }
            } else {
                $retorno = array(
                    'codigo' => 99,
                    'msg' => 'Os campos vindos do FrontEnd não representam o método de Inserção. Verifique.'
                );
            }
        } catch (Exception $e) {
            $retorno = array(
                'codigo' => 0,
                'msg' => 'Atenção: O seguinte erro aconteceu -> ',
                $e->getMessage()
            );
        }

        //retorno no formato JSON
        echo json_encode($retorno);
    }

    public function consultar()
    {
        /*
        código, descricao, tipo(ADM ou comum) recebidos via JSON e
        colocados em variaveis
        Possiveis retornos: 
        1 - dados consultados corretamente no banco
        6 - dados nao encontrados no banco
        */

        try {
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);

            //array com dados que deverão vir do FRONT
            $lista = array(
                "codigo" => '0',
                "andar" => '0',
                "capacidade" => '0'
            );
            if (verificarParam($resultado, $lista) == 1) {
                //fazendo os setters
                $this->setCodigo($resultado->codigo);
                $this->setDescricao($resultado->descricao);
                $this->setAndar($resultado->andar);
                $this->setCapacidade($resultado->capacidade);

                //instancia da model
                $this->load->model('M_sala');

                //atribuindo $retorno recebe array com info da consulta dos dados
                $retorno = $this->M_sala->consultar(
                    $this->getCodigo(),
                    $this->getDescricao(),
                    $this->getAndar(),
                    $this->getCapacidade()
                );
            } else {
                $retorno = array(
                    'codigo' => 99,
                    'msg' => 'Os campos vindos do FRONTEND não representam o método de Consulta. Verifique.'
                );
            }
        } catch (Exception $e) {
            $retorno = array(
                'codigo' => 0,
                'msg' => 'Atenção: O seguinte erro aconteceu -> ',
                $e->getMessage()
            );
        }
        //retorno no formato JSON
        echo json_encode($retorno);
    }
    public function alterar()
    {
        //codigo, descricao, andar recebidos via JSON e colocados em variaveis
        //possiveis retornos:
        //1 - dados aletrados corretamente
        //2 - codigo sala nao informado ou zerado
        //3 - descricao nao informada

        try {
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);

            //array com os dados que deverão vir do front
            $lista = array(
                "codigo" => '0',
                "descricao" => '0',
                "andar" => '0',
                "capacidade" => '0'
            );
            if (verificarParam($resultado, $lista) == 1) {
                //fazendo os setters
                $this->setCodigo($resultado->codigo);
                $this->setDescricao($resultado->descricao);
                $this->setAndar($resultado->andar);
                $this->setCapacidade($resultado->capacidade);

                //validacao para tipo de usuario que deverá ser ADM, comum ou vazio
                if (trim($this->getCodigo() == '')) {
                    $retorno = array(
                        'codigo' => 2,
                        'msg' => 'Código não informado'
                    );
                    //descricao, andar ou capacidade, pelo menos 1 deles precisa ser informado
                } elseif (
                    trim($this->getDescricao()) == '' && trim($this->getAndar()) == '' &&
                    trim($this->getCapacidade()) == ''
                ) {
                    $retorno = array(
                        'codigo' => 3,
                        'msg' => 'Pelo menos um parametro precisa ser passado para atualização'
                    );
                } else {
                    //relizo a instancia da model
                    $this->load->model('M_sala');

                    //atributo $retorno recebe array com informações da alteração dos dados
                    $retorno = $this->M_sala->alterar(
                        $this->getCodigo(),
                        $this->getDescricao(),
                        $this->getAndar(),
                        $this->getCapacidade()
                    );
                }
            } else {
                $retorno = array(
                    'codigo' => 99,
                    'msg' => 'Os campos vindos do FrontEnd não representam o método de alteração. Verifique.'
                );
            }
        } catch (Exception $e) {
            $retorno = array(
                'codigo' => 0,
                'msg' => 'ATENÇÃO: O seguinte erro aconteceu ->',
                $e->getMessage()
            );
        }
        //retorno formato JSON
        echo json_encode($retorno);
    }
    public function desativar()
    {
        /*
        Usuário recebido via JSON e colocado em variavel, retornos possiveis:
        1- sala desativada corretamente(banco)
        2- codigo da sala nao informado
        5- Houve algum problema na desativacao da sala
        6- dados nao encontrados
        */

        try {
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);

            //array com dados que deverao vir do front
            $lista = array(
                "codigo" => '0'
            );

            if (verificarParam($resultado, $lista) == 1) {
                $json - file_get_contents('php://input');
                $resultado = json_decode($json);

                //fazendo os setters
                $this->setCodigo($resultado->codigo);
                //validação para o usuario que nao devera ser branco
                if (trim($this->getCodigo() == '')) {
                    $retorno = array(
                        'codigo' => 2,
                        'msg' => 'Codigo nao informado'
                    );
                } else {
                    //realizando instancia da model
                    $this->load->model('M_Sala');

                    //atributo retorno recebe array com informações
                    $retorno = $this->M_Sala->dseativar($this->getCodigo());
                }
            } else {
                $retorno = array(
                    'codigo' => 99,
                    'ms' => 'Os campos vindos do FrontEnd não representam o método de alteração. Verifique.'
                );
            }
        } catch (Exception $e) {
            $retorno = array(
                'codigo' => 0,
                'msg' => 'ATENÇÃO: O seguinte erro aconteceu ->',
                $e->getMessage()
            );
        }
        //retorno no formato JSON
        echo json_encode($retorno);
    }
}
