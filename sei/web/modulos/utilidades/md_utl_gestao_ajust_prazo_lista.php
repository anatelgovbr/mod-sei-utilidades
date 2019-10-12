<?php

require_once dirname(__FILE__) . '/../../SEI.php';

session_start();
SessaoSEI::getInstance()->validarLink();

PaginaSEI::getInstance()->salvarCamposPost(array('txtProcessoMdGestao','selStatusProcMdGestao', 'selServidorMdGestao'));

$txtProcessoCampo     = array_key_exists('txtProcessoMdGestao', $_POST) ? $_POST['txtProcessoMdGestao'] : PaginaSEI::getInstance()->recuperarCampo('txtProcessoMdGestao');
$selStatusProcCampo   = array_key_exists('selStatusProcMdGestao', $_POST) ? $_POST['selStatusProcMdGestao'] : PaginaSEI::getInstance()->recuperarCampo('selStatusProcMdGestao');
$selServidorCampo     = array_key_exists('selServidorMdGestao', $_POST) ? $_POST['selServidorMdGestao'] : PaginaSEI::getInstance()->recuperarCampo('selServidorMdGestao');

//URL Base
$strUrl = 'controlador.php?acao=md_utl_gestao_ajust_prazo_';
$strUrlPadraoTela = 'controlador.php?acao=md_utl_gestao_solicitacoes_';

$strUrlFechar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_controlar&acao_origem=' . $_GET['acao']);

$arrPostDados = array('txtProcessoMdGestao' => $txtProcessoCampo, 'selStatusProcMdGestao'=> $selStatusProcCampo, 'selServidorMdGestao' => $selServidorCampo);

$objMdUtlAdmTpCtrlUndRN     = new MdUtlAdmRelTpCtrlDesempUndRN();
$objMdUtlAdmTpCtrlUsuRN     = new MdUtlAdmRelTpCtrlDesempUsuRN();
$objMdUtlGestaoAjustPrazoRN = new MdUtlGestaoAjustPrazoRN();
$objMdUtlAjustePrazoRN      = new MdUtlAjustePrazoRN();
$objMdUtlControleDsmpRN     = new MdUtlControleDsmpRN();
$objMdUtlAdmUtlTpCtrlRN     = new MdUtlAdmTpCtrlDesempRN();
$objMdUtlAjustePrazoDTO     = new MdUtlAjustePrazoDTO();
$objMdUtlControleDsmpDTO    =null;

/* Id Tipo de Controle */
$idTipoControle = $objMdUtlAdmTpCtrlUndRN->getTipoControleUnidadeLogada();

/* Verifica se é gestor */
$isGestorSipSei  = count($objMdUtlAdmTpCtrlUsuRN->usuarioLogadoIsGestorTpControle()) > 0;


$isPesquisar = array_key_exists('pesquisar',$_GET) ? $_GET['pesquisar'] : 0;

if(!is_null($idTipoControle)){
    $numIdControleDsmp = '';
    $numIdAjustePrazo = '';
    $objMdUtlControleDsmpDTO  = new MdUtlControleDsmpDTO();
    $objMdUtlControleDsmpDTO  = $objMdUtlGestaoAjustPrazoRN->buscarSolicitacoesAjustePrazo(array($idTipoControle, $arrPostDados));
    $objMdUtlControleDsmpDist = $objMdUtlGestaoAjustPrazoRN->buscarSolicitacoesAjustePrazo(array($idTipoControle, null));
    $arrStatusProcesso       = MdUtlGestaoAjustPrazoINT::montarSelectStatusProcesso($selStatusProcCampo);
    $arrObjsServidorDTO      = !is_null($objMdUtlControleDsmpDist) ? InfraArray::distinctArrInfraDTO($objMdUtlControleDsmpDist, 'IdUsuarioDistribuicao') : null;

    $arrSelServidor          = MdUtlGestaoAjustPrazoINT::montarSelectServidor($selServidorCampo, $arrObjsServidorDTO);

    $strUrlPesquisar  = SessaoSEI::getInstance()->assinarLink($strUrlPadraoTela . 'listar&acao_origem=' . $_GET['acao'] . '&id_tipo_controle_utl=' . $idTipoControle.'&pesquisar=1');
    $strUrlRecarregar = SessaoSEI::getInstance()->assinarLink($strUrlPadraoTela . 'listar&acao_origem=' . $_GET['acao']);

    $isParametrizado = $objMdUtlAdmUtlTpCtrlRN->verificaTipoControlePossuiParametrizacao($idTipoControle);
}

if (!is_null($objMdUtlControleDsmpDTO)) {

    $numRegistros = count($objMdUtlControleDsmpDTO);
    $caption = $numRegistros == 1 ? 'Pendente de Resposta: '. $numRegistros .' registro' : 'Pendentes de Resposta: '. $numRegistros .' registros';

    //Tabela de resultado.
    if ($numRegistros > 0) {
        $strCaption .= '<caption class="infraCaption">';
        $strCaption .= '<span class="spnCaptionRegistros" >'. $caption .'</span>';
        $strCaption .= '</caption>';
        $strResultado .= '<table width="100%" class="infraTable" summary="Processos" id="tbCtrlProcesso">';


        //Cabeçalho da Tabela
        $strResultado .= '<tr>';
        $strResultado .= '<th class="infraTh" width="18%" style="text-align: center"> Processo </th>';
        $strResultado .= '<th class="infraTh" width="12%" style="text-align: center"> Tipo de Solicitação </th>';
        $strResultado .= '<th class="infraTh" width="10%" style="text-align: center"> Servidor </th>';
        $strResultado .= '<th class="infraTh" width="12%" style="text-align: center"> Data da Solicitação </th>';
        $strResultado .= '<th class="infraTh" width="15%" style="text-align: center"> Justificativa </th>';
        $strResultado .= '<th class="infraTh" width="10%" style="text-align: center"> Prazo Entrega </th>';
        $strResultado .= '<th class="infraTh" width="10%" style="text-align: center"> Prazo Solicitado </th>';
        $strResultado .= '<th class="infraTh" width="14%" style="text-align: center"> Ações </th>';

        $strResultado .= '</tr>';


        //Linhas
        $strCssTr = '<tr class="infraTrEscura">';

        for ($i = 0; $i < $numRegistros; $i++) {
            $numIdControleDsmp  = $objMdUtlControleDsmpDTO[$i]->getNumIdMdUtlControleDsmp();
            $numIdAjustePrazo   = $objMdUtlControleDsmpDTO[$i]->getNumIdMdUtlAjustePrazo();
            $dblIdProcedimento  = $objMdUtlControleDsmpDTO[$i]->getDblIdProcedimento();
            $strNomeProc        = $objMdUtlControleDsmpDTO[$i]->getStrNomeTipoProcesso();
            $strProcesso        = $objMdUtlControleDsmpDTO[$i]->getStrProtocoloProcedimentoFormatado();
            $strTpSolicitacao   = $objMdUtlControleDsmpDTO[$i]->getStrStaTipoSolicitacaoAjustePrazo();
            $strSiglaUsuario    = $objMdUtlControleDsmpDTO[$i]->getStrSiglaUsuarioDistribuicao();
            $strNomeUsuario     = $objMdUtlControleDsmpDTO[$i]->getStrNomeUsuarioDistribuicao();
            $arrDataSolicitacao = explode(' ', $objMdUtlControleDsmpDTO[$i]->getDthAtual());
            $dthDataSolicitacao = $arrDataSolicitacao[0];
            $strJustificativa   = $objMdUtlControleDsmpDTO[$i]->getStrNomeJustificativa();
            $strObservacao      = $objMdUtlControleDsmpDTO[$i]->getStrObservacao();
            $dthPrazoEntrega    = MdUtlGestaoAjustPrazoINT::formatarData($objMdUtlControleDsmpDTO[$i]->getStrDthPrazoInicialAjustePrazo());
            $dthPrazoSolicitado = MdUtlGestaoAjustPrazoINT::formatarData($objMdUtlControleDsmpDTO[$i]->getStrDthPrazoSolicitacaoAjustePrazo());
            $idContato          = $objMdUtlControleDsmpDTO[$i]->getNumIdContato();

            $linkProcedimento = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem=md_utl_gestao_ajust_prazo_lista&id_procedimento=' . $dblIdProcedimento . '');

            $bolRegistroAtivo = true;

            $strCssTr = !$bolRegistroAtivo ? '<tr class="trVermelha">' : ($strCssTr == '<tr class="infraTrClara">' ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">');
            $strResultado .= $strCssTr;

            //Linha Tipo de Solicitação
            $strResultado .= '<td class="tdIdProcedimento" style="display: none" >';
            $strResultado .= $dblIdProcedimento;
            $strResultado .= '</td>';

            //Linha Processo
            $strResultado .= '<td class="tdIdProcesso" >';
            $strResultado .= '<a href="#" onclick="window.open(\'' . $linkProcedimento . '\')" alt="' . $strNomeProc . '" title="' . $strNomeProc . '" class="ancoraPadraoAzul">' . $strProcesso . '</a>';
            $strResultado .= '</td>';

            //Linha Tipo de Solicitação
            $strResultado .= '<td class="tdSolicitacao" >';
            $strResultado .= MdUtlGestaoAjustPrazoINT::montarTipoSolicitacao($strTpSolicitacao);
            $strResultado .= '</td>';

            //Linha Servidor
            $strResultado .= '<td class="tdServidor" >';
            $strResultado .= '<a class="ancoraSigla" href="#" alt="' . $strNomeUsuario . '" title="' . $strNomeUsuario . '">' . $strSiglaUsuario . '</a>';
            $strResultado .= '</td>';

            //Linha Data de Solicitação
            $strResultado .= '<td class="tdDthSolicitacao" >';
            $strResultado .= $dthDataSolicitacao;
            $strResultado .= '</td>';

            //Linha Justificativa
            $strResultado .= '<td class="tdJustificativa">';
            $strResultado .= '<a class="ancoraSigla" href="#" alt="' . $strObservacao . '" title="' . $strObservacao . '">' . $strJustificativa . '</a>';
            $strResultado .= '</td>';

            //Linha Prazo de Entrega
            $strResultado .= '<td class="tdDthPrazoEntrega" >';
            $strResultado .= $dthPrazoEntrega;
            $strResultado .= '</td>';

            //Linha Prazo Solicitado
            $strResultado .= '<td class="tdDthPrazoSolicitacao" >';
            $strResultado .= $dthPrazoSolicitado;
            $strResultado .= '</td>';

            //Linha Ações
            $strResultado .= '<td class="tdAcoes" align="center">';
            $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_ajuste_prazo_consultar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_ajuste_prazo=' . $numIdAjustePrazo . '&id_controle_desempenho=' . $numIdControleDsmp .'&is_gerir=0').'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getDiretorioImagensGlobal().'/consultar.gif" title="Consultar Solicitação de Ajuste de Prazo" alt="" class="infraImg" /></a>&nbsp;';
            $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_ajuste_prazo_alterar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_ajuste_prazo='. $numIdAjustePrazo .'&id_controle_desempenho='. $numIdControleDsmp.'&is_gerir=1').'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getDiretorioImagensGlobal().'/alterar.gif" title="Alterar Solicitação de Ajuste de Prazo" alt="" class="infraImg" /></a>&nbsp;';
            $strResultado .= '<a id="aprovarSolicitacao" onclick="confirmarAcao(\''.MdUtlAjustePrazoRN::$APROVADA.'\',\''.$strProcesso.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="modulos/utilidades/imagens/aprovar_ajuste_prazo.png" title="Aprovar Solicitação de Ajuste de Prazo" alt="" class="infraImg" /></a>&nbsp;';
            $strResultado .= '<a id="reprovarSolicitacao" onclick="confirmarAcao(\''.MdUtlAjustePrazoRN::$REPROVADA.'\',\''.$strProcesso.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="modulos/utilidades/imagens/reprovar_ajuste_prazo.png" title="Reprovar Solicitação de Ajuste de Prazo" alt="" class="infraImg" /></a>&nbsp;';
            $strResultado .= '</td>';

            $strResultado .= '</tr>';

        }
        $strResultado .= '</table>';
    }
}


$strTitulo = 'Gestão de Solicitações';

switch ($_GET['acao']) {

    //region Listar
    case 'md_utl_gestao_solicitacoes_listar':

        break;

    case 'md_utl_gestao_ajust_prazo_aprovar':
        $idControleDsmp   = array_key_exists('id_controle_desempenho', $_GET) ? $_GET['id_controle_desempenho'] : null;
        $numIdAjustePrazo = array_key_exists('id_ajuste_prazo', $_GET) ? $_GET['id_ajuste_prazo'] : null;

        $objControleDsmpDTO = new MdUtlControleDsmpDTO();
        $objControleDsmpDTO->setNumIdMdUtlControleDsmp($idControleDsmp);
        $objControleDsmpDTO->setNumIdMdUtlAjustePrazo($numIdAjustePrazo);
        $objControleDsmpDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objControleDsmpDTO->retTodos();
        $objControleDsmpDTO->retStrProtocoloProcedimentoFormatado();
        $objControleDsmpDTO->retNumIdContato();
        $objControleDsmpDTO->retStrEmail();
        $objControleDsmpDTO->setNumMaxRegistrosRetorno(1);
        $objControleDsmpDTO = $objMdUtlControleDsmpRN->consultar($objControleDsmpDTO);

        /*Efetuar ação / Enviar E-Mail / Gravar Histórico*/
        if (isset($_POST)) {
            try{
                $objInfraException = new InfraException();
                if($objControleDsmpDTO->getStrEmail() == ''){
                    $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_102);
                    $objInfraException->lancarValidacao($msg);
                }
            }catch(Exception $e){
                PaginaSEI::getInstance()->processarExcecao($e);
            }
            $objMdUtlGestaoAjustPrazoRN->aprovarSolicitacao($objControleDsmpDTO);
            header('Location: '.$strUrlRecarregar);
            die;
        }

    case 'md_utl_gestao_ajust_prazo_reprovar':
        $idControleDsmp = array_key_exists('id_controle_desempenho', $_GET) ? $_GET['id_controle_desempenho'] : null;
        $numIdAjustePrazo = array_key_exists('id_ajuste_prazo', $_GET) ? $_GET['id_ajuste_prazo'] : null;

        $objControleDsmpDTO = new MdUtlControleDsmpDTO();
        $objControleDsmpDTO->setNumIdMdUtlControleDsmp($idControleDsmp);
        $objControleDsmpDTO->setNumIdMdUtlAjustePrazo($numIdAjustePrazo);
        $objControleDsmpDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objControleDsmpDTO->retTodos();
        $objControleDsmpDTO->retStrProtocoloProcedimentoFormatado();
        $objControleDsmpDTO->retNumIdContato();
        $objControleDsmpDTO->retStrEmail();
        $objControleDsmpDTO->setNumMaxRegistrosRetorno(1);
        $objControleDsmpDTO = $objMdUtlControleDsmpRN->consultar($objControleDsmpDTO);

        /*Efetuar ação / Enviar E-Mail / Gravar Histórico*/
        if (isset($_POST)) {
            try{
                $objInfraException = new InfraException();
                if($objControleDsmpDTO->getStrEmail() == ''){
                    $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_102);
                    $objInfraException->lancarValidacao($msg);
                }
            }catch(Exception $e){
                PaginaSEI::getInstance()->processarExcecao($e);
            }
            $objMdUtlGestaoAjustPrazoRN->reprovarSolicitacao($objControleDsmpDTO);
            header('Location: '.$strUrlRecarregar);
            die;
        }
        break;


    //endregion

    //region Erro
    default:
        throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    //endregion
}

$arrComandos[] = '<button type="button" accesskey="P" id="btnPesquisar" onclick="pesquisar()" class="infraButton">
                                        <span class="infraTeclaAtalho">P</span>esquisar</button>';

$arrComandos[] = '<button type="button" accesskey="c" id="btnFechar" onclick="fechar()" class="infraButton">
                                        Fe<span class="infraTeclaAtalho">c</span>har</button>';



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

    .spnCaptionRegistros{
        font-size: 1.1em;
        float: right;
        margin-right: 5px;
        text-align: right;
        margin-top: 4px;
    }

    .divAjustePrazoGeral{
        border-radius: 6px;
        background-color: #d7d7d7;
        font-weight: 500;
        border-bottom: 1px #c3c3c3 solid;
        padding-top: 7px;
        padding-bottom:5px;
        width: 98%;
        float: left;
    }

    .spnAjustePrazoGeral{
        font-size: 10pt;
        margin-left: 6px;
        font-weight: bold;
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

    #divStatusProc {
        position: absolute;
        margin-left: 14.8%;
        margin-top: 8px;
        width: 20.5%;
    }

    #divServidor {
        position: absolute;
        margin-left: 32%;
        margin-top: 8px;
        width: 24%;
    }

    <?
    if (0) { ?></style><?
} ?>

<?php PaginaSEI::getInstance()->fecharStyle();

PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
require_once 'md_utl_geral_js.php';
?>
<?if(0){?><script type="text/javascript"><?}?>
    var msg24 = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_24); ?>';
    var msg25 = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_25); ?>';
    var msg104 = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_104)?>';
    var msg102  = '<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_102) ?>';

    function inicializar() {
        var urlCtrlProcessos = document.getElementById('hdnUrlControleProcessos').value;
        var idParam  = document.getElementById('hdnIdParametroCtrlUtl').value;
        var tpCtrl   = document.getElementById('hdnIdTipoControleUtl').value;
        var isGestor = document.getElementById('hdnIsGestor').value;
        var isPesquisar = '<?=$isPesquisar?>';
        var numRegistros = '<?=$numRegistros?>';

        if(isPesquisar == 1 && numRegistros > 0){
            expandirTodos('div0', document.getElementById('imgExpandir'), false);
        }

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

        if (isGestor == 0){
            alert(msg104);
            window.location.href = urlCtrlProcessos;
            return false;
        }

        infraEfeitoTabelas(true);
    }

    function fechar() {
        location.href = "<?= $strUrlFechar ?>";
    }

    function expandirTodos(idDiv,img) {
        var divFilha = document.getElementById(idDiv);

        if(divFilha.style.visibility == "hidden") {
            document.getElementById(idDiv).setAttribute("style", "overflow:hidden; max-height: 100em; transition: max-height 1s ease-in-out;");
            img.setAttribute('src', '/infra_css/imagens/ver_resumo.gif');//menos

        } else {
            document.getElementById(idDiv).setAttribute("style", "max-height: 0em; visibility: hidden");
            img.setAttribute('src', '/infra_css/imagens/ver_tudo.gif');//mais
        }
    }

    function pesquisar() {
        document.getElementById('frmGestaoLista').action = '<?= $strUrlPesquisar ?>';
        document.getElementById('frmGestaoLista').submit();
    }

    function confirmarAcao(situacao, numeroProcesso){
        var linkAprovar = '<?= SessaoSEI::getInstance()->assinarLink($strUrl . 'aprovar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_controle_desempenho='.$numIdControleDsmp.'&id_ajuste_prazo='.$numIdAjustePrazo.''); ?>';
        var linkReprovar = '<?= SessaoSEI::getInstance()->assinarLink($strUrl . 'reprovar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_controle_desempenho='.$numIdControleDsmp.'&id_ajuste_prazo='.$numIdAjustePrazo.''); ?>';
        var msg103padrao  = '<?=MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_103)?>';
        if(situacao == '<?=MdUtlAjustePrazoRN::$APROVADA?>'){
            var msg = setMensagemPersonalizada(msg103padrao, ['aprovação', numeroProcesso]);
            var validar = confirm(msg);
            if(validar == true){
                document.getElementById('frmGestaoLista').action = linkAprovar;
                document.getElementById('frmGestaoLista').submit();
                expandirTodos();
            }
        }
        if(situacao == '<?=MdUtlAjustePrazoRN::$REPROVADA?>'){
            msg = setMensagemPersonalizada(msg103padrao, ['reprovação', numeroProcesso]);
            validar = confirm(msg);
            if(validar == true){
                document.getElementById('frmGestaoLista').action = linkReprovar;
                document.getElementById('frmGestaoLista').submit();
                expandirTodos();
            }
        }
    }


    <?if(0){?></script><?}?>
<?php PaginaSEI::getInstance()->fecharJavaScript(); ?>


<?php
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
<form id="frmGestaoLista" method="post" onsubmit="return OnSubmitForm();"
      action="<?= PaginaSEI::getInstance()->formatarXHTML(
          SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])
      ) ?>">

    <?php
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    PaginaSEI::getInstance()->abrirAreaDados('7em');
    ?>
    <div class="bloco" id="divProcesso">
        <label id="lblProcesso" for="txtProcessoMdGestao" class="infraLabelOpcional">
            Processo:
        </label>

        <div class="clear"></div>

        <input type="text" id="txtProcessoMdGestao" name="txtProcessoMdGestao" class="inputFila infraText padraoInput"
               size="30"
               value="<?php echo $txtProcessoCampo ?>"
               maxlength="100" tabindex="502"/>
    </div>


    <div id="divStatusProc">
        <label id="lblStatusProc" for="selStatusProcMdGestao" accesskey="" class="infraLabelOpcional">Status do Processo:</label>
        <select id="selStatusProcMdGestao" name="selStatusProcMdGestao" class="infraSelect padraoSelect"
                onchange="pesquisar();"
                tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
            <?= $arrStatusProcesso ?>
        </select>
    </div>


    <div id="divServidor">
        <label id="lblServidor" for="selServidorMdGestao" accesskey="" class="infraLabelOpcional">Servidor:</label>
        <select id="selServidorMdGestao" name="selServidorMdGestao" class="infraSelect padraoSelect"
                onchange="pesquisar();"
                tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
            <?= $arrSelServidor ?>
        </select>
    </div>

    <?php
    PaginaSEI::getInstance()->fecharAreaDados();
    ?>

    <br/>
    <br/>
    
    <?php if($numRegistros > 0){?>
        <?php $cServico = 0; ?>
        <div>
            <div class="divAjustePrazoGeral">
                <span class="spnAjustePrazoGeral">
                    <img id="imgExpandir" style="margin-bottom: -2px;" onclick="expandirTodos('div<?php echo $cServico; ?>', this)"
                         src=" <?php echo PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/ver_tudo.gif' ?>"/>
                    Ajuste de Prazo
                </span>
                <?php echo $strCaption ?>
            <br/>
                <div id="div<?php echo $cServico; ?>"
                    <?php if($_GET['acao_origem'] == 'md_utl_gestao_ajust_prazo_aprovar' || $_GET['acao_origem'] == 'md_utl_gestao_ajust_prazo_reprovar'){?>
                    style="" <?php } else {?> style="max-height: 0px; visibility: hidden" <?php }?>>
                    <?php PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros);?>
                </div>
                <?php $cServico++; ?>
            </div>
        </div>
    <?php }?>
    <input type="hidden" id="hdnUrlControleProcessos" name="hdnUrlControleProcessos"
           value="<?php echo SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_controlar&acaoorigem=' . $_GET['acao']); ?>"/>
    <input type="hidden" id="hdnIdTipoControleUtl" name="hdnIdTipoControleUtl"
           value="<?php echo is_null($idTipoControle) ? '0' : $idTipoControle; ?>"/>
    <input type="hidden" id="hdnIdParametroCtrlUtl" name="hdnIdParametroCtrlUtl"
           value="<?php echo $isParametrizado ? '1' : '0'; ?>"/>
    <input type="hidden" id="hdnIsGestor" name="hdnIsGestor" value="<?php echo $isGestorSipSei ? '1' : '0';?>"/>
</form>

<?php
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
