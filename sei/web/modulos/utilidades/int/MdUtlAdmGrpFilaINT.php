<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/09/2018 - criado por jhon.carvalho
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmGrpFilaINT extends InfraINT {

  public static function montarSelectIdMdUtlAdmGrpFila($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdMdUtlAdmGrp='', $numIdMdUtlAdmFila=''){
    $objMdUtlAdmGrpFilaDTO = new MdUtlAdmGrpFilaDTO();
    $objMdUtlAdmGrpFilaDTO->retNumIdMdUtlAdmGrpFila();
    $objMdUtlAdmGrpFilaDTO->retNumIdMdUtlAdmGrpFila();

    if ($numIdMdUtlAdmGrp!==''){
      $objMdUtlAdmGrpFilaDTO->setNumIdMdUtlAdmGrp($numIdMdUtlAdmGrp);
    }

    if ($numIdMdUtlAdmFila!==''){
      $objMdUtlAdmGrpFilaDTO->setNumIdMdUtlAdmFila($numIdMdUtlAdmFila);
    }

    if ($strValorItemSelecionado!=null){
      $objMdUtlAdmGrpFilaDTO->setBolExclusaoLogica(false);
      $objMdUtlAdmGrpFilaDTO->adicionarCriterio(array('SinAtivo','IdMdUtlAdmGrpFila'),array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),array('S',$strValorItemSelecionado),InfraDTO::$OPER_LOGICO_OR);
    }

    $objMdUtlAdmGrpFilaDTO->setOrdNumIdMdUtlAdmGrpFila(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdUtlAdmGrpFilaRN = new MdUtlAdmGrpFilaRN();
    $arrObjMdUtlAdmGrpFilaDTO = $objMdUtlAdmGrpFilaRN->listar($objMdUtlAdmGrpFilaDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdUtlAdmGrpFilaDTO, 'IdMdUtlAdmGrpFila', 'IdMdUtlAdmGrpFila');
  }
  
  public static function autoCompletarGrupoFilaAtividade($post, $arrPrms){
    $objMdUtlAdmGrpFilaRN = new MdUtlAdmGrpFilaRN();
    $objDTO = $objMdUtlAdmGrpFilaRN->buscarGruposFilaVinculados(array($post,$arrPrms));

    return $objDTO;
  }
}
