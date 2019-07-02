<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 25/09/2018 - criado por jhon.carvalho
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

  //Id tipo de controle
  $idTipoControle = array_key_exists('id_tipo_controle_utl', $_GET) ? $_GET['id_tipo_controle_utl'] : $_POST['hdnIdTipoControleUtl'];

  SessaoSEI::getInstance()->validarLink();

  PaginaSEI::getInstance()->prepararSelecao('md_utl_adm_just_prazo_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);


  switch($_GET['acao']){
    case 'md_utl_adm_just_prazo_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjMdUtlAdmJustPrazoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objMdUtlAdmJustPrazoDTO = new MdUtlAdmJustPrazoDTO();
          $objMdUtlAdmJustPrazoDTO->setNumIdMdUtlAdmJustPrazo($arrStrIds[$i]);
          $arrObjMdUtlAdmJustPrazoDTO[] = $objMdUtlAdmJustPrazoDTO;
        }
        $objMdUtlAdmJustPrazoRN = new MdUtlAdmJustPrazoRN();
        $objMdUtlAdmJustPrazoRN->excluir($arrObjMdUtlAdmJustPrazoDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTipoControle));
      die;


    case 'md_utl_adm_just_prazo_desativar':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjMdUtlAdmJustPrazoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objMdUtlAdmJustPrazoDTO = new MdUtlAdmJustPrazoDTO();
          $objMdUtlAdmJustPrazoDTO->setNumIdMdUtlAdmJustPrazo($arrStrIds[$i]);
          $arrObjMdUtlAdmJustPrazoDTO[] = $objMdUtlAdmJustPrazoDTO;
        }
        $objMdUtlAdmJustPrazoRN = new MdUtlAdmJustPrazoRN();
        $objMdUtlAdmJustPrazoRN->desativar($arrObjMdUtlAdmJustPrazoDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTipoControle));
      die;

    case 'md_utl_adm_just_prazo_reativar':
      $strTitulo = 'Reativar Justificativas de Dilação de Prazo';
      if ($_GET['acao_confirmada']=='sim'){
        try{
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjMdUtlAdmJustPrazoDTO = array();
          for ($i=0;$i<count($arrStrIds);$i++){
            $objMdUtlAdmJustPrazoDTO = new MdUtlAdmJustPrazoDTO();
            $objMdUtlAdmJustPrazoDTO->setNumIdMdUtlAdmJustPrazo($arrStrIds[$i]);
            $arrObjMdUtlAdmJustPrazoDTO[] = $objMdUtlAdmJustPrazoDTO;
          }
          $objMdUtlAdmJustPrazoRN = new MdUtlAdmJustPrazoRN();
          $objMdUtlAdmJustPrazoRN->reativar($arrObjMdUtlAdmJustPrazoDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        } 
        header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTipoControle));
        die;
      } 
      break;


    case 'md_utl_adm_just_prazo_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Justificativa de Dilação de Prazo','Selecionar Justificativas de Dilação de Prazo');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='md_utl_adm_just_prazo_cadastrar'){
        if (isset($_GET['id_md_utl_adm_just_prazo'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_md_utl_adm_just_prazo']);
        }
      }
      break;

    case 'md_utl_adm_just_prazo_listar':
      $strTitulo = 'Justificativa de Dilação de Prazo';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  if ($_GET['acao'] == 'md_utl_adm_just_prazo_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  if ($_GET['acao'] == 'md_utl_adm_just_prazo_listar' || $_GET['acao'] == 'md_utl_adm_just_prazo_selecionar'){
    $arrComandos[] = '<button type="submit" accesskey="P" id="btnPesquisar" value="Pesquisar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';
    $arrComandos[] = '<button type="button" accesskey="N" id="btnNova" value="Novo" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_just_prazo_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_tipo_controle_utl='.$idTipoControle).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
  }

  $objMdUtlAdmJustPrazoDTO = new MdUtlAdmJustPrazoDTO();
  $objMdUtlAdmJustPrazoDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTipoControle);

  $objMdUtlAdmJustPrazoDTO->retNumIdMdUtlAdmJustPrazo();
  $objMdUtlAdmJustPrazoDTO->retStrNome();
  $objMdUtlAdmJustPrazoDTO->retStrDescricao();
  $objMdUtlAdmJustPrazoDTO->retStrSinAtivo();

  if($_POST['txtNome'] != ''){
    $objMdUtlAdmJustPrazoDTO->setStrNome('%'.trim($_POST['txtNome'].'%'),InfraDTO::$OPER_LIKE);
  }

  if($_POST['txtDescricao'] != ''){
    $objMdUtlAdmJustPrazoDTO->setStrDescricao('%'.trim($_POST['txtDescricao'].'%'),InfraDTO::$OPER_LIKE);
  }

  $objMdUtlAdmJustPrazoDTO->setBolExclusaoLogica(false);

  if ($_GET['acao'] == 'md_utl_adm_just_prazo_reativar'){
    //Lista somente inativos
    $objMdUtlAdmJustPrazoDTO->setStrSinAtivo('N');
  }

  PaginaSEI::getInstance()->prepararOrdenacao($objMdUtlAdmJustPrazoDTO, 'Nome', InfraDTO::$TIPO_ORDENACAO_ASC);
  PaginaSEI::getInstance()->prepararPaginacao($objMdUtlAdmJustPrazoDTO, 200);

  $objMdUtlAdmJustPrazoRN = new MdUtlAdmJustPrazoRN();
  $arrObjMdUtlAdmJustPrazoDTO = $objMdUtlAdmJustPrazoRN->listar($objMdUtlAdmJustPrazoDTO);

  PaginaSEI::getInstance()->processarPaginacao($objMdUtlAdmJustPrazoDTO);
  $numRegistros = count($arrObjMdUtlAdmJustPrazoDTO);

  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='md_utl_adm_just_prazo_selecionar'){
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_just_prazo_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_just_prazo_alterar');
      $bolAcaoImprimir = false;
      //$bolAcaoGerarPlanilha = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolCheck = true;
    }else {
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_just_prazo_consultar');
      $bolAcaoAlterar   = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_just_prazo_alterar');
      $bolAcaoExcluir   = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_just_prazo_excluir');
      $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_just_prazo_desativar');
      $bolAcaoReativar  = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_just_prazo_reativar');
    }
      $bolCheck = true;
      $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_just_prazo_desativar&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTipoControle);
      $strLinkReativar  = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_just_prazo_reativar&acao_origem='.$_GET['acao'].'&acao_confirmada=sim'.'&id_tipo_controle_utl='.$idTipoControle);
      $strLinkExcluir   = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_just_prazo_excluir&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTipoControle);

      $strResultado = '';

    if ($_GET['acao']!='md_utl_adm_just_prazo_reativar'){
      $strSumarioTabela = 'Tabela de Justificativas de Dilação de Prazo.';
      $strCaptionTabela = 'Justificativas de Dilação de Prazo';
    }else{
      $strSumarioTabela = 'Tabela de Justificativas de Dilação de Prazo Inativas.';
      $strCaptionTabela = 'Justificativas de Dilação de Prazo Inativas';
    }

    $displayNone = 'style="display:none"';
    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%" '.$displayNone.'>'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh" >'.PaginaSEI::getInstance()->getThOrdenacao($objMdUtlAdmJustPrazoDTO,'Justificativa','Nome',$arrObjMdUtlAdmJustPrazoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="60%">'.PaginaSEI::getInstance()->getThOrdenacao($objMdUtlAdmJustPrazoDTO,'Descrição','Descricao',$arrObjMdUtlAdmJustPrazoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="15%">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strCssTr = ($arrObjMdUtlAdmJustPrazoDTO[$i]->getStrSinAtivo()=='N') ? '<tr class="trVermelha">': $strCssTr;
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top" '.$displayNone.'>'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjMdUtlAdmJustPrazoDTO[$i]->getNumIdMdUtlAdmJustPrazo(),$arrObjMdUtlAdmJustPrazoDTO[$i]->getNumIdMdUtlAdmJustPrazo()).'</td>';
      }
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjMdUtlAdmJustPrazoDTO[$i]->getStrNome()).'</td>';
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjMdUtlAdmJustPrazoDTO[$i]->getStrDescricao()).'</td>';
      $strResultado .= '<td align="center">';

      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjMdUtlAdmJustPrazoDTO[$i]->getNumIdMdUtlAdmJustPrazo());

      if ($bolAcaoConsultar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_just_prazo_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_md_utl_adm_just_prazo='.$arrObjMdUtlAdmJustPrazoDTO[$i]->getNumIdMdUtlAdmJustPrazo().'&id_tipo_controle_utl='.$idTipoControle).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getDiretorioImagensGlobal().'/consultar.gif" title="Consultar Justificativa de Dilação de Prazo" alt="Consultar Justificativa de Dilação de Prazo" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_just_prazo_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_md_utl_adm_just_prazo='.$arrObjMdUtlAdmJustPrazoDTO[$i]->getNumIdMdUtlAdmJustPrazo().'&id_tipo_controle_utl='.$idTipoControle).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getDiretorioImagensGlobal().'/alterar.gif" title="Alterar Justificativa de Dilação de Prazo" alt="Alterar Justificativa de Dilação de Prazo" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir){
        $strId = $arrObjMdUtlAdmJustPrazoDTO[$i]->getNumIdMdUtlAdmJustPrazo();
        $strNome = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjMdUtlAdmJustPrazoDTO[$i]->getStrNome());
      }

      if ($arrObjMdUtlAdmJustPrazoDTO[$i]->getStrSinAtivo()=='S'){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoDesativar(\''.$strId.'\',\''.$strNome.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getDiretorioImagensGlobal().'/desativar.gif" title="Desativar Justificativa de Dilação de Prazo" alt="Desativar Justificativa de Dilação de Prazo" class="infraImg" /></a>&nbsp;';
      }

      if ($arrObjMdUtlAdmJustPrazoDTO[$i]->getStrSinAtivo()=='N'){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoReativar(\''.$strId.'\',\''.$strNome.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getDiretorioImagensGlobal().'/reativar.gif" title="Reativar Justificativa de Dilação de Prazo" alt="Reativar Justificativa de Dilação de Prazo" class="infraImg" /></a>&nbsp;';
      }


      if ($bolAcaoExcluir){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoExcluir(\''.$strId.'\',\''.$strNome.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getDiretorioImagensGlobal().'/excluir.gif" title="Excluir Justificativa de Dilação de Prazo" alt="Excluir Justificativa de Dilação de Prazo" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'md_utl_adm_just_prazo_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="c" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
  }else{
    $arrComandos[] = '<button type="button" accesskey="c" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTipoControle.PaginaSEI::getInstance()->montarAncora($idTipoControle)).'\'" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
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

    #lblNome{
        top: 0%;
        position: absolute;
    }
    #txtNome{
        top: 20%;
        position: absolute;
        width: 25%;
    }
    #lblDescricao{

        top: 0%;
        position: absolute;
        margin-left: 27%;

    }
    #txtDescricao{

        top: 20%;
        position: absolute;
        margin-left: 27%;
        width: 30%;
    }
<?if(0){?></style><?}?>
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
require_once 'md_utl_geral_js.php';
?>
<?if(0){?><script type="text/javascript"><?}?>
    var msg71 = '<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_71); ?>';
    var msg73 = '<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_73); ?>';
    var msg75 = '<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_75); ?>';

function inicializar(){
  if ('<?=$_GET['acao']?>'=='md_utl_adm_just_prazo_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
  }else{
    document.getElementById('btnFechar').focus();
  }
  infraEfeitoTabelas(true);
}

<? if ($bolAcaoDesativar){ ?>
function acaoDesativar(id,desc){
  var msg = setMensagemPersonalizada(msg71, ['Justificativa de Dilação de Prazo', desc]);
  if (confirm(msg)){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmMdUtlAdmJustPrazoLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmMdUtlAdmJustPrazoLista').submit();
  }
}

<? } ?>

<? if ($bolAcaoReativar){ ?>
function acaoReativar(id,desc){
    var msg = setMensagemPersonalizada(msg73, ['Justificativa de Dilação de Prazo', desc]);
  if (confirm(msg)){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmMdUtlAdmJustPrazoLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmMdUtlAdmJustPrazoLista').submit();
  }
}

<? } ?>

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  var msg = setMensagemPersonalizada(msg75, ['Justificativa de Dilação de Prazo', desc]);
  if (confirm(msg)){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmMdUtlAdmJustPrazoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmMdUtlAdmJustPrazoLista').submit();
  }
}

<? } ?>

<?if(0){?></script><?}?>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmMdUtlAdmJustPrazoLista" method="POST" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->abrirAreaDados('8em');
  ?>
    <label id="lblNome" for="txtNome" accesskey="" class="infraLabelOpcional">Justificativa:</label>
    <input type="text" id="txtNome" name="txtNome" class="infraText" value="<?=$_POST['txtNome']?>" onkeypress="return infraMascaraTexto(this,event,100);" maxlength="100" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

    <label id="lblDescricao" for="txtDescricao" accesskey="" class="infraLabelOpcional">Descrição:</label>
    <input type="text" id="txtDescricao" name="txtDescricao" class="infraText" value="<?=$_POST['txtDescricao']?>" onkeypress="return infraMascaraTexto(this,event,100);" maxlength="100" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

    <?
  PaginaSEI::getInstance()->fecharAreaDados();
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  //PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>

    <input type="hidden" id="hdnIdTipoControleUtl" name="hdnIdTipoControleUtl"  value="<?=$idTipoControle?>"/>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
