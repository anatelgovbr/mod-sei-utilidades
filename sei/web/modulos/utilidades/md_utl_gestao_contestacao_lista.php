<?php
/**
 * Created by PhpStorm.
 * User: thamires.zamai
 * Date: 27/09/2019
 * Time: 10:52
 */

if (!is_null($objMdUtlControleDsmpDTOCont)) {

    $numRegistrosCont = count($objMdUtlControleDsmpDTOCont);
    $caption = $numRegistrosCont == 1 ? 'Pendente de Resposta: '. $numRegistrosCont .' registro' : 'Pendentes de Resposta: '. $numRegistrosCont .' registros';

    //Tabela de resultado.
    if ($numRegistrosCont > 0) {
        $strCaptionCont .= '<caption class="infraCaption">';
        $strCaptionCont .= '<span class="spnCaptionRegistros" >' . $caption . '</span>';
        $strCaptionCont .= '</caption>';

        //Tabela Contestação
        $strResultadoContest .= '<table width="100%" class="infraTable" summary="Processos" id="tbCtrlProcesso">';


        //Cabeçalho da Tabela
        $strResultadoContest .= '<tr>';

        $strResultadoContest .= '<th class="infraTh" width="5%" style="text-align: left; min-width: 175px"> Processo </th>';
        $strResultadoContest .= '<th class="infraTh" width="10%" style="text-align: left"> Tipo de Controle </th>';
        $strResultadoContest .= '<th class="infraTh" width="10%" style="text-align: left"> Servidor </th>';
        $strResultadoContest .= '<th class="infraTh" width="8%" style="text-align: left"> Data da Contestação </th>';
        $strResultadoContest .= '<th class="infraTh" width="10%" style="text-align: left"> Justificativa </th>';
        $strResultadoContest .= '<th class="infraTh" width="11%" style="text-align: left"> Revisor</th>';
        $strResultadoContest .= '<th class="infraTh" width="6%" style="text-align: center"> Ações </th>';

        $strResultadoContest .= '</tr>';

        //Linhas
        $strCssTr = '<tr class="infraTrEscura">';

        for ($i = 0; $i < $numRegistrosCont; $i++) {

            $numIdControleDsmpCont     = $objMdUtlControleDsmpDTOCont[$i]->getNumIdMdUtlControleDsmp();
            $dblIdProcedimentoCont     = $objMdUtlControleDsmpDTOCont[$i]->getDblIdProcedimento();
            $strNomeProcCont           = $objMdUtlControleDsmpDTOCont[$i]->getStrNomeTipoProcesso();
            $numIdTriagemCont          = $objMdUtlControleDsmpDTOCont[$i]->getNumIdMdUtlTriagem();
            $strProcessoCont           = $objMdUtlControleDsmpDTOCont[$i]->getStrProtocoloProcedimentoFormatado();
            $strNmTpCtrlCont           = $objMdUtlControleDsmpDTOCont[$i]->getStrNomeTpControle();
            $strJustificativaCont      = $objMdUtlControleDsmpDTOCont[$i]->getStrNomeJustContestacao();
            $strObservacaoCont         = $objMdUtlControleDsmpDTOCont[$i]->getStrInformacoesComplementares();
            $numIdContest              = $objMdUtlControleDsmpDTOCont[$i]->getNumIdMdUtlAdmJustContest();
            $strNomeUsuarioCont        = $objMdUtlControleDsmpDTOCont[$i]->getStrNomeUsuarioDistribuicao();
            $strSiglaUsuarioCont       = $objMdUtlControleDsmpDTOCont[$i]->getStrSiglaUsuarioDistribuicao();
            $idContestRevisaoExistente = $objMdUtlControleDsmpDTOCont[$i]->getNumIdMdUtlContestRevisao();
            $staAtendimento            = $objMdUtlControleDsmpDTOCont[$i]->getStrStaAtendimentoDsmp();
            $dthDataSolicitacaoCont = explode(' ',$objMdUtlControleDsmpDTOCont[$i]->getDthAtual());
            $usuRevisorCont = MdUtlHistControleDsmpINT::getusuarioRevisor($dblIdProcedimentoCont);

            $linkProcedimentoCont = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem=md_utl_gestao_solicitacoes_lista&id_procedimento=' . $dblIdProcedimentoCont . '');
            $linkAprovarCont      = SessaoSEI::getInstance()->assinarLink($strUrlContest . 'aprovar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_controle_desempenho=' . $numIdControleDsmpCont . '&id_contest='.$idContestRevisaoExistente . '');
            $linkReprovarCont     = SessaoSEI::getInstance()->assinarLink($strUrlContest . 'reprovar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_controle_desempenho=' . $numIdControleDsmpCont . '&id_contest=' . $idContestRevisaoExistente . '');

            $bolRegistroAtivo = true;

            $strCssTr = !$bolRegistroAtivo ? '<tr class="trVermelha">' : ($strCssTr == '<tr class="infraTrClara">' ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">');
            $strResultadoContest .= $strCssTr;

            //Linha Tipo de Solicitação
            $strResultadoContest .= '<td class="tdIdProcedimentoCont" style="display: none" >';
            $strResultadoContest .= $dblIdProcedimentoCont;
            $strResultadoContest .= '</td>';

            //Linha Processo
            $strResultadoContest .= '<td class="tdIdProcessoCont" >';
            $strResultadoContest .= '<a href="#" onclick="window.open(\'' . $linkProcedimentoCont . '\')" alt="' . $strNomeProcCont . '" title="' . $strNomeProcCont . '" class="ancoraPadraoAzul">' . $strProcessoCont . '</a>';
            $strResultadoContest .= '</td>';

            // Nome Tipo de Controle
            $strResultadoContest .= '<td class="tdSolicitacao" >';
            $strResultadoContest .= $strNmTpCtrlCont;
            $strResultadoContest .= '</td>';

            //Linha Servidor
            $strResultadoContest .= '<td class="tdServidorCont" >';
            $strResultadoContest .= '<a class="ancoraSigla" href="#" alt="' . $strNomeUsuarioCont . '" title="' . $strNomeUsuarioCont . '">' . $strSiglaUsuarioCont . '</a>';
            $strResultadoContest .= '</td>';

            //Linha Data de Solicitação
            $strResultadoContest .= '<td class="tdDthSolicitacao" >';
            $strResultadoContest .= $dthDataSolicitacaoCont[0];
            $strResultadoContest .= '</td>';

            //Linha Justificativa
            $strResultadoContest .= '<td class="tdJustificativaCont">';
            $strResultadoContest .= '<a class="ancoraSigla" href="#" alt="' . $strObservacaoCont . '" title="' . $strObservacaoCont . '">' . $strJustificativaCont. '</a>';
            $strResultadoContest .= '</td>';

            //Linha Revisor
            $strResultadoContest .= '<td class="tdRevisorCont" >';
            $strResultadoContest .= '<a class="ancoraSigla" href="#" alt="' . $usuRevisorCont->getStrNomeUsuarioDist() . '" title="' . $usuRevisorCont->getStrNomeUsuarioDist() . '">' . $usuRevisorCont->getStrSiglaUsuarioDist() . '</a>';
            $strResultadoContest .= '</td>';


            //Linha Ações
            $strResultadoContest .= '<td class="tdAcoes" align="center">';
            $strResultadoContest .= '<a href="' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_contest_revisao_consultar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_controle_desempenho=' . $numIdControleDsmpCont . '&is_gerir=0' . '&id_triagem=' . $numIdTriagemCont . '&id_contestacao_revisao=' . $idContestRevisaoExistente)) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/consultar.svg" title="Consultar Solicitação de Contestação" alt="" class="infraImg" /></a>&nbsp;';
            $strResultadoContest .= '<a id="aprovarContestacao" onclick="confirmarAcaoContest(\'' . MdUtlAjustePrazoRN::$APROVADA . '\',\'' . $strProcessoCont . '\', \'' . $linkAprovarCont . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="'.PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/transportar.svg" title="Aprovar Solicitação de Contestação" alt="" class="infraImg" /></a>&nbsp;';
            $strResultadoContest .= '<a id="reprovarContestacao" onclick="confirmarAcaoContest(\'' . MdUtlAjustePrazoRN::$REPROVADA . '\',\'' . $strProcessoCont . '\', \'' . $linkReprovarCont . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="'.PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/remover.svg" title="Reprovar Solicitação de Contestação" alt="" class="infraImg" /></a>&nbsp;';

           /* $strResultadoContest .= '<td class="tdAcoes" align="center">';
            $strResultadoContest .= '<a href="' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_contest_revisao_consultar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_controle_desempenho=' . $numIdControleDsmpCont . '&is_gerir=0' . '&id_triagem=' . $numIdTriagemCont . '&id_contestacao_revisao=' . $idContestRevisaoExistente)) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/consultar.gif" title="Consultar Solicitação de Contestação" alt="" class="infraImg" /></a>&nbsp;';
            $strResultadoContest .= '<a onclick=construcao()><img src="modulos/utilidades/imagens/aprovar_ajuste_prazo.png" title="Aprovar Solicitação de Contestação" alt="" class="infraImg" /></a>&nbsp;';
            $strResultadoContest .= '<a onclick=construcao()><img src="modulos/utilidades/imagens/reprovar_ajuste_prazo.png" title="Reprovar Solicitação de Contestação" alt="" class="infraImg" /></a>&nbsp;';
            $strResultadoContest .= '</td>';*/

            $strResultadoContest .= '</td>';
            $strResultadoContest .= '</tr>';

        }
        $strResultadoContest .= '</table>';
    }
}
