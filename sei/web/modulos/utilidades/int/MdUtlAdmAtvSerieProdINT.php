<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 07/08/2018 - criado por jhon.carvalho
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmAtvSerieProdINT extends InfraINT {

  public static function montarSelectIdMdUtlAdmAtvSerieProd($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdMdUtlAdmAtividade='', $numIdMdUtlAdmTpProduto='', $numIdSerie=''){
    $objMdUtlAdmAtvSerieProdDTO = new MdUtlAdmAtvSerieProdDTO();
    $objMdUtlAdmAtvSerieProdDTO->retNumIdMdUtlAdmAtvSerieProd();
    $objMdUtlAdmAtvSerieProdDTO->retNumIdMdUtlAdmAtvSerieProd();

    if ($numIdMdUtlAdmAtividade!==''){
      $objMdUtlAdmAtvSerieProdDTO->setNumIdMdUtlAdmAtividade($numIdMdUtlAdmAtividade);
    }

    if ($numIdMdUtlAdmTpProduto!==''){
      $objMdUtlAdmAtvSerieProdDTO->setNumIdMdUtlAdmTpProduto($numIdMdUtlAdmTpProduto);
    }

    if ($numIdSerie!==''){
      $objMdUtlAdmAtvSerieProdDTO->setNumIdSerie($numIdSerie);
    }

    $objMdUtlAdmAtvSerieProdDTO->setOrdNumIdMdUtlAdmAtvSerieProd(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdUtlAdmAtvSerieProdRN = new MdUtlAdmAtvSerieProdRN();
    $arrObjMdUtlAdmAtvSerieProdDTO = $objMdUtlAdmAtvSerieProdRN->listar($objMdUtlAdmAtvSerieProdDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdUtlAdmAtvSerieProdDTO, 'IdMdUtlAdmAtvSerieProd', 'IdMdUtlAdmAtvSerieProd');
  }


  public static function montarSelectStaTipo($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objMdUtlAdmAtvSerieProdRN = new MdUtlAdmAtvSerieProdRN();

    $arrObjTipoMdUtlAdmAtvSerieProdDTO = $objMdUtlAdmAtvSerieProdRN->listarValoresTipo();

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjTipoMdUtlAdmAtvSerieProdDTO, 'StaTipo', 'Descricao');

  }

  /*Em Desuso
  public static function montarSelectStaAplicabilidadeSerie($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objMdUtlAdmAtvSerieProdRN = new MdUtlAdmAtvSerieProdRN();

    $arrObjAplicabilidadeSerieMdUtlAdmAtvSerieProdDTO = $objMdUtlAdmAtvSerieProdRN->listarValoresAplicabilidadeSerie();

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjAplicabilidadeSerieMdUtlAdmAtvSerieProdDTO, 'StaAplicabilidadeSerie', 'Descricao');

  }
  */
}
