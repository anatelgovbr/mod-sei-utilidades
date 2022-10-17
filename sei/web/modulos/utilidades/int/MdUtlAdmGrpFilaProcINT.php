<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4� REGI�O
*
* 13/09/2018 - criado por jhon.carvalho
*
* Vers�o do Gerador de C�digo: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmGrpFilaProcINT extends InfraINT {

  public static function montarSelectIdMdUtlAdmGrpFilaProc($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdMdUtlAdmGrpFila='', $numIdTipoProcedimento=''){
    $objMdUtlAdmGrpFilaProcDTO = new MdUtlAdmGrpFilaProcDTO();
    $objMdUtlAdmGrpFilaProcDTO->retNumIdMdUtlAdmGrpFilaProc();
    $objMdUtlAdmGrpFilaProcDTO->retNumIdMdUtlAdmGrpFilaProc();

    if ($numIdMdUtlAdmGrpFila!==''){
      $objMdUtlAdmGrpFilaProcDTO->setNumIdMdUtlAdmGrpFila($numIdMdUtlAdmGrpFila);
    }

    if ($numIdTipoProcedimento!==''){
      $objMdUtlAdmGrpFilaProcDTO->setNumIdTipoProcedimento($numIdTipoProcedimento);
    }

    $objMdUtlAdmGrpFilaProcDTO->setOrdNumIdMdUtlAdmGrpFilaProc(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdUtlAdmGrpFilaProcRN = new MdUtlAdmGrpFilaProcRN();
    $arrObjMdUtlAdmGrpFilaProcDTO = $objMdUtlAdmGrpFilaProcRN->listar($objMdUtlAdmGrpFilaProcDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdUtlAdmGrpFilaProcDTO, 'IdMdUtlAdmGrpFilaProc', 'IdMdUtlAdmGrpFilaProc');
  }
}
