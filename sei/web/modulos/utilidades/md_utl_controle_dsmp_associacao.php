<?php
/**
 * Created by PhpStorm.
 * User: jhon.carvalho
 * Date: 04/10/2018
 * Time: 13:54
 */


try {
    require_once dirname(__FILE__) . '/../../SEI.php';

    session_start();

    //////////////////////////////////////////////////////////////////////////////
    InfraDebug::getInstance()->setBolLigado(false);
    InfraDebug::getInstance()->setBolDebugInfra(true);
    InfraDebug::getInstance()->limpar();
    //////////////////////////////////////////////////////////////////////////////

    SessaoSEI::getInstance()->validarLink();
    $strLinkAjaxUltimaFila = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_utl_adm_buscar_ultimas_filas');
    $objFilaRN             = new MdUtlAdmFilaRN();

    PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);

    $strParametros = '';

    switch($_GET['acao']) {

        case 'md_utl_controle_dsmp_associar':

            $strTitulo = 'Associar Processo à Fila';
            $idTpControle     = isset($_GET['id_tp_controle_desmp']) ? $_GET['id_tp_controle_desmp'] : $_POST['hdnIdTipoControleUtl'];
            $idProcedimento   = isset($_GET['id_procedimento']) ? $_GET['id_procedimento'] : $_POST['hdnIdProcedimento'];
            $isDetalhamento   = isset($_GET['is_detalhamento']) ? $_GET['is_detalhamento'] : 0;
            $selFila = '';

            if(isset($_POST['sbmAssociarFila'])){

                $objHistoricoRN        = new MdUtlHistControleDsmpRN();
                $objMdUtlControleDsmpRN = new MdUtlControleDsmpRN();
                $arrDados = PaginaSEI::getInstance()->getArrItensTabelaDinamica($_POST['hdnTbAssociarFila']);
                $count = count($arrDados);

                if($_POST['hdnValueFila'] == ''){

                    $funcFirstElement = function ($value) {
                        reset($value);
                        return current($value);
                    };


                    $arrRetorno = $objMdUtlControleDsmpRN->controlarHistorico($arrDados, 'S', 'N', true, true);

                    $arrIdProcedimento = array_map($funcFirstElement, $arrDados);

                    $objHistoricoRN->removerFilaControleDsmp(array($arrIdProcedimento, $arrRetorno));
                    $objRegrasGerais = new MdUtlRegrasGeraisRN();
                    $objRegrasGerais->controlarAtribuicaoGrupo($arrIdProcedimento);

                    $_SESSION['ID_PROCEDIMENTO_FILA_ASSOCIAR'] = $arrIdProcedimento;

                }else{
                    $objRN = new MdUtlControleDsmpRN();
                    $objRN->associarFila();
                }

                echo "<script>";
                echo "window.opener.location.reload();";
                //echo " window.opener.focus();";
                echo "window.close();";
                echo "</script>";
                die;
            }
            break;

        default:
            throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
    }

    $arrComandos = array();

    //Botões de ação do topo
    $arrComandos[] = '<button type="button" accesskey="S" id="sbmAssociarFila" onclick="submeterAssociarFila();" name="sbmAssociarFila" class="infraButton">
                                    <span class="infraTeclaAtalho">S</span>alvar</button>';

    $arrComandos[] = '<button type="button" accesskey="c" id="btnFechar" onclick="window.close();" class="infraButton">
                                    Fe<span class="infraTeclaAtalho">c</span>har</button>';

    $arrObjsFilaDTO  = $objFilaRN->getFilasTipoControle($idTpControle);
    $selFila         = MdUtlAdmFilaINT::montarSelectFilas($selFila, $arrObjsFilaDTO, null, true);


}catch(Exception $e){
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

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
require_once 'md_utl_geral_js.php';
if(0){?><script><?}?>
    var msgNenhumaFila = new Array();
    var msgPadrao84 = '<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_84)?>';
    var msgPadrao85 ='<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_85)?>';

    function inicializar() {
        iniciarTbDinamica();
        var arrIdsProcedimento = new Array();
        var isDetalhamento = "<?=$isDetalhamento?>";

        if(isDetalhamento != '1') {
            arrIdsProcedimento = retornaIdsProcedimentosFormatados();
        }

        buscarUltimasFilas(arrIdsProcedimento, isDetalhamento);

    }

    function submeterAssociarFila(){
        if(validarNenhumaFila()) {
            var selectNomeFila = document.getElementById('selFila');
            var nomeFila = selectNomeFila.options[selectNomeFila.selectedIndex].innerText;
            document.getElementById('hdnValueFila').value = selectNomeFila.value;
            document.getElementById('hdnSelFila').value = nomeFila;
            document.getElementById("frmAssociarFila").submit();
        }
    }

    function validarNenhumaFila(){
        var selFila = document.getElementById('selFila').value;
        if(selFila == ''){
            if(msgNenhumaFila.length > 0){
                if(msgNenhumaFila.length == 1){
                    var msg = setMensagemPersonalizada(msgPadrao85, msgNenhumaFila);
                    alert(msg);
                }else {
                    var msg = msgPadrao84 + '\n';
                    for (var i = 0; i < msgNenhumaFila.length; i++) {
                        msg += ' \n - ' +msgNenhumaFila[i];
                    }

                    alert(msg);
                }

                return false;
            }else{
                return true;
            }
        }else{
            return true;
        }
    }


    function iniciarTbDinamica() {
        objTabelaDinamicaAssociarFila = new infraTabelaDinamica('tbAssociarFila', 'hdnTbAssociarFila');
        objTabelaDinamicaAssociarFila.gerarEfeitoTabela=true;
        objTabelaDinamicaAssociarFila.atualizaHdn();
    }


    function buscarUltimasFilas(jsonIdsProcedimento, isDetalhamento) {
        var idProcedimento = "<?=$idProcedimento?>";

        $.ajax({
            type: "POST",
            url: "<?=$strLinkAjaxUltimaFila?>",
            dataType: "xml",
            async: false,
            data: {
                jsonIdsProcedimento: jsonIdsProcedimento,
                idProcedimento     : idProcedimento,
                isDetalhamento     : isDetalhamento,
            },
            error: function(r){
                console.log(r);
            },
            success: function (arrUltimasFilas) {

                if(isDetalhamento == '1'){
                    montarTelaDetalhamento(arrUltimasFilas, idProcedimento);
                }else{
                    montarTelaMultipla(arrUltimasFilas);
                }
            }
        });
    }

    function montarTelaMultipla(arrUltimasFilas){

        var linhas = window.opener.$(".infraTrMarcada");

        for (var i = linhas.length - 1; i >=0; i--) {
            var objLinha         = linhas[i];
            var idProcedimento   = $(objLinha).find('.tdIdProcesso').text();
            var strProcesso      = $(objLinha).find('.tdNomeProcesso').text();
            var filaAtual        = $(objLinha).find('.tdFilaProcesso').text();
            var nomeCampoUltFila = 'UltimaFila' + idProcedimento;
            var ultimaFila       = $(arrUltimasFilas).find(nomeCampoUltFila).text();
            var status           = $(objLinha).find('.tdIdStatusAtual').text();
            var linhaAtual       = [idProcedimento,strProcesso,ultimaFila,filaAtual, status];

            if(filaAtual == '') {
                msgNenhumaFila.push(strProcesso);
            }

            objTabelaDinamicaAssociarFila.adicionar(linhaAtual);
        }
    }

    function montarTelaDetalhamento(arrUltimasFilas, idProcedimento){
        var objLinha         = arrUltimasFilas[0];
        var idProcedimento   = idProcedimento;
        var strProcesso      = window.opener.document.getElementById('hdnProtocoloFormatado').value;
        var filaAtual        =  window.opener.document.getElementById('hdnNomeFilaAtual').value;;
        var status           =  window.opener.document.getElementById('hdnIdStatusAtual').value;;
        var nomeCampoUltFila = 'UltimaFila' + idProcedimento;
        var ultimaFila       = $(arrUltimasFilas).find(nomeCampoUltFila).text();
        var linhaAtual = [idProcedimento,strProcesso,ultimaFila,filaAtual, status];

        if(filaAtual == '') {
            msgNenhumaFila.push(strProcesso);
        }

        objTabelaDinamicaAssociarFila.adicionar(linhaAtual);
    }


    function retornaIdsProcedimentosFormatados(){
        var linhas = window.opener.$(".infraTrMarcada");
        var arrProcedimentos = new Array();

        for (var i = 0; i < linhas.length; i++) {
            var objLinha = linhas[i];
            var idProcedimento = $(objLinha).find('.tdIdProcesso').text();
            arrProcedimentos.push(idProcedimento);
        }

        if(arrProcedimentos.length > 0){
            return JSON.stringify(arrProcedimentos);
        }

        return '';

    }

    <?if(0){?></script><?}

PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
    <form id="frmAssociarFila" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">

        <?
        //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
        PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        PaginaSEI::getInstance()->abrirAreaDados('26em');
        //PaginaSEI::getInstance()->montarAreaValidacao();
        ?>
        <div id="divFila">
            <label id="lblFila" for="selFila" accesskey="" class="infraLabelOpcional">Fila:</label>
            <select style="width:300px" id="selFila"  name="selFila" class="infraSelect"
                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                <?=$selFila ?>
            </select>
        </div>
        <br><br>
        <table width="80%" class="infraTable" summary="Associar à Fila" id="tbAssociarFila">
            <tr>
                <th class="infraTh" align="center" width="1%" style="display: none" >IdVinculo</th>
                <th class="infraTh" width="23%">Processo</th>
                <th class="infraTh" width="20%">Última Fila Registrada</th>
                <th class="infraTh" width="20%">Fila Atual</th>
                <th class="infraTh" style="display: none">Status</th>
            </tr>
        </table>
        <?

        PaginaSEI::getInstance()->fecharAreaDados();
        //PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
        PaginaSEI::getInstance()->montarAreaDebug();
        //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
        ?>
        <input type="hidden" id="hdnTbAssociarFila"     name="hdnTbAssociarFila"  />
        <input type="hidden" id="sbmAssociarFila"       name="sbmAssociarFila"  />
        <input type="hidden" id="hdnIdTipoControleUtl"  name="hdnIdTipoControleUtl" value="<?=$idTpControle?>">
        <input type="hidden" id="hdnIdProcedimento"     name="hdnIdProcedimento"    value="<?=$idProcedimento?>">
        <input type="hidden" id="hdnSelFila"            name="hdnSelFila" >
        <input type="hidden" id="hdnValueFila"          name="hdnValueFila" >



    </form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>