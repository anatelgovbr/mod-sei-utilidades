<?php

try {
    require_once dirname(__FILE__) . '/../../SEI.php';

    session_start();

    SessaoSEI::getInstance()->validarLink();
    PaginaSEI::getInstance()->verificarSelecao('md_utl_controle_dsmp_listar');
    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);
    $isTelaProcesso = $_GET['acao_origem'] == 'md_utl_controle_dsmp_listar' || (array_key_exists('hdnIsTelaProcesso', $_POST) && $_POST['hdnIsTelaProcesso'] == '1');
    $isChamadaPropriaTela = $_GET['acao_origem'] == 'md_utl_distrib_usuario_cadastrar' ? '1' : '0';

    $strUrlBuscarDadosCarga = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_utl_buscar_dados_carga_usuario');
    $strUrlBuscarDadosRegimeTrabalho = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_utl_buscar_dados_regime_trabalho');

    if ($isTelaProcesso) {
        PaginaSEI::getInstance()->setTipoPagina(PaginaSEI::$TIPO_PAGINA_SIMPLES);
    }


    $objMdUtlControleDsmpRN = new MdUtlControleDsmpRN();
    $objMdUtlHistControleDsmpRN = new MdUtlHistControleDsmpRN();
    $objMdUtlControleDsmpDTO = new MdUtlControleDsmpDTO();
    $objMdUtlControleDsmpDTO->retTodos();
    $objMdUtlControleDsmpDTO->retStrProtocoloProcedimentoFormatado();

    $strTitulo = '';
    $strItensTabela = '';
    $strGridProcesso = '';
    $idDistribuicao = 0;
    $somaTempoExecucao = '';
    $exibirColAcao = true;

    if ($isTelaProcesso) {
        $arrStatus = MdUtlControleDsmpINT::retornaArrSituacoesControleDsmpCompleto();
        $objMdUtlAdmTpCtrlUndRN = new MdUtlAdmRelTpCtrlDesempUndRN();
        $objMdUtlControleDsmpRN    = new MdUtlControleDsmpRN();

        $idDistribuicao = array_key_exists('id_controle_dsmp', $_GET) && $_GET['id_controle_dsmp'] != '' ? trim($_GET['id_controle_dsmp']) : trim($_POST['hdnDistribuicaoTelaProc']);
        $idsDistribuicao = array($idDistribuicao);
        $idStatus = array_key_exists('status', $_GET) && $_GET['status'] != '' ? trim($_GET['status']) : trim($_POST['hdnSelStatus']);
        $isStrStatus = $arrStatus[$idStatus];
        $idFila = array_key_exists('id_fila', $_GET) && $_GET['id_fila'] != '' ? trim($_GET['id_fila']) : trim($_POST['hdnIdFila']);
        $idProcedimentoTelaProc = array_key_exists('id_procedimento', $_GET) ? trim($_GET['id_procedimento']) : trim($_POST['hdnIdProcedimentoTelaProc']);
        $strLinkCancelar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_processo_listar&acao_origem=' . $_GET['acao'] . '&id_procedimento=' . $idProcedimentoTelaProc);
        $objControleDsmpDTO = $objMdUtlControleDsmpRN->getObjControleDsmpAtivo($idProcedimentoTelaProc);
        $idTipoControle = $objControleDsmpDTO->getNumIdMdUtlAdmTpCtrlDesemp();
        $exibirColAcao = false;

    } else {
        $idsDistribuicao = array_key_exists('hdnDistribuicao', $_POST) && $_POST['hdnDistribuicao'] != '' ? json_decode($_POST['hdnDistribuicao']) : null;
        $idStatus = array_key_exists('hdnSelStatus', $_POST) && $_POST['hdnSelStatus'] != '' ? trim($_POST['hdnSelStatus']) : null;
        $isStrStatus = array_key_exists('selStatus', $_POST) && $_POST['selStatus'] != '';
        $idFila = array_key_exists('hdnSelFila', $_POST) && $_POST['hdnSelFila'] != '' ? trim($_POST['hdnSelFila']) : null;
        if (is_null($idFila)) {
            $idFila = $_POST['hdnIdFila'];
        }

        $strLinkCancelar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_distrib_usuario_listar&acao_origem=' . $_GET['acao']);
        $idTipoControle = isset($_GET['id_tp_controle_desmp']) ? $_GET['id_tp_controle_desmp'] : $_POST['hdnIdTipoControleUtl'];
        $idProcedimentoTelaProc = 0;
        $exibirColAcao = ( isset($_GET['btn_acao']) && $_GET['btn_acao'] == 0 ) ? false : true;
    }

    //variaveis para campos de selecao
    $strLinkUsuarioParticipante = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_usuario_selecionar&tipo_selecao=1&id_tipo_controle_utl=' . $idTipoControle . '&is_bol_distribuicao=1&id_fila=' . $idFila . '&id_status=' . $idStatus . '&id_object=objLupaUsuarioParticipante');
    $strLinkMontarLinkUsuarioParticipante = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=montar_link_md_utl_adm_usuario_selecionar&tipo_selecao=1');
    $strLinkAjaxUsuarioParticipante = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_utl_adm_usuario_participante_auto_completar&id_fila=' . $idFila . '&id_status=' . $idStatus);
    $countDistribuicao = 0;
    $arrComandos = array();


    switch ($_GET['acao']) {

        case 'md_utl_distrib_usuario_cadastrar':

            $arrTriagem = array(MdUtlControleDsmpRN::$AGUARDANDO_TRIAGEM, MdUtlControleDsmpRN::$EM_TRIAGEM, MdUtlControleDsmpRN::$AGUARDANDO_CORRECAO_TRIAGEM, MdUtlControleDsmpRN::$EM_CORRECAO_TRIAGEM);
            $arrAnalise = array(MdUtlControleDsmpRN::$AGUARDANDO_ANALISE, MdUtlControleDsmpRN::$EM_ANALISE, MdUtlControleDsmpRN::$AGUARDANDO_CORRECAO_ANALISE, MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE);
            $plural = count($idsDistribuicao) > 1 ? 's' : '';

            if (in_array($idStatus, $arrTriagem)) {
                $strTitulo = 'Distribuição de Processo' . $plural . ' para Triagem';
            } else if (in_array($idStatus, $arrAnalise)) {
                $strTitulo = 'Distribuição de Processo' . $plural . ' para Análise';
            } else {
                $strTitulo = 'Distribuição de Processo' . $plural . ' para Avaliação';
            }


            $objMdUtlControleDsmpDTO->retTodos();
            $objMdUtlControleDsmpDTO->retStrNomeTipoProcesso();
            $objMdUtlControleDsmpDTO->setNumIdMdUtlControleDsmp($idsDistribuicao, InfraDTO::$OPER_IN);
            $objMdUtlControleDsmpDTO->retStrProtocoloProcedimentoFormatado();
            $objMdUtlControleDsmpDTO->retNumTempoExecucao();
            $objMdUtlControleDsmpDTO->retNumIdMdUtlAdmTpCtrlDesemp();
            $objMdUtlControleDsmpDTO->retStrNomeTpControle();
            $objMdUtlControleDsmpDTO->retStrNomeFila();

            $countDistribuicao = $objMdUtlControleDsmpRN->contar($objMdUtlControleDsmpDTO);
            if ($countDistribuicao > 0) {
                $arrObjs = $objMdUtlControleDsmpRN->listar($objMdUtlControleDsmpDTO);

                $idsProcesso = InfraArray::converterArrInfraDTO($arrObjs, 'IdProcedimento');
                $arrUltimosResponsaveis = $objMdUtlHistControleDsmpRN->getUltimosResponsaveisPorProcesso(array($idsProcesso));

                foreach ($arrObjs as $obj) {
                    $idProcedimento = $obj->getDblIdProcedimento();
                    $idControleDsmp = $obj->getNumIdMdUtlControleDsmp();
                    $idTpControleDsmp = $obj->getNumIdMdUtlAdmTpCtrlDesemp();
                    $tempoExecucao = $obj->getNumTempoExecucao();
                    $nomeTpCtrl = $obj->getStrNomeTpControle();
                    $nomeFila = $obj->getStrNomeFila();
                    $somaTempoExecucao += $tempoExecucao;

                    //Formatando Protocolo
                    $protocoloFormatado = $obj->getStrProtocoloProcedimentoFormatado();
                    $nomeProcesso = $obj->getStrNomeTipoProcesso();
                    $urlProcedimento = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem=md_utl_distrib_usuario_cadastrar&id_procedimento=' . $idProcedimento . '');
                    $hrefLinkProcesso = '<a onclick="window.open(\'' . $urlProcedimento . '\')" alt="' . $nomeProcesso . '" title="' . $nomeProcesso . '" class="ancoraPadraoAzul">' . $protocoloFormatado . '</a>';

                    //Formatando Usuário
                    $arrDadosUsuario = array_key_exists($idProcedimento, $arrUltimosResponsaveis) ? $arrUltimosResponsaveis[$idProcedimento] : array();
                    $idUsuario = array_key_exists('ID_USUARIO', $arrDadosUsuario) ? $arrDadosUsuario['ID_USUARIO'] : '';
                    $nomeUsuario = array_key_exists('NOME', $arrDadosUsuario) ? $arrDadosUsuario['NOME'] : '';
                    $siglaUsuario = array_key_exists('SIGLA', $arrDadosUsuario) ? $arrDadosUsuario['SIGLA'] : '';
                    $linkUsuario = $nomeUsuario != '' && $siglaUsuario != '' ? '<a class="ancoraSigla" alt="' . $nomeUsuario . '" title="' . $nomeUsuario . '">' . $siglaUsuario . '</a>' : '';

                    if($idUsuario) {
                        $tempoExecucaoTeletrabalho = MdUtlAdmPrmGrINT::convertToHoursMins(MdUtlAdmPrmGrUsuINT::retornaCalculoPercentualDesempenho($tempoExecucao, $idTpControleDsmp, $idUsuario));
                    } else {
                        $tempoExecucaoTeletrabalho = MdUtlAdmPrmGrINT::convertToHoursMins($tempoExecucao);
                    }
                    $arrStrGridProcesso[] = array($idProcedimento, $idControleDsmp, $hrefLinkProcesso, $tempoExecucaoTeletrabalho, $linkUsuario);

                }

                $strGridProcesso = PaginaSEI::getInstance()->gerarItensTabelaDinamica($arrStrGridProcesso);
            }

            $arrComandos = array();

            //Botões de ação do topo
            $arrComandos[] = '<button type="submit" accesskey="S" id="sbmSalvar" name="sbmSalvar" class="botaoSalvar infraButton">
                                    <span class="infraTeclaAtalho">S</span>alvar</button>';

            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="cancelar()" class="infraButton">
                                <span class="infraTeclaAtalho">C</span>ancelar</button>';

            
            $arrCalcTempo = MdUtlAdmPrmGrUsuINT::getValoresParamUnidEsf( $idTipoControle );

            $idPrmGr                    = $arrCalcTempo['idPrmGr'];
            $numCargaPadrão             = $arrCalcTempo['numCargaPadrao'];
            $numPercentualTele          = $arrCalcTempo['numPercentualTele'];
            $inicioPeriodoParametrizado = $arrCalcTempo['inicioPeriodo'];
            $strStaFrequencia           = $arrCalcTempo['staFrequencia'];
            $strFrequencia              = $arrCalcTempo['strFrequencia'];
            $strCargaPadrao             = $arrCalcTempo['strCargaPadrao'];

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['txtUsuarioParticipante'] != '') {
                $objMdUtlControleDsmpRN->incluirNovaDistribuicao($idStatus);
                $isTelaProcesso = $_POST['hdnIsTelaProcesso'] == 1;

                if ($isTelaProcesso) {
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . '&id_procedimento=' . $idProcedimentoTelaProc));
                } else {
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']));
                }

                die;
            }

            break;

        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    }


} catch (Exception $e) {
    PaginaSEI::getInstance()->processarExcecao($e);
}

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo);
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();

if (0) { ?>
    <style><? }
    ?>
        <?php if($isTelaProcesso){ ?>
        div[id^="divOpcoesDistribuicao"] {
            position: absolute;
            width: 15%;
            left: 48%;
            margin-top: -17px;
        }

        #txtUsuarioParticipante {
            width: 47%;
        }

        <?php }else {?>

        div[id^="divOpcoesDistribuicao"] {
            position: absolute;
            width: 15%;
            left: 40.5%;
            margin-top: -17px;
        }

        #txtUsuarioParticipante {
            width: 40%;
        }

        <?php } ?>

        #imgExcluirProcesso {
            margin-left: 35%;
            margin-top: 2%;
        }

        #divTabelaProcessos {
            margin-top: 28px;
        }

        #divCarga {
            width: 100%;
        }

        .dv_container{
            display: flex;
            flex-direction: column;
        }

        .dv_container > div{
            width: 100% !important;
            margin: 8px;
            text-align: left;
        }

        .dv_container_hr{
            display: flex;
        }

        .dv_container_hr > div{
            width: auto;
            margin: 0px;
            text-align: left;
        }


        <?
        if (0) { ?></style><?
} ?>

<?php
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
require_once 'md_utl_geral_js.php';
if (0){
    ?>
    <script><?}?>

        var idParam = '<?php echo $idPrmGr ?>';
        var numCargaPadrao = '<?php echo $numCargaPadrão ?>';
        var numPercentualTele = '<?php echo $numPercentualTele ?>';
        var staFrequencia = '<?php echo $strStaFrequencia?>';
        var strStaFrequencia = '<?php echo $strFrequencia?>';
        var selecionadaDist = '<?php echo $somaTempoExecucao?>';
        var inicioPeriodo = '<?php echo $inicioPeriodoParametrizado; ?>';

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

        function verificarConcorrencia(count) {

            if (count == 0) {
                alert('Os registros indicados não possuem o status informado! Favor selecionar novamente');
                cancelar();
            }
        }

        function iniciarGridDinamicaDistribuicao() {
            objTabelaDinamicaProcesso = new infraTabelaDinamica('tbProcesso', 'hdnTbProcesso', false, false);
            objTabelaDinamicaProcesso.gerarEfeitoTabela = true;

            var hdnLista = '';
            var arrhdnLista = '';


            if (objTabelaDinamicaProcesso.hdn.value != '') {
                objTabelaDinamicaProcesso.recarregar();

                <?php if( $exibirColAcao ) { ?>
                    //acoes
                    hdnLista = objTabelaDinamicaProcesso.hdn.value;
                    arrhdnLista = hdnLista.split('¥');

                    //array
                    if (arrhdnLista.length > 0) {
                        for (i = 0; i < arrhdnLista.length; i++) {
                            var hdnListaTela = arrhdnLista[i].split('±');
                            var undEsf = '"'+hdnListaTela[3]+'"';
                            var btnDistribuicao = "<a onclick='objTabelaDinamicaProcesso.removerProcesso(" + hdnListaTela[0] + "," + undEsf + ")'><img title='Remover Seleção do Processo' alt='Remover Seleção do Processo' src=\"modulos/utilidades/imagens/removerSelecao.png\" class='infraImg'/></a><img src=\"/infra_css/imagens/espaco.gif\" class=\"\" border=\"0\">";

                            objTabelaDinamicaProcesso.adicionarAcoes(hdnListaTela[0], btnDistribuicao);
                        }
                    }
                <?php } ?>
            }

            objTabelaDinamicaProcesso.removerProcesso = function (idProcesso, undEsforco) {

                let qtdRows = $('#tbProcesso > thead').find('tr');

                if ( qtdRows.length == 2 ){
                    alert('Não é permitido remover este Processo da Distribuição, pois é necessário, no mínimo, um Processo para Distribuição.');
                    return false;
                }

                var row = objTabelaDinamicaProcesso.procuraLinha(idProcesso);

                objTabelaDinamicaProcesso.removerLinha(row);

                controlarExibicaoCargaDistribuida(undEsforco);

                if (objTabelaDinamicaProcesso.tbl.rows.length == 1) {
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

        function controlarExibicaoCargaDistribuida(tempoExecucao) {
            tempoExecucao = parseInt(tempoExecucao);
            var cargaSelecDistri = $.trim(document.getElementById('txtSelecionadaDist').value);
            var totalTmpExecucao = $.trim(document.getElementById('txtTotalUniEsforco').value);

            cargaSelecDistri = parseInt(cargaSelecDistri);
            totalTmpExecucao = parseInt(totalTmpExecucao);

            var somaTmpExecucao = cargaSelecDistri - tempoExecucao;
            var totalTmpExecucao = totalTmpExecucao - tempoExecucao;
            document.getElementById('txtSelecionadaDist').value = $.trim(somaTmpExecucao);
            document.getElementById('txtTotalUniEsforco').value = $.trim(totalTmpExecucao);
        }

        function realizarDistribuicao() {
            var selParticipante = document.getElementById('txtUsuarioParticipante').value;
            var tdCount = document.getElementsByClassName('infraTd').length;

            if (selParticipante == '') {
                alert('Preencha o Usuário Participante!');
                return false;
            }

            if (tdCount == 0) {
                alert('Selecione ao menos um processo para realizar a Distribuição!');
                return false;
            }

            document.getElementById('hdnSelParticipante').value = selParticipante;
            bloquearBotaoSalvar();
            return true;
        }

        function carregarComponenteUsuarioParticipante() {
            objLupaUsuarioParticipante = new infraLupaText('txtUsuarioParticipante', 'hdnIdUsuarioParticipanteLupa', '<?=$strLinkUsuarioParticipante?>');

            objAutoCompletarUsuarioParticipante = new infraAjaxAutoCompletar('hdnIdUsuarioParticipanteLupa', 'txtUsuarioParticipante', '<?=$strLinkAjaxUsuarioParticipante?>');
            objAutoCompletarUsuarioParticipante.limparCampo = true;
            objAutoCompletarUsuarioParticipante.tamanhoMinimo = 3;
            objAutoCompletarUsuarioParticipante.prepararExecucao = function () {
                // busca os procedimentos listados na tela para verificar se o colaborador pode ser selecionado
                let arrProcedimentos = [];
                Array.from(document.querySelectorAll('#tbProcesso tr')).forEach(tr => {
                    let td = tr.querySelectorAll('td');
                    if(td[0] && td[0].innerText){
                        arrProcedimentos.push(td[0].innerText);
                    }
                });

                return 'palavras_pesquisa=' + document.getElementById('txtUsuarioParticipante').value+'&arr_procedimentos='+arrProcedimentos;
            };

            objAutoCompletarUsuarioParticipante.processarResultado = function (id, descricao, complemento) {

                if (id != '') {
                    document.getElementById('hdnIdUsuarioParticipanteLupa').value = id;
                    document.getElementById('txtUsuarioParticipante').value = descricao;
                    //chamar a função responsavel por carregar os campos do participante - Ajax
                    realizarAjaxDadosCarga();
                } else {
                    limparCampos();
                }
            }

            objLupaUsuarioParticipante.carregarLink = function() {
                montarLinkParaBuscarUsuarioParaDistribuicao();
            }

            objLupaUsuarioParticipante.finalizarSelecao = function () {
                objAutoCompletarUsuarioParticipante.selecionar(document.getElementById('hdnIdUsuarioParticipanteLupa').value, document.getElementById('txtUsuarioParticipante').value);
                //chamar a funo responsavel por carregar os campos do participante - Lupa
                realizarAjaxDadosCarga();
            }

            objLupaUsuarioParticipante.processarRemocao = function () {
                limparCampos();
                return true;
            }

        }

        function cancelar() {
            location.href = "<?= $strLinkCancelar ?>";
        }

        function limparCampos() {
            document.getElementById('txtTotalUniEsforco').value = convertToHoursMins('<?=$somaTempoExecucao?>');
            document.getElementById('txtSelecionadaDist').value = convertToHoursMins('<?=$somaTempoExecucao?>');
            document.getElementById('txtDistribuida').value = '';
            document.getElementById('txtCargaPadrao').value = '<?=$strCargaPadrao?>';
            resetarTempoExecucaoTabelaDistribuicao();
        }

        function realizarAjaxDadosCarga() {

            var params = {
                idUsuarioParticipante: document.getElementById('hdnIdUsuarioParticipanteLupa').value,
                idParam: idParam,
                numCargaPadrao: numCargaPadrao,
                numPercentualTele: numPercentualTele,
                staFrequencia: staFrequencia,
                inicioPeriodo: inicioPeriodo,
                idTipoControle: <?php echo $idTipoControle ?>
            };

            $.ajax({
                url: '<?=$strUrlBuscarDadosCarga?>',
                type: 'POST',
                data: params,
                dataType: 'XML',
                success: function (r) {

                    //Carga Padrão
                    var valorCarga = $(r).find('ValorCarga').text();
                    var cargaPadrao = $.trim(convertToHoursMins(valorCarga)) + ' - ' + strStaFrequencia;
                    document.getElementById('txtCargaPadrao').value = cargaPadrao;

                    var valorUndEs = $(r).find('ValorUndEs').text();
                    var cargaDisti = valorUndEs;
                    document.getElementById('txtDistribuida').value = convertToHoursMins(cargaDisti);

                    var valorUndEsExec = $(r).find('ValorUndEsExecutado').text();
                    document.getElementById('txtTotalExePeriodo').value = convertToHoursMins(valorUndEsExec);

                    var valorUndEsExec = $(r).find('ValorTempoPendenteExecucao').text();
                    document.getElementById('txtTotalTempoPendenteExecucao').value = convertToHoursMins(valorUndEsExec);

                    totalUniesforco = parseInt(cargaDisti) + parseInt(selecionadaDist);
                    calculaCargaHorariaTotal();

                },
                error: function (e) {
                    console.error('Erro ao buscar URL de Tipo de Controle: ' + e.responseText);
                }
            });

            var params2 = {
                idUsuarioParticipante: document.getElementById('hdnIdUsuarioParticipanteLupa').value,
                nunEsforco: selecionadaDist,
                idTipoControle: <?php echo $idTipoControle ?>
            };

            $.ajax({
                url: '<?=$strUrlBuscarDadosRegimeTrabalho?>',
                type: 'POST',
                data: params2,
                dataType: 'XML',
                success: function (r) {

                    // //Carga Padrão
                    var valorDistribuicao = $(r).find('ValorDistribuicao').text();
                    document.getElementById('txtSelecionadaDist').value = convertToHoursMins($.trim(valorDistribuicao));
                    calculaCargaHorariaTotal();
                    atualizarTempoExecucaoTabelaDistribuicao(valorDistribuicao);
                },
                error: function (e) {
                    console.error('Erro ao buscar URL de Tipo de Controle: ' + e.responseText);
                }
            });

        }

        function montarLinkParaBuscarUsuarioParaDistribuicao(){

            let arrProcedimentos = [];
            Array.from(document.querySelectorAll('#tbProcesso tr')).forEach(tr => {
                let td = tr.querySelectorAll('td');
                if(td[0] && td[0].innerText){
                    arrProcedimentos.push(td[0].innerText);
                }
            });

            var params = {
                idFila: <?php echo $idFila ?>,
                idStatus: <?php echo $idStatus ?>,
                idTipoControle: <?php echo $idTipoControle ?>,
                arrProcedimentos: arrProcedimentos
            };

            $.ajax({
                url: '<?=$strLinkMontarLinkUsuarioParticipante?>',
                type: 'POST',
                data: params,
                dataType: 'text',
                success: function (r) {
                    var valorDistribuicao = $(r).find('LinkUsuario').text();

                    objLupaUsuarioParticipante.url = valorDistribuicao;
                    objLupaUsuarioParticipante.selecionar(700,500);
                },
                error: function (e) {
                    console.error('Erro ao buscar URL: ' + e.responseText);
                }
            });
        }

        function atualizarTempoExecucaoTabelaDistribuicao(valorDistribuicao){
            Array.from(document.querySelectorAll('#tbProcesso tr')).forEach(tr => {
                Array.from(tr.querySelectorAll('td')).forEach((td, index, todas_td) => {
                    if (index === 4 && td.innerText === '') {
                        let qtde = document.querySelectorAll('#tbProcesso tr').length;
                        let tempo = parseInt(convertToMins(valorDistribuicao.trim())) / (qtde - 1);
                        todas_td[3].innerText = convertToHoursMins(parseInt(tempo));
                        todas_td[3].style.textAlign = "center";
                    }
                });
            });
        }

        function resetarTempoExecucaoTabelaDistribuicao(){
            Array.from(document.querySelectorAll('#tbProcesso tr')).forEach(tr => {
                Array.from(tr.querySelectorAll('td')).forEach((td, index, todas_td) => {
                    if (index === 4 && td.innerText === '') {
                        let qtde = document.querySelectorAll('#tbProcesso tr').length;
                        let tempo = parseInt('<?=$somaTempoExecucao?>') / (qtde - 1);
                        todas_td[3].innerText = convertToHoursMins(parseInt(tempo));
                        todas_td[3].style.textAlign = "center";
                    }
                });
            });
        }

        function calculaCargaHorariaTotal(){
            var txtDistribuida = convertToMins($.trim(document.getElementById('txtDistribuida').value));
            var valorDistribuicao = document.getElementById('txtSelecionadaDist').value
            var valorDistribuicaoMinuto = convertToMins(valorDistribuicao.trim());
            document.getElementById('txtTotalUniEsforco').value = convertToHoursMins(valorDistribuicaoMinuto + txtDistribuida);
        }


        function convertToHoursMins(time) {

            hours = Math.trunc(time / 60);
            minutes = (time % 60);
            if (time == 0) {
                format = '0min';
            } else {
                if (time < 60) {
                    format = minutes + 'min';
                } else {
                    if(minutes == 0)
                        format = hours + 'h ';
                    else
                        format = hours + 'h ' + minutes + 'min';
                }
            }

            return format;
        }

        function convertToMins(time){
            var tempo = time.split(" ");
            var minutos = 0;
            if(tempo.length == 1){
                if(tempo[0].indexOf("min") != -1){
                    minutos = parseInt(tempo[0].replace("min", "")) + minutos;
                }
                if(tempo[0].indexOf("h") != -1) {
                    minutos = parseInt(tempo[0].replace("h", "")) * 60;
                }
            } else {
                minutos = parseInt(tempo[0].replace("h", "")) * 60;
                minutos = parseInt(tempo[1].replace("min", "")) + minutos;
            }
            return minutos;
        }

        <? if (0){ ?></script><?
}

PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
    <form id="frmDistribuicao" onsubmit="return realizarDistribuicao();" method="post"
          action="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao']) ?>">

        <?
        //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
        PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);

        PaginaSEI::getInstance()->abrirAreaDados('auto');

        //PaginaSEI::getInstance()->montarAreaValidacao();

        $larguraInput = "width: 47.5%";
        if ($isTelaProcesso) {
            $larguraInput = "width: 50%";
        }

        // textos dos tooltips
        $txtTooltipUsuárioParticipante = 'Selecione o Membro Participante para o qual pretende distribuir esta atividade no fluxo do Controle de Desempenho.\n \n Caso alguém não seja listado como Membro Participante, verifique com o Gestor do Controle de Desempenho para adicioná-lo na Fila correspondente do Tipo de Controle indicado. Também é necessário que o Membro Participante possua permissões nesta Unidade.';

        $txtTooltipCargaHorariaPadrao = 'A Carga Horária Padrão no Período corresponde ao tempo da jornada de trabalho esperada para o Período de distribuição e acompanhamento, conforme definido nos parâmetros gerais do Tipo de Controle de Desempenho, abatendo Feriados que ocorram no Período ou possível jornada reduzida do Participante neste Tipo de Controle de Desempenho.';

        $txtTooltipTotalTempoPendenteExecucao = 'O Total de Tempo Pendente de Execução somente será exibido depois que for selecionado o Membro Participante.\n \n O Total corresponde à soma do Tempo de Execução de cada processo sob responsabilidade do Membro Participante, independentemente de quando tenha sido distribuído.';

        $txtTooltipTotalTempoExecutadoPeriodo = MdUtlAdmPrmGrINT::recuperarTextoFrequenciaTooltipDinamicoDistribuirPessoaTempoExecutadoPeriodo($idTipoControle);

        $txtTooltipCargaHorariaDistribuidaPeriodo = MdUtlAdmPrmGrINT::recuperarTextoFrequenciaTooltipDinamicoDistribuirPessoaCargaHoraria($idTipoControle);

        $txtTooltipCargaHorariaSelecionadaDistribuicao = 'A Carga Horária selecionada para Distribuição corresponde ao Tempo de Execução que o Membro Participante ganhará ao realizar a atividade no fluxo do Controle de Desempenho que está sendo distribuída, seja Triagem, Análise ou Avaliação.\n \n O Tempo de Execução de Triagem é padrão por Fila do Controle de Desempenho.\n \n O Tempo de Execução de Análise depende das Atividades incluídas na fase de Triagem. Contudo, ao final, o Membro Participante que realizar a Análise somente ganhará o Tempo de Execução das Atividades que tenha entregado pelo menos um Produto.\n \n O Tempo de Execução de Avaliação depende de cada Produto entregue nas Atividades na fase de Análise.';

        $txtTooltipCargaHorariaTotal = 'A Carga Horária Total corresponde à soma da "Carga Horária Distribuída no Período" com a "Carga Horária selecionada para Distribuição" para o Membro Participante selecionado.';
        ?>
        <div>

            <div id="divNmTpCtrl">
                <label for="lblNmTpCtrl">Tipo de Controle:</label>
                <br>
                <input type="text" name="" id="" disabled value="<?= $nomeTpCtrl ?>" class='infraText' style='width: 325px;'>
            </div>
            <br><br>

            <div id="divNmTpCtrl">
                <label for="lblFila">Fila:</label>
                <br>
                <input type="text" name="" id="" disabled value="<?= $nomeFila ?>" class='infraText' style='width: 325px;'>
            </div>
            <br><br>

            <div id="divDistribuicao">
                <label id="lblDistribuicao" for="txtUsuarioParticipante" accesskey="" class="infraLabelObrigatorio">
                    Membro Participante:
                    <a id="btnAtividade" <?= PaginaSEI::montarTitleTooltip($txtTooltipUsuárioParticipante) ?>>
                        <img id="imgAjudaAtividade" border="0" style="width: 16px;height: 16px;margin-bottom: -3px;"
                             src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" class="infraImg"/>
                    </a>
                </label>
                <div class="clear"></div>

                <input type="text" id="txtUsuarioParticipante" name="txtUsuarioParticipante" class="infraText"
                       tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                <div id="divOpcoesDistribuicao" style='margin-left: 5px;'>
                    <img id="imgLupaDistribuicao" onclick="objLupaUsuarioParticipante.carregarLink();"
                         src="/infra_css/imagens/lupa.gif" alt="Selecionar Usuário Participante"
                         title="Selecionar Usuário Participante" class="infraImg">
                    <img id="imgExcluirDistribuicao" onclick="objLupaUsuarioParticipante.remover();"
                         src="/infra_css/imagens/remover.gif" alt="Remover Usuário Participante"
                         title="Remover Usuário Participante" class="infraImg">
                </div>
            </div>

            <div class="clear">&nbsp;<br><br></div>

            <div class="dv_container_hr">
                <div class="dv_subitem" style="<?php echo $larguraInput; ?>">
                    <div id="divCargaPadrao">
                        <label id="lblCargaPadrao" for="txtCargaPadrao" class="infraLabelOpcional">
                            Carga Horária Padrão no Período:
                            <a id="btnAtividade" <?= PaginaSEI::montarTitleTooltip($txtTooltipCargaHorariaPadrao) ?>>
                                <img id="imgAjudaAtividade" border="0" style="width: 16px;height: 16px;margin-bottom: -3px;"
                                     src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" class="infraImg"/>
                            </a>
                        </label>
                        <br>
                        <input type="text" id="txtCargaPadrao" name="txtCargaPadrao" class="infraText" style="width: 67%"
                               value="<?= $strCargaPadrao ?>" disabled/>
                    </div>
                    <div class="clear">&nbsp;<br><br></div>
                    <div id="divTotalTempoPendenteExecucao">
                        <label id="lblTotalTempoPendenteExecucao" for="txtTotalTempoPendenteExecucao" class="infraLabelOpcional">
                            Total de Tempo Pendente de Execução:
                            <a id="btnAtividade" <?= PaginaSEI::montarTitleTooltip($txtTooltipTotalTempoPendenteExecucao) ?>>
                                <img id="imgAjudaAtividade" border="0" style="width: 16px;height: 16px;margin-bottom: -3px;"
                                     src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" class="infraImg"/>
                            </a>
                        </label>
                        <br>
                        <input type="text" id="txtTotalTempoPendenteExecucao" name="txtCargaPadrao" class="infraText" style="width: 67%" disabled/>
                    </div>
                    <div class="clear">&nbsp;<br><br></div>
                    <div id="divTotalExePeriodo">
                        <label id="lblTotalExePeriodo" for="txtTotalExePeriodo" class="infraLabelOpcional">
                            Total de Tempo Executado no Período:
                            <a id="btnAtividade" <?= PaginaSEI::montarTitleTooltip($txtTooltipTotalTempoExecutadoPeriodo) ?>>
                                <img id="imgAjudaAtividade" border="0" style="width: 16px;height: 16px;margin-bottom: -3px;"
                                     src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" class="infraImg"/>
                            </a>
                        </label>
                        <br>
                        <input type="text" id="txtTotalExePeriodo" name="txtTotalExePeriodo" class="infraText" style="width: 67%" disabled/>
                    </div>
                </div>

                <div class="dv_subitem" style="<?php echo $larguraInput; ?>">
                    <div id="divDistribuidaMes">
                        <label id="lblDistribuida" for="txtDistribuida" class="infraLabelOpcional">
                            Carga Horária Distribuída no Período:
                            <a id="btnAtividade" <?= PaginaSEI::montarTitleTooltip($txtTooltipCargaHorariaDistribuidaPeriodo) ?>>
                                <img id="imgAjudaAtividade" border="0" style="width: 16px;height: 16px;margin-bottom: -3px;"
                                     src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" class="infraImg"/>
                            </a>
                        </label>
                        <br>
                        <input type="text" id="txtDistribuida" name="txtDistribuida" class="infraText" style="width: 67%" disabled/>
                    </div>
                    <div class="clear">&nbsp;<br><br></div>
                    <div id="divSelecionadaDist">
                        <label id="lblSelecionadaDist" for="txtSelecionadaDist" class="infraLabelOpcional">
                            Carga Horária selecionada para Distribuição:
                            <a id="btnAtividade" <?= PaginaSEI::montarTitleTooltip($txtTooltipCargaHorariaSelecionadaDistribuicao) ?>>
                                <img id="imgAjudaAtividade" border="0" style="width: 16px;height: 16px;margin-bottom: -3px;"
                                     src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" class="infraImg"/>
                            </a>
                        </label>
                        <br>
                        <input type="text" id="txtSelecionadaDist" name="txtSelecionadaDist" class="infraText"
                               style="width: 67%" value="<?= MdUtlAdmPrmGrINT::convertToHoursMins($somaTempoExecucao) ?>" disabled/>
                    </div>
                    <div class="clear">&nbsp;<br><br></div>
                    <div id="divTotalUniEsforco">
                        <label id="lblTotalUniEsforco" for="txtTotalUniEsforco" class="infraLabelOpcional">Carga Horária Total:
                            <a id="btnAtividade" <?= PaginaSEI::montarTitleTooltip($txtTooltipCargaHorariaTotal) ?>>
                                <img id="imgAjudaAtividade" border="0" style="width: 16px;height: 16px;margin-bottom: -3px;"
                                     src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" class="infraImg"/>
                            </a>
                        </label>
                        <br>
                        <input type="text" id="txtTotalUniEsforco" name="txtTotalUniEsforco" class="infraText"
                               style="width: 67%" value="<?= MdUtlAdmPrmGrINT::convertToHoursMins($somaTempoExecucao) ?>" disabled/>
                    </div>
                </div>
            </div>

            <div class="clear">&nbsp;<br><br></div>

            <?php
            $width = $isTelaProcesso ? '85%' : '80%';
            if ($countDistribuicao > 0) { ?>
                <div id="divTabelaProcessos">

                    <table width="<?= $width ?>" class="infraTable" summary="Demanda" id="tbProcesso">
                        <caption class="infraCaption">
                            <?= PaginaSEI::getInstance()->gerarCaptionTabela($strTitulo, 0) ?>
                        </caption>
                        <tr>
                            <th class="infraTh" style="display: none;">ID do Processo</th>
                            <th class="infraTh" style="display: none;">ID do Controle do Dsmp</th>
                            <th class="infraTh" align="center">Processo</th>
                            <th class="infraTh" align="center" style="width:30%;">Tempo de Execução para Distribuição</th>
                            <th class="infraTh" align="center" >Último Responsável</th>
                            <?php if( $exibirColAcao) { ?>
                                <th class="infraTh" align="center" style="width:8%;" data-item="acao">Ações</th>
                            <?php } ?>
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
        <input type="hidden" name="hdnTbProcesso" id="hdnTbProcesso" value="<?php echo $strGridProcesso ?>"/>
        <input type="hidden" id="btnDistribuir" name="btnDistribuir"/>
        <input type="hidden" id="hdnDistribuicao" name="hdnDistribuicao"
               value=<?php echo json_encode($idsDistribuicao); ?>>
        <input type="hidden" id="hdnIdProcedimento" name="hdnIdProcedimento" value="<?= $idProcedimento ?>">
        <input type="hidden" id="hdnUsuarioParticipanteLupa" name="hdnUsuarioParticipanteLupa"
               value="<?= $_POST['hdnUsuarioParticipanteLupa'] ?>"/>
        <input type="hidden" id="hdnIdUsuarioParticipanteLupa" name="hdnIdUsuarioParticipanteLupa" value=""/>
        <input type="hidden" id="hdnIdTipoControleUtl" name="hdnIdTipoControleUtl"
               value="<?php echo $idTipoControle; ?>"/>
        <input type="hidden" id="hdnIdFila" name="hdnIdFila" value="<?php echo $idFila; ?>"/>
        <input type="hidden" id="hdnSelStatus" name="hdnSelStatus" value="<?php echo $idStatus; ?>"/>

        <input type="hidden" id="hdnSelParticipante" name="hdnSelParticipante">
        <input type="hidden" id="strStaTipoPresenca" name="strStaTipoPresenca" value=""/>
        <input type="hidden" id="numTempoExecucaoAtribuido" name="numTempoExecucaoAtribuido" value=""/>
        <input type="hidden" id="numPercentualDesempenho" name="numPercentualDesempenho" value=""/>

        <!-- Controle da Tela de Processo -->
        <input type="hidden" id="hdnIsTelaProcesso" name="hdnIsTelaProcesso"
               value="<?php echo $isTelaProcesso ? '1' : '0' ?>"/>
        <input type="hidden" id="hdnIdProcedimentoTelaProc" name="hdnIdProcedimentoTelaProc"
               value="<?php echo $idProcedimentoTelaProc ?>"/>
        <input type="hidden" id="hdnDistribuicaoTelaProc" name="hdnDistribuicaoTelaProc"
               value="<?php echo $idDistribuicao ?>"/>


    </form>

    <script>
        document.getElementById('txtUsuarioParticipante').focus();
    </script>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>