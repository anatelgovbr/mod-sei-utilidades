<?php

/**
 * @author Jaqueline Mendes
 * @since  11/09/2018
 */

require_once dirname(__FILE__) . '/../../SEI.php';

session_start();
SessaoSEI::getInstance()->validarLink();
PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);

$isProcessoConcluido = array_key_exists('is_processo_concluido', $_GET) ? $_GET['is_processo_concluido'] : 0;
$isProcessoAutorizadoConcluir = array_key_exists('hdnIsConcluirProcesso', $_POST) ? $_POST['hdnIsConcluirProcesso'] : 0;

$strParametros = '';
if(isset($_GET['arvore'])){
    PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
    $strParametros .= '&arvore='.$_GET['arvore'];
}


$idProcedimento  = array_key_exists('id_procedimento', $_GET) ? $_GET['id_procedimento'] : $_POST['hdnIdProcedimento'];
$idProcedimento = trim($idProcedimento);
$urlInicial = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_processo_listar&id_procedimento=' . $idProcedimento);
$strLinkAjaxListarHistoricoTipoControle = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_utl_hist_controle_dsmp_tp_controle&acao_origem=' . $_GET['acao']);
$strLinkAjaxVerificarSePodeDistribuirParaMim = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_utl_verificar_pode_distrib_para_mim&id_procedimento=' . $idProcedimento);



if ($isProcessoAutorizadoConcluir == 1) {
    $_POST['hdnIsConcluirProcesso'] = 0;
    $isProcessoAutorizadoConcluir = 0;

    $objEntradaConcluirProcessoAPI = new EntradaConcluirProcessoAPI();
    $objEntradaConcluirProcessoAPI->setIdProcedimento($idProcedimento);

    $objSEIRN = new SeiRN();
    $objSEIRN->concluirProcesso($objEntradaConcluirProcessoAPI);

}

if (!is_null($idProcedimento) && $idProcedimento != ''){
    $strParametros .= '&id_procedimento='.$idProcedimento;
}


//Acao única
$acaoPrincipal = 'md_utl_processo_listar';

//URL Base
$strUrlPadrao = 'controlador.php?acao=' . $acaoPrincipal;

// Vars
$isParametrizado = true;
$nomeStatus      = '';
$strTitulo       = 'Detalhamento do Processo ';
$arrCtrlVisualizacao = array();
$isUsuarioDuplicado = false;
//Rns
$objRegrasGerais           = new MdUtlRegrasGeraisRN();
$objMdUtlAdmUtlTpCtrlRN    = new MdUtlAdmTpCtrlDesempRN();
$objMdUtlAdmTpCtrlDTO      = new MdUtlAdmTpCtrlDesempDTO();
$objTriagemRN              = new MdUtlTriagemRN();
$objMdUtlControleDsmpRN    = new MdUtlControleDsmpRN();
$objAnaliseRN              = new MdUtlAnaliseRN();
$objMdUtlAdmTpCtrlUndRN    = new MdUtlAdmRelTpCtrlDesempUndRN();
$objMdUtlAdmTpCtrlUsuRN    = new MdUtlAdmRelTpCtrlDesempUsuRN();
$objMdUtlAdmPrmGrRN        = new MdUtlAdmPrmGrRN();
$objProcedimentoDTO        = $objRegrasGerais->getObjProcedimentoPorId($idProcedimento);

$objDTO            = new MdUtlProcedimentoDTO();
$objProcedimentoRN = new ProcedimentoRN(); 

$idTipoControle = null;

$objDTO->setDblIdProcedimento( $idProcedimento );
$objDTO->setNumIdUnidade( SessaoSEI::getInstance()->getNumIdUnidadeAtual() );
$objDTO->retNumIdMdUtlAdmTpCtrlDesemp();

$res = $objProcedimentoRN->contarRN0279($objDTO);

if ( !is_null($res) ) {
    $res = $objProcedimentoRN->listarRN0278($objDTO);    
    if( !is_null($res[0]->getNumIdMdUtlAdmTpCtrlDesemp() ) ){
        $idTipoControle = $res[0]->getNumIdMdUtlAdmTpCtrlDesemp();
    }
}

#if( is_null( $idTipoControle) ) $idTipoControle = $objMdUtlAdmTpCtrlUndRN->getTipoControleUnidadeLogada();

//Preenche Vars Principais
if(!is_null($idTipoControle)) {
    $isParametrizado = $objMdUtlAdmUtlTpCtrlRN->verificaTipoControlePossuiParametrizacao($idTipoControle);
}

$objMdUtlAdmTpCtrlDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTipoControle);
$objMdUtlAdmTpCtrlDTO->retStrStaFrequencia();
$objMdUtlAdmTpCtrlDTO->setNumMaxRegistrosRetorno(1);
$objDTOTipoControle = $objMdUtlAdmUtlTpCtrlRN->consultar($objMdUtlAdmTpCtrlDTO);

$staFrequencia = is_null($objDTOTipoControle) ? '0' : $objDTOTipoControle->getStrStaFrequencia();

$options = array( 'tp_procedimento' => true , 'idTpProced' => $objProcedimentoDTO->getNumIdTipoProcedimento() );
$isPermiteAcoes = is_null($idTipoControle) ? true : $objMdUtlControleDsmpRN->validaVisualizacaoUsuarioLogado($idTipoControle , null , $options );

if($isPermiteAcoes){
    $isPermiteAcoes = $objRegrasGerais->validarSituacaoProcesso($idProcedimento);
    if($isPermiteAcoes){
        $isConcluido =  $objRegrasGerais->verificaConclusaoProcesso(array($idProcedimento));
        $isPermiteAcoes = $isConcluido ? false : true;
    }
}

$arrSituacao = MdUtlControleDsmpINT::retornaArrSituacoesControleDsmpCompleto();

//Status
$objControleDsmpDTO    = $objMdUtlControleDsmpRN->getObjControleDsmpAtivo($idProcedimento);
$idStatus             = MdUtlControleDsmpRN::$AGUARDANDO_FILA;
$idFila               = null;
$dthDataHoraAtual     = '';
$strNomeUsuarioAtual  = '';
$strSiglaUsuarioAtual = '';
$strDetalheAtual      = '';
$strStatusAtual       = 0;
$strTipoAcaoAtual     = '';
$idControleDsmp       = 0;


if(!is_null($objControleDsmpDTO)){
    $idStatus                 = trim($objControleDsmpDTO->getStrStaAtendimentoDsmp());
    $idFila                   = $objControleDsmpDTO->getNumIdMdUtlAdmFila();
    $dthDataHoraAtual         = $objControleDsmpDTO->getDthAtual();
    $strNomeUsuarioAtual      = $objControleDsmpDTO->getStrNomeUsuarioAtual();
    $strSiglaUsuarioAtual     = $objControleDsmpDTO->getStrSiglaUsuarioAtual();
    $strDetalheAtual          = $objControleDsmpDTO->getStrDetalhe();
    $strNomeTipoControleAtual = $objControleDsmpDTO->getStrNomeTpControle();
    $strNomeFila              = $objControleDsmpDTO->getStrNomeFila();
    $strStatusAtual           = trim($objControleDsmpDTO->getStrStaAtendimentoDsmp());
    $strTipoAcaoAtual         = $objControleDsmpDTO->getStrTipoAcao();
    $idControleDsmp           = $objControleDsmpDTO->getNumIdMdUtlControleDsmp();

    $isUsuarioDuplicado = $objControleDsmpDTO->getNumIdUsuarioDistribuicao() == SessaoSEI::getInstance()->getNumIdUsuario();
}

$nomeStatus       = $arrSituacao[$idStatus];

$isPossuiAnalise       = $objMdUtlControleDsmpRN->verificaTriagemPossuiAnalise($objControleDsmpDTO);
$strNumeroProcedimento = $objProcedimentoDTO->getStrProtocoloProcedimentoFormatado();
$msg107                = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_107, array($strNumeroProcedimento,  SessaoSEI::getInstance()->getStrSiglaUnidadeAtual()));
$msg92                 = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_92, array($strNumeroProcedimento));

//Controle de Urls
$idUsuarioDistrb     = !is_null($objControleDsmpDTO) ? $objControleDsmpDTO->getNumIdUsuarioDistribuicao() : null;
$isStatusAjustePrazo = !is_null($objControleDsmpDTO) && ($idStatus == MdUtlControleDsmpRN::$SUSPENSO || $idStatus == MdUtlControleDsmpRN::$INTERROMPIDO);
$idRevisaoAjustePrz  = $isStatusAjustePrazo &&  !is_null($objControleDsmpDTO) ?  $objControleDsmpDTO->getNumIdMdUtlRevisao() : null;

$arrCtrlUrls      = MdUtlControleDsmpINT::retornaUrlsAcessoDsmp($idStatus, $isPossuiAnalise, $idProcedimento, $idFila, $idUsuarioDistrb);

//Urls
$strLinkAssociarFila   = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_controle_dsmp_associar&acao_origem=' . $_GET['acao'] . '&id_procedimento=' . $idProcedimento . '&is_detalhamento=1');
$strLinkIniciarTriagem = $arrCtrlUrls['TRIAGEM'];
$strLinkIniciarAnalise = $arrCtrlUrls['ANALISE'];
$strLinkIniciarRevisao = $arrCtrlUrls['REVISAO'];

$strLinkIniciarDistrb  = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_distrib_usuario_cadastrar&acao_retorno=md_utl_processo_listar&acao_origem=md_utl_controle_dsmp_listar&id_procedimento=' . $idProcedimento.'&id_controle_dsmp='.$idControleDsmp.'&status='.$strStatusAtual.'&id_fila='.$idFila);
$strLinkAtribuir  = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_atribuicao_automatica&acao_retorno=md_utl_processo_listar&acao_origem=md_utl_controle_dsmp_listar&id_procedimento=' . $idProcedimento.'&id_controle_dsmp='.$idControleDsmp.'&status='.$strStatusAtual.'&id_fila='.$idFila.'&id_tp_ctrl='.$idTipoControle);
$strUrlFechar          = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_retorno=procedimento_controlar&acao_origem=md_utl_controle_dsmp_listar&id_procedimento=' . $idProcedimento);
$strLinkConcluirProcesso =  $isProcessoConcluido == 1  ? SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_processo_listar&id_procedimento=' . $idProcedimento.'&is_concluir_processo'.$isProcessoConcluido) : '';

//Controle de Visualização
$idTipoProcedimento = $objProcedimentoDTO->getNumIdTipoProcedimento();

$isTipoProcessoParametrizado = is_null($idTipoControle) ? true : $objMdUtlAdmPrmGrRN->verificaTipoProcessoParametrizado(array($idTipoProcedimento, $idTipoControle));
$arrCtrlVisualizacao = MdUtlControleDsmpINT::retornaArrVisualizacaoBotao($idStatus, $isPossuiAnalise, $isTipoProcessoParametrizado, $idFila, $idRevisaoAjustePrz);

$idsTpCtrlUsuarioGestor = $objMdUtlAdmTpCtrlUsuRN->usuarioLogadoIsGestorTpControle();

$isGestorTpControle = true;
if(!is_null($idTipoControle)){
    if ($idsTpCtrlUsuarioGestor) {
        $isGestorTpControle = in_array($idTipoControle, $idsTpCtrlUsuarioGestor);
    } else {
        $isGestorTpControle = false;
    }
}

if (count($arrCtrlVisualizacao) > 0 && $isPermiteAcoes) {
    if($arrCtrlVisualizacao['ASSOCIACAO'] || $isGestorTpControle) {
        $arrComandos[] = '<button type="button" accesskey="a" id="btnAssoFila" onclick="associarFila()" class="infraButton">
                                    <span class="infraTeclaAtalho">A</span>ssociar à Fila</button>';
    }

    if($arrCtrlVisualizacao['ATRIBUICAO']) {
        if($isUsuarioDuplicado) {
            $arrComandos[] = '<button type="button" accesskey="m" id="btnAtribuicao" onclick="exibirExcessaoDuplicidade()" class="infraButton">'
                                . MdUtlControleDsmpINT::getLabelBtn(2, $idStatus) .
                            '</button>';
        }else {
            $arrComandos[] = '<button type="button" accesskey="m" id="btnAtribuicao" onclick="atribuicaoAutomatica()" class="infraButton">'
                                . MdUtlControleDsmpINT::getLabelBtn(2, $idStatus) .
                            '</button>';
        }
    }

    if($arrCtrlVisualizacao['DISTRIBUICAO']) {        
        $arrComandos[] = '<button type="button" accesskey="i" id="btnDistribuicao" onclick="iniciarDistribuicao()" class="infraButton">'
                            . MdUtlControleDsmpINT::getLabelBtn(1, $idStatus) .
                        '</button>';
    }


    if($arrCtrlVisualizacao['TRIAGEM']) {
        $arrComandos[] = '<button type="button" accesskey="t" id="btnIniciarTriagem" onclick="iniciarTriagem()" class="infraButton">
                                    <span class="infraTeclaAtalho">T</span>riagem</button>';
    }

    if($arrCtrlVisualizacao['ANALISE']) {
        $arrComandos[] = '<button type="button" accesskey="n" id="btnAnalise" onclick="iniciarAnalise()" class="infraButton">
                                    A<span class="infraTeclaAtalho">n</span>álise</button>';
    }

    if($arrCtrlVisualizacao['REVISAO']) {
        $arrComandos[] = '<button type="button" accesskey="v" id="btnRevisao" onclick="iniciarRevisao()" class="infraButton">
                                    A<span class="infraTeclaAtalho">v</span>aliação</button>';
    }

}

switch ($_GET['acao']) {

    //region Listar
    case $acaoPrincipal:
        $arrObjTpControle = $objMdUtlAdmTpCtrlUndRN->getArrayTipoControleUnidadeLogada(array('origem'=>'detalhamento'));         
        $arrIdsTpCtrlAux  = is_null($arrObjTpControle) ? array() : MdUtlAdmFilaINT::montarSelectTpControle($arrObjTpControle,'NumIdMdUtlAdmTpCtrlDesemp', 'StrNomeTipoControle',null);        
        break;
    //endregion

    case 'md_utl_atribuicao_automatica':
        $objMdUtlControleDsmpRN->atribuirDistribuicaoUsuarioLogado();

            header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_procedimento='.$idProcedimento));
        die;
    break;

    //region Erro
    default:
        throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    //endregion
}

$strResultado = MdUtlHistControleDsmpINT::retornarHistoricoPorTipoDeControle($idProcedimento, null, $strStatusAtual, $strTitulo);
$numRegistros = MdUtlHistControleDsmpINT::retornarQuantidadeRegistroHistorico($idProcedimento);

$arrComandos[] = '<button type="button" accesskey="C" name="btnFechar" value="Fechar" onclick="window.top.location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem=' . $_GET['acao'] . '&acao_destino=' . $_GET['acao'] . $strParametros . PaginaSEI::montarAncora($arrStrIdProtocolo)) . '\';" 
class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(':: ' . PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo . ' ::');
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
?>
    #tblSituacaoAtual{
        font-size: 0.97em;
    }

    #tblSituacaoAtual .tdCabecalho{
        width: 100px;
    }

    #tblSituacaoAtual .tdEscopo{
    width: 75%;
    }

    #fldSituacaoAtual{
        width: 500px;
    }
<?php
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript(); ?>
<?php
if(0) {
    ?>
    <script type="javascript">
<?php } ?>

var msg25 = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_25)?>';
var permiteDistribuirParaMim = false;

function inicializar() {

    this.distribuirParaMimProcesso();
    var idParam = document.getElementById('hdnIdParametroCtrlUtl').value;
    var isProcessoConcl  = '<?php echo $isProcessoConcluido ?>';
    var msgConclusao = '<?php echo $msg107 ?>';

    if (idParam == 0) {
        alert(msg25);
    }

   if(isProcessoConcl == 1){
       if(confirm(msgConclusao)) {
           document.getElementById('hdnIsConcluirProcesso').value = 1;
           document.getElementById("frmUtlProcessoLista").submit();
       }else{
          window.location.href = '<?=$urlInicial?>';
       }
    }

    if ('<?= $_GET['acao'] ?>' == 'md_utl_processo_listar') {
        infraReceberSelecao();
    } else {
        infraEfeitoTabelas();
    }
}


function associarFila(){
      infraAbrirJanela('<?=$strLinkAssociarFila?>', 'janelaAssinatura', 1000, 450, 'location=0,status=1,resizable=1,scrollbars=1');
}

function iniciarTriagem(){
    window.location.href = '<?= $strLinkIniciarTriagem ?>';
}

function iniciarAnalise(){
    window.location.href = '<?= $strLinkIniciarAnalise ?>';
}

function iniciarRevisao(){
    window.location.href = '<?= $strLinkIniciarRevisao ?>';
}

function iniciarDistribuicao(){

    var staFrequencia = '<?=$staFrequencia?>';
    if(staFrequencia == 0){
        alert('A Frequência de Distribuição não está parametrizada no Tipo de Controle desta Unidade. Converse com o Gestor da sua área!');
        return false;
    }else{
        window.location.href = '<?= $strLinkIniciarDistrb ?>';
    }
}

function atribuicaoAutomatica() {
    if(permiteDistribuirParaMim){
        if(confirm("Confirma a Distribuição do Processo em sua carga?")){
            window.location.href = '<?= $strLinkAtribuir ?>';
        }
    } else {
        alert('Não é permitido Distribuir a Avaliação cuja tarefa no fluxo a ser avaliada tenha sido realizada pelo mesmo Membro Participante.')
    }
}

function exibirExcessaoDuplicidade(){
    var msg92 = '<?php echo $msg92 ?>'
    alert(msg92);
}

function fechar() {
    window.location.href = '<?= $strLinkFechar ?>';
}

function atualizarHistorico(){

    var paramsAjax = {
        idTipoControleSelecionado : document.getElementById('filtrarTipoControle').value,
        idProcedimento : '<?= $idProcedimento?>',
        strStatusAtual : '<?= $strStatusAtual?>',
        strTitulo : '<?= $strTitulo?>',
    };

    $.ajax({
        url: '<?=$strLinkAjaxListarHistoricoTipoControle?>',
        type: 'POST',
        dataType: 'XML',
        data: paramsAjax,
        success: function (response) {
            $('#tbHistDetalhe').replaceWith($(response).find("NovaTabela").html());
        },
        error: function (e) {
            console.error('Erro ao processar o XML do SEI: ' + e.responseText);
        }
    });
}

function distribuirParaMimProcesso(){

    var paramsAjax = {
        idProcedimento : '<?= $idProcedimento?>',
    };

    $.ajax({
        url: '<?=$strLinkAjaxVerificarSePodeDistribuirParaMim?>',
        type: 'POST',
        dataType: 'XML',
        data: paramsAjax,
        success: function (response) {
            if ($(response).find("PermiteDistribuirParaMim").html() == 1) {
                permiteDistribuirParaMim = true;
            }
        },
        error: function (e) {
            console.error('Erro ao processar o XML do SEI: ' + e.responseText);
        }
    });
}

<?php
if(0) {
?>

</script>
    <?php } ?>

<?php PaginaSEI::getInstance()->fecharJavaScript(); ?>


<?php
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
    <form id="frmUtlProcessoLista" method="post"
          action="<?= PaginaSEI::getInstance()->formatarXHTML(
              SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])
          ) ?>">

        <?php
         PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
         PaginaSEI::getInstance()->abrirAreaDados('auto');
        ?>
        <div class="bloco" id="divCabecalho">
            <div id="divProcesso" style="font-size: 1.1em;">
                <label class="infraLabelObrigatorio">
                    Tipo de Controle:
                </label>
                <br>
                <select id="filtrarTipoControle" style="width: 184px;" onchange="atualizarHistorico()">
                    <?php echo $arrIdsTpCtrlAux ?>
                </select>
            </div>
            <br>
            <!--
            <div id="divProcesso" style="font-size: 1.1em;">                
                <label id="lblProcesso" for="txtProcesso" class="infraLabelObrigatorio">
                    Processo:
                </label>
                <label><?=$strNumeroProcedimento?></label>
                
                </br>
            </div>
            -->
            <?php if(is_null($objControleDsmpDTO)) {
                if($isTipoProcessoParametrizado) {
                    ?>
                    <div style="font-size: 1.1em;">
                        <label id="lblStatus" for="txtStatus" class="infraLabelObrigatorio">
                            Situação Atual:
                        </label>
                        <label>
                            <?php echo MdUtlControleDsmpRN::$STR_AGUARDANDO_FILA; ?>
                        </label>
                    </div>
                    <?
                }
            }else{ ?>
                </br>
                <fieldset id="fldSituacaoAtual" class="infraFieldset">
                    <legend class="infraLegend">Situação Atual</legend>

                    <!-- Processo -->
                    <table id="tblSituacaoAtual">

                        <tr>
                            <td class="tdCabecalho">
                                <label id="lblProcesso" for="lblProcessoText" class="infraLabelObrigatorio">
                                    Data/Hora:
                                </label>
                            </td>
                            <td class="tdEscopo">
                                <label id="lblProcessoText"><?= MdUtlHistControleDsmpINT::formatarDataHora($dthDataHoraAtual); ?></label>
                            </td>
                        </tr>

                    <!-- Usuário Ação -->
                        <tr>
                            <td class="tdCabecalho">
                                <label id="lblUsuarioAtual" for="lblUsuarioAtualText" class="infraLabelObrigatorio">
                                    Usuário Ação:
                                </label>
                            </td>
                            <td class="tdEscopo">
                                <label id="lblUsuarioAtualText"> <a class="ancoraSigla" href="javascript:void(0);" alt="<?php echo PaginaSEI::tratarHTML($strNomeUsuarioAtual); ?>" title="<?php echo PaginaSEI::tratarHTML($strNomeUsuarioAtual); ?>"><?php  echo PaginaSEI::tratarHTML($strSiglaUsuarioAtual) ?> </a></label>
                                <br/>
                            </td>
                        </tr>

                    <!-- Tipo de Controle -->
                        <tr>
                            <td class="tdCabecalho">
                                <label id="lblTipoControle" for="lblTipoControleText" class="infraLabelObrigatorio">
                                    Tipo de Controle:
                                </label></td>

                            <td class="tdEscopo">
                                <label id="lblTipoAcaoText"><?= $strNomeTipoControleAtual ?></label>
                            </td>
                        </tr>

                    <!-- Tipo de Controle -->
                        <tr>
                            <td class="tdCabecalho">
                                <label id="lblFila" for="lblFilaText" class="infraLabelObrigatorio">
                                    Fila:
                                </label></td>

                            <td class="tdEscopo">
                                <label id="lblNomeFilaText"><?= $strNomeFila ?></label>
                            </td>
                        </tr>

                    <!-- Tipo de Ação -->
                        <tr>
                            <td class="tdCabecalho">
                                <label id="lblTipoAcao" for="lblTipoAcaoText" class="infraLabelObrigatorio">
                                    Tipo de Ação:
                                </label></td>

                            <td class="tdEscopo">
                                <label id="lblTipoAcaoText"><?= $strTipoAcaoAtual ?></label>
                            </td>
                        </tr>

                    <!-- Detalhe -->
                        <tr>
                            <td class="tdCabecalho">
                                <label id="lblDetalhe" for="lblDetalheText" class="infraLabelObrigatorio">
                                    Detalhe:
                                </label>
                            </td>
                            <td class="tdEscopo">
                                <label id="lblDetalheText"><?= $strDetalheAtual ?></label>
                            </td>
                        </tr>

                    <!-- Status Atual -->
                        <tr>
                            <td class="tdCabecalho">
                                <label id="lblDetalhe" for="lblDetalheText" class="infraLabelObrigatorio">
                                    Situação Atual:
                                </label>
                            </td>
                            <td class="tdEscopo">
                                <label id="lblDetalheText"><?= $arrSituacao[$strStatusAtual] ?></label>
                            </td>
                        </tr>

                    </table>

                </fieldset>
           <? } ?>



        </div>


        <?
        PaginaSEI::getInstance()->fecharAreaDados();
        PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros);
        PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
        ?>

        <input type="hidden" id="hdnIdTipoControleUtl" name="hdnIdTipoControleUtl" value="<?php echo is_null($idTipoControle) ? '0' : $idTipoControle; ?>"/>
        <input type="hidden" id="hdnIdParametroCtrlUtl" name="hdnIdParametroCtrlUtl" value="<?php echo $isParametrizado ? '1' : '0'; ?>"/>
        <input type="hidden" id="hdnProtocoloFormatado" name="hdnProtocoloFormatado" value="<?php echo !is_null($objProcedimentoDTO) ? $objProcedimentoDTO->getStrProtocoloProcedimentoFormatado() : '' ?>"/>
        <input type="hidden" id="hdnNomeFilaAtual" name="hdnNomeFilaAtual" value="<?php echo !is_null($objControleDsmpDTO) ? $objControleDsmpDTO->getStrNomeFila() : '' ?>"/>
        <input type="hidden" id="hdnNomeTpCtrlAtual" name="hdnNomeTpCtrlAtual" value="<?php echo !is_null($objControleDsmpDTO) ? $objControleDsmpDTO->getStrNomeTpControle() : '' ?>"/>
        <input type="hidden" id="hdnIdProcedimento" name="hdnIdProcedimento" value="<?php echo $idProcedimento?>"/>
        <input type="hidden" id="hdnIdStatusAtual" name="hdnIdStatusAtual" value="<?php echo !is_null($objControleDsmpDTO) ? $objControleDsmpDTO->getStrStaAtendimentoDsmp() : 0 ?>"/>
        <input type="hidden" id="hdnIsConcluirProcesso" name="hdnIsConcluirProcesso" value="<?php echo $isProcessoAutorizadoConcluir ?>"/>


    </form>

<?php
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();

