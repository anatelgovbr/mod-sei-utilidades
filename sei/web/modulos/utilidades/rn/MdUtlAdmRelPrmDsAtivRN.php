<?php

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmRelPrmDsAtivRN extends InfraRN{

    public function __construct(){
        parent::__construct();
    }

    protected function inicializarObjInfraIBanco() {
        return BancoSEI::getInstance();
    }

    private function validarNumIdMdUtlAdmParamDs(MdUtlAdmRelPrmDsAtivDTO $objMdUtlAdmRelPrmDsAtivDTO, InfraException $objInfraException){
        if (InfraString::isBolVazia($objMdUtlAdmRelPrmDsAtivDTO->getNumIdMdUtlAdmParamDs())){
            $objMdUtlAdmRelPrmDsAtivDTO->setNumIdMdUtlAdmParamDs(null);
        }
    }

    protected function cadastrarControlado(MdUtlAdmRelPrmDsAtivDTO $objMdUtlAdmRelPrmDsAtivDTO) {
        try{

            //Valida Permissao
            SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_rel_prm_ds_ativ_cadastrar', __METHOD__, $objMdUtlAdmRelPrmDsAtivDTO);

            //Regras de Negocio
            $objInfraException = new InfraException();

            $this->validarNumIdMdUtlAdmParamDs($objMdUtlAdmRelPrmDsAtivDTO, $objInfraException);

            $objInfraException->lancarValidacoes();

            $objMdUtlAdmRelPrmDsAtivBD = new MdUtlAdmRelPrmDsAtivBD($this->getObjInfraIBanco());
            $ret = $objMdUtlAdmRelPrmDsAtivBD->cadastrar($objMdUtlAdmRelPrmDsAtivDTO);

            return $ret;

        }catch(Exception $e){
            throw new InfraException('Erro cadastrando .',$e);
        }
    }

    protected function alterarControlado(MdUtlAdmRelPrmDsAtivDTO $objMdUtlAdmRelPrmDsAtivDTO){
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_rel_prm_ds_alterar', __METHOD__, $objMdUtlAdmRelPrmDsAtivDTO);

            //Regras de Negocio
            $objInfraException = new InfraException();

            if ($objMdUtlAdmRelPrmDsAtivDTO->isSetNumIdMdUtlAdmParamDs()){
                $this->validarNumIdMdUtlAdmParamDs($objMdUtlAdmRelPrmDsAtivDTO, $objInfraException);
            }

            $objInfraException->lancarValidacoes();

            $objMdUtlAdmRelPrmDsAtivBD = new MdUtlAdmRelPrmDsAtivBD($this->getObjInfraIBanco());
            $objMdUtlAdmRelPrmDsAtivBD->alterar($objMdUtlAdmRelPrmDsAtivDTO);

        }catch(Exception $e){
            throw new InfraException('Erro alterando .',$e);
        }
    }

    protected function excluirControlado($arrObjMdUtlAdmRelPrmDsAtivDTO){
        try {

            //Valida Permissao
//            SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_rel_prm_ds_excluir', __METHOD__, $arrObjMdUtlAdmRelPrmDsAtivDTO);

            $objMdUtlAdmRelPrmDsAtivBD = new MdUtlAdmRelPrmDsAtivBD($this->getObjInfraIBanco());
            for($i=0;$i<count($arrObjMdUtlAdmRelPrmDsAtivDTO);$i++){
                $obj = $objMdUtlAdmRelPrmDsAtivBD->excluir($arrObjMdUtlAdmRelPrmDsAtivDTO[$i]);

            }
        }catch(Exception $e){
            throw new InfraException('Erro excluindo .',$e);
        }
    }

    protected function consultarConectado(MdUtlAdmRelPrmDsAtivDTO $objMdUtlAdmRelPrmDsAtivDTO){
        try {

            //Valida Permissao
//            SessaoSEI::getInstance()->validarPermissao('md_utl_adm_rel_prm_ds_consultar');

            $objMdUtlAdmRelPrmDsAtivBD = new MdUtlAdmRelPrmDsAtivBD($this->getObjInfraIBanco());
            $ret = $objMdUtlAdmRelPrmDsAtivBD->consultar($objMdUtlAdmRelPrmDsAtivDTO);

            return $ret;

        }catch(Exception $e){
            throw new InfraException('Erro consultando .',$e);
        }
    }

    protected function listarConectado(MdUtlAdmRelPrmDsAtivDTO $objMdUtlAdmRelPrmDsAtivDTO) {
        try {

            //Valida Permissao
//            SessaoSEI::getInstance()->validarPermissao('md_utl_adm_rel_prm_ds_listar');

            $objMdUtlAdmRelPrmDsAtivBD = new MdUtlAdmRelPrmDsAtivBD($this->getObjInfraIBanco());
            $ret = $objMdUtlAdmRelPrmDsAtivBD->listar($objMdUtlAdmRelPrmDsAtivDTO);

            return $ret;

        }catch(Exception $e){
            throw new InfraException('Erro listando .',$e);
        }
    }

    protected function contarConectado(MdUtlAdmRelPrmDsAtivDTO $objMdUtlAdmRelPrmDsAtivDTO){
        try {

            //Valida Permissao
//            SessaoSEI::getInstance()->validarPermissao('md_utl_adm_rel_prm_ds_listar');

            $objMdUtlAdmRelPrmDsAtivBD = new MdUtlAdmRelPrmDsAtivBD($this->getObjInfraIBanco());
            $ret = $objMdUtlAdmRelPrmDsAtivBD->contar($objMdUtlAdmRelPrmDsAtivDTO);

            return $ret;
        }catch(Exception $e){
            throw new InfraException('Erro contando .',$e);
        }
    }

    protected function montarArrAtividadeControlado($idMdUtlAdmPrmDs){
        $objMdUtlAdmRelPrmDsAtividadeDTO = new MdUtlAdmRelPrmDsAtivDTO();
        $objMdUtlAdmRelPrmDsAtividadeRN  = new MdUtlAdmRelPrmDsAtivRN();
        $objMdUtlAdmAtividadeDTO         = new MdUtlAdmAtividadeDTO();
        $objMdUtlAdmAtividadeRN          = new MdUtlAdmAtividadeRN();

        $objMdUtlAdmRelPrmDsAtividadeDTO->setNumIdMdUtlAdmParamDs($idMdUtlAdmPrmDs);
        $objMdUtlAdmRelPrmDsAtividadeDTO->retTodos();
        $objMdUtlAdmRelPrmDsAtividadeDTO->setOrdNumPrioridade(InfraDTO::$TIPO_ORDENACAO_DESC);
        $objMdUtlAdmRelPrmDsAtividade = $objMdUtlAdmRelPrmDsAtividadeRN->listar($objMdUtlAdmRelPrmDsAtividadeDTO);

        foreach ($objMdUtlAdmRelPrmDsAtividade as $key => $dadosAtividade) {
            $atividade = array();
            $opt = array();
            $opcoes = '';

            $objMdUtlAdmAtividadeDTO->setNumIdMdUtlAdmAtividade($dadosAtividade->getNumIdMdUtlAdmAtividade());
            $objMdUtlAdmAtividadeDTO->retTodos();
            $objMdUtlAdmAtividadeDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);
            $objMdUtlAdmAtividade = $objMdUtlAdmAtividadeRN->consultar($objMdUtlAdmAtividadeDTO);

            $atividade[] = $dadosAtividade->getNumIdMdUtlAdmAtividade();
            $atividade[] = $objMdUtlAdmAtividade->getStrNome() . ' - ' . $objMdUtlAdmAtividade->getStrDescricao() . ' - ' . MdUtlAdmAtividadeRN::$ARR_COMPLEXIDADE[ $objMdUtlAdmAtividade->getNumComplexidade() ];

            $prioridade = $dadosAtividade->getNumPrioridade();
            $idSelect = 'selPriAtividade_'.$dadosAtividade->getNumIdMdUtlAdmAtividade();

            for ($i = 0; $i < count($objMdUtlAdmRelPrmDsAtividade); $i++) {
                $opt[] = $i+1;
                $valor = $i+1;
                if($opt[$prioridade-1] == $valor) {
                    $opcoes .= "<option selected>".$valor."</option>";
                } else {
                    $opcoes .= "<option>".$valor."</option>";
                }
            }

            $selectOption = "<select style='width: 100%;' class='infraSelect selectAtividade' id='" . $idSelect . "' name='" . $idSelect."' >$opcoes</select>";
            $atividade[] = $selectOption;

            $arrAtividade[]= $atividade;
        }

        return array('itensTabela'=>$arrAtividade,'qtdAtividade'=>count($arrAtividade));
    }

    protected function montarArrAtiviPrioridadeControlado($idMdUtlAdmPrmDs){
        $objMdUtlAdmRelPrmDsAtividadeDTO = new MdUtlAdmRelPrmDsAtivDTO();
        $objMdUtlAdmRelPrmDsAtividadeRN  = new MdUtlAdmRelPrmDsAtivRN();
        $objMdUtlAdmAtividadeDTO         = new MdUtlAdmAtividadeDTO();
        $objMdUtlAdmAtividadeRN          = new MdUtlAdmAtividadeRN();

        $objMdUtlAdmRelPrmDsAtividadeDTO->setNumIdMdUtlAdmParamDs($idMdUtlAdmPrmDs);
        $objMdUtlAdmRelPrmDsAtividadeDTO->retTodos();
        $objMdUtlAdmRelPrmDsAtividadeDTO->setOrdNumPrioridade(InfraDTO::$TIPO_ORDENACAO_ASC);
        $objMdUtlAdmRelPrmDsAtividade = $objMdUtlAdmRelPrmDsAtividadeRN->listar($objMdUtlAdmRelPrmDsAtividadeDTO);

        $arrAtividade = array();
        foreach ($objMdUtlAdmRelPrmDsAtividade as $key => $dadosAtividade) {

            $objMdUtlAdmAtividadeDTO->setNumIdMdUtlAdmAtividade($dadosAtividade->getNumIdMdUtlAdmAtividade());
            $objMdUtlAdmAtividadeDTO->retTodos();
            $objMdUtlAdmAtividadeDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);
            $objMdUtlAdmAtividade = $objMdUtlAdmAtividadeRN->consultar($objMdUtlAdmAtividadeDTO);

            $arrAtividade[$key]['id'] = $dadosAtividade->getNumIdMdUtlAdmAtividade();
            $arrAtividade[$key]['desc'] = $objMdUtlAdmAtividade->getStrNome() . ' - ' . $objMdUtlAdmAtividade->getStrDescricao() . ' - ' . MdUtlAdmAtividadeRN::$ARR_COMPLEXIDADE[ $objMdUtlAdmAtividade->getNumComplexidade() ];
            $arrAtividade[$key]['prioridade'] = $dadosAtividade->getNumPrioridade();
        }

        return $arrAtividade;
    }
}
