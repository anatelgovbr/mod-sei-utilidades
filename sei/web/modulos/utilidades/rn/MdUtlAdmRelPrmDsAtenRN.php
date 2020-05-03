<?php

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmRelPrmDsAtenRN extends InfraRN{

    public function __construct(){
        parent::__construct();
    }

    protected function inicializarObjInfraIBanco() {
        return BancoSEI::getInstance();
    }

    private function validarNumIdMdUtlAdmParamDs(MdUtlAdmRelPrmDsAtenDTO $objMdUtlAdmRelPrmDsAtenDTO, InfraException $objInfraException){
        if (InfraString::isBolVazia($objMdUtlAdmRelPrmDsAtenDTO->getNumIdMdUtlAdmParamDs())){
            $objMdUtlAdmRelPrmDsAtenDTO->setNumIdMdUtlAdmParamDs(null);
        }
    }

    protected function cadastrarControlado(MdUtlAdmRelPrmDsAtenDTO $objMdUtlAdmRelPrmDsAtenDTO) {
        try{

            //Valida Permissao
            SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_rel_prm_ds_aten_cadastrar', __METHOD__, $objMdUtlAdmRelPrmDsAtenDTO);

            //Regras de Negocio
            $objInfraException = new InfraException();

            $this->validarNumIdMdUtlAdmParamDs($objMdUtlAdmRelPrmDsAtenDTO, $objInfraException);

            $objInfraException->lancarValidacoes();

            $objMdUtlAdmRelPrmDsAtenBD = new MdUtlAdmRelPrmDsAtenBD($this->getObjInfraIBanco());
            $ret = $objMdUtlAdmRelPrmDsAtenBD->cadastrar($objMdUtlAdmRelPrmDsAtenDTO);

            return $ret;

        }catch(Exception $e){
            throw new InfraException('Erro cadastrando .',$e);
        }
    }

    protected function alterarControlado(MdUtlAdmRelPrmDsAtenDTO $objMdUtlAdmRelPrmDsAtenDTO){
        try {

            //Valida Permissao
//            SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_rel_prm_ds_alterar', __METHOD__, $objMdUtlAdmRelPrmDsAtenDTO);

            //Regras de Negocio
            $objInfraException = new InfraException();

            if ($objMdUtlAdmRelPrmDsAtenDTO->isSetNumIdMdUtlAdmParamDs()){
                $this->validarNumIdMdUtlAdmParamDs($objMdUtlAdmRelPrmDsAtenDTO, $objInfraException);
            }

            $objInfraException->lancarValidacoes();

            $objMdUtlAdmRelPrmDsAtenBD = new MdUtlAdmRelPrmDsAtenBD($this->getObjInfraIBanco());
            $objMdUtlAdmRelPrmDsAtenBD->alterar($objMdUtlAdmRelPrmDsAtenDTO);

        }catch(Exception $e){
            throw new InfraException('Erro alterando .',$e);
        }
    }

    protected function excluirControlado($arrObjMdUtlAdmRelPrmDsAtenDTO){
        try {

            //Valida Permissao
//            SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_rel_prm_ds_excluir', __METHOD__, $arrObjMdUtlAdmRelPrmDsAtenDTO);

            $objMdUtlAdmRelPrmDsAtenBD = new MdUtlAdmRelPrmDsAtenBD($this->getObjInfraIBanco());
            for($i=0;$i<count($arrObjMdUtlAdmRelPrmDsAtenDTO);$i++){
                $obj = $objMdUtlAdmRelPrmDsAtenBD->excluir($arrObjMdUtlAdmRelPrmDsAtenDTO[$i]);

            }
        }catch(Exception $e){
            throw new InfraException('Erro excluindo .',$e);
        }
    }

    protected function consultarConectado(MdUtlAdmRelPrmDsAtenDTO $objMdUtlAdmRelPrmDsAtenDTO){
        try {

            //Valida Permissao
//            SessaoSEI::getInstance()->validarPermissao('md_utl_adm_rel_prm_ds_consultar');

            $objMdUtlAdmRelPrmDsAtenBD = new MdUtlAdmRelPrmDsAtenBD($this->getObjInfraIBanco());
            $ret = $objMdUtlAdmRelPrmDsAtenBD->consultar($objMdUtlAdmRelPrmDsAtenDTO);

            return $ret;

        }catch(Exception $e){
            throw new InfraException('Erro consultando .',$e);
        }
    }

    protected function listarConectado(MdUtlAdmRelPrmDsAtenDTO $objMdUtlAdmRelPrmDsAtenDTO) {
        try {

            //Valida Permissao
/*            SessaoSEI::getInstance()->validarPermissao('md_utl_adm_rel_prm_ds_listar');*/

            $objMdUtlAdmRelPrmDsAtenBD = new MdUtlAdmRelPrmDsAtenBD($this->getObjInfraIBanco());
            $ret = $objMdUtlAdmRelPrmDsAtenBD->listar($objMdUtlAdmRelPrmDsAtenDTO);

            return $ret;

        }catch(Exception $e){
            throw new InfraException('Erro listando .',$e);
        }
    }

    protected function contarConectado(MdUtlAdmRelPrmDsAtenDTO $objMdUtlAdmRelPrmDsAtenDTO){
        try {

            //Valida Permissao
//            SessaoSEI::getInstance()->validarPermissao('md_utl_adm_rel_prm_ds_listar');

            $objMdUtlAdmRelPrmDsAtenBD = new MdUtlAdmRelPrmDsAtenBD($this->getObjInfraIBanco());
            $ret = $objMdUtlAdmRelPrmDsAtenBD->contar($objMdUtlAdmRelPrmDsAtenDTO);

            return $ret;
        }catch(Exception $e){
            throw new InfraException('Erro contando .',$e);
        }
    }

    protected function montarArrStatusControlado($idMdUtlAdmPrmDs){
        $objMdUtlAdmRelPrmDsStatusDTO = new MdUtlAdmRelPrmDsAtenDTO();
        $objMdUtlAdmRelPrmDsStatusRN  = new MdUtlAdmRelPrmDsAtenRN();

        $objMdUtlAdmRelPrmDsStatusDTO->setNumIdMdUtlAdmParamDs($idMdUtlAdmPrmDs);
        $objMdUtlAdmRelPrmDsStatusDTO->retTodos();
        $objMdUtlAdmRelPrmDsAten = $objMdUtlAdmRelPrmDsStatusRN->listar($objMdUtlAdmRelPrmDsStatusDTO);

        $arrObjStatus = array(
            MdUtlControleDsmpRN::$AGUARDANDO_TRIAGEM => MdUtlControleDsmpRN::$STR_AGUARDANDO_TRIAGEM,
            MdUtlControleDsmpRN::$AGUARDANDO_ANALISE => MdUtlControleDsmpRN::$STR_AGUARDANDO_ANALISE,
            MdUtlControleDsmpRN::$AGUARDANDO_REVISAO => MdUtlControleDsmpRN::$STR_AGUARDANDO_REVISAO,
            MdUtlControleDsmpRN::$AGUARDANDO_CORRECAO_TRIAGEM => MdUtlControleDsmpRN::$STR_AGUARDANDO_CORRECAO_TRIAGEM,
            MdUtlControleDsmpRN::$AGUARDANDO_CORRECAO_ANALISE => MdUtlControleDsmpRN::$STR_AGUARDANDO_CORRECAO_ANALISE,
        );

        foreach ($objMdUtlAdmRelPrmDsAten as $key => $dadosStatus) {
            $status = array();
            $opt = array();
            $opcoes = '';

            $status[] = $dadosStatus->getNumStaAtendimentoDsmp();

            $status[] = $arrObjStatus[$dadosStatus->getNumStaAtendimentoDsmp()];

            $prioridade = $dadosStatus->getNumPrioridade();
            $idSelect = 'selPriStatus_'.$dadosStatus->getNumStaAtendimentoDsmp();

            for ($i = 0; $i < count($objMdUtlAdmRelPrmDsAten); $i++) {
                $opt[] = $i+1;
                $valor = $i+1;
                if($opt[$prioridade-1] == $valor) {
                    $opcoes .= "<option selected>".$valor."</option>";
                } else {
                    $opcoes .= "<option>".$valor."</option>";
                }
            }

            $selectOption = "<select style='width: 100%;' class='infraSelect selectStatus' id='" . $idSelect . "' name='" . $idSelect."' >$opcoes</select>";
            $status[] = $selectOption;

            $arrStatus[]= $status;

        }

        return array('itensTabela'=>$arrStatus,'qtdStatus'=>count($arrStatus));
    }

}
