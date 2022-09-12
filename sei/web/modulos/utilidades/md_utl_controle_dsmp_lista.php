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
$objFilaRN              = new MdUtlAdmFilaRN();
$objMdUtlControleDsmpRN = new MdUtlControleDsmpRN();
$objMdUtlAdmUtlTpCtrlRN = new MdUtlAdmTpCtrlDesempRN();
$objMdUtlAdmTpCtrlUndRN = new MdUtlAdmRelTpCtrlDesempUndRN();
$objMdUtlAdmTpCtrlUsuRN = new MdUtlAdmRelTpCtrlDesempUsuRN();

//Array que sera usado para montar os tipos de controles da unidade
$arrObjTpControle   = $objMdUtlAdmTpCtrlUndRN->getArrayTipoControleUnidadeLogada();  
$arrListaTpControle = array();
if (!is_null($arrObjTpControle) && count($arrObjTpControle) > 0 ){
    foreach ($arrObjTpControle as $k => $v) {
        $arrListaTpControle[$v->getNumIdMdUtlAdmTpCtrlDesemp()] = $v->getStrNomeTipoControle();
    }
}

//Validação de Tipo de Controle
$idTipoControle = null;
if (isset($_POST['selTpControle']) && !empty($_POST['selTpControle'])) {
    $idTipoControle = $_POST['selTpControle'];
} 

// Tipos de Controles onde usuario é gestor
$idsTpCtrlUsuarioGestor = $objMdUtlAdmTpCtrlUsuRN->usuarioLogadoIsGestorTpControle();

/*
    Para cada tipo de controle, valida se esta parametrizado e permite associacao
    Caso tenha sido selecionado um tipo de controle, nao passa pela validacao, pois ja está validado
*/
$isParametrizado     = true;
$isPermiteAssociacao = true;

//URL Base
$strUrl = 'controlador.php?acao=md_utl_controle_dsmp_';
//URL das Actions
$strUrlsPesquisar    = SessaoSEI::getInstance()->assinarLink($strUrl . 'listar&acao_origem=' . $_GET['acao']);
$strUrlFechar        = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_controlar&acao_origem=' . $_GET['acao']);
$strUrlValTpProced   = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_utl_ctrl_dsmp_tp_procedimento');

// responsavel pelos dados da combo Tipo de Controle
$selTpControle = is_null($arrObjTpControle) ? array() : MdUtlAdmFilaINT::montarSelectTpControle($arrObjTpControle, 'NumIdMdUtlAdmTpCtrlDesemp', 'StrNomeTipoControle', $_POST['selTpControle'], 'associar');
$arrObjTiposProcesso = $objMdUtlControleDsmpRN->getTiposProcessoTipoControleAssociarFila(empty($idTipoControle) ? $arrListaTpControle : array($idTipoControle => $idTipoControle));
$arrObjsTpProcesso = empty($arrObjTiposProcesso) ? array() : $arrObjTiposProcesso;
$selTipoProcesso = InfraINT::montarSelectArrInfraDTO(null, null, $_POST['selTipoProcesso'], $arrObjsTpProcesso, 'IdTipoProcedimento', 'NomeProcedimento');
$idsTpProcesso = InfraArray::converterArrInfraDTO($arrObjsTpProcesso, 'IdTipoProcedimento');
$selStatus = MdUtlControleDsmpINT::montarSelectStatus($_POST['selStatus']); 

if(!is_null($idTipoControle) && $isParametrizado) {
    $arrObjFilaDTO = $objFilaRN->getFilasTipoControle($idTipoControle);
    $selFila = MdUtlAdmFilaINT::montarSelectFilas($_POST['selFila'], $arrObjFilaDTO);
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

if (/*!is_null($idTipoControle) && */ $isPermiteAssociacao) {
    //Botões de ação do topo
    $arrComandos[] = '<button type="button" accesskey="A" id="btnAssoFila" onclick="associarFila()" class="infraButton">
                                        <span class="infraTeclaAtalho">A</span>ssociar à Fila</button>';
}


$arrComandos[] = '<button type="button" accesskey="c" id="btnFechar" onclick="fechar()" class="infraButton">
                                        Fe<span class="infraTeclaAtalho">c</span>har</button>';


$objDTO = new MdUtlProcedimentoDTO();

//Set Campos definidos por Regras
$objDTO->setStrStaEstadoProtocolo(ProtocoloRN::$TE_NORMAL);
$objDTO->setStrStaNivelAcessoLocalProtocolo(array(ProtocoloRN::$NA_PUBLICO, ProtocoloRN::$NA_RESTRITO, ProtocoloRN::$NA_SIGILOSO), InfraDTO::$OPER_IN);
$objDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());


$idsProcessoAberto = $objMdUtlControleDsmpRN->getIdsProcessoAbertoUnidade($objDTO);


if ( $isParametrizado ) {

    //Configuração da Paginação    
    $idsProcessoDocumento = array();
    $txtProcesso        = array_key_exists('txtProcesso', $_POST) && $_POST['txtProcesso'] != '';
    $isTipoProcesso     = array_key_exists('selTipoProcesso', $_POST) && $_POST['selTipoProcesso'] != '';
    $isIdFila           = array_key_exists('selFila', $_POST) && $_POST['selFila'] != '';
    $isStrStatus        = array_key_exists('selStatus', $_POST) && $_POST['selStatus'] != '';
    $isStrDocumento     = array_key_exists('txtDocumento', $_POST) && trim($_POST['txtDocumento']) != '';
    $isAguardandoFila   = $_POST['selStatus'] == MdUtlControleDsmpRN::$AGUARDANDO_FILA;
    $arrIdsAtivosDsmp   = array();
    $isFiltroDocumento  = false;

    if ($isTipoProcesso) {
        $objDTO->setNumIdTipoProcedimento($_POST['selTipoProcesso']);
    } else {
        if(!empty($idsTpProcesso)) $objDTO->setNumIdTipoProcedimento($idsTpProcesso, InfraDTO::$OPER_IN);
    }
 
    if ( !empty($idTipoControle)) {
        $objDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTipoControle);
        $objDTO->setControleDsmpTIPOFK(InfraDTO::$TIPO_FK_OBRIGATORIA);
    }

    if ($isIdFila) {        
        $objDTO->setNumIdFila($_POST['selFila']);
    }

    if ($txtProcesso) {
        $objDTO->setStrProtocoloProcedimentoFormatado('%' . trim($_POST['txtProcesso'] . '%'), InfraDTO::$OPER_LIKE);
    }   
    
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
        }

    } else {        
        $objDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
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
        $objDTO->retNumIdTipoProcedimento();
        $objDTO->retStrStaAtendimentoDsmp();
        $objDTO->retStrSiglaUnidade();
        $objDTO->retStrProtocoloProcedimentoFormatado();
        $objDTO->retStrNomeFila();
        $objDTO->retNumIdFila();
        $objDTO->retNumTempoExecucao();
        $objDTO->retStrNomeUsuarioDistribuicao();
        $objDTO->retDthAtual();
        $objDTO->retStrSiglaUsuarioDistribuicao();
        $objDTO->retNumIdMdUtlAnalise();
        $objDTO->retStrNomeTpCtrlDsmp();
        $objDTO->retStrIdTipoCtrlDsmp();

        PaginaSEI::getInstance()->prepararOrdenacao($objDTO, 'ProtocoloProcedimentoFormatado', InfraDTO::$TIPO_ORDENACAO_ASC);
        PaginaSEI::getInstance()->prepararPaginacao($objDTO, 200);

        $arrObjs      = $objMdUtlControleDsmpRN->listarProcessos($objDTO);
        $numRegistros = count($idsProcessoAberto) > 0 ? count($arrObjs) : 0;

        PaginaSEI::getInstance()->processarPaginacao($objDTO);

        //Tabela de resultado.
        if ($numRegistros > 0) {
            $arrUltimasFilas = array();

            $displayNoneCheck = $isPermiteAssociacao ? '' : 'style="display:none"';
            $strResultado .= '<table class="infraTable" summary="Associar Processos a Filas" id="tbCtrlProcesso">';
            $strResultado .= '<caption class="infraCaption">';
            $strResultado .= PaginaSEI::getInstance()->gerarCaptionTabela('Associar Processos a Filas', $numRegistros);
            $strResultado .= '</caption>';


            //Cabeçalho da Tabela
            $strResultado .= '<tr>';
            $strResultado .= '<th ' . $displayNoneCheck . ' class="infraTh" align="center" width="1%" >' . PaginaSEI::getInstance()->getThCheck() . '</th>';
            $strResultado .= '<th class="infraTh" style="min-width:160px;">' . PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Processo', 'ProtocoloProcedimentoFormatado', $arrObjs) . '</th>';
            $strResultado .= '<th class="infraTh">' . PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Tipo de Processo', 'IdTipoProcedimento', $arrObjs) . '</th>';

            //ADICIONAR ORDENAÇÃO PARA OS OUTROS CAMPOS
            $strResultado .= '<th class="infraTh" style="width:110px">' . PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Tipo de Controle', 'NomeTpCtrlDsmp', $arrObjs) . '</th>';
            $strResultado .= '<th class="infraTh" >' . PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Fila', 'NomeFila', $arrObjs) . '</th>';
            $strResultado .= '<th class="infraTh" style="width:160px">' . PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Situação', 'StaAtendimentoDsmp', $arrObjs) . '</th>';

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
                $strNomeTpCtrlDsmp = $arrObjs[$i]->getStrNomeTpCtrlDsmp();                
                $nomeTpProcesso   = $arrObjs[$i]->getStrNomeTipoProcedimento();
                $numIdTpProcedimento = $arrObjs[$i]->getNumIdTipoProcedimento();
                $strStatus        = trim($arrObjs[$i]->getStrStaAtendimentoDsmp());
                $strStatus        = $strStatus == '' ? null : $strStatus;
                $strUltimaFila    = count($arrUltimasFilas) > 0 && array_key_exists($strId, $arrUltimasFilas) ? $arrUltimasFilas[$strId] : '';
                $linkProcedimento = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem=md_utl_controle_dsmp_listar&id_procedimento=' . $strId . '');

                $bolRegistroAtivo = true;

                $strCssTr = !$bolRegistroAtivo ? '<tr class="trVermelha">' : ($strCssTr == '<tr class="infraTrClara">' ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">');
                $strCssTr = in_array($strId, $arrIdProcedimentoAssociado) ? '<tr class="infraTrAcessada">' : $strCssTr;
                $strResultado .= $strCssTr;

                //Linha Checkbox
                $strResultado .= '<td ' . $displayNoneCheck . ' align="center" valign="middle">';
                $strResultado .= PaginaSEI::getInstance()->getTrCheck($i, $strId, $strProcesso);
                $strResultado .= '</td>';

                //Linha Nome
                $strResultado .= '<td class="tdIdProcesso" style="display: none">';
                $strResultado .= $strId;
                $strResultado .= '</td>';

                //Linha Nome
                $strResultado .= '<td class="tdNomeProcesso">';
                $strResultado .= '<a href="javascript:void(0);" onclick="window.open(\'' . $linkProcedimento . '\')" alt="' . $nomeTpProcesso . '" title="' . $nomeTpProcesso . '" class="ancoraPadraoAzul" style="padding:0px !important;">' . $strProcesso . '</a>';
                $strResultado .= '</td>';

                //Linha Descrição
                $strResultado .= '<td class="tdTipoProcesso">';
                $strResultado .= PaginaSEI::tratarHTML($nomeTpProcesso);
                $strResultado .= '</td>';

                //Linha Tipo Controle  Desempenho
                $strResultado .= '<td class="tdIdTpCtrl" style="display: none">';
                $strResultado .= $arrObjs[$i]->getStrIdTipoCtrlDsmp();
                $strResultado .= '</td>';

                $strResultado .= '<td class="tdTpCtrl">';
                $strResultado .= PaginaSEI::tratarHTML($strNomeTpCtrlDsmp);
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

                // Linha Id Tipo Procedimento
                $strResultado .= '<td class="tdIdTpProcedimento" style="display: none">';
                $strResultado .= $numIdTpProcedimento;
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
PaginaSEI::getInstance()->fecharStyle();

PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
PaginaSEI::getInstance()->fecharJavaScript();

PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>

<form id="frmTpControleLista" method="post"
        action="<?= PaginaSEI::getInstance()->formatarXHTML(
            SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])
        ) ?>">

    <?php
        PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        PaginaSEI::getInstance()->abrirAreaDados();
        $col_def = "col-sm-6 col-md-6 col-lg-4 mb-2";
    ?>
    
    <div class="row mb-2">
        <div class="<?= $col_def ?> bloco" id="divProcesso">
            <label id="lblProcesso" for="txtProcesso" class="infraLabelOpcional">
                Processo:
            </label>
            <input type="text" id="txtProcesso" name="txtProcesso" class="inputFila infraText form-control"                   
                value="<?php echo array_key_exists('txtProcesso', $_POST) ? $_POST['txtProcesso'] : '' ?>"
                maxlength="100" tabindex="502"/>
        </div>

        <div class="<?= $col_def ?> bloco" id="divDocumento">
            <label id="lblDocumento" for="txtDocumento" accesskey="S" class="infraLabelOpcional">
                Documento SEI:
            </label>
            <input type="text" id="txtDocumento" name="txtDocumento" class="inputFila infraText form-control"
                value="<?php echo array_key_exists('txtDocumento', $_POST) ? $_POST['txtDocumento'] : '' ?>"
                maxlength="100" tabindex="502"/>
        </div>

        <div class="<?= $col_def ?> bloco" id="divTpControle">
            <label id="lblTpControle" for="selTpControle" accesskey="" class="infraLabelOpcional">Tipo de Controle:</label>
            <select id="selTpControle" name="selTpControle" class="infraSelect form-control"                    
                onchange="pesquisar()" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                <?= $selTpControle ?>
            </select>
        </div>

        <div id="divFila" class="bloco <?= $col_def ?>">
            <label id="lblFila" for="selFila" accesskey="" class="infraLabelOpcional">Fila:</label>
            <select id="selFila" name="selFila" class="infraSelect form-control"
                    onchange="pesquisar();" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                <?= $selFila ?>
            </select>
        </div>
        
        <div id="divStatus" class="bloco <?= $col_def ?>">
            <label id="lblStatus" for="selStatus" accesskey="" class="infraLabelOpcional">Situação:</label>
            <select id="selStatus" name="selStatus" class="infraSelect form-control"
                    onchange="pesquisar();" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                <?= $selStatus ?>
            </select>
        </div>

        <div id="divTipoProcesso" class="bloco <?= $col_def ?>">
            <label id="lblTipoProcesso" for="selTipoProcesso" accesskey="" class="infraLabelOpcional">
                Tipo de Processo:
            </label>
            <select id="selTipoProcesso" name="selTipoProcesso" class="infraSelect form-control" onchange="pesquisar();" 
                tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                <option value=""></option>
                <?= $selTipoProcesso ?>
            </select>
        </div>
    </div>

    <input type="hidden" id="hdnIdTipoControleUtl" name="hdnIdTipoControleUtl"
            value="<?= empty($arrListaTpControle) ? '0' : (!empty($arrListaTpControle) ? '1' : $idTipoControle); ?>"/>
    <input type="hidden" id="hdnIdParametroCtrlUtl" name="hdnIdParametroCtrlUtl"
            value="<?php echo $isParametrizado ? '1' : '0'; ?>"/>
    <input type="hidden" id="hdnValidaCtrlUnidUtl" name="hdnValidaCtrlUnidUtl" value="<?= empty($arrListaTpControle) ? '0' : '1' ?>"/>
    <input type="hidden" id="hdnDadosAssociarFila" name="hdnDadosAssociarFila"/>
    <input type="hidden" id="hdnUrlControleProcessos" name="hdnUrlControleProcessos"
            value="<?php echo SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_controlar&acao_origem=' . $_GET['acao']); ?>"/>
    <?php
        PaginaSEI::getInstance()->fecharAreaDados();
        PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros);
        PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>

</form>

<script type="text/javascript">
    var msg24 = '<?php  echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_24); ?>';
    var msg25 = '<?php  echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_25); ?>';
    var msg26 = '<?php  echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_26); ?>';
    var msg27 ='<?php  echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_27); ?>';
    var arrTpCtrl = new Array();
    var strUrlAssocFila = '';
    var strAssociarParcial;
    var arrTpCtrlUserGestor = new Array();       

    // loop para adicionar os tipos de controles da unidade
    <?php 
        if (!empty($arrListaTpControle)) { 
            foreach ($arrListaTpControle as $k => $v) { 
    ?>
                arrTpCtrl.push( <?= $k ?> );
    <?php 
            }
        }
    ?>

    // loop para adicionar os tipos de controles onde usuario é gestor
    <?php 
        if($idsTpCtrlUsuarioGestor){
            foreach($idsTpCtrlUsuarioGestor as $k => $v ){
    ?>
                arrTpCtrlUserGestor.push(<?= $v ?>);
    <?php
            }
        }
    ?>
    
    function inicializar() {
        var urlCtrlProcessos = document.getElementById('hdnUrlControleProcessos').value;
        var idParam = document.getElementById('hdnIdParametroCtrlUtl').value;
        var tpCtrl = document.getElementById('hdnValidaCtrlUnidUtl').value;

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

        // Adiciona a class "infraLabelOpcional" quando não retorna nenhum registro na grid
        // para ficar na mesma formatação das labels que retornam dados referentes a tempo
        if( $('#divInfraAreaTabela').find('table').length == 0 ){
            $('#divInfraAreaPaginacaoSuperior').hide();
            $('#divInfraAreaTabela').addClass('mt-3');
            $('#divInfraAreaTabela > label').addClass('infraLabelOpcional'); 
        }else{
            if( $('#divInfraAreaPaginacaoSuperior').find('select').length == 0 ){
                $('#divInfraAreaPaginacaoSuperior').hide();
            }
        }
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
                var tpCtrlEle = parseInt( $(linhas[i]).find('.tdIdTpCtrl').text() );                    
                var valElem = arrTpCtrlUserGestor.indexOf( tpCtrlEle );                    
                if( valElem < 0 ){
                    if(valido){
                        msgInicio  += "\n";
                    }
                    valido = false;
                    msgInicio += "\n";
                    msgInicio +=  " - " + $(linhas[i]).find('.tdNomeProcessoFormatado').text();
                }
            }
        }

        if(!valido) {
            alert(msgInicio);
        }
        return valido;
    }

    function associarFila() {
        var valido = true;
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
        
        if( !validaTpProcedimentoComTpCtrl() ){
            return false;
        }

        valido = associacaoIsPermitida();                    
        if( valido ){
            infraAbrirJanela(strUrlAssocFila, 'janelaAssinatura', 1000, 450, 'location=0,status=1,resizable=1,scrollbars=1');                      
        }
    }     

    function pesquisar() {
        var selTpControle = document.getElementById('selTpControle').value;
        document.getElementById('hdnIdTipoControleUtl').value = selTpControle;
        document.getElementById('frmTpControleLista').action  = "<?= $strUrlsPesquisar ?>";
        document.getElementById('frmTpControleLista').submit();
    }

    function fechar() {
        location.href = "<?= $strUrlFechar ?>";
    }

    function validaTpProcedimentoComTpCtrl(){
        var arrValTpCtrl     = new Array();
        var arrObjTpProcesso = new Array();
        var arrObjProcesso   = new Array();
        var ultTpCtrl        = '.';
        var elems            = $('.infraCheckboxInput:checked');
        var valid            = true;
        
        $( elems ).each(function( i , obj ){
            arrObjTpProcesso.push( parseInt( $(obj).closest('tr').find('.tdIdTpProcedimento').text() ) );
            arrObjProcesso.push( parseInt( $(obj).closest('tr').find('.tdIdProcesso').text() ) );
        });

        var params = {
            listTpCtrl: arrTpCtrl,
            listTpProced: arrObjTpProcesso,
            listProcessos: arrObjProcesso,
            acao_origem: "<?= $_GET['acao'] ?>"
        };

        $.ajax({
            url: "<?= $strUrlValTpProced ?>",
            data: params,
            type: 'post',
            dataType: 'xml',
            async: false
        })
        .done( function( rs ){
            if ( $( rs ).find('Validado').length > 0 ){
                strUrlAssocFila = $( rs ).find('Url').text().trim();
                valid = true;
            }else if( $( rs ).find('Qtd').length > 0 ){
                alert("<?= MdUtlMensagemINT::$MSG_UTL_120 ?>");
                valid = false;
            }else if( $( rs ).find('NaoValidado').length > 0 ){
                alert( $( rs ).find('Mensagem').text().trim() );                    
                valid = false;
            }
        })
        .fail( function( e ){
            console.error('Erro ao validar o Tipo de Controle: ' + e.responseText);
        });

        return valid;
    }
</script>

<?php
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();