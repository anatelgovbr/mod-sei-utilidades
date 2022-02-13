<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/07/2018 - criado por jhon.cast
*
* Versão do Gerador de Código: 1.41.0
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
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
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
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'md_utl_adm_tp_ausencia_reativar':
      $strTitulo = 'Reativar Motivos de Ausência';
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
          PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        } 
        header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']. PaginaSEI::getInstance()->montarAncora($idTpAusencia)));
        die;
      } 
      break;


    case 'md_utl_adm_tp_ausencia_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Motivo de Ausência','Selecionar Motivos de Ausência');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='md_utl_adm_tp_ausencia_cadastrar'){
        if (isset($_GET['id_md_utl_adm_tp_ausencia'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_md_utl_adm_tp_ausencia']);
        }
      }
      break;

    case 'md_utl_adm_tp_ausencia_listar':
      $strTitulo = 'Motivo de Ausência';
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_tp_ausencia_reativar');
        break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
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
      $strSumarioTabela = 'Tabela de Motivos de Ausência.';
      $strCaptionTabela = 'Motivos de Ausência';
    }else{
      $strSumarioTabela = 'Tabela de Motivos de Ausência Inativos.';
      $strCaptionTabela = 'Motivos de Ausência Inativos';
    }

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%" style="display: none">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh" width="35%">'.PaginaSEI::getInstance()->getThOrdenacao($objMdUtlAdmTpAusenciaDTO,'Motivo de Ausência','Nome',$arrObjMdUtlAdmTpAusenciaDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="50%">'.PaginaSEI::getInstance()->getThOrdenacao($objMdUtlAdmTpAusenciaDTO,'Descrição','Descricao',$arrObjMdUtlAdmTpAusenciaDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="15%">Ações</th>'."\n";
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
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_tp_ausencia_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_md_utl_adm_tp_ausencia='.$arrObjMdUtlAdmTpAusenciaDTO[$i]->getNumIdMdUtlAdmTpAusencia()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getDiretorioImagensGlobal().'/consultar.gif" title="Consultar Motivo de Ausência" alt="Consultar Motivo de Ausência" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_tp_ausencia_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_md_utl_adm_tp_ausencia='.$arrObjMdUtlAdmTpAusenciaDTO[$i]->getNumIdMdUtlAdmTpAusencia()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getDiretorioImagensGlobal().'/alterar.gif" title="Alterar Motivo de Ausência" alt="Alterar Motivo de Ausência" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir){
        $strId = $arrObjMdUtlAdmTpAusenciaDTO[$i]->getNumIdMdUtlAdmTpAusencia();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjMdUtlAdmTpAusenciaDTO[$i]->getStrNome());
      }

      if ($bolAcaoDesativar && $arrObjMdUtlAdmTpAusenciaDTO[$i]->getStrSinAtivo()=='S'){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getDiretorioImagensGlobal().'/desativar.gif" title="Desativar Motivo de Ausência" alt="Desativar Motivo de Ausência" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar && $arrObjMdUtlAdmTpAusenciaDTO[$i]->getStrSinAtivo()=='N'){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoReativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getDiretorioImagensGlobal().'/reativar.gif" title="Reativar Motivo de Ausência" alt="Reativar Motivo de Ausência" class="infraImg" /></a>&nbsp;';
      }


      if ($bolAcaoExcluir){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getDiretorioImagensGlobal().'/excluir.gif" title="Excluir Motivo de Ausência" alt="Excluir Motivo de Ausência" class="infraImg" /></a>&nbsp;';
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
?>
<?if(0){?><style><?}?>

  .bloco {
    position: relative;
    float: left;
  }

  .clear {
    clear: both;
  }


  
<?if(0){?></style><?}?>
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
<?if(0){?><script type="text/javascript"><?}?>

  var msg100Padrao = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_100)?>';

  function inicializar(){
  if ('<?=$_GET['acao']?>'=='md_utl_adm_tp_ausencia_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
  }else{
    document.getElementById('btnFechar').focus();
  }
  infraEfeitoTabelas(true);
}

function acaoDesativar(id,desc){
  if (confirm("Confirma desativação do Motivo de Ausência \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmMdUtlAdmTpAusenciaLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmMdUtlAdmTpAusenciaLista').submit();
  }
}



function acaoReativar(id,desc){
  if (confirm("Confirma reativação do Motivo de Ausência \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmMdUtlAdmTpAusenciaLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmMdUtlAdmTpAusenciaLista').submit();
  }
}

function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão do Motivo de Ausência \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmMdUtlAdmTpAusenciaLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmMdUtlAdmTpAusenciaLista').submit();
  }
}


function fechar(){
  location.href="<?= $strUrlFechar ?>";
}

<?if(0){?></script><?}?>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmMdUtlAdmTpAusenciaLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
     <?
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  ?>

  <div id="divInfraAreaDados" class="infraAreaDados">

    <div style="width: 27%;" class="bloco">
      <label id="lblTpAusencia" for="txtTpAusencia" accesskey="S" class="infraLabelOpcional">
        Motivo de Ausência:
      </label>

      <div class="clear"></div>

      <input type="text" id="txtTpAusencia" name="txtTpAusencia" class="infraText" size="30"
             value="<?=$tpAusencia?>" maxlength="100"
             tabindex="502"/>
    </div>
    <div style="width: 45%;" class="bloco">
      <label id="lblDescricao" for="txtDescricaoTpControle" accesskey="S"
             class="infraLabelOpcional">
        Descrição:
      </label>

      <div class="clear"></div>

      <input style="width: 68%" type="text" id="txtDescricao" name="txtDescricao" class="infraText"
             size="30"
             value="<?=$txtDescricao?>" maxlength="100"
             tabindex="502"/>
    </div>
  </div>

   <?
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  //PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
