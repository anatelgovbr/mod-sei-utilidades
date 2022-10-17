<?
/**
 * ANATEL
 *
 * 19/01/2016 - criado por marcelo.bezerra - CAST
 *
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
  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  // ======================= INICIO ACOES PHP DA PAGINA
  require_once 'md_utl_adm_jornada_cadastro_acoes.php';
  // ======================= FIM ACOES PHP DA PAGINA

} catch(Exception $e){
    PaginaSEI::getInstance()->processarExcecao($e);
}

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(':: '.PaginaSEI::getInstance()->getStrNomeSistema().' - '.$strTitulo.' ::');
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();

// ======================= INICIO CSS
require_once('md_utl_adm_jornada_cadastro_css.php');
// ======================= FIM CSS

PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');

?>
  
  	<div class="row">
		<div class="col">

			<form id="frmTipoControleUtilidadesCadastro" method="post" onsubmit="return onSubmitForm();" action="<?=PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao']))?>">
				
				<?
					PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
					PaginaSEI::getInstance()->abrirAreaDados('auto');
				?>
				
				<div class="row">
					<div class="col-12">
						<label id="lblTpControleDesempenho" class="infraLabelObrigatorio">
							Tipo de Controle de Desempenhos:
							<img align="bottom" src="/infra_css/svg/ajuda.svg" class="infraImg d-inline-block mb-n1" name="ajuda" onmouseover="return infraTooltipMostrar('Tipo de Controle de Desempenho da Unidade Logada.', 'Ajuda');" onmouseout="return infraTooltipOcultar();">
						</label>
						<label id="lblTpControleLogado" class="d-block infraLabelOpcional"><?php echo $nomeTpControle; ?></label>
					</div>
				</div>

				<div class="row">
					<div class="col-xl-7 col-lg-9 col-md-10 col-sm-11 col-12">
						<div class="form-group">
							<label id="lblNome" for="txtNome" class="infraLabelObrigatorio">
								Nome:
								<img align="top" src="/infra_css/svg/ajuda.svg" class="infraImg d-inline-block mb-n1" name="ajuda" onmouseover="return infraTooltipMostrar('Indicar o Nome do Ajuste', 'Ajuda');" onmouseout="return infraTooltipOcultar();">
							</label>
							<?php $txtNome = $isAlterar ? (array_key_exists('txtNome', $_POST) ? $_POST['txtNome'] : $objJornadaDTO->getStrNome()) : $_POST['txtNome']; ?>
							<input type="text" id="txtNome" name="txtNome" class="form-control infraText" value="<?= PaginaSEI::tratarHTML($txtNome); ?>" onkeypress="return infraMascaraTexto(this,event,100);"
								maxlength="100" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" <?= $_GET['acao'] == 'md_utl_adm_jornada_consultar' ? 'readonly disabled' : '' ?>/>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-xl-7 col-lg-9 col-md-10 col-sm-11 col-12">
						<div class="form-group">
							<label id="lblDescricao" for="txaDescricao" class="infraLabelObrigatorio">
								Descrição:
								<img align="top" src="/infra_css/svg/ajuda.svg" class="infraImg d-inline-block mb-n1" name="ajuda" onmouseover="return infraTooltipMostrar('Indicar a Descrição do Ajuste', 'Ajuda');" onmouseout="return infraTooltipOcultar();">
							</label>
							<?php $txtDescricao = $isAlterar ? (array_key_exists('txaDescricao', $_POST) ? $_POST['txaDescricao'] : $objJornadaDTO->getStrDescricao()) : $_POST['txaDescricao']; ?>
							<textarea id="txaDescricao" name="txaDescricao" rows="4" class="form-control infraTextarea" onkeypress="return infraLimitarTexto(this,event,250);" maxlength="250"
								tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" <?= $_GET['acao'] == 'md_utl_adm_jornada_consultar' ? 'readonly disabled' : '' ?>><?= $txtDescricao;?></textarea>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-12 pr-0">
						<div class="form-group mb-1">
							<label class="infraLabelCheckbox infraLabelObrigatorio" for="txtPercentualAjuste" id="lblPercentualAjuste">
								Percentual de Ajuste:
								<img align="top" src="/infra_css/svg/ajuda.svg" class="infraImg d-inline-block mb-n1" name="ajuda" onmouseover="return infraTooltipMostrar('Indique o Percentual de Ajuste de Jornada', 'Ajuda');" onmouseout="return infraTooltipOcultar();">
							</label>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xl-2 col-lg-3 col-md-3 col-sm-3 col-3">
						<div class="form-group">
							<?php $txtPercentualAjuste = $isAlterar ? (array_key_exists('txtPercentualAjuste', $_POST) ? $_POST['txtPercentualAjuste'] : $objJornadaDTO->getNumPercentualAjuste()) : $_POST['txtPercentualAjuste']; ?>
							<input onchange="validarValorPercentual(this)" class="form-control infraText" type="text" maxlength="3" utlSomenteNumeroPaste="true" onkeypress="return infraMascaraNumero(this,event, 3);" name="txtPercentualAjuste" id="txtPercentualAjuste" value="<?= $txtPercentualAjuste;?>" <?= $_GET['acao'] == 'md_utl_adm_jornada_consultar' ? 'readonly disabled' : '' ?>>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xl-2 col-lg-3 col-md-3 col-sm-4 col-4">
						<div class="form-group">
							<label id="lblDtInicio" for="txtDtInicio" class="infraLabelObrigatorio">Início:</label>
							<?php
								$dtInicio = $isAlterar ? (array_key_exists('txtDtInicio', $_POST) ? $_POST['txtDtInicio'] :  $objJornadaDTO->getDthInicio()) : $_POST['txtDtInicio'];
								$dtFim    = $isAlterar ? (array_key_exists('txtDtFim', $_POST) ? $_POST['txtDtFim'] :  $objJornadaDTO->getDthFim()) : $_POST['txtDtFim'];
							?>
							<div class="input-group">
								<input type="text" name="txtDtInicio" id="txtDtInicio"
								onchange="validarDataJornada(this);"
								value="<?= $dtInicio ?>"
								onkeypress="return infraMascara(this, event, '##/##/####');" 
								class="form-control rounded infraText" <?= $_GET['acao'] == 'md_utl_adm_jornada_consultar' ? 'readonly disabled' : '' ?>/>

								<div class="input-group-append">
									<span class="input-group-text bg-white border-0 px-0 pl-1" id="inputGroupPrepend">
										<img style="margin-bottom: -3px;"
										src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/calendario.svg" id="imgDtInicio"
										title="Selecionar Data/Hora Inicial"
										alt="Selecionar Data/Hora Inicial" class="infraImg"
										onclick="infraCalendario('txtDtInicio',this,false,'<?= InfraData::getStrDataAtual() ?>');"/>
									</span>
								</div>
							</div>
						</div>
					</div>

					<div class="col-xl-2 col-lg-3 col-md-3 col-sm-4 col-4">
						<div class="form-group">
							<label id="lblDtFim" for="txtDtFim" class="infraLabelOpcional">Fim:</label>

							<div class="input-group">
								<input type="text" name="txtDtFim" id="txtDtFim"
								value="<?= $dtFim ?>"
								onchange="validarDataJornada(this);" onkeypress="return infraMascara(this, event, '##/##/####');"
								maxlength="16"  
								class="form-control rounded infraText" <?= $_GET['acao'] == 'md_utl_adm_jornada_consultar' ? 'readonly disabled' : '' ?>/>

								<div class="input-group-append">
									<span class="input-group-text bg-white border-0 px-0 pl-1" id="inputGroupPrepend">
									<img style="margin-bottom: -3px;"
									src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/calendario.svg" id="imgDtFim"
									title="Selecionar Data/Hora Final"
									alt="Selecionar Data/Hora Final"
									class="infraImg"
									onclick="infraCalendario('txtDtFim',this,false,'<?= InfraData::getStrDataAtual() ?>');"/>
									</span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<label name="lblTpAjuste" id="lblTpAjuste" for="selTpAjuste" class="d-block infraLabelObrigatorio"	>
							Tipo de Ajuste:
							<img align="top" src="/infra_css/svg/ajuda.svg" class="infraImg d-inline-block mb-n1" name="ajuda" onmouseover="return infraTooltipMostrar('Indicar se o ajuste é para todos os servidores ou se é para algum servidor específico.', 'Ajuda');" onmouseout="return infraTooltipOcultar();">
						</label>

						<div id="divRadiosTpAjuste">
							<div id="divOptGeral" class="infraDivRadio">
								<input <?php echo $idTpAjuste == MdUtlAdmJornadaRN::$TIPO_JORNADA_GERAL ? 'checked=checked' : '' ?> type="radio" name="rdoTipoAjuste" onchange="controlarHdnTipoAjuste();" id="rdoGeral" value="<?php echo MdUtlAdmJornadaRN::$TIPO_JORNADA_GERAL ?>" class="form-check-input infraRadio d-inline" <?= $_GET['acao'] == 'md_utl_adm_jornada_consultar' ? 'readonly disabled' : '' ?> />
								<label id="lblGeral" for="rdoGeral" class="form-check-label infraLabelRadio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">Geral</label>
							</div>
							<div id="divOptEspecifico" class="infraDivRadio">
								<input <?php echo $idTpAjuste == MdUtlAdmJornadaRN::$TIPO_JORNADA_ESPECIFICO ? 'checked=checked' : '' ?> type="radio" name="rdoTipoAjuste" onchange="controlarHdnTipoAjuste();" id="rdoEspecifico" value="<?php echo MdUtlAdmJornadaRN::$TIPO_JORNADA_ESPECIFICO ?>" class="form-check-input infraRadio d-inline" <?= $_GET['acao'] == 'md_utl_adm_jornada_consultar' ? 'readonly disabled' : '' ?> />
								<label id="lblEspecifico" for="rdoEspecifico" class="form-check-label infraLabelRadio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">Específico</label>
							</div>
						</div>
					</div>
				</div>

				<?php $hdnMembrosLupa = array_key_exists('hdnMembrosLupa', $_POST) ? $_POST['hdnMembrosLupa']  : $strGridUsuariosParticipantes ?>
				<input type="hidden" id="hdnMembrosLupa" name="hdnMembrosLupa" value="<?=$_POST['hdnMembrosLupa'] ?>" />
				<input type="hidden" id="hdnMembros" name="hdnMembros" value="<?=$strGridUsuariosParticipantes?>" />
				<input type="hidden" id="hdnIdMembrosLupa" name="hdnIdMembrosLupa" value=""/>
				<input type="hidden" id="hdnIdTipoControleUtl" name="hdnIdTipoControleUtl" value="<?php echo $idTipoControle; ?>"/>
				<input type="hidden" id="hdnIdJornada" name="hdnIdJornada" value="<?php echo $idJornada; ?>"/>
				<input type="hidden" id="hdnTpAjusteEspecifico" name="hdnTpAjusteEspecifico" value="<?php echo MdUtlAdmJornadaRN::$TIPO_JORNADA_ESPECIFICO ?>"/>
				<input type="hidden" id="hdnIdTpCtrlInicialAlteracao" name="hdnIdTpCtrlInicialAlteracao" value="<?php echo !is_null($objJornadaDTO) ? $objJornadaDTO->getNumIdMdUtlAdmTpCtrlDesemp() : '' ?>"/>
				<input type="hidden" id="hdnTpAjuste" name="hdnTpAjuste" value="<?php echo $idTpAjuste ?>"/>
			
				<?php
					PaginaSEI::getInstance()->fecharAreaDados();
					//PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
				?>
			</form>	

		
		</div>
	</div>

<?

PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
PaginaSEI::getInstance()->fecharJavaScript();
require_once('md_utl_adm_jornada_cadastro_js.php');
require_once('md_utl_geral_js.php');
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
