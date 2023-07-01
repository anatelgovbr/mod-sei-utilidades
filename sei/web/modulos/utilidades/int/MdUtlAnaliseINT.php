<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/11/2018 - criado por jaqueline.cast
*
* Versão do Gerador de Código: 1.42.0
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

  public static function validaPostAnalise( $p , &$objInfraException ){
    $arrMsg = array(
      'Não foi capturado o parâmetro: hdnItensSelecionados. Por favor, atualize sua página e tente novamente.',
    );
    
    if( !isset( $p['hdnItensSelecionados'] ) || strlen( $p['hdnItensSelecionados'] ) == 0 ){
      $objInfraException->adicionarValidacao( $arrMsg[0] );
    }
  }

}