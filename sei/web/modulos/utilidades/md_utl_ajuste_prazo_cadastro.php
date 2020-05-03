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
?>
<?if(0){?><style><?}?>
.colunaPrincipal{
    width: 127px;
}

.divGeral {
    margin-top: 8px;
}

.selectPadraoTela{
    width: 182px;
}

#divSolicitacaoPrazo{
    margin-top: 20px;
}

#imgPrazoData{
    margin-bottom: -4.2px;
}

#txaObservacao {
    width: 380px;
    resize: none;
}

.imgAjuda{
    margin-bottom: -3px;
}

.tamanhoInput{
    width: 117px;
}

#divObservacao{
    margin-bottom: 5px;
}

    <?if(0){?></style><?}?>
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
require_once 'md_utl_geral_js.php';
?>
<?if(0){?><script type="text/javascript"><?}?>

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
    var prazoDias = document.getElementById('txtPrazoDiasUteis').value;
    var msg90     = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_90); ?>';

    if(isPrazoDiasUteis) {
        if(prazoDias == ''){
            limparPrazos();
            document.getElementById('txtPrazoDiasUteis').focus();
            return false;
        }

        if (prazoDias == 0) {
            limparPrazos();
            var msg = setMensagemPersonalizada(msg15Padrao, ['Prazo em Dias Úteis']);
            alert(msg);
            document.getElementById('txtPrazoDiasUteis').focus();
            return false;
        }
    }else{
        var data = $.trim(document.getElementById('txtPrazoData').value);

        if(data.length == 0){

            limparPrazos();
            return false;
        }

        if(data.length < 10){
            alert(msg46);
            limparPrazos();
            document.getElementById('txtPrazoData').focus();
            return false;
        }

        if(infraCompararDatas(infraDataAtual(), document.getElementById('txtPrazoData').value) <= 0){

            alert(msg90);
            limparPrazos();
            document.getElementById('txtPrazoData').focus();
            return false;
        }

        if(!validarFormatoData(document.getElementById('txtPrazoData'))) {
            limparPrazos();
            document.getElementById('txtPrazoData').focus();
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
        }
    });
}

    function limparPrazos() {
        document.getElementById('txtPrazoDiasUteis').value = '';
        document.getElementById('txtPrazoData').value = '';
    }

<?if(0){?></script><?}?>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmMdUtlAjustePrazoCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
PaginaSEI::getInstance()->abrirAreaDados('45em');
?>

    <div>
        <table style="font-size: 1.0em;">
            <tr>
                <td class="colunaPrincipal"><label id="lblProcessoDesc" name="lblProcessoDesc" class="infraLabelObrigatorio">Processo: </label>
                </td>
                <td><label id="lblProcessoValor" name="lblProcessoValor"
                           class="infraLabelOpcional"> <?php echo $objControleDesempenhoDTO->getStrProtocoloProcedimentoFormatado() ?> </label>
                </td>
            </tr>

            <tr>
                <td class="colunaPrincipal"><label id="lblStatusAtualDesc" name="lblStatusAtualDesc" class="infraLabelObrigatorio">Status: </label>
                </td>
                <td><label id="lblStatusAtualValor" name="lblStatusAtualValor"
                           class="infraLabelOpcional"> <?php echo $strStatus ?> </label>
                </td>
            </tr>


            <tr>
                <td class="colunaPrincipal"><label id="lblUnidadeEsforcoDesc" name="lblUnidadeEsforcoDesc" class="infraLabelObrigatorio">Unidade de Esforço: </label>
                </td>
                <td><label id="lblUnidadeEsforcoValor" name="lblUnidadeEsforcoValor"
                           class="infraLabelOpcional"> <?php echo $objControleDesempenhoDTO->getNumUnidadeEsforco(); ?> </label>
                </td>
            </tr>

            <tr>
                <td class="colunaPrincipal"><label id="lblPrazoAtualDesc" name="lblPrazoAtualDesc" class="infraLabelObrigatorio">Prazo Atual: </label>
                </td>
                <td><label id="lblPrazoAtualValor" name="lblPrazoAtualValor"  class="infraLabelOpcional"> <?php echo $dthFormatada; ?> </label>
                </td>
            </tr>

        </table>
    </div>
  <div id="divSolicitacaoPrazo">
      <fieldset class="infraFieldset" style="width: 63%;">
          <legend class="infraLegend">Solicitar Novo Prazo</legend>

          <div id="divTipoSolicitacao" class="divGeral">

              <label id="lblTipoSolicitacao" for="selTipoSolicitacao" accesskey="" class="infraLabelObrigatorio">Tipo de Solicitação:</label>

              <a href="javascript:void(0);" id="ancAjudaTipoSolicitacao" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>" <?= PaginaSEI::montarTitleTooltip('Informar o Tipo de Solicitação de Ajuste de Prazo.') ?>><img
                          class="tamanhoBtnAjuda imgAjuda"
                          src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif"
                          class="infraImg"/></a>

                <br/>
              <select <?php echo $isTelaGerir ? 'disabled="disabled"' : '' ?> utlCampoObrigatorio="o" onchange="habitarCamposSolicitacao(this);" id="selTipoSolicitacao" name="selTipoSolicitacao" class="selectPadraoTela infraSelect">
                  <?= $selTipoSolicitacao ?>
              </select>

          </div>

          <div id="divPrazoDiasUteis" class="divGeral">
                <label id="lblPrazoDiasUteis" name="lblPrazoDiasUteis" for="txtPrazoDiasUteis" class="infraLabelObrigatorio">Prazo em Dias Úteis:</label>
              <a href="javascript:void(0);" id="ancAjudaPrazoDiasUteis" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>" <?= PaginaSEI::montarTitleTooltip('Informa o prazo do ajuste de prazo em dias úteis.') ?>><img
                          class="tamanhoBtnAjuda imgAjuda"
                          src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif"
                          class="infraImg"/></a>
                <br/>

                <input  maxlength="3" utlSomenteNumeroPaste="true" ondrop="return infraMascaraNumero(this,event, 3);" onkeypress="return infraMascaraNumero(this,event, 3);"  <?php echo $strDisabled; ?> utlCampoObrigatorio="o" type="text" class="tamanhoInput infraText" name="txtPrazoDiasUteis" id="txtPrazoDiasUteis"  value="<?php echo $intDiasUteis; ?>" onchange="calcularPrazoData(true);"/>

          </div>

          <div id="divPrazoData" class="divGeral">
              <label id="lblPrazoData" name="lblPrazoData" for="txtPrazoData" class="infraLabelObrigatorio">Prazo em Data:</label>
              <a href="javascript:void(0);" id="ancAjudaPrazoData" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>" <?= PaginaSEI::montarTitleTooltip('Informa o prazo do ajuste de prazo em Data.') ?>><img
                          class="tamanhoBtnAjuda imgAjuda"
                          src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif"
                          class="infraImg"/></a>
              <br/>

              <input onchange="calcularPrazoData(false)" <?php echo $strDisabled; ?> type="text" utlCampoObrigatorio="o" id="txtPrazoData" name="txtPrazoData" onpaste="return infraMascaraData(this, event)" ondrop="return infraMascaraData(this, event)" onkeypress="return infraMascaraData(this, event)" class="infraText tamanhoInput" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"
                     value="<?php echo $dthPrazo; ?>"/>

              <img src="/infra_css/imagens/calendario.gif" id="imgPrazoData" title="Selecionar Prazo"
                   alt="Selecionar Prazo"
                   size="10"
                   class="tamanhoBtnAjuda imgAjuda" onclick="infraCalendario('txtPrazoData',this);"
                   tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>

          </div>

          <div id="divJustificativa" class="divGeral">
              <label id="lblTipoJustificativa" for="selTipoJustificativa" class="infraLabelObrigatorio">Justificativa:</label>

              <a href="javascript:void(0);" id="ancAjudaTipoJustificativa" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>" <?= PaginaSEI::montarTitleTooltip(' Informa a Justificativa para o Ajuste de Prazo.') ?>><img
                          class="tamanhoBtnAjuda imgAjuda"
                          src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif"
                          class="infraImg"/></a>

              <br/>

              <label style="display: none" id="lblTipoJustificativa" for="selTipoJustificativaD" class="infraLabelObrigatorio">Justificativa:</label>
              <label style="display: none" id="lblTipoJustificativa" for="selTipoJustificativaI" class="infraLabelObrigatorio">Justificativa:</label>
              <label style="display: none" id="lblTipoJustificativa" for="selTipoJustificativaS" class="infraLabelObrigatorio">Justificativa:</label>


              <select style="<?php echo $displayJustNull ?>" <?php echo $utlObrigatorioNull; ?> disabled="disabled" class="selectPadraoTela infraSelect tiposJustificativa" id="selTipoJustificativa" name="selTipoJustificativa">
                  <?= $selTipoJustificativaNull ?>
              </select>

              <select  style="<?php echo $displayJustDilc ?>" <?php echo $utlObrigatorioDilc; ?> class="selectPadraoTela infraSelect tiposJustificativa" id="selTipoJustificativaD" name="selTipoJustificativaD">
                  <?= $selTipoJustificativaDila ?>
              </select>

              <select  style="<?php echo $displayJustSusp ?>" <?php echo $utlObrigatorioSusp; ?> class="selectPadraoTela infraSelect tiposJustificativa" id="selTipoJustificativaS" name="selTipoJustificativaS">
                  <?= $selTipoJustificativaSusp ?>
              </select>

              <select  style="<?php echo $displayJustInt ?>" <?php echo $utlObrigatorioInt; ?> class="selectPadraoTela infraSelect tiposJustificativa" id="selTipoJustificativaI" name="selTipoJustificativaI">
                  <?= $selTipoJustificativaInte ?>
              </select>


          </div>

          <div class="divGeral" id="divObservacao" name="divObservacao">

              <label id="lblObservacao" for="txaObservacao" accesskey=""
                     class="infraLabelOpcional">Observação:</label>
              <a href="javascript:void(0);" id="ancAjudaObs"
                 tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>" <?= PaginaSEI::montarTitleTooltip(' Informa a Observação para Solicitação de Ajuste de Prazo.') ?>><img
                          class="tamanhoBtnAjuda imgAjuda"
                          src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif"
                          class="infraImg"/></a>
<br/>
              <textarea type="text" id="txaObservacao" rows="4" maxlength="250" name="txaObservacao" class="infraTextArea"
                        onkeypress="return infraMascaraTexto(this,event,250);" maxlength="250"
                        <?php echo $isTelaGerir ? 'disabled="disabled"' : '' ?>
                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"><?= PaginaSEI::tratarHTML($strObservacao); ?></textarea>

          </div>

    </fieldset>
  </div>
<?
PaginaSEI::getInstance()->fecharAreaDados();
?>
  <input type="hidden" id="hdnIdControleDesempenho" name="hdnIdControleDesempenho" value="<?=$idControleDesempenho?>" />
  <input type="hidden" id="hdnIdSelJustificativa" name="hdnIdSelJustificativa" value="<?=$idControleDesempenho?>" />
  <input type="hidden" id="hdnIdProcedimento" name="hdnIdProcedimento" value="<?php echo $idProcedimento; ?>"/>
  <input type="hidden" id="hdnDetalheFluxoAtend" name="hdnDetalheFluxoAtend" value=""/>
  <input type="hidden" id="hdnIdMdUtlAjustePrazo" name="hdnIdMdUtlAjustePrazo" value="<?php echo $idAjustePrazo; ?>">
  <input type="hidden" id="hdnDiasUteisExcedentes" name="hdnDiasUteisExcedentes" value="<?php echo $intDiasUteisExcedentes; ?>">
    <input type="hidden" id="hdnIsTelaGerir" name="hdnIsTelaGerir" value="<?php echo $isTelaGerir; ?>">

    <?
  //PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
