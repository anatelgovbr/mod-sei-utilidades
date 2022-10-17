<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 25/09/2018 - criado por jhon.carvalho
 *
 * Versão do Gerador de Código: 1.41.0
 */

try {
    require_once dirname(__FILE__) . '/../../SEI.php';

    session_start();

    //////////////////////////////////////////////////////////////////////////////
    //InfraDebug::getInstance()->setBolLigado(false);
    //InfraDebug::getInstance()->setBolDebugInfra(true);
    //InfraDebug::getInstance()->limpar();
    //////////////////////////////////////////////////////////////////////////////

    //Id tipo de controle
    $idTipoControle = array_key_exists('id_tipo_controle_utl', $_GET) ? $_GET['id_tipo_controle_utl'] : $_POST['hdnIdTipoControleUtl'];

    SessaoSEI::getInstance()->validarLink();

    PaginaSEI::getInstance()->verificarSelecao('md_utl_adm_just_prazo_selecionar');

    //SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

    $objMdUtlAdmJustPrazoDTO = new MdUtlAdmJustPrazoDTO();

    $strDesabilitar = '';

    $arrComandos = array();

    switch ($_GET['acao']) {
        case 'md_utl_adm_just_prazo_cadastrar':
            $strTitulo = 'Nova Justificativa de Ajuste de Prazo';
            $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarMdUtlAdmJustPrazo" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . '&id_tipo_controle_utl=' . $idTipoControle) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

            $objMdUtlAdmJustPrazoDTO->setNumIdMdUtlAdmJustPrazo(null);
            $objMdUtlAdmJustPrazoDTO->setStrNome($_POST['txtNome']);
            $objMdUtlAdmJustPrazoDTO->setStrDescricao($_POST['txaDescricao']);
            $objMdUtlAdmJustPrazoDTO->setStrSinAtivo('S');
            $objMdUtlAdmJustPrazoDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTipoControle);

            $strSinDilacao = array_key_exists('rdoDilacao', $_POST) && $_POST['rdoDilacao'] == 'on' ? 'S' : 'N';
            $strSinSuspensao = array_key_exists('rdoSuspensao', $_POST) && $_POST['rdoSuspensao'] == 'on' ? 'S' : 'N';
            $strSinInterrupcao = array_key_exists('rdoInterrupcao', $_POST) && $_POST['rdoInterrupcao'] == 'on' ? 'S' : 'N';

            $objMdUtlAdmJustPrazoDTO->setStrSinDilacao($strSinDilacao);
            $objMdUtlAdmJustPrazoDTO->setStrSinSuspensao($strSinSuspensao);
            $objMdUtlAdmJustPrazoDTO->setStrSinInterrupcao($strSinInterrupcao);


            if (isset($_POST['sbmCadastrarMdUtlAdmJustPrazo'])) {
                try {
                    $objMdUtlAdmJustPrazoRN = new MdUtlAdmJustPrazoRN();
                    $objMdUtlAdmJustPrazoRN->validarDuplicidade(array($_POST['txtNome'], $idTipoControle));

                    $objMdUtlAdmJustPrazoDTO = $objMdUtlAdmJustPrazoRN->cadastrar($objMdUtlAdmJustPrazoDTO);


                    PaginaSEI::getInstance()->adicionarMensagem('Justificativa de Ajuste de Prazo "' . $objMdUtlAdmJustPrazoDTO->getNumIdMdUtlAdmJustPrazo() . '" cadastrada com sucesso.');
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . '&id_tipo_controle_utl=' . $idTipoControle . '&id_md_utl_adm_just_prazo=' . $objMdUtlAdmJustPrazoDTO->getNumIdMdUtlAdmJustPrazo() . PaginaSEI::getInstance()->montarAncora($objMdUtlAdmJustPrazoDTO->getNumIdMdUtlAdmJustPrazo())));
                    die;
                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
            }
            break;

        case 'md_utl_adm_just_prazo_alterar':
            $isAlterar = true;
            $strTitulo = 'Alterar Justificativa de Ajuste de Prazo';
            $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarMdUtlAdmJustPrazo" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
            $strDesabilitar = 'disabled="disabled"';

            if (isset($_GET['id_md_utl_adm_just_prazo'])) {
                $objMdUtlAdmJustPrazoDTO->setNumIdMdUtlAdmJustPrazo($_GET['id_md_utl_adm_just_prazo']);
                $objMdUtlAdmJustPrazoDTO->retTodos();
                $objMdUtlAdmJustPrazoRN = new MdUtlAdmJustPrazoRN();
                $objMdUtlAdmJustPrazoDTO = $objMdUtlAdmJustPrazoRN->consultar($objMdUtlAdmJustPrazoDTO);
                if ($objMdUtlAdmJustPrazoDTO == null) {
                    throw new InfraException("Registro não encontrado.");
                }
            } else {
                $objMdUtlAdmJustPrazoDTO->setNumIdMdUtlAdmJustPrazo($_POST['hdnIdMdUtlAdmJustPrazo']);
                $objMdUtlAdmJustPrazoDTO->setStrNome($_POST['txtNome']);
                $objMdUtlAdmJustPrazoDTO->setStrDescricao($_POST['txaDescricao']);
                $objMdUtlAdmJustPrazoDTO->setStrSinAtivo('S');
                $objMdUtlAdmJustPrazoDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTipoControle);

                $checkedDilacao = array_key_exists('rdoDilacao', $_POST) && $_POST['rdoDilacao'] == 'on' ? 'S' : 'N';
                $objMdUtlAdmJustPrazoDTO->setStrSinDilacao($checkedDilacao);

                $checkedDilacao = array_key_exists('rdoSuspensao', $_POST) && $_POST['rdoSuspensao'] == 'on' ? 'S' : 'N';
                $objMdUtlAdmJustPrazoDTO->setStrSinSuspensao($checkedDilacao);

                $checkedDilacao = array_key_exists('rdoInterrupcao', $_POST) && $_POST['rdoInterrupcao'] == 'on' ? 'S' : 'N';
                $objMdUtlAdmJustPrazoDTO->setStrSinInterrupcao($checkedDilacao);
            }

            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . '&id_tipo_controle_utl=' . $idTipoControle . PaginaSEI::getInstance()->montarAncora($objMdUtlAdmJustPrazoDTO->getNumIdMdUtlAdmJustPrazo())) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

            if (isset($_POST['sbmAlterarMdUtlAdmJustPrazo'])) {
                try {
                    $objMdUtlAdmJustPrazoRN = new MdUtlAdmJustPrazoRN();
                    $objMdUtlAdmJustPrazoRN->validarDuplicidade(array($_POST['txtNome'], $idTipoControle, $_POST['hdnIdMdUtlAdmJustPrazo']));

                    $strSinDilacao = array_key_exists('rdoDilacao', $_POST) && $_POST['rdoDilacao'] == 'on' ? 'S' : 'N';
                    $strSinSuspensao = array_key_exists('rdoSuspensao', $_POST) && $_POST['rdoSuspensao'] == 'on' ? 'S' : 'N';
                    $strSinInterrupcao = array_key_exists('rdoInterrupcao', $_POST) && $_POST['rdoInterrupcao'] == 'on' ? 'S' : 'N';

                    $objMdUtlAdmJustPrazoDTO->setStrSinDilacao($strSinDilacao);
                    $objMdUtlAdmJustPrazoDTO->setStrSinSuspensao($strSinSuspensao);
                    $objMdUtlAdmJustPrazoDTO->setStrSinInterrupcao($strSinInterrupcao);

                    $objMdUtlAdmJustPrazoRN->alterar($objMdUtlAdmJustPrazoDTO);
                    PaginaSEI::getInstance()->adicionarMensagem('Justificativa de Ajuste de Prazo "' . $objMdUtlAdmJustPrazoDTO->getNumIdMdUtlAdmJustPrazo() . '" alterada com sucesso.');
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . '&id_tipo_controle_utl=' . $idTipoControle . PaginaSEI::getInstance()->montarAncora($objMdUtlAdmJustPrazoDTO->getNumIdMdUtlAdmJustPrazo())));
                    die;
                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
            }
            break;

        case 'md_utl_adm_just_prazo_consultar':
            $strTitulo = 'Consultar Justificativa de Ajuste de Prazo';
            $arrComandos[] = '<button type="button" accesskey="c" name="btnFechar" value="Fechar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . '&id_tipo_controle_utl=' . $idTipoControle . PaginaSEI::getInstance()->montarAncora($_GET['id_md_utl_adm_just_prazo'])) . '\';" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
            $objMdUtlAdmJustPrazoDTO->setNumIdMdUtlAdmJustPrazo($_GET['id_md_utl_adm_just_prazo']);
            $objMdUtlAdmJustPrazoDTO->setBolExclusaoLogica(false);
            $objMdUtlAdmJustPrazoDTO->retTodos();
            $objMdUtlAdmJustPrazoRN = new MdUtlAdmJustPrazoRN();
            $objMdUtlAdmJustPrazoDTO = $objMdUtlAdmJustPrazoRN->consultar($objMdUtlAdmJustPrazoDTO);

            if ($objMdUtlAdmJustPrazoDTO === null) {
                throw new InfraException("Registro não encontrado.");
            }
            break;

        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    }


} catch (Exception $e) {
    PaginaSEI::getInstance()->processarExcecao($e);
}

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo);
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
require_once 'md_utl_adm_just_prazo_cadastro_css.php';
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
    <form id="frmMdUtlAdmJustPrazoCadastro" method="post" onsubmit="return OnSubmitForm();"
          action="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao']) ?>">
        <?
        PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        PaginaSEI::getInstance()->abrirAreaDados();
        ?>
        <div class="row linha">
            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <div class="form-group">
                            <label id="txtNome" name="txtNome" for="txtNome" accesskey="f" class="infraLabelObrigatorio">Justificativa:
                                <img id="ancAjuda"
                                    src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg"
                                    class="infraImgModulo" <?= PaginaSEI::montarTitleTooltip('Nome da Justificativa de Ajuste de Prazo.', 'Ajuda') ?>/>
                            </label>
                            <input type="text" id="txtNome" name="txtNome" class="infraText form-control"
                                value="<?= PaginaSEI::tratarHTML($objMdUtlAdmJustPrazoDTO->getStrNome()); ?>"
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
                                    class="infraImgModulo" <?= PaginaSEI::montarTitleTooltip('Texto que descreve a Justificativa de Ajuste de Prazo.', 'Ajuda') ?>/>
                            </label>
                            <textarea type="text" id="txaDescricao" rows="3" name="txaDescricao"
                                    class="infraTextarea form-control"
                                    onkeypress="return infraMascaraTexto(this,event,250);"
                                    maxlength="250"
                                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"><?php ?><?= PaginaSEI::tratarHTML($objMdUtlAdmJustPrazoDTO->getStrDescricao()); ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <div class="form-group">
                            <label id="lblTipoSolicitacao" for="lblTipoSolicitacao" accesskey="q"
                                class="infraLabelObrigatorio">Tipo de Solicitação:
                                <img id="ancAjuda"
                                    src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg"
                                    class="infraImgModulo" <?= PaginaSEI::montarTitleTooltip('Opção que define o Tipo de Solicitação de Ajuste de Prazo.', 'Ajuda') ?>/>
                            </label>
                            <div class="row">
                                <div class="col-12">
                                    <?php $checkedDilacao = $objMdUtlAdmJustPrazoDTO->getStrSinDilacao() != null && $objMdUtlAdmJustPrazoDTO->getStrSinDilacao() == 'S' ? 'checked="checked"' : ''; ?>
                                            <input <?php echo $checkedDilacao; ?> type="checkbox" name="rdoDilacao" id="rdoDilacao"
                                                                                class="infraCheckbox"/>
                                            <label class="infraLabelChec infraLabelOpcional mr-3"
                                                for="rdoDilacao" id="lblDilacao">Dilação</label>
                                
                                    <?php $checkeSuspensao = $objMdUtlAdmJustPrazoDTO->getStrSinSuspensao() != null && $objMdUtlAdmJustPrazoDTO->getStrSinSuspensao() == 'S' ? 'checked="checked"' : ''; ?>
                                        <input <?php echo $checkeSuspensao; ?> type="checkbox" name="rdoSuspensao"
                                                                            id="rdoSuspensao"
                                                                            class="infraCheckbox"/>
                                        <label class="infraLabelChec infraLabelOpcional mr-3"
                                            for="rdoSuspensao" id="lblSuspensao">Suspensão</label>
                                
                                    <?php $checkeInterrupcao = $objMdUtlAdmJustPrazoDTO->getStrSinInterrupcao() != null && $objMdUtlAdmJustPrazoDTO->getStrSinInterrupcao() == 'S' ? 'checked="checked"' : ''; ?>
                                        <input <?php echo $checkeInterrupcao; ?> type="checkbox" name="rdoInterrupcao"
                                                                                id="rdoInterrupcao" class="infraCheckbox"/>
                                        <label class="infraLabelChec infraLabelOpcional"
                                            for="rdoInterrupcao" id="lblInterrupcao">Interrupção</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" id="hdnIdMdUtlAdmJustPrazo" name="hdnIdMdUtlAdmJustPrazo"
               value="<?= $objMdUtlAdmJustPrazoDTO->getNumIdMdUtlAdmJustPrazo(); ?>"/>
        <input type="hidden" id="hdnIdTipoControleUtl" name="hdnIdTipoControleUtl" value="<?= $idTipoControle ?>"/>
        </div>
        <?
        PaginaSEI::getInstance()->fecharAreaDados();
        ?>
    </form>
<?php
require_once 'md_utl_geral_js.php';
require_once 'md_utl_adm_just_prazo_cadastro_js.php';
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
