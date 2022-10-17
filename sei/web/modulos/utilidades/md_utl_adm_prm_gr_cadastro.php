<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4� REGI�O
 *
 * 10/07/2018 - criado por jhon.cast
 *
 * Vers�o do Gerador de C�digo: 1.41.0
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
$strLinkAjaxValidaNumPlanoTrab = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_utl_adm_prm_valida_plano_trab');

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo);
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
PaginaSEI::getInstance()->fecharStyle();
#require_once 'md_utl_adm_prm_gr_cadastro_css.php';
require_once 'md_utl_geral_css.php';
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');

$layout01 = '<div class="row mb-3"> <div class="col-sm-9 col-md-6 col-lg-6">';

$layout02 = 'col-sm-9 col-md-8 col-lg-6 mb-3';

$fim_layout = '</div></div>';

?>
    <form id="frmMdUtlAdmPrmGrCadastro" method="post" action="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao']) ?>">
        <?php
            PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
            //PaginaSEI::getInstance()->montarAreaValidacao();
            PaginaSEI::getInstance()->abrirAreaDados();
        ?>

        <div class="row mb-3">
            <div class="col-sm-12 col-md-5 col-lg-5 col-xl-5">
                <label id="lblDtCorte" name="lblDtCorte" for="txtDtCorte" class="infraLabelObrigatorio">
                    Data de Corte:
                    <img align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg"
                        name="ajuda" <?= PaginaSEI::montarTitleTooltip('Texto que define a data de corte.','Ajuda') ?> />
                </label>
                <div class="input-group">
                    <input type="text" id="txtDtCorte" name="txtDtCorte" onchange="return validarFormatoData(this)" 
                           onkeypress="return infraMascara(this, event,'##/##/####')" class="infraText" 
                           value="<?= PaginaSEI::tratarHTML($dataCorte); ?>" 
                           tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                    <img src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/calendario.svg' ?>" id="imgCalDthCorte" 
                        title="Selecionar Data/Hora Inicial" alt="Selecionar Data de Corte" class="infraImg" 
                        onclick="infraCalendario('txtDtCorte',this,false,'<?= date('d/m/Y') ?>');">
                </div>
            </div>
        </div>

        <?= $layout01 ?>
            <label id="lblCargaPadrao" for="txtCargaPadrao" accesskey="" class="infraLabelObrigatorio">
                Carga Padr�o Di�ria (em minutos):
                <img align="top" class="infraImg" name="ajuda"
                        src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" 
                        <?= PaginaSEI::montarTitleTooltip('Informar a carga padr�o di�ria (em minutos).','Ajuda') ?>/>
            </label>
            <input type="text" id="txtCargaPadrao" name="txtCargaPadrao" onkeypress="return infraMascaraNumero(this, event,6)"
                    class="infraText form-control" value="<?= PaginaSEI::tratarHTML($cargaPadrao); ?>"
                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
        <?= $fim_layout ?>

        <?= $layout01 ?>
            <label id="lblStaFrequencia" for="selStaFrequencia" accesskey="" class="infraLabelObrigatorio">
                Frequ�ncia de distribui��o:
                <img align="top" class="infraImg" name="ajuda"
                        src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" 
                        <?= PaginaSEI::montarTitleTooltip('Informar a frequ�ncia da distribui��o das tarefas.','Ajuda') ?>/>
            </label>
            <select id="selStaFrequencia" name="selStaFrequencia" class="infraSelect form-control" onchange="montarPeriodo()"
                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                <?= $strItensSelStaFrequencia ?>
            </select>
        <?= $fim_layout ?>

        <?= $layout01 ?>
            <label id="lblInicioPeriodo" for="selInicioPeriodo" accesskey="" class="infraLabelObrigatorio">
                In�cio do Per�odo:
                <img align="top" class="infraImg" name="ajuda"
                        src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" 
                        <?= PaginaSEI::montarTitleTooltip('Informar a frequ�ncia da distribui��o das tarefas.','Ajuda') ?>/>
            </label>
            <select id="selInicioPeriodo" name="selInicioPeriodo" class="infraSelect form-control" onchange="montarFimPeriodo()"
                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                <?= $strItensSelInicioPeriodo ?>
            </select>
        <?= $fim_layout ?>

        <?= $layout01 ?>
            <label id="lblFimPeriodo" for="selFimPeriodo" accesskey="" class="infraLabelOpcional">
                Fim do Per�odo:
                <img align="top" class="infraImg" name="ajuda"
                        src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" 
                        <?= PaginaSEI::montarTitleTooltip('Informar a frequ�ncia da distribui��o das tarefas.','Ajuda') ?>/>
            </label>
            <select id="selFimPeriodo" name="selFimPeriodo" class="infraSelect form-control" disabled
                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                <?= $strItensSelFimPeriodo ?>
            </select>
        <?= $fim_layout ?>

        <?= $layout01 ?>
            <label id="lblPercentualTeletrabalho" for="txtPercentualTeletrabalho" accesskey="" class="infraLabelOpcional">
                Percentual de Desempenho a Maior para Teletrabalho:
                <img align="top" class="infraImg" name="ajuda"
                        src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" 
                        <?= PaginaSEI::montarTitleTooltip('Informar o percentual de desempenho. Esse valor ser� acrescido para a distribui��o das tarefas de servidor em teletrabalho.','Ajuda') ?>/>
            </label>
            <input type="text" id="txtPercentualTeletrabalho" name="txtPercentualTeletrabalho"
                    onkeypress="return infraMascaraNumero(this, event,3)" class="infraText form-control"
                    onkeyup="return validarPercentual(this,'Percentual de Desempenho')"
                    value="<?= PaginaSEI::tratarHTML($percentualTeletrabalho); ?>"
                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
        <?= $fim_layout ?>

        <?= $layout01 ?>
            <label id="lblRetorno" for="selRetorno" accesskey="" class="infraLabelObrigatorio">
                Retorno para �ltima Fila:
                <img align="top" class="infraImg" name="ajuda"
                         src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" 
                          <?= PaginaSEI::montarTitleTooltip('Quando um processo retorna a uma �rea, o processo vai para a �ltima fila que o tratou nesta �rea.','Ajuda') ?>/>
            </label>
            <select id="selRetorno" name="selRetorno" class="infraSelect form-control" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                <?= $strItensSelSinRetono ?>
            </select>
        <?= $fim_layout ?>

        <div class="row">
            <div class="col-xs-2 col-sm-9 col-md-6 col-lg-6">
                <label id="lblTpProcesso" for="selTpProcesso" accesskey="" class="infraLabelObrigatorio"> Tipos de Processos:
                <img align="top"
                        src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg"
                        name="ajuda" <?= PaginaSEI::montarTitleTooltip('Selecionar um ou m�ltiplos tipos de processos que ser�o tratados no tipo de controle. Se o tipo de processo estiver desabilitado, significa que ele esta em uso em outro tipo de controle com mesmo conjunto de unidades.','Ajuda') ?>
                        class="infraImg"/>
                </label>                               
                <input type="text" id="txtTpProcesso" name="txtTpProcesso" class="infraText form-control" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-sm-10 col-md-10 col-lg-9">
                <div class="input-group">
                    <select id="selTpProcesso" name="selTpProcesso" size="6" multiple="multiple" class="infraSelect form-control"
                            tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                        <?= $strItensSelTpProcesso ?>
                    </select>

                    <div id="_divOpcoesUnidades" class="ml-1">
                        <img id="imgLupaTpProcesso" onclick="objLupaTpProcesso.selecionar(700,500);" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/pesquisar.svg'?>" alt="Selecionar Unidade" title="Selecionar Unidade" class="infraImg" />
                        <br>
                        <img id="imgExcluirTpProcesso" onclick="objLupaTpProcesso.remover();" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/remover.svg'?>" alt="Remover Unidade Selecionada" title="Remover Unidade Selecionada" class="infraImg" />
                    </div>
                    <input type="hidden" id="hdnIdTpProcesso" name="hdnIdTpProcesso" value="" />
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <fieldset class="infraFieldset mb-4 p-4">
                    <legend class="infraLegend">Resposta T�cita para Solicita��o de Ajuste de Prazo</legend>
                    <div class="row">
                    <div class="<?= $layout02 ?>">
                        <label id="lblDilacao" for="selDilacao" accesskey="" class="infraLabelObrigatorio"> Resposta T�cita para Dila��o de Prazo:
                            <img align="top" class="infraImg" name="ajuda"
                                src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" 
                                <?= PaginaSEI::montarTitleTooltip('Informar o Tipo de Resposta T�cita para Solicita��o de Dila��o de Prazo.','Ajuda') ?>/>
                        </label>
                        <select id="selDilacao" name="selDilacao" class="infraSelect form-control" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                            <?=$strItensSelRespDilacao?>
                        </select>
                    </div>

                    <div class="<?= $layout02 ?>">
                        <label id="lblSuspensao" for="selSuspensao" accesskey="" class="infraLabelObrigatorio"> Resposta T�cita para Suspens�o de Prazo:
                            <img align="top" class="infraImg" name="ajuda"
                                src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" 
                                <?= PaginaSEI::montarTitleTooltip('Informar o Tipo de Resposta T�cita para Solicita��o de Suspens�o de Prazo.','Ajuda') ?>/>
                        </label>
                        <select id="selSuspensao" name="selSuspensao" class="infraSelect form-control" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                            <?=$strItensSelRespSuspensao?>
                        </select>
                    </div>

                    <div class="<?= $layout02 ?>">
                        <label id="lblPrzSuspensao" for="przSuspensao" accesskey="" class="infraLabelObrigatorio"> Prazo m�ximo de Suspens�o:
                            <img align="top" class="infraImg" name="ajuda"
                                src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" 
                                <?= PaginaSEI::montarTitleTooltip('Informar o Prazo M�ximo em dias �teis para Suspens�o de Prazo.','Ajuda') ?>/>
                        
                        </label>                            
                        <input type="text" id="przSuspensao" name="przSuspensao" utlsomentenumeropaste="true"
                                maxlength="3" onkeypress="return infraMascaraNumero(this, event, 3)" onchange="validarValorDosPrazos(this)"
                                class="infraText form-control" value="<?= PaginaSEI::tratarHTML($numPrzSuspensao); ?>" />
                    </div>

                    <div class="<?= $layout02 ?>">
                        <label id="lblInterrupcao" for="selInterrupcao" accesskey="" class="infraLabelObrigatorio"> Resposta T�cita para Interrup��o de Prazo:
                            <img align="top" class="infraImg" name="ajuda"
                                src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" 
                                <?= PaginaSEI::montarTitleTooltip('Informar o Tipo de Resposta T�cita para Solicita��o de Interrup��o de Prazo.','Ajuda') ?>/>
                        </label>
                        <select id="selInterrupcao" name="selInterrupcao" class="infraSelect form-control" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                            <?=$strItensSelRespInterrupcao?>
                        </select>
                    </div>

                    <div class="<?= $layout02 ?>">
                        <label id="lblPrzInterrupcao" for="przInterrupcao" accesskey="" class="infraLabelObrigatorio"> Prazo m�ximo de Interrup��o:
                            <img align="top" class="infraImg" name="ajuda"
                                src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" 
                                <?= PaginaSEI::montarTitleTooltip('Informar o Prazo M�ximo em dias �teis para Interrup��o de Prazo.','Ajuda') ?>/>
                        </label>
                        <input type="text" id="przInterrupcao" name="przInterrupcao" utlsomentenumeropaste="true"
                                maxlength="3" onkeypress="return infraMascaraNumero(this, event, 3)" onchange="validarValorDosPrazos(this)"
                                class="infraText form-control" value="<?= PaginaSEI::tratarHTML($numPrzInterrupcao); ?>" />
                    </div>
                    </div>
                </fieldset>
            </div>
        </div>

        <div class="row">
            <div class="col-12" id="blocoUsuario">
                <fieldset class="infraFieldset mb-4 p-4">
                    <legend class="infraLegend">Controle de Participantes</legend>               
                    <div id="divUsuario">
                        <div class="row mb-3">
                            <div class="col-xs-2 col-sm-9 col-md-6 col-lg-6">
                                <label class="infraLabelOpcional">Plano de Trabalho ( N�mero SEI ):</label>
                                <img align="top" class="infraImg" name="ajuda"
                                    src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" 
                                    <?= PaginaSEI::montarTitleTooltip('Plano de Trabalho do membro Participante.','Ajuda') ?>/>    
                                <input type="text" class="infraText form-control" id="txtPlanoTrabalho" name="txtPlanoTrabalho" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-xs-2 col-sm-10 col-md-10 col-lg-6">
                                <label id="lblUsuario" for="selUsuario" accesskey="" class="infraLabelObrigatorio">
                                    Usu�rios Participantes:                                    
                                    <img align="top" class="infraImg" name="ajuda"
                                        src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" 
                                        <?= PaginaSEI::montarTitleTooltip('Selecionar os participantes que atuar�o no tipo de controle cadastrado.','Ajuda') ?>/>    
                                </label>                               
                                <div class="input-group">
                                    <input type="text" id="txtUsuario" name="txtUsuario" class="infraText form-control"  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

                                    <div id="divOpcoesUsuario" class="ml-1">
                                        <img id="imgLupaUsuario" onclick="objLupaUsuario.selecionar(700,500);" 
                                            src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/pesquisar.svg'?>" 
                                            alt="Selecionar Usuario" title="Selecionar Unidade" class="infraImg" />
                                        
                                        <img id="imgExcluirUsuario" onclick="objLupaUsuario.remover();" 
                                            src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/remover.svg'?>" 
                                            alt="Remover Usuario Selecionado" title="Remover Unidade Selecionada" class="infraImg" />  
                                    </div>
                                    <input type="hidden" id="hdnIdUsuario" name="hdnIdUsuario" value="" />
                                </div>
                            </div>
                        </div>
                    </div> 
                    
                    <div class="row mb-3">
                        <div class="col-xs-2 col-sm-10 col-md-10 col-lg-6 mb-2">
                            <label id="lblTpPresenca" for="selTpPresenca" accesskey="" class="infraLabelObrigatorio"> Tipo de Presen�a:
                                <img align="top" class="infraImg" name="ajuda"
                                    src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" 
                                    <?= PaginaSEI::montarTitleTooltip('Informar o tipo de presen�a do servidor.','Ajuda') ?>/>
                            </label>
                            <select id="selTpPresenca" name="selTpPresenca" class="infraSelect form-control" onchange="validarTpPresenca(this.value);"
                                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                <?= $strItensSelTpPresenca ?>
                            </select>
                        </div>
                        <div class="col-xs-2 col-sm-10 col-md-10 col-lg-6" id="divFtDesemp" style="display: none;">
                            <label id="lblFtDesemp" for="txtFtDesemp" accesskey="" class="infraLabelObrigatorio"> Fator de Desempenho Diferenciado:
                                <img align="top" class="infraImg" name="ajuda"
                                    src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" 
                                    <?= PaginaSEI::montarTitleTooltip('Informar o percentual esperado de desempenho a maior pelo servidor quando o tipo de presen�a for igual a diferenciado.','Ajuda') ?>/>
                            </label>                            
                            <input type="text" id="txtFtDesemp" name="txtFtDesemp" onkeypress="return infraMascaraNumero(this, event)"
                                    class="infraText form-control" value="<?= PaginaSEI::tratarHTML($objMdUtlAdmPrmGrDTO->getNumCargaPadrao()); ?>"
                                    onkeyup="return validarPercentual(this,'Fator de Desempenho Diferenciado')"
                                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-xs-2 col-sm-10 col-md-10 col-lg-6 mb-2">
                            <label id="lblTpJornada" for="selTpJornada" accesskey="" class="infraLabelObrigatorio"> Tipo de Jornada:
                                <img align="top" class="infraImg" name="ajuda"
                                    src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" 
                                    <?= PaginaSEI::montarTitleTooltip('Informar a jornada do servidor. Se reduzido deve-se informar o fator de redu��o de desempenho.','Ajuda') ?>/>
                            </label>                            
                            <select id="selTpJornada" name="selTpJornada" class="infraSelect form-control" onchange="validarTpJornada(this.value);"
                                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                <?= $strItensSelTpJornada ?>
                            </select>
                        </div>
                        <div class="col-xs-2 col-sm-10 col-md-10 col-lg-6" id="divRedJornada" style="display: none;">
                            <label id="lblFtReduc" for="txtFtReduc" accesskey="" class="infraLabelObrigatorio">Fator de Presen�a da Jornada Reduzida:
                                <img align="top" class="infraImg" name="ajuda"
                                    src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" 
                                    <?= PaginaSEI::montarTitleTooltip('Informar o percentual de presen�a de jornada para o servidor quando o tipo de jornada for Reduzido','Ajuda') ?>/>
                            </label>
                            <input type="text" id="txtFtReduc" name="txtFtReduc" onkeypress="return infraMascaraNumero(this, event)"
                                    class="infraText form-control" value="<?= PaginaSEI::tratarHTML($objMdUtlAdmPrmGrDTO->getNumCargaPadrao()); ?>"
                                    onkeyup="return validarPercentual(this,'Fator de Redu��o da Jornada')"
                                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-sm-3 offset-sm-7 col-md-3 offset-md-7 col-lg-2 offset-lg-10 col-xl-2 offset-xl-10">
                            <button type="button" class="infraButton float-right" id="btnAdicionar" accesskey="a" onclick="buscarNomeUsuario();">
                                <span class="infraTeclaAtalho">A</span>dicionar
                            </button>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <div class="table-responsive">
                                <table class="infraTable table" summary="UsuarioParticipante" id="tbUsuario" style="<?php echo $strTbUsuarioPart == '' ? 'display: none' : 'width: 100%'?>">
                                    <caption class="infraCaption">&nbsp;</caption>
                                    <tr>
                                        <th style="display: none">Id</th>
                                        <th class="infraTh" align="center">Usu�rio Participante</th>
                                        <th class="infraTh" align="center">Tipo de Presen�a</th>                     
                                        <th style="display: none"></th>
                                        <th class="infraTh" align="center">Plano de Trabalho</th>
                                        <th class="infraTh" align="center" width="15%">Fator de Desempenho Diferenciado</th>
                                        <th class="infraTh" align="center">Tipo de Jornada</th>
                                        <th style="display: none"></th>
                                        <th class="infraTh" align="center" width="15%"> Fator de Presen�a da Jornada Reduzida </th>
                                        <th style="display: none"></th>
                                        <th style="display: none">Nome Usuario hidden</th>
                                        <th class="infraTh" align="center" width="0">A��es</th>
                                    </tr>
                                </table> 
                            </div> 
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

        <?php
            PaginaSEI::getInstance()->fecharAreaDados();
            //PaginaSEI::getInstance()->montarAreaDebug();
            PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
        ?>
    </form>
<?php
require_once('md_utl_adm_prm_gr_cadastro_js.php');
require_once('md_utl_geral_js.php');

PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>