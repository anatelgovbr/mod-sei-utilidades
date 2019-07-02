<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/11/2018 - criado por jaqueline.cast
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlTriagemINT extends InfraINT {

  public static function montarSelectIdMdUtlTriagem($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objMdUtlTriagemDTO = new MdUtlTriagemDTO();
    $objMdUtlTriagemDTO->retNumIdMdUtlTriagem();

    if ($strValorItemSelecionado!=null){
      $objMdUtlTriagemDTO->setBolExclusaoLogica(false);
      $objMdUtlTriagemDTO->adicionarCriterio(array('SinAtivo',''),array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),array('S',$strValorItemSelecionado),InfraDTO::$OPER_LOGICO_OR);
    }

    $objMdUtlTriagemDTO->setOrdNumIdMdUtlTriagem(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdUtlTriagemRN = new MdUtlTriagemRN();
    $arrObjMdUtlTriagemDTO = $objMdUtlTriagemRN->listar($objMdUtlTriagemDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdUtlTriagemDTO, '', 'IdMdUtlTriagem');
  }

  public static function validarGrupoAtividade($jsonIdsGrupoAtv, $idTipoProcedimento, $idTpCtrl){
    $idsGrupoAtividade = json_decode($jsonIdsGrupoAtv);

    $mdUtlAdmAtividadeDTO = new MdUtlAdmAtividadeDTO();
    $mdUtlAdmAtividadeRN = new MdUtlAdmAtividadeRN();

    $mdUtlAdmAtividadeDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTpCtrl);
    $mdUtlAdmAtividadeDTO->setStrSinAtivo('S');
    $mdUtlAdmAtividadeDTO->retTodos();
    $mdUtlAdmAtividadeDTO->setNumMaxRegistrosRetorno(50);

    if(!is_null($idsGrupoAtividade)) {
      $objGrupoFilaAtvRN = new MdUtlAdmGrpFilaProcRN();
      $idsAtividade = $objGrupoFilaAtvRN->getAtividadePorIdGrupoFila(array($idsGrupoAtividade, $idTipoProcedimento));

      if(count($idsAtividade) > 0) {
        $idsAtividade = array_unique($idsAtividade);
        $xml = '<Dados>';
        foreach ($idsAtividade as $id) {
          $xml .= '<idsAtividade' . $id . '>';
          $xml .= $id;
          $xml .= '</idsAtividade' . $id . '>';
        }
        $xml .= '</Dados>';

        return $xml;
      }
    }
    
    return null;
  }


}
