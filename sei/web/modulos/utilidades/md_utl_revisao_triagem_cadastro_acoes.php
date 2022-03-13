<?php


//Get Dados da Triagem
$idsAtividades = $objTriagemRN->getIdsAtividadesTriagem($idProcedimento);

$idTriagem         = $objControleDsmpDTO->getNumIdMdUtlTriagem();
$objTriagemDTO     = $objTriagemRN->buscarObjTriagemPorId($idTriagem);
$strInformComp     = $objTriagemDTO->getStrInformacaoComplementar();

$MdUtlRelTriagemAtvRN  = new MdUtlRelTriagemAtvRN();
$objMdUtlRelTriagemAtvDTO = new MdUtlRelTriagemAtvDTO();

$objMdUtlRelTriagemAtvDTO->setNumIdMdUtlTriagem($idTriagem);
$objMdUtlRelTriagemAtvDTO->retTodos();
$objMdUtlRelTriagemAtvDTO->retNumComplexidadeAtividade() ;
$objMdUtlRelTriagemAtvDTO->retStrNomeAtividade();

$exibirCol = " display:none; ";

if ($isConsultar) {

    $disabled = 'disabled="disabled"';
    $ckbRealizarAvalProdProd = '';

    $objMdUtlRevisaoDTO = new MdUtlRevisaoDTO();
    $MdUtlRevisaoRN = new MdUtlRevisaoRN();

    $idRevisao = $objControleDsmpDTO->getNumIdMdUtlRevisao();

    $objMdUtlRevisaoDTO->setNumIdMdUtlRevisao($idRevisao);
    $objMdUtlRevisaoDTO->retTodos();

    $objMdUtlRevisaoDTO = $MdUtlRevisaoRN->consultar($objMdUtlRevisaoDTO);

    $strInformCompRevisao = $objMdUtlRevisaoDTO ? $objMdUtlRevisaoDTO->getStrInformacoesComplementares() : '';

    $strEncaminhamento = !is_null($objMdUtlRevisaoDTO) ? $objMdUtlRevisaoDTO->getStrStaEncaminhamentoRevisao() : '';

    if(is_null($strEncaminhamento) && !is_null($objMdUtlRevisaoDTO->getStrStaEncaminhamentoContestacao())){
        $strEncaminhamento = $objMdUtlRevisaoDTO->getStrStaEncaminhamentoContestacao();
    }

    $objMdUtlRelRevisTrgAnlsDTO = new MdUtlRelRevisTrgAnlsDTO();
    $MdUtlRelRevisTrgAnlsRN = new MdUtlRelRevisTrgAnlsRN();

    if ( $objMdUtlRevisaoDTO && $objMdUtlRevisaoDTO->getStrSinRealizarAvalProdProd() == 'S' ) {
        $exibirCol = "";
        $ckbRealizarAvalProdProd = 'checked';
        $objMdUtlRelRevisTrgAnlsDTO->setNumIdMdUtlRevisao($objMdUtlRevisaoDTO->getNumIdMdUtlRevisao());
        $objMdUtlRelRevisTrgAnlsDTO->retTodos();
        $arrMdUtlRelRevisTrgAnls = $MdUtlRelRevisTrgAnlsRN->listar($objMdUtlRelRevisTrgAnlsDTO);
    }
}

if ($isEdicao) {

    $objMdUtlRevisaoDTO = new MdUtlRevisaoDTO();
    $MdUtlRevisaoRN = new MdUtlRevisaoRN();

    $idRevisao = $objControleDsmpDTO->getNumIdMdUtlRevisao();

    $objMdUtlRevisaoDTO->setNumIdMdUtlRevisao($idRevisao);
    $objMdUtlRevisaoDTO->retTodos();

    $objMdUtlRevisaoDTO = $MdUtlRevisaoRN->consultar($objMdUtlRevisaoDTO);

    $strInformCompRevisao = $objMdUtlRevisaoDTO ? $objMdUtlRevisaoDTO->getStrInformacoesComplementares() : '';
    $strEncaminhamento = '';

    $objMdUtlRelRevisTrgAnlsDTO = new MdUtlRelRevisTrgAnlsDTO();
    $MdUtlRelRevisTrgAnlsRN = new MdUtlRelRevisTrgAnlsRN();

    $objMdUtlRelRevisTrgAnlsDTO->setNumIdMdUtlRevisao($idRevisao);
    $objMdUtlRelRevisTrgAnlsDTO->retTodos();
    $objMdUtlRelRevisTrgAnlsDTO->retNumIdMdUtlAdmAtividade();

    //array com as atividades da triagem da última avaliação
    $arrMdUtlRelRevisTrgAnls = $MdUtlRelRevisTrgAnlsRN->listar($objMdUtlRelRevisTrgAnlsDTO);

    $objUltimoRelTriagemAtvRN  = new MdUtlRelTriagemAtvRN();
    $objUltimoRelTriagemAtvDTO = new MdUtlRelTriagemAtvDTO();

    $arrObjIguais = [];

    $idsRelAtividadesAntigas = InfraArray::converterArrInfraDTO($arrMdUtlRelRevisTrgAnls, 'IdMdUtlRelTriagemAtv');
    if(count($idsRelAtividadesAntigas) > 0) {
        $arrObjRelAtividadesDTOAntigos = $objUltimoRelTriagemAtvRN->getObjsRelTriagemAtividade($idsRelAtividadesAntigas);

    }
}

if ($MdUtlRevisao) {
    $objMdUtlRelRevisTrgAnlsDTO->setNumIdMdUtlRevisao($MdUtlRevisao->getNumIdMdUtlRevisao());
    $objMdUtlRelRevisTrgAnlsDTO->retTodos();
    $arrMdUtlRelRevisTrgAnls = $MdUtlRelRevisTrgAnlsRN->listar($objMdUtlRelRevisTrgAnlsDTO);
}

$numRegistro = $MdUtlRelTriagemAtvRN->contar($objMdUtlRelTriagemAtvDTO);
$MdUtlRelTriagemAtv = $MdUtlRelTriagemAtvRN->listar($objMdUtlRelTriagemAtvDTO);

$strResultado="";

$strResultado .= '<table width="99%" class="infraTable" summary="Revisao" id="tb_avaliacao">';
$strResultado .= '<caption class="infraCaption">';
$strResultado .= PaginaSEI::getInstance()->gerarCaptionTabela('Avaliação:', $numRegistro);
$strResultado .= '</caption>';

//Cabeçalho da Tabela
$strResultado .= '<tr>';
$strResultado .= '<th class="infraTh" style="display: none" width="10%">Id_RelTriagem</th>';
//$strResultado .= '<th class="infraTh" width="15%">' . PaginaSEI::getInstance()->getThOrdenacao($objMdUtlAdmAtvSerieProdDTO, 'Atividade', 'NomeAtividade', $arrObjs) . '</th>';
$strResultado .= '<th class="infraTh" width="13%">Atividade</th>';
$strResultado .= '<th class="infraTh" width="15%" style="'.$exibirCol.'">Resultado</th>';
$strResultado .= '<th class="infraTh" width="14%" style="'.$exibirCol.'">Justificativa</th>';
$strResultado .= '<th class="infraTh" width="25%" style="'.$exibirCol.'">Observação sobre a Avaliação</th>';
$strResultado .= '</tr>';

$hdnTbRevisaoAnalise = "";

for ($i = 0 ; $i < $numRegistro ; $i++){

    $displayJustificativa = 'style="display: none"';
    $strObservacao = '';
    $selRevisao = MdUtlAdmTpRevisaoINT::montarSelectTpRevisao($idTipoControle, null);
    $selJustRevisao = MdUtlAdmTpJustRevisaoINT::montarSelectJustRevisao($idTipoControle,  null);

    $idMdUtlRelTriagemAtv = $MdUtlRelTriagemAtv[$i]->getNumIdMdUtlRelTriagemAtv();

    if($isConsultar) {
        if($arrMdUtlRelRevisTrgAnls) {
            $objMdUtlRelRevisTrgAnls = InfraArray::filtrarArrInfraDTO($arrMdUtlRelRevisTrgAnls, 'IdMdUtlRelTriagemAtv', $idMdUtlRelTriagemAtv);
            $strObservacao = count($objMdUtlRelRevisTrgAnls) > 0 ? $objMdUtlRelRevisTrgAnls[0]->getStrObservacao() : '';
            $idTpRevisao = count($objMdUtlRelRevisTrgAnls) > 0 ? $objMdUtlRelRevisTrgAnls[0]->getNumIdMdUtlAdmTpRevisao() : null;
            $selRevisao = MdUtlAdmTpRevisaoINT::montarSelectTpRevisao($idTipoControle, $idTpRevisao);

            if (count($objMdUtlRelRevisTrgAnls) > 0 && $objMdUtlRelRevisTrgAnls[0]->getNumIdMdUtlAdmTpJustRevisao() > 0) {
                $selJustRevisao = MdUtlAdmTpJustRevisaoINT::montarSelectJustRevisao($idTipoControle, $objMdUtlRelRevisTrgAnls[0]->getNumIdMdUtlAdmTpJustRevisao());
                $displayJustificativa = '';
            }
        }
    } else {

        if($isEdicao) {

            if (count($arrMdUtlRelRevisTrgAnls) > 0 && $arrMdUtlRelRevisTrgAnls != null) {

                $arrDadosAntigaRevisaoDTO = InfraArray::filtrarArrInfraDTO($arrMdUtlRelRevisTrgAnls, 'IdMdUtlAdmAtividade', $MdUtlRelTriagemAtv[$i]->getNumIdMdUtlAdmAtividade());

                if(count($arrDadosAntigaRevisaoDTO) > 0 && $arrDadosAntigaRevisaoDTO[0] != null) {

                    $selRevisao = MdUtlAdmTpRevisaoINT::montarSelectTpRevisao($idTipoControle, $arrDadosAntigaRevisaoDTO[0]->getNumIdMdUtlAdmTpRevisao());

                    if ($arrDadosAntigaRevisaoDTO[0]->getNumIdMdUtlAdmTpJustRevisao() > 0) {
                        $selJustRevisao = MdUtlAdmTpJustRevisaoINT::montarSelectJustRevisao($idTipoControle, $arrDadosAntigaRevisaoDTO[0]->getNumIdMdUtlAdmTpJustRevisao());
                        $strObservacao = $arrDadosAntigaRevisaoDTO[0]->getStrObservacao();
                        $displayJustificativa = '';
                    }

                    MdUtlRevisaoINT::setObjUtilizadoTriagemRevisao($arrMdUtlRelRevisTrgAnls, $arrDadosAntigaRevisaoDTO);
                }
            }
        }
    }

    $validarSelect = $displayJustificativa == '' ? 'style="width:100%"' : $displayJustificativa;
    $strResultado .= '<tr class="infraTrClara">';
    $strResultado .= '<td style="display: none" >'.$idMdUtlRelTriagemAtv.'</td>';
    $strResultado .= '<td>'.$MdUtlRelTriagemAtv[$i]->getStrNomeAtividade() . ' - ' . MdUtlAdmAtividadeRN::$ARR_COMPLEXIDADE[$MdUtlRelTriagemAtv[$i]->getNumComplexidadeAtividade()].'</td>';
    $strResultado .= '<td style="'.$exibirCol.'">';
    $strResultado .= '<select  campo="R"  style="width:100%" class="infraSelect" '.$disabled.' id="selRev_'.$idMdUtlRelTriagemAtv.'" name="selRev_'.$idMdUtlRelTriagemAtv.'" onchange="verificarJustificativa(this);" >'.$selRevisao.'</select>';
    $strResultado .= '</td>';
    $strResultado .= '<td style="'.$exibirCol.'">';
    $strResultado .= '<select  campo="J"  '.$validarSelect.' class="infraSelect" '.$disabled.' id="selJust_'.$idMdUtlRelTriagemAtv.'" name="selJust_'.$idMdUtlRelTriagemAtv.'" >'.$selJustRevisao.'</select>';
    $strResultado .= '</td>';
    $strResultado .= '<td style="'.$exibirCol.'"><input onpaste="return infraLimitarTexto(this,event,250);" onkeypress="return infraLimitarTexto(this,event,250);" class="inputObservacao" id="obs_'.$idMdUtlRelTriagemAtv.'" '.$disabled.' name="obs_'.$idMdUtlRelTriagemAtv.'" type="text" value="'.$strObservacao.'" style="'.$exibirCol.'"/></td>';
    $strResultado .= '</tr>';

    if($hdnTbRevisaoAnalise != ""){
        $hdnTbRevisaoAnalise.="¥";
    }

    $hdnTbRevisaoAnalise.=$idMdUtlRelTriagemAtv;
    $hdnTbRevisaoAnalise.="±selRev_".$idMdUtlRelTriagemAtv;
    $hdnTbRevisaoAnalise.="±selJust_".$idMdUtlRelTriagemAtv;
    $hdnTbRevisaoAnalise.="±obs_".$idMdUtlRelTriagemAtv;
}

$strResultado .= '</table>';

$divInfComplementar = '<div id="divInformacaoComplementarTriagem" style="margin-top: 1.8%;">
             <label id="lblInformacaoComplementar" style="display: block" for="txaInformacaoComplementar" class="infraLabelOpcional" disabled="disabled"
                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                 Informações Complementares da Triagem:
             </label>

             <textarea style="width: 79%" id="txaInformacaoComplementarAnlTri" name="txaInformacaoComplementarAnlTri" disabled="disabled" rows="3" class="infraTextarea" onkeypress="return infraMascaraTexto(this,event, 500);" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">'.$strInformComp.'</textarea>
         </div>';