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
        
    $strTitulo       = 'Análise ';

//Tipo de Controle e Procedimento
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

    $isAnalise                 = $_GET['acao'] == 'md_utl_revisao_analise_cadastrar' || $_GET['acao'] == 'md_utl_revisao_analise_consultar';

    $isEdicao                  = $_GET['acao'] == 'md_utl_revisao_analise_cadastrar' || $_GET['acao'] == 'md_utl_revisao_triagem_cadastrar';
    $idFilaAtiva               = $_GET['id_fila'];
    $selectFila                = '';
    
    
    $objControleDsmpDTO        = $objMdUtlControleDsmpRN->getObjControleDsmpAtivoRevisao(array($idProcedimento, $isAnalise));
    $idContatoAtual            = $objControleDsmpDTO->getNumIdContato();
    $strNumeroProcesso         = $objControleDsmpDTO->getStrProtocoloProcedimentoFormatado();
    $idTipoControle            = $objControleDsmpDTO->getNumIdMdUtlAdmTpCtrlDesemp();
    $idRevisao                 = $objControleDsmpDTO->getNumIdMdUtlRevisao();
    $idMdUtlAnalise            = $objControleDsmpDTO->getNumIdMdUtlAnalise();
    $idMdUtlControleDsmp       = $objControleDsmpDTO->getNumIdMdUtlControleDsmp();
    $valorTempoExecucao           = $objControleDsmpDTO->getNumTempoExecucao();
    $idStatus                  = $objControleDsmpDTO->getStrStaAtendimentoDsmp();
    $strNomeFila               = $objControleDsmpDTO->getStrNomeFila();
    $strNomeTpControle         = $objControleDsmpDTO->getStrNomeTpControle();

    $isUsuarioPertenceFila     = false;
    $isConsultar               = false;
    $isUsuarioDistribuido      = $objControleDsmpDTO->getNumIdUsuarioDistribuicao() == SessaoSEI::getInstance()->getNumIdUnidadeAtual();
    $strAcao = $_GET['acao'];

    if (strrpos($strAcao, 'consultar') && strrpos($strAcao, 'analise')) {

        $statusRevisaoConsultar = array(MdUtlControleDsmpRN::$EM_REVISAO);

        if (!$isUsuarioDistribuido && in_array($idStatus, $statusRevisaoConsultar)) {

            $idMdUtlAnaliseAntigo = $objMdUtlHistControleDsmpRN->getIdAnaliseAnterior(array($idMdUtlAnalise, $idProcedimento));

            if (!is_null($idMdUtlAnaliseAntigo)) {
                $idMdUtlAnalise = $idMdUtlAnaliseAntigo;
            }
        }
    }

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

    $strTitulo='Avaliação';

    $tpAcaoAval = null;

    switch ($_GET['acao']) {

        case 'md_utl_revisao_analise_cadastrar':
            $arrComandos[] = '<button type="button" accesskey="s" id="btnSalvar" value="salvar" onclick="salvar();" class="infraButton botaoSalvar">
                                                <span class="infraTeclaAtalho">S</span>alvar</button>';
            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="window.history.back();" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

            $tpAcaoAval = MdUtlControleDsmpRN::$EM_ANALISE;

            require_once 'md_utl_revisao_analise_cadastro_acoes.php';

            if(isset($_POST) && count($_POST) > 0){
                $objMdUtlRelRevisTrgAnlsRN = new MdUtlRelRevisTrgAnlsRN();

                if($idContest == 0) {
                    $isProcessoConcluido = $objMdUtlRelRevisTrgAnlsRN->cadastrarRevisaoTriagemAnalise($objControleDsmpDTO);
                    if ($isPgPadrao == 0) {
                        header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_processo_listar&id_procedimento=' . $idProcedimento . '&is_processo_concluido=' . $isProcessoConcluido));
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
            $arrComandos[] = '<button type="button" accesskey="s" id="btnSalvar" value="salvar" onclick="salvar();" class="infraButton botaoSalvar">
                                                <span class="infraTeclaAtalho">S</span>alvar</button>';
            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="window.history.back();" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

            $tpAcaoAval = MdUtlControleDsmpRN::$EM_TRIAGEM;

            require_once 'md_utl_revisao_triagem_cadastro_acoes.php';

            if(isset($_POST) && count($_POST) >0){
                $objMdUtlRelRevisTrgAnlsRN = new MdUtlRelRevisTrgAnlsRN();
                if ($idContest == 0)
                {
                    $isProcessoConcluido = $objMdUtlRelRevisTrgAnlsRN->cadastrarRevisaoTriagemAnalise($objControleDsmpDTO);
                    if ($isPgPadrao == 0) {
                        header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_processo_listar&id_procedimento=' . $idProcedimento . '&is_processo_concluido=' . $isProcessoConcluido));
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

            if($_GET['acao_origem']== 'md_utl_analise_alterar' || $_GET['acao_origem']== 'md_utl_triagem_alterar'){
                $arrComandos[] = '<button type="button" accesskey="C" name="btnFechar" id="btnFechar" value="Fechar" onclick="window.close();" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
            }else {
                $arrComandos[] = '<button type="button" accesskey="C" name="btnFechar" id="btnFechar" value="Fechar" onclick="window.history.back();" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
            }

            $tpAcaoAval = MdUtlControleDsmpRN::$EM_ANALISE;

            require_once 'md_utl_revisao_analise_cadastro_acoes.php';

            break;

        case 'md_utl_revisao_triagem_consultar':
            $isConsultar = true;

            if($_GET['acao_origem']== 'md_utl_triagem_alterar'){
                $arrComandos[] = '<button type="button" accesskey="C" name="btnFechar" id="btnFechar" value="Fechar" onclick="window.close();" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
            }else {
                $arrComandos[] = '<button type="button" accesskey="C" name="btnFechar" id="btnFechar" value="Fechar" onclick="window.history.back();" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
            }

            $tpAcaoAval = MdUtlControleDsmpRN::$EM_TRIAGEM;

            require_once 'md_utl_revisao_triagem_cadastro_acoes.php';

            break;

        //region Erro
        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");

    }

}catch(Exception $e){
    PaginaSEI::getInstance()->processarExcecao($e);
}

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(':: ' . PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo . ' ::');
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
if(0){?><style><?}?>
    textarea{
        resize: none;
    }

    .inputObservacao{
        width: 97%;
    }

    #divPrincipalEncaminhamento{
        width: 100%;
    }

    #divEncaminhamentoAnl{
        margin-top: 1.8%;
        display: inline-block;
    }

    #divFila{
        display: inline-block;
        width: 260px;
        margin-top: 1.8%
    }

    #selEncaminhamento{
        width: 360px;
    }

    #selAssociarProcFila{
        width: 260px;
    }

    #selFila{
        width: 200px;
    }

    #selEncaminhamentoContest{
        width: 200px;
    }

    #txaInformacaoComplementarAnlTri{
        background-color: #dfdfdfdf;
    }

    <?if(0){?></style><?}
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
require_once ('md_utl_funcoes_js.php');
require_once ('md_utl_geral_js.php');

if(0){?><script type="text/javascript"><?}?>

    var msg11Padrao     = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_11); ?>';
    var msg52           = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_52); ?>';
    var msg53           = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_53); ?>';
    var msg54           = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_54); ?>';
    var vlFluxoFim      = '<?php echo MdUtlRevisaoRN::$FLUXO_FINALIZADO ?>';
    var vlFluxoNovaFila = '<?php echo MdUtlRevisaoRN::$NOVA_FILA ?>';
    var opcEncAvaliacao = "<?= MdUtlRevisaoINT::montarSelectEncaminhamentoString() ?>";
    var opCurrentEncAval = null;
    
    function verificarJustificativa(sel) {

        var val = sel.value;
        var nameSel = sel.name;
        var idSel = nameSel.split('_')[1];

        if(val.split('_')[1] == 'S'){
            infraGetElementById("selJust_"+idSel).style.display = "inherit";
            infraGetElementById("selJust_"+idSel).style.width = "100%";
            infraGetElementById("obs_"+idSel).style.display = "inherit";
            infraGetElementById("selJust_"+idSel).value = "";
            infraGetElementById("obs_"+idSel).value = "";
        }else{
            infraGetElementById("selJust_"+idSel).style.display = "none";
            infraGetElementById("obs_"+idSel).style.display = "none";
            infraGetElementById("selJust_"+idSel).value = "";
            infraGetElementById("obs_"+idSel).value = "";
        }
    }

    function salvar(){

        var isContestacao = '<?=$idContest ?>';
        var associarFila = '';

        if(isContestacao == 0) {
            var selectEncaminhamento = document.querySelector('#selEncaminhamento');
            var option = selectEncaminhamento.children[selectEncaminhamento.selectedIndex];
            var encaminhamentoDetalhe = option.textContent;
        }

        var selectFilaEscolhida = document.querySelector('#selFila');
        var optionFila = selectFilaEscolhida.children[selectFilaEscolhida.selectedIndex];
        var fila = optionFila.textContent;

        var valido = true;

        valido = validarSelects();
        
        if(valido) {
            valido = validarObservacao();
        }

        if( document.getElementById('selAvalQualitativa').value == '' ){
            alert( "<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_11, 'Avaliação Qualitativa das Atividades Entregues')?>" );
            document.getElementById('selAvalQualitativa').focus();
            return false;
        }

        var txtInfoComplementar = document.getElementById('txaInformacaoComplementar');

        if( !validaQtdCaracteres(txtInfoComplementar,500) ){
            alert("<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_06, array('Justificativa da Avaliação Qualitativa', '500'))?>");
            txtInfoComplementar.focus();
            return false;
        }  

        var idEncaminhamento = isContestacao == 0 ? 'selEncaminhamento' : 'selEncaminhamentoContest';
        var encaminhamento = infraGetElementById(idEncaminhamento).value;
        var associarFilaSelect = infraGetElementById('selAssociarProcFila').value;
        var selectFila = infraGetElementById('selFila').value;


        if(valido) {
            if (encaminhamento == '') {
                valido = false;
                var valor = isContestacao == 0 ? 'Encaminhamento da Avaliação' : 'Encaminhamento da Contestação';
                var msg = setMensagemPersonalizada(msg11Padrao, [valor]);
                alert(msg);
            }
        }

        if(valido){
            if(encaminhamento == vlFluxoFim){
                associarFila = 'Não';
                document.getElementById('selAssociarProcFila').value = 'N';
            }
        }

        if(valido){
            if(encaminhamento == vlFluxoNovaFila){
                encaminhamento == vlFluxoFim;
                associarFila = 'Sim';

                //verifica se selecionou uma fila
                if(selectFila == ''){
                    valido = false;
                    var msg = setMensagemPersonalizada(msg11Padrao, ['Fila']);
                    alert(msg);
                }
                // alterar valores para fluxo padrão
                if (valido){
                    document.getElementById('selEncaminhamento').value = vlFluxoFim;
                    document.getElementById('selAssociarProcFila').value = 'S';
                }
            }
        }

        if(valido){
            var isPossuiFila = document.getElementById('selFila').value != '';

            if(isPossuiFila) {
                var nomeFila = document.getElementById('selFila').options[document.getElementById('selFila').selectedIndex].innerText;
                document.getElementById('hdnSelFila').value = nomeFila.trim();
            }

            bloquearBotaoSalvar();
            if(isContestacao == 0) {
                document.getElementById('hdnEncaminhamento').value = encaminhamentoDetalhe;
            }

            document.getElementById('hdnAssociarFila').value = associarFila;
            document.getElementById('hdnFila').value = fila;
            infraGetElementById('frmRevisaoCadastro').submit();
        }
    }

    function validarObservacao() {
        var inputs = document.getElementsByClassName('inputObservacao');

        for(var i = 0; i < inputs.length; i++ ){
            /*
            if(isVisible(inputs[i]) ){
                if( inputs[i].value.trim() == '' ){
                    alert(msg52);
                    return false;
                }else if( !validaQtdCaracteres(inputs[i],250)){
                    alert("<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_06, array('Observação', '250'))?>");
                    inputs[i].focus();
                    return false;
                }
            }
            */
            if( !validaQtdCaracteres(inputs[i],250)){
                alert("<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_06, array('Observação', '250'))?>");
                inputs[i].focus();
                return false;
            }
        }

        return true;
    }

    function exibirCampoObservacao(){
        var inputs = document.getElementsByClassName('inputObservacao');
        for(var i = 0; i < inputs.length; i++ ){
            var input = inputs[i].id;

            if(infraGetElementById(input).value == ""){
                    inputs[i].style.display = 'none';

            }
        }
    }

    function inicializar(){
       exibirCampoObservacao();      
    }

    function validarSelects() {

        var arrSel = document.getElementsByTagName('Select');

        for(var i = 0; i < arrSel.length; i++ ){
            var idSel = arrSel[i].id;

            if($('#'+idSel).is(':visible')){

                if(infraGetElementById(idSel).value == ""){
                    var campo = infraGetElementById(idSel).getAttribute('campo');

                    if(campo != undefined) {
                        if (campo == 'R') {
                            alert(msg53);
                        } else {
                            alert(msg54)
                        }

                        return false;
                    }
                }
            }
        }
        return true;
    }

    function encaminhamento(val){

        if(val === 'N'){
            document.getElementById('divFila').style.display = 'inline-block' ;
            document.getElementById('selFila').innerHTML = '<?=$selFila?>';
        }else{
            document.getElementById('divFila').style.display='none';
            document.getElementById('selFila').value = '';
        }

        if( val == 'F' || val == 'R'){
            document.getElementById('txtAlertEncAvaliacao').style.display = 'block';
        }else{
            document.getElementById('txtAlertEncAvaliacao').style.display = 'none';
        }
    }

    function avaliacaoQualitativa( e ){
        if( e.value >= 0 && e.value <= 4 && e.value != '' ){
            document.getElementById('txtAlertAvalQualitativa').style.display = 'block';
            document.getElementById('divFila').style.display = 'none';

            AddRemoveOptEncaminhamentoAnalise('rem');
        }else if( e.options.selectedIndex == 0 || e.value > 4 ){
            document.getElementById('txtAlertAvalQualitativa').style.display = 'none';
            AddRemoveOptEncaminhamentoAnalise('add');
        }        
    }

    function AddRemoveOptEncaminhamentoAnalise( acao ){
        opCurrentEncAval = $('#selEncaminhamento').val();
        $('#selEncaminhamento').empty();
        $("#selEncaminhamento").append('<option value=""></option>');     
        var arrOptions = opcEncAvaliacao.split('#');
        for(var i = 0 ; i < arrOptions.length ; i++){
            var arrItemOptions = arrOptions[i].split('_');
            let selectedItem = opCurrentEncAval == arrItemOptions[0] ? ' selected ' : '';            
            if( acao == 'rem' ){
                if( arrItemOptions[0] == 'R' || arrItemOptions[0] == 'F' ){                    
                    $("#selEncaminhamento").append('<option value="'+arrItemOptions[0]+'" '+ selectedItem +'>'+arrItemOptions[1]+'</option>');
                }
            }else{
                $("#selEncaminhamento").append('<option value="'+arrItemOptions[0]+'" '+ selectedItem +'>'+arrItemOptions[1]+'</option>');
            } 
        }        
    }

    function realizarAvaliacaoProd( e ){
        <?php if($tpAcaoAval == MdUtlControleDsmpRN::$EM_ANALISE ) { ?>
            var arrIdxCol = new Array(5,6,7);
        <?php } else { ?>
            var arrIdxCol = new Array(2,3,4);
        <?php } ?>

        if ( e.checked ) {
            arrIdxCol.forEach( function( e , i) {
                mostraColumn( e );
            });
        }else{            
            arrIdxCol.forEach( function( e , i) {
                ocultaColumn( e );
            });
        }
    }

    function ocultaColumn (colIndex) {
        var table = document.getElementById('tb_avaliacao');
        for (var r = 0; r < table.rows.length; r++){
            table.rows[r].cells[colIndex].style.display = 'none';
        }
    }

    function mostraColumn (colIndex) {
        var table = document.getElementById('tb_avaliacao');
        for (var r = 0; r < table.rows.length; r++){
            table.rows[r].cells[colIndex].style.display = '';
        }
    }

    <?if(0){?></script><?}
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,"onload='inicializar();'");

//texto do tooltip
$txtTooltipEncaminhamentoRevisao="Selecione a opção \"Associar em Fila após Finalizar Fluxo\" caso queira reiniciar o fluxo em alguma Fila imediatamente com a finalização do fluxo atual. Esta opção é listada somente se a Avaliação Qualitativa das Atividades Entregues for maior que 4. \n \n Selecione a opção \"Finalizar Fluxo\" para concluir sem associar a qualquer Fila imediatamente na finalização do fluxo atual. Esta opção é listada somente se a Avaliação Qualitativa das Atividades Entregues for maior que 4. \n \n Selecione a opção \"Retornar para Correção por outro Participante na mesma Fila\" caso identificada necessidade de correção que possa ser feita por qualquer Membro Participante da Fila. A Análise da Correção ainda demandará sua Distribuição manual. Esta opção implica na perca do Tempo Executado pelo Membro Participante que fez a Análise atual. \n \n Selecione a opção \"Retornar para Correção pelo mesmo Participante\" caso identificada necessidade de correção e deseje que a Análise da Correção seja automaticamente distribuída para o Membro Participante que realizou a Análise atual. Esta opção implica na perca do Tempo Executado pelo Membro Participante que fez a Análise atual.";

?>
    <form  id="frmRevisaoCadastro" method="post"
           action="<?= PaginaSEI::getInstance()->formatarXHTML(
               SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])
           ) ?>">

        <?php PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);?>
        
        <?php if( $isPgPadrao != 0 ) { ?>
            <label style='margin-bottom: .2em; font-weight: bold; line-height: 1.5em; color: black;'>
                Número do Processo:
            </label>
            <label><?= $strNumeroProcesso ?> </label>
            <div class="clear"></div>
            <br><br>
        <?php } ?>

        <label id="lblTipoControle" for="selTipoControle" accesskey="" class="infraLabelOpcional">Tipo de Controle:</label><br/>
        <div class="clear"></div>
        <input type="text" style="width:300px" id="txtTipoControle" name="txtTipoControle" class="infraText" value="<?= $strNomeTpControle ?>" disabled/><br/><br/>

        <label id="lblFila" for="selFila" accesskey="" class="infraLabelOpcional">Fila:</label><br/>
        <div class="clear"></div>
        <input type="text" style="width:300px" id="txtNomeFila" name="txtNomeFila" class="infraText" value="<?= $strNomeFila ?>" disabled/><br/><br/>
        

        <label for="selAvalQualitativa" class="infraLabelObrigatorio">
            Avaliação Qualitativa das Atividades Entregues:
            <a <?=PaginaSEI::montarTitleTooltip("A Avaliação Qualitativa das Atividades Entregues ocorre com a atribuição de uma nota entre 0 e 10 para representar a qualidade do que foi entregue como um todo, onde 0 é a menor nota e 10 a maior nota. \n \n Ao selecionar nota entre 0 e 4 implica na reprovação das Atividades Entregues, sendo necessário o Retorno para Correção.")?>
                tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
                <img border="0" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/ajuda.gif" 
                     style="width: 16px;height: 16px;margin-bottom: -3px;"class="infraImg"/>
            </a>
        </label>
        <br>
        <div class="clear"></div>
        <select name="selAvalQualitativa" id="selAvalQualitativa" class="infraSelect" <?=$disabled?> onchange="avaliacaoQualitativa( this )" style="width:308px;">
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

        <div id='txtAlertAvalQualitativa' style='color:red; font-size: 1.3em; margin: 2px 0px 3px 0px; display:none;'> 
            <span style="font-weight: bold; color:red;">Atenção: </span> A nota selecionada implica na reprovação das Atividades Entregues. 
        </div>

        <br>

        <label id="lblInformacaoComplementar" for="txaInformacaoComplementar" class="infraLabelOpcional"
                   tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
            Justificativa da Avaliação Qualitativa:
        </label>
        <a <?=PaginaSEI::montarTitleTooltip("A Justificativa da Avaliação Qualitativa não é obrigatória, contudo, pode ser útil para explicar detalhes sobre a nota atribuída e do que deve ser corrigido.")?>
            tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
            <img border="0" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/ajuda.gif" 
                 style="width: 16px; height: 16px; margin-bottom: -3px;" class="infraImg"/>
        </a>
        <br/>

        <textarea style="width: 79%" id="txaInformacaoComplementar" <?=$disabled?> name="txaInformacaoComplementar" rows="3" class="infraTextArea" maxlength="500" onkeypress="return infraMascaraTexto(this,event, 500);" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?= $isConsultar?$strInformCompRevisao:''?></textarea>

        <br>

        <?

        $isConsultaContestacao = !is_null($objMdUtlRevisaoDTO) && !is_null($objMdUtlRevisaoDTO->getStrStaEncaminhamentoContestacao()) && $isConsultar;
        if($idContest == 1 || $isConsultaContestacao){
            $disabledContestacao = '';
            if($isConsultar) {
                $disabledContestacao = !is_null($objMdUtlRevisaoDTO->getStrStaEncaminhamentoContestacao()) ? 'disabled=disabled' : '';
            }
            ?>
            <div style="margin-top: 2%; display: inline-block;">
                <label id="lblInformacaoComplementar" for="txaInformacaoComplementar" class="infraLabelObrigatorio"
                       tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                    Encaminhamento da Contestação:
                </label>
                <?php
                $option = MdUtlRevisaoINT::montarSelectEncaminhamentoContestacao($encaminhamentoRevisao['sta_encaminhamento'], $idContest);
                ?>
                <select <?php echo $disabledContestacao; ?> class="infraSelect" name="selEncaminhamentoContest"  id="selEncaminhamentoContest" onchange="encaminhamento(this.value)">
                    <?=$option?>
                </select>
            </div>

        <? } else {
            ?>
            <div style="margin-top: 1.8%; display: inline-block;">
                <label id="lblInformacaoComplementar" for="txaInformacaoComplementar" class="infraLabelObrigatorio"
                       tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                    Encaminhamento da Avaliação:
                </label>
                <a style="" id="btAjudaEncAnalise" <?=PaginaSEI::montarTitleTooltip($txtTooltipEncaminhamentoRevisao)?>
                   tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
                    <img border="0" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/ajuda.gif" 
                         style="width: 16px; height: 16px; margin-bottom: -3px;" class="infraImg"/>
                </a>
                <?php
                    $option = MdUtlRevisaoINT::montarSelectEncaminhamento($encaminhamentoRevisao['sta_encaminhamento'],$isConsultar);
                ?>
                <select class="infraSelect" name="selEncaminhamento"  id="selEncaminhamento" <?=$disabled?> onchange="encaminhamento(this.value)">
                    <?=$option?>
                </select>
            </div>

            <div id='txtAlertEncAvaliacao' style='color:red; font-size: 1.3em; margin: 3px 0px 3px 0px; display:none;'> 
                <span style="font-weight: bold; color:red;">Atenção: </span> O encaminhamento selecionado implica na reprovação das Atividades Entregues e o Tempo Executado pelo Participante correspondente será desconsiderado.
            </div>
        <?}?>

        <div id="divFila" style="display: none; margin-left: 45px;">
            <label id="lblFila" for="selFila" class="infraLabelObrigatorio">Fila:</label>
            <select id="selFila" name="selFila" <?=$disabled?> class="infraSelect">
                <?= $selFila ?>
            </select>
        </div>
        
        <div style="margin-left: -2px; margin-top: 12px;">
            <input type="checkbox" name="cbkRealizarAvalProdAProd" id="cbkRealizarAvalProdAProd" <?=$disabled?> <?= $ckbRealizarAvalProdProd ?> onchange="realizarAvaliacaoProd( this )" value="S">
            <label id="lblInformacaoComplementar" for="txaInformacaoComplementar" class="infraLabelOpcional"
                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                    Realizar Avaliação Produto a Produto
            </label>
            <a <?=PaginaSEI::montarTitleTooltip("Marque a opção Realizar Avaliação Produto a Produto caso seja necessário avaliar, justificar e explicar detalhes da avaliação sobre cada Produto entregue.")?>
                tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
                <img border="0" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/ajuda.gif"
                     style="width: 16px; height: 16px; margin-bottom: -3px;" class="infraImg"/>
            </a>
        </div>

        <?php
            PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistro);
            PaginaSEI::getInstance()->abrirAreaDados('auto');
            echo $divInfComplementar;
        ?>

        <input type="hidden" id="hdnTbRevisaoAnalise" name="hdnTbRevisaoAnalise"   value="<?=$hdnTbRevisaoAnalise?>"/>
        <input type="hidden" id="hdnIdProcedimento"   name="hdnIdProcedimento"     value="<?=$idProcedimento?>"/>
        <input type="hidden" id="hdnIdFilaAtiva"      name="hdnIdFilaAtiva"        value="<?=$idFilaAtiva?>"/>
        <input type="hidden" id="hdnIdTpCtrl"         name="hdnIdTpCtrl"           value="<?=$idTipoControle?>"/>
        <input type="hidden" id="hdnTmpExecucao"       name="hdnTmpExecucao"         value="<?=$valorTempoExecucao?>"/>
        <input type="hidden" id="hdnEncaminhamento" name="hdnEncaminhamento" value="">
        <input type="hidden" id="hdnAssociarFila" name="hdnAssociarFila" value="">
        <input type="hidden" id="selAssociarProcFila" name="selAssociarProcFila" value="">
        <input type="hidden" id="hdnFila" name="hdnFila" value="">
        <input type="hidden" name="hdnIsPgPadrao" id="hdnIsPgPadrao" value="<?php echo $isPgPadrao; ?>"/>
        <input type="hidden" name="hdnSelFila" id="hdnSelFila" value=""/>
        <input type="hidden" id="hdnIdMdUtlContestRevisao" name="hdnIdMdUtlContestRevisao" value="<?php echo $idContest ?>"/>

        <?php

        PaginaSEI::getInstance()->fecharAreaDados();
        PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
        ?>

    </form>
    <script>        
        if(document.getElementById('selEncaminhamento').value === 'N' || document.getElementById('selEncaminhamentoContest').value === 'N'){
            document.getElementById('divFila').style.display = 'inline-block' ;
        }        
    </script>
<?php
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
