<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 09/10/2018 - criado por jhon.carvalho
 *
 * Versão do Gerador de Código: 1.41.0
 */

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlControleDsmpINT extends InfraINT
{


    public static function retornaArrSituacoesControleDsmp()
    {
        $arrRetorno = array();
        $arrRetorno[MdUtlControleDsmpRN::$AGUARDANDO_FILA] = MdUtlControleDsmpRN::$STR_AGUARDANDO_FILA;
        $arrRetorno[MdUtlControleDsmpRN::$AGUARDANDO_TRIAGEM] = MdUtlControleDsmpRN::$STR_AGUARDANDO_TRIAGEM;
        $arrRetorno[MdUtlControleDsmpRN::$EM_TRIAGEM] = MdUtlControleDsmpRN::$STR_EM_TRIAGEM;
        $arrRetorno[MdUtlControleDsmpRN::$AGUARDANDO_ANALISE] = MdUtlControleDsmpRN::$STR_AGUARDANDO_ANALISE;
        $arrRetorno[MdUtlControleDsmpRN::$EM_ANALISE] = MdUtlControleDsmpRN::$STR_EM_ANALISE;
        $arrRetorno[MdUtlControleDsmpRN::$AGUARDANDO_REVISAO] = MdUtlControleDsmpRN::$STR_AGUARDANDO_REVISAO;
        $arrRetorno[MdUtlControleDsmpRN::$EM_REVISAO] = MdUtlControleDsmpRN::$STR_EM_REVISAO;
        $arrRetorno[MdUtlControleDsmpRN::$AGUARDANDO_CORRECAO_TRIAGEM] = MdUtlControleDsmpRN::$STR_AGUARDANDO_CORRECAO_TRIAGEM;
        $arrRetorno[MdUtlControleDsmpRN::$EM_CORRECAO_TRIAGEM] = MdUtlControleDsmpRN::$STR_EM_CORRECAO_TRIAGEM;
        $arrRetorno[MdUtlControleDsmpRN::$AGUARDANDO_CORRECAO_ANALISE] = MdUtlControleDsmpRN::$STR_AGUARDANDO_CORRECAO_ANALISE;
        $arrRetorno[MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE] = MdUtlControleDsmpRN::$STR_EM_CORRECAO_ANALISE;
        $arrRetorno[MdUtlControleDsmpRN::$SUSPENSO] = MdUtlControleDsmpRN::$STR_SUSPENSO;
        $arrRetorno[MdUtlControleDsmpRN::$INTERROMPIDO] = MdUtlControleDsmpRN::$STR_INTERROMPIDO;

        return $arrRetorno;
    }

    public static function retornaArrSituacoesControleDsmpCompleto()
    {
        $arrRetorno = array();
        $arrRetorno[MdUtlControleDsmpRN::$AGUARDANDO_FILA] = MdUtlControleDsmpRN::$STR_AGUARDANDO_FILA;
        $arrRetorno[MdUtlControleDsmpRN::$AGUARDANDO_TRIAGEM] = MdUtlControleDsmpRN::$STR_AGUARDANDO_TRIAGEM;
        $arrRetorno[MdUtlControleDsmpRN::$EM_TRIAGEM] = MdUtlControleDsmpRN::$STR_EM_TRIAGEM;
        $arrRetorno[MdUtlControleDsmpRN::$AGUARDANDO_ANALISE] = MdUtlControleDsmpRN::$STR_AGUARDANDO_ANALISE;
        $arrRetorno[MdUtlControleDsmpRN::$EM_ANALISE] = MdUtlControleDsmpRN::$STR_EM_ANALISE;
        $arrRetorno[MdUtlControleDsmpRN::$AGUARDANDO_REVISAO] = MdUtlControleDsmpRN::$STR_AGUARDANDO_REVISAO;
        $arrRetorno[MdUtlControleDsmpRN::$EM_REVISAO] = MdUtlControleDsmpRN::$STR_EM_REVISAO;
        $arrRetorno[MdUtlControleDsmpRN::$AGUARDANDO_CORRECAO_TRIAGEM] = MdUtlControleDsmpRN::$STR_AGUARDANDO_CORRECAO_TRIAGEM;
        $arrRetorno[MdUtlControleDsmpRN::$EM_CORRECAO_TRIAGEM] = MdUtlControleDsmpRN::$STR_EM_CORRECAO_TRIAGEM;
        $arrRetorno[MdUtlControleDsmpRN::$AGUARDANDO_CORRECAO_ANALISE] = MdUtlControleDsmpRN::$STR_AGUARDANDO_CORRECAO_ANALISE;
        $arrRetorno[MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE] = MdUtlControleDsmpRN::$STR_EM_CORRECAO_ANALISE;
        $arrRetorno[MdUtlControleDsmpRN::$FLUXO_FINALIZADO] = MdUtlControleDsmpRN::$STR_FLUXO_FINALIZADO;
        $arrRetorno[MdUtlControleDsmpRN::$REMOCAO_FILA] = MdUtlControleDsmpRN::$STR_REMOCAO_FILA;
        $arrRetorno[MdUtlControleDsmpRN::$SUSPENSO] = MdUtlControleDsmpRN::$STR_SUSPENSO;
        $arrRetorno[MdUtlControleDsmpRN::$INTERROMPIDO] = MdUtlControleDsmpRN::$STR_INTERROMPIDO;

        return $arrRetorno;
    }

    public static function montarSelectStatus($valorSelecionado = null, $isAguardandoFila = true, $idsStatusPermitido = false)
    {
        $arrDados = self::retornaArrSituacoesControleDsmp();

        $html = '<option value=""></option>';

        foreach ($arrDados as $key => $status) {
            $isAguardandoFilaValido = ($key != MdUtlControleDsmpRN::$AGUARDANDO_FILA || $key == MdUtlControleDsmpRN::$AGUARDANDO_FILA && $isAguardandoFila);
            $isStatusValido = $idsStatusPermitido && in_array($key, $idsStatusPermitido) || !$idsStatusPermitido;
            if ($isAguardandoFilaValido && $isStatusValido) {
                $selected = '';

                if ($valorSelecionado != '' && $valorSelecionado != null && $valorSelecionado == $key) {
                    $selected = 'selected=selected';
                }

                $html .= '<option ' . $selected . ' value="' . $key . '">' . $status . '</option>';

            }
        }

        return $html;
    }

    public static function montarSelectStatusMeusProcessos($valorSelecionado = null, $isAguardandoFila = true, $idsStatusPermitido = false)
    {
        $arrDados = self::retornaArrSituacoesControleDsmpCompleto();

        $html = '<option value=""></option>';

        foreach ($arrDados as $key => $status) {
            $isAguardandoFilaValido = ($key != MdUtlControleDsmpRN::$AGUARDANDO_FILA || $key == MdUtlControleDsmpRN::$AGUARDANDO_FILA && $isAguardandoFila);
            $isStatusValido = $idsStatusPermitido && in_array($key, $idsStatusPermitido) || !$idsStatusPermitido;
            if ($isAguardandoFilaValido && $isStatusValido) {
                $selected = '';

                if ($valorSelecionado != '' && $valorSelecionado != null && $valorSelecionado == $key) {
                    $selected = 'selected=selected';
                }

                $html .= '<option ' . $selected . ' value="' . $key . '">' . $status . '</option>';

            }
        }

        return $html;
    }

    public static function montarSelectTipoProcesso($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado)
    {
        $objRN = new MdUtlControleDsmpRN();
        $arrObjsDTO = $objRN->getTiposProcessoTipoControle();

        return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjsDTO, 'IdTipoProcedimento', 'NomeProcedimento');
    }

    public static function retornaXmlUltimasFilas($jsonIdsProcedimento, $idProcedimento, $isTelaDetalhe)
    {
        $isTelaDetalhe = $isTelaDetalhe == '1' ? true : false;
        $idsProcedimento = $isTelaDetalhe ? array($idProcedimento) : json_decode($jsonIdsProcedimento);

        $objHsRN = new MdUtlHistControleDsmpRN();

        $arrObjsUltimasFilas = $objHsRN->getUltimasFilasPorProcedimento($idsProcedimento);
        
        $xml = '<Dados>';
        if (!is_null($arrObjsUltimasFilas)) {
            foreach ($arrObjsUltimasFilas as $objUltimaFila) {
                $xml .= '<UltimaFila' . $objUltimaFila->getDblIdProcedimento() . '>';
                $xml .= htmlspecialchars($objUltimaFila->getStrNomeFila());
                $xml .= '</UltimaFila' . $objUltimaFila->getDblIdProcedimento() . '>';

                $xml .= '<ProtocoloFormatado' . $objUltimaFila->getDblIdProcedimento() . '>';
                $xml .= htmlspecialchars($objUltimaFila->getStrNomeFila());
                $xml .= '</ProtocoloFormatado' . $objUltimaFila->getDblIdProcedimento() . '>';

                $xml .= '<TipoControle' . $objUltimaFila->getDblIdProcedimento() . '>';
                $xml .= htmlspecialchars($objUltimaFila->getStrNomeTpControle());
                $xml .= '</TipoControle' . $objUltimaFila->getDblIdProcedimento() . '>';
            }
        }


        $xml .= '</Dados>';

        return $xml;
    }

    public static function retornaArrVisualizacaoBotao($idStatus, $isPossuiAnalise, $isTipoProcessoParametrizado, $idFila)
    {

        $objRelTpCtrlUsuRN = new MdUtlAdmRelTpCtrlDesempUsuRN();
        $objMdUtlFilaRN = new MdUtlAdmFilaRN();
        $isUsuarioPertenceFila = true;
        $arrVisualizacao = array();
        $isGestor = $objRelTpCtrlUsuRN->usuarioLogadoIsGestorSipSei();
        $isUsuarioPertenceFila      = $objMdUtlFilaRN->verificaUsuarioLogadoPertenceFila(array($idFila, $idStatus));
        $isUsuarioPertenceFilaPapel = $objMdUtlFilaRN->verificaUsuarioLogadoPertenceFila(array($idFila, $idStatus, true));

        switch ($idStatus) {
            case MdUtlControleDsmpRN::$AGUARDANDO_FILA:
                $arrVisualizacao['ASSOCIACAO'] = $isTipoProcessoParametrizado;
                $arrVisualizacao['TRIAGEM'] = false;
                $arrVisualizacao['ANALISE'] = false;
                $arrVisualizacao['REVISAO'] = false;
                $arrVisualizacao['DISTRIBUICAO'] = false;
                $arrVisualizacao['ATRIBUICAO'] = false;
                break;

            case MdUtlControleDsmpRN::$AGUARDANDO_TRIAGEM:
                $arrVisualizacao['ASSOCIACAO'] = $isTipoProcessoParametrizado;
                $arrVisualizacao['TRIAGEM'] = false;
                $arrVisualizacao['ANALISE'] = false;
                $arrVisualizacao['REVISAO'] = false;
                $arrVisualizacao['DISTRIBUICAO'] = $isGestor || $isUsuarioPertenceFila;
                $arrVisualizacao['ATRIBUICAO'] = $isUsuarioPertenceFilaPapel;
                break;

            case MdUtlControleDsmpRN::$EM_TRIAGEM:
                $arrVisualizacao['ASSOCIACAO'] = $isTipoProcessoParametrizado;
                $arrVisualizacao['TRIAGEM'] = true;
                $arrVisualizacao['ANALISE'] = false;
                $arrVisualizacao['REVISAO'] = false;
                $arrVisualizacao['DISTRIBUICAO'] = $isGestor;
                $arrVisualizacao['ATRIBUICAO'] = false;
                break;

            case MdUtlControleDsmpRN::$AGUARDANDO_ANALISE:
                $arrVisualizacao['ASSOCIACAO'] = $isTipoProcessoParametrizado;
                $arrVisualizacao['TRIAGEM'] = true;
                $arrVisualizacao['ANALISE'] = false;
                $arrVisualizacao['REVISAO'] = false;
                $arrVisualizacao['DISTRIBUICAO'] = $isGestor || $isUsuarioPertenceFila;
                $arrVisualizacao['ATRIBUICAO'] = $isUsuarioPertenceFilaPapel;
                break;

            case MdUtlControleDsmpRN::$EM_ANALISE:
                $arrVisualizacao['ASSOCIACAO'] = $isTipoProcessoParametrizado;
                $arrVisualizacao['TRIAGEM'] = true;
                $arrVisualizacao['ANALISE'] = true;
                $arrVisualizacao['REVISAO'] = false;
                $arrVisualizacao['DISTRIBUICAO'] = $isGestor;
                $arrVisualizacao['ATRIBUICAO'] = false;
                break;

            case MdUtlControleDsmpRN::$AGUARDANDO_REVISAO:
                $arrVisualizacao['ASSOCIACAO'] = false;
                $arrVisualizacao['TRIAGEM'] = true;
                $arrVisualizacao['ANALISE'] = $isPossuiAnalise;
                $arrVisualizacao['REVISAO'] = false;
                $arrVisualizacao['DISTRIBUICAO'] = $isGestor || $isUsuarioPertenceFila;
                $arrVisualizacao['ATRIBUICAO'] = $isUsuarioPertenceFilaPapel;
                break;

            case MdUtlControleDsmpRN::$AGUARDANDO_CORRECAO_TRIAGEM:
            case MdUtlControleDsmpRN::$AGUARDANDO_CORRECAO_ANALISE:
                $arrVisualizacao['ASSOCIACAO'] = false;
                $arrVisualizacao['TRIAGEM'] = true;
                $arrVisualizacao['ANALISE'] = $isPossuiAnalise;
                $arrVisualizacao['REVISAO'] = true;
                $arrVisualizacao['DISTRIBUICAO'] = $isGestor || $isUsuarioPertenceFila;
                $arrVisualizacao['ATRIBUICAO'] = $isUsuarioPertenceFilaPapel;
                break;

            case MdUtlControleDsmpRN::$EM_REVISAO:
            case MdUtlControleDsmpRN::$EM_CORRECAO_TRIAGEM:
            case MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE:
                $arrVisualizacao['ASSOCIACAO'] = false;
                $arrVisualizacao['TRIAGEM'] = true;
                $arrVisualizacao['ANALISE'] = $isPossuiAnalise;
                $arrVisualizacao['REVISAO'] = true;
                $arrVisualizacao['DISTRIBUICAO'] = $isGestor;
                $arrVisualizacao['ATRIBUICAO'] = false;
                break;

            default:
                $arrVisualizacao['ASSOCIACAO'] = false;
                $arrVisualizacao['TRIAGEM'] = false;
                $arrVisualizacao['ANALISE'] = false;
                $arrVisualizacao['REVISAO'] = false;
                $arrVisualizacao['DISTRIBUICAO'] = false;
                $arrVisualizacao['ATRIBUICAO'] = false;
                break;
        }


        return $arrVisualizacao;
    }

    public static function retornaUrlsAcessoDsmp($idStatus, $isPossuiAnalise, $idProcedimento, $idFila, $idUsuarioDsBd, $isMeusProcessos = false)
    {
        $arrUrls = array();
        $isPermiteCadastro = ($idUsuarioDsBd == SessaoSEI::getInstance()->getNumIdUsuario());

        $strPadraoUrl = 'controlador.php?acao=$URL_COMPLETA$&acao_origem=' . $_GET['acao'] . '&id_procedimento=' . $idProcedimento . '&id_fila=' . $idFila;
        $replaceUrl = function ($busca) use ($strPadraoUrl, $isPermiteCadastro, $isMeusProcessos) {
            $buscaArr = explode('_', $busca);
            $vlUnset = $buscaArr[2] == 'revisao' ? 4 : 3;
            unset($buscaArr[$vlUnset]);
            $busca = $isPermiteCadastro ? $busca : (implode("_", $buscaArr) . '_consultar');


            $novaUrl = str_replace('$URL_COMPLETA$', $busca, $strPadraoUrl);

            if ($isMeusProcessos) {
                $novaUrl .= '&pg_padrao=1';
            }

            return SessaoSEI::getInstance()->assinarLink($novaUrl);
        };

        $arrUrls['TRIAGEM'] = '';
        $arrUrls['ANALISE'] = '';
        $arrUrls['REVISAO'] = '';

        switch ($idStatus) {
            case MdUtlControleDsmpRN::$EM_TRIAGEM:
                $arrUrls['TRIAGEM'] = $replaceUrl('md_utl_triagem_cadastrar');
                $arrUrls['ATRIBUICAO'] = $replaceUrl('md_utl_atribuicao_automatica');
                break;
            case MdUtlControleDsmpRN::$AGUARDANDO_ANALISE:
                $arrUrls['TRIAGEM'] = $replaceUrl('md_utl_triagem_consultar');
                $arrUrls['ATRIBUICAO'] = $replaceUrl('md_utl_atribuicao_automatica');
                break;

            case MdUtlControleDsmpRN::$EM_ANALISE:
                $arrUrls['TRIAGEM'] = $replaceUrl('md_utl_triagem_consultar');
                $arrUrls['ANALISE'] = $replaceUrl('md_utl_analise_cadastrar');
                $arrUrls['ATRIBUICAO'] = $replaceUrl('md_utl_atribuicao_automatica');
                break;

            case MdUtlControleDsmpRN::$AGUARDANDO_REVISAO:
                $arrUrls['TRIAGEM'] = $replaceUrl('md_utl_triagem_consultar');
                $arrUrls['ANALISE'] = $replaceUrl('md_utl_analise_consultar');
                $arrUrls['ATRIBUICAO'] = $replaceUrl('md_utl_atribuicao_automatica');
                break;

            case MdUtlControleDsmpRN::$EM_REVISAO:
                $arrUrls['TRIAGEM'] = $replaceUrl('md_utl_triagem_consultar');
                $arrUrls['ANALISE'] = $replaceUrl('md_utl_analise_consultar');
                $conc = $isPossuiAnalise ? 'md_utl_revisao_analise_cadastrar' : 'md_utl_revisao_triagem_cadastrar';
                $arrUrls['REVISAO'] = $replaceUrl($conc);
                $arrUrls['ATRIBUICAO'] = $replaceUrl('md_utl_atribuicao_automatica');
                break;

            case MdUtlControleDsmpRN::$EM_CORRECAO_TRIAGEM:
                $arrUrls['TRIAGEM'] = $replaceUrl('md_utl_triagem_alterar');
                $arrUrls['REVISAO'] = $replaceUrl('md_utl_revisao_triagem_consultar');
                $arrUrls['ATRIBUICAO'] = $replaceUrl('md_utl_atribuicao_automatica');
                break;

            case MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE:
                $arrUrls['TRIAGEM'] = $replaceUrl('md_utl_triagem_consultar');
                $arrUrls['ANALISE'] = $replaceUrl('md_utl_analise_alterar');
                $arrUrls['REVISAO'] = $replaceUrl('md_utl_revisao_analise_consultar');
                $arrUrls['ATRIBUICAO'] = $replaceUrl('md_utl_atribuicao_automatica');
                break;

            case MdUtlControleDsmpRN::$AGUARDANDO_CORRECAO_ANALISE:
                $arrUrls['TRIAGEM'] = $replaceUrl('md_utl_triagem_consultar');
                $arrUrls['ANALISE'] = $replaceUrl('md_utl_analise_consultar');
                $arrUrls['REVISAO'] = $replaceUrl('md_utl_revisao_analise_consultar');
                $arrUrls['ATRIBUICAO'] = $replaceUrl('md_utl_atribuicao_automatica');
                break;

            case MdUtlControleDsmpRN::$AGUARDANDO_CORRECAO_TRIAGEM:
                $arrUrls['TRIAGEM'] = $replaceUrl('md_utl_triagem_consultar');
                $arrUrls['REVISAO'] = $replaceUrl('md_utl_revisao_triagem_consultar');
                $arrUrls['ATRIBUICAO'] = $replaceUrl('md_utl_atribuicao_automatica');
                break;

        }

        return $arrUrls;
    }

    public static function getProximoStatusDistribuicao($idStatus)
    {

        switch ($idStatus) {
            case MdUtlControleDsmpRN::$AGUARDANDO_TRIAGEM:
            case MdUtlControleDsmpRN::$EM_TRIAGEM:
                $idRetorno = MdUtlControleDsmpRN::$EM_TRIAGEM;
                break;

            case MdUtlControleDsmpRN::$AGUARDANDO_ANALISE:
            case MdUtlControleDsmpRN::$EM_ANALISE:
                $idRetorno = MdUtlControleDsmpRN::$EM_ANALISE;
                break;

            case MdUtlControleDsmpRN::$AGUARDANDO_REVISAO:
            case MdUtlControleDsmpRN::$EM_REVISAO:
                $idRetorno = MdUtlControleDsmpRN::$EM_REVISAO;
                break;

            case MdUtlControleDsmpRN::$AGUARDANDO_CORRECAO_TRIAGEM:
            case MdUtlControleDsmpRN::$EM_CORRECAO_TRIAGEM:
                $idRetorno = MdUtlControleDsmpRN::$EM_CORRECAO_TRIAGEM;
                break;

            case MdUtlControleDsmpRN::$AGUARDANDO_CORRECAO_ANALISE:
            case MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE:
                $idRetorno = MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE;
                break;

            default:
                $idRetorno = null;

        }

        return $idRetorno;
    }

    public static function montarSelectEncaminhamentoAnaliseTriagem($vlSelecionado = null)
    {
        $arrParametros = self::retornaSelectEncaminhamentoAnaliseTriagem();
        $select = '<option value=""></option>';

        foreach ($arrParametros as $key => $parametros) {
            $strSelected = $vlSelecionado != null && $key == $vlSelecionado ? 'selected=selected' : '';
            $select .= '<option ' . $strSelected . ' value="' . $key . '">' . $parametros . '</option>';
        }


        return $select;
    }

    public static function retornaSelectEncaminhamentoAnaliseTriagem()
    {
        $arrParametros = array();
        $arrParametros[MdUtlControleDsmpRN::$ENC_ASSOCIAR_EM_FILA] = MdUtlControleDsmpRN::$STR_ENC_ASSOCIAR_EM_FILA;
        $arrParametros[MdUtlControleDsmpRN::$ENC_FINALIZAR_TAREFA] = MdUtlControleDsmpRN::$STR_ENC_FINALIZAR_TAREFA;

        return $arrParametros;

    }

    public static function retornaLinkStatus($arrCtrlUrls, $idStatus)
    {

        $arrStatusTriagem = array(MdUtlControleDsmpRN::$EM_TRIAGEM, MdUtlControleDsmpRN::$EM_CORRECAO_TRIAGEM);
        $arrStatusAnalise = array(MdUtlControleDsmpRN::$EM_ANALISE, MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE);
        $arrStatusRevisao = array(MdUtlControleDsmpRN::$EM_REVISAO);
        $arrStatusEspera = array(MdUtlControleDsmpRN::$SUSPENSO, MdUtlControleDsmpRN::$INTERROMPIDO);

        if (in_array($idStatus, $arrStatusTriagem)) {
            return $arrCtrlUrls['TRIAGEM'];
        }

        if (in_array($idStatus, $arrStatusAnalise)) {
            return $arrCtrlUrls['ANALISE'];
        }

        if (in_array($idStatus, $arrStatusRevisao)) {
            return $arrCtrlUrls['REVISAO'];
        }

        if (in_array($idStatus, $arrStatusEspera)) {
            return '#';
        }

        return '';
    }

    public static function validarTrocaTipoAtividade($idAtividade)
    {

        $xml = '<Dados>';
        $objControleDsmpRN = new MdUtlControleDsmpRN();

        $isRelacionamentosAtivos = true;

        if ($idAtividade) {
            $isRelacionamentosAtivos = $objControleDsmpRN->verificaExisteRelacionamentoAtivoAtividade($idAtividade);
        }

        $xml .= '<IsValido>';
        $xml .= $isRelacionamentosAtivos ? '0' : '1';
        $xml .= '</IsValido>';
        $xml .= '</Dados>';

        return $xml;
    }

    public static function removeNullsTriagem($idsTriagem)
    {
        foreach ($idsTriagem as $key => $idTriagem) {
            if (is_null($idTriagem)) {
                unset($idsTriagem[$key]);
            }
        }
        return $idsTriagem;
    }

    public static function retornaSelectTipoSolicitacao()
    {
        $arr = array();
        $arr[MdUtlControleDsmpRN::$TP_SOLICITACAO_DILACAO] = MdUtlControleDsmpRN::$STR_TP_SOLICITACAO_DILACAO;
        $arr[MdUtlControleDsmpRN::$TP_SOLICITACAO_SUSPENSAO] = MdUtlControleDsmpRN::$STR_TP_SOLICITACAO_SUSPENSAO;
        $arr[MdUtlControleDsmpRN::$TP_SOLICITACAO_INTERRUPCAO] = MdUtlControleDsmpRN::$STR_TP_SOLICITACAO_INTERRUPCAO;
        return $arr;
    }

    public static function getIconePadronizadoAjustePrazo($strStatus, $isDataPermitida, $idPrazoExistente, $staSolicitacao, $numIdControleDsmp, $isDadosParametrizados, $strIdProcedimento, $statusAnterior)

    {
        $strResultado = '';

        $arrStatusNaoPermitidos = array(MdUtlControleDsmpRN::$EM_TRIAGEM, MdUtlControleDsmpRN::$EM_CORRECAO_TRIAGEM);

        $strUrl = PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_meus_processos_dsmp_retornar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_procedimento=' . $strIdProcedimento));
        if (!in_array($strStatus, $arrStatusNaoPermitidos)) {
            if ($isDadosParametrizados) {
                if (is_null($idPrazoExistente)) {
                    if ($isDataPermitida) {
                        $strResultado .= '<a href="' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_ajuste_prazo_cadastrar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_controle_desempenho=' . $numIdControleDsmp . '&is_gerir=0')) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="modulos/utilidades/imagens/ajuste_prazo_cadastro.png" title="Solicitar Ajuste de Prazo" alt="Solicitar Ajuste de Prazo" class="infraImg" /></a>&nbsp;';
                    }
                } else {

                    if ($staSolicitacao == MdUtlAjustePrazoRN::$PENDENTE_RESPOSTA && $isDataPermitida) {
                        $strResultado .= '<a href="' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_ajuste_prazo_alterar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_ajuste_prazo=' . $idPrazoExistente . '&id_controle_desempenho=' . $numIdControleDsmp . '&is_gerir=0')) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="modulos/utilidades/imagens/ajuste_prazo_alteracao.png" title="Alterar Ajuste de Prazo" alt="Alterar Ajuste de Prazo" class="infraImg" /></a>&nbsp;';
                    }

                    if ($strStatus == MdUtlControleDsmpRN::$SUSPENSO || $strStatus == MdUtlControleDsmpRN::$INTERROMPIDO && !is_null($statusAnterior)) {

                        $strResultado .= '<a href="' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_ajuste_prazo_consultar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_ajuste_prazo=' . $idPrazoExistente . '&id_controle_desempenho=' . $numIdControleDsmp . '&is_gerir=0')) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="modulos/utilidades/imagens/ajuste_prazo_consulta.png" title="Consultar Ajuste de Prazo" alt="Consultar Ajuste de Prazo" class="infraImg" /></a>&nbsp;';

                        if ($statusAnterior == MdUtlControleDsmpRN::$EM_REVISAO) {
                            $strResultado .= '<a id="retornarRevisao" onclick="confirmarRetorno(\'' . $strStatus . '\',\'' . $strUrl . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="modulos/utilidades/imagens/retornar_processo.png" title="Retornar para Avaliação" alt="Retornar para Avaliação" class="infraImg" /></a>&nbsp;';
                        } else if ($statusAnterior == MdUtlControleDsmpRN::$EM_ANALISE || MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE) {
                            $strResultado .= '<a id="retornarAnalise" onclick="confirmarRetorno(\'' . $strStatus . '\',\'' . $strUrl . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="modulos/utilidades/imagens/retornar_processo.png" title="Retornar para Análise" alt="Retornar para Análise" class="infraImg" /></a>&nbsp;';
                        }


                    } else {
                        if ($staSolicitacao == MdUtlAjustePrazoRN::$APROVADA || $staSolicitacao == MdUtlAjustePrazoRN::$REPROVADA) {
                            if ($isDataPermitida) {
                                $strResultado .= '<a href="' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_ajuste_prazo_cadastrar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_controle_desempenho=' . $numIdControleDsmp . '&is_gerir=0')) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="modulos/utilidades/imagens/ajuste_prazo_cadastro.png" title="Solicitar Ajuste de Prazo" alt="Solicitar Ajuste de Prazo" class="infraImg" /></a>&nbsp;';
                            }
                            $strResultado .= '<a href="' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_ajuste_prazo_consultar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_ajuste_prazo=' . $idPrazoExistente . '&id_controle_desempenho=' . $numIdControleDsmp . '&is_gerir=0')) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="modulos/utilidades/imagens/ajuste_prazo_consulta.png" title="Consultar Ajuste de Prazo" alt="Consultar Ajuste de Prazo" class="infraImg" /></a>&nbsp;';
                        }
                    }
                }
            } else {
                if ($isDataPermitida) {
                    $strResultado .= '<a href="#" onclick="alert(\'' . MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_87) . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="modulos/utilidades/imagens/ajuste_prazo_cadastro.png" title="Solicitar Ajuste de Prazo" alt="Solicitar Ajuste de Prazo" class="infraImg" /></a>&nbsp;';
                }
            }
        }

        return $strResultado;
    }

    public static function getIconePadronizadoContestacao($strStatus, $numIdControleDsmp, $objContestRevisao, $numIdTriagem, $isDadosParametrizados, $strSituacao)
    {
        $idContestRevisaoExistente = $objContestRevisao->getNumIdMdUtlContestRevisao();
        $strResultado = '';
        $arrContestacaoPermitidos = array(MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE, MdUtlControleDsmpRN::$EM_CORRECAO_TRIAGEM);

        if (in_array($strStatus, $arrContestacaoPermitidos)) {
            if ($isDadosParametrizados) {
                if (is_null($idContestRevisaoExistente) || $strSituacao == MdUtlContestacaoRN::$CANCELADA) {
                    $strResultado .= '<a href="' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_contest_revisao_cadastrar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_controle_desempenho=' . $numIdControleDsmp . '&is_gerir=0' . '&id_triagem=' . $numIdTriagem)) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="modulos/utilidades/imagens/cadastrar_contestacao.png" title="Contestar Avaliação" alt="Contestar Avaliação" class="infraImg" /></a>&nbsp;';
                } else {
                    if ($strSituacao == MdUtlContestacaoRN::$PENDENTE_RESPOSTA) {
                        $strResultado .= '<a href="' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_contest_revisao_alterar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_controle_desempenho=' . $numIdControleDsmp . '&is_gerir=0' . '&id_triagem=' . $numIdTriagem . '&id_contestacao_revisao=' . $idContestRevisaoExistente)) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="modulos/utilidades/imagens/editar_contestacao.png" title="Alterar Contestação de Avaliação" alt="Alterar Contestação de Avaliação" class="infraImg" /></a>&nbsp;';
                        $strResultado .= '<a href="' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_contest_revisao_consultar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_controle_desempenho=' . $numIdControleDsmp . '&is_gerir=0' . '&id_triagem=' . $numIdTriagem . '&id_contestacao_revisao=' . $idContestRevisaoExistente)) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="modulos/utilidades/imagens/visualizar_contestacao.png" title="Consultar Contestação de Avaliação" alt="Consultar Contestação de Avaliação" class="infraImg" /></a>&nbsp;';
                    }
                }
            }
        }

        return $strResultado;
    }

    public static function montarSelectTipoSolicitacao($idSelecionado = null)
    {
        $select = '<option value=""></option>';
        $arr = self::retornaSelectTipoSolicitacao();
        $idSelecionado = trim($idSelecionado);

        foreach ($arr as $key => $value) {

            $selected = !is_null($idSelecionado) && $key == $idSelecionado ? 'selected="selected"' : '';
            $select .= '<option ' . $selected . ' value="' . $key . '"> ' . $value . ' </option>';
        }

        return $select;
    }

    public static function formatarDatasComDoisDigitos($dataFormato)
    {
        $arrData = explode('/', $dataFormato);
        $dia = str_pad($arrData[0], 2, '0', STR_PAD_LEFT);
        $mes = str_pad($arrData[1], 2, '0', STR_PAD_LEFT);
        $ano = str_pad($arrData[2], 2, '0', STR_PAD_LEFT);

        $dataHoraCompleta = $dia . '/' . $mes . '/' . $ano . ' ' . $arrData[3];
        $dataHoraCompleta = trim($dataHoraCompleta);

        return $dataHoraCompleta;
    }

    public static function setNomeAtividade(&$arrObjs, $arrObjsTriagem)
    {
        if (count($arrObjs) > 0) {
            foreach ($arrObjs as $objDTO) {
                $strNomeAtividade = array_key_exists($objDTO->getNumIdMdUtlTriagem(), $arrObjsTriagem) ? $arrObjsTriagem[$objDTO->getNumIdMdUtlTriagem()] : '';
                $strNomeAtividade = is_array($strNomeAtividade) ? 'Múltiplas' : $strNomeAtividade;
                $objDTO->setStrNomeAtividadeTriagem($strNomeAtividade);
            }
        }

        return $arrObjs;
    }

    public static function verificaFilaAssociacaoAutomatica($sinUltimaFila, $idFilaHistorico, $nomeUltimaFila, $idFilaPadrao, $nomeFila)
    {
        $arrRetorno = array();
        $arrRetorno['idFilaCompleto'] = null;
        $arrRetorno['nomeFilaCompleto'] = null;
        $isPreenchido = false;

        /* Se a última fila estiver como SIM e existir ultima fila */
        if ($sinUltimaFila == 'S' && $idFilaHistorico != '') {
            $arrRetorno['idFilaCompleto'] = $idFilaHistorico;
            $arrRetorno['nomeFilaCompleto'] = $nomeUltimaFila;
            $isPreenchido = true;
        }

        /* Se a última fila estiver como SIM e NÃO existir ultima fila, porém existir  fila Padrão */
        if(!$isPreenchido) {
            if (($sinUltimaFila == 'S' && $idFilaHistorico == '' && $idFilaPadrao != '')) {
                $arrRetorno['idFilaCompleto'] = $idFilaPadrao;
                $arrRetorno['nomeFilaCompleto'] = $nomeFila;
                $isPreenchido = true;
            }
        }

        /* Se a última fila estiver como NÃO e Fila Padrão estiver SIM */
        if(!$isPreenchido) {
            if (($sinUltimaFila == 'N' || is_null($sinUltimaFila)) && $idFilaPadrao != '') {
                $arrRetorno['idFilaCompleto'] = $idFilaPadrao;
                $arrRetorno['nomeFilaCompleto'] = $nomeFila;
                $isPreenchido = true;
            }
        }

        /*if ($sinUltimaFila == 'S' && $idFilaHistorico == '' && $idFilaPadrao == '') {
            $isPreenchido = false;
        }

        if (($sinUltimaFila == 'N' || is_null($sinUltimaFila)) && $idFilaHistorico == '' && $idFilaPadrao == '') {
            $isPreenchido = false;
        }*/
        $arrRetorno['isPreenchido'] = $isPreenchido;

        return $arrRetorno;
    }

    public static function validaAssociarProcessoAFila(){
        //retorna os tipos de controle filtrado com: [tipos de procedimentos x tipo de controle vindos do POST]
        $objMdUtlAdmRelPrmGrProcDTO = new MdUtlAdmRelPrmGrProcDTO(); 
        $objMdUtlAdmRelPrmGrProcRN  = new MdUtlAdmRelPrmGrProcRN();
        $objMdUtlAdmRelPrmGrProcDTO->setNumIdTipoProcedimento($_POST['listTpProced'],InfraDTO::$OPER_IN);
        $objMdUtlAdmRelPrmGrProcDTO->setNumIdMdUtlAdmTpCtrlDesemp($_POST['listTpCtrl'],InfraDTO::$OPER_IN);
        $objMdUtlAdmRelPrmGrProcDTO->setDistinct(true);
        $objMdUtlAdmRelPrmGrProcDTO->setOrdNumIdMdUtlAdmTpCtrlDesemp(InfraDTO::$TIPO_ORDENACAO_ASC);
        $objMdUtlAdmRelPrmGrProcDTO->retNumIdMdUtlAdmParamGr();
        $objMdUtlAdmRelPrmGrProcDTO->retNumIdMdUtlAdmTpCtrlDesemp();
        $objMdUtlAdmRelPrmGrProcDTO->retNumIdTipoProcedimento();
        $objMdUtlAdmRelPrmGrProcDTO->retStrNomeTipoControle();
        
        $arrTpCtrlTpProc = self::_montarArrayPersonalizado( $objMdUtlAdmRelPrmGrProcRN->listar($objMdUtlAdmRelPrmGrProcDTO) );
        
        if( empty( $arrTpCtrlTpProc ) ) {
            return '<ListaTpControle>
                        <NaoValidado>
                            <Mensagem>
                                Processo(s) com o Tipo de Processo não vinculado(s) ao Tipo de Controle respectivo(s).
                            </Mensagem>
                        </NaoValidado>
                    </ListaTpControle>';
        }
        
        $arrRetorno = self::validaRegrasParaAssociar($arrTpCtrlTpProc);
        
        //traz lista tp controle agrupado por tipo de procedimento
        $arrTpCtrlTpProcAgrup = self::retornaTpCtrlAgrupadoPorTpProcedimento();
       
        //sinaliza os tp controle que sao comuns ou nao
        $arrRetorno = self::_montaArrayTpCtrlComum($_POST['listTpCtrl'] , $arrTpCtrlTpProcAgrup , $arrRetorno['listaTpCtrl']);
    
        return self::_montaXmlTpCtrlDsmpComum( self::_getArrayTpCtrlComum($arrRetorno) );        
    }

    private static function _montarArrayPersonalizado($arrObj){
        $arrTpCtrl = array();
        foreach ($arrObj as $k => $v) {
            $arrTpCtrl[$v->getNumIdMdUtlAdmTpCtrlDesemp()] = array(
                'idPrmGr'    => $v->getNumIdMdUtlAdmParamGr() , 
                'idTpCtrl'   => $v->getNumIdMdUtlAdmTpCtrlDesemp() , 
                'nmTpCtrl'   => $v->getStrNomeTipoControle() ,
                'idTpProced' => $v->getNumIdTipoProcedimento()
            );
        }
        return $arrTpCtrl;
    }

    private static function validaRegrasParaAssociar($arrTpCtrl){
        $qtdTpCtrlValidado     = 0;
        $objMdUtlAdmPrmGrUsuRN = new MdUtlAdmPrmGrUsuRN();

        // retorna os ids do tipo de controle onde o usuario eh gestor
        $objMdUtlAdmTpCtrlUsuRN = new MdUtlAdmRelTpCtrlDesempUsuRN();
        $isGestor = $objMdUtlAdmTpCtrlUsuRN->usuarioLogadoIsGestorTpControle();

        foreach ($arrTpCtrl as $k => $v) {
            $objMdUtlAdmPrmGrUsuDTO = new MdUtlAdmPrmGrUsuDTO();
            $objMdUtlAdmPrmGrUsuDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
            $objMdUtlAdmPrmGrUsuDTO->setNumIdMdUtlAdmPrmGr($v['idPrmGr']);

            $isMembroParticipante = $objMdUtlAdmPrmGrUsuRN->contar($objMdUtlAdmPrmGrUsuDTO) > 0;

            if ( $isMembroParticipante || in_array($v['idTpCtrl'] , $isGestor)) {
                $arrTpCtrl[$k]['isParticipante'] = 's';
                $qtdTpCtrlValidado ++;
            }else{
                $arrTpCtrl[$k]['isParticipante'] = 'n';
            }
        }
        return array('qtdItensValidado' => $qtdTpCtrlValidado , 'listaTpCtrl' => $arrTpCtrl);
    }

    private static function retornaTpCtrlAgrupadoPorTpProcedimento(){
        $objMdUtlAdmRelPrmGrProcDTO = new MdUtlAdmRelPrmGrProcDTO(); 
        $objMdUtlAdmRelPrmGrProcRN = new MdUtlAdmRelPrmGrProcRN();
        $objMdUtlAdmRelPrmGrProcDTO->setNumIdTipoProcedimento($_POST['listTpProced'],InfraDTO::$OPER_IN);
        $objMdUtlAdmRelPrmGrProcDTO->setNumIdMdUtlAdmTpCtrlDesemp($_POST['listTpCtrl'],InfraDTO::$OPER_IN);
        $objMdUtlAdmRelPrmGrProcDTO->retNumIdMdUtlAdmParamGr();
        $objMdUtlAdmRelPrmGrProcDTO->retNumIdMdUtlAdmTpCtrlDesemp();
        $objMdUtlAdmRelPrmGrProcDTO->retNumIdTipoProcedimento();        
        
        $arrObjTpCtrl = $objMdUtlAdmRelPrmGrProcRN->listar($objMdUtlAdmRelPrmGrProcDTO);
        $isElem   = '.';
        $arrDados = array();
        foreach ($arrObjTpCtrl as $k => $v) {
            if($isElem != $v->getNumIdTipoProcedimento()){
                $arrDados[$v->getNumIdTipoProcedimento()][] = $v->getNumIdMdUtlAdmTpCtrlDesemp();
            }else{
                array_push($arrDados[$v->getNumIdTipoProcedimento()],$v->getNumIdMdUtlAdmTpCtrlDesemp());
            }
        }
        return $arrDados;
    }

    private static function _montaArrayTpCtrlComum($arrTpCtrl, $arrTpCtrlTpProcedAgrup, $arrListaGeral ){        
        foreach ($arrTpCtrl as $k => $v) { // loop nos tipos de controle vindos da combo
            if ( array_key_exists($v , $arrListaGeral)) {
                $valid = true;
                foreach ( $arrTpCtrlTpProcedAgrup as $k1 => $v1) { // loop de cada tipo de procedimento
                    if(!in_array( $v , $v1 ) ){ $valid = false; break; }
                }                

                if( $arrListaGeral[$v]['isParticipante'] == 's' ){
                    $arrListaGeral[$v]['is_comum'] = $valid;
                }                
            }
        }
        return $arrListaGeral;       
    }

    private static function _getArrayTpCtrlComum($arrTpCtrl){
        $arrNovo = array();
        foreach ($arrTpCtrl as $k => $v) {
            if ($arrTpCtrl[$k]['is_comum'] === true){
                $arrNovo[$k] = $v;
            }
        }
        return $arrNovo;
    }

    private static function _montaXmlTpCtrlDsmpComum($arrTpCtrlDsmp) {        
        $strIdsTpCtrl = array();
        $strUrl       = '';
        $xml = '<ListaTpControle>';
        if (empty($arrTpCtrlDsmp)) {
            $xml .= '<Qtd>0</Qtd>';
        } else {
            $xml .= '<Validado></Validado>';
            foreach ($arrTpCtrlDsmp as $k => $v) {
                array_push($strIdsTpCtrl,$k);
            }
            $strUrlAux = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_controle_dsmp_associar&acao_origem='.$_POST['acao_origem'].'&id_tp_controle_desmp='.implode(',',$strIdsTpCtrl));
            $strUrl .= '<Url>'.str_replace('&','&amp;',$strUrlAux).'</Url>';
        }
        $xml .= $strUrl . '</ListaTpControle>';
        return $xml;
    }   

    public static function validaDistribuicaoMultiplo(){
        $p = $_POST;

        $objProcessoDTO = new MdUtlProcedimentoDTO();
        $objProcessoRN  = new ProcedimentoRN();
        $objProcessoDTO->setDblIdProcedimento($p['listProcessos'],InfraDTO::$OPER_IN);
        $objProcessoDTO->setNumIdUnidade( SessaoSEI::getInstance()->getNumIdUnidadeAtual() );
        $objProcessoDTO->retStrNomeTpCtrlDsmp();
        $objProcessoDTO->retNumIdMdUtlAdmTpCtrlDesemp();
        $objProcessoDTO->retStrNomeFila();
        $objProcessoDTO->retNumIdFila();
        $objProcessoDTO->retStrStaAtendimentoDsmp();
        
        $rs = $objProcessoRN->listarRN0278($objProcessoDTO);

        $strNmTpCtrl    = $rs[0]->getStrNomeTpCtrlDsmp();
        $strNmFila      = $rs[0]->getStrNomeFila();
        $idFila         = $rs[0]->getNumIdFila();
        $strStrSitAtend = $rs[0]->getStrStaAtendimentoDsmp();
        $idTpCtrl       = $rs[0]->getNumIdMdUtlAdmTpCtrlDesemp();
        
        $xml   = "<Dados>";
        $valid = true;
        foreach ($rs as $k => $v) {
            if( $v->getStrNomeTpCtrlDsmp() != $strNmTpCtrl )        { $xml .= "<TipoControle></TipoControle>"; $valid = false; }
            if( $v->getStrNomeFila() != $strNmFila )                { $xml .= "<Fila></Fila>"; $valid = false; }
            if( $v->getStrStaAtendimentoDsmp() != $strStrSitAtend ) { $xml .= "<Situacao></Situacao>"; $valid = false; }
        }
        if( $valid ) {
            $strUrl    = 'controlador.php?acao=md_utl_distrib_usuario_';
            $strUrlAux = SessaoSEI::getInstance()->assinarLink($strUrl.'cadastrar&acao_origem='.$p['acao'].'&id_tp_controle_desmp='.$idTpCtrl.'&acao_retorno='.$p['acao']);
            $xml      .= "<Url>".str_replace('&','&amp;',$strUrlAux)."</Url>";
            $xml      .= "<Situacao>$strStrSitAtend</Situacao>";
            $xml      .= "<Fila>$idFila</Fila>";
        }
        $xml .= "</Dados>";
        return $xml;
    }

    /**
     * $tpBtn = [1 => Distribuir , 2 => Distribuir para Mim]
     */
    public static function getLabelBtn($tpBtn , $situacao){
        $nmBtn = 'Sem Nome';
        if ( $tpBtn == 1 ) {
            $nmBase  = 'D<span class="infraTeclaAtalho">i</span>stribuir #mudar';
            $nmBase2 = 'Alterar D<span class="infraTeclaAtalho">i</span>stribuição da #mudar';
            switch ( $situacao ) {
                case 1:
                case 7:
                    $nmBtn = str_replace( '#mudar' , MdUtlControleDsmpRN::$STR_TIPO_ACAO_TRIAGEM , $nmBase );
                    break;                
                case 2:
                case 8:
                    $nmBtn = str_replace( '#mudar' , MdUtlControleDsmpRN::$STR_TIPO_ACAO_TRIAGEM , $nmBase2 );
                    break;
                case 4:
                case 10:
                    $nmBtn = str_replace( '#mudar' , MdUtlControleDsmpRN::$STR_TIPO_ACAO_ANALISE , $nmBase2 );
                    break;
                case 6:
                    $nmBtn = str_replace( '#mudar' , 'Avaliação' , $nmBase2 );
                    break;
                case 3:
                case 9:
                    $nmBtn = str_replace( '#mudar' , MdUtlControleDsmpRN::$STR_TIPO_ACAO_ANALISE , $nmBase );
                    break;
                case 5:
//                    $nmBtn = str_replace( '#mudar' , MdUtlControleDsmpRN::$STR_TIPO_ACAO_REVISAO , $nmBase );
                    $nmBtn = str_replace( '#mudar' , 'Avaliação' , $nmBase );
                    break;
                default:    
                    break;
            }
        } 
        else if ( $tpBtn == 2 ) {
            $nmBase = 'Distribuir #mudar para <span class="infraTeclaAtalho">m</span>im';
            switch ( $situacao ) {
                case 1:
                case 7:
                    $nmBtn = str_replace( '#mudar' , MdUtlControleDsmpRN::$STR_TIPO_ACAO_TRIAGEM , $nmBase );
                    break;
                case 3:
                    $nmBtn = str_replace( '#mudar' , MdUtlControleDsmpRN::$STR_TIPO_ACAO_ANALISE , $nmBase );
                    break;
                case 5:
//                    $nmBtn = str_replace( '#mudar' , MdUtlControleDsmpRN::$STR_TIPO_ACAO_REVISAO , $nmBase );
                    $nmBtn = str_replace( '#mudar' , 'Avaliação' , $nmBase );
                    break;
                case 9:
                    $nmBtn = str_replace( '#mudar' , MdUtlControleDsmpRN::$STR_TIPO_ACAO_ANALISE , $nmBase );
                    break;
            }
        }
        return $nmBtn;
    }
}