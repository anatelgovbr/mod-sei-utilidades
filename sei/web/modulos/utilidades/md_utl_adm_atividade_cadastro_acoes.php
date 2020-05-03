<?php
/**
 * Created by PhpStorm.
 * User: jhon.carvalho
 * Date: 06/08/2018
 * Time: 11:29
 */



$arrComandos = array();
//Id tipo de controle
$idTipoControle        = array_key_exists('id_tipo_controle_utl', $_GET) ? $_GET['id_tipo_controle_utl'] : $_POST['hdnIdTipoControleUtl'];
$objTpControleUtlRN    = new MdUtlAdmTpCtrlDesempRN();
$objTipoControleUtlDTO = $objTpControleUtlRN->buscarObjTpControlePorId($idTipoControle);
$strLinkAjaxVinTpAtiv  = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_utl_validar_alt_tipo_atividade');
$idAtividade           = array_key_exists('id_md_utl_adm_atividade', $_GET) ? $_GET['id_md_utl_adm_atividade'] :$_POST['hdnIdAtividade'];
$rdnTpAtividade        = null;
$chkAmostragem         = null;
$strItensTabela        = '';
$strDesabilitar        = '';
$mdUtlAdmAtividadeRN   = new MdUtlAdmAtividadeRN();
$bolConsultar          = false;

$strUtlValidarVinculoAnalise = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_utl_adm_validar_exclusao_prod_atv');

if($idAtividade>0){

    $mdUtlAdmAtvSerieProdRN = new MdUtlAdmAtvSerieProdRN();
    $mdUtlAdmAtividadeDTO   = new MdUtlAdmAtividadeDTO();
    $mdUtlAdmAtividadeDTO->retTodos();
    $mdUtlAdmAtividadeDTO->setNumIdMdUtlAdmAtividade($idAtividade);
    $mdUtlAdmAtividadeDTO->setBolExclusaoLogica(false);
    $mdUtlAdmAtividade      = $mdUtlAdmAtividadeRN->consultar($mdUtlAdmAtividadeDTO);

    $strAtividade       = $_POST['txtAtividade']  ? $_POST['txtAtividade']  : $mdUtlAdmAtividade->getStrNome() ;
    $strDescricao       = $_POST['txaDescricao']  ? $_POST['txaDescricao']  : $mdUtlAdmAtividade->getStrDescricao() ;
    $strValorUndEsforco = $_POST['txtUndEsforco'] ? $_POST['txtUndEsforco'] : $mdUtlAdmAtividade->getNumUndEsforcoAtv() ;
    $strPrazoExeAtv     = $_POST['txtExecucaoAtividade'] ? $_POST['txtExecucaoAtividade'] : $mdUtlAdmAtividade->getNumPrzExecucaoAtv() ;
    $strUndEsforcoRev   = $_POST['txtRevUnidEsf'] ? $_POST['txtRevUnidEsf'] : $mdUtlAdmAtividade->getNumUndEsforcoRev() ;
    $strPrzRevisaoAtv   = $_POST['txtRevAtividade'] ? $_POST['txtRevAtividade'] : $mdUtlAdmAtividade->getNumPrzRevisaoAtv() ;

    $rdnTpAtividade     = $_POST['rdnTpAtivdade'] ? $_POST['rdnTpAtivdade'] : $mdUtlAdmAtividade->getStrSinAnalise() ;
    
    $chkAmostragem  = $_POST['chkAtvRevAmost'] ? $_POST['chkAtvRevAmost'] : $mdUtlAdmAtividade->getStrSinAtvRevAmostragem() =='S'?'checked="checked"': null ;

    if($rdnTpAtividade == 'S'){

        $rdnComAnalise  = 'checked="checked"';

        $strItensTabela = $mdUtlAdmAtvSerieProdRN->retornarItensTabelasDinamica($idAtividade);
        $strItensTabela = $_POST['hdnTbProdutoEsperado'] ? $_POST['hdnTbProdutoEsperado']:PaginaSEI::getInstance()->gerarItensTabelaDinamica($strItensTabela);

    }else{
        $rdnSemAnalise  = 'checked="checked"';
    }

}else{

    $strAtividade       = $_POST['txtAtividade']  ? $_POST['txtAtividade']  : '';
    $strDescricao       = $_POST['txaDescricao']  ? $_POST['txaDescricao']  : '';
    $strValorUndEsforco = $_POST['txtUndEsforco'] ? $_POST['txtUndEsforco'] : '';
    $strPrazoExeAtv     = $_POST['txtExecucaoAtividade'] ? $_POST['txtExecucaoAtividade'] : '';
    $strUndEsforcoRev   = $_POST['txtRevUnidEsf'] ? $_POST['txtRevUnidEsf'] : '';
    $strPrzRevisaoAtv   = $_POST['txtRevAtividade'] ? $_POST['txtRevAtividade'] : '';

    $rdnTpAtividade     = $_POST['rdnTpAtivdade'] ? $_POST['rdnTpAtivdade'] : '';

    if($rdnTpAtividade == 'S'){

        $rdnComAnalise  = 'checked="checked"';
        $chkAmostragem  = $_POST['chkAtvRevAmost'] ? 'checked="checked"' : null;

        $strItensTabela = $_POST['hdnTbProdutoEsperado'];

    }elseif($rdnTpAtividade == 'N'){
        $rdnSemAnalise  = 'checked="checked"';
    }

}
// Carregar combos selects
$strItensSelTpProduto          = MdUtlAdmTpProdutoINT::montarSelectTpProduto($idTipoControle);
$strItensSelTpDocumentoExterno = MdUtlAdmAtividadeINT::montarSelectTipoDocumentoExterno();
$strItensSelTpDocumentoInterno = MdUtlAdmAtividadeINT::montarSelectTipoDocumentoInterno();


if(is_null($objTipoControleUtlDTO)){
    header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_tp_ctrl_desemp_listar&acao_origem=md_utl_adm_fila_listar'));
}


$nomeTpControle        = !is_null($objTipoControleUtlDTO) ? $objTipoControleUtlDTO->getStrNome() : '';

switch($_GET['acao']){

    case 'md_utl_adm_atividade_cadastrar':

        $strTitulo = 'Nova Atividade - '.$nomeTpControle;
        $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarAtividade" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
        $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTipoControle)).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

        if(isset($_POST['sbmCadastrarAtividade'])){

            if($mdUtlAdmAtividadeRN->verificarNomeDuplicidade(array($_POST['txtAtividade'],$idTipoControle))) {

                $mdUtlAdmAtividadeDTO = $mdUtlAdmAtividadeRN->cadastrarAtividade($idTipoControle);
                header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . '&id_tipo_controle_utl=' . $idTipoControle . PaginaSEI::getInstance()->montarAncora($mdUtlAdmAtividadeDTO->getNumIdMdUtlAdmAtividade())));

            }

        }

        break;

    case 'md_utl_adm_atividade_alterar':
        $isAlterar = 1;
        $strTitulo = 'Alterar Atividade - '.$nomeTpControle;

        $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarAtividade" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
        //$strDesabilitar = 'disabled="disabled"';
        $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&id_tipo_controle_utl='.$idTipoControle.'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_md_utl_adm_atividade']))).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';


        if(isset($_POST['sbmAlterarAtividade'])){

            $objMdUtlCtrlDsmpRN = new MdUtlControleDsmpRN();

            if($mdUtlAdmAtividadeRN->verificarNomeDuplicidade(array($_POST['txtAtividade'],$idTipoControle,$idAtividade))){

                $mdUtlAdmAtividadeDTO = $mdUtlAdmAtividadeRN->alterarAtividade(array($idAtividade,$idTipoControle,$mdUtlAdmAtividade->getStrSinAnalise()));
                header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTipoControle.PaginaSEI::getInstance()->montarAncora($mdUtlAdmAtividadeDTO->getNumIdMdUtlAdmAtividade())));
            }

        }

        break;

    case 'md_utl_adm_atividade_consultar':
        $strDesabilitar = 'disabled="disabled"';
        $bolConsultar   = true;
        $strTitulo = 'Consultar Atividade - '.$nomeTpControle;
        $arrComandos[] = '<button type="button" accesskey="c" name="btnFechar" id="btnFechar" value="Fechar" onclick="location.href=\''.PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&id_tipo_controle_utl='.$idTipoControle.'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_md_utl_adm_atividade']))).'\';" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';

        break;

    default:
        throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
}

