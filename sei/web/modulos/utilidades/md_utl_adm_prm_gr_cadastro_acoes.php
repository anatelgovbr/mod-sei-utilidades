<?php

PaginaSEI::getInstance()->verificarSelecao('md_utl_adm_prm_gr_selecionar');

SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

PaginaSEI::getInstance()->salvarCamposPost(array('selStaFrequencia', 'selMdUtlAdmFila'));

// Consulta tipo de controle


$idTipoControleUtl = isset($_GET['id_tipo_controle_utl'])?$_GET['id_tipo_controle_utl']:$_POST['hdnIdTipoControleUtl'];
$objMdUtlAdmTpCtrlDesempRN = new MdUtlAdmTpCtrlDesempRN();
$objMdUtlAdmTpCtrlDesempDTO = new MdUtlAdmTpCtrlDesempDTO();

$objMdUtlAdmTpCtrlDesempDTO->retTodos();
$objMdUtlAdmTpCtrlDesempDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTipoControleUtl);

$objMdUtlAdmTpCtrlDesemp = $objMdUtlAdmTpCtrlDesempRN->consultar($objMdUtlAdmTpCtrlDesempDTO);

$objMdUtlAdmPrmGrDTO = new MdUtlAdmPrmGrDTO();
$objMdUtlAdmPrmGrRN = new MdUtlAdmPrmGrRN();

$strTbUsuarioPart   = '';
$strDesabilitar     = '';
$strLupaTpProcesso  = '';
//controle css da tabela dinamica usuario participante
$heigthAreaDados    = 45;
$qtdUsuario         = 0;
$idMdUtlAdmPrmGr    = 0;

// Parâmetros
if($objMdUtlAdmTpCtrlDesemp->getNumIdMdUtlAdmPrmGr() > 0) {

    $objMdUtlAdmPrmGrDTO->retTodos();
    $objMdUtlAdmPrmGrDTO->setNumIdMdUtlAdmPrmGr($objMdUtlAdmTpCtrlDesemp->getNumIdMdUtlAdmPrmGr());

    $objMdUtlAdmPrmGrDTO    = $objMdUtlAdmPrmGrRN->consultar($objMdUtlAdmPrmGrDTO);

    $dataCorte              = $objMdUtlAdmPrmGrDTO->getDtaDataCorte();
    $cargaPadrao            = $objMdUtlAdmPrmGrDTO->getNumCargaPadrao();
    $selStaFrequencia       = $objMdUtlAdmPrmGrDTO->getStrStaFrequencia();
    $percentualTeletrabalho = $objMdUtlAdmPrmGrDTO->getDblPercentualTeletrabalho();
    $selSinRetono           = $objMdUtlAdmPrmGrDTO->getStrSinRetornoUltFila();
    $idMdUtlAdmPrmGr        = $objMdUtlAdmPrmGrDTO->getNumIdMdUtlAdmPrmGr();    
    $selRespTctDilacao      = $objMdUtlAdmPrmGrDTO->getStrRespTacitaDilacao();
    $selRespTctSuspencao    = $objMdUtlAdmPrmGrDTO->getStrRespTacitaSuspensao();
    $selRespTctInterrupcao  = $objMdUtlAdmPrmGrDTO->getStrRespTacitaInterrupcao();
    $numPrzSuspensao        = $objMdUtlAdmPrmGrDTO->getNumPrazoMaxSuspensao();
    $numPrzInterrupcao      = $objMdUtlAdmPrmGrDTO->getNumPrazoMaxInterrupcao();
    $selInicioPeriodo       = $objMdUtlAdmPrmGrDTO->getNumInicioPeriodo();

    $mdUtlAdmPrmGrUsuRN = new MdUtlAdmPrmGrUsuRN();

    $strUsuarioPart = $mdUtlAdmPrmGrUsuRN->montarArrUsuarioParticipante($idMdUtlAdmPrmGr);
    $arrUsuarioParticipante = $strUsuarioPart['itensTabela'];

    $strTbUsuarioPart = PaginaSEI::getInstance()->gerarItensTabelaDinamica($arrUsuarioParticipante);

    // controle do espaçamento heigth para cada usuario
    $qtdUsuario = ($strUsuarioPart['qtdUsuario']*2)+8;

    $mdUtlAdmRelPrmGrProcRN = new MdUtlAdmRelPrmGrProcRN();
    $strLupaTpProcesso   = $mdUtlAdmRelPrmGrProcRN->montarArrTpProcesso($idMdUtlAdmPrmGr);

}

$arrLupaTpProcessoOrigin   = PaginaSEI::getInstance()->getArrItensTabelaDinamica($strLupaTpProcesso);

// Tamanho para controlar o tamanho do area dados controle de participante , de acordo com a qtd de usuarios
$heigthAreaDados+=$qtdUsuario;

$arrComandos = array();

$strTitulo = 'Parâmetros Gerais - '.$objMdUtlAdmTpCtrlDesemp->getStrNome();

$objMdUtlAdmPrmGrRN = new MdUtlAdmPrmGrRN();

switch ($_GET['acao']) {
    case 'md_utl_adm_prm_gr_cadastrar':

        $arrComandos[] = '<button type="button" accesskey="S" onclick="onSubmitForm();" name="sbmCadastrarMdUtlAdmPrmGr" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
        $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' .SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'].PaginaSEI::getInstance()->montarAncora($idTipoControleUtl)).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

        $objMdUtlAdmPrmGrDTO->setNumCargaPadrao($_POST['txtCargaPadrao']);
        $strStaFrequencia = $_POST['selStaFrequencia'];
        if ($strStaFrequencia != '' || $strStaFrequencia != 0 ) {
            $objMdUtlAdmPrmGrDTO->setStrStaFrequencia($strStaFrequencia);
        }

        /*
        if(isset($_POST['selFilaPadrao']) && $_POST['selFilaPadrao']!= $selFilaPadrao){
            $objMdUtlAdmPrmGrDTO->setNumIdMdUtlAdmFila($_POST['selFilaPadrao']);
        }
        */


        $objMdUtlAdmPrmGrDTO->setDblPercentualTeletrabalho($_POST['txtPercentualTeletrabalho']);
        $sinRetornoUltimaFila = $_POST['selRetorno'] != '' ? $_POST['selRetorno'] : null;

        $objMdUtlAdmPrmGrDTO->setStrSinRetornoUltFila($sinRetornoUltimaFila);
        $objMdUtlAdmPrmGrDTO->setNumIdMdUtlAdmPrmGr($idMdUtlAdmPrmGr);

        /** Alterar com os dados reais dos campos - Dados abaixo mocados para permitir parametrização */
        $objMdUtlAdmPrmGrDTO->setStrRespTacitaDilacao($_POST['selDilacao']);
        $objMdUtlAdmPrmGrDTO->setStrRespTacitaSuspensao($_POST['selSuspensao']);
        $objMdUtlAdmPrmGrDTO->setNumPrazoMaxSuspensao($_POST['przSuspensao']);
        $objMdUtlAdmPrmGrDTO->setStrRespTacitaInterrupcao($_POST['selInterrupcao']);
        $objMdUtlAdmPrmGrDTO->setNumPrazoMaxInterrupcao($_POST['przInterrupcao']);
        $objMdUtlAdmPrmGrDTO->setNumInicioPeriodo($_POST['selInicioPeriodo']);
        $objMdUtlAdmPrmGrDTO->setDtaDataCorte($_POST['txtDtCorte']);

        if (!empty($_POST)) {

            try {
                $objMdUtlAdmPrmGrRN->cadastrarParametrizacao($idMdUtlAdmPrmGr, $idTipoControleUtl, $objMdUtlAdmPrmGrDTO, $objMdUtlAdmTpCtrlDesempDTO);
                    //PaginaSEI::getInstance()->adicionarMensagem('Parâmetro Geral "' . $objMdUtlAdmPrmGrDTO->getNumIdMdUtlAdmPrmGr() . '" cadastrado com sucesso.');
                header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($idTipoControleUtl)));
                 die;

            } catch (Exception $e) {
                PaginaSEI::getInstance()->processarExcecao($e);
            }
        }
        break;

    default:
        throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
}


  $selTpPresenca    ='';
  $selTpJornada     ='';

  $strItensSelStaFrequencia = MdUtlAdmPrmGrINT::montarSelectStaFrequencia($selStaFrequencia);
  $strItensSelSinRetono     = MdUtlAdmPrmGrINT::montarSelectSinRetornoUltimaFila($selSinRetono);
  $strItensSelTpPresenca    = MdUtlAdmPrmGrUsuINT::montarSelectStaTipoPresenca($selTpPresenca);
  $strItensSelTpJornada     = MdUtlAdmPrmGrUsuINT::montarSelectStaTipoJornada($selTpJornada);
  #$strFilaPadrao            = MdUtlAdmPrmGrINT::montarSelectFilaPadrao($selFilaPadrao,$idTipoControleUtl);
  $strItensSelRespDilacao = MdUtlAdmPrmGrINT::montarSelectRespostaTacita($selRespTctDilacao);
  $strItensSelRespSuspensao   = MdUtlAdmPrmGrINT::montarSelectRespostaTacita($selRespTctSuspencao);
  $strItensSelRespInterrupcao = MdUtlAdmPrmGrINT::montarSelectRespostaTacita($selRespTctInterrupcao);
  $strItensSelInicioPeriodo   = MdUtlAdmPrmGrINT::montarSelectInicioPeriodo($selStaFrequencia, $selInicioPeriodo);
  $strItensSelFimPeriodo   = MdUtlAdmPrmGrINT::montarSelectFimPeriodo($selInicioPeriodo);

  //$strItensSelMdUtlAdmFila = MdUtlAdmFilaINT::montarSelect???????('null','&nbsp;',$objMdUtlAdmPrmGrDTO->getNumIdMdUtlAdmFila());
