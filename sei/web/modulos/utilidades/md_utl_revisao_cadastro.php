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

    $strTitulo       = 'Análise ';

//Tipo de Controle e Procedimento
    $objTriagemRN              = new MdUtlTriagemRN();
    $objRegrasGerais           = new MdUtlRegrasGeraisRN();
    $objTriagemDTO             = new MdUtlTriagemDTO();
    $objMdUtlControleDsmpRN     = new MdUtlControleDsmpRN();
    $objRelTpCtrlUndRN         = new MdUtlAdmRelTpCtrlDesempUndRN();

    $idTipoControle            = $objRelTpCtrlUndRN->getTipoControleUnidadeLogada();
    $isAnalise                 = $_GET['acao'] == 'md_utl_revisao_analise_cadastrar' || $_GET['acao'] == 'md_utl_revisao_analise_consultar';
    $idFilaAtiva               = $_GET['id_fila'];
    $objControleDsmpDTO         = $objMdUtlControleDsmpRN->getObjControleDsmpAtivoRevisao(array($idProcedimento, $isAnalise));

    $idMdUtlAnalise            = $objControleDsmpDTO->getNumIdMdUtlAnalise();
    $idMdUtlControleDsmp        = $objControleDsmpDTO->getNumIdMdUtlControleDsmp();
    $valorUndEsforco           = $objControleDsmpDTO->getNumUnidadeEsforco();
    $isConsultar               = false;

    $selRevisao     = MdUtlAdmTpRevisaoINT::montarSelectTpRevisao($idTipoControle);
    $selJustRevisao = MdUtlAdmTpJustRevisaoINT::montarSelectJustRevisao($idTipoControle);
    $arrComandos    = array();

    $strTitulo='Revisão';

    switch ($_GET['acao']) {

        case 'md_utl_revisao_analise_cadastrar':
            $arrComandos[] = '<button type="button" accesskey="s" id="btnSalvar" value="salvar" onclick="salvar();" class="infraButton botaoSalvar">
                                                <span class="infraTeclaAtalho">S</span>alvar</button>';
            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="window.history.back();" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

            require_once 'md_utl_revisao_analise_cadastro_acoes.php';
            if(isset($_POST) && count($_POST) >0){

                $MdUtlRelRevisTrgAnlsRN = new MdUtlRelRevisTrgAnlsRN();
                $MdUtlRelRevisTrgAnlsRN->cadastrarRevisaoTriagemAnalise($objControleDsmpDTO);
                if($isPgPadrao == 0) {
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_processo_listar&id_procedimento=' . $idProcedimento));
                }else{
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_meus_processos_dsmp_listar&id_procedimento=' . $idProcedimento));
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

                $MdUtlRelRevisTrgAnlsRN = new MdUtlRelRevisTrgAnlsRN();
                $MdUtlRelRevisTrgAnlsRN->cadastrarRevisaoTriagemAnalise($objControleDsmpDTO);

                if($isPgPadrao == 0) {
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_processo_listar&id_procedimento=' . $idProcedimento));
                }else{
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_meus_processos_dsmp_listar&id_procedimento=' . $idProcedimento));
                }
                die;
            }
            break;

        case 'md_utl_revisao_analise_consultar':
            $isConsultar = true;

            if($_GET['acao_origem']== 'md_utl_analise_alterar'){
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

        var selectEncaminhamento = document.querySelector('#selEncaminhamento');
        var option = selectEncaminhamento.children[selectEncaminhamento.selectedIndex];
        var encaminhamentoDetalhe = option.textContent;

       var valido = true;

        valido = validarSelects();

        if(valido) {
            valido = validarObservacao();
        }

        var encaminhamento = infraGetElementById('selEncaminhamento').value;
        if(valido && encaminhamento == ''){
            valido = false;
            var msg = setMensagemPersonalizada(msg11Padrao, ['Encaminhamento da Revisão']);
            alert(msg);
        }

        if(valido){
            bloquearBotaoSalvar();
            document.getElementById('hdnEncaminhamento').value = encaminhamentoDetalhe;
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

        <div style="margin-top: 1.8%">
            <label id="lblInformacaoComplementar" for="txaInformacaoComplementar" class="infraLabelObrigatorio"
                   tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                Encaminhamento da Revisão:
            </label>
            <?php
            $option = MdUtlRevisaoINT::montarSelectEncaminhamentoRevisao($strEncaminhamento);
            ?>
            <select class="infraSelect" style="width: 30%" name="selEncaminhamento" <?=$disabled?> id="selEncaminhamento">
                <?=$option?>
            </select>
        </div>

        <input type="hidden" id="hdnTbRevisaoAnalise" name="hdnTbRevisaoAnalise"   value="<?=$hdnTbRevisaoAnalise?>"/>
        <input type="hidden" id="hdnIdProcedimento"   name="hdnIdProcedimento"     value="<?=$idProcedimento?>"/>
        <input type="hidden" id="hdnIdFilaAtiva"      name="hdnIdFilaAtiva"        value="<?=$idFilaAtiva?>"/>
        <input type="hidden" id="hdnIdTpCtrl"         name="hdnIdTpCtrl"           value="<?=$idTipoControle?>"/>
        <input type="hidden" id="hdnUndEsforco"       name="hdnUndEsforco"         value="<?=$valorUndEsforco?>"/>
        <input type="hidden" id="hdnEncaminhamento" name="hdnEncaminhamento" value="">
        <input type="hidden" name="hdnIsPgPadrao" id="hdnIsPgPadrao" value="<?php echo $isPgPadrao; ?>"/>

        <?php

        PaginaSEI::getInstance()->fecharAreaDados();
        PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
        ?>


    </form>

<?php
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();




