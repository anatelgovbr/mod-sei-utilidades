<?

try {
    require_once dirname(__FILE__).'/../../SEI.php';

    session_start();

    $idControleDesempenho = array_key_exists('id_controle_desempenho', $_GET) ? $_GET['id_controle_desempenho'] : $_POST['hdnIdControleDesempenho'];
    $idTriagem = array_key_exists('id_triagem', $_GET) ?  $_GET['id_triagem'] : null;

    SessaoSEI::getInstance()->validarLink();
    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);


    $objMdUtlRelTriagemAtvRN = new MdUtlRelTriagemAtvRN();
    $objControleDesempenhoRN = new MdUtlControleDsmpRN();

    $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();

    $objControleDesempenhoDTO = $objControleDesempenhoRN->getObjControleDsmpPorId($idControleDesempenho);

    $idProcedimento = $objControleDesempenhoDTO->getDblIdProcedimento();

    $objMdUtlRevisaoDTO        = new MdUtlRevisaoDTO();
    $objMdUtlRevisaoRN         = new MdUtlRevisaoRN();
    $objMdUtlContestacaoDTO    = new MdUtlContestacaoDTO();
    $objMdUtlContestacaoRN     = new MdUtlContestacaoRN();
    $objMdUtlAdmJustContestDTO = new MdUtlAdmJustContestDTO();
    $objMdUtlAdmJustContestRN  = new MdUtlAdmJustContestRN();
    $objMdUtlAdmTpCtrlUndRN   = new MdUtlAdmRelTpCtrlDesempUndRN();
    $idTipoControle           =  $objMdUtlAdmTpCtrlUndRN->getTipoControleUnidadeLogada();

    $objMdUtlRevisaoDTO->setNumIdMdUtlRevisao($objControleDesempenhoDTO->getNumIdMdUtlRevisao());
    $objMdUtlRevisaoDTO->retTodos();

    $objMdUtlRevisaoDTO = $objMdUtlRevisaoRN->consultar($objMdUtlRevisaoDTO);

    $strInformCompRevisao = $objMdUtlRevisaoDTO->getStrInformacoesComplementares();
    $strInformComp = '';
    $selTipoJustificativa = '';
    $arrComandos = array();

    $objMdUtlAdmTpContestacaoDTO = null;

    $staStatus    = trim($objControleDesempenhoDTO->getStrStaAtendimentoDsmp());
    $arrSituacao  = MdUtlControleDsmpINT::retornaArrSituacoesControleDsmpCompleto();
    $strStatus    = $arrSituacao[$staStatus];

    $arrDataHoraCompleta = explode(' ', $objControleDesempenhoDTO->getDthPrazoTarefa());
    $dthFormatada = count($arrDataHoraCompleta) >0 ? $arrDataHoraCompleta[0] : '';

    $strResultado = '';
    if($staStatus == MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE){
        $objMdUtlRelAnaliseProdutoDTO = new MdUtlRelAnaliseProdutoDTO();
        $objMdUtlRelAnaliseProdutoRN  = new MdUtlRelAnaliseProdutoRN();

        $objMdUtlRelAnaliseProdutoDTO->setNumIdMdUtlAnalise($objControleDesempenhoDTO->getNumIdMdUtlAnalise());
        $objMdUtlRelAnaliseProdutoDTO->retTodos(true);
        $arrObjMdUtlRelAnaliseProdutoDTO = $objMdUtlRelAnaliseProdutoRN->listar($objMdUtlRelAnaliseProdutoDTO);
        
        $objMdUtlRelRevisTrgAnlsRN = new MdUtlRelRevisTrgAnlsRN();
        $objMdUtlRelRevisTrgAnlsDTO = new MdUtlRelRevisTrgAnlsDTO();
        $objMdUtlRelRevisTrgAnlsDTO->setNumIdMdUtlRevisao($objControleDesempenhoDTO->getNumIdMdUtlRevisao());
        $objMdUtlRelRevisTrgAnlsDTO->retTodos();
        $objMdUtlRelRevisTrgAnlsDTO->retStrNomeTipoRevisao();
        $objMdUtlRelRevisTrgAnlsDTO->retStrNomeJustificativaRevisao();
        $objMdUtlRelRevisTrgAnlsDTO->retStrObservacao();
        $arrObjMdUtlRelRevisTrgAnlsDTO = $objMdUtlRelRevisTrgAnlsRN->listar($objMdUtlRelRevisTrgAnlsDTO);
        
        foreach ($arrObjMdUtlRelAnaliseProdutoDTO as $objRelAnaliseProdutoDTO) {

            $nomeAtividade = $objRelAnaliseProdutoDTO->getStrNomeAtividade().' - ' . MdUtlAdmAtividadeRN::$ARR_COMPLEXIDADE[$objRelAnaliseProdutoDTO->getNumComplexidadeAtividade()];
            $objDTORevisao = InfraArray::filtrarArrInfraDTO($arrObjMdUtlRelRevisTrgAnlsDTO, 'IdMdUtlRelAnaliseProduto', $objRelAnaliseProdutoDTO->getNumIdMdUtlRelAnaliseProduto());
            reset($objDTORevisao);
            $objDTORevisao = current($objDTORevisao);

            $bolDocSei = $objRelAnaliseProdutoDTO->getStrNomeSerie() != "";
            $strProduto = $bolDocSei ? $objRelAnaliseProdutoDTO->getStrNomeSerie() : $objRelAnaliseProdutoDTO->getStrNomeProduto();

            $strCssTr = $strCssTr == '<tr class="infraTrClara">' ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">';

            $strResultado .= $strCssTr;
            //Atividade
            $strResultado .= '<td>'.$objRelAnaliseProdutoDTO->getStrNomeAtividade() . ' - ' . MdUtlAdmAtividadeRN::$ARR_COMPLEXIDADE[$objRelAnaliseProdutoDTO->getNumComplexidadeAtividade()].'</td>';

            //Produto
            $bolDocSei = $objRelAnaliseProdutoDTO->getStrNomeSerie() != "" ? true : false;
            $strProduto = $bolDocSei ? $objRelAnaliseProdutoDTO->getStrNomeSerie() : $objRelAnaliseProdutoDTO->getStrNomeProduto();
            $strResultado .= '<td>'.$strProduto.'</td>';

            //Documento
            $strDocumento = "";
            if ($bolDocSei) {
                $dblIdDocumento = $objRelAnaliseProdutoDTO->getDblIdDocumento();
                $strDocumento = $strProduto . " (" . $objRelAnaliseProdutoDTO->getStrDocumentoFormatado() . ")";
                $strAcoesDocumento = '<a href="#" onclick="infraAbrirJanela(\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=documento_visualizar&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento=' . $idProcedimento . '&id_documento=' . $dblIdDocumento . '&arvore=1') . '\',\'janelaCancelarAssinaturaExterna\',850,600,\'location=0,status=1,resizable=1,scrollbars=1\')" tabindex="' . $numTabBotao . '" class="botaoSEI">' . $strDocumento . '</a>';
            }
            $strResultado .= '<td>'.$strAcoesDocumento.'</td>';

            foreach ($arrObjMdUtlRelRevisTrgAnlsDTO as $bjMdUtlRelRevisTrgAnlsDTO) {
                $resultadoRevisao = $bjMdUtlRelRevisTrgAnlsDTO->getStrNomeTipoRevisao();
                $justificativaRevisao = $bjMdUtlRelRevisTrgAnlsDTO->getStrNomeJustificativaRevisao();
            }

            //Resultado
            $strResultado .= '<td>'.$resultadoRevisao.'</td>'; //todo

            //Justificativa
            $strResultado .= '<td>'.$justificativaRevisao.'</td>'; //todo

            //Observação
            $strObsRev = !empty($objDTORevisao) ? $objDTORevisao->getStrObservacao() : '';
            $strResultado .= '<td>'. $strObsRev .'</td>';
            $strResultado .= '</tr>';

        }

    } else {

        $objMdUtlRelTriagemAtvRN = new MdUtlRelTriagemAtvRN();
        $objMdUtlRelTriagemAtvDTO = new MdUtlRelTriagemAtvDTO();
        $objMdUtlRelTriagemAtvDTO->setNumIdMdUtlTriagem($objControleDesempenhoDTO->getNumIdMdUtlTriagem());
        $objMdUtlRelTriagemAtvDTO->retTodos();
        $objMdUtlRelTriagemAtvDTO->retNumComplexidadeAtividade() ;
        $objMdUtlRelTriagemAtvDTO->retStrNomeAtividade();
        $arrObjMdUtlRelTriagemAtDTO = $objMdUtlRelTriagemAtvRN->listar($objMdUtlRelTriagemAtvDTO);

        $objMdUtlRelRevisTrgAnlsDTO = new MdUtlRelRevisTrgAnlsDTO();
        $objMdUtlRelRevisTrgAnlsRN = new MdUtlRelRevisTrgAnlsRN();
        $objMdUtlRelRevisTrgAnlsDTO->setNumIdMdUtlRevisao($objControleDesempenhoDTO->getNumIdMdUtlRevisao());
        $objMdUtlRelRevisTrgAnlsDTO->retStrNomeTipoRevisao();
        $objMdUtlRelRevisTrgAnlsDTO->retStrNomeJustificativaRevisao();
        $objMdUtlRelRevisTrgAnlsDTO->retTodos();
        $arrObjMdUtlRelRevisTrgAnlsDTO = $objMdUtlRelRevisTrgAnlsRN->listar($objMdUtlRelRevisTrgAnlsDTO);

        $strCssTr = $strCssTr == '<tr class="infraTrClara">' ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">';
        foreach ($arrObjMdUtlRelTriagemAtDTO as $objDTOTriagem) {

            $objDTORevisao = InfraArray::filtrarArrInfraDTO($arrObjMdUtlRelRevisTrgAnlsDTO, 'IdMdUtlRelTriagemAtv', $objDTOTriagem->getNumIdMdUtlRelTriagemAtv());
            reset($objDTORevisao);
            $objDTORevisao = current($objDTORevisao);
            //Atividade
            $strResultado .= $strCssTr;
            $strResultado .= '<td>'.$objDTOTriagem->getStrNomeAtividade() . ' - ' . MdUtlAdmAtividadeRN::$ARR_COMPLEXIDADE[$objDTOTriagem->getNumComplexidadeAtividade()].'</td>';

            //Resultado
            $strResultado .= '<td>'.$objDTORevisao->getStrNomeTipoRevisao().'</td>';

            //Justificativa
            $strResultado .= '<td>'.$objDTORevisao->getStrNomeJustificativaRevisao().'</td>';

            //Observação
            $strResultado .= '<td>'.$objDTORevisao->getStrObservacao().'</td>';
            $strResultado .= '</tr>';

        }

    }

    switch ($_GET['acao']) {
        case 'md_utl_contest_revisao_cadastrar':
            $strTitulo = 'Nova Contestação de Avaliação';
            $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarMdUtlContestacao" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idControleDesempenho).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

           $selTipoJustificativa = MdUtlContestacaoINT::montarSelectJustificativa(' ', '', null, $idTipoControle);

            if (isset($_POST['sbmCadastrarMdUtlContestacao'])) {
                try {
                    $objMdUtlContestacaoRN = new MdUtlContestacaoRN();
                    $objMdUtlContestacaoDTO->setNumIdMdUtlContestRevisao(null);
                    $objMdUtlContestacaoDTO->setStrStaSolicitacao(MdUtlContestacaoRN::$PENDENTE_RESPOSTA);
                    $objMdUtlContestacaoDTO->setStrSinAtivo('S');
                    $objMdUtlContestacaoDTO->setNumIdMdUtlAdmJustContest($_POST['selTipoJustificativa']);
                    $objMdUtlContestacaoDTO->setStrInformacoesComplementares($_POST['txaInformacoes']);

                    $strDetalheFluxoAtend = MdUtlContestacaoRN::$SOLICITACAO;

                    $objControleDesempenhoNovoDTO = $objMdUtlContestacaoRN->solicitarContestacao(array($objMdUtlContestacaoDTO, $objControleDesempenhoDTO, $strDetalheFluxoAtend));

                    header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_procedimento_selecionado='.$objControleDesempenhoNovoDTO->getDblIdProcedimento().PaginaSEI::getInstance()->montarAncora($objControleDesempenhoNovoDTO->getDblIdProcedimento())));
                    die;
                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
            }

            break;

        case 'md_utl_contest_revisao_alterar':
            $strTitulo = 'Alterar Contestação de Avaliação';
            $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarMdUtlContestacao" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
            $arrComandos[] = '<button type="button" accesskey="L" name="sbmCancelarContestacao" id="sbmCancelarContestacao" value="Cancelar Contestação" onclick="deletaContestacao()" class="infraButton">Cance<span class="infraTeclaAtalho">l</span>ar Contestação</button>';
            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idControleDesempenho).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';
            $isAlterar     = true;

            $idContestRevisao = array_key_exists('id_contestacao_revisao', $_GET) ? $_GET['id_contestacao_revisao'] : $_POST['hdnIdMdUtlContestRevisao'];

            // Recuperar Dados
            $objMdUtlContestacaoDTO->setNumIdMdUtlContestRevisao($idContestRevisao);
            $objMdUtlContestacaoDTO->retTodos();
            $objMdUtlContestacaoDTO->retNumIdMdUtlAdmJustContest();
            $objMdUtlContestacaoDTO->retStrStaSolicitacao();
            $objMdUtlContestacaoDTO->retStrInformacoesComplementares();
            $objMdUtlContestacaoDTO->setNumMaxRegistrosRetorno(1);
            $objMdUtlContestacaoDTO = $objMdUtlContestacaoRN->consultar($objMdUtlContestacaoDTO);

            $idJustificativa = $objMdUtlContestacaoDTO->getNumIdMdUtlAdmJustContest();
            $objMdUtlAdmJustContestDTO->setNumIdMdUtlAdmJustContest($objMdUtlContestacaoDTO->getNumIdMdUtlAdmJustContest());
            $objMdUtlAdmJustContestDTO->retTodos();
            $objMdUtlAdmJustContestDTO->retStrNome();
            $objMdUtlAdmJustContestDTO->retStrDescricao();
            $objJustificativa = $objMdUtlAdmJustContestRN->consultar($objMdUtlAdmJustContestDTO);
            $idJust = $objJustificativa ? $objJustificativa->getNumIdMdUtlAdmJustContest() : null;
            $nomeJust = $objJustificativa ? $objJustificativa->getStrNome() : null;

            // Select Justificativa
            $selTipoJustificativa = MdUtlContestacaoINT::montarSelectJustificativa(' ', '', $idJust, $idTipoControle);

            // Informações Complementares
            $strInformComp = $objMdUtlContestacaoDTO->getStrInformacoesComplementares();

            if (isset($_POST['sbmAlterarMdUtlContestacao'])) {
                try {
                    $objMdUtlContestacaoRN = new MdUtlContestacaoRN();
                    $objMdUtlContestacaoDTO->setNumIdMdUtlContestRevisao(null);
                    $objMdUtlContestacaoDTO->setStrStaSolicitacao(MdUtlContestacaoRN::$PENDENTE_RESPOSTA);
                    $objMdUtlContestacaoDTO->setNumIdMdUtlAdmJustContest($_POST['selTipoJustificativa']);
                    $objMdUtlContestacaoDTO->setStrInformacoesComplementares($_POST['txaInformacoes']);

                    $strDetalheFluxoAtend = MdUtlContestacaoRN::$ALTERACAO;

                    $objControleDesempenhoNovoDTO = $objMdUtlContestacaoRN->solicitarContestacao(array($objMdUtlContestacaoDTO, $objControleDesempenhoDTO, $strDetalheFluxoAtend));

                    header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_procedimento_selecionado='.$objControleDesempenhoNovoDTO->getDblIdProcedimento().PaginaSEI::getInstance()->montarAncora($objControleDesempenhoNovoDTO->getDblIdProcedimento())));
                    die;
                }catch(Exception $e){
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
            } else if (isset($_POST['sbmCancelarContestacao'])) {
                try {

                    $objMdUtlContestacaoDTO->setStrStaSolicitacao(MdUtlContestacaoRN::$CANCELADA);
                    $objMdUtlContestacaoDTO->setStrSinAtivo(MdUtlContestacaoRN::$INATIVO);

                    $strDetalheFluxoAtend = MdUtlContestacaoRN::$CANCELAMENTO;

                    $objControleDesempenhoNovoDTO = $objMdUtlContestacaoRN->solicitarContestacao(array($objMdUtlContestacaoDTO, $objControleDesempenhoDTO, $strDetalheFluxoAtend));

                    header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).PaginaSEI::getInstance()->montarAncora($objControleDesempenhoNovoDTO->getDblIdProcedimento()));
                    die;
                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
            }

            break;

        case 'md_utl_contest_revisao_consultar':
            $strTitulo = 'Consultar Contestação de Avaliação';
            $arrComandos[] = '<button type="button" accesskey="C" name="btnFechar" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idControleDesempenho).'\';" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';

            $idContestRevisao = array_key_exists('id_contestacao_revisao', $_GET) ? $_GET['id_contestacao_revisao'] : $_POST['hdnIdMdUtlContestRevisao'];

            $objMdUtlContestacaoDTO->setNumIdMdUtlContestRevisao($idContestRevisao);
            $objMdUtlContestacaoDTO->retTodos();
            $objMdUtlContestacaoDTO->retNumIdMdUtlAdmJustContest();
            $objMdUtlContestacaoDTO->retStrStaSolicitacao();
            $objMdUtlContestacaoDTO->retStrInformacoesComplementares();
            $objMdUtlContestacaoDTO->setNumMaxRegistrosRetorno(1);
            $objMdUtlContestacaoDTO = $objMdUtlContestacaoRN->consultar($objMdUtlContestacaoDTO);

            $idJustificativa = $objMdUtlContestacaoDTO->getNumIdMdUtlAdmJustContest();

            $selTipoJustificativa = MdUtlContestacaoINT::montarSelectJustificativa(' ', '',$idJustificativa, $idTipoControle);

            // INformações Complementares
            $strInformComp = $objMdUtlContestacaoDTO->getStrInformacoesComplementares();
            break;

        default:
            throw new InfraException("Ação '".$_GET['acao']."' não reconhecida");
    }

} catch (Exception $e) {
    PaginaSEI::getInstance()->processarExcecao($e);
}

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema().' - '.$strTitulo);
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
?>

<?if(0){?><style><?}?>
    #txaInformacoes {position:relative;left:0%;top:29%;width:95%;resize: none}
    #txaInformacoesRevisao {position:relative;left:0%;width:95%;resize: none}
    #selTipoJustificativa { width: 182px;}

    .colunaPrincipal{
        width: 127px;
    }

    <?if(0){?></style><?}?>

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
require_once 'md_utl_geral_js.php';
?>

<?if(0){?><script type="text/javascript"><?}?>

    var msgPadrao = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_11); ?>';
    var msg95 = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_95); ?>';

    function inicializar() {
        if ('<?=$_GET['acao']?>' == 'md_utl_contest_revisao_cadastrar') {
            document.getElementById('selTipoJustificativa').focus();
        } else if ('<?=$_GET['acao']?>' == 'md_utl_contest_revisao_consultar') {
            infraDesabilitarCamposAreaDados();
        } else {
            document.getElementById('btnCancelar').focus();
        }
        infraEfeitoTabelas(true);
    }

    function validarJustificativas() {
        var cont = document.getElementById('selTipoJustificativa').options.length;
        if (cont == 0) {
            alert(msg95);
            document.getElementById('selTipoJustificativa').value = '';
            return false;
        }
        return true;
    }

    function validarCamposVazio() {
        var justificativa = infraGetElementById('selTipoJustificativa').value.length;
        var msg = setMensagemPersonalizada(msgPadrao, 'padrão');
        if (justificativa < 0) {
            alert(msg)
            return false;
        }

        return true;
    }

    function deletaContestacao() {
        var ok = confirm('Confirma o Cancelamento da Contestação de Avaliação?');
        if (ok) {
            document.getElementById('sbmCancelarContestacao').type = 'submit';
        } else {
            document.getElementById('sbmCancelarContestacao').type = 'button';
        }
    }

    function OnSubmitForm() {
        var valido = utlValidarObrigatoriedade();
         return valido;

    }


    <?if(0){?></script><?}?>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>

    <form id="frmMdUtlContestacaoCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
        <?
        PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        PaginaSEI::getInstance()->abrirAreaDados('auto');
        ?>
        <div>
            <table style="font-size: 1.0em;">
                <tr>
                    <td class="colunaPrincipal"><label id="lblProcessoDesc" name="lblProcessoDesc" class="infraLabelObrigatorio">Processo: </label>
                    </td>
                    <td><label id="lblProcessoValor" name="lblProcessoValor"
                               class="infraLabelOpcional"> <?php echo $objControleDesempenhoDTO->getStrProtocoloProcedimentoFormatado() ?> </label>
                    </td>
                </tr>

                <tr>
                    <td class="colunaPrincipal"><label id="lblStatusAtualDesc" name="lblStatusAtualDesc" class="infraLabelObrigatorio">Status: </label>
                    </td>
                    <td><label id="lblStatusAtualValor" name="lblStatusAtualValor"
                               class="infraLabelOpcional"> <?php echo $strStatus ?> </label>
                    </td>
                </tr>

                <tr>
                    <td class="colunaPrincipal"><label id="lblPrazoAtualDesc" name="lblPrazoAtualDesc" class="infraLabelObrigatorio">Prazo Atual: </label>
                    </td>
                    <td><label id="lblPrazoAtualValor" name="lblPrazoAtualValor"  class="infraLabelOpcional"> <?php echo $dthFormatada; ?> </label>
                    </td>
                </tr>

            </table>
        </div>


        <div id="resultadoRevisao" >
            <fieldset class="infraFieldset" style="padding-bottom: 3%; margin-top: 25px;" >
                <legend class="infraLegend" >Resultado da Avaliação</legend>
                </br>
                <div id="divInfraAreaTabela" class="infraAreaTabela">
                    <table width="99%" class="infraTable" summary="ResultadoRevisão" id="tbResultadoRevisão">
                        <caption class="infraCaption">&nbsp;</caption>
                        <tr>
                            <th style="display: none">Id</th>
                            <th class="infraTh"  align="center">Atividade</th>
                            <?php if($strStatus == MdUtlControleDsmpRN::$STR_EM_CORRECAO_ANALISE){ ?>
                                <th class="infraTh"  align="center">Produto</th>
                                <th class="infraTh" align="center">Documento</th>
                            <?php } ?>
                            <th class="infraTh"  align="center">Resultado</th>
                            <th class="infraTh" align="center">Justificativa</th>
                            <th class="infraTh" align="center">Observação  sobre a Avaliação</th>
                        </tr>
                        <?php echo $strResultado ?>
                    </table>
                </div>

                <!-- TextArea Informações complementares da Avaliação -->
                <div style="margin-top: 19px;">
                    <label id="lblInformacoes" for="txaInformacoes" accesskey="" class="infraLabelOpcional">Informações Complementares da Avaliação:</label>
                </div>
                <div>
                    <textarea type="text" id="txaInformacoesRevisao" rows="4" maxlength="500" name="txaInformacoesRevisao" disabled="disabled"
                              class="infraTextArea" onkeypress="return infraMascaraTexto(this,event,500);"
                              maxlength="500" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?= $strInformCompRevisao ?></textarea>
                </div>

            </fieldset>
        </div>

        <div id="contestacaoRevisao"  >
            <fieldset class="infraFieldset" style="padding-bottom: 4%; margin-top: 2%;" >
                <legend class="infraLegend" >Contestação da Avaliação</legend>
                </br>

                <div id="divContestacaoRevisao" >
                    <!-- Select Justificativa -->
                    <div style="margin-bottom: 1%">
                        <label for="selTipoJustificativa" id="lblJustificativa" for="Tipo" accesskey="" class="infraLabelObrigatorio">Justificativa: </label>
                        <select utlCampoObrigatorio="o"
                                id="selTipoJustificativa"
                                name="selTipoJustificativa"
                                class="infraSelect"
                                onclick="validarJustificativas()"
                                tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                            <?= $selTipoJustificativa ?>
                        </select>
                    </div>

                    <!-- TextArea Informações complementares da Contestação -->
                    <div>
                        <label id="lblInformacoes" for="txaInformacoes" accesskey="" class="infraLabelObrigatorio">Informações Complementares da Contestação:</label>
                    </div>
                    <div>
                    <textarea type="text" id="txaInformacoes" rows="4" maxlength="500" name="txaInformacoes"  utlCampoObrigatorio="a"
                              class="infraTextArea" onkeypress="return infraMascaraTexto(this,event,500);"
                              maxlength="500" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?= $strInformComp ?></textarea>
                    </div>
                </div>
            </fieldset>
        </div>

        <?
        PaginaSEI::getInstance()->fecharAreaDados();
        ?>

        <input type="hidden" id="hdnIdControleDesempenho" name="hdnIdControleDesempenho" value="<?=$idControleDesempenho?>" />
        <input type="hidden" id="hdnIdMdUtlContestRevisao" name="hdnIdMdUtlContestRevisao" value="<?=$idContestRevisao?>" />
        <input type="hidden" id="hdnIdContestRevisao" name="hdnIdContestRevisao" value="<?=$idContestRevisao?>" />
        <input type="hidden" id="hdnDetalheFluxoAtend" name="hdnDetalheFluxoAtend" value=""/>
        <input type="hidden" id="hdnTeste" name="hdnTeste" value="<?php echo $strDetalheFluxoAtend?>" />
        <input type="hidden" id="hdnIsTelaGerir" name="hdnIsTelaGerir" value="<?php echo $isTelaGerir; ?>">
        <?
        //PaginaSEI::getInstance()->montarAreaDebug();
        PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
        ?>
    </form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
