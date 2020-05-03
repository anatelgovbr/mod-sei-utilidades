<?php
try {
    require_once dirname(__FILE__) . '/../../SEI.php';
    session_start();

    SessaoSEI::getInstance()->validarLink();

    $idTpCtrl     = isset($_GET['id_tipo_controle_utl'])?$_GET['id_tipo_controle_utl']:$_POST['hdnIdTpCtrlUtl'];
    PaginaSEI::getInstance()->prepararSelecao('md_utl_adm_status_selecionar');

    $objTpControleUtlRN    = new MdUtlAdmTpCtrlDesempRN();
    $objTipoControleUtlDTO = $objTpControleUtlRN->buscarObjTpControlePorId($idTpCtrl);

    switch($_GET['acao']){
        case 'md_utl_adm_status_selecionar' :
            $vlisPrmDistrib  = array_key_exists('is_prm_distr',$_GET) ? $_GET['is_prm_distr'] : $_POST['hdnIsPrmDistrib'];
            $isPrmDistrib    = $vlisPrmDistrib == 1;
            $nomeTpCtrl     = !is_null($objTipoControleUtlDTO) ? $objTipoControleUtlDTO->getStrNome() : '';

            $strTitulo = 'Selecionar Status';
            $isSelecionar = true;

            $objLupaStatus         =  array_key_exists('id_object', $_GET) ? $_GET['id_object'] : $_POST['hdnObjLupaStatus'];
            $objLupaStatusUnica    =  array_key_exists('id_object', $_GET) ? $_GET['id_object'] : $_POST['hdnObjLupaStatusUnica'];

            if($objLupaFilaUnica != '' || $objLupaFila != '') {
                $displayNone = "";
            }

            break;

        default:
            throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
    }

    $arrComandos = array();

    if ($_GET['acao'] == 'md_utl_adm_status_selecionar'){
        $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
        $arrComandos[] = '<button type="button" accesskey="C" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
    }

}catch(Exception $e){
    PaginaSEI::getInstance()->processarExcecao($e);
}

$arrObjStatus = array(
    MdUtlControleDsmpRN::$AGUARDANDO_TRIAGEM => MdUtlControleDsmpRN::$STR_AGUARDANDO_TRIAGEM,
    MdUtlControleDsmpRN::$AGUARDANDO_ANALISE => MdUtlControleDsmpRN::$STR_AGUARDANDO_ANALISE,
    MdUtlControleDsmpRN::$AGUARDANDO_REVISAO => MdUtlControleDsmpRN::$STR_AGUARDANDO_REVISAO,
    MdUtlControleDsmpRN::$AGUARDANDO_CORRECAO_TRIAGEM => MdUtlControleDsmpRN::$STR_AGUARDANDO_CORRECAO_TRIAGEM,
    MdUtlControleDsmpRN::$AGUARDANDO_CORRECAO_ANALISE => MdUtlControleDsmpRN::$STR_AGUARDANDO_CORRECAO_ANALISE,
);

$numRegistros = count($arrObjStatus);

//Tabela de resultado.
if ($numRegistros > 0) {
    $strResultado .= '<table width="99%" class="infraTable" summary="Status">';
    $strResultado .= '<caption class="infraCaption">';
    $strResultado .= PaginaSEI::getInstance()->gerarCaptionTabela('Status', $numRegistros);
    $strResultado .= '</caption>';
    //Cabeçalho da Tabela
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" align="center" width="1%" style="display: ">' . PaginaSEI::getInstance()->getThCheck() . '</th>';
    $strResultado .= '<th class="infraTh" width=140px" style="text-align: left; padding-left: 5px;">' . 'Status' . '</th>';
    $strResultado .= '<th class="infraTh" width="20%">Ações</th>';
    $strResultado .= '</tr>';

    //Linhas
    $strCssTr = '<tr class="infraTrEscura">';
    $i = 0;

    foreach ($arrObjStatus as $key => $item) {
        //vars
        $strNomeTpControle = $item;

        $strCssTr = !$strNomeTpControle ? '<tr class="trVermelha">' : ($strCssTr == '<tr class="infraTrClara">' ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">');
        $strResultado .= $strCssTr;

        //Linha Checkbox
        $strResultado .= '<td align="center" valign="top" style="display: ">';
        $strResultado .= PaginaSEI::getInstance()->getTrCheck($i, $key, $strNomeTpControle);
        $strResultado .= '</td>';

        //Linha Status
        $strResultado .= '<td>';
        $strResultado .= PaginaSEI::tratarHTML($strNomeTpControle);
        $strResultado .= '</td>';

        $strResultado .= '<td align="center">';
        $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i, $key);
        $strResultado .= '</td>';

        $i++;
    }
}

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema().' - '.$strTitulo);
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
?>
<?if(0){?><style><?}?>
    #frmMdUtlAdmStatusLista {
        margin-top: 5px;
    }
    <?if(0){?></style><?}?>
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
require_once 'md_utl_geral_js.php';
?>
<?if(0){?><script type="text/javascript"><?}?>

    function inicializar() {
        if ('<?=$_GET['acao']?>' == 'md_utl_adm_status_selecionar') {
        } else {
        }
        infraEfeitoTabelas(true);
    }

    <?if(0){?></script><?}?>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
    <form id="frmMdUtlAdmStatusLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
        <?
        PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        PaginaSEI::getInstance()->abrirAreaDados('0em');
        ?>

        <input type="hidden" name="hdnIdTpCtrlUtl" id="hdnIdTpCtrlUtl" value="<?php echo $idTpCtrl ?>"/>

        <?
        PaginaSEI::getInstance()->fecharAreaDados();
        PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
        PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
        ?>
    </form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
