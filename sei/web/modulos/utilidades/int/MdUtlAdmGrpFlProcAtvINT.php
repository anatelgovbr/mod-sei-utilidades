<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4� REGI�O
*
* 13/09/2018 - criado por jhon.carvalho
*
* Vers�o do Gerador de C�digo: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmGrpFlProcAtvINT extends InfraINT {

  public static function montarSelectIdMdUtlAdmGrpFlProcAtv($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdMdUtlAdmAtividade='', $numIdMdUtlAdmGrpFilaProc=''){
    $objMdUtlAdmGrpFlProcAtvDTO = new MdUtlAdmGrpFlProcAtvDTO();
    $objMdUtlAdmGrpFlProcAtvDTO->retNumIdMdUtlAdmGrpFlProcAtv();
    $objMdUtlAdmGrpFlProcAtvDTO->retNumIdMdUtlAdmGrpFlProcAtv();

    if ($numIdMdUtlAdmAtividade!==''){
      $objMdUtlAdmGrpFlProcAtvDTO->setNumIdMdUtlAdmAtividade($numIdMdUtlAdmAtividade);
    }

    if ($numIdMdUtlAdmGrpFilaProc!==''){
      $objMdUtlAdmGrpFlProcAtvDTO->setNumIdMdUtlAdmGrpFilaProc($numIdMdUtlAdmGrpFilaProc);
    }

    $objMdUtlAdmGrpFlProcAtvDTO->setOrdNumIdMdUtlAdmGrpFlProcAtv(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdUtlAdmGrpFlProcAtvRN = new MdUtlAdmGrpFlProcAtvRN();
    $arrObjMdUtlAdmGrpFlProcAtvDTO = $objMdUtlAdmGrpFlProcAtvRN->listar($objMdUtlAdmGrpFlProcAtvDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdUtlAdmGrpFlProcAtvDTO, 'IdMdUtlAdmGrpFlProcAtv', 'IdMdUtlAdmGrpFlProcAtv');
  }
}
