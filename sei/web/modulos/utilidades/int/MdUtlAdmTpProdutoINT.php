<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/08/2018 - criado por jaqueline.mendes
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmTpProdutoINT extends InfraINT {

  public static function montarSelectIdMdUtlAdmTpProduto($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objMdUtlAdmTpProdutoDTO = new MdUtlAdmTpProdutoDTO();
    $objMdUtlAdmTpProdutoDTO->retNumIdMdUtlAdmTpProduto();
    $objMdUtlAdmTpProdutoDTO->retNumIdMdUtlAdmTpProduto();

    if ($strValorItemSelecionado!=null){
      $objMdUtlAdmTpProdutoDTO->setBolExclusaoLogica(false);
      $objMdUtlAdmTpProdutoDTO->adicionarCriterio(array('SinAtivo','IdMdUtlAdmTpProduto'),array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),array('S',$strValorItemSelecionado),InfraDTO::$OPER_LOGICO_OR);
    }

    $objMdUtlAdmTpProdutoDTO->setOrdNumIdMdUtlAdmTpProduto(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdUtlAdmTpProdutoRN = new MdUtlAdmTpProdutoRN();
    $arrObjMdUtlAdmTpProdutoDTO = $objMdUtlAdmTpProdutoRN->listar($objMdUtlAdmTpProdutoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdUtlAdmTpProdutoDTO, 'IdMdUtlAdmTpProduto', 'IdMdUtlAdmTpProduto');
  }

    public static function montarSelectTpProduto($idTipoControle = null , $strPrimeiroItemValor = null, $strPrimeiroItemDescricao = null, $strValorItemSelecionado = null){


        $objMdUtlAdmTpProdutoDTO = new MdUtlAdmTpProdutoDTO();
        $objMdUtlAdmTpProdutoDTO->retNumIdMdUtlAdmTpProduto();
        $objMdUtlAdmTpProdutoDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTipoControle);
        $objMdUtlAdmTpProdutoDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);
        $objMdUtlAdmTpProdutoDTO->retStrNome();

        if ($strValorItemSelecionado!=null){
            $objMdUtlAdmTpProdutoDTO->setBolExclusaoLogica(false);
            $objMdUtlAdmTpProdutoDTO->adicionarCriterio(array('SinAtivo','IdMdUtlAdmTpProduto'),array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),array('S',$strValorItemSelecionado),InfraDTO::$OPER_LOGICO_OR);
        }

        $objMdUtlAdmTpProdutoDTO->setOrdNumIdMdUtlAdmTpProduto(InfraDTO::$TIPO_ORDENACAO_ASC);

        $objMdUtlAdmTpProdutoRN = new MdUtlAdmTpProdutoRN();
        $arrObjMdUtlAdmTpProdutoDTO = $objMdUtlAdmTpProdutoRN->listar($objMdUtlAdmTpProdutoDTO);

        return parent::montarSelectArrInfraDTO(0, " ", $strValorItemSelecionado, $arrObjMdUtlAdmTpProdutoDTO, 'IdMdUtlAdmTpProduto', 'Nome');
    }

}
