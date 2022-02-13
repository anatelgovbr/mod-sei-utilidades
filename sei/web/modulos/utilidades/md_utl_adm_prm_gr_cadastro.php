<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 10/07/2018 - criado por jhon.cast
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

    SessaoSEI::getInstance()->validarLink();

    require_once 'md_utl_adm_prm_gr_cadastro_acoes.php';

} catch (Exception $e) {
    PaginaSEI::getInstance()->processarExcecao($e);
}

$strLinkUsuarioSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=usuario_selecionar&tipo_selecao=2&id_object=objLupaUsuario');
$strLinkAjaxUsuario = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_utl_usuario_auto_completar');
$strLinkAjaxVincUsuFila = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_utl_adm_prm_vinculo_usuario_fila');
$strUrlBuscarNomesUsuario = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_utl_adm_prm_buscar_nome_usuario');

//Tipo Processo
$strLinkTipoProcessoSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=tipo_procedimento_selecionar&tipo_selecao=2&id_object=objLupaTpProcesso');
$strLinkAjaxTipoProcesso = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_utl_adm_tipo_processo_auto_completar');
$strLinkAjaxVincDesProc = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_utl_adm_prm_vinculo_tp_processo_desempenho');

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo);
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();

require_once 'md_utl_adm_prm_gr_cadastro_css.php';
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();

require_once 'md_utl_geral_js.php';
require_once 'md_utl_adm_prm_gr_cadastro_js.php';
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
    <form id="frmMdUtlAdmPrmGrCadastro" method="post" action="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao']) ?>">
        <?
        PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        //PaginaSEI::getInstance()->montarAreaValidacao();
        PaginaSEI::getInstance()->abrirAreaDados('overflow:unset');
        ?>
        <div class="dv_container">
            <div>
                <label id="lblCargaPadrao" for="txtCargaPadrao" accesskey="" class="infraLabelObrigatorio">
                    Carga Padrão Diária (em minutos):
                    <a  id="btnCargaPadrao" <?= PaginaSEI::montarTitleTooltip('Informar a carga padrão diária (em minutos).') ?>
                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                        <img id="imgAjudaCargaPadrao" border="0" src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" class="infraImg cls-img"/>
                    </a>
                </label>
                <br>
                <input type="text" id="txtCargaPadrao" name="txtCargaPadrao" onkeypress="return infraMascaraNumero(this, event,6)"
                       class="infraText cls-input" value="<?= PaginaSEI::tratarHTML($cargaPadrao); ?>"
                       tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
            </div>

            <div>
                <label id="lblStaFrequencia" for="selStaFrequencia" accesskey="" class="infraLabelObrigatorio">
                    Frequência de distribuição:
                    <a style="" id="btnlStaFrequencia" <?= PaginaSEI::montarTitleTooltip('Informar a frequência da distribuição das tarefas.') ?>
                       tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                        <img id="imgAjudalStaFrequencia" border="0" src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" class="infraImg cls-img"/>
                    </a>
                </label>
                <br>
                <select id="selStaFrequencia" name="selStaFrequencia" class="infraSelect cls-select" onchange="montarPeriodo()"
                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                    <?= $strItensSelStaFrequencia ?>
                </select>
            </div>

            <div>
                <label id="lblInicioPeriodo" for="selInicioPeriodo" accesskey="" class="infraLabelObrigatorio">
                    Início do Período:
                    <a style="" id="btnInicioPeriodo" <?= PaginaSEI::montarTitleTooltip('Informar a frequência da distribuição das tarefas.') ?>
                       tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                        <img id="imgAjudaInicioPeriodo" border="0" src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" class="infraImg cls-img"/>
                    </a>
                </label>
                <br>
                <select id="selInicioPeriodo" name="selInicioPeriodo" class="infraSelect cls-select" onchange="montarFimPeriodo()"
                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                    <?= $strItensSelInicioPeriodo ?>
                </select>
            </div>


            <div>
                <label id="lblFimPeriodo" for="selFimPeriodo" accesskey="" class="infraLabelOpcional">
                    Fim do Período:
                    <a style="" id="btnFimPeriodo" <?= PaginaSEI::montarTitleTooltip('Informar a frequência da distribuição das tarefas.') ?>
                       tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                        <img id="imgAjudaFimPeriodo" border="0" src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" class="infraImg cls-img"/>
                    </a>
                </label>
                <br>
                <select id="selFimPeriodo" name="selFimPeriodo" class="infraSelect cls-select" disabled
                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                    <?= $strItensSelFimPeriodo ?>
                </select>
            </div>

            <div>
                <label id="lblPercentualTeletrabalho" for="txtPercentualTeletrabalho" accesskey="" class="infraLabelOpcional">
                    Percentual de Desempenho a Maior para Teletrabalho:
                    <a style="" id="btnPercentualTeletrabalho" <?= PaginaSEI::montarTitleTooltip('Informar o percentual de desempenho. Esse valor será acrescido para a distribuição das tarefas de servidor em teletrabalho.') ?>
                       tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                        <img id="imgAjudaPercentualTeletrabalho" border="0" style="width: 16px;height: 16px;"
                             src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" class="infraImg cls-img"/>
                    </a>
                </label>
                <br>
                <input type="text" id="txtPercentualTeletrabalho" name="txtPercentualTeletrabalho"
                       onkeypress="return infraMascaraNumero(this, event,3)" class="infraText cls-input"
                       onkeyup="return validarPercentual(this,'Percentual de Desempenho')"
                       value="<?= PaginaSEI::tratarHTML($percentualTeletrabalho); ?>"
                       tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
            </div>

            <div>
                <label id="lblFilaPadrao" for="selFilaPadrao" accesskey="" class="infraLabelOpcional">
                    Fila padrão:
                    <a style="" id="btnFilaPadrao" <?= PaginaSEI::montarTitleTooltip('Informar a fila padrão em que os processos serão incluídos assim que chegarem na área.') ?>
                       tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                        <img id="imgAjudaFilaPadrao" border="0" src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" class="infraImg cls-img"/>
                    </a>
                </label>
                <br>
                <select id="selFilaPadrao" name="selFilaPadrao" class="infraSelect cls-select"
                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                    <?= $strFilaPadrao ?>
                </select>
            </div>

            <div>
                <label id="lblRetorno" for="selRetorno" accesskey="" class="infraLabelObrigatorio">
                    Retorno para Última Fila:
                    <a style="" id="btnRetorno" <?= PaginaSEI::montarTitleTooltip('Quando um processo retorna a uma área, o processo vai para a última fila que o tratou nesta área.') ?>
                       tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                        <img id="imgAjudaRetorno" border="0" src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" class="infraImg cls-img"/>
                    </a>
                </label>
                <br>
                <select id="selRetorno" name="selRetorno" class="infraSelect cls-select" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                    <?= $strItensSelSinRetono ?>
                </select>
            </div>

            <div id="divTpProcesso" class="dv_container_hr">
                <div>
                    <label id="lblTpProcesso" for="selTpProcesso" accesskey="" class="infraLabelObrigatorio">
                        Tipos de Processos:
                        <img id="imgAjuda" src="/infra_css/imagens/ajuda.gif" name="ajuda" onmouseover="return infraTooltipMostrar('Selecionar um ou múltiplos tipos de processos que serão tratados no tipo de controle. Se o tipo de processo estiver desabilitado, significa que ele esta em uso em outro tipo de controle com mesmo conjunto de unidades.');" onmouseout="return infraTooltipOcultar();" alt="Ajuda" class="infraImg cls-img">
                        <a id="btnTpProcesso"></a>
                    </label>
                    <br>
                    <input type="text" id="txtTpProcesso" name="txtTpProcesso" class="infraText cls-input" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" size='36'/>
                    <span class="space-row"></span>
                    <select id="selTpProcesso" name="selTpProcesso" size="6" multiple="multiple" class="infraSelect cls-select"
                            tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                        <?= $strItensSelTpProcesso ?>
                    </select>
                </div>
                <div id="divOpcoesTpProcesso" class="lupa">
                    <img id="imgLupaTpProcesso" onclick="objLupaTpProcesso.selecionar(700,500);" src="/infra_css/imagens/lupa.gif" alt="Selecionar Unidade" title="Selecionar Unidade" class="infraImg cls-img" />
                    <br>
                    <img id="imgExcluirTpProcesso" onclick="objLupaTpProcesso.remover();" src="/infra_css/imagens/remover.gif" alt="Remover Unidade Selecionada" title="Remover Unidade Selecionada" class="infraImg cls-img" />
                    <!--<img id="imgExcluirTpProcesso" onclick="verificarVinculoTpProcesso(this);" src="/infra_css/imagens/remover.gif" alt="Remover Unidade Selecionada" title="Remover Unidade Selecionada" class="infraImg" />-->
                </div>
                <input type="hidden" id="hdnIdTpProcesso" name="hdnIdTpProcesso" value="" />
            </div>

            <div id="blocoRespTacita">
                <fieldset class="infraFieldset cls-fieldset-1">
                    <legend class="infraLegend">Resposta Tácita para Solicitação de Ajuste de Prazo</legend>
                    </br>
                    <div class="dv_container">
                        <!-- Resposta tácita para dilação de prazo-->
                        <div>
                            <label id="lblDilacao" for="selDilacao" accesskey="" class="infraLabelObrigatorio">
                                Resposta Tácita para Dilação de Prazo:
                                <a id="hintDilacao" <?= PaginaSEI::montarTitleTooltip('Informar o Tipo de Resposta Tácita para Solicitação de Dilação de Prazo.') ?>
                                   tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                    <img id="imgDilacao" border="0" src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" class="infraImg cls-img"/>
                                </a>
                            </label>
                            <br>
                            <select id="selDilacao" name="selDilacao" class="infraSelect cls-select" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                <?=$strItensSelRespDilacao?>
                            </select>
                        </div>
                        <!-- Resposta tácita para suspensão de prazo-->
                        <div>
                            <label id="lblSuspensao" for="selSuspensao" accesskey="" class="infraLabelObrigatorio">
                                Resposta Tácita para Suspensão de Prazo:
                                <a id="hintSuspensao" <?= PaginaSEI::montarTitleTooltip('Informar o Tipo de Resposta Tácita para Solicitação de Suspensão de Prazo.') ?>
                                   tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                    <img id="imgSuspensao" border="0" src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" class="infraImg cls-img"/>
                                </a>
                            </label>
                            <br>
                            <select id="selSuspensao" name="selSuspensao" class="infraSelect cls-select" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                <?=$strItensSelRespSuspensao?>
                            </select>
                        </div>
                        <!-- Prazo máximo de suspensão de prazo-->
                        <div>
                            <label id="lblPrzSuspensao" for="przSuspensao" accesskey="" class="infraLabelObrigatorio">
                                Prazo máximo de Suspensão:
                                <a id="hintPrzSuspensao" <?= PaginaSEI::montarTitleTooltip('Informar o Prazo Máximo em dias úteis para Suspensão de Prazo.') ?>
                                   tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                    <img id="imgPrzSuspensao" border="0" src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" class="infraImg cls-img"/>
                                </a>
                            </label>
                            <br>
                            <input type="text" id="przSuspensao" name="przSuspensao" utlsomentenumeropaste="true"
                                   maxlength="3" onkeypress="return infraMascaraNumero(this, event, 3)" onchange="validarValorDosPrazos(this)"
                                   class="infraText cls-input" value="<?= PaginaSEI::tratarHTML($numPrzSuspensao); ?>" />
                        </div>
                        <!-- Resposta tácita para interrupção de prazo-->
                        <div>
                            <label id="lblInterrupcao" for="selInterrupcao" accesskey="" class="infraLabelObrigatorio">
                                Resposta Tácita para Interrupção de Prazo:
                                <a id="hintInterrupcao" <?= PaginaSEI::montarTitleTooltip('Informar o Tipo de Resposta Tácita para Solicitação de Interrupção de Prazo.') ?>
                                   tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                    <img id="imgInterrupcao" border="0" src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" class="infraImg cls-img"/>
                                </a>
                            </label>
                            <br>
                            <select id="selInterrupcao" name="selInterrupcao" class="infraSelect cls-select" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                <?=$strItensSelRespInterrupcao?>
                            </select>
                        </div>

                        <!-- Prazo máximo de interrupção de prazo-->
                        <div>
                            <label id="lblPrzInterrupcao" for="przInterrupcao" accesskey="" class="infraLabelObrigatorio">
                                Prazo máximo de Interrupção:
                                <a id="hintPrzInterrupcao" <?= PaginaSEI::montarTitleTooltip('Informar o Prazo Máximo em dias úteis para Interrupção de Prazo.') ?>
                                   tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                    <img id="imgPrzInterrupcao" border="0" src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" class="infraImg cls-img"/>
                                </a>
                            </label>
                            <br>
                            <input type="text" id="przInterrupcao" name="przInterrupcao" utlsomentenumeropaste="true"
                                   maxlength="3" onkeypress="return infraMascaraNumero(this, event, 3)" onchange="validarValorDosPrazos(this)"
                                   class="infraText cls-input" value="<?= PaginaSEI::tratarHTML($numPrzInterrupcao); ?>" />
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
        <?
        PaginaSEI::getInstance()->fecharAreaDados();
        PaginaSEI::getInstance()->abrirAreaDados('overflow:unset');
        ?>
        <div id="blocoUsuario" class="dv_container">
            <div>
                <fieldset class="infraFieldset cls-fieldset">
                    <legend class="infraLegend" >Controle de participantes</legend>
                    </br>
                    <!--  Usuario Participante -->
                    <div class="dv_container">
                        <div id="divUsuario" class="dv_container_hr">
                            <div>
                                <label id="lblUsuario" for="selUsuario" accesskey="" class="infraLabelObrigatorio">
                                    Usuários Participantes:
                                    <a  id="btnUsuario" <?= PaginaSEI::montarTitleTooltip('Selecionar os participantes que atuarão no tipo de controle cadastrado.') ?>
                                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                        <img id="imgAjudaUsuario" border="0" src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" class="infraImg cls-img"/>
                                    </a>
                                </label>
                                <br>
                                <input style="width:45%" type="text" id="txtUsuario" name="txtUsuario" class="infraText cls-input"  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
                                <span class="space-row"></span>
                                <select id="selUsuario" name="selUsuario" size="6" multiple="multiple" class="infraSelect cls-select">
                                    <?=$strItensSelUsuario?>
                                </select>
                            </div>
                            <div id="divOpcoesUsuario" class="lupa">
                                <img id="imgLupaUsuario" onclick="objLupaUsuario.selecionar(700,500);" src="/infra_css/imagens/lupa.gif" alt="Selecionar Usuario" title="Selecionar Unidade" class="infraImg cls-img" />
                                <br>
                                <img id="imgExcluirUsuario" onclick="objLupaUsuario.remover();" src="/infra_css/imagens/remover.gif" alt="Remover Usuario Selecionado" title="Remover Unidade Selecionada" class="infraImg cls-img" />
                            </div>
                            <input type="hidden" id="hdnIdUsuario" name="hdnIdUsuario" value="" />
                        </div>

                        <div class="dv_container_hr">
                            <div id='divTpPres' class="dv_subitem" style='width:50%;'>
                                <label id="lblTpPresenca" for="selTpPresenca" accesskey="" class="infraLabelObrigatorio">
                                    Tipo de Presença:
                                    <a  id="btnTpPresenca" <?= PaginaSEI::montarTitleTooltip('Informar o tipo de presença do servidor.') ?>
                                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                        <img id="imgAjudaTpPresenca" border="0" src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" class="infraImg cls-img"/>
                                    </a>
                                </label>
                                <br>
                                <select id="selTpPresenca" name="selTpPresenca" class="infraSelect cls-select cls-select-2" onchange="validarTpPresenca(this.value);"
                                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                    <?= $strItensSelTpPresenca ?>
                                </select>
                            </div>

                            <div id="divFtDesemp" class="dv_subitem" style="display: none; width:50%" >
                                <label id="lblFtDesemp" for="txtFtDesemp" accesskey="" class="infraLabelObrigatorio">
                                    Fator de Desempenho Diferenciado:
                                    <a  id="btnFtDesemp" <?= PaginaSEI::montarTitleTooltip('Informar o percentual esperado de desempenho a maior pelo servidor quando o tipo de presença for igual a diferenciado.') ?>
                                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                        <img id="imgAjudaFtDesemp" border="0" src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" class="infraImg cls-img"/>
                                    </a>
                                </label>
                                <br>
                                <input type="text" id="txtFtDesemp" name="txtFtDesemp" onkeypress="return infraMascaraNumero(this, event)"
                                       class="infraText cls-input cls-input-2" value="<?= PaginaSEI::tratarHTML($objMdUtlAdmPrmGrDTO->getNumCargaPadrao()); ?>"
                                       onkeyup="return validarPercentual(this,'Fator de Desempenho Diferenciado')"
                                       tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                            </div>
                        </div>

                        <div class="dv_container_hr">
                            <div class="dv_subitem" style="width: 50%;">
                                <label id="lblTpJornada" for="selTpJornada" accesskey="" class="infraLabelObrigatorio">
                                    Tipo de Jornada:
                                    <a  id="btnTpJornada" <?= PaginaSEI::montarTitleTooltip('Informar a jornada do servidor. Se reduzido deve-se informar o fator de redução de desempenho.') ?>
                                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                        <img id="imgAjudaTpJornada" border="0" src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" class="infraImg cls-img"/>
                                    </a>
                                </label>
                                <br>
                                <select id="selTpJornada" name="selTpJornada" class="infraSelect cls-select cls-select-2" onchange="validarTpJornada(this.value);"
                                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                    <?= $strItensSelTpJornada ?>
                                </select>
                            </div>

                            <div id="divRedJornada" class="dv_subitem" style="display: none; width: 50%;">
                                <label id="lblFtReduc" for="txtFtReduc" accesskey="" class="infraLabelObrigatorio">Fator de Presença da Jornada:
                                    <a  id="btnFtReduc" <?= PaginaSEI::montarTitleTooltip('Informar o percentual de presença de jornada para o servidor quando o tipo de jornada for Reduzido.') ?>
                                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                        <img id="imgAjudaFtReduc" border="0" src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" class="infraImg cls-img"/>
                                    </a>
                                </label>
                                <br>
                                <input type="text" id="txtFtReduc" name="txtFtReduc" onkeypress="return infraMascaraNumero(this, event)"
                                       class="infraText cls-input cls-input-2" value="<?= PaginaSEI::tratarHTML($objMdUtlAdmPrmGrDTO->getNumCargaPadrao()); ?>"
                                       onkeyup="return validarPercentual(this,'Fator de Redução da Jornada')"
                                       tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                            </div>
                        </div>

                        <span class='cls-btn'>
                            <button type="button" class="infraButton" id="btnAdicionar" accesskey="a" onclick="buscarNomeUsuario();"><span class="infraTeclaAtalho">A</span>dicionar</button>
                        </span>

                        <div>
                            <table width="99%" class="infraTable" summary="UsuarioParticipante" id="tbUsuario" style="<?php echo $strTbUsuarioPart == '' ? 'display: none' : ''?>">
                                <caption class="infraCaption">&nbsp;</caption>
                                <tr >
                                    <th style="display: none">Id</th>
                                    <th class="infraTh"  align="center" >Usuário Participante</th> <!--1-->
                                    <th class="infraTh"  align="center" >Tipo de Presença</th> <!--2-->
                                    <th style="display: none"></th>
                                    <th class="infraTh" align="center" width="15%">Fator de Desempenho Diferenciado</th> <!--0-->
                                    <th class="infraTh"  align="center" >Tipo de Jornada</th> <!--3-->
                                    <th style="display: none"></th>
                                    <th class="infraTh" align="center" width="15%"> Fator de Presença da Jornada Reduzida </th><!--4-->
                                    <th style="display: none"></th>
                                    <th style="display: none">Nome Usuario hidden</th>
                                    <th class="infraTh" align="center" width="0"  >Ações</th><!--5-->
                                </tr>
                            </table>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
        <input type="hidden" id="hdnTpProcesso" name="hdnTpProcesso" value="<?=$strLupaTpProcesso?>" />
        <input type="hidden" id="hdnUsuario" name="hdnUsuario" value="<?=$_POST['hdnUsuario']?>" />
        <input type="hidden" id="hdnTbUsuario" name="hdnTbUsuario" value="<?=$strTbUsuarioPart?>" />
        <input type="hidden" id="hdnIdTipoControleUtl" name="hdnIdTipoControleUtl" value="<?=$idTipoControleUtl?>" />
        <input type="hidden" id="hdnTbUsuarioRemove" name="hdnTbUsuarioRemove" value=""/>
        <input type="hidden" id="hdnTbUsuarioNovo" name="hdnTbUsuarioNovo" value=""/>
        <?
        PaginaSEI::getInstance()->fecharAreaDados();
        //PaginaSEI::getInstance()->montarAreaDebug();
        PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
        ?>
    </form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();