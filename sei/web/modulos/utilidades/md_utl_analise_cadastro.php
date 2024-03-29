<?php

/**
 * @author Jaqueline Mendes
 * @since  06/11/2018
 */

require_once dirname(__FILE__) . '/../../SEI.php';

session_start();
SessaoSEI::getInstance()->validarLink();

$isPgPadrao      = array_key_exists('pg_padrao', $_GET) ? $_GET['pg_padrao'] : (array_key_exists('hdnIsPgPadrao', $_POST) ? $_POST['hdnIsPgPadrao'] : 0);

$isMeusProcessos = true;

if(is_null($isPgPadrao) || $isPgPadrao == 0) {
    PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);
    $isMeusProcessos = false;
}

//Acao �nica
$acaoPrincipal = 'md_utl_analise_cadastrar';
$acaoConsultar = 'md_utl_analise_consultar';

//URL Base
$strUrlPadrao = 'controlador.php?acao=' . $acaoPrincipal;

// Vars
$idProcedimento    = array_key_exists('id_procedimento', $_GET) ? $_GET['id_procedimento'] : $_POST['hdnIdProcedimento'];
$strInformComp     = '';
$idControleDsmp    = null;
$isConsultar       = false;
$idsAtividades     = array();
$isConsultar       = false;
$isAlterar         = false;
$isCadastrar       = false;
$disabledConsultar = "";
$disabled          = 'disabled="disabled"';
$strTitulo         = 'An�lise ';
$strTela           = trim($strTitulo);
$displayFila       = "display:none";
$isRetriagem       = array_key_exists('id_retriagem', $_GET) ? $_GET['id_retriagem'] : $_POST['hdnIdRetriagem'];
$isProcessoConcluido = 0;
$idUsuarioFezAnalise   = null;
$idUsuarioDistrAnalise = null;

///rNS
$objMdUtlControleDsmpRN      = new MdUtlControleDsmpRN();
$objTriagemRN               = new MdUtlTriagemRN();
$objRegrasGerais            = new MdUtlRegrasGeraisRN();
$objTriagemDTO              = new MdUtlTriagemDTO();
$objMdRelTpCtrlUndRN        = new MdUtlAdmRelTpCtrlDesempUndRN();
$objMdUtlAdmAtvSerieProdRN  = new MdUtlAdmAtvSerieProdRN();
$objMdUtlAnaliseRN          = new MdUtlAnaliseRN();
$objMdUtlRelTriagemAtvRN    = new MdUtlRelTriagemAtvRN();
$objFilaRN                  = new MdUtlAdmFilaRN();
$objMdUtlAdmAtvSerieProdDTO = new MdUtlAdmAtvSerieProdDTO();
$objMdUtlAnaliseDTO         = new MdUtlAnaliseDTO();

$objProcedimentoDTO        = $objRegrasGerais->getObjProcedimentoPorId($idProcedimento);
$numProcessoFormatado      = $objProcedimentoDTO->getStrProtocoloProcedimentoFormatado();
$objProcedimentoDTO = null;
// retorna objeto controle desempenho
$objControleDsmpDTO        = $objMdUtlControleDsmpRN->getObjControleDsmpAtivo($idProcedimento);

$idUsuarioDistribuicao    = $objControleDsmpDTO->getNumIdUsuarioDistribuicao();
$idTipoControle           = $objControleDsmpDTO->getNumIdMdUtlAdmTpCtrlDesemp();
$situacaoAtual            = $objControleDsmpDTO->getStrStaAtendimentoDsmp();
$rascunho = 0;

if($situacaoAtual == "15") {
    $rascunho = 1;
}
$idFilaAtiva              = $_GET['id_fila'];
$selEncaminhamentoAnalise ='';
$arrObjFilaDTO            = $objFilaRN->getFilasVinculadosUsuario( $idTipoControle ); #$objFilaRN->getFilasTipoControle($idTipoControle);
$selFila                  = MdUtlAdmFilaINT::montarSelectFilas('', $arrObjFilaDTO);
$selEncaminhamentoAnalise = MdUtlControleDsmpINT::montarSelectEncaminhamentoAnaliseTriagem();
$arrayBuscarUsuarios = array(
    "tipo_selecao" => "1",
    "id_tipo_controle_utl" => $idTipoControle,
    "is_bol_distribuicao" => "1",
    "id_fila" => $idFilaAtiva,
    "id_status" => "5",
    "arr_procedimentos" => $idProcedimento
);

$selEncaminhamentoResponsavelAvaliacao = MdUtlControleDsmpINT::montarSelectMembroResponsavelAvaliacao(NULL, $arrayBuscarUsuarios);
//monta um array com o tipo de controle do processo pra ser usado na busca das labels que retornam o tempo de execucao, distribuidas, etc
$arrIdsTpCtrls = [$idTipoControle];


$dataAnalise = date("d/m/Y");
// Processo para recuperar ids do rel_triagem_atv
$idMdUtlAnalise           = $objControleDsmpDTO->getNumIdMdUtlAnalise();
$idTriagem                = $objControleDsmpDTO->getNumIdMdUtlTriagem();

// informacoes usadas para a funcionalidade de distribuir o processo automaticamente para o analista
$strSiglaUsuario          = SessaoSEI::getInstance()->getStrSiglaUsuario();
$strNmSiglaUsuario        = SessaoSEI::getInstance()->getStrNomeUsuario() . " ($strSiglaUsuario)";
$chkDistAutoParaMim       = null;
$bolPertenceAFila         = false;

//recupera o nome do usuario que realizou/realizara a analise
$idObj = in_array(
            $objControleDsmpDTO->getStrStaAtendimentoDsmp(),
            [MdUtlControleDsmpRN::$EM_ANALISE , MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE , MdUtlControleDsmpRN::$RASCUNHO_ANALISE, MdUtlControleDsmpRN::$RASCUNHO_CORRECAO_ANALISE]
        )
        ? $objControleDsmpDTO->getNumIdMdUtlControleDsmp()
        : $objControleDsmpDTO->getNumIdMdUtlAnalise();

$UsuarioRespTriagAnaliseAval = MdUtlControleDsmpINT::getNomeUsuarioRespTriagAnaliseAval(
    $idObj,
    $objControleDsmpDTO->getStrStaAtendimentoDsmp(),
    MdUtlControleDsmpRN::$STR_TIPO_ACAO_ANALISE
);

$nm_usuario_analise = $UsuarioRespTriagAnaliseAval->getStrNome();
$id_usuario_analise = $UsuarioRespTriagAnaliseAval->getNumIdUsuario();
$idUsuarioResp      = $id_usuario_analise;

$selPeriodo = MdUtlControleDsmpINT::montarSelectPeriodoAnalise($objControleDsmpDTO->getNumIdMdUtlAdmTpCtrlDesemp(), $id_usuario_analise);

//Urls
$acaoOrigem = $isMeusProcessos ? 'md_utl_meus_processos_listar' : 'md_utl_processo_listar';
$strUrlValidarDocumentoSEI = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_utl_adm_validar_documento_sei');
$strLinkValidaUsuarioPertenceAFila = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_utl_usuario_pertence_fila');
$strUrlBuscarDadosCarga = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_utl_buscar_dados_carga_usuario_todos_tpctrl');

$isPgPadraoRetriagem = !is_null($isPgPadrao) && $isPgPadrao != 0 ? '&pg_padrao=1' : '';
$strUrlRetriagem           = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_triagem_alterar&acao_origem='.$acaoOrigem.'&id_procedimento='.$idProcedimento.'&id_fila='.$idFilaAtiva.'&id_retriagem=1'.$isPgPadraoRetriagem);
$strUrlRtgAnlCorrecao      = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_triagem_alterar&acao_origem='.$acaoOrigem.'&id_procedimento='.$idProcedimento.'&id_fila='.$idFilaAtiva.'&id_retriagem=1&isRtgAnlCorrecao=1'.$isPgPadraoRetriagem);

$linkCancelar = "controlador.php?acao=".($isMeusProcessos ? $_GET['acao_origem'] : 'md_utl_processo_listar');
$linkCancelar .= $acaoOrigem == 'md_utl_processo_listar' ? "&id_procedimento=$idProcedimento" : "";
$strCancelar  = SessaoSEI::getInstance()->assinarLink($linkCancelar);

$idsAtividades = $objTriagemRN->getIdsAtividadesTriagem($idTriagem);

$idStatus = $objControleDsmpDTO->getStrStaAtendimentoDsmp();
$idFila = array_key_exists('id_fila', $_GET) && $_GET['id_fila'] != '' ? trim($_GET['id_fila']) : trim($_POST['hdnIdFila']);
$idProcedimentoTelaProc = array_key_exists('id_procedimento', $_GET) ? trim($_GET['id_procedimento']) : trim($_POST['hdnIdProcedimentoTelaProc']);
$idTipoControle = $objControleDsmpDTO->getNumIdMdUtlAdmTpCtrlDesemp();

$objMdUtlFilaPrmUsuRN     = new MdUtlAdmFilaPrmGrUsuRN;
$tipoRevisao              = $objMdUtlFilaPrmUsuRN->getPercentualTriagemAnalisePorFila($idFilaAtiva);

$displayMembroResponsavelAvaliacao = "display:none";
if($tipoRevisao == "1") {
    $displayMembroResponsavelAvaliacao = "";
}

if(!is_null($idMdUtlAnalise)){

    $objMdUtlAnaliseDTO = new MdUtlAnaliseDTO();
    $objMdUtlAnaliseRN  = new MdUtlAnaliseRN();
    $objMdUtlAnaliseDTO->setNumIdMdUtlAnalise($idMdUtlAnalise);
    $objMdUtlAnaliseDTO->retTodos();
    $objMdUtlAnaliseDTO = $objMdUtlAnaliseRN->consultar($objMdUtlAnaliseDTO);
    $strInformComp            = !is_null($objMdUtlAnaliseDTO) ? $objMdUtlAnaliseDTO->getStrInformacoesComplementares() : '';
    $vlFila                   = !is_null($objMdUtlAnaliseDTO) ? $objMdUtlAnaliseDTO->getNumIdMdUtlAdmFila() : '';
    $vlEncaminhamento         = !is_null($objMdUtlAnaliseDTO) ? $objMdUtlAnaliseDTO->getStrStaEncaminhamentoAnalise() : '';
    $vlMembroResponsavelAvaliacao        = !is_null($objMdUtlAnaliseDTO) ? $objMdUtlAnaliseDTO->getNumIdUsuarioAvaliacao() : '';
    $selEncaminhamentoAnalise = MdUtlControleDsmpINT::montarSelectEncaminhamentoAnaliseTriagem($vlEncaminhamento);
    $arrayBuscarUsuarios = array(
        "tipo_selecao" => "1",
        "id_tipo_controle_utl" => $idTipoControle,
        "is_bol_distribuicao" => "1",
        "id_fila" => $idFila,
        "id_status" => "5",
        "arr_procedimentos" => $idProcedimentoTelaProc
    );

    $selEncaminhamentoResponsavelAvaliacao = MdUtlControleDsmpINT::montarSelectMembroResponsavelAvaliacao($vlMembroResponsavelAvaliacao, $arrayBuscarUsuarios);
    $dataPeriodoInicioAnalise = $objMdUtlAnaliseDTO->getDtaPeriodoInicio();
    $dataPeriodoFimAnalise = $objMdUtlAnaliseDTO->getDtaPeriodoFim();
    $dataAnalise = $dataPeriodoInicioAnalise;
    $selPeriodo = MdUtlControleDsmpINT::montarSelectPeriodoAnalise($objControleDsmpDTO->getNumIdMdUtlAdmTpCtrlDesemp(), $id_usuario_analise, $dataPeriodoInicioAnalise, $dataPeriodoFimAnalise, $objMdUtlAnaliseDTO->getStrStaFrequenciaAdmPrmGr());
    $selFila                  = MdUtlAdmFilaINT::montarSelectFilas($vlFila, $arrObjFilaDTO);
    $idUsuarioFezAnalise      = $objMdUtlAnaliseDTO->getNumIdUsuario();
    $idUsuarioDistrAnalise    = $objControleDsmpDTO->getNumIdUsuarioDistribuicao();
    if($objMdUtlAnaliseDTO->getStrStaFrequenciaAdmPrmGr() != NULL) {
        $staFrequenciaAdmPrmGr = $objMdUtlAnaliseDTO->getStrStaFrequenciaAdmPrmGr();
    } else {
        $staFrequenciaAdmPrmGr = $selPeriodo[0];
    }
    // informacao usada para a funcionalidade de distribuir o processo automaticamente para o analista apos finalizar o processo
    $bolPertenceAFila = $objFilaRN->verificaUsuarioLogadoPertenceFila(
        [ $vlFila , 1 , true , $idUsuarioFezAnalise ]
    );

    if( $bolPertenceAFila )
        $chkDistAutoParaMim = !is_null($objMdUtlAnaliseDTO) ? $objMdUtlAnaliseDTO->getStrDistAutoParaMim() : null;

    $ckbRelatarDiaDia = !is_null($objMdUtlAnaliseDTO) ? $objMdUtlAnaliseDTO->getStrSinRelatarDiaDia() : null;

}

if($ckbRelatarDiaDia == "S") {
    $displayDatas = "block";
} else {
    $displayDatas = "none";
}
if(!is_null($idProcedimento) && !$acaoPrincipal != $_GET['acao']){
    $objMdUtlRelTriagemAtvDTO = $objTriagemRN->getObjDTOAnalise($idTriagem);
}

$objMdUtlAdmPrmGrRN      = new MdUtlAdmPrmGrRN();
$isTpProcParametrizado   = $objMdUtlAdmPrmGrRN->verificaTipoProcessoParametrizado(array($objControleDsmpDTO->getNumIdTpProcedimento(), $idTipoControle));

$isJsTpProcParametrizado = $isTpProcParametrizado ? '1' : '0';

//Retorna os tempos calculados: Executado, Pendente, Distribuido e Carga Padrao
$arrParams = ['idTipoControle' => $arrIdsTpCtrls , 'idUsuarioParticipante' => $idUsuarioResp , 'isRetornoXML' => false];
$arrTempos = MdUtlAdmPrmGrUsuINT::buscarDadosCargaUsuarioCompleto( $arrParams );

//Configura��o da Pagina��o
switch (true) {

    //region Listar
    case ($acaoPrincipal == $_GET['acao'] || $_POST["hdnRascunho"] == "1"):
        $isCadastrar = true;
        $count = $objMdUtlRelTriagemAtvRN->contar($objMdUtlRelTriagemAtvDTO);

        if ($count > 0) {
            $arrObjs = $objMdUtlRelTriagemAtvRN->listar($objMdUtlRelTriagemAtvDTO);
        }

		$arrComandos[] = '<button type="button" accesskey="s" id="btnSalvarRascunho" value="salvarRascunho" onClick="salvarRascunho()" class="infraButton botaoSalvar"><span class="infraTeclaAtalho">S</span>alvar Rascunho</button>';
		$arrComandos[] = '<button type="submit" accesskey="s" id="btnSalvar" value="salvar" class="infraButton botaoSalvar"><span class="infraTeclaAtalho">E</span>ncaminhar para Avalia��o</button>';
		$arrComandos[] = '<button type="button" accesskey="t" id="btnRetriagem" value="Retriagem" onClick="Retriagem();" class="infraButton">Re<span class="infraTeclaAtalho">t</span>riagem</button>';
		$arrComandos[] = '<button type="button" accesskey="c" id="" value="Cancelar" onClick="fechar();" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

        if(!empty($_POST)){
            try {

                $objInfraException = new InfraException();
                MdUtlAnaliseINT::validaPostAnalise( $_POST , $objInfraException );
                $objInfraException->lancarValidacoes();

                if( $_POST['hdnIdRetriagem'] == 1 && $_POST["hdnSalvarRascunho"] != "1"){
	                $_POST['isOrigemTelaAnalise'] = true;

	                // retorna os itens que foram marcados na Tela de analise para a busca dos registros de
                    // RelTriagem corretos para a retriagem automatica
	                $arrItens       = explode( ',' , $_POST['hdnItensSelecionados'] );
	                $arrIdsRelTriag = [];
	                foreach ( $arrItens as $item ) {
		                array_push($arrIdsRelTriag,$_POST['idRelTriagem_'.$item]);
	                }

	                //simula a gera�ao da grid, como se estivesse na tela de Retriagem
	                $arrObjsRelTriag             = $objMdUtlRelTriagemAtvRN->getObjsRelTriagemAtividade($arrIdsRelTriag);
	                $objMdUtlAdmAtividadeRN      = new MdUtlAdmAtividadeRN();
	                $arrItensRelTriagem          = $objMdUtlAdmAtividadeRN->getAtividadesParaRetriagem($arrObjsRelTriag);
	                $_POST['hdnTbAtividade']     = $arrItensRelTriagem['itensTable'];
	                $_POST['hdnTmpExecucao']     = $arrItensRelTriagem['tmpExecucao'];
	                $_POST['hdnIsPossuiAnalise'] = 'S';
	                $objTriagemDTO               = $objTriagemRN->buscarObjTriagemPorId($idTriagem);
	                $isRetriagemConcluida        = $objTriagemRN->cadastroRetriagem($objTriagemDTO,$objControleDsmpDTO);
                }

                $objRn = new MdUtlAnaliseRN();

                $isProcessoConcluido = $objRn->cadastrarDadosAnalise(array($_POST, $isTpProcParametrizado, false));

                $actionRedirect = ($isPgPadrao == 0) ? 'md_utl_processo_listar' : 'md_utl_meus_processos_dsmp_listar';
                header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $actionRedirect . '&id_procedimento=' . $idProcedimento.'&is_processo_concluido=' . $isProcessoConcluido));

                die;

            }catch(Exception $e){
                PaginaSEI::getInstance()->processarExcecao($e);
                $url = 'controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao_origem'].'&id_procedimento='.$_POST['hdnIdProcedimento'].'&id_fila='.$_POST['hdnIdFilaAtiva'];
                header('Location: ' . SessaoSEI::getInstance()->assinarLink( $url ) );
                #throw new InfraException('Erro cadastrando .',$e);
            }

        }

        break;

    case $_GET['acao'] == 'md_utl_analise_consultar':

        $isConsultar = true;
        $arrObjs = $objMdUtlRelTriagemAtvRN->listarComAnalise($idMdUtlAnalise);

        $arrComandos[] = '<button type="button" accesskey="c" id="btnFechar" value="Fechar" onclick="fechar();" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
        if(is_null($idMdUtlAnalise)) {
            $displayFila = '';
        }else{
            if($objMdUtlAnaliseDTO->getStrStaEncaminhamentoAnalise() == MdUtlControleDsmpRN::$ENC_ASSOCIAR_EM_FILA){
                $displayFila = '';
            }
        }
        $disabledConsultar = 'disabled = "disabled"';
        break;

    case $_GET['acao'] == 'md_utl_analise_alterar':

        if($rascunho == "1" || $situacaoAtual == "10" || $situacaoAtual == "16") {
            $isAlterar = true;
        } else {
            $isAlterar = false;
        }
        $arrObjsPreenchidos = $objMdUtlRelTriagemAtvRN->listarComAnalise($idMdUtlAnalise);

        if(is_null($idMdUtlAnalise)) {
            $displayFila = '';
        }else{
            if($objMdUtlAnaliseDTO->getStrStaEncaminhamentoAnalise() == MdUtlControleDsmpRN::$ENC_ASSOCIAR_EM_FILA){
                $displayFila = '';
            }
        }

        //Set Valor Default Objs Preenchidos
        $setValorDefaultObj = function ($value) {
            return $value->setStrSinObjPreenchido('N');
        };
        array_map($setValorDefaultObj, $arrObjsPreenchidos);

        $count = $objMdUtlRelTriagemAtvRN->contar($objMdUtlRelTriagemAtvDTO);

        if ($count > 0) {
            $arrObjs = $objMdUtlRelTriagemAtvRN->listar($objMdUtlRelTriagemAtvDTO);

            //Set Valor Default Objs Analise
            $setValorDefaultObj2 = function ($value) {
                return $value->setStrSinAnalisado('N');
            };
            array_map($setValorDefaultObj2, $arrObjs);

            foreach($arrObjs as $key1 => $objDTO){

                $idTpProdutoAtividade   = $objDTO->getNumIdMdUtlAdmTpProduto();
                $idSerieAtiv            = $objDTO->getNumIdSerieRel();
                $idAtividadeProduto     = $objDTO->getNumIdMdUtlAdmAtividade();

                foreach($arrObjsPreenchidos as $key2 => $objPreenchidoDTO){

                    $idTpProdutoPreenchido = $objPreenchidoDTO->getNumIdMdUtlAdmTpProduto();
                    $idAtividadePreenchido = $objPreenchidoDTO->getNumIdMdUtlAdmAtividade();
                    $idSerieAtual          = $objPreenchidoDTO->getNumIdSerie();

                    $isAtividadeIgual      = $idAtividadeProduto == $idAtividadePreenchido;
                    $isProdutoIgual        = !is_null($idTpProdutoPreenchido) && $idTpProdutoAtividade == $idTpProdutoPreenchido;
                    $isSerieIgual          = !is_null($idSerieAtual) && $idSerieAtiv == $idSerieAtual;
                    $isProdGeralIgual      = $isSerieIgual || $isProdutoIgual;
                    $isProdMarcado         = $arrObjs[$key1]->getNumIdMdUtlRelTriagemAtv() == $objPreenchidoDTO->getNumIdMdUtlRelTriagemAtv();

                    if(
                        $isProdGeralIgual &&
                        $isAtividadeIgual &&
                        $objPreenchidoDTO->getStrSinObjPreenchido() == 'N' &&
                        $arrObjs[$key1]->getStrSinAnalisado() == 'N'
                        && $isProdMarcado
                    ){
                        $arrObjs[$key1]->setStrSinAnalisado('S');
                        $arrObjs[$key1]->setStrObservacaoAnalise($objPreenchidoDTO->getStrObservacaoAnalise());
                        $arrObjs[$key1]->setStrProtocoloFormatado($objPreenchidoDTO->getStrProtocoloFormatado());
                        $arrObjsPreenchidos[$key2]->setStrSinObjPreenchido('S');
                    }
                }
            }
        }
        if($rascunho != "1") {
	        $arrComandos[] = '<button type="button" accesskey="s" id="btnSalvarRascunho" value="salvarRascunho" onClick="salvarRascunho()" class="infraButton botaoSalvar"><span class="infraTeclaAtalho">S</span>alvar Rascunho</button>';
            $arrComandos[] = '<button type="submit" accesskey="e" id="btnSalvar" value="salvar" class="infraButton botaoSalvar"><span class="infraTeclaAtalho">E</span>ncaminhar para Avalia��o</button>';
            $arrComandos[] = '<button type="button" accesskey="t" id="btnRetriagem" value="Retriagem" onClick="RetriagemAnlCorrecao()" class="infraButton">Re<span class="infraTeclaAtalho">t</span>riagem</button>';
            $arrComandos[] = '<button type="button" accesskey="v" id="btnAbrirModalRevisao" value="Revisao" onClick="abrirModalRevisao()" class="infraButton">A<span class="infraTeclaAtalho">v</span>alia��o</button>';
            $arrComandos[] = '<button type="button" accesskey="c" id="" onclick="fechar()" value="Cancelar" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';
        } else {
	        $arrComandos[] = '<button type="button" accesskey="s" id="btnSalvarRascunho" value="salvarRascunho" onClick="salvarRascunho()" class="infraButton botaoSalvar"><span class="infraTeclaAtalho">S</span>alvar Rascunho</button>';
            $arrComandos[] = '<button type="submit" accesskey="e" id="btnSalvar" value="salvar" class="infraButton botaoSalvar"><span class="infraTeclaAtalho">E</span>ncaminhar para Avalia��o</button>';
            $arrComandos[] = '<button type="button" accesskey="t" id="btnRetriagem" value="Retriagem" onClick="Retriagem();" class="infraButton">Re<span class="infraTeclaAtalho">t</span>riagem</button>';
            $arrComandos[] = '<button type="button" accesskey="c" id="" value="Cancelar" onClick="fechar();" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';
        }
        $objRelTriagemAnaliseRN = new MdUtlRelTriagemAtvRN();
        $arrObjsDadosAnalise = $objRelTriagemAnaliseRN->listarComAnalise($idMdUtlAnalise);

        $strLinkIniciarRevisao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_revisao_analise_consultar&acao_origem=' . $_GET['acao'] . '&id_procedimento=' . $idProcedimento);

        if(!empty($_POST)){
            try {

                $objInfraException = new InfraException();
                MdUtlAnaliseINT::validaPostAnalise( $_POST , $objInfraException );
                $objInfraException->lancarValidacoes();

                $objRevisaoRN  = new MdUtlRevisaoRN();
                $idRevisao     = $objControleDsmpDTO->getNumIdMdUtlRevisao();
                $objRevisaoDTO = $objRevisaoRN->buscarObjRevisaoPorId($idRevisao);
                $objRevisaoRN->desativar(array($objRevisaoDTO));
                $objMdUtlAnaliseRN->desativar(array($objMdUtlAnaliseDTO));

                if( $_POST['hdnIdRetriagem'] == 1 ){
	                $_POST['isOrigemTelaAnalise'] = true;

	                // retorna os itens que foram marcados na Tela de analise para a busca dos registros de
	                // RelTriagem corretos para a retriagem automatica
	                $arrItens       = explode( ',' , $_POST['hdnItensSelecionados'] );
	                $arrIdsRelTriag = [];
	                foreach ( $arrItens as $item ) {
		                array_push($arrIdsRelTriag,$_POST['idRelTriagem_'.$item]);
	                }

	                //simula a gera�ao da grid, como se estivesse na tela de Retriagem
	                $arrObjsRelTriag             = $objMdUtlRelTriagemAtvRN->getObjsRelTriagemAtividade($arrIdsRelTriag);
	                $objMdUtlAdmAtividadeRN      = new MdUtlAdmAtividadeRN();
	                $arrItensRelTriagem          = $objMdUtlAdmAtividadeRN->getAtividadesParaRetriagem($arrObjsRelTriag);
	                $_POST['hdnTbAtividade']     = $arrItensRelTriagem['itensTable'];
	                $_POST['hdnTmpExecucao']     = $arrItensRelTriagem['tmpExecucao'];
	                $_POST['hdnIsPossuiAnalise'] = 'S';
	                $objTriagemDTO               = $objTriagemRN->buscarObjTriagemPorId($idTriagem);
	                $isRetriagemConcluida        = $objTriagemRN->cadastroRetriagem($objTriagemDTO,$objControleDsmpDTO);
                }

                $isProcessoConcluido = $objMdUtlAnaliseRN->cadastrarDadosAnalise(array($_POST, $isTpProcParametrizado, true));

                if($isPgPadrao == 0) {
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_processo_listar&id_procedimento=' . $idProcedimento.'&is_processo_concluido='.$isProcessoConcluido));
                }else{
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_meus_processos_dsmp_listar&id_procedimento=' . $idProcedimento.'&is_processo_concluido='.$isProcessoConcluido));
                }

            }catch(Exception $e){
                PaginaSEI::getInstance()->processarExcecao($e);
                $url = 'controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao_origem'].'&id_procedimento='.$_POST['hdnIdProcedimento'].'&id_fila='.$_POST['hdnIdFilaAtiva'];
                header('Location: ' . SessaoSEI::getInstance()->assinarLink( $url ) );
                //throw new InfraException('Erro cadastrando .',$e);
            }

        }

        break;

    //region Erro
    default:
        throw new InfraException("A��o '" . $_GET['acao'] . "' n�o reconhecida.");
    //endregion
}

$numRegistros = !is_null($idsAtividades) && count($idsAtividades) > 0 ? count($arrObjs) : 0;
//Tabela de resultado.
if ($numRegistros > 0) {

    $htmlCheck    = '<a href="javascript:void(0);" id="lnkInfraCheck" onclick="selecionarTodosAnalise();" tabindex="1001"><img src="'. PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/check.svg" id="imgInfraCheck" title="Remover Sele��o" alt="Remover Sele��o" class="infraImg"></a>';
    $htmlCheck    = !$isConsultar ? $htmlCheck : '';

    $strResultado .= '<table id="tbAnalise"class="infraTable" summary="An�lise" style="width: 100%">';

    $strResultado .= '<caption class="infraCaption">';
    $strResultado .= PaginaSEI::getInstance()->gerarCaptionTabela('An�lise', $numRegistros);
    $strResultado .= '</caption>';
    //Cabe�alho da Tabela
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" id="tdSelecao">'. $htmlCheck . '</th>';
    $strResultado .= '<th class="infraTh" id="tdAtvidade" style="display:none">Atividade</th>';
    $strResultado .= '<th class="infraTh" id="tdProduto" style="width: 30%">Produtos Esperados</th>';
    $strResultado .= '<th class="infraTh" id="tdNumSei" style="width: 15%">N�mero SEI</th>';
    $strResultado .= '<th class="infraTh" id="tdObs" style="width: 55%">Observa��es</th>';
    $strResultado .= '</tr>';

    $strCssTr       = '<tr class="infraTrEscura">';
    $linhaClara     = true;
    $cont           = 0;
    $bloco          = 0;
    $idRelTriagem2  = 0;
    for ($i = 0; $i < $numRegistros; $i++) {

        //vars
        $observacao     = "";
        $numSei         = "";
        $idRelTriagem   = "";
        $strValor       = 'N';
        $rowNmAtv       = '';
	    $strId          = $i;

        // Controle para tratar os dados: Tempo de Execucao e Nome da Atividade
        $vlrUnidEsf      = !is_null($arrObjs[$i]->getNumTempoExecucaoAtribuido()) ? $arrObjs[$i]->getNumTempoExecucaoAtribuido() : 0;
        $tempoEsforcoAtividade = $vlrUnidEsf;
        $vlrUnidEsf      =  MdUtlAdmPrmGrINT::convertToHoursMins( $vlrUnidEsf );

        $ctrlNmAtividade = PaginaSEI::tratarHTML($arrObjs[$i]->getStrNomeAtividade().' (' . MdUtlAdmAtividadeRN::$ARR_COMPLEXIDADE[$arrObjs[$i]->getNumComplexidadeAtividade()]) . ') - ' . $vlrUnidEsf;

        if( $idRelTriagem2 != $arrObjs[$i]->getNumIdMdUtlRelTriagemAtv() ){
	        $dataExecucaoAtividade = '';
            if($arrObjs[$i]->getDtaDataExecucao() != "") {
                $dataExecucaoAtividade = $arrObjs[$i]->getDtaDataExecucao();
            }

            if($tipoRevisao == "2") {
                if($arrObjs[$i]->getStrSinAtvRevAmostragem() == "S") {
                    $displayMembroResponsavelAvaliacao = '';
                }
            }
            $bloco++;
            $rowNmAtv = '<tr style="height: 50px;" class="table-success">
                <td colspan="5">
                    <div class="row">
                        <div class="col-sm-8 col-md-9">'.$ctrlNmAtividade.'</div>
                        <div class="col-5 col-sm-4 col-md-3 dataRelatarDiaDia" style="display: '.$displayDatas.'"> 
                            <div class="float-right input-group mb-3" style="margin-bottom: 0 !important">
                                <label id="lblDtAnaliseAtividade" for="txtDtAnaliseAtividade'.$arrObjs[$i]->getNumIdMdUtlRelTriagemAtv().'"  class="infraLabelObrigatorio" style="margin-bottom: 0; line-height: 2">Data: </label>
                                <input type="text" id="txtDtAnaliseAtividade'.$arrObjs[$i]->getNumIdMdUtlRelTriagemAtv().'" name="txtDtAnaliseAtividade'.$arrObjs[$i]->getNumIdMdUtlRelTriagemAtv().'" onchange="return validaPeriodoDataDiaADia(this);"
                                onkeypress="return infraMascara(this, event,\'##/##/####\')" class="infraText form-control txtDtAnaliseAtividade"
                                value="'.PaginaSEI::tratarHTML($dataExecucaoAtividade).'" tabindex="'.PaginaSEI::getInstance()->getProxTabDados().'">
                                <img src="'.PaginaSEI::getInstance()->getDiretorioSvgGlobal() .'/calendario.svg" id="imgCalDthCorte"
                                title="Selecionar Data/Hora Inicial" alt="Selecionar Data de Corte" class="infraImg"
                                onclick="infraCalendario(\'txtDtAnaliseAtividade'.$arrObjs[$i]->getNumIdMdUtlRelTriagemAtv().'\',this,false,\''.$dataExecucaoAtividade.'\');">
                            </div>
                        </div>
                    </div>
                </td>
                <input type="hidden" id="complexidadeTarefa'.$arrObjs[$i]->getNumIdMdUtlRelTriagemAtv().'" value="'.$tempoEsforcoAtividade.'">
            </tr>';
        }

        $idRelTriagem2  = $arrObjs[$i]->getNumIdMdUtlRelTriagemAtv();
        $idRelTriagem   = $idRelTriagem2;
        $strIdSerieProd = $isCadastrar ? $arrObjs[$i]->getNumIdMdUtlAdmAtvSerieProd() : null;
        $TmpExecucao    = $isCadastrar || $isAlterar ? $arrObjs[$i]->getNumTempoExecucaoProduto() : 0;
        $idSerieAtual   = $isCadastrar || $isAlterar ? $arrObjs[$i]->getNumIdSerieRel() : $arrObjs[$i]->getNumIdSerie();
        $isDocumentoSEI = !is_null($idSerieAtual);
        $vlProduto      = $isDocumentoSEI ? $arrObjs[$i]->getStrNomeSerie() : $arrObjs[$i]->getStrNomeProduto();
        $idProduto      = !is_null($arrObjs[$i]->getNumIdMdUtlAdmTpProduto()) ? $arrObjs[$i]->getNumIdMdUtlAdmTpProduto() : null;
        $isObrigatorio  = $isCadastrar || $isAlterar ? $arrObjs[$i]->getStrSinObrigatorio() == 'S' : false;

        $idUnico = $isCadastrar || $isAlterar ?  $arrObjs[$i]->getNumIdMdUtlRelTriagemAtv(). '_'.$arrObjs[$i]->getNumIdMdUtlAdmAtvSerieProd() :  $arrObjs[$i]->getNumIdMdUtlRelTriagemAtv(). '_'.$arrObjs[$i]->getNumIdMdUtlRelAnaliseProduto();

        //Cria a linha somente com o Nome da Atividade + Complexidade + tempo de Execu��o
        $strResultado .= $rowNmAtv;

        $strCssTr = $linhaClara ? '<tr class="infraTrClara">' : '<tr class="infraTrEscura">';

        $linhaClara = $linhaClara ? false : true;
        $strValor = 'N';

        if($isConsultar || $isAlterar) {
            $isAnalise = $isAlterar ? ($arrObjs[$i]->getStrSinAnalisado() == 'S' ? true : false) : true;

            if ($isAnalise) {
                $cont+=1;
                $observacao    = $arrObjs[$i]->getStrObservacaoAnalise();
                $nomeAtividade = $arrObjs[$i]->getStrNomeAtividade() . ' (' . MdUtlAdmAtividadeRN::$ARR_COMPLEXIDADE[ $arrObjs[$i]->getNumComplexidadeAtividade() ] . ')';
                $numSei        = $arrObjs[$i]->getStrProtocoloFormatado();
                $strValor = 'S';
            }
        }

        $strResultado .= $strCssTr;

        //Linha Checkbox
        $classObrigatorio = $isObrigatorio ? 'class="classTdObrigatorio"' : '';
        $strResultado .= '<td align="center" valign="middle" ' . $classObrigatorio.' >';

        $attrAdapt          = $strValor == 'S' ? ($isConsultar ? 'checkado = "S" checked="checked"' : ' checkado = "S" ') : '';
        $disabled           = $isAlterar && $strValor == 'S' ? '' : 'disabled="disabled"';
        $disabledCheckbox   = $isConsultar ? 'disabled="disabled"' : '';
        $attrs              = $disabledCheckbox . $disabledConsultar . $attrAdapt;

        $strResultado .= PaginaSEI::getInstance()->getTrCheck($i, $strId,  $arrObjs[$i]->getStrNomeAtividade().' (' . MdUtlAdmAtividadeRN::$ARR_COMPLEXIDADE[$arrObjs[$i]->getNumComplexidadeAtividade()] . ')', null, null, $attrs)  ;
        $strResultado .= '</td>';

        //Linha Nome Atividade
        $strResultado .= '<td style="width: 20%; display:none">';
        $strResultado .= $ctrlNmAtividade;
        $strResultado .= '</td>';

        //Linha Produtos Esperados
        $strResultado .= ($isObrigatorio) ? '<td style="font-weight: bold;">' : '<td>';
        $strResultado .= PaginaSEI::tratarHTML($vlProduto);
        $strResultado .= '</td>';

        //Linha N�mero SEI
        $strResultado .= '<td align="center">';
        $strResultado .= $isDocumentoSEI ? '<input '.$disabled.' maxlength="11" utlSomenteNumeroPaste="true" id="numeroSEI_'.$i.'" name="numeroSEI_'.$strId.'" onkeypress="return infraMascaraNumero(this, event,11)"; onchange="validarDocumentoSEI('.$idSerieAtual.','.$i.')" class="infraText form-control desabilitado" type="text" value="'.$numSei.'"/>' : '';
        $strResultado .= '</td>';

        //Linha Observa��o
        $strResultado .= '<td style="padding: 2px 10px 2px 5px;">';
        $strResultado .= '<textarea '.$disabled.' id="observacao_'.$i.'" name="observacao_'.$strId.'" class="form-control desabilitado" rows="2" cols="40" class="infraTextArea" maxlength="500" onkeypress="return infraMascaraTexto(this,event, 500);">'.$observacao.'</textarea>';
        $strResultado .= '</td>';

        //Linha idSerieProd
        $strResultado .= '<td style="display: none">';
        $strResultado .= '<input style="width: 97%;" name="idRelTriagem_'.$strId.'" type="text" value="'.$idRelTriagem.'" disabled="disabled" class="desabilitado"/>';
        $strResultado .= '</td>';

        $strResultado .= '<td style="display: none">';
        $strResultado .= '<input style="width: 97%;" name="idSerieProd_'.$strId.'" type="text" value="'.$strIdSerieProd.'" disabled="disabled" class="desabilitado"/>';
        $strResultado .= '</td>';

        //Linha TmpExecucao
        $strResultado .= '<td style="display: none">';
        $strResultado .= '<input style="width: 97%;" id="TmpExecucao" name="TmpExecucao_'.$strId.'" type="text" value="'.$TmpExecucao.'" disabled="disabled" class="desabilitado"/>';
        $strResultado .= '</td>';

        //Linha Produto
        $strResultado .= '<td style="display: none">';
        $strResultado .= '<input style="width: 97%;" id="idProduto" name="idProduto_'.$strId.'" type="text" value="'.$idProduto.'" disabled="disabled"  class="desabilitado"/>';
        $strResultado .= '</td>';

        //Linha Atividade
        $strResultado .= '<td style="display: none"><span>'.$bloco.'</span>';
        $strResultado .= '<input style="width: 97%;" id="idAtividade" name="idAtividade_'.$strId.'" type="text" value="'.$arrObjs[$i]->getNumIdMdUtlAdmAtividade().'" disabled="disabled" class="desabilitado"/>';
        $strResultado .= '</td>';

        //Linha Produto Nome
        $nomeProduto   = $isDocumentoSEI ? $arrObjs[$i]->getStrNomeSerie() : $arrObjs[$i]->getStrNomeProduto();
        $strResultado .= '<td style="display: none">';
        $strResultado .= '<input style="width: 97%;" id="nomeProduto" name="nomeProduto_'.$strId.'" type="text" value="'.$nomeProduto.'" disabled="disabled" class="desabilitado"/>';
        $strResultado .= '</td>';


        $strResultado .= '</tr>';

    }

    $strResultado .= '</table>';
}

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(':: ' . PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo . ' ::');
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
PaginaSEI::getInstance()->fecharStyle();
require_once "md_utl_geral_css.php";
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');

//texto tooltip
$txtTooltipEncaminhamentoAnalise = 'A depender das parametriza��es em seu perfil ou sobre as Atividades entregues, o que for selecionado neste campo ser� meramente sugestivo ou ser� executado imediatamente.\n \n Selecione a op��o "Associar em Fila ap�s Finalizar Fluxo" caso queira reiniciar o fluxo em alguma Fila imediatamente com a finaliza��o do fluxo atual.\n \n Ou selecione a op��o "Finalizar Fluxo" para concluir sem associar a qualquer Fila imediatamente na finaliza��o do fluxo atual.';

$linkProcedimento = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem=md_utl_analise_cadastrar&id_procedimento=' . $idProcedimento . '');

?>
    <form onsubmit="return onSubmitForm();" id="frmUtlAnaliseCadastro" method="post"
          action="<?= PaginaSEI::getInstance()->formatarXHTML(
              SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])
          ) ?>">

        <div class="row">
            <div class="col-12">
                <?php PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos); ?>
            </div>
        </div>

        <?php PaginaSEI::getInstance()->abrirAreaDados('auto'); ?>

        <input type="hidden" name="hdnIdProcedimento" id="hdnIdProcedimento" value="<?php echo $idProcedimento ?>"/>
        <input type="hidden" name="hdnIdFilaAtiva" id="hdnIdFilaAtiva" value="<?php echo $idFilaAtiva ?>"/>
        <input type="hidden" name="hdnIdTpCtrl" id="hdnIdTpCtrl" value="<?php echo $idTipoControle ?>"/>
        <input type="hidden" name="hdnIdCtrlProcFilaCorrecao" id="hdnIdCtrlProcFilaCorrecao" value="<?php echo $idCtrlProcFilaCorrecao ?>"/>
        <input type="hidden" name="hdnTmpExecucao" id="hdnTmpExecucao" />
        <input type="hidden" name="hdnFila" id="hdnFila" value="<?=$vlFila?>"/>
        <input type="hidden" name="hdnEncaminhamentoAnl" id="hdnEncaminhamentoAnl" value="<?=$vlEncaminhamento?>"/>
        <input type="hidden" name="hdnStaPermiteAssociarAnalise" id="hdnStaPermiteAssociarAnalise" value="<?php echo MdUtlControleDsmpRN::$ENC_ASSOCIAR_EM_FILA ?>"/>
        <input type="hidden" name="hdnSelFila" id="hdnSelFila" value=""/>
        <input type="hidden" name="hdnIsPgPadrao" id="hdnIsPgPadrao" value="<?php echo $isPgPadrao; ?>"/>
        <input type="hidden" name="hdnIdRetriagem" id="hdnIdRetriagem"/>
        <input type="hidden" name="hdnIdRtgAnlCorrecao" id="hdnIdRtgAnlCorrecao"/>
        <input type="hidden" name="idsAtividades" id="idsAtividades">
        <input type="hidden" name="hdnIdUsuarioDistrAuto" value="<?= SessaoSEI::getInstance()->getNumIdUsuario() ?>">
        <input type="hidden" name="hdnNmUsuarioDistrAuto" value="<?= $strNmSiglaUsuario ?>">
        <input type="hidden" name="hdnSalvarRascunho" id="hdnSalvarRascunho" value="0">
        <input type="hidden" name="hdnRascunho" id="hdnRascunho" value="<?= $rascunho ?>">
        <input type="hidden" name="staFrequenciaAdmPrmGr" id="staFrequenciaAdmPrmGr" value="<?= $staFrequenciaAdmPrmGr ?>">
        <input type="hidden" name="hdnNomeMembroResponsavelAvaliacao" id="hdnNomeMembroResponsavelAvaliacao">
        <input type="hidden" id="atividadesSelecionadas">
        <?php if( $isPgPadrao != 0 ): ?>
        <div class="row mb-3">
            <div class="col-12">
                <label class="marcar-label"> N�mero do Processo: </label>
                <a href="<?= $linkProcedimento ?>" target="_blank" alt="Acessar o processo: <?= $numProcessoFormatado ?>" title="Acessar o processo: <?= $numProcessoFormatado ?>" class="ancoraPadraoAzul">
                  <?= $numProcessoFormatado ?>
                </a>
            </div>
        </div>
        <?php endif ?>

        <div class="row">
            <div class="col-sm-6 col-md-5 col-lg-4">
                <div class="form-group">
                    <label id="lblTipoControle" accesskey="" class="infraLabelOpcional">Tipo de Controle:</label>
                    <input type="text" id="txtTipoControle" name="txtTipoControle" class="infraText form-control" value="<?= $objControleDsmpDTO->getStrNomeTpControle() ?>" disabled/>
                </div>
            </div>

            <div class="col-sm-6 col-md-5 col-lg-4">
                <div class="form-group">
                    <label id="lblFila" accesskey="" class="infraLabelOpcional">Fila:</label><br/>
                    <input type="text" id="txtNomeFila" name="txtNomeFila" class="infraText form-control" value="<?= $objControleDsmpDTO->getStrNomeFila() ?>" disabled/>
                </div>
            </div>
            <div class="col-sm-12 col-md-10 col-lg-4 mb-3">
                <label class="infraLabelOpcional">Membro Respons�vel pela An�lise:</label>
                <input type="text" value="<?= $nm_usuario_analise ?>" readonly class="infraText form-control">
            </div>
        </div>

        <?php require_once 'md_utl_triag_analise_rev_calculo_tempo.php' ?>

        <?php if($isTpProcParametrizado){ ?>
            <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-xs-10 form-group mb-3" style="padding-top: 9px;<?= $displayMembroResponsavelAvaliacao ?>">
                    <label id="lblUsuarioResponsavelAvaliacao" for="selUsuarioResponsavelAvaliacao" accesskey="" class="infraLabelOpcional">
                        Membro Respons�vel pela Avalia��o:
                    </label>
                    <select id="selUsuarioResponsavelAvaliacao" name="selUsuarioResponsavelAvaliacao" class="infraSelect padraoSelect form-control"
                            onchange="preencherNomeHidden(this);"
                            tabindex="<?= PaginaSEI::getInstance()->getProxTabDados()?>">
                        <?php echo $selEncaminhamentoResponsavelAvaliacao ?>
                    </select>

                </div>
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-xs-10">
                    <div class="form-group mb-3" id="divPrincipalEncaminhamento">
                        <div id="divEncaminhamentoAnl">
                            <label id="lblEncaminhamentoAnl" for="selEncaminhamentoAnl"  class="infraLabelObrigatorio">Encaminhamento da An�lise:</label>
                            <img class="infraImg" name="ajuda" src="/infra_css/svg/ajuda.svg" onmouseover="return infraTooltipMostrar('A depender das parametriza��es em seu perfil ou sobre as Atividades entregues, o que for selecionado neste campo ser� meramente sugestivo ou ser� executado imediatamente.\n \n Selecione a op��o \&quot;Associar em Fila ap�s Finalizar Fluxo\&quot; caso queira reiniciar o fluxo em alguma Fila imediatamente com a finaliza��o do fluxo atual.\n \n Ou selecione a op��o \&quot;Finalizar Fluxo\&quot; para concluir sem associar a qualquer Fila imediatamente na finaliza��o do fluxo atual.','Ajuda');" onmouseout="return infraTooltipOcultar();">
                            <select id="selEncaminhamentoAnl" name="selEncaminhamentoAnl" class="infraSelect padraoSelect form-control"
                                    onchange="controlarExibicaoAnalise(this);"
                                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados()?>">
                                <?php echo $selEncaminhamentoAnalise?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-xs-10">
                    <div class="form-group mb-3 mt-2" id="divFila" style="<?= $displayFila ?>">
                        <label id="lblFila" for="selFila" class="infraLabelObrigatorio">Fila:</label>
                        <select id="selFila" name="selFila" class="infraSelect form-control" onchange="carregarHiddenFila(this)">
                            <?= $selFila ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div id="divDistAutoParaMim" class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12" <?= $bolPertenceAFila ? 'style="margin-bottom:10px"' : 'style="display:none;margin-bottom:10px"'?> >
                    <div class="infraCheckboxDiv">
                        <input type="checkbox" name="ckbDistAutoParaMim" id="ckbDistAutoParaMim"
                            <?= $chkDistAutoParaMim == 'S' ? 'checked' : '' ?> value="S">
                        <label class="infraCheckboxLabel" for="ckbDistAutoParaMim"></label>
                    </div>
                    <label class="infraLabelChec infraLabelOpcional" for="ckbDistAutoParaMim">
                        Distribuir automaticamente a Triagem do pr�ximo fluxo para voc� mesmo?
                    </label>
                </div>
            </div>
            <div class="row">
                <?php
                if($selPeriodo[0] == "S") {
                    $labelComplementoPeriodo = "Semanal";
                } else {
                    $labelComplementoPeriodo = "Mensal";
                }
                if($selPeriodo[0] != "D") {
                ?>
                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-xs-10 form-group">
                        <label id="lblPeriodo" for="selPeriodo"  class="infraLabelObrigatorio">Per�odo <?= $labelComplementoPeriodo ?>:</label>
                        <select id="selPeriodo" name="selPeriodo" onchange="limparCamposData()" class="infraSelect padraoSelect form-control"
                                tabindex="<?= PaginaSEI::getInstance()->getProxTabDados()?>">
                            <?php echo $selPeriodo[1] ?>
                        </select>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-xs-10 pt-4 form-group div-check-dia">
                        <div class="infraCheckboxDiv">
                            <input type="checkbox" name="ckbRelatarDiaDia" id="ckbRelatarDiaDia"
                                <?= $ckbRelatarDiaDia == 'S' ? 'checked' : '' ?> value="S" onchange="relatarDiaDia(this)" class="form-check-input infraCheckboxInput">
                            <label class="infraCheckboxLabel" for="ckbRelatarDiaDia"></label>
                        </div>
                        <label class="infraLabelChec infraLabelOpcional" for="ckbRelatarDiaDia">
                            Relatar dia a dia do Per�odo
                        </label>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-xs-10 mb-3">
                        <label id="lblDtAnalise" for="txtDtAnalise"  class="infraLabelObrigatorio">Data:</label>
                        <div class="input-group mb-3">
                            <input type="text" id="txtDtAnalise" name="txtDtAnalise" onchange="return validaPeriodoData(this);"
                                   onkeypress="return infraMascara(this, event,'##/##/####')" class="infraText form-control"
                                   value="<?= PaginaSEI::tratarHTML($dataAnalise); ?>"
                                   tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                            <img src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/calendario.svg' ?>" id="imgCalDthAnalise"
                                 title="Selecionar Data/Hora Inicial" alt="Selecionar Data de An�lise" class="infraImg"
                                 onclick="infraCalendario('txtDtAnalise',this,false,'<?= $dataAnalise ?>');">
                        </div>
                    </div>
                    <?php
                }
                    ?>
            </div>
        <? } ?>

        <div class="row mb-3">
            <div class="col-12">
                <?php PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros); ?>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="form-group mb-3" id="divInformacaoComplementar">
                    <label id="lblInformacaoComplementar" for="txaInformacaoComplementar" class="infraLabelOpcional"
                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                        Informa��es Complementares da An�lise:
                    </label>
                    <textarea <?=$disabledConsultar?> id="txaInformacaoComplementar" name="txaInformacaoComplementar" rows="4" class="infraTextArea form-control" onkeypress="return infraMascaraTexto(this,event, 500);" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?php echo $strInformComp ?></textarea>
                </div>
            </div>
        </div>

        <?php
            PaginaSEI::getInstance()->fecharAreaDados();
            PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
        ?>

    </form>

<?php
    require_once "md_utl_funcoes_js.php";
    require_once "md_utl_geral_js.php";
    require_once "md_utl_analise_cadastro_js.php";

    PaginaSEI::getInstance()->fecharBody();
    PaginaSEI::getInstance()->fecharHtml();