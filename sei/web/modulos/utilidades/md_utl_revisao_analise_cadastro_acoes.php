<?php
/**
 * Created by PhpStorm.
 * User: jhon.carvalho
 * Date: 13/12/2018
 * Time: 16:34
 */


$objMdUtlAnaliseRN  = new MdUtlAnaliseRN();
$objMdUtlRevisaoRN  = new MdUtlRevisaoRN();

$arrObjRelAnaliseProdutoDTOAntigos = array();
$displayJustificativa = 'style="display: none"';
$exibirCol            = " display:none; ";
$strResultado         ="";
$idRevisao = $objControleDsmpDTO->getNumIdMdUtlRevisao();

$objMdUtlAnaliseDTO = new MdUtlAnaliseDTO();
$objMdUtlAnaliseDTO->setNumIdMdUtlAnalise($idMdUtlAnalise);
$objMdUtlAnaliseDTO->retTodos();
$objMdUtlAnalise = $objMdUtlAnaliseRN->consultar($objMdUtlAnaliseDTO);

$idUsuarioAnalise = $objMdUtlAnalise->getNumIdUsuario();

$strInformComp = $objMdUtlAnalise->getStrInformacoesComplementares();

$objMdUtlRelAnaliseProdutoDTO = new MdUtlRelAnaliseProdutoDTO();
$objMdUtlRelAnaliseProdutoRN  = new MdUtlRelAnaliseProdutoRN();
$objMdUtlRelAnaliseProdutoDTO->setNumIdMdUtlAnalise($objMdUtlAnalise->getNumIdMdUtlAnalise());
$objMdUtlRelAnaliseProdutoDTO->retTodos(true);
$arrMdUtlRelAnaliseProduto   = $objMdUtlRelAnaliseProdutoRN->listar($objMdUtlRelAnaliseProdutoDTO);

if ($isConsultar) {
    $disabled = 'disabled="disabled"';
    $objMdUtlRevisaoDTO = new MdUtlRevisaoDTO();
    $objMdUtlRevisaoDTO->setNumIdMdUtlRevisao($idRevisao);
    $objMdUtlRevisaoDTO->retTodos();

    $objMdUtlRevisaoDTO = $objMdUtlRevisaoRN->consultar($objMdUtlRevisaoDTO);
    $strInformCompRevisao = !is_null($objMdUtlRevisaoDTO) ? $objMdUtlRevisaoDTO->getStrInformacoesComplementares() : '';
    $strEncaminhamento = !is_null($objMdUtlRevisaoDTO) ? $objMdUtlRevisaoDTO->getStrStaEncaminhamentoRevisao() : '';

    $vlrAvaliacaoQualitativa = !is_null($objMdUtlRevisaoDTO) ? $objMdUtlRevisaoDTO->getNumAvaliacaoQualitativa() : '';

    if(is_null($strEncaminhamento) && !is_null($objMdUtlRevisaoDTO->getStrStaEncaminhamentoContestacao())){
        $strEncaminhamento = $objMdUtlRevisaoDTO->getStrStaEncaminhamentoContestacao();
    }

    $objMdUtlRelRevisTrgAnlsDTO = new MdUtlRelRevisTrgAnlsDTO();
    $objMdUtlRelRevisTrgAnlsRN = new MdUtlRelRevisTrgAnlsRN();
    $ckbRealizarAvalProdProd = '';
    if (!is_null($objMdUtlRevisaoDTO) && $objMdUtlRevisaoDTO->getStrSinRealizarAvalProdProd() == 'S' ) {
        $exibirCol = "";
        $ckbRealizarAvalProdProd = 'checked';
        $objMdUtlRelRevisTrgAnlsDTO->setNumIdMdUtlRevisao($idRevisao);
        $objMdUtlRelRevisTrgAnlsDTO->retTodos();
        $arrMdUtlRelRevisTrgAnls = $objMdUtlRelRevisTrgAnlsRN->listar($objMdUtlRelRevisTrgAnlsDTO);
    }
}

if ($isEdicao) {
    $objMdUtlRevisaoDTO = new MdUtlRevisaoDTO();
    $objMdUtlRevisaoDTO->setNumIdMdUtlRevisao($idRevisao);
    $objMdUtlRevisaoDTO->retTodos();

    $objMdUtlRevisaoDTO = $objMdUtlRevisaoRN->consultar($objMdUtlRevisaoDTO);

    $strInformCompRevisao = !is_null($objMdUtlRevisaoDTO) ? $objMdUtlRevisaoDTO->getStrInformacoesComplementares() : '';
    $strEncaminhamento = '';

    $objMdUtlRelRevisTrgAnlsDTO = new MdUtlRelRevisTrgAnlsDTO();
    $objMdUtlRelRevisTrgAnlsRN = new MdUtlRelRevisTrgAnlsRN();

    $objMdUtlRelRevisTrgAnlsDTO->setNumIdMdUtlRevisao($idRevisao);
    $objMdUtlRelRevisTrgAnlsDTO->retTodos();
    $arrMdUtlRelRevisTrgAnlsEdicaoDTO = $objMdUtlRelRevisTrgAnlsRN->listar($objMdUtlRelRevisTrgAnlsDTO);

    $objMdUtlRelAnaliseProdutoDTO = new MdUtlRelAnaliseProdutoDTO();
    $novaListaRelRevisTrgAnls = [];

    $idsRelProdutoAntigos = InfraArray::converterArrInfraDTO($arrMdUtlRelRevisTrgAnlsEdicaoDTO, 'IdMdUtlRelAnaliseProduto');
    if (count($idsRelProdutoAntigos) > 0) {
        $arrObjRelAnaliseProdutoDTOAntigos = $objMdUtlRelAnaliseProdutoRN->getArrObjPorIds($idsRelProdutoAntigos);

    }
}

if (!is_null($objMdUtlRevisaoDTO)) {
    $objMdUtlRelRevisTrgAnlsDTO->setNumIdMdUtlRevisao($idRevisao);
    $objMdUtlRelRevisTrgAnlsDTO->retTodos();
    $arrMdUtlRelRevisTrgAnls = $objMdUtlRelRevisTrgAnlsRN->listar($objMdUtlRelRevisTrgAnlsDTO);
}

$numRegistro = count($arrMdUtlRelAnaliseProduto);

if($numRegistro > 0) {

    #if(count($arrMdUtlRelRevisTrgAnls) > 0 && $isConsultar || !$isConsultar) {
    if( true ){
        $strResultado .= '<table width="99%" class="infraTable" summary="Revisao" id="tb_avaliacao">';
        $strResultado .= '<caption class="infraCaption">';
        $strResultado .= PaginaSEI::getInstance()->gerarCaptionTabela('Atividades e Produtos Entregues', $numRegistro);
        $strResultado .= '</caption>';
        //Cabe�alho da Tabela
        $strResultado .= '<tr>';
        $strResultado .= '<th class="infraTh" style="display: none" width="10%">Id_Analise</th>';
        $strResultado .= '<th class="infraTh" width="13%">Atividade</th>';
        $strResultado .= '<th class="infraTh" width="8%">Produto</th>';
        $strResultado .= '<th class="infraTh" width="8%">Documento</th>';
        $strResultado .= '<th class="infraTh" width="18%" style="text-align: left;">Observa��o sobre a An�lise</th>';
        $strResultado .= '<th class="infraTh" width="13%" style="'.$exibirCol.'">Resultado</th>';
        $strResultado .= '<th class="infraTh" width="13%" style="'.$exibirCol.'">Justificativa</th>';
        $strResultado .= '<th class="infraTh" width="20%" style="text-align: left; '.$exibirCol.'">Observa��o sobre a Avalia��o</th>';
        $strResultado .= '</tr>';

        $hdnTbRevisaoAnalise = "";

        for ($i = 0; $i < $numRegistro; $i++) {
            $displayJustificativa = 'style="display: none"';
            $strObservacao  = '';
            $strDocumento   = '';
            $selRevisao     = MdUtlAdmTpRevisaoINT::montarSelectTpRevisao($idTipoControle, null);
            $selJustRevisao = MdUtlAdmTpJustRevisaoINT::montarSelectJustRevisao($idTipoControle,  null);

            $idMdUtlRelAnaliseProduto = $arrMdUtlRelAnaliseProduto[$i]->getNumIdMdUtlRelAnaliseProduto();
            $bolDocSei = $arrMdUtlRelAnaliseProduto[$i]->getStrNomeSerie() != "" ? true : false;

            if ($isConsultar) {
                $arrMdUtlRelRevisTrgAnlsCompleto = !empty($arrMdUtlRelRevisTrgAnls) ? InfraArray::filtrarArrInfraDTO($arrMdUtlRelRevisTrgAnls, 'IdMdUtlRelAnaliseProduto', $idMdUtlRelAnaliseProduto) : array();

                if(count($arrMdUtlRelRevisTrgAnlsCompleto) > 0) {
                    $strObservacao = $arrMdUtlRelRevisTrgAnlsCompleto[0]->getStrObservacao();
                    $selRevisao = MdUtlAdmTpRevisaoINT::montarSelectTpRevisao($idTipoControle, $arrMdUtlRelRevisTrgAnlsCompleto[0]->getNumIdMdUtlAdmTpRevisao());

                    if ($arrMdUtlRelRevisTrgAnlsCompleto[0]->getNumIdMdUtlAdmTpJustRevisao() > 0) {
                        $selJustRevisao = MdUtlAdmTpJustRevisaoINT::montarSelectJustRevisao($idTipoControle, $arrMdUtlRelRevisTrgAnlsCompleto[0]->getNumIdMdUtlAdmTpJustRevisao());
                        $displayJustificativa = '';
                    }
                }
            }

            /*
            if ($isEdicao) {
                $arrMdRelAtividadeIgualDTO = InfraArray::filtrarArrInfraDTO($arrObjRelAnaliseProdutoDTOAntigos, 'IdMdUtlAdmAtividade', $arrMdUtlRelAnaliseProduto[$i]->getNumIdMdUtlAdmAtividade());
                $strAtributoSearch = $bolDocSei ? 'IdDocumento' : 'IdMdUtlAdmTpProduto';
                $valorSearch = $bolDocSei ? $arrMdUtlRelAnaliseProduto[$i]->getDblIdDocumento() : $arrMdUtlRelAnaliseProduto[$i]->getNumIdMdUtlAdmTpProduto();
                $arrMdRelIgualDTO = InfraArray::filtrarArrInfraDTO($arrMdRelAtividadeIgualDTO, $strAtributoSearch, $valorSearch);

                if (count($arrMdRelIgualDTO) > 0 && $arrMdRelIgualDTO[0] != null) {

                    $arrDadosAntigaRevisaoDTO = InfraArray::filtrarArrInfraDTO($arrMdUtlRelRevisTrgAnls, 'IdMdUtlRelAnaliseProduto', $arrMdRelIgualDTO[0]->getNumIdMdUtlRelAnaliseProduto());

                    if (count($arrDadosAntigaRevisaoDTO) > 0 && $arrDadosAntigaRevisaoDTO[0] != null) {
                        $selRevisao = MdUtlAdmTpRevisaoINT::montarSelectTpRevisao($idTipoControle, $arrDadosAntigaRevisaoDTO[0]->getNumIdMdUtlAdmTpRevisao());

                        if ($arrDadosAntigaRevisaoDTO[0]->getNumIdMdUtlAdmTpJustRevisao() != null) {
                            $selJustRevisao = MdUtlAdmTpJustRevisaoINT::montarSelectJustRevisao($idTipoControle, $arrDadosAntigaRevisaoDTO[0]->getNumIdMdUtlAdmTpJustRevisao());
                            $strObservacao = $arrDadosAntigaRevisaoDTO[0]->getStrObservacao();

                            $displayJustificativa = '';
                        }
                    }

                    MdUtlRevisaoINT::setObjUtilizadoAnaliseRevisao($arrObjRelAnaliseProdutoDTOAntigos, $arrMdRelIgualDTO);
                }
            }
            */

            $strResultado .= '<tr class="infraTrClara">';
            $strResultado .= '<td style="display: none" >' . $idMdUtlRelAnaliseProduto . '</td>';

            $vlrUnidEsf = !is_null($arrMdUtlRelAnaliseProduto[$i]->getNumTempoExecucao()) ? $arrMdUtlRelAnaliseProduto[$i]->getNumTempoExecucao() : 0;
            if( !empty( $idUsuarioAnalise )){
                $vlrUnidEsf =MdUtlAdmPrmGrUsuINT::retornaCalculoPercentualDesempenho( $vlrUnidEsf , $idTipoControle , $idUsuarioAnalise);
            }
            $vlrUnidEsf =  MdUtlAdmPrmGrINT::convertToHoursMins( $vlrUnidEsf );
            
            $strResultado .= '<td>' . $arrMdUtlRelAnaliseProduto[$i]->getStrNomeAtividade() . ' (' . MdUtlAdmAtividadeRN::$ARR_COMPLEXIDADE[$arrMdUtlRelAnaliseProduto[$i]->getNumComplexidadeAtividade()] . ') - ' . $vlrUnidEsf .'</td>';

            $strProduto = $bolDocSei ? $arrMdUtlRelAnaliseProduto[$i]->getStrNomeSerie() : $arrMdUtlRelAnaliseProduto[$i]->getStrNomeProduto();
            $strResultado .= '<td>' . $strProduto . '</td>';

            if ($bolDocSei) {
                $dblIdDocumento = $arrMdUtlRelAnaliseProduto[$i]->getDblIdDocumento();
                $strDocumento = $strProduto . " (" . $arrMdUtlRelAnaliseProduto[$i]->getStrDocumentoFormatado() . ")";
            }

            $strAcoesDocumento = '<a href="#" onclick="infraAbrirJanela(\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=documento_visualizar&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento=' . $idProcedimento . '&id_documento=' . $dblIdDocumento . '&arvore=1') . '\',\'janelaCancelarAssinaturaExterna\',850,600,\'location=0,status=1,resizable=1,scrollbars=1\')" tabindex="' . $numTabBotao . '" class="botaoSEI">' . $strDocumento . '</a>';

            $strResultado .= '<td>' . $strAcoesDocumento . '</td>';
            $strResultado .= '<td>'. $arrMdUtlRelAnaliseProduto[$i]->getStrObservacaoAnalise() .'</td>';
            $strResultado .= '<td style="'.$exibirCol.'">';
            $strResultado .= '<select campo="R" style="width:95%" class="infraSelect" ' . $disabled . ' id="selRev_' . $idMdUtlRelAnaliseProduto . '" name="selRev_' . $idMdUtlRelAnaliseProduto . '" onchange="verificarJustificativa(this);" >' . $selRevisao . '</select>';
            $strResultado .= '</td>';
            $strResultado .= '<td style="'.$exibirCol.'">';
            $strResultado .= '<select campo="J"  class="infraSelect" ' . $disabled . ' id="selJust_' . $idMdUtlRelAnaliseProduto . '" name="selJust_' . $idMdUtlRelAnaliseProduto . '" ' . $displayJustificativa . '>' . $selJustRevisao . '</select>';
            $strResultado .= '</td>';
            $strResultado .= '<td style="padding: 4px 10px 2px 3px; '.$exibirCol.'"><textarea onpaste="return infraLimitarTexto(this,event,250);" onkeypress="return infraLimitarTexto(this,event,250);" class="infraTextArea inputObservacao" id="obs_' . $idMdUtlRelAnaliseProduto . '" ' . $disabled . ' name="obs_' . $idMdUtlRelAnaliseProduto . '" style="width: 100%; '.$exibirCol.'">' . $strObservacao . '</textarea></td>';
            $strResultado .= '</tr>';

            if ($hdnTbRevisaoAnalise != "") {
                $hdnTbRevisaoAnalise .= "�";
            }

            $hdnTbRevisaoAnalise .= $idMdUtlRelAnaliseProduto;
            $hdnTbRevisaoAnalise .= "�selRev_" . $idMdUtlRelAnaliseProduto;
            $hdnTbRevisaoAnalise .= "�selJust_" . $idMdUtlRelAnaliseProduto;
            $hdnTbRevisaoAnalise .= "�obs_" . $idMdUtlRelAnaliseProduto;
        }

        $strResultado .= '</table>';
    }
}

$divInfComplementar = '<div id="divInformacaoComplementarAnalise" style="margin-top: 1.8%">
             <label id="lblInformacaoComplementar" style="display: block"  for="txaInformacaoComplementarAnlTri" class="infraLabelOpcional"
                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                 Informa��es Complementares da An�lise:
             </label>

             <textarea style="width: 79%" id="txaInformacaoComplementarAnlTri" disabled="disabled" name="txaInformacaoComplementarAnlTri" rows="3" class="infraTextArea" onkeypress="return infraMascaraTexto(this,event, 500);" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">'.$strInformComp.'</textarea>
         </div>';
