<?php
/**
* TRIBUNAL REGIONAL FEDERAL DA 4� REGI�O
*
* 06/07/2018 - criado por jhon.cast
*
* Vers�o do Gerador de C�digo: 1.41.0
*/

try {
  require_once dirname(__FILE__).'/../../SEI.php';
  
  session_start();

  //////////////////////////////////////////////////////////////////////////////
  // InfraDebug::getInstance()->setBolLigado(false);
  // InfraDebug::getInstance()->setBolDebugInfra(true);
  // InfraDebug::getInstance()->limpar();  
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  PaginaSEI::getInstance()->verificarSelecao('md_utl_adm_tp_ausencia_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $objMdUtlAdmTpAusenciaDTO = new MdUtlAdmTpAusenciaDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  switch($_GET['acao']){
    case 'md_utl_adm_tp_ausencia_cadastrar':
      $strTitulo = 'Novo Motivo de Aus�ncia';
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
          PaginaSEI::getInstance()->adicionarMensagem('Motivo de Aus�ncia "'.$objMdUtlAdmTpAusenciaDTO->getNumIdMdUtlAdmTpAusencia().'" cadastrado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_md_utl_adm_tp_ausencia='.$objMdUtlAdmTpAusenciaDTO->getNumIdMdUtlAdmTpAusencia().PaginaSEI::getInstance()->montarAncora($objMdUtlAdmTpAusenciaDTO->getNumIdMdUtlAdmTpAusencia())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'md_utl_adm_tp_ausencia_alterar':
      $strTitulo = 'Alterar Motivo de Aus�ncia';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarMdUtlAdmTpAusencia" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_md_utl_adm_tp_ausencia'])){
        $objMdUtlAdmTpAusenciaDTO->setNumIdMdUtlAdmTpAusencia($_GET['id_md_utl_adm_tp_ausencia']);
        $objMdUtlAdmTpAusenciaDTO->retTodos();
        $objMdUtlAdmTpAusenciaRN = new MdUtlAdmTpAusenciaRN();
        $objMdUtlAdmTpAusenciaDTO->setBolExclusaoLogica(false);
        $objMdUtlAdmTpAusenciaDTO = $objMdUtlAdmTpAusenciaRN->consultar($objMdUtlAdmTpAusenciaDTO);
        if ($objMdUtlAdmTpAusenciaDTO==null){
          throw new InfraException("Registro n�o encontrado.");
        }
      } else {
        $objMdUtlAdmTpAusenciaDTO->setNumIdMdUtlAdmTpAusencia($_POST['hdnIdMdUtlAdmTpAusencia']);
        $objMdUtlAdmTpAusenciaDTO->setStrNome($_POST['txtNome']);
        $objMdUtlAdmTpAusenciaDTO->setStrDescricao($_POST['txaDescricao']);
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objMdUtlAdmTpAusenciaDTO->getNumIdMdUtlAdmTpAusencia())).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarMdUtlAdmTpAusencia'])) {
        try{
          $objMdUtlAdmTpAusenciaRN = new MdUtlAdmTpAusenciaRN();
          $objMdUtlAdmTpAusenciaRN->alterar($objMdUtlAdmTpAusenciaDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Motivo de Aus�ncia "'.$objMdUtlAdmTpAusenciaDTO->getNumIdMdUtlAdmTpAusencia().'" alterado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objMdUtlAdmTpAusenciaDTO->getNumIdMdUtlAdmTpAusencia())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'md_utl_adm_tp_ausencia_consultar':
      $strTitulo = 'Consultar Motivo de Aus�ncia';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_md_utl_adm_tp_ausencia'])).'\';" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
      $objMdUtlAdmTpAusenciaDTO->setNumIdMdUtlAdmTpAusencia($_GET['id_md_utl_adm_tp_ausencia']);
      $objMdUtlAdmTpAusenciaDTO->setBolExclusaoLogica(false);
      $objMdUtlAdmTpAusenciaDTO->retTodos();
      $objMdUtlAdmTpAusenciaRN = new MdUtlAdmTpAusenciaRN();
      $objMdUtlAdmTpAusenciaDTO = $objMdUtlAdmTpAusenciaRN->consultar($objMdUtlAdmTpAusenciaDTO);
      if ($objMdUtlAdmTpAusenciaDTO===null){
        throw new InfraException("Registro n�o encontrado.");
      }
      break;

    default:
      throw new InfraException("A��o '".$_GET['acao']."' n�o reconhecida.");
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
PaginaSEI::getInstance()->fecharStyle();
require_once "md_utl_adm_tp_ausencia_cadastro_css.php";
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar()"');
?>
<form id="frmMdUtlAdmTpAusenciaCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  
  <?php
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos); 
    PaginaSEI::getInstance()->abrirAreaDados('98%');
  ?>
  
    <div class="row mb-3">
      <div class="col-xs-6 col-sm-10 col-md-10 col-lg-10">
        <label id="lblNome" for="txtNome" accesskey="" class="infraLabelObrigatorio"> Motivo de Aus�ncia:        
          <img align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg"
                name="ajuda" <?= PaginaSEI::montarTitleTooltip('Nome do Motivo de Aus�ncia que ir� aparecer para os servidores escolherem quando necessitarem se ausentar.','Ajuda') ?>
                class="infraImg"/>
        </label>        
        <input type="text" id="txtNome" name="txtNome" class="infraText form-control" maxlength="100" 
                value="<?=PaginaSEI::tratarHTML($objMdUtlAdmTpAusenciaDTO->getStrNome());?>" 
                onkeypress="return infraMascaraTexto(this,event,100);"  
                tabindex="<?=PaginaSEI::getInstance()->getProxTabDados(); ?>"/>        
      </div>
    </div>

    <div class="row">
      <div class="col-xs-6 col-sm-10 col-md-10 col-lg-10">
        <label id="lblDescricao" for="txaDescricao" accesskey="" class="infraLabelObrigatorio">Descri��o:
          <img align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg"
                name="ajuda" <?= PaginaSEI::montarTitleTooltip('Texto que define o Motivo de aus�ncia.','Ajuda') ?>
                class="infraImg"/>
        </label>
        <textarea type="text" id="txaDescricao" rows="3" maxlength="250" name="txaDescricao" class="infraTextArea form-control" maxlength="250"
                  onkeypress="return infraMascaraTexto(this,event,250);" 
                  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?= PaginaSEI::tratarHTML($objMdUtlAdmTpAusenciaDTO->getStrDescricao()) ?></textarea>
      </div>
    </div>
    
    <input type="hidden" id="hdnIdMdUtlAdmTpAusencia" name="hdnIdMdUtlAdmTpAusencia" value="<?= $objMdUtlAdmTpAusenciaDTO->getNumIdMdUtlAdmTpAusencia() ?>"/>

<?php PaginaSEI::getInstance()->fecharAreaDados(); ?>

</form>

<?php
require_once("md_utl_geral_js.php");
require_once("md_utl_adm_tp_ausencia_cadastro_js.php");
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>