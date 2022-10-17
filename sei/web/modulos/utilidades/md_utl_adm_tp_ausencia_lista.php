<?
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
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  PaginaSEI::getInstance()->prepararSelecao('md_utl_adm_tp_ausencia_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  PaginaSEI::getInstance()->salvarCamposPost(array('txtDescricao','txtTpAusencia'));

  switch($_GET['acao']){
    case 'md_utl_adm_tp_ausencia_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjMdUtlAdmTpAusenciaDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objMdUtlAdmTpAusenciaDTO = new MdUtlAdmTpAusenciaDTO();
          $objMdUtlAdmTpAusenciaDTO->setNumIdMdUtlAdmTpAusencia($arrStrIds[$i]);
          $arrObjMdUtlAdmTpAusenciaDTO[] = $objMdUtlAdmTpAusenciaDTO;
        }
        $objMdUtlAdmTpAusenciaRN = new MdUtlAdmTpAusenciaRN();
        $objMdUtlAdmTpAusenciaRN->excluir($arrObjMdUtlAdmTpAusenciaDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Opera��o realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;


    case 'md_utl_adm_tp_ausencia_desativar':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjMdUtlAdmTpAusenciaDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objMdUtlAdmTpAusenciaDTO = new MdUtlAdmTpAusenciaDTO();
          $objMdUtlAdmTpAusenciaDTO->setNumIdMdUtlAdmTpAusencia($arrStrIds[$i]);
          $arrObjMdUtlAdmTpAusenciaDTO[] = $objMdUtlAdmTpAusenciaDTO;
        }
        $objMdUtlAdmTpAusenciaRN = new MdUtlAdmTpAusenciaRN();
        $objMdUtlAdmTpAusenciaRN->desativar($arrObjMdUtlAdmTpAusenciaDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Opera��o realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'md_utl_adm_tp_ausencia_reativar':
      $strTitulo = 'Reativar Motivos de Aus�ncia';
      if ($_GET['acao_confirmada']=='sim'){
        try{
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjMdUtlAdmTpAusenciaDTO = array();
          for ($i=0;$i<count($arrStrIds);$i++){
            $objMdUtlAdmTpAusenciaDTO = new MdUtlAdmTpAusenciaDTO();
            $idTpAusencia = $arrStrIds[$i];
            $objMdUtlAdmTpAusenciaDTO->setNumIdMdUtlAdmTpAusencia($arrStrIds[$i]);
            $arrObjMdUtlAdmTpAusenciaDTO[] = $objMdUtlAdmTpAusenciaDTO;
          }
          $objMdUtlAdmTpAusenciaRN = new MdUtlAdmTpAusenciaRN();
          $objMdUtlAdmTpAusenciaRN->reativar($arrObjMdUtlAdmTpAusenciaDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Opera��o realizada com sucesso.');
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        } 
        header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']. PaginaSEI::getInstance()->montarAncora($idTpAusencia)));
        die;
      } 
      break;


    case 'md_utl_adm_tp_ausencia_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Motivo de Aus�ncia','Selecionar Motivos de Aus�ncia');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='md_utl_adm_tp_ausencia_cadastrar'){
        if (isset($_GET['id_md_utl_adm_tp_ausencia'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_md_utl_adm_tp_ausencia']);
        }
      }
      break;

    case 'md_utl_adm_tp_ausencia_listar':
      $strTitulo = 'Motivo de Aus�ncia';
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_tp_ausencia_reativar');
        break;

    default:
      throw new InfraException("A��o '".$_GET['acao']."' n�o reconhecida.");
  }

  $arrComandos = array();
    $arrComandos[] = '<button type="submit" accesskey="P" id="btnPesquisar" value="Pesquisar" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';

    if ($_GET['acao'] == 'md_utl_adm_tp_ausencia_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  $objMdUtlAdmTpAusenciaDTO = new MdUtlAdmTpAusenciaDTO();

  if ($_GET['acao'] == 'md_utl_adm_tp_ausencia_listar' || $_GET['acao'] == 'md_utl_adm_tp_ausencia_selecionar'){
      $descricao  = PaginaSEI::getInstance()->recuperarCampo('txtDescricao');
      $tpAusencia = PaginaSEI::getInstance()->recuperarCampo('txtTpAusencia');

      $objMdUtlAdmTpAusenciaDTO->setStrNome('%'.trim($tpAusencia.'%'),InfraDTO::$OPER_LIKE);
      $objMdUtlAdmTpAusenciaDTO->setStrDescricao('%'.trim($descricao.'%'),InfraDTO::$OPER_LIKE);

    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_tp_ausencia_cadastrar');
    if ($bolAcaoCadastrar){
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_tp_ausencia_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
    }
  }


  $objMdUtlAdmTpAusenciaDTO->retNumIdMdUtlAdmTpAusencia();
  $objMdUtlAdmTpAusenciaDTO->retStrNome();
  $objMdUtlAdmTpAusenciaDTO->retStrDescricao();
  $objMdUtlAdmTpAusenciaDTO->retStrSinAtivo();

  if($bolAcaoReativar) {
      $objMdUtlAdmTpAusenciaDTO->setBolExclusaoLogica(false);
  }

 /* if ($_GET['acao'] == 'md_utl_adm_tp_ausencia_reativar'){
    //Lista somente inativos
    $objMdUtlAdmTpAusenciaDTO->setBolExclusaoLogica(false);
    $objMdUtlAdmTpAusenciaDTO->setStrSinAtivo('N');
  }*/

  PaginaSEI::getInstance()->prepararOrdenacao($objMdUtlAdmTpAusenciaDTO, 'IdMdUtlAdmTpAusencia', InfraDTO::$TIPO_ORDENACAO_ASC);
  PaginaSEI::getInstance()->prepararPaginacao($objMdUtlAdmTpAusenciaDTO);

  $objMdUtlAdmTpAusenciaRN = new MdUtlAdmTpAusenciaRN();
  $arrObjMdUtlAdmTpAusenciaDTO = $objMdUtlAdmTpAusenciaRN->listar($objMdUtlAdmTpAusenciaDTO);

  PaginaSEI::getInstance()->processarPaginacao($objMdUtlAdmTpAusenciaDTO);
  $numRegistros = count($arrObjMdUtlAdmTpAusenciaDTO);
  $strUrlFechar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_controlar&acao_origem=' . $_GET['acao']);

  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='md_utl_adm_tp_ausencia_selecionar'){
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_tp_ausencia_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_tp_ausencia_alterar');
      $bolAcaoImprimir = false;
      //$bolAcaoGerarPlanilha = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolCheck = true;
    }else if ($_GET['acao']=='md_utl_adm_tp_ausencia_reativar'){
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_tp_ausencia_reativar');
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_tp_ausencia_consultar');
      $bolAcaoAlterar = false;
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_tp_ausencia_excluir');
      $bolAcaoDesativar = false;
    }else{
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_tp_ausencia_reativar');
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_tp_ausencia_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_tp_ausencia_alterar');
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_tp_ausencia_excluir');
      $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_tp_ausencia_desativar');
    }

    
    if ($bolAcaoDesativar){
      $bolCheck = true;
      //$arrComandos[] = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
      $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_tp_ausencia_desativar&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoReativar){
      $bolCheck = true;
      //$arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
      $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_tp_ausencia_reativar&acao_origem='.$_GET['acao'].'&acao_confirmada=sim');
    }
    

    if ($bolAcaoExcluir){
      $bolCheck = true;
      //$arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_tp_ausencia_excluir&acao_origem='.$_GET['acao']);
    }

    /*
    if ($bolAcaoGerarPlanilha){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="P" id="btnGerarPlanilha" value="Gerar Planilha" onclick="infraGerarPlanilhaTabela(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=infra_gerar_planilha_tabela').'\');" class="infraButton">Gerar <span class="infraTeclaAtalho">P</span>lanilha</button>';
    }
    */

    $strResultado = '';

    if ($_GET['acao']!='md_utl_adm_tp_ausencia_reativar'){
      $strSumarioTabela = 'Tabela de Motivos de Aus�ncia.';
      $strCaptionTabela = 'Motivos de Aus�ncia';
    }else{
      $strSumarioTabela = 'Tabela de Motivos de Aus�ncia Inativos.';
      $strCaptionTabela = 'Motivos de Aus�ncia Inativos';
    }

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%" style="display: none">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh" width="35%">'.PaginaSEI::getInstance()->getThOrdenacao($objMdUtlAdmTpAusenciaDTO,'Motivo de Aus�ncia','Nome',$arrObjMdUtlAdmTpAusenciaDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="38%">'.PaginaSEI::getInstance()->getThOrdenacao($objMdUtlAdmTpAusenciaDTO,'Descri��o','Descricao',$arrObjMdUtlAdmTpAusenciaDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="22%">A��es</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      if($arrObjMdUtlAdmTpAusenciaDTO[$i]->getStrSinAtivo()=='N')
      {
          $strCssTr = '<tr class="trVermelha">';
      }
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top" style="display: none">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjMdUtlAdmTpAusenciaDTO[$i]->getNumIdMdUtlAdmTpAusencia(),$arrObjMdUtlAdmTpAusenciaDTO[$i]->getNumIdMdUtlAdmTpAusencia()).'</td>';
      }
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjMdUtlAdmTpAusenciaDTO[$i]->getStrNome()).'</td>';
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjMdUtlAdmTpAusenciaDTO[$i]->getStrDescricao()).'</td>';
      $strResultado .= '<td align="center">';

      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjMdUtlAdmTpAusenciaDTO[$i]->getNumIdMdUtlAdmTpAusencia());

      if ($bolAcaoConsultar){        
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_tp_ausencia_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_md_utl_adm_tp_ausencia='.$arrObjMdUtlAdmTpAusenciaDTO[$i]->getNumIdMdUtlAdmTpAusencia()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getDiretorioSvgGlobal().'/consultar.svg" title="Consultar Motivo de Aus�ncia" alt="Consultar Motivo de Aus�ncia" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_tp_ausencia_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_md_utl_adm_tp_ausencia='.$arrObjMdUtlAdmTpAusenciaDTO[$i]->getNumIdMdUtlAdmTpAusencia()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getDiretorioSvgGlobal().'/alterar.svg" title="Alterar Motivo de Aus�ncia" alt="Alterar Motivo de Aus�ncia" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir){
        $strId = $arrObjMdUtlAdmTpAusenciaDTO[$i]->getNumIdMdUtlAdmTpAusencia();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjMdUtlAdmTpAusenciaDTO[$i]->getStrNome());
      }

      if ($bolAcaoDesativar && $arrObjMdUtlAdmTpAusenciaDTO[$i]->getStrSinAtivo()=='S'){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getDiretorioSvgGlobal().'/desativar.svg" title="Desativar Motivo de Aus�ncia" alt="Desativar Motivo de Aus�ncia" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar && $arrObjMdUtlAdmTpAusenciaDTO[$i]->getStrSinAtivo()=='N'){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoReativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getDiretorioSvgGlobal().'/reativar.svg" title="Reativar Motivo de Aus�ncia" alt="Reativar Motivo de Aus�ncia" class="infraImg" /></a>&nbsp;';
      }


      if ($bolAcaoExcluir){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getDiretorioSvgGlobal().'/excluir.svg" title="Excluir Motivo de Aus�ncia" alt="Excluir Motivo de Aus�ncia" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'md_utl_adm_tp_ausencia_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }else{
    $arrComandos[] = '<button type="button" accesskey="C" id="btnFechar" value="Fechar" onclick="fechar();" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
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
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>

<form id="frmMdUtlAdmTpAusenciaLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  
  <?php PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos); ?>
  
  <div class="row">
    <div class="col-sm-8 col-md-6 col-lg-6 mb-3">
      <label id="lblTpAusencia" for="txtTpAusencia" accesskey="S" class="infraLabelOpcional">
        Motivo de Aus�ncia:
      </label>
      <input type="text" id="txtTpAusencia" name="txtTpAusencia" class="infraText form-control"
              maxlength="100" value="<?=$tpAusencia?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
    </div>
  
    <div class="col-sm-8 col-md-6 col-lg-6">
      <label id="lblDescricao" for="txtDescricaoTpControle" accesskey="S" class="infraLabelOpcional">
        Descri��o:
      </label>
      <input type="text" id="txtDescricao" name="txtDescricao" class="infraText form-control"
             value="<?=$txtDescricao?>" maxlength="100" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
    </div>
  </div> 

  <?php
    PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);  
    PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?php
require_once "md_utl_adm_tp_ausencia_lista_js.php";
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
