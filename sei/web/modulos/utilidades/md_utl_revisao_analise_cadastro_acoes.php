<?php

/**
 * Created by PhpStorm.
 * User: jhon.carvalho
 * Date: 13/12/2018
 * Time: 16:34
 */

$objMdUtlAnaliseRN  = new MdUtlAnaliseRN();
$objMdUtlRevisaoRN  = new MdUtlRevisaoRN();

$arrObjRelAnaliseProdutoDTOAntigos  = array();
$displayJustificativa               = 'style="display: none"';
$exibirCol                          = " display:none; ";
$strResultado                       = "";
$idRevisao                          = $objControleDsmpDTO->getNumIdMdUtlRevisao();

// Retorna os dados da análise:
$objMdUtlAnaliseDTO = new MdUtlAnaliseDTO();
$objMdUtlAnaliseDTO->setNumIdMdUtlAnalise($idMdUtlAnalise);
$objMdUtlAnaliseDTO->retTodos();
$objMdUtlAnalise    = $objMdUtlAnaliseRN->consultar($objMdUtlAnaliseDTO);

$idUsuarioAnalise   = $objMdUtlAnalise->getNumIdUsuario();
$strInformComp      = $objMdUtlAnalise->getStrInformacoesComplementares();
$chkDistAutoTriagem = $objMdUtlAnalise->getStrDistAutoParaMim() ?: null;

// Retorna os dados da análise de cada produto:
$objMdUtlRelAnaliseProdutoDTO = new MdUtlRelAnaliseProdutoDTO();
$objMdUtlRelAnaliseProdutoRN  = new MdUtlRelAnaliseProdutoRN();
$objMdUtlRelAnaliseProdutoDTO->setNumIdMdUtlAnalise($objMdUtlAnalise->getNumIdMdUtlAnalise());

//marca as colunas que serao retornadas na consulta
$objMdUtlRelAnaliseProdutoDTO->retNumIdMdUtlRelAnaliseProduto();
$objMdUtlRelAnaliseProdutoDTO->retNumIdMdUtlAnalise();
$objMdUtlRelAnaliseProdutoDTO->retNumIdMdUtlAdmAtividade();
$objMdUtlRelAnaliseProdutoDTO->retNumIdMdUtlAdmTpProduto();
$objMdUtlRelAnaliseProdutoDTO->retNumIdMdUtlRelTriagemAtv();
$objMdUtlRelAnaliseProdutoDTO->retNumIdSerie();
$objMdUtlRelAnaliseProdutoDTO->retStrProtocoloFormatado();
$objMdUtlRelAnaliseProdutoDTO->retStrObservacaoAnalise();
$objMdUtlRelAnaliseProdutoDTO->retNumPrazoExecucaoAtividade();
$objMdUtlRelAnaliseProdutoDTO->retNumPrazoRevisaoAtividade();
$objMdUtlRelAnaliseProdutoDTO->retStrNomeSerie();
$objMdUtlRelAnaliseProdutoDTO->retStrNomeProduto();
$objMdUtlRelAnaliseProdutoDTO->retNumComplexidadeAtividade();
$objMdUtlRelAnaliseProdutoDTO->retStrNomeAtividade();
$objMdUtlRelAnaliseProdutoDTO->retStrSinNaoAplicarPercDsmpAtv();
$objMdUtlRelAnaliseProdutoDTO->retStrSinAtivoAnalise();
$objMdUtlRelAnaliseProdutoDTO->retNumTempoExecucao();
$objMdUtlRelAnaliseProdutoDTO->retNumTempoExecucaoAtribuido();
$objMdUtlRelAnaliseProdutoDTO->retStrSinObjPreenchido();
$objMdUtlRelAnaliseProdutoDTO->retDtaDataExecucao();

$arrMdUtlRelAnaliseProduto   = $objMdUtlRelAnaliseProdutoRN->listar($objMdUtlRelAnaliseProdutoDTO);

if ($isConsultar) {

    $disabled = 'disabled="disabled"';

    // Retorna os dados da revisão do item:
    $objMdUtlRevisaoDTO = new MdUtlRevisaoDTO();
    $objMdUtlRevisaoDTO->setNumIdMdUtlRevisao($idRevisao);
    $objMdUtlRevisaoDTO->retTodos();
    $objMdUtlRevisaoDTO = $objMdUtlRevisaoRN->consultar($objMdUtlRevisaoDTO);

    $vlrAvaliacaoQualitativa    = !is_null($objMdUtlRevisaoDTO) ? $objMdUtlRevisaoDTO->getNumAvaliacaoQualitativa() : '';
    $strInformCompRevisao       = !is_null($objMdUtlRevisaoDTO) ? $objMdUtlRevisaoDTO->getStrInformacoesComplementares() : '';
    $strEncaminhamento          = !is_null($objMdUtlRevisaoDTO) ? $objMdUtlRevisaoDTO->getStrStaEncaminhamentoRevisao() : '';

    if(is_null($strEncaminhamento) && !is_null($objMdUtlRevisaoDTO->getStrStaEncaminhamentoContestacao())){
        $strEncaminhamento = $objMdUtlRevisaoDTO->getStrStaEncaminhamentoContestacao();
    }

    $objMdUtlRelRevisTrgAnlsDTO = new MdUtlRelRevisTrgAnlsDTO();
    $objMdUtlRelRevisTrgAnlsRN  = new MdUtlRelRevisTrgAnlsRN();
    $ckbRealizarAvalProdProd    = '';

    if (!is_null($objMdUtlRevisaoDTO) && $objMdUtlRevisaoDTO->getStrSinRealizarAvalProdProd() == 'S' ) {

        $exibirCol = "";
        $ckbRealizarAvalProdProd = 'checked="checked"';

        $objMdUtlRelRevisTrgAnlsDTO->setNumIdMdUtlRevisao($idRevisao);
        $objMdUtlRelRevisTrgAnlsDTO->retTodos();
        $arrMdUtlRelRevisTrgAnls = $objMdUtlRelRevisTrgAnlsRN->listar($objMdUtlRelRevisTrgAnlsDTO);

    }

    // Deixa oculto o campo que sinaliza a a marcacao da distribuicao automatica para triagem
    $chkDistAutoTriagem = false;

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

    $objMdUtlRelRevisTrgAnlsRN->validaDistAutoTriagAnalise( $objMdUtlAnalise, $objMdUtlFilaRN, $validaDistAutoTriagem, $strNomeUsuarioDistrAuto, $idUsuarioDistrAuto );
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

        $strResultado .= '<table class="infraTable" summary="Revisao" id="tb_avaliacao" style="width: 100%">';
        $strResultado .= '<caption class="infraCaption">';
        $strResultado .= PaginaSEI::getInstance()->gerarCaptionTabela('Atividades e Produtos Entregues', $numRegistro);
        $strResultado .= '</caption>';
        //Cabeçalho da Tabela
        $strResultado .= '<tr>';
        $strResultado .= '<th class="infraTh" style="display: none">Id_Analise</th>';
        $strResultado .= '<th class="infraTh" style="display: none">Atividade</th>';
        $strResultado .= '<th class="infraTh">Produto</th>';
        #$strResultado .= '<th class="infraTh" width="8%">Documento</th>';
        $strResultado .= '<th class="infraTh" align="left">Observação sobre a Análise</th>';
        $strResultado .= '<th class="infraTh" width="16%" style="'.$exibirCol.'">Resultado</th>';
        $strResultado .= '<th class="infraTh" width="22%" style="'.$exibirCol.'">Justificativa</th>';
        $strResultado .= '<th class="infraTh" width="25%" style="'.$exibirCol.'">Observação sobre a Avaliação</th>';
        $strResultado .= '</tr>';

        $hdnTbRevisaoAnalise = "";
        $idCtrlIdAtv         = 0;
        $numColsPan          = empty($ckbRealizarAvalProdProd) ? 2 : 5;
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
                        $displayJustificativa = 'style="display: block"';
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
            $vlrUnidEsf = !is_null($arrMdUtlRelAnaliseProduto[$i]->getNumTempoExecucaoAtribuido())
            ? $arrMdUtlRelAnaliseProduto[$i]->getNumTempoExecucaoAtribuido()
            : 0;

            $vlrUnidEsf      = MdUtlAdmPrmGrINT::convertToHoursMins( $vlrUnidEsf );
            $rowNmAtv        = '';
            $ctrlNmAtividade = $arrMdUtlRelAnaliseProduto[$i]->getStrNomeAtividade() . ' (' . MdUtlAdmAtividadeRN::$ARR_COMPLEXIDADE[$arrMdUtlRelAnaliseProduto[$i]->getNumComplexidadeAtividade()] . ') - ' . $vlrUnidEsf;
            
            if( $idCtrlIdAtv != $arrMdUtlRelAnaliseProduto[$i]->getNumIdMdUtlRelTriagemAtv() ){
                if($arrMdUtlRelAnaliseProduto[$i]->getDtaDataExecucao() != "") {
                    $dataExecucaoAtividade = $arrMdUtlRelAnaliseProduto[$i]->getDtaDataExecucao();
                }
                $rowNmAtv = '<tr style="height: 50px;" class="table-success">
                    <td colspan="'.$numColsPan.'">
                        <div class="row">
                            <div class="col-10">'.$ctrlNmAtividade.'</div>
                            <div class="col-2 dataRelatarDiaDia" style="display: '.$displayDatas.'"> 
                                <div class="float-right input-group mb-3" style="margin-bottom: 0 !important">
                                    <label id="lblDtAnaliseAtividade" for="txtDtAnaliseAtividade'.$arrMdUtlRelAnaliseProduto[$i]->getNumIdMdUtlRelTriagemAtv().'"  class="infraLabelObrigatorio" style="margin-bottom: 0; line-height: 2">Data: </label>
                                    <input type="text" id="txtDtAnaliseAtividade'.$arrMdUtlRelAnaliseProduto[$i]->getNumIdMdUtlRelTriagemAtv().'" name="txtDtAnaliseAtividade'.$arrMdUtlRelAnaliseProduto[$i]->getNumIdMdUtlRelTriagemAtv().'" onchange="return validaPeriodoDataDiaADia(this);"
                                    onkeypress="return infraMascara(this, event,\'##/##/####\')" class="infraText form-control txtDtAnaliseAtividade"
                                    value="'.PaginaSEI::tratarHTML($dataExecucaoAtividade).'" tabindex="'.PaginaSEI::getInstance()->getProxTabDados().'" disabled>
                                    <img src="'.PaginaSEI::getInstance()->getDiretorioSvgGlobal() .'/calendario.svg" id="imgCalDthCorte"
                                    title="Selecionar Data/Hora Inicial" alt="Selecionar Data de Corte" class="infraImg"
                                    onclick="infraCalendario(\'txtDtAnaliseAtividade'.$arrMdUtlRelAnaliseProduto[$i]->getNumIdMdUtlRelTriagemAtv().'\',this,false,\''.$dataExecucaoAtividade.'\');">
                                </div>
                            </div>
                        </div> 
                    </td>
                </tr>';
                $idCtrlIdAtv = $arrMdUtlRelAnaliseProduto[$i]->getNumIdMdUtlRelTriagemAtv();
            }

            $strResultado .= $rowNmAtv;

            $strResultado .= '<tr class="infraTrClara">';
            $strResultado .= '<td style="display: none">' . $idMdUtlRelAnaliseProduto . '</td>';

            $strResultado .= '<td style="display: none">' . $ctrlNmAtividade .'</td>';

            $strProduto = $bolDocSei ? $arrMdUtlRelAnaliseProduto[$i]->getStrNomeSerie() : $arrMdUtlRelAnaliseProduto[$i]->getStrNomeProduto();

            $numSei = $arrMdUtlRelAnaliseProduto[$i]->getStrProtocoloFormatado();

            $strResultado .= '<td>' . $strProduto .' '. $numSei  . '</td>';

            #$strAcoesDocumento = '<a href="#" onclick="infraAbrirJanela(\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=documento_visualizar&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento=' . $idProcedimento . '&id_documento=' . $dblIdDocumento . '&arvore=1') . '\',\'janelaCancelarAssinaturaExterna\',850,600,\'location=0,status=1,resizable=1,scrollbars=1\')" tabindex="' . $numTabBotao . '" class="botaoSEI">' . $strDocumento . '</a>';
            #$strResultado .= '<td>' . $numSei . '</td>';
            $strResultado .= '<td>'. $arrMdUtlRelAnaliseProduto[$i]->getStrObservacaoAnalise() .'</td>';
            $strResultado .= '<td style="'.$exibirCol.'">';
            $strResultado .= '<select campo="R" class="form-control infraSelect" ' . $disabled . ' id="selRev_' . $idMdUtlRelAnaliseProduto . '" name="selRev_' . $idMdUtlRelAnaliseProduto . '" onchange="verificarJustificativa(this);" >' . $selRevisao . '</select>';
            $strResultado .= '</td>';
            $strResultado .= '<td style="'.$exibirCol.'">';
            $strResultado .= '<select campo="J" class="form-control infraSelect" ' . $disabled . ' id="selJust_' . $idMdUtlRelAnaliseProduto . '" name="selJust_' . $idMdUtlRelAnaliseProduto . '" ' . $displayJustificativa . '>' . $selJustRevisao . '</select>';
            $strResultado .= '</td>';
            $strResultado .= '<td style="'.$exibirCol.'">';
            $strResultado .= '<textarea onpaste="return infraMascaraTexto(this,event,250);" onkeypress="return infraLimitarTexto(this,event,250);" class="form-control" id="obs_' . $idMdUtlRelAnaliseProduto . '" ' . $disabled . ' name="obs_' . $idMdUtlRelAnaliseProduto . '" ' . $displayJustificativa . '>' . $strObservacao . '</textarea>';
            $strResultado .= '</td>';
            $strResultado .= '</tr>';
            // inputObservacao

            if ($hdnTbRevisaoAnalise != "") {
                $hdnTbRevisaoAnalise .= "¥";
            }

            $hdnTbRevisaoAnalise .= $idMdUtlRelAnaliseProduto;
            $hdnTbRevisaoAnalise .= "±selRev_" . $idMdUtlRelAnaliseProduto;
            $hdnTbRevisaoAnalise .= "±selJust_" . $idMdUtlRelAnaliseProduto;
            $hdnTbRevisaoAnalise .= "±obs_" . $idMdUtlRelAnaliseProduto;
        }

        $strResultado .= '</table>';

    }
}

$divInfComplementar =   '<div id="divInformacaoComplementarAnalise" class="form-group my-3">
                            <label id="lblInformacaoComplementar" style="display: block"  for="txaInformacaoComplementarAnlTri" class="infraLabelOpcional"
                                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                Informações Complementares da Análise:
                            </label>

                            <textarea id="txaInformacaoComplementarAnlTri" disabled="disabled" name="txaInformacaoComplementarAnlTri" rows="3" class="form-control infraTextArea" onkeypress="return infraMascaraTexto(this,event, 500);" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">'.$strInformComp.'</textarea>
                        </div>';
