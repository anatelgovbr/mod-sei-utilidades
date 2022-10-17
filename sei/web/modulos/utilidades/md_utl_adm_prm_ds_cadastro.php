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

    $strItensSelSinRetono               = MdUtlAdmPrmGrINT::montarSelectSinRetorno('');
    $strItensSelSinRetonoFila           = MdUtlAdmPrmGrINT::montarSelectSinRetorno('');
    $strItensSelSinRetonoStatus         = MdUtlAdmPrmGrINT::montarSelectSinRetorno('');
    $strItensSelSinRetonoAtividade      = MdUtlAdmPrmGrINT::montarSelectSinRetorno('');
    $strItensSelSinRetonoTipoProcesso   = MdUtlAdmPrmGrINT::montarSelectSinRetorno('');
    $strItensSelSinRetonoDiasUteis      = MdUtlAdmPrmGrINT::montarSelectSinRetorno('');

    $objMdUtlAdmPrmDsDTO           = new MdUtlAdmPrmDsDTO();
    $objMdUtlAdmRelPrmDsFilaDTO    = new MdUtlAdmRelPrmDsFilaDTO();
    $objMdUtlAdmRelPrmDsAtenDTO    = new MdUtlAdmRelPrmDsAtenDTO();
    $objMdUtlAdmRelPrmDsAtivDTO    = new MdUtlAdmRelPrmDsAtivDTO();
    $objMdUtlAdmRelPrmDsProcDTO    = new MdUtlAdmRelPrmDsProcDTO();

    $objMdUtlAdmPrmDsRN            = new MdUtlAdmPrmDsRN();
    $objMdUtlAdmRelPrmDsFilaRN     = new MdUtlAdmRelPrmDsFilaRN();
    $objMdUtlAdmRelPrmDsAtenRN     = new MdUtlAdmRelPrmDsAtenRN();
    $objMdUtlAdmRelPrmDsAtivRN     = new MdUtlAdmRelPrmDsAtivRN();
    $objMdUtlAdmRelPrmDsProcRN     = new MdUtlAdmRelPrmDsProcRN();

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
        $selectTipoProcesso        = $objMdUtlAdmPrmDsDTO->getStrSinTipoProcesso();
        $selectDiasUteis           = $objMdUtlAdmPrmDsDTO->getStrSinDiasUteis();

        $prioridadeDistribuicao    = $objMdUtlAdmPrmDsDTO->getNumDistribuicaoPrioridade();
        $prioridadeFila            = $objMdUtlAdmPrmDsDTO->getNumFilaPrioridade();
        $prioridadeStatus          = $objMdUtlAdmPrmDsDTO->getNumStatusPrioridade();
        $prioridadeAtividade       = $objMdUtlAdmPrmDsDTO->getNumAtividadePrioridade();
        $prioridadeTipoProcesso    = $objMdUtlAdmPrmDsDTO->getNumTipoProcessoPrioridade();
        $prioridadeDiasUteis       = $objMdUtlAdmPrmDsDTO->getNumDiasUteisPrioridade();
        $qtdDiasUteis              = $objMdUtlAdmPrmDsDTO->getNumQtdDiasUteis();

        $strItensSelSinRetono               = MdUtlAdmPrmGrINT::montarSelectSinRetorno($selectDistribuicao);
        $strItensSelSinRetonoFila           = MdUtlAdmPrmGrINT::montarSelectSinRetorno($selectFila);
        $strItensSelSinRetonoStatus         = MdUtlAdmPrmGrINT::montarSelectSinRetorno($selectStatus);
        $strItensSelSinRetonoAtividade      = MdUtlAdmPrmGrINT::montarSelectSinRetorno($selectAtividade);
        $strItensSelSinRetonoTipoProcesso   = MdUtlAdmPrmGrINT::montarSelectSinRetorno($selectTipoProcesso);
        $strItensSelSinRetonoDiasUteis      = MdUtlAdmPrmGrINT::montarSelectSinRetorno($selectDiasUteis);

        //montar Fila
        $objMdUtlAdmRelPrmDsFilaRN     = new MdUtlAdmRelPrmDsFilaRN();
        $arrFila = $objMdUtlAdmRelPrmDsFilaRN->montarArrFila($idMdUtlAdmPrmDs); //monta os dados do array
        $strFila = is_null($arrFila['itensTabela']) ? array() : $arrFila['itensTabela'];

        $strGridFila = PaginaSEI::getInstance()->gerarItensTabelaDinamica($strFila); //preenche os dados na grid

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

        //montar Tipo Processo
        $objMdUtlAdmRelPrmDsProcRN     = new MdUtlAdmRelPrmDsProcRN();
        $strTipoProcesso = $objMdUtlAdmRelPrmDsProcRN->montarArrTipoProcesso($idMdUtlAdmPrmDs);
        $arrTipoProcesso = $strTipoProcesso['itensTabela'];
        $strGridTipoProcesso = PaginaSEI::getInstance()->gerarItensTabelaDinamica($arrTipoProcesso);
    }

    switch($_GET['acao']){
        case 'md_utl_adm_prm_ds_cadastrar':
            $strTitulo     = 'Distribuição - '.$objMdUtlAdmTpCtrlDesemp->getStrNome();
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
                    $objMdUtlAdmPrmDsDTO->setStrSinTipoProcesso($_POST['selTipoProcesso']);
                    $objMdUtlAdmPrmDsDTO->setStrSinDiasUteis($_POST['selDiasUteis']);

                    $objMdUtlAdmPrmDsDTO->setNumDistribuicaoPrioridade($_POST['selDistribuicao'] != "S" ? null : $_POST['selPrioridadeDistribuicao']);
                    $objMdUtlAdmPrmDsDTO->setNumFilaPrioridade($_POST['selFila'] != "S" ? null : $_POST['selPrioridadeFila']);
                    $objMdUtlAdmPrmDsDTO->setNumStatusPrioridade($_POST['selStatus'] != "S" ? null : $_POST['selPrioridadeStatus']);
                    $objMdUtlAdmPrmDsDTO->setNumAtividadePrioridade($_POST['selAtividade'] != "S" ? null : $_POST['selPrioridadeAtividade']);
                    $objMdUtlAdmPrmDsDTO->setNumTipoProcessoPrioridade($_POST['selTipoProcesso'] != "S" ? null : $_POST['selPrioridadeTipoProcesso']);
                    $objMdUtlAdmPrmDsDTO->setNumDiasUteisPrioridade($_POST['selDiasUteis'] != "S" ? null : $_POST['selPrioridadeDiasUteis']);
                    $objMdUtlAdmPrmDsDTO->setNumQtdDiasUteis($_POST['selDiasUteis'] != "S" ? null : $_POST['qtdDiasUteis']);

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

$strLinkTipoProcessoSelecao    = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_rel_prm_gr_proc_selecionar&tipo_selecao=2&id_object=objLupaTipoProcesso&id_tipo_controle='.$idTipoControleUtl);
$strLinkAjaxTipoProcesso       = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_utl_adm_processo_parametrizado_auto_completar&id_parametro='.$idTipoControleUtl);

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(':: '.PaginaSEI::getInstance()->getStrNomeSistema().' - '.$strTitulo.' ::');

PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
PaginaSEI::getInstance()->fecharStyle();
require_once('md_utl_geral_css.php');

PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
PaginaSEI::getInstance()->fecharJavaScript();

PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>

<form id="frmPrmDistribCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
    <?php
        PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        PaginaSEI::getInstance()->abrirAreaDados();
        $col_def_01 = 'col-sm-6 col-md-6 col-lg-4';
        $col_def_02 = 'col-sm-4 col-md-4 col-lg-3';
    ?>

    <!--  Prazo para Resposta -->
    <div id="blocoPrazoRespostat" class="rowFieldSet mb-3">        
        <fieldset class="infraFieldset fieldset-comum form-control">
            <legend class="infraLegend">Prazo para Resposta</legend>
            <!-- Componente de Priorização Distribuição por Prazo para resposta indicado na Triagem -->
            <div id="divSelectPriorizar" class="row">
                <div class="col-sm-8 col-md-8 col-lg-8 mb-2">
                    <label id="lblSelDistribuicao" for="selDistribuicao" accesskey="" class="infraLabelObrigatorio">Priorizar Distribuição por Prazo para Resposta indicado na Triagem:</label>
                    <select utlCampoObrigatorio="o" id="selDistribuicao" name="selDistribuicao" class="infraSelect form-control" 
                            onchange="sinPriorizar()" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                        <?= $strItensSelSinRetono ?>
                    </select>
                </div>
                <!-- Componente de Prioridade geral por Distribuição -->
                <div id="divPrioridadeDistribuicao" class="col-sm-2 col-md-2 col-lg-3">
                    <label id="lblPrioridadeDistribuicao" for="lblPrioridadeDistribuicao" accesskey="" class="infraLabelObrigatorio">Prioridade Geral:</label>
                    <select utlCampoObrigatorio="o" id="selPrioridadeDistribuicao" name="selPrioridadeDistribuicao" class="infraSelect prioridadeGeral form-control" 
                            tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                    </select>
                </div>
            </div>
        </fieldset>       
    </div>

    <!-- Bloco Fila -->
    <div id="blocoFila" class="rowFieldSet mb-3">        
        <fieldset class="infraFieldset fieldset-comum form-control">
            <legend class="infraLegend">Fila</legend>
            <!-- Componente de Priorização por Fila -->
            <div id="divSelectPriorizar">
                <div class="row mb-3">
                    <div class="<?= $col_def_01 ?> mb-2">
                        <label id="lblSelFila" for="selFila" accesskey="" class="infraLabelObrigatorio">Priorizar por Fila:</label>
                        <select utlCampoObrigatorio="o" id="selFila" name="selFila" class="infraSelect form-control"
                                onchange="sinPriorizar()"
                                tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                            <?= $strItensSelSinRetonoFila ?>
                        </select>
                    </div>
                    <!-- Componente de Prioridade geral por Fila -->
                    <div class="<?= $col_def_02 ?>" id="divPrioridadeFila">
                        <label id="lblPrioridadeFila" for="lblPrioridadeFila" accesskey="" class="infraLabelObrigatorio">Prioridade Geral:</label>                        
                        <select utlCampoObrigatorio="o" id="selPrioridadeFila" name="selPrioridadeFila" 
                                class="infraSelect prioridadeGeral form-control" 
                                tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                            <?= $prioridadeFila ?>
                        </select>                        
                    </div>
                </div>
            </div>

            <!-- Componente de Filas -->
            <div id="divFila">
                <div class="row mb-1">
                    <div class="col-xs-4 col-sm-7 col-md-7 col-lg-7">
                        <label id="lblFila" for="selItensFila" accesskey="" class="infraLabelObrigatorio">Filas:</label>
                        <input type="text" id="txtFila" name="txtFila" class="infraText form-control" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-12 col-md-12 col-lg-10">
                        <div class="input-group">
                            <select id="selItensFila" name="selItensFila" multiple="multiple" class="infraSelect form-control"></select>
                            <div id="divOpcoesFila" class="ml-1">
                                <img id="imgLupaFila" onclick="objLupaFila.selecionar(700,500);"
                                    src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/pesquisar.svg'?>" alt="Selecionar Fila Selecionada" title="Selecionar Fila" class="infraImg"/>
                                <br>
                                <img id="imgExcluirFila" onclick="objLupaFila.remover();"
                                    src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/remover.svg'?>" alt="Remover Fila Selecionada"
                                    title="Remover Unidade Selecionada" class="infraImg"/>
                                <br>
                                <span id="divBtnAdicionarFila">
                                    <button type="button" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"
                                            onclick="adicionarTabelaFila()" name="btnAdicionarFila" id="btnAdicionarFila" class="infraButton ml-3">Adicionar
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div id="divTabelaFila" style="<?= $strGridFila == '' ? 'display: none' : ''?>">
                            <table class="infraTable mgnTop" summary="Fila" id="tbFila">
                                <caption class="infraCaption caption"> <?= PaginaSEI::getInstance()->gerarCaptionTabela('Filas', 0) ?> </caption>
                                <tr>
                                    <th class="infraTh" width="0" style="display: none;">Id</th>
                                    <th class="infraTh" align="center"  width="80%">Fila</th>
                                    <th class="infraTh" align="center" width="20%">Prioridade</th>
                                    <th class="infraTh" align="center" width="15%"> Ações </th>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>
    <!-- Fim Bloco Fila -->

    <!-- Bloco Status -->
    <div id="blocoStatus" class="rowFieldSet mb-3">
        <fieldset class="infraFieldset fieldset-comum form-control">
            <legend class="infraLegend">Situação</legend>

            <!-- Componente de Priorização por Status -->
            <div id="divSelectPriorizar">
                <div class="row mb-3">
                    <div class="<?= $col_def_01 ?> mb-2">
                        <label id="lblSelStatus" for="selStatus" accesskey="" class="infraLabelObrigatorio">Priorizar por Situação:</label>
                        <select utlCampoObrigatorio="o" id="selStatus" name="selStatus" class="infraSelect form-control" onchange="sinPriorizar()" 
                                tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                            <?= $strItensSelSinRetonoStatus ?>
                        </select>
                    </div>
                    <!-- Componente de Prioridade geral por Status -->
                    <div class="<?= $col_def_02 ?>" id="divPrioridadeStatus">
                        <label id="lblPrioridadeStatus" for="lblPrioridadeStatus" accesskey="" class="infraLabelObrigatorio">Prioridade Geral:</label>
                        <select utlCampoObrigatorio="o" id="selPrioridadeStatus" name="selPrioridadeStatus" class="infraSelect prioridadeGeral form-control" 
                                tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                            <?= $prioridadeStatus ?>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Componente de Status -->
            <div id="divStatus">
                <div class="row mb-1">
                    <div class="col-xs-4 col-sm-7 col-md-7 col-lg-7">
                        <label id="lblStatus" for="selItensStatus" accesskey="" class="infraLabelObrigatorio">Situação:</label>
                        <input type="text" id="txtStatus" name="txtStatus" class="infraText form-control" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-12 col-md-12 col-lg-10">
                        <div class="input-group">
                            <select id="selItensStatus" name="selItensStatus" multiple="multiple" class="infraSelect form-control">
                                <?= $strLinkStatus ?>
                            </select>
                            <div id="divOpcoesStatus" class="ml-1">
                                <img id="imgLupaStatus" onclick="objLupaStatus.selecionar(700,500);"
                                    src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/pesquisar.svg'?>" alt="Selecionar Situação Selecionado" title="Selecionar Situação" class="infraImg"/>
                                <br>
                                <img id="imgExcluirStatus" onclick="objLupaStatus.remover();"
                                    src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/remover.svg'?>" alt="Remover Status Selecionado"
                                    title="Remover Unidade Selecionada" class="infraImg"/>
                                <br>
                                <span id="divBtnAdicionarStatus">
                                    <button type="button" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"
                                            onclick="adicionarTabelaStatus();" name="btnAdicionarStatus" id="btnAdicionarStatus" class="infraButton ml-3">Adicionar
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div id="divTabelaStatus" style="<?= $strGridStatus == '' ? 'display: none' : ''?>">
                            <table class="infraTable mgnTop" summary="Status" id="tbStatus">
                                <caption class="infraCaption caption"> <?= PaginaSEI::getInstance()->gerarCaptionTabela('Situação', 0) ?> </caption>
                                <tr>
                                    <th class="infraTh" width="0" style="display: none;">Id</th>
                                    <th class="infraTh" align="center" width="80%">Situação</th>
                                    <th class="infraTh" align="center" width="20%">Prioridade</th>
                                    <th class="infraTh" align="center" width="15%"> Ações </th>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
    </div> 
    <!-- Fim Bloco Status -->

    <!-- Bloco Atividade -->
    <div id="blocoAtividade" class="rowFieldSet mb-3">
        <fieldset class="infraFieldset fieldset-comum form-control">
            <legend class="infraLegend">Atividade</legend>
            <!-- Componente de Priorização por Atividade -->
            <div id="divSelectPriorizar">
                <div class="row mb-3">
                    <div class="<?= $col_def_01 ?> mb-2">
                        <label id="lblAtividade" for="selAtividade" accesskey="" class="infraLabelObrigatorio">Priorizar por Atividade:</label>
                        <select utlCampoObrigatorio="o" id="selAtividade" name="selAtividade" class="infraSelect form-control" onchange="sinPriorizar()" 
                                tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                            <?= $strItensSelSinRetonoAtividade ?>
                        </select>
                    </div>

                     <!-- Componente de Prioridade geral por Atividade -->
                    <div class="<?= $col_def_02 ?>" id="divPrioridadeAtividade">
                        <label id="lblPrioridadeAtividade" for="lblPrioridadeAtividade" accesskey="" class="infraLabelObrigatorio">Prioridade Geral:</label>
                        <select utlCampoObrigatorio="o" id="selPrioridadeAtividade" name="selPrioridadeAtividade" class="infraSelect prioridadeGeral form-control" 
                                tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                            <?= $prioridadeAtividade ?>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Componente de Atividade -->
            <div id="divAtividade">
                <div class="row mb-1">
                    <div class="col-xs-4 col-sm-7 col-md-7 col-lg-7">
                        <label id="lblAtividade" for="selItensAtividade" accesskey="" class="infraLabelObrigatorio">Atividade:</label>
                        <input type="text" id="txtAtividade" name="txtAtividade" class="infraText form-control" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-12 col-md-12 col-lg-10">
                        <div class="input-group">
                            <select id="selItensAtividade" name="selItensAtividade" multiple="multiple" class="infraSelect form-control">
                                <?= $strLinkAtividade ?>
                            </select>
                            <div id="divOpcoesAtividade" class="ml-1">
                                <img id="imgLupaAtividade" onclick="objLupaAtividade.selecionar(700,500);"
                                    src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/pesquisar.svg'?>" alt="Selecionar Atividade Selecionada" title="Selecionar Atividade" class="infraImg"/>
                                <br>
                                <img id="imgExcluirAtividade" onclick="objLupaAtividade.remover();"
                                    src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/remover.svg'?>" alt="Remover Atividade Selecionada"
                                    title="Remover Unidade Selecionada" class="infraImg"/>
                                <br>
                                <span id="divBtnAdicionarAtividade">
                                    <button type="button" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"
                                            onclick="adicionarTabelaAtividade();" name="btnAdicionarAtividade" id="btnAdicionarAtividade" 
                                            class="infraButton ml-3"> Adicionar
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>                
                <div class="row">
                    <div class="col-12">
                        <div id="divTabelaAtividade" style="<?= $strGridAtividade == '' ? 'display: none' : ''?>">
                            <table class="infraTable mgnTop" summary="Atividade" id="tbAtividade">
                                <caption class="infraCaption caption"> <?= PaginaSEI::getInstance()->gerarCaptionTabela('Atividade', 0) ?> </caption>
                                <tr>
                                    <th class="infraTh" width="0" style="display: none;">Id</th>
                                    <th class="infraTh" align="center" width="80%">Atividade</th>
                                    <th class="infraTh" align="center" width="20%">Prioridade</th>
                                    <th class="infraTh" align="center" width="15%"> Ações </th>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
    </div> 
    <!-- Fim Bloco Atividade -->

    <!-- Bloco Tipo de Processo -->
    <div id="blocoTipoProcesso" class="rowFieldSet mb-3">
        <fieldset class="infraFieldset fieldset-comum form-control">
            <legend class="infraLegend">Tipo de Processo</legend>        

            <!-- Componente de Priorização por Tipo Processo -->
            <div id="divSelectPriorizar">
                <div class="row mb-3">
                    <div class="<?= $col_def_01 ?> mb-2">
                        <label id="lblTipoProcesso" for="selTipoProcesso" accesskey="" class="infraLabelObrigatorio">Priorizar por Tipo de Processo:</label>
                        <select utlCampoObrigatorio="o" id="selTipoProcesso" name="selTipoProcesso" class="infraSelect form-control" onchange="sinPriorizar()" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                            <?= $strItensSelSinRetonoTipoProcesso ?>
                        </select>
                    </div>

                    <!-- Componente de Prioridade geral por Atividade -->
                    <div class="<?= $col_def_02 ?>" id="divPrioridadeTipoProcesso">
                    <label id="lblPrioridadeTipoProcesso" for="lblPrioridadeTipoProcesso" accesskey="" class="infraLabelObrigatorio">Prioridade Geral:</label>
                        <select utlCampoObrigatorio="o" id="selPrioridadeTipoProcesso" name="selPrioridadeTipoProcesso" class="infraSelect prioridadeGeral form-control" 
                                tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                            <?= $prioridadeTipoProcesso ?>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Componente de Tipo Processo -->
            <div id="divTipoProcesso"> 
                <div class="row mb-1">
                    <div class="col-xs-4 col-sm-7 col-md-7 col-lg-7">
                        <label id="lblTipoProcesso" for="selItensTipoProcesso" accesskey="" class="infraLabelObrigatorio">Tipos de Processos:</label>
                        <input type="text" id="txtTipoProcesso" name="txtTipoProcesso" class="infraText form-control" 
                                tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-12 col-md-12 col-lg-10">
                        <div class="input-group">
                            <select id="selItensTipoProcesso" name="selItensTipoProcesso" multiple="multiple" class="infraSelect form-control">
                                <?= $strLinkTipoProcesso ?>
                            </select>
                            <div id="divOpcoesTipoProcesso" class="ml-1">
                                <img id="imgLupaTipoProcesso" onclick="objLupaTipoProcesso.selecionar(700,500);"
                                    src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/pesquisar.svg'?>" alt="Selecionar Tipo Processo Selecionado" title="Selecionar Tipo Processo" class="infraImg"/>
                                <br>
                                <img id="imgExcluirTipoProcesso" onclick="objLupaTipoProcesso.remover();"
                                    src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/remover.svg'?>" alt="Remover Tipo Processo Selecionada"
                                    title="Remover Unidade Selecionada" class="infraImg"/>
                                <br>
                                <span id="divBtnAdicionarTipoProcesso">
                                    <button type="button" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"
                                        onclick="adicionarTabelaTipoProcesso();" name="btnAdicionarTipoProcesso" id="btnAdicionarTipoProcesso" class="infraButton ml-3">Adicionar
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12"> 
                        <div id="divTabelaTipoProcesso" style="<?= $strGridTipoProcesso == '' ? 'display: none' : ''?>">
                            <table class="infraTable mgnTop" summary="Tipo de Processo" id="tbTipoProcesso">
                                <caption class="infraCaption caption"> <?= PaginaSEI::getInstance()->gerarCaptionTabela('Tipo de Processo', 0) ?> </caption>
                                <tr>
                                    <th class="infraTh" width="0" style="display: none;">Id</th>
                                    <th class="infraTh" align="center" width="80%">Tipo de Processo</th>
                                    <th class="infraTh" align="center" width="20%">Prioridade</th>
                                    <th class="infraTh" align="center" width="15%"> Ações </th>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>
    <!-- Fim Bloco Tipo Processo -->

    <!-- Bloco Dias Úteis -->
    <div id="blocoTipoProcesso" class="rowFieldSet mb-3">
        <fieldset class="infraFieldset fieldset-comum form-control">
            <legend class="infraLegend">Dias Úteis na Situação</legend>
            
            <div class="row">
                <div class="col-sm-6 col-md-6 col-lg-4 mb-2" id="divSelectPriorizar">
                    <label id="lblDiasUteis" for="selDiasUteis" accesskey="" class="infraLabelObrigatorio">Priorizar por Dias Úteis:</label>
                    <select utlCampoObrigatorio="o" id="selDiasUteis" name="selDiasUteis" class="infraSelect form-control" 
                            onchange="sinPriorizar()" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                        <?= $strItensSelSinRetonoDiasUteis ?>
                    </select>
                </div>
            
                <!-- Componente quantitativo de Dias Úteis -->
                <div class="col-sm-6 col-md-6 col-lg-4 mb-2 divPrioridadeDiasUteis" style="display: none;">
                    <label id="lblQtdDiasUteis" for="lblQtdDiasUteis" accesskey="" class="infraLabelObrigatorio">Quantidade Dias Úteis:</label>
                    <input type="text" maxlength="3" utlSomenteNumeroPaste="true" value="<?=$qtdDiasUteis?>" class="infraText form-control"
                            onkeypress="return infraMascaraNumero(this,event, 3);" name="qtdDiasUteis" id="qtdDiasUteis">
                </div>

                <!-- Componente de Prioridade geral por Dias Úteis -->
                <div class="col-sm-6 col-md-6 col-lg-4 divPrioridadeDiasUteis" style="display: none;">
                    <label id="lblPrioridadeDiasUteis" for="lblPrioridadeDiasUteis" accesskey="" class="infraLabelObrigatorio">Prioridade Geral:</label>
                    <select utlCampoObrigatorio="o" id="selPrioridadeDiasUteis" name="selPrioridadeDiasUteis" class="infraSelect prioridadeGeral form-control" 
                            tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                    </select>
                </div>
            </div> 
        </fieldset>
    </div>
    <!-- Fim Bloco Tipo Processo -->

    <input type="hidden" id="hdnFilaLupa" name="hdnFilaLupa" value="<?=$_POST['hdnFilaLupa']?>" />
    <input type="hidden" id="hdnIdFila" name="hdnIdFila" value="<?php echo $idFila;?>"/>
    <input type="hidden" id="hdnFila" name="hdnFila" value='<?php echo $strGridFila?>' />
    <input type="hidden" id="hdnIdFilaLupa" name="hdnIdFilaLupa" value=""/>

    <input type="hidden" id="hdnStatusLupa" name="hdnStatusLupa" value="<?=$_POST['hdnStatusLupa']?>" />
    <input type="hidden" id="hdnIdStatus" name="hdnIdStatus" value="<?php echo $idStatus;?>"/>
    <input type="hidden" id="hdnStatus" name="hdnStatus" value="<?=$strGridStatus?>" />
    <input type="hidden" id="hdnIdStatusLupa" name="hdnIdStatusLupa" value=""/>

    <input type="hidden" id="hdnAtividadeLupa" name="hdnAtividadeLupa" value="<?=$_POST['hdnAtividadeLupa']?>" />
    <input type="hidden" id="hdnIdAtividade" name="hdnIdAtividade" value="<?php echo $idAtividade;?>"/>
    <input type="hidden" id="hdnAtividade" name="hdnAtividade" value="<?=$strGridAtividade?>" />
    <input type="hidden" id="hdnIdAtividadeLupa" name="hdnIdAtividadeLupa" value=""/>

    <input type="hidden" id="hdnTipoProcessoLupa" name="hdnTipoProcessoLupa" value="<?=$_POST['hdnTipoProcessoLupa']?>" />
    <input type="hidden" id="hdnIdTipoProcesso" name="hdnIdTipoProcesso" value="<?php echo $idTipoProcesso;?>"/>
    <input type="hidden" id="hdnTipoProcesso" name="hdnTipoProcesso" value="<?=$strGridTipoProcesso?>" />
    <input type="hidden" id="hdnIdTipoProcessoLupa" name="hdnIdTipoProcessoLupa" value=""/>

    <input type="hidden" id="hdnIdTipoControleUtl" name="hdnIdTipoControleUtl" value="<?= $idTipoControleUtl;?>"/>

    <input type="hidden" id="hdnPrioridadeDistribuicao" name="hdnPrioridadeDistribuicao" value="<?=$prioridadeDistribuicao?>"/>
    <input type="hidden" id="hdnPrioridadeFila" name="hdnPrioridadeFila" value="<?=$prioridadeFila?>"/>
    <input type="hidden" id="hdnPrioridadeStatus" name="hdnPrioridadeStatus" value="<?=$prioridadeStatus?>"/>
    <input type="hidden" id="hdnPrioridadeAtividade" name="hdnPrioridadeAtividade" value="<?=$prioridadeAtividade?>"/>
    <input type="hidden" id="hdnPrioridadeTipoProcesso" name="hdnPrioridadeTipoProcesso" value="<?=$prioridadeTipoProcesso?>"/>
    <input type="hidden" id="hdnPrioridadeDiasUteis" name="hdnPrioridadeDiasUteis" value="<?=$prioridadeDiasUteis?>"/>
    <input type="hidden" id="hdnQtdDiasUteis" name="hdnQtdDiasUteis" value="<?=$qtdDiasUteis?>"/>

    <?php
        PaginaSEI::getInstance()->fecharAreaDados();
        PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
</form>

<?php
    require_once 'md_utl_geral_js.php';
    require_once 'md_utl_adm_prm_ds_cadastro_js.php';
    PaginaSEI::getInstance()->fecharBody();
    PaginaSEI::getInstance()->fecharHtml();
?>
