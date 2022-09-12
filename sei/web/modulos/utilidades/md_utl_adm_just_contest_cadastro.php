<?
try{
    require_once dirname(__FILE__).'/../../SEI.php';
    session_start();

    SessaoSEI::getInstance()->validarLink();

    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

    $objMdUtlAdmJustContestDTO = new MdUtlAdmJustContestDTO();

    $idTpCtrl = array_key_exists('id_tipo_controle_utl', $_GET) ? $_GET['id_tipo_controle_utl'] : $_POST['hdnIdTpCtrlUtl'];
    $objTpControleUtlRN    = new MdUtlAdmTpCtrlDesempRN();
    $objTipoControleUtlDTO = $objTpControleUtlRN->buscarObjTpControlePorId($idTpCtrl);
    $nomeTpCtrl = !is_null($objTipoControleUtlDTO) ? $objTipoControleUtlDTO->getStrNome() : '';

    $arrComandos = array();

    switch ($_GET['acao']){

        case 'md_utl_adm_just_contest_consultar':
            $strTitulo = 'Consultar Justificativa de Contestação';
            $arrComandos[] = '<button type="button" accesskey="C" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&id_tipo_controle_utl='.$idTpCtrl.'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_md_utl_adm_just_contest'])).'\';" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
            $objMdUtlAdmJustContestDTO->setNumIdMdUtlAdmJustContest($_GET['id_md_utl_adm_just_contest']);
            $objMdUtlAdmJustContestDTO->setBolExclusaoLogica(false);
            $objMdUtlAdmJustContestDTO->retTodos();
            $objMdUtlAdmJustContestRN = new MdUtlAdmJustContestRN();
            $objMdUtlAdmJustContestDTO = $objMdUtlAdmJustContestRN->consultar($objMdUtlAdmJustContestDTO);
            if ($objMdUtlAdmJustContestDTO===null){
                throw new InfraException("Registro não encontrado.");
            }
            break;

        case 'md_utl_adm_just_contest_cadastrar':
            $strTitulo = 'Nova Justificativa de Contestação';
            $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarMdUtlAdmJustContestCadastro" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTpCtrl).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

            $objMdUtlAdmJustContestDTO->setNumIdMdUtlAdmJustContest(null);
            $objMdUtlAdmJustContestDTO->setStrNome($_POST['txtNome']);
            $objMdUtlAdmJustContestDTO->setStrDescricao($_POST['txaDescricao']);
            $objMdUtlAdmJustContestDTO->setStrSinAtivo('S');
            $objMdUtlAdmJustContestDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTpCtrl);

            if (isset($_POST['sbmCadastrarMdUtlAdmJustContestCadastro'])) {
                try{
                    $objMdUtlAdmJustContestRN = new MdUtlAdmJustContestRN();
                    $objMdUtlAdmJustContestDTO = $objMdUtlAdmJustContestRN->cadastrar($objMdUtlAdmJustContestDTO);
                    header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTpCtrl.'&id_md_utl_adm_just_contest='.$objMdUtlAdmJustContestDTO->getNumIdMdUtlAdmJustContest().PaginaSEI::getInstance()->montarAncora($objMdUtlAdmJustContestDTO->getNumIdMdUtlAdmJustContest())));
                    die;
                }catch(Exception $e){
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
            }
            break;

        case 'md_utl_adm_just_contest_alterar':
            $strTitulo = 'Alterar Justificativa de Contestação';
            $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarMdUtlAdmJustContest" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
            $strDesabilitar = 'disabled="disabled"';

            if (isset($_GET['id_md_utl_adm_just_contest'])) {
                $objMdUtlAdmJustContestDTO->setNumIdMdUtlAdmJustContest($_GET['id_md_utl_adm_just_contest']);
                $objMdUtlAdmJustContestDTO->retTodos();
                $objMdUtlAdmJustContestDTO->setBolExclusaoLogica(false);
                $objMdUtlAdmJustContestRN = new MdUtlAdmJustContestRN();
                $objMdUtlAdmJustContestDTO = $objMdUtlAdmJustContestRN->consultar($objMdUtlAdmJustContestDTO);
                if ($objMdUtlAdmJustContestDTO == null) {
                    throw new InfraException("Registro não encontrado.");
                }
            } else {
                $objMdUtlAdmJustContestDTO->setNumIdMdUtlAdmJustContest($_POST['hdnIdMdUtlAdmJustContest']);
                $objMdUtlAdmJustContestDTO->setStrNome($_POST['txtNome']);
                $objMdUtlAdmJustContestDTO->setStrDescricao($_POST['txaDescricao']);
                $objMdUtlAdmJustContestDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTpCtrl);
            }

            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&id_tipo_controle_utl='.$idTpCtrl.'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objMdUtlAdmJustContestDTO->getNumIdMdUtlAdmJustContest())).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

            if (isset($_POST['sbmAlterarMdUtlAdmJustContest'])) {
                try{
                    $objMdUtlAdmJustContestRN = new MdUtlAdmJustContestRN();
                    $objMdUtlAdmJustContestRN->alterar($objMdUtlAdmJustContestDTO);
                    PaginaSEI::getInstance()->adicionarMensagem('Justificativa de Contestação "'.$objMdUtlAdmJustContestDTO->getNumIdMdUtlAdmJustContest().'" alterado com sucesso.');
                    header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTpCtrl.PaginaSEI::getInstance()->montarAncora($objMdUtlAdmJustContestDTO->getNumIdMdUtlAdmJustContest())));
                    die;
                }catch(Exception $e){
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
            }
            break;

        default:
            throw new InfraException("Ação '".$_GET['acao']."não reconhecida.");
    }

}catch (Exception $e){
    PaginaSEI::getInstance()->processarExcecao($e);
}

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema().' - '.$strTitulo);
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
    <form id="sbmCadastrarMdUtlAdmJustContestCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])?>">
        <?
        PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        PaginaSEI::getInstance()->abrirAreaDados();
        ?>
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <div class="form-group">
                            <label id="txtNome" name="txtNome" for="txtNome" accesskey="f" class="infraLabelObrigatorio">Justificativa:
                                <img id="ancAjuda"
                                    src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg"
                                    class="infraImgModulo" <?= PaginaSEI::montarTitleTooltip('Nome da Justificativa de Contestação de Avaliação.', 'Ajuda') ?>/>
                            </label>
                            <input type="text" id="txtNome" name="txtNome" class="infraText form-control"
                                value="<?= PaginaSEI::tratarHTML($objMdUtlAdmJustContestDTO->getStrNome()); ?>"
                                tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <div class="form-group">
                            <label id="lblDescricao" for="txaDescricao" accesskey="q"
                                class="infraLabelObrigatorio">Descrição:
                                <img id="ancAjuda"
                                    src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg"
                                    class="infraImgModulo" <?= PaginaSEI::montarTitleTooltip('Texto que descreve a Justificativa de Contestação de Avaliação.', 'Ajuda') ?>/>
                            </label>
                            <textarea type="text" id="txaDescricao" rows="3" name="txaDescricao"
                                    class="infraTextarea form-control"
                                    onkeypress="return infraMascaraTexto(this,event,250);"
                                    maxlength="250"
                                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"><?php ?><?= PaginaSEI::tratarHTML($objMdUtlAdmJustContestDTO->getStrDescricao()); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?
        PaginaSEI::getInstance()->fecharAreaDados();
        ?>

        <input type="hidden" id="hdnIdMdUtlAdmJustContest" name="hdnIdMdUtlAdmJustContest" value="<?=$objMdUtlAdmJustContestDTO->getNumIdMdUtlAdmJustContest();?>" />
        <input type="hidden" name="hdnIdTpCtrlUtl" id="hdnIdTpCtrlUtl" value="<?php echo $idTpCtrl ?>"/>
    </form>
<?php
require_once 'md_utl_geral_js.php';
require_once 'md_utl_adm_just_contest_cadastro_js.php';
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>
