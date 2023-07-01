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
        $arrFeriados  = $this->recuperarFeriados($strData, $strDataFinal);

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

    public function retornaProximoDiaUtil($strData)
    {
        $strDataFinal = InfraData::calcularData((365), InfraData::$UNIDADE_DIAS, InfraData::$SENTIDO_ADIANTE, $strData);

        $this->_removerTimeDate($strData);
        $arrFeriados  = $this->recuperarFeriados($strData, $strDataFinal);

        $count = 0;
        while (InfraData::obterDescricaoDiaSemana($strData) == 'sábado' ||
            InfraData::obterDescricaoDiaSemana($strData) == 'domingo' ||
            in_array($strData, $arrFeriados)) {
            $strData = InfraData::calcularData(1, InfraData::$UNIDADE_DIAS, InfraData::$SENTIDO_ADIANTE, $strData);

            $count++;
            if($count == 10){
                break;
            }
        }

        return $strData;
    }

		public function retornaQtdDiaUtil($dtPrazoInicial, $dtPrazoFinal, $isSomarDia = true, $isFeriadoSEI = true)
		{

			$this->_removerTimeDate($dtPrazoInicial);
			$this->_removerTimeDate($dtPrazoFinal);

			$qtdDiasNormais = InfraData::compararDatas($dtPrazoInicial, $dtPrazoFinal);
			$arrFeriados    = $this->recuperarFeriados($dtPrazoInicial, $dtPrazoFinal);

			$qtdDiasUteis = 0;

			while (InfraData::compararDatasSimples($dtPrazoInicial, $dtPrazoFinal) != 0) {

				if($isSomarDia) {
					$dtPrazoInicial = InfraData::calcularData(1, InfraData::$UNIDADE_DIAS, InfraData::$SENTIDO_ADIANTE, $dtPrazoInicial);
				}

				$diaSemana = InfraData::obterDescricaoDiaSemana($dtPrazoInicial);

				if ( $diaSemana != 'sábado' && $diaSemana != 'domingo' ) {
					if( $isFeriadoSEI ) {
						if ( !in_array($dtPrazoInicial, $arrFeriados) ) {
							$qtdDiasUteis++;
						}
					} else {
						$qtdDiasUteis++;
					}
				}

				$isSomarDia = true;

				if($qtdDiasUteis == $qtdDiasNormais){
					break;
				}
			}

			return $qtdDiasUteis;
		}

		public function verificaDiaUtil($dtPrazoInicial, $dtPrazoFinal, $isFeriadoSEI = true)
		{

			$this->_removerTimeDate($dtPrazoInicial);
			$this->_removerTimeDate($dtPrazoFinal);

			$arrFeriados    = $this->recuperarFeriados($dtPrazoInicial, $dtPrazoFinal);

			$diaSemana = InfraData::obterDescricaoDiaSemana($dtPrazoInicial);

			if ( $diaSemana != 'sábado' && $diaSemana != 'domingo' ) {
				if ( $isFeriadoSEI ) {
					if ($dtPrazoInicial != current($arrFeriados)) return true;
				} else {
					return true;
				}
			}

			return false;
		}

    private function _somarMes($numMes, $strData)
    {
        $strDataFinal = InfraData::calcularData(($numMes + 12), InfraData::$UNIDADE_MESES, InfraData::$SENTIDO_ADIANTE, $strData);
        $arrFeriados  = $this->recuperarFeriados($strData, $strDataFinal);

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
        $arrFeriados  = $this->recuperarFeriados($strData, $strDataFinal);

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

    public function recuperarFeriados($strDataInicial, $strDataFinal)
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

    public function retornaPrimeiroDiaSemana($dataPrimeiroDiaSemana, $primeiroDiaSemana = 'segunda-feira')
    {
        $count = 0;
        while (InfraData::obterDescricaoDiaSemana($dataPrimeiroDiaSemana) != $primeiroDiaSemana) {
            $dataPrimeiroDiaSemana = date('d-m-Y', strtotime('-1 days', strtotime($dataPrimeiroDiaSemana)));

            $count++;
            if ($count == 20) {
                break;
            }
        }

        return $dataPrimeiroDiaSemana;
    }

    public function retornaPrimeiraOcorrenciaDiaSemana($dataPrimeiroDiaSemana, $diaSemana = 'segunda-feira'){
      $count = 0;
        while (InfraData::obterDescricaoDiaSemana($dataPrimeiroDiaSemana) != $diaSemana) {
            $dataPrimeiroDiaSemana = date('d-m-Y', strtotime('+1 days', strtotime($dataPrimeiroDiaSemana)));

            $count++;
            if ($count == 20) {
                break;
            }
        }

        return $dataPrimeiroDiaSemana;
    }

    public function getDatasPorFrequencia($inicioPeriodo){

        $dataAtual = InfraData::getStrDataAtual();
        $dataAtualFormatada = explode('/', $dataAtual);
        $mesAtual      = $dataAtualFormatada[1];
        $anoAtual      = $dataAtualFormatada[2];

           switch ($inicioPeriodo){
               case MdUtlAdmPrmGrRN::$FREQUENCIA_INICIO_DIARIO:
                   $dtInicial = $dataAtual;
                   $dtFinal   = $dataAtual;
                   break;

               case MdUtlAdmPrmGrRN::$FREQUENCIA_INICIO_SEMANAL_DOMINGO:
                   $dataAtualFormatada = implode('-',$dataAtualFormatada);
                   $dataPrimeiroDiaSemana = $dataAtualFormatada;
                   $dtInicial = $this->retornaPrimeiroDiaSemana($dataPrimeiroDiaSemana, 'domingo');
                   $dtFinal   = date('d/m/Y', strtotime('+6 days', strtotime($dtInicial)));
                   $dtInicial = str_replace('-','/', $dtInicial);
                   break;

               case MdUtlAdmPrmGrRN::$FREQUENCIA_INICIO_SEMANAL_SEGUNDA:
                   $dataAtualFormatada = implode('-',$dataAtualFormatada);
                   $dataPrimeiroDiaSemana = $dataAtualFormatada;
                   $dtInicial = $this->retornaPrimeiroDiaSemana($dataPrimeiroDiaSemana);
                   $dtFinal   = date('d/m/Y', strtotime('+6 days', strtotime($dtInicial)));
                   $dtInicial =  str_replace('-','/', $dtInicial);
                   break;

               case MdUtlAdmPrmGrRN::$FREQUENCIA_MENSAL_PRIMEIRO_DIA_MES:
                   $ultimoDiaMes = InfraData::obterUltimoDiaMes($mesAtual, $anoAtual);
                   $dtInicial =  '01/' . $mesAtual . '/' . $anoAtual;
                   $dtFinal   = $ultimoDiaMes . '/' . $mesAtual . '/' . $anoAtual;
                   break;

               case MdUtlAdmPrmGrRN::$FREQUENCIA_MENSAL_PRIMEIRO_DIA_UTIL_MES:
                   //Data Inicial
                   $dtInicialMes = '01/'.$mesAtual.'/'.$anoAtual;
                   $dtInicial = $this->retornaProximoDiaUtil($dtInicialMes);
                   $dtInicialFormt =  str_replace('/','-', $dtInicial);

                   //Data Final
                   $inicioProximoMes = (date('d/m/Y', strtotime('+1 month', strtotime($dtInicialFormt))));
                   $primeiroDiaUtilMes = $this->retornaProximoDiaUtil($inicioProximoMes);
                   $primeiroDiaUtilMes = str_replace('/','-', $primeiroDiaUtilMes);
                   $dtFinal   = date('d/m/Y', strtotime('-1 day', strtotime($primeiroDiaUtilMes)));

                   break;

               case MdUtlAdmPrmGrRN::$FREQUENCIA_MENSAL_PRIMEIRA_SEGUNDA_MES:
                   //Data Inicial
                   $dtInicialMes = '01-'.$mesAtual.'-'.$anoAtual;
                   $dtInicial = $this->retornaPrimeiraOcorrenciaDiaSemana($dtInicialMes);
                   $dtInicial = str_replace('-','/', $dtInicial);

                   //Data Final
                   $inicioProximoMes = (date('d/m/Y', strtotime('+1 month', strtotime($dtInicialMes))));
                   $inicioProximoMes = str_replace('/','-', $inicioProximoMes);
                   $primeiroDiaUtilProxMes = $this->retornaPrimeiraOcorrenciaDiaSemana($inicioProximoMes);
                   $dtFinal = (date('d/m/Y', strtotime('-1 day', strtotime($primeiroDiaUtilProxMes))));
                    break;


               default:
                   $ultimoDiaMes = InfraData::obterUltimoDiaMes($mesAtual, $anoAtual);
                   $dtInicial =  '01/' . $mesAtual . '/' . $anoAtual;
                   $dtFinal   = $ultimoDiaMes . '/' . $mesAtual . '/' . $anoAtual;
                   break;
           }

        $dtInicial = $dtInicial. ' 00:00:00';
        $dtFinal   = $dtFinal. ' 23:59:59';
        $arrRetorno = array('DT_INICIAL'=> $dtInicial, 'DT_FINAL'=> $dtFinal);

        return $arrRetorno;
    }

		public function getDatasPeriodoAtual($idPrmGr){
    	$objMdUtlAdmPrmGrDTO = new MdUtlAdmPrmGrDTO();
			$objMdUtlAdmPrmGrRN = new MdUtlAdmPrmGrRN();

			$objMdUtlAdmPrmGrDTO->setNumIdMdUtlAdmPrmGr($idPrmGr);
			$objMdUtlAdmPrmGrDTO->retStrStaFrequencia();

			$objMdUtlAdmPrmGrDTO = $objMdUtlAdmPrmGrRN->consultar( $objMdUtlAdmPrmGrDTO );

			$arrPeriodo = ( new MdUtlAdmPrmGrUsuRN() )->getDiasUteisNoPeriodo( [$objMdUtlAdmPrmGrDTO->getStrStaFrequencia(),false] );

			return [
				'DT_INICIAL' => $arrPeriodo['dtInicial'] . ' 00:00:00',
				'DT_FINAL'   => $arrPeriodo['dtFinal'] . ' 23:59:59'
			];
		}
}
