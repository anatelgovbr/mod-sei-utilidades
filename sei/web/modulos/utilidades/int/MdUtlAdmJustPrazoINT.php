<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 25/09/2018 - criado por jhon.carvalho
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmJustPrazoINT extends InfraINT {

  public static function montarSelectIdMdUtlAdmJustPrazo($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objMdUtlAdmJustPrazoDTO = new MdUtlAdmJustPrazoDTO();
    $objMdUtlAdmJustPrazoDTO->retNumIdMdUtlAdmJustPrazo();
    $objMdUtlAdmJustPrazoDTO->retNumIdMdUtlAdmJustPrazo();

    if ($strValorItemSelecionado!=null){
      $objMdUtlAdmJustPrazoDTO->setBolExclusaoLogica(false);
      $objMdUtlAdmJustPrazoDTO->adicionarCriterio(array('SinAtivo','IdMdUtlAdmJustPrazo'),array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),array('S',$strValorItemSelecionado),InfraDTO::$OPER_LOGICO_OR);
    }

    $objMdUtlAdmJustPrazoDTO->setOrdNumIdMdUtlAdmJustPrazo(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdUtlAdmJustPrazoRN = new MdUtlAdmJustPrazoRN();
    $arrObjMdUtlAdmJustPrazoDTO = $objMdUtlAdmJustPrazoRN->listar($objMdUtlAdmJustPrazoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdUtlAdmJustPrazoDTO, 'IdMdUtlAdmJustPrazo', 'IdMdUtlAdmJustPrazo');
  }

  public static function montarSelectJustificativa($idTpControle, $tipoSolicitacao = null, $idSelect = null){
    $select = '';
    if(!is_null($tipoSolicitacao)){
      $objMdUtlAdmJustPrazoDTO = new MdUtlAdmJustPrazoDTO();

      if($tipoSolicitacao == MdUtlControleDsmpRN::$TP_SOLICITACAO_DILACAO) {
        $objMdUtlAdmJustPrazoDTO->setStrSinDilacao('S');
      }

      if($tipoSolicitacao == MdUtlControleDsmpRN::$TP_SOLICITACAO_SUSPENSAO) {
        $objMdUtlAdmJustPrazoDTO->setStrSinSuspensao('S');
      }

      if($tipoSolicitacao == MdUtlControleDsmpRN::$TP_SOLICITACAO_INTERRUPCAO) {
        $objMdUtlAdmJustPrazoDTO->setStrSinInterrupcao('S');
      }

      $objMdUtlAdmJustPrazoDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTpControle);
      $objMdUtlAdmJustPrazoDTO->setStrSinAtivo('S');
      $objMdUtlAdmJustPrazoDTO->retTodos();
      $objMdUtlAdmJustPrazoRN = new MdUtlAdmJustPrazoRN();

      if($objMdUtlAdmJustPrazoRN->contar($objMdUtlAdmJustPrazoDTO) > 0) {
        $arrObjs = $objMdUtlAdmJustPrazoRN->listar($objMdUtlAdmJustPrazoDTO);
        return parent::montarSelectArrInfraDTO(' ', '', $idSelect, $arrObjs, 'IdMdUtlAdmJustPrazo', 'Nome');
      }

    }

    return $select;
  }
}
