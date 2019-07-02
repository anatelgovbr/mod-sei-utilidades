<?php

/**
 * @since  11/08/2016
 * @author André Luiz <andre.luiz@castgroup.com.br>
 */

require_once dirname(__FILE__) . '/../../SEI.php';

session_start();
SessaoSEI::getInstance()->validarLink();
//Id tipo de controle
$idTipoControle = array_key_exists('id_tipo_controle_utl', $_GET) ? $_GET['id_tipo_controle_utl'] : $_POST['hdnIdTipoControleUtl'];
PaginaSEI::getInstance()->prepararSelecao('md_utl_adm_fila_selecionar');

//URL Base
$strUrl = 'controlador.php?acao=md_utl_adm_fila_';
//URL das Actions
$strUrlDesativar = SessaoSEI::getInstance()->assinarLink($strUrl . 'desativar&acao_origem=' . $_GET['acao'].'&id_tipo_controle_utl='.$idTipoControle);
$strUrlReativar  = SessaoSEI::getInstance()->assinarLink($strUrl . 'reativar&acao_origem=' . $_GET['acao'].'&id_tipo_controle_utl='.$idTipoControle);
$strUrlExcluir   = SessaoSEI::getInstance()->assinarLink($strUrl . 'excluir&acao_origem=' . $_GET['acao'].'&id_tipo_controle_utl='.$idTipoControle);
$strUrlPesquisar = SessaoSEI::getInstance()->assinarLink($strUrl . 'listar&acao_origem=' . $_GET['acao'].'&id_tipo_controle_utl='.$idTipoControle);
$strUrlNovo      = SessaoSEI::getInstance()->assinarLink($strUrl . 'cadastrar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'].'&id_tipo_controle_utl='.$idTipoControle);
$strUrlFechar    = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_tp_ctrl_desemp_listar&acao_origem=' . $_GET['acao']. PaginaSEI::getInstance()->montarAncora($idTipoControle));
$displayNone ="style='display: none'";
$strTitulo      = 'Filas';

switch ($_GET['acao']) {

    //region Desativar
    case 'md_utl_adm_fila_desativar':
        try {

            $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
            $objMdUtlAdmFilaDTO = new MdUtlAdmFilaDTO();
            $objMdUtlAdmFilaRN = new MdUtlAdmFilaRN();
            $idExcluir = array_key_exists(0, $arrStrIds) ? $arrStrIds[0] : null;

            if (!is_null($idExcluir)) {
                if( $objMdUtlAdmFilaRN->validarExclusaoFila(array($idExcluir, $idTipoControle, false))){
                    $objMdUtlAdmFilaDTO->setNumIdMdUtlAdmFila($arrStrIds[0]);
                    $arrObjMdUtlAdmFila[] = $objMdUtlAdmFilaDTO;
                    $objMdUtlAdmFilaRN->desativar($arrObjMdUtlAdmFila);
                }
            }


        } catch (Exception $e) {
            PaginaSEI::getInstance()->processarExcecao($e);
        }
        header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao'].'&id_tipo_controle_utl='.$idTipoControle));
        die;
        break;
    //endregion

    //region Reativar
    case 'md_utl_adm_fila_reativar':

        try {
            $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
            $id        = reset($arrStrIds);
            for ($i = 0; $i < count($arrStrIds); $i++) {
                $objMdUtlAdmFilaDTO = new MdUtlAdmFilaDTO();
                $objMdUtlAdmFilaDTO->setNumIdMdUtlAdmFila($arrStrIds[$i]);
                $arrObjMdUtlAdmFila[] = $objMdUtlAdmFilaDTO;
            }
            $objMdUtlAdmFilaRN = new MdUtlAdmFilaRN();
            $objMdUtlAdmFilaRN->reativar($arrObjMdUtlAdmFila);

        } catch (Exception $e) {
            PaginaSEI::getInstance()->processarExcecao($e);
        }
        header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] .'&id_tipo_controle_utl='.$idTipoControle. '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($id)));
        die;

        break;

    //endregion

    //region Excluir
    case 'md_utl_adm_fila_excluir':
        try {


            $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
            $objMdUtlAdmFilaRN = new MdUtlAdmFilaRN();
            $mdUtlAdmFilaRN = new MdUtlAdmFilaRN();
            $idExcluir = array_key_exists(0, $arrStrIds) ? $arrStrIds[0] : null;

            if (!is_null($idExcluir)) {
                if ($mdUtlAdmFilaRN->validarExclusaoFila(array($idExcluir, $idTipoControle, true))) {
                    $objMdUtlAdmFilaDTO = new MdUtlAdmFilaDTO();
                    $objMdUtlAdmFilaDTO->setNumIdMdUtlAdmFila($idExcluir);
                    $arrObjMdUtlAdmFila[] = $objMdUtlAdmFilaDTO;
                    $objMdUtlAdmFilaRN->excluirRelacionamentos($idExcluir);
                    $objMdUtlAdmFilaRN->excluir($arrObjMdUtlAdmFila);
                }
            }




        } catch (Exception $e) {
            PaginaSEI::getInstance()->processarExcecao($e);
        }
        header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao'].'&id_tipo_controle_utl='.$idTipoControle));
        die;
        break;
    //endregion

    //region Selecionar
    case 'md_utl_adm_fila_selecionar':
        $strTitulo       = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Fila', 'Selecionar Fila');
        $strUrlPesquisar = SessaoSEI::getInstance()->assinarLink($strUrl . 'selecionar&acao_origem=' . $_GET['acao']);

        if(isset($_GET['id_object'])&&$_GET['id_object']=='objLupaFila') {
            $displayNone = "";
        }
        break;
    //endregion

    //region Listar
    case 'md_utl_adm_fila_listar':
  

        break;
    //endregion

    //region Erro
    default:
        throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.A");
    //endregion
}

//Verifica se é ação Selecionar
$bolSelecionar = $_GET['acao'] == 'md_utl_adm_fila_selecionar';


//Botões de ação do topo
$arrComandos[] = '<button type="button" accesskey="P" id="btnPesquisar" onclick="pesquisar()" class="infraButton">
                                    <span class="infraTeclaAtalho">P</span>esquisar
                              </button>';
if (!$bolSelecionar) {
    $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" onclick="novo()" class="infraButton">
                                    <span class="infraTeclaAtalho">N</span>ovo
                              </button>';

  //  $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" onclick="imprimir()" class="infraButton">
    //                                <span class="infraTeclaAtalho">I</span>mprimir
      //                        </button>';
    $arrComandos[] = '<button type="button" accesskey="c" id="btnFechar" onclick="fechar()" class="infraButton">
                                    Fe<span class="infraTeclaAtalho">c</span>har
                              </button>';
} else {
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton">
                                    <span class="infraTeclaAtalho">T</span>ransportar
                            </button>';

    $arrComandos[] = '<button type="button" accesskey="c" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton">
                                    Fe<span class="infraTeclaAtalho">c</span>har
                            </button>';
}


//Consulta
$objMdUtlAdmFilaDTO = new MdUtlAdmFilaDTO();
$objMdUtlAdmFilaDTO->retTodos();

if (isset ($_POST ['txtNomeFila']) && trim($_POST ['txtNomeFila']) != '') {
    $objMdUtlAdmFilaDTO->setStrNome('%' . $_POST ['txtNomeFila'] . '%', InfraDTO::$OPER_LIKE);
}

if (isset ($_POST ['txtDescricaoFila']) && trim($_POST ['txtDescricaoFila']) != '') {
    $objMdUtlAdmFilaDTO->setStrDescricao('%' . $_POST ['txtDescricaoFila'] . '%', InfraDTO::$OPER_LIKE);
}

if (isset ($_POST ['selMembro']) && trim($_POST ['selMembro']) != '') {
    $selMembro = $_POST['selMembro'];

    $objMdUtlAdmFilaDTO->retNumIdUsuario();
    $objMdUtlAdmFilaDTO->setNumIdUsuario($selMembro);
}


$objMdUtlAdmFilaDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTipoControle);
if($bolSelecionar){
    $objMdUtlAdmFilaDTO->setStrSinAtivo('S');
}
$objMdUtlAdmFilaDTO->retNumIdMdUtlAdmTpCtrlDesemp();
$objMdUtlAdmFilaRN = new MdUtlAdmFilaRN();

//Configuração da Paginação
PaginaSEI::getInstance()->prepararOrdenacao($objMdUtlAdmFilaDTO, 'Nome', InfraDTO::$TIPO_ORDENACAO_ASC, true);
PaginaSEI::getInstance()->prepararPaginacao($objMdUtlAdmFilaDTO, 200);


$arrObjMdUtlAdmFila = $objMdUtlAdmFilaRN->listar($objMdUtlAdmFilaDTO);

$arrObjMdUtlAdmFila = $objMdUtlAdmFilaRN->setFilaPadrao($arrObjMdUtlAdmFila);

PaginaSEI::getInstance()->processarPaginacao($objMdUtlAdmFilaDTO);
$numRegistros = count($arrObjMdUtlAdmFila);

//Tabela de resultado.
if ($numRegistros > 0) {

    $strResultado .= '<table width="99%" class="infraTable" summary="Fila">';
    $strResultado .= '<caption class="infraCaption">';
    $strResultado .= PaginaSEI::getInstance()->gerarCaptionTabela($strTitulo, $numRegistros);
    $strResultado .= '</caption>';
    //Cabeçalho da Tabela
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" align="center" width="1%" '.$displayNone.' >' . PaginaSEI::getInstance()->getThCheck() . '</th>';
    $strResultado .= '<th class="infraTh" width="30%">' . PaginaSEI::getInstance()->getThOrdenacao($objMdUtlAdmFilaDTO, 'Fila', 'Nome', $arrObjMdUtlAdmFila) . '</th>';
    $strResultado .= '<th class="infraTh" width="30%">' . PaginaSEI::getInstance()->getThOrdenacao($objMdUtlAdmFilaDTO, 'Descrição', 'Descricao', $arrObjMdUtlAdmFila) . '</th>';
    $strResultado .= '<th class="infraTh" width="25%">' . PaginaSEI::getInstance()->getThOrdenacao($objMdUtlAdmFilaDTO, 'Fila Padrão', 'FilaPadrao', $arrObjMdUtlAdmFila) . '</th>';
    $strResultado .= '<th class="infraTh" width="15%">Ações</th>';
    $strResultado .= '</tr>';

    //Linhas

    $strCssTr = '<tr class="infraTrEscura">';

    for ($i = 0; $i < $numRegistros; $i++) {

        //vars
        $strId                      = $arrObjMdUtlAdmFila[$i]->getNumIdMdUtlAdmFila();
        $strNomeTpControle          = $arrObjMdUtlAdmFila[$i]->getStrNome();
        $strDescricaoTpControle     = $arrObjMdUtlAdmFila[$i]->getStrDescricao();
        $strNomeTpControleParametro = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjMdUtlAdmFila[$i]->getStrNome());
        $bolRegistroAtivo           = $arrObjMdUtlAdmFila[$i]->getStrSinAtivo() == 'S';

        $strCssTr = !$bolRegistroAtivo ? '<tr class="trVermelha">' : ($strCssTr == '<tr class="infraTrClara">' ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">');
        $strResultado .= $strCssTr;

        //Linha Checkbox
        $strResultado .= '<td align="center" valign="top" '.$displayNone.' >';
        $strResultado .= PaginaSEI::getInstance()->getTrCheck($i, $strId, $strNomeTpControle);
        $strResultado .= '</td>';

        //Linha Nome
        $strResultado .= '<td>';
        $strResultado .= PaginaSEI::tratarHTML($strNomeTpControle);
        $strResultado .= '</td>';

        //Linha Descrição
        $strResultado .= '<td>';
        $strResultado .= PaginaSEI::tratarHTML($strDescricaoTpControle);
        $strResultado .= '</td>';

        //Linha Fila Padrão
        $strResultado .= '<td>';
        $strResultado .= PaginaSEI::tratarHTML($arrObjMdUtlAdmFila[$i]->getStrFilaPadrao());
        $strResultado .= '</td>';

        $strResultado .= '<td align="center">';

        //Ação Consulta
        if (!$bolSelecionar) {

            //Ação Consultar 
            $strResultado .= '<a href="' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink($strUrl . 'consultar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_tipo_controle_utl='.$idTipoControle.'&id_fila_utl='.$strId)) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/consultar.gif" title="Consultar Fila" alt="Consultar Fila" class="infraImg" /></a>&nbsp;';

            //Ação Alterar
            $strResultado .= '<a href="' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink($strUrl . 'alterar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_tipo_controle_utl='.$idTipoControle.'&id_fila_utl='.$strId)) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/alterar.gif" title="Alterar Fila" alt="Alterar Fila" class="infraImg" /></a>&nbsp;';

            //Ação Desativar
            if ($bolRegistroAtivo) {
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="desativar(\'' . $strId . '\',\'' . PaginaSEI::getInstance()->formatarParametrosJavaScript($strNomeTpControle) . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/desativar.gif" title="Desativar Fila" alt="Desativar Fila" class="infraImg" /></a>&nbsp;';
            }

            //Ação Reativar
            if (!$bolRegistroAtivo) {
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="reativar(\'' . $strId . '\',\'' . PaginaSEI::getInstance()->formatarParametrosJavaScript($strNomeTpControle) . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/reativar.gif" title="Reativar Fila" alt="Reativar Fila" class="infraImg" /></a>&nbsp;';
            }

/*            //Ação Excluir
            if($arrObjMdUtlAdmFila[$i]->getStrFilaPadrao() == 'Sim') {
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="alert(\''.MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_23, 'excluir').'\')" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/excluir.gif" title="Excluir Fila" alt="Excluir Fila" class="infraImg" /></a>&nbsp;';
            }else {*/
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="excluir(\'' . $strId . '\',\'' . PaginaSEI::getInstance()->formatarParametrosJavaScript($strNomeTpControle) . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/excluir.gif" title="Excluir Fila" alt="Excluir Fila" class="infraImg" /></a>&nbsp;';
         //   }
        } else {
            $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i, $strId);
        }
        $strResultado .= '</td>';
        $strResultado .= '</tr>';

    }
    $strResultado .= '</table>';
}

$selMembros = MdUtlAdmTpCtrlDesempINT::montarSelectMembros(array($idTipoControle),$selMembro);

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(':: ' . PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo . ' ::');

//Include de estilos CSS
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
if (0) { ?>
    <style><? }?>
.bloco {
    position: relative;
    float: left;
    margin-top: 1%;
    width: 90%;
    }

    .clear {
    clear: both;
    }

    inputFila{
    width: 45%!important;
    }

    textarea {
    resize: none;
    width : 60%;
    }

    select[multiple] {
    width: 61%;
    margin-top: 0.5%;
    }

    img[id^="imgExcluir"]{
    margin-left: -2px;
    }

    div[id^="divOpcoes"]{
    position: absolute;
    width:1%;
    left: 62%;
    top: 44%;
    }

    img[id^="imgAjuda"]{
    margin-bottom: -4px;
    }

    #divMembro{
        position: absolute;
        margin-left: 60%;
        margin-top: 1.1%;

    }

    #divDescricao{
        position: absolute;
        margin-left: 30%;
    }



<?
if (0) { ?></style><?
} ?>

<?php PaginaSEI::getInstance()->fecharStyle();

PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
require_once 'md_utl_geral_js.php';
?>

    var msg71 = '<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_71); ?>';
    var msg73 = '<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_73); ?>';
    var msg75 = '<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_75); ?>';

    function inicializar() {
    if ('<?= $_GET['acao'] ?>' == 'md_utl_adm_fila_selecionar') {
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
    } else {
    infraEfeitoTabelas();
    }

    addEventoEnter();
    }

    function addEventoEnter() {
        var obj1 = document.getElementById('txtNomeFila');
        var obj2 = document.getElementById('txtDescricaoFila');

        obj1.addEventListener("keypress", function (evt) {
            addPesquisarEnter(evt);
        });

        obj2.addEventListener("keypress", function (evt) {
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

    function pesquisar(){
    document.getElementById('frmTpControleLista').action='<?= $strUrlPesquisar ?>';
    document.getElementById('frmTpControleLista').submit();
    }

    function desativar(id, desc) {
    var msg = setMensagemPersonalizada(msg71, ['Fila', desc]);
    if (confirm(msg)) {
    document.getElementById('hdnInfraItemId').value = id;
    document.getElementById('frmTpControleLista').action = '<?= $strUrlDesativar ?>';
    document.getElementById('frmTpControleLista').submit();
    }
    }

    function reativar(id, desc){
    var msg = setMensagemPersonalizada(msg73, ['Fila', desc]);
    if (confirm(msg)){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmTpControleLista').action='<?= $strUrlReativar ?>';
    document.getElementById('frmTpControleLista').submit();
    }
    }

    function excluir(id, desc){
    var msg = setMensagemPersonalizada(msg75, ['Fila', desc]);
    if (confirm(msg)){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmTpControleLista').action='<?= $strUrlExcluir ?>';
    document.getElementById('frmTpControleLista').submit();
    }
    }

    function novo(){
    location.href="<?= $strUrlNovo ?>";
    }

    function imprimir(){
    infraImprimirTabela();
    }

    function fechar(){
    location.href="<?= $strUrlFechar ?>";
    }
<?php PaginaSEI::getInstance()->fecharJavaScript(); ?>


<?php
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
    <form id="frmTpControleLista" method="post"
          action="<?= PaginaSEI::getInstance()->formatarXHTML(
              SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])
          ) ?>">

        <?php PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos); ?>

        <div id="divInfraAreaDados" class="infraAreaDados">

            <div style="width: 45%;" class="bloco" id="divNome">
                    <label id="lblNomeTpControle" for="txtNomeFila" accesskey="S" class="infraLabelOpcional">
                        Nome:
                    </label>

                <div class="clear"></div>

                    <input type="text" style="width: 60%" id="txtNomeFila" name="txtNomeFila" class="inputFila infraText" size="30"
                           value="<?php echo array_key_exists('txtNomeFila', $_POST) ? $_POST['txtNomeFila'] : '' ?>" maxlength="100" tabindex="502"/>
            </div>

            <div style="width: 45%;" class="bloco" id="divDescricao">
                    <label id="lblDescricaoTpControle" for="txtDescricaoFila" accesskey="S"
                           class="infraLabelOpcional">
                        Descrição:
                    </label>

                <div class="clear"></div>

                    <input value="<?php echo array_key_exists('txtDescricaoFila', $_POST) ?  $_POST ['txtDescricaoFila'] : '' ?>" style="width: 60%" type="text" id="txtDescricaoFila" name="txtDescricaoFila" class="inputFila infraText"
                           size="30"  maxlength="100" tabindex="502"/>
            </div>

            <div id="divMembro">
                <label id="lblMembro" for="selMembro" accesskey="" class="infraLabelOpcional">Membro:</label>
                <select style="width:200px" id="selMembro"  name="selMembro" class="infraSelect" onchange="pesquisar();"
                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                    <?=$selMembros ?>
                </select>
            </div>

        </div>

        <input type="hidden" id="hdnIdTipoControleUtl" name="hdnIdTipoControleUtl" value="<?php echo $idTipoControle; ?>"/>

        <?php
        PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros);
        PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
        ?>

    </form>

<?php
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();

