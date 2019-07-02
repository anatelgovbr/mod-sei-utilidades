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


PaginaSEI::getInstance()->salvarCamposPost(array('txtProcessoUtlMs', 'selFilaUtlMs', 'selTipoProcessoUtlMs', 'selStatusUtlMs'));

$txtProcessoCampo     = array_key_exists('txtProcessoUtlMs', $_POST) ? $_POST['txtProcessoUtlMs'] : PaginaSEI::getInstance()->recuperarCampo('txtProcessoUtlMs');
$selFilaCampo         = array_key_exists('selFilaUtlMs', $_POST) ? $_POST['selFilaUtlMs'] : PaginaSEI::getInstance()->recuperarCampo('selFilaUtlMs');
$selTipoProcessoCampo = array_key_exists('selTipoProcessoUtlMs', $_POST) ? $_POST['selTipoProcessoUtlMs'] : PaginaSEI::getInstance()->recuperarCampo('selTipoProcessoUtlMs');
$selStatusCampo       = array_key_exists('selStatusUtlMs', $_POST) ? $_POST['selStatusUtlMs'] : PaginaSEI::getInstance()->recuperarCampo('selStatusUtlMs');
$somaUndEsforco = '';

$arrPostDados = array('txtProcesso' => $txtProcessoCampo, 'selFila' => $selFilaCampo, 'selTipoProcesso'=> $selTipoProcessoCampo, 'selStatus'=> $selStatusCampo);

//Id tipo de controle
$objFilaRN                 = new MdUtlAdmFilaRN();
$objMdUtlAdmFilaPrmGrUsuRN = new MdUtlAdmFilaPrmGrUsuRN();
$objMdUtlAdmTpCtrlUsuRN    = new MdUtlAdmRelTpCtrlDesempUsuRN();
$objMdUtlControleDsmpRN     = new MdUtlControleDsmpRN();
$objMdUtlAdmTpCtrlUndRN    = new MdUtlAdmRelTpCtrlDesempUndRN();

$idTipoControle            = $objMdUtlAdmTpCtrlUndRN->getTipoControleUnidadeLogada();
$arrObjsFilaDTO            = $objFilaRN->getFilasTipoControle($idTipoControle);

$idsFilasPermitidas        = InfraArray::converterArrInfraDTO($arrObjsFilaDTO, 'IdMdUtlAdmFila');
$arrObjsFilaUsuDTO         = $objMdUtlAdmFilaPrmGrUsuRN->getPapeisDeUsuario($idsFilasPermitidas);

$idParametro = null;

$isPermiteAssociacao       = false;
$isPermiteAssociacao       = $objMdUtlControleDsmpRN->validaVisualizacaoUsuarioLogado($idTipoControle);

if (!is_null($idTipoControle)) {
    $objMdUtlAdmUtlTpCtrlRN = new MdUtlAdmTpCtrlDesempRN();
    $isParametrizado = $objMdUtlAdmUtlTpCtrlRN->verificaTipoControlePossuiParametrizacao($idTipoControle);
}

if (!is_null($idTipoControle) && $isParametrizado) {

    $isGestorSipSei = $objMdUtlAdmTpCtrlUsuRN->usuarioLogadoIsGestorSipSei();
    $idsStatusPermitido = $objMdUtlControleDsmpRN->getStatusPermitido($arrObjsFilaUsuDTO, $isGestorSipSei, true);

//URL Base
    $strUrl = 'controlador.php?acao=md_utl_meus_processos_dsmp_';

//URL das Actions
    $strUrlPesquisar = SessaoSEI::getInstance()->assinarLink($strUrl . 'listar&acao_origem=' . $_GET['acao'] . '&id_tipo_controle_utl=' . $idTipoControle);
    $strUrlFechar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_controlar&acao_origem=' . $_GET['acao']);


    $idsFilasPermitidasUsBasico = $isGestorSipSei || count($arrObjsFilaUsuDTO) == 0 ? null : InfraArray::converterArrInfraDTO($arrObjsFilaUsuDTO, 'IdMdUtlAdmFila');

    if ($isGestorSipSei) {
        $selFila = MdUtlAdmFilaINT::montarSelectFilas($selFilaCampo, $arrObjsFilaDTO);
    } else {
        $selFila = count($idsFilasPermitidasUsBasico) > 0 ? $selFila = MdUtlAdmFilaINT::montarSelectFilas($selFilaCampo, $arrObjsFilaDTO, $idsFilasPermitidasUsBasico) : null;
    }

    $selStatus         =  count($idsStatusPermitido) > 0 || $isGestorSipSei ? MdUtlControleDsmpINT::montarSelectStatus($selStatusCampo, false, $idsStatusPermitido): null;
    $arrObjsTpProcesso = $objMdUtlControleDsmpRN->getTiposProcessoTipoControle($idTipoControle);
    $selTipoProcesso   = $isPermiteAssociacao  ? InfraINT::montarSelectArrInfraDTO(null, null, $selTipoProcessoCampo, $arrObjsTpProcesso, 'IdTipoProcedimento', 'NomeProcedimento') : '';
}

$strTitulo = 'Meus Processos';

switch ($_GET['acao']) {

    //region Listar
    case 'md_utl_meus_processos_dsmp_listar':

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


$arrComandos[] = '<button type="button" accesskey="c" id="btnFechar" onclick="fechar()" class="infraButton">
                                        Fe<span class="infraTeclaAtalho">c</span>har</button>';
$numRegistros = 0;
if (!is_null($idTipoControle) && $isParametrizado) {

    //Configuração da Paginação
    if((count($arrObjsFilaDTO) == 0)){
        $objDTO = null;
    }else {
        $objDTO = $objMdUtlControleDsmpRN->getObjDTOParametrizadoMeusProcessos(array($arrObjsFilaUsuDTO, $isGestorSipSei, $arrObjsTpProcesso, $idTipoControle, $arrPostDados));
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
            $displayNoneCheck = 'style="display:none"';
            $strResultado .= '<table width="99%" class="infraTable" summary="Processos" id="tbCtrlProcesso">';
            $strResultado .= '<caption class="infraCaption">';
            $strResultado .= PaginaSEI::getInstance()->gerarCaptionTabela('Meus Processos', $numRegistros);
            $strResultado .= '</caption>';


            //Cabeçalho da Tabela
            $strResultado .= '<tr>';
            $strResultado .= '<th ' . $displayNoneCheck . ' class="infraTh" align="center" width="1%" >' . PaginaSEI::getInstance()->getThCheck() . '</th>';
            $strResultado .= '<th class="infraTh" width="18%">' . PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Processo', 'ProtocoloProcedimentoFormatado', $arrObjs) . '</th>';
            $strResultado .= '<th class="infraTh" width="16%">' . PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Tipo de Processo', 'IdTipoProcedimento', $arrObjs) . '</th>';

            //ADICIONAR ORDENAÇÃO PARA OS OUTROS CAMPOS

            $strResultado .= '<th class="infraTh" width="15%">' . PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Fila', 'NomeFila', $arrObjs) . '</th>';
            $strResultado .= '<th class="infraTh" width="10%" style="text-align: left">'. PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Unidade de Esforço', 'UnidadeEsforco', $arrObjs) .' </th>';
            $strResultado .= '<th class="infraTh" width="14%">' . PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Status', 'StaAtendimentoDsmp', $arrObjs) . '</th>';
            $strResultado .= '<th class="infraTh" width="14%">' . PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Data Registro Status', 'Atual', $arrObjs) . '</th>';
            $strResultado .= '<th class="infraTh" width="14%">'  . PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Prazo', 'PrazoTarefa',$arrObjs) . ' </th>';
            $strResultado .= '<th class="infraTh" style="display: none">Última Fila</th>';
            $strResultado .= '</tr>';


            //Linhas
            $strCssTr = '<tr class="infraTrEscura">';

            for ($i = 0; $i < $numRegistros; $i++) {


                $strId              = $arrObjs[$i]->getDblIdProcedimento();
                $strProcesso        = $arrObjs[$i]->getStrProtocoloProcedimentoFormatado();
                $strFila            = $arrObjs[$i]->getStrNomeFila();
                $idFila             = $arrObjs[$i]->getNumIdFila();
                $strTpProcesso      = $arrObjs[$i]->getNumIdTipoProcedimento();
                $nomeTpProcesso     = $arrObjs[$i]->getStrNomeTipoProcedimento();
                $strStatus          = trim($arrObjs[$i]->getStrStaAtendimentoDsmp());
                $numIdControleDsmp   = $arrObjs[$i]->getNumIdMdUtlControleDsmp();
                $numUndEsforco      = $arrObjs[$i]->getNumUnidadeEsforco();
                $arrSituacao        = MdUtlControleDsmpINT::retornaArrSituacoesControleDsmp();
                $linkProcedimento   = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem=md_utl_meus_processos_dsmp_listar&id_procedimento=' . $strId . '');
                $data               = explode(' ', $arrObjs[$i]->getDthAtual());
                $dataFormatada      = $data[0];
                $dataPrazo          = explode(' ', $arrObjs[$i]->getDthPrazoTarefa());
                $dataPrazoFormatada = $dataPrazo[0];
                $bolRegistroAtivo   = true;

                $isPossuiAnalise  = $objMdUtlControleDsmpRN->verificaTriagemPossuiAnalise($arrObjs[$i]);
                $arrCtrlUrls      = MdUtlControleDsmpINT::retornaUrlsAcessoDsmp($strStatus, $isPossuiAnalise, $strId, $idFila, $arrObjs[$i]->getNumIdUsuarioDistribuicao(), true);
                $linkStatus       = MdUtlControleDsmpINT::retornaLinkStatus($arrCtrlUrls, $strStatus);
                
                $status            = !is_null($strStatus) ? PaginaSEI::tratarHTML($arrSituacao[$strStatus]) : PaginaSEI::tratarHTML($arrSituacao[0]);

                $somaUndEsforco   += $numUndEsforco;

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
                $strResultado .=  PaginaSEI::tratarHTML($numUndEsforco);
                $strResultado .= '</td>';


                //Linha Fila Status
                $strResultado .= '<td class="tdStatusProcesso">';
                $strResultado .= '<a href="'.$linkStatus.'" alt="' . $nomeTpProcesso . '" title="' . $nomeTpProcesso . '" class="ancoraPadraoAzul">' . $status . '</a>';

                $strResultado .= '</td>';

                //Linha Data Registro Status
                $strResultado .= '<td class="tdDtRegistroStatus">';
                $strResultado .= PaginaSEI::tratarHTML($dataFormatada);
                $strResultado .= '</td>';

                //Linha Prazo
                $strResultado .= '<td class="tdPrazo">';
                $strResultado .= PaginaSEI::tratarHTML($dataPrazoFormatada);
                $strResultado .= '</td>';

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

        #divFila {
            position: absolute;
            margin-left: 14.8%;
            margin-top: 8px;
            width: 20.5%;
        }

        #divTipoProcesso {
            position: absolute;
            margin-left: 32%;
            margin-top: 8px;
            width: 24%;
        }

        #divStatus {
            position: absolute;
            margin-left: 52%;
            margin-top: 8px;
            width: 24%;
        }

        #divSomaUndEsforco {
            margin-top: 15px;
            position: absolute;
        }

        .tdNomeProcesso, .tdStatusProcesso{
            font-size: 0.92em;
        }

        <?
        if (0) { ?></style><?
} ?>

<?php PaginaSEI::getInstance()->fecharStyle();

PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
if (0){ ?>
    <script type="text/javascript"><?}?>
        
        function inicializar() {

            var urlCtrlProcessos = document.getElementById('hdnUrlControleProcessos').value;
            var idParam = document.getElementById('hdnIdParametroCtrlUtl').value;
            var tpCtrl = document.getElementById('hdnIdTipoControleUtl').value;

            if (tpCtrl == 0) {
                alert('Esta Unidade não está associada a nenhum Tipo de Controle de Desempenho.');
                window.location.href = urlCtrlProcessos;
                return false;
            }

            if (idParam == 0) {
                alert('O Tipo de Controle desta Unidade não está Parametrizado!');
                window.location.href = urlCtrlProcessos;
                return false;
            }


            addEnter();   
        }
        

        function addEnter() {
            document.getElementById('txtProcessoUtlMs').addEventListener("keypress", function (evt) {
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
        <div class="bloco" id="divProcesso">
            <label id="lblProcesso" for="txtProcessoUtlMs" class="infraLabelOpcional">
                Processo:
            </label>

            <div class="clear"></div>

            <input type="text" id="txtProcessoUtlMs" name="txtProcessoUtlMs" class="inputFila infraText padraoInput"
                   size="30"
                   value="<?php echo $txtProcessoCampo ?>"
                   maxlength="100" tabindex="502"/>
        </div>


        <div id="divFila">
            <label id="lblFila" for="selFilaUtlMs" accesskey="" class="infraLabelOpcional">Fila:</label>
            <select id="selFilaUtlMs" name="selFilaUtlMs" class="infraSelect padraoSelect"
                    onchange="pesquisar();"
                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                <?= $selFila ?>
            </select>
        </div>


        <div id="divTipoProcesso">
            <label id="lblTipoProcesso" for="selTipoProcessoUtlMs" accesskey="" class="infraLabelOpcional">Tipo de
                Processo:</label>
            <select id="selTipoProcessoUtlMs" name="selTipoProcessoUtlMs" class="infraSelect padraoSelect"
                    onchange="pesquisar();"
                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                <option value=""></option>
                <?= $selTipoProcesso ?>
            </select>
        </div>


        <div id="divStatus">
            <label id="lblStatus" for="selStatusUtlMs" accesskey="" class="infraLabelOpcional">Status:</label>
            <select id="selStatusUtlMs" name="selStatusUtlMs" class="infraSelect padraoSelect"
                    onchange="pesquisar();"
                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                <?= $selStatus ?>
            </select>
        </div>


        <input type="hidden" id="hdnSubmit" name="hdnSubmit" value="<?php echo $vlControlePost; ?>"/>
        <input type="hidden" id="hdnIdTipoControleUtl" name="hdnIdTipoControleUtl"
               value="<?php echo is_null($idTipoControle) ? '0' : $idTipoControle; ?>"/>
        <input type="hidden" id="hdnIdParametroCtrlUtl" name="hdnIdParametroCtrlUtl"
               value="<?php echo $isParametrizado ? '1' : '0'; ?>"/>
        <input type="hidden" id="hdnUrlControleProcessos" name="hdnUrlControleProcessos"
               value="<?php echo SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_controlar&acao_origem=' . $_GET['acao']); ?>"/>


        <?php
        PaginaSEI::getInstance()->fecharAreaDados();
        if($numRegistros > 0){
        ?>
        <div id="divSomaUndEsforco">
            <label><u>Total de Unidade de Esforço:<?=$somaUndEsforco?></u></label>
        </div>
        <?php } ?>
        <br/>
        <br/>
<div style="margin-top: -16px;">
        <?php
            PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros);
        ?>
    </div>
        <?php
            PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
        ?>

    </form>

<?php
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
