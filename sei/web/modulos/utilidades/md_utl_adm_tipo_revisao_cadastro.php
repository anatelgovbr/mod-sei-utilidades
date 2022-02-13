<?php
/**
 * Created by PhpStorm.
 * User: jhon.carvalho
 * Date: 03/08/2018
 * Time: 09:52
 */

try {
    require_once dirname(__FILE__).'/../../SEI.php';
    session_start();

    //////////////////////////////////////////////////////////////////////////////
    //InfraDebug::getInstance()->setBolLigado(false);
    //InfraDebug::getInstance()->setBolDebugInfra(true);
    //InfraDebug::getInstance()->limpar();
    //////////////////////////////////////////////////////////////////////////////

    SessaoSEI::getInstance()->validarLink();

    PaginaSEI::getInstance()->verificarSelecao('md_utl_adm_tipo_revisao_cadastrar');

    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

    $objMdUtlAdmTpAusenciaDTO = new MdUtlAdmTpAusenciaDTO();

    $strDesabilitar = '';

    $arrComandos = array();

    switch($_GET['acao']){
        case 'md_utl_adm_tipo_revisao_cadastrar':
            $strTitulo = 'Novo Tipo de Ausência';
            $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarMdUtlAdmTpAusencia" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

            $objMdUtlAdmTpAusenciaDTO->setNumIdMdUtlAdmTpAusencia(null);
            $objMdUtlAdmTpAusenciaDTO->setStrNome($_POST['txtNome']);
            $objMdUtlAdmTpAusenciaDTO->setStrDescricao($_POST['txaDescricao']);
            $objMdUtlAdmTpAusenciaDTO->setStrSinAtivo('S');

            if (isset($_POST['sbmCadastrarMdUtlAdmTpAusencia'])) {
                try{
                    $objMdUtlAdmTpAusenciaRN = new MdUtlAdmTpAusenciaRN();
                    $objMdUtlAdmTpAusenciaDTO = $objMdUtlAdmTpAusenciaRN->cadastrar($objMdUtlAdmTpAusenciaDTO);
                    PaginaSEI::getInstance()->adicionarMensagem('Tipo de Ausência "'.$objMdUtlAdmTpAusenciaDTO->getNumIdMdUtlAdmTpAusencia().'" cadastrado com sucesso.');
                    header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_md_utl_adm_tp_ausencia='.$objMdUtlAdmTpAusenciaDTO->getNumIdMdUtlAdmTpAusencia().PaginaSEI::getInstance()->montarAncora($objMdUtlAdmTpAusenciaDTO->getNumIdMdUtlAdmTpAusencia())));
                    die;
                }catch(Exception $e){
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
            }
            break;

        case 'md_utl_adm_tp_ausencia_alterar':
            $strTitulo = 'Alterar Tipo de Ausência';
            $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarMdUtlAdmTpAusencia" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
            $strDesabilitar = 'disabled="disabled"';

            if (isset($_GET['id_md_utl_adm_tp_ausencia'])){
                $objMdUtlAdmTpAusenciaDTO->setNumIdMdUtlAdmTpAusencia($_GET['id_md_utl_adm_tp_ausencia']);
                $objMdUtlAdmTpAusenciaDTO->retTodos();
                $objMdUtlAdmTpAusenciaRN = new MdUtlAdmTpAusenciaRN();
                $objMdUtlAdmTpAusenciaDTO = $objMdUtlAdmTpAusenciaRN->consultar($objMdUtlAdmTpAusenciaDTO);
                if ($objMdUtlAdmTpAusenciaDTO==null){
                    throw new InfraException("Registro não encontrado.");
                }
            } else {
                $objMdUtlAdmTpAusenciaDTO->setNumIdMdUtlAdmTpAusencia($_POST['hdnIdMdUtlAdmTpAusencia']);
                $objMdUtlAdmTpAusenciaDTO->setStrNome($_POST['txtNome']);
                $objMdUtlAdmTpAusenciaDTO->setStrDescricao($_POST['txaDescricao']);
                $objMdUtlAdmTpAusenciaDTO->setStrSinAtivo('S');
            }

            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objMdUtlAdmTpAusenciaDTO->getNumIdMdUtlAdmTpAusencia())).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

            if (isset($_POST['sbmAlterarMdUtlAdmTpAusencia'])) {
                try{
                    $objMdUtlAdmTpAusenciaRN = new MdUtlAdmTpAusenciaRN();
                    $objMdUtlAdmTpAusenciaRN->alterar($objMdUtlAdmTpAusenciaDTO);
                    PaginaSEI::getInstance()->adicionarMensagem('Tipo de Ausência "'.$objMdUtlAdmTpAusenciaDTO->getNumIdMdUtlAdmTpAusencia().'" alterado com sucesso.');
                    header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objMdUtlAdmTpAusenciaDTO->getNumIdMdUtlAdmTpAusencia())));
                    die;
                }catch(Exception $e){
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
            }
            break;

        case 'md_utl_adm_tp_ausencia_consultar':
            $strTitulo = 'Consultar Tipo de Ausência';
            $arrComandos[] = '<button type="button" accesskey="C" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_md_utl_adm_tp_ausencia'])).'\';" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
            $objMdUtlAdmTpAusenciaDTO->setNumIdMdUtlAdmTpAusencia($_GET['id_md_utl_adm_tp_ausencia']);
            $objMdUtlAdmTpAusenciaDTO->setBolExclusaoLogica(false);
            $objMdUtlAdmTpAusenciaDTO->retTodos();
            $objMdUtlAdmTpAusenciaRN = new MdUtlAdmTpAusenciaRN();
            $objMdUtlAdmTpAusenciaDTO = $objMdUtlAdmTpAusenciaRN->consultar($objMdUtlAdmTpAusenciaDTO);
            if ($objMdUtlAdmTpAusenciaDTO===null){
                throw new InfraException("Registro não encontrado.");
            }
            break;

        default:
            throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
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
<?if(0){?><style><?}?>
    #lblNome {position:absolute;left:0%;top:6%;width:40%;}
    #ancAjudaNome{position:absolute;left:40px;top:0%;}
    #txtNome {position:absolute;left:0%;top:45%;width:40%;}

    #lblDescricao {position:absolute;left:0%;top:10%;width:60%;}
    #ancAjudaDesc {position:absolute;left:63px;top:8%;}
    #txaDescricao {position:absolute;left:0%;top:25%;width:60%;}

    .tamanhoBtnAjuda{
        width: 16px;
        height: 16px;
    }

    <?if(0){?></style><?}?>
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
<?if(0){?><script type="text/javascript"><?}?>

    var msg11Padrao = '<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_11) ?>';
    
    function inicializar(){
        if ('<?=$_GET['acao']?>'=='md_utl_adm_tp_ausencia_cadastrar'){
            document.getElementById('txtNome').focus();
        } else if ('<?=$_GET['acao']?>'=='md_utl_adm_tp_ausencia_consultar'){
            infraDesabilitarCamposAreaDados();
        }else{
            document.getElementById('btnCancelar').focus();
        }
        infraEfeitoTabelas(true);
    }

    function validarCadastro() {
        if (infraTrim(document.getElementById('txtNome').value)=='') {
            var msg = setMensagemPersonalizada(msg11Padrao, ['Nome']);
            alert(msg);
            document.getElementById('txtNome').focus();
            return false;
        }

        if (infraTrim(document.getElementById('txaDescricao').value)=='') {
            var msg = setMensagemPersonalizada(msg11Padrao, ['Descrição']);
            alert(msg);
            document.getElementById('txaDescricao').focus();
            return false;
        }

        return true;
    }

    function OnSubmitForm() {
        return validarCadastro();
    }

    <?if(0){?></script><?}?>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
    <form id="frmMdUtlAdmTpAusenciaCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
        <?
        PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        //PaginaSEI::getInstance()->montarAreaValidacao();
        PaginaSEI::getInstance()->abrirAreaDados('4.5em');
        ?>
        <label id="lblNome" for="txtNome" accesskey="" class="infraLabelObrigatorio">Nome:</label>
        <a href="javascript:void(0);" id="ancAjudaNome" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" <?=PaginaSEI::montarTitleTooltip('Nome do Tipo de Ausência que irá aparecer para os servidores escolherem quando necessitarem se ausentar.')?>><img class="tamanhoBtnAjuda" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/ajuda.gif" class="infraImg"/></a>

        <input type="text" id="txtNome" name="txtNome" maxlength="100" class="infraText"  value="<?=PaginaSEI::tratarHTML($objMdUtlAdmTpAusenciaDTO->getStrNome());?>" onkeypress="return infraMascaraTexto(this,event,100);" maxlength="100" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
        <?
        PaginaSEI::getInstance()->fecharAreaDados();
        PaginaSEI::getInstance()->abrirAreaDados('12em');
        ?>
        <label id="lblDescricao" for="txaDescricao" accesskey="" class="infraLabelObrigatorio">Descrição:</label>
        <a href="javascript:void(0);" id="ancAjudaDesc" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" <?=PaginaSEI::montarTitleTooltip('Texto que define o tipo de ausência.')?>><img class="tamanhoBtnAjuda" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/ajuda.gif" class="infraImg"/></a>
        <textarea type="text" id="txaDescricao" rows="3" maxlength="250" name="txaDescricao" class="infraTextArea" onkeypress="return infraMascaraTexto(this,event,250);" maxlength="250" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?=PaginaSEI::tratarHTML($objMdUtlAdmTpAusenciaDTO->getStrDescricao());?></textarea>
        <?
        PaginaSEI::getInstance()->fecharAreaDados();
        ?>
        <input type="hidden" id="hdnIdMdUtlAdmTpAusencia" name="hdnIdMdUtlAdmTpAusencia" value="<?=$objMdUtlAdmTpAusenciaDTO->getNumIdMdUtlAdmTpAusencia();?>" />
        <?
        //PaginaSEI::getInstance()->montarAreaDebug();
        //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
        ?>
    </form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
