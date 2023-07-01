<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 23/11/2022 - criado por gustavos.colab
*
* Versão do Gerador de Código: 1.43.1
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

  #PaginaSEI::getInstance()->prepararSelecao('md_utl_adm_integracao_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  //Links dos botos
  $btnLinkNovo = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_integracao_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']);

  switch($_GET['acao']){
    case 'md_utl_adm_integracao_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjMdUtlAdmIntegracaoDTO = array();
        for ( $i = 0 ; $i < count($arrStrIds) ; $i++ ){

          // primeiro: deleta registros da Integracao Parametro
          $objMdUtlAdmIntegParamDTO = new MdUtlAdmIntegParamDTO();
          $objMdUtlAdmIntegParamRN  = new MdUtlAdmIntegParamRN();

          $objMdUtlAdmIntegParamDTO->setNumIdMdUtlAdmIntegracao( $arrStrIds[$i] );
          $objMdUtlAdmIntegParamDTO->retNumIdMdUtlAdmIntegParam();

          $arrObjMdUtlAdmIntegParamDTO = $objMdUtlAdmIntegParamRN->listar( $objMdUtlAdmIntegParamDTO );
          if (!empty($arrObjMdUtlAdmIntegParamDTO) ) $objMdUtlAdmIntegParamRN->excluir( $arrObjMdUtlAdmIntegParamDTO );

          // segundo: deleta registros da Integracao Header
          $objMdUtlAdmIntegHeaderDTO = new MdUtlAdmIntegHeaderDTO();
          $objMdUtlAdmIntegHeaderRN  = new MdUtlAdmIntegHeaderRN();

          $objMdUtlAdmIntegHeaderDTO->setNumIdMdUtlAdmIntegracao( $arrStrIds[$i] );
          $objMdUtlAdmIntegHeaderDTO->retNumIdMdUtlAdmIntegHeader();

          $arrObjMdUtlAdmIntegHeaderDTO = $objMdUtlAdmIntegHeaderRN->listar( $objMdUtlAdmIntegHeaderDTO );
          if (!empty($arrObjMdUtlAdmIntegHeaderDTO) ) $objMdUtlAdmIntegHeaderRN->excluir( $arrObjMdUtlAdmIntegHeaderDTO );

          // terceiro: deleta registro PAI
          $objMdUtlAdmIntegracaoDTO = new MdUtlAdmIntegracaoDTO();
          $objMdUtlAdmIntegracaoDTO->setNumIdMdUtlAdmIntegracao($arrStrIds[$i]);
          $arrObjMdUtlAdmIntegracaoDTO[] = $objMdUtlAdmIntegracaoDTO;
        }
        $objMdUtlAdmIntegracaoRN = new MdUtlAdmIntegracaoRN();
        $objMdUtlAdmIntegracaoRN->excluir($arrObjMdUtlAdmIntegracaoDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'md_utl_adm_integracao_desativar':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjMdUtlAdmIntegracaoDTO = array();
        for ( $i = 0 ; $i < count($arrStrIds) ; $i++ ) {
          $objMdUtlAdmIntegracaoDTO = new MdUtlAdmIntegracaoDTO();
          $objMdUtlAdmIntegracaoDTO->setNumIdMdUtlAdmIntegracao($arrStrIds[$i]);
          $objMdUtlAdmIntegracaoDTO->setStrSinAtivo('N');
          $arrObjMdUtlAdmIntegracaoDTO[] = $objMdUtlAdmIntegracaoDTO;
        }
        $objMdUtlAdmIntegracaoRN = new MdUtlAdmIntegracaoRN();
        $objMdUtlAdmIntegracaoRN->desativar($arrObjMdUtlAdmIntegracaoDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'md_utl_adm_integracao_reativar':
      $strTitulo = 'Reativar Integrações';
      if ( $_GET['acao_confirmada'] == 'sim' ) {
        try{
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjMdUtlAdmIntegracaoDTO = array();
          for ( $i = 0 ; $i < count($arrStrIds) ; $i++ ) {
            $objMdUtlAdmIntegracaoDTO = new MdUtlAdmIntegracaoDTO();
            $objMdUtlAdmIntegracaoDTO->setNumIdMdUtlAdmIntegracao($arrStrIds[$i]);
            $objMdUtlAdmIntegracaoDTO->setStrSinAtivo('S');
            $arrObjMdUtlAdmIntegracaoDTO[] = $objMdUtlAdmIntegracaoDTO;
          }
          $objMdUtlAdmIntegracaoRN = new MdUtlAdmIntegracaoRN();
          $objMdUtlAdmIntegracaoRN->reativar($arrObjMdUtlAdmIntegracaoDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        } 
        header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
        die;
      } 
      break;

    case 'md_utl_adm_integracao_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Integração','Selecionar Integrações');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='md_utl_adm_integracao_cadastrar'){
        if (isset($_GET['id_md_utl_adm_integracao'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_md_utl_adm_integracao']);
        }
      }
      break;

    case 'md_utl_adm_integracao_listar':
      $strTitulo = 'Mapeamento de Integrações';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  /* No momento não tem usabilidade este botao
  if ($_GET['acao'] == 'md_utl_adm_integracao_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }
  */

  if ( $_GET['acao'] == 'md_utl_adm_integracao_listar' || $_GET['acao'] == 'md_utl_adm_integracao_selecionar' ){
    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_integracao_cadastrar');
    if ($bolAcaoCadastrar){
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="acionarNovo()" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
    }
  }

  $objMdUtlAdmIntegracaoDTO = new MdUtlAdmIntegracaoDTO();
  $objMdUtlAdmIntegracaoDTO->retNumIdMdUtlAdmIntegracao();
  $objMdUtlAdmIntegracaoDTO->retStrNome();
  $objMdUtlAdmIntegracaoDTO->retNumFuncionalidade();
  //$objMdUtlAdmIntegracaoDTO->retStrTipoIntegracao();
  //$objMdUtlAdmIntegracaoDTO->retNumMetodoAutenticacao();
  //$objMdUtlAdmIntegracaoDTO->retNumMetodoRequisicao();
  //$objMdUtlAdmIntegracaoDTO->retStrFormatoResposta();
  //$objMdUtlAdmIntegracaoDTO->retStrVersaoSoap();
  //$objMdUtlAdmIntegracaoDTO->retStrTokenAutenticacao();
  //$objMdUtlAdmIntegracaoDTO->retStrUrlWsdl();
  //$objMdUtlAdmIntegracaoDTO->retStrOperacaoWsdl();
  $objMdUtlAdmIntegracaoDTO->retStrSinAtivo();

  if ($_GET['acao'] == 'md_utl_adm_integracao_reativar'){
    //Lista somente inativos
    $objMdUtlAdmIntegracaoDTO->setBolExclusaoLogica(false);
    $objMdUtlAdmIntegracaoDTO->setStrSinAtivo('N');
  }

  PaginaSEI::getInstance()->prepararOrdenacao($objMdUtlAdmIntegracaoDTO, 'Nome', InfraDTO::$TIPO_ORDENACAO_ASC);
  PaginaSEI::getInstance()->prepararPaginacao($objMdUtlAdmIntegracaoDTO);

  $objMdUtlAdmIntegracaoRN = new MdUtlAdmIntegracaoRN();
  $arrObjMdUtlAdmIntegracaoDTO = $objMdUtlAdmIntegracaoRN->listar($objMdUtlAdmIntegracaoDTO);

  PaginaSEI::getInstance()->processarPaginacao($objMdUtlAdmIntegracaoDTO);

  /** @var MdUtlAdmIntegracaoDTO[] $arrObjMdUtlAdmIntegracaoDTO */

  $numRegistros         = count($arrObjMdUtlAdmIntegracaoDTO);
  $strResultado         = '';
  $qtdCadastrados       = count( MdUtlAdmIntegracaoINT::getDadosFuncionalidade() );
  $qtdCadastradosAtivos = 0;

  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='md_utl_adm_integracao_selecionar'){
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_integracao_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_integracao_alterar');
      $bolAcaoImprimir = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolCheck = true;
    }else if ($_GET['acao']=='md_utl_adm_integracao_reativar'){
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_integracao_reativar');
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_integracao_consultar');
      $bolAcaoAlterar = false;
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_integracao_excluir');
      $bolAcaoDesativar = false;
    }else{
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_integracao_reativar');
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_integracao_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_integracao_alterar');
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_integracao_excluir');
      $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_integracao_desativar');
    }

    if ($bolAcaoDesativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
      $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_integracao_desativar&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoReativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
      $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_integracao_reativar&acao_origem='.$_GET['acao'].'&acao_confirmada=sim');
    }

    if ($bolAcaoExcluir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_integracao_excluir&acao_origem='.$_GET['acao']);
    }

    if ($_GET['acao']!='md_utl_adm_integracao_reativar'){
      $strSumarioTabela = 'Tabela de Integrações.';
      $strCaptionTabela = 'Integrações';
    }else{
      $strSumarioTabela = 'Tabela de Integrações Inatives.';
      $strCaptionTabela = 'Integrações Inatives';
    }

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objMdUtlAdmIntegracaoDTO,'Nome','Nome',$arrObjMdUtlAdmIntegracaoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objMdUtlAdmIntegracaoDTO,'Funcionalidade','Funcionalidade',$arrObjMdUtlAdmIntegracaoDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objMdUtlAdmIntegracaoDTO,'','TipoIntegracao',$arrObjMdUtlAdmIntegracaoDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objMdUtlAdmIntegracaoDTO,'','MetodoAutenticacao',$arrObjMdUtlAdmIntegracaoDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objMdUtlAdmIntegracaoDTO,'','MetodoRequisicao',$arrObjMdUtlAdmIntegracaoDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objMdUtlAdmIntegracaoDTO,'','FormatoResposta',$arrObjMdUtlAdmIntegracaoDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objMdUtlAdmIntegracaoDTO,'','VersaoSoap',$arrObjMdUtlAdmIntegracaoDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objMdUtlAdmIntegracaoDTO,'','TokenAutenticacao',$arrObjMdUtlAdmIntegracaoDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objMdUtlAdmIntegracaoDTO,'','UrlWsdl',$arrObjMdUtlAdmIntegracaoDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objMdUtlAdmIntegracaoDTO,'','OperacaoWsdl',$arrObjMdUtlAdmIntegracaoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="20%">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      if ($arrObjMdUtlAdmIntegracaoDTO[$i]->getStrSinAtivo() == 'S' ) $qtdCadastradosAtivos++;

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjMdUtlAdmIntegracaoDTO[$i]->getNumIdMdUtlAdmIntegracao(),$arrObjMdUtlAdmIntegracaoDTO[$i]->getStrNome()).'</td>';
      }
      $strResultado .= '<td class="txt-col-center">'.PaginaSEI::tratarHTML($arrObjMdUtlAdmIntegracaoDTO[$i]->getStrNome()).'</td>';
      $strResultado .= '<td class="txt-col-center">'.PaginaSEI::tratarHTML(MdUtlAdmIntegracaoINT::montarSelectFuncionalidade(null,$arrObjMdUtlAdmIntegracaoDTO[$i]->getNumFuncionalidade())).'</td>';
      //$strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjMdUtlAdmIntegracaoDTO[$i]->getStrTipoIntegracao()).'</td>';
      //$strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjMdUtlAdmIntegracaoDTO[$i]->getNumMetodoAutenticacao()).'</td>';
      //$strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjMdUtlAdmIntegracaoDTO[$i]->getNumMetodoRequisicao()).'</td>';
      //$strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjMdUtlAdmIntegracaoDTO[$i]->getStrFormatoResposta()).'</td>';
      //$strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjMdUtlAdmIntegracaoDTO[$i]->getStrVersaoSoap()).'</td>';
      //$strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjMdUtlAdmIntegracaoDTO[$i]->getStrTokenAutenticacao()).'</td>';
      //$strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjMdUtlAdmIntegracaoDTO[$i]->getStrUrlWsdl()).'</td>';
      //$strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjMdUtlAdmIntegracaoDTO[$i]->getStrOperacaoWsdl()).'</td>';
      $strResultado .= '<td align="right">';

      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjMdUtlAdmIntegracaoDTO[$i]->getNumIdMdUtlAdmIntegracao());

      if ($bolAcaoConsultar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_integracao_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_md_utl_adm_integracao='.$arrObjMdUtlAdmIntegracaoDTO[$i]->getNumIdMdUtlAdmIntegracao()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeConsultar().'" title="Consultar Integração" alt="Consultar Integração" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_integracao_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_md_utl_adm_integracao='.$arrObjMdUtlAdmIntegracaoDTO[$i]->getNumIdMdUtlAdmIntegracao()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar Integração" alt="Alterar Integração" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir){
        $strId = $arrObjMdUtlAdmIntegracaoDTO[$i]->getNumIdMdUtlAdmIntegracao();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjMdUtlAdmIntegracaoDTO[$i]->getStrNome());
      }

      if ($bolAcaoDesativar && $arrObjMdUtlAdmIntegracaoDTO[$i]->getStrSinAtivo() == 'S'){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeDesativar().'" title="Desativar Integração" alt="Desativar Integração" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar && $arrObjMdUtlAdmIntegracaoDTO[$i]->getStrSinAtivo() == 'N'){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoReativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeReativar().'" title="Reativar Integração" alt="Reativar Integração" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoExcluir){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeExcluir().'" title="Excluir Integração" alt="Excluir Integração" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'md_utl_adm_integracao_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }else{
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }

  $strBloquearNovoCadastro = $qtdCadastradosAtivos == $qtdCadastrados ? 'S' : 'N';

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
require 'md_utl_geral_css.php';
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmMdUtlAdmIntegracaoLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->abrirAreaDados();
  PaginaSEI::getInstance()->fecharAreaDados();
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  //PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>

<script type="text/javascript">

    function inicializar(){
        if ('<?=$_GET['acao']?>'=='md_utl_adm_integracao_selecionar'){
            infraReceberSelecao();
            document.querySelector('#btnFecharSelecao').focus();
        }else{
            document.querySelector('#btnFechar').focus();
        }
        infraEfeitoTabelas(true);
    }

    <? if ($bolAcaoDesativar){ ?>
        function acaoDesativar(id,desc){
            if (confirm("Confirma desativação de Integração \""+desc+"\"?")){
                document.querySelector('#hdnInfraItemId').value=id;
                document.querySelector('#frmMdUtlAdmIntegracaoLista').action='<?=$strLinkDesativar?>';
                document.querySelector('#frmMdUtlAdmIntegracaoLista').submit();
            }
        }
    <? } ?>

    <? if ($bolAcaoReativar){ ?>
        function acaoReativar(id,desc){
            if (confirm("Confirma reativação de Integração \""+desc+"\"?")){
                document.querySelector('#hdnInfraItemId').value=id;
                document.querySelector('#frmMdUtlAdmIntegracaoLista').action='<?=$strLinkReativar?>';
                document.querySelector('#frmMdUtlAdmIntegracaoLista').submit();
            }
        }
    <? } ?>

    <? if ($bolAcaoExcluir){ ?>
        function acaoExcluir(id,desc){
            if (confirm("Confirma exclusão de Integração \""+desc+"\"?")){
                document.querySelector('#hdnInfraItemId').value=id;
                document.querySelector('#frmMdUtlAdmIntegracaoLista').action='<?=$strLinkExcluir?>';
                document.querySelector('#frmMdUtlAdmIntegracaoLista').submit();
            }
        }
    <? } ?>

    function acionarNovo(){
        <?php if ( $strBloquearNovoCadastro == 'S' ): ?>
            alert('Todas as integrações do Controle de Desempenho já foram mapeadas.');
        <?php else: ?>
            location.href = "<?= $btnLinkNovo ?>";
        <?php endif; ?>
    }

</script>

<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
