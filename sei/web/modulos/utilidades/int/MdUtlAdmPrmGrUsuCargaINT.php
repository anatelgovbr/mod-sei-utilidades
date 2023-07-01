<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/01/2023 - criado por gustavos.colab
*
* Versão do Gerador de Código: 1.43.2
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmPrmGrUsuCargaINT extends InfraINT {

  public static function montarSelectIdMdUtlAdmPrmGrUsu($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdMdUtlAdmPrmGrUsu=''){
    $objMdUtlAdmPrmGrUsuCargaDTO = new MdUtlAdmPrmGrUsuCargaDTO();
    $objMdUtlAdmPrmGrUsuCargaDTO->retNumIdMdUtlAdmPrmGrUsuCarga();
    $objMdUtlAdmPrmGrUsuCargaDTO->retNumIdMdUtlAdmPrmGrUsu();

    if ($numIdMdUtlAdmPrmGrUsu!==''){
      $objMdUtlAdmPrmGrUsuCargaDTO->setNumIdMdUtlAdmPrmGrUsu($numIdMdUtlAdmPrmGrUsu);
    }

    $objMdUtlAdmPrmGrUsuCargaDTO->setOrdNumIdMdUtlAdmPrmGrUsu(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdUtlAdmPrmGrUsuCargaRN = new MdUtlAdmPrmGrUsuCargaRN();
    $arrObjMdUtlAdmPrmGrUsuCargaDTO = $objMdUtlAdmPrmGrUsuCargaRN->listar($objMdUtlAdmPrmGrUsuCargaDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdUtlAdmPrmGrUsuCargaDTO, 'IdMdUtlAdmPrmGrUsuCarga', 'IdMdUtlAdmPrmGrUsu');
  }

	/*
	 * Caso tenha o caracter dois ponto na string significa um intervalo de datas
	 */
	public static function criaDiasAusenciasUtilizados($strDatas){
		$arrDatasAusencia = !empty($strDatas) ? explode(',' , $strDatas) : null;
		$arrDatasRetorno  = [];
		if( $arrDatasAusencia ){
			foreach ($arrDatasAusencia as $dtAusenca) {
				if (strpos($dtAusenca ,':') !== false) { // existe um intervalo de datas salva na tabela de parametrizacao usuario x carga
					$arrIntervaloDatas = explode(':', $dtAusenca);
					$arrIntervaloDatas = self::geraRangeDias( $arrIntervaloDatas[0] , $arrIntervaloDatas[1] , false );
					foreach ($arrIntervaloDatas as $data) {
						if (!in_array($data,$arrDatasRetorno)) array_push($arrDatasRetorno,$data);
					}
				} else if ( !in_array($dtAusenca,$arrDatasRetorno) ) {
					array_push($arrDatasRetorno,$dtAusenca);
				}
			}
			return $arrDatasRetorno;
		}
		return [];
	}

	public static function geraRangeDias($dti , $dtf , $bolOcultarFDS = true ){

		$inicio = new DateTime($dti);
		$fim    = new DateTime($dtf);
		$fim->modify('+1 day');
		$periodo = new DatePeriod($inicio, new DateInterval('P1D'), $fim);
		$validos = [];
		foreach($periodo as $item){
			if( $bolOcultarFDS ){
				if(substr($item->format("D"), 0, 1) != 'S'){
					$validos[] = $item->format('Y-m-d');
				}
			} else {
				$validos[] = $item->format('Y-m-d');
			}
		}
		return $validos;
	}

	public static function montaDatasAusenciasBanco($arrDatas){
		$strDataRetorno = '';
		if( !empty($arrDatas)){
			$strDataRetorno       = $arrDatas[0]; // inicia a string com a primeira data do array
			$bolSinalizaIntervalo = false;
			$bolMarcacaoUltReg    = false;

			foreach ($arrDatas as $k => $data) {

				// indice do, provavel, proximo valor do array das datas
				$idxDataPosterior = $k + 1;

				if (array_key_exists($idxDataPosterior,$arrDatas)) {

					// data do indice atual no padrao Brasileiro
					$dataPadraoBR  = implode( '/', array_reverse( explode( '-', $data ) ) );

					// gera o proximo dia em relacao a data do indice atual
					$diaPosterior  = InfraData::calcularData(1, 'd', InfraData::$SENTIDO_ADIANTE, $dataPadraoBR);

					// converte o valor para o padrao americano
					$dataPadraoDef = implode( '-' , array_reverse( explode( '/', $diaPosterior ) ) );

					if( $arrDatas[$idxDataPosterior] == $dataPadraoDef ) {
						$bolSinalizaIntervalo = true;
						$bolMarcacaoUltReg    = false;
					} else {
						if ( $bolSinalizaIntervalo ) {
							// fecha com a data fim do intervalo e adiciona a nova data
							$strDataRetorno .= ':' . $data . ',' . $arrDatas[$idxDataPosterior];
							$bolSinalizaIntervalo = false;
						} else {
							// se nao tem intervalo de datas adiciona a nova data
							$strDataRetorno .= ',' . $arrDatas[$idxDataPosterior];
						}
						$bolMarcacaoUltReg = true;
					}
				}
			}
			if ( ! $bolMarcacaoUltReg && count($arrDatas) > 1 ) {
				$strDataRetorno .= $bolSinalizaIntervalo ? ':' . end($arrDatas) : ',' . end($arrDatas);
			}
		}
		return $strDataRetorno;
	}
}
