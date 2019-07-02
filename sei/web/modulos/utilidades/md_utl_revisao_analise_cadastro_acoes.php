<?php
/**
 * Created by PhpStorm.
 * User: jhon.carvalho
 * Date: 13/12/2018
 * Time: 16:34
 */

$objMdUtlAnaliseDTO = new MdUtlAnaliseDTO();
$objMdUtlAnaliseRN  = new MdUtlAnaliseRN();

$objMdUtlAnaliseDTO->setNumIdMdUtlAnalise($idMdUtlAnalise);
$objMdUtlAnaliseDTO->retTodos();

$objMdUtlAnalise = $objMdUtlAnaliseRN->consultar($objMdUtlAnaliseDTO);
$strInformComp = $objMdUtlAnalise->getStrInformacoesComplementares();

$objMdUtlRelAnaliseProdutoDTO = new MdUtlRelAnaliseProdutoDTO();
$objMdUtlRelAnaliseProdutoRN  = new MdUtlRelAnaliseProdutoRN();

$objMdUtlRelAnaliseProdutoDTO->setNumIdMdUtlAnalise($objMdUtlAnalise->getNumIdMdUtlAnalise());
$objMdUtlRelAnaliseProdutoDTO->retTodos(true);

$objMdUtlRelAnaliseProduto = $objMdUtlRelAnaliseProdutoRN->listar($objMdUtlRelAnaliseProdutoDTO);

$strResultado="";
$numRegistro = count($objMdUtlRelAnaliseProduto);

if($isConsultar){

    $disabled = 'disabled="disabled"';
    $idRevisao = $objControleDsmpDTO->getNumIdMdUtlRevisao();

    $MdUtlRevisaoDTO = new MdUtlRevisaoDTO();
    $MdUtlRevisaoRN  = new MdUtlRevisaoRN();

    $MdUtlRevisaoDTO->setNumIdMdUtlRevisao($idRevisao);
    $MdUtlRevisaoDTO->retTodos();

    $MdUtlRevisao = $MdUtlRevisaoRN->consultar($MdUtlRevisaoDTO);
    $strInformCompRevisao = !is_null($MdUtlRevisao) ?  $MdUtlRevisao->getStrInformacoesComplementares() : '';
    $strEncaminhamento = !is_null($MdUtlRevisao) ? $MdUtlRevisao->getStrStaEncaminhamentoRevisao() : '';

    $MdUtlRelRevisTrgAnlsDTO = new MdUtlRelRevisTrgAnlsDTO();
    $MdUtlRelRevisTrgAnlsRN  = new MdUtlRelRevisTrgAnlsRN();

    if(!is_null($MdUtlRevisao)) {
        $MdUtlRelRevisTrgAnlsDTO->setNumIdMdUtlRevisao($idRevisao);
        $MdUtlRelRevisTrgAnlsDTO->retTodos();
        $arrMdUtlRelRevisTrgAnls = $MdUtlRelRevisTrgAnlsRN->listar($MdUtlRelRevisTrgAnlsDTO);
    }

}



if($numRegistro > 0) {

    if(count($arrMdUtlRelRevisTrgAnls) > 0 && $isConsultar || !$isConsultar) {
        $strResultado .= '<table width="99%" class="infraTable" summary="Revisao">';
        $strResultado .= '<caption class="infraCaption">';
        $strResultado .= PaginaSEI::getInstance()->gerarCaptionTabela('Revisão', $numRegistro);
        $strResultado .= '</caption>';
        //Cabeçalho da Tabela
        $strResultado .= '<tr>';
        $strResultado .= '<th class="infraTh" style="display: none" width="10%">Id_Analise</th>';
        $strResultado .= '<th class="infraTh" width="13%">Atividade</th>';
        $strResultado .= '<th class="infraTh" width="12%">Produto</th>';
        $strResultado .= '<th class="infraTh" width="17%">Documento</th>';
        $strResultado .= '<th class="infraTh" width="13%" style="text-align: left;">Observação sobre a Análise</th>';
        $strResultado .= '<th class="infraTh" width="13%">Resultado</th>';
        $strResultado .= '<th class="infraTh" width="13%">Justificativa</th>';
        $strResultado .= '<th class="infraTh" width="20%" style="text-align: left;">Observação sobre a Revisão</th>';
        $strResultado .= '</tr>';

        $hdnTbRevisaoAnalise = "";
        for ($i = 0; $i < $numRegistro; $i++) {

            $displayJustificativa = 'style="display: none"';
            $idMdUtlRelAnaliseProduto = $objMdUtlRelAnaliseProduto[$i]->getNumIdMdUtlRelAnaliseProduto();

            if ($isConsultar) {
                $MdUtlRelRevisTrgAnls = InfraArray::filtrarArrInfraDTO($arrMdUtlRelRevisTrgAnls, 'IdMdUtlRelAnaliseProduto', $idMdUtlRelAnaliseProduto);

                $strObservacao = $MdUtlRelRevisTrgAnls[0]->getStrObservacao();
                $selRevisao = MdUtlAdmTpRevisaoINT::montarSelectTpRevisao($idTipoControle, $MdUtlRelRevisTrgAnls[0]->getNumIdMdUtlAdmTpRevisao());

                if ($MdUtlRelRevisTrgAnls[0]->getNumIdMdUtlAdmTpJustRevisao() > 0) {
                    $selJustRevisao = MdUtlAdmTpJustRevisaoINT::montarSelectJustRevisao($idTipoControle, $MdUtlRelRevisTrgAnls[0]->getNumIdMdUtlAdmTpJustRevisao());
                    $displayJustificativa = '';
                }
            }

            $strResultado .= '<tr class="infraTrClara">';
            $strResultado .= '<td style="display: none" >' . $idMdUtlRelAnaliseProduto . '</td>';
            $strResultado .= '<td>' . $objMdUtlRelAnaliseProduto[$i]->getStrNomeAtividade() . '</td>';

            $bolDocSei = $objMdUtlRelAnaliseProduto[$i]->getStrNomeSerie() != "" ? true : false;
            $strProduto = $bolDocSei ? $objMdUtlRelAnaliseProduto[$i]->getStrNomeSerie() : $objMdUtlRelAnaliseProduto[$i]->getStrNomeProduto();
            $strResultado .= '<td>' . $strProduto . '</td>';

            $strDocumento = "";
            if ($bolDocSei) {
                $dblIdDocumento = $objMdUtlRelAnaliseProduto[$i]->getDblIdDocumento();
                $strDocumento = $strProduto . " (" . $objMdUtlRelAnaliseProduto[$i]->getStrDocumentoFormatado() . ")";
            }

            $strAcoesDocumento = '<a href="#" onclick="infraAbrirJanela(\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=documento_visualizar&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento=' . $idProcedimento . '&id_documento=' . $dblIdDocumento . '&arvore=1') . '\',\'janelaCancelarAssinaturaExterna\',850,600,\'location=0,status=1,resizable=1,scrollbars=1\')" tabindex="' . $numTabBotao . '" class="botaoSEI">' . $strDocumento . '</a>';

            $strResultado .= '<td>' . $strAcoesDocumento . '</td>';
            $strResultado .= '<td align="center"><img src="modulos/utilidades/imagens/obsAnalise.png" title="' . $objMdUtlRelAnaliseProduto[$i]->getStrObservacaoAnalise() . '" alt="teste" class="infraImg" style="width: 16px;height: 16px"/></td>';
            $strResultado .= '<td>';
            $strResultado .= '<select campo="R" style="width:95%" class="infraSelect" ' . $disabled . ' id="selRev_' . $idMdUtlRelAnaliseProduto . '" name="selRev_' . $idMdUtlRelAnaliseProduto . '" onchange="verificarJustificativa(this);" >' . $selRevisao . '</select>';
            $strResultado .= '</td>';
            $strResultado .= '<td>';
            $strResultado .= '<select campo="J"  class="infraSelect" ' . $disabled . ' id="selJust_' . $idMdUtlRelAnaliseProduto . '" name="selJust_' . $idMdUtlRelAnaliseProduto . '" ' . $displayJustificativa . '>' . $selJustRevisao . '</select>';
            $strResultado .= '</td>';
            $strResultado .= '<td><input onpaste="return infraLimitarTexto(this,event,250);" onkeypress="return infraLimitarTexto(this,event,250);" class="inputObservacao" id="obs_' . $idMdUtlRelAnaliseProduto . '" ' . $disabled . ' name="obs_' . $idMdUtlRelAnaliseProduto . '" type="text" value="' . $strObservacao . '"/></td>';
            $strResultado .= '</tr>';

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

$divInfComplementar = '<div id="divInformacaoComplementarAnalise" style="margin-top: 1.8%">
             <label id="lblInformacaoComplementar" style="display: block"  for="txaInformacaoComplementar" class="infraLabelOpcional"
                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                 Informações Complementares da Análise:
             </label>

             <textarea style="width: 79%" id="txaInformacaoComplementarAnlTri" disabled="disabled" name="txaInformacaoComplementarAnlTri" rows="3" class="infraTextArea" onkeypress="return infraMascaraTexto(this,event, 500);" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">'.$strInformComp.'</textarea>
         </div>';