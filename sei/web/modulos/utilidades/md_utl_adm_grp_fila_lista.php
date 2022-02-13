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

  PaginaSEI::getInstance()->prepararSelecao('md_utl_adm_grp_fila_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  PaginaSEI::getInstance()->salvarCamposPost(array('txtDescricao','txtGrupoAtividade'));

  $idTpCtrl = array_key_exists('id_tipo_controle_utl', $_GET) ? $_GET['id_tipo_controle_utl'] : $_POST['hdnIdTpCtrlUtl'];
  $objTpControleUtlRN    = new MdUtlAdmTpCtrlDesempRN();
  $objTipoControleUtlDTO = $objTpControleUtlRN->buscarObjTpControlePorId($idTpCtrl);
  $selFilaPadrao         = $_POST['selFila'] == 'null' ? null : $_POST['selFila'];
  $strFilaPadrao         = MdUtlAdmPrmGrINT::montarSelectFilaPadrao($selFilaPadrao,$idTpCtrl, false);
  $trAcessada            = false;
  $isSelecionar          = false;
  $objMdUtlAdmGrpFilaProcRN = new MdUtlAdmGrpFilaProcRN();
  $addGruposFila         = false;

  switch($_GET['acao']){
    case 'md_utl_adm_grp_fila_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjMdUtlAdmGrpFilaDTO = array();

        for ($i=0;$i<count($arrStrIds);$i++){
          $objMdUtlAdmGrpFilaDTO = new MdUtlAdmGrpFilaDTO();
          $objMdUtlAdmGrpFilaDTO->setNumIdMdUtlAdmGrpFila($arrStrIds[$i]);
          $arrObjMdUtlAdmGrpFilaDTO[] = $objMdUtlAdmGrpFilaDTO;
        }

        $idRel  = $arrObjMdUtlAdmGrpFilaDTO[0]->getNumIdMdUtlAdmGrpFila();
        $objMdUtlAdmGrpFilaRN = new MdUtlAdmGrpFilaRN();

        $arrPrms      = $objMdUtlAdmGrpFilaRN->verificaQtdRegistrosRelacionados($idRel);
        $arrFilaProc = $objMdUtlAdmGrpFilaProcRN->verificarQtdRegistroRelacionados($idRel);

        if(is_array($arrPrms)) {
          $count  = array_key_exists(0, $arrPrms) ? $arrPrms[0] : null;
          $idMain = array_key_exists(1, $arrPrms) ? $arrPrms[1] : null;

          $objMdUtlAdmGrpFilaRN->excluir($arrObjMdUtlAdmGrpFilaDTO);

          if ($count == 1) {
            $objMdUtlAdmGrpRN = new MdUtlAdmGrpRN();
            $objMdUtlAdmGrpDTO = new MdUtlAdmGrpDTO();
            $objMdUtlAdmGrpDTO->setNumIdMdUtlAdmGrp($idMain);
            $arrObjMdUtlAdmGrpDTO[] = $objMdUtlAdmGrpDTO;
            $objMdUtlAdmGrpRN->excluir($arrObjMdUtlAdmGrpDTO);
          }
        }

      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      }
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTpCtrl));
      die;


    case 'md_utl_adm_grp_fila_desativar':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjMdUtlAdmGrpFilaDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objMdUtlAdmGrpFilaDTO = new MdUtlAdmGrpFilaDTO();
          $objMdUtlAdmGrpFilaDTO->setNumIdMdUtlAdmGrpFila($arrStrIds[$i]);
          $arrObjMdUtlAdmGrpFilaDTO[] = $objMdUtlAdmGrpFilaDTO;
        }
        $objMdUtlAdmGrpFilaRN = new MdUtlAdmGrpFilaRN();
        $objMdUtlAdmGrpFilaRN->desativar($arrObjMdUtlAdmGrpFilaDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      }
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTpCtrl));
      die;

    case 'md_utl_adm_grp_fila_reativar':
      $strTitulo = 'Reativar Atividade';
      if ($_GET['acao_confirmada']=='sim'){
        try{
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjMdUtlAdmGrpFilaDTO = array();
          for ($i=0;$i<count($arrStrIds);$i++){
            $objMdUtlAdmGrpFilaDTO = new MdUtlAdmGrpFilaDTO();
            $idTpRevisao = $arrStrIds[$i];
            $objMdUtlAdmGrpFilaDTO->setNumIdMdUtlAdmGrpFila($arrStrIds[$i]);
            $arrObjMdUtlAdmGrpFilaDTO[] = $objMdUtlAdmGrpFilaDTO;
          }
          $objMdUtlAdmGrpFilaRN = new MdUtlAdmGrpFilaRN();
          $objMdUtlAdmGrpFilaRN->reativar($arrObjMdUtlAdmGrpFilaDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
        header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTpCtrl.PaginaSEI::getInstance()->montarAncora($idTpRevisao)));
        die;
      }
      break;

    case 'md_utl_adm_grp_fila_listar':
      $nomeTpCtrl = !is_null($objTipoControleUtlDTO) ? $objTipoControleUtlDTO->getStrNome() : '';
      $strTitulo  = 'Grupo de Atividades - '.$nomeTpCtrl;
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_grp_fila_reativar');

      if($_GET['acao_origem']== 'md_utl_adm_grp_fila_cadastrar'){

          $idMdUtlAdmGrp = $_GET['id_md_utl_adm_grp'];
        if($idMdUtlAdmGrp>0) {
            $mdUtlAdmGrpFilaDTO = new MdUtlAdmGrpFilaDTO();
            $mdUtlAdmGrpFilaRN = new MdUtlAdmGrpFilaRN();

            $mdUtlAdmGrpFilaDTO->setNumIdMdUtlAdmGrp($idMdUtlAdmGrp);
            $mdUtlAdmGrpFilaDTO->retNumIdMdUtlAdmGrpFila();

            $arrIdMdUtlAdmGrpFila = $mdUtlAdmGrpFilaRN->retornarIdGrpFila($mdUtlAdmGrpFilaDTO);

            if(count($arrIdMdUtlAdmGrpFila)>0){
                $trAcessada = true;
            }
        }
      }
      break;

    case 'md_utl_adm_grp_fila_selecionar' :
      $nomeTpCtrl = !is_null($objTipoControleUtlDTO) ? $objTipoControleUtlDTO->getStrNome() : '';
      $strTitulo = 'Selecionar Grupo de Atividade - '.$nomeTpCtrl;
      $isSelecionar = true;

       break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  $arrComandos[] = '<button type="submit" accesskey="P" id="btnPesquisar" value="Pesquisar" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';

  if ($_GET['acao'] == 'md_utl_adm_grp_fila_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }



  $objMdUtlAdmGrpFilaDTO = new MdUtlAdmGrpFilaDTO();

  if ($_GET['acao'] == 'md_utl_adm_grp_fila_listar' || $_GET['acao'] == 'md_utl_adm_grp_fila_selecionar'){
    $descricao  = $_POST['txtDescricao'];
    $tpRevisao  = $_POST['txtGrupoAtividade'];
    $idFila     = $_POST['selFila'] == 'null' ? null : $_POST['selFila'];

    if($isSelecionar){
      $idFila = $_GET['id_fila_ativa'];
    }

    $objMdUtlAdmGrpFilaDTO->setStrNomeGrupoAtividade('%'.trim($tpRevisao.'%'),InfraDTO::$OPER_LIKE);
    $objMdUtlAdmGrpFilaDTO->setStrDescricaoGrupoAtividade('%'.trim($descricao.'%'),InfraDTO::$OPER_LIKE);

    if(!is_null($idFila)){
      $objMdUtlAdmGrpFilaDTO->setNumIdMdUtlAdmFila($idFila);
    }

    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_grp_fila_cadastrar');
    if ($bolAcaoCadastrar && !$isSelecionar){
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_grp_fila_cadastrar&id_tipo_controle_utl='.$idTpCtrl.'&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
    }
  }


  $objMdUtlAdmGrpFilaDTO->retNumIdMdUtlAdmGrpFila();
  $objMdUtlAdmGrpFilaDTO->retStrNomeGrupoAtividade();
  $objMdUtlAdmGrpFilaDTO->retStrDescricaoGrupoAtividade();
  $objMdUtlAdmGrpFilaDTO->retStrNomeFila();
  $objMdUtlAdmGrpFilaDTO->retStrSinAtivo();
  $objMdUtlAdmGrpFilaDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTpCtrl);

  if($bolAcaoReativar) {
    $objMdUtlAdmGrpFilaDTO->setBolExclusaoLogica(false);
  }
  
  if($isSelecionar){
    $idTpProcedimento = $_GET['id_tipo_procedimento'];
    $idsGrupoFila = $objMdUtlAdmGrpFilaProcRN->getGruposFilaDesteProcesso($idTpProcedimento);
    $addGruposFila = !is_null($idsGrupoFila) && is_array($idsGrupoFila) && count($idsGrupoFila) > 0;
    if($addGruposFila){
      $objMdUtlAdmGrpFilaDTO->setNumIdMdUtlAdmGrpFila($idsGrupoFila, InfraDTO::$OPER_IN);
    }else{
      $objMdUtlAdmGrpFilaDTO = new MdUtlAdmGrpFilaDTO();
    }
  }

  PaginaSEI::getInstance()->prepararOrdenacao($objMdUtlAdmGrpFilaDTO, 'NomeGrupoAtividade', InfraDTO::$TIPO_ORDENACAO_ASC);
  PaginaSEI::getInstance()->prepararPaginacao($objMdUtlAdmGrpFilaDTO, 200);

  $objMdUtlAdmGrpFilaRN = new MdUtlAdmGrpFilaRN();
  if(!$isSelecionar) {
    $arrObjMdUtlAdmGrpFilaDTO = $objMdUtlAdmGrpFilaRN->listar($objMdUtlAdmGrpFilaDTO);
  }else{
    if($addGruposFila){
      $arrObjMdUtlAdmGrpFilaDTO = $objMdUtlAdmGrpFilaRN->listar($objMdUtlAdmGrpFilaDTO);
    }else {
      $arrObjMdUtlAdmGrpFilaDTO = null;
    }
  }


  PaginaSEI::getInstance()->processarPaginacao($objMdUtlAdmGrpFilaDTO);
  $numRegistros = count($arrObjMdUtlAdmGrpFilaDTO);

  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='md_utl_adm_grp_fila_selecionar'){
      $bolAcaoReativar = false;
      $bolAcaoConsultar = false;
      $bolAcaoAlterar = false;
      $bolAcaoImprimir = false;
      //$bolAcaoGerarPlanilha = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolCheck = true;
      $bolAcoesParametrizar = false;
    }else if ($_GET['acao']=='md_utl_adm_grp_fila_reativar'){
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_grp_fila_reativar');
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_grp_fila_consultar');
      $bolAcaoAlterar = false;
      $bolAcaoImprimir = true;
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_grp_fila_excluir');
      $bolAcaoDesativar = false;
      $bolAcoesParametrizar = true;
    }else{
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_grp_fila_reativar');
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_grp_fila_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_grp_fila_alterar');
      $bolAcaoImprimir = true;
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_grp_fila_excluir');
      $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_grp_fila_desativar');
      $bolAcoesParametrizar = true;
    }


    if ($bolAcaoDesativar){
      $bolCheck = true;
      $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_grp_fila_desativar&id_tipo_controle_utl='.$idTpCtrl.'&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoReativar){
      $bolCheck = true;
      $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_grp_fila_reativar&id_tipo_controle_utl='.$idTpCtrl.'&acao_origem='.$_GET['acao'].'&acao_confirmada=sim');
    }


    if ($bolAcaoExcluir){
      $bolCheck = true;
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_grp_fila_excluir&id_tipo_controle_utl='.$idTpCtrl.'&acao_origem='.$_GET['acao']);
    }

    $displayNoneSelect =   !$isSelecionar ? 'display:none' : '';
    $strResultado = '';

    if ($_GET['acao']!='md_utl_adm_grp_fila_reativar'){
      $strSumarioTabela = 'Tabela de Grupo de Atividades.';
      $strCaptionTabela = 'Grupo de Atividades';
    }else{
      $strSumarioTabela = 'Tabela de Grupo de Atividades Inativos.';
      $strCaptionTabela = 'Atividades Inativos';
    }

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%" style="'.$displayNoneSelect.'">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh" width="25%">'.PaginaSEI::getInstance()->getThOrdenacao($objMdUtlAdmGrpFilaDTO,'Grupo de Atividade ','NomeGrupoAtividade',$arrObjMdUtlAdmGrpFilaDTO).'</th>'."\n";

      $strResultado .= '<th class="infraTh" width="30%">' . PaginaSEI::getInstance()->getThOrdenacao($objMdUtlAdmGrpFilaDTO, 'Descrição', 'DescricaoGrupoAtividade', $arrObjMdUtlAdmGrpFilaDTO) . '</th>' . "\n";
    if(!$isSelecionar) {
      $strResultado .= '<th class="infraTh" width="25%">' . PaginaSEI::getInstance()->getThOrdenacao($objMdUtlAdmGrpFilaDTO, 'Fila', 'NomeFila', $arrObjMdUtlAdmGrpFilaDTO) . '</th>' . "\n";
    }

    $strResultado .= '<th class="infraTh" width="15%">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';

    for($i = 0;$i < $numRegistros; $i++){


      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      if($arrObjMdUtlAdmGrpFilaDTO[$i]->getStrSinAtivo()=='N')
      {
        $strCssTr = '<tr class="trVermelha">';
      }else if($trAcessada){

          $bolNovo = in_array($arrObjMdUtlAdmGrpFilaDTO[$i]->getNumIdMdUtlAdmGrpFila(),$arrIdMdUtlAdmGrpFila);
          if($bolNovo){
              $strCssTr = '<tr class="infraTrClara infraTrAcessada">';
          }

      }

      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top" style="'.$displayNoneSelect.'">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjMdUtlAdmGrpFilaDTO[$i]->getNumIdMdUtlAdmGrpFila(),$arrObjMdUtlAdmGrpFilaDTO[$i]->getStrNomeGrupoAtividade()).'</td>';
      }
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjMdUtlAdmGrpFilaDTO[$i]->getStrNomeGrupoAtividade()).'</td>';
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjMdUtlAdmGrpFilaDTO[$i]->getStrDescricaoGrupoAtividade()).'</td>';
      if(!$isSelecionar) {
        $strResultado .= '<td>' . PaginaSEI::tratarHTML($arrObjMdUtlAdmGrpFilaDTO[$i]->getStrNomeFila()) . '</td>';
      }
      $strResultado .= '<td align="center">';

      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjMdUtlAdmGrpFilaDTO[$i]->getNumIdMdUtlAdmGrpFila());

      if($bolAcoesParametrizar) {
        $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_grp_fl_proc_atv_cadastrar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_tipo_controle_utl=' . $idTpCtrl . '&id_md_utl_adm_grp_fila=' . $arrObjMdUtlAdmGrpFilaDTO[$i]->getNumIdMdUtlAdmGrpFila()) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioImagensLocal() . '/sei_servicos.gif" title="Parametrizar Grupo de Atividade" alt="Parametrizar Grupo de Atividade" class="infraImg" /></a>&nbsp;';
        $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_grp_fila_cadastrar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_tipo_controle_utl=' . $idTpCtrl . '&id_md_utl_adm_grp_fila=' . $arrObjMdUtlAdmGrpFilaDTO[$i]->getNumIdMdUtlAdmGrpFila()) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="/infra_css/imagens/mais.gif" title="Incluir nova Fila em  Grupo de Atividade já existente" alt="Incluir nova fila em grupo de atividade já existente" class="infraImg" /></a>&nbsp;';
      }

        if ($bolAcaoConsultar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_grp_fila_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_tipo_controle_utl='.$idTpCtrl.'&id_md_utl_adm_grp_fila='.$arrObjMdUtlAdmGrpFilaDTO[$i]->getNumIdMdUtlAdmGrpFila()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getDiretorioImagensGlobal().'/consultar.gif" title="Consultar Grupo de Atividade" alt="Consultar  Grupo de Atividade" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_grp_fila_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_tipo_controle_utl='.$idTpCtrl.'&id_md_utl_adm_grp_fila='.$arrObjMdUtlAdmGrpFilaDTO[$i]->getNumIdMdUtlAdmGrpFila()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getDiretorioImagensGlobal().'/alterar.gif" title="Alterar  Grupo de Atividade" alt="Alterar  Grupo de Atividade" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir){
        $strId = $arrObjMdUtlAdmGrpFilaDTO[$i]->getNumIdMdUtlAdmGrpFila();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjMdUtlAdmGrpFilaDTO[$i]->getStrNomeGrupoAtividade());
          $strDescricaoFila = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjMdUtlAdmGrpFilaDTO[$i]->getStrNomeFila());

          $strNomeGrupo = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjMdUtlAdmGrpFilaDTO[$i]->getStrNomeGrupoAtividade());
        }

      if ($bolAcaoDesativar && $arrObjMdUtlAdmGrpFilaDTO[$i]->getStrSinAtivo()=='S'){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricaoFila.'\',\''.$strNomeGrupo.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getDiretorioImagensGlobal().'/desativar.gif" title="Desativar  Grupo de Atividade" alt="Desativar  Grupo de Atividade" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar && $arrObjMdUtlAdmGrpFilaDTO[$i]->getStrSinAtivo()=='N'){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoReativar(\''.$strId.'\',\''.$strDescricaoFila.'\',\''.$strNomeGrupo.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getDiretorioImagensGlobal().'/reativar.gif" title="Reativar  Grupo de Atividade" alt="Reativar  Grupo de Atividade" class="infraImg" /></a>&nbsp;';
      }


      if ($bolAcaoExcluir){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricaoFila.'\',\''.$strNomeGrupo.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getDiretorioImagensGlobal().'/excluir.gif" title="Excluir  Grupo de Atividade" alt="Excluir  Grupo de Atividade" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'md_utl_adm_grp_fila_selecionar'){
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

    #divPesqAtividade{
        position: absolute;
        float: left;

    }
    #divPesqDescricao{
        position: absolute;
        left: 25%;
    }
    #divPesqFila{
      position: absolute;
      margin-left: 59.5%;
      margin-top: 0%;
      width: 30%
    }

  <?if(0){?></style><?}?>
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
require_once 'md_utl_geral_js.php';
?>
<?if(0){?><script type="text/javascript"><?}?>
    var msg76 = '<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_76); ?>';
    var msg77 = '<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_77); ?>';
    var msg78 = '<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_78); ?>';

  function inicializar() {
    if ('<?=$_GET['acao']?>' == 'md_utl_adm_grp_fila_selecionar') {
      infraReceberSelecao();
      document.getElementById('btnFecharSelecao').focus();
    } else {
      document.getElementById('btnFechar').focus();
    }
    infraEfeitoTabelas(true);
  }

  function pesquisar(){
    document.getElementById("frmMdUtlAdmGrpFilaLista").submit();
  }

  <? if ($bolAcaoDesativar){ ?>
  function acaoDesativar(id,descFila,nomeGrupo){
      var msg = setMensagemPersonalizada(msg76, [descFila, nomeGrupo]);
      if (confirm(msg)){
      document.getElementById('hdnInfraItemId').value=id;
      document.getElementById('frmMdUtlAdmGrpFilaLista').action='<?=$strLinkDesativar?>';
      document.getElementById('frmMdUtlAdmGrpFilaLista').submit();
    }
  }

  <? } ?>

  <? if ($bolAcaoReativar){ ?>
  function acaoReativar(id,descFila,nomeGrupo){
      var msg = setMensagemPersonalizada(msg77, [descFila, nomeGrupo]);
      if (confirm(msg)){
      document.getElementById('hdnInfraItemId').value=id;
      document.getElementById('frmMdUtlAdmGrpFilaLista').action='<?=$strLinkReativar?>';
      document.getElementById('frmMdUtlAdmGrpFilaLista').submit();
    }
  }


  <? } ?>

  <? if ($bolAcaoExcluir){ ?>
  function acaoExcluir(id,descFila,nomeGrupo){
      var msg = setMensagemPersonalizada(msg78, [descFila, nomeGrupo]);
    if (confirm(msg)){
      document.getElementById('hdnInfraItemId').value=id;
      document.getElementById('frmMdUtlAdmGrpFilaLista').action='<?=$strLinkExcluir?>';
      document.getElementById('frmMdUtlAdmGrpFilaLista').submit();
    }
  }

  <? } ?>

  <?if(0){?></script><?}?>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
  <form id="frmMdUtlAdmGrpFilaLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
    <?
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    PaginaSEI::getInstance()->abrirAreaDados('6em');
    ?>


      <div id="divPesqAtividade" style="width: 30%">
        <label id="lblGrupoAtividade" for="txtGrupoAtividade" accesskey="S" class="infraLabelOpcional">
          Grupo de Atividade:
        </label>

        <div class="clear"></div>

        <input type="text" id="txtGrupoAtividade" name="txtGrupoAtividade" class="infraText" style="width: 75%" size="30"
               value="<?=$tpRevisao?>" maxlength="100"
               tabindex="502"/>
      </div>

    <?php if(!$isSelecionar){ ?>
      <div id="divPesqDescricao" style="width: 40%">
        <label id="lblDescricao" for="txtDescricao" accesskey="S"
               class="infraLabelOpcional">
          Descrição:
        </label>

        <div class="clear"></div>

        <input  style="width: 80%" type="text" id="txtDescricao" name="txtDescricao" class="infraText"
               size="30"
               value="<?=$descricao?>" maxlength="100"
               tabindex="502"/>
      </div>

        <div id="divPesqFila">
            <label id="lblFila" for="txtFila" accesskey="S"
                   class="infraLabelOpcional">Fila:</label>

            <div class="clear"></div>
            <select onchange="pesquisar();" style="width: 65%;height: 20px;" id="selFila" name="selFila" class="infraSelect">
              <?= $strFilaPadrao ?>
            </select>

        </div>
<?php } ?>
    <input type="hidden" name="hdnIdTpCtrlUtl" id="hdnIdTpCtrlUtl" value="<?php echo $idTpCtrl ?>"/>

    <?
    PaginaSEI::getInstance()->fecharAreaDados();
    PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
    //PaginaSEI::getInstance()->montarAreaDebug();
    PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
  </form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
