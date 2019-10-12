<?php
/**
 * Created by PhpStorm.
 * User: jhon.carvalho
 * Date: 12/12/2018
 * Time: 11:16
 */
require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlRelAnaliseProdutoRN extends InfraRN{


    public function __construct(){
        parent::__construct();
    }

    protected function inicializarObjInfraIBanco(){
        return BancoSEI::getInstance();
    }

    protected function cadastrarControlado(MdUtlRelAnaliseProdutoDTO $objMdUtlRelAnaliseProdutoDTO) {
        try{

            SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_rel_analise_produto_cadastrar',__METHOD__, $objMdUtlRelAnaliseProdutoDTO);

            //Regras de Negocio
            $objInfraException = new InfraException();

            $objInfraException->lancarValidacoes();

            $objMdUtlRelAnaliseProdutoBD = new MdUtlRelAnaliseProdutoBD($this->getObjInfraIBanco());
            $ret = $objMdUtlRelAnaliseProdutoBD->cadastrar($objMdUtlRelAnaliseProdutoDTO);

            return $ret;

        }catch(Exception $e){
            throw new InfraException('Erro cadastrando .',$e);
        }
    }

    protected function alterarControlado(MdUtlRelAnaliseProdutoDTO $objMdUtlRelAnaliseProdutoDTO){
        try {

            SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_rel_analise_produto_alterar', __METHOD__, $objMdUtlRelAnaliseProdutoDTO);

            //Regras de Negocio
            $objInfraException = new InfraException();


            $objInfraException->lancarValidacoes();

            $objMdUtlRelAnaliseProdutoBD = new MdUtlRelAnaliseProdutoBD($this->getObjInfraIBanco());
            $objMdUtlRelAnaliseProdutoBD->alterar($objMdUtlRelAnaliseProdutoDTO);

        }catch(Exception $e){
            throw new InfraException('Erro alterando .',$e);
        }
    }

    protected function excluirControlado($arrObjMdUtlRelAnaliseProdutoDTO){
        try {

            SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_rel_analise_produto_excluir', __METHOD__, $arrObjMdUtlRelAnaliseProdutoDTO);

            $objMdUtlRelAnaliseProdutoBD = new MdUtlRelAnaliseProdutoBD($this->getObjInfraIBanco());
            for($i=0;$i<count($arrObjMdUtlRelAnaliseProdutoDTO);$i++){
                $objMdUtlRelAnaliseProdutoBD->excluir($arrObjMdUtlRelAnaliseProdutoDTO[$i]);
            }

        }catch(Exception $e){
            throw new InfraException('Erro excluindo .',$e);
        }
    }

    protected function consultarConectado(MdUtlRelAnaliseProdutoDTO $objMdUtlRelAnaliseProdutoDTO){
        try {

            SessaoSEI::getInstance()->validarPermissao('md_utl_rel_analise_produto_consultar');

            $objMdUtlRelAnaliseProdutoBD = new MdUtlRelAnaliseProdutoBD($this->getObjInfraIBanco());
            $ret = $objMdUtlRelAnaliseProdutoBD->consultar($objMdUtlRelAnaliseProdutoDTO);

            return $ret;
        }catch(Exception $e){
            throw new InfraException('Erro consultando .',$e);
        }
    }

    protected function listarConectado(MdUtlRelAnaliseProdutoDTO $objMdUtlRelAnaliseProdutoDTO) {
        try {

            if(!$objMdUtlRelAnaliseProdutoDTO->isSetAtributo('SinVerificarPermissao')){
                $objMdUtlRelAnaliseProdutoDTO->setStrSinVerificarPermissao('S');
            }

            if($objMdUtlRelAnaliseProdutoDTO->getStrSinVerificarPermissao() == 'S'){
                SessaoSEI::getInstance()->validarPermissao('md_utl_rel_analise_produto_listar');
            }

            $objMdUtlRelAnaliseProdutoBD = new MdUtlRelAnaliseProdutoBD($this->getObjInfraIBanco());
            $ret = $objMdUtlRelAnaliseProdutoBD->listar($objMdUtlRelAnaliseProdutoDTO);

            return $ret;

        }catch(Exception $e){
            throw new InfraException('Erro listando .',$e);
        }
    }

    protected function contarConectado(MdUtlRelAnaliseProdutoDTO $objMdUtlRelAnaliseProdutoDTO){
        try {

            if(!$objMdUtlRelAnaliseProdutoDTO->isSetAtributo('SinVerificarPermissao')){
                $objMdUtlRelAnaliseProdutoDTO->setStrSinVerificarPermissao('S');
            }

            if($objMdUtlRelAnaliseProdutoDTO->getStrSinVerificarPermissao() == 'S'){
                SessaoSEI::getInstance()->validarPermissao('md_utl_rel_analise_produto_listar');
            }

            $objMdUtlRelAnaliseProdutoBD = new MdUtlRelAnaliseProdutoBD($this->getObjInfraIBanco());
            $ret = $objMdUtlRelAnaliseProdutoBD->contar($objMdUtlRelAnaliseProdutoDTO);

            return $ret;
        }catch(Exception $e){
            throw new InfraException('Erro contando .',$e);
        }
    }

    protected function preencherProtocoloFormatadoDocControlado(){
        $objMdUtlRelAnaliseDTO = new MdUtlRelAnaliseProdutoDTO();
        $objMdUtlRelAnaliseDTO->setDblIdDocumento(null, InfraDTO::$OPER_DIFERENTE);
        $objMdUtlRelAnaliseDTO->retStrDocumentoFormatado();
        $objMdUtlRelAnaliseDTO->retDblIdDocumento();
        $objMdUtlRelAnaliseDTO->retNumIdMdUtlRelAnaliseProduto();

        $count  = $this->contar($objMdUtlRelAnaliseDTO);

        if($count > 0) {
            $arrObjs = $this->listar($objMdUtlRelAnaliseDTO);

            foreach ($arrObjs as $objDTO) {
                $objDTO->setStrValor($objDTO->getStrDocumentoFormatado());
                $this->alterar($objDTO);
            }
        }
    }

    protected function getArrObjPorIdsConectado($arrIds){
        $objMdUtlRelAnaliseProdutoDTO = new MdUtlRelAnaliseProdutoDTO();
        $objMdUtlRelAnaliseProdutoDTO->setNumIdMdUtlRelAnaliseProduto($arrIds, InfraDTO::$OPER_IN);
        $objMdUtlRelAnaliseProdutoDTO->retTodos(true);

        return $this->listar($objMdUtlRelAnaliseProdutoDTO);
    }

}