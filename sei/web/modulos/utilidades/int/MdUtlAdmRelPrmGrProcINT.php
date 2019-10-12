<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 10/07/2018 - criado por jhon.cast
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmRelPrmGrProcINT extends InfraINT {

  public static function montarSelectIdMdUtlAdmParamGr($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdMdUtlAdmParamGr='', $numIdTipoProcedimento=''){
    $objMdUtlAdmRelPrmGrProcDTO = new MdUtlAdmRelPrmGrProcDTO();
    $objMdUtlAdmRelPrmGrProcDTO->retNumIdMdUtlAdmParamGr();
    $objMdUtlAdmRelPrmGrProcDTO->retNumIdTipoProcedimento();
    $objMdUtlAdmRelPrmGrProcDTO->retNumIdMdUtlAdmParamGr();

    if ($numIdMdUtlAdmParamGr!==''){
      $objMdUtlAdmRelPrmGrProcDTO->setNumIdMdUtlAdmParamGr($numIdMdUtlAdmParamGr);
    }

    if ($numIdTipoProcedimento!==''){
      $objMdUtlAdmRelPrmGrProcDTO->setNumIdTipoProcedimento($numIdTipoProcedimento);
    }

    $objMdUtlAdmRelPrmGrProcDTO->setOrdNumIdMdUtlAdmParamGr(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdUtlAdmRelPrmGrProcRN = new MdUtlAdmRelPrmGrProcRN();
    $arrObjMdUtlAdmRelPrmGrProcDTO = $objMdUtlAdmRelPrmGrProcRN->listar($objMdUtlAdmRelPrmGrProcDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdUtlAdmRelPrmGrProcDTO, array('IdMdUtlAdmParamGr','IdTipoProcedimento'), 'IdMdUtlAdmParamGr');
  }

  public static function autoCompletarTipoProcedimentoPorParametrizacao($strPalavrasPesquisa, $idParametro){
    
    $strPalavrasPesquisa = trim($strPalavrasPesquisa);
    
    if ($strPalavrasPesquisa != ''){
      $objTipoProcedimentoDTO = new MdUtlAdmRelPrmGrProcDTO();
      $objTipoProcedimentoDTO->retTodos();
      $objTipoProcedimentoDTO->setNumIdMdUtlAdmParamGr($idParametro);
      $objTipoProcedimentoDTO->retStrNomeProcedimento();
      $objTipoProcedimentoDTO->setOrdStrNomeProcedimento(InfraDTO::$TIPO_ORDENACAO_ASC);
      $objTipoProcedimentoDTO->setNumMaxRegistrosRetorno(50);

      $objTipoProcedimentoRN = new MdUtlAdmRelPrmGrProcRN();
      $arrObjTipoProcedimentoDTO = $objTipoProcedimentoRN->listar($objTipoProcedimentoDTO);
    
      $ret = array();
      $strPalavrasPesquisa = strtolower($strPalavrasPesquisa);
      foreach($arrObjTipoProcedimentoDTO as $objTipoProcedimentoDTO){
        if (strpos(strtolower($objTipoProcedimentoDTO->getStrNomeProcedimento()),$strPalavrasPesquisa)!==false){
          $ret[] = $objTipoProcedimentoDTO;
        }
      }
    
    } 
    return $ret;
  }
}
