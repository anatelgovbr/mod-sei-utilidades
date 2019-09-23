<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 07/08/2018 - criado por jhon.carvalho
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmAtividadeINT extends InfraINT {

  public static function montarSelectIdMdUtlAdmAtividade($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdMdUtlAdmTpCtrlDesemp=''){

    $objMdUtlAdmAtividadeDTO = new MdUtlAdmAtividadeDTO();
    $objMdUtlAdmAtividadeDTO->retNumIdMdUtlAdmAtividade();
    $objMdUtlAdmAtividadeDTO->retNumIdMdUtlAdmAtividade();

    if ($numIdMdUtlAdmTpCtrlDesemp!==''){
      $objMdUtlAdmAtividadeDTO->setNumIdMdUtlAdmTpCtrlDesemp($numIdMdUtlAdmTpCtrlDesemp);
    }

    if ($strValorItemSelecionado!=null){
      $objMdUtlAdmAtividadeDTO->setBolExclusaoLogica(false);
      $objMdUtlAdmAtividadeDTO->adicionarCriterio(array('SinAtivo','IdMdUtlAdmAtividade'),array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),array('S',$strValorItemSelecionado),InfraDTO::$OPER_LOGICO_OR);
    }

    $objMdUtlAdmAtividadeDTO->setOrdNumIdMdUtlAdmAtividade(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdUtlAdmAtividadeRN = new MdUtlAdmAtividadeRN();
    $arrObjMdUtlAdmAtividadeDTO = $objMdUtlAdmAtividadeRN->listar($objMdUtlAdmAtividadeDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdUtlAdmAtividadeDTO, 'IdMdUtlAdmAtividade', 'IdMdUtlAdmAtividade');

  }

  public static function montarSelectTipoDocumentoExterno($strValorItemSelecionado = null){

      $serieDTO = new SerieDTO();
      $serieRN = new SerieRN();

      $serieDTO->setStrStaAplicabilidade(array(SerieRN::$TA_INTERNO_EXTERNO, SerieRN::$TA_EXTERNO),InfraDTO::$OPER_IN);
      $serieDTO->setStrSinInterno('N');

      $serieDTO->retNumIdSerie();
      $serieDTO->retStrNome();
      $serieDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

      $arrObjSerieDTO = $serieRN->listarRN0646($serieDTO);

      return parent::montarSelectArrInfraDTO(0, " ", $strValorItemSelecionado, $arrObjSerieDTO, 'IdSerie', 'Nome');

  }


    public static function montarSelectTipoDocumentoInterno($strValorItemSelecionado = null){

        $serieDTO = new SerieDTO();
        $serieRN = new SerieRN();

        $serieDTO->setStrStaAplicabilidade(array(SerieRN::$TA_INTERNO_EXTERNO, SerieRN::$TA_INTERNO),InfraDTO::$OPER_IN);
        $serieDTO->setStrSinInterno('N');

        $serieDTO->retNumIdSerie();
        $serieDTO->retStrNome();
        $serieDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);
        $arrObjSerieDTO = $serieRN->listarRN0646($serieDTO);

        return parent::montarSelectArrInfraDTO(0, " ", $strValorItemSelecionado, $arrObjSerieDTO, 'IdSerie', 'Nome');

    }

    public static function autoCompletarAtividadeFiltroGrupo($strPalavrasPesquisa, $idTpCtrl, $idsGrupoAtividade, $idsTipoProcedimento){

        $mdUtlAdmAtividadeDTO = new MdUtlAdmAtividadeDTO();
        $mdUtlAdmAtividadeRN = new MdUtlAdmAtividadeRN();

        $mdUtlAdmAtividadeDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTpCtrl);
        $mdUtlAdmAtividadeDTO->setStrSinAtivo('S');
        $mdUtlAdmAtividadeDTO->retTodos();
        $mdUtlAdmAtividadeDTO->setNumMaxRegistrosRetorno(50);

        if(!is_null($idsGrupoAtividade)) {
            $objGrupoFilaAtvRN = new MdUtlAdmGrpFilaProcRN();
            $idsGrupoFormatado = PaginaSEI::getInstance()->getArrValuesSelect($idsGrupoAtividade);
            $idsAtividade = $objGrupoFilaAtvRN->getAtividadePorIdGrupoFila(array($idsGrupoFormatado, $idsTipoProcedimento));

            if(!is_null($idsAtividade)) {
                $mdUtlAdmAtividadeDTO->setNumIdMdUtlAdmAtividade($idsAtividade, InfraDTO::$OPER_IN);
            }else{
                return InfraAjax::gerarXMLItensArrInfraDTO(null, 'IdMdUtlAdmAtividade', 'Nome');
            }
        }

        if (!InfraString::isBolVazia($strPalavrasPesquisa)){

            $strPalavrasPesquisa = InfraString::prepararIndexacao($strPalavrasPesquisa);

            $arrPalavrasPesquisa = explode(' ',$strPalavrasPesquisa);
            $numPalavras = count($arrPalavrasPesquisa);
            for($i=0;$i<$numPalavras;$i++){
                $arrPalavrasPesquisa[$i] = '%'.$arrPalavrasPesquisa[$i].'%';
            }

            if ($numPalavras==1){
                $mdUtlAdmAtividadeDTO->setStrNome($arrPalavrasPesquisa[0],InfraDTO::$OPER_LIKE);
            }else{
                $a = array_fill(0,count($arrPalavrasPesquisa),'Nome');
                $c = array_fill(0,count($arrPalavrasPesquisa),InfraDTO::$OPER_LIKE);
                $d = array_fill(0,count($arrPalavrasPesquisa)-1,InfraDTO::$OPER_LOGICO_AND);
                $mdUtlAdmAtividadeDTO->adicionarCriterio($a,$c,$arrPalavrasPesquisa,$d);
            }
        }

        $mdUtlAdmAtividade = $mdUtlAdmAtividadeRN->listar($mdUtlAdmAtividadeDTO);

        $arrRetorno = array();
        foreach($mdUtlAdmAtividade as $objDTO){
            $possuiAnalise = $objDTO->getStrSinAnalise();
            $vlAnalise     = $possuiAnalise == 'S' ? $objDTO->getNumUndEsforcoAtv() : $objDTO->getNumUndEsforcoRev();
            $novoId = $objDTO->getNumIdMdUtlAdmAtividade().'_'.$possuiAnalise.'_'.$vlAnalise;
            $objDTO->setStrIdAutoComplete($novoId);
            $arrRetorno[] = $objDTO;
        }

        $xml = InfraAjax::gerarXMLItensArrInfraDTO($arrRetorno, 'IdAutoComplete', 'Nome');

        return $xml;
    }

    public static function autoCompletarAtividade($strPalavrasPesquisa, $idTpCtrl){

      $mdUtlAdmAtividadeDTO = new MdUtlAdmAtividadeDTO();
        $mdUtlAdmAtividadeRN = new MdUtlAdmAtividadeRN();

        $mdUtlAdmAtividadeDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTpCtrl);
        $mdUtlAdmAtividadeDTO->setStrSinAtivo('S');
        $mdUtlAdmAtividadeDTO->retTodos();
        $mdUtlAdmAtividadeDTO->setNumMaxRegistrosRetorno(50);


        if (!InfraString::isBolVazia($strPalavrasPesquisa)){

            $strPalavrasPesquisa = InfraString::prepararIndexacao($strPalavrasPesquisa);

            $arrPalavrasPesquisa = explode(' ',$strPalavrasPesquisa);
            $numPalavras = count($arrPalavrasPesquisa);
            for($i=0;$i<$numPalavras;$i++){
                $arrPalavrasPesquisa[$i] = '%'.$arrPalavrasPesquisa[$i].'%';
            }

            if ($numPalavras==1){
                $mdUtlAdmAtividadeDTO->setStrNome($arrPalavrasPesquisa[0],InfraDTO::$OPER_LIKE);
            }else{
                $a = array_fill(0,count($arrPalavrasPesquisa),'Nome');
                $c = array_fill(0,count($arrPalavrasPesquisa),InfraDTO::$OPER_LIKE);
                $d = array_fill(0,count($arrPalavrasPesquisa)-1,InfraDTO::$OPER_LOGICO_AND);
                $mdUtlAdmAtividadeDTO->adicionarCriterio($a,$c,$arrPalavrasPesquisa,$d);
            }
        }

        $mdUtlAdmAtividade = $mdUtlAdmAtividadeRN->listar($mdUtlAdmAtividadeDTO);
        $xml = InfraAjax::gerarXMLItensArrInfraDTO($mdUtlAdmAtividade, 'IdMdUtlAdmAtividade', 'Nome');

        return $xml;
    }

    public static function montarSelectAtividadesTriagem($selAtividadeCampo, $arrObjsTriagemAtividade)
    {

        $objTriagemAtividadeDTO = InfraArray::distinctArrInfraDTO($arrObjsTriagemAtividade, 'IdMdUtlAdmAtividade');


        return parent::montarSelectArrInfraDTO(null, null, $selAtividadeCampo, $objTriagemAtividadeDTO, 'IdMdUtlAdmAtividade', 'NomeAtividade');
    }

}
