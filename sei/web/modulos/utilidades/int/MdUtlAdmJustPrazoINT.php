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
}
