<?php

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdUtlAdmRelPrmDsProcRN extends InfraRN
{

    public function __construct()
    {
        parent::__construct();
    }

    protected function inicializarObjInfraIBanco()
    {
        return BancoSEI::getInstance();
    }

    private function validarNumIdMdUtlAdmParamDs(MdUtlAdmRelPrmDsProcDTO $objMdUtlAdmRelPrmDsProcDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objMdUtlAdmRelPrmDsProcDTO->getNumIdMdUtlAdmParamDs())) {
            $objMdUtlAdmRelPrmDsProcDTO->setNumIdMdUtlAdmParamDs(null);
        }
    }

    protected function cadastrarControlado(MdUtlAdmRelPrmDsProcDTO $objMdUtlAdmRelPrmDsProcDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_rel_prm_ds_proc_cadastrar', __METHOD__, $objMdUtlAdmRelPrmDsProcDTO);

            //Regras de Negocio
            $objInfraException = new InfraException();

            $this->validarNumIdMdUtlAdmParamDs($objMdUtlAdmRelPrmDsProcDTO, $objInfraException);

            $objInfraException->lancarValidacoes();

            $objMdUtlAdmRelPrmDsProcBD = new MdUtlAdmRelPrmDsProcBD($this->getObjInfraIBanco());
            $ret = $objMdUtlAdmRelPrmDsProcBD->cadastrar($objMdUtlAdmRelPrmDsProcDTO);

            return $ret;

        } catch (Exception $e) {
            throw new InfraException('Erro cadastrando .', $e);
        }
    }

    protected function alterarControlado(MdUtlAdmRelPrmDsProcDTO $objMdUtlAdmRelPrmDsProcDTO)
    {
        try {

            $objInfraException = new InfraException();

            if ($objMdUtlAdmRelPrmDsProcDTO->isSetNumIdMdUtlAdmParamDs()) {
                $this->validarNumIdMdUtlAdmParamDs($objMdUtlAdmRelPrmDsProcDTO, $objInfraException);
            }

            $objInfraException->lancarValidacoes();

            $objMdUtlAdmRelPrmDsProcBD = new MdUtlAdmRelPrmDsProcBD($this->getObjInfraIBanco());
            $objMdUtlAdmRelPrmDsProcBD->alterar($objMdUtlAdmRelPrmDsProcDTO);

        } catch (Exception $e) {
            throw new InfraException('Erro alterando .', $e);
        }
    }

    protected function excluirControlado($arrObjMdUtlAdmRelPrmDsProcDTO)
    {
        try {
            $objMdUtlAdmRelPrmDsProcBD = new MdUtlAdmRelPrmDsProcBD($this->getObjInfraIBanco());
            for ($i = 0; $i < count($arrObjMdUtlAdmRelPrmDsProcDTO); $i++) {
                $obj = $objMdUtlAdmRelPrmDsProcBD->excluir($arrObjMdUtlAdmRelPrmDsProcDTO[$i]);

            }
        } catch (Exception $e) {
            throw new InfraException('Erro excluindo .', $e);
        }
    }

    protected function consultarConectado(MdUtlAdmRelPrmDsProcDTO $objMdUtlAdmRelPrmDsProcDTO)
    {
        try {

            $objMdUtlAdmRelPrmDsProcBD = new MdUtlAdmRelPrmDsProcBD($this->getObjInfraIBanco());
            $ret = $objMdUtlAdmRelPrmDsProcBD->consultar($objMdUtlAdmRelPrmDsProcDTO);

            return $ret;

        } catch (Exception $e) {
            throw new InfraException('Erro consultando .', $e);
        }
    }

    protected function listarConectado(MdUtlAdmRelPrmDsProcDTO $objMdUtlAdmRelPrmDsProcDTO)
    {
        try {

            $objMdUtlAdmRelPrmDsProcBD = new MdUtlAdmRelPrmDsProcBD($this->getObjInfraIBanco());
            $ret = $objMdUtlAdmRelPrmDsProcBD->listar($objMdUtlAdmRelPrmDsProcDTO);

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro listando .', $e);
        }
    }

    protected function contarConectado(MdUtlAdmRelPrmDsProcDTO $objMdUtlAdmRelPrmDsProcDTO)
    {
        try {
            $objMdUtlAdmRelPrmDsProcBD = new MdUtlAdmRelPrmDsProcBD($this->getObjInfraIBanco());
            $ret = $objMdUtlAdmRelPrmDsProcBD->contar($objMdUtlAdmRelPrmDsProcDTO);

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro contando .', $e);
        }
    }

    protected function montarArrTipoProcessoControlado($idMdUtlAdmPrmDs)
    {

        $objMdUtlAdmRelPrmDsTipoProcessoDTO = new MdUtlAdmRelPrmDsProcDTO();
        $objMdUtlAdmRelPrmDsProcRN = new MdUtlAdmRelPrmDsProcRN();
        $objMdUtlAdmRelPrmGrProcDTO = new MdUtlAdmRelPrmGrProcDTO();
        $objMdUtlAdmRelPrmGrProcRN = new MdUtlAdmRelPrmGrProcRN();

        $objMdUtlAdmRelPrmDsTipoProcessoDTO->setNumIdMdUtlAdmParamDs($idMdUtlAdmPrmDs);
        $objMdUtlAdmRelPrmDsTipoProcessoDTO->retTodos();
        $objMdUtlAdmRelPrmDsTipoProcessoDTO->setOrdNumPrioridade(InfraDTO::$TIPO_ORDENACAO_DESC);
        $objMdUtlAdmRelPrmDsProcesso = $objMdUtlAdmRelPrmDsProcRN->listar($objMdUtlAdmRelPrmDsTipoProcessoDTO);

        foreach ($objMdUtlAdmRelPrmDsProcesso as $key => $dadosTipoProcesso) {
            $tipoProcesso = array();
            $opt = array();
            $opcoes = '';

            $objMdUtlAdmRelPrmGrProcDTO->retNumIdMdUtlAdmParamGr();
            $objMdUtlAdmRelPrmGrProcDTO->retNumIdTipoProcedimento();
            $objMdUtlAdmRelPrmGrProcDTO->retNumIdMdUtlAdmParamGr();
            $objMdUtlAdmRelPrmGrProcDTO->retStrNomeProcedimento();
            $objMdUtlAdmRelPrmGrProcDTO->setNumIdTipoProcedimento($dadosTipoProcesso->getNumIdMdUtlAdmTipoProcesso());
            $objMdUtlAdmRelPrmGrProcDTO->setOrdNumIdMdUtlAdmParamGr(InfraDTO::$TIPO_ORDENACAO_ASC);

            $arrObjMdUtlAdmRelPrmGrProcDTO = $objMdUtlAdmRelPrmGrProcRN->listar($objMdUtlAdmRelPrmGrProcDTO);

            foreach ($arrObjMdUtlAdmRelPrmGrProcDTO as $key => $objTipoProcedimentoDTO) {
                $nomeProcedimento = $objTipoProcedimentoDTO->getStrNomeProcedimento();
            }

            $tipoProcesso[] = $dadosTipoProcesso->getNumIdMdUtlAdmTipoProcesso();
            $tipoProcesso[] = $nomeProcedimento;

            $prioridade = $dadosTipoProcesso->getNumPrioridade();
            $idSelect = 'selPriTipoProcesso_' . $dadosTipoProcesso->getNumIdMdUtlAdmTipoProcesso();

            for ($i = 0; $i < count($objMdUtlAdmRelPrmDsProcesso); $i++) {
                $opt[] = $i + 1;
                $valor = $i + 1;
                if ($opt[$prioridade - 1] == $valor) {
                    $opcoes .= "<option selected>" . $valor . "</option>";
                } else {
                    $opcoes .= "<option>" . $valor . "</option>";
                }
            }

            $selectOption = "<select style='width: 100%;' class='infraSelect selectTipoProcesso' id='" . $idSelect . "' name='" . $idSelect . "' >$opcoes</select>";
            $tipoProcesso[] = $selectOption;

            $arrTipoProcesso[] = $tipoProcesso;
        }

        return array(
            'itensTabela' => empty( $arrTipoProcesso ) ? array() : $arrTipoProcesso , 
            'qtdTipoProcesso' => empty( $arrTipoProcesso ) ? 0 : count($arrTipoProcesso)
        );
    }

    protected function montarArrTipoProcessoPrioridadeControlado($idMdUtlAdmPrmDs)
    {

        $objMdUtlAdmRelPrmDsTipoProcessoDTO = new MdUtlAdmRelPrmDsProcDTO();
        $objMdUtlAdmRelPrmDsProcRN = new MdUtlAdmRelPrmDsProcRN();
        $objMdUtlAdmRelPrmGrProcDTO = new MdUtlAdmRelPrmGrProcDTO();
        $objMdUtlAdmRelPrmGrProcRN = new MdUtlAdmRelPrmGrProcRN();

        $objMdUtlAdmRelPrmDsTipoProcessoDTO->setNumIdMdUtlAdmParamDs($idMdUtlAdmPrmDs);
        $objMdUtlAdmRelPrmDsTipoProcessoDTO->retTodos();
        $objMdUtlAdmRelPrmDsTipoProcessoDTO->setOrdNumPrioridade(InfraDTO::$TIPO_ORDENACAO_ASC);
        $objMdUtlAdmRelPrmDsProcesso = $objMdUtlAdmRelPrmDsProcRN->listar($objMdUtlAdmRelPrmDsTipoProcessoDTO);

        $arrTipoProcesso = array();
        foreach ($objMdUtlAdmRelPrmDsProcesso as $key => $dadosTipoProcesso) {
            $objMdUtlAdmRelPrmGrProcDTO->retNumIdMdUtlAdmParamGr();
            $objMdUtlAdmRelPrmGrProcDTO->retNumIdTipoProcedimento();
            $objMdUtlAdmRelPrmGrProcDTO->retNumIdMdUtlAdmParamGr();
            $objMdUtlAdmRelPrmGrProcDTO->retStrNomeProcedimento();
            $objMdUtlAdmRelPrmGrProcDTO->setNumIdTipoProcedimento($dadosTipoProcesso->getNumIdMdUtlAdmTipoProcesso());

            $arrObjMdUtlAdmRelPrmGrProcDTO = $objMdUtlAdmRelPrmGrProcRN->listar($objMdUtlAdmRelPrmGrProcDTO);

            foreach ($arrObjMdUtlAdmRelPrmGrProcDTO as $objTipoProcedimentoDTO) {
                $nomeProcedimento = $objTipoProcedimentoDTO->getStrNomeProcedimento();
            }

            $arrTipoProcesso[$key]['id'] = $dadosTipoProcesso->getNumIdMdUtlAdmTipoProcesso();
            $arrTipoProcesso[$key]['desc'] = $nomeProcedimento;
            $arrTipoProcesso[$key]['prioridade'] = $dadosTipoProcesso->getNumPrioridade();
        }
        return $arrTipoProcesso;
    }
}
