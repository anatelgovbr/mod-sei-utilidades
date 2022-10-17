<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/08/2018 - criado por jaqueline.mendes
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmTpJustRevisaoINT extends InfraINT {

  public static function montarSelectIdMdUtlAdmTpJustRevisao($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objMdUtlAdmTpJustRevisaoDTO = new MdUtlAdmTpJustRevisaoDTO();
    $objMdUtlAdmTpJustRevisaoDTO->retNumIdMdUtlAdmTpJustRevisao();
    $objMdUtlAdmTpJustRevisaoDTO->retNumIdMdUtlAdmTpJustRevisao();

    if ($strValorItemSelecionado!=null){
      $objMdUtlAdmTpJustRevisaoDTO->setBolExclusaoLogica(false);
      $objMdUtlAdmTpJustRevisaoDTO->adicionarCriterio(array('SinAtivo','IdMdUtlAdmTpJustRevisao'),array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),array('S',$strValorItemSelecionado),InfraDTO::$OPER_LOGICO_OR);
    }

    $objMdUtlAdmTpJustRevisaoDTO->setOrdNumIdMdUtlAdmTpJustRevisao(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdUtlAdmTpJustRevisaoRN = new MdUtlAdmTpJustRevisaoRN();
    $arrObjMdUtlAdmTpJustRevisaoDTO = $objMdUtlAdmTpJustRevisaoRN->listar($objMdUtlAdmTpJustRevisaoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdUtlAdmTpJustRevisaoDTO, 'IdMdUtlAdmTpJustRevisao', 'IdMdUtlAdmTpJustRevisao');
  }

  public static function montarSelectJustRevisao($idMdUtlAdmTpCtrlDesemp, $idMdUtlAdmTpJustRevisao = null)
  {
      $options = '<option value=""></option>';
      $objMdUtlAdmTpJustRevisaoDTO = new MdUtlAdmTpJustRevisaoDTO();
      $objMdUtlAdmTpJustRevisaoDTO->setNumIdMdUtlAdmTpCtrlDesemp($idMdUtlAdmTpCtrlDesemp);
      $objMdUtlAdmTpJustRevisaoDTO->retTodos();
      $objMdUtlAdmTpJustRevisaoRN = new MdUtlAdmTpJustRevisaoRN();

      $count = $objMdUtlAdmTpJustRevisaoRN->contar($objMdUtlAdmTpJustRevisaoDTO);
      if($count > 0) {
          $arrObjMdUtlAdmTpJustRevisaoDTO = $objMdUtlAdmTpJustRevisaoRN->listar($objMdUtlAdmTpJustRevisaoDTO);
          foreach($arrObjMdUtlAdmTpJustRevisaoDTO as $objDTO){
              $selected = !is_null($idMdUtlAdmTpJustRevisao) && $idMdUtlAdmTpJustRevisao == $objDTO->getNumIdMdUtlAdmTpJustRevisao() ? 'selected = selected' : '';
              $options .= '<option '.$selected.' value="'.$objDTO->getNumIdMdUtlAdmTpJustRevisao().'"> '.PaginaSEI::tratarHTML($objDTO->getStrNome()).' </option>';
          }
      }

      return $options;

  //return parent::montarSelectArrInfraDTO("", "", $idMdUtlAdmTpJustRevisao, $arrObjMdUtlAdmTpJustRevisaoDTO, 'IdMdUtlAdmTpJustRevisao', 'Nome');
  }
}
