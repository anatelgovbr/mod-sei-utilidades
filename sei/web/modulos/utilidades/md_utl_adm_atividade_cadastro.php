<?php
/**
 * Created by PhpStorm.
 * User: jhon.carvalho
 * Date: 06/08/2018
 * Time: 11:13
 */

try{
    $isAlterar = 0;
    require_once dirname(__FILE__).'/../../SEI.php';
    session_start();

    SessaoSEI::getInstance()->validarLink();
    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

    // ======================= INICIO ACOES PHP DA PAGINA
    require_once 'md_utl_adm_atividade_cadastro_acoes.php';
    // ======================= FIM ACOES PHP DA PAGINA

    $habDivComAnalise = false;
    $habDivSemAnalise = false;
    if( $isAlterar == 1 || $bolConsultar || $isClonar ){
        if( $rdnTpAtividade == 'S' ){
          $habDivComAnalise = true;
          $habDivSemAnalise = false;
        }else{
          $habDivComAnalise = false;
          $habDivSemAnalise = true;
        }
    }
   
}catch (Exception $e){
    PaginaSEI::getInstance()->processarExcecao($e);
}

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(':: '.PaginaSEI::getInstance()->getStrNomeSistema().' - '.$strTitulo.' ::');

PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
PaginaSEI::getInstance()->fecharStyle();
require_once('md_utl_adm_atividade_cadastro_css.php');
require_once 'md_utl_geral_css.php';

PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
PaginaSEI::getInstance()->fecharJavaScript();

PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');

if(!is_null($strValorTmpExecucao) && !is_null($idUsuario)){
    $tempoExecucaoTeletrabalho = MdUtlAdmPrmGrUsuINT::retornaCalculoPercentualDesempenho($strValorTmpExecucao, $idTipoControle, $idUsuario);
}

$strAction = $isClonar 
            ? SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].'&id_md_utl_adm_atividade='.$idAtividade.'&id_tipo_controle_utl='.$idTipoControle.'&isClonar=S')
            : SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])          

?>
<form id="frmTipoControleUtilidadesCadastro" method="post" onsubmit="return onSubmitForm();" action="<?=PaginaSEI::getInstance()->formatarXHTML($strAction)?>">
    <?php
      PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
      PaginaSEI::getInstance()->abrirAreaDados();
    ?>

    <div class="row mb-3">
      <div class="col-sm-8 col-md-10 col-lg-10">
        <label id="lblAtividade" for="txtAtividade" class="infraLabelObrigatorio">Atividade:</label>
        <img align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg" name="ajuda"
                                  <?= PaginaSEI::montarTitleTooltip('Nome indicativo da Atividade.','Ajuda') ?>/>

        <input utlCampoObrigatorio="a" type="text" id="txtAtividade" name="txtAtividade" class="infraText form-control" <?=$strDesabilitar?> value="<?= !is_null($strAtividade) ? PaginaSEI::tratarHTML($strAtividade) : $_POST['txtAtividade'];?>"
              onkeypress="return infraLimitarTexto(this,event,100);"  maxlength="100" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-sm-8 col-md-10 col-lg-10">
        <label id="lblDescricao" for="txaDescricao"  class="infraLabelObrigatorio">Descrição:</label>
        <img align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg" name="ajuda"
                                  <?= PaginaSEI::montarTitleTooltip('Breve descrição da Atividade.','Ajuda') ?>/>

        <textarea id="txaDescricao" utlCampoObrigatorio="a" name="txaDescricao" rows="4" class="infraTextarea form-control" <?=$strDesabilitar?> onkeypress="return infraLimitarTexto(this,event,250);" maxlength="250"
                  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?= !is_null($strDescricao) ? PaginaSEI::tratarHTML($strDescricao) :  $_POST['txaDescricao'] ;?></textarea>
      </div>
    </div>

    <div class="row mb-3" id="divAnalise">
      <div class="col-sm-8 col-md-10 col-lg-10">
        <label id="lblTipoAtividade" for="rdnTpAtivdade"  class="infraLabelObrigatorio">Tipo de Atividade:</label>
        <img align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg" name="ajuda"
          <?= PaginaSEI::montarTitleTooltip('Definir se a atividade que está sendo cadastrada precisa ser distribuída para a análise ou já teve encaminhamento na própria triagem.','Ajuda') ?>/>         
          <div id="divRadiosAnalise">
            <div class="form-check-inline">
              <input type="radio" utlCampoObrigatorio="o" name="rdnTpAtivdade" id="rdnTpAtivdadeComAnalise" value="S" class="infraRadio" <?=$rdnComAnalise?> <?=$strDesabilitar?> 
                    onchange="trocarTipoAtividade(this)">
              <label id="lblComAnalise" name="lblComAnalise" for="rdnTpAtivdadeComAnalise"
                    class="infraLabelOpcional infraLabelRadio">Com Análise</label>
            </div>

            <div class="form-check-inline">
              <input type="radio" utlCampoObrigatorio="o" name="rdnTpAtivdade" id="rdnTpAtivdadeSemAnalise" value="N" class="infraRadio" <?=$rdnSemAnalise?> <?=$strDesabilitar?> 
                    onchange="trocarTipoAtividade(this)">
              <label id="lblSemAnalise" name="lblSemAnalise" for="rdnTpAtivdadeSemAnalise"
                    class="infraLabelOpcional infraLabelRadio">Sem Análise</label>
            </div>
          </div>
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-sm-6 col-md-6 col-lg-6">
        <label id="lblAtividade" for="txtAtividade" class="infraLabelObrigatorio">Complexidade:</label>
        <img align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg" name="ajuda"
          <?= PaginaSEI::montarTitleTooltip('Complexidade da Atividade.','Ajuda') ?>/>
        <select id="selComplexidade" name="selComplexidade" class="infraSelect form-control" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" <?=$strDesabilitar?>>
          <?php
          if( $mdUtlAdmAtividade )
            echo MdUtlAdmAtividadeINT::montarSelectComplexidade( $mdUtlAdmAtividade->getNumComplexidade() );
          else
            echo  MdUtlAdmAtividadeINT::montarSelectComplexidade(null);
          ?>
        </select>
      </div>
    </div>

    <div id="divAtvRevAmost" <?= $habDivComAnalise || $habDivSemAnalise ? '' : 'style="display: none;"' ?> >
      <div class="row mb-3">
        <div class="col-sm-8 col-md-10 col-lg-10">          
          <input type="checkbox" <?=$chkAmostragem?> <?=$strDesabilitar?> name="chkAtvRevAmost" id="chkAtvRevAmost" class="infraCheckbox form-check-input">
          <label id="lblAtvRevAmost" for="chkAtvRevAmost"  class="infraLabelOpcional">Habilitar Atividade para Avaliação</label>
          <img align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg" name="ajuda"
            <?= PaginaSEI::montarTitleTooltip('A Atividade passará por Avaliação.','Ajuda') ?>/>          
        </div>
      </div>
    </div>
    
    <div id="divComAnalise" <?= $habDivComAnalise === true ? '' : 'style="display: none;"' ?>>
      <div class="row mb-3">
        <div class="col-sm-10 col-md-10 col-lg-8">
          <label  id="lblTmpExecucao" for="txtTmpExecucao" class="infraLabelObrigatorio">Tempo de Execução da Análise da Atividade (em minutos):</label>
          <img align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg" name="ajuda"
              <?= PaginaSEI::montarTitleTooltip('Indicar o Tempo de Execução da Análise da Atividade (em minutos) atribuído a essa atividade. Esse valor será levado em consideração na distribuição do processo.','Ajuda') ?>/>

          <input utlSomenteNumeroPaste="true" utlCampoObrigatorio="o" type="text" id="txtTmpExecucao" name="txtTmpExecucao" class="infraText form-control" <?=$strDesabilitar?> value="<?= !is_null($strValorTmpExecucao) ? PaginaSEI::tratarHTML($strValorTmpExecucao) : $_POST['txtTmpExecucao'];?>" onkeypress="return infraMascaraNumero(this, event,6);"
                  maxlength="6" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
        </div>
      </div>

      <div id="divNaoAplicarPercDsmp">
        <div class="row mb-3">
          <div class="col-sm-11 col-md-11 col-lg-10">
            <input type="checkbox" <?= $chkNaoAplicarPerc ?> <?= $strDesabilitar ?> name="chkNaoAplicarPercDsmp" id="chkNaoAplicarPercDsmp" 
                  class="infraCheckbox form-check-input" onchange="checkNaoAplicarPerc(this);">
            <label id="lblNaoAplicarPercDsmp" for="chkNaoAplicarPercDsmp"  class="infraLabelOpcional">
              Não aplicar Percentual de Desempenho a Maior para Teletrabalho
            </label>
            <img align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg" name="ajuda"
              <?= PaginaSEI::montarTitleTooltip('A Definir.','Ajuda') ?>/>          
          </div>
        </div>
      </div>

      <div id="divTmpAnaliseTeletrabalho" class="row mb-3" <?= is_null($chkNaoAplicarPerc) ? '' : 'style="display:none;"' ?>>        
        <div class="col-sm-10 col-md-10 col-lg-8">
          <label id="lblTempoExecucaoAnaliseAtividade" for="txtTempoExecucaoAnaliseAtividade" class="infraLabelObrigatorio">Tempo de Execução da Análise da Atividade em Teletrabalho (em minutos):</label>
          <input type="text" id="txtTempoExecucaoAnaliseAtividade" name="txtTempoExecucaoAnaliseAtividade" class="infraText form-control" value="<?= !is_null($strValorTmpExecucao) ? $tempoExecucaoTeletrabalho : '0.00';?>"
                maxlength="6" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" readonly <?=$strDesabilitar?> />
        </div>
      </div> 

      <div class="row mb-3">        
        <div class="col-sm-10 col-md-10 col-lg-8">
          <label id="lblExecucaoAtividade" for="txtExecucaoAtividade">Prazo em Dias para Análise:</label>
          <img align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg" name="ajuda"
              <?= PaginaSEI::montarTitleTooltip('Indicar o prazo em dias úteis em que a análise da atividade deve ser concluída.','Ajuda') ?>/>

          <input utlSomenteNumeroPaste="true"  type="text" id="txtExecucaoAtividade" name="txtExecucaoAtividade" <?=$strDesabilitar?> class="infraText form-control" value="<?= !is_null($strPrazoExeAtv) ? PaginaSEI::tratarHTML($strPrazoExeAtv) : $_POST['txtExecucaoAtividade'];?>" onkeypress="return infraMascaraNumero(this, event,3)"
               maxlength="3" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
        </div>
      </div>
    </div>
    
    <div id="divSemAnalise" <?= $habDivSemAnalise ? '' : 'style="display: none;"' ?>>
      <div class="row mb-3">
        <div class="col-sm-10 col-md-10 col-lg-8">
          <label id="lblRevUnidEsf" for="txtRevUnidEsf" class="infraLabelObrigatorio">Tempo de Execução da Avaliação da Atividade (em minutos):</label>
          <img align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg" name="ajuda"
              <?= PaginaSEI::montarTitleTooltip('Indicar o Tempo de Execução da Avaliação da Atividade (em minutos) atribuído a avaliação dessa atividade. Esse tempo será levado em consideração na distribuição do processo para a avaliação.','Ajuda') ?>/>

          <input utlSomenteNumeroPaste=true  type="text" utlCampoObrigatorio="o" id="txtRevUnidEsf" name="txtRevUnidEsf" class="infraText form-control" <?=$strDesabilitar?> value="<?= !is_null($strTmpExecucaoRev) ? PaginaSEI::tratarHTML($strTmpExecucaoRev) : $_POST['txtRevUnidEsf'];?>" onkeypress="return infraMascaraNumero(this, event,6)"
                maxlength="6" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
        </div>
      </div>
    </div>

    <div id="divRevATividade" style="display:none;">
      <div class="row mb-3">
        <div class="col-sm-10 col-md-10 col-lg-8">
          <label id="lblRevAtividade" for="txtRevAtividade" class="infraLabelObrigatorio">Prazo para Avaliação da Atividade:</label>
          <img align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg" name="ajuda"
                <?= PaginaSEI::montarTitleTooltip('Indicar o prazo em que a avaliação da atividade deve ser concluída.','Ajuda') ?>/>
              
          <input utlSomenteNumeroPaste=true type="text" id="txtRevAtividade" name="txtRevAtividade" <?=$strDesabilitar?> class="infraText form-control" value="<?= !is_null($strPrzRevisaoAtv) ? PaginaSEI::tratarHTML($strPrzRevisaoAtv) : $_POST['txtRevAtividade'];?>" onkeypress="return infraMascaraNumero(this, event,3)"
                maxlength="50" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
        </div>
      </div>
    </div>

    <div <?= $habDivComAnalise ? '' : 'style="display: none;"' ?> id="blocoListaProduto">
      <div class="row rowFieldSet">
        <div class="col-sm-12 col-md-12 col-lg-12">
          <fieldset class="infraFieldset fieldset-comum form-control" id="fieldListaProduto">
            <legend class="infraLegend">Lista de Produtos Esperados</legend>

            <!-- DIV TIPO DE ATIVIDADE: DOCUMENTO OU PRODUTO -->
            <div id="divTpAtividade" <?php if( $bolConsultar ) echo 'style="display:none;"'?>>
              <div class="row mb-3">
                <div class="col-sm-10 col-md-10 col-lg-8">
                  <label id="lblTipo" class="infraLabelObrigatorio">Tipo:</label>
                  <img align="top" id="btAjudaTipo" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg" name="ajuda"
                    <?= PaginaSEI::montarTitleTooltip('Selecionar o Tipo de Produto, posteriormente escolher o documento/produto na lista abaixo e após o preenchimento de todos os campos clicar no botão Adicionar.','Ajuda') ?>/>
                  
                  <div id="divRadiosAtividade">                
                    <div class="form-check-inline">
                      <input type="radio" name="rdnTipo" id="rdnDocumento" value="D" class="infraRadio" <?=$strDesabilitar?> onchange="exibirTipo(this)">
                      <label id="lblDocumento" name="lblDocumento" for="rdnDocumento"
                            class="infraLabelOpcional infraLabelRadio">Documento SEI</label>
                    </div>

                    <div class="form-check-inline">
                      <input type="radio" name="rdnTipo" id="rdnProduto" value="P" class="infraRadio" <?=$strDesabilitar?> onchange="exibirTipo(this)">
                      <label id="lblProduto" name="lblProduto" for="rdnProduto"
                            class="infraLabelOpcional infraLabelRadio">Produto</label>
                    </div>
                  </div>            
                </div>
              </div>
            </div>

            <!-- DIV PRODUTO -->
            <div class="bloco" id="divTpProduto" style="display: none;">
              <div class="row mb-3">
                <div class="col-sm-10 col-md-10 col-lg-8">              
                  <label id="lblTpProduto" for="selTpProduto" accesskey="" class="infraLabelObrigatorio">Tipo de Produto:</label>
                  <img align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg" name="ajuda"
                    <?= PaginaSEI::montarTitleTooltip('Selecionar o produto na lista abaixo e após o preenchimento de todos os campos clicar no botão Adicionar.','Ajuda') ?>/>

                  <select id="selTpProduto" name="selTpProduto" <?=$strDesabilitar?> class="infraSelect form-control" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
                    <?=$strItensSelTpProduto?>
                  </select>
                </div>
              </div>
            </div>

            <!-- DIV DOCUMENTO -->
            <div class="bloco" id="divTpDocumento" style="display: none;">
              <div class="row mb-3">
                <div class="col-sm-10 col-md-10 col-lg-8">              
                  <label id="_selTpDocumento" for="selTpDocumento" accesskey="" class="infraLabelObrigatorio">Tipo de Documento SEI:</label>
                  <img align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg" name="ajuda"
                    <?= PaginaSEI::montarTitleTooltip('Selecionar o documento na lista abaixo e após o preenchimento de todos os campos clicar no botão Adicionar.','Ajuda') ?>/>
                  <div id="divSelectAplicacaoDoc">
                    <select id="selTpDocumento" name="selTpDocumento" <?=$strDesabilitar?> class="infraSelect form-control" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
                      <?=$strItensSelTpDocIntAndExt?>
                    </select>
                  </div>
                </div>
              </div>
            </div>

            <!-- DIV FINAL - COMUM -->
            <div class="bloco" id="divFinal" style="display:none;">
              <div class="row">
                <div class="col-sm-8 col-md-10 col-lg-10 mb-3">                  
                    <input type="checkbox" name="chkObrigatorio" <?=$strDesabilitar?> id="chkObrigatorio" class="form-check-input infraCheckbox">
                    <label id="lblObrigatorio" for="chkObrigatorio" class="infraLabelOpcional infraCheckboxLabel">Obrigatório</label>
                    <img align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg" name="ajuda"
                    <?= PaginaSEI::montarTitleTooltip('Informa se o preenchimento do Tipos de Documento SEI/ Tipo de Produto será ou não obrigatório.','Ajuda') ?>/>                                
                </div>
               
                <div class="col-sm-12 col-md-10 col-lg-8" id="divVlRevisaoProdEsforco">             
                  <label id="lblRevUnidade" for="txtRevUnidade" class="infraLabelObrigatorio">Tempo de Execução da Avaliação do Produto (em minutos):</label>
                  <img align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg" name="ajuda"
                      <?= PaginaSEI::montarTitleTooltip('Indicar o Tempo de Execução da Avaliação do Produto (em minutos) atribuído a avaliação desse produto quando essa atividade for para avaliação. Esse valor será levado em consideração na distribuição do processo para a avaliação. Após o preenchimento de todos os campos clicar no botão Adicionar.','Ajuda') ?>/>
                  
                  <input utlSomenteNumeroPaste="true"  type="text" id="txtRevUnidade" name="txtRevUnidade" <?=$strDesabilitar?> class="infraText form-control" value="<?= !is_null($objFilaDTO) ? PaginaSEI::tratarHTML($objFilaDTO->getStrNome()) : $_POST['txtNome'];?>" onkeypress="return infraMascaraNumero(this, event,6)"
                        maxlength="6" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />                           
                </div>

                <div class="col-sm-8 col-md-8 col-lg-8" style="padding: 10px 0px 15px 15px;">
                  <button type="button" class="infraButton" id="adicionar" accesskey="a" onclick="adicionarRegistroTabelaProduto()"><span class="infraTeclaAtalho">A</span>dicionar</button>
                </div>

              </div>
            </div>

            <div class="row">
              <div class="col-12">
                <table class="infraTable" summary="Produto Esperado" id="tbProdutoEsperado"  style="<?php echo $strItensTabela == '' ? 'display: none' : ''?>">
                  <caption style="background-color: white;" class="infraCaption"> <?= PaginaSEI::getInstance()->gerarCaptionTabela('Produtos', 0) ?> </caption>
                    <tr>
                      <th style="display: none">id_pk_tabela</th>
                      <th style="display: none">id_documernto_tipo</th>
                      <th style="display: none">tipo</th>
                      <th style="display: none">aplicabilidade</th>
                      <th class="infraTh"  align="center" width="30%" >Tipo de Documento SEI/Tipo de Produto</th> <!--1-->
                      <th class="infraTh"  align="center" width="30%" >Tempo de Execução da Avaliação do Produto (em minutos)</th> <!--2-->
                      <th class="infraTh"  align="center" width="25%" >Obrigatório</th>
                      <th style="display: none">chk_obrigatorio</th>
                      <th style="display: none">id_vinculo</th><!--0-->
                      <th style="display: none">id_doc</th>
                      <th style="display: none">vinculo_analise</th>
                      <th style="display: none">is</th>
                      <th class="infraTh"  align="center" width="15%" >Ações</th> <!--5-->
                    </tr>
                </table>
              </div>
            </div>
          </fieldset>
        </div>
      </div>
    </div>
    <input <?php echo $strItensTabela != '' ? 'utlCampoObrigatorio="o"' : ''; ?> type="hidden" id="hdnTbProdutoEsperado" name="hdnTbProdutoEsperado" value="<?=$strItensTabela?>" />
    <input type="hidden" id="hdnIdTipoControleUtl" name="hdnIdTipoControleUtl" value="<?= $idTipoControle?>" />
    <input type="hidden" id="hdnIdAtividade"       name="hdnIdAtividade"       value="<?= $isClonar ? null : $idAtividade?>" />
    <input type="hidden" id="hdnIdsRemovido"       name="hdnIdsRemovido"       value="" />
    <input type="hidden" id="hdnIsAlterar"         name="hdnIsAlterar"         value="<?= $isAlterar ?>" />
    <input type="hidden" id="hdnIdAlteracao"       name="hdnIdAlteracao"       value="" />

    <?php
      PaginaSEI::getInstance()->fecharAreaDados();
      PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
</form>

<?php
    require_once('md_utl_adm_atividade_cadastro_js.php');
    require_once('md_utl_geral_js.php');
    PaginaSEI::getInstance()->fecharBody();
    PaginaSEI::getInstance()->fecharHtml();
?>