<?php
/**
 * Created by PhpStorm.
 * User: rafael.veloso
 * Date: 27/09/2019
 * Time: 14:30
 */

try {
    require_once dirname(__FILE__) . '/../../SEI.php';

    session_start();
    SessaoSEI::getInstance()->validarLink();
    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

    $idTipoControleUtl     = isset($_GET['id_tipo_controle_utl'])?$_GET['id_tipo_controle_utl']:$_POST['hdnIdTipoControleUtl'];

    $idFila                = array_key_exists('id_fila_utl',$_GET) ? $_GET['id_fila_utl'] : (array_key_exists('hdnIdFilaUtl', $_POST) ? $_POST['hdnIdFilaUtl'] : null);
    $bolConsultar          = false;

    $strItensSelSinRetono          = MdUtlAdmPrmGrINT::montarSelectSinRetorno('');
    $strItensSelSinRetonoFila      = MdUtlAdmPrmGrINT::montarSelectSinRetorno('');
    $strItensSelSinRetonoStatus    = MdUtlAdmPrmGrINT::montarSelectSinRetorno('');
    $strItensSelSinRetonoAtividade = MdUtlAdmPrmGrINT::montarSelectSinRetorno('');

    $objMdUtlAdmPrmDsDTO           = new MdUtlAdmPrmDsDTO();
    $objMdUtlAdmRelPrmDsFilaDTO    = new MdUtlAdmRelPrmDsFilaDTO();
    $objMdUtlAdmRelPrmDsAtenDTO    = new MdUtlAdmRelPrmDsAtenDTO();
    $objMdUtlAdmRelPrmDsAtivDTO    = new MdUtlAdmRelPrmDsAtivDTO();

    $objMdUtlAdmPrmDsRN            = new MdUtlAdmPrmDsRN();
    $objMdUtlAdmRelPrmDsFilaRN     = new MdUtlAdmRelPrmDsFilaRN();
    $objMdUtlAdmRelPrmDsAtenRN     = new MdUtlAdmRelPrmDsAtenRN();
    $objMdUtlAdmRelPrmDsAtivRN     = new MdUtlAdmRelPrmDsAtivRN();

    $objMdUtlAdmTpCtrlDesempRN     = new MdUtlAdmTpCtrlDesempRN();
    $objMdUtlAdmTpCtrlDesempDTO    = new MdUtlAdmTpCtrlDesempDTO();

    $objMdUtlAdmTpCtrlDesempDTO->retTodos();
    $objMdUtlAdmTpCtrlDesempDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTipoControleUtl);
    $objMdUtlAdmTpCtrlDesemp = $objMdUtlAdmTpCtrlDesempRN->consultar($objMdUtlAdmTpCtrlDesempDTO);

        // Parâmetros
    $objMdUtlAdmPrmDsDTO->retTodos();
    $objMdUtlAdmPrmDsDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTipoControleUtl);
    $objMdUtlAdmPrmDsDTO = $objMdUtlAdmPrmDsRN->consultar($objMdUtlAdmPrmDsDTO);

    $idMdUtlAdmPrmDsDTO = $objMdUtlAdmPrmDsDTO ? $objMdUtlAdmPrmDsDTO->getNumIdMdUtlAdmPrmDs() : null;
    if($idMdUtlAdmPrmDsDTO > 0) {

        $selectDistribuicao        = $objMdUtlAdmPrmDsDTO->getStrSinPriorizarDistribuicao();
        $selectFila                = $objMdUtlAdmPrmDsDTO->getStrSinFila();
        $selectStatus              = $objMdUtlAdmPrmDsDTO->getStrSinStatusAtendimentoDsmp();
        $selectAtividade           = $objMdUtlAdmPrmDsDTO->getStrSinAtividade();
        $idMdUtlAdmPrmDs           = $objMdUtlAdmPrmDsDTO->getNumIdMdUtlAdmPrmDs();

        $strItensSelSinRetono          = MdUtlAdmPrmGrINT::montarSelectSinRetorno($selectDistribuicao);
        $strItensSelSinRetonoFila      = MdUtlAdmPrmGrINT::montarSelectSinRetorno($selectFila);
        $strItensSelSinRetonoStatus    = MdUtlAdmPrmGrINT::montarSelectSinRetorno($selectStatus);
        $strItensSelSinRetonoAtividade = MdUtlAdmPrmGrINT::montarSelectSinRetorno($selectAtividade);

        //montar Fila
        $objMdUtlAdmRelPrmDsFilaRN     = new MdUtlAdmRelPrmDsFilaRN();
        $strFila = $objMdUtlAdmRelPrmDsFilaRN->montarArrFila($idMdUtlAdmPrmDs); //monta os dados do array
        $arrFila = $strFila['itensTabela'];

        $strGridFila = PaginaSEI::getInstance()->gerarItensTabelaDinamica($arrFila); //preenche os dados na grid

        //montar Status
        $objMdUtlAdmRelPrmDsAtenRN     = new MdUtlAdmRelPrmDsAtenRN();
        $strStatus = $objMdUtlAdmRelPrmDsAtenRN->montarArrStatus($idMdUtlAdmPrmDs);
        $arrStatus = $strStatus['itensTabela'];
        $strGridStatus = PaginaSEI::getInstance()->gerarItensTabelaDinamica($arrStatus);

        //montar Atividade
        $objMdUtlAdmRelPrmDsAtivRN     = new MdUtlAdmRelPrmDsAtivRN();
        $strAtividade = $objMdUtlAdmRelPrmDsAtivRN->montarArrAtividade($idMdUtlAdmPrmDs);
        $arrAtividade = $strAtividade['itensTabela'];
        $strGridAtividade = PaginaSEI::getInstance()->gerarItensTabelaDinamica($arrAtividade);

    }

    switch($_GET['acao']){
        case 'md_utl_adm_prm_ds_cadastrar':
            $strTitulo     = 'Distribuição - '.$objMdUtlAdmTpCtrlDesemp->getStrNome().' - Homologação ';
            $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarMdUtlAdmPrmDistrib" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTipoControleUtl).PaginaSei::getInstance()->montarAncora($idTipoControleUtl).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

            if (isset($_POST['sbmCadastrarMdUtlAdmPrmDistrib'])) {
                try{

                    $objMdUtlAdmPrmDsDTO = new MdUtlAdmPrmDsDTO();
                    $objMdUtlAdmPrmDsRN  = new MdUtlAdmPrmDsRN();
                    $objMdUtlAdmPrmDsDTO->setNumIdMdUtlAdmPrmDs(null);
                    $objMdUtlAdmPrmDsDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTipoControleUtl);
                    $objMdUtlAdmPrmDsDTO->setStrSinPriorizarDistribuicao($_POST['selDistribuicao']);
                    $objMdUtlAdmPrmDsDTO->setStrSinFila($_POST['selFila']);
                    $objMdUtlAdmPrmDsDTO->setStrSinStatusAtendimentoDsmp($_POST['selStatus']);
                    $objMdUtlAdmPrmDsDTO->setStrSinAtividade($_POST['selAtividade']);

                    $objMdUtlAdmPrmDsNovoDTO = $objMdUtlAdmPrmDsRN->cadastrarParemetrizacao($objMdUtlAdmPrmDsDTO, $idMdUtlAdmPrmDsDTO);

                    header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTipoControleUtl.PaginaSEI::getInstance()->montarAncora($idTipoControleUtl)));
                    die;
                }catch(Exception $e){
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
            }
            break;

        default:
            throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
    }
} catch (Exception $e) {
    PaginaSEI::getInstance()->processarExcecao($e);
}


$strLinkFilaSelecao         = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_fila_selecionar&tipo_selecao=2&id_object=objLupaFila&id_tipo_controle_utl='.$idTipoControleUtl.'&is_prm_distr=1');
$strLinkAjaxFila            = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_utl_adm_fila_auto_completar&id_tipo_controle_utl='.$idTipoControleUtl.'&is_prm_distr=1');

$strLinkStatusSelecao       = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_status_selecionar&tipo_selecao=2&id_object=objLupaStatus&id_tipo_controle_utl='.$idTipoControleUtl.'&is_prm_distr=1');
$strLinkAjaxStatus          = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_utl_adm_status_auto_completar&id_tipo_controle_utl='.$idTipoControleUtl.'&is_prm_distr=1');

$strLinkAtividadeSelecao    = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_atividade_selecionar&tipo_selecao=2&id_object=objLupaAtividade&id_tipo_controle_utl='.$idTipoControleUtl.'&is_prm_distr=1');
$strLinkAjaxAtividade       = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_utl_adm_atividade_auto_completar&id_tipo_controle_utl='.$idTipoControleUtl.'&is_prm_distr=1');

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(':: '.PaginaSEI::getInstance()->getStrNomeSistema().' - '.$strTitulo.' ::');
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
require_once('md_utl_adm_prm_ds_cadastro_css.php');

?>

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();

require_once 'md_utl_geral_js.php';
require_once 'md_utl_adm_prm_ds_cadastro_js.php';
?>

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmPrmDistribCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
    <?
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    PaginaSEI::getInstance()->abrirAreaDados('auto');
    ?>

    <!--  Prazo para Resposta -->
    <div id="blocoPrazoRespostat" class="bloco">
        <fieldset style="width: 86%;" class="infraFieldset">
            <legend class="infraLegend">Prazo para Resposta</legend>

            <!-- Componente de Priorização Distribuição por Prazo para resposta indicado na Triagem -->
            <div id="divSelectPriorizar">
                <label for="selDistribuicao" accesskey="" class="infraLabelObrigatorio">Priorizar Distribuição por Prazo para Resposta indicado na Triagem:</label>
                <div class="clear"></div>
                <select utlCampoObrigatorio="o" id="selDistribuicao" name="selDistribuicao" class="infraSelect"
                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                    <?= $strItensSelSinRetono ?>
                </select>
            </div>

        </fieldset>

    </div>

    <!-- Bloco Fila -->
    <div id="blocoFila" class="bloco">
        <fieldset style="width: 86%;" class="infraFieldset">
            <legend class="infraLegend">Fila</legend>

            <!-- Componente de Priorização por Fila -->
            <div id="divSelectPriorizar">
                <label for="selFila" accesskey="" class="infraLabelObrigatorio">Priorizar por Fila:</label>
                <div class="clear"></div>
                <select utlCampoObrigatorio="o" id="selFila" name="selFila" class="infraSelect"
                        onchange="sinPriorizar()"
                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                    <?= $strItensSelSinRetonoFila ?>
                </select>
            </div>

            <!-- Componente de Filas -->
            <div id="divFila">

                <label id="lblFila" for="selItensFila" accesskey="" class="infraLabelObrigatorio">Filas:</label>
                <div class="clear"></div>
                <input type="text" id="txtFila" name="txtFila" class="infraText" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                <select id="selItensFila" name="selItensFila" size="4" multiple="multiple" class="infraSelect">
<!--                    --><?//= $strLinkFila ?>
                </select>
                <div id="divOpcoesFila">
                    <img id="imgLupaFila" onclick="objLupaFila.selecionar(700,500);"
                         src="/infra_css/imagens/lupa.gif" alt="Selecionar Fila Selecionada" title="Selecionar Fila" class="infraImg"/>
                    <br>
                    <img id="imgExcluirFila" onclick="objLupaFila.remover();"
                         src="/infra_css/imagens/remover.gif" alt="Remover Fila Selecionada"
                         title="Remover Unidade Selecionada" class="infraImg"/>
                </div>

            </div>

            <!-- Btn Adicionar Fila -->
            <div id="divBtnAdicionarFila">
                <button type="button" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"
                        onclick="adicionarTabelaFila()" name="btnAdicionarFila" id="btnAdicionarFila" class="infraButton">Adicionar
                </button>
            </div>

            <div style="width: 100%"></div>

            <div id="divTabelaFila" style="<?php echo $strGridFila == '' ? 'display: none' : ''?>">
                <table width="99%" class="infraTable mgnTop" summary="Fila" id="tbFila">
                <caption class="infraCaption caption"> <?= PaginaSEI::getInstance()->gerarCaptionTabela('Filas', 0) ?> </caption>
                    <tr>
                        <th class="infraTh" width="0" style="display: none;">Id</th>
                        <th class="infraTh" align="center"  width="80%">Fila</th>
                        <th class="infraTh" align="center" width="20%">Prioridade</th>
                        <th class="infraTh" align="center" width="15%"> Ações </th>
                    </tr>
                </table>

            </div>

        </fieldset>
    </div> <!-- Fim Bloco Fila -->

    <!-- Bloco Status -->
    <div id="blocoStatus" class="bloco">
        <fieldset style="width: 86%;" class="infraFieldset">
            <legend class="infraLegend">Status</legend>

            <!-- Componente de Priorização por Status -->
            <div id="divSelectPriorizar">
                <label for="selStatus" accesskey="" class="infraLabelObrigatorio">Priorizar por Status:</label>
                <div class="clear"></div>
                <select utlCampoObrigatorio="o" id="selStatus" name="selStatus" class="infraSelect"
                        onchange="sinPriorizar()"
                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                    <?= $strItensSelSinRetonoStatus ?>
                </select>
            </div>

            <!-- Componente de Status -->
            <div id="divStatus">

                <label id="lblStatus" for="selItensStatus" accesskey="" class="infraLabelObrigatorio">Status:</label>
                <div class="clear"></div>
                <input type="text" id="txtStatus" name="txtStatus" class="infraText" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                <select id="selItensStatus" name="selItensStatus" size="4" multiple="multiple" class="infraSelect">
                    <?= $strLinkStatus ?>
                </select>
                <div id="divOpcoesStatus">
                    <img id="imgLupaStatus" onclick="objLupaStatus.selecionar(700,500);"
                         src="/infra_css/imagens/lupa.gif" alt="Selecionar Status Selecionado" title="Selecionar Status" class="infraImg"/>
                    <br>
                    <img id="imgExcluirStatus" onclick="objLupaStatus.remover();"
                         src="/infra_css/imagens/remover.gif" alt="Remover Status Selecionado"
                         title="Remover Unidade Selecionada" class="infraImg"/>
                </div>

            </div>

            <!-- Btn Adicionar Status -->
            <div id="divBtnAdicionarStatus">
                <button type="button" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"
                        onclick="adicionarTabelaStatus();" name="btnAdicionarStatus" id="btnAdicionarStatus" class="infraButton">Adicionar
                </button>
            </div>

            <div style="width: 100%"></div>

            <div id="divTabelaStatus" style="<?php echo $strGridStatus == '' ? 'display: none' : ''?>">
                <table width="99%" class="infraTable mgnTop" summary="Status" id="tbStatus">
                    <caption class="infraCaption caption"> <?= PaginaSEI::getInstance()->gerarCaptionTabela('Status', 0) ?> </caption>
                    <tr>
                        <th class="infraTh" width="0" style="display: none;">Id</th>
                        <th class="infraTh" align="center" width="80%">Status</th>
                        <th class="infraTh" align="center" width="20%">Prioridade</th>
                        <th class="infraTh" align="center" width="15%"> Ações </th>
                    </tr>

                </table>

            </div>

        </fieldset>
    </div> <!-- Fim Bloco Status -->


    <!-- Bloco Atividade -->
    <div id="blocoAtividade" class="bloco">
        <fieldset style="width: 86%;" class="infraFieldset">
            <legend class="infraLegend">Atividade</legend>

            <!-- Componente de Priorização por Atividade -->
            <div id="divSelectPriorizar">
                <label for="selAtividade" accesskey="" class="infraLabelObrigatorio">Priorizar por Atividade:</label>
                <div class="clear"></div>
                <select utlCampoObrigatorio="o" id="selAtividade" name="selAtividade" class="infraSelect"
                        onchange="sinPriorizar()"
                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                    <?= $strItensSelSinRetonoAtividade ?>
                </select>
            </div>

            <!-- Componente de Atividade -->
            <div id="divAtividade">

                <label id="lblAtividade" for="selItensAtividade" accesskey="" class="infraLabelObrigatorio">Atividade:</label>
                <div class="clear"></div>
                <input type="text" id="txtAtividade" name="txtAtividade" class="infraText" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                <select id="selItensAtividade" name="selItensAtividade" size="4" multiple="multiple" class="infraSelect">
                    <?= $strLinkAtividade ?>
                </select>
                <div id="divOpcoesAtividade">
                    <img id="imgLupaAtividade" onclick="objLupaAtividade.selecionar(700,500);"
                         src="/infra_css/imagens/lupa.gif" alt="Selecionar Atividade Selecionada" title="Selecionar Atividade" class="infraImg"/>
                    <br>
                    <img id="imgExcluirAtividade" onclick="objLupaAtividade.remover();"
                         src="/infra_css/imagens/remover.gif" alt="Remover Atividade Selecionada"
                         title="Remover Unidade Selecionada" class="infraImg"/>
                </div>

            </div>

            <!-- Btn Adicionar Atividade -->
            <div id="divBtnAdicionarAtividade">
                <button type="button" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"
                        onclick="adicionarTabelaAtividade();" name="btnAdicionarAtividade" id="btnAdicionarAtividade" class="infraButton">Adicionar
                </button>
            </div>

            <div style="width: 100%"></div>

            <div id="divTabelaAtividade" style="<?php echo $strGridAtividade == '' ? 'display: none' : ''?>">

                <table width="99%" class="infraTable mgnTop" summary="Atividade" id="tbAtividade">
                    <caption class="infraCaption caption"> <?= PaginaSEI::getInstance()->gerarCaptionTabela('Atividade', 0) ?> </caption>
                    <tr>
                        <th class="infraTh" width="0" style="display: none;">Id</th>
                        <th class="infraTh" align="center" width="80%">Atividade</th>
                        <th class="infraTh" align="center" width="20%">Prioridade</th>
                        <th class="infraTh" align="center" width="15%"> Ações </th>
                    </tr>
                </table>

            </div>

        </fieldset>
    </div> <!-- Fim Bloco Atividade -->

    <input type="hidden" id="hdnFilaLupa" name="hdnFilaLupa" value="<?=$_POST['hdnFilaLupa']?>" />
    <input type="hidden" id="hdnIdFila" name="hdnIdFila" value="<?php echo $idFila;?>"/>
    <input type="hidden" id="hdnFila" name="hdnFila" value='<?php echo htmlentities($strGridFila)?>' />
    <input type="hidden" id="hdnIdFilaLupa" name="hdnIdFilaLupa" value=""/>

    <input type="hidden" id="hdnStatusLupa" name="hdnStatusLupa" value="<?=$_POST['hdnStatusLupa']?>" />
    <input type="hidden" id="hdnIdStatus" name="hdnIdStatus" value="<?php echo $idStatus;?>"/>
    <input type="hidden" id="hdnStatus" name="hdnStatus" value="<?=$strGridStatus?>" />
    <input type="hidden" id="hdnIdStatusLupa" name="hdnIdStatusLupa" value=""/>

    <input type="hidden" id="hdnAtividadeLupa" name="hdnAtividadeLupa" value="<?=$_POST['hdnAtividadeLupa']?>" />
    <input type="hidden" id="hdnIdAtividade" name="hdnIdAtividade" value="<?php echo $idAtividade;?>"/>
    <input type="hidden" id="hdnAtividade" name="hdnAtividade" value="<?=$strGridAtividade?>" />
    <input type="hidden" id="hdnIdAtividadeLupa" name="hdnIdAtividadeLupa" value=""/>

    <input type="hidden" id="hdnIdTipoControleUtl" name="hdnIdTipoControleUtl" value="<?= $idTipoControleUtl;?>"/>

    <?
    PaginaSEI::getInstance()->fecharAreaDados();
    PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>
