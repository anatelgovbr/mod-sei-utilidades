<?php
/**
 */

try {
    require_once dirname(__FILE__) . '/../../SEI.php';

    session_start();

    //////////////////////////////////////////////////////////////////////////////
    //InfraDebug::getInstance()->setBolLigado(false);
    //InfraDebug::getInstance()->setBolDebugInfra(true);
    //InfraDebug::getInstance()->limpar();
    //////////////////////////////////////////////////////////////////////////////

    SessaoSEI::getInstance()->validarLink();

    PaginaSEI::getInstance()->prepararSelecao('md_utl_adm_rel_prm_gr_proc_selecionar');

    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

    switch($_GET['acao']){
        case 'md_utl_adm_rel_prm_gr_proc_selecionar':
            $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Tipo de Processo','Selecionar Tipos de Processo');

            //Se cadastrou alguem
            if ($_GET['acao_origem']=='tipo_procedimento_cadastrar'){
                if (isset($_GET['id_tipo_procedimento'])){
                    PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_tipo_procedimento']);
                }
            }
            break;


        default:
            throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
    }

    $arrComandos = array();

    $arrComandos[] = '<button type="submit" accesskey="P" id="sbmPesquisar" value="Pesquisar" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';

    if ($_GET['acao'] == 'md_utl_adm_rel_prm_gr_proc_selecionar'){
        $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
    }

    $objTipoProcedimentoDTO = new TipoProcedimentoDTO();
    $objTipoProcedimentoDTO->retNumIdTipoProcedimento();
    $objTipoProcedimentoDTO->retStrNome();

   //Set Padrão para Seleção:
    $objMdUtlControleDsmpRN = new MdUtlControleDsmpRN();

    $idTipoControle = array_key_exists('id_tipo_controle', $_GET) ? $_GET['id_tipo_controle'] : $_POST['hdnIdTipoControle'];
    $idsTpProcesso = $objMdUtlControleDsmpRN->getTiposProcessoTipoControle($idTipoControle, true);
    if(count($idsTpProcesso) > 0){
        $objTipoProcedimentoDTO->setNumIdTipoProcedimento($idsTpProcesso, InfraDTO::$OPER_IN);
    }


    $strNomeTipoProcessoPesquisa = $_POST['txtNomeTipoProcessoPesquisa'];
    if (trim($strNomeTipoProcessoPesquisa) != ''){
        $objTipoProcedimentoDTO->setStrNome('%'.trim($strNomeTipoProcessoPesquisa.'%'),InfraDTO::$OPER_LIKE);
    }

    PaginaSEI::getInstance()->prepararOrdenacao($objTipoProcedimentoDTO,'Nome',InfraDTO::$TIPO_ORDENACAO_ASC);

    PaginaSEI::getInstance()->prepararPaginacao($objTipoProcedimentoDTO);

    $objTipoProcedimentoRN = new TipoProcedimentoRN();
    $arrObjTipoProcedimentoDTO = $objTipoProcedimentoRN->listarRN0244($objTipoProcedimentoDTO);

    PaginaSEI::getInstance()->processarPaginacao($objTipoProcedimentoDTO);
    $numRegistros = count($arrObjTipoProcedimentoDTO);

    if ($numRegistros > 0){

        $bolCheck = true;

        $strResultado = '';

        if ($_GET['acao']!='tipo_procedimento_reativar'){
            $strSumarioTabela = 'Tabela de Tipos de Processo.';
            $strCaptionTabela = 'Tipos de Processo';
        }else{
            $strSumarioTabela = 'Tabela de Tipos de Processo Inativos.';
            $strCaptionTabela = 'Tipos de Processo Inativos';
        }

        $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n"; //70
        $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
        $strResultado .= '<tr>';
        if ($bolCheck) {
            $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
        }
        $strResultado .= '<th class="infraTh" width="10%">ID</th>'."\n";
        $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objTipoProcedimentoDTO,'Nome','Nome',$arrObjTipoProcedimentoDTO).'</th>'."\n";
        //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objTipoProcedimentoDTO,'Descrição','Descricao',$arrObjTipoProcedimentoDTO).'</th>'."\n";
        $strResultado .= '<th class="infraTh" width="25%">Ações</th>'."\n";
        $strResultado .= '</tr>'."\n";
        $strCssTr='';
        for($i = 0;$i < $numRegistros; $i++){

            $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';

            $strResultado .= $strCssTr;

            if ($bolCheck){
                $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjTipoProcedimentoDTO[$i]->getNumIdTipoProcedimento(),$arrObjTipoProcedimentoDTO[$i]->getStrNome()).'</td>';
            }

            $strResultado .= '<td align="center">'.$arrObjTipoProcedimentoDTO[$i]->getNumIdTipoProcedimento().'</td>';
            $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjTipoProcedimentoDTO[$i]->getStrNome()).'</td>';
            //$strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjTipoProcedimentoDTO[$i]->getStrDescricao()).'</td>';
            $strResultado .= '<td align="center">';

            $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjTipoProcedimentoDTO[$i]->getNumIdTipoProcedimento());

            $strResultado .= '</td></tr>'."\n";
        }
        $strResultado .= '</table>';
    }
    if ($_GET['acao'] == 'md_utl_adm_rel_prm_gr_proc_selecionar'){
        $arrComandos[] = '<button type="button" accesskey="C" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
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
?>

    #lblNomeTipoProcessoPesquisa {position:absolute;left:0%;top:0%;}
    #txtNomeTipoProcessoPesquisa {position:absolute;left:0%;top:40%;width:70%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

    function inicializar(){
    if ('<?= $_GET['acao'] ?>'=='md_utl_adm_rel_prm_gr_proc_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
    }

    infraEfeitoTabelas();
    }


<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
    <form id="frmTipoProcedimentoLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
        <?
        //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
        PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        PaginaSEI::getInstance()->abrirAreaDados('5em');
        ?>

        <label id="lblNomeTipoProcessoPesquisa" accesskey="o" for="txtNomeTipoProcessoPesquisa" class="infraLabelOpcional">N<span class="infraTeclaAtalho">o</span>me:</label>
        <input type="text" id="txtNomeTipoProcessoPesquisa" name="txtNomeTipoProcessoPesquisa" value="<?=PaginaSEI::tratarHTML($strNomeTipoProcessoPesquisa)?>" class="infraText" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

        <input type="hidden" name="hdnIdTipoControle" name="hdnIdTipoControle" value="<?php echo $idTipoControle;?>"/>

        <?
        PaginaSEI::getInstance()->fecharAreaDados();
        PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
        //PaginaSEI::getInstance()->montarAreaDebug();
        PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
        ?>
    </form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>