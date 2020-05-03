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

    $idTipoControle            = $objRelTpCtrlUndRN->getTipoControleUnidadeLogada();
    $isAnalise                 = $_GET['acao'] == 'md_utl_revisao_analise_cadastrar' || $_GET['acao'] == 'md_utl_revisao_analise_consultar';

    $isEdicao                  = $_GET['acao'] == 'md_utl_revisao_analise_cadastrar' || $_GET['acao'] == 'md_utl_revisao_triagem_cadastrar';
    $idFilaAtiva               = $_GET['id_fila'];
    $selectFila                = '';
    
    
    $objControleDsmpDTO        = $objMdUtlControleDsmpRN->getObjControleDsmpAtivoRevisao(array($idProcedimento, $isAnalise));
    $idContatoAtual            = $objControleDsmpDTO->getNumIdContato();
    $strNumeroProcesso         = $objControleDsmpDTO->getStrProtocoloProcedimentoFormatado();

    $idRevisao                 = $objControleDsmpDTO->getNumIdMdUtlRevisao();
    $idMdUtlAnalise            = $objControleDsmpDTO->getNumIdMdUtlAnalise();
    $idMdUtlControleDsmp       = $objControleDsmpDTO->getNumIdMdUtlControleDsmp();
    $valorUndEsforco           = $objControleDsmpDTO->getNumUnidadeEsforco();
    $idStatus                  = $objControleDsmpDTO->getStrStaAtendimentoDsmp();

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
    $arrObjsFilaDTO  = $objFilaRN->getFilasTipoControle($idTipoControle);
    $selFila         = MdUtlAdmFilaINT::montarSelectFilas($selFila, $arrObjsFilaDTO, null, true);
    $optionAssociar  = MdUtlRevisaoINT::montarSelectSinRetorno();

    if($idContest == 0) {
        if ($idMdUtlAnalise != '' || !is_null($idMdUtlAnalise)) {
            $selAssocFila = $objControleDsmpDTO->getNumIdMdUtlAdmFilaEncAnalise() != null ? 'S' : 'N';
            $idAssocFila = $objControleDsmpDTO->getNumIdMdUtlAdmFilaEncAnalise();
            $optionAssociar = MdUtlRevisaoINT::montarSelectSinRetorno($selAssocFila);
            $selFila = MdUtlAdmFilaINT::montarSelectFilas($idAssocFila, $arrObjsFilaDTO, null, true);
        } else {
            $selAssocFila = $objControleDsmpDTO->getNumIdMdUtlAdmFilaEncTriagem() != null ? 'S' : 'N';
            $idAssocFila = $objControleDsmpDTO->getNumIdMdUtlAdmFilaEncTriagem();
            $optionAssociar = MdUtlRevisaoINT::montarSelectSinRetorno($selAssocFila);
            $selFila = MdUtlAdmFilaINT::montarSelectFilas($idAssocFila, $arrObjsFilaDTO, null, true);
        }
    }

    $arrComandos    = array();

    $strTitulo='Revisão';

    switch ($_GET['acao']) {

        case 'md_utl_revisao_analise_cadastrar':
            $arrComandos[] = '<button type="button" accesskey="s" id="btnSalvar" value="salvar" onclick="salvar();" class="infraButton botaoSalvar">
                                                <span class="infraTeclaAtalho">S</span>alvar</button>';
            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="window.history.back();" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

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

            require_once 'md_utl_revisao_analise_cadastro_acoes.php';

            break;

        case 'md_utl_revisao_triagem_consultar':
            $isConsultar = true;

            if($_GET['acao_origem']== 'md_utl_triagem_alterar'){
                $arrComandos[] = '<button type="button" accesskey="C" name="btnFechar" id="btnFechar" value="Fechar" onclick="window.close();" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
            }else {
                $arrComandos[] = '<button type="button" accesskey="C" name="btnFechar" id="btnFechar" value="Fechar" onclick="window.history.back();" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
            }

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
        margin-left: 45px;
        width: 260px;
    }

    #selEncaminhamento{
        width: 200px;
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
require_once ('md_utl_geral_js.php');

if(0){?><script type="text/javascript"><?}?>

    var msg11Padrao = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_11); ?>';
    var msg52       = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_52); ?>';
    var msg53       = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_53); ?>';
    var msg54       = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_54); ?>';
    var vlFluxoFim  = '<?php echo MdUtlRevisaoRN::$FLUXO_FINALIZADO ?>';

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

        if(isContestacao == 0) {
            var selectEncaminhamento = document.querySelector('#selEncaminhamento');
            var option = selectEncaminhamento.children[selectEncaminhamento.selectedIndex];
            var encaminhamentoDetalhe = option.textContent;
        }

        var selectAssociarFila = document.querySelector('#selAssociarProcFila');
        var optionAssociarFila = selectAssociarFila.children[selectAssociarFila.selectedIndex];
        var associarFila = optionAssociarFila.textContent;

        var selectFilaEscolhida = document.querySelector('#selFila');
        var optionFila = selectFilaEscolhida.children[selectFilaEscolhida.selectedIndex];
        var fila = optionFila.textContent;

        var valido = true;

        valido = validarSelects();

        if(valido) {
            valido = validarObservacao();
        }


        var idEncaminhamento = isContestacao == 0 ? 'selEncaminhamento' : 'selEncaminhamentoContest';
        var encaminhamento = infraGetElementById(idEncaminhamento).value;
        var associarFilaSelect = infraGetElementById('selAssociarProcFila').value;
        var selectFila = infraGetElementById('selFila').value;


        if(valido) {
            if (encaminhamento == '') {
                valido = false;
                var valor = isContestacao == 0 ? 'Encaminhamento da Revisão' : 'Encaminhamento da Contestação';
                var msg = setMensagemPersonalizada(msg11Padrao, [valor]);
                alert(msg);
            }
        }

        if(valido){
            if(encaminhamento == vlFluxoFim){
                if(associarFilaSelect == ''){
                    valido = false;
                    var msg = setMensagemPersonalizada(msg11Padrao, ['Associar Processos em Fila Após a Revisão']);
                    alert(msg);
                }

                if(valido && associarFilaSelect == 'S'){
                    if(selectFila == ''){
                        valido = false;
                        var msg = setMensagemPersonalizada(msg11Padrao, ['Fila']);
                        alert(msg);
                    }
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

            if(isVisible(inputs[i]) && inputs[i].value.trim() == '' ){
                    alert(msg52);
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

    function validarFila(val){
        if(val === 'S'){
            document.getElementById('divFila').style.display = 'inline-block' ;
            document.getElementById('selFila').innerHTML = '<?=$selFila?>';
        }else{
            document.getElementById('divFila').style.display='none';
            document.getElementById('selFila').value = '';
        }
    }

    function encaminhamento(val){
        var idDivPrincipal ='divPrincipalEncaminhamento';
        var selectFila = document.getElementById('selFila').value;
        var valAssocFila = '<?=$selAssocFila?>';
       
        if(val === 'X'){
            document.getElementById('selAssociarProcFila').innerHTML = '<?=$optionAssociar?>';
            validarFila(valAssocFila);
            document.getElementById(idDivPrincipal).style.display = 'inline-block' ;
            if(selectFila){
            document.getElementById('divFila').style.display = 'inline-block' ;
            }
        }else{
            document.getElementById(idDivPrincipal).style.display='none';
            document.getElementById('divFila').style.display='none';
            document.getElementById('selAssociarProcFila').value = '';
            document.getElementById('selFila').value = '';
        }
    }

    <?if(0){?></script><?}
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,"onload='inicializar();'");

?>
    <form  id="frmRevisaoCadastro" method="post"
           action="<?= PaginaSEI::getInstance()->formatarXHTML(
               SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])
           ) ?>">

        <?php PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);

           PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistro);

        PaginaSEI::getInstance()->abrirAreaDados('auto');

        echo $divInfComplementar;

        ?>

        <div id="divInformacaoComplementarRevisao" style="margin-top: 1.8%">
            <label id="lblInformacaoComplementar" for="txaInformacaoComplementar" class="infraLabelOpcional"
                   tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                Informações Complementares da Revisão:
            </label>
            <br/>
            <textarea style="width: 79%" id="txaInformacaoComplementar" <?=$disabled?> name="txaInformacaoComplementar" rows="3" class="infraTextArea" onkeypress="return infraMascaraTexto(this,event, 500);" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?php echo $strInformCompRevisao ?></textarea>
        </div>

        <?

        $isConsultaContestacao = !is_null($objMdUtlRevisaoDTO) && !is_null($objMdUtlRevisaoDTO->getStrStaEncaminhamentoContestacao()) && $isConsultar;
        if($idContest == 1 || $isConsultaContestacao){
            $disabledContestacao = '';
            if($isConsultar) {
                $disabledContestacao = !is_null($objMdUtlRevisaoDTO->getStrStaEncaminhamentoContestacao()) ? 'disabled=disabled' : '';
            }
            ?>
            <div style="margin-top: 2%">
                <label id="lblInformacaoComplementar" for="txaInformacaoComplementar" class="infraLabelObrigatorio"
                       tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                    Encaminhamento da Contestação:
                </label>
                <?php
                $option = MdUtlRevisaoINT::montarSelectEncaminhamentoContestacao($strEncaminhamento, $idContest);
                ?>
                <select <?php echo $disabledContestacao; ?> class="infraSelect" name="selEncaminhamentoContest"  id="selEncaminhamentoContest" onchange="encaminhamento(this.value)">
                    <?=$option?>
                </select>
            </div>

        <? } else {
            ?>
            <div style="margin-top: 1.8%">
                <label id="lblInformacaoComplementar" for="txaInformacaoComplementar" class="infraLabelObrigatorio"
                       tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                    Encaminhamento da Revisão:
                </label>
                <?php
                $option = MdUtlRevisaoINT::montarSelectEncaminhamento($strEncaminhamento);
                ?>
                <select class="infraSelect" name="selEncaminhamento"  id="selEncaminhamento" <?=$disabled?> onchange="encaminhamento(this.value)">
                    <?=$option?>
                </select>
            </div>
        <?}?>

        <div id="divPrincipalEncaminhamento" style="display: none;">
            <div id="divEncaminhamentoAnl">

                <div style="margin-top: 1.8%">
                    <label id="lblInformacaoComplementar" for="txaInformacaoComplementar" class="infraLabelObrigatorio"
                           tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                        Associar Processo em Fila após a Revisão:
                    </label>

                    <select class="infraSelect" name="selAssociarProcFila"  id="selAssociarProcFila" <?=$disabled?> onchange="validarFila(this.value);">
                        <?=$optionAssociar?>
                    </select>
                </div>

            </div>

            <div id="divFila" style="display: none;">
                <label id="lblFila" for="selFila" class="infraLabelObrigatorio">Fila:</label>
                <select id="selFila" name="selFila" <?=$disabled?> class="infraSelect">
                    <?= $selFila ?>
                </select>
            </div>
        </div>

        <input type="hidden" id="hdnTbRevisaoAnalise" name="hdnTbRevisaoAnalise"   value="<?=$hdnTbRevisaoAnalise?>"/>
        <input type="hidden" id="hdnIdProcedimento"   name="hdnIdProcedimento"     value="<?=$idProcedimento?>"/>
        <input type="hidden" id="hdnIdFilaAtiva"      name="hdnIdFilaAtiva"        value="<?=$idFilaAtiva?>"/>
        <input type="hidden" id="hdnIdTpCtrl"         name="hdnIdTpCtrl"           value="<?=$idTipoControle?>"/>
        <input type="hidden" id="hdnUndEsforco"       name="hdnUndEsforco"         value="<?=$valorUndEsforco?>"/>
        <input type="hidden" id="hdnEncaminhamento" name="hdnEncaminhamento" value="">
        <input type="hidden" id="hdnAssociarFila" name="hdnAssociarFila" value="">
        <input type="hidden" id="hdnFila" name="hdnFila" value="">
        <input type="hidden" name="hdnIsPgPadrao" id="hdnIsPgPadrao" value="<?php echo $isPgPadrao; ?>"/>
        <input type="hidden" name="hdnSelFila" id="hdnSelFila" value=""/>
        <input type="hidden" id="hdnIdMdUtlContestRevisao" name="hdnIdMdUtlContestRevisao" value="<?php echo $idContest ?>"/>

        <?php

        PaginaSEI::getInstance()->fecharAreaDados();
        PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
        ?>

    </form>

<?php
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
