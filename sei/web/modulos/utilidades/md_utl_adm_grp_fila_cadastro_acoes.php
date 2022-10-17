<?php
/**
 * Created by PhpStorm.
 * User: jhon.carvalho
 * Date: 11/09/2018
 * Time: 11:20
 */

$idTpCtrl                   = array_key_exists('id_tipo_controle_utl', $_GET) ? $_GET['id_tipo_controle_utl'] : $_POST['hdnIdTpCtrlUtl'];
$strLinkFilaSelecao         = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_fila_selecionar&tipo_selecao=2&id_object=objLupaFila&id_tipo_controle_utl='.$idTpCtrl);
$strLinkFilaSelecaoUnica    = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_fila_selecionar&tipo_selecao=1&id_object=objLupaFilaUnica&id_tipo_controle_utl='.$idTpCtrl);
$strLinkAjaxFila            = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_utl_adm_fila_auto_completar&id_tipo_controle_utl='.$idTpCtrl);


SessaoSEI::getInstance()->validarLink();

SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

$strDesabilitar = '';
$selectMultiplo    = true;

$objTpControleUtlRN    = new MdUtlAdmTpCtrlDesempRN();
$objTipoControleUtlDTO = $objTpControleUtlRN->buscarObjTpControlePorId($idTpCtrl);
$nomeTpCtrl            = $objTipoControleUtlDTO->getStrNome();
$arrComandos           = array();
$strIdFila             = null;
$strNomeFila           = null;

$idMdAdmGrpFila = isset($_GET['id_md_utl_adm_grp_fila'])?$_GET['id_md_utl_adm_grp_fila'] : $_POST['hdnIdMdAdmGrpFila'];
$mdUtlAdmGrpFilaDTO = new MdUtlAdmGrpFilaDTO();

if($idMdAdmGrpFila > 0) {

    $mdUtlAdmGrpFilaRN  = new MdUtlAdmGrpFilaRN();

    $mdUtlAdmGrpFilaDTO->setNumIdMdUtlAdmGrpFila($idMdAdmGrpFila);
    $mdUtlAdmGrpFilaDTO->setBolExclusaoLogica(false);
    $mdUtlAdmGrpFilaDTO->retTodos(true);

    $mdUtlAdmGrpFilaDTO = $mdUtlAdmGrpFilaRN->consultar($mdUtlAdmGrpFilaDTO);

    $arrFila = array($mdUtlAdmGrpFilaDTO->getNumIdMdUtlAdmFila(),$mdUtlAdmGrpFilaDTO->getStrNomeFila());

    $strIdFila = $mdUtlAdmGrpFilaDTO->getNumIdMdUtlAdmFila();
    $strNomeFila = $mdUtlAdmGrpFilaDTO->getStrNomeFila();

    $idMdAdmGrp = $mdUtlAdmGrpFilaDTO->getNumIdMdUtlAdmGrp();

    $grupoAtividade = $mdUtlAdmGrpFilaDTO->getStrNomeGrupoAtividade();
    $descricaoGrupoAtividade = $mdUtlAdmGrpFilaDTO->getStrDescricaoGrupoAtividade();
    $strItensFila = PaginaSEI::getInstance()->gerarItensLupa(array($arrFila));
    $selectMultiplo = false;
}


$grupoAtividade             = isset($_POST['txtNome'])?$_POST['txtNome'] : $grupoAtividade;
$descricaoGrupoAtividade    = isset($_POST['txaDescricao'])?$_POST['txaDescricao'] : $descricaoGrupoAtividade;
$strItensFila               = isset($_POST['hdnFila'])? $_POST['hdnFila'] : $strItensFila;
$isConsultar                = false;


switch($_GET['acao']){
    case 'md_utl_adm_grp_fila_cadastrar':

        if($idMdAdmGrpFila>0) {

            $strTitulo = 'Incluir Nova Fila em Grupo de Atividade - '.$nomeTpCtrl;
            $strDesabilitar = "disabled='disabled'";
            $strItensFila   = "";
            $arrComandos[] = '<button type="submit" accesskey="S" name="sbmIncluirMdUtlAdmGrpFila" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTpCtrl.PaginaSEI::getInstance()->montarAncora($idMdAdmGrpFila)).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

        }else {

            $strTitulo = 'Novo Grupo de Atividade - ' . $nomeTpCtrl;
            $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarMdUtlAdmGrpFila" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . '&id_tipo_controle_utl=' . $idTpCtrl) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';
        }


        if (isset($_POST['sbmCadastrarMdUtlAdmGrpFila'])) {
            try{
                $mdUtlAdmGrpRN = new MdUtlAdmGrpRN();
                $mdUtlAdmGrpDTO = $mdUtlAdmGrpRN->cadastrarGrupoAtividadeFila($_POST);
                header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . '&id_tipo_controle_utl=' . $idTpCtrl.'&id_md_utl_adm_grp='.$mdUtlAdmGrpDTO->getNumIdMdUtlAdmGrp()));
                die;

            }catch(Exception $e){
                PaginaSEI::getInstance()->processarExcecao($e);
            }

        }else if(isset($_POST['sbmIncluirMdUtlAdmGrpFila'])){

            try {
                // Inclui uma nova fila
                $arrFila = PaginaSEI::getInstance()->getArrItensTabelaDinamica($_POST['hdnFila']);

                $mdUtlAdmGrpFilaDTO = new MdUtlAdmGrpFilaDTO();
                $mdUtlAdmGrpFilaRN = new MdUtlAdmGrpFilaRN();
                $mdUtlAdmGrpFilaDTO->setNumIdMdUtlAdmGrp($_POST['hdnIdMdAdmGrp']);
                $mdUtlAdmGrpFilaDTO->setNumIdMdUtlAdmFila($arrFila[0][0]);
                $mdUtlAdmGrpFilaDTO->setStrSinAtivo('S');
                $mdUtlAdmGrpFilaDTO->retTodos();

                if ($mdUtlAdmGrpFilaRN->validarDuplicidadeFila($mdUtlAdmGrpFilaDTO)) {

                    $mdUtlAdmGrpFilaDTO = $mdUtlAdmGrpFilaRN->cadastrar($mdUtlAdmGrpFilaDTO);
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . '&id_tipo_controle_utl=' . $idTpCtrl . PaginaSEI::getInstance()->montarAncora($mdUtlAdmGrpFilaDTO->getNumIdMdUtlAdmGrpFila())));
                    die;
                }

            }catch(Exception $e){
                PaginaSEI::getInstance()->processarExcecao($e);
            }
        }
        break;

    case 'md_utl_adm_grp_fila_alterar':

        $strTitulo = 'Alterar Grupo de Atividade - '.$nomeTpCtrl;



        $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarMdUtlAdmGrpFila" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
        $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTpCtrl.PaginaSEI::getInstance()->montarAncora($idMdAdmGrpFila)).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

        if(isset($_POST['sbmAlterarMdUtlAdmGrpFila'])){
            $mdUtlAdmGrpRN = new MdUtlAdmGrpRN();

            $mdUtlAdmGrpDTO = $mdUtlAdmGrpRN->cadastrarGrupoAtividadeFila($_POST);

            header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTpCtrl.PaginaSEI::getInstance()->montarAncora($idMdAdmGrpFila)));
            die;

        }
        break;

    case 'md_utl_adm_grp_fila_consultar':
        $isConsultar = true;
        $strTitulo = 'Consultar Grupo de Atividade - '.$nomeTpCtrl;
        $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTpCtrl).PaginaSEI::getInstance()->montarAncora($idMdAdmGrpFila).'\';" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';

        break;

    default:
        throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
}