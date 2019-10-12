<?php

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmHistPrmGrUsuRN extends InfraRN {

    public function __construct(){
        parent::__construct();
    }

    protected function inicializarObjInfraIBanco(){
        return BancoSEI::getInstance();
    }

    protected function cadastrarControlado(MdUtlAdmHistPrmGrUsuDTO $objMdUtlAdmHistPrmGrDTO) {
        try{

            //Valida Permissao
            SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_hist_prm_gr_cadastrar',__METHOD__,$objMdUtlAdmHistPrmGrDTO);

            //Regras de Negocio
            $objInfraException = new InfraException();

            $objInfraException->lancarValidacoes();

            $objMdUtlAdmPrmGrBD = new MdUtlAdmHistPrmGrUsuBD($this->getObjInfraIBanco());
            $ret = $objMdUtlAdmPrmGrBD->cadastrar($objMdUtlAdmHistPrmGrDTO);

            //Auditoria

            return $ret;

        }catch(Exception $e){
            throw new InfraException('Erro cadastrando Histórico de Relacionamento e Parâmetro.',$e);
        }
    }

    protected function alterarControlado(MdUtlAdmHistPrmGrUsuDTO $objMdUtlAdmHistPrmGrDTO){
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_hist_prm_gr_alterar',__METHOD__, $objMdUtlAdmHistPrmGrDTO);

            //Regras de Negocio
            $objInfraException = new InfraException();
            $objInfraException->lancarValidacoes();

            $objMdUtlAdmPrmGrBD = new MdUtlAdmHistPrmGrUsuBD($this->getObjInfraIBanco());
            $objMdUtlAdmPrmGrBD->alterar($objMdUtlAdmHistPrmGrDTO);

            //Auditoria

        }catch(Exception $e){
            throw new InfraException('Erro alterando Histórico de Relacionamento e Parâmetro.',$e);
        }
    }

    protected function contarConectado(MdUtlAdmHistPrmGrUsuDTO $objMdUtlAdmHistPrmGrDTO){
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_utl_adm_hist_prm_gr_consultar');

            $objMdUtlHistAdmPrmGrBD = new MdUtlAdmHistPrmGrUsuBD($this->getObjInfraIBanco());
            $ret = $objMdUtlHistAdmPrmGrBD->contar($objMdUtlAdmHistPrmGrDTO);

            //Auditoria

            return $ret;
        }catch(Exception $e){
            throw new InfraException('Erro contando os registros do Histórico de Relacionamento e Parâmetro.',$e);
        }
    }

    protected function listarConectado(MdUtlAdmHistPrmGrUsuDTO $objMdUtlAdmHistPrmGrDTO){
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_utl_adm_hist_prm_gr_listar');

            $objMdUtlHistAdmPrmGrBD = new MdUtlAdmHistPrmGrUsuBD($this->getObjInfraIBanco());
            $ret = $objMdUtlHistAdmPrmGrBD->listar($objMdUtlAdmHistPrmGrDTO);

            //Auditoria

            return $ret;
        }catch(Exception $e){
            throw new InfraException('Erro listando os registros do Histórico de Relacionamento e Parâmetro.',$e);
        }
    }

    protected function clonarObjParametroParaHistoricoConectado($objDTOAtual){
        $objHistoricoDTO = new MdUtlAdmHistPrmGrUsuDTO();

        foreach($objDTOAtual->getArrAtributos() as $attr){
            $strValor    = $attr[InfraDTO::$POS_ATRIBUTO_VALOR];
            $strAtributo = $attr[InfraDTO::$FLAG_SET];
            if($strAtributo == 'IdMdUtlAdmPrmGrUsu'){
                $objHistoricoDTO->set('IdMdUtlAdmHistPrmGrUsu', null);
            }else {
                $objHistoricoDTO->set($strAtributo, $strValor);
            }
        }

        return $objHistoricoDTO;
    }


    protected function migrarDadosExistentesParamHistoricoControlado(){
        $objMdUtlPrmGrUsuRN = new MdUtlAdmPrmGrUsuRN();
        $objMdUtlHistPrmGrUsuRN = new MdUtlAdmHistPrmGrUsuDTO();
        $dthSolicitada = '31-05-2019 00:01:00';
        $objMdUtlPrmGrUsuDTO = new MdUtlAdmPrmGrUsuDTO();
        $objMdUtlPrmGrUsuDTO->retTodos();

        $isPossuiRegistro  = $objMdUtlPrmGrUsuRN->contar($objMdUtlPrmGrUsuDTO) > 0;

        if ($isPossuiRegistro) {
            $objUsuarioRN = new MdUtlUsuarioRN();
            $objUsuarioDTO = $objUsuarioRN->getObjUsuarioUtilidades();

            $arrObjs = $objMdUtlPrmGrUsuRN->listar($objMdUtlPrmGrUsuDTO);

            foreach ($arrObjs as $objDTO) {

                $objMdUtlAdmHistPrmGrUsuDTO = $this->clonarObjParametroParaHistorico($objDTO);
                $objMdUtlAdmHistPrmGrUsuDTO->setDthInicial($dthSolicitada);
                $objMdUtlAdmHistPrmGrUsuDTO->setNumIdUsuarioAtual($objUsuarioDTO->getNumIdUsuario());
                $this->cadastrar($objMdUtlAdmHistPrmGrUsuDTO);
            }
        }


    }




}