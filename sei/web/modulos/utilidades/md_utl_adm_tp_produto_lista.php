<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 06/07/2018 - criado por jaqueline.cast
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

  PaginaSEI::getInstance()->prepararSelecao('md_utl_adm_tp_produto_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  PaginaSEI::getInstance()->salvarCamposPost(array('txtDescricao','txtTpJustificativa'));

  $idTpCtrl = array_key_exists('id_tipo_controle_utl', $_GET) ? $_GET['id_tipo_controle_utl'] : $_POST['hdnIdTpCtrlUtl'];
  $objTpControleUtlRN    = new MdUtlAdmTpCtrlDesempRN();
  $objTipoControleUtlDTO = $objTpControleUtlRN->buscarObjTpControlePorId($idTpCtrl);
  $nomeTpCtrl = !is_null($objTipoControleUtlDTO) ? $objTipoControleUtlDTO->getStrNome() : '';

  switch($_GET['acao']){
    case 'md_utl_adm_tp_produto_excluir':
      try{

        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjMdUtlAdmTpProdutoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objMdUtlAdmTpProdutoDTO = new MdUtlAdmTpProdutoDTO();
          $objMdUtlAdmTpProdutoDTO->setNumIdMdUtlAdmTpProduto($arrStrIds[$i]);
          $arrObjMdUtlAdmTpProdutoDTO[] = $objMdUtlAdmTpProdutoDTO;
        }
        $objMdUtlAdmTpProdutoRN = new MdUtlAdmTpProdutoRN();
        $objMdUtlAdmTpProdutoRN->excluir($arrObjMdUtlAdmTpProdutoDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      }
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTpCtrl));
      die;


    case 'md_utl_adm_tp_produto_desativar':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjMdUtlAdmTpProdutoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objMdUtlAdmTpProdutoDTO = new MdUtlAdmTpProdutoDTO();
          $objMdUtlAdmTpProdutoDTO->setNumIdMdUtlAdmTpProduto($arrStrIds[$i]);
          $arrObjMdUtlAdmTpProdutoDTO[] = $objMdUtlAdmTpProdutoDTO;
        }
        $objMdUtlAdmTpProdutoRN = new MdUtlAdmTpProdutoRN();
        $objMdUtlAdmTpProdutoRN->desativar($arrObjMdUtlAdmTpProdutoDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      }
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTpCtrl));
      die;

    case 'md_utl_adm_tp_produto_reativar':
      $strTitulo = 'Reativar Tipo de Produto';
      if ($_GET['acao_confirmada']=='sim'){
        try{
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjMdUtlAdmTpProdutoDTO = array();
          for ($i=0;$i<count($arrStrIds);$i++){
            $objMdUtlAdmTpProdutoDTO = new MdUtlAdmTpProdutoDTO();
            $idTpProduto = $arrStrIds[$i];
            $objMdUtlAdmTpProdutoDTO->setNumIdMdUtlAdmTpProduto($arrStrIds[$i]);
            $arrObjMdUtlAdmTpProdutoDTO[] = $objMdUtlAdmTpProdutoDTO;
          }
          $objMdUtlAdmTpProdutoRN = new MdUtlAdmTpProdutoRN();
          $objMdUtlAdmTpProdutoRN->reativar($arrObjMdUtlAdmTpProdutoDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
        header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTpCtrl.PaginaSEI::getInstance()->montarAncora($idTpProduto)));
        die;
      }
      break;


    case 'md_utl_adm_tp_produto_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Tipo de Produto','Selecionar Tipo de Produto');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='md_utl_adm_tp_produto_cadastrar'){
        if (isset($_GET['id_md_utl_adm_tp_produto'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_md_utl_adm_tp_produto']);
        }
      }
      break;

    case 'md_utl_adm_tp_produto_listar':
      $strTitulo = 'Tipo de Produto - '.$nomeTpCtrl;
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_tp_produto_reativar');
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  $arrComandos[] = '<button type="submit" accesskey="P" id="btnPesquisar" value="Pesquisar" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';

  if ($_GET['acao'] == 'md_utl_adm_tp_produto_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  $objMdUtlAdmTpProdutoDTO = new MdUtlAdmTpProdutoDTO();

  if ($_GET['acao'] == 'md_utl_adm_tp_produto_listar' || $_GET['acao'] == 'md_utl_adm_tp_produto_selecionar'){
    $descricao  = $_POST['txtDescricao'];
    $tpProduto = $_POST['txtTpProduto'];

    $objMdUtlAdmTpProdutoDTO->setStrNome('%'.trim($tpProduto.'%'),InfraDTO::$OPER_LIKE);
    $objMdUtlAdmTpProdutoDTO->setStrDescricao('%'.trim($descricao.'%'),InfraDTO::$OPER_LIKE);

    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_tp_produto_cadastrar');
    if ($bolAcaoCadastrar){
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_tp_produto_cadastrar&id_tipo_controle_utl='.$idTpCtrl.'&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
    }
  }


  $objMdUtlAdmTpProdutoDTO->retNumIdMdUtlAdmTpProduto();
  $objMdUtlAdmTpProdutoDTO->retStrNome();
  $objMdUtlAdmTpProdutoDTO->retStrDescricao();
  $objMdUtlAdmTpProdutoDTO->retStrSinAtivo();
  $objMdUtlAdmTpProdutoDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTpCtrl);
  if($bolAcaoReativar) {
    $objMdUtlAdmTpProdutoDTO->setBolExclusaoLogica(false);
  }

  PaginaSEI::getInstance()->prepararOrdenacao($objMdUtlAdmTpProdutoDTO, 'Nome', InfraDTO::$TIPO_ORDENACAO_ASC);
  PaginaSEI::getInstance()->prepararPaginacao($objMdUtlAdmTpProdutoDTO, 200);

  $objMdUtlAdmTpProdutoRN = new MdUtlAdmTpProdutoRN();

  $arrObjMdUtlAdmTpProdutoDTO = $objMdUtlAdmTpProdutoRN->listar($objMdUtlAdmTpProdutoDTO);

  PaginaSEI::getInstance()->processarPaginacao($objMdUtlAdmTpProdutoDTO);
  $numRegistros = count($arrObjMdUtlAdmTpProdutoDTO);

  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='md_utl_adm_tp_produto_selecionar'){
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_tp_produto_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_tp_produto_alterar');
      $bolAcaoImprimir = false;
      //$bolAcaoGerarPlanilha = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolCheck = true;
    }else if ($_GET['acao']=='md_utl_adm_tp_produto_reativar'){
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_tp_produto_reativar');
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_tp_produto_consultar');
      $bolAcaoAlterar = false;
      $bolAcaoImprimir = true;
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_tp_produto_excluir');
      $bolAcaoDesativar = false;
    }else{
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_tp_produto_reativar');
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_tp_produto_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_tp_produto_alterar');
      $bolAcaoImprimir = true;
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_tp_produto_excluir');
      $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_tp_produto_desativar');
    }


    if ($bolAcaoDesativar){
      $bolCheck = true;
      $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_tp_produto_desativar&id_tipo_controle_utl='.$idTpCtrl.'&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoReativar){
      $bolCheck = true;
      $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_tp_produto_reativar&id_tipo_controle_utl='.$idTpCtrl.'&acao_origem='.$_GET['acao'].'&acao_confirmada=sim');
    }


    if ($bolAcaoExcluir){
      $bolCheck = true;
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_tp_produto_excluir&id_tipo_controle_utl='.$idTpCtrl.'&acao_origem='.$_GET['acao']);
    }


    $strResultado = '';

    if ($_GET['acao']!='md_utl_adm_tp_produto_reativar'){
      $strSumarioTabela = 'Tabela de Tipo de Produto.';
      $strCaptionTabela = 'Tipo de Produto';
    }else{
      $strSumarioTabela = 'Tabela de Tipo de Produto Inativos.';
      $strCaptionTabela = 'Tipo de Produto Inativos';
    }

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%" style="display: none">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh" width="35%">'.PaginaSEI::getInstance()->getThOrdenacao($objMdUtlAdmTpProdutoDTO,'Tipo de Produto','Nome',$arrObjMdUtlAdmTpProdutoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="50%">'.PaginaSEI::getInstance()->getThOrdenacao($objMdUtlAdmTpProdutoDTO,'Descrição','Descricao',$arrObjMdUtlAdmTpProdutoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="15%">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      if($arrObjMdUtlAdmTpProdutoDTO[$i]->getStrSinAtivo()=='N')
      {
        $strCssTr = '<tr class="trVermelha">';
      }
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top" style="display: none">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjMdUtlAdmTpProdutoDTO[$i]->getNumIdMdUtlAdmTpProduto(),$arrObjMdUtlAdmTpProdutoDTO[$i]->getNumIdMdUtlAdmTpProduto()).'</td>';
      }
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjMdUtlAdmTpProdutoDTO[$i]->getStrNome()).'</td>';
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjMdUtlAdmTpProdutoDTO[$i]->getStrDescricao()).'</td>';
      $strResultado .= '<td align="center">';

      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjMdUtlAdmTpProdutoDTO[$i]->getNumIdMdUtlAdmTpProduto());

      if ($bolAcaoConsultar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_tp_produto_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_tipo_controle_utl='.$idTpCtrl.'&id_md_utl_adm_tp_produto='.$arrObjMdUtlAdmTpProdutoDTO[$i]->getNumIdMdUtlAdmTpProduto()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/consultar.svg" title="Consultar Tipo de Produto" alt="Consultar Tipo de Produto" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_tp_produto_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_tipo_controle_utl='.$idTpCtrl.'&id_md_utl_adm_tp_produto='.$arrObjMdUtlAdmTpProdutoDTO[$i]->getNumIdMdUtlAdmTpProduto()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/alterar.svg" title="Alterar Tipo de  Produto" alt="Alterar Tipo de Produto" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir){
        $strId = $arrObjMdUtlAdmTpProdutoDTO[$i]->getNumIdMdUtlAdmTpProduto();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjMdUtlAdmTpProdutoDTO[$i]->getStrNome());
      }

      if ($bolAcaoDesativar && $arrObjMdUtlAdmTpProdutoDTO[$i]->getStrSinAtivo()=='S'){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/desativar.svg" title="Desativar Tipo de Produto" alt="Desativar Tipo de Produto" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar && $arrObjMdUtlAdmTpProdutoDTO[$i]->getStrSinAtivo()=='N'){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoReativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/reativar.svg" title="Reativar Tipo de Produto" alt="Reativar Tipo de Produto" class="infraImg" /></a>&nbsp;';
      }


      if ($bolAcaoExcluir){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/excluir.svg" title="Excluir Tipo de Produto" alt="Excluir Tipo de Produto" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'md_utl_adm_tp_produto_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }else{
    $arrComandos[] = '<button type="button" accesskey="C" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($idTpCtrl)).'\'" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
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
<form id="frmMdUtlAdmTpProdutoLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  
  <?php PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos); ?>

  <div id="divInfraAreaDados" class="infraAreaDados">
    <div class="row">
        <div class="col-sm-6 col-md-6 col-lg-6 col-xl-5 mb-2">
          <label id="lblTpProduto" for="txtTpProduto" accesskey="S" class="infraLabelOpcional">Tipo de Produto:</label>
          <input type="text" id="txtTpProduto" name="txtTpProduto" class="infraText form-control" 
                value="<?=$tpProduto?>" maxlength="100"
                tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
        </div>

        <div class="col-sm-6 col-md-6 col-lg-6 col-xl-5">
          <label id="lblDescricao" for="txtDescricao" accesskey="S" class="infraLabelOpcional">Descrição:</label>
          <input type="text" id="txtDescricao" name="txtDescricao" class="infraText form-control"
                size="30"
                value="<?=$descricao?>" maxlength="100"
                tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
        </div>
    </div>      
  </div>

  <input type="hidden" name="hdnIdTpCtrlUtl" id="hdnIdTpCtrlUtl" value="<?php echo $idTpCtrl ?>"/>

  <?php
    PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
    //PaginaSEI::getInstance()->montarAreaDebug();
    PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>

<script type="text/javascript">
  var msg70 = '<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_70); ?>';
  var msg72 = '<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_72); ?>';
  var msg74 = '<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_74); ?>';

  function inicializar(){
    if ('<?=$_GET['acao']?>'=='md_utl_adm_tp_produto_selecionar'){
      infraReceberSelecao();
      document.getElementById('btnFecharSelecao').focus();
    }else{
      document.getElementById('btnFechar').focus();
    }
    infraEfeitoTabelas(true);
  }

  <? if ($bolAcaoDesativar){ ?>
  function acaoDesativar(id,desc){
      var msg = setMensagemPersonalizada(msg70, ['Tipo de Produto', desc]);
    if (confirm(msg)){
      document.getElementById('hdnInfraItemId').value=id;
      document.getElementById('frmMdUtlAdmTpProdutoLista').action='<?=$strLinkDesativar?>';
      document.getElementById('frmMdUtlAdmTpProdutoLista').submit();
    }
  }

  <? } ?>

  <? if ($bolAcaoReativar){ ?>
  function acaoReativar(id,desc){
      var msg = setMensagemPersonalizada(msg72, ['Tipo de Produto', desc]);
    if (confirm(msg)){
      document.getElementById('hdnInfraItemId').value=id;
      document.getElementById('frmMdUtlAdmTpProdutoLista').action='<?=$strLinkReativar?>';
      document.getElementById('frmMdUtlAdmTpProdutoLista').submit();
    }
  }
  <? } ?>

  <? if ($bolAcaoExcluir){ ?>
  function acaoExcluir(id,desc){
    var msg = setMensagemPersonalizada(msg74, ['Tipo de Produto', desc]);
    if (confirm(msg)){
      document.getElementById('hdnInfraItemId').value=id;
      document.getElementById('frmMdUtlAdmTpProdutoLista').action='<?=$strLinkExcluir?>';
      document.getElementById('frmMdUtlAdmTpProdutoLista').submit();
    }
  }

<? } ?>
</script>

<?php
require_once 'md_utl_geral_js.php';
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
