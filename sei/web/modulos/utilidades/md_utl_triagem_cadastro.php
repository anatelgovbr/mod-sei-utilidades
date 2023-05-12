<?php

/**
 * @author Jaqueline Mendes
 * @since  06/11/2018
 */

require_once dirname(__FILE__) . '/../../SEI.php';

session_start();
SessaoSEI::getInstance()->validarLink();
$isPgPadrao      = array_key_exists('pg_padrao', $_GET) ? $_GET['pg_padrao'] : (array_key_exists('hdnIsPgPadrao', $_POST) ? $_POST['hdnIsPgPadrao'] : 0);

$isMeusProcessos = true;

if(is_null($isPgPadrao) || $isPgPadrao == 0) {
    PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);
    $isMeusProcessos = false;
}

//Acao única
$acaoPrincipal = 'md_utl_triagem_cadastrar';

//URL Base
$strUrlPadrao = 'controlador.php?acao=' . $acaoPrincipal;

// Vars
$idProcedimento  = array_key_exists('id_procedimento', $_GET) ? $_GET['id_procedimento'] : $_POST['hdnIdProcedimento'];
$strTitulo       = 'Triagem ';

//Tipo de Controle e Procedimento
$objMdUtlAdmTpCtrlUndRN    = new MdUtlAdmRelTpCtrlDesempUndRN();
$objTriagemRN              = new MdUtlTriagemRN();
$objRegrasGerais           = new MdUtlRegrasGeraisRN();
$objMdUtlControleDsmpRN    = new MdUtlControleDsmpRN();
$objRelTriagemAtvRN        = new MdUtlRelTriagemAtvRN();
$objFilaRN                 = new MdUtlAdmFilaRN();
$objMdUtlAdmPrmGrRN        = new MdUtlAdmPrmGrRN();
$objAnaliseRN              = new MdUtlAnaliseRN();

$objProcedimentoDTO        = $objRegrasGerais->getObjProcedimentoPorId($idProcedimento);
$idFilaAtiva               = $_GET['id_fila'];
$idTipoProcedimento        = $objProcedimentoDTO->getNumIdTipoProcedimento();
$isBuscaDados              = $_GET['acao'] == 'md_utl_triagem_alterar' || $_GET['acao'] == 'md_utl_triagem_consultar';
$idTriagem                 = null;
$objTriagemDTO             = null;
$isRetriagem               = array_key_exists('id_retriagem', $_GET) ? $_GET['id_retriagem'] : $_POST['hdnIdRetriagem'];
$isRtgAnlCorrecao          = array_key_exists('isRtgAnlCorrecao', $_GET) ? $_GET['isRtgAnlCorrecao'] : $_POST['hdnIdRtgAnlCorrecao'];
$isAnalise                 = false;
$tpPresencaRef             = null;
$percDsmpRef               = null;
$idUsuarioFezAnalise       = null;
$idUsuarioDistrAnalise     = null;
$arrAtvAnalisadas          = null;

//retona objeto controle desempenho
$objControleDsmpDTO = $objMdUtlControleDsmpRN->getObjControleDsmpAtivo($idProcedimento);

$idTipoControle = $objControleDsmpDTO->getNumIdMdUtlAdmTpCtrlDesemp();

if($isBuscaDados) {    
    $idTriagem = $objControleDsmpDTO->getNumIdMdUtlTriagem();
    if (!is_null($idTriagem))
    {
        $objTriagemDTO = $objTriagemRN->buscarObjTriagemPorId($idTriagem);
    }
    $idAtendimento = $objControleDsmpDTO->getStrStaAtendimentoDsmp();
    if ($idAtendimento == MdUtlControleDsmpRN::$EM_ANALISE){
        $isAnalise = true;
    }
}

//recupera o nome do usuario que realizou/realizara a triagem
$idObj = in_array(
    $objControleDsmpDTO->getStrStaAtendimentoDsmp(), 
    [MdUtlControleDsmpRN::$EM_TRIAGEM,MdUtlControleDsmpRN::$EM_CORRECAO_TRIAGEM]
)
? $objControleDsmpDTO->getNumIdMdUtlControleDsmp()
: $objControleDsmpDTO->getNumIdMdUtlTriagem();

$nm_usuario_triagem = MdUtlControleDsmpINT::getNomeUsuarioRespTriagAnaliseAval(
    $idObj,
    $objControleDsmpDTO->getStrStaAtendimentoDsmp(),
    MdUtlControleDsmpRN::$STR_TIPO_ACAO_TRIAGEM
);

//Urls
$strLinkAtividadeSelecao   = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_atividade_selecionar&tipo_selecao=2&id_object=objLupaAtividade&id_tipo_controle_utl='.$idTipoControle.'&id_tipo_procedimento='.$idTipoProcedimento.'&acao_origem='.$acaoPrincipal);
$strLinkAjaxAtividade      = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_utl_adm_atividade_filtro_auto_completar&id_tipo_controle_utl='.$idTipoControle.'&id_tipo_procedimento='.$idTipoProcedimento);

$strLinkGrupoAtividadeSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_grp_fila_selecionar&tipo_selecao=2&id_object=objLupaGrupoAtividade&id_fila_ativa='.$idFilaAtiva.'&id_tipo_controle_utl='.$idTipoControle.'&id_tipo_procedimento='.$idTipoProcedimento);
$strLinkAjaxGrupoAtividade    = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_utl_adm_grp_fila_auto_completar&id_fila_ativa='.$idFilaAtiva.'&id_tipo_controle_utl='.$idTipoControle.'&id_tipo_procedimento='.$idTipoProcedimento);
$strUrlAjaxValidarGrupoAtvAtividade = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_utl_adm_validar_grupo_atividade&id_fila_ativa='.$idFilaAtiva.'&id_tipo_controle_utl='.$idTipoControle.'&id_tipo_procedimento='.$idTipoProcedimento);
$strLinkValidaUsuarioPertenceAFila = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_utl_usuario_pertence_fila');

$urlRetorno = 'controlador.php?acao=md_utl_analise_alterar&acao_origem=md_utl_processo_listar&id_procedimento=' . $idProcedimento.'&id_fila='.$idFilaAtiva.'&id_retriagem=1&isRtgAnlCorrecao=1';

if($isPgPadrao == 1){
    $urlRetorno = str_replace('md_utl_processo_listar','md_utl_meus_processos_listar',$urlRetorno);
    $urlRetorno .= '&pg_padrao=1';
}

$strDetalhamento = SessaoSEI::getInstance()->assinarLink($urlRetorno);

$isConsultar                  = false;
$strGridTriagem               = '';
$valorTotalUE                 = 0;
$dtaPrazoResp                 = '';
$strInformComp                = '';
$valorTotalHdn                = 0;
$hdnContadorPagina            = 0;
$selEncaminhamentoTriagem     = '';
$vlEncaminhamentoTriagem      = !is_null($objTriagemDTO) ? $objTriagemDTO->getStrStaEncaminhamentoTriagem() : null;
$vlFila                       = !is_null($objTriagemDTO) ? $objTriagemDTO->getNumIdMdUtlAdmFila() : null;

// informacoes usadas para a funcionalidade de distribuir o processo automaticamente para o triador
$strSiglaUsuario              = SessaoSEI::getInstance()->getStrSiglaUsuario();
$strNmSiglaUsuario            = SessaoSEI::getInstance()->getStrNomeUsuario() . " ($strSiglaUsuario)";
$bolPertenceAFila             = false;
$chkDistAutoParaMim           = null;

if( !is_null( $objTriagemDTO ) ){
    // informacao usada para a funcionalidade de distribuir o processo automaticamente para o analista apos finalizar o processo
    $bolPertenceAFila = $objFilaRN->verificaUsuarioLogadoPertenceFila( 
        [ $vlFila , 1 , true , $objTriagemDTO->getNumIdUsuario() ]
    );
    
    if( $bolPertenceAFila )
        $chkDistAutoParaMim  = $objTriagemDTO->getStrDistAutoParaMim() ?: null;
}

$selEncaminhamentoTriagem     = MdUtlControleDsmpINT::montarSelectEncaminhamentoAnaliseTriagem($vlEncaminhamentoTriagem);
$arrObjFilaDTO                = $objFilaRN->getFilasVinculadosUsuario( $idTipoControle ); #$objFilaRN->getFilasTipoControle($idTipoControle);
$selFila                      = MdUtlAdmFilaINT::montarSelectFilas($vlFila, $arrObjFilaDTO);
$displayEncaminhamento        = "display:none";
$displayFila                  = "display:none";

$isTpProcessoParametrizado   = $objMdUtlAdmPrmGrRN->verificaTipoProcessoParametrizado(array($idTipoProcedimento, $idTipoControle));
$isJsTpProcParametrizado     = $isTpProcessoParametrizado ? '1' : '0';

// verifica se exite grupo cadastrado para ocultar ou exibir os campos na tela
$existeGrupoCadastrado = MdUtlAdmGrpINT::verificarExisteGruposParametrizado($objControleDsmpDTO->getNumIdMdUtlAdmTpCtrlDesemp(), $objControleDsmpDTO->getNumIdMdUtlAdmFila(), $idTipoProcedimento);

switch ($_GET['acao']) {

    //region Listar
    case $acaoPrincipal:

        $arrComandos[] = '<button type="submit" accesskey="s" id="btnSalvar" value="salvar" class="infraButton botaoSalvar">
                                    <span class="infraTeclaAtalho">S</span>alvar</button>';


        $arrComandos[] = '<button type="button" accesskey="c" id="" value="Cancelar" onclick="fechar();" class="infraButton">
                                    <span class="infraTeclaAtalho">C</span>ancelar
                            </button>';

        if(!empty($_POST)){
            $idProcedimento = array_key_exists('hdnIdProcedimento', $_POST) ? $_POST['hdnIdProcedimento'] : null;

            if(!is_null($idProcedimento)){
                try{
                    $isProcessoConcluido = $objTriagemRN->cadastrarDadosTriagem($_POST);

                    if($isPgPadrao == 0) {
                        header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_processo_listar&id_procedimento=' . $idProcedimento.'&is_processo_concluido='.$isProcessoConcluido));
                    }else{
                        header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_meus_processos_dsmp_listar&id_procedimento=' . $idProcedimento.'&is_processo_concluido='.$isProcessoConcluido));
                    }

                    die;

                } catch(Exception $e){
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
            }
        }

        break;

    case 'md_utl_triagem_consultar':
        $isConsultar = true;
        $arrComandos[] = '<button type="button" accesskey="c" id="btnFechar" value="Fechar" onclick="fechar();" class="infraButton">
                                    Fe<span class="infraTeclaAtalho">c</span>har
                            </button>';

        if(!is_null($idTriagem)) {
            $arrObjsRel   = $objRelTriagemAtvRN->getObjsPorIdTriagem($idTriagem);
            $isSemAnalise = false;

            if (!is_null($objTriagemDTO) && (is_array($arrObjsRel) && !is_null($arrObjsRel))) {
                $arrGrid = array();
                $contador = 0;

                // buscar id do usuario da última distribuição para aplicar o fator de desempenho
                //$idUsuarioDistribuicaoAnalise = MdUtlAdmPrmGrUsuINT::buscarIdUsuarioDitribuidoAnalise($objTriagemDTO->getNumIdMdUtlTriagem());

                foreach ($arrObjsRel as $objDTO) {
                    $tempoExecucao = $objDTO->getNumTempoExecucaoAtribuido();

                    $idMain = $contador . '_' . $objDTO->getNumIdMdUtlAdmAtividade();
                    $idPk = $objDTO->getNumIdMdUtlAdmAtividade();
                    $vlUe = $objDTO->getStrSinAnalise() == 'S' ? MdUtlAdmPrmGrINT::convertToHoursMins($tempoExecucao) : '0min';
                    $strVlAnalise = $objDTO->getStrSinAnalise() == 'S' ? 'Sim' : 'Não';
                    $isSemAnalise = $objDTO->getStrSinAnalise() == 'N';
                    $contador++;

                    $arrGrid[] = array($idMain, $idPk, $objDTO->getStrNomeAtividade() . ' (' . MdUtlAdmAtividadeRN::$ARR_COMPLEXIDADE[$objDTO->getNumComplexidadeAtividade()] . ')', $vlUe, $objDTO->getStrSinAnalise(), $strVlAnalise);
                    $valorTotalUE += !is_null($tempoExecucao) ? $tempoExecucao : 0;
                }

                $strGridTriagem = PaginaSEI::getInstance()->gerarItensTabelaDinamica($arrGrid);

                //Prazo Resposta
                $dtaPrazoResp = $objTriagemDTO->getDthPrazoResposta();
                if (!is_null($dtaPrazoResp)) {
                    $dtaPrazoResp = explode(' ', $dtaPrazoResp);
                    $dtaPrazoResp = array_key_exists('0', $dtaPrazoResp) ? $dtaPrazoResp[0] : '';
                }

                $strInformComp = $objTriagemDTO->getStrInformacaoComplementar();

                if($isSemAnalise && $isTpProcessoParametrizado){
                    $displayEncaminhamento = '';

                    if($objTriagemDTO->getStrStaEncaminhamentoTriagem() == MdUtlControleDsmpRN::$ENC_ASSOCIAR_EM_FILA) {
                        $displayFila = '';
                    }
                }

            }
        }
        break;

    case 'md_utl_triagem_alterar':

        if($isRetriagem == 1){
            $strTitulo = 'Retriagem ';

            if ( $objControleDsmpDTO->getStrStaAtendimentoDsmp() == MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE ) {
                //Quem fez analise
                $idUsuarioFezAnalise = $objAnaliseRN->getAnalisePorId( 
                    $objControleDsmpDTO->getNumIdMdUtlAnalise() 
                )->getNumIdUsuario();

                //Com quem estar neste momento o processo
                $idUsuarioDistrAnalise = $objControleDsmpDTO->getNumIdUsuarioDistribuicao();

                //Recupera a lista de atividades analisadas
                $arrAtvAnalisadas = ( new MdUtlRelTriagemAtvRN() )->listarComAnalise( $objControleDsmpDTO->getNumIdMdUtlAnalise() );
                $arrAtvAnalisadas = InfraArray::converterArrInfraDTO(
                    InfraArray::distinctArrInfraDTO($arrAtvAnalisadas,'IdMdUtlAdmAtividade'),
                    'IdMdUtlAdmAtividade'
                );
            }
            $strLinkIniciarRevisao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_revisao_analise_consultar&acao_origem=' . $_GET['acao'] . '&id_procedimento=' . $idProcedimento);
        }else {
            $strLinkIniciarRevisao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_revisao_triagem_consultar&acao_origem=' . $_GET['acao'] . '&id_procedimento=' . $idProcedimento . '&id_revisao=' . $objControleDsmpDTO->getNumIdMdUtlRevisao());
        }


        $arrObjsRel = $objRelTriagemAtvRN->getObjsPorIdTriagem($idTriagem);

        if(!$isAnalise) {
            $arrComandos[] = '<button type="button" accesskey="v" id="btnAbriAvaliacao" value="Revisao" onclick="abrirModalRevisao();" class="infraButton">
                                            A<span class="infraTeclaAtalho">v</span>aliação
                                    </button>';
        }

        $arrComandos[] = '<button type="submit" accesskey="s" id="btnSalvar" value="Salvar" class="infraButton botaoSalvar">
                                    <span class="infraTeclaAtalho">S</span>alvar</button>';


        $arrComandos[] = '<button type="button" accesskey="c" id="" value="Cancelar" onclick="fechar();" class="infraButton">
                                    <span class="infraTeclaAtalho">C</span>ancelar</button>';

        if(!is_null($objTriagemDTO) && (is_array($arrObjsRel) && !is_null($arrObjsRel))) {
            $arrGrid = array();
            $contador = 0;
            $isSemAnalise = false;

            $tpPresencaRef = $objControleDsmpDTO->getStrStaTipoPresenca();
            $percDsmpRef   = $objControleDsmpDTO->getNumPercentualDesempenho();

            foreach ($arrObjsRel as $objDTO) {
                $vlrTmpExec = $objDTO->getStrSinAnalise() == 'S' && !is_null($objDTO->getNumTempoExecucaoAtribuido()) ? $objDTO->getNumTempoExecucaoAtribuido() : 0;

                $idMain = $contador . '_' . $objDTO->getNumIdMdUtlAdmAtividade();
                $idPk = $objDTO->getNumIdMdUtlAdmAtividade();
                $vlUe = $vlrTmpExec > 0 ? MdUtlAdmPrmGrINT::convertToHoursMins( $vlrTmpExec ) : $vlrTmpExec.'min';
                $vlUeHdn = $objDTO->getStrSinAnalise() == 'S' ? $objDTO->getNumVlTmpExecucaoAtv() : $objDTO->getNumVlTmpExecucaoRev();
                $strVlAnalise = $objDTO->getStrSinAnalise() == 'S' ? 'Sim' : 'Não';
                $contador++;
                $valorTotalUE += $vlrTmpExec;
                $valorTotalHdn += $vlUeHdn;
                $arrGrid[] = array($idMain, $idPk, $objDTO->getStrNomeAtividade() . ' (' . MdUtlAdmAtividadeRN::$ARR_COMPLEXIDADE[$objDTO->getNumComplexidadeAtividade()] . ')', $vlUe, $objDTO->getStrSinAnalise(), $strVlAnalise, $vlUeHdn, $vlrTmpExec);
                $isSemAnalise = $objDTO->getStrSinAnalise() == 'N';
            }

            $hdnContadorPagina = $contador;
            $strGridTriagem = PaginaSEI::getInstance()->gerarItensTabelaDinamica($arrGrid);

            //Prazo Resposta
            $dtaPrazoResp   = $objTriagemDTO->getDthPrazoResposta();
            if(!is_null($dtaPrazoResp)){
                $dtaPrazoResp = explode(' ', $dtaPrazoResp);
                $dtaPrazoResp = array_key_exists('0', $dtaPrazoResp) ? $dtaPrazoResp[0] : '';
            }

            $strInformComp  = $objTriagemDTO->getStrInformacaoComplementar();

            if($isSemAnalise && $isTpProcessoParametrizado){
                $displayEncaminhamento = '';

                if($objTriagemDTO->getStrStaEncaminhamentoTriagem() == MdUtlControleDsmpRN::$ENC_ASSOCIAR_EM_FILA) {
                    $displayFila = '';
                }
            }
        }

        if(!empty($_POST)){
            $idProcedimento   = array_key_exists('hdnIdProcedimento', $_POST) ? $_POST['hdnIdProcedimento'] : null;
            $idRetriagem      = array_key_exists('hdnIdRetriagem', $_POST) ? $_POST['hdnIdRetriagem'] : null;
            $idRtgAnlCorrecao = array_key_exists('hdnIdRtgAnlCorrecao', $_POST) ? $_POST['hdnIdRtgAnlCorrecao'] : null;
            $isPossuiAnalise  = array_key_exists('hdnIsPossuiAnalise', $_POST) ? $_POST['hdnIsPossuiAnalise'] : null;
            $isFilaAtiva      = array_key_exists('hdnIdFilaAtiva', $_POST) ? $_POST['hdnIdFilaAtiva'] : null;

            if(!is_null($idProcedimento)){
                try{                   
                    $isProcessoConcluido = $objTriagemRN->cadastroRetriagem($objTriagemDTO,$objControleDsmpDTO);

                    $paginaRetorno = $isPgPadrao == 0 ? 'md_utl_processo_listar' : 'md_utl_meus_processos_dsmp_listar';

                    if ($idRetriagem == 1) {
                        if ($isPossuiAnalise == 'N') {
                            $url = 'controlador.php?acao='.$paginaRetorno.'&id_procedimento=' . $idProcedimento . '&is_processo_concluido' . $isProcessoConcluido;
                        } else if ($isRtgAnlCorrecao == 1) {
                            $url = 'controlador.php?acao=md_utl_analise_alterar&acao_origem=md_utl_analise_alterar&id_procedimento=' . $idProcedimento . '&id_fila=' . $isFilaAtiva . '&id_retriagem=1&isRtgAnlCorrecao=1';
                        } else {
                            $url = 'controlador.php?acao=md_utl_analise_cadastrar&acao_origem=md_utl_analise_cadastrar&id_procedimento=' . $idProcedimento . '&id_fila=' . $isFilaAtiva . '&id_retriagem=1';
                        }
                    } else {
                        $url = 'controlador.php?acao='.$paginaRetorno.'&id_procedimento=' . $idProcedimento . '&is_processo_concluido' . $isProcessoConcluido;
                    }

                    if($isPgPadrao == 1){
                        $url = $url . '&pg_padrao=1';
                    }

                    header('Location: ' . SessaoSEI::getInstance()->assinarLink($url));

                    die;

                } catch(Exception $e){
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
            }
        }
        break;
    //region Erro
    default:
        throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    //endregion
}

//Botões de ação do topo
PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(':: ' . PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo . ' ::');
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
PaginaSEI::getInstance()->fecharStyle();
require_once "md_utl_geral_css.php";
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');

// Texto do tooltip
$txtTooltipEncaminhamentoTriagem = 'A depender das parametrizações em seu perfil ou sobre as Atividades entregues, o que for selecionado neste campo será meramente sugestivo ou será executado imediatamente.\n \n Selecione a opção "Associar em Fila após Finalizar Fluxo" caso queira reiniciar o fluxo em alguma Fila imediatamente com a finalização do fluxo atual.\n \n Ou selecione a opção "Finalizar Fluxo" para concluir sem associar a qualquer Fila imediatamente na finalização do fluxo atual.';

$linkProcedimento = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem=md_utl_triagem_cadastrar&id_procedimento=' . $idProcedimento . '');

$strNumeroProcesso = $objProcedimentoDTO->getStrProtocoloProcedimentoFormatado();

?>
    <form onsubmit="return onSubmitForm();" id="frmUtlTriagemCadastro" method="post"
          action="<?= PaginaSEI::getInstance()->formatarXHTML(
              SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])
          ) ?>">

        <?php
            PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
            PaginaSEI::getInstance()->abrirAreaDados('auto');
        ?>

        <?php if( $isPgPadrao != 0 ) { ?>
            <div class="row mb-3">
                <div class="col-12">
                    <label class="marcar-label"> Número do Processo: </label>
                    <a href="<?= $linkProcedimento ?>" target="_blank" alt="Acessar o processo: <?= $strNumeroProcesso ?>" title="Acessar o processo: <?= $strNumeroProcesso ?>" class="ancoraPadraoAzul">
                      <?= $strNumeroProcesso ?>
                    </a>
                </div>
            </div>
        <?php } ?>

        <div class="row">
            <div class="col-sm-6 col-md-5 col-lg-4 mb-2">
                <label id="lblTipoControle" accesskey="" class="infraLabelOpcional">Tipo de Controle:</label>
                <input type="text" id="txtTipoControle" name="txtTipoControle" class="infraText form-control" value="<?= $objControleDsmpDTO->getStrNomeTpControle() ?>" disabled/>
            </div>

            <div class="col-sm-6 col-md-5 col-lg-4 mb-2">
                <label id="lblFila" accesskey="" class="infraLabelOpcional">Fila:</label>
                <input type="text" id="txtNomeFila" name="txtNomeFila" class="infraText form-control" value="<?= $objControleDsmpDTO->getStrNomeFila() ?>" disabled/>
            </div>

            <div class="col-sm-12 col-md-12 col-lg-4 mb-2">
                <label class="infraLabelOpcional">Membro Responsável pela Triagem:</label>
                <input type="text" value="<?= $nm_usuario_triagem ?>" readonly class="infraText form-control">
            </div>
        </div>

        <div id="divGrupoAtividade" class="mb-3" <?php if (!$existeGrupoCadastrado || $isConsultar) { echo 'style="display:none"'; }?>>
            <div class="row mb-1">
                <div class="col-xs-4 col-sm-6 col-md-6 col-lg-6">
                    <label id="lblGrupoAtividade" for="selGrupoAtividade" accesskey="" class="infraLabelOpcional">Grupo de Atividade:</label>
                    <img id="btnGrupoAtividade" align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg"
                        name="ajuda" <?= PaginaSEI::montarTitleTooltip('Selecione um Grupo de Atividade.','Ajuda') ?> />
                    <input type="text" id="txtGrupoAtividade" name="txtGrupoAtividade" class="infraText form-control"
                            tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-9 col-md-9 col-lg-9">
                    <div class="input-group">
                        <select id="selGrupoAtividade" name="selGrupoAtividade" multiple="multiple" class="infraSelect form-control">
                            <?= $strItensSelGrupoAtividade ?>
                        </select>
                        <div id="divOpcoesAtividade">
                            <div id="divOpcoesGrupoAtividade" class="ml-1">
                                <img id="imgLupaGrupoAtividade" onclick="abrirGrupoAtividade();" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/pesquisar.svg'?>"
                                    alt="Selecionar Grupo de Atividade" title="Selecionar Grupo de Atividade" class="infraImg"/>
                                <br>
                                <img id="imgExcluirGrupoAtividade" onclick="removerGrupoAtividade()" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/remover.svg'?>"
                                    alt="Remover Grupo de Atividade Selecionada" title="Remover Grupo de Atividade Selecionada" class="infraImg"/>
                            </div>
                        </div>
                        <input type="hidden" id="hdnGrupoAtividade" name="hdnGrupoAtividade" value=""/>
                        <input type="hidden" id="hdnIdGrupoAtividade" name="hdnIdGrupoAtividade" value=""/>
                    </div>
                </div>
            </div>
        </div>

        <div id="divAtividade" class="mb-3" <?= $isConsultar ? 'style="display:none"' : '' ?>>
            <div class="row">
                <div class="col-xs-4 col-sm-10 col-md-10 col-lg-6">
                    <label id="lblAtividade" for="selAtividade" accesskey="" class="infraLabelObrigatorio">Atividades:</label>
                    <img id="btnAtividade" align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg"
                        name="ajuda" <?= PaginaSEI::montarTitleTooltip('Selecione uma ou múltiplas Atividades.','Ajuda') ?> />
                    <input type="text" id="txtAtividade" name="txtAtividade" class="infraText form-control"
                            tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-9">
                    <div class="input-group">
                        <select id="selAtividade" name="selAtividade" multiple="multiple" class="infraSelect form-control">
                            <?= $strItensSelAtividade ?>
                        </select>
                        <div id="divOpcoesAtividade" class="ml-1">
                            <img id="imgLupaAtividade" onclick="selecionarAtividade();" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/pesquisar.svg'?>"
                                alt="Selecionar Atividade" title="Selecionar Atividade" class="infraImg"/>
                            <br>
                            <img id="imgExcluirAtividade" onclick="objLupaAtividade.remover();" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/remover.svg'?>"
                                alt="Remover Atividade Selecionada" title="Remover Atividade Selecionada" class="infraImg"/>
                        </div>
                        <input type="hidden" id="hdnAtividade" name="hdnAtividade" value=""/>
                        <input type="hidden" id="hdnIdAtividade" name="hdnIdAtividade" value=""/>
                        <input type="hidden" id="hdnContadorTableAtv" name="hdnContadorTableAtv" value="<?= $hdnContadorPagina ?>"/>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-3" <?= $isConsultar ? 'style="display:none"' : '' ?>>
            <div class="col-xs-2 col-sm-12 col-md-12 col-lg-9 text-right">
                <?php $disabled = $isConsultar ? 'style="display:none"' : ''; ?>
                <button <?= $disabled ?> type="button" class="infraButton" id="btnAdicionar" accesskey="a" onclick="adicionarRegistroTabelaAtividade();">
                    <span class="infraTeclaAtalho">A</span>dicionar
                </button>
            </div>
        </div>

        <div id="divPrincipalEncaminhamento" class="row mb-3" style="<?= $displayEncaminhamento ?>">
            <div id="divEncaminhamentoTriagem" class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                <label for="selEncaminhamentoTriagem" id="lblEncaminhamentoTriagem" class="infraLabelObrigatorio">Encaminhamento da Triagem:</label>
                <img align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg"
                     name="ajuda" <?= PaginaSEI::montarTitleTooltip( $txtTooltipEncaminhamentoTriagem,'Ajuda') ?> />

                <select onchange="controlarExibicaoFila(this);" id="selEncaminhamentoTriagem" class="infraSelect form-control" name="selEncaminhamentoTriagem">
                    <?= $selEncaminhamentoTriagem; ?>
                </select>
            </div>

            <div id="divFila" style="<?= $displayFila ?>" class="col-xs-12 col-sm-6 col-md-6 col-lg-6 mb-3 pt-2 col-xl-6">
                <label for="selFila" id="lblFila" class="infraLabelObrigatorio">Fila:</label>
                <select id="selFila" name="selFila" class="infraSelect form-control" onchange="distribuicaoAutoParaMim(this , 1 , <?= SessaoSEI::getInstance()->getNumIdUsuario() ?>)">
                    <?= $selFila; ?>
                </select>
            </div>

            <div id="divDistAutoParaMim" class="col-12" <?= $bolPertenceAFila ? '' : 'style="display:none;"'?> >
                <div class="infraCheckboxDiv">
                    <input type="checkbox" name="ckbDistAutoParaMim" id="ckbDistAutoParaMim" 
                            <?= $chkDistAutoParaMim == 'S' ? 'checked' : '' ?> value="S">
                    <label class="infraCheckboxLabel" for="ckbDistAutoParaMim"></label>
                </div>
                <label class="infraLabelChec infraLabelOpcional mr-3" for="ckbDistAutoParaMim">
                    Distribuir automaticamente a Triagem do próximo fluxo para você mesmo?
                </label>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <div id="divTbAtividade" style="<?= $strGridTriagem != '' ? '' : 'display:none' ?>">
                    <table class="infraTable table" summary="Atividade" id="tbAtividade">
                        <caption class="infraCaption">&nbsp;</caption>
                        <tr>
                            <th style="display: none">id_atividade_contador</th><!--0-->
                            <th style="display: none">id_atividade</th><!--1-->
                            <th class="infraTh" align="center" width="50%">Atividade</th> <!--2-->
                            <th class="infraTh" align="center" width="35%">Tempo de Execução</th> <!--3ComAnalise-->
                            <th style="display: none"></th><!--4-->
                            <th class="infraTh" align="center" width="20%">Com Análise? </th> <!--5-->
                            <th style="display: none">Total de Tempo de Execução </th><!--4-->
                            <th style="display: none">Total de Tempo de Execução Atribuido </th>
                            <?php if(!$isConsultar) { ?>
                                <th class="infraTh" align="center" width="15%">Ações</th><!--6-->
                            <?php } ?>
                        </tr>
                    </table>
                    <div id="divContadorTabela" style="margin-top: 12px">
                        <label id="lblTltAtividade">Total de Tempo de Execução: </label>
                        <label id="lblVlTltAtividade"><?= MdUtlAdmPrmGrINT::convertToHoursMins($valorTotalUE) ?></label>
                    </div>
                </div>
                <input type="hidden" name="hdnTbAtividade" id="hdnTbAtividade" utlCampoObrigatorio="a" value="<?= $strGridTriagem; ?>"/>
            </div>
        </div>

        <div id="divPrazoResposta" class="row mb-3">
            <div class="col-xs-4 col-sm-5 col-md-5 col-lg-5">
                <label id="lblPrazoResposta" accesskey="" for="txtPrazoResposta" class="infraLabelOpcional"
                      tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                    Prazo para Resposta:
                </label>
                <div class="input-group">
                    <input onchange="return validarFormatoDataTriagem(this);" type="text" id="txtPrazoResposta"
                            name="txtPrazoResposta" onkeypress="return infraMascaraData(this, event)"
                            class="infraText form-control" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"
                            value="<?= $dtaPrazoResp ?>"/>

                    <img src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/calendario.svg"
                        id="imgPrazoResposta" title="Selecionar Prazo para Resposta" alt="Selecionar Prazo para Resposta"
                        class="infraImg ml-1" onclick="infraCalendario('txtPrazoResposta',this);"
                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <label id="lblInformacaoComplementar" for="txaInformacaoComplementar" class="infraLabelOpcional"
                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                    Informação Complementar:
                </label>
                <textarea id="txaInformacaoComplementar" name="txaInformacaoComplementar" rows="4" class="infraTextArea form-control"
                        onkeypress="return infraMascaraTexto(this,event, 500);"
                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados()?>"><?= $strInformComp ?></textarea>
            </div>
        </div>

        <input type="hidden" name="hdnIdProcedimento"   id="hdnIdProcedimento" value="<?= $idProcedimento ?>"/>
        <input type="hidden" name="hdnIdFilaAtiva"      id="hdnIdFilaAtiva"    value="<?= $idFilaAtiva ?>"/>
        <input type="hidden" name="hdnIdTpCtrl"         id="hdnIdTpCtrl"       value="<?= $idTipoControle ?>"/>
        <input type="hidden" name="hdnTmpExecucao" id="hdnTmpExecucao" value="<?= $valorTotalHdn; ?>"/>
        <input type="hidden" name="hdnIsPossuiAnalise" id="hdnIsPossuiAnalise" value=""/>
        <input type="hidden" name="hdnStaPermiteAssociarFila" id="hdnStaPermiteAssociarFila" value="<?= MdUtlControleDsmpRN::$ENC_ASSOCIAR_EM_FILA ?>"/>
        <input type="hidden" name="hdnSelFila" id="hdnSelFila" value=""/>
        <input type="hidden" name="hdnIsPgPadrao" id="hdnIsPgPadrao" value="<?= $isPgPadrao; ?>"/>
        <input type="hidden" name="hdnIdRetriagem" id="hdnIdRetriagem" value="<?= $isRetriagem?>">
        <input type="hidden" name="hdnIdRtgAnlCorrecao" id="hdnIdRtgAnlCorrecao" value="<?= $isRtgAnlCorrecao?>">

        <input type="hidden" name="hndTpPresencaRef" id="hndTpPresencaRef" value="<?= $tpPresencaRef ?>">
        <input type="hidden" name="hndPercDsmpRef" id="hndPercDsmpRef" value="<?= $percDsmpRef ?>">
        <input type="hidden" id="hdnAtividadesAnalisadas" value="<?= !empty($arrAtvAnalisadas) ? implode(',' , $arrAtvAnalisadas) : null ?>">

        <input type="hidden" name="hdnIdUsuarioDistrAuto" value="<?= SessaoSEI::getInstance()->getNumIdUsuario() ?>">
        <input type="hidden" name="hdnNmUsuarioDistrAuto" value="<?= $strNmSiglaUsuario ?>">

        <?php
            PaginaSEI::getInstance()->fecharAreaDados();
            PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
        ?>

    </form>

<?php

require_once('md_utl_funcoes_js.php');
require_once('md_utl_geral_js.php');
require_once('md_utl_triagem_cadastro_js.php');

PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();