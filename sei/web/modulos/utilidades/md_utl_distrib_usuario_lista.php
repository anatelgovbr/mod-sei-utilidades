<?php
/**
 * Created by PhpStorm.
 * User: thamires.zamai
 * Date: 11/01/2019
 * Time: 10:57
 */

require_once dirname(__FILE__) . '/../../SEI.php';

session_start();
SessaoSEI::getInstance()->validarLink();
$arrIdProcedimentoDistrib = array();
if (isset($_SESSION['IDS_PROCEDIMENTOS_DISTRIBUICAO'])) {
    $arrIdProcedimentoDistrib = $_SESSION['IDS_PROCEDIMENTOS_DISTRIBUICAO'];
    unset($_SESSION['IDS_PROCEDIMENTOS_DISTRIBUICAO']);
}


PaginaSEI::getInstance()->salvarCamposPost(array('txtProcessoUtlDist','txtDocumento', 'selFilaUtlDist', 'selTipoProcessoUtlDist','selResponsavelUtlDist', 'selStatusUtlDist'));

$txtProcessoCampo     = array_key_exists('txtProcessoUtlDist', $_POST) ? $_POST['txtProcessoUtlDist'] : PaginaSEI::getInstance()->recuperarCampo('txtProcessoUtlDist');
$txtDocumentoCampo    = array_key_exists('txtDocumentoUtlDist', $_POST) ? $_POST['txtDocumentoUtlDist'] : PaginaSEI::getInstance()->recuperarCampo('txtDocumentoUtlDist');
$selFilaCampo         = array_key_exists('selFilaUtlDist', $_POST) ? $_POST['selFilaUtlDist'] : PaginaSEI::getInstance()->recuperarCampo('selFilaUtlDist');
$selTipoProcessoCampo = array_key_exists('selTipoProcessoUtlDist', $_POST) ? $_POST['selTipoProcessoUtlDist'] : PaginaSEI::getInstance()->recuperarCampo('selTipoProcessoUtlDist');
$selResponsavelCampo  = array_key_exists('selResponsavelUtlDist', $_POST) ? $_POST['selResponsavelUtlDist'] : PaginaSEI::getInstance()->recuperarCampo('selResponsavelUtlDist');
$selStatusCampo       = array_key_exists('selStatusUtlDist', $_POST) ? $_POST['selStatusUtlDist'] : PaginaSEI::getInstance()->recuperarCampo('selStatusUtlDist');

$arrPostDados = array('txtProcesso' => $txtProcessoCampo, 'txtDocumento'=> $txtDocumentoCampo, 'selFila' => $selFilaCampo, 'selTipoProcesso'=> $selTipoProcessoCampo, 'selResponsavel' => $selResponsavelCampo, 'selStatus'=> $selStatusCampo);

//Id tipo de controle
$objFilaRN                 = new MdUtlAdmFilaRN();
$objMdUtlAdmFilaPrmGrUsuRN = new MdUtlAdmFilaPrmGrUsuRN();
$objMdUtlAdmTpCtrlUsuRN    = new MdUtlAdmRelTpCtrlDesempUsuRN();
$objMdUtlControleDsmpRN  = new MdUtlControleDsmpRN();
$objMdUtlAdmTpCtrlUndRN    = new MdUtlAdmRelTpCtrlDesempUndRN();

$idTipoControle            = $objMdUtlAdmTpCtrlUndRN->getTipoControleUnidadeLogada();
$arrObjsFilaDTO            = $objFilaRN->getFilasTipoControle($idTipoControle);

$idsFilasPermitidas        = InfraArray::converterArrInfraDTO($arrObjsFilaDTO, 'IdMdUtlAdmFila');
$arrObjsFilaUsuDTO         = $objMdUtlAdmFilaPrmGrUsuRN->getPapeisDeUsuario($idsFilasPermitidas);

$idParametro = null;

$idsFilasResponsavel       = $selFilaCampo != '' ? array($selFilaCampo) : $idsFilasPermitidas;
$arrObjsResponsavelDTO     = $objMdUtlAdmFilaPrmGrUsuRN->getResponsavelPorFila($idsFilasResponsavel);

$arrObjsResponsavelDTO     = !is_null($arrObjsResponsavelDTO) ? InfraArray::distinctArrInfraDTO($arrObjsResponsavelDTO, 'IdUsuario') : null;
$isPermiteAssociacao       = false;
$isPermiteAssociacao       = $objMdUtlControleDsmpRN->validaVisualizacaoUsuarioLogado($idTipoControle);



if (!is_null($idTipoControle)) {
    $objMdUtlAdmUtlTpCtrlRN = new MdUtlAdmTpCtrlDesempRN();
    $isParametrizado = $objMdUtlAdmUtlTpCtrlRN->verificaTipoControlePossuiParametrizacao($idTipoControle);
}

if (!is_null($idTipoControle) && $isParametrizado) {
    $isGestorSipSei = $objMdUtlAdmTpCtrlUsuRN->usuarioLogadoIsGestorSipSei();
    $idsStatusPermitido = $objMdUtlControleDsmpRN->getStatusPermitido($arrObjsFilaUsuDTO, $isGestorSipSei);
//URL Base
    $strUrl = 'controlador.php?acao=md_utl_distrib_usuario_';

//URL das Actions
    $strLinkDistribuir = SessaoSEI::getInstance()->assinarLink($strUrl . 'cadastrar&acao_origem=' . $_GET['acao'] . '&id_tp_controle_desmp=' . $idTipoControle.'&acao_retorno='.$_GET['acao']);
    $strUrlPesquisar = SessaoSEI::getInstance()->assinarLink($strUrl . 'listar&acao_origem=' . $_GET['acao'] . '&id_tipo_controle_utl=' . $idTipoControle);
    $strUrlFechar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_controlar&acao_origem=' . $_GET['acao']);
    $idsFilasPermitidasUsBasico = $isGestorSipSei || count($arrObjsFilaUsuDTO) == 0 ? null : InfraArray::converterArrInfraDTO($arrObjsFilaUsuDTO, 'IdMdUtlAdmFila');

    if ($isGestorSipSei) {
        $selFila = MdUtlAdmFilaINT::montarSelectFilas($selFilaCampo, $arrObjsFilaDTO);
    } else {
        $selFila = count($idsFilasPermitidasUsBasico) > 0 ? $selFila = MdUtlAdmFilaINT::montarSelectFilas($selFilaCampo, $arrObjsFilaDTO, $idsFilasPermitidasUsBasico) : null;
    }

    if($isGestorSipSei){

        $selResponsavel = MdUtlAdmFilaPrmGrUsuINT::montarSelectResponsavel($selResponsavelCampo, $arrObjsResponsavelDTO);
    }else{
        $selResponsavel = '';
    }

    $selStatus = count($idsStatusPermitido) > 0 || $isGestorSipSei ? MdUtlControleDsmpINT::montarSelectStatus($selStatusCampo, false, $idsStatusPermitido) : null;
    $arrObjsTpProcesso = $objMdUtlControleDsmpRN->getTiposProcessoTipoControle($idTipoControle);
    $selTipoProcesso = $isPermiteAssociacao  ? InfraINT::montarSelectArrInfraDTO(null, null, $selTipoProcessoCampo, $arrObjsTpProcesso, 'IdTipoProcedimento', 'NomeProcedimento') : '';
}

$strTitulo = 'Distribuição';

switch ($_GET['acao']) {

    //region Listar
    case 'md_utl_distrib_usuario_listar':

        break;
    //endregion

    //region Erro
    default:
        throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    //endregion
}


//Verifica se é ação Selecionar
$bolSelecionar = $_GET['acao'] == 'md_utl_adm_fila_selecionar';

$arrComandos[] = '<button type="button" accesskey="P" id="btnPesquisar" onclick="pesquisar()" class="infraButton">
                                        <span class="infraTeclaAtalho">P</span>esquisar</button>';

if (!is_null($idTipoControle) && $isPermiteAssociacao) {
    //Botões de ação do topo
    $arrComandos[] = '<button type="button" accesskey="i" id="btnAssoFila" onclick="distribuir(true, false, false, false)" class="infraButton">
                                        D<span class="infraTeclaAtalho">i</span>stribuir</button>';
}


$arrComandos[] = '<button type="button" accesskey="c" id="btnFechar" onclick="fechar()" class="infraButton">
                                        Fe<span class="infraTeclaAtalho">c</span>har</button>';
$numRegistros = 0;
if (!is_null($idTipoControle) && $isParametrizado) {
    //Configuração da Paginação
    if((count($arrObjsFilaDTO) == 0 && !$isGestorSipSei) || !$isPermiteAssociacao){
        $objDTO = null;
    }else {
        $objDTO = $objMdUtlControleDsmpRN->getObjDTOParametrizadoDistrib(array($arrObjsFilaUsuDTO, $isGestorSipSei, $idTipoControle, $arrPostDados));
    }

    if (!is_null($objDTO)) {

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

        $arrObjs      = $objMdUtlControleDsmpRN->listarProcessos($objDTO);
        $numRegistros = count($arrObjs);

        PaginaSEI::getInstance()->processarPaginacao($objDTO);

        //Tabela de resultado.
        if ($numRegistros > 0) {
            $displayNoneCheck = $isPermiteAssociacao ? '' : 'style="display:none"';
            $strResultado .= '<table width="99%" class="infraTable" summary="Processos" id="tbCtrlProcesso">';
            $strResultado .= '<caption class="infraCaption">';
            $strResultado .= PaginaSEI::getInstance()->gerarCaptionTabela('Distribuição', $numRegistros);
            $strResultado .= '</caption>';


            //Cabeçalho da Tabela
            $strResultado .= '<tr>';
            $strResultado .= '<th ' . $displayNoneCheck . ' class="infraTh" align="center" width="1%" >' . PaginaSEI::getInstance()->getThCheck() . '</th>';
            $strResultado .= '<th class="infraTh" width="18%">' . PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Processo', 'ProtocoloProcedimentoFormatado', $arrObjs) . '</th>';
            $strResultado .= '<th class="infraTh" width="19%">' . PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Tipo de Processo', 'IdTipoProcedimento', $arrObjs) . '</th>';

            //ADICIONAR ORDENAÇÃO PARA OS OUTROS CAMPOS

            $strResultado .= '<th class="infraTh" width="13%">' . PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Fila', 'NomeFila', $arrObjs) . '</th>';
            $strResultado .= '<th class="infraTh" width="10%" style="text-align: left">'. PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Unidade de Esforço', 'UnidadeEsforco', $arrObjs) .' </th>';
            $strResultado .= '<th class="infraTh" width="16%" style="text-align: left">'. PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Responsável', 'NomeUsuarioDistribuicao', $arrObjs) .'</th>';
            $strResultado .= '<th class="infraTh" width="15%">' . PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Status', 'StaAtendimentoDsmp', $arrObjs) . '</th>';
            $strResultado .= '<th class="infraTh" width="16%">' . PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Data Registro Status', 'Atual', $arrObjs) . '</th>';

            if($isPermiteAssociacao) {
                $strResultado .= '<th class="infraTh" width="16%"> Ações </th>';
            }

            $strResultado .= '<th class="infraTh" style="display: none">Última Fila</th>';
            $strResultado .= '</tr>';


            //Linhas
            $strCssTr = '<tr class="infraTrEscura">';

            for ($i = 0; $i < $numRegistros; $i++) {

                $strId            = $arrObjs[$i]->getDblIdProcedimento();
                $strProcesso      = $arrObjs[$i]->getStrProtocoloProcedimentoFormatado();
                $strFila          = $arrObjs[$i]->getStrNomeFila();
                $strTpProcesso    = $arrObjs[$i]->getNumIdTipoProcedimento();
                $nomeTpProcesso   = $arrObjs[$i]->getStrNomeTipoProcedimento();
                $strStatus        = trim($arrObjs[$i]->getStrStaAtendimentoDsmp());
                $numIdControleDsmp = $arrObjs[$i]->getNumIdMdUtlControleDsmp();
                $arrSituacao      = MdUtlControleDsmpINT::retornaArrSituacoesControleDsmp();
                $linkProcedimento = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem=md_utl_distrib_usuario_listar&id_procedimento=' . $strId . '');
                $data             = explode(' ', $arrObjs[$i]->getDthAtual());
                $dataFormatada    = $data[0];
                $bolRegistroAtivo = true;

                $strCssTr = !$bolRegistroAtivo ? '<tr class="trVermelha">' : ($strCssTr == '<tr class="infraTrClara">' ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">');
                $strCssTr = in_array($strId, $arrIdProcedimentoDistrib) ? '<tr class="infraTrAcessada">' : $strCssTr;
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

                //Linha Unidade de Esforço
                $strResultado .= '<td class="tdUniEsforco">';
                $strResultado .=  $arrObjs[$i]->getNumUnidadeEsforco();
                $strResultado .= '</td>';

                //Linha Responsável
                $strResultado .= '<td class="tdResponsavel">';
                $strResultado .= '<a class="ancoraSigla" href="javascript:void(0);" alt="' . PaginaSEI::tratarHTML($arrObjs[$i]->getStrNomeUsuarioDistribuicao()) . '" title="' . PaginaSEI::tratarHTML($arrObjs[$i]->getStrNomeUsuarioDistribuicao()) . '">' . PaginaSEI::tratarHTML($arrObjs[$i]->getStrSiglaUsuarioDistribuicao()) . '</a>';
                $strResultado .= '</td>';

                //Linha Fila Status
                $strResultado .= '<td class="tdStatusProcesso">';
                $strResultado .= !is_null($strStatus) ? PaginaSEI::tratarHTML($arrSituacao[$strStatus]) : PaginaSEI::tratarHTML($arrSituacao[0]);
                $strResultado .= '</td>';

                //Linha Data Registro Status
                $strResultado .= '<td class="tdDtRegistroStatus">';
                $strResultado .= PaginaSEI::tratarHTML($dataFormatada);
                $strResultado .= '</td>';

                //Linha Açôes
                if($isPermiteAssociacao) {
                    $strResultado .= '<td class="tdAcoes">';
                    $btnDistribuir = '<img src="modulos/utilidades/imagens/distribuir1.png" id="btnDistribuicao" style="margin-left: 30%" onclick="distribuir(false ,\'' . $numIdControleDsmp . '\' ,\'' . $strStatus . '\' ,\'' . $arrObjs[$i]->getNumIdFila() . '\');" title="Distribuir" alt="Distribuir" class="infraImg" />';
                    $strResultado .= $btnDistribuir;
                    $strResultado .= '</td>';
                }

                //Linha Controle Dsmp
                $strResultado .= '<td class="tdIdControleDsmp" style="display: none">';
                $strResultado .= $numIdControleDsmp;
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
            width: 78%;
        }

        .padraoInput {
            width: 78%;
        }


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

        #divProcesso {
            position: absolute;
            margin-top: 10px;
            width: 17.1%;
        }

        #divDocumento {
            position: absolute;
            margin-left: 14.8%;
            margin-top: 10px;
            width: 11.5%;
        }

        #divFila {
            position: absolute;
            margin-left: 25%;
            margin-top: 8px;
            width: 20.5%;
        }

        #divTipoProcesso {
            position: absolute;
            margin-left: 42%;
            margin-top: 8px;
            width: 22%;
        }

        #divResponsavel {
            position: absolute;
            margin-left: 60.1%;
            margin-top: 8px;
            width: 23%;
        }


        #divStatus {
            position: absolute;
            margin-left: 79%;
            margin-top: 8px;
            width: 20%;
        }

        <?
        if (0) { ?></style><?
} ?>

<?php PaginaSEI::getInstance()->fecharStyle();

PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
if (0){ ?>
    <script type="text/javascript"><?}?>
        var msg57 = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_57); ?>';
        var msg58 = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_58); ?>';
        var msg59 = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_59); ?>';
        var msg24 = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_24); ?>';
        var msg25 = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_25); ?>';

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

            if ('<?= $_GET['acao'] ?>' == 'md_utl_distrib_usuario_selecionar') {
                infraReceberSelecao();
                document.getElementById('btnFecharSelecao').focus();
            } else {
                infraEfeitoTabelas();
            }

            addEnter();   
        }
        

        function addEnter() {
            document.getElementById('txtProcessoUtlDist').addEventListener("keypress", function (evt) {
                addPesquisarEnter(evt);
            });

            document.getElementById('txtDocumentoUtlDist').addEventListener("keypress", function (evt) {
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

        function distribuir(multiplo, idSelecionado, idStatus, idFila){
            var numeroRegistroTela = '<?= $numRegistros ?>';
            var isValido = true;

            if(numeroRegistroTela == 0){
                alert(msg59);
                return false;
            }

            if(multiplo){
                isValido = realizarValidacoesFiltro();
            }

            if(isValido) {
                preencherHiddenDistribuicao(multiplo, idSelecionado);
                enviarStatusFila(multiplo, idStatus, idFila);
                document.getElementById('frmTpControleLista').action = '<?=$strLinkDistribuir?>';
                document.getElementById('frmTpControleLista').submit();
            }
        }

        function enviarStatusFila(multiplo, idStatus, idFila){
            var idStatusEnviar = multiplo  ? document.getElementById('selStatusUtlDist').value : idStatus;
            var idFilaEnviar   = multiplo  ?  document.getElementById('selFilaUtlDist').value : idFila;
            document.getElementById('hdnSelStatus').value = idStatusEnviar;
            document.getElementById('hdnSelFila').value = idFilaEnviar;
        }

        function realizarValidacoesFiltro(){

            var numSelecionados = infraNroItensSelecionados();

            var selFila = document.getElementById('selFilaUtlDist').value;
            var selStatus = document.getElementById('selStatusUtlDist').value;

            if(selFila == 0 ){
                alert(msg57);
                return false;
            }

            if(selStatus == 0) {
                alert(msg58);
                return false;
            }

            if (numSelecionados == 0) {
                alert(msg59);
                return false;
            }

            return true;
        }


        function pesquisar() {
            document.getElementById('frmTpControleLista').action = '<?= $strUrlPesquisar ?>';
            document.getElementById('frmTpControleLista').submit();
        }

        function fechar() {
            location.href = "<?= $strUrlFechar ?>";
        }

        function preencherHiddenDistribuicao(multiplo, idSelecionado){
            var json = '';
            var linhas = new Array();

            if(multiplo) {
                var objs = document.getElementsByClassName('infraTrMarcada');

                for (var i = 0; i < objs.length; i++) {
                    var idControleDsmp = $(objs[i]).find('.tdIdControleDsmp').text();
                    linhas.push(idControleDsmp);
                }

            }else{
                linhas.push(idSelecionado);
            }

            if(linhas.length > 0) {
                json = JSON.stringify(linhas);
                document.getElementById('hdnDistribuicao').value = json;
            }
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
        <div class="bloco" id="divProcesso">
            <label id="lblProcesso" for="txtProcessoUtlDist" class="infraLabelOpcional">
                Processo:
            </label>

            <div class="clear"></div>

            <input type="text" id="txtProcessoUtlDist" name="txtProcessoUtlDist" class="inputFila infraText padraoInput"
                   size="30"
                   value="<?php echo $txtProcessoCampo ?>"
                   maxlength="100" tabindex="502"/>
        </div>

        <div class="bloco" id="divDocumento">
            <label id="lblDocumento" for="txtDocumentoUtlDist" accesskey="S" class="infraLabelOpcional">
                Documento SEI:
            </label>

            <div class="clear"></div>

            <input type="text" id="txtDocumentoUtlDist" name="txtDocumentoUtlDist" class="inputFila infraText padraoInput"
                   size="30"
                   value="<?php echo $txtDocumentoCampo ?>"
                   maxlength="100" tabindex="502"/>
        </div>

        <div id="divFila">
            <label id="lblFila" for="selFilaUtlDist" accesskey="" class="infraLabelOpcional">Fila:</label>
            <select id="selFilaUtlDist" name="selFilaUtlDist" class="infraSelect padraoSelect"
                    onchange="pesquisar();"
                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                <?= $selFila ?>
            </select>
        </div>


        <div id="divTipoProcesso">
            <label id="lblTipoProcesso" for="selTipoProcessoUtlDist" accesskey="" class="infraLabelOpcional">Tipo de
                Processo:</label>
            <select id="selTipoProcessoUtlDist" name="selTipoProcessoUtlDist" class="infraSelect padraoSelect"
                    onchange="pesquisar();"
                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                <option value=""></option>
                <?= $selTipoProcesso ?>
            </select>
        </div>

        <div id="divResponsavel">
            <label id="lblResponsavel" for="selResponsavelUtlDist" accesskey="" class="infraLabelOpcional">Responsável:</label>
            <select <?php echo !$isGestorSipSei ? 'disabled="disabled' : ''; ?> id="selResponsavelUtlDist" name="selResponsavelUtlDist" class="infraSelect padraoSelect"
                    onchange="pesquisar();"
                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                <?= $selResponsavel ?>
            </select>
        </div>

        <div id="divStatus">
            <label id="lblStatus" for="selStatusUtlDist" accesskey="" class="infraLabelOpcional">Status:</label>
            <select id="selStatusUtlDist" name="selStatusUtlDist" class="infraSelect padraoSelect"
                    onchange="pesquisar();"
                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                <?= $selStatus ?>
            </select>
        </div>

        <input type="hidden" id="hdnSelStatus" name="hdnSelStatus" value=""/>
        <input type="hidden" id="hdnSubmit" name="hdnSubmit" value="<?php echo $vlControlePost; ?>"/>
        <input type="hidden" id="hdnSelFila" name="hdnSelFila" value=""/>
        <input type="hidden" id="hdnIdTipoControleUtl" name="hdnIdTipoControleUtl"
               value="<?php echo is_null($idTipoControle) ? '0' : $idTipoControle; ?>"/>
        <input type="hidden" id="hdnIdParametroCtrlUtl" name="hdnIdParametroCtrlUtl"
               value="<?php echo $isParametrizado ? '1' : '0'; ?>"/>
        <input type="hidden" id="hdnDadosAssociarFila" name="hdnDadosAssociarFila"/>
        <input type="hidden" id="hdnDistribuicao" name="hdnDistribuicao"/>
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