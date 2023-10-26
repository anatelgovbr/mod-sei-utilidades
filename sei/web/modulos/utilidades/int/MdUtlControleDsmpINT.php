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
	      $arrRetorno[MdUtlControleDsmpRN::$RASCUNHO_ANALISE] = MdUtlControleDsmpRN::$STR_RASCUNHO_ANALISE;
	      $arrRetorno[MdUtlControleDsmpRN::$RASCUNHO_CORRECAO_ANALISE] = MdUtlControleDsmpRN::$STR_RASCUNHO_CORRECAO_ANALISE;

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
        $arrRetorno[MdUtlControleDsmpRN::$RASCUNHO_ANALISE] = MdUtlControleDsmpRN::$STR_RASCUNHO_ANALISE;
        $arrRetorno[MdUtlControleDsmpRN::$RASCUNHO_CORRECAO_ANALISE] = MdUtlControleDsmpRN::$STR_RASCUNHO_CORRECAO_ANALISE;

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
                $arrVisualizacao['DISTRIBUICAO'] = $isGestor || $isUsuarioPertenceFila;
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
            case MdUtlControleDsmpRN::$RASCUNHO_ANALISE:
                $arrVisualizacao['ASSOCIACAO'] = $isTipoProcessoParametrizado;
                $arrVisualizacao['TRIAGEM'] = true;
                $arrVisualizacao['ANALISE'] = true;
                $arrVisualizacao['REVISAO'] = false;
                $arrVisualizacao['DISTRIBUICAO'] = $isGestor || $isUsuarioPertenceFila;
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
            case MdUtlControleDsmpRN::$RASCUNHO_CORRECAO_ANALISE:
                $arrVisualizacao['ASSOCIACAO'] = false;
                $arrVisualizacao['TRIAGEM'] = true;
                $arrVisualizacao['ANALISE'] = $isPossuiAnalise;
                $arrVisualizacao['REVISAO'] = true;
                $arrVisualizacao['DISTRIBUICAO'] = $isGestor || $isUsuarioPertenceFila;
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
            case MdUtlControleDsmpRN::$RASCUNHO_ANALISE:
            case MdUtlControleDsmpRN::$RASCUNHO_CORRECAO_ANALISE:
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

            case MdUtlControleDsmpRN::$RASCUNHO_ANALISE:
                $idRetorno = MdUtlControleDsmpRN::$RASCUNHO_ANALISE;
                break;

            case MdUtlControleDsmpRN::$RASCUNHO_CORRECAO_ANALISE:
                $idRetorno = MdUtlControleDsmpRN::$RASCUNHO_CORRECAO_ANALISE;
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

    public static function montarSelectMembroResponsavelAvaliacao($vlSelecionado = null, $arrayBuscarUsuarios)
    {

        $arrParametros = self::retornaSelectMembroResponsavelAvaliacao($arrayBuscarUsuarios);
        $select = '<option value=""></option>';
        if(!is_null($arrParametros)) {
            foreach ($arrParametros as$parametros) {
                $strSelected = $vlSelecionado != null && $parametros->getNumIdUsuario() == $vlSelecionado ? 'selected=selected' : '';
                $select .= '<option ' . $strSelected . ' value="' . $parametros->getNumIdUsuario() . '">' . $parametros->getStrNome() . '</option>';
            }
        }
        return $select;
    }

    public static function montarSelectPeriodoAnalise($idTipoControleDesempenho, $idUsuarioAtribuicao, $periodoInicialSelecionado = NULL, $periodoFinalSelecionado = NULL, $frequenciaAnalise = NULL)
    {
        $objMdUtlAdmTpCtrlDesempRN  = new MdUtlAdmTpCtrlDesempRN();
        $objMdUtlAdmTpCtrlDesempDTO = new MdUtlAdmTpCtrlDesempDTO();
        $objMdUtlAdmTpCtrlDesempDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTipoControleDesempenho);
        $objMdUtlAdmTpCtrlDesempDTO->retNumIdMdUtlAdmPrmGr();
        $objMdUtlAdmTpCtrlDesemp = $objMdUtlAdmTpCtrlDesempRN->consultar($objMdUtlAdmTpCtrlDesempDTO);

        $objMdUtlAdmPrmGrRN = new MdUtlAdmPrmGrRN();
        $objMdUtlAdmPrmGrDTO = new MdUtlAdmPrmGrDTO();
        $objMdUtlAdmPrmGrDTO->setNumIdMdUtlAdmPrmGr( $objMdUtlAdmTpCtrlDesemp->getNumIdMdUtlAdmPrmGr() );
        $objMdUtlAdmPrmGrDTO->retStrStaFrequencia();
        $objMdUtlAdmPrmGrDTO->retNumInicioPeriodo();
        $objMdUtlAdmPrmGrDTO->retDtaDataCorte();
        $objMdUtlAdmPrmGr = $objMdUtlAdmPrmGrRN->consultar($objMdUtlAdmPrmGrDTO);

        $objMdUtlAdmPrmGrUsuDTO = new MdUtlAdmPrmGrUsuDTO();
        $objMdUtlAdmPrmGrUsuRN = new MdUtlAdmPrmGrUsuRN();

        $objMdUtlAdmPrmGrUsuDTO->setNumIdMdUtlAdmPrmGr($objMdUtlAdmTpCtrlDesemp->getNumIdMdUtlAdmPrmGr());
        $objMdUtlAdmPrmGrUsuDTO->setNumIdUsuario($idUsuarioAtribuicao);
        $objMdUtlAdmPrmGrUsuDTO->setNumMaxRegistrosRetorno(1);
        $objMdUtlAdmPrmGrUsuDTO->retDthInicioParticipacao();
        $objMdUtlAdmPrmGrUsuDTO = $objMdUtlAdmPrmGrUsuRN->consultar($objMdUtlAdmPrmGrUsuDTO);

        if(!is_null($objMdUtlAdmPrmGrUsuDTO->getDthInicioParticipacao())) {
            $dataInicioPeriodo = $objMdUtlAdmPrmGrUsuDTO->getDthInicioParticipacao();
            $dataInicioPeriodo = explode(" ", $dataInicioPeriodo);
            $dataInicioPeriodo = $dataInicioPeriodo[0];
        } elseif($objMdUtlAdmPrmGr->getDtaDataCorte()) {
            $dataInicioPeriodo = $objMdUtlAdmPrmGr->getDtaDataCorte();
        } else {
            $dataInicioPeriodo = date("Y-m-d");
        }
        $dataInicioPeriodo = implode('-', array_reverse(explode('/', $dataInicioPeriodo)));
        $periodo = array();
        if($frequenciaAnalise != NULL) {
            $frequencia = $frequenciaAnalise;
        } else {
            $frequencia = $objMdUtlAdmPrmGr->getStrStaFrequencia();
        }

        if($frequencia == "S") {
		        $inicioSemana        = date('Y-m-d', strtotime("Monday this week"));
		        $strDiaSemanaAtual   = InfraData::obterDescricaoDiaSemana(date('d/m/Y'));
		        $diaFinalSemanaAtual = $strDiaSemanaAtual == 'domingo' ? InfraData::getStrDataAtual() : date("d/m/Y", strtotime("next Sunday"));
		        $periodo[]           = "Semanal (". date("d/m/Y", strtotime($inicioSemana))." a ". $diaFinalSemanaAtual .") - Atual";
		        $i = -1;
            while(strtotime($inicioSemana) >  strtotime($dataInicioPeriodo)) {
                $inicioSemana = date("Y-m-d", strtotime($i." week", strtotime($inicioSemana)));
                $periodo[] = "Semanal (". date("d/m/Y", strtotime($inicioSemana))." a ". date("d/m/Y", strtotime("next Sunday", strtotime($inicioSemana))).")";
            }
        } elseif($frequencia == "M") {
                $inicioMes = date('Y-m-01');
                $periodo[] = "Mensal (". date("d/m/Y", strtotime($inicioMes))." a ". date("t/m/Y").") - Atual";
                $i = -1;
                while(strtotime($inicioMes) >  strtotime($dataInicioPeriodo)) {
                    $inicioMes = date("Y-m-01", strtotime($i." month"));
                    $periodo[] = "Mensal (". date("d/m/Y", strtotime($inicioMes))." a ". date("t/m/Y", strtotime($inicioMes)).")";
                    $i--;
                }
        } else {
            return array("D", $dataInicioPeriodo);
        }
        $select = '<option value=""></option>';
        $vlSelecionado = $periodoInicialSelecionado."|".$periodoFinalSelecionado;
				$numLimitador = 15;
        foreach ($periodo as $key => $parametros) {
            $periodoExplodido = explode("(", $parametros);
            $periodoInicialExplodido = explode(" ", $periodoExplodido[1]);
            $periodoInicial = $periodoInicialExplodido[0];
            $periodoFinalExplodido = explode(")", $periodoInicialExplodido[2]);
            $periodoFinal = $periodoFinalExplodido[0];
            $chave = $periodoInicial . '|' . $periodoFinal;
            $strSelected = $vlSelecionado != null && $chave == $vlSelecionado ? 'selected=selected' : '';
            $select .= '<option ' . $strSelected . ' value="' . $chave.'">' . $parametros . '</option>';
            $numLimitador--;
            if( $numLimitador == 0 ) break;
        }
        return array($frequencia, $select);
    }

    public static function retornaSelectEncaminhamentoAnaliseTriagem()
    {
        $arrParametros = array();
        $arrParametros[MdUtlControleDsmpRN::$ENC_ASSOCIAR_EM_FILA] = MdUtlControleDsmpRN::$STR_ENC_ASSOCIAR_EM_FILA;
        $arrParametros[MdUtlControleDsmpRN::$ENC_FINALIZAR_TAREFA] = MdUtlControleDsmpRN::$STR_ENC_FINALIZAR_TAREFA;

        return $arrParametros;

    }

    public static function retornaSelectMembroResponsavelAvaliacao($arrayBuscarUsuarios)
    {
        $idTipoControle    = $arrayBuscarUsuarios["id_tipo_controle_utl"];
        $idParams          = null;
        $isBolDistribuicao = $arrayBuscarUsuarios["is_bol_distribuicao"];
        $tpSelecao         = $arrayBuscarUsuarios["tipo_selecao"];
        $idFila            = $arrayBuscarUsuarios["id_fila"];
        $idStatus          = $arrayBuscarUsuarios["id_status"];
        $arrProcedimentos  = $arrayBuscarUsuarios["arr_procedimentos"];
        $possuiRegistrosDist = false;
        $arrObjUsuarioDTO  = null;
        $isVazioUsers      = false;

        $urlPadrao = 'controlador.php?acao=md_utl_adm_usuario_selecionar';

        if(!is_null($isBolDistribuicao) && $isBolDistribuicao == '1'){
            $tpSelecao = 1;
            $isBolUsuario = 1;
            $isBolUsuarioDTO = 1;
            $urlPadrao .= '&is_bol_distribuicao='.$isBolDistribuicao;
        }

        if(!is_null($tpSelecao)){
            $urlPadrao .= '&tipo_selecao='.$tpSelecao;
        }

        if(!is_null($idFila)){
            $urlPadrao .= '&id_fila='.$idFila;
        }

        if(!is_null($idStatus)){
            $urlPadrao .= '&id_status='.$idStatus;
        }

        if(!is_null($idTipoControle)){
            $urlPadrao .= '&id_tipo_controle_utl='.$idTipoControle;
        }

        if(!is_null($isBolUsuario) && $isBolUsuario == '1'){
            $urlPadrao .= '&is_bol_usuario='.$isBolUsuario;
        }

        if(!is_null($isBolUsuarioDTO) && $isBolUsuarioDTO == '1'){
            $urlPadrao .= '&is_bol_usu_dto='.$isBolUsuarioDTO;
        }

        if(!is_null($idTipoControle) && $idTipoControle !=''){
            $objMdUtlAdmTpCtrlRN  = new MdUtlAdmTpCtrlDesempRN();

            $objMdUtlAdmTpCtrlDTO = new MdUtlAdmTpCtrlDesempDTO();
            $objMdUtlAdmTpCtrlDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTipoControle);
            $objMdUtlAdmTpCtrlDTO->retNumIdMdUtlAdmPrmGr();
            $objMdUtlAdmTpCtrlDTO->setNumTotalRegistros(1);

            $objMdUtlAdmTpCtrlDTO = $objMdUtlAdmTpCtrlRN->consultar($objMdUtlAdmTpCtrlDTO);

            $idParams = $objMdUtlAdmTpCtrlDTO->getNumIdMdUtlAdmPrmGr();
        }
        if(!is_null($isBolUsuarioDTO) && $isBolUsuarioDTO == 1 && is_null($idParams)) {
            $objUsuarioDTO = new UsuarioDTO();
        }else {
            $objUsuarioDTO = new MdUtlAdmPrmGrUsuDTO();
            $objUsuarioDTO->retNumIdMdUtlAdmPrmGrUsu();
        }


        if (!is_null($isBolDistribuicao) && $isBolDistribuicao == '1') {
            $strPapelUsuario = MdUtlAdmFilaINT::getPapeisDeUsuario($idStatus);

            if (!is_null($strPapelUsuario)) {
                $arrDTO = null;
                $objRegrasGeraisRN = new MdUtlRegrasGeraisRN();
                $idsUsuarioUnidade = $objRegrasGeraisRN->getIdsUsuariosUnidadeLogada();

                if(count($idsUsuarioUnidade) > 0) {
                    $objMdUtlAdmFilaPrmUsuRN = new MdUtlAdmFilaPrmGrUsuRN();
                    $arrDTO = $objMdUtlAdmFilaPrmUsuRN->getUsuarioPorPapel(array($strPapelUsuario, $idFila, $idsUsuarioUnidade));
                }

                if (is_null($arrDTO)) {
                    $isVazioUsers = true;
                }else{
                    $idsUsuario = InfraArray::converterArrInfraDTO($arrDTO, 'IdUsuario');

                    // se tiver informado o procedimento, não retorna pessoas que possam ser avaliadoras dela mesma.
                    $moduloAutoAvaliacaoLiberado = MdUtlAdmPrmGrUsuINT::verificaModoluloLiberarAutoAvaliacaoAtivado();
                    $arrProcedimentos = explode(",", trim($arrProcedimentos));

                    if (!$moduloAutoAvaliacaoLiberado && count($arrProcedimentos)>0){
                        $arrIdsPessoasQueNaoPodeDistribuir = MdUtlAdmPrmGrUsuINT::buscarArrayPessoasNaoPodeDistribuir($arrProcedimentos, true);
                        $idsUsuario = array_diff($idsUsuario, $arrIdsPessoasQueNaoPodeDistribuir);
                    }
                    $possuiRegistrosDist = count($idsUsuario) > 0;
                    if (count($idsUsuario) > 0) {
                        $objUsuarioDTO->setNumIdUsuario($idsUsuario, InfraDTO::$OPER_IN);
                    }
                }
            }
        }

        $objUsuarioDTO->retNumIdUsuario();
        $objUsuarioDTO->retNumIdOrgao();
        $objUsuarioDTO->retStrSiglaOrgao();
        $objUsuarioDTO->retStrDescricaoOrgao();
        $objUsuarioDTO->retStrSigla();
        $objUsuarioDTO->retStrNome();
        if( (!is_null($idParams) || !is_null($isBolUsuarioDTO)) && !$isVazioUsers) {

            if (!is_null($idParams)) {
                $objUsuarioDTO->setNumIdMdUtlAdmPrmGr($idParams);
            }

            $objUsuarioDTO->setStrStaTipo(UsuarioRN::$TU_SIP);

            PaginaSEI::getInstance()->prepararOrdenacao($objUsuarioDTO, 'Nome', InfraDTO::$TIPO_ORDENACAO_ASC);

            PaginaSEI::getInstance()->prepararPaginacao($objUsuarioDTO);

            if (!is_null($isBolUsuarioDTO) && $isBolUsuarioDTO == 1 && is_null($idParams) && $isBolDistribuicao != 1) {
                $objUsuarioRN = new UsuarioRN();
                $arrObjUsuarioDTO = $objUsuarioRN->pesquisar($objUsuarioDTO);
            }

            // Para a Fila
            if (!is_null($idParams) && is_null($isBolUsuarioDTO)) {
                $objUsuarioRN = new MdUtlAdmPrmGrUsuRN();
                $arrObjUsuarioDTO = $objUsuarioRN->pesquisarUsuarioParametros($objUsuarioDTO);
            }

            //Para a Distribuição
            if ($isBolDistribuicao == '1' && $possuiRegistrosDist) {

                $objUsuarioRN = new MdUtlAdmPrmGrUsuRN();
                $arrObjUsuarioDTO = $objUsuarioRN->pesquisarUsuarioParametros($objUsuarioDTO);
            }

            if (!is_null($arrObjUsuarioDTO)) {
                $arrObjUsuarioDTO = InfraArray::distinctArrInfraDTO($arrObjUsuarioDTO, 'IdUsuario');
            }

            PaginaSEI::getInstance()->processarPaginacao($objUsuarioDTO);

            return $arrObjUsuarioDTO;
        }

    }

    public static function retornaLinkStatus($arrCtrlUrls, $idStatus)
    {

        $arrStatusTriagem = array(MdUtlControleDsmpRN::$EM_TRIAGEM, MdUtlControleDsmpRN::$EM_CORRECAO_TRIAGEM);
        $arrStatusAnalise = array(MdUtlControleDsmpRN::$EM_ANALISE, MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE, MdUtlControleDsmpRN::$RASCUNHO_ANALISE, MdUtlControleDsmpRN::$RASCUNHO_CORRECAO_ANALISE);
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

    public static function getIconePadronizadoAjustePrazo($strStatus, $isDataPermitida, $idPrazoExistente, $staSolicitacao, $numIdControleDsmp, $isDadosParametrizados, $strIdProcedimento, $statusAnterior, $prazoResposta, $bolHabAjustePrazoAtv, $dataPrazoFormatada)

    {
        $strResultado = '';

        if ( $prazoResposta == '' && $bolHabAjustePrazoAtv === false ) return $strResultado;

        $arrStatusNaoPermitidos = array(MdUtlControleDsmpRN::$EM_TRIAGEM, MdUtlControleDsmpRN::$EM_CORRECAO_TRIAGEM);

        $strUrl = PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_meus_processos_dsmp_retornar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_procedimento=' . $strIdProcedimento));
        if (!in_array($strStatus, $arrStatusNaoPermitidos)) {
            if ($isDadosParametrizados) {
                if (is_null($idPrazoExistente)) {
                    if ($isDataPermitida) {
                    	  $link = PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_ajuste_prazo_cadastrar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_controle_desempenho=' . $numIdControleDsmp . '&is_gerir=0'));
                        $strResultado .= '<a onclick="validarDataPrazo(\''.$dataPrazoFormatada.'\',\''.$link.'\')" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="modulos/utilidades/imagens/svg/ajuste_prazo_cadastro.svg?11" width="24" height="24" title="Solicitar Ajuste de Prazo" alt="Solicitar Ajuste de Prazo" class="infraImg" /></a>&nbsp;';
                    }
                } else {

                    if ($staSolicitacao == MdUtlAjustePrazoRN::$PENDENTE_RESPOSTA && $isDataPermitida) {
                    	  $link = PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_ajuste_prazo_alterar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_ajuste_prazo=' . $idPrazoExistente . '&id_controle_desempenho=' . $numIdControleDsmp . '&is_gerir=0'));
                        $strResultado .= '<a onclick="validarDataPrazo(\''.$dataPrazoFormatada.'\',\''.$link.'\')" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="modulos/utilidades/imagens/svg/ajuste_prazo_alteracao.svg?11" width="24" height="24" title="Alterar Ajuste de Prazo" alt="Alterar Ajuste de Prazo" class="infraImg" /></a>&nbsp;';
                    }

                    if ($strStatus == MdUtlControleDsmpRN::$SUSPENSO || $strStatus == MdUtlControleDsmpRN::$INTERROMPIDO && !is_null($statusAnterior)) {

                        $strResultado .= '<a href="' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_ajuste_prazo_consultar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_ajuste_prazo=' . $idPrazoExistente . '&id_controle_desempenho=' . $numIdControleDsmp . '&is_gerir=0')) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="modulos/utilidades/imagens/svg/ajuste_prazo_consulta.svg?11" width="24" height="24" title="Consultar Ajuste de Prazo" alt="Consultar Ajuste de Prazo" class="infraImg" /></a>&nbsp;';

                        if ($statusAnterior == MdUtlControleDsmpRN::$EM_REVISAO) {
                            $strResultado .= '<a id="retornarRevisao" onclick="confirmarRetorno(\'' . $strStatus . '\',\'' . $strUrl . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="modulos/utilidades/imagens/svg/retornar_processo.svg?11" width="24" height="24" title="Retornar para Avaliação" alt="Retornar para Avaliação" class="infraImg" /></a>&nbsp;';
                        } else if ($statusAnterior == MdUtlControleDsmpRN::$EM_ANALISE || MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE) {
                            $strResultado .= '<a id="retornarAnalise" onclick="confirmarRetorno(\'' . $strStatus . '\',\'' . $strUrl . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="modulos/utilidades/imagens/svg/retornar_processo.svg?11" width="24" height="24" title="Retornar para Análise" alt="Retornar para Análise" class="infraImg" /></a>&nbsp;';
                        }


                    } else {
                        if ($staSolicitacao == MdUtlAjustePrazoRN::$APROVADA || $staSolicitacao == MdUtlAjustePrazoRN::$REPROVADA) {
                            if ($isDataPermitida) {
                            	  $link = PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_ajuste_prazo_cadastrar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_controle_desempenho=' . $numIdControleDsmp . '&is_gerir=0'));
                                $strResultado .= '<a onclick="validarDataPrazo(\''.$dataPrazoFormatada.'\',\''.$link.'\')" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="modulos/utilidades/imagens/svg/ajuste_prazo_cadastro.svg?11" width="24" height="24"" title="Solicitar Ajuste de Prazo" alt="Solicitar Ajuste de Prazo" class="infraImg" /></a>&nbsp;';
                            }
                            $strResultado .= '<a href="' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_ajuste_prazo_consultar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_ajuste_prazo=' . $idPrazoExistente . '&id_controle_desempenho=' . $numIdControleDsmp . '&is_gerir=0')) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="modulos/utilidades/imagens/svg/ajuste_prazo_consulta.svg?11" width="24" height="24"" title="Consultar Ajuste de Prazo" alt="Consultar Ajuste de Prazo" class="infraImg" /></a>&nbsp;';
                        }
                    }
                }
            } else {
                if ($isDataPermitida) {
                    $strResultado .= '<a href="#" onclick="alert(\'' . MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_87) . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="modulos/utilidades/imagens/svg/ajuste_prazo_cadastro.svg?11" width="24" height="24"" title="Solicitar Ajuste de Prazo" alt="Solicitar Ajuste de Prazo" class="infraImg" /></a>&nbsp;';
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
                    $strResultado .= '<a href="' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_contest_revisao_cadastrar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_controle_desempenho=' . $numIdControleDsmp . '&is_gerir=0' . '&id_triagem=' . $numIdTriagem)) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="modulos/utilidades/imagens/svg/contestacao_cadastro.svg?11" width="24" height="24" title="Contestar Avaliação" alt="Contestar Avaliação" class="infraImg" /></a>&nbsp;';
                } else {
                    if ($strSituacao == MdUtlContestacaoRN::$PENDENTE_RESPOSTA) {
                        $strResultado .= '<a href="' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_contest_revisao_alterar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_controle_desempenho=' . $numIdControleDsmp . '&is_gerir=0' . '&id_triagem=' . $numIdTriagem . '&id_contestacao_revisao=' . $idContestRevisaoExistente)) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="modulos/utilidades/imagens/svg/contestacao_editar.svg?11" width="24" height="24"" title="Alterar Contestação de Avaliação" alt="Alterar Contestação de Avaliação" class="infraImg" /></a>&nbsp;';
                        $strResultado .= '<a href="' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_contest_revisao_consultar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_controle_desempenho=' . $numIdControleDsmp . '&is_gerir=0' . '&id_triagem=' . $numIdTriagem . '&id_contestacao_revisao=' . $idContestRevisaoExistente)) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="modulos/utilidades/imagens/svg/contestacao_visualizar.svg?11" width="24" height="24"" title="Consultar Contestação de Avaliação" alt="Consultar Contestação de Avaliação" class="infraImg" /></a>&nbsp;';
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
                case 15:
                case 16:
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

    public static function getNomeUsuarioRespTriagAnaliseAval( $numId , $strSitAtendimento, $strTela , $strOrigem = null ){
        /* RELACAO TABELA
            C = CONTROLE DSMP, T = TRIAGEM , A = ANALISE , V = REVISAO(AVALIACAO)
        */
        $arrLetraIni = ['C' => 'C' , 'T' => 'T' , 'A' => 'A' , 'V' => 'V'];

        $objInfo = null;
        switch ( $strTela ) {
            case MdUtlControleDsmpRN::$STR_TIPO_ACAO_TRIAGEM: //Triagem
                if ( in_array( $strSitAtendimento , [ MdUtlControleDsmpRN::$EM_TRIAGEM,MdUtlControleDsmpRN::$EM_CORRECAO_TRIAGEM ] ) ) {
                    $objInfo = self::getUsuarioTriagemAnaliseAvaliacao( $numId , $arrLetraIni['C'] );
                }
                else{
                    $objInfo = self::getUsuarioTriagemAnaliseAvaliacao( $numId , $arrLetraIni['T'] );
                }   
                break;
            
            case MdUtlControleDsmpRN::$STR_TIPO_ACAO_ANALISE: //Analise

                if ( in_array( $strSitAtendimento , [MdUtlControleDsmpRN::$EM_ANALISE , MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE, MdUtlControleDsmpRN::$RASCUNHO_ANALISE, MdUtlControleDsmpRN::$RASCUNHO_CORRECAO_ANALISE] ) ) {
                    $objInfo = self::getUsuarioTriagemAnaliseAvaliacao( $numId , $arrLetraIni['C'] );
                }                
                else{
                    $objInfo = self::getUsuarioTriagemAnaliseAvaliacao( $numId , $arrLetraIni['A'] );
                }   
                break;
            
            case MdUtlControleDsmpRN::$STR_TIPO_ACAO_REVISAO: //Revisao (Avaliacao)
                if ( $strSitAtendimento == MdUtlControleDsmpRN::$EM_REVISAO ) {
                    $objInfo = self::getUsuarioTriagemAnaliseAvaliacao( $numId , $arrLetraIni['C'] );
                }
                else{
                    $objInfo = self::getUsuarioTriagemAnaliseAvaliacao( $numId , $arrLetraIni['V'] );
                }
                   
                break;
            
            default:  break;
        }

        return $objInfo;
    }

    public static function getUsuarioTriagemAnaliseAvaliacao( $id , $table ){
        $id_usuario = null;
        switch ( $table ) {
            case 'T': // md_utl_triagem               
                $objComumDTO = new MdUtlTriagemDTO();
                $objComumRN  = new MdUtlTriagemRN();

                $objComumDTO->setNumIdMdUtlTriagem( $id );
                $objComumDTO->retNumIdUsuario();
                $objComumDTO = $objComumRN->consultar( $objComumDTO );

                $id_usuario = $objComumDTO->getNumIdUsuario();
               
                break;
            
            case 'A': // md_utl_analise
                $objComumDTO = new MdUtlAnaliseDTO();
                $objComumRN  = new MdUtlAnaliseRN();
    
                $objComumDTO->setNumIdMdUtlAnalise( $id );
                $objComumDTO->retNumIdUsuario();
                $objComumDTO = $objComumRN->consultar( $objComumDTO );
    
                $id_usuario = $objComumDTO->getNumIdUsuario();
                
                break;

            case 'V': // md_utl_revisao(avaliacao)
                $objComumDTO = new MdUtlRevisaoDTO();
                $objComumRN  = new MdUtlRevisaoRN();

                $objComumDTO->setNumIdMdUtlRevisao( $id );
                $objComumDTO->retNumIdUsuario();
                $objComumDTO = $objComumRN->consultar( $objComumDTO );

                $id_usuario = $objComumDTO->getNumIdUsuario();
                
                break;
            
            default: // Controle Desempenho                
                $objComumDTO = new MdUtlControleDsmpDTO();
                $objComumRN  = new MdUtlControleDsmpRN();

                $objComumDTO->setNumIdMdUtlControleDsmp( $id );
                $objComumDTO->retNumIdUsuarioDistribuicao();
                $objComumDTO = $objComumRN->consultar( $objComumDTO );

                $id_usuario = $objComumDTO->getNumIdUsuarioDistribuicao();
            break;
        }

        $objUsuarioDTO = new UsuarioDTO();
        $objUsuarioRN  = new UsuarioRN();

        $objUsuarioDTO->setNumIdUsuario( $id_usuario );
        $objUsuarioDTO->setBolExclusaoLogica(false);
        $objUsuarioDTO->retStrNome();
        $objUsuarioDTO->retNumIdUsuario();
        $objUsuarioDTO = $objUsuarioRN->consultarRN0489( $objUsuarioDTO );

        return $objUsuarioDTO;
    }

    public static function habAjustePrazoAtv( $arrIdsTriagem ){
        $arrRetorno = [];
        foreach ( $arrIdsTriagem as $k => $v ) {
            $arrRetorno[$v] = false;
            $objMdUtlTriagemAtvDTO = new MdUtlRelTriagemAtvDTO();
            $objMdUtlTriagemAtvRN  = new MdUtlRelTriagemAtvRN();

            $objMdUtlTriagemAtvDTO->setNumIdMdUtlTriagem( $v );
            $objMdUtlTriagemAtvDTO->retNumPrazoExecucaoAtividade();

            $listTriagAtv = $objMdUtlTriagemAtvRN->listar( $objMdUtlTriagemAtvDTO );
            foreach ( $listTriagAtv as $k1 => $v1 ) {
                if ( ! empty( $v1->getNumPrazoExecucaoAtividade() ) ) {
                    $arrRetorno[$v] = true;
                    break;
                }
            }
        }
        return $arrRetorno;
    }
}