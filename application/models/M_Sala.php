<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_sala extends CI_Model{
public function inserir($codigo, $descricao, $andar, $capacidade)
{
try{
    //verifico se a sala ja esta cadastrada
    $retornoConsulta = $this->consultaSala($codigo);
    if($retornoConsulta['codigo'] !=1 &&
    $retornoConsulta['codigo'] !=7){
        //Query de insercao de dados
        $this->db->query("insert into tbl_sala(codigo, descricao, andar, capacidade)
        values ($codigo, '$descricao', $andar, $capacidade)");
   
        //verificar insercao
        if($this->db->affected_rows() > 0){
            $dados = array(
                'codigo' => 1,
                'msg' => 'Sala cadastrada com sucesso'
            );
        }else{
            $dados = array(
                'codigo' => 6,
                'msg' => 'Houve algum problema com a insercao na tabela de salas'
            );
        }
    }else{$dados = array('codigo'=> $retornoConsulta['codigo'],
                        'msg'=> $retornoConsulta['msg']
    );
}
}catch(Exception $e){
    $dados = array(
        'codigo' => 00,
        'msg' => 'ATENÇÃO: O seguinte erro aconteceu ->',
        $e->getMessage(),
        "\n"
    );
}
    //envia o array dados com as informaç~eos tratadas acimea 
    return $dados;
}
private function consultaSala($codigo){
    try{
        //query para consultar dados de acordo com parametros passados
        $sql = "select * from tbl_sala where codigo = $codigo ";
        $retornoSala = $this->db->query($sql);

        //verificar se a consulta ocorreu com sucesso
        if($retornoSala->num_rows() > 0){
            $linha = $retornoSala->row();
            if(trim($linha->estatus) =="D"){
                $dados = array(
                    'codigo' => 7,
                    'msg' => 'Sala desativada no sistema, caso precise reativar, fale com o administardor.'
                );
            }else{
                $dados = array(
                    'codigo' => 8,
                    'msg' => 'Sala já cadastrada no sistema'
                );
            }
        }else{
            $dados = array(
                'codigo' => 6,
                'msg' => 'Sala não encontrada'
            );
        }
    }catch(Exception $e){
        $dados = array(
            'codigo' => 00,
            'msg' => 'ATENÇÃO: O seguinte erro aconteceu ->',
            $e->getMessage(),
            "\n"
        );
    }
   //envia o array dados com as informaç~eos tratadas acimea 
   return $dados;
}
public function consultar($codigo, $descricao, $andar, $capacidade){
    try{
        //query para  consultar dados
        $sql = "select *from tbl-sala where estatus = '' ";
        if(trim($codigo) != ''){
            $sql = $sql . "and codigo = '$codigo'";
        }

        if(trim($andar) != ''){
            $sql = $sql . "and andar = '$andar' ";
        }

        if(trim($descricao) != ''){
            $sql = $sql . "and descricao like = '%$descricao%' ";
        }

        if(trim($capacidade) != ''){
            $sql = $sql . "and capacidade = '$capacidade'";
        }

          $sql = $sql . "Order by codigo";
          $retorno = $this->db->query($sql);
          //verificar se a consulta ocorreu com sucesso
          if($retorno->num_rows() > 0){
            $dados = array(
                'codigo' => 1,
                'msg' => 'Consulta efetuada com sucesso',
                'dados' => $retorno->result()
            );
          }else{
            $dados = array(
                'codigo' => 6,
                'msg' => 'Sala não encontrada'
               
            );
          }
    }catch(Exception $e){
        $dados = array(
            'codigo' => 00,
            'msg' => 'ATENÇÃO: O seguinte erro aconteceu ->',
            $e->getMessage(),
            "\n"
        );
    }
 //envia o array dados com as informaç~eos tratadas acimea 
 return $dados;
}
public function alterar($codigo, $descricao, $andar, $capacidade){

    try{
        //verificando sala cadastrada
        $retornoConsulta = $this->consultaSala($codigo);
        if($retornoConsulta['codigo'] == 8){
            //inicio da query para att
            $query = "update tbl_sala set ";

            //compara itens
            if($descricao !== ''){
                $query .= "descricao = '$descricao',";
            }
            if($andar !== ''){
                $query .= "andar = '$andar',";
            }
            if($capacidade !== ''){
                $query .= "capacidade = '$capacidade',";
            }
            
            //terminando concatenacao da query
            $queryFinal = rtrim($query, ", ") . "where codigo = $codigo";

            //executando a query de att dos dados
            $this->db->query($queryFinal);

            //verificar se att teve sucesso

            if($this->db->affected_rows() > 0){

                $dados = array(
                    'codigo' => 1,
                    'msg' => 'Sala atualizada corretamente'
                );
            }else {
                $dados = array(
                    'codigo' => 6,
                    'msg' => 'Houve algum problema na atualização na tabela de sala'
                );
            }

        }else {
            $dados = array(
                'codigo' => 5,
                'msg' => 'Sala não cadastrada no sistema'
            );
        }
    }catch(Exception $e){
        $dados = array(
            'codigo' => 00,
            'msg' => 'ATENÇÃO: O seguinte erro aconteceu ->',
            $e->getMessage(),
            "\n"
        );
    }
   //envia o array dados com as informaç~eos tratadas acimea 
 return $dados;
}
public function desativar($codigo, $descricao, $andar, $capacidade){

    try{
        //verificando se a sala ja esta cadatsrada
        $retornoConsulta = $this->consultaSala($codigo);

        if($retornoConsulta['codigo'] == 8){
            //query de atualização dos dados
            $this->db->query("update tbl_sala set estatus = 'D'
                            where codigo = $codigo");

            //verificar se a atualização
            if($this->db->affected_rows() > 0){
                $dados = array(
                    'codigo' => 1,
                    'msg' => 'Sala DESATIVADA corretamente',
                );
            } else {
                $dados = array(
                    'codigo' => 5,
                    'msg' => 'Houve algum problema na DESATIVAÇÃO da Sala',
                );
            }
        }else {
            $dados = array(
                'codigo' => 6,
                'msg' => 'Sala não cadastrada no Sistema, não pode excluir',
            );
        }
    }catch(Exception $e){
        $dados = array(
            'codigo' => 00,
            'msg' => 'ATENÇÃO: O seguinte erro aconteceu ->',
            $e->getMessage(),
            "\n"
        );
    }
    //envia o array
    return $dados;
}
}

