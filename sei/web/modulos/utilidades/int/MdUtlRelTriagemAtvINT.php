<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4� REGI�O
*
* 06/11/2018 - criado por jaqueline.cast
*
* Vers�o do Gerador de C�digo: 1.42.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlRelTriagemAtvINT extends InfraINT {

  public static function montarSelectIdMdUtlRelTriagemAtv($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objMdUtlRelTriagemAtvDTO = new MdUtlRelTriagemAtvDTO();
    $objMdUtlRelTriagemAtvDTO->retNumIdMdUtlRelTriagemAtv();

    $objMdUtlRelTriagemAtvDTO->setOrdNumIdMdUtlRelTriagemAtv(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdUtlRelTriagemAtvRN = new MdUtlRelTriagemAtvRN();
    $arrObjMdUtlRelTriagemAtvDTO = $objMdUtlRelTriagemAtvRN->listar($objMdUtlRelTriagemAtvDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdUtlRelTriagemAtvDTO, '', 'IdMdUtlRelTriagemAtv');
  }
}
