<?

$objTipoControleUtilidadesDTO = new MdUtlAdmTpCtrlDesempDTO();

$strDesabilitar = '';

$arrComandos = array();
$objTipoControleUtilidadesRN = new MdUtlAdmTpCtrlDesempRN();

switch($_GET['acao']){

    case 'md_utl_adm_tp_ctrl_desemp_cadastrar':

        $strTitulo = 'Novo Tipo de Controle de Desempenho';
        $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarTipoControleUtilidades" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
        $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'])).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';


        $objTipoControleUtilidadesDTO->setNumIdMdUtlAdmTpCtrlDesemp(null);
        $objTipoControleUtilidadesDTO->setStrNome($_POST['txtNome']);
        $objTipoControleUtilidadesDTO->setStrDescricao($_POST['txtDescricao']);
 
        
        if (isset($_POST['sbmCadastrarTipoControleUtilidades'])) {

            try{

                //GESTORES
                    $arrObjTipoControleUtilidadesGestorDTO = array();
                    $arrGestores = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnGestores']);

                    for ($x = 0; $x < count($arrGestores); $x++) {
                        $objTipoControleUtilidadesUsuarioDTO = new MdUtlAdmRelTpCtrlDesempUsuDTO();
                        $objTipoControleUtilidadesUsuarioDTO->setNumIdUsuario($arrGestores[$x]);
                        array_push($arrObjTipoControleUtilidadesGestorDTO, $objTipoControleUtilidadesUsuarioDTO);
                    }

                    $objTipoControleUtilidadesDTO->setArrObjRelTipoControleUtilidadesUsuarioDTO($arrObjTipoControleUtilidadesGestorDTO);


                    //UNIDADES
                    $arrObjTipoControleUtilidadesUnidadeDTO = array();
                    $arrUnidades = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnUnidades']);

                    //Validar se já existe uma unidade cadastrada para outro tipo de controle.
//                    $objTipoControleUtilidadesUnidadeRN = new MdUtlAdmRelTpCtrlDesempUndRN();
//                    $objTipoControleUtilidadesUnidadeRN->validarDuplicidadeUnidade(array($arrUnidades));


                    for ($x = 0; $x < count($arrUnidades); $x++) {
                        $objTipoControleUtilidadesUnidadeDTO = new MdUtlAdmRelTpCtrlDesempUndDTO();
                        $objTipoControleUtilidadesUnidadeDTO->setNumIdUnidade($arrUnidades[$x]);

                        array_push($arrObjTipoControleUtilidadesUnidadeDTO, $objTipoControleUtilidadesUnidadeDTO);
                    }

                    $objTipoControleUtilidadesDTO->setArrObjRelTipoControleUtilidadesUnidadeDTO($arrObjTipoControleUtilidadesUnidadeDTO);

                    //Set SinAtivo
                    $objTipoControleUtilidadesDTO->setStrSinAtivo('S');
                    $objTipoControleUtilidadesRN = new MdUtlAdmTpCtrlDesempRN();
                    $objTipoControleUtilidadesDTO = $objTipoControleUtilidadesRN->cadastrar($objTipoControleUtilidadesDTO);
                
                header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objTipoControleUtilidadesDTO->getNumIdMdUtlAdmTpCtrlDesemp())));
                die;

            } catch(Exception $e){
                PaginaSEI::getInstance()->processarExcecao($e);
            }

        }

        break;

    case 'md_utl_adm_tp_ctrl_desemp_alterar':
        $strTitulo = 'Alterar Tipo de Controle de Desempenho';
        $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarTipoControleUtilidades" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
        $strDesabilitar = 'disabled="disabled"';

        if (isset($_GET['id_tipo_controle_utilidades'])){

            $objTipoControleUtilidadesDTO->setNumIdMdUtlAdmTpCtrlDesemp($_GET['id_tipo_controle_utilidades']);
            $objTipoControleUtilidadesDTO->retTodos();
            $objTipoControleUtilidadesRN = new MdUtlAdmTpCtrlDesempRN();
            $objTipoControleUtilidadesDTO = $objTipoControleUtilidadesRN->consultar($objTipoControleUtilidadesDTO);

            if ($objTipoControleUtilidadesDTO==null){
                throw new InfraException("Registro não encontrado.");
            }

            //============= INICIO OBTER REGISTROS RELACIONADOS PARA EDIÇAO ===============================

            //consultar as unidades relacionadas
            $objTipoControleUtilidadesUnidadeDTO = new MdUtlAdmRelTpCtrlDesempUndDTO();
            $objTipoControleUtilidadesUnidadeDTO->retTodos();
            $objTipoControleUtilidadesUnidadeDTO->setNumIdMdUtlAdmTpCtrlDesemp($_GET['id_tipo_controle_utilidades']);
            $objTipoControleUtilidadesUnidadeDTO->retStrSiglaUnidade();
            $objTipoControleUtilidadesUnidadeDTO->retStrDescricaoUnidade();
            $objTipoControleUtilidadesUnidadeDTO->setOrdStrSiglaUnidade(InfraDTO::$TIPO_ORDENACAO_ASC);

            $objRelTipoControleUtilidadesUnidadeRN = new MdUtlAdmRelTpCtrlDesempUndRN();
            $arrUnidades = $objRelTipoControleUtilidadesUnidadeRN->listar( $objTipoControleUtilidadesUnidadeDTO );
            $arrUnidadesOrigin = $arrUnidades;
            $objTipoControleUtilidadesDTO->setArrObjRelTipoControleUtilidadesUnidadeDTO( $arrUnidades );

            $strItensSelUnidades = "";
            $objUnidadeRN = new UnidadeRN();

            for($x = 0;$x<count($arrUnidades);$x++){
                $strItensSelUnidades .= "<option value='" . $arrUnidades[$x]->getNumIdUnidade() .  "'>" . $arrUnidades[$x]->getStrSiglaUnidade() .' - '.$arrUnidades[$x]->getStrDescricaoUnidade(); "</option>";
            }

            //consultar os gestores relacionados
            $objTipoControleUtilidadesUsuarioDTO = new MdUtlAdmRelTpCtrlDesempUsuDTO();
            $objTipoControleUtilidadesUsuarioDTO->retTodos();
            $objTipoControleUtilidadesUsuarioDTO->setNumIdMdUtlAdmTpCtrlDesemp($_GET['id_tipo_controle_utilidades']);
            $objTipoControleUtilidadesUsuarioDTO->setOrdStrNomeUsuario(InfraDTO::$TIPO_ORDENACAO_ASC);
            $objTipoControleUtilidadesUsuarioDTO->retStrNomeUsuario();
            $objTipoControleUtilidadesUsuarioDTO->retStrSiglaUsuario();

            $objRelTipoControleUtilidadesUsuarioRN = new MdUtlAdmRelTpCtrlDesempUsuRN();
            $arrGestoresDTO = $objRelTipoControleUtilidadesUsuarioRN->listar( $objTipoControleUtilidadesUsuarioDTO );
            $objTipoControleUtilidadesDTO->setArrObjRelTipoControleUtilidadesUsuarioDTO( $arrGestoresDTO );

            $strItensSelGestores = "";
            $objUsuarioRN = new UsuarioRN();

            for($x = 0;$x<count($arrGestoresDTO);$x++){
                 $strItensSelGestores .= "<option value='" . $arrGestoresDTO[$x]->getNumIdUsuario() .  "'>" . $arrGestoresDTO[$x]->getStrNomeUsuario().' (' .$arrGestoresDTO[$x]->getStrSiglaUsuario().')'."</option>";
            }

        } else {
            $objTipoControleUtilidadesDTO->setNumIdMdUtlAdmTpCtrlDesemp($_POST['hdnIdTipoControleUtilidades']);
            $objTipoControleUtilidadesDTO->setStrNome($_POST['txtNome']);
            $objTipoControleUtilidadesDTO->setStrDescricao($_POST['txtDescricao']);

            $objTipoControleUtilidadesRN = new MdUtlAdmTpCtrlDesempRN();

            //consultar as unidades relacionadas
            $objTipoControleUtilidadesUnidadeDTO = new MdUtlAdmRelTpCtrlDesempUndDTO();
            $objTipoControleUtilidadesUnidadeDTO->retTodos();
            $objTipoControleUtilidadesUnidadeDTO->setNumIdMdUtlAdmTpCtrlDesemp($_POST['hdnIdTipoControleUtilidades']);

            $objRelTipoControleUtilidadesUnidadeRN = new MdUtlAdmRelTpCtrlDesempUndRN();
            $arrUnidadesOrigin = $objRelTipoControleUtilidadesUnidadeRN->listar( $objTipoControleUtilidadesUnidadeDTO );


            $arrObjTipoControleUtilidadesGestorDTO = array();
            $arrGestores = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnGestores']);

            for($x = 0; $x < count($arrGestores); $x++){
                $objTipoControleUtilidadesUsuarioDTO = new MdUtlAdmRelTpCtrlDesempUsuDTO();
                $objTipoControleUtilidadesUsuarioDTO->setNumIdUsuario($arrGestores[$x]);
                array_push( $arrObjTipoControleUtilidadesGestorDTO, $objTipoControleUtilidadesUsuarioDTO );
            }

            $objTipoControleUtilidadesDTO->setArrObjRelTipoControleUtilidadesUsuarioDTO($arrObjTipoControleUtilidadesGestorDTO);

            //UNIDADES
            $arrObjTipoControleUtilidadesUnidadeDTO = array();
            $arrUnidades = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnUnidades']);

            for($x = 0;$x<count($arrUnidades);$x++){
                $objTipoControleUtilidadesUnidadeDTO = new MdUtlAdmRelTpCtrlDesempUndDTO();
                $objTipoControleUtilidadesUnidadeDTO->setNumIdUnidade($arrUnidades[$x]);
                array_push( $arrObjTipoControleUtilidadesUnidadeDTO, $objTipoControleUtilidadesUnidadeDTO );
            }

            $objTipoControleUtilidadesDTO->setArrObjRelTipoControleUtilidadesUnidadeDTO($arrObjTipoControleUtilidadesUnidadeDTO);

        }

        $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objTipoControleUtilidadesDTO->getNumIdMdUtlAdmTpCtrlDesemp()))).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

        if (isset($_POST['sbmAlterarTipoControleUtilidades'])) {
            try{
                $objTipoControleUtilidadesRN = new MdUtlAdmTpCtrlDesempRN();
                $objTipoControleUtilidadesRN->alterarTipoControle($arrUnidadesOrigin, $objTipoControleUtilidadesDTO);
                header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objTipoControleUtilidadesDTO->getNumIdMdUtlAdmTpCtrlDesemp())));
                die;
            }catch(Exception $e){
                PaginaSEI::getInstance()->processarExcecao($e);
            }
        }
        break;

    case 'md_utl_adm_tp_ctrl_desemp_consultar':
        $strTitulo = 'Consultar Tipo de Controle de Desempenho';
        $arrComandos[] = '<button type="button" accesskey="c" name="btnFechar" value="Fechar" onclick="location.href=\''.PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_tipo_controle_utilidades']))).'\';" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
        $objTipoControleUtilidadesDTO->setNumIdMdUtlAdmTpCtrlDesemp($_GET['id_tipo_controle_utilidades']);
        $objTipoControleUtilidadesDTO->setBolExclusaoLogica(false);
        $objTipoControleUtilidadesDTO->retTodos();
        $objTipoControleUtilidadesRN = new MdUtlAdmTpCtrlDesempRN();
        $objTipoControleUtilidadesDTO = $objTipoControleUtilidadesRN->consultar($objTipoControleUtilidadesDTO);

        if ($objTipoControleUtilidadesDTO===null){
            throw new InfraException("Registro não encontrado.");
        }

        //============= INICIO OBTER REGISTROS RELACIONADOS PARA EDIÇAO ===============================

        //consultar as unidades relacionadas
        $objTipoControleUtilidadesUnidadeDTO = new MdUtlAdmRelTpCtrlDesempUndDTO();
        $objTipoControleUtilidadesUnidadeDTO->retTodos();
        $objTipoControleUtilidadesUnidadeDTO->setNumIdMdUtlAdmTpCtrlDesemp($_GET['id_tipo_controle_utilidades']);
        $objTipoControleUtilidadesUnidadeDTO->retStrSiglaUnidade();
        $objTipoControleUtilidadesUnidadeDTO->setOrdStrSiglaUnidade(InfraDTO::$TIPO_ORDENACAO_ASC);

        $objRelTipoControleUtilidadesUnidadeRN = new MdUtlAdmRelTpCtrlDesempUndRN();
        $arrUnidades = $objRelTipoControleUtilidadesUnidadeRN->listar( $objTipoControleUtilidadesUnidadeDTO );
        $objTipoControleUtilidadesDTO->setArrObjRelTipoControleUtilidadesUnidadeDTO( $arrUnidades );

        $strItensSelUnidades = "";
        $objUnidadeRN = new UnidadeRN();

        for($x = 0;$x<count($arrUnidades);$x++){
            $strItensSelUnidades .= "<option value='" . $arrUnidades[$x]->getNumIdUnidade() .  "'>" . $arrUnidades[$x]->getStrSiglaUnidade() . "</option>";
        }

        //consultar os gestores relacionados
        $objTipoControleUtilidadesUsuarioDTO = new MdUtlAdmRelTpCtrlDesempUsuDTO();
        $objTipoControleUtilidadesUsuarioDTO->retTodos();
        $objTipoControleUtilidadesUsuarioDTO->setNumIdMdUtlAdmTpCtrlDesemp($_GET['id_tipo_controle_utilidades']);
        $objTipoControleUtilidadesUsuarioDTO->setOrdStrNomeUsuario(InfraDTO::$TIPO_ORDENACAO_ASC);
        $objTipoControleUtilidadesUsuarioDTO->retStrNomeUsuario();

        $objRelTipoControleUtilidadesUsuarioRN = new MdUtlAdmRelTpCtrlDesempUsuRN();
        $arrGestoresDTO = $objRelTipoControleUtilidadesUsuarioRN->listar( $objTipoControleUtilidadesUsuarioDTO );
        $objTipoControleUtilidadesDTO->setArrObjRelTipoControleUtilidadesUsuarioDTO( $arrGestoresDTO );

        $strItensSelGestores = "";
        $objUsuarioRN = new UsuarioRN();

        for($x = 0;$x<count($arrGestoresDTO);$x++){
            $strItensSelGestores .= "<option value='" . $arrGestoresDTO[$x]->getNumIdUsuario() .  "'>" . $arrGestoresDTO[$x]->getStrNomeUsuario() . "</option>";
        }



        //============= FIM OBTER REGISTROS RELACIONADOS PARA EDIÇAO ===============================

        break;

    default:
        throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
}

?>