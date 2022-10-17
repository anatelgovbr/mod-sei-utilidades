<?

$objFilaDTO = null;

$strDesabilitar = '';

$arrComandos = array();
//Id tipo de controle
$idTipoControle        = array_key_exists('id_tipo_controle_utl', $_GET) ? $_GET['id_tipo_controle_utl'] : $_POST['hdnIdTipoControleUtl'];
$objTpControleUtlRN    = new MdUtlAdmTpCtrlDesempRN();
$objControleDsmpRN     = new MdUtlControleDsmpRN();
$objTipoControleUtlDTO = $objTpControleUtlRN->buscarObjTpControlePorId($idTipoControle);
$strSelectMembros      = MdUtlAdmFilaINT::montarSelectMembros();
$strItensSelRespDilacao = MdUtlAdmPrmGrINT::montarSelectRespostaTacita();
$strItensSelTipoRevisao = MdUtlAdmFilaINT::montarSelectTipoRevisao();


if(is_null($objTipoControleUtlDTO)){
    header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_tp_ctrl_desemp_listar&acao_origem=md_utl_adm_fila_listar'));
}


$nomeTpControle        = !is_null($objTipoControleUtlDTO) ? $objTipoControleUtlDTO->getStrNome() : '';

$idFila                = array_key_exists('id_fila_utl',$_GET) ? $_GET['id_fila_utl'] : (array_key_exists('hdnIdFilaUtl', $_POST) ? $_POST['hdnIdFilaUtl'] : null);

//URL Base
//URL das Actions
$strUrlBuscarNomesUsuario = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_utl_adm_fila_buscar_nome_usuario');
$strUrlValidarVinculoUsuario = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_utl_adm_prm_vinculo_usuario_parametrizado_fila');
$isAlterar                   = 0;
$idAparente = 0;
$isConsultar = false;

switch($_GET['acao']){

    case 'md_utl_adm_fila_cadastrar':

        $strTitulo = 'Nova Fila - '.$nomeTpControle;
        $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarFila" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
        $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTipoControle)).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

        $objFilaDTO = null;
        $strGridUsuariosParticipantes = array_key_exists('hdnUsuarioParticipante',$_POST) ? htmlentities($_POST['hdnUsuarioParticipante']) : '';

        if (isset($_POST['sbmCadastrarFila'])) {

            try{

                $objMdUtlFilaRN = new MdUtlAdmFilaRN();
                $objRetorno = $objMdUtlFilaRN->cadastrarFila();
                header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTipoControle.PaginaSEI::getInstance()->montarAncora($objRetorno->getNumIdMdUtlAdmFila())));
                die;

            } catch(Exception $e){
                PaginaSEI::getInstance()->processarExcecao($e);
            }

        }

        break;

    case 'md_utl_adm_fila_alterar':
        $isAlterar = 1;
        $strTitulo = 'Alterar Fila - '.$nomeTpControle;
        $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarFila" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
        $strDesabilitar = 'disabled="disabled"';
        $objFilaDTO = new MdUtlAdmFilaDTO();


        if (!is_null($idFila)){

            $objFilaDTO->setNumIdMdUtlAdmFila($idFila);
            $objFilaDTO->retTodos();
            $objFilaRN = new MdUtlAdmFilaRN();
            $objFilaDTO = $objFilaRN->consultar($objFilaDTO);
            $strItensSelRespDilacao = MdUtlAdmPrmGrINT::montarSelectRespostaTacita($objFilaDTO->getStrRespTacitaDilacao());

            if ($objFilaDTO==null){
                throw new InfraException("Registro n�o encontrado.");
            }

            //============= INICIO OBTER REGISTROS RELACIONADOS PARA EDI�AO ===============================

            //consultar os usu�rios relaceionados
            $objFilaUsuarioDTO = new MdUtlAdmFilaPrmGrUsuDTO();
            $objFilaUsuarioDTO->retTodos(true);
            $objFilaUsuarioDTO->setNumIdMdUtlAdmFila($idFila);
            $objFilaUsuarioDTO->setOrdStrSigla(InfraDTO::$TIPO_ORDENACAO_DESC);
            $objFilaUsuarioRN = new MdUtlAdmFilaPrmGrUsuRN();
            $objArrFilaUsuarioDTO = $objFilaUsuarioRN->listar( $objFilaUsuarioDTO );
            //     $objFilaDTO->setArrObjRelFilaUnidadeDTO( $arrUnidades );

            $idsUsuarioFila = InfraArray::converterArrInfraDTO($objArrFilaUsuarioDTO, 'IdUsuario');

            $arrUsuariosVinculados = array();
            if ($idsUsuarioFila) {
                $arrUsuariosVinculados = $objControleDsmpRN->getArrVinculosExistentes(array($idsUsuarioFila, $idFila));
            }

            $arrGrid = array();

            foreach($objArrFilaUsuarioDTO as $objVincUsuario){
                $idAparente++;
                $strAnalista = $objVincUsuario->getStrSinAnalista() == 'S' ? 'Sim' : 'N�o';
                $strTriador  = $objVincUsuario->getStrSinTriador() == 'S' ? 'Sim' : 'N�o';
                $strRevisor  = $objVincUsuario->getStrSinRevisor() == 'S' ? 'Sim' : 'N�o';
                $vlTipoRevisao = $objVincUsuario->getNumTipoRevisao();

                if($vlTipoRevisao == 0){
                    $vlTipoRevisao = '';
                }
                if($vlTipoRevisao == MdUtlAdmFilaRN::$TOTAL){
                    $vlTipoRevisao = MdUtlAdmFilaRN::$STR_TOTAL;
                }
                if($vlTipoRevisao == MdUtlAdmFilaRN::$POR_ATIVIDADE){
                    $vlTipoRevisao = MdUtlAdmFilaRN::$STR_POR_ATIVIDADE;
                }
                if($vlTipoRevisao == MdUtlAdmFilaRN::$SEM_REVISAO){
                    $vlTipoRevisao = MdUtlAdmFilaRN::$STR_SEM_REVISAO;
                }


                $htmlDadosUsuario  = '<a alt="'.$objVincUsuario->getStrNomeUsuario().'" title="'.$objVincUsuario->getStrNomeUsuario().'" class="ancoraSigla"> '.$objVincUsuario->getStrSigla().' </a>';
                $isPossuiVinculo   = in_array($objVincUsuario->getNumIdUsuario(), $arrUsuariosVinculados) ? 1 : 0;

                $arrGrid[] = array($objVincUsuario->getNumIdMdUtlAdmPrmGrUsu(),
                    $htmlDadosUsuario,
                    $strTriador,  $objVincUsuario->getStrSinTriador(),
                    $strAnalista, $objVincUsuario->getStrSinAnalista(),
                    $vlTipoRevisao,
                    $strRevisor,
                    $objVincUsuario->getStrSinRevisor(),
                    $objVincUsuario->getStrNomeUsuario().' ('.$objVincUsuario->getStrSigla().')',
                    $isPossuiVinculo,
                    $idAparente);

            }

            $strGridUsuariosParticipantes = PaginaSEI::getInstance()->gerarItensTabelaDinamica($arrGrid);



        } else {
            $sinDstAutomatica = array_key_exists('rdoDstAutomatica', $_POST) && $_POST['rdoDstAutomatica'] != null && $_POST['rdoDstAutomatica'] == 'S' ? 'S' : 'N';
            $sinDstUltFila    = array_key_exists('rdoDstUltimaFila', $_POST) && $_POST['rdoDstUltimaFila'] != null && $_POST['rdoDstUltimaFila'] == 'S' ? 'S' : 'N';

            $objFilaDTO->setNumIdMdUtlAdmFila($_POST['hdnIdFila']);
            $objFilaDTO->setStrNome($_POST['txtNome']);
            $objFilaDTO->setStrDescricao($_POST['txaDescricao']);
            $objFilaDTO->setNumTmpExecucaoTriagem($_POST['txtTmpExecucaoTriagem']);
            $objFilaDTO->setNumPrazoTarefa($_POST['txtPrazoTarefa']);
            $objFilaDTO->setStrRespTacitaDilacao($_POST['selDilacao']);
            $objFilaDTO->setStrSinDistribuicaoAutomatica($sinDstAutomatica);
            $objFilaDTO->setStrSinDistribuicaoUltUsuario($sinDstUltFila);

            $strGridUsuariosParticipantes = array_key_exists('hdnUsuarioParticipante',$_POST) ? htmlentities($_POST['hdnUsuarioParticipante']) : '';
            $objFilaRN = new MdUtlAdmFilaRN();

            $arrObjFilaUsuarioParticipanteDTO = array();
            /*      $arrTbUsuarioParticipante = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnUsuarioParticipante']);

                  for($x = 0; $x < count($arrTbUsuarioParticipante); $x++){
                      $objFilaUsuarioDTO = new MdUtlAdmRelTpCtrlDesempUsuDTO();
                      $objFilaUsuarioDTO->setNumIdUsuario($arrTbUsuarioParticipante[$x]);
                      array_push( $arrObjFilaUsuarioParticipanteDTO, $objFilaUsuarioDTO );
                  }

                  $objFilaDTO->setArrObjRelFilaUsuarioDTO($arrObjFilaUsuarioParticipanteDTO);*/

        }


        $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&id_tipo_controle_utl='.$idTipoControle.'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objFilaDTO->getNumIdMdUtlAdmFila()))).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

        if (isset($_POST['sbmAlterarFila'])) {
            try{
                $objFilaRN = new MdUtlAdmFilaRN();
                $objFilaRN->alterar($objFilaDTO);
                header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTipoControle.PaginaSEI::getInstance()->montarAncora($objFilaDTO->getNumIdMdUtlAdmFila())));
                die;
            }catch(Exception $e){
                PaginaSEI::getInstance()->processarExcecao($e);
            }
        }
        break;

    case 'md_utl_adm_fila_consultar':
        $objFilaDTO = new MdUtlAdmFilaDTO();
        $strDesabilitar = 'disabled="disabled"';
        $strTitulo = 'Consultar Fila - '.$nomeTpControle;
        $isConsultar = true;
        if (!is_null($idFila)){

            $objFilaDTO->setNumIdMdUtlAdmFila($idFila);
            $objFilaDTO->retTodos();
            $objFilaRN = new MdUtlAdmFilaRN();
            $objFilaDTO = $objFilaRN->consultar($objFilaDTO);
            $strItensSelRespDilacao = MdUtlAdmPrmGrINT::montarSelectRespostaTacita($objFilaDTO->getStrRespTacitaDilacao());

            if ($objFilaDTO==null){
                throw new InfraException("Registro n�o encontrado.");
            }

            $arrComandos[] = '<button type="button" accesskey="C" name="btnFechar" value="Fechar" onclick="location.href=\''.PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTipoControle.PaginaSEI::getInstance()->montarAncora($idFila))).'\';" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';

            //============= INICIO OBTER REGISTROS RELACIONADOS PARA EDI�AO ===============================

            //consultar os usu�rios relacionados
            $objFilaUsuarioDTO = new MdUtlAdmFilaPrmGrUsuDTO();
            $objFilaUsuarioDTO->retTodos(true);
            $objFilaUsuarioDTO->setNumIdMdUtlAdmFila($idFila);
            $objFilaUsuarioDTO->setOrdStrSigla(InfraDTO::$TIPO_ORDENACAO_DESC);
            $objFilaUsuarioRN = new MdUtlAdmFilaPrmGrUsuRN();
            $objArrFilaUsuarioDTO = $objFilaUsuarioRN->listar( $objFilaUsuarioDTO );

            $arrGrid = array();
            foreach($objArrFilaUsuarioDTO as $objVincUsuario){

                $strAnalista = $objVincUsuario->getStrSinAnalista() == 'S' ? 'Sim' : 'N�o';
                $strTriador  = $objVincUsuario->getStrSinTriador() == 'S' ? 'Sim' : 'N�o';
                $strRevisor  = $objVincUsuario->getStrSinRevisor() == 'S' ? 'Sim' : 'N�o';
                $vlTipoRevisao = $objVincUsuario->getNumTipoRevisao();

                if($vlTipoRevisao == 0){
                    $vlTipoRevisao = '';
                }
                if($vlTipoRevisao == MdUtlAdmFilaRN::$TOTAL){
                    $vlTipoRevisao = MdUtlAdmFilaRN::$STR_TOTAL;
                }
                if($vlTipoRevisao == MdUtlAdmFilaRN::$POR_ATIVIDADE){
                    $vlTipoRevisao = MdUtlAdmFilaRN::$STR_POR_ATIVIDADE;
                }
                if($vlTipoRevisao == MdUtlAdmFilaRN::$SEM_REVISAO){
                    $vlTipoRevisao = MdUtlAdmFilaRN::$STR_SEM_REVISAO;
                }
                $htmlDadosUsuario  = '<a alt="'.$objVincUsuario->getStrNomeUsuario().'" title="'.$objVincUsuario->getStrNomeUsuario().'" class="ancoraSigla"> '.$objVincUsuario->getStrSigla().' </a>';

                $arrGrid[] = array($objVincUsuario->getNumIdMdUtlAdmPrmGrUsu(), $htmlDadosUsuario, $strTriador,  $objVincUsuario->getStrSinTriador(), $strAnalista, $objVincUsuario->getStrSinAnalista(), $vlTipoRevisao, $strRevisor, $objVincUsuario->getStrSinRevisor());

            }

            $strGridUsuariosParticipantes = PaginaSEI::getInstance()->gerarItensTabelaDinamica($arrGrid);
        }

        //============= FIM OBTER REGISTROS RELACIONADOS PARA EDI�AO ===============================

        break;

    default:
        throw new InfraException("A��o '".$_GET['acao']."' n�o reconhecida.");
}


?>