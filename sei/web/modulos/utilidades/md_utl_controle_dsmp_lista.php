<?php
/**
 * User: jaqueline.mendes@castgroup.com.br
 * Date: 28/02/2019
 */

require_once dirname(__FILE__) . '/../../SEI.php';

session_start();
SessaoSEI::getInstance()->validarLink();
$arrIdProcedimentoAssociado = array();
if (isset($_SESSION['ID_PROCEDIMENTO_FILA_ASSOCIAR'])) {
    $arrIdProcedimentoAssociado = $_SESSION['ID_PROCEDIMENTO_FILA_ASSOCIAR'];
    unset($_SESSION['ID_PROCEDIMENTO_FILA_ASSOCIAR']);
}

//Instâncias
$objMdUtlAdmTpCtrlUndRN   = new MdUtlAdmRelTpCtrlDesempUndRN();
$objFilaRN                = new MdUtlAdmFilaRN();
$objMdUtlControleDsmpRN = new MdUtlControleDsmpRN();

//Validação de Tipo de Controle
$idTipoControle         = $objMdUtlAdmTpCtrlUndRN->getTipoControleUnidadeLogada();
$isParametrizado         = true;
if (!is_null($idTipoControle)) {
    $objMdUtlAdmUtlTpCtrlRN = new MdUtlAdmTpCtrlDesempRN();
    $isParametrizado = $objMdUtlAdmUtlTpCtrlRN->verificaTipoControlePossuiParametrizacao($idTipoControle);
}


//URL Base
$strUrl = 'controlador.php?acao=md_utl_controle_dsmp_';
//URL das Actions
$strLinkAssociarFila = SessaoSEI::getInstance()->assinarLink($strUrl . 'associar&acao_origem=' . $_GET['acao'] . '&id_tp_controle_desmp=' . $idTipoControle);
$strUrlPesquisar     = SessaoSEI::getInstance()->assinarLink($strUrl . 'listar&acao_origem=' . $_GET['acao'] . '&id_tipo_controle_utl=' . $idTipoControle);
$strUrlFechar        = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_controlar&acao_origem=' . $_GET['acao']);

if(!is_null($idTipoControle) && $isParametrizado) {
    $arrObjFilaDTO = $objFilaRN->getFilasTipoControle($idTipoControle);
    $selFila = MdUtlAdmFilaINT::montarSelectFilas($_POST['selFila'], $arrObjFilaDTO);
    $selStatus = MdUtlControleDsmpINT::montarSelectStatus($_POST['selStatus']);
    $arrObjsTpProcesso = $objMdUtlControleDsmpRN->getTiposProcessoTipoControle($idTipoControle);
    $selTipoProcesso = InfraINT::montarSelectArrInfraDTO(null, null, $_POST['selTipoProcesso'], $arrObjsTpProcesso, 'IdTipoProcedimento', 'NomeProcedimento');
    $isPermiteAssociacao = $objMdUtlControleDsmpRN->validaVisualizacaoUsuarioLogado($idTipoControle);
    $idsTpProcesso = InfraArray::converterArrInfraDTO($arrObjsTpProcesso, 'IdTipoProcedimento');
}
$strTitulo = 'Associar Processos a Filas';

switch ($_GET['acao']) {

    //region Listar
    case 'md_utl_controle_dsmp_listar':

        break;
    //endregion

    //region Erro
    default:
        throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    //endregion
}


$arrComandos[] = '<button type="button" accesskey="P" id="btnPesquisar" onclick="pesquisar()" class="infraButton">
                                        <span class="infraTeclaAtalho">P</span>esquisar</button>';

if (!is_null($idTipoControle) && $isPermiteAssociacao) {
    //Botões de ação do topo
    $arrComandos[] = '<button type="button" accesskey="A" id="btnAssoFila" onclick="associarFila()" class="infraButton">
                                        <span class="infraTeclaAtalho">A</span>ssociar à Fila</button>';
}


$arrComandos[] = '<button type="button" accesskey="c" id="btnFechar" onclick="fechar()" class="infraButton">
                                        Fe<span class="infraTeclaAtalho">c</span>har</button>';

if (!is_null($idTipoControle) && $isParametrizado) {

    //Configuração da Paginação
    $objDTO = new MdUtlProcedimentoDTO();
    $idsProcessoDocumento = array();
    $idsProcessoAberto  = array();
    $txtProcesso        = array_key_exists('txtProcesso', $_POST) && $_POST['txtProcesso'] != '';
    $isTipoProcesso     = array_key_exists('selTipoProcesso', $_POST) && $_POST['selTipoProcesso'] != '';
    $isIdFila           = array_key_exists('selFila', $_POST) && $_POST['selFila'] != '';
    $isStrStatus        = array_key_exists('selStatus', $_POST) && $_POST['selStatus'] != '';
    $isStrDocumento     = array_key_exists('txtDocumento', $_POST) && trim($_POST['txtDocumento']) != '';
    $isAguardandoFila   = $_POST['selStatus'] == MdUtlControleDsmpRN::$AGUARDANDO_FILA;
    $arrIdsAtivosDsmp    = array();
    $isFiltroDocumento  = false;

    //Set Campos definidos por Regras
    $objDTO->setStrStaEstadoProtocolo(ProtocoloRN::$TE_NORMAL);
    $objDTO->setStrStaNivelAcessoLocalProtocolo(array(ProtocoloRN::$NA_PUBLICO, ProtocoloRN::$NA_RESTRITO), InfraDTO::$OPER_IN);
    $objDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

    if ($isTipoProcesso) {
        $objDTO->setNumIdTipoProcedimento($_POST['selTipoProcesso']);
    } else {
        $objDTO->setNumIdTipoProcedimento($idsTpProcesso, InfraDTO::$OPER_IN);
    }

    if ($isIdFila) {
        $objDTO->setControleDsmpTIPOFK(InfraDTO::$TIPO_FK_OBRIGATORIA);
        $objDTO->setNumIdFila($_POST['selFila']);
    }


    if ($txtProcesso) {
        $objDTO->setStrProtocoloProcedimentoFormatado('%' . trim($_POST['txtProcesso'] . '%'), InfraDTO::$OPER_LIKE);
    }

    $idsProcessoAberto = $objMdUtlControleDsmpRN->getIdsProcessoAbertoUnidade($objDTO);

    if ($isStrStatus) {

        if ($isAguardandoFila) {
            $arrIdsAtivosDsmp = $objMdUtlControleDsmpRN->getIdsAtivosControleDesempenho($idsProcessoAberto);
            $idsProcessoAberto = count($arrIdsAtivosDsmp) > 0 && $isAguardandoFila ? array_diff($idsProcessoAberto, $arrIdsAtivosDsmp) : $idsProcessoAberto;

            if(count($idsProcessoAberto) == 0){
                $objDTO->setNumIdMdUtlControleDsmp(null);
            }

        } else {
            $objDTO->setControleDsmpTIPOFK(InfraDTO::$TIPO_FK_OBRIGATORIA);
            $objDTO->setStrStaAtendimentoDsmp(trim($_POST['selStatus']));
            $objDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTipoControle);
        }


    } else {
        $objDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTipoControle);
    }

    if (count($idsProcessoAberto) > 0) {

        if ($isStrDocumento)
        {
            //Realiza o filtro de Documento
            $idsProcessoAberto = $objMdUtlControleDsmpRN->getIdsProcessoDocumentosFiltrados(array($objDTO, $idsProcessoAberto));
            $isFiltroDocumento    = true;

            if (count($idsProcessoAberto) > 0) {
                $objDTO->setDblIdProcedimento($idsProcessoAberto, InfraDTO::$OPER_IN);
            } else {
                //Se não existir dados validos ele faz a pesquisa de forma normal, para retornar a paginação.]
                $objDTO->setStrProtocoloFormatadoDocumento('%' . trim($_POST['txtDocumento']) . '%', InfraDTO::$OPER_LIKE);
            }

        } else {
            //Setta todos os processos que estão abertos
            if (count($idsProcessoAberto) > 0) {
                $idsProcessoAberto = count($arrIdsAtivosDsmp) > 0 && $isAguardandoFila ? array_diff($idsProcessoAberto, $arrIdsAtivosDsmp) : $idsProcessoAberto;
                $objDTO->setDblIdProcedimento($idsProcessoAberto, InfraDTO::$OPER_IN);
            }
        }


        $objDTO->retNumIdMdUtlAdmRelControleDsmp();
        $objDTO->retNumIdMdUtlControleDsmp();
        $objDTO->retNumIdUnidade();
        $objDTO->retStrNomeTipoProcedimento();
        $objDTO->retStrStaAtendimentoDsmp();
        $objDTO->retStrSiglaUnidade();
        $objDTO->retStrProtocoloProcedimentoFormatado();
        $objDTO->retStrNomeFila();
        $objDTO->retNumIdFila();
        $objDTO->retNumUnidadeEsforco();
        $objDTO->retStrNomeUsuarioDistribuicao();
        $objDTO->retDthAtual();
        $objDTO->retStrSiglaUsuarioDistribuicao();
        $objDTO->retNumIdMdUtlAnalise();

        PaginaSEI::getInstance()->prepararOrdenacao($objDTO, 'ProtocoloProcedimentoFormatado', InfraDTO::$TIPO_ORDENACAO_ASC);
        PaginaSEI::getInstance()->prepararPaginacao($objDTO, 200);

         $arrObjs     = $objMdUtlControleDsmpRN->listarProcessos($objDTO);
        $numRegistros = count($idsProcessoAberto) > 0 ? count($arrObjs) : 0;

        PaginaSEI::getInstance()->processarPaginacao($objDTO);

        //$numRegistros = count($arrObjs);


        //Tabela de resultado.
        if ($numRegistros > 0) {
            $arrUltimasFilas = array();

            $displayNoneCheck = $isPermiteAssociacao ? '' : 'style="display:none"';
            $strResultado .= '<table width="99%" class="infraTable" summary="Associar Processos a Filas" id="tbCtrlProcesso">';
            $strResultado .= '<caption class="infraCaption">';
            $strResultado .= PaginaSEI::getInstance()->gerarCaptionTabela('Associar Processos a Filas', $numRegistros);
            $strResultado .= '</caption>';


            //Cabeçalho da Tabela
            $strResultado .= '<tr>';
            $strResultado .= '<th ' . $displayNoneCheck . ' class="infraTh" align="center" width="1%" >' . PaginaSEI::getInstance()->getThCheck() . '</th>';
            $strResultado .= '<th class="infraTh" width="23%">' . PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Processo', 'ProtocoloProcedimentoFormatado', $arrObjs) . '</th>';
            $strResultado .= '<th class="infraTh" width="23%">' . PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Tipo de Processo', 'IdTipoProcedimento', $arrObjs) . '</th>';

            //ADICIONAR ORDENAÇÃO PARA OS OUTROS CAMPOS
            $strResultado .= '<th class="infraTh" width="23%">' . PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Fila', 'NomeFila', $arrObjs) . '</th>';
            $strResultado .= '<th class="infraTh" width="23%">' . PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Status', 'StaAtendimentoDsmp', $arrObjs) . '</th>';

            $strResultado .= '<th class="infraTh" style="display: none">Última Fila</th>';
            //$strResultado .= '<th class="infraTh" style="display: none">Última Fila Registrada</th>';
            $strResultado .= '</tr>';


            //Linhas
            $strCssTr = '<tr class="infraTrEscura">';

            $arrSituacao = MdUtlControleDsmpINT::retornaArrSituacoesControleDsmpCompleto();

            for ($i = 0; $i < $numRegistros; $i++) {

                //vars
                $strId            = $arrObjs[$i]->getDblIdProcedimento();
                $strProcesso      = $arrObjs[$i]->getStrProtocoloProcedimentoFormatado();
                $strFila          = $arrObjs[$i]->getStrNomeFila();
                $strTpProcesso    = $arrObjs[$i]->getNumIdTipoProcedimento();
                $nomeTpProcesso   = $arrObjs[$i]->getStrNomeTipoProcedimento();
                $strStatus        = trim($arrObjs[$i]->getStrStaAtendimentoDsmp());
                $strStatus        = $strStatus == '' ? null : $strStatus;
                $strUltimaFila    = count($arrUltimasFilas) > 0 && array_key_exists($strId, $arrUltimasFilas) ? $arrUltimasFilas[$strId] : '';
                $linkProcedimento = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem=md_utl_controle_dsmp_listar&id_procedimento=' . $strId . '');

                $bolRegistroAtivo = true;

                $strCssTr = !$bolRegistroAtivo ? '<tr class="trVermelha">' : ($strCssTr == '<tr class="infraTrClara">' ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">');
                $strCssTr = in_array($strId, $arrIdProcedimentoAssociado) ? '<tr class="infraTrAcessada">' : $strCssTr;
                $strResultado .= $strCssTr;

                //Linha Checkbox
                $strResultado .= '<td ' . $displayNoneCheck . ' align="center" valign="top"  >';
                $strResultado .= PaginaSEI::getInstance()->getTrCheck($i, $strId, $strProcesso);
                $strResultado .= '</td>';

                //Linha Nome
                $strResultado .= '<td class="tdIdProcesso" style="display: none">';
                $strResultado .= $strId;
                $strResultado .= '</td>';

                //Linha Nome
                $strResultado .= '<td class="tdNomeProcesso">';
                $strResultado .= '<a href="javascript:void(0);" onclick="window.open(\'' . $linkProcedimento . '\')" alt="' . $nomeTpProcesso . '" title="' . $nomeTpProcesso . '" class="ancoraPadraoAzul">' . $strProcesso . '</a>';
                $strResultado .= '</td>';

                //Linha Descrição
                $strResultado .= '<td class="tdTipoProcesso">';
                $strResultado .= PaginaSEI::tratarHTML($nomeTpProcesso);
                $strResultado .= '</td>';

                //Linha Fila Padrão
                $strResultado .= '<td class="tdFilaProcesso">';
                $strResultado .= PaginaSEI::tratarHTML($strFila);
                $strResultado .= '</td>';

                //Linha Fila Status
                $strResultado .= '<td class="tdStatusProcesso">';
                $strResultado .= !is_null($strStatus) ? PaginaSEI::tratarHTML($arrSituacao[$strStatus]) : PaginaSEI::tratarHTML($arrSituacao[0]);
                $strResultado .= '</td>';

                //Linha Fila Desativada
                $strResultado .= '<td class="tdUltimaFilaProcesso" style="display: none">';
                $strResultado .= $strUltimaFila;
                $strResultado .= '</td>';

                //Linha Status Id
                $strResultado .= '<td class="tdIdStatusAtual" style="display: none">';
                $strResultado .= !is_null($strStatus) ? $strStatus : MdUtlControleDsmpRN::$AGUARDANDO_FILA;
                $strResultado .= '</td>';

                //Linha Status Id
                $strResultado .= '<td class="tdNomeProcessoFormatado" style="display: none">';
                $strResultado .= $strProcesso;
                $strResultado .= '</td>';

                $strResultado .= '</tr>';

            }
            $strResultado .= '</table>';
        }
    }
}



PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(':: ' . PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo . ' ::');

//Include de estilos CSS
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
if (0) { ?>
    <style><? }
    ?>
        .bloco {
            position: relative;
            float: left;
            margin-top: 1%;
            width: 90%;
        }

        .clear {
            clear: both;
        }

        .padraoSelect {
            margin-top: 1px;
            height: 21px;
        }

        /*  .inputFila{
              width: 45%!important;
          }*/

        textarea {
            resize: none;
            width: 60%;
        }

        select[multiple] {
            width: 61%;
            margin-top: 0.5%;
        }

        img[id^="imgExcluir"] {
            margin-left: -2px;
        }

        div[id^="divOpcoes"] {
            position: absolute;
            width: 1%;
            left: 62%;
            top: 44%;
        }

        img[id^="imgAjuda"] {
            margin-bottom: -4px;
        }

        #divFila {
            position: absolute;
            margin-left: 38.5%;
            margin-top: 8px;
        }

        #divTipoProcesso {
            position: absolute;
            margin-left: 57.5%;
            margin-top: 8px;
        }

        #divStatus {
            position: absolute;
            margin-left: 76.5%;
            margin-top: 8px;
        }

        #divDocumento {
            position: absolute;
            margin-left: 19.2%;
            margin-top: 9px;
        }

        #divProcesso {
            position: absolute;
            margin-top: 9px;
        }
        <?
        if (0) { ?></style><?
} ?>

<?php PaginaSEI::getInstance()->fecharStyle();

PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
if (0){ ?>
    <script type="text/javascript"><?}?>
        var msg24 = '<?php  echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_24); ?>';
        var msg25 = '<?php  echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_25); ?>';
        var msg26 = '<?php  echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_26); ?>';
        var msg27 ='<?php  echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_27); ?>';


        function inicializar() {
            var urlCtrlProcessos = document.getElementById('hdnUrlControleProcessos').value;
            var idParam = document.getElementById('hdnIdParametroCtrlUtl').value;
            var tpCtrl = document.getElementById('hdnIdTipoControleUtl').value;

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

            if ('<?= $_GET['acao'] ?>' == 'md_utl_ctrl_controle_dsmp_selecionar') {
                infraReceberSelecao();
                document.getElementById('btnFecharSelecao').focus();
            } else {
                infraEfeitoTabelas();
            }

            addEnter();
        }

        function addEnter() {
            document.getElementById('txtProcesso').addEventListener("keypress", function (evt) {
                addPesquisarEnter(evt);
            });

            document.getElementById('txtDocumento').addEventListener("keypress", function (evt) {
                addPesquisarEnter(evt);
            });
        }

        function addPesquisarEnter(evt) {
            var key_code = evt.keyCode ? evt.keyCode :
                evt.charCode ? evt.charCode :
                    evt.which ? evt.which : void 0;

            if (key_code == 13) {
                pesquisar();
            }

        }

        function associacaoIsPermitida(){
            var valido = true;
            var linhas = document.getElementsByClassName('infraTrMarcada');
            var msgInicio = msg26;
            for(var i = 0; i < linhas.length; i++){
                var idStatus = $(linhas[i]).find('.tdIdStatusAtual').text();

                if(idStatus > 4){
                    if(valido){
                        msgInicio  += "\n";
                    }

                    valido = false;
                    msgInicio  += "\n";
                    msgInicio +=  " - " + $(linhas[i]).find('.tdNomeProcessoFormatado').text();

                }
            }

            if(!valido) {
                alert(msgInicio);
            }

            return valido;
        }


        function associarFila() {
            var valido          = true;
            var numeroRegistroTela = '<?= $numRegistros ?>';

            if(numeroRegistroTela == 0){
                alert(msg27);
                return false;
            }

            var numSelecionados = infraNroItensSelecionados();
            valido = numSelecionados != 0;

            if (!valido) {
                alert(msg27);
                return false;
            }

           valido = associacaoIsPermitida();

            if(valido){
                infraAbrirJanela('<?=$strLinkAssociarFila?>', 'janelaAssinatura', 700, 450, 'location=0,status=1,resizable=1,scrollbars=1');
            }
        }

        function pesquisar() {
            document.getElementById('frmTpControleLista').action = '<?= $strUrlPesquisar ?>';
            document.getElementById('frmTpControleLista').submit();
        }

        function fechar() {
            location.href = "<?= $strUrlFechar ?>";
        }

        <?php if (0){ ?>
    </script><? } ?>

<?php PaginaSEI::getInstance()->fecharJavaScript(); ?>


<?php
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
    <form id="frmTpControleLista" method="post"
          action="<?= PaginaSEI::getInstance()->formatarXHTML(
              SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])
          ) ?>">

        <?php
        PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        PaginaSEI::getInstance()->abrirAreaDados('7em');
        ?>
        <div style="width: 20%;" class="bloco" id="divProcesso">
            <label id="lblProcesso" for="txtProcesso" class="infraLabelOpcional">
                Processo:
            </label>

            <div class="clear"></div>

            <input type="text" style="width: 85%" id="txtProcesso" name="txtProcesso" class="inputFila infraText"
                   size="30"
                   value="<?php echo array_key_exists('txtProcesso', $_POST) ? $_POST['txtProcesso'] : '' ?>"
                   maxlength="100" tabindex="502"/>
        </div>

        <div style="width: 20%;" class="bloco" id="divDocumento">
            <label id="lblDocumento" for="txtDocumento" accesskey="S" class="infraLabelOpcional">
                Documento SEI:
            </label>

            <div class="clear"></div>

            <input type="text" style="width: 85%" id="txtDocumento" name="txtDocumento" class="inputFila infraText"
                   size="30"
                   value="<?php echo array_key_exists('txtDocumento', $_POST) ? $_POST['txtDocumento'] : '' ?>"
                   maxlength="100" tabindex="502"/>
        </div>

        <div id="divFila" style="width: 20%;">
            <label id="lblFila" for="selFila" accesskey="" class="infraLabelOpcional">Fila:</label>
            <select style="width:85%" id="selFila" name="selFila" class="infraSelect padraoSelect"
                    onchange="pesquisar();"
                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                <?= $selFila ?>
            </select>
        </div>


        <div id="divTipoProcesso" style="width: 20%;">
            <label id="lblTipoProcesso" for="selTipoProcesso" accesskey="" class="infraLabelOpcional">Tipo de
                Processo:</label>
            <select style="width:85%" id="selTipoProcesso" name="selTipoProcesso" class="infraSelect padraoSelect"
                    onchange="pesquisar();"
                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                <option value=""></option>
                <?= $selTipoProcesso ?>
            </select>
        </div>

        <div id="divStatus" style="width: 20%;">
            <label id="lblStatus" for="selStatus" accesskey="" class="infraLabelOpcional">Status:</label>
            <select style="width:85%" id="selStatus" name="selStatus" class="infraSelect padraoSelect"
                    onchange="pesquisar();"
                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                <?= $selStatus ?>
            </select>
        </div>

        <input type="hidden" id="hdnIdTipoControleUtl" name="hdnIdTipoControleUtl"
               value="<?php echo is_null($idTipoControle) ? '0' : $idTipoControle; ?>"/>
        <input type="hidden" id="hdnIdParametroCtrlUtl" name="hdnIdParametroCtrlUtl"
               value="<?php echo $isParametrizado ? '1' : '0'; ?>"/>
        <input type="hidden" id="hdnDadosAssociarFila" name="hdnDadosAssociarFila"/>
        <input type="hidden" id="hdnUrlControleProcessos" name="hdnUrlControleProcessos"
               value="<?php echo SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_controlar&acao_origem=' . $_GET['acao']); ?>"/>
        <?php
        PaginaSEI::getInstance()->fecharAreaDados();
        PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros);
        PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
        ?>

    </form>

<?php
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();