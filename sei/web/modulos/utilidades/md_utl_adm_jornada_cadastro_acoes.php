<?

$objJornadaDTO = null;

$strDesabilitar = '';

$arrComandos = array();
//Id tipo de controle
$objTpControleUtlRN    = new MdUtlAdmTpCtrlDesempRN();
$objTpControleUtlUsuRN = new MdUtlAdmRelTpCtrlDesempUsuRN();
$objTpControleUtlUndRN = new MdUtlAdmRelTpCtrlDesempUndRN();

$idJornada             = array_key_exists('id_jornada',$_GET) ? $_GET['id_jornada'] : (array_key_exists('hdnIdJornada', $_POST) ? $_POST['hdnIdJornada'] : null);
$objTpControle         = $objTpControleUtlUndRN->getObjTipoControleUnidadeLogada();
$idTipoControle        = $objTpControle->getNumIdMdUtlAdmTpCtrlDesemp();
$nomeTpControle        = $objTpControle->getStrNomeTipoControle();

//URL Base
//URL das Actions
$strUrlBuscarLinksAssinados = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_utl_adm_buscar_links_assinados');
//$strSelectTipoControle      = null;
$arrTpsControle             = null;
$isAlterar                  = false;
$isConsultar                = false;

switch($_GET['acao']){

    case 'md_utl_adm_jornada_cadastrar':
        $arrTpsControle         = $objTpControleUtlUsuRN->usuarioLogadoIsGestorTpControle();
        $strTitulo = 'Novo Ajuste de Jornada';
        $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarJornada" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
        $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTipoControle)).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';
        $idTpAjuste    = array_key_exists('hdnTpAjuste', $_POST)  ? $_POST['hdnTpAjuste'] : null;
        $objJornadaDTO  = null;

        if (isset($_POST['sbmCadastrarJornada'])) {
            try{
                $objMdUtlJornadaRN = new MdUtlAdmJornadaRN();
                $objRetorno = $objMdUtlJornadaRN->cadastrarJornada();
                header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTipoControle.PaginaSEI::getInstance()->montarAncora($objRetorno->getNumIdMdUtlAdmJornada())));
                die;

            } catch(Exception $e){
                PaginaSEI::getInstance()->processarExcecao($e);
            }

        }

        break;

    case 'md_utl_adm_jornada_alterar':
        $isAlterar = true;
        $strTitulo = 'Alterar Jornada';
        $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarJornada" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
        $strDesabilitar = 'disabled="disabled"';
        $objJornadaDTO = new MdUtlAdmJornadaDTO();

        if (!is_null($idJornada)){

            $objJornadaDTO->setNumIdMdUtlAdmJornada($idJornada);
            $objJornadaDTO->retTodos();
            $objJornadaRN = new MdUtlAdmJornadaRN();
            $objJornadaDTO = $objJornadaRN->consultar($objJornadaDTO);

            if ($objJornadaDTO==null){
                throw new InfraException("Registro não encontrado.");
            }

            $dthDataInicio = explode(' ', $objJornadaDTO->getDthInicio());
            $objJornadaDTO->setDthInicio($dthDataInicio[0]);
            $dthDataFim    = explode(' ', $objJornadaDTO->getDthFim());
            $objJornadaDTO->setDthFim($dthDataFim[0]);

            $arrTpsControle         = $objTpControleUtlUsuRN->usuarioLogadoIsGestorTpControle();
            $idTpCtrl               = array_key_exists('hdnIdTipoControleUtl', $_POST) ? $_POST['hdnIdTipoControleUtl']: $objJornadaDTO->getNumIdMdUtlAdmTpCtrlDesemp();
            $idTpAjuste             = array_key_exists('hdnTpAjuste',$_POST) ? $_POST['hdnTpAjuste']: $objJornadaDTO->getStrStaTipoAjuste();

            //============= INICIO OBTER REGISTROS RELACIONADOS PARA EDIÇAO ===============================

            //consultar os usuários relacionados
            $objJornadaUsuarioDTO = new MdUtlAdmRelJornadaUsuDTO();
            $objJornadaUsuarioDTO->retTodos(true);
            $objJornadaUsuarioDTO->setNumIdMdUtlAdmJornada($idJornada);

            $objJornadaUsuarioRN = new MdUtlAdmRelJornadaUsuRN();
            $countJornadaUsu     = $objJornadaUsuarioRN->contar( $objJornadaUsuarioDTO );

            if($countJornadaUsu > 0 && $objJornadaDTO->getStrStaTipoAjuste() == MdUtlAdmJornadaRN::$TIPO_JORNADA_ESPECIFICO) {
                $objArrJornadaUsuarioDTO = $objJornadaUsuarioRN->listar($objJornadaUsuarioDTO);
                $strItensSelMembros = "";

                for ($x = 0; $x < count($objArrJornadaUsuarioDTO); $x++) {

                    $strItensSelUsuarios .= "<option value='" . $objArrJornadaUsuarioDTO[$x]->getNumIdUsuario() . "'>" . $objArrJornadaUsuarioDTO[$x]->getStrNomeUsuario().' (' .$objArrJornadaUsuarioDTO[$x]->getStrSiglaUsuario().')'."</option>";
                }
            }



        }


        $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($idJornada))).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

        if (isset($_POST['sbmAlterarJornada'])) {
            try{

                $objJornadaRN = new MdUtlAdmJornadaRN();
                $objJornadaRN->alterarJornada($idJornada);
                header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($idJornada)));
                die;
            }catch(Exception $e){
                PaginaSEI::getInstance()->processarExcecao($e);
            }
        }

        break;

    case 'md_utl_adm_jornada_consultar':
        $objJornadaDTO = new MdUtlAdmJornadaDTO();
        $strTitulo = 'Consultar Jornada';
        $isAlterar = true;
        $arrComandos[] = '<button type="button" accesskey="C" name="btnFechar" value="Fechar" onclick="location.href=\''.PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($idJornada))).'\';" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';


        if (!is_null($idJornada)) {

            $objJornadaDTO->setNumIdMdUtlAdmJornada($idJornada);
            $objJornadaDTO->retTodos();
            $objJornadaRN = new MdUtlAdmJornadaRN();
            $objJornadaDTO = $objJornadaRN->consultar($objJornadaDTO);

            if ($objJornadaDTO == null) {
                throw new InfraException("Registro não encontrado.");
            }

            $dthDataInicio = explode(' ', $objJornadaDTO->getDthInicio());
            $objJornadaDTO->setDthInicio($dthDataInicio[0]);
            $dthDataFim = explode(' ', $objJornadaDTO->getDthFim());
            $objJornadaDTO->setDthFim($dthDataFim[0]);

            //$strSelectTipoControle = MdUtlAdmTpCtrlDesempINT::montarSelectIdMdUtlAdmTpCtrlDesemp($isAlterar, $objJornadaDTO->getNumIdMdUtlAdmTpCtrlDesemp());
            $idTpAjuste             = array_key_exists('hdnTpAjuste',$_POST) ? $_POST['hdnTpAjuste']: $objJornadaDTO->getStrStaTipoAjuste();


            //============= INICIO OBTER REGISTROS RELACIONADOS PARA EDIÇAO ===============================

            //consultar os usuários relacionados
            $objJornadaUsuarioDTO = new MdUtlAdmRelJornadaUsuDTO();
            $objJornadaUsuarioDTO->retTodos(true);
            $objJornadaUsuarioDTO->setNumIdMdUtlAdmJornada($idJornada);

            $objJornadaUsuarioRN = new MdUtlAdmRelJornadaUsuRN();
            $countJornadaUsu = $objJornadaUsuarioRN->contar($objJornadaUsuarioDTO);

            if ($countJornadaUsu > 0 && $objJornadaDTO->getStrStaTipoAjuste() == MdUtlAdmJornadaRN::$TIPO_JORNADA_ESPECIFICO) {
                $objArrJornadaUsuarioDTO = $objJornadaUsuarioRN->listar($objJornadaUsuarioDTO);

                $strItensSelMembros = "";
                $objUnidadeRN = new UnidadeRN();


                for ($x = 0; $x < count($objArrJornadaUsuarioDTO); $x++) {

                    $strItensSelUsuarios .= "<option value='" . $objArrJornadaUsuarioDTO[$x]->getNumIdUsuario() . "'>" . $objArrJornadaUsuarioDTO[$x]->getStrNomeUsuario() . ' (' . $objArrJornadaUsuarioDTO[$x]->getStrSiglaUsuario() . ')' . "</option>";
                }
            }
        }
        //============= FIM OBTER REGISTROS RELACIONADOS PARA EDIÇAO ===============================

        break;

    default:
        throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
}

?>