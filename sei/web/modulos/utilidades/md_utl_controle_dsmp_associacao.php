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
    $objMdUtlAdmTpCtrlUndRN = new MdUtlAdmRelTpCtrlDesempUndRN();

    PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);

    $strParametros = '';
    $strUrlChangeTpCtrl = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_controle_dsmp_associar&acao_origem='.$_GET['acao_origem']);
    $strIdsTpCtrl = '';
    $idTpControle = '0'; 

    if ( isset($_GET['id_tp_controle_desmp']) && !isset($_GET['is_detalhamento'] ) ) {
        $arrAux = explode(',',$_GET['id_tp_controle_desmp']);
        if(count($arrAux) == 1 ) $idTpControle = $arrAux[0];
    } else if( isset( $_POST['hdnIdTipoControleUtl']) ) {
        $idTpControle = $_POST['hdnIdTipoControleUtl'];
    }

    switch($_GET['acao']) {
        case 'md_utl_controle_dsmp_associar':
            if(isset($_GET['is_detalhamento']) && $_GET['is_detalhamento'] == '1'){
                $arrObjTpControle   = $objMdUtlAdmTpCtrlUndRN->getArrayTipoControleUnidadeLogada();
                $arrListaTpControle = array();

                if (count($arrObjTpControle) > 0 ){
                    foreach ($arrObjTpControle as $k => $v) {
                        array_push($arrListaTpControle,$v->getNumIdMdUtlAdmTpCtrlDesemp());
                    }
                }
               
                $objMdUtlProcedimentoDTO    = new MdUtlProcedimentoDTO();
                $objMdUtlAdmRelPrmGrProcDTO = new MdUtlAdmRelPrmGrProcDTO();                
                $objMdUtlAdmPrmGrDTO        = new MdUtlAdmPrmGrDTO();

                $objMdUtlControleDsmpRN    = new MdUtlControleDsmpRN();
                $objMdUtlControleDsmpINT   = new MdUtlControleDsmpINT();
                $objMdUtlAdmRelPrmGrProcRN = new MdUtlAdmRelPrmGrProcRN();
                $objMdUtlAdmPrmGrRN        = new MdUtlAdmPrmGrRN();

                // Tipos de controle da unidade logada
                $arrObjTpCtrlUnid = $objMdUtlAdmTpCtrlUndRN->getArrayTipoControleUnidadeLogada();
                $arrTpCtrlUnid = array();
                if(!empty($arrObjTpCtrlUnid)){
                    $arrTpCtrlUnid = InfraArray::converterArrInfraDTO($arrObjTpCtrlUnid,'IdMdUtlAdmTpCtrlDesemp');
                }

                // Tipo de Procedimento do Processo
                $objMdUtlProcedimentoDTO->setDblIdProcedimento($_GET['id_procedimento']);
                $objMdUtlProcedimentoDTO->retNumIdTipoProcedimento();
                $arrTpProcedimento = InfraArray::converterArrInfraDTO($objMdUtlControleDsmpRN->listarProcessos($objMdUtlProcedimentoDTO), 'IdTipoProcedimento');               

                // Tipos de controle do Tipo de Procedimento consultado acima
                $objMdUtlAdmRelPrmGrProcDTO->setNumIdTipoProcedimento($arrTpProcedimento[0]);
                $objMdUtlAdmRelPrmGrProcDTO->retNumIdMdUtlAdmTpCtrlDesemp();
                $objMdUtlAdmRelPrmGrProcDTO->retNumIdMdUtlAdmParamGr();               
               
                $arrObjs = $objMdUtlAdmRelPrmGrProcRN->listar($objMdUtlAdmRelPrmGrProcDTO);

                // monta relacao tipo de controle x parametrizacao geral
                $arrTpCtrlPrmGr = array();
                foreach ($arrObjs as $k => $v) {
                    if( in_array( $v->getNumIdMdUtlAdmTpCtrlDesemp() , $arrTpCtrlUnid ) ){
                        $arrTpCtrlPrmGr[$v->getNumIdMdUtlAdmTpCtrlDesemp()] = $v->getNumIdMdUtlAdmParamGr();
                    }                    
                }

                // Tipos de controle que o usuario é membro ou gestor tendo como parametro de validacao o array $arrTpCtrlPrmGr
                $objMdUtlAdmTpCtrlUsuRN = new MdUtlAdmRelTpCtrlDesempUsuRN();
                $objMdUtlAdmPrmGrUsuRN  = new MdUtlAdmPrmGrUsuRN();

                $isGestor = $objMdUtlAdmTpCtrlUsuRN->usuarioLogadoIsGestorTpControle();

                $isGestor = $isGestor ?: array();

                $qtdAdd = 0;

                foreach ($arrTpCtrlPrmGr as $k => $v) {
                    $objMdUtlAdmPrmGrUsuDTO = new MdUtlAdmPrmGrUsuDTO();
                    $objMdUtlAdmPrmGrUsuDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
                    $objMdUtlAdmPrmGrUsuDTO->setNumIdMdUtlAdmPrmGr( $v );
        
                    $isMembroParticipante = $objMdUtlAdmPrmGrUsuRN->contar($objMdUtlAdmPrmGrUsuDTO) > 0;
        
                    if ( $isMembroParticipante || in_array($k , $isGestor) ) {
                        $strIdsTpCtrl .= $k . ',';
                        $qtdAdd++;
                        continue;
                    }else{
                        unset( $arrTpCtrlPrmGr[$k] );
                    }
                }              

                $strIdsTpCtrl = substr($strIdsTpCtrl, 0, -1);
               
                // caso não tenha o vinculo do procedimento com o controle desempenho, usa os tipos de controles da relacao usuario x unidade
                if(!empty($strIdsTpCtrl)){
                    if($qtdAdd == 1){
                        $idTpControle = (int) $strIdsTpCtrl;
                    }
                }
            }else{
                $strIdsTpCtrl = isset($_GET['id_tp_controle_desmp']) ? $_GET['id_tp_controle_desmp'] : $_POST['hdnIdsTipoCtrlCombo'];
            }
           
            $strTitulo = 'Associar Processo à Fila';
            $idProcedimento   = isset($_GET['id_procedimento']) ? $_GET['id_procedimento'] : $_POST['hdnIdProcedimento'];
            $isDetalhamento   = isset($_GET['is_detalhamento']) ? $_GET['is_detalhamento'] : $_POST['hdnDetalhamento'];
            $selFila = '';

            //Retona lista de tipo de controle
            $objMdUtlAdmTpCtrlDsmpRN = new MdUtlAdmTpCtrlDesempRN();
            $objMdUtlAdmTpCtrlDsmpDTO = new MdUtlAdmTpCtrlDesempDTO();

            $objMdUtlAdmTpCtrlDsmpDTO->setNumIdMdUtlAdmTpCtrlDesemp( explode(',' , $strIdsTpCtrl) , InfraDTO::$OPER_IN );
            $objMdUtlAdmTpCtrlDsmpDTO->retNumIdMdUtlAdmTpCtrlDesemp();
            $objMdUtlAdmTpCtrlDsmpDTO->retStrNome();

            $arrListTpCtrlDsmp = $objMdUtlAdmTpCtrlDsmpRN->listar($objMdUtlAdmTpCtrlDsmpDTO);
            
            if(isset($_POST['hdnAssociarFila']) && $_POST['hdnAssociarFila'] == 'ok'){

                $objHistoricoRN         = new MdUtlHistControleDsmpRN();
                $objMdUtlControleDsmpRN = new MdUtlControleDsmpRN();
                $arrDados = PaginaSEI::getInstance()->getArrItensTabelaDinamica($_POST['hdnTbAssociarFila']);
                $count = count($arrDados);

                if($_POST['hdnValueFila'] == ''){

                    $funcFirstElement = function ($value) {
                        reset($value);
                        return current($value);
                    };


                    $arrRetorno = $objMdUtlControleDsmpRN->controlarHistorico($arrDados, 'S', 'N', true, true, 'associar_fila');

                    $arrIdProcedimento = array_map($funcFirstElement, $arrDados);

                    $objHistoricoRN->removerFilaControleDsmp(array($arrIdProcedimento, $arrRetorno));
                    $objRegrasGerais = new MdUtlRegrasGeraisRN();
                    $objRegrasGerais->controlarAtribuicaoGrupo($arrIdProcedimento);

                    $_SESSION['ID_PROCEDIMENTO_FILA_ASSOCIAR'] = $arrIdProcedimento;

                }else{
                    $objRN = new MdUtlControleDsmpRN();
                    $objRN->associarFila();
                }

                // monta o link do direcionamento quando a acao veio da tela de detalhamento do processo
                $arrHref = [
                    "acao=procedimento_visualizar",
                    "acao_origem={$_GET['acao']}",
                    "montar_visualizacao=0",
                    "id_procedimento=$idProcedimento"
                ];

	            $linkPosSubmitDetalhamento = SessaoSEI::getInstance()->assinarLink("controlador.php?".implode('&',$arrHref));

            }  
            break;

        default:
            throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
    }

    $arrComandos = array();

    //Botões de ação do topo
    $arrComandos[] = '<button type="button" accesskey="S" id="sbmAssociarFila" onclick="submeterAssociarFila();" name="sbmAssociarFila" class="infraButton">
                                    <span class="infraTeclaAtalho">S</span>alvar</button>';

    $arrComandos[] = '<button type="button" accesskey="c" id="btnFechar" onclick="closeModal();" class="infraButton">
                                    Fe<span class="infraTeclaAtalho">c</span>har</button>';

    if($idTpControle != '0'){
        #getFilasTipoControle($idTpControle);
        $arrObjsFilaDTO  = $objFilaRN->getFilasVinculadosUsuario( $idTpControle );
        $selFila         = MdUtlAdmFilaINT::montarSelectFilas($selFila, $arrObjsFilaDTO, null, true);
    }


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
PaginaSEI::getInstance()->fecharStyle();

PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
PaginaSEI::getInstance()->fecharJavaScript();

PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
    <form id="frmAssociarFila" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">

        <?php
            //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
            PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
            PaginaSEI::getInstance()->abrirAreaDados('auto');
            //PaginaSEI::getInstance()->montarAreaValidacao();
        ?>

        <div class="row mb-3">
            <div class="col-5" id="divTpCtrl">
                <label for="lblTpCtrl" class="infraLabelOpcional">Tipo de Controle:</label>
                <select name="selTpCtrl" id="selTpCtrl" class="infraSelect form-control" onchange="SelecionaTpCtrl()">
                    <option value="" <?= $idTpControle == '0' ? 'selected' : '' ?> ></option>
                    <?php foreach ($arrListTpCtrlDsmp as $k => $v) { ?>                   
                        <?php if( (int) $idTpControle === (int) $v->getNumIdMdUtlAdmTpCtrlDesemp() ){ ?>
                            <option value="<?= $v->getNumIdMdUtlAdmTpCtrlDesemp() ?>" selected ><?= $v->getStrNome() ?></option>
                        <?php } else { ?>
                            <option value="<?= $v->getNumIdMdUtlAdmTpCtrlDesemp() ?>" ><?= $v->getStrNome() ?></option>
                        <?php } ?>        
                    <?php } ?>
                </select>
            </div>
        </div>
        
        <div class="row mb-3">
            <div class="col-5" id="divFila">
                <label id="lblFila" for="selFila" accesskey="" class="infraLabelOpcional">Fila:</label>
                <select id="selFila"  name="selFila" class="infraSelect form-control"
                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                    <?=$selFila ?>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <table class="infraTable table" summary="Associar à Fila" id="tbAssociarFila">
                    <tr>
                        <th class="infraTh" align="center" width="" style="display: none" >IdVinculo</th>
                        <th class="infraTh" width="">Processo</th>                
                        <th class="infraTh" width="">Última Fila Registrada</th>
                        <th class="infraTh" width="">Fila Atual</th>
                        <th class="infraTh" style="display: none">Status</th>
                    </tr>
                </table>
            </div>
        </div>

        <?php
            PaginaSEI::getInstance()->fecharAreaDados();
            //PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
            //PaginaSEI::getInstance()->montarAreaDebug();
            //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
        ?>

        <input type="hidden" id="hdnTbAssociarFila"     name="hdnTbAssociarFila"  />
        <input type="hidden" id="sbmAssociarFila"       name="sbmAssociarFila"      value="" />
        <input type="hidden" id="hdnAssociarFila"       name="hdnAssociarFila"      value="" />
        <input type="hidden" id="hdnIdTipoControleUtl"  name="hdnIdTipoControleUtl" value="<?= $idTpControle ?>" />
        <input type="hidden" id="hdnIdsTipoCtrlCombo"   name="hdnIdsTipoCtrlCombo"  value="<?= $strIdsTpCtrl ?>" />
        <input type="hidden" id="hdnIdProcedimento"     name="hdnIdProcedimento"    value="<?= $idProcedimento ?>" />
        <input type="hidden" id="hdnSelFila"            name="hdnSelFila" />
        <input type="hidden" id="hdnValueFila"          name="hdnValueFila" />
        <input type="hidden" id="hdnDetalhamento"       name="hdnDetalhamento" value="<?= $isDetalhamento ?>" />

    </form>

<?php require_once 'md_utl_geral_js.php'; ?>

<script>
    var msgNenhumaFila = new Array();
    var msgPadrao84 = '<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_84)?>';
    var msgPadrao85 ='<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_85)?>';

    function inicializar() {
	    <?php if ( isset($_POST['hdnAssociarFila'] ) && $_POST['hdnAssociarFila'] == 'ok' ): ?>
            // veio da tela de detalhamento do processo
            if ( document.querySelector('#hdnDetalhamento').value == 1 ) {
                window.parent.document.querySelector('#ifrArvore').src = '<?= $linkPosSubmitDetalhamento ?>';

                //window.parent.document.location.reload(); // 4.0.12
                window.parent.document.querySelector('#ifrConteudoVisualizacao').contentWindow.document.querySelector('#btnDtlhProcesso').click(); // 4.1.*

            } else { // veio do menu Associar Processo a Fila
                window.parent.document.querySelector('#btnPesquisar').click();
            }
            closeModal();
	    <?php endif; ?>

        if( document.getElementById('selTpCtrl').length == 1 ){
            alert('Não existe Tipo de Controle vinculado ao Tipo de Processo');
            window.close();
        }
        
        iniciarTbDinamica();
        var arrIdsProcedimento = new Array();
        var isDetalhamento = "<?=$isDetalhamento?>";
        if(isDetalhamento != '1') {
            arrIdsProcedimento = retornaIdsProcedimentosFormatados();
        }
        buscarUltimasFilas(arrIdsProcedimento, isDetalhamento);
    }

    function submeterAssociarFila(){

        if(document.getElementById('selTpCtrl').value == '' ){
            alert('Não é permitido salvar até selecionar o Tipo de Controle e Fila.');
            return false;
        }

        if(validarNenhumaFila()) {
            var selectNomeFila = document.getElementById('selFila');
            var nomeFila = selectNomeFila.options[selectNomeFila.selectedIndex].innerText;
            document.getElementById('hdnAssociarFila').value = 'ok';
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

        var linhas = $( window.parent.document.querySelectorAll('.infraTrMarcada') );

        for (var i = linhas.length - 1; i >=0; i--) {
            var objLinha         = linhas[i];
            var idProcedimento   = $(objLinha).find('.tdIdProcesso').text();
            var strProcesso      = $(objLinha).find('.tdNomeProcesso').text();
            var filaAtual        = $(objLinha).find('.tdFilaProcesso').text();
            var nmTpCtrlAtual    = $(objLinha).find('.tdTpCtrl').text();
            var nomeCampoUltFila = 'UltimaFila' + idProcedimento;
            var ultimaFila       = $(arrUltimasFilas).find(nomeCampoUltFila).text();
            var TpControle       = $(arrUltimasFilas).find('TipoControle' + idProcedimento).text();
            var status           = $(objLinha).find('.tdIdStatusAtual').text();
            var linhaAtual       = [idProcedimento, strProcesso, TpControle == '' ? '' : TpControle +' - FILA: '+ ultimaFila, filaAtual == '' ? '' : nmTpCtrlAtual +' - FILA: '+ filaAtual, status];

            if(filaAtual == '') {
                msgNenhumaFila.push(strProcesso);
            }

            objTabelaDinamicaAssociarFila.adicionar(linhaAtual);
        }
    }

    function montarTelaDetalhamento(arrUltimasFilas, idProcedimento){
        var objLinha         = arrUltimasFilas[0];
        var idProcedimento   = idProcedimento;
        
        // var objVisualizacao  = window.parent.document.querySelector('#ifrVisualizacao'); // 4.0.12
        var objVisualizacao  = window.parent.document.querySelector('#ifrConteudoVisualizacao').contentWindow.document.querySelector('#ifrVisualizacao'); // 4.1.*
        var strProcesso      = objVisualizacao.contentWindow.document.querySelector('#hdnProtocoloFormatado').value;
        var filaAtual        = objVisualizacao.contentWindow.document.querySelector('#hdnNomeFilaAtual').value;
        var status           = objVisualizacao.contentWindow.document.querySelector('#hdnIdStatusAtual').value;
        var nmTpCtrlAtual    = objVisualizacao.contentWindow.document.querySelector('#hdnNomeTpCtrlAtual').value;

        var nomeCampoUltFila = 'UltimaFila' + idProcedimento;
        var TpControle       = $(arrUltimasFilas).find('TipoControle' + idProcedimento ).text();
        var ultimaFila       = $(arrUltimasFilas).find(nomeCampoUltFila).text();
        var linhaAtual = [idProcedimento, strProcesso, TpControle == '' ? '' : TpControle +' - FILA: '+ ultimaFila, filaAtual == '' ? '' : nmTpCtrlAtual +' - FILA: '+ filaAtual, status];

        if(filaAtual == '') {
            msgNenhumaFila.push(strProcesso);
        }

        objTabelaDinamicaAssociarFila.adicionar(linhaAtual);
    }


    function retornaIdsProcedimentosFormatados(){
        var linhas = $( window.parent.document.querySelectorAll('.infraTrMarcada') );
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

    function SelecionaTpCtrl(){        
        var tpCtrl = $('#selTpCtrl').val();
        if( tpCtrl != "" ){
            infraExibirAviso(false);
            $('#hdnIdTipoControleUtl').val( tpCtrl );
            $("#frmAssociarFila").attr("action","<?= $strUrlChangeTpCtrl ?>");
            $("#frmAssociarFila").submit();
        }
    }    

</script>

<?php
    PaginaSEI::getInstance()->fecharBody();
    PaginaSEI::getInstance()->fecharHtml();
?>