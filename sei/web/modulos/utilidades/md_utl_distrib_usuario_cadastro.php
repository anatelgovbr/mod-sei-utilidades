<?php

try {
    require_once dirname(__FILE__) . '/../../SEI.php';

    session_start();

    session_start();
    SessaoSEI::getInstance()->validarLink();
    PaginaSEI::getInstance()->verificarSelecao('md_utl_controle_dsmp_listar');
    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);
    $isTelaProcesso = $_GET['acao_origem'] == 'md_utl_controle_dsmp_listar' || (array_key_exists('hdnIsTelaProcesso', $_POST) && $_POST['hdnIsTelaProcesso'] == '1');
    $isChamadaPropriaTela =$_GET['acao_origem'] == 'md_utl_distrib_usuario_cadastrar' ? '1' : '0';

    $strUrlBuscarDadosCarga = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_utl_buscar_dados_carga_usuario');

    if($isTelaProcesso){
        PaginaSEI::getInstance()->setTipoPagina(PaginaSEI::$TIPO_PAGINA_SIMPLES);
    }

    $objMdUtlControleDsmpRN     = new MdUtlControleDsmpRN();
    $objMdUtlHistControleDsmpRN = new MdUtlHistControleDsmpRN();
    $objMdUtlControleDsmpDTO    = new MdUtlControleDsmpDTO();
    $objMdUtlControleDsmpDTO->retTodos();
    $objMdUtlControleDsmpDTO->retStrProtocoloProcedimentoFormatado();

    $strTitulo       = '';
    $strItensTabela  = '';
    $strGridProcesso = '';
    $idDistribuicao  = 0;
    $somaUndEsforco  = '';

    if($isTelaProcesso){
        $arrStatus              = MdUtlControleDsmpINT::retornaArrSituacoesControleDsmpCompleto();
        $objMdUtlAdmTpCtrlUndRN = new MdUtlAdmRelTpCtrlDesempUndRN();

        $idDistribuicao  = array_key_exists('id_controle_dsmp', $_GET) && $_GET['id_controle_dsmp'] != '' ? trim($_GET['id_controle_dsmp']) : trim($_POST['hdnDistribuicaoTelaProc']);
        $idsDistribuicao = array($idDistribuicao);
        $idStatus        = array_key_exists('status', $_GET) && $_GET['status'] != '' ? trim($_GET['status']) : trim($_POST['hdnSelStatus']);
        $isStrStatus     = $arrStatus[$idStatus];
        $idFila          = array_key_exists('id_fila', $_GET) && $_GET['id_fila'] != '' ? trim($_GET['id_fila']) : trim($_POST['hdnIdFila']);
        $idProcedimentoTelaProc = array_key_exists('id_procedimento', $_GET) ? trim($_GET['id_procedimento']) : trim($_POST['hdnIdProcedimentoTelaProc']);
        $strLinkCancelar =  SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_processo_listar&acao_origem=' . $_GET['acao'].'&id_procedimento='.$idProcedimentoTelaProc);
        $idTipoControle  = $objMdUtlAdmTpCtrlUndRN->getTipoControleUnidadeLogada();

    }else {
        $idsDistribuicao = array_key_exists('hdnDistribuicao', $_POST) && $_POST['hdnDistribuicao'] != '' ? json_decode($_POST['hdnDistribuicao']) : null;
        $idStatus        = array_key_exists('hdnSelStatus', $_POST) && $_POST['hdnSelStatus'] != '' ? trim($_POST['hdnSelStatus']): null;
        $isStrStatus     = array_key_exists('selStatus', $_POST) && $_POST['selStatus'] != '';
        $idFila          = array_key_exists('hdnSelFila', $_POST) && $_POST['hdnSelFila'] != '' ? trim($_POST['hdnSelFila']) : null;
        if(is_null($idFila)){
           $idFila = $_POST['hdnIdFila'];
        }

        $strLinkCancelar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_distrib_usuario_listar&acao_origem=' . $_GET['acao']);
        $idTipoControle  = isset($_GET['id_tp_controle_desmp']) ? $_GET['id_tp_controle_desmp'] : $_POST['hdnIdTipoControleUtl'];
        $idProcedimentoTelaProc = 0;
    }


    //variaveis para campos de selecao
    $strLinkUsuarioParticipante     = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_usuario_selecionar&tipo_selecao=1&id_tipo_controle_utl='. $idTipoControle .'&is_bol_distribuicao=1&id_fila='. $idFila .'&id_status='. $idStatus .'&id_object=objLupaUsuarioParticipante');
    $strLinkAjaxUsuarioParticipante = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_utl_adm_usuario_participante_auto_completar&id_fila='. $idFila .'&id_status='. $idStatus);
    $countDistribuicao              = 0;
    $arrComandos = array();



    switch($_GET['acao']) {

        case 'md_utl_distrib_usuario_cadastrar':

            $arrTriagem = array(MdUtlControleDsmpRN::$AGUARDANDO_TRIAGEM, MdUtlControleDsmpRN::$EM_TRIAGEM, MdUtlControleDsmpRN::$AGUARDANDO_CORRECAO_TRIAGEM, MdUtlControleDsmpRN::$EM_CORRECAO_TRIAGEM);
            $arrAnalise = array(MdUtlControleDsmpRN::$AGUARDANDO_ANALISE, MdUtlControleDsmpRN::$EM_ANALISE, MdUtlControleDsmpRN::$AGUARDANDO_CORRECAO_ANALISE, MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE);
            $plural    = count($idsDistribuicao) > 1 ? 's' : '';

            if(in_array($idStatus, $arrTriagem)){
                $strTitulo  = 'Distribuição de Processo'.$plural.' para Triagem';
            } else if(in_array($idStatus, $arrAnalise)){
                $strTitulo  = 'Distribuição de Processo'.$plural.' para Análise';
            } else {
                $strTitulo  = 'Distribuição de Processo'.$plural.' para Revisão';
            }


            $objMdUtlControleDsmpDTO->retTodos();
            $objMdUtlControleDsmpDTO->retStrNomeTipoProcesso();
            $objMdUtlControleDsmpDTO->setNumIdMdUtlControleDsmp($idsDistribuicao, InfraDTO::$OPER_IN);
            $objMdUtlControleDsmpDTO->retStrProtocoloProcedimentoFormatado();
            $objMdUtlControleDsmpDTO->retNumUnidadeEsforco();

            $countDistribuicao  =  $objMdUtlControleDsmpRN->contar($objMdUtlControleDsmpDTO);
            if($countDistribuicao > 0) {
                $arrObjs = $objMdUtlControleDsmpRN->listar($objMdUtlControleDsmpDTO);

                $idsProcesso = InfraArray::converterArrInfraDTO($arrObjs, 'IdProcedimento');
                $arrUltimosResponsaveis = $objMdUtlHistControleDsmpRN->getUltimosResponsaveisPorProcesso(array($idsProcesso));

                foreach ($arrObjs as $obj) {
                    $idProcedimento = $obj->getDblIdProcedimento();
                    $idControleDsmp = $obj->getNumIdMdUtlControleDsmp();
                    $numUndEsforco  = $obj->getNumUnidadeEsforco();

                    $somaUndEsforco += $numUndEsforco;

                    //Formatando Protocolo
                    $protocoloFormatado = $obj->getStrProtocoloProcedimentoFormatado();
                    $nomeProcesso = $obj->getStrNomeTipoProcesso();
                    $urlProcedimento = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem=md_utl_distrib_usuario_cadastrar&id_procedimento=' . $idProcedimento . '');
                    $hrefLinkProcesso = '<a onclick="window.open(\'' . $urlProcedimento . '\')" alt="' . $nomeProcesso . '" title="' . $nomeProcesso . '" class="ancoraPadraoAzul">' . $protocoloFormatado . '</a>';

                    //Formatando Usuário
                    $arrDadosUsuario = array_key_exists($idProcedimento, $arrUltimosResponsaveis) ? $arrUltimosResponsaveis[$idProcedimento] : array();
                    $nomeUsuario     = array_key_exists('NOME', $arrDadosUsuario) ? $arrDadosUsuario['NOME'] : '';
                    $siglaUsuario    = array_key_exists('SIGLA', $arrDadosUsuario) ? $arrDadosUsuario['SIGLA'] : '';
                    $linkUsuario     = $nomeUsuario != ''  && $siglaUsuario != '' ? '<a class="ancoraSigla" alt="' . $nomeUsuario . '" title="' . $nomeUsuario . '">' . $siglaUsuario . '</a>' : '';

                    $arrStrGridProcesso[] = array($idProcedimento, $idControleDsmp, $hrefLinkProcesso, $numUndEsforco, $linkUsuario);

                }

                $strGridProcesso = PaginaSEI::getInstance()->gerarItensTabelaDinamica($arrStrGridProcesso);
            }

            $arrComandos = array();

            //Botões de ação do topo
            $arrComandos[] = '<button type="submit" accesskey="S" id="sbmSalvar" name="sbmSalvar" class="botaoSalvar infraButton">
                                    <span class="infraTeclaAtalho">S</span>alvar</button>';

            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="cancelar()" class="infraButton">
                                <span class="infraTeclaAtalho">C</span>ancelar</button>';

            $objMdUtlAdmTpCtrlDesempDTO = new MdUtlAdmTpCtrlDesempDTO();
            $objMdUtlAdmTpCtrlDesempRN = new MdUtlAdmTpCtrlDesempRN();

            $objMdUtlAdmTpCtrlDesempDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTipoControle);
            $objMdUtlAdmTpCtrlDesempDTO->retNumIdMdUtlAdmPrmGr();
            $objMdUtlAdmTpCtrlDesempDTO->retNumCargaPadrao();
            $objMdUtlAdmTpCtrlDesempDTO->retStrStaFrequencia();
            $objMdUtlAdmTpCtrlDesempDTO->retNumPercentualTeletrabalho();
            $objMdUtlAdmTpCtrlDesempDTO->retNumInicioPeriodoParametrizado();

            $objDTO = $objMdUtlAdmTpCtrlDesempRN->consultar($objMdUtlAdmTpCtrlDesempDTO);

            $idPrmGr           = $objDTO->getNumIdMdUtlAdmPrmGr();
            $numCargaPadrão    = $objDTO->getNumCargaPadrao();
            $strStaFrequencia  = $objDTO->getStrStaFrequencia();
            $numPercentualTele = $objDTO->getNumPercentualTeletrabalho();
            $inicioPeriodoParametrizado = $objDTO->getNumInicioPeriodoParametrizado();

            $arrFrequencia = MdUtlAdmPrmGrINT::retornaArrPadraoFrequenciaDiaria();
            $strFrequencia = $arrFrequencia[$strStaFrequencia];

            $strCargaPadrao = $numCargaPadrão .' - '. $strFrequencia;


            if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['txtUsuarioParticipante'] != '') {
                $objMdUtlControleDsmpRN->incluirNovaDistribuicao($idStatus);
                $isTelaProcesso = $_POST['hdnIsTelaProcesso'] == 1;

                if($isTelaProcesso){
                    header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_procedimento='.$idProcedimentoTelaProc));
                }else {
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']));
                }

                die;
            }

            break;

        default:
            throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
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

if (0) { ?>
    <style><? }
    ?>
        <?php if($isTelaProcesso){ ?>
        div[id^="divOpcoesDistribuicao"]{
            position: absolute;
            width: 15%;
            left: 48%;
            margin-top: -17px;
        }

        #txtUsuarioParticipante{
            width: 47%;
        }

        <?php }else {?>

        div[id^="divOpcoesDistribuicao"] {
            position: absolute;
            width: 15%;
            left: 40.5%;
            margin-top: -17px;
        }

        #txtUsuarioParticipante{
            width: 40%;
        }

        <?php } ?>

        #imgExcluirProcesso{
            margin-left: 35%;
            margin-top: 2%;
        }

        #divTabelaProcessos{
            margin-top: 139px;
        }

        #divCarga {
            width: 100%;
        }

        #divCargaPadrao{
            margin-top: 1%;
            position: absolute;
        }

        #divDistribuidaMes {
            position: absolute;
            margin-top: 6.5%;
        }

        #divMais{
            position: absolute;
            margin-top: 8.5%;
            margin-left: 20%;
            width: 5%;
        }

        #spnMais{
            font-size: 1.5em;
        }

        #divSelecionadaDist{
            margin-top: 6.5%;
            margin-left: 25%;
            position: absolute;
        }

        #divIgual{
            margin-top: 8.5%;
            margin-left: 48%;
            width: 5%;
            position: absolute
        }

        #spnIgual{
            font-size: 1.5em;
        }

        #divTotalUniEsforco{
            margin-top: 6.5%;
            margin-left: 53%;
            position: absolute;
        }

        <?
        if (0) { ?></style><?
} ?>

<?php
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
require_once 'md_utl_geral_js.php';
if(0){?><script><?}?>

    var idParam           = '<?php echo $idPrmGr ?>';
    var numCargaPadrao    = '<?php echo $numCargaPadrão ?>';
    var numPercentualTele = '<?php echo $numPercentualTele ?>';
    var staFrequencia     = '<?php echo $strStaFrequencia?>';
    var strStaFrequencia  = '<?php echo $strFrequencia?>';
    var selecionadaDist   = '<?php echo $somaUndEsforco?>';
    var inicioPeriodo     =  '<?php echo $inicioPeriodoParametrizado; ?>';

    function inicializar() {
        var count = "<?=$countDistribuicao?>";
        verificarConcorrencia(count);

        if (count > 0) {
            carregarComponenteUsuarioParticipante();
            iniciarGridDinamicaDistribuicao();

            $('input').on('drop', function () {
                return false;
            });
        }
    }

    function verificarConcorrencia (count){

        if(count == 0){
            alert('Os registros indicados não possuem o status informado! Favor selecionar novamente');
            cancelar();
        }
    }

    function iniciarGridDinamicaDistribuicao(){
        objTabelaDinamicaProcesso = new infraTabelaDinamica('tbProcesso', 'hdnTbProcesso', false, false);
        objTabelaDinamicaProcesso.gerarEfeitoTabela = true;

        var hdnLista    = '';
        var arrhdnLista = '';


        if (objTabelaDinamicaProcesso.hdn.value != '') {
            objTabelaDinamicaProcesso.recarregar();

            //acoes
            hdnLista = objTabelaDinamicaProcesso.hdn.value;
            arrhdnLista = hdnLista.split('¥');

            //array
            if (arrhdnLista.length > 0) {
                for (i = 0; i < arrhdnLista.length; i++) {
                    var hdnListaTela = arrhdnLista[i].split('±');
                    var btnDistribuicao = "<a onclick='objTabelaDinamicaProcesso.removerProcesso("+hdnListaTela[0]+"," +hdnListaTela[3] +")'><img title='Remover Seleção do Processo' alt='Remover Seleção do Processo' src=\"modulos/utilidades/imagens/removerSelecao.png\" class='infraImg'/></a><img src=\"/infra_css/imagens/espaco.gif\" class=\"\" border=\"0\">";

                    objTabelaDinamicaProcesso.adicionarAcoes(hdnListaTela[0], btnDistribuicao);
                }
            }
        }

        objTabelaDinamicaProcesso.removerProcesso = function (idProcesso, undEsforco) {
            var row = objTabelaDinamicaProcesso.procuraLinha(idProcesso);
            objTabelaDinamicaProcesso.removerLinha(row);

            controlarExibicaoCargaDistribuida(undEsforco);

            if (objTabelaDinamicaProcesso.tbl.rows.length==1){
                document.getElementById('divTabelaProcessos').style.display = 'none';
            }
        };

        objTabelaDinamicaProcesso.procuraLinha = function (idProcesso) {
            var linha;

            for (i = 1; i < document.getElementById('tbProcesso').rows.length; i++) {
                linha = document.getElementById('tbProcesso').rows[i];
                var valorLinha = $.trim(linha.cells[0].innerText);

                if (valorLinha == idProcesso) {
                    return i;
                }

            }
            return null;
        };
    }

    function controlarExibicaoCargaDistribuida(unidadeEsforco){

        var cargaSelecDistri = $.trim(document.getElementById('txtSelecionadaDist').value);
        var totalUndEsforco  = $.trim(document.getElementById('txtTotalUniEsforco').value);

        cargaSelecDistri = parseInt(cargaSelecDistri);
        totalUndEsforco  = parseInt(totalUndEsforco);

        var somaUndEsforco  = cargaSelecDistri - unidadeEsforco;
        var totalUniEsforco = totalUndEsforco - unidadeEsforco;
        document.getElementById('txtSelecionadaDist').value = somaUndEsforco;
        document.getElementById('txtTotalUniEsforco').value = totalUniEsforco;
    }

    function realizarDistribuicao(){
        var selParticipante = document.getElementById('txtUsuarioParticipante').value;
        var tdCount = document.getElementsByClassName('infraTd').length;

        if(selParticipante == ''){
            alert('Preencha o Usuário Participante!');
            return false;
        }

        if (tdCount == 0){
            alert('Selecione ao menos um processo para realizar a Distribuição!');
            return false;
        }

        document.getElementById('hdnSelParticipante').value = selParticipante;
        bloquearBotaoSalvar();
        return true;
    }

    function carregarComponenteUsuarioParticipante(){
        objLupaUsuarioParticipante = new infraLupaText('txtUsuarioParticipante','hdnIdUsuarioParticipanteLupa','<?=$strLinkUsuarioParticipante?>');

        objAutoCompletarUsuarioParticipante = new infraAjaxAutoCompletar('hdnIdUsuarioParticipanteLupa','txtUsuarioParticipante','<?=$strLinkAjaxUsuarioParticipante?>');
        objAutoCompletarUsuarioParticipante.limparCampo = true;
        objAutoCompletarUsuarioParticipante.tamanhoMinimo = 3;
        objAutoCompletarUsuarioParticipante.prepararExecucao = function(){
            return 'palavras_pesquisa='+document.getElementById('txtUsuarioParticipante').value;
        };

        objAutoCompletarUsuarioParticipante.processarResultado = function(id,descricao,complemento){

            if (id!=''){
                document.getElementById('hdnIdUsuarioParticipanteLupa').value = id;
                document.getElementById('txtUsuarioParticipante').value = descricao;
                //chamar a função responsavel por carregar os campos do participante - Ajax
                realizarAjaxDadosCarga();
            }else{
                limparCampos();
            }
        }

        objLupaUsuarioParticipante.finalizarSelecao = function(){
            objAutoCompletarUsuarioParticipante.selecionar(document.getElementById('hdnIdUsuarioParticipanteLupa').value,document.getElementById('txtUsuarioParticipante').value);
            //chamar a função responsavel por carregar os campos do participante - Lupa
            realizarAjaxDadosCarga();
        }

        objLupaUsuarioParticipante.processarRemocao = function(){
            limparCampos();
            return true;
        }

    }

    function cancelar() {
        location.href = "<?= $strLinkCancelar ?>";
    }

    function limparCampos() {
        document.getElementById('txtTotalUniEsforco').value = '<?=$somaUndEsforco?>';
        document.getElementById('txtDistribuida').value = '';
        document.getElementById('txtCargaPadrao').value = '<?=$strCargaPadrao?>';
    }

    function realizarAjaxDadosCarga(){


        var params = {
            idUsuarioParticipante: document.getElementById('hdnIdUsuarioParticipanteLupa').value,
            idParam: idParam,
            numCargaPadrao : numCargaPadrao,
            numPercentualTele : numPercentualTele,
            staFrequencia : staFrequencia,
            inicioPeriodo : inicioPeriodo,
            idTipoControle : <?php echo $idTipoControle ?>
        };

        $.ajax({
            url: '<?=$strUrlBuscarDadosCarga?>',
            type: 'POST',
            data: params,
            dataType: 'XML',
            success: function (r) {

                //Carga Padrão
                var valorCarga = $(r).find('ValorCarga').text();
                var cargaPadrao = valorCarga + ' - ' + strStaFrequencia;
                document.getElementById('txtCargaPadrao').value = cargaPadrao;

                var valorUndEs = $(r).find('ValorUndEs').text();
                var cargaDisti = valorUndEs;
                document.getElementById('txtDistribuida').value = cargaDisti;

                totalUniesforco = parseInt(cargaDisti) + parseInt(selecionadaDist);
                document.getElementById('txtTotalUniEsforco').value = totalUniesforco;

            },
            error: function (e) {
                console.error('Erro ao buscar URL de Tipo de Controle: ' + e.responseText);
            }
        });

    }

    <?if(0){?></script><?}

PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
    <form id="frmDistribuicao"  onsubmit="return realizarDistribuicao();" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">

        <?
        //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
        PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);

        PaginaSEI::getInstance()->abrirAreaDados('auto');

        //PaginaSEI::getInstance()->montarAreaValidacao();
        ?>
        <div>


            <div id="divDistribuicao">
                <label id="lblDistribuicao" for="txtUsuarioParticipante" accesskey="" class="infraLabelObrigatorio">
                    Usuário Participante: <img align="top" style="height:16px; width:16px;" id="imgAjuda" src="/infra_css/imagens/ajuda.gif" name="ajuda" onmouseover="return infraTooltipMostrar('Selecionar usuário participante para Distribuição de Processo.');" onmouseout="return infraTooltipOcultar();" alt="Ajuda" class="infraImg">
                </label>
                <div class="clear"></div>

                <input type="text" id="txtUsuarioParticipante" name="txtUsuarioParticipante" class="infraText" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
                <div id="divOpcoesDistribuicao">
                    <img id="imgLupaDistribuicao" onclick="objLupaUsuarioParticipante.selecionar(700,500);" src="/infra_css/imagens/lupa.gif" alt="Selecionar Usuário Participante" title="Selecionar Usuário Participante" class="infraImg">
                    <img id="imgExcluirDistribuicao" onclick="objLupaUsuarioParticipante.remover();" src="/infra_css/imagens/remover.gif" alt="Remover Usuário Participante" title="Remover Usuário Participante" class="infraImg">
                </div>
            </div>


                    <div id="divCargaPadrao">
                        <label id="lblCargaPadrao" for="txtCargaPadrao" class="infraLabelOpcional" >Carga Padrão:</label>
                        <br>
                        <input type="text" id="txtCargaPadrao" name="txtCargaPadrao" class="infraText" style="width: 90%" value="<?=$strCargaPadrao?>" disabled/>
                    </div>

            <div id="divCarga">
                <div id="divDistribuidaMes">
                    <label id="lblDistribuida" for="txtDistribuida" class="infraLabelOpcional" >Carga já Distribuída no Período:</label>
                    <br>
                    <input type="text" id="txtDistribuida" name="txtDistribuida" class="infraText" style="width: 77%" disabled/>
                </div>
                <div id="divMais">
                    <span id="spnMais">+</span>
                </div>
                <div id="divSelecionadaDist">
                    <label id="lblSelecionadaDist" for="txtSelecionadaDist" class="infraLabelOpcional" >Carga selecionada para Distribuição:</label>
                    <br>
                    <input type="text" id="txtSelecionadaDist" name="txtSelecionadaDist" class="infraText" style="width: 68%" value="<?=$somaUndEsforco?>" disabled/>
                </div>
                <div id="divIgual">
                    <span id="spnIgual">=</span>
                </div>

                    <div id="divTotalUniEsforco">
                        <label id="lblTotalUniEsforco" for="txtTotalUniEsforco" class="infraLabelOpcional" >Total Unidade de Esforço:</label>
                        <br>
                        <input type="text" id="txtTotalUniEsforco" name="txtTotalUniEsforco" class="infraText" style="width: 90%" value="<?=$somaUndEsforco?>" disabled/>
                    </div>
            </div>




<?php
$width = $isTelaProcesso ? '85%' : '80%';
if($countDistribuicao > 0){ ?>
            <div id="divTabelaProcessos">

            <table width="<?=$width?>" class="infraTable" summary="Demanda" id="tbProcesso">
                <caption class="infraCaption"> <?= PaginaSEI::getInstance()->gerarCaptionTabela($strTitulo, 0) ?> </caption>
                <tr>
                    <th class="infraTh" style="display: none;">ID do Processo</th>
                    <th class="infraTh" style="display: none;">ID do Controle do Dsmp</th>
                    <th class="infraTh" align="center">Processo</th>
                    <th class="infraTh" align="center">Unidade de Esforço</th>
                    <th class="infraTh" align="center">Último Responsável</th>
                    <th class="infraTh" align="center">Ações</th>
                </tr>

            </table>
            </div>
            <?php } ?>



        </div>
        <?

        PaginaSEI::getInstance()->fecharAreaDados();
        PaginaSEI::getInstance()->montarAreaDebug();
        //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
        ?>

        <!--Hidden de Controle da Tabela -->
        <input type="hidden" name="hdnTbProcesso" id="hdnTbProcesso" value="<?php echo $strGridProcesso?>"/>
        <input type="hidden" id="btnDistribuir"   name="btnDistribuir"  />
        <input type="hidden" id="hdnDistribuicao" name="hdnDistribuicao" value=<?php echo json_encode($idsDistribuicao); ?>>
        <input type="hidden" id="hdnIdProcedimento"    name="hdnIdProcedimento"    value="<?=$idProcedimento?>">
        <input type="hidden" id="hdnUsuarioParticipanteLupa" name="hdnUsuarioParticipanteLupa" value="<?=$_POST['hdnUsuarioParticipanteLupa']?>" />
        <input type="hidden" id="hdnIdUsuarioParticipanteLupa" name="hdnIdUsuarioParticipanteLupa" value=""/>
        <input type="hidden" id="hdnIdTipoControleUtl" name="hdnIdTipoControleUtl" value="<?php echo $idTipoControle; ?>"/>
        <input type="hidden" id="hdnIdFila" name="hdnIdFila" value="<?php echo $idFila; ?>"/>
        <input type="hidden" id="hdnSelStatus" name="hdnSelStatus" value="<?php echo $idStatus;?>"/>

        <input type="hidden" id="hdnSelParticipante" name="hdnSelParticipante">

        <!-- Controle da Tela de Processo -->
        <input type="hidden" id="hdnIsTelaProcesso" name="hdnIsTelaProcesso" value="<?php echo $isTelaProcesso ? '1' : '0'?>"/>
        <input type="hidden" id="hdnIdProcedimentoTelaProc" name="hdnIdProcedimentoTelaProc" value="<?php echo $idProcedimentoTelaProc ?>"/>
        <input type="hidden" id="hdnDistribuicaoTelaProc" name="hdnDistribuicaoTelaProc" value="<?php echo $idDistribuicao ?>"/>


    </form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>