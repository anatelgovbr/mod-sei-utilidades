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

// ========== INICIO CSS
require_once('md_utl_adm_atividade_cadastro_css.php');
// ========== FIM CSS

PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();

// ======================= INICIO JS
require_once('md_utl_adm_atividade_cadastro_js.php');
require_once('md_utl_geral_js.php');
// ======================= FIM JS

PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');

if(!is_null($strValorTmpExecucao) && !is_null($idUsuario)){
    $tempoExecucaoTeletrabalho = MdUtlAdmPrmGrUsuINT::retornaCalculoPercentualDesempenho($strValorTmpExecucao, $idTipoControle, $idUsuario);
}

?>
<form id="frmTipoControleUtilidadesCadastro" method="post" onsubmit="return onSubmitForm();" action="<?=PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao']))?>">
    <?
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    PaginaSEI::getInstance()->abrirAreaDados('auto');
    ?>
    <div class="bloco">
        <label id="lblAtividade" for="txtAtividade" class="infraLabelObrigatorio">Atividade:</label>
        <a style="" id="btAjudaAtividade" <?=PaginaSEI::montarTitleTooltip('Nome indicativo da Atividade.')?>
           tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
            <img id="imgAjudaAtividade" border="0" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/ajuda.gif" class="tamImg"/>
        </a>
        <div class="clear"></div>

        <input utlCampoObrigatorio="a" type="text" id="txtAtividade" name="txtAtividade" class="infraText" <?=$strDesabilitar?> value="<?= !is_null($strAtividade) ? PaginaSEI::tratarHTML($strAtividade) : $_POST['txtAtividade'];?>"
               onkeypress="return infraLimitarTexto(this,event,100);"  maxlength="100" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
    </div>
    </br>
    <div class="bloco">
        <label id="lblDescricao" for="txaDescricao"  class="infraLabelObrigatorio">Descri��o:</label>
        <a style="" id="btAjudaDescricao" <?=PaginaSEI::montarTitleTooltip('Breve descri��o da Atividade.')?>
           tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
            <img id="imgAjudaDescricao" border="0" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/ajuda.gif" class="tamImg"/>
        </a>
        <div class="clear"></div>
        <textarea id="txaDescricao" utlCampoObrigatorio="a" name="txaDescricao" rows="4" class="infraTextarea" <?=$strDesabilitar?> onkeypress="return infraLimitarTexto(this,event,250);" maxlength="250"
                  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?= !is_null($strDescricao) ? PaginaSEI::tratarHTML($strDescricao) :  $_POST['txaDescricao'] ;?></textarea>
    </div>
    </br>
    <div class="bloco" id="divAnalise">
        <label id="lblTipoAtividade" for="rdnTpAtivdade"  class="infraLabelObrigatorio">Tipo de Atividade:</label>
        <a style="" id="btAjudaTipoAtividade" <?=PaginaSEI::montarTitleTooltip('Definir se a atividade que est� sendo cadastrada precisa ser distribu�da para a an�lise ou j� teve encaminhamento na pr�pria triagem.')?>
           tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
            <img id="imgAjudaTipoAtividade" border="0" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/ajuda.gif" class="tamImg"/>
        </a>
        <br>
        <div id="divRadiosAnalise">
            <input type="radio" utlCampoObrigatorio="o" name="rdnTpAtivdade" id="rdnTpAtivdadeComAnalise" value="S" <?=$rdnComAnalise?> <?=$strDesabilitar?> onchange="trocarTipoAtividade(this)" class="infraRadio">
            <label id="lblComAnalise"  class="infraLabelOpicional">Com An�lise</label>
            <input type="radio" utlCampoObrigatorio="o" name="rdnTpAtivdade" id="rdnTpAtivdadeSemAnalise" value="N" <?=$rdnSemAnalise?> <?=$strDesabilitar?> onchange="trocarTipoAtividade(this)" class="infraRadio">
            <label id="lblSemAnalise" class="infraLabelOpicional">Sem An�lise</label>
        </div>
    </div>
    <br>
    <div class="bloco">
        <label id="lblAtividade" for="txtAtividade" class="infraLabelObrigatorio">Complexidade: </label>
        <a style="" id="btAjudaAtividade" <?=PaginaSEI::montarTitleTooltip('Complexidade da Atividade.') ?>
           tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" >
            <img id="imgAjudaAtividade" border="0" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/ajuda.gif" class="tamImg" />
        </a>
        <div class="clear"></div>

        <select style="width: 215px" id="selComplexidade" name="selComplexidade" class="infraSelect"	tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" <?=$strDesabilitar?> >
            <?
            if( $mdUtlAdmAtividade )
                echo MdUtlAdmAtividadeINT::montarSelectComplexidade( $mdUtlAdmAtividade->getNumComplexidade() );
            else
                echo  MdUtlAdmAtividadeINT::montarSelectComplexidade(null);
            ?>
        </select>
    </div>
    <br>
    <div class="bloco blocoExibir" id="divAtvRevAmost" >
        <input type="checkbox" <?=$chkAmostragem?> <?=$strDesabilitar?> name="chkAtvRevAmost" id="chkAtvRevAmost">
        <label id="lblAtvRevAmost" for="chkAtvRevAmost"  class="infraLabelOpcional">Habilitar Atividade para Avalia��o</label>
        <a style="" id="btAjudaAtvRevAmost" <?=PaginaSEI::montarTitleTooltip('A Atividade passar� por Avalia��o.')?>
           tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
            <img id="imgAjudaAtvRevAmost" border="0" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/ajuda.gif" class="tamImg"/>
        </a>
        <br><br>
    </div>
    <div class="bloco blocoExibir" id="divComAnalise" >

        <label  id="lblTmpExecucao" for="txtTmpExecucao" class="infraLabelObrigatorio">Tempo de Execu��o da An�lise da Atividade (em minutos):</label>
        <a style="" id="btAjudaTmpExecucao" <?=PaginaSEI::montarTitleTooltip('Indicar o Tempo de Execu��o da An�lise da Atividade (em minutos) atribu�do a essa atividade. Esse valor ser� levado em considera��o na distribui��o do processo.')?>
           tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
            <img id="imgAjudaTmpExecucao" border="0" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/ajuda.gif" class="tamImg"/>
        </a>

        <div class="clear"></div>

        <input utlSomenteNumeroPaste="true" utlCampoObrigatorio="o" type="text" id="txtTmpExecucao" name="txtTmpExecucao" style="width: 35%" class="infraText" <?=$strDesabilitar?> value="<?= !is_null($strValorTmpExecucao) ? PaginaSEI::tratarHTML($strValorTmpExecucao) : $_POST['txtTmpExecucao'];?>" onkeypress="return infraMascaraNumero(this, event,6);"
               maxlength="6" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

        <div class="clear">&nbsp;</div>

        <label id="lblTempoExecucaoAnaliseAtividade" for="txtTempoExecucaoAnaliseAtividade" class="infraLabelObrigatorio">Tempo de Execu��o da An�lise da Atividade em Teletrabalho (em minutos):</label>
        </br></br>
        <input type="text" id="txtTempoExecucaoAnaliseAtividade" name="txtTempoExecucaoAnaliseAtividade" style="width: 35%" class="infraText" value="<?= !is_null($strValorTmpExecucao) ? $tempoExecucaoTeletrabalho : '0.00';?>"
               maxlength="6" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" readonly <?=$strDesabilitar?> />

        <div class="clear">&nbsp;</div>

        <label id="lblExecucaoAtividade" for="txtExecucaoAtividade">Prazo em Dias para An�lise:</label>
        <a style="" id="btAjudaExecucaoAtividade" <?=PaginaSEI::montarTitleTooltip('Indicar o prazo em dias �teis em que a an�lise da atividade deve ser conclu�da.')?>
           tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
            <img id="imgAjudaExecucaoAtividade" border="0" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/ajuda.gif" class="tamImg"/>
        </a>
        <div class="clear"></div>

        <input utlSomenteNumeroPaste="true"  type="text" id="txtExecucaoAtividade" style="width: 35%" name="txtExecucaoAtividade" <?=$strDesabilitar?> class="infraText" value="<?= !is_null($strPrazoExeAtv) ? PaginaSEI::tratarHTML($strPrazoExeAtv) : $_POST['txtExecucaoAtividade'];?>" onkeypress="return infraMascaraNumero(this, event,3)"
               maxlength="3" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

    </div>

    <div class="bloco blocoExibir" id="divSemAnalise">
        <label id="lblRevUnidEsf" for="txtRevUnidEsf" class="infraLabelObrigatorio">Tempo de Execu��o da Avalia��o da Atividade (em minutos):</label>
        <a style="" id="btAjudaRevUnidEsf" <?=PaginaSEI::montarTitleTooltip('Indicar o Tempo de Execu��o da Avalia��o da Atividade (em minutos) atribu�do a avalia��o dessa atividade. Esse tempo ser� levado em considera��o na distribui��o do processo para a avalia��o.')?>
           tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
            <img id="imgAjudaRevUnidEsf" border="0" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/ajuda.gif" class="tamImg"/>
        </a>
        <div class="clear"></div>

        <input utlSomenteNumeroPaste=true  type="text" utlCampoObrigatorio="o" id="txtRevUnidEsf" style="width: 35%" name="txtRevUnidEsf" class="infraText" <?=$strDesabilitar?> value="<?= !is_null($strTmpExecucaoRev) ? PaginaSEI::tratarHTML($strTmpExecucaoRev) : $_POST['txtRevUnidEsf'];?>" onkeypress="return infraMascaraNumero(this, event,6)"
               maxlength="6" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

    </div>

    <div class="bloco" id="divRevATividade" style="display: none">
        </br>
        <label id="lblRevAtividade" for="txtRevAtividade" class="infraLabelObrigatorio">Prazo para Avalia��o da Atividade:</label>
        <a style="" id="btAjudaRevAtividade" <?=PaginaSEI::montarTitleTooltip('Indicar o prazo em que a avalia��o da atividade deve ser conclu�da.')?>
           tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
            <img id="imgAjudaRevAtividade" border="0" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/ajuda.gif" class="tamImg"/>
        </a>

        <div class="clear"></div>

        <input utlSomenteNumeroPaste=true type="text" id="txtRevAtividade" name="txtRevAtividade" style="width: 35%" <?=$strDesabilitar?> class="infraText" value="<?= !is_null($strPrzRevisaoAtv) ? PaginaSEI::tratarHTML($strPrzRevisaoAtv) : $_POST['txtRevAtividade'];?>" onkeypress="return infraMascaraNumero(this, event,3)"
               maxlength="50" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
        <div class="clear">&nbsp</div>
    </div>
    <div class="bloco blocoExibir" id="blocoListaProduto" >
        <fieldset class="infraFieldset" id="fieldListaProduto">
            <legend class="infraLegend">Lista de Produtos Esperados</legend>
            <br>

            <div id="divTpAtividade">
                <!-- Tipo  -->
                <label id="lblTipo" for="rdnTipo"  class="infraLabelObrigatorio">Tipo:</label>
                <a style="" id="btAjudaTipo" <?=PaginaSEI::montarTitleTooltip('Selecionar o Tipo de Produto, posteriormente escolher o documento/produto na lista abaixo e ap�s o preenchimento de todos os campos clicar no bot�o Adicionar.')?>
                   tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
                    <img id="imgAjudaTipo" border="0" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/ajuda.gif" class="tamImg"/>
                </a>
                <br>
                <div id="divRadiosAtividade">
                <input type="radio"  name="rdnTipo" value="D" id="rdnDocumento" <?=$strDesabilitar?> class="infraRadio" onchange="exibirTipo(this)">
                <label id="lblDocumentoSEI"  class="infraLabelOpicional">Documento SEI</label>
                 <input type="radio" name="rdnTipo" value="P" id="rdnProduto" <?=$strDesabilitar?> class="infraRadio" onchange="exibirTipo(this)">
                <label id="lblProduto"  class="infraLabelOpicional">Produto</label>
                </div>

            </div>

            <div class="bloco blocoExibir" id="divTpProduto">
                <br>
                <label id="lblTpProduto" for="selTpProduto" accesskey="" class="infraLabelObrigatorio">Tipo de Produto:</label>
                <a style="" id="btnAjudaTpProduto" <?=PaginaSEI::montarTitleTooltip('Selecionar o produto na lista abaixo e ap�s o preenchimento de todos os campos clicar no bot�o Adicionar.')?>
                   tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
                    <img id="imgAjudaTpProduto" border="0" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/ajuda.gif" class="tamImg"/>
                </a>
                <select id="selTpProduto" name="selTpProduto" style="width:25%" <?=$strDesabilitar?> class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
                    <?=$strItensSelTpProduto?>
                </select>

            </div>
            <div class="bloco blocoExibir" id="divTpDocumento">
                <br>
                <label id="selTpDocumento" for="selTpDocumento" accesskey="" class="infraLabelObrigatorio">Tipo de Documento SEI:</label>
                <a style="" id="btnAjudaTpDocumento" <?=PaginaSEI::montarTitleTooltip('Selecionar o documento na lista abaixo e ap�s o preenchimento de todos os campos clicar no bot�o Adicionar.')?>
                   tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
                    <img id="imgAjudaTpDocumento" border="0" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/ajuda.gif" class="tamImg"/>
                </a>
                <br>
                <div id="divRadiosAplicacaoDoc">
                <input type="radio"  id="rdnAplicSerieInterno" name="rdnAplicSerie" value="I" class="infraRadio" <?=$strDesabilitar?> onchange="exibirTipoDocumento(this.value)">
                <label id="lblInterno"  class="infraLabelOpicional" <?=$strDesabilitar?> >Interno</label>
                <input type="radio" id="rdnAplicSerieExterno" name="rdnAplicSerie" value="E" class="infraRadio" <?=$strDesabilitar?> onchange="exibirTipoDocumento(this.value)">
                <label id="lblExterno"  class="infraLabelOpicional">Externo</label>
                </div>
                <div id="divSelectAplicacaoDoc">
                <select id="selTpDocumentoExt" name="selTpDocumento" <?=$strDesabilitar?> style="width: 25%; display: none" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
                    <?=$strItensSelTpDocumentoExterno?>
                </select>

                <select id="selTpDocumentoInt" name="selTpDocumento" <?=$strDesabilitar?> style="width: 25%; display: none" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
                    <?=$strItensSelTpDocumentoInterno?>
                </select>
                </div>
            </div>
            <br>
            <div class="bloco" id="divFinal" style="display: none">
                <input type="checkbox" name="chkObrigatorio" <?=$strDesabilitar?> id="chkObrigatorio">
                <label id="lblObrigatorio" for="chkObrigatorio" class="infraLabelOpcional">Obrigat�rio</label>
                <a style="" id="btAjudaObrigatorio" <?=PaginaSEI::montarTitleTooltip('Informa se o preenchimento do Tipos de Documento SEI/ Tipo de Produto ser� ou n�o obrigat�rio.')?>
                   tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
                    <img id="imgAjudaObrigatorio" border="0" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/ajuda.gif" class="tamImg"/>
                </a>

                <div class="clear"></div>
                <div id="divVlRevisaoProdEsforco">
                <label id="lblRevUnidade" for="txtRevUnidade" class="infraLabelObrigatorio">Tempo de Execu��o da Avalia��o do Produto (em minutos):</label>
                <a style="" id="btAjudaRevUnidade" <?=PaginaSEI::montarTitleTooltip('Indicar o Tempo de Execu��o da Avalia��o do Produto (em minutos) atribu�do a avalia��o desse produto quando essa atividade for para avalia��o. Esse valor ser� levado em considera��o na distribui��o do processo para a avalia��o. Ap�s o preenchimento de todos os campos clicar no bot�o Adicionar.')?>
                   tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
                    <img id="imgAjudaRevUnidade" border="0" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/ajuda.gif" class="tamImg"/>
                </a>
                </div>

                <div class="clear"></div>

                <input utlSomenteNumeroPaste="true"  type="text" id="txtRevUnidade" name="txtRevUnidade" <?=$strDesabilitar?> style="width: 40%" class="infraText" value="<?= !is_null($objFilaDTO) ? PaginaSEI::tratarHTML($objFilaDTO->getStrNome()) : $_POST['txtNome'];?>" onkeypress="return infraMascaraNumero(this, event,6)"
                       maxlength="6" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

                <div id="divBtnAdicionar">
                <button type="button" class="infraButton" id="adicionar" accesskey="a" onclick="adicionarRegistroTabelaProduto()"><span class="infraTeclaAtalho">A</span>dicionar</button>
                </div>
            </div>
            <br>

            <table width="99%" class="infraTable" summary="Produto Esperado" id="tbProdutoEsperado"  style="<?php echo $strItensTabela == '' ? 'display: none' : ''?>">
                <caption style="background-color: white;" class="infraCaption"> <?= PaginaSEI::getInstance()->gerarCaptionTabela('Produtos', 0) ?> </caption>
                <tr>
                    <th style="display: none">id_pk_tabela</th>
                    <th style="display: none">id_documernto_tipo</th>
                    <th style="display: none">tipo</th>
                    <th style="display: none">aplicabilidade</th>
                    <th class="infraTh"  align="center" width="30%" >Tipo de Documento SEI/Tipo de Produto</th> <!--1-->
                    <th class="infraTh"  align="center" width="30%" >Tempo de Execu��o da Avalia��o do Produto (em minutos)</th> <!--2-->
                    <th class="infraTh"  align="center" width="25%" >Obrigat�rio</th>
                    <th style="display: none">chk_obrigatorio</th>
                    <th style="display: none">id_vinculo</th><!--0-->
                    <th style="display: none">id_doc</th>
                    <th style="display: none">vinculo_analise</th>
                    <th style="display: none">is</th>
                    <th class="infraTh"  align="center" width="15%" >A��es</th> <!--5-->
                </tr>
            </table>
        </fieldset>
    </div>
    <input <?php echo $strItensTabela != '' ? 'utlCampoObrigatorio="o"' : ''; ?> type="hidden" id="hdnTbProdutoEsperado" name="hdnTbProdutoEsperado" value="<?=$strItensTabela?>" />
    <pre>
    </pre>
    <input type="hidden" id="hdnIdTipoControleUtl" name="hdnIdTipoControleUtl" value="<?=$idTipoControle?>" />
    <input type="hidden" id="hdnIdAtividade"       name="hdnIdAtividade"       value="<?=$idAtividade?>" />
    <input type="hidden" id="hdnIdsRemovido"       name="hdnIdsRemovido"       value="" />
    <input type="hidden" id="hdnIsAlterar"       name="hdnIsAlterar"       value="<?php echo $isAlterar ?>" />
    <input type="hidden" id="hdnIdAlteracao"       name="hdnIdAlteracao"       value="" />
    <?
    PaginaSEI::getInstance()->fecharAreaDados();
    PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>