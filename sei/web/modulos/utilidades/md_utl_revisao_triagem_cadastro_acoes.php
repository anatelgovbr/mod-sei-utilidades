<?php


//Get Dados da Triagem
$idsAtividades = $objTriagemRN->getIdsAtividadesTriagem($idProcedimento);

$idTriagem         = $objControleDsmpDTO->getNumIdMdUtlTriagem();
$objTriagemDTO     = $objTriagemRN->buscarObjTriagemPorId($idTriagem);
$strInformComp     = $objTriagemDTO->getStrInformacaoComplementar();

$MdUtlRelTriagemAtvRN  = new MdUtlRelTriagemAtvRN();
$MdUtlRelTriagemAtvDTO = new MdUtlRelTriagemAtvDTO();

$MdUtlRelTriagemAtvDTO->setNumIdMdUtlTriagem($idTriagem);
$MdUtlRelTriagemAtvDTO->retTodos();
$MdUtlRelTriagemAtvDTO->retStrNomeAtividade();

if ($isConsultar) {

    $disabled = 'disabled="disabled"';

    $MdUtlRevisaoDTO = new MdUtlRevisaoDTO();
    $MdUtlRevisaoRN = new MdUtlRevisaoRN();

    $idRevisao = $objControleDsmpDTO->getNumIdMdUtlRevisao();

    $MdUtlRevisaoDTO->setNumIdMdUtlRevisao($idRevisao);
    $MdUtlRevisaoDTO->retTodos();

    $MdUtlRevisao = $MdUtlRevisaoRN->consultar($MdUtlRevisaoDTO);

    $strInformCompRevisao = $MdUtlRevisao ? $MdUtlRevisao->getStrInformacoesComplementares() : '';
    $strEncaminhamento = $MdUtlRevisao ? $MdUtlRevisao->getStrStaEncaminhamentoRevisao() : '';

    $MdUtlRelRevisTrgAnlsDTO = new MdUtlRelRevisTrgAnlsDTO();
    $MdUtlRelRevisTrgAnlsRN = new MdUtlRelRevisTrgAnlsRN();

    if ($MdUtlRevisao) {
        $MdUtlRelRevisTrgAnlsDTO->setNumIdMdUtlRevisao($MdUtlRevisao->getNumIdMdUtlRevisao());
        $MdUtlRelRevisTrgAnlsDTO->retTodos();
        $arrMdUtlRelRevisTrgAnls = $MdUtlRelRevisTrgAnlsRN->listar($MdUtlRelRevisTrgAnlsDTO);
    }

}

    $numRegistro = $MdUtlRelTriagemAtvRN->contar($MdUtlRelTriagemAtvDTO);
    $MdUtlRelTriagemAtv = $MdUtlRelTriagemAtvRN->listar($MdUtlRelTriagemAtvDTO);

    $strResultado="";

    $strResultado .= '<table width="99%" class="infraTable" summary="Revisao">';
    $strResultado .= '<caption class="infraCaption">';
    $strResultado .= PaginaSEI::getInstance()->gerarCaptionTabela('Revisão:', $numRegistro);
    $strResultado .= '</caption>';

    //Cabeçalho da Tabela
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" style="display: none" width="10%">Id_RelTriagem</th>';
    //$strResultado .= '<th class="infraTh" width="15%">' . PaginaSEI::getInstance()->getThOrdenacao($objMdUtlAdmAtvSerieProdDTO, 'Atividade', 'NomeAtividade', $arrObjs) . '</th>';
    $strResultado .= '<th class="infraTh" width="13%">Atividade</th>';
    $strResultado .= '<th class="infraTh" width="15%">Resultado</th>';
    $strResultado .= '<th class="infraTh" width="14%">Justificativa</th>';
    $strResultado .= '<th class="infraTh" width="25%">Observação sobre a Revisão</th>';
    $strResultado .= '</tr>';

    $hdnTbRevisaoAnalise = "";

    for ($i = 0 ; $i < $numRegistro ; $i++){

            $displayJustificativa = 'style="display: none"';

            $idMdUtlRelTriagemAtv = $MdUtlRelTriagemAtv[$i]->getNumIdMdUtlRelTriagemAtv();

            if($isConsultar) {
                if($arrMdUtlRelRevisTrgAnls) {
                    $MdUtlRelRevisTrgAnls = InfraArray::filtrarArrInfraDTO($arrMdUtlRelRevisTrgAnls, 'IdMdUtlRelTriagemAtv', $idMdUtlRelTriagemAtv);
                    $strObservacao = $MdUtlRelRevisTrgAnls[0]->getStrObservacao();
                    $selRevisao = MdUtlAdmTpRevisaoINT::montarSelectTpRevisao($idTipoControle, $MdUtlRelRevisTrgAnls[0]->getNumIdMdUtlAdmTpRevisao());
                    
                    if ($MdUtlRelRevisTrgAnls[0]->getNumIdMdUtlAdmTpJustRevisao() > 0) {
                        $selJustRevisao = MdUtlAdmTpJustRevisaoINT::montarSelectJustRevisao($idTipoControle, $MdUtlRelRevisTrgAnls[0]->getNumIdMdUtlAdmTpJustRevisao());
                        $displayJustificativa = '';
                    }
                }
            }
            $validarSelect = $displayJustificativa == '' ? 'style="width:100%"' : $displayJustificativa;
            $strResultado .= '<tr class="infraTrClara">';
            $strResultado .= '<td style="display: none" >'.$idMdUtlRelTriagemAtv.'</td>';
            $strResultado .= '<td>'.$MdUtlRelTriagemAtv[$i]->getStrNomeAtividade().'</td>';
            $strResultado .= '<td>';
            $strResultado .= '<select  campo="R"  style="width:100%" class="infraSelect" '.$disabled.' id="selRev_'.$idMdUtlRelTriagemAtv.'" name="selRev_'.$idMdUtlRelTriagemAtv.'" onchange="verificarJustificativa(this);" >'.$selRevisao.'</select>';
            $strResultado .= '</td>';
            $strResultado .= '<td>';
            $strResultado .= '<select  campo="J"  '.$validarSelect.' class="infraSelect" '.$disabled.' id="selJust_'.$idMdUtlRelTriagemAtv.'" name="selJust_'.$idMdUtlRelTriagemAtv.'" >'.$selJustRevisao.'</select>';
            $strResultado .= '</td>';
            $strResultado .= '<td><input onpaste="return infraLimitarTexto(this,event,250);" onkeypress="return infraLimitarTexto(this,event,250);" class="inputObservacao" id="obs_'.$idMdUtlRelTriagemAtv.'" '.$disabled.' name="obs_'.$idMdUtlRelTriagemAtv.'" type="text" value="'.$strObservacao.'"/></td>';
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