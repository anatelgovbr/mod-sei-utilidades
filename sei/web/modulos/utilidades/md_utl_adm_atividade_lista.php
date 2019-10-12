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

  PaginaSEI::getInstance()->prepararSelecao('md_utl_adm_atividade_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  PaginaSEI::getInstance()->salvarCamposPost(array('txtDescricao','txtTpJustificativa'));

  $idTpCtrl = array_key_exists('id_tipo_controle_utl', $_GET) ? $_GET['id_tipo_controle_utl'] : $_POST['hdnIdTpCtrlUtl'];
  $objTpControleUtlRN    = new MdUtlAdmTpCtrlDesempRN();
  $objTipoControleUtlDTO = $objTpControleUtlRN->buscarObjTpControlePorId($idTpCtrl);
  $displayNone =  "style='display: none'";
  $strLinkSelecionar = '';
  $objTriagemRn = new MdUtlTriagemRN();
  $isSelecionar = $_GET['acao'] == 'md_utl_adm_atividade_selecionar';
  $selTpAnalise = '';

  switch($_GET['acao']){
    case 'md_utl_adm_atividade_excluir':
      try{
        $objMdUtlAdmAtividadeRN = new MdUtlAdmAtividadeRN();

          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjMdUtlAdmAtividadeDTO = array();

          for ($i = 0; $i < count($arrStrIds); $i++) {
            $objMdUtlAdmAtividadeDTO = new MdUtlAdmAtividadeDTO();
            $objMdUtlAdmAtividadeDTO->setNumIdMdUtlAdmAtividade($arrStrIds[$i]);
            $arrObjMdUtlAdmAtividadeDTO[] = $objMdUtlAdmAtividadeDTO;
          }

          $idRel = $arrObjMdUtlAdmAtividadeDTO[0]->getNumIdMdUtlAdmAtividade();
          $objRNRel = new MdUtlAdmAtvSerieProdRN();
          $objRNRel->consultarExcluirVinculos($idRel);
          $mdUtlAdmGrpFlProcAtvRN = new MdUtlAdmGrpFlProcAtvRN();
          $mdUtlAdmGrpFlProcAtvRN->consultarExcluirVinculo($idRel);


          $objMdUtlAdmAtividadeRN->excluir($arrObjMdUtlAdmAtividadeDTO);

      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      }
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTpCtrl));
      die;


    case 'md_utl_adm_atividade_desativar':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjMdUtlAdmAtividadeDTO = array();

        $MdUtlRelTriagemAtvRN = new MdUtlRelTriagemAtvRN();
        $MdUtlRelTriagemAtvRN->verificarDesativacaoAtividadeTriagem($arrStrIds[0]);

        for ($i=0;$i<count($arrStrIds);$i++){
          $objMdUtlAdmAtividadeDTO = new MdUtlAdmAtividadeDTO();
          $objMdUtlAdmAtividadeDTO->setNumIdMdUtlAdmAtividade($arrStrIds[$i]);
          $arrObjMdUtlAdmAtividadeDTO[] = $objMdUtlAdmAtividadeDTO;
        }
        $objMdUtlAdmAtividadeRN = new MdUtlAdmAtividadeRN();
        $objMdUtlAdmAtividadeRN->desativar($arrObjMdUtlAdmAtividadeDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      }
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTpCtrl));
      die;

    case 'md_utl_adm_atividade_reativar':
      $strTitulo = 'Reativar Atividade';
      if ($_GET['acao_confirmada']=='sim'){
        try{
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjMdUtlAdmAtividadeDTO = array();
          for ($i=0;$i<count($arrStrIds);$i++){
            $objMdUtlAdmAtividadeDTO = new MdUtlAdmAtividadeDTO();
            $idTpRevisao = $arrStrIds[$i];
            $objMdUtlAdmAtividadeDTO->setNumIdMdUtlAdmAtividade($arrStrIds[$i]);
            $arrObjMdUtlAdmAtividadeDTO[] = $objMdUtlAdmAtividadeDTO;
          }
          $objMdUtlAdmAtividadeRN = new MdUtlAdmAtividadeRN();
          $objMdUtlAdmAtividadeRN->reativar($arrObjMdUtlAdmAtividadeDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
        header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTpCtrl.PaginaSEI::getInstance()->montarAncora($idTpRevisao)));
        die;
      }
      break;

    case 'md_utl_adm_atividade_listar':
      $nomeTpCtrl = !is_null($objTipoControleUtlDTO) ? $objTipoControleUtlDTO->getStrNome() : '';
      $strTitulo  = 'Atividades - '.$nomeTpCtrl;
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_atividade_reativar');
      break;

      case 'md_utl_adm_atividade_selecionar':
          $nomeTpCtrl     = !is_null($objTipoControleUtlDTO) ? $objTipoControleUtlDTO->getStrNome() : '';
          $strTitulo      = 'Selecionar Atividades - '.$nomeTpCtrl;
          $displayNone    = "";
          $tpProcesso     = array_key_exists('id_tipo_procedimento', $_GET) ? $_GET['id_tipo_procedimento'] : 0;
          $idTipoControle = array_key_exists('id_tipo_controle_utl', $_GET) ? $_GET['id_tipo_controle_utl'] : 0;
          $strLinkSelecionar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao_origem'].'&id_object=objLupaAtividade&id_tipo_controle_utl='.$idTipoControle.'&tipo_selecao=2&id_tipo_procedimento='.$tpProcesso);
          break;
    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  $arrComandos[] = '<button type="button" onclick="pesquisar();" accesskey="P" id="btnPesquisar" value="Pesquisar" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';

  if ($_GET['acao'] == 'md_utl_adm_atividade_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  $objMdUtlAdmAtividadeDTO = new MdUtlAdmAtividadeDTO();

  if ($_GET['acao'] == 'md_utl_adm_atividade_listar' || $_GET['acao'] == 'md_utl_adm_atividade_selecionar'){
    $descricao  = $_POST['txtDescricao'];
    $tpRevisao = $_POST['txtAtividade'];

    $objMdUtlAdmAtividadeDTO->setStrNome('%'.trim($tpRevisao.'%'),InfraDTO::$OPER_LIKE);
    $objMdUtlAdmAtividadeDTO->setStrDescricao('%'.trim($descricao.'%'),InfraDTO::$OPER_LIKE);

    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_atividade_cadastrar');
    if ($bolAcaoCadastrar && $_GET['acao'] != 'md_utl_adm_atividade_selecionar'){
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_atividade_cadastrar&id_tipo_controle_utl='.$idTpCtrl.'&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
    }
  }


  $objMdUtlAdmAtividadeDTO->retNumIdMdUtlAdmAtividade();
  $objMdUtlAdmAtividadeDTO->retStrNome();
  $objMdUtlAdmAtividadeDTO->retStrDescricao();
  $objMdUtlAdmAtividadeDTO->retStrSinAtivo();
  $objMdUtlAdmAtividadeDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTpCtrl);
  $objMdUtlAdmAtividadeDTO->retNumUndEsforcoAtv();
  $objMdUtlAdmAtividadeDTO->retNumUndEsforcoRev();
  $objMdUtlAdmAtividadeDTO->retStrSinAnalise();
  if($bolAcaoReativar) {
    $objMdUtlAdmAtividadeDTO->setBolExclusaoLogica(false);
  }

  if ($_GET['acao_origem'] =='md_utl_triagem_cadastrar' && $_GET['acao'] == 'md_utl_adm_atividade_selecionar'){
    $objGrupoFilaAtvRN = new MdUtlAdmGrpFilaProcRN();
    if($_POST['hdnIdsGrupoAtividadeTriagem'] != 0) {
      $idsGrupoFormatado = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnIdsGrupoAtividadeTriagem']);
      $idTipoProcedimento = array_key_exists('id_tipo_procedimento', $_GET) ? $_GET['id_tipo_procedimento'] : null;
      if (is_array($idsGrupoFormatado) && !is_null($idsGrupoFormatado) && !is_null($idTipoProcedimento) && count($idsGrupoFormatado) > 0) {
        $idsAtividade = $objGrupoFilaAtvRN->getAtividadePorIdGrupoFila(array($idsGrupoFormatado, $idTipoProcedimento));
        $objMdUtlAdmAtividadeDTO->setNumIdMdUtlAdmAtividade($idsAtividade, InfraDTO::$OPER_IN);
      }
    }
  }


      $selTpAnalise = array_key_exists('selTipoAnalise', $_POST) && $_POST['selTipoAnalise'] != '' ? $_POST['selTipoAnalise'] : null;
      if(!is_null($selTpAnalise)){
          $objMdUtlAdmAtividadeDTO->setStrSinAnalise($selTpAnalise);
      }



  PaginaSEI::getInstance()->prepararOrdenacao($objMdUtlAdmAtividadeDTO, 'Nome', InfraDTO::$TIPO_ORDENACAO_ASC);
  PaginaSEI::getInstance()->prepararPaginacao($objMdUtlAdmAtividadeDTO, 200);

  $objMdUtlAdmAtividadeRN = new MdUtlAdmAtividadeRN();
  $arrObjMdUtlAdmAtividadeDTO = $objMdUtlAdmAtividadeRN->listar($objMdUtlAdmAtividadeDTO);

  PaginaSEI::getInstance()->processarPaginacao($objMdUtlAdmAtividadeDTO);
  $numRegistros = count($arrObjMdUtlAdmAtividadeDTO);

  
  if ($numRegistros > 0){
    $idsAtividades = InfraArray::converterArrInfraDTO($arrObjMdUtlAdmAtividadeDTO, 'IdMdUtlAdmAtividade');
    $arrVinculosTriagemAtividade = $objTriagemRn->retornaArrVinculosAtividadeTriagem($idsAtividades);
    
    $bolCheck = false;

    if ($_GET['acao']=='md_utl_adm_atividade_selecionar'){
      $bolAcaoReativar = false;
      $bolAcaoConsultar = false;//SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_atividade_consultar');
      $bolAcaoAlterar = false;//SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_atividade_alterar');
      $bolAcaoImprimir = false;
      //$bolAcaoGerarPlanilha = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolCheck = true;

    }else if ($_GET['acao']=='md_utl_adm_atividade_reativar'){
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_atividade_reativar');
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_atividade_consultar');
      $bolAcaoAlterar = false;
      $bolAcaoImprimir = true;
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_atividade_excluir');
      $bolAcaoDesativar = false;
    }else{
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_atividade_reativar');
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_atividade_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_atividade_alterar');
      $bolAcaoImprimir = true;
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_atividade_excluir');
      $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_atividade_desativar');
    }


    if ($bolAcaoDesativar){
      $bolCheck = true;
      $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_atividade_desativar&id_tipo_controle_utl='.$idTpCtrl.'&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoReativar){
      $bolCheck = true;
      $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_atividade_reativar&id_tipo_controle_utl='.$idTpCtrl.'&acao_origem='.$_GET['acao'].'&acao_confirmada=sim');
    }


    if ($bolAcaoExcluir){
      $bolCheck = true;
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_atividade_excluir&id_tipo_controle_utl='.$idTpCtrl.'&acao_origem='.$_GET['acao']);
    }


    $strResultado = '';

    if ($_GET['acao']!='md_utl_adm_atividade_reativar'){
      $strSumarioTabela = 'Tabela de Atividade.';
      $strCaptionTabela = 'Atividade';
    }else{
      $strSumarioTabela = 'Tabela de Atividades Inativos.';
      $strCaptionTabela = 'Atividades Inativos';
    }

    $isVazioHdnTriagem   = !array_key_exists('hdnIdsGrupoAtividadeTriagem', $_POST);
    $hidden       = array_key_exists('acao_origem', $_GET) && $_GET['acao_origem'] == 'md_utl_triagem_cadastrar' && $isVazioHdnTriagem ? 'style="display:none"' : '';
    $strResultado .= '<table '.$hidden.' width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%" '.$displayNone.'>'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh" width="30%">'.PaginaSEI::getInstance()->getThOrdenacao($objMdUtlAdmAtividadeDTO,'Atividade ','Nome',$arrObjMdUtlAdmAtividadeDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="35%">'.PaginaSEI::getInstance()->getThOrdenacao($objMdUtlAdmAtividadeDTO,'Descrição','Descricao',$arrObjMdUtlAdmAtividadeDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="20%">' . PaginaSEI::getInstance()->getThOrdenacao($objMdUtlAdmAtividadeDTO, 'Possui Análise?', 'SinAnalise', $arrObjMdUtlAdmAtividadeDTO) . '</th>' . "\n";

    $strResultado .= '<th class="infraTh" width="15%">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      if($arrObjMdUtlAdmAtividadeDTO[$i]->getStrSinAtivo()=='N')
      {
        $strCssTr = '<tr class="trVermelha">';
      }
      $strResultado .= $strCssTr;

      $isTelaTriagem = $_GET['acao_origem'] == 'md_utl_triagem_cadastrar';
      $isComAnalise  = $arrObjMdUtlAdmAtividadeDTO[$i]->getStrSinAnalise() == 'S';
      $isSemAnalise  = $arrObjMdUtlAdmAtividadeDTO[$i]->getStrSinAnalise() == 'N';
      if($bolCheck)
      {
        if($isTelaTriagem)
        {
          if($isSemAnalise)
          {
            $vlTriagem = 'N_'.$arrObjMdUtlAdmAtividadeDTO[$i]->getNumUndEsforcoRev();
          }
          if($isComAnalise)
          {
            $vlTriagem = 'S_'.$arrObjMdUtlAdmAtividadeDTO[$i]->getNumUndEsforcoAtv();
          }

          $idSelecao = $arrObjMdUtlAdmAtividadeDTO[$i]->getNumIdMdUtlAdmAtividade().'_'.$vlTriagem;
        }
        else
        {
          $idSelecao = $arrObjMdUtlAdmAtividadeDTO[$i]->getNumIdMdUtlAdmAtividade();
        }
        $strResultado .= '<td valign="top" '.$displayNone.'>'.PaginaSEI::getInstance()->getTrCheck($i,$idSelecao,$arrObjMdUtlAdmAtividadeDTO[$i]->getStrNome()).'</td>';
     }

      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjMdUtlAdmAtividadeDTO[$i]->getStrNome()).'</td>';
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjMdUtlAdmAtividadeDTO[$i]->getStrDescricao()).'</td>';

          $vlAnalise     = $arrObjMdUtlAdmAtividadeDTO[$i]->getStrSinAnalise() == 'S' ? 'Sim' : 'Não';
          $strResultado .= '<td>'.PaginaSEI::tratarHTML($vlAnalise).'</td>';


      $strResultado .= '<td align="center">';

      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjMdUtlAdmAtividadeDTO[$i]->getNumIdMdUtlAdmAtividade());

      if ($bolAcaoConsultar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_atividade_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_tipo_controle_utl='.$idTpCtrl.'&id_md_utl_adm_atividade='.$arrObjMdUtlAdmAtividadeDTO[$i]->getNumIdMdUtlAdmAtividade()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getDiretorioImagensGlobal().'/consultar.gif" title="Consultar Atividade" alt="Consultar Atividade" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_atividade_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_tipo_controle_utl='.$idTpCtrl.'&id_md_utl_adm_atividade='.$arrObjMdUtlAdmAtividadeDTO[$i]->getNumIdMdUtlAdmAtividade()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getDiretorioImagensGlobal().'/alterar.gif" title="Alterar Atividade" alt="Alterar Atividade" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir){
        $strId = $arrObjMdUtlAdmAtividadeDTO[$i]->getNumIdMdUtlAdmAtividade();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjMdUtlAdmAtividadeDTO[$i]->getStrNome());
      }

      if ($bolAcaoDesativar && $arrObjMdUtlAdmAtividadeDTO[$i]->getStrSinAtivo()=='S'){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getDiretorioImagensGlobal().'/desativar.gif" title="Desativar Atividade" alt="Desativar Atividade" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar && $arrObjMdUtlAdmAtividadeDTO[$i]->getStrSinAtivo()=='N'){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoReativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getDiretorioImagensGlobal().'/reativar.gif" title="Reativar Atividade" alt="Reativar Atividade" class="infraImg" /></a>&nbsp;';
      }


      if ($bolAcaoExcluir){
        $possuiVinculo = $arrVinculosTriagemAtividade[$arrObjMdUtlAdmAtividadeDTO[$i]->getNumIdMdUtlAdmAtividade()];
        if($possuiVinculo){
          $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="alertarUsuarioExclusao(\'' . $strDescricao . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/excluir.gif" title="Excluir Atividade" alt="Excluir Atividade" class="infraImg" /></a>&nbsp;';
        }else {
          $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="acaoExcluir(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/excluir.gif" title="Excluir Atividade" alt="Excluir Atividade" class="infraImg" /></a>&nbsp;';
        }
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'md_utl_adm_atividade_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="C" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
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

    #blocoDesc{
        margin-left: 30px;
    }

    #selTipoAnalise{
        width: 140px;
    }

    <?if($isSelecionar){?>
    #blocoTipoAnalise{
        margin-left: -71px;
     }
     <?php } else { ?>
    #blocoTipoAnalise{
      margin-left: -121px;
    }

   <?php } ?>

  <?if(0){?></style><?}?>
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
require_once 'md_utl_geral_js.php';
?>
<?if(0){?><script type="text/javascript"><?}?>
    var msg28 = '<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_28, array('excluir')); ?>';
    var msg71 = '<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_71); ?>';
    var msg73 = '<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_73); ?>';
    var msg75 = '<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_75); ?>';

  function inicializar(){
    if ('<?=$_GET['acao']?>'=='md_utl_adm_atividade_selecionar'){
      infraReceberSelecao();
      document.getElementById('btnFecharSelecao').focus();
      document.getElementById('frmMdUtlAdmAtividadeLista').action = '<?=$strLinkSelecionar?>';
      if ('<?=$_GET['acao_origem']?>'=='md_utl_triagem_cadastrar'){
        preencherHiddenGrupoAtividade();
      }

    }else{
      document.getElementById('btnFechar').focus();
    }

    infraEfeitoTabelas(true);
    addEnter();
  }

  function addEnter(){
      var obj1 = document.getElementById('txtAtividade');
      var obj2 = document.getElementById('txtDescricao');

      obj1.addEventListener("keypress", function (evt) {
          addPesquisarEnter(evt);
      });

      obj2.addEventListener("keypress", function (evt) {
          addPesquisarEnter(evt);
      });
  }

    function addPesquisarEnter(evt) {
        var keyCode = evt.keyCode ? evt.keyCode :
            evt.charCode ? evt.charCode :
                evt.which ? evt.which : void 0;

        if (keyCode == 13) {
            pesquisar();
        }
    }

  function preencherHiddenGrupoAtividade(){
    var idsGrupoAtv = window.opener.document.getElementById('hdnGrupoAtividade').value;
    var isVazioHdn = document.getElementById('hdnIdsGrupoAtividadeTriagem').value == '';

    if(isVazioHdn) {
      idsGrupoAtv = idsGrupoAtv != '' ? idsGrupoAtv : 0;
      document.getElementById('hdnIdsGrupoAtividadeTriagem').value = idsGrupoAtv;
      document.getElementById('frmMdUtlAdmAtividadeLista').action = '<?=$strLinkSelecionar?>';
      document.getElementById("frmMdUtlAdmAtividadeLista").submit();
    }
  }

  <? if ($bolAcaoDesativar){ ?>
  function acaoDesativar(id,desc){
    var msg = setMensagemPersonalizada(msg71, ['Atividade', desc]);
    if (confirm(msg)){
      document.getElementById('hdnInfraItemId').value=id;
      document.getElementById('frmMdUtlAdmAtividadeLista').action='<?=$strLinkDesativar?>';
      document.getElementById('frmMdUtlAdmAtividadeLista').submit();
    }
  }
  <? } ?>

  <? if ($bolAcaoReativar){ ?>
  function acaoReativar(id,desc){
      var msg = setMensagemPersonalizada(msg73, ['Atividade', desc]);
    if (confirm(msg)){
      document.getElementById('hdnInfraItemId').value=id;
      document.getElementById('frmMdUtlAdmAtividadeLista').action='<?=$strLinkReativar?>';
      document.getElementById('frmMdUtlAdmAtividadeLista').submit();
    }
  }

  <? } ?>

  <? if ($bolAcaoExcluir){ ?>
  function acaoExcluir(id,desc){
      var msg = setMensagemPersonalizada(msg75, ['Atividade', desc]);
    if (confirm(msg)){
      document.getElementById('hdnInfraItemId').value=id;
      document.getElementById('frmMdUtlAdmAtividadeLista').action='<?=$strLinkExcluir?>';
      document.getElementById('frmMdUtlAdmAtividadeLista').submit();
    }
  }

  function alertarUsuarioExclusao(desc){
    alert(msg28);
  }

  <? } ?>

    function pesquisar(){
        var form = document.getElementById('frmMdUtlAdmAtividadeLista');
        form.submit();
    }

  <?if(0){?></script><?}?>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
  <form id="frmMdUtlAdmAtividadeLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao_origem'])?>">
    <?
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    ?>

    <div id="divInfraAreaDados" class="infraAreaDados">
        <?php
        if($isSelecionar){

        ?>

            <div class="clear"></div>
        <?php } ?>

      <div style="width: 30%;" class="bloco" id="blocoAtv">
        <label id="lblAtividade" for="txtAtividade" accesskey="S" class="infraLabelOpcional">
          Atividade:
        </label>

        <div class="clear"></div>

        <input type="text" id="txtAtividade" name="txtAtividade" style="width: 100%" class="infraText" size="30"
               value="<?=$tpRevisao?>" maxlength="100"
               tabindex="502"/>
      </div>
      <div style="width: 45%;" class="bloco" id="blocoDesc">
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
        <div style="width: 25%" class="bloco" id="blocoTipoAnalise">

            <label id="lblTipoAnalise" name="lblTipoAnalise" for="selTipoAnalise"
                   class="infraLabelOpcional">
                Possui Análise?
            </label>

            <div class="clear"></div>

            <select style="width: 80%;" id="selTipoAnalise" onchange="pesquisar();" name="selTipoAnalise">
                <option value=""></option>
                <option <?php echo $selTpAnalise != '' && $selTpAnalise == 'S' ? 'selected = selected' : ''; ?> value="S">Sim</option>
                <option <?php echo $selTpAnalise != '' && $selTpAnalise == 'N' ? 'selected = selected' : ''; ?> value="N">Não</option>
            </select>

        </div>

    </div>

    <input type="hidden" name="hdnIdTpCtrlUtl" id="hdnIdTpCtrlUtl" value="<?php echo $idTpCtrl ?>"/>
    <input type="hidden" name="hdnIdsGrupoAtividadeTriagem" id="hdnIdsGrupoAtividadeTriagem" value="<?php echo array_key_exists('hdnIdsGrupoAtividadeTriagem', $_POST) ? $_POST['hdnIdsGrupoAtividadeTriagem'] : '' ?>"/>
    <?
    PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
    //PaginaSEI::getInstance()->montarAreaDebug();
    PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
  </form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
