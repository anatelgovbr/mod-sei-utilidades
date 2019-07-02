<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 21/12/2018 - criado por jhon.cast
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlRevisaoINT extends InfraINT {

  public static function montarSelectidMdUtlRevisao($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objMdUtlRevisaoDTO = new MdUtlRevisaoDTO();
    $objMdUtlRevisaoDTO->retNumidMdUtlRevisao();

    $objMdUtlRevisaoDTO->setOrdNumidMdUtlRevisao(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdUtlRevisaoRN = new MdUtlRevisaoRN();
    $arrObjMdUtlRevisaoDTO = $objMdUtlRevisaoRN->listar($objMdUtlRevisaoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdUtlRevisaoDTO, '', 'idMdUtlRevisao');
  }

  public static function montarSelectEncaminhamentoRevisao($strEncaminhamento){
      $arrOption[MdUtlRevisaoRN::$VOLTAR_PARA_RESPONSAVEL] = MdUtlRevisaoRN::$STR_VOLTAR_PARA_RESPONSAVEL;
      $arrOption[MdUtlRevisaoRN::$VOLTAR_PARA_FILA]        = MdUtlRevisaoRN::$STR_VOLTAR_PARA_FILA;
      $arrOption[MdUtlRevisaoRN::$FLUXO_FINALIZADO]        = MdUtlRevisaoRN::$STR_FLUXO_FINALIZADO;

      $option = '<option value=""></option>';
      foreach ($arrOption as $key => $op){

          $selected = "";
          if($strEncaminhamento == $key){
              $selected = 'selected';
          }
          $option .= '<option value="'.$key.'" '.$selected.'>'.$op.'</option>';

      }

      return $option;
  }

}
