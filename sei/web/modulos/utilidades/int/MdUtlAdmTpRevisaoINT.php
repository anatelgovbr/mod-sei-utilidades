<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4� REGI�O
*
* 06/08/2018 - criado por jaqueline.mendes
*
* Vers�o do Gerador de C�digo: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmTpRevisaoINT extends InfraINT {

  public static function montarSelectIdMdUtlAdmTpRevisao($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objMdUtlAdmTpRevisaoDTO = new MdUtlAdmTpRevisaoDTO();
    $objMdUtlAdmTpRevisaoDTO->retNumIdMdUtlAdmTpRevisao();
    $objMdUtlAdmTpRevisaoDTO->retNumIdMdUtlAdmTpRevisao();

    if ($strValorItemSelecionado!=null){
      $objMdUtlAdmTpRevisaoDTO->setBolExclusaoLogica(false);
      $objMdUtlAdmTpRevisaoDTO->adicionarCriterio(array('SinAtivo','IdMdUtlAdmTpRevisao'),array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),array('S',$strValorItemSelecionado),InfraDTO::$OPER_LOGICO_OR);
    }

    $objMdUtlAdmTpRevisaoDTO->setOrdNumIdMdUtlAdmTpRevisao(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdUtlAdmTpRevisaoRN = new MdUtlAdmTpRevisaoRN();
    $arrObjMdUtlAdmTpRevisaoDTO = $objMdUtlAdmTpRevisaoRN->listar($objMdUtlAdmTpRevisaoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdUtlAdmTpRevisaoDTO, 'IdMdUtlAdmTpRevisao', 'IdMdUtlAdmTpRevisao');
  }

  public static function montarSelectTpRevisao($idMdUtlAdmTpCtrlDesemp,$idMdUtlAdmTpRevisao = null)
  {
      $objMdUtlAdmTpRevisaoDTO  = new MdUtlAdmTpRevisaoDTO();
      $objMdUtlAdmTpRevisaoRN   = new MdUtlAdmTpRevisaoRN();

      $objMdUtlAdmTpRevisaoDTO->setNumIdMdUtlAdmTpCtrlDesemp($idMdUtlAdmTpCtrlDesemp);
      $objMdUtlAdmTpRevisaoDTO->retTodos();

      $objMdUtlAdmTpRevisao   = $objMdUtlAdmTpRevisaoRN->listar($objMdUtlAdmTpRevisaoDTO);
      $select = '<option value="" ></option>';

      if(count($objMdUtlAdmTpRevisao)>0) {
          foreach ($objMdUtlAdmTpRevisao as $revisao) {
              $selected="";
              if($idMdUtlAdmTpRevisao == $revisao->getNumIdMdUtlAdmTpRevisao()){
                  $selected = 'selected';
              }
              $select .= '<option value="'.$revisao->getNumIdMdUtlAdmTpRevisao().'_'.$revisao->getStrSinJustificativa().'" name="teste" id="tes" '.$selected.'>'.$revisao->getStrNome().'</option>';
          }
      }
      return $select;
       //   return parent::montarSelectArrInfraDTO("null", "", "", $objMdUtlAdmTpRevisao, 'IdMdUtlAdmTpRevisao', 'Nome');
  }
}
