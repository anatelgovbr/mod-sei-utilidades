<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 14/04/2008 - criado por mga
*
* Versão do Gerador de Código: 1.14.0
*
* Versão no CVS: $Id$
*/

try {
  require_once dirname(__FILE__) . '/../../SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  PaginaSEI::getInstance()->prepararSelecao('md_utl_adm_usuario_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  PaginaSEI::getInstance()->salvarCamposPost(array('selOrgao','txtSiglaUsuario','txtNomeUsuario'));


  $idTipoControle    = array_key_exists('id_tipo_controle_utl', $_GET) ? $_GET['id_tipo_controle_utl'] : null;
  $idParams          = null;
  $isBolUsuario      = array_key_exists('is_bol_usuario', $_GET) ? $_GET['is_bol_usuario'] : null;
  $isBolUsuarioDTO   = array_key_exists('is_bol_usu_dto', $_GET) ? $_GET['is_bol_usu_dto'] : null;
  $idObject          = array_key_exists('id_object', $_GET) ? $_GET['id_object'] : null;
  $isBolDistribuicao = array_key_exists('is_bol_distribuicao', $_GET) ? $_GET['is_bol_distribuicao'] : null;
  $tpSelecao         = array_key_exists('tipo_selecao', $_GET) ? $_GET['tipo_selecao'] : null;
  $idFila            = array_key_exists('id_fila', $_GET) ? $_GET['id_fila'] : null;
  $idStatus          = array_key_exists('id_status', $_GET) ? $_GET['id_status'] : null;
  $arrProcedimentos  = array_key_exists('arr_procedimentos', $_GET) ? $_GET['arr_procedimentos'] : null;
  $possuiRegistrosDist = false;
  $arrObjUsuarioDTO  = null;
  $isVazioUsers      = false;

  $urlPadrao = 'controlador.php?acao=md_utl_adm_usuario_selecionar';

  if(!is_null($isBolDistribuicao) && $isBolDistribuicao == '1'){
    $tpSelecao = 1;
    $isBolUsuario = 1;
    $isBolUsuarioDTO = 1;
    $urlPadrao .= '&is_bol_distribuicao='.$isBolDistribuicao;
  }

  if(!is_null($tpSelecao)){
    $urlPadrao .= '&tipo_selecao='.$tpSelecao;
  }

  if(!is_null($idFila)){
    $urlPadrao .= '&id_fila='.$idFila;
  }

  if(!is_null($idStatus)){
    $urlPadrao .= '&id_status='.$idStatus;
  }

  if(!is_null($idTipoControle)){
    $urlPadrao .= '&id_tipo_controle_utl='.$idTipoControle;
  }

  if(!is_null($isBolUsuario) && $isBolUsuario == '1'){
    $urlPadrao .= '&is_bol_usuario='.$isBolUsuario;
  }

  if(!is_null($isBolUsuarioDTO) && $isBolUsuarioDTO == '1'){
    $urlPadrao .= '&is_bol_usu_dto='.$isBolUsuarioDTO;
  }

  if(!is_null($idObject)){
    $urlPadrao .= '&id_object='.$idObject;
  }

  if(!is_null($idTipoControle) && $idTipoControle !=''){
    $objMdUtlAdmTpCtrlRN  = new MdUtlAdmTpCtrlDesempRN();

    $objMdUtlAdmTpCtrlDTO = new MdUtlAdmTpCtrlDesempDTO();
    $objMdUtlAdmTpCtrlDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTipoControle);
    $objMdUtlAdmTpCtrlDTO->retNumIdMdUtlAdmPrmGr();
    $objMdUtlAdmTpCtrlDTO->setNumTotalRegistros(1);

    $objMdUtlAdmTpCtrlDTO = $objMdUtlAdmTpCtrlRN->consultar($objMdUtlAdmTpCtrlDTO);

    $idParams = $objMdUtlAdmTpCtrlDTO->getNumIdMdUtlAdmPrmGr();
  }

  switch($_GET['acao']){


    case 'md_utl_adm_usuario_selecionar':
    $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Usuario','Selecionar Usuarios');
      break;

    case 'md_utl_adm_usuario_listar':
      $strTitulo = 'Usuários';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();

  $arrComandos[] = '<button type="submit" id="btnPesquisar" accesskey="P" value="Pesquisar" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';

  if ($_GET['acao'] == 'md_utl_adm_usuario_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }


  if(!is_null($isBolUsuarioDTO) && $isBolUsuarioDTO == 1 && is_null($idParams)) {
    $objUsuarioDTO = new UsuarioDTO();
  }else {
    $objUsuarioDTO = new MdUtlAdmPrmGrUsuDTO();
    $objUsuarioDTO->retNumIdMdUtlAdmPrmGrUsu();
  }


    if (!is_null($isBolDistribuicao) && $isBolDistribuicao == '1') {
        /*     $objMdUtlAdmTpCtrlUsuRN = new MdUtlAdmRelTpCtrlDesempUsuDTO();
             $isGestorSipSei = $objMdUtlAdmTpCtrlUsuRN->usuarioLogadoIsGestorSipSei();*/

        //if(!$isGestorSipSei) {
        $strPapelUsuario = MdUtlAdmFilaINT::getPapeisDeUsuario($idStatus);

        if (!is_null($strPapelUsuario)) {
            $arrDTO = null;
            $objRegrasGeraisRN = new MdUtlRegrasGeraisRN();
            $idsUsuarioUnidade = $objRegrasGeraisRN->getIdsUsuariosUnidadeLogada();

            if(count($idsUsuarioUnidade) > 0) {
                $objMdUtlAdmFilaPrmUsuRN = new MdUtlAdmFilaPrmGrUsuRN();
                $arrDTO = $objMdUtlAdmFilaPrmUsuRN->getUsuarioPorPapel(array($strPapelUsuario, $idFila, $idsUsuarioUnidade));
            }

            if (is_null($arrDTO)) {
                $isVazioUsers = true;
            }else{
                $idsUsuario = InfraArray::converterArrInfraDTO($arrDTO, 'IdUsuario');

                // se tiver informado o procedimento, não retorna pessoas que possam ser avaliadoras dela mesma.
                $moduloAutoAvaliacaoLiberado = MdUtlAdmPrmGrUsuINT::verificaModoluloLiberarAutoAvaliacaoAtivado();
                $arrProcedimentos = explode(",", trim($arrProcedimentos));
                if (!$moduloAutoAvaliacaoLiberado && count($arrProcedimentos)>0){
                    $arrIdsPessoasQueNaoPodeDistribuir = MdUtlAdmPrmGrUsuINT::buscarArrayPessoasNaoPodeDistribuir($arrProcedimentos);
                    $idsUsuario = array_diff($idsUsuario, $arrIdsPessoasQueNaoPodeDistribuir);
                }

                $possuiRegistrosDist = count($idsUsuario) > 0;
                if (count($idsUsuario) > 0) {
                    $objUsuarioDTO->setNumIdUsuario($idsUsuario, InfraDTO::$OPER_IN);
                }
            }
        }
    }

  $objUsuarioDTO->retNumIdUsuario();
  $objUsuarioDTO->retNumIdOrgao();
  $objUsuarioDTO->retStrSiglaOrgao();
  $objUsuarioDTO->retStrDescricaoOrgao();
  $objUsuarioDTO->retStrSigla();
  $objUsuarioDTO->retStrNome();

  if( (!is_null($idParams) || !is_null($isBolUsuarioDTO)) && !$isVazioUsers) {

    if(!is_null($idParams)) {
      $objUsuarioDTO->setNumIdMdUtlAdmPrmGr($idParams);
    }

    $numIdOrgao = PaginaSEI::getInstance()->recuperarCampo('selOrgao');
    if ($numIdOrgao !== '') {
      $objUsuarioDTO->setNumIdOrgao($numIdOrgao);
    }


    $strSiglaPesquisa = trim(PaginaSEI::getInstance()->recuperarCampo('txtSiglaUsuario'));
    if ($strSiglaPesquisa !== '') {
      $objUsuarioDTO->setStrSigla($strSiglaPesquisa);
    }

    $strNomePesquisa = PaginaSEI::getInstance()->recuperarCampo('txtNomeUsuario');
    if ($strNomePesquisa !== '') {
      $objUsuarioDTO->setStrNome($strNomePesquisa);
    }

    if ($_GET['acao'] == 'md_utl_adm_usuario_reativar') {
      //Lista somente inativos
      $objUsuarioDTO->setBolExclusaoLogica(false);
      $objUsuarioDTO->setStrSinAtivo('N');
    }

    $objUsuarioDTO->setStrStaTipo(UsuarioRN::$TU_SIP);

    PaginaSEI::getInstance()->prepararOrdenacao($objUsuarioDTO, 'Sigla', InfraDTO::$TIPO_ORDENACAO_ASC);

    PaginaSEI::getInstance()->prepararPaginacao($objUsuarioDTO);


//Para a Jornada
    if(!is_null($isBolUsuarioDTO) && $isBolUsuarioDTO == 1 && is_null($idParams) && $isBolDistribuicao != 1) {
      $objUsuarioRN     = new UsuarioRN();
      $arrObjUsuarioDTO = $objUsuarioRN->pesquisar($objUsuarioDTO);
    }

 // Para a Fila
    if(!is_null($idParams) && is_null($isBolUsuarioDTO)){
      $objUsuarioRN     = new MdUtlAdmPrmGrUsuRN();
      $arrObjUsuarioDTO = $objUsuarioRN->pesquisarUsuarioParametros($objUsuarioDTO);
    }

 //Para a Distribuição
    if($isBolDistribuicao == '1' && $possuiRegistrosDist){

      $objUsuarioRN     = new MdUtlAdmPrmGrUsuRN();
      $arrObjUsuarioDTO = $objUsuarioRN->pesquisarUsuarioParametros($objUsuarioDTO);
    }

    if(!is_null($arrObjUsuarioDTO)) {
        $arrObjUsuarioDTO = InfraArray::distinctArrInfraDTO($arrObjUsuarioDTO, 'IdUsuario');
    }


    PaginaSEI::getInstance()->processarPaginacao($objUsuarioDTO);
    $numRegistros = is_null($arrObjUsuarioDTO) ? 0 : count($arrObjUsuarioDTO);

    if ($numRegistros > 0) {

      $bolCheck = false;

      if ($_GET['acao'] == 'md_utl_adm_usuario_selecionar') {
        $bolAcaoReativar = false;
        $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_usuario_consultar');
        $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_usuario_alterar');
        $bolAcaoImprimir = false;
        $bolAcaoExcluir = false;
        $bolAcaoDesativar = false;
        $bolCheck = true;
      } else if ($_GET['acao'] == 'md_utl_adm_usuario_reativar') {
        $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_usuario_reativar');
        $bolAcaoConsultar = false;
        $bolAcaoAlterar = false;
        $bolAcaoImprimir = true;
        $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_usuario_excluir');
        $bolAcaoDesativar = false;
      } else {
        $bolAcaoReativar = false;
        $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_usuario_consultar');
        $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_usuario_alterar');
        $bolAcaoImprimir = true;
        $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_usuario_excluir');
        $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_usuario_desativar');
      }

      if ($bolAcaoDesativar) {
        $bolCheck = true;
        $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_usuario_desativar&acao_origem=' . $_GET['acao']);
      }

      if ($bolAcaoReativar) {
        $bolCheck = true;
        $arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
        $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_usuario_reativar&acao_origem=' . $_GET['acao'] . '&acao_confirmada=sim');
      }


      if ($bolAcaoExcluir) {
        $bolCheck = true;
        $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_usuario_excluir&acao_origem=' . $_GET['acao']);
      }

      if ($bolAcaoImprimir) {
        $bolCheck = true;
        $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';

      }

      $strResultado = '';

      if ($_GET['acao'] != 'md_utl_adm_usuario_reativar') {
        $strSumarioTabela = 'Tabela de Usuários.';
        $strCaptionTabela = 'Usuários';
      } else {
        $strSumarioTabela = 'Tabela de Usuários Inativos.';
        $strCaptionTabela = 'Usuários Inativos';
      }

      $strResultado .= '<table width="99%" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n";
      $strResultado .= '<caption class="infraCaption">' . PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistros) . '</caption>';
      $strResultado .= '<tr>';
      if ($bolCheck) {
        $strResultado .= '<th class="infraTh" width="1%">' . PaginaSEI::getInstance()->getThCheck() . '</th>' . "\n";
      }

      $strResultado .= '<th class="infraTh" width="10%">' . PaginaSEI::getInstance()->getThOrdenacao($objUsuarioDTO, 'ID', 'IdUsuario', $arrObjUsuarioDTO) . '</th>' . "\n";
      $strResultado .= '<th class="infraTh">' . PaginaSEI::getInstance()->getThOrdenacao($objUsuarioDTO, 'Sigla', 'Sigla', $arrObjUsuarioDTO) . '</th>' . "\n";
      $strResultado .= '<th class="infraTh">' . PaginaSEI::getInstance()->getThOrdenacao($objUsuarioDTO, 'Nome', 'Nome', $arrObjUsuarioDTO) . '</th>' . "\n";
      $strResultado .= '<th class="infraTh" width="10%">' . PaginaSEI::getInstance()->getThOrdenacao($objUsuarioDTO, 'Órgao', 'SiglaOrgao', $arrObjUsuarioDTO) . '</th>' . "\n";

      $strResultado .= '<th class="infraTh" width="15%">Ações</th>' . "\n";
      $strResultado .= '</tr>' . "\n";
      $strCssTr = '';
      for ($i = 0; $i < $numRegistros; $i++) {

        $strCssTr      = ($strCssTr == '<tr class="infraTrClara">') ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">';
        $strResultado .= $strCssTr;
        $idMain        = !is_null($isBolUsuario) && $isBolUsuario == '1' ? $arrObjUsuarioDTO[$i]->getNumIdUsuario() : $arrObjUsuarioDTO[$i]->getNumIdMdUtlAdmPrmGrUsu();

        if ($bolCheck) {
          $nomeSigla     = $arrObjUsuarioDTO[$i]->getStrNome() . ' (' . $arrObjUsuarioDTO[$i]->getStrSigla() . ')';
          $strResultado .= '<td valign="top">' . PaginaSEI::getInstance()->getTrCheck($i, $idMain, $nomeSigla) . '</td>';
        }

        $strResultado .= '<td align="center">' . $arrObjUsuarioDTO[$i]->getNumIdUsuario() . '</td>';
        $strResultado .= '<td>' . PaginaSEI::tratarHTML($arrObjUsuarioDTO[$i]->getStrSigla()) . '</td>';
        $strResultado .= '<td>' . PaginaSEI::tratarHTML($arrObjUsuarioDTO[$i]->getStrNome()) . '</td>';
        $strResultado .= '<td align="center"><a alt="' . PaginaSEI::tratarHTML($arrObjUsuarioDTO[$i]->getStrDescricaoOrgao()) . '" title="' . PaginaSEI::tratarHTML($arrObjUsuarioDTO[$i]->getStrDescricaoOrgao()) . '" class="ancoraSigla">' . PaginaSEI::tratarHTML($arrObjUsuarioDTO[$i]->getStrSiglaOrgao()) . '</a></td>';
        $strResultado .= '<td align="center">';

        $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i, $idMain);


        if ($bolAcaoConsultar) {
          $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_usuario_consultar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_usuario=' . $arrObjUsuarioDTO[$i]->getNumIdUsuario() . '&sigla=' . $arrObjUsuarioDTO[$i]->getStrSigla() . '&nome=' . $arrObjUsuarioDTO[$i]->getStrNome()) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="imagens/consultar.gif" title="Consultar Usuário" alt="Consultar Usuário" class="infraImg" /></a>&nbsp;';
        }

        if ($bolAcaoAlterar) {
          $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_usuario_alterar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_usuario=' . $arrObjUsuarioDTO[$i]->getNumIdUsuario() . '&sigla=' . $arrObjUsuarioDTO[$i]->getStrSigla() . '&nome=' . $arrObjUsuarioDTO[$i]->getStrNome()) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="imagens/alterar.gif" title="Alterar Usuário" alt="Alterar Usuário" class="infraImg" /></a>&nbsp;';
        }


        if ($bolAcaoDesativar) {
          $strResultado .= '<a href="#ID-' . $arrObjUsuarioDTO[$i]->getNumIdUsuario() . '"  onclick="acaoDesativar(\'' . $arrObjUsuarioDTO[$i]->getNumIdUsuario() . '\',\'' . $arrObjUsuarioDTO[$i]->getStrSigla() . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="imagens/desativar.gif" title="Desativar Usuário" alt="Desativar Usuário" class="infraImg" /></a>&nbsp;';
        }

        if ($bolAcaoReativar) {
          $strResultado .= '<a href="#ID-' . $arrObjUsuarioDTO[$i]->getNumIdUsuario() . '"  onclick="acaoReativar(\'' . $arrObjUsuarioDTO[$i]->getNumIdUsuario() . '\',\'' . $arrObjUsuarioDTO[$i]->getStrSigla() . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="imagens/reativar.gif" title="Reativar Usuário" alt="Reativar Usuário" class="infraImg" /></a>&nbsp;';
        }


        if ($bolAcaoExcluir) {
          $strResultado .= '<a href="#ID-' . $arrObjUsuarioDTO[$i]->getNumIdUsuario() . '"  onclick="acaoExcluir(\'' . $arrObjUsuarioDTO[$i]->getNumIdUsuario() . '\',\'' . $arrObjUsuarioDTO[$i]->getStrSigla() . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="imagens/excluir.gif" title="Excluir Usuário" alt="Excluir Usuário" class="infraImg" /></a>&nbsp;';
        }

        $strResultado .= '</td></tr>' . "\n";
      }
      $strResultado .= '</table>';
    }
  }
  if ($_GET['acao'] == 'md_utl_adm_usuario_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="C" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
  }else{
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }

  $strItensSelOrgao = OrgaoINT::montarSelectSiglaRI1358('','Todos',$numIdOrgao);

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

#lblOrgao {position:absolute;left:0%;top:0%;width:20%;}
#selOrgao {position:absolute;left:0%;top:40%;width:20%;}

#lblSiglaUsuario {position:absolute;left:25%;top:0%;width:10%;}
#txtSiglaUsuario {position:absolute;left:25%;top:40%;width:10%;}

#lblNomeUsuario {position:absolute;left:40%;top:0%;width:55%;}
#txtNomeUsuario {position:absolute;left:40%;top:40%;width:55%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
<?if(0){?><script type="text/javascript"><?}?>
  var msg100Padrao  = '<?=MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_100)?>';

function inicializar(){
  if ('<?=$_GET['acao']?>'=='md_utl_adm_usuario_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
  }else{
    document.getElementById('btnFechar').focus();
  }

  infraEfeitoTabelas();
}

<? if ($bolAcaoDesativar){ ?>
function acaoDesativar(id,desc){
  if (confirm("Confirma desativação do usuário \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmUsuarioLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmUsuarioLista').submit();
  }
}

function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum usuário selecionado.');
    return;
  }
  if (confirm("Confirma desativação dos usuários selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmUsuarioLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmUsuarioLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoReativar){ ?>
function acaoReativar(id,desc){
  if (confirm("Confirma reativação do usuário \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmUsuarioLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmUsuarioLista').submit();
  }
}

function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum usuário selecionado.');
    return;
  }
  if (confirm("Confirma reativação dos usuários selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmUsuarioLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmUsuarioLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão do usuário \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmUsuarioLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmUsuarioLista').submit();
  }
}

  function acaoExclusaoMultipla(){
    if (document.getElementById('hdnInfraItensSelecionados').value==''){
      var msg = setMensagemPersonalizada(msg100Padrao, ['Usuário']);
      alert(msg);
      return;
    }
    if (confirm("Confirma exclusão dos usuários selecionados?")){
      document.getElementById('hdnInfraItemId').value='';
      document.getElementById('frmUsuarioLista').action='<?=$strLinkExcluir?>';
      document.getElementById('frmUsuarioLista').submit();
    }
  }
  <? } ?>
  <?if(0){?></script><?}?>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');


?>
<form id="frmUsuarioLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink($urlPadrao)?>">
  <?
  //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->abrirAreaDados('5em');
  ?>
  <label id="lblOrgao" for="selOrgao" accesskey="o" class="infraLabelOpcional">Órgã<span class="infraTeclaAtalho">o</span>:</label>
  <select id="selOrgao" name="selOrgao" onchange="this.form.submit();" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" >
  <?=$strItensSelOrgao?>
  </select>

  <label id="lblSiglaUsuario" for="txtSiglaUsuario" accesskey="S" class="infraLabelOpcional"><span class="infraTeclaAtalho">S</span>igla:</label>
  <input type="text" id="txtSiglaUsuario" name="txtSiglaUsuario" class="infraText" value="<?=PaginaSEI::tratarHTML($strSiglaPesquisa)?>" maxlength="15" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  <label id="lblNomeUsuario" for="txtNomeUsuario" accesskey="N" class="infraLabelOpcional"><span class="infraTeclaAtalho">N</span>ome:</label>
  <input type="text" id="txtNomeUsuario" name="txtNomeUsuario" class="infraText" value="<?=PaginaSEI::tratarHTML($strNomePesquisa)?>" maxlength="50" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

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

?>