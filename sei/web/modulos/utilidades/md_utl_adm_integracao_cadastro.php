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

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  // Links para consulta Ajax
  $strLinkValidarWsdl = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_utl_integracao_busca_operacao');

  // Instancia classes RN e DTO
  $objMdUtlAdmIntegracaoDTO = new MdUtlAdmIntegracaoDTO();

  $objMdUtlAdmIntegracaoRN  = new MdUtlAdmIntegracaoRN();
  $objMdUtlAdmIntegHeaderRN = new MdUtlAdmIntegHeaderRN();
  $objMdUtlAdmIntegParamRN  = new MdUtlAdmIntegParamRN();

  // Variaveis globais
  $strDesabilitar   = '';
  $strTbHeader      = '';
  $strTipoAcao      = '';
  $isRest           = false;
  $arrDados         = [];
  $arrConfig        = ['hab_soap' => false , 'hab_rest' => false];
  $tpFuncionalidade = null;
  $metAutenticacao  = null;
  $vlrSelOperacao   = '';
  $selOperacao      = null;
  $arrFuncionalidadesCadastradas = null;

  $arrComandos = array();

  switch($_GET['acao']){
    case 'md_utl_adm_integracao_cadastrar':
	  $strTipoAcao = 'cadastrar';
      $strTitulo = 'Novo Mapeamento de Integração';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarMdUtlAdmIntegracao" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objMdUtlAdmIntegracaoDTO->setNumIdMdUtlAdmIntegracao(null);
      $objMdUtlAdmIntegracaoDTO->setStrNome($_POST['txtNome']);
      $objMdUtlAdmIntegracaoDTO->setNumFuncionalidade($_POST['selFuncionalidade']);
      $objMdUtlAdmIntegracaoDTO->setStrTipoIntegracao($_POST['rdnTpIntegracao']);
      $objMdUtlAdmIntegracaoDTO->setNumMetodoAutenticacao($_POST['selMetodoAutenticacao']);
      $objMdUtlAdmIntegracaoDTO->setNumMetodoRequisicao($_POST['selMetodoRequisicao']);
      $objMdUtlAdmIntegracaoDTO->setNumFormatoResposta($_POST['selFormato']);
      $objMdUtlAdmIntegracaoDTO->setStrVersaoSoap($_POST['selVersaoSoap']);
      $objMdUtlAdmIntegracaoDTO->setStrUrlWsdl($_POST['txtUrlDefServico']);
      $objMdUtlAdmIntegracaoDTO->setStrOperacaoWsdl($_POST['txtUrlServico']);
      $objMdUtlAdmIntegracaoDTO->setStrSinAtivo('S');

      $arrFuncionalidadesCadastradas = $objMdUtlAdmIntegracaoRN->buscaFuncionalidadesCadastradas();

      if (isset($_POST['sbmCadastrarMdUtlAdmIntegracao'])) {
        try{
	      $idFunc   = $_POST['selFuncionalidade'];
	      $vlrToken = $_POST['txtTokenAut'.$idFunc];
	      $objMdUtlAdmIntegracaoDTO->setStrTokenAutenticacao( empty($vlrToken) ? null : MdUtlAdmIntegracaoINT::gerenciaDadosRestritos( $vlrToken ) );

          $objMdUtlAdmIntegracaoDTO = $objMdUtlAdmIntegracaoRN->cadastrar($objMdUtlAdmIntegracaoDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Integração "'.$objMdUtlAdmIntegracaoDTO->getStrNome().'" cadastrada com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_md_utl_adm_integracao='.$objMdUtlAdmIntegracaoDTO->getNumIdMdUtlAdmIntegracao().PaginaSEI::getInstance()->montarAncora($objMdUtlAdmIntegracaoDTO->getNumIdMdUtlAdmIntegracao())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'md_utl_adm_integracao_alterar':
	  $strTipoAcao = 'alterar';
      $strTitulo = 'Alterar Mapeamento de Integração';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarMdUtlAdmIntegracao" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_md_utl_adm_integracao'])){
        $objMdUtlAdmIntegracaoDTO->setNumIdMdUtlAdmIntegracao( $_GET['id_md_utl_adm_integracao'] );
        $objMdUtlAdmIntegracaoDTO->retTodos();
        $objMdUtlAdmIntegracaoRN = new MdUtlAdmIntegracaoRN();
        $objMdUtlAdmIntegracaoDTO = $objMdUtlAdmIntegracaoRN->consultar($objMdUtlAdmIntegracaoDTO);

        if ($objMdUtlAdmIntegracaoDTO==null){
          throw new InfraException("Registro não encontrado.");
        }

        // habilta ou nao dados relacionados ao SOAP ou REST
        if ( $objMdUtlAdmIntegracaoDTO->getStrTipoIntegracao() == 'SO') $arrConfig['hab_soap'] = true;
        if ( $objMdUtlAdmIntegracaoDTO->getStrTipoIntegracao() == 'RE') $isRest = $arrConfig['hab_rest'] = true;

	    // retorna o nome da operacao
	    $selOperacao = MdUtlAdmIntegracaoINT::geraOperacao( $objMdUtlAdmIntegracaoDTO->getStrOperacaoWsdl() );

        // busca dados para popular a grid "Headers"
        $arrDadosHeader    = $objMdUtlAdmIntegHeaderRN->montarArrHeaders( $_GET['id_md_utl_adm_integracao'] );
        $strTbHeader       = PaginaSEI::getInstance()->gerarItensTabelaDinamica( $arrDadosHeader['itensTabela'] );
        $strIdsItensHeader = $arrDadosHeader['strIdsItensHeader'];

        // busca dados de Entrada/Saida
        $strDados = $objMdUtlAdmIntegParamRN->buscaDadosEntradaSaida( [ $_GET['id_md_utl_adm_integracao'] , true ] );

        // retorna o metodo de autenticacao
        $metAutenticacao  = $objMdUtlAdmIntegracaoDTO->getNumMetodoAutenticacao();
	    $tpFuncionalidade = $objMdUtlAdmIntegracaoDTO->getNumFuncionalidade();

      } else {
        $objMdUtlAdmIntegracaoDTO->setNumIdMdUtlAdmIntegracao($_POST['hdnIdMdUtlAdmIntegracao']);
        $objMdUtlAdmIntegracaoDTO->setStrNome($_POST['txtNome']);
        $objMdUtlAdmIntegracaoDTO->setNumFuncionalidade($_POST['selFuncionalidade']);
        $objMdUtlAdmIntegracaoDTO->setStrTipoIntegracao($_POST['rdnTpIntegracao']);
        $objMdUtlAdmIntegracaoDTO->setNumMetodoAutenticacao($_POST['selMetodoAutenticacao']);
        $objMdUtlAdmIntegracaoDTO->setNumMetodoRequisicao($_POST['selMetodoRequisicao']);
        $objMdUtlAdmIntegracaoDTO->setNumFormatoResposta($_POST['selFormato']);
        $objMdUtlAdmIntegracaoDTO->setStrVersaoSoap($_POST['selVersaoSoap']);
	    $objMdUtlAdmIntegracaoDTO->setStrUrlWsdl($_POST['txtUrlDefServico']);
	    $objMdUtlAdmIntegracaoDTO->setStrOperacaoWsdl($_POST['txtUrlServico']);
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objMdUtlAdmIntegracaoDTO->getNumIdMdUtlAdmIntegracao())).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarMdUtlAdmIntegracao'])) {
        try{
	      $idFunc   = $_POST['selFuncionalidade'];
	      $vlrToken = $_POST['txtTokenAut'.$idFunc] == MdUtlAdmIntegracaoRN::$INFO_RESTRITO
                ? $_POST['hdnTokenAut'.$idFunc]
                : $_POST['txtTokenAut'.$idFunc];

	      $objMdUtlAdmIntegracaoDTO->setStrTokenAutenticacao( empty($vlrToken) ? null : MdUtlAdmIntegracaoINT::gerenciaDadosRestritos( $vlrToken ) );

          $objMdUtlAdmIntegracaoRN = new MdUtlAdmIntegracaoRN();
          $objMdUtlAdmIntegracaoRN->alterar( $objMdUtlAdmIntegracaoDTO );
          PaginaSEI::getInstance()->adicionarMensagem('Integração "'.$objMdUtlAdmIntegracaoDTO->getStrNome().'" alterada com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objMdUtlAdmIntegracaoDTO->getNumIdMdUtlAdmIntegracao())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'md_utl_adm_integracao_consultar':
	  $strTipoAcao = 'consultar';
      $strTitulo = 'Consultar Mapeamento de Integração';
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_md_utl_adm_integracao'])).'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objMdUtlAdmIntegracaoDTO->setNumIdMdUtlAdmIntegracao($_GET['id_md_utl_adm_integracao']);
      $objMdUtlAdmIntegracaoDTO->setBolExclusaoLogica(false);
      $objMdUtlAdmIntegracaoDTO->retTodos();
      $objMdUtlAdmIntegracaoRN = new MdUtlAdmIntegracaoRN();
      $objMdUtlAdmIntegracaoDTO = $objMdUtlAdmIntegracaoRN->consultar($objMdUtlAdmIntegracaoDTO);

      // habilta ou nao dados relacionados ao SOAP ou REST
      if ( $objMdUtlAdmIntegracaoDTO->getStrTipoIntegracao() == 'SO') $arrConfig['hab_soap'] = true;
      if ( $objMdUtlAdmIntegracaoDTO->getStrTipoIntegracao() == 'RE') $isRest = $arrConfig['hab_rest'] = true;

      // retorna o nome da operacao
      $strOperacao = MdUtlAdmIntegracaoINT::geraOperacao( $objMdUtlAdmIntegracaoDTO->getStrOperacaoWsdl() );

      // busca dados para popular a grid "Headers"
      $arrDadosHeader    = $objMdUtlAdmIntegHeaderRN->montarArrHeaders( $_GET['id_md_utl_adm_integracao'] );
      $strTbHeader       = PaginaSEI::getInstance()->gerarItensTabelaDinamica( $arrDadosHeader['itensTabela'] );
      $strIdsItensHeader = $arrDadosHeader['strIdsItensHeader'];

      // busca dados de Entrada/Saida
      $arrDados = $objMdUtlAdmIntegParamRN->buscaDadosEntradaSaida( [ $_GET['id_md_utl_adm_integracao'] ] );

      // retorna o metodo de autenticacao
      $metAutenticacao  = $objMdUtlAdmIntegracaoDTO->getNumMetodoAutenticacao();
	  $tpFuncionalidade = $objMdUtlAdmIntegracaoDTO->getNumFuncionalidade();

      // retorna o nome da operacao
      $selOperacao = MdUtlAdmIntegracaoINT::geraOperacao( $objMdUtlAdmIntegracaoDTO->getStrOperacaoWsdl() );

      if ($objMdUtlAdmIntegracaoDTO===null){
        throw new InfraException("Registro não encontrado.");
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
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
require 'md_utl_geral_css.php';

PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmMdUtlAdmIntegracaoCadastro" method="post" onsubmit="return OnSubmitForm();"
      action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados();
?>

<div class="row mb-2">
    <div class="col-sm-12 col-md-10">
        <label id="lblFuncionalidade" for="Funcionalidade"  class="infraLabelObrigatorio">Funcionalidade:</label>
        <select id="selFuncionalidade" name="selFuncionalidade" class="infraSelect form-control"
                tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
          <?= MdUtlAdmIntegracaoINT::montarSelectFuncionalidade(
                  $objMdUtlAdmIntegracaoDTO->getNumFuncionalidade(),
                  false,
                  $arrFuncionalidadesCadastradas
            )
          ?>
        </select>
    </div>
</div>

<div class="row mb-2">
  <div class="col-sm-12 col-md-10">
    <label id="lblNome" for="txtNome" class="infraLabelObrigatorio">Nome:</label>
    <input type="text" id="txtNome" name="txtNome" class="infraText form-control"
            value="<?=PaginaSEI::tratarHTML($objMdUtlAdmIntegracaoDTO->getStrNome());?>"
            onkeypress="return infraMascaraTexto(this,event,100);" maxlength="100"
            tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  </div>
</div>

<div class="row mb-2">
  <div class="col-sm-12 col-lg-12 mb-2">
    <label id="lblTipoIntegracao" class="infraLabelObrigatorio">Tipo de Integração:</label>
    <div id="divRadiosTpIntegracao">
      <div class="form-check-inline">
        <div class="infraRadioDiv">
            <input type="radio" name="rdnTpIntegracao" id="rdnTpSemIntegracao"
                   value="<?= MdUtlAdmIntegracaoRN::$TP_INTEGRACAO_SEM_AUTENTICACAO ?>" class="infraRadioInput"
              <?= $objMdUtlAdmIntegracaoDTO->getStrTipoIntegracao() == MdUtlAdmIntegracaoRN::$TP_INTEGRACAO_SEM_AUTENTICACAO ? 'checked' : ''?>>
            <label class="infraRadioLabel" for="rdnTpSemIntegracao"></label>
        </div>
        <label id="lblSemIntegracao" name="lblSemIntegracao" for="rdnTpSemIntegracao" class="infraLabelOpcional infraLabelRadio">Sem Integração</label>
      </div>

      <div class="form-check-inline">
        <div class="infraRadioDiv">
          <input type="radio" name="rdnTpIntegracao" id="rdnTpIntegracaoSoap" value="<?= MdUtlAdmIntegracaoRN::$TP_INTEGRACAO_SOAP ?>" class="infraRadioInput"
            <?= $objMdUtlAdmIntegracaoDTO->getStrTipoIntegracao() == MdUtlAdmIntegracaoRN::$TP_INTEGRACAO_SOAP ? 'checked' : ''?>>
          <label class="infraRadioLabel" for="rdnTpIntegracaoSoap"></label>
        </div>
        <label id="lblIntegracaoSoap" name="lblIntegracaoSoap" for="rdnTpIntegracaoSoap" class="infraLabelOpcional infraLabelRadio">SOAP</label>
      </div>

      <div class="form-check-inline">
        <div class="infraRadioDiv">
          <input type="radio" name="rdnTpIntegracao" id="rdnTpIntegracaoRest" value="<?= MdUtlAdmIntegracaoRN::$TP_INTEGRACAO_REST ?>" class="infraRadioInput"
            <?= $objMdUtlAdmIntegracaoDTO->getStrTipoIntegracao() == MdUtlAdmIntegracaoRN::$TP_INTEGRACAO_REST ? 'checked' : ''?>>
          <label class="infraRadioLabel" for="rdnTpIntegracaoRest"></label>
        </div>
        <label id="lblIntegracaoRest" name="lblIntegracaoRest" for="rdnTpIntegracaoRest" class="infraLabelOpcional infraLabelRadio">REST</label>
      </div>
    </div>
  </div>

  <div class="col-sm-12 col-md-10 col-lg-3 mb-2 selSOAP" <?= $arrConfig['hab_soap'] == true ? '' : 'style="display: none;"'?> >
    <label id="lblVersaoSOAP" class="infraLabelObrigatorio">Versão SOAP:</label>
    <select id="selVersaoSOAP" name="selVersaoSOAP" class="infraSelect form-control"
            tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
      <option value="">Selecione</option>
      <option value="1.2">1.2</option>
      <option value="1.1">1.1</option>
    </select>
  </div>

  <div class="col-sm-12 col-md-10 col-lg-3 mb-2 selREST" <?= $arrConfig['hab_rest'] ? '' : 'style="display: none;"'?> >
    <label id="lblMetodoRequisicao" class="infraLabelObrigatorio">Método da Requisição:</label>
    <select id="selMetodoRequisicao" name="selMetodoRequisicao" class="infraSelect form-control"
            tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
      <?= MdUtlAdmIntegracaoINT::montarSelectMetdoRequisicao(PaginaSEI::tratarHTML($objMdUtlAdmIntegracaoDTO->getNumMetodoRequisicao()))?>
    </select>
  </div>

  <div class="col-sm-12 col-md-10 col-lg-4 mb-2 selREST" <?= $arrConfig['hab_rest'] ? '' : 'style="display: none;"'?>>
    <label id="lblMetodoAutenticacao" class="infraLabelObrigatorio">Método de Autenticação:</label>
    <select id="selMetodoAutenticacao" name="selMetodoAutenticacao" class="infraSelect form-control"
            tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
      <?= MdUtlAdmIntegracaoINT::montarSelectMetdoAutenticacao(PaginaSEI::tratarHTML($objMdUtlAdmIntegracaoDTO->getNumMetodoAutenticacao()))?>
    </select>
  </div>

  <div class="col-sm-12 col-md-10 col-lg-3 selREST" <?= $arrConfig['hab_rest'] ? '' : 'style="display: none;"'?>>
    <label id="lblFormato" class="infraLabelObrigatorio">Formato do Retorno da Operação:</label>
    <select id="selFormato" name="selFormato" class="infraSelect form-control"
            tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
      <?= MdUtlAdmIntegracaoINT::montarSelectFormato(PaginaSEI::tratarHTML($objMdUtlAdmIntegracaoDTO->getNumFormatoResposta()))?>
    </select>
  </div>
  
</div>

<div class="dvConteudo" style="width:100%; <?= ($arrConfig['hab_soap'] || $arrConfig['hab_rest'] ) ? '' : 'display: none'?>">
  <div class="row mb-2">
    <div class="col-sm-12 col-md-10">
      <label id="lblUrlServico" for="txtUrlServico" class="infraLabelObrigatorio">URL do Endpoint da Operação:</label>
      <img id="imgDefServico" align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg"
             name="ajuda" <?= PaginaSEI::montarTitleTooltip('Informar URL que consta o ENDPOINT do serviço a ser solicitado.','Ajuda') ?> />
      <div class="input-group">
        <input type="text" id="txtUrlServico" name="txtUrlServico" class="infraText form-control"
               value="<?=PaginaSEI::tratarHTML($objMdUtlAdmIntegracaoDTO->getStrOperacaoWsdl());?>"
               onkeypress="return infraMascaraTexto(this,event,100);"
               maxlength="100"
               tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      </div>
    </div>
  </div>

  <div class="row mb-2">
    <div class="col-sm-12 col-md-10">
      <label id="lblUrlDefServico" for="txtUrlDefServico" class="infraLabelObrigatorio">URL Definição do Serviço:</label>
      <img id="imgDefServico" align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg"
             name="ajuda" <?= PaginaSEI::montarTitleTooltip('Informar URL que consta a documentação/informação sobre todos os dados do Serviço a ser usado com a extensão ".json".','Ajuda') ?> />
      <div class="input-group">
        <input type="text" id="txtUrlDefServico" name="txtUrlDefServico" class="infraText form-control mr-2"
              value="<?=PaginaSEI::tratarHTML($objMdUtlAdmIntegracaoDTO->getStrUrlWsdl());?>"
              onkeypress="return infraMascaraTexto(this,event,100);"
              maxlength="100"
              tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
        <button type="button" class="infraButton btnFormulario" accesskey="m" onclick="validarMapear()">
            <span class="infraTeclaAtalho">M</span>apear
        </button>
      </div>
    </div>
  </div>

  <div class="row mb-2" id="divHeader">
    <div class="col-12">
      <fieldset class="infraFieldset p-3">
        <legend class="infraLegend">Headers</legend>

        <div class="row">
          <div class="col-md-4">
            <label class="infraLabelOpcional">Atributo</label>
            <input type="text" class="infraText form-control input_header" id="atributoHeader" name="atributoHeader">
          </div>

          <div class="col-md-4">
            <label class="infraLabelOpcional">Conteúdo</label>
            <input type="text" class="infraText form-control input_header" id="conteudoHeader" name="conteudoHeader">
          </div>

          <div class="col-md-2" style="padding-top: 2.0em;">
            <div class="form-check-inline infraCheckboxDiv" style="margin-left: 0px;">
              <input type="checkbox" name="ckbDadoRestrito" id="ckbDadoRestrito" class="infraCheckboxInput input_header" value="S">
              <label class="infraCheckboxLabel" for="ckbDadoRestrito"></label>
            </div>
            <label class="infraLabelOpcional" for="ckbDadoRestrito" style="margin-left: -3px">
                Dado Restrito
            </label>
          </div>
          <div class="col-md-2" style="padding-top:1.7em">
            <button type="button" class="infraButton btnFormulario" onclick="adicionarHeaderTable()" accesskey="a">
                <span class="infraTeclaAtalho">A</span>dicionar
            </button>
          </div>
        </div>

        <div class="row">
          <div class="col-12">
            <table class="infraTable table" id="tblHeaders" summary="Headers">
              <caption class="infraCaption">&nbsp;</caption>
              <thead>
                <th style='display:none;'>#</th>
                <th class="infraTh txt-col-left">Atributo</th>
                <th class="infraTh txt-col-left">Conteúdo</th>
                <th class="infraTh txt-col-left">Dado Restrito</th>
                <th style="display: none;">Conteudo Original</th>
                <th class="infraTh">Ações</th>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
      </fieldset>
    </div>
  </div>

  <!-- DADOS DE ENTRADA -->
  <?= MdUtlAdmIntegracaoINT::geraDadosEntrada( $arrDados , $metAutenticacao , $tpFuncionalidade, $objMdUtlAdmIntegracaoDTO ) ?>

  <!-- DADOS DE SAIDA -->
  <?= MdUtlAdmIntegracaoINT::geraDadosSaida( $arrDados , $tpFuncionalidade ) ?>

</div>
<? PaginaSEI::getInstance()->fecharAreaDados(); ?>
<input type="hidden" id="hdnIdMdUtlAdmIntegracao" name="hdnIdMdUtlAdmIntegracao" value="<?= $objMdUtlAdmIntegracaoDTO->getNumIdMdUtlAdmIntegracao() ?>" />
<input type="hidden" id="hdnTbHeaders" name="hdnTbHeaders" value="<?= $strTbHeader ?>">
<input type="hidden" id="hdnTipoAcao" value="<?= $strTipoAcao ?>">
<input type="hidden" id="hdnIsRest" value="<?= $isRest ? 's' : 'n' ?>">
<input type="hidden" id="hdnIdsItensHeader" name="hdnIdsItensHeader" value="<?= $strIdsItensHeader ?>">

<?
//PaginaSEI::getInstance()->montarAreaDebug();
PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
?>
</form>

<?
require 'md_utl_geral_js.php';
require 'md_utl_adm_integracao_cadastro_js.php';

PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
