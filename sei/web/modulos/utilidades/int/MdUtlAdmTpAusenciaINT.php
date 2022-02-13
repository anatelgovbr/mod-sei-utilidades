<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/07/2018 - criado por jhon.cast
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmTpAusenciaINT extends InfraINT {

  public static function montarSelectIdMdUtlAdmTpAusencia($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objMdUtlAdmTpAusenciaDTO = new MdUtlAdmTpAusenciaDTO();
    $objMdUtlAdmTpAusenciaDTO->retNumIdMdUtlAdmTpAusencia();
    $objMdUtlAdmTpAusenciaDTO->retNumIdMdUtlAdmTpAusencia();

    if ($strValorItemSelecionado!=null){
      $objMdUtlAdmTpAusenciaDTO->setBolExclusaoLogica(false);
      $objMdUtlAdmTpAusenciaDTO->adicionarCriterio(array('SinAtivo','IdMdUtlAdmTpAusencia'),array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),array('S',$strValorItemSelecionado),InfraDTO::$OPER_LOGICO_OR);
    }

    $objMdUtlAdmTpAusenciaDTO->setOrdNumIdMdUtlAdmTpAusencia(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdUtlAdmTpAusenciaRN = new MdUtlAdmTpAusenciaRN();
    $arrObjMdUtlAdmTpAusenciaDTO = $objMdUtlAdmTpAusenciaRN->listar($objMdUtlAdmTpAusenciaDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdUtlAdmTpAusenciaDTO, 'IdMdUtlAdmTpAusencia', 'IdMdUtlAdmTpAusencia');
  }
}
