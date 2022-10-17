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

  PaginaSEI::getInstance()->prepararSelecao('md_utl_adm_tp_just_revisao_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  PaginaSEI::getInstance()->salvarCamposPost(array('txtDescricao','txtTpJustificativa'));

  $idTpCtrl = array_key_exists('id_tipo_controle_utl', $_GET) ? $_GET['id_tipo_controle_utl'] : $_POST['hdnIdTpCtrlUtl'];

  $objTpControleUtlRN    = new MdUtlAdmTpCtrlDesempRN();
  $objTipoControleUtlDTO = $objTpControleUtlRN->buscarObjTpControlePorId($idTpCtrl);

  switch($_GET['acao']){
    case 'md_utl_adm_tp_just_revisao_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjMdUtlAdmTpJustRevisaoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objMdUtlAdmTpJustRevisaoDTO = new MdUtlAdmTpJustRevisaoDTO();
          $objMdUtlAdmTpJustRevisaoDTO->setNumIdMdUtlAdmTpJustRevisao($arrStrIds[$i]);
          $arrObjMdUtlAdmTpJustRevisaoDTO[] = $objMdUtlAdmTpJustRevisaoDTO;
        }

        $objMdUtlAdmRelRevisaoRN = new MdUtlRelRevisTrgAnlsRN();
        $objMdUtlAdmRelRevisaoDTO = new MdUtlRelRevisTrgAnlsDTO();
        $objMdUtlAdmRelRevisaoDTO->setNumIdMdUtlAdmTpJustRevisao($arrStrIds[0]);
        $objMdUtlAdmRelRevisaoDTO->retTodos();
        $isPossuiVinculo =  $objMdUtlAdmRelRevisaoRN->contar($objMdUtlAdmRelRevisaoDTO) > 0;

        if($isPossuiVinculo){
            $objInfraException = new InfraException();
            $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_33, array('excluir'));
            $objInfraException->lancarValidacao($msg);
        }else {
            $objMdUtlAdmTpJustRevisaoRN = new MdUtlAdmTpJustRevisaoRN();
            $objMdUtlAdmTpJustRevisaoRN->excluir($arrObjMdUtlAdmTpJustRevisaoDTO);
        }

        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      }
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTpCtrl));
      die;


    case 'md_utl_adm_tp_just_revisao_desativar':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjMdUtlAdmTpJustRevisaoDTO = array();
        $isPossuiVinculoAtivo           = false;

        for ($i=0;$i<count($arrStrIds);$i++){
          $objMdUtlAdmTpJustRevisaoDTO = new MdUtlAdmTpJustRevisaoDTO();
          $objMdUtlAdmTpJustRevisaoDTO->setNumIdMdUtlAdmTpJustRevisao($arrStrIds[$i]);
          $arrObjMdUtlAdmTpJustRevisaoDTO[] = $objMdUtlAdmTpJustRevisaoDTO;
        }
        $objMdUtlAdmTpJustRevisaoRN = new MdUtlAdmTpJustRevisaoRN();
        $objMdUtlAdmTpJustRevisaoRN->desativar($arrObjMdUtlAdmTpJustRevisaoDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      }
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTpCtrl));
      die;

    case 'md_utl_adm_tp_just_revisao_reativar':
      $strTitulo = 'Reativar Justificativa de Avaliação';
      if ($_GET['acao_confirmada']=='sim'){
        try{
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjMdUtlAdmTpJustRevisaoDTO = array();
          for ($i=0;$i<count($arrStrIds);$i++){
            $objMdUtlAdmTpJustRevisaoDTO = new MdUtlAdmTpJustRevisaoDTO();
            $idTpJustificativa = $arrStrIds[$i];
            $objMdUtlAdmTpJustRevisaoDTO->setNumIdMdUtlAdmTpJustRevisao($arrStrIds[$i]);
            $arrObjMdUtlAdmTpJustRevisaoDTO[] = $objMdUtlAdmTpJustRevisaoDTO;
          }
          $objMdUtlAdmTpJustRevisaoRN = new MdUtlAdmTpJustRevisaoRN();
          $objMdUtlAdmTpJustRevisaoRN->reativar($arrObjMdUtlAdmTpJustRevisaoDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
        header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTpCtrl.PaginaSEI::getInstance()->montarAncora($idTpJustificativa)));
        die;
      }
      break;


    case 'md_utl_adm_tp_just_revisao_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Justificativa de Avaliação de Avaliação','Selecionar Justificativa de Avaliação de Avaliação');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='md_utl_adm_tp_just_revisao_cadastrar'){
        if (isset($_GET['id_md_utl_adm_tp_justificativa'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_md_utl_adm_tp_justificativa']);
        }
      }
      break;

    case 'md_utl_adm_tp_just_revisao_listar':
      $nomeTpCtrl = !is_null($objTipoControleUtlDTO) ? $objTipoControleUtlDTO->getStrNome() : '';
      $strTitulo  = 'Justificativa de Avaliação - '.$nomeTpCtrl;
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_tp_just_revisao_reativar');
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  $arrComandos[] = '<button type="submit" accesskey="P" id="btnPesquisar" value="Pesquisar" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';

  if ($_GET['acao'] == 'md_utl_adm_tp_just_revisao_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  $objMdUtlAdmTpJustRevisaoDTO = new MdUtlAdmTpJustRevisaoDTO();

  if ($_GET['acao'] == 'md_utl_adm_tp_just_revisao_listar' || $_GET['acao'] == 'md_utl_adm_tp_just_revisao_selecionar'){
    $descricao  = $_POST['txtDescricao'];
    $tpJustificativa = $_POST['txtTpJustificativa'];

    $objMdUtlAdmTpJustRevisaoDTO->setStrNome('%'.trim($tpJustificativa.'%'),InfraDTO::$OPER_LIKE);
    $objMdUtlAdmTpJustRevisaoDTO->setStrDescricao('%'.trim($descricao.'%'),InfraDTO::$OPER_LIKE);

    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_tp_just_revisao_cadastrar');
    //if ($bolAcaoCadastrar){
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_tp_just_revisao_cadastrar&id_tipo_controle_utl='.$idTpCtrl.'&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
    //}
  }


  $objMdUtlAdmTpJustRevisaoDTO->retNumIdMdUtlAdmTpJustRevisao();
  $objMdUtlAdmTpJustRevisaoDTO->retStrNome();
  $objMdUtlAdmTpJustRevisaoDTO->retStrDescricao();
  $objMdUtlAdmTpJustRevisaoDTO->retStrSinAtivo();

  if($bolAcaoReativar) {
    $objMdUtlAdmTpJustRevisaoDTO->setBolExclusaoLogica(false);
  }
  $objMdUtlAdmTpJustRevisaoDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTpCtrl);
  
  PaginaSEI::getInstance()->prepararOrdenacao($objMdUtlAdmTpJustRevisaoDTO, 'Nome', InfraDTO::$TIPO_ORDENACAO_ASC);
  PaginaSEI::getInstance()->prepararPaginacao($objMdUtlAdmTpJustRevisaoDTO, 200);

  $objMdUtlAdmTpJustRevisaoRN = new MdUtlAdmTpJustRevisaoRN();


  $arrObjMdUtlAdmTpJustRevisaoDTO = $objMdUtlAdmTpJustRevisaoRN->listar($objMdUtlAdmTpJustRevisaoDTO);

  PaginaSEI::getInstance()->processarPaginacao($objMdUtlAdmTpJustRevisaoDTO);
  $numRegistros = count($arrObjMdUtlAdmTpJustRevisaoDTO);

  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='md_utl_adm_tp_just_revisao_selecionar'){
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_tp_just_revisao_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_tp_just_revisao_alterar');
      $bolAcaoImprimir = false;
      //$bolAcaoGerarPlanilha = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolCheck = true;
    }else if ($_GET['acao']=='md_utl_adm_tp_just_revisao_reativar'){
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_tp_just_revisao_reativar');
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_tp_just_revisao_consultar');
      $bolAcaoAlterar = false;
      $bolAcaoImprimir = true;
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_tp_just_revisao_excluir');
      $bolAcaoDesativar = false;
    }else{
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_tp_just_revisao_reativar');
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_tp_just_revisao_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_tp_just_revisao_alterar');
      $bolAcaoImprimir = true;
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_tp_just_revisao_excluir');
      $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_tp_just_revisao_desativar');
    }


    if ($bolAcaoDesativar){
      $bolCheck = true;
      $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_tp_just_revisao_desativar&id_tipo_controle_utl='.$idTpCtrl.'&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoReativar){
      $bolCheck = true;
      $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_tp_just_revisao_reativar&id_tipo_controle_utl='.$idTpCtrl.'&acao_origem='.$_GET['acao'].'&acao_confirmada=sim');
    }


    if ($bolAcaoExcluir){
      $bolCheck = true;
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_tp_just_revisao_excluir&id_tipo_controle_utl='.$idTpCtrl.'&acao_origem='.$_GET['acao']);
    }


    $strResultado = '';

    if ($_GET['acao']!='md_utl_adm_tp_just_revisao_reativar'){
      $strSumarioTabela = 'Tabela de Justificativa de Avaliação.';
      $strCaptionTabela = 'Justificativa de Avaliação';
    }else{
      $strSumarioTabela = 'Tabela de Justificativa de Avaliação Inativos.';
      $strCaptionTabela = 'Justificativa de Avaliação Inativos';
    }

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%" style="display: none">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh" width="35%">'.PaginaSEI::getInstance()->getThOrdenacao($objMdUtlAdmTpJustRevisaoDTO,'Justificativa','Nome',$arrObjMdUtlAdmTpJustRevisaoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="50%">'.PaginaSEI::getInstance()->getThOrdenacao($objMdUtlAdmTpJustRevisaoDTO,'Descrição','Descricao',$arrObjMdUtlAdmTpJustRevisaoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="15%">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      if($arrObjMdUtlAdmTpJustRevisaoDTO[$i]->getStrSinAtivo()=='N')
      {
        $strCssTr = '<tr class="trVermelha">';
      }
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top" style="display: none">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjMdUtlAdmTpJustRevisaoDTO[$i]->getNumIdMdUtlAdmTpJustRevisao(),$arrObjMdUtlAdmTpJustRevisaoDTO[$i]->getNumIdMdUtlAdmTpJustRevisao()).'</td>';
      }
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjMdUtlAdmTpJustRevisaoDTO[$i]->getStrNome()).'</td>';
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjMdUtlAdmTpJustRevisaoDTO[$i]->getStrDescricao()).'</td>';
      $strResultado .= '<td align="center">';

      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjMdUtlAdmTpJustRevisaoDTO[$i]->getNumIdMdUtlAdmTpJustRevisao());

      if ($bolAcaoConsultar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_tp_just_revisao_consultar&id_tipo_controle_utl='.$idTpCtrl.'&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_md_utl_adm_tp_justificativa='.$arrObjMdUtlAdmTpJustRevisaoDTO[$i]->getNumIdMdUtlAdmTpJustRevisao()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/consultar.svg" title="Consultar Justificativa de Avaliação" alt="Consultar Justificativa de Avaliação" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_tp_just_revisao_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_tipo_controle_utl='.$idTpCtrl.'&id_md_utl_adm_tp_justificativa='.$arrObjMdUtlAdmTpJustRevisaoDTO[$i]->getNumIdMdUtlAdmTpJustRevisao()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/alterar.svg" title="Alterar Justificativa de Avaliação" alt="Alterar Justificativa de Avaliação" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir){
        $strId = $arrObjMdUtlAdmTpJustRevisaoDTO[$i]->getNumIdMdUtlAdmTpJustRevisao();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjMdUtlAdmTpJustRevisaoDTO[$i]->getStrNome());
      }

      if ($bolAcaoDesativar && $arrObjMdUtlAdmTpJustRevisaoDTO[$i]->getStrSinAtivo()=='S'){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/desativar.svg" title="Desativar Justificativa de Avaliação" alt="Desativar Justificativa de Avaliação" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar && $arrObjMdUtlAdmTpJustRevisaoDTO[$i]->getStrSinAtivo()=='N'){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoReativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/reativar.svg" title="Reativar Justificativa de Avaliação" alt="Reativar Justificativa de Avaliação" class="infraImg" /></a>&nbsp;';
      }


      if ($bolAcaoExcluir){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/excluir.svg" title="Excluir Justificativa de Avaliação" alt="Excluir Justificativa de Avaliação" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'md_utl_adm_tp_just_revisao_selecionar'){
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
  <form id="frmMdUtlAdmTpJustRevisaoLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
    <?php PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos); ?>

    <div id="divInfraAreaDados" class="infraAreaDados">
      <div class="row">
        <div class="col-sm-6 col-md-6 col-lg-6 col-xl-5 mb-2">
          <label id="lblTpJustificativa" for="txtTpJustificativa" accesskey="S" class="infraLabelOpcional">
            Justificativa:
          </label>
          <input type="text" id="txtTpJustificativa" name="txtTpJustificativa" class="infraText form-control" value="<?=$tpJustificativa?>" 
                maxlength="100"
                tabindex="502"/>
        </div>

        <div class="col-sm-6 col-md-6 col-lg-6 col-xl-5">
          <label id="lblDescricao" for="txtDescricao" accesskey="S" class="infraLabelOpcional">
            Descrição:
          </label>
          <input type="text" id="txtDescricao" name="txtDescricao" class="infraText form-control" value="<?=$descricao?>" maxlength="100" tabindex="502"/>
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

<?php require_once 'md_utl_geral_js.php'; ?>

<script type="text/javascript">
    var msg71 = '<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_71); ?>';
    var msg73 = '<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_73); ?>';
    var msg75 = '<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_75); ?>';

  function inicializar(){
    if ('<?=$_GET['acao']?>'=='md_utl_adm_tp_just_revisao_selecionar'){
      infraReceberSelecao();
      document.getElementById('btnFecharSelecao').focus();
    }else{
      document.getElementById('btnFechar').focus();
    }
    infraEfeitoTabelas(true);
  }

  <? if ($bolAcaoDesativar){ ?>
  function acaoDesativar(id,desc){
    var msg = setMensagemPersonalizada(msg71, ['Justificativa de Avaliação', desc]);
    if (confirm(msg)){
      document.getElementById('hdnInfraItemId').value=id;
      document.getElementById('frmMdUtlAdmTpJustRevisaoLista').action='<?=$strLinkDesativar?>';
      document.getElementById('frmMdUtlAdmTpJustRevisaoLista').submit();
    }
  }

  <? } ?>

  <? if ($bolAcaoReativar){ ?>
  function acaoReativar(id,desc){
    var msg = setMensagemPersonalizada(msg73, ['Justificativa de Avaliação', desc]);
    if (confirm(msg)){
      document.getElementById('hdnInfraItemId').value=id;
      document.getElementById('frmMdUtlAdmTpJustRevisaoLista').action='<?=$strLinkReativar?>';
      document.getElementById('frmMdUtlAdmTpJustRevisaoLista').submit();
    }
  }

  <? } ?>

  <? if ($bolAcaoExcluir){ ?>
  function acaoExcluir(id,desc){
      var msg = setMensagemPersonalizada(msg75, ['Justificativa de Avaliação', desc]);
    if (confirm(msg)){
      document.getElementById('hdnInfraItemId').value=id;
      document.getElementById('frmMdUtlAdmTpJustRevisaoLista').action='<?=$strLinkExcluir?>';
      document.getElementById('frmMdUtlAdmTpJustRevisaoLista').submit();
    }
  }

  <? } ?>
</script>

<?php
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
