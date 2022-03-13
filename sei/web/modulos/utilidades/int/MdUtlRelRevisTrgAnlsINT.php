<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 21/12/2018 - criado por jhon.cast
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlRelRevisTrgAnlsINT extends InfraINT {

  public static function montarSelectidMdUtlRelRevisTrgAnls($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objMdUtlRelRevisTrgAnlsDTO = new MdUtlRelRevisTrgAnlsDTO();
    $objMdUtlRelRevisTrgAnlsDTO->retNumidMdUtlRelRevisTrgAnls();

    $objMdUtlRelRevisTrgAnlsDTO->setOrdNumidMdUtlRelRevisTrgAnls(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdUtlRelRevisTrgAnlsRN = new MdUtlRelRevisTrgAnlsRN();
    $arrObjMdUtlRelRevisTrgAnlsDTO = $objMdUtlRelRevisTrgAnlsRN->listar($objMdUtlRelRevisTrgAnlsDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdUtlRelRevisTrgAnlsDTO, '', 'idMdUtlRelRevisTrgAnls');
  }
}
