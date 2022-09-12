<?php

require_once dirname(__FILE__) . '/../../SEI.php';

session_start();
SessaoSEI::getInstance()->validarLink();

PaginaSEI::getInstance()->salvarCamposPost(array('txtProcessoMdGestao','selStatusProcMdGestao', 'selServidorMdGestao'));

$objMdUtlAdmTpCtrlUndRN     = new MdUtlAdmRelTpCtrlDesempUndRN();
$objMdUtlAdmTpCtrlUsuRN     = new MdUtlAdmRelTpCtrlDesempUsuRN();
$objMdUtlGestaoAjustPrazoRN = new MdUtlGestaoAjustPrazoRN();
$objMdUtlAjustePrazoRN      = new MdUtlAjustePrazoRN();
$objMdUtlControleDsmpRN     = new MdUtlControleDsmpRN();
$objMdUtlAdmUtlTpCtrlRN     = new MdUtlAdmTpCtrlDesempRN();
$objMdUtlFilaRN             = new MdUtlAdmFilaRN();
$objMdUtlAjustePrazoDTO     = new MdUtlAjustePrazoDTO();
$objRegrasGerais            = new MdUtlRegrasGeraisRN();
$msg102                     = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_102);
$isContatoVazioRev          = array_key_exists('is_contato_vazio', $_GET) ? $_GET['is_contato_vazio'] : $_POST['hdnIsContatoVazio'];

$txtProcessoCampo     = array_key_exists('txtProcessoMdGestao', $_POST) ? $_POST['txtProcessoMdGestao'] : PaginaSEI::getInstance()->recuperarCampo('txtProcessoMdGestao');
$selStatusProcCampo   = array_key_exists('selStatusProcMdGestao', $_POST) ? $_POST['selStatusProcMdGestao'] : PaginaSEI::getInstance()->recuperarCampo('selStatusProcMdGestao');
$selServidorCampo     = array_key_exists('selServidorMdGestao', $_POST) ? $_POST['selServidorMdGestao'] : PaginaSEI::getInstance()->recuperarCampo('selServidorMdGestao');
$selTpControleCampo   = array_key_exists('selTpControle', $_POST) ? $_POST['selTpControle'] : PaginaSEI::getInstance()->recuperarCampo('selTpControle');
$isProcessoConcluido  = array_key_exists('is_processo_concluido', $_GET) ? $_GET['is_processo_concluido'] : 0;
$isProcessoAutorizadoConcluir = array_key_exists('hdnIsConcluirProcesso', $_POST) ? $_POST['hdnIsConcluirProcesso'] : 0;
$idProcedimentoAprovacao = array_key_exists('id_procedimento', $_GET) ? $_GET['id_procedimento'] : $_POST['hdnIdProcedimento'];

if($idProcedimentoAprovacao != null && $idProcedimentoAprovacao != '') {
    $objProcedimentoDTO = $objRegrasGerais->getObjProcedimentoPorId($idProcedimentoAprovacao);
    $strNumeroProcedimento = $objProcedimentoDTO->getStrProtocoloProcedimentoFormatado();
    $msg107 = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_107, array($strNumeroProcedimento, SessaoSEI::getInstance()->getStrSiglaUnidadeAtual()));
}else{
    $msg107 = '';
}

if($isProcessoAutorizadoConcluir == 1){
    $_POST['hdnIsConcluirProcesso'] = 0;
    $isProcessoAutorizadoConcluir = 0;

    $objEntradaConcluirProcessoAPI = new EntradaConcluirProcessoAPI();
    $objEntradaConcluirProcessoAPI->setIdProcedimento($idProcedimentoAprovacao);

    $objSEIRN = new SeiRN();
    $objSEIRN->concluirProcesso($objEntradaConcluirProcessoAPI);
}

//URL Base
$strUrl        = 'controlador.php?acao=md_utl_gestao_ajust_prazo_';
$strUrlContest = 'controlador.php?acao=md_utl_gestao_contestacao_';

$strUrlFechar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_controlar&acao_origem=' . $_GET['acao']);

$arrPostDados = array('txtProcessoMdGestao' => $txtProcessoCampo, 'selStatusProcMdGestao'=> $selStatusProcCampo, 'selServidorMdGestao' => $selServidorCampo, 'selTpControle' => $selTpControleCampo);

$objMdUtlControleDsmpDTO = null;

/* Id Tipo de Controle */
$idTipoControle   = isset($_POST['selTpControle']) && !empty($_POST['selTpControle']) ? $_POST['selTpControle'] : null;
$arrIdsTpControle = array();

//Retorna tipos de controles onde o usuário é gestor
$arrGestorSipSei  = $objMdUtlAdmTpCtrlUsuRN->usuarioLogadoIsGestorTpControle();

//Retorna os tipos de controles da unidade
$arrObjTpControleUnid = $objMdUtlAdmTpCtrlUndRN->getArrayTipoControleUnidadeLogada();

$existeTpCtrlUnidade = !is_null( $arrObjTpControleUnid );
$userIsAvaliador     = 0;
if ( $existeTpCtrlUnidade ) {
    $arrIsAvaliador = $objMdUtlFilaRN->verificaUsuarioLogadoAvaliador(
        InfraArray::converterArrInfraDTO( $arrObjTpControleUnid , 'IdMdUtlAdmTpCtrlDesemp' )
    );
}

$userIsAvaliador = $arrIsAvaliador['qtdUserAvaliador'];

//Relaciona os tipos de controle da unidade onde o usuario é gestor
if ( count($arrObjTpControleUnid) > 0 && ($arrGestorSipSeicount || ( $arrGestorSipSei ) > 0 ) ){
    foreach ($arrObjTpControleUnid as $k => $v) {
        if ( in_array( $v->getNumIdMdUtlAdmTpCtrlDesemp() , $arrGestorSipSei ) ) array_push( $arrIdsTpControle , $v->getNumIdMdUtlAdmTpCtrlDesemp() );
    }
}

//Unifica os tipos de controle onde: o usuario é gestor e/ou avaliador
$arrIdsTpControle = array_unique(array_merge($arrIdsTpControle,$arrIsAvaliador['idsTpCtrlUsuarioAvaliador']));

//Ocorre um limpa para retornar somente os tipos de controles que tem relação como gestor ou avaliador
foreach ($arrObjTpControleUnid as $k => $v) {
    if ( !in_array( $v->getNumIdMdUtlAdmTpCtrlDesemp() , $arrIdsTpControle ) ) unset( $arrObjTpControleUnid[$k] );
}

$existeTpCtrlGestorUnid = !empty( $arrIdsTpControle );

$selTpControle = is_null($arrObjTpControleUnid) ? array() : MdUtlAdmFilaINT::montarSelectTpControle($arrObjTpControleUnid,'NumIdMdUtlAdmTpCtrlDesemp', 'StrNomeTipoControle',$_POST['selTpControle']);

$objSeridores = $objMdUtlGestaoAjustPrazoRN->recuperarServidoresSolicitacoes($idTipoControle);

/* Verifica se é gestor */
#$isGestorSipSei  = count($objMdUtlAdmTpCtrlUsuRN->usuarioLogadoIsGestorTpControle()) > 0;

$isPesquisar = array_key_exists('pesquisar',$_GET) ? $_GET['pesquisar'] : 0;

$isParametrizado = true;

if(!is_null($idTipoControle) || count($arrIdsTpControle) > 0 ){
    $numIdControleDsmp = '';
    $numIdAjustePrazo = '';
    $numIdControleDsmpCont = '';
    $idContestRevisaoExistente = '';
    $paramsTpCtrl = is_null($idTipoControle) ? $arrIdsTpControle : $idTipoControle;
    $objMdUtlControleDsmpDTO  = new MdUtlControleDsmpDTO();
    $objMdUtlControleDsmpDTO  = $objMdUtlGestaoAjustPrazoRN->buscarSolicitacoesAjustePrazo(array($paramsTpCtrl, $arrPostDados));
    $objMdUtlControleDsmpDTOCont  = $objMdUtlGestaoAjustPrazoRN->buscarSolicitacoesContestacao(array($paramsTpCtrl, $arrPostDados));

    $objMdUtlControleDsmpDist = $objMdUtlGestaoAjustPrazoRN->buscarSolicitacoesAjustePrazo(array($paramsTpCtrl, null));
    $arrStatusProcesso       = MdUtlGestaoAjustPrazoINT::montarSelectStatusProcesso($selStatusProcCampo);
    $arrObjsServidorDTO      = !is_null($objSeridores) ? InfraArray::distinctArrInfraDTO($objSeridores, 'IdUsuarioDistribuicao') : null;
    $arrSelServidor          = !is_null($arrObjsServidorDTO) ? MdUtlGestaoAjustPrazoINT::montarSelectServidor($selServidorCampo, $arrObjsServidorDTO) : '';
    $strUrlPadraoTela = 'controlador.php?acao=md_utl_gestao_solicitacoes_';

    $strUrlPesquisar  = SessaoSEI::getInstance()->assinarLink($strUrlPadraoTela . 'listar&acao_origem=' . $_GET['acao'] . '&pesquisar=1');
    $strUrlRecarregar = SessaoSEI::getInstance()->assinarLink($strUrlPadraoTela . 'listar&acao_origem=' . $_GET['acao']);
}

require_once 'md_utl_gestao_ajust_prazo_lista.php';

require_once 'md_utl_gestao_contestacao_lista.php';

$strTitulo = 'Gestão de Solicitações';

switch ($_GET['acao']) {

    //region Listar
    case 'md_utl_gestao_solicitacoes_listar':

        break;

    case 'md_utl_gestao_ajust_prazo_aprovar':
        $idControleDsmp   = array_key_exists('id_controle_desempenho', $_GET) ? $_GET['id_controle_desempenho'] : null;
        $numIdAjustePrazo = array_key_exists('id_ajuste_prazo', $_GET) ? $_GET['id_ajuste_prazo'] : null;

        $objControleDsmpDTO = new MdUtlControleDsmpDTO();
        $objControleDsmpDTO->setNumIdMdUtlControleDsmp($idControleDsmp);
        $objControleDsmpDTO->setNumIdMdUtlAjustePrazo($numIdAjustePrazo);
        $objControleDsmpDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objControleDsmpDTO->retTodos();
        $objControleDsmpDTO->retStrProtocoloProcedimentoFormatado();
        $objControleDsmpDTO->retNumIdContato();
        $objControleDsmpDTO->retStrEmail();
        $objControleDsmpDTO->setNumMaxRegistrosRetorno(1);
        $objControleDsmpDTO = $objMdUtlControleDsmpRN->consultar($objControleDsmpDTO);

        /*Efetuar ação / Enviar E-Mail / Gravar Histórico*/
        if (isset($_POST)) {
            try{
                $objInfraException = new InfraException();
                if($objControleDsmpDTO->getStrEmail() == ''){
                    $objInfraException->lancarValidacao($msg102);
                }
            }catch(Exception $e){
                PaginaSEI::getInstance()->processarExcecao($e);
            }
            $objMdUtlGestaoAjustPrazoRN->aprovarSolicitacao($objControleDsmpDTO);
            header('Location: '.$strUrlRecarregar);
            die;
        }

    case 'md_utl_gestao_ajust_prazo_reprovar':
        $idControleDsmp = array_key_exists('id_controle_desempenho', $_GET) ? $_GET['id_controle_desempenho'] : null;
        $numIdAjustePrazo = array_key_exists('id_ajuste_prazo', $_GET) ? $_GET['id_ajuste_prazo'] : null;

        $objControleDsmpDTO = new MdUtlControleDsmpDTO();
        $objControleDsmpDTO->setNumIdMdUtlControleDsmp($idControleDsmp);
        $objControleDsmpDTO->setNumIdMdUtlAjustePrazo($numIdAjustePrazo);
        $objControleDsmpDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objControleDsmpDTO->retTodos();
        $objControleDsmpDTO->retStrProtocoloProcedimentoFormatado();
        $objControleDsmpDTO->retNumIdContato();
        $objControleDsmpDTO->retStrEmail();
        $objControleDsmpDTO->setNumMaxRegistrosRetorno(1);
        $objControleDsmpDTO = $objMdUtlControleDsmpRN->consultar($objControleDsmpDTO);

        /*Efetuar ação / Enviar E-Mail / Gravar Histórico*/
        if (isset($_POST)) {
            try{
                $objInfraException = new InfraException();
                if($objControleDsmpDTO->getStrEmail() == ''){
                    $objInfraException->lancarValidacao($msg102);
                }
            }catch(Exception $e){
                PaginaSEI::getInstance()->processarExcecao($e);
            }
            $objMdUtlGestaoAjustPrazoRN->reprovarSolicitacao($objControleDsmpDTO);
            header('Location: '.$strUrlRecarregar);
            die;

        }

    case 'md_utl_gestao_contestacao_aprovar':

        $idControleDsmp = array_key_exists('id_controle_desempenho', $_GET) ? $_GET['id_controle_desempenho'] : null;
        $numIdAContest  = array_key_exists('id_contest', $_GET) ? $_GET['id_contest'] : null;

        $objControleDsmpDTO = new MdUtlControleDsmpDTO();
        $objControleDsmpDTO->setNumIdMdUtlControleDsmp($idControleDsmp);
        $objControleDsmpDTO->setNumIdMdUtlContestRevisao($numIdAContest);
        $objControleDsmpDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objControleDsmpDTO->retTodos();
        $objControleDsmpDTO->retStrProtocoloProcedimentoFormatado();
        $objControleDsmpDTO->retNumIdContato();
        $objControleDsmpDTO->retStrEmail();
        $objControleDsmpDTO->setNumMaxRegistrosRetorno(1);
        $objControleDsmpDTO = $objMdUtlControleDsmpRN->consultar($objControleDsmpDTO);

        $idProcedimento = $objControleDsmpDTO->getDblIdProcedimento();
        $staAtendimento = $objControleDsmpDTO->getStrStaAtendimentoDsmp();

        if($staAtendimento == MdUtlControleDsmpRN::$EM_CORRECAO_TRIAGEM){

            header('Location: ' .SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_revisao_triagem_cadastrar&acao_origem=md_utl_processo_listar&id_procedimento=' . $idProcedimento . '&id_contest=1&pg_padrao=1'));
        }

        if($staAtendimento == MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE){

            header('Location: ' .SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_revisao_analise_cadastrar&acao_origem=md_utl_processo_listar&id_procedimento='. $idProcedimento . '&id_contest=1&pg_padrao=1'));
        }


        break;

    case 'md_utl_gestao_contestacao_reprovar':

        $idControleDsmp = array_key_exists('id_controle_desempenho', $_GET) ? $_GET['id_controle_desempenho'] : null;
        $numIdAContest  = array_key_exists('id_contest', $_GET) ? $_GET['id_contest'] : null;

        $objControleDsmpDTO = new MdUtlControleDsmpDTO();
        $objControleDsmpDTO->setNumIdMdUtlControleDsmp($idControleDsmp);
        $objControleDsmpDTO->setNumIdMdUtlContestRevisao($numIdAContest);
        $objControleDsmpDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objControleDsmpDTO->retTodos();
        $objControleDsmpDTO->retStrProtocoloProcedimentoFormatado();
        $objControleDsmpDTO->retNumIdContato();
        $objControleDsmpDTO->retStrEmail();
        $objControleDsmpDTO->setNumMaxRegistrosRetorno(1);
        $objControleDsmpDTO = $objMdUtlControleDsmpRN->consultar($objControleDsmpDTO);

        /*Efetuar ação / Enviar E-Mail / Gravar Histórico*/
        if (isset($_POST)) {
            try{
                $objInfraException = new InfraException();
                if($objControleDsmpDTO->getStrEmail() == ''){
                    $objInfraException->lancarValidacao($msg102);
                }
            }catch(Exception $e){
                PaginaSEI::getInstance()->processarExcecao($e);
            }
            $objMdUtlGestaoAjustPrazoRN->reprovarSolicitacao($objControleDsmpDTO);
            header('Location: '.$strUrlRecarregar);
            die;

        }

    //endregion

    //region Erro
    default:
        throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    //endregion
}

$arrComandos[] = '<button type="button" accesskey="P" id="btnPesquisar" onclick="pesquisar()" class="infraButton">
                                        <span class="infraTeclaAtalho">P</span>esquisar</button>';

$arrComandos[] = '<button type="button" accesskey="c" id="btnFechar" onclick="fechar()" class="infraButton">
                                        Fe<span class="infraTeclaAtalho">c</span>har</button>';

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(':: ' . PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo . ' ::');

//Include de estilos CSS
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
PaginaSEI::getInstance()->fecharStyle();

PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
PaginaSEI::getInstance()->fecharJavaScript();

PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
<form id="frmGestaoLista" method="post" onsubmit="return OnSubmitForm();"
      action="<?= PaginaSEI::getInstance()->formatarXHTML(
          SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])
      ) ?>">

    <?php
        PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        PaginaSEI::getInstance()->abrirAreaDados('auto');
        $col_def = "col-sm-6 col-md-6 col-lg-3";
    ?>

    <div class="row mb-3">
        <div id="divTpCtrl" class="<?= $col_def ?> bloco">
            <label id="lblTpControle" for="selTpControle" accesskey="" class="infraLabelOpcional">Tipo de Controle:</label>
            <select id="selTpControle" name="selTpControle" class="infraSelect form-control"
                    onchange="pesquisar()" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                <?= $selTpControle ?>
            </select>
        </div>

        <div id="divProcesso" class="<?= $col_def ?> bloco">
            <label id="lblProcesso" for="txtProcessoMdGestao" class="infraLabelOpcional">Processo:</label>
            <input type="text" id="txtProcessoMdGestao" name="txtProcessoMdGestao" class="inputFila infraText form-control"
                value="<?= $txtProcessoCampo ?>"
                maxlength="100" tabindex="502"/>
        </div>

        <div id="divStatusProc" class="<?= $col_def ?> bloco">
            <label id="lblStatusProc" for="selStatusProcMdGestao" accesskey="" class="infraLabelOpcional">
                Status do Processo:
            </label>
            <select id="selStatusProcMdGestao" name="selStatusProcMdGestao" class="infraSelect form-control"
                    onchange="pesquisar();"
                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                <?= $arrStatusProcesso ?>
            </select>
        </div>

        <div id="divServidor" class="<?= $col_def ?> bloco">
            <label id="lblServidor" for="selServidorMdGestao" accesskey="" class="infraLabelOpcional">
                Servidor:
            </label>
            <select id="selServidorMdGestao" name="selServidorMdGestao" class="infraSelect form-control"
                    onchange="pesquisar();"
                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                <?= $arrSelServidor ?>
            </select>
        </div>
    </div>

    <?php PaginaSEI::getInstance()->fecharAreaDados(); ?>

    <?php if( $numRegistros > 0 ){ ?>
        <?php $cServico = 0; ?>
        <div class="row">
            <div class="divAjustePrazoGeral col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div style="background-color: #999999; font-weight: 500; border-bottom: 1px #bfb7b7 solid; padding-top: 5px; padding-bottom:5px;">
                    <span class="spnExpandirTodos">
                        <img id="imgExpandir" style="margin-bottom: -7px;"
                                onclick="expandirTodos('div<?= $cServico; ?>', this)"
                                src=" <?php echo PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/exibir.svg' ?>"/>
                        <label class="infraLabelObrigatorio">Ajuste de Prazo</label> - <?= $strCaption ?>
                    </span>
                    <div id="div<?= $cServico; ?>" style="display: none;" class="table-responsive">
                        <?php PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros);?>
                    </div>
                    <?php $cServico++; ?>
                </div>
            </div>
        </div>
    <?php }?>



    <?php if( $numRegistrosCont > 0 ){ ?>
        <?php $cServicoCont = 1; ?>
        <div class="row">
            <div class="divContestacaoGeral col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div style="background-color: #999999; font-weight: 500; border-bottom: 1px #bfb7b7 solid; padding-top: 5px; padding-bottom:5px;">
                    <span class="spnExpandirTodos">
                        <img id="imgExpandir" style="margin-bottom: -7px;"
                                onclick="expandirTodos('div<?= $cServicoCont; ?>', this)"
                                src=" <?php echo PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/exibir.svg' ?>"/>
                        <label class="infraLabelObrigatorio">Contestação</label> - <?= $strCaptionCont ?>
                    </span>
                    <div id="div<?= $cServicoCont; ?>" style="display: none;" class="table-responsive">
                        <?php PaginaSEI::getInstance()->montarAreaTabela($strResultadoContest, $numRegistrosCont);?>
                    </div>
                    <?php $cServicoCont++; ?>
                </div>
            </div>
        </div>
    <?php }?>

    <input type="hidden" id="hdnIsContatoVazio" name="hdnIsContatoVazio" value="<?= $isContatoVazioRev;?>"/>
    <input type="hidden" id="hdnUrlControleProcessos" name="hdnUrlControleProcessos"
           value="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_controlar&acaoorigem=' . $_GET['acao']); ?>"/>
    <input type="hidden" id="hdnIdTipoControleUtl" name="hdnIdTipoControleUtl"
           value="<?= is_null($idTipoControle) ? '0' : $idTipoControle; ?>"/>
    <input type="hidden" id="hdnValidaTpCtrl" name="hdnValidaTpCtrl" value="<?= $existeTpCtrlUnidade ? '1' : '0' ?>">
    <input type="hidden" id="hdnIdParametroCtrlUtl" name="hdnIdParametroCtrlUtl"
           value="<?= $isParametrizado ? '1' : '0'; ?>"/>
    <input type="hidden" id="hdnIsGestor" name="hdnIsGestor" value="<?= $existeTpCtrlGestorUnid ? '1' : '0';?>"/>
    <input type="hidden" id="hdnIdMdUtlContestRevisao" name="hdnIdMdUtlContestRevisao" value=""/>
    <input type="hidden" id="hdnIsConcluirProcesso" name="hdnIsConcluirProcesso" value="<?= $isProcessoAutorizadoConcluir ?>"/>
    <input type="hidden" id="hdnIdProcedimento" name="hdnIdProcedimento" value="<?= $idProcedimentoAprovacao ?>"/>
    <input type="hidden" id="hdnUserIsAvaliador" value="<?= $userIsAvaliador > 0 ? 'S' : 'N'?>">
</form>

<?php require_once 'md_utl_geral_js.php'; ?>

<script type="text/javascript">
    var msg24 = '<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_24); ?>';
    var msg25 = '<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_25); ?>';
    var msg104 = '<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_104)?>';
    var msg102  = '<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_102) ?>';
    var msg123  = '<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_123) ?>';

    function inicializar() {
        var urlCtrlProcessos = document.getElementById('hdnUrlControleProcessos').value;
        var idParam  = document.getElementById('hdnIdParametroCtrlUtl').value;
        var tpCtrl   = document.getElementById('hdnValidaTpCtrl').value;
        var isGestor = document.getElementById('hdnIsGestor').value;
        var isAvaliador = document.getElementById('hdnUserIsAvaliador').value;
        var isPesquisar = '<?=$isPesquisar?>';
        var numRegistros = '<?=$numRegistros?>';
        var numRegistrosCont = '<?=$numRegistrosCont?>';
        var isProcessoConcl  = '<?= $isProcessoConcluido ?>';
        var msgConclusao = '<?= $msg107 ?>';
        var msgEmail = '<?= $msg102 ?>';
        var isContatoVazio = '<?= $isContatoVazioRev ?>';
        var isValorGet = false;

        if(isPesquisar == 1 && numRegistros > 0){
            expandirTodos('div0', document.getElementById('imgExpandir'), false);
        }
        if(isPesquisar == 1 && numRegistrosCont > 0){
            expandirTodos('div1', document.getElementById('imgExpandirCont'), false);
        }

        if ( isAvaliador == 'N' && isGestor == 0 ) {
            alert(msg123);
            window.location.href = urlCtrlProcessos;
            return false;
        }

        if (tpCtrl == 0) {
            alert(msg24);
            window.location.href = urlCtrlProcessos;
            return false;
        }

        if (idParam == 0) {
            alert(msg25);
            window.location.href = urlCtrlProcessos;
            return false;
        }

        /*
        if (isGestor == 0){
            alert(msg104);
            window.location.href = urlCtrlProcessos;
            return false;
        }
        */

        if(isContatoVazio == 1){
            alert(msg102);
            document.getElementById('hdnIsContatoVazio').value = 0;

            if(isProcessoConcl == 0){
                pesquisar();
            }
        }

        infraEfeitoTabelas(true);

        if(isProcessoConcl == 1){
            if(confirm(msgConclusao)){
                document.getElementById('hdnIsConcluirProcesso').value = 1;
                pesquisar();
            }else{
                pesquisar();
            }
        }
    }

    function fechar() {
        location.href = "<?= $strUrlFechar ?>";
    }

    function confirmarAcaoContest(situacao, numeroProcesso, link) {
        var msg103padrao  = '<?=MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_108)?>';

        if(situacao == '<?=MdUtlAjustePrazoRN::$APROVADA?>'){
            var msg = setMensagemPersonalizada(msg103padrao, ['aprovação', numeroProcesso]);
                document.getElementById('frmGestaoLista').action = link;
                document.getElementById('frmGestaoLista').submit();
                expandirTodos();
        }
        if(situacao == '<?=MdUtlAjustePrazoRN::$REPROVADA?>'){
            msg = setMensagemPersonalizada(msg103padrao, ['reprovação', numeroProcesso]);
            validar = confirm(msg);
            if(validar == true){
                document.getElementById('frmGestaoLista').action = link;
                document.getElementById('frmGestaoLista').submit();
                expandirTodos();
            }
        }
    }

    function expandirTodos(idDiv, img) {
        var divFilha = document.getElementById(idDiv);

        if (divFilha.style.display == "none") {
            document.getElementById(idDiv).removeAttribute('style');
            img.setAttribute('src', '/infra_css/svg/ocultar.svg');//menos
        } else {
            document.getElementById(idDiv).setAttribute("style", "display: none");
            img.setAttribute('src', '/infra_css/svg/exibir.svg');//mais
        }
    }

    function pesquisar() {
        document.getElementById('frmGestaoLista').action = '<?= $strUrlPesquisar ?>';
        document.getElementById('frmGestaoLista').submit();
    }

    function confirmarAcao(situacao, numeroProcesso, link){
        var msg103padrao  = '<?=MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_103)?>';

        if(situacao == '<?=MdUtlAjustePrazoRN::$APROVADA?>'){
            var msg = setMensagemPersonalizada(msg103padrao, ['aprovação', numeroProcesso]);
            var validar = confirm(msg);
            if(validar == true){
                document.getElementById('frmGestaoLista').action = link;
                document.getElementById('frmGestaoLista').submit();
                expandirTodos();
            }
        }
        if(situacao == '<?=MdUtlAjustePrazoRN::$REPROVADA?>'){
            msg = setMensagemPersonalizada(msg103padrao, ['reprovação', numeroProcesso]);
            validar = confirm(msg);
            if(validar == true){
                document.getElementById('frmGestaoLista').action = link;
                document.getElementById('frmGestaoLista').submit();
                expandirTodos();
            }
        }
    }
</script>

<?php
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
