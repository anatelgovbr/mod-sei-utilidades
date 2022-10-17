<?php
/**
 * Created by PhpStorm.
 * User: jhon.carvalho
 * Date: 04/12/2018
 * Time: 14:20
 */

try {

    require_once dirname(__FILE__) . '/../../SEI.php';

    session_start();
    SessaoSEI::getInstance()->validarLink();
    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

    $isPgPadrao      = array_key_exists('pg_padrao', $_GET) ? $_GET['pg_padrao'] : (array_key_exists('hdnIsPgPadrao', $_POST) ? $_POST['hdnIsPgPadrao'] : 0);
    $isMeusProcessos = true;

    if(is_null($isPgPadrao) || $isPgPadrao == 0) {
        PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);
        $isMeusProcessos = false;
    }

    // Vars
    $idProcedimento  = array_key_exists('id_procedimento', $_GET) ? $_GET['id_procedimento'] : $_POST['hdnIdProcedimento'];
    $idContest       = array_key_exists('id_contest', $_GET) ? $_GET['id_contest'] : $_POST['hdnIdMdUtlContestRevisao'];
    $encaminhamentoRevisao = MdUtlHistControleDsmpINT::recuperarEncaminhamentoProcessoParaRevisao($idProcedimento);

    if(is_null($idContest)){
        $idContest = 0;
    }

    $strTitulo = 'Análise ';
    $strAcao = $_GET['acao'];

    //Tipo de Controle e Procedimento
    $objException                = new InfraException();
    $objTriagemRN                = new MdUtlTriagemRN();
    $objRegrasGerais             = new MdUtlRegrasGeraisRN();
    $objTriagemDTO               = new MdUtlTriagemDTO();
    $objFilaRN                   = new MdUtlAdmFilaRN();
    $objRelTpCtrlUndRN           = new MdUtlAdmRelTpCtrlDesempUndRN();
    $objMdUtlRelRevsTrgAnlRN     = new MdUtlRelRevisTrgAnlsRN();
    $objMdUtlControleDsmpRN      = new MdUtlControleDsmpRN();
    $objMdUtlHistControleDsmpDTO = new MdUtlHistControleDsmpDTO();
    $objMdUtlHistControleDsmpRN  = new MdUtlHistControleDsmpRN();
    $objMdUtlRevDTO              = new MdUtlRevisaoDTO();
    $objMdUtlRevRN               = new MdUtlRevisaoRN();
    $objMdUtlFilaRN              = new MdUtlAdmFilaRN();
    $idContatoAtual              = null;
    $strNumeroProcesso           = null;

    $isAnalise                   = $_GET['acao'] == 'md_utl_revisao_analise_cadastrar' || $_GET['acao'] == 'md_utl_revisao_analise_consultar';
    $isEdicao                    = $_GET['acao'] == 'md_utl_revisao_analise_cadastrar' || $_GET['acao'] == 'md_utl_revisao_triagem_cadastrar';
    $selectFila                  = '';
    $strTriagOuAnalise           = $isAnalise ? 'Analista' : 'Triador';

    //Variaveis relacionadas a distribuiçao automatica após finalizar o fluxo, ou seja, do ultimo Triador ou Analista
    $validaDistAutoTriagem       = false;
    $idUsuarioDistrAuto          = null;
    $strNomeUsuarioDistrAuto     = null;

    $objControleDsmpDTO          = $objMdUtlControleDsmpRN->getObjControleDsmpAtivoRevisao(array($idProcedimento, $isAnalise));
    $idContatoAtual              = $objControleDsmpDTO->getNumIdContato();
    $strNumeroProcesso           = $objControleDsmpDTO->getStrProtocoloProcedimentoFormatado();
    $idTipoControle              = $objControleDsmpDTO->getNumIdMdUtlAdmTpCtrlDesemp();
    $idFilaAtiva                 = $objControleDsmpDTO->getNumIdMdUtlAdmFila();
    $idRevisao                   = $objControleDsmpDTO->getNumIdMdUtlRevisao();
    $idMdUtlAnalise              = $objControleDsmpDTO->getNumIdMdUtlAnalise();
    $idMdUtlControleDsmp         = $objControleDsmpDTO->getNumIdMdUtlControleDsmp();
    $valorTempoExecucao          = $objControleDsmpDTO->getNumTempoExecucao();
    $idStatus                    = $objControleDsmpDTO->getStrStaAtendimentoDsmp();
    $strNomeFila                 = $objControleDsmpDTO->getStrNomeFila();
    $strNomeTpControle           = $objControleDsmpDTO->getStrNomeTpControle();

    $isUsuarioPertenceFila        = false;
    $isConsultar                  = false;
    $isUsuarioDistribuido         = $objControleDsmpDTO->getNumIdUsuarioDistribuicao() == SessaoSEI::getInstance()->getNumIdUnidadeAtual();

    //recupera o nome do usuario que realizou/realizara a avaliacao e quem fez as triagem e analise
    $nm_usuario_avaliacao = MdUtlControleDsmpINT::getNomeUsuarioRespTriagAnaliseAval(
        $objControleDsmpDTO->getStrStaAtendimentoDsmp() == 6 ? $objControleDsmpDTO->getNumIdMdUtlControleDsmp() : $objControleDsmpDTO->getNumIdMdUtlRevisao(),
        $objControleDsmpDTO->getStrStaAtendimentoDsmp(),
        MdUtlControleDsmpRN::$STR_TIPO_ACAO_REVISAO,
        'V'
    );

    $nm_usuario_triagem = null;
    $nm_usuario_analise = null;

    $selRevisao      = MdUtlAdmTpRevisaoINT::montarSelectTpRevisao($idTipoControle);
    $selJustRevisao  = MdUtlAdmTpJustRevisaoINT::montarSelectJustRevisao($idTipoControle);
    $arrObjsFilaDTO  = $objFilaRN->getFilasVinculadosUsuario( $idTipoControle );
    $selFila         = MdUtlAdmFilaINT::montarSelectFilas($selFila, $arrObjsFilaDTO, null, true);
    $optionAssociar  = MdUtlRevisaoINT::montarSelectSinRetorno();

    if($idContest == 0) {
        if ($idMdUtlAnalise != '' || !is_null($idMdUtlAnalise)) {
            $selAssocFila = $objControleDsmpDTO->getNumIdMdUtlAdmFilaEncAnalise() != null ? 'S' : 'N';
            $idAssocFila = $objControleDsmpDTO->getNumIdMdUtlAdmFilaEncAnalise();
            $optionAssociar = MdUtlRevisaoINT::montarSelectSinRetorno($selAssocFila);
            $selFila = MdUtlAdmFilaINT::montarSelectFilas($encaminhamentoRevisao['id_fila'], $arrObjsFilaDTO, null, true);
        } else {
            $selAssocFila = $objControleDsmpDTO->getNumIdMdUtlAdmFilaEncTriagem() != null ? 'S' : 'N';
            $idAssocFila = $objControleDsmpDTO->getNumIdMdUtlAdmFilaEncTriagem();
            $optionAssociar = MdUtlRevisaoINT::montarSelectSinRetorno($selAssocFila);
            $selFila = MdUtlAdmFilaINT::montarSelectFilas($encaminhamentoRevisao['id_fila'], $arrObjsFilaDTO, null, true);
        }
    }

    $arrComandos    = array();
    $strTitulo      = 'Avaliação';
    $tpAcaoAval     = null;
    $strLinkValidaUsuarioPertenceAFila = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_utl_usuario_pertence_fila');

    switch ($_GET['acao']) {

        case 'md_utl_revisao_analise_cadastrar':

            $arrComandos[] = '<button type="button" accesskey="s" id="btnSalvar" value="salvar" onclick="salvar();" class="infraButton botaoSalvar"><span class="infraTeclaAtalho">S</span>alvar</button>';
            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="window.history.back();" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

            $tpAcaoAval = MdUtlControleDsmpRN::$EM_ANALISE;

            $idObj = in_array(
                $objControleDsmpDTO->getStrStaAtendimentoDsmp(),
                [MdUtlControleDsmpRN::$EM_ANALISE,MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE]
            )
            ? $objControleDsmpDTO->getNumIdMdUtlControleDsmp()
            : $objControleDsmpDTO->getNumIdMdUtlAnalise();

            $nm_usuario_analise = MdUtlControleDsmpINT::getNomeUsuarioRespTriagAnaliseAval(
                $idObj,
                $objControleDsmpDTO->getStrStaAtendimentoDsmp(),
                MdUtlControleDsmpRN::$STR_TIPO_ACAO_ANALISE
            );

            require_once 'md_utl_revisao_analise_cadastro_acoes.php';

            if(isset($_POST) && count($_POST) > 0){

                $objMdUtlRelRevisTrgAnlsRN = new MdUtlRelRevisTrgAnlsRN();

                if($idContest == 0) {
                    $isProcessoConcluido = $objMdUtlRelRevisTrgAnlsRN->cadastrarRevisaoTriagemAnalise($objControleDsmpDTO);
                    if ($isPgPadrao == 0) {
                        if ( $isProcessoConcluido ){
                            $strLink = "acao=arvore_visualizar&acao_origem=procedimento_visualizar&id_procedimento=$idProcedimento";
                            header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?'.$strLink));
                        } else {
                            header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_processo_listar&id_procedimento=' . $idProcedimento . '&is_processo_concluido=' . $isProcessoConcluido));
                        }

                    } else {
                        header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_meus_processos_dsmp_listar&id_procedimento=' . $idProcedimento . '&is_processo_concluido=' . $isProcessoConcluido));
                    }
                }else{
                    $arrDados = $objMdUtlRelRevisTrgAnlsRN->cadastrarRevisaoTriagemAnaliseContest(array($idProcedimento, $idContatoAtual, $strNumeroProcesso));
                    $isProcessoConcluido = $arrDados[0];
                    $isContatoVazio      = $arrDados[1];
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_gestao_solicitacoes_listar&id_procedimento=' . $idProcedimento . '&is_processo_concluido=' . $isProcessoConcluido.'&is_contato_vazio='.$isContatoVazio));
                }
                die;
            }

            break;

        case 'md_utl_revisao_triagem_cadastrar':

            $arrComandos[] = '<button type="button" accesskey="s" id="btnSalvar" value="salvar" onclick="salvar();" class="infraButton botaoSalvar"><span class="infraTeclaAtalho">S</span>alvar</button>';
            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="window.history.back();" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

            $tpAcaoAval = MdUtlControleDsmpRN::$EM_TRIAGEM;

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

            require_once 'md_utl_revisao_triagem_cadastro_acoes.php';

            if(isset($_POST) && count($_POST) > 0){

                $objMdUtlRelRevisTrgAnlsRN = new MdUtlRelRevisTrgAnlsRN();
                if ($idContest == 0)
                {
                    $isProcessoConcluido = $objMdUtlRelRevisTrgAnlsRN->cadastrarRevisaoTriagemAnalise($objControleDsmpDTO);
                    if ($isPgPadrao == 0) {
                        if ( $isProcessoConcluido ){
                            $strLink = "acao=arvore_visualizar&acao_origem=procedimento_visualizar&id_procedimento=$idProcedimento";
                            header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?'.$strLink));
                        } else {
                            header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_processo_listar&id_procedimento=' . $idProcedimento . '&is_processo_concluido=' . $isProcessoConcluido));
                        }
                    } else {
                        header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_meus_processos_dsmp_listar&id_procedimento=' . $idProcedimento . '&is_processo_concluido=' . $isProcessoConcluido));
                    }
                } else {
                    $arrDados = $objMdUtlRelRevisTrgAnlsRN->cadastrarRevisaoTriagemAnaliseContest(array($idProcedimento, $idContatoAtual, $strNumeroProcesso));
                    $isProcessoConcluido = $arrDados[0];
                    $isContatoVazio      = $arrDados[1];
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_gestao_solicitacoes_listar&id_procedimento=' . $idProcedimento . '&is_processo_concluido=' . $isProcessoConcluido.'&is_contato_vazio='.$isContatoVazio));
                }
                die;
            }

            break;

        case 'md_utl_revisao_analise_consultar':

            $isConsultar = true;

            $onClickCloseAction = in_array($_GET['acao_origem'], ['md_utl_analise_alterar', 'md_utl_triagem_alterar']) ? 'window.close();' : 'window.history.back();';
            $arrComandos[] = '<button type="button" accesskey="C" name="btnFechar" id="btnFechar" value="Fechar" onclick="'.$onClickCloseAction.'" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';

            $tpAcaoAval = MdUtlControleDsmpRN::$EM_ANALISE;

            $numId = $objControleDsmpDTO->getStrStaAtendimentoDsmp() == MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE
                ? $objControleDsmpDTO->getNumIdMdUtlControleDsmp()
                : $objControleDsmpDTO->getNumIdMdUtlAnalise();

            $nm_usuario_analise = MdUtlControleDsmpINT::getNomeUsuarioRespTriagAnaliseAval(
                $numId,
                $objControleDsmpDTO->getStrStaAtendimentoDsmp(),
                MdUtlControleDsmpRN::$STR_TIPO_ACAO_ANALISE,
                'A'
            );

            require_once 'md_utl_revisao_analise_cadastro_acoes.php';

            break;

        case 'md_utl_revisao_triagem_consultar':

            $isConsultar = true;

            $onClickCloseAction = $_GET['acao_origem'] == 'md_utl_triagem_alterar' ? 'window.close();' : 'window.history.back();';
            $arrComandos[] = '<button type="button" accesskey="C" name="btnFechar" id="btnFechar" value="Fechar" onclick="'.$onClickCloseAction.'" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';

            $tpAcaoAval = MdUtlControleDsmpRN::$EM_TRIAGEM;

            $numId = $objControleDsmpDTO->getStrStaAtendimentoDsmp() == MdUtlControleDsmpRN::$EM_CORRECAO_TRIAGEM
            ? $objControleDsmpDTO->getNumIdMdUtlControleDsmp()
            : $objControleDsmpDTO->getNumIdMdUtlTriagem();

            $nm_usuario_triagem = MdUtlControleDsmpINT::getNomeUsuarioRespTriagAnaliseAval(
                $numId,
                $objControleDsmpDTO->getStrStaAtendimentoDsmp(),
                MdUtlControleDsmpRN::$STR_TIPO_ACAO_TRIAGEM,
                'T'
            );

            require_once 'md_utl_revisao_triagem_cadastro_acoes.php';

            break;

        //region Erro
        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");

    }

}catch(Exception $e){
    PaginaSEI::getInstance()->processarExcecao($e);
}

$habDivDistAutoTriagem = $encaminhamentoRevisao['sta_encaminhamento'] == 'N';

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(':: ' . PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo . ' ::');
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,"onload='inicializar();'");

//texto do tooltip
$txtTooltipEncaminhamentoRevisao="Selecione a opção Associar em Fila após Finalizar Fluxo caso queira reiniciar o fluxo em alguma Fila imediatamente com a finalização do fluxo atual. Esta opção é listada somente se a Avaliação Qualitativa das Atividades Entregues for maior que 4. \n \n Selecione a opção Finalizar Fluxo para concluir sem associar a qualquer Fila imediatamente na finalização do fluxo atual. Esta opção é listada somente se a Avaliação Qualitativa das Atividades Entregues for maior que 4. \n \n Selecione a opção Retornar para Correção por outro Participante na mesma Fila caso identificada necessidade de correção que possa ser feita por qualquer Membro Participante da Fila. A Análise da Correção ainda demandará sua Distribuição manual. Esta opção implica na perca do Tempo Executado pelo Membro Participante que fez a Análise atual. \n \n Selecione a opção Retornar para Correção pelo mesmo Participante caso identificada necessidade de correção e deseje que a Análise da Correção seja automaticamente distribuída para o Membro Participante que realizou a Análise atual. Esta opção implica na perca do Tempo Executado pelo Membro Participante que fez a Análise atual.";

?>

    <form  id="frmRevisaoCadastro" method="post"
           action="<?= PaginaSEI::getInstance()->formatarXHTML(
               SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])
           ) ?>">

        <div class="row">
            <div class="col-md-12">
            <?php PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);?>
            </div>
        </div>

        <?php if( $isPgPadrao != 0 ) { ?>
        <div class="row mb-2">
            <div class="col-12">
                <label style='margin-bottom: .2em; font-weight: bold; line-height: 1.5em; color: black;'>
                    Número do Processo:
                </label>
                <label><?= $strNumeroProcesso ?> </label>
            </div>
        </div>
        <?php } ?>

        <div class="row">
            <div class="col-sm-6 col-md-6 col-lg-6">
                <div class="form-group">
                    <label id="lblTipoControle" accesskey="" class="infraLabelOpcional">Tipo de Controle:</label>
                    <input type="text" id="txtTipoControle" name="txtTipoControle" class="form-control infraText" value="<?= $strNomeTpControle ?>" disabled="disabled"/>
                </div>
            </div>

            <div class="col-sm-6 col-md-6 col-lg-6">
                <div class="form-group">
                    <label id="lblFila" accesskey="" class="infraLabelOpcional">Fila:</label>
                    <input type="text" id="txtNomeFila" name="txtNomeFila" class="form-control infraText" value="<?= $strNomeFila ?>" disabled/>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm col-md col-lg">
                <div class="form-group">
                    <label class="infraLabelOpcional">Membro Responsável pela Avaliação:</label>
                    <input type="text" value="<?= $nm_usuario_avaliacao ?>" readonly class="infraText form-control">
                </div>
            </div>
            <?php if ( !is_null( $nm_usuario_triagem ) ) { ?>
                <div class="col-sm col-md col-lg">
                    <div class="form-group">
                        <label class="infraLabelOpcional">Membro Responsável pela Triagem:</label>
                        <input type="text" value="<?= $nm_usuario_triagem ?>" readonly class="infraText form-control">
                    </div>
                </div>
            <?php } ?>

            <?php if ( !is_null( $nm_usuario_analise ) ) { ?>
                <div class="col-sm col-md col-lg">
                    <div class="form-group">
                        <label class="infraLabelOpcional">Membro Responsável pela Análise:</label>
                        <input type="text" value="<?= $nm_usuario_analise ?>" readonly class="infraText form-control">
                    </div>
                </div>
            <?php } ?>
        </div>

        <div class="row">
            <div class="col-xl-6 col-lg-6 col-md-7 col-sm-12 col-xs-6">
                <div class="form-group mb-0">
                    <label for="selAvalQualitativa" class="infraLabelObrigatorio">
                        Avaliação Qualitativa das Atividades Entregues:
                        <img class="infraImg" name="ajuda" src="/infra_css/svg/ajuda.svg" onmouseover="return infraTooltipMostrar('A Avaliação Qualitativa das Atividades Entregues ocorre com a atribuição de uma nota entre 0 e 10 para representar a qualidade do que foi entregue como um todo, onde 0 é a menor nota e 10 a maior nota. \n \n Ao selecionar nota entre 0 e 4 implica na reprovação das Atividades Entregues, sendo necessário o Retorno para Correção.','Ajuda');" onmouseout="return infraTooltipOcultar();">
                    </label>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="form-group mb-3">
                   <select name="selAvalQualitativa" id="selAvalQualitativa" class="form-control infraSelect" <?=$disabled?> onchange="avaliacaoQualitativa( this )">
                        <option value=''></option>
                        <?php
                            $selSelQualitativa = range(0,10);
                            foreach( $selSelQualitativa as $item ){
                                if( isset($vlrAvaliacaoQualitativa) && $vlrAvaliacaoQualitativa == $item && $vlrAvaliacaoQualitativa != ''){
                                    echo "<option value='$item' selected >$item</option>";
                                }else{
                                    echo "<option value='$item'>$item</option>";
                                }
                            }
                        ?>
                    </select>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div id='txtAlertAvalQualitativa' class="mb-3" style="display: none">
                    <label class="infraLabelOpcional text-danger"><span style="font-weight: bold; font-size: 0.875rem;">Atenção:</span> A nota selecionada implica na reprovação das Atividades Entregues. </label>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <label id="lblInformacaoComplementar" for="txaInformacaoComplementar" class="infraLabelOpcional"
                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                        Justificativa da Avaliação Qualitativa:
                    </label>
                    <img class="infraImg" name="ajuda" src="/infra_css/svg/ajuda.svg" onmouseover="return infraTooltipMostrar('A Justificativa da Avaliação Qualitativa não é obrigatória, contudo, pode ser útil para explicar detalhes sobre a nota atribuída e do que deve ser corrigido.','Ajuda');" onmouseout="return infraTooltipOcultar();">
                    <textarea id="txaInformacaoComplementar" <?=$disabled?> name="txaInformacaoComplementar" rows="3" class="form-control infraTextArea" maxlength="500" onkeypress="return infraMascaraTexto(this,event, 500);" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?= $isConsultar?$strInformCompRevisao:''?></textarea>
                </div>
            </div>
        </div>

        <div class="row">
            <?
                $isConsultaContestacao = !is_null($objMdUtlRevisaoDTO) && !is_null($objMdUtlRevisaoDTO->getStrStaEncaminhamentoContestacao()) && $isConsultar;

                if($idContest == 1 || $isConsultaContestacao){
                    $disabledContestacao = '';

                    if($isConsultar) {
                        $disabledContestacao = !is_null($objMdUtlRevisaoDTO->getStrStaEncaminhamentoContestacao()) ? 'disabled=disabled' : '';
                    }
            ?>

            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="form-group">
                    <label id="lblInformacaoComplementar" for="txaInformacaoComplementar" class="infraLabelObrigatorio"
                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                    Encaminhamento da Contestação:
                </label>
                <?php
                    $option = MdUtlRevisaoINT::montarSelectEncaminhamentoContestacao($encaminhamentoRevisao['sta_encaminhamento'], $idContest);
                    ?>
                    <select <?php echo $disabledContestacao; ?> class="form-control infraSelect" name="selEncaminhamentoContest"  id="selEncaminhamentoContest" onchange="encaminhamento(this.value)">
                        <?= $option ?>
                    </select>
                </div>
            </div>

            <? } else { ?>

            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                <div class="form-group">
                    <label id="lblInformacaoComplementar" for="txaInformacaoComplementar" class="infraLabelObrigatorio"
                            tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                        Encaminhamento da Avaliação:
                    </label>

                    <img class="infraImg" name="ajuda" src="/infra_css/svg/ajuda.svg" onmouseover="return infraTooltipMostrar('Selecione a opção Associar em Fila após Finalizar Fluxo caso queira reiniciar o fluxo em alguma Fila imediatamente com a finalização do fluxo atual. Esta opção é listada somente se a Avaliação Qualitativa das Atividades Entregues for maior que 4. \n \n Selecione a opção Finalizar Fluxo para concluir sem associar a qualquer Fila imediatamente na finalização do fluxo atual. Esta opção é listada somente se a Avaliação Qualitativa das Atividades Entregues for maior que 4. \n \n Selecione a opção Retornar para Correção por outro Participante na mesma Fila caso identificada necessidade de correção que possa ser feita por qualquer Membro Participante da Fila. A Análise da Correção ainda demandará sua Distribuição manual. Esta opção implica na perca do Tempo Executado pelo Membro Participante que fez a Análise atual. \n \n Selecione a opção Retornar para Correção pelo mesmo Participante caso identificada necessidade de correção e deseje que a Análise da Correção seja automaticamente distribuída para o Membro Participante que realizou a Análise atual. Esta opção implica na perca do Tempo Executado pelo Membro Participante que fez a Análise atual.','Ajuda');" onmouseout="return infraTooltipOcultar();">
                    <?php $option = MdUtlRevisaoINT::montarSelectEncaminhamento($encaminhamentoRevisao['sta_encaminhamento'],$isConsultar); ?>
                    <select class="form-control infraSelect" name="selEncaminhamento"  id="selEncaminhamento" <?=$disabled?> onchange="encaminhamento(this.value)">
                        <?=$option?>
                    </select>

                </div>
            </div>

            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12" id="divFila" style="display: none;">
                <div class="form-group">
                    <div class="mt-2" style="width: 100%">
                        <label id="lblFila" for="selFila" class="infraLabelObrigatorio">Fila:</label>
                        <select id="selFila" name="selFila" <?= $disabled ?> class="form-control infraSelect" onchange="distribuicaoAutoParaMim(this, 1 , <?= $idUsuarioDistrAuto ?: 0 ?> , 'avaliacao')">
                            <?= $selFila ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-3" id="divDistAutoTriagAnalise" <?= $habDivDistAutoTriagem ? '' : 'style="display:none;"'?> >
            <div class="col-12">
                <div class="form-check-inline mb-0 infraCheckboxDiv">
                    <input type="checkbox" name="ckbDistAutoTriagAnalise" id="ckbDistAutoTriagAnalise" class="infraCheckboxInput"
                     <?= $chkDistAutoTriagem ? 'checked' : '' ?> value='S' <?= $disabled ?>>
                    <label class="infraCheckboxLabel" for="ckbDistAutoTriagAnalise"></label>
                </div>                
                <label class="infraLabelOpcional" for="ckbDistAutoTriagAnalise" style="margin-left: -3px">
                    Distribuir automaticamente a Triagem do próximo fluxo para o último <?= $strTriagOuAnalise ?>?
                </label>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div id='txtAlertEncAvaliacao' class="mb-3" style='display:none;'>
                    <label class="infraLabelOpcional text-danger"> <span style="font-weight: bold; font-size: 0.875rem;">Atenção:</span> O encaminhamento selecionado implica na reprovação das Atividades Entregues e o Tempo Executado pelo Participante correspondente será desconsiderado. </label>
                </div>
            </div>
            <? } ?>

        </div>

        <div class="row">
            <div class="col-12">
                <div class="form-check form-check-inline mb-0">
                    <div class="form-check-inline infraCheckboxDiv">
                        <input type="checkbox" name="cbkRealizarAvalProdAProd" id="cbkRealizarAvalProdAProd" class="infraCheckboxInput"
                        <?= $ckbRealizarAvalProdProd . $disabled ?> value='S' onchange="realizarAvaliacaoProd( this ) ">
                        <label class="infraCheckboxLabel" for="cbkRealizarAvalProdAProd"></label>
                    </div>
                    <label for="cbkRealizarAvalProdAProd" class="form-check-label infraLabelOpcional pt-1" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                        Realizar Avaliação Produto a Produto
                    </label>
                    <img class="infraImg ml-1" name="ajuda" src="/infra_css/svg/ajuda.svg" onmouseover="return infraTooltipMostrar('Marque a opção Realizar Avaliação Produto a Produto caso seja necessário avaliar, justificar e explicar detalhes da avaliação sobre cada Produto entregue.','Ajuda');" onmouseout="return infraTooltipOcultar();">
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <?php PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistro); ?>
        </div>
        <?php PaginaSEI::getInstance()->abrirAreaDados('auto'); ?>
        <div class="row">
            <div class="col-12">
                <?= $divInfComplementar ?>
            </div>
        </div>

        <input type="hidden" id="hdnTbRevisaoAnalise" name="hdnTbRevisaoAnalise"   value="<?=$hdnTbRevisaoAnalise?>"/>
        <input type="hidden" id="hdnIdProcedimento"   name="hdnIdProcedimento"     value="<?=$idProcedimento?>"/>
        <input type="hidden" id="hdnIdFilaAtiva"      name="hdnIdFilaAtiva"        value="<?=$idFilaAtiva?>"/>
        <input type="hidden" id="hdnIdTpCtrl"         name="hdnIdTpCtrl"           value="<?=$idTipoControle?>"/>
        <input type="hidden" id="hdnTmpExecucao"       name="hdnTmpExecucao"         value="<?=$valorTempoExecucao?>"/>
        <input type="hidden" id="hdnEncaminhamento" name="hdnEncaminhamento" value="">
        <input type="hidden" id="hdnAssociarFila" name="hdnAssociarFila" value="">
        <input type="hidden" id="selAssociarProcFila" name="selAssociarProcFila" value="">
        <input type="hidden" id="hdnFila" name="hdnFila" value="">
        <input type="hidden" name="hdnIsPgPadrao" id="hdnIsPgPadrao" value="<?= $isPgPadrao; ?>"/>
        <input type="hidden" name="hdnSelFila" id="hdnSelFila" value=""/>
        <input type="hidden" id="hdnIdMdUtlContestRevisao" name="hdnIdMdUtlContestRevisao" value="<?= $idContest ?>"/>
        <input type="hidden" id="chkDistAutoTriagem" value="<?= $chkDistAutoTriagem ?: '' ?>">
        <input type="hidden" id="validaDistAutoTriagem" value="<?= $validaDistAutoTriagem ? 'ok' : 'err' ?>">
        <input type="hidden" name="hdnIdUsuarioDistrAuto" value="<?= $idUsuarioDistrAuto ?>">
        <input type="hidden" name="hdnNmUsuarioDistrAuto" value="<?= $strNomeUsuarioDistrAuto ?>">

        <?php
            PaginaSEI::getInstance()->fecharAreaDados();
            PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
        ?>

    </form>
    <script>
        let cmpEncaminhamento     = document.getElementById('selEncaminhamento');
        let cmpEncaminhamentoCont = document.getElementById('selEncaminhamentoContest');

        if( ( cmpEncaminhamento !== null && cmpEncaminhamento.value === 'N' ) || ( cmpEncaminhamentoCont !== null && cmpEncaminhamentoCont.value === 'N' ) ){
            let divFila = document.getElementById('divFila');
            if( divFila !== null ) divFila.style.display = 'block' ;
        }
    </script>

<?php

require_once "md_utl_geral_js.php";
require_once "md_utl_funcoes_js.php";
require_once "md_utl_revisao_cadastro_js.php";
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
