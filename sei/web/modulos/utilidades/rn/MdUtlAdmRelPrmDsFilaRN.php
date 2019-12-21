<?php

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmRelPrmDsFilaRN extends InfraRN{

    public function __construct(){
        parent::__construct();
    }

    protected function inicializarObjInfraIBanco() {
        return BancoSEI::getInstance();
    }

    private function validarNumIdMdUtlAdmParamDs(MdUtlAdmRelPrmDsFilaDTO $objMdUtlAdmRelPrmDsFilaDTO, InfraException $objInfraException){
        if (InfraString::isBolVazia($objMdUtlAdmRelPrmDsFilaDTO->getNumIdMdUtlAdmParamDs())){
            $objMdUtlAdmRelPrmDsFilaDTO->setNumIdMdUtlAdmParamDs(null);
        }
    }

    protected function cadastrarControlado(MdUtlAdmRelPrmDsFilaDTO $objMdUtlAdmRelPrmDsFilaDTO) {
        try{

            //Valida Permissao
            SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_rel_prm_ds_fila_cadastrar', __METHOD__, $objMdUtlAdmRelPrmDsFilaDTO);

            //Regras de Negocio
            $objInfraException = new InfraException();

            $this->validarNumIdMdUtlAdmParamDs($objMdUtlAdmRelPrmDsFilaDTO, $objInfraException);

            $objInfraException->lancarValidacoes();

            $objMdUtlAdmRelPrmDsFilaBD = new MdUtlAdmRelPrmDsFilaBD($this->getObjInfraIBanco());
            $ret = $objMdUtlAdmRelPrmDsFilaBD->cadastrar($objMdUtlAdmRelPrmDsFilaDTO);

            return $ret;

        }catch(Exception $e){
            throw new InfraException('Erro cadastrando .',$e);
        }
    }

    protected function alterarControlado(MdUtlAdmRelPrmDsFilaDTO $objMdUtlAdmRelPrmDsFilaDTO){
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_rel_prm_ds_alterar', __METHOD__, $objMdUtlAdmRelPrmDsFilaDTO);

            //Regras de Negocio
            $objInfraException = new InfraException();

            if ($objMdUtlAdmRelPrmDsFilaDTO->isSetNumIdMdUtlAdmParamDs()){
                $this->validarNumIdMdUtlAdmParamDs($objMdUtlAdmRelPrmDsFilaDTO, $objInfraException);
            }

            $objInfraException->lancarValidacoes();

            $objMdUtlAdmRelPrmDsFilaBD = new MdUtlAdmRelPrmDsFilaBD($this->getObjInfraIBanco());
            $objMdUtlAdmRelPrmDsFilaBD->alterar($objMdUtlAdmRelPrmDsFilaDTO);

        }catch(Exception $e){
            throw new InfraException('Erro alterando .',$e);
        }
    }

    protected function excluirControlado($arrObjMdUtlAdmRelPrmDsFilaDTO){
        try {

            //Valida Permissao
//            SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_rel_prm_ds_excluir', __METHOD__, $arrObjMdUtlAdmRelPrmDsFilaDTO);

            $objMdUtlAdmRelPrmDsFilaBD = new MdUtlAdmRelPrmDsFilaBD($this->getObjInfraIBanco());
            for($i=0;$i<count($arrObjMdUtlAdmRelPrmDsFilaDTO);$i++){
                $obj = $objMdUtlAdmRelPrmDsFilaBD->excluir($arrObjMdUtlAdmRelPrmDsFilaDTO[$i]);

            }
        }catch(Exception $e){
            throw new InfraException('Erro excluindo .',$e);
        }
    }

    protected function consultarConectado(MdUtlAdmRelPrmDsFilaDTO $objMdUtlAdmRelPrmDsFilaDTO){
        try {

            //Valida Permissao
//            SessaoSEI::getInstance()->validarPermissao('md_utl_adm_rel_prm_ds_fila_consultar');

            $objMdUtlAdmRelPrmDsFilaBD = new MdUtlAdmRelPrmDsFilaBD($this->getObjInfraIBanco());
            $ret = $objMdUtlAdmRelPrmDsFilaBD->consultar($objMdUtlAdmRelPrmDsFilaDTO);

            return $ret;

        }catch(Exception $e){
            throw new InfraException('Erro consultando .',$e);
        }
    }

    protected function listarConectado(MdUtlAdmRelPrmDsFilaDTO $objMdUtlAdmRelPrmDsFilaDTO) {
        try {

            //Valida Permissao
/*            SessaoSEI::getInstance()->validarPermissao('md_utl_adm_rel_prm_ds_listar');*/

            $objMdUtlAdmRelPrmDsFilaBD = new MdUtlAdmRelPrmDsFilaBD($this->getObjInfraIBanco());
            $ret = $objMdUtlAdmRelPrmDsFilaBD->listar($objMdUtlAdmRelPrmDsFilaDTO);

            return $ret;

        }catch(Exception $e){
            throw new InfraException('Erro listando .',$e);
        }
    }

    protected function contarConectado(MdUtlAdmRelPrmDsFilaDTO $objMdUtlAdmRelPrmDsFilaDTO){
        try {

            //Valida Permissao
           // SessaoSEI::getInstance()->validarPermissao('md_utl_adm_rel_prm_ds_listar');

            $objMdUtlAdmRelPrmDsFilaBD = new MdUtlAdmRelPrmDsFilaBD($this->getObjInfraIBanco());
            $ret = $objMdUtlAdmRelPrmDsFilaBD->contar($objMdUtlAdmRelPrmDsFilaDTO);

            return $ret;
        }catch(Exception $e){
            throw new InfraException('Erro contando .',$e);
        }
    }

    protected function montarArrFilaControlado($idMdUtlAdmPrmDs){
        $objMdUtlAdmRelPrmDsFilaDTO = new MdUtlAdmRelPrmDsFilaDTO();
        $objMdUtlAdmRelPrmDsFilaRN  = new MdUtlAdmRelPrmDsFilaRN();
        $objMdUtlAdmFilaDTO         = new MdUtlAdmFilaDTO();
        $objMdUtlAdmFilaRN          = new MdUtlAdmFilaRN();

        $objMdUtlAdmRelPrmDsFilaDTO->setNumIdMdUtlAdmParamDs($idMdUtlAdmPrmDs);
        $objMdUtlAdmRelPrmDsFilaDTO->retTodos();
        $arrObjMdUtlAdmRelPrmDsFila = $objMdUtlAdmRelPrmDsFilaRN->listar($objMdUtlAdmRelPrmDsFilaDTO);

        foreach ($arrObjMdUtlAdmRelPrmDsFila as $key => $dadosFila) {
            $fila = array();
            $opt = array();
            $opcoes = '';

            $objMdUtlAdmFilaDTO->setNumIdMdUtlAdmFila($dadosFila->getNumIdMdUtlAdmFila());
            $objMdUtlAdmFilaDTO->retTodos();
            $objMdUtlAdmFilaDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_DESC);
            $objMdUtlAdmFila = $objMdUtlAdmFilaRN->consultar($objMdUtlAdmFilaDTO);

            $fila[] = $dadosFila->getNumIdMdUtlAdmFila();
            $fila[] = $objMdUtlAdmFila->getStrNome().' - '.$objMdUtlAdmFila->getStrDescricao();

            $prioridade = $dadosFila->getNumPrioridade();
            $idSelect = 'selPriFila_'.$dadosFila->getNumIdMdUtlAdmFila();

            for ($i = 0; $i < count($arrObjMdUtlAdmRelPrmDsFila); $i++) {
                $opt[] = $i+1;
                $valor = $i+1;
                if($opt[$prioridade-1] == $valor) {
                    $opcoes .= "<option selected>".$valor."</option>";
                } else {
                    $opcoes .= "<option>".$valor."</option>";
                }
            }

            $selectOption = '<select style="width: 100%;" class="infraSelect selectFila" id="' . $idSelect . '" name="' . $idSelect.'" >'.$opcoes.'</select>';
            $fila[] = $selectOption;

            $arrFila[]= $fila;
        }

        return array('itensTabela'=>$arrFila,'qtdFila'=>count($arrFila));
    }
}
