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
$idControleDsmp     = null;
$isConsultar       = false;
$idsAtividades     = array();
$isConsultar       = false;
$isAlterar         = false;
$isCadastrar       = false;
$disabledConsultar ="";
$strTitulo         = 'Análise ';
$displayFila       = "display:none";
$isRetriagem       = array_key_exists('id_retriagem', $_GET) ? $_GET['id_retriagem'] : $_POST['hdnIdRetriagem'];
$isProcessoConcluido = 0;


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


//Urls
$acaoOrigem = $isMeusProcessos ? 'md_utl_meus_processos_listar' : 'md_utl_processo_listar';
$strUrlValidarDocumentoSEI = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_utl_adm_validar_documento_sei');

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

            $arrComandos[] = '<button type="submit" accesskey="s" id="btnSalvar" value="salvar" class="infraButton botaoSalvar">
                                    <span class="infraTeclaAtalho">S</span>alvar</button>';

            $arrComandos[] = '<button type="button" accesskey="t" id="btnRetriagem" value="Retriagem" onclick="Retriagem();" class="infraButton">
                                    Re<span class="infraTeclaAtalho">t</span>riagem
                            </button>';

            $arrComandos[] = '<button type="button" accesskey="c" id="btnFecharSelecao" value="Cancelar" onclick="fechar();" class="infraButton">
                                    <span class="infraTeclaAtalho">C</span>ancelar
                            </button>';


        $disabled="";
        if(!empty($_POST)){
          
            try {
                if( $_POST['hdnIdRetriagem'] == 1 ){
                  $objMdUtlAdmAtividadeRN = new MdUtlAdmAtividadeRN();
                  $dados = $objMdUtlAdmAtividadeRN->getAtividadesParaRetriagem( $_POST['idsAtividades'] );
                  $_POST['hdnTbAtividade'] = $dados['itensTable'];
                  $_POST['hdnTmpExecucao'] = $dados['tmpExecucao'];
                  $_POST['hdnIsPossuiAnalise'] = 'S';
                  $objTriagemDTO = $objTriagemRN->buscarObjTriagemPorId($idTriagem);
                  $isRetriagemConcluida = $objTriagemRN->cadastroRetriagem($objTriagemDTO,$objControleDsmpDTO);
                }

                $objRn = new MdUtlAnaliseRN();
                $isProcessoConcluido = $objRn->cadastrarDadosAnalise(array($_POST, $isTpProcParametrizado, false));

                if ($isPgPadrao == 0) {
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_processo_listar&id_procedimento=' . $idProcedimento.'&is_processo_concluido='.$isProcessoConcluido));
                } else {
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_meus_processos_dsmp_listar&id_procedimento=' . $idProcedimento.'&is_processo_concluido='.$isProcessoConcluido));
                }
                die;

            }catch(Exception $e){
                throw new InfraException('Erro cadastrando .',$e);
            }

        }

        break;

    case 'md_utl_analise_consultar':
        $isConsultar = true;
        $arrObjs = $objMdUtlRelTriagemAtvRN->listarComAnalise($idMdUtlAnalise);

        $arrComandos[] = '<button type="button" accesskey="c" id="btnFechar" value="Fechar" onclick="fechar();" class="infraButton">
                                    Fe<span class="infraTeclaAtalho">c</span>har
                            </button>';

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
                $idTpProdutoAtividade = $objDTO->getNumIdMdUtlAdmTpProduto();
                $idSerieAtiv      = $objDTO->getNumIdSerieRel();
                $idAtividadeProduto   = $objDTO->getNumIdMdUtlAdmAtividade();

                foreach($arrObjsPreenchidos as $key2 => $objPreenchidoDTO){

                    $idTpProdutoPreenchido = $objPreenchidoDTO->getNumIdMdUtlAdmTpProduto();
                    $idAtividadePreenchido = $objPreenchidoDTO->getNumIdMdUtlAdmAtividade();
                    $idSerieAtual          = $objPreenchidoDTO->getNumIdSerie();

                    $isAtividadeIgual      = $idAtividadeProduto == $idAtividadePreenchido;
                    $isProdutoIgual        = !is_null($idTpProdutoPreenchido) && $idTpProdutoAtividade == $idTpProdutoPreenchido;
                    $isSerieIgual          = !is_null($idSerieAtual) && $idSerieAtiv == $idSerieAtual;
                    $isProdGeralIgual      = $isSerieIgual || $isProdutoIgual;

                    if($isProdGeralIgual && $isAtividadeIgual && $objPreenchidoDTO->getStrSinObjPreenchido() == 'N' && $arrObjs[$key1]->getStrSinAnalisado() == 'N'){

                        $arrObjs[$key1]->setStrSinAnalisado('S');
                        $arrObjs[$key1]->setStrObservacaoAnalise($objPreenchidoDTO->getStrObservacaoAnalise());
                        $arrObjs[$key1]->setStrDocumentoFormatado($objPreenchidoDTO->getStrDocumentoFormatado());
                        $arrObjsPreenchidos[$key2]->setStrSinObjPreenchido('S');
                    }
                }
            }
        }



        $arrComandos[] = '<button type="submit" accesskey="s" id="btnSalvar" value="Salvar" class="infraButton botaoSalvar">
                                    <span class="infraTeclaAtalho">S</span>alvar</button>';

        $arrComandos[] = '<button type="button" accesskey="t" id="btnRetriagem" value="Retriagem" onclick="RetriagemAnlCorrecao();" class="infraButton">
                                     Re<span class="infraTeclaAtalho">t</span>riagem
                            </button>';

        $arrComandos[] = '<button type="button" accesskey="v" id="btnFecharSelecao" value="Revisao" onclick="abrirModalRevisao();" class="infraButton">
                                    A<span class="infraTeclaAtalho">v</span>aliação
                            </button>';

        $arrComandos[] = '<button type="button" accesskey="c" id="btnFecharSelecao" value="Cancelar" onclick="fechar();" class="infraButton">
                                    <span class="infraTeclaAtalho">C</span>ancelar
                            </button>';

        $objRelTriagemAnaliseRN = new MdUtlRelTriagemAtvRN();
        $arrObjsDadosAnalise = $objRelTriagemAnaliseRN->listarComAnalise($idMdUtlAnalise);


        $strLinkIniciarRevisao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_revisao_analise_consultar&acao_origem=' . $_GET['acao'] . '&id_procedimento=' . $idProcedimento);

        if(!empty($_POST)){


            try {

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
                throw new InfraException('Erro cadastrando .',$e);
            }

        }

        break;

    //region Erro
    default:
        throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    //endregion
}

$numRegistros = count($idsAtividades) > 0 ? count($arrObjs) : 0;

$disabled ="";

//Tabela de resultado.
if ($numRegistros > 0) {

    $htmlCheck    = '<a href="javascript:void(0);" id="lnkInfraCheck" onclick="selecionarTodosAnalise();" tabindex="1001"><img src="/infra_css/imagens/check.gif" id="imgInfraCheck" title="Remover Seleção" alt="Remover Seleção" class="infraImg"></a>';
    $htmlCheck    = !$isConsultar  ? $htmlCheck : '';

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

    $strCssTr = '<tr class="infraTrEscura">';
    $linhaClara = true;
    $cont = 0;
    $bloco = 1;
    $idRelTriagem2 = $arrObjs[0]->getNumIdMdUtlRelTriagemAtv();
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
        $idRelTriagem   = $arrObjs[$i]->getNumIdMdUtlRelTriagemAtv();
        $strId          = $i;
        $strIdSerieProd = $isCadastrar ? $arrObjs[$i]->getNumIdMdUtlAdmAtvSerieProd() : null;
        $TmpExecucao     = $isCadastrar || $isAlterar ? $arrObjs[$i]->getNumTempoExecucaoProduto() : 0;
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
                $numSei        = $arrObjs[$i]->getStrDocumentoFormatado();
                $strValor = 'S';
            }
        }

        $strResultado .= $strCssTr;

        //Linha Checkbox
        $classObrigatorio = $isObrigatorio ? 'class="classTdObrigatorio"' : '';
        $strResultado .= '<td align="center" valign="middle" ' . $classObrigatorio.' >';
        $attrAdapt = $strValor == 'S' ? ' checkado = "S"' : '';
        $attrs = $disabled . $attrAdapt;

        $strResultado .= PaginaSEI::getInstance()->getTrCheck($i, $strId,  $arrObjs[$i]->getStrNomeAtividade().' (' . MdUtlAdmAtividadeRN::$ARR_COMPLEXIDADE[$arrObjs[$i]->getNumComplexidadeAtividade()] . ')',null,null, $attrs)  ;
        $strResultado .= '</td>';

        //Linha Nome Atividade
        $strResultado .= '<td style="width: 20%;">';
        $vlrUnidEsf = !is_null($arrObjs[$i]->getNumTempoExecucao()) ? $arrObjs[$i]->getNumTempoExecucao() : 0;

        if(empty($idMdUtlAnalise)){            
            $vlrUnidEsf = MdUtlAdmPrmGrUsuINT::retornaCalculoPercentualDesempenho( $vlrUnidEsf , $idTipoControle , $idUsuarioDistribuicao );
        }else{
            if( $objControleDsmpDTO->getStrStaAtendimentoDsmp() == MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE ){
                $vlrUnidEsf = MdUtlAdmPrmGrUsuINT::retornaCalculoPercentualDesempenho( $vlrUnidEsf , $idTipoControle , $idUsuarioDistribuicao );
            }else{
                $vlrUnidEsf = MdUtlAdmPrmGrUsuINT::retornaCalculoPercentualDesempenho( $vlrUnidEsf , $idTipoControle , $objMdUtlAnaliseDTO->getNumIdUsuario());
            }                        
        }
        $vlrUnidEsf =  MdUtlAdmPrmGrINT::convertToHoursMins( $vlrUnidEsf );
        $strResultado .= PaginaSEI::tratarHTML($arrObjs[$i]->getStrNomeAtividade().' (' . MdUtlAdmAtividadeRN::$ARR_COMPLEXIDADE[$arrObjs[$i]->getNumComplexidadeAtividade()]) . ') - ' . $vlrUnidEsf;
        $strResultado .= '</td>';

        //Linha Produtos Esperados
        if($isObrigatorio){
            $strResultado .= '<td style="font-weight: bold;">';
        }else {
            $strResultado .= '<td>';
        }

        $strResultado .= PaginaSEI::tratarHTML($vlProduto);
        $strResultado .= '</td>';

        //Linha Número SEI
        $strResultado .= '<td align="center">';
        $strResultado .= $isDocumentoSEI ? '<input '.$disabled.' maxlength="11" utlSomenteNumeroPaste="true" id="numeroSEI_'.$i.'" name="numeroSEI_'.$strId.'" onkeypress="return infraMascaraNumero(this, event,11)"; disabled="disabled" onchange="validarDocumentoSEI('.$idSerieAtual.','.$i.')" style="width: 90%" type="text" value="'.$numSei.'"/>' : '';
        $strResultado .= '</td>';

        //Linha Observação
        $strResultado .= '<td style="padding: 2px 10px 2px 5px;">';
        //$strResultado .= '<input disabled="disabled" '.$disabled.' style="width: 98%;" id="observacao_'.$i.'" name="observacao_'.$strId.'" type="text" value="'.$observacao.'" onkeypress="return infraMascaraTexto(this,event,250);"/>';
        $strResultado .= '<textarea disabled="disabled" '.$disabled.' id="observacao_'.$i.'" name="observacao_'.$strId.'" style="resize: none; width: 100%;" rows="2" cols="40" class="infraTextArea" maxlength="500" onkeypress="return infraMascaraTexto(this,event, 500);">'.$observacao.'</textarea>';
        $strResultado .= '</td>';

        //Linha idSerieProd
        $strResultado .= '<td style="display: none">';
        $strResultado .= '<input style="width: 97%;" name="idRelTriagem_'.$strId.'" type="text" value="'.$idRelTriagem.'"/>';
        $strResultado .= '</td>';

        $strResultado .= '<td style="display: none">';
        $strResultado .= '<input style="width: 97%;" name="idSerieProd_'.$strId.'" type="text" value="'.$strIdSerieProd.'"/>';
        $strResultado .= '</td>';

        //Linha TmpExecucao
        $strResultado .= '<td style="display: none">';
        $strResultado .= '<input style="width: 97%;" id="TmpExecucao" name="TmpExecucao_'.$strId.'" type="text" value="'.$TmpExecucao.'"/>';
        $strResultado .= '</td>';

        //Linha Produto
        $strResultado .= '<td style="display: none">';
        $strResultado .= '<input style="width: 97%;" id="idProduto" name="idProduto_'.$strId.'" type="text" value="'.$idProduto.'"/>';
        $strResultado .= '</td>';

        //Linha Atividade
        $strResultado .= '<td style="display: none"><span>'.$bloco.'</span>';
        $strResultado .= '<input style="width: 97%;" id="idAtividade" name="idAtividade_'.$strId.'" type="text" value="'.$arrObjs[$i]->getNumIdMdUtlAdmAtividade().'" />';

        $strResultado .= '</td>';

        //Linha Produto Nome
        $nomeProduto   = $isDocumentoSEI ? $arrObjs[$i]->getStrNomeSerie() : $arrObjs[$i]->getStrNomeProduto();
        $strResultado .= '<td style="display: none">';
        $strResultado .= '<input style="width: 97%;" id="nomeProduto" name="nomeProduto_'.$strId.'" type="text" value="'.$nomeProduto.'"/>';
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
if(0){?><style><?} ?>
.clear {
    clear: both;
}

select[multiple] {
    width: 79%;
    margin-top: 0.5%;
}

#divPrincipalEncaminhamento{
    width: 100%;
}

#divFila{
    display: inline-block;
}

#divEncaminhamentoAnl{
    margin-top: 1.8%;
    display: inline-block;
    margin-right: 45px;
    margin-bottom: 1.8%;
}

#selFila{
    width: 201px;
}

#selEncaminhamentoAnl{
    width: 260px;
}

textarea{
    resize: none;
}

#imgAjudaEncAnalise {
    position: absolute;
}

.tamanhoBtnAjuda{
    width: 16px;
    height: 16px;
}

<?php if($isMeusProcessos){?>
    #tbAnalise{
        width: 86%;
    }
    #txaInformacaoComplementar{
        width: 55%; 
    }
<?php } else {?> 
    #tbAnalise{
        width: 99%;
    }
    #txaInformacaoComplementar{
        width: 63%; 
    }
<?php }?>

#tdSelecao{
    width: 5%;
}

#tdAtvidade{
    width: 15%;
}

#tdProduto{
    width: 20%;
}

#tdNumSei{
    width: 9%;
}

#tdObs{
    width: 30%;
}

<?if(0){?></style><?}?>
<?php PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
require_once ('md_utl_geral_js.php');
require_once ('md_utl_funcoes_js.php');
require_once ('md_utl_analise_cadastro_js.php');
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

        <?php PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos); ?>
        <?php PaginaSEI::getInstance()->abrirAreaDados('auto');
    ?>

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
        
        <?php if( $isPgPadrao != 0 ) { ?>
            <label style='margin-bottom: .2em; font-weight: bold; line-height: 1.5em; color: black;'>
                Número do Processo:
            </label>
            <label><?= $numProcessoFormatado ?> </label>
            <div class="clear"></div>
            <br><br>
        <?php } ?>

        <label id="lblTipoControle" for="selTipoControle" accesskey="" class="infraLabelOpcional">Tipo de Controle:</label><br/>
        <div class="clear"></div>
        <input type="text" style="width:300px" id="txtTipoControle" name="txtTipoControle" class="infraText" value="<?= $objControleDsmpDTO->getStrNomeTpControle() ?>" disabled/><br/><br/>

        <label id="lblFila" for="selFila" accesskey="" class="infraLabelOpcional">Fila:</label><br/>
        <div class="clear"></div>
        <input type="text" style="width:300px" id="txtNomeFila" name="txtNomeFila" class="infraText" value="<?= $objControleDsmpDTO->getStrNomeFila() ?>" disabled/><br/><br/>
        <?php if($isTpProcParametrizado){ ?>
            <div id="divPrincipalEncaminhamento">
                <div id="divEncaminhamentoAnl">
                    <label id="lblEncaminhamentoAnl" for="selEncaminhamentoAnl"  class="infraLabelObrigatorio">Encaminhamento da Análise:</label>
                    <a style="" id="btAjudaEncAnalise" <?=PaginaSEI::montarTitleTooltip($txtTooltipEncaminhamentoAnalise)?>
                       tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
                        <img class="tamanhoBtnAjuda" id="imgAjudaEncAnalise" border="0" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/ajuda.gif" style="width: 16px; height: 16px; margin-bottom: -3px; margin-left: 4px" class="infraImg"/>
                    </a>
                    <select id="selEncaminhamentoAnl" name="selEncaminhamentoAnl" style="width:307px" class="infraSelect padraoSelect"
                            onchange="controlarExibicaoAnalise(this);"
                            tabindex="<?= PaginaSEI::getInstance()->getProxTabDados()?>">
                        <?php echo $selEncaminhamentoAnalise?>
                    </select>
                </div>
                <div id="divFila" style="<?php //echo $displayFila ?>">
                    <label id="lblFila" for="selFila" class="infraLabelObrigatorio">Fila:</label>
                    <select id="selFila" name="selFila" class="infraSelect" style="width:307px" onchange="carregarHiddenFila(this)">
                        <?= $selFila ?>
                    </select>
                </div>
            </div>
        <? } ?>
        <br/>
<?php


        PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros);
        ?>
        <div id="divInformacaoComplementar" style="margin-top: 1.8%">
            <label id="lblInformacaoComplementar" style="display: block" for="txaInformacaoComplementar" class="infraLabelOpcional"
                   tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                Informações Complementares da Análise:
            </label>

            <textarea <?=$disabledConsultar?> id="txaInformacaoComplementar" name="txaInformacaoComplementar" style="resize: none; width: 98.5%" rows="4" class="infraTextArea" onkeypress="return infraMascaraTexto(this,event, 500);" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?php echo $strInformComp ?></textarea>
        </div>



        <?php
        PaginaSEI::getInstance()->fecharAreaDados();
        PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
        ?>


    </form>

<?php
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();

