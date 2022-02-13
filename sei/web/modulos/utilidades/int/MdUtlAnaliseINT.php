<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4� REGI�O
*
* 06/11/2018 - criado por jaqueline.cast
*
* Vers�o do Gerador de C�digo: 1.42.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAnaliseINT extends InfraINT {

  public static function montarSelectIdMdUtlAnalise($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objMdUtlAnaliseDTO = new MdUtlAnaliseDTO();
    $objMdUtlAnaliseDTO->retNumIdMdUtlAnalise();

    $objMdUtlAnaliseDTO->setOrdNumIdMdUtlAnalise(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdUtlAnaliseRN = new MdUtlAnaliseRN();
    $arrObjMdUtlAnaliseDTO = $objMdUtlAnaliseRN->listar($objMdUtlAnaliseDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdUtlAnaliseDTO, '', 'IdMdUtlAnalise');
  }

  public static function validarDocumentoSEI($numeroSEI, $idSerieSolicitado, $idProcedimento){
    $xml  = '<Dados>';

    if ($numeroSEI != '') {
          $arrParams  = array($numeroSEI, $idProcedimento, $idSerieSolicitado);
          $objRnGeral = new MdUtlRegrasGeraisRN();
          $arrDados = $objRnGeral->retornaArrDadosDocumentoSEI($arrParams);
          $strErro  = $arrDados['erro'] ? '1' : '0';
          $xml .= '<Erro>'.$strErro.'</Erro>';
          $xml .= '<Msg>'.$arrDados['msg'].'</Msg>';
    }

    $xml .= '</Dados>';

    return $xml;
  }

}
