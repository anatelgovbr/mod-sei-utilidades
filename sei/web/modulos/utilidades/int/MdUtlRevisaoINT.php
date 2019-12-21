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

  public static function montarSelectEncaminhamento($strEncaminhamento){
      $arrOption[MdUtlRevisaoRN::$VOLTAR_PARA_RESPONSAVEL] = MdUtlRevisaoRN::$STR_VOLTAR_PARA_RESPONSAVEL;
      $arrOption[MdUtlRevisaoRN::$FLUXO_FINALIZADO]        = MdUtlRevisaoRN::$STR_FLUXO_FINALIZADO;
      $arrOption[MdUtlRevisaoRN::$VOLTAR_PARA_FILA]        = MdUtlRevisaoRN::$STR_VOLTAR_PARA_FILA;

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

  public static function montarSelectEncaminhamentoContestacao($strEncaminhamento){
      $arrOption[MdUtlRevisaoRN::$MANTER_O_RESPONSAVEL]        = MdUtlRevisaoRN::$STR_MANTER_O_RESPONSAVEL;
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

  public static function montarSelectSinRetorno($valorSelecionado = ''){

        $select = '<option value=""></option>';

        $arrAssociar = array( MdUtlRevisaoRN::$ASSOCIAR_SIM => 'Sim',
                                MdUtlRevisaoRN::$ASSOCIAR_NAO =>'Não');
        foreach ($arrAssociar as $key=>$associar){

            $add= '';

            if($valorSelecionado == $key){
                $add= 'selected = selected';
            }

            $select.='<option '.$add.' value="'.$key.'">'.$associar.'</option>';
        }

        return $select;
    }

    public static function setObjUtilizadoAnaliseRevisao(&$arrObjRelAnaliseProdutoDTOAntigos, $arrMdRelIgualDTO){
      $idRelAnaliseProdutoDTO = $arrMdRelIgualDTO[0]->getNumIdMdUtlRelAnaliseProduto();
        for ($i = 0; $i < count($arrObjRelAnaliseProdutoDTOAntigos); $i++) {
                if($arrObjRelAnaliseProdutoDTOAntigos[$i]->getNumIdMdUtlRelAnaliseProduto() == $idRelAnaliseProdutoDTO) {
                    unset($arrObjRelAnaliseProdutoDTOAntigos[$i]);
                    $arrObjRelAnaliseProdutoDTOAntigos = array_values($arrObjRelAnaliseProdutoDTOAntigos);
                }
        }
    }

    public static function setObjUtilizadoTriagemRevisao(&$arrObjRelAtividadesDTOAntigos, $arrDadosAntigaRevisaoDTO){
      $idRelTriagemAtividadeDTO = $arrDadosAntigaRevisaoDTO[0]->getNumIdMdUtlRelTriagemAtv();
        for ($i = 0; $i < count($arrObjRelAtividadesDTOAntigos); $i++) {
                if($arrObjRelAtividadesDTOAntigos[$i]->getNumIdMdUtlRelTriagemAtv() == $idRelTriagemAtividadeDTO) {
                    unset($arrObjRelAtividadesDTOAntigos[$i]);
                    $arrObjRelAtividadesDTOAntigos = array_values($arrObjRelAtividadesDTOAntigos);
                }
        }
    }

}
