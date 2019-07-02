<?php
/**
 * @author Jaqueline Mendes <jaqueline.mendes@castgroup.com.br>
 * @since  15/04/2019
 */

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdUtlPrazoRN extends InfraRN
{

    public function __construct()
    {
        parent::__construct();
    }

    protected function inicializarObjInfraIBanco()
    {
        return BancoSEI::getInstance();
    }

    public function somarDiaUtil($numQtde, $strData)
    {
        $strDataFinal = InfraData::calcularData(($numQtde + 365), InfraData::$UNIDADE_DIAS, InfraData::$SENTIDO_ADIANTE, $strData);

        $this->_removerTimeDate($strData);
        $arrFeriados  = $this->_recuperarFeriados($strData, $strDataFinal);

        $count = 0;
        while ($count < $numQtde) {
            $strData = InfraData::calcularData(1, InfraData::$UNIDADE_DIAS, InfraData::$SENTIDO_ADIANTE, $strData);
            if (InfraData::obterDescricaoDiaSemana($strData) != 'sábado' &&
                InfraData::obterDescricaoDiaSemana($strData) != 'domingo' &&
                !in_array($strData, $arrFeriados)
            ) {
                $count++;
            }
        }

        return $strData;
    }

    private function _somarMes($numMes, $strData)
    {
        $strDataFinal = InfraData::calcularData(($numMes + 12), InfraData::$UNIDADE_MESES, InfraData::$SENTIDO_ADIANTE, $strData);
        $arrFeriados  = $this->_recuperarFeriados($strData, $strDataFinal);

        $this->_removerTimeDate($strData);
        $strDataEUA    = implode('-', array_reverse(explode('/', $strData)));
        $objData       = new DateTime($strDataEUA);
        $numMes        = '+' . $numMes . 'month';
        $novaData      = $objData->modify($numMes);
        $dataCalculada = $novaData->format('d/m/Y');

        while (InfraData::obterDescricaoDiaSemana($dataCalculada) == 'sábado' ||
            InfraData::obterDescricaoDiaSemana($dataCalculada) == 'domingo' ||
            in_array($dataCalculada, $arrFeriados)) {
            $dataCalculada = InfraData::calcularData(1, InfraData::$UNIDADE_DIAS, InfraData::$SENTIDO_ADIANTE, $dataCalculada);
        }

        return $dataCalculada;

    }

    private function _somarAno($numAno, $strData)
    {
        $strDataFinal = InfraData::calcularData($numAno, InfraData::$UNIDADE_ANOS, InfraData::$SENTIDO_ADIANTE, $strData);
        $arrFeriados  = $this->_recuperarFeriados($strData, $strDataFinal);

        $this->_removerTimeDate($strData);
        $strDataEUA    = implode('-', array_reverse(explode('/', $strData)));
        $objData       = new DateTime($strDataEUA);
        $numAno        = '+' . $numAno . 'year';
        $novaData      = $objData->modify($numAno);
        $dataCalculada = $novaData->format('d/m/Y');

        while (InfraData::obterDescricaoDiaSemana($dataCalculada) == 'sábado' ||
            InfraData::obterDescricaoDiaSemana($dataCalculada) == 'domingo' ||
            in_array($dataCalculada, $arrFeriados)) {
            $dataCalculada = InfraData::calcularData(1, InfraData::$UNIDADE_DIAS, InfraData::$SENTIDO_ADIANTE, $dataCalculada);


            return $dataCalculada;
        }
    }

    private function _removerTimeDate(&$strData){
        $countDate  = strlen($strData);
        $isDateTime = $countDate > 10 ? true : false;
        if($isDateTime){
            $arrData = explode(" ",$strData);
            $strData = $arrData[0];
        }
    }

    private function _recuperarFeriados($strDataInicial, $strDataFinal)
    {
        $numIdOrgao = SessaoSEI::getInstance()->getNumIdOrgaoUnidadeAtual();

        if (is_null($numIdOrgao)){
            $objOrgaoDTO = new OrgaoDTO();
            $objOrgaoDTO->retNumIdOrgao();
            $objOrgaoDTO->setBolExclusaoLogica(false);
            $objOrgaoDTO->adicionarCriterio(array('SinAtivo','Sigla'),array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),array('S',ConfiguracaoSEI::getInstance()->getValor('SessaoSEI','SiglaOrgaoSistema')),InfraDTO::$OPER_LOGICO_AND);

            $objOrgaoRN = new OrgaoRN();
            $arrObjOrgaoDTO = $objOrgaoRN->listarRN1353($objOrgaoDTO);
            $numIdOrgao = !is_null($arrObjOrgaoDTO) && count($arrObjOrgaoDTO) > 0 ? current($arrObjOrgaoDTO)->getNumIdOrgao() : null;
        }

        $arrFeriados  = array();

        $objFeriadoRN = new FeriadoRN();
        $objFeriadoDTO = new FeriadoDTO();
        $objFeriadoDTO->retDtaFeriado();
        $objFeriadoDTO->retStrDescricao();

        if($numIdOrgao != ''){
            $objFeriadoDTO->adicionarCriterio(array('IdOrgao','IdOrgao'),
                array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),
                array(null,$numIdOrgao),
                array(InfraDTO::$OPER_LOGICO_OR));
        }else{
            $objFeriadoDTO->setNumIdOrgao(null);
        }

        $objFeriadoDTO->adicionarCriterio(array('Feriado', 'Feriado'),
            array(InfraDTO::$OPER_MAIOR_IGUAL, InfraDTO::$OPER_MENOR_IGUAL),
            array($strDataInicial, $strDataFinal),
            array(InfraDTO::$OPER_LOGICO_AND));

        $objFeriadoDTO->setOrdDtaFeriado(InfraDTO::$TIPO_ORDENACAO_ASC);

        $count = $objFeriadoRN->contar($objFeriadoDTO);
        $arrObjFeriadoDTO = $objFeriadoRN->listar($objFeriadoDTO);

        if($count > 0)
        {
            $arrFeriados = InfraArray::converterArrInfraDTO($arrObjFeriadoDTO, 'Feriado');
        }

        return $arrFeriados;
    }

}
