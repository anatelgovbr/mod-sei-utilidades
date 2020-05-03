<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/09/2018 - criado por jhon.carvalho
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmGrpINT extends InfraINT {

  public static function montarSelectIdMdUtlAdmGrp($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdMdUtlAdmTpCtrlDesemp=''){
    $objMdUtlAdmGrpDTO = new MdUtlAdmGrpDTO();
    $objMdUtlAdmGrpDTO->retNumIdMdUtlAdmGrp();
    $objMdUtlAdmGrpDTO->retNumIdMdUtlAdmGrp();

    if ($numIdMdUtlAdmTpCtrlDesemp!==''){
      $objMdUtlAdmGrpDTO->setNumIdMdUtlAdmTpCtrlDesemp($numIdMdUtlAdmTpCtrlDesemp);
    }

    $objMdUtlAdmGrpDTO->setOrdNumIdMdUtlAdmGrp(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdUtlAdmGrpRN = new MdUtlAdmGrpRN();
    $arrObjMdUtlAdmGrpDTO = $objMdUtlAdmGrpRN->listar($objMdUtlAdmGrpDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdUtlAdmGrpDTO, 'IdMdUtlAdmGrp', 'IdMdUtlAdmGrp');
  }
}
