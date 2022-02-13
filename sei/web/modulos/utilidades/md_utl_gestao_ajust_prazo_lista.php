<?php
/**
 * Created by PhpStorm.
 * User: thamires.zamai
 * Date: 27/09/2019
 * Time: 10:50
 */

if (!is_null($objMdUtlControleDsmpDTO)) {

    $numRegistros = count($objMdUtlControleDsmpDTO);
    $caption = $numRegistros == 1 ? 'Pendente de Resposta: ' . $numRegistros . ' registro' : 'Pendentes de Resposta: ' . $numRegistros . ' registros';

    //Tabela de resultado.
    if ($numRegistros > 0) {
        $strCaption .= '<caption class="infraCaption">';
        $strCaption .= '<span class="spnCaptionRegistros" >' . $caption . '</span>';
        $strCaption .= '</caption>';
        //Tabela Ajuste de Prazo
        $strResultado .= '<table width="100%" class="infraTable" summary="Processos" id="tbCtrlProcesso">';


        //Cabe�alho da Tabela
        $strResultado .= '<tr>';
        $strResultado .= '<th class="infraTh" width="16%" style="text-align: left"> Processo </th>';
        $strResultado .= '<th class="infraTh" width="10%" style="text-align: left"> Tipo de Controle  </th>';
        $strResultado .= '<th class="infraTh" width="10%" style="text-align: left"> Tipo de Solicita��o </th>';
        $strResultado .= '<th class="infraTh" width="10%" style="text-align: left"> Servidor </th>';
        $strResultado .= '<th class="infraTh" width="9%" style="text-align: left"> Data da Solicita��o </th>';
        $strResultado .= '<th class="infraTh" width="14%" style="text-align: left"> Justificativa </th>';
        $strResultado .= '<th class="infraTh" width="9%" style="text-align: left"> Prazo Entrega </th>';
        $strResultado .= '<th class="infraTh" width="10%" style="text-align: left"> Prazo Solicitado </th>';
        $strResultado .= '<th class="infraTh" width="17%" style="text-align: center"> A��es </th>';

        $strResultado .= '</tr>';


        //Linhas
        $strCssTr = '<tr class="infraTrEscura">';

        for ($i = 0; $i < $numRegistros; $i++) {
            $numIdControleDsmp = $objMdUtlControleDsmpDTO[$i]->getNumIdMdUtlControleDsmp();
            $numIdAjustePrazo = $objMdUtlControleDsmpDTO[$i]->getNumIdMdUtlAjustePrazo();
            $dblIdProcedimento = $objMdUtlControleDsmpDTO[$i]->getDblIdProcedimento();
            $strNomeProc = $objMdUtlControleDsmpDTO[$i]->getStrNomeTipoProcesso();
            $strProcesso = $objMdUtlControleDsmpDTO[$i]->getStrProtocoloProcedimentoFormatado();
            $strTpSolicitacao = $objMdUtlControleDsmpDTO[$i]->getStrStaTipoSolicitacaoAjustePrazo();
            $strNmTpCtrl      = $objMdUtlControleDsmpDTO[$i]->getStrNomeTpControle();
            $strSiglaUsuario = $objMdUtlControleDsmpDTO[$i]->getStrSiglaUsuarioDistribuicao();
            $strNomeUsuario = $objMdUtlControleDsmpDTO[$i]->getStrNomeUsuarioDistribuicao();
            $arrDataSolicitacao = explode(' ', $objMdUtlControleDsmpDTO[$i]->getDthAtual());
            $dthDataSolicitacao = $arrDataSolicitacao[0];
            $strJustificativa = $objMdUtlControleDsmpDTO[$i]->getStrNomeJustificativa();
            $strObservacao = $objMdUtlControleDsmpDTO[$i]->getStrObservacao();
            $dthPrazoEntrega = MdUtlGestaoAjustPrazoINT::formatarData($objMdUtlControleDsmpDTO[$i]->getDthPrazoInicialAjustePrazo(),'/');
            $dthPrazoSolicitado = MdUtlGestaoAjustPrazoINT::formatarData($objMdUtlControleDsmpDTO[$i]->getDthPrazoSolicitacaoAjustePrazo(),'/');
            $idContato = $objMdUtlControleDsmpDTO[$i]->getNumIdContato();

            $linkProcedimento = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem=md_utl_gestao_solicitacoes_lista&id_procedimento=' . $dblIdProcedimento . '');
            $linkAprovar      = SessaoSEI::getInstance()->assinarLink($strUrl . 'aprovar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_controle_desempenho=' . $numIdControleDsmp . '&id_ajuste_prazo='.$numIdAjustePrazo.'');
            $linkReprovar     = SessaoSEI::getInstance()->assinarLink($strUrl . 'reprovar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_controle_desempenho=' . $numIdControleDsmp . '&id_ajuste_prazo='.$numIdAjustePrazo.'');

            $bolRegistroAtivo = true;

            $strCssTr = !$bolRegistroAtivo ? '<tr class="trVermelha">' : ($strCssTr == '<tr class="infraTrClara">' ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">');
            $strResultado .= $strCssTr;

            //Linha Tipo de Solicita��o
            $strResultado .= '<td class="tdIdProcedimento" style="display: none" >';
            $strResultado .= $dblIdProcedimento;
            $strResultado .= '</td>';

            //Linha Processo
            $strResultado .= '<td class="tdIdProcesso" >';
            $strResultado .= '<a href="#" onclick="window.open(\'' . $linkProcedimento . '\')" alt="' . $strNomeProc . '" title="' . $strNomeProc . '" class="ancoraPadraoAzul">' . $strProcesso . '</a>';
            $strResultado .= '</td>';
            
            // Nome Tipo de Controle
            $strResultado .= '<td class="tdSolicitacao" >';
            $strResultado .= $strNmTpCtrl;
            $strResultado .= '</td>';

            //Linha Tipo de Solicita��o
            $strResultado .= '<td class="tdSolicitacao" >';
            $strResultado .= MdUtlGestaoAjustPrazoINT::montarTipoSolicitacao($strTpSolicitacao);
            $strResultado .= '</td>';

            //Linha Servidor
            $strResultado .= '<td class="tdServidor" >';
            $strResultado .= '<a class="ancoraSigla" href="#" alt="' . $strNomeUsuario . '" title="' . $strNomeUsuario . '">' . $strSiglaUsuario . '</a>';
            $strResultado .= '</td>';

            //Linha Data de Solicita��o
            $strResultado .= '<td class="tdDthSolicitacao" >';
            $strResultado .= $dthDataSolicitacao;
            $strResultado .= '</td>';

            //Linha Justificativa
            $strResultado .= '<td class="tdJustificativa">';
            $strResultado .= '<a class="ancoraSigla" href="#" alt="' . $strObservacao . '" title="' . $strObservacao . '">' . $strJustificativa . '</a>';
            $strResultado .= '</td>';

            //Linha Prazo de Entrega
            $strResultado .= '<td class="tdDthPrazoEntrega" >';
            $strResultado .= $dthPrazoEntrega;
            $strResultado .= '</td>';

            //Linha Prazo Solicitado
            $strResultado .= '<td class="tdDthPrazoSolicitacao" >';
            $strResultado .= $dthPrazoSolicitado;
            $strResultado .= '</td>';

            //Linha A��es
            $strResultado .= '<td class="tdAcoes" align="center">';
            $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_ajuste_prazo_consultar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_ajuste_prazo=' . $numIdAjustePrazo . '&id_controle_desempenho=' . $numIdControleDsmp . '&is_gerir=0') . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/consultar.gif" title="Consultar Solicita��o de Ajuste de Prazo" alt="" class="infraImg" /></a>&nbsp;';
            $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_ajuste_prazo_alterar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_ajuste_prazo=' . $numIdAjustePrazo . '&id_controle_desempenho=' . $numIdControleDsmp . '&is_gerir=1') . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/alterar.gif" title="Alterar Solicita��o de Ajuste de Prazo" alt="" class="infraImg" /></a>&nbsp;';
            $strResultado .= '<a id="aprovarSolicitacao" onclick="confirmarAcao(\'' . MdUtlAjustePrazoRN::$APROVADA . '\',\'' . $strProcesso . '\', \'' . $linkAprovar . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="modulos/utilidades/imagens/aprovar_ajuste_prazo.png" title="Aprovar Solicita��o de Ajuste de Prazo" alt="" class="infraImg" /></a>&nbsp;';
            $strResultado .= '<a id="reprovarSolicitacao" onclick="confirmarAcao(\'' . MdUtlAjustePrazoRN::$REPROVADA . '\',\'' . $strProcesso . '\', \'' . $linkReprovar . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="modulos/utilidades/imagens/reprovar_ajuste_prazo.png" title="Reprovar Solicita��o de Ajuste de Prazo" alt="" class="infraImg" /></a>&nbsp;';
            $strResultado .= '</td>';

            $strResultado .= '</tr>';

        }
        $strResultado .= '</table>';

    }
}