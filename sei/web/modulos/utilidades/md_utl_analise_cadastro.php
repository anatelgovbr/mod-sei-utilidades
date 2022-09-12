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

//Acao única
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
$strTitulo         = 'Análise ';
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

$idFilaAtiva              = $_GET['id_fila'];
$selEncaminhamentoAnalise ='';
$arrObjFilaDTO            = $objFilaRN->getFilasVinculadosUsuario( $idTipoControle ); #$objFilaRN->getFilasTipoControle($idTipoControle);
$selFila                  = MdUtlAdmFilaINT::montarSelectFilas('', $arrObjFilaDTO);
$selEncaminhamentoAnalise = MdUtlControleDsmpINT::montarSelectEncaminhamentoAnaliseTriagem();

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
            [MdUtlControleDsmpRN::$EM_ANALISE , MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE]
        )
        ? $objControleDsmpDTO->getNumIdMdUtlControleDsmp()
        : $objControleDsmpDTO->getNumIdMdUtlAnalise();

$nm_usuario_analise = MdUtlControleDsmpINT::getNomeUsuarioRespTriagAnaliseAval(
    $idObj,
    $objControleDsmpDTO->getStrStaAtendimentoDsmp(),
    MdUtlControleDsmpRN::$STR_TIPO_ACAO_ANALISE
);

//Urls
$acaoOrigem = $isMeusProcessos ? 'md_utl_meus_processos_listar' : 'md_utl_processo_listar';
$strUrlValidarDocumentoSEI = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_utl_adm_validar_documento_sei');
$strLinkValidaUsuarioPertenceAFila = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_utl_usuario_pertence_fila');

$isPgPadraoRetriagem = !is_null($isPgPadrao) && $isPgPadrao != 0 ? '&pg_padrao=1' : '';
$strUrlRetriagem           = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_triagem_alterar&acao_origem='.$acaoOrigem.'&id_procedimento='.$idProcedimento.'&id_fila='.$idFilaAtiva.'&id_retriagem=1'.$isPgPadraoRetriagem);
$strUrlRtgAnlCorrecao      = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_triagem_alterar&acao_origem='.$acaoOrigem.'&id_procedimento='.$idProcedimento.'&id_fila='.$idFilaAtiva.'&id_retriagem=1&isRtgAnlCorrecao=1'.$isPgPadraoRetriagem);
$strDetalhamento           = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_processo_listar&id_procedimento=' . $idProcedimento);
$idsAtividades = $objTriagemRN->getIdsAtividadesTriagem($idTriagem);


if(!is_null($idMdUtlAnalise)){

    $objMdUtlAnaliseDTO = new MdUtlAnaliseDTO();
    $objMdUtlAnaliseRN  = new MdUtlAnaliseRN();
    $objMdUtlAnaliseDTO->setNumIdMdUtlAnalise($idMdUtlAnalise);
    $objMdUtlAnaliseDTO->retTodos();
    $objMdUtlAnaliseDTO = $objMdUtlAnaliseRN->consultar($objMdUtlAnaliseDTO);
    $strInformComp            = !is_null($objMdUtlAnaliseDTO) ? $objMdUtlAnaliseDTO->getStrInformacoesComplementares() : '';
    $vlFila                   = !is_null($objMdUtlAnaliseDTO) ? $objMdUtlAnaliseDTO->getNumIdMdUtlAdmFila() : '';
    $vlEncaminhamento         = !is_null($objMdUtlAnaliseDTO) ? $objMdUtlAnaliseDTO->getStrStaEncaminhamentoAnalise() : '';
    $selEncaminhamentoAnalise = MdUtlControleDsmpINT::montarSelectEncaminhamentoAnaliseTriagem($vlEncaminhamento);
    $selFila                  = MdUtlAdmFilaINT::montarSelectFilas($vlFila, $arrObjFilaDTO);
    $idUsuarioFezAnalise      = $objMdUtlAnaliseDTO->getNumIdUsuario();
    $idUsuarioDistrAnalise    = $objControleDsmpDTO->getNumIdUsuarioDistribuicao();
    
    // informacao usada para a funcionalidade de distribuir o processo automaticamente para o analista apos finalizar o processo
    $bolPertenceAFila = $objFilaRN->verificaUsuarioLogadoPertenceFila( 
        [ $vlFila , 1 , true , $idUsuarioFezAnalise ]
    );

    if( $bolPertenceAFila )
        $chkDistAutoParaMim = !is_null($objMdUtlAnaliseDTO) ? $objMdUtlAnaliseDTO->getStrDistAutoParaMim() : null;
}

if(!is_null($idProcedimento) && !$acaoPrincipal != $_GET['acao']){
    $objMdUtlRelTriagemAtvDTO = $objTriagemRN->getObjDTOAnalise($idTriagem);
}

$objMdUtlAdmPrmGrRN      = new MdUtlAdmPrmGrRN();
$isTpProcParametrizado   = $objMdUtlAdmPrmGrRN->verificaTipoProcessoParametrizado(array($objControleDsmpDTO->getNumIdTpProcedimento(), $idTipoControle));

$isJsTpProcParametrizado = $isTpProcParametrizado ? '1' : '0';

//Configuração da Paginação
switch ($_GET['acao']) {

    //region Listar
    case $acaoPrincipal:

        $isCadastrar = true;
        $count = $objMdUtlRelTriagemAtvRN->contar($objMdUtlRelTriagemAtvDTO);

        if ($count > 0) {
            $arrObjs = $objMdUtlRelTriagemAtvRN->listar($objMdUtlRelTriagemAtvDTO);
        }

        $arrComandos[] = '<button type="submit" accesskey="s" id="btnSalvar" value="salvar" class="infraButton botaoSalvar"><span class="infraTeclaAtalho">S</span>alvar</button>';
        $arrComandos[] = '<button type="button" accesskey="t" id="btnRetriagem" value="Retriagem" onClick="Retriagem();" class="infraButton">Re<span class="infraTeclaAtalho">t</span>riagem</button>';
        $arrComandos[] = '<button type="button" accesskey="c" id="" value="Cancelar" onClick="fechar();" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

        if(!empty($_POST)){

            try {
                $objInfraException = new InfraException();
                MdUtlAnaliseINT::validaPostAnalise( $_POST , $objInfraException );
                $objInfraException->lancarValidacoes();

                if( $_POST['hdnIdRetriagem'] == 1 ){

                    $objMdUtlAdmAtividadeRN         = new MdUtlAdmAtividadeRN();
                    $dados                          = $objMdUtlAdmAtividadeRN->getAtividadesParaRetriagem( $_POST['idsAtividades'] );
                    $_POST['hdnTbAtividade']        = $dados['itensTable'];
                    $_POST['hdnTmpExecucao']        = $dados['tmpExecucao'];
                    $_POST['hdnIsPossuiAnalise']    = 'S';
                    $objTriagemDTO                  = $objTriagemRN->buscarObjTriagemPorId($idTriagem);
                    $isRetriagemConcluida           = $objTriagemRN->cadastroRetriagem($objTriagemDTO,$objControleDsmpDTO);

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

    case 'md_utl_analise_consultar':

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

    case 'md_utl_analise_alterar':
        
        $isAlterar = true;
        $arrObjsPreenchidos = $objMdUtlRelTriagemAtvRN->listarComAnalise($idMdUtlAnalise);

        //Set Valor Default Objs Preenchidos
        $setValorDefaultObj = function ($value) {
            return $value->setStrSinObjPreenchido('N');
        };
        array_map($setValorDefaultObj, $arrObjsPreenchidos);

        if($objMdUtlAnaliseDTO->getStrStaEncaminhamentoAnalise() == MdUtlControleDsmpRN::$ENC_ASSOCIAR_EM_FILA) {
            $displayFila = '';
        }

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

                    if(
                        $isProdGeralIgual && 
                        $isAtividadeIgual && 
                        $objPreenchidoDTO->getStrSinObjPreenchido() == 'N' && 
                        $arrObjs[$key1]->getStrSinAnalisado() == 'N'
                    ){                        
                        $arrObjs[$key1]->setStrSinAnalisado('S');
                        $arrObjs[$key1]->setStrObservacaoAnalise($objPreenchidoDTO->getStrObservacaoAnalise());
                        $arrObjs[$key1]->setStrProtocoloFormatado($objPreenchidoDTO->getStrProtocoloFormatado());
                        //$arrObjs[$key1]->setStrDocumentoFormatado($objPreenchidoDTO->getStrDocumentoFormatado());
                        $arrObjsPreenchidos[$key2]->setStrSinObjPreenchido('S');
                    }
                }
            }
        }

        $arrComandos[] = '<button type="submit" accesskey="s" id="btnSalvar" value="Salvar" class="infraButton botaoSalvar"><span class="infraTeclaAtalho">S</span>alvar</button>';
        $arrComandos[] = '<button type="button" accesskey="t" id="btnRetriagem" value="Retriagem" onClick="RetriagemAnlCorrecao()" class="infraButton">Re<span class="infraTeclaAtalho">t</span>riagem</button>';
        $arrComandos[] = '<button type="button" accesskey="v" id="btnAbrirModalRevisao" value="Revisao" onClick="abrirModalRevisao()" class="infraButton">A<span class="infraTeclaAtalho">v</span>aliação</button>';
        $arrComandos[] = '<button type="button" accesskey="c" id="" onclick="fechar()" value="Cancelar" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

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
                    $objMdUtlAdmAtividadeRN      = new MdUtlAdmAtividadeRN();
                    $dados = $objMdUtlAdmAtividadeRN->getAtividadesParaRetriagem( $_POST['idsAtividades'] );
                    $_POST['hdnTbAtividade'] = $dados['itensTable'];
                    $_POST['hdnTmpExecucao'] = $dados['tmpExecucao'];
                    $_POST['hdnIsPossuiAnalise'] = 'S';
                    $objTriagemDTO = $objTriagemRN->buscarObjTriagemPorId($idTriagem);
                    $isRetriagemConcluida = $objTriagemRN->cadastroRetriagem($objTriagemDTO,$objControleDsmpDTO);
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
        throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    //endregion
}

$numRegistros = !is_null($idsAtividades) && count($idsAtividades) > 0 ? count($arrObjs) : 0;

//Tabela de resultado.
if ($numRegistros > 0) {

    $htmlCheck    = '<a href="javascript:void(0);" id="lnkInfraCheck" onclick="selecionarTodosAnalise();" tabindex="1001"><img src="'. PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/check.svg" id="imgInfraCheck" title="Remover Seleção" alt="Remover Seleção" class="infraImg"></a>';
    $htmlCheck    = !$isConsultar ? $htmlCheck : '';

    $strResultado .= '<table id="tbAnalise"class="infraTable" summary="Análise">';
  
    $strResultado .= '<caption class="infraCaption">';
    $strResultado .= PaginaSEI::getInstance()->gerarCaptionTabela('Análise', $numRegistros);
    $strResultado .= '</caption>';
    //Cabeçalho da Tabela
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" id="tdSelecao">'. $htmlCheck . '</th>';
    $strResultado .= '<th class="infraTh" id="tdAtvidade">Atividade</th>';
    $strResultado .= '<th class="infraTh" id="tdProduto">Produtos Esperados</th>';
    $strResultado .= '<th class="infraTh" id="tdNumSei">Número SEI</th>';
    $strResultado .= '<th class="infraTh" id="tdObs">Observações</th>';
    $strResultado .= '</tr>';

    $strCssTr       = '<tr class="infraTrEscura">';
    $linhaClara     = true;
    $cont           = 0;
    $bloco          = 1;
    $idRelTriagem2  = $arrObjs[0]->getNumIdMdUtlRelTriagemAtv();
    for ($i = 0; $i < $numRegistros; $i++) {

        //vars
        $observacao     = "";
        $numSei         = "";
        $idRelTriagem   = "";
        $strValor       = 'N';

        if( $idRelTriagem2 != $arrObjs[$i]->getNumIdMdUtlRelTriagemAtv() ){
            $bloco++;
        }

        $idRelTriagem2  = $arrObjs[$i]->getNumIdMdUtlRelTriagemAtv();
        $idRelTriagem   = $idRelTriagem2; //$arrObjs[$i]->getNumIdMdUtlRelTriagemAtv();
        $strId          = $i;
        $strIdSerieProd = $isCadastrar ? $arrObjs[$i]->getNumIdMdUtlAdmAtvSerieProd() : null;
        $TmpExecucao    = $isCadastrar || $isAlterar ? $arrObjs[$i]->getNumTempoExecucaoProduto() : 0;
        $idSerieAtual   = $isCadastrar || $isAlterar ? $arrObjs[$i]->getNumIdSerieRel() : $arrObjs[$i]->getNumIdSerie();
        $isDocumentoSEI = !is_null($idSerieAtual);
        $vlProduto      = $isDocumentoSEI ? $arrObjs[$i]->getStrNomeSerie() : $arrObjs[$i]->getStrNomeProduto();
        $idProduto      = !is_null($arrObjs[$i]->getNumIdMdUtlAdmTpProduto()) ? $arrObjs[$i]->getNumIdMdUtlAdmTpProduto() : null;
        $isObrigatorio  = $isCadastrar || $isAlterar ? $arrObjs[$i]->getStrSinObrigatorio() == 'S' : false;

        $idUnico = $isCadastrar || $isAlterar ?  $arrObjs[$i]->getNumIdMdUtlRelTriagemAtv(). '_'.$arrObjs[$i]->getNumIdMdUtlAdmAtvSerieProd() :  $arrObjs[$i]->getNumIdMdUtlRelTriagemAtv(). '_'.$arrObjs[$i]->getNumIdMdUtlRelAnaliseProduto();

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
        $strResultado .= '<td style="width: 20%;">';
        $vlrUnidEsf = !is_null($arrObjs[$i]->getNumTempoExecucaoAtribuido()) ? $arrObjs[$i]->getNumTempoExecucaoAtribuido() : 0;

        $vlrUnidEsf =  MdUtlAdmPrmGrINT::convertToHoursMins( $vlrUnidEsf );
        $strResultado .= PaginaSEI::tratarHTML($arrObjs[$i]->getStrNomeAtividade().' (' . MdUtlAdmAtividadeRN::$ARR_COMPLEXIDADE[$arrObjs[$i]->getNumComplexidadeAtividade()]) . ') - ' . $vlrUnidEsf;
        $strResultado .= '</td>';

        //Linha Produtos Esperados
        $strResultado .= ($isObrigatorio) ? '<td style="font-weight: bold;">' : '<td>';
        $strResultado .= PaginaSEI::tratarHTML($vlProduto);
        $strResultado .= '</td>';

        //Linha Número SEI
        $strResultado .= '<td align="center">';
        $strResultado .= $isDocumentoSEI ? '<input '.$disabled.' maxlength="11" utlSomenteNumeroPaste="true" id="numeroSEI_'.$i.'" name="numeroSEI_'.$strId.'" onkeypress="return infraMascaraNumero(this, event,11)"; onchange="validarDocumentoSEI('.$idSerieAtual.','.$i.')" class="infraText form-control" type="text" value="'.$numSei.'"/>' : '';
        $strResultado .= '</td>';

        //Linha Observação
        $strResultado .= '<td style="padding: 2px 10px 2px 5px;">';
        //$strResultado .= '<input disabled="disabled" '.$disabled.' style="width: 98%;" id="observacao_'.$i.'" name="observacao_'.$strId.'" type="text" value="'.$observacao.'" onkeypress="return infraMascaraTexto(this,event,250);"/>';
        $strResultado .= '<textarea '.$disabled.' id="observacao_'.$i.'" name="observacao_'.$strId.'" class="form-control" rows="2" cols="40" class="infraTextArea" maxlength="500" onkeypress="return infraMascaraTexto(this,event, 500);">'.$observacao.'</textarea>';
        $strResultado .= '</td>';

        //Linha idSerieProd
        $strResultado .= '<td style="display: none">';
        $strResultado .= '<input style="width: 97%;" name="idRelTriagem_'.$strId.'" type="text" value="'.$idRelTriagem.'" disabled="disabled"/>';
        $strResultado .= '</td>';

        $strResultado .= '<td style="display: none">';
        $strResultado .= '<input style="width: 97%;" name="idSerieProd_'.$strId.'" type="text" value="'.$strIdSerieProd.'" disabled="disabled"/>';
        $strResultado .= '</td>';

        //Linha TmpExecucao
        $strResultado .= '<td style="display: none">';
        $strResultado .= '<input style="width: 97%;" id="TmpExecucao" name="TmpExecucao_'.$strId.'" type="text" value="'.$TmpExecucao.'" disabled="disabled"/>';
        $strResultado .= '</td>';

        //Linha Produto
        $strResultado .= '<td style="display: none">';
        $strResultado .= '<input style="width: 97%;" id="idProduto" name="idProduto_'.$strId.'" type="text" value="'.$idProduto.'" disabled="disabled"/>';
        $strResultado .= '</td>';

        //Linha Atividade
        $strResultado .= '<td style="display: none"><span>'.$bloco.'</span>';
        $strResultado .= '<input style="width: 97%;" id="idAtividade" name="idAtividade_'.$strId.'" type="text" value="'.$arrObjs[$i]->getNumIdMdUtlAdmAtividade().'" disabled="disabled"/>';
        $strResultado .= '</td>';

        //Linha Produto Nome
        $nomeProduto   = $isDocumentoSEI ? $arrObjs[$i]->getStrNomeSerie() : $arrObjs[$i]->getStrNomeProduto();
        $strResultado .= '<td style="display: none">';
        $strResultado .= '<input style="width: 97%;" id="nomeProduto" name="nomeProduto_'.$strId.'" type="text" value="'.$nomeProduto.'" disabled="disabled"/>';
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
$txtTooltipEncaminhamentoAnalise = 'A depender das parametrizações em seu perfil ou sobre as Atividades entregues, o que for selecionado neste campo será meramente sugestivo ou será executado imediatamente.\n \n Selecione a opção "Associar em Fila após Finalizar Fluxo" caso queira reiniciar o fluxo em alguma Fila imediatamente com a finalização do fluxo atual.\n \n Ou selecione a opção "Finalizar Fluxo" para concluir sem associar a qualquer Fila imediatamente na finalização do fluxo atual.';

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
        
        <?php if( $isPgPadrao != 0 ): ?>
        <div class="row">
            <div class="col-12">
                <label id="lblStatus" for="txtStatus" class="infraLabelObrigatorio">
                    Número do Processo:
                </label>
                <label><?= $numProcessoFormatado ?></label>
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
                <label class="infraLabelOpcional">Membro Responsável pela Análise:</label>
                <input type="text" value="<?= $nm_usuario_analise ?>" readonly class="infraText form-control">
            </div>
        </div>

        <?php if($isTpProcParametrizado){ ?>
            <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-xs-10">
                    <div class="form-group mb-3" id="divPrincipalEncaminhamento">
                        <div id="divEncaminhamentoAnl">
                            <label id="lblEncaminhamentoAnl" for="selEncaminhamentoAnl"  class="infraLabelObrigatorio">Encaminhamento da Análise:</label>
                            <img class="infraImg" name="ajuda" src="/infra_css/svg/ajuda.svg" onmouseover="return infraTooltipMostrar('A depender das parametrizações em seu perfil ou sobre as Atividades entregues, o que for selecionado neste campo será meramente sugestivo ou será executado imediatamente.\n \n Selecione a opção \&quot;Associar em Fila após Finalizar Fluxo\&quot; caso queira reiniciar o fluxo em alguma Fila imediatamente com a finalização do fluxo atual.\n \n Ou selecione a opção \&quot;Finalizar Fluxo\&quot; para concluir sem associar a qualquer Fila imediatamente na finalização do fluxo atual.','Ajuda');" onmouseout="return infraTooltipOcultar();">
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
                <div id="divDistAutoParaMim" class="col-12" <?= $bolPertenceAFila ? '' : 'style="display:none;"'?> >
                    <div class="infraCheckboxDiv">
                        <input type="checkbox" name="ckbDistAutoParaMim" id="ckbDistAutoParaMim" 
                                <?= $chkDistAutoParaMim == 'S' ? 'checked' : '' ?> value="S">
                        <label class="infraCheckboxLabel" for="ckbDistAutoParaMim"></label>
                    </div>
                    <label class="infraLabelChec infraLabelOpcional" for="ckbDistAutoParaMim">
                        Distribuir automaticamente a Triagem do próximo fluxo para você mesmo?
                    </label>
                </div>
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
                        Informações Complementares da Análise:
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

    <?php require_once "md_utl_analise_cadastro_js.php"; ?>
    <?php require_once "md_utl_geral_js.php"; ?>
    <?php require_once "md_utl_funcoes_js.php"; ?>

<?php

PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
