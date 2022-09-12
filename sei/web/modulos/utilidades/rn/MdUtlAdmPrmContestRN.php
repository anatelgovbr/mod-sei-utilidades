<?php

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmPrmContestRN extends InfraRN
{
    //Reprova��o T�cita na Contesta��o de Revis�o
    public static $RETORNO_SIM = 'S';
    public static $RETORNO_NAO = 'N';
    public static $STR_SIM = 'Sim';
    public static $STR_NAO = 'N�o';

    public function __construct(){
        parent::__construct();
    }

    protected function inicializarObjInfraIBanco(){
        return BancoSEI::getInstance();
    }

    protected function cadastrarControlado(MdUtlAdmPrmContestDTO $objMdUtlAdmPrmContestDTO) {
        try{
            SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_prm_contest_cadastrar', __METHOD__, $objMdUtlAdmPrmContestDTO);
            $objMdUtlAdmPrmContestBD = new MdUtlAdmPrmContestBD($this->getObjInfraIBanco());
            return $objMdUtlAdmPrmContestBD->cadastrar($objMdUtlAdmPrmContestDTO);

        }catch(Exception $e){
            throw new InfraException('Erro cadastrando Par�metro de Contesta��o.',$e);
        }
    }
    protected function consultarConectado(MdUtlAdmPrmContestDTO $objMdUtlAdmPrmContestDTO){
        try {
            SessaoSEI::getInstance()->validarPermissao('md_utl_adm_prm_contest_consultar');
            $objMdUtlAdmPrmContestBD = new MdUtlAdmPrmContestBD($this->getObjInfraIBanco());
            return $objMdUtlAdmPrmContestBD->consultar($objMdUtlAdmPrmContestDTO);
        }catch(Exception $e){
            throw new InfraException('Erro consultando Par�metro de Contesta��o.',$e);
        }
    }

    protected function alterarControlado(MdUtlAdmPrmContestDTO $objMdUtlAdmPrmContestDTO){
        try {
            SessaoSEI::getInstance()->validarPermissao('md_utl_adm_prm_contest_consultar');
            $objMdUtlAdmPrmContestBD = new MdUtlAdmPrmContestBD($this->getObjInfraIBanco());
            return $objMdUtlAdmPrmContestBD->alterar($objMdUtlAdmPrmContestDTO);
        }catch(Exception $e){
            throw new InfraException('Erro alterando Par�metro de Contesta��o.',$e);
        }
    }

    protected function listarConectado(MdUtlAdmPrmContestDTO $objMdUtlAdmPrmContestDTO) {
        try {

            $objMdUtlAdmPrmContestBD = new MdUtlAdmPrmContestBD($this->getObjInfraIBanco());
           return $objMdUtlAdmPrmContestBD->listar($objMdUtlAdmPrmContestDTO);


        }catch(Exception $e){
            throw new InfraException('Erro listando Par�metro de Contesta��o.',$e);
        }
    }

    protected function contarConectado(MdUtlAdmPrmContestDTO $objMdUtlAdmPrmContestDTO){
        try {
            //SessaoSEI::getInstance()->validarPermissao('md_utl_adm_just_contest_listar');

            $objMdUtlAdmJustContestBD = new MdUtlAdmJustContestBD($this->getObjInfraIBanco());
            return $objMdUtlAdmJustContestBD->contar($objMdUtlAdmPrmContestDTO);

        }catch(Exception $e){
            throw new InfraException('Erro contando Par�metro de Contesta��o.',$e);
        }
    }

    protected function excluirControlado($arrObjMdUtlAdmPrmContestDTO){
        try {
            $objMdUtlAdmPrmContestBD = new MdUtlAdmJustContestBD($this->getObjInfraIBanco());
            for($i=0;$i<count($arrObjMdUtlAdmPrmContestDTO);$i++){
                $objMdUtlAdmPrmContestBD->excluir($arrObjMdUtlAdmPrmContestDTO[$i]);
            }
        }catch(Exception $e){
            throw new InfraException('Erro excluindo Par�metro de Contesta��o.',$e);
        }
    }
}