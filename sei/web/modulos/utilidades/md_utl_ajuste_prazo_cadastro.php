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
  $objMdUtlAjustePrazoRN = new MdUtlAjustePrazoRN();
    //Id tipo de controle
  $idControleDesempenho = array_key_exists('id_controle_desempenho', $_GET) ? $_GET['id_controle_desempenho'] : $_POST['hdnIdControleDesempenho'];
  $isGerir              = array_key_exists('is_gerir', $_GET) ? $_GET['is_gerir'] : $_POST['hdnIsTelaGerir'];
  $isTelaGerir          = $isGerir == 1;

  SessaoSEI::getInstance()->validarLink();
  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $objControleDesempenhoRN  = new MdUtlControleDsmpRN();
  $objControleDesempenhoDTO = $objControleDesempenhoRN->getObjControleDsmpPorId($idControleDesempenho);

  $idProcedimento           = $objControleDesempenhoDTO->getDblIdProcedimento();

  $objMdUtlAdmTpCtrlUndRN   = new MdUtlAdmRelTpCtrlDesempUndRN();
  $idTipoControle           =  $objMdUtlAdmTpCtrlUndRN->getTipoControleUnidadeLogada();


  $staStatus    = trim($objControleDesempenhoDTO->getStrStaAtendimentoDsmp());
  $arrSituacao  = MdUtlControleDsmpINT::retornaArrSituacoesControleDsmpCompleto();
  $strStatus    = $arrSituacao[$staStatus];

  $arrDataHoraCompleta = explode(' ', $objControleDesempenhoDTO->getDthPrazoTarefa());
  $dthFormatada = count($arrDataHoraCompleta) >0 ? $arrDataHoraCompleta[0] : '';

  $tipoSolicitacao = null;

  $strUrlCalcularPrazoJust  = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=calcular_prazo_data_just');
  $strDesabilitar = '';
  $selTipoJustificativaNull = MdUtlAdmJustPrazoINT::montarSelectJustificativa($idTipoControle);

  $arrComandos = array();
  $objMdUtlAjustePrazoDTO = new MdUtlAjustePrazoDTO();
  $isAlterar = false;
  $displayJustNull = '';
  $displayJustDilc = $displayJustInt = $displayJustSusp = 'display:none';
  $utlObrigatorioNull = 'utlCampoObrigatorio="a"';
  $utlObrigatorioDilc = $utlObrigatorioInt = $utlObrigatorioSusp = '';
  $strDisabled     = 'disabled=disabled';
  $dthPrazo        = '';
  $intDiasUteis    = '';
  $intDiasUteisExcedentes = 0;


  switch($_GET['acao']){
    case 'md_utl_ajuste_prazo_cadastrar':

      $strTitulo = 'Nova Solicitação de Ajuste de Prazo';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarMdUtlAjustePrazo" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTipoControle).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';
      $idAjustePrazo = 0;
      $strObservacao = '';

      $selTipoSolicitacao       = MdUtlControleDsmpINT::montarSelectTipoSolicitacao();
      $selTipoJustificativaDila = MdUtlAdmJustPrazoINT::montarSelectJustificativa($idTipoControle, MdUtlControleDsmpRN::$TP_SOLICITACAO_DILACAO);
      $selTipoJustificativaSusp = MdUtlAdmJustPrazoINT::montarSelectJustificativa($idTipoControle, MdUtlControleDsmpRN::$TP_SOLICITACAO_SUSPENSAO);
      $selTipoJustificativaInte = MdUtlAdmJustPrazoINT::montarSelectJustificativa($idTipoControle, MdUtlControleDsmpRN::$TP_SOLICITACAO_INTERRUPCAO);

      if (isset($_POST['sbmCadastrarMdUtlAjustePrazo'])) {
        try{
          $objMdUtlAjustePrazoDTO->setNumIdMdUtlAjustePrazo(null);
          $objMdUtlAjustePrazoDTO->setStrStaTipoSolicitacao($_POST['selTipoSolicitacao']);
          $objMdUtlAjustePrazoDTO->setDthPrazoSolicitacao($_POST['txtPrazoData']);
          $objMdUtlAjustePrazoDTO->setNumIdMdUtlAdmJustPrazo($_POST['hdnIdSelJustificativa']);
          $objMdUtlAjustePrazoDTO->setStrSinAtivo('S');
          $objMdUtlAjustePrazoDTO->setStrStaSolicitacao(MdUtlAjustePrazoRN::$PENDENTE_RESPOSTA);
          $objMdUtlAjustePrazoDTO->setStrObservacao($_POST['txaObservacao']);
          $objMdUtlAjustePrazoDTO->setDthPrazoInicial($objControleDesempenhoDTO->getDthPrazoTarefa());

          $objPrazoRN = new MdUtlPrazoRN();
          $qtdDiasUteis = $objPrazoRN->retornaQtdDiaUtil(InfraData::getStrDataAtual(), $objControleDesempenhoDTO->getDthPrazoTarefa());
          $objMdUtlAjustePrazoDTO->setNumDiasUteisExcedentes($qtdDiasUteis);

          $objControleDesempenhoNovoDTO = $objMdUtlAjustePrazoRN->solicitarAjustePrazo(array($objMdUtlAjustePrazoDTO, $objControleDesempenhoDTO));
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_procedimento_selecionado='.$objControleDesempenhoNovoDTO->getDblIdProcedimento()));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'md_utl_ajuste_prazo_alterar':
      $strTitulo     = 'Alterar Solicitação de Ajuste de Prazo';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarMdUtlAjustePrazo" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTipoControle).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';
      $isAlterar     = true;
      $objPrazoRN    = new MdUtlPrazoRN();

      $idAjustePrazo = array_key_exists('id_ajuste_prazo', $_GET) ? $_GET['id_ajuste_prazo'] : $_POST['hdnIdMdUtlAjustePrazo'];
      $objMdUtlAjustePrazoDTO->setNumIdMdUtlAjustePrazo($idAjustePrazo);
      $objMdUtlAjustePrazoDTO->retTodos();
      $objMdUtlAjustePrazoDTO->retStrNomeJustificativa();
      $objMdUtlAjustePrazoDTO->setNumMaxRegistrosRetorno(1);
      $objMdUtlAjustePrazoDTO = $objMdUtlAjustePrazoRN->consultar($objMdUtlAjustePrazoDTO);

      $tipoSolicitacao    = $objMdUtlAjustePrazoDTO->getStrStaTipoSolicitacao();
      $selTipoSolicitacao = MdUtlControleDsmpINT::montarSelectTipoSolicitacao($tipoSolicitacao);

      $strDisabled     = '';
      $displayJustNull = 'display:none';
      $vlDilacao = $vlSuspensao = $vlInterrupcao = null;
      $utlObrigatorioNull = '';

        if ($isTelaGerir) {
            $displayJustNull = '';
            $selTipoJustificativaNull = '<option value="' . $objMdUtlAjustePrazoDTO->getNumIdMdUtlAdmJustPrazo() . '">' . $objMdUtlAjustePrazoDTO->getStrNomeJustificativa() . '</option>';
        } else {
            if ($tipoSolicitacao == MdUtlControleDsmpRN::$TP_SOLICITACAO_DILACAO) {
                $displayJustDilc = '';
                $vlDilacao = $objMdUtlAjustePrazoDTO->getNumIdMdUtlAdmJustPrazo();
                $utlObrigatorioDilc = 'utlCampoObrigatorio="a"';
            }

            if ($tipoSolicitacao == MdUtlControleDsmpRN::$TP_SOLICITACAO_SUSPENSAO) {
                $displayJustSusp = '';
                $vlSuspensao = $objMdUtlAjustePrazoDTO->getNumIdMdUtlAdmJustPrazo();
                $utlObrigatorioSusp = 'utlCampoObrigatorio="a"';
            }

            if ($tipoSolicitacao == MdUtlControleDsmpRN::$TP_SOLICITACAO_INTERRUPCAO) {
                $displayJustInt = '';
                $vlInterrupcao = $objMdUtlAjustePrazoDTO->getNumIdMdUtlAdmJustPrazo();
                $utlObrigatorioInt = 'utlCampoObrigatorio="a"';
            }

            $selTipoJustificativaDila = MdUtlAdmJustPrazoINT::montarSelectJustificativa($idTipoControle, MdUtlControleDsmpRN::$TP_SOLICITACAO_DILACAO, $vlDilacao);
            $selTipoJustificativaSusp = MdUtlAdmJustPrazoINT::montarSelectJustificativa($idTipoControle, MdUtlControleDsmpRN::$TP_SOLICITACAO_SUSPENSAO, $vlSuspensao);
            $selTipoJustificativaInte = MdUtlAdmJustPrazoINT::montarSelectJustificativa($idTipoControle, MdUtlControleDsmpRN::$TP_SOLICITACAO_INTERRUPCAO, $vlInterrupcao);
        }

      $arrDth          = explode(" ",$objMdUtlAjustePrazoDTO->getDthPrazoSolicitacao());
      $dthPrazo        = $arrDth[0];
      $intDiasUteis    = $objPrazoRN->retornaQtdDiaUtil($objMdUtlAjustePrazoDTO->getDthPrazoInicial(), $objMdUtlAjustePrazoDTO->getDthPrazoSolicitacao());
      $strObservacao   = $objMdUtlAjustePrazoDTO->getStrObservacao();
      $intDiasUteisExcedentes = $objMdUtlAjustePrazoDTO->getNumDiasUteisExcedentes();

      if (isset($_POST['sbmAlterarMdUtlAjustePrazo'])) {
        try{

            $objMdUtlAjustePrazoDTO->setNumIdMdUtlAjustePrazo(null);

            if(!$isTelaGerir) {
                $objMdUtlAjustePrazoDTO->setStrStaTipoSolicitacao($_POST['selTipoSolicitacao']);
                $objMdUtlAjustePrazoDTO->setNumIdMdUtlAdmJustPrazo($_POST['hdnIdSelJustificativa']);
            }

            $objMdUtlAjustePrazoDTO->setDthPrazoSolicitacao($_POST['txtPrazoData']);
            $objMdUtlAjustePrazoDTO->setStrSinAtivo('S');
            $objMdUtlAjustePrazoDTO->setStrStaSolicitacao(MdUtlAjustePrazoRN::$PENDENTE_RESPOSTA);
            $objMdUtlAjustePrazoDTO->setStrObservacao($_POST['txaObservacao']);
            $objMdUtlAjustePrazoDTO->setDthPrazoInicial($objControleDesempenhoDTO->getDthPrazoTarefa());
            $objMdUtlAjustePrazoDTO->setNumDiasUteisExcedentes($_POST['hdnDiasUteisExcedentes']);


            $objControleDesempenhoNovoDTO = $objMdUtlAjustePrazoRN->solicitarAjustePrazo(array($objMdUtlAjustePrazoDTO, $objControleDesempenhoDTO, true));
            header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_procedimento_selecionado='.$objControleDesempenhoNovoDTO->getDblIdProcedimento()));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'md_utl_ajuste_prazo_consultar':
        $strTitulo     = 'Consultar Solicitação de Ajuste de Prazo';
        $arrComandos[] = '<button type="button" accesskey="C" name="btnFechar" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTipoControle).'\';" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
        $isAlterar     = true;
        $objPrazoRN    = new MdUtlPrazoRN();

        $idAjustePrazo = array_key_exists('id_ajuste_prazo', $_GET) ? $_GET['id_ajuste_prazo'] : $_POST['hdnIdMdUtlAjustePrazo'];
        $objMdUtlAjustePrazoDTO->setNumIdMdUtlAjustePrazo($idAjustePrazo);
        $objMdUtlAjustePrazoDTO->retTodos();
        $objMdUtlAjustePrazoDTO->retStrNomeJustificativa();
        $objMdUtlAjustePrazoDTO->setNumMaxRegistrosRetorno(1);
        $objMdUtlAjustePrazoDTO = $objMdUtlAjustePrazoRN->consultar($objMdUtlAjustePrazoDTO);

        $tipoSolicitacao    = $objMdUtlAjustePrazoDTO->getStrStaTipoSolicitacao();
        $selTipoSolicitacao = MdUtlControleDsmpINT::montarSelectTipoSolicitacao($tipoSolicitacao);

        $displayJustNull = '';
        $selTipoJustificativaNull = '<option value="' . $objMdUtlAjustePrazoDTO->getNumIdMdUtlAdmJustPrazo() . '">' . $objMdUtlAjustePrazoDTO->getStrNomeJustificativa() . '</option>';

        $arrDth          = explode(" ",$objMdUtlAjustePrazoDTO->getDthPrazoSolicitacao());
        $dthPrazo        = $arrDth[0];
        $intDiasUteis    = $objPrazoRN->retornaQtdDiaUtil($objMdUtlAjustePrazoDTO->getDthPrazoInicial(), $objMdUtlAjustePrazoDTO->getDthPrazoSolicitacao());
        $strObservacao   = $objMdUtlAjustePrazoDTO->getStrObservacao();

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
require_once "md_utl_geral_css.php";
require_once "md_utl_ajuste_prazo_cadastro_css.php";

PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
PaginaSEI::getInstance()->fecharJavaScript();

PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmMdUtlAjustePrazoCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
    <?php
        PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        PaginaSEI::getInstance()->abrirAreaDados('auto');
        $col_def = "col-sm-6 col-md-6 col-lg-6";
    ?>

    <div class="row mb-3">
        <div class="col-12">
            <table>
                <tr>
                    <td>
                        <label id="lblProcessoDesc" name="lblProcessoDesc" class="infraLabelObrigatorio">Processo:</label>
                    </td>
                    <td class="pl-4">
                        <?= $objControleDesempenhoDTO->getStrProtocoloProcedimentoFormatado() ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label id="lblStatusAtualDesc" name="lblStatusAtualDesc" class="infraLabelObrigatorio">Status:</label>
                    </td>
                    <td class="pl-4">
                        <?= $strStatus ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label id="lblTempoExecucaoDesc" name="lblTempoExecucaoDesc" class="infraLabelObrigatorio">Tempo de Execução:</label>
                    </td>
                    <td class="pl-4">
                        <?= MdUtlAdmPrmGrINT::convertToHoursMins($objControleDesempenhoDTO->getNumTempoExecucao()); ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label id="lblPrazoAtualDesc" name="lblPrazoAtualDesc" class="infraLabelObrigatorio">Prazo Atual:</label>
                    </td>
                    <td class="pl-4">
                        <?= $dthFormatada ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div id="divSolicitacaoPrazo" class="row rowFieldSet">
        <fieldset class="infraFieldset fieldset-comum form-control">
            <legend class="infraLegend">Solicitar Novo Prazo</legend>
            <div class="row">
                <div id="divTipoSolicitacao"  class="<?= $col_def ?>">
                    <div class="form-group">
                        <label id="lblTipoSolicitacao" for="selTipoSolicitacao" accesskey="" class="infraLabelObrigatorio">Tipo de Solicitação:</label>

                        <img align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg"
                            name="ajuda" <?= PaginaSEI::montarTitleTooltip('Informar o Tipo de Solicitação de Ajuste de Prazo.','Ajuda') ?> />

                        <select <?= $isTelaGerir ? 'disabled="disabled"' : '' ?> utlCampoObrigatorio="o" onchange="habitarCamposSolicitacao(this);" id="selTipoSolicitacao" name="selTipoSolicitacao" class="selectPadraoTela infraSelect form-control">
                            <?= $selTipoSolicitacao ?>
                        </select>
                    </div>
                </div>
                <div id="divJustificativa" class="<?= $col_def ?>">
                    <div class="form-group">
                        <label id="lblTipoJustificativa" for="selTipoJustificativa" class="infraLabelObrigatorio">Justificativa:</label>

                        <img align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg"
                                name="ajuda" <?= PaginaSEI::montarTitleTooltip('Informa a Justificativa para o Ajuste de Prazo.','Ajuda') ?> />

                        <label style="display: none" id="lblTipoJustificativa" for="selTipoJustificativaD" class="infraLabelObrigatorio">Justificativa:</label>
                        <label style="display: none" id="lblTipoJustificativa" for="selTipoJustificativaI" class="infraLabelObrigatorio">Justificativa:</label>
                        <label style="display: none" id="lblTipoJustificativa" for="selTipoJustificativaS" class="infraLabelObrigatorio">Justificativa:</label>

                        <select style="<?= $displayJustNull ?>" <?= $utlObrigatorioNull ?> disabled="disabled" class="selectPadraoTela infraSelect tiposJustificativa form-control" id="selTipoJustificativa" name="selTipoJustificativa">
                            <?= $selTipoJustificativaNull ?>
                        </select>

                        <select style="<?= $displayJustDilc ?>" <?= $utlObrigatorioDilc ?> class="selectPadraoTela infraSelect tiposJustificativa form-control" id="selTipoJustificativaD" name="selTipoJustificativaD">
                            <?= $selTipoJustificativaDila ?>
                        </select>

                        <select style="<?= $displayJustSusp ?>" <?= $utlObrigatorioSusp ?> class="selectPadraoTela infraSelect tiposJustificativa form-control" id="selTipoJustificativaS" name="selTipoJustificativaS">
                            <?= $selTipoJustificativaSusp ?>
                        </select>

                        <select style="<?= $displayJustInt ?>" <?= $utlObrigatorioInt ?> class="selectPadraoTela infraSelect tiposJustificativa form-control" id="selTipoJustificativaI" name="selTipoJustificativaI">
                            <?= $selTipoJustificativaInte ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div id="divPrazoDiasUteis" class="<?= $col_def ?>">
                    <div class="form-group">
                        <label id="lblPrazoDiasUteis" name="lblPrazoDiasUteis" for="txtPrazoDiasUteis" class="infraLabelObrigatorio">Prazo em Dias Úteis:</label>

                        <img align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg"
                            name="ajuda" <?= PaginaSEI::montarTitleTooltip('Informa o prazo do ajuste de prazo em dias úteis.','Ajuda') ?> />

                        <input maxlength="3" utlSomenteNumeroPaste="true" ondrop="return infraMascaraNumero(this,event, 3);" onkeypress="return infraMascaraNumero(this,event, 3);" <?= $strDisabled; ?>
                                utlCampoObrigatorio="o" type="text" class="tamanhoInput infraText form-control" name="txtPrazoDiasUteis" id="txtPrazoDiasUteis"
                                value="<?= $intDiasUteis ?>" onchange="calcularPrazoData(true);"/>
                    </div>
                </div>
                <div id="divPrazoData" class="<?= $col_def ?>">
                    <div class="form-group">
                        <label id="lblPrazoData" name="lblPrazoData" for="txtPrazoData" class="infraLabelObrigatorio">Prazo em Data:</label>
                        <img align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg"
                            name="ajuda" <?= PaginaSEI::montarTitleTooltip('Informa o prazo do ajuste de prazo em Data.','Ajuda') ?> />

                        <div class="input-group">
                            <input onchange="calcularPrazoData(false)" <?= $strDisabled ?> type="text" utlCampoObrigatorio="o"
                                    id="txtPrazoData" name="txtPrazoData" onpaste="return infraMascaraData(this, event)"
                                    ondrop="return infraMascaraData(this, event)" onkeypress="return infraMascaraData(this, event)"
                                    class="infraText tamanhoInput form-control" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"
                                    value="<?= $dthPrazo ?>"/>

                            <img src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/calendario.svg"" id="imgPrazoData" title="Selecionar Prazo"
                                alt="Selecionar Prazo"
                                class="infraImg ml-1" onclick="infraCalendario('txtPrazoData',this);"
                                tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" id="divObservacao" name="divObservacao">
                <div class="col-sm-12 col-md-12 col-lg-12">
                    <div class="form-group">
                        <label id="lblObservacao" for="txaObservacao" accesskey="" class="infraLabelOpcional">Observação:</label>

                        <img align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg"
                            name="ajuda" <?= PaginaSEI::montarTitleTooltip('Informa a Observação para Solicitação de Ajuste de Prazo.','Ajuda') ?> />

                        <textarea type="text" id="txaObservacao" rows="4" maxlength="250" name="txaObservacao" class="infraTextArea form-control"
                                onkeypress="return infraMascaraTexto(this,event,250);" maxlength="250"
                                <?= $isTelaGerir ? 'disabled="disabled"' : '' ?>
                                tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"><?= PaginaSEI::tratarHTML($strObservacao); ?></textarea>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>

  <?php PaginaSEI::getInstance()->fecharAreaDados(); ?>

  <input type="hidden" id="hdnIdControleDesempenho" name="hdnIdControleDesempenho" value="<?=$idControleDesempenho?>" />
  <input type="hidden" id="hdnIdSelJustificativa" name="hdnIdSelJustificativa" value="<?=$idControleDesempenho?>" />
  <input type="hidden" id="hdnIdProcedimento" name="hdnIdProcedimento" value="<?= $idProcedimento; ?>"/>
  <input type="hidden" id="hdnDetalheFluxoAtend" name="hdnDetalheFluxoAtend" value=""/>
  <input type="hidden" id="hdnIdMdUtlAjustePrazo" name="hdnIdMdUtlAjustePrazo" value="<?= $idAjustePrazo; ?>">
  <input type="hidden" id="hdnDiasUteisExcedentes" name="hdnDiasUteisExcedentes" value="<?= $intDiasUteisExcedentes; ?>">
  <input type="hidden" id="hdnIsTelaGerir" name="hdnIsTelaGerir" value="<?= $isTelaGerir; ?>">

</form>

<?php require_once 'md_utl_geral_js.php'; ?>

<script type="text/javascript">

    var msg15Padrao = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_15); ?>';
    var msg46 = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_46); ?>';
    var msg95 = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_95); ?>';


    function inicializar(){
    if ('<?=$_GET['acao']?>'=='md_utl_ajuste_prazo_cadastrar'){
        document.getElementById('selTipoSolicitacao').focus();
    } else if ('<?=$_GET['acao']?>'=='md_utl_ajuste_prazo_consultar'){
        infraDesabilitarCamposAreaDados();
    }else{
        document.getElementById('btnCancelar').focus();
    }
    infraEfeitoTabelas(true);
    }

    function preencheValoresHidden(){
        var obj = document.getElementById('selTipoSolicitacao');
        document.getElementById('hdnDetalheFluxoAtend').value = obj.options[obj.selectedIndex].innerText.trim();

        var obj1 = document.getElementById('selTipoJustificativaD');
        if(isVisible(obj1)){
            document.getElementById('hdnIdSelJustificativa').value = obj1.value;
        }

        var obj2 = document.getElementById('selTipoJustificativaI');
        if(isVisible(obj2)){
            document.getElementById('hdnIdSelJustificativa').value = obj2.value;
        }

        var obj3 = document.getElementById('selTipoJustificativaS');
        if(isVisible(obj3)){
            document.getElementById('hdnIdSelJustificativa').value = obj3.value;
        }
    }

    function OnSubmitForm() {
        var valido = utlValidarObrigatoriedade();

        if(valido){
            preencheValoresHidden();
        }

        return valido;
    }

    function validoJustificativas(id){
        var cont = document.getElementById(id).options.length;

        if(cont == 0){
            alert(msg95);
            esconderTodasJustificativas();
            limparPrazos();
            document.getElementById('selTipoJustificativa').style = '';
            document.getElementById('selTipoSolicitacao').value = '';
            document.getElementById('txtPrazoDiasUteis').setAttribute('disabled', 'disabled');
            document.getElementById('txtPrazoData').setAttribute('disabled', 'disabled');
            return false;
        }

        return true;
    }

    function habitarCamposSolicitacao(obj){
        var valor = obj.value;
        var id = 'selTipoJustificativa' + valor;
        //Justificativa
        if(validoJustificativas(id)) {

            esconderTodasJustificativas();

            document.getElementById(id).value = '';
            document.getElementById(id).style = '';
            document.getElementById(id).setAttribute('utlCampoObrigatorio', 'o');
            limparPrazos();

            if ($.trim(valor) != '') {
                document.getElementById('txtPrazoDiasUteis').removeAttribute('disabled');
                document.getElementById('txtPrazoData').removeAttribute('disabled');
            } else {
                document.getElementById('txtPrazoDiasUteis').setAttribute('disabled', 'disabled');
                document.getElementById('txtPrazoData').setAttribute('disabled', 'disabled');
            }
        }
    }

    function esconderTodasJustificativas(){
        var objs = document.getElementsByClassName('tiposJustificativa');

        for(var i= 0; i < objs.length; i++){
            objs[i].style = 'display:none';
            objs[i].removeAttribute('utlCampoObrigatorio');
        }
    }


    function calcularPrazoData(isPrazoDiasUteis){
        infraExibirAviso(false);
        var prazoDias = document.getElementById('txtPrazoDiasUteis').value;
        var msg90     = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_90); ?>';

        if(isPrazoDiasUteis) {
            if(prazoDias == ''){
                limparPrazos();
                document.getElementById('txtPrazoDiasUteis').focus();
                infraOcultarAviso();
                return false;
            }

            if (prazoDias == 0) {
                limparPrazos();
                var msg = setMensagemPersonalizada(msg15Padrao, ['Prazo em Dias Úteis']);
                alert(msg);
                document.getElementById('txtPrazoDiasUteis').focus();
                infraOcultarAviso();
                return false;
            }
        }else{
            var data = $.trim(document.getElementById('txtPrazoData').value);

            if(data.length == 0){

                limparPrazos();
                infraOcultarAviso();
                return false;
            }

            if(data.length < 10){
                alert(msg46);
                limparPrazos();
                document.getElementById('txtPrazoData').focus();
                infraOcultarAviso();
                return false;
            }


            if(infraCompararDatas(infraDataAtual(), document.getElementById('txtPrazoData').value) <= 0){

                alert(msg90);
                limparPrazos();
                document.getElementById('txtPrazoData').focus();
                infraOcultarAviso();
                return false;
            }
            if(!validarFormatoData(document.getElementById('txtPrazoData'))) {
                limparPrazos();
                document.getElementById('txtPrazoData').focus();
                infraOcultarAviso();
                return false;
            }
        }
        var isStrPrazo = isPrazoDiasUteis ? 1: 0;

        var params = {
            prazoDias : prazoDias,
            idControle: document.getElementById('hdnIdControleDesempenho').value,
            tipoSolicitacao : document.getElementById('selTipoSolicitacao').value,
            prazoData : document.getElementById('txtPrazoData').value,
            isPrazo : isStrPrazo,
            prazoInicial : '<?=$objControleDesempenhoDTO->getDthPrazoTarefa()?>'
        };

        $.ajax({
            url: '<?=$strUrlCalcularPrazoJust?>',
            type: 'POST',
            data: params,
            dataType: 'XML',
            beforeSend: function(){
                infraExibirAviso(false);
            },
            success: function (r) {
            var sucesso = $(r).find('Sucesso').text() == '1';
                if(sucesso) {
                    if(isPrazoDiasUteis) {
                    var data = $(r).find('PrazoData').text();
                    document.getElementById('txtPrazoData').value = data;
                    }else{
                        var dias = $(r).find('PrazoDias').text();
                        document.getElementById('txtPrazoDiasUteis').value = dias;
                    }

                }else{
                    var msg  = $(r).find('Msg').text();
                    limparPrazos();
                    if(isPrazoDiasUteis){
                        document.getElementById('txtPrazoDiasUteis').focus();
                    }else{
                        document.getElementById('txtPrazoData').focus();
                    }

                    alert(msg);
                }
            },
            error: function (e) {
                console.error('Erro ao buscar o nome do usuário: ' + e.responseText);
            },
            complete: function(xhr){
                infraAvisoCancelar();
            }
        });
    }

    function limparPrazos() {
        document.getElementById('txtPrazoDiasUteis').value = '';
        document.getElementById('txtPrazoData').value = '';
    }

</script>

<?php
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
