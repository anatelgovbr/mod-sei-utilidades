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

  PaginaSEI::getInstance()->prepararSelecao('md_utl_adm_tp_revisao_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  PaginaSEI::getInstance()->salvarCamposPost(array('txtDescricao','txtTpJustificativa'));

  $idTpCtrl = array_key_exists('id_tipo_controle_utl', $_GET) ? $_GET['id_tipo_controle_utl'] : $_POST['hdnIdTpCtrlUtl'];
  $objTpControleUtlRN    = new MdUtlAdmTpCtrlDesempRN();
  $objTipoControleUtlDTO = $objTpControleUtlRN->buscarObjTpControlePorId($idTpCtrl);

  switch($_GET['acao']){
    case 'md_utl_adm_tp_revisao_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjMdUtlAdmTpRevisaoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objMdUtlAdmTpRevisaoDTO = new MdUtlAdmTpRevisaoDTO();
          $objMdUtlAdmTpRevisaoDTO->setNumIdMdUtlAdmTpRevisao($arrStrIds[$i]);
          $arrObjMdUtlAdmTpRevisaoDTO[] = $objMdUtlAdmTpRevisaoDTO;
        }

          $objMdUtlAdmRelRevisaoRN = new MdUtlRelRevisTrgAnlsRN();
          $objMdUtlAdmRelRevisaoDTO = new MdUtlRelRevisTrgAnlsDTO();
          $objMdUtlAdmRelRevisaoDTO->setNumIdMdUtlAdmTpRevisao($arrStrIds[0]);
          $objMdUtlAdmRelRevisaoDTO->retTodos();
          $isPossuiVinculo =  $objMdUtlAdmRelRevisaoRN->contar($objMdUtlAdmRelRevisaoDTO) > 0;

          if($isPossuiVinculo){
              $objInfraException = new InfraException();
              $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_17, array('excluir'));
              $objInfraException->lancarValidacao($msg);
          }else{
              $objMdUtlAdmTpRevisaoRN = new MdUtlAdmTpRevisaoRN();
              $objMdUtlAdmTpRevisaoRN->excluir($arrObjMdUtlAdmTpRevisaoDTO);
          }

      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      }
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTpCtrl));
      die;


    case 'md_utl_adm_tp_revisao_desativar':
        try {
            $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
            $arrObjMdUtlAdmTpRevisaoDTO = array();
            for ($i = 0; $i < count($arrStrIds); $i++) {
                $objMdUtlAdmTpRevisaoDTO = new MdUtlAdmTpRevisaoDTO();
                $objMdUtlAdmTpRevisaoDTO->setNumIdMdUtlAdmTpRevisao($arrStrIds[$i]);
                $arrObjMdUtlAdmTpRevisaoDTO[] = $objMdUtlAdmTpRevisaoDTO;
            }

            $isPossuiVinculoAtivo     = true;
            $objMdUtlAdmRelRevisaoRN  = new MdUtlRelRevisTrgAnlsRN();
            $objMdUtlAdmRelRevisaoDTO = new MdUtlRelRevisTrgAnlsDTO();
            $objMdUtlAdmRelRevisaoDTO->setNumIdMdUtlAdmTpRevisao($arrStrIds[0]);
            $objMdUtlAdmRelRevisaoDTO->retTodos();
            $isPossuiVinculo = $objMdUtlAdmRelRevisaoRN->contar($objMdUtlAdmRelRevisaoDTO) > 0;

            if ($isPossuiVinculo) {
                $objRelRevisaoDTO = $objMdUtlAdmRelRevisaoRN->listar($objMdUtlAdmRelRevisaoDTO);
                $idsRevisao = InfraArray::converterArrInfraDTO($objRelRevisaoDTO, 'IdMdUtlRevisao');

                if(count($idsRevisao) > 0) {
                    $objMdUtlControleDsmpDTO = new MdUtlControleDsmpDTO();
                    $objMdUtlControleDsmpDTO->setNumIdMdUtlRevisao($idsRevisao, InfraDTO::$OPER_IN);
                    $objMdUtlControleDsmpDTO->retTodos();
                    $objMdUtlControleDsmpRN = new MdUtlControleDsmpRN();
                    $isPossuiVinculoAtivo = $objMdUtlControleDsmpRN->contar($objMdUtlControleDsmpDTO) > 0;
                }else{
                    $isPossuiVinculoAtivo = false;
                }
            }else{
                $isPossuiVinculoAtivo = false;
            }

            if ($isPossuiVinculoAtivo) {
                $objInfraException = new InfraException();
                $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_17, array('desativar'));
                $objInfraException->lancarValidacao($msg);
            } else {
                $objMdUtlAdmTpRevisaoRN = new MdUtlAdmTpRevisaoRN();
                $objMdUtlAdmTpRevisaoRN->desativar($arrObjMdUtlAdmTpRevisaoDTO);
            }


        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      }
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTpCtrl));
      die;

    case 'md_utl_adm_tp_revisao_reativar':
      $strTitulo = 'Reativar Tipo de Revisão';
      if ($_GET['acao_confirmada']=='sim'){
        try{
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjMdUtlAdmTpRevisaoDTO = array();
          for ($i=0;$i<count($arrStrIds);$i++){
            $objMdUtlAdmTpRevisaoDTO = new MdUtlAdmTpRevisaoDTO();
            $idTpRevisao = $arrStrIds[$i];
            $objMdUtlAdmTpRevisaoDTO->setNumIdMdUtlAdmTpRevisao($arrStrIds[$i]);
            $arrObjMdUtlAdmTpRevisaoDTO[] = $objMdUtlAdmTpRevisaoDTO;
          }
          $objMdUtlAdmTpRevisaoRN = new MdUtlAdmTpRevisaoRN();
          $objMdUtlAdmTpRevisaoRN->reativar($arrObjMdUtlAdmTpRevisaoDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
        header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTpCtrl.PaginaSEI::getInstance()->montarAncora($idTpRevisao)));
        die;
      }
      break;

    case 'md_utl_adm_tp_revisao_listar':
      $nomeTpCtrl = !is_null($objTipoControleUtlDTO) ? $objTipoControleUtlDTO->getStrNome() : '';
      $strTitulo  = 'Resultado da Revisão - '.$nomeTpCtrl;
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_tp_revisao_reativar');
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  $arrComandos[] = '<button type="submit" accesskey="P" id="btnPesquisar" value="Pesquisar" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';

  if ($_GET['acao'] == 'md_utl_adm_tp_revisao_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  $objMdUtlAdmTpRevisaoDTO = new MdUtlAdmTpRevisaoDTO();

  if ($_GET['acao'] == 'md_utl_adm_tp_revisao_listar' || $_GET['acao'] == 'md_utl_adm_tp_revisao_selecionar'){
    $descricao  = $_POST['txtDescricao'];
    $tpRevisao = $_POST['txtTpRevisao'];

    $objMdUtlAdmTpRevisaoDTO->setStrNome('%'.trim($tpRevisao.'%'),InfraDTO::$OPER_LIKE);
    $objMdUtlAdmTpRevisaoDTO->setStrDescricao('%'.trim($descricao.'%'),InfraDTO::$OPER_LIKE);

    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_tp_revisao_cadastrar');
    if ($bolAcaoCadastrar){
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_tp_revisao_cadastrar&id_tipo_controle_utl='.$idTpCtrl.'&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
    }
  }


  $objMdUtlAdmTpRevisaoDTO->retNumIdMdUtlAdmTpRevisao();
  $objMdUtlAdmTpRevisaoDTO->retStrNome();
  $objMdUtlAdmTpRevisaoDTO->retStrDescricao();
  $objMdUtlAdmTpRevisaoDTO->retStrSinAtivo();
  $objMdUtlAdmTpRevisaoDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTpCtrl);

  if($bolAcaoReativar) {
    $objMdUtlAdmTpRevisaoDTO->setBolExclusaoLogica(false);
  }

  PaginaSEI::getInstance()->prepararOrdenacao($objMdUtlAdmTpRevisaoDTO, 'Nome', InfraDTO::$TIPO_ORDENACAO_ASC);
  PaginaSEI::getInstance()->prepararPaginacao($objMdUtlAdmTpRevisaoDTO, 200);

  $objMdUtlAdmTpRevisaoRN = new MdUtlAdmTpRevisaoRN();
  $arrObjMdUtlAdmTpRevisaoDTO = $objMdUtlAdmTpRevisaoRN->listar($objMdUtlAdmTpRevisaoDTO);

  PaginaSEI::getInstance()->processarPaginacao($objMdUtlAdmTpRevisaoDTO);
  $numRegistros = count($arrObjMdUtlAdmTpRevisaoDTO);

  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='md_utl_adm_tp_revisao_selecionar'){
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_tp_revisao_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_tp_revisao_alterar');
      $bolAcaoImprimir = false;
      //$bolAcaoGerarPlanilha = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolCheck = true;
    }else if ($_GET['acao']=='md_utl_adm_tp_revisao_reativar'){
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_tp_revisao_reativar');
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_tp_revisao_consultar');
      $bolAcaoAlterar = false;
      $bolAcaoImprimir = true;
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_tp_revisao_excluir');
      $bolAcaoDesativar = false;
    }else{
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_tp_revisao_reativar');
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_tp_revisao_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_tp_revisao_alterar');
      $bolAcaoImprimir = true;
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_tp_revisao_excluir');
      $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_tp_revisao_desativar');
    }

    if ($bolAcaoDesativar){
      $bolCheck = true;
      $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_tp_revisao_desativar&id_tipo_controle_utl='.$idTpCtrl.'&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoReativar){
      $bolCheck = true;
      $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_tp_revisao_reativar&id_tipo_controle_utl='.$idTpCtrl.'&acao_origem='.$_GET['acao'].'&acao_confirmada=sim');
    }


    if ($bolAcaoExcluir){
      $bolCheck = true;
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_tp_revisao_excluir&id_tipo_controle_utl='.$idTpCtrl.'&acao_origem='.$_GET['acao']);
    }


    $strResultado = '';

    if ($_GET['acao']!='md_utl_adm_tp_revisao_reativar'){
      $strSumarioTabela = 'Tabela de Resultado da Revisão.';
      $strCaptionTabela = 'Resultados da Revisão';
    }else{
      $strSumarioTabela = 'Tabela de Resultado da Revisão Inativos.';
      $strCaptionTabela = 'Resultados da Revisão Inativos';
    }

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%" style="display: none">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh" width="35%">'.PaginaSEI::getInstance()->getThOrdenacao($objMdUtlAdmTpRevisaoDTO,'Resultado da Revisão ','Nome',$arrObjMdUtlAdmTpRevisaoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="50%">'.PaginaSEI::getInstance()->getThOrdenacao($objMdUtlAdmTpRevisaoDTO,'Descrição','Descricao',$arrObjMdUtlAdmTpRevisaoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="15%">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      if($arrObjMdUtlAdmTpRevisaoDTO[$i]->getStrSinAtivo()=='N')
      {
        $strCssTr = '<tr class="trVermelha">';
      }
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top" style="display: none">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjMdUtlAdmTpRevisaoDTO[$i]->getNumIdMdUtlAdmTpRevisao(),$arrObjMdUtlAdmTpRevisaoDTO[$i]->getNumIdMdUtlAdmTpRevisao()).'</td>';
      }
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjMdUtlAdmTpRevisaoDTO[$i]->getStrNome()).'</td>';
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjMdUtlAdmTpRevisaoDTO[$i]->getStrDescricao()).'</td>';
      $strResultado .= '<td align="center">';

      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjMdUtlAdmTpRevisaoDTO[$i]->getNumIdMdUtlAdmTpRevisao());

      if ($bolAcaoConsultar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_tp_revisao_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_tipo_controle_utl='.$idTpCtrl.'&id_md_utl_adm_tp_revisao='.$arrObjMdUtlAdmTpRevisaoDTO[$i]->getNumIdMdUtlAdmTpRevisao()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getDiretorioImagensGlobal().'/consultar.gif" title="Consultar Resultado da Revisão" alt="Consultar Resultado da Revisão" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_tp_revisao_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_tipo_controle_utl='.$idTpCtrl.'&id_md_utl_adm_tp_revisao='.$arrObjMdUtlAdmTpRevisaoDTO[$i]->getNumIdMdUtlAdmTpRevisao()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getDiretorioImagensGlobal().'/alterar.gif" title="Alterar Resultado da Revisão" alt="Alterar Resultado da Revisão" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir){
        $strId = $arrObjMdUtlAdmTpRevisaoDTO[$i]->getNumIdMdUtlAdmTpRevisao();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjMdUtlAdmTpRevisaoDTO[$i]->getStrNome());
      }

      if ($bolAcaoDesativar && $arrObjMdUtlAdmTpRevisaoDTO[$i]->getStrSinAtivo()=='S'){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getDiretorioImagensGlobal().'/desativar.gif" title="Desativar Resultado da Revisão" alt="Desativar Resultado da Revisão" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar && $arrObjMdUtlAdmTpRevisaoDTO[$i]->getStrSinAtivo()=='N'){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoReativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getDiretorioImagensGlobal().'/reativar.gif" title="Reativar Resultado da Revisão" alt="Reativar Resultado da Revisão" class="infraImg" /></a>&nbsp;';
      }


      if ($bolAcaoExcluir){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getDiretorioImagensGlobal().'/excluir.gif" title="Excluir Resultado da Revisão" alt="Excluir Resultado da Revisão" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'md_utl_adm_tp_revisao_selecionar'){
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
require_once 'md_utl_geral_js.php';
?>
<?if(0){?><script type="text/javascript"><?}?>
    var msg70 = '<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_70); ?>';
    var msg72 = '<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_72); ?>';
    var msg74 = '<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_74); ?>';

  function inicializar(){
    if ('<?=$_GET['acao']?>'=='md_utl_adm_tp_revisao_selecionar'){
      infraReceberSelecao();
      document.getElementById('btnFecharSelecao').focus();
    }else{
      document.getElementById('btnFechar').focus();
    }
    infraEfeitoTabelas(true);
  }

  <? if ($bolAcaoDesativar){ ?>
  function acaoDesativar(id,desc){
      var msg = setMensagemPersonalizada(msg70, ['Resultado da Revisão', desc]);
    if (confirm(msg)){
      document.getElementById('hdnInfraItemId').value=id;
      document.getElementById('frmMdUtlAdmTpRevisaoLista').action='<?=$strLinkDesativar?>';
      document.getElementById('frmMdUtlAdmTpRevisaoLista').submit();
    }
  }

  <? } ?>

  <? if ($bolAcaoReativar){ ?>
  function acaoReativar(id,desc){
    var msg = setMensagemPersonalizada(msg72, ['Resultado da Revisão', desc]);
    if (confirm(msg)){
      document.getElementById('hdnInfraItemId').value=id;
      document.getElementById('frmMdUtlAdmTpRevisaoLista').action='<?=$strLinkReativar?>';
      document.getElementById('frmMdUtlAdmTpRevisaoLista').submit();
    }
  }

  <? } ?>

  <? if ($bolAcaoExcluir){ ?>
  function acaoExcluir(id,desc){
    var msg = setMensagemPersonalizada(msg74, ['Resultado da Revisão', desc]);
    if (confirm(msg)){
      document.getElementById('hdnInfraItemId').value=id;
      document.getElementById('frmMdUtlAdmTpRevisaoLista').action='<?=$strLinkExcluir?>';
      document.getElementById('frmMdUtlAdmTpRevisaoLista').submit();
    }
  }

  <? } ?>

  <?if(0){?></script><?}?>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
  <form id="frmMdUtlAdmTpRevisaoLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
    <?
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    ?>

    <div id="divInfraAreaDados" class="infraAreaDados">

      <div style="width: 27%;" class="bloco">
        <label id="lblTpRevisao" for="txtTpRevisao" accesskey="S" class="infraLabelOpcional">
          Resultado da Revisão:
        </label>

        <div class="clear"></div>

        <input type="text" id="txtTpRevisao" name="txtTpRevisao" class="infraText" size="30"
               value="<?=$tpRevisao?>" maxlength="100"
               tabindex="502"/>
      </div>
      <div style="width: 45%;" class="bloco">
        <label id="lblDescricao" for="txtDescricao" accesskey="S"
               class="infraLabelOpcional">
          Descrição:
        </label>

        <div class="clear"></div>

        <input style="width: 68%" type="text" id="txtDescricao" name="txtDescricao" class="infraText"
               size="30"
               value="<?=$descricao?>" maxlength="100"
               tabindex="502"/>
      </div>
    </div>

    <input type="hidden" name="hdnIdTpCtrlUtl" id="hdnIdTpCtrlUtl" value="<?php echo $idTpCtrl ?>"/>

    <?
    PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
    //PaginaSEI::getInstance()->montarAreaDebug();
    PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
  </form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
