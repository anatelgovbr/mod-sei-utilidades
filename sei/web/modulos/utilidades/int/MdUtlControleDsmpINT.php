<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 09/10/2018 - criado por jhon.carvalho
 *
 * Versão do Gerador de Código: 1.41.0
 */

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlControleDsmpINT extends InfraINT {


    public static function retornaArrSituacoesControleDsmp(){
        $arrRetorno = array();
        $arrRetorno[MdUtlControleDsmpRN::$AGUARDANDO_FILA] =  MdUtlControleDsmpRN::$STR_AGUARDANDO_FILA;
        $arrRetorno[MdUtlControleDsmpRN::$AGUARDANDO_TRIAGEM] = MdUtlControleDsmpRN::$STR_AGUARDANDO_TRIAGEM;
        $arrRetorno[MdUtlControleDsmpRN::$EM_TRIAGEM] = MdUtlControleDsmpRN::$STR_EM_TRIAGEM;
        $arrRetorno[MdUtlControleDsmpRN::$AGUARDANDO_ANALISE] = MdUtlControleDsmpRN::$STR_AGUARDANDO_ANALISE;
        $arrRetorno[MdUtlControleDsmpRN::$EM_ANALISE] = MdUtlControleDsmpRN::$STR_EM_ANALISE;
        $arrRetorno[MdUtlControleDsmpRN::$AGUARDANDO_REVISAO] = MdUtlControleDsmpRN::$STR_AGUARDANDO_REVISAO;
        $arrRetorno[MdUtlControleDsmpRN::$EM_REVISAO] = MdUtlControleDsmpRN::$STR_EM_REVISAO;
        $arrRetorno[MdUtlControleDsmpRN::$AGUARDANDO_CORRECAO_TRIAGEM] = MdUtlControleDsmpRN::$STR_AGUARDANDO_CORRECAO_TRIAGEM;
        $arrRetorno[MdUtlControleDsmpRN::$EM_CORRECAO_TRIAGEM] = MdUtlControleDsmpRN::$STR_EM_CORRECAO_TRIAGEM;
        $arrRetorno[MdUtlControleDsmpRN::$AGUARDANDO_CORRECAO_ANALISE] = MdUtlControleDsmpRN::$STR_AGUARDANDO_CORRECAO_ANALISE;
        $arrRetorno[MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE]  = MdUtlControleDsmpRN::$STR_EM_CORRECAO_ANALISE;

        return $arrRetorno;
    }

    public static function retornaArrSituacoesControleDsmpCompleto(){
        $arrRetorno = array();
        $arrRetorno[MdUtlControleDsmpRN::$AGUARDANDO_FILA] =  MdUtlControleDsmpRN::$STR_AGUARDANDO_FILA;
        $arrRetorno[MdUtlControleDsmpRN::$AGUARDANDO_TRIAGEM] = MdUtlControleDsmpRN::$STR_AGUARDANDO_TRIAGEM;
        $arrRetorno[MdUtlControleDsmpRN::$EM_TRIAGEM] = MdUtlControleDsmpRN::$STR_EM_TRIAGEM;
        $arrRetorno[MdUtlControleDsmpRN::$AGUARDANDO_ANALISE] = MdUtlControleDsmpRN::$STR_AGUARDANDO_ANALISE;
        $arrRetorno[MdUtlControleDsmpRN::$EM_ANALISE] = MdUtlControleDsmpRN::$STR_EM_ANALISE;
        $arrRetorno[MdUtlControleDsmpRN::$AGUARDANDO_REVISAO] = MdUtlControleDsmpRN::$STR_AGUARDANDO_REVISAO;
        $arrRetorno[MdUtlControleDsmpRN::$EM_REVISAO] = MdUtlControleDsmpRN::$STR_EM_REVISAO;
        $arrRetorno[MdUtlControleDsmpRN::$AGUARDANDO_CORRECAO_TRIAGEM] = MdUtlControleDsmpRN::$STR_AGUARDANDO_CORRECAO_TRIAGEM;
        $arrRetorno[MdUtlControleDsmpRN::$EM_CORRECAO_TRIAGEM] = MdUtlControleDsmpRN::$STR_EM_CORRECAO_TRIAGEM;
        $arrRetorno[MdUtlControleDsmpRN::$AGUARDANDO_CORRECAO_ANALISE] = MdUtlControleDsmpRN::$STR_AGUARDANDO_CORRECAO_ANALISE;
        $arrRetorno[MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE]  = MdUtlControleDsmpRN::$STR_EM_CORRECAO_ANALISE;
        $arrRetorno[MdUtlControleDsmpRN::$FLUXO_FINALIZADO]  = MdUtlControleDsmpRN::$STR_FLUXO_FINALIZADO;
        $arrRetorno[MdUtlControleDsmpRN::$REMOCAO_FILA]  = MdUtlControleDsmpRN::$STR_REMOCAO_FILA;

        return $arrRetorno;
    }

    public static function montarSelectStatus($valorSelecionado = null, $isAguardandoFila = true, $idsStatusPermitido = false){
        $arrDados = self::retornaArrSituacoesControleDsmp();

        $html = '<option value=""></option>';

        foreach ($arrDados as $key => $status) {
            $isAguardandoFilaValido =($key != MdUtlControleDsmpRN::$AGUARDANDO_FILA || $key == MdUtlControleDsmpRN::$AGUARDANDO_FILA && $isAguardandoFila);
            $isStatusValido         = $idsStatusPermitido && in_array($key, $idsStatusPermitido) || !$idsStatusPermitido;
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

    public static function montarSelectTipoProcesso($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
        $objRN = new MdUtlControleDsmpRN();
        $arrObjsDTO = $objRN->getTiposProcessoTipoControle();

        return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjsDTO, 'IdTipoProcedimento', 'NomeProcedimento');
    }

    public static function retornaXmlUltimasFilas($jsonIdsProcedimento, $idProcedimento, $isTelaDetalhe){
        $isTelaDetalhe   = $isTelaDetalhe == '1' ? true : false;
        $idsProcedimento = $isTelaDetalhe ? array($idProcedimento) : json_decode($jsonIdsProcedimento);

        $objHsRN = new MdUtlHistControleDsmpRN();

        $arrObjsUltimasFilas = $objHsRN->getUltimasFilasPorProcedimento($idsProcedimento);

        $xml = '<Dados>';
        if(!is_null($arrObjsUltimasFilas)){
            foreach($arrObjsUltimasFilas as $objUltimaFila) {
                $xml .= '<UltimaFila' . $objUltimaFila->getDblIdProcedimento() . '>';
                $xml .= htmlspecialchars($objUltimaFila->getStrNomeFila());
                $xml .= '</UltimaFila' . $objUltimaFila->getDblIdProcedimento() . '>';

                $xml .= '<ProtocoloFormatado' . $objUltimaFila->getDblIdProcedimento() . '>';
                $xml .= htmlspecialchars($objUltimaFila->getStrNomeFila());
                $xml .= '</ProtocoloFormatado' . $objUltimaFila->getDblIdProcedimento() . '>';
            }
        }



        $xml .= '</Dados>';

        return $xml;
    }

    public static function retornaArrVisualizacaoBotao($idStatus, $isPossuiAnalise, $isTipoProcessoParametrizado, $idFila){

        $objMdUtlFilaRN      = new MdUtlAdmFilaRN();
        $objRelTpCtrlUsuRN   = new MdUtlAdmRelTpCtrlDesempUsuRN();
        $isGestor = $objRelTpCtrlUsuRN->usuarioLogadoIsGestorSipSei();
        $isUsuarioPertenceFila = true;

        if(!$isGestor){
            $isUsuarioPertenceFila = $objMdUtlFilaRN->verificaUsuarioLogadoPertenceFila(array($idFila, $idStatus));
        }

        $arrVisualizacao = array();

        switch ($idStatus){
            case MdUtlControleDsmpRN::$AGUARDANDO_FILA:
                $arrVisualizacao['ASSOCIACAO']   = $isTipoProcessoParametrizado;
                $arrVisualizacao['TRIAGEM']      = false;
                $arrVisualizacao['ANALISE']      = false;
                $arrVisualizacao['REVISAO']      = false;
                $arrVisualizacao['DISTRIBUICAO'] = false;
                break;

            case MdUtlControleDsmpRN::$AGUARDANDO_TRIAGEM:
                 $arrVisualizacao['ASSOCIACAO']   = $isTipoProcessoParametrizado;
                 $arrVisualizacao['TRIAGEM']      = false;
                 $arrVisualizacao['ANALISE']      = false;
                 $arrVisualizacao['REVISAO']      = false;
                 $arrVisualizacao['DISTRIBUICAO'] = $isGestor || $isUsuarioPertenceFila;
                 break;

            case MdUtlControleDsmpRN::$EM_TRIAGEM:
                $arrVisualizacao['ASSOCIACAO']   = $isTipoProcessoParametrizado;
                $arrVisualizacao['TRIAGEM']      = true;
                $arrVisualizacao['ANALISE']      = false;
                $arrVisualizacao['REVISAO']      = false;
                $arrVisualizacao['DISTRIBUICAO'] = $isGestor;
                break;

            case MdUtlControleDsmpRN::$AGUARDANDO_ANALISE:
                $arrVisualizacao['ASSOCIACAO']   = $isTipoProcessoParametrizado;
                $arrVisualizacao['TRIAGEM']      = true;
                $arrVisualizacao['ANALISE']      = false;
                $arrVisualizacao['REVISAO']      = false;
                $arrVisualizacao['DISTRIBUICAO'] = $isGestor || $isUsuarioPertenceFila;
                break;

            case MdUtlControleDsmpRN::$EM_ANALISE:
                $arrVisualizacao['ASSOCIACAO']   = $isTipoProcessoParametrizado;
                $arrVisualizacao['TRIAGEM']      = true;
                $arrVisualizacao['ANALISE']      = true;
                $arrVisualizacao['REVISAO']      = false;
                $arrVisualizacao['DISTRIBUICAO'] = $isGestor;
                break;

            case MdUtlControleDsmpRN::$AGUARDANDO_REVISAO:
                $arrVisualizacao['ASSOCIACAO']   = false;
                $arrVisualizacao['TRIAGEM']      = true;
                $arrVisualizacao['ANALISE']      = $isPossuiAnalise;
                $arrVisualizacao['REVISAO']      = false;
                $arrVisualizacao['DISTRIBUICAO'] = $isGestor || $isUsuarioPertenceFila;
                break;

            case MdUtlControleDsmpRN::$AGUARDANDO_CORRECAO_TRIAGEM:
            case MdUtlControleDsmpRN::$AGUARDANDO_CORRECAO_ANALISE:
                $arrVisualizacao['ASSOCIACAO']   = false;
                $arrVisualizacao['TRIAGEM']      = true;
                $arrVisualizacao['ANALISE']      = $isPossuiAnalise;
                $arrVisualizacao['REVISAO']      = true;
                $arrVisualizacao['DISTRIBUICAO'] = $isGestor || $isUsuarioPertenceFila;
                break;

            case MdUtlControleDsmpRN::$EM_REVISAO:
            case MdUtlControleDsmpRN::$EM_CORRECAO_TRIAGEM:
            case MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE:
                $arrVisualizacao['ASSOCIACAO']   = false;
                $arrVisualizacao['TRIAGEM']      = true;
                $arrVisualizacao['ANALISE']      = $isPossuiAnalise;
                $arrVisualizacao['REVISAO']      = true;
                $arrVisualizacao['DISTRIBUICAO'] = $isGestor;
            break;

            default:
                $arrVisualizacao['ASSOCIACAO']   = false;
                $arrVisualizacao['TRIAGEM']      = false;
                $arrVisualizacao['ANALISE']      = false;
                $arrVisualizacao['REVISAO']      = false;
                $arrVisualizacao['DISTRIBUICAO'] = false;
                break;
        }


        return $arrVisualizacao;
    }

    public static function retornaUrlsAcessoDsmp($idStatus, $isPossuiAnalise, $idProcedimento, $idFila, $idUsuarioDsBd, $isMeusProcessos = false)
    {
        $arrUrls           = array();
        $isPermiteCadastro = ($idUsuarioDsBd == SessaoSEI::getInstance()->getNumIdUsuario());

        $strPadraoUrl = 'controlador.php?acao=$URL_COMPLETA$&acao_origem=' . $_GET['acao'] . '&id_procedimento=' . $idProcedimento.'&id_fila='.$idFila;
        $replaceUrl   = function($busca) use ($strPadraoUrl, $isPermiteCadastro, $isMeusProcessos) {
            $buscaArr = explode('_', $busca);
            $vlUnset = $buscaArr[2] == 'revisao' ? 4 : 3;
            unset($buscaArr[$vlUnset]);
            $busca = $isPermiteCadastro ? $busca : (implode("_", $buscaArr) .'_consultar');


            $novaUrl  = str_replace('$URL_COMPLETA$', $busca, $strPadraoUrl);

            if($isMeusProcessos){
                   $novaUrl .='&pg_padrao=1';
            }

            return SessaoSEI::getInstance()->assinarLink($novaUrl);
        };

        $arrUrls['TRIAGEM']      = '';
        $arrUrls['ANALISE']      =  '';
        $arrUrls['REVISAO']      =  '';

        switch ($idStatus) {
            case MdUtlControleDsmpRN::$EM_TRIAGEM:
                $arrUrls['TRIAGEM'] =  $replaceUrl('md_utl_triagem_cadastrar');
                break;
            case MdUtlControleDsmpRN::$AGUARDANDO_ANALISE:
                $arrUrls['TRIAGEM'] =  $replaceUrl('md_utl_triagem_consultar');
                break;

            case MdUtlControleDsmpRN::$EM_ANALISE:
                $arrUrls['TRIAGEM'] =  $replaceUrl('md_utl_triagem_consultar');
                $arrUrls['ANALISE'] =  $replaceUrl('md_utl_analise_cadastrar');
                break;

            case MdUtlControleDsmpRN::$AGUARDANDO_REVISAO:
                $arrUrls['TRIAGEM'] =  $replaceUrl('md_utl_triagem_consultar');
                $arrUrls['ANALISE'] =  $replaceUrl('md_utl_analise_consultar');
                break;

            case MdUtlControleDsmpRN::$EM_REVISAO:
                $arrUrls['TRIAGEM'] =  $replaceUrl('md_utl_triagem_consultar');
                $arrUrls['ANALISE'] =  $replaceUrl('md_utl_analise_consultar');
                $conc = $isPossuiAnalise ? 'md_utl_revisao_analise_cadastrar' : 'md_utl_revisao_triagem_cadastrar';
                $arrUrls['REVISAO'] = $replaceUrl($conc);
                break;

            case MdUtlControleDsmpRN::$EM_CORRECAO_TRIAGEM:
                $arrUrls['TRIAGEM'] =  $replaceUrl('md_utl_triagem_alterar');
                $arrUrls['REVISAO'] =  $replaceUrl('md_utl_revisao_triagem_consultar');
                break;

            case MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE:
                $arrUrls['TRIAGEM'] =  $replaceUrl('md_utl_triagem_consultar');
                $arrUrls['ANALISE'] =  $replaceUrl('md_utl_analise_alterar');
                $arrUrls['REVISAO'] =  $replaceUrl('md_utl_revisao_analise_consultar');
                break;

            case MdUtlControleDsmpRN::$AGUARDANDO_CORRECAO_ANALISE:
                $arrUrls['TRIAGEM'] =  $replaceUrl('md_utl_triagem_consultar');
                $arrUrls['ANALISE'] =  $replaceUrl('md_utl_analise_consultar');
                $arrUrls['REVISAO'] =  $replaceUrl('md_utl_revisao_analise_consultar');
                break;

            case MdUtlControleDsmpRN::$AGUARDANDO_CORRECAO_TRIAGEM:
                $arrUrls['TRIAGEM'] =  $replaceUrl('md_utl_triagem_consultar');
                $arrUrls['REVISAO'] =  $replaceUrl('md_utl_revisao_triagem_consultar');
                break;

        }

        return $arrUrls;
    }

    public static function getProximoStatusDistribuicao($idStatus){

        switch ($idStatus){
            case MdUtlControleDsmpRN::$AGUARDANDO_TRIAGEM:
            case MdUtlControleDsmpRN::$EM_TRIAGEM:
                $idRetorno = MdUtlControleDsmpRN::$EM_TRIAGEM;
                break;

            case MdUtlControleDsmpRN::$AGUARDANDO_ANALISE:
            case MdUtlControleDsmpRN::$EM_ANALISE:
            $idRetorno =  MdUtlControleDsmpRN::$EM_ANALISE;
                break;

            case MdUtlControleDsmpRN::$AGUARDANDO_REVISAO:
            case MdUtlControleDsmpRN::$EM_REVISAO:
                $idRetorno =  MdUtlControleDsmpRN::$EM_REVISAO;
                break;

            case MdUtlControleDsmpRN::$AGUARDANDO_CORRECAO_TRIAGEM:
            case MdUtlControleDsmpRN::$EM_CORRECAO_TRIAGEM:
                $idRetorno =  MdUtlControleDsmpRN::$EM_CORRECAO_TRIAGEM;
                break;

            case MdUtlControleDsmpRN::$AGUARDANDO_CORRECAO_ANALISE:
            case MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE:
                $idRetorno =  MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE;
                break;

            default:
                $idRetorno = null;

        }

        return $idRetorno;
    }

    public static function montarSelectEncaminhamentoAnaliseTriagem($vlSelecionado = null){
        $arrParametros = self::retornaSelectEncaminhamentoAnaliseTriagem();
        $select = '<option value=""></option>';

        foreach($arrParametros as $key => $parametros){
            $strSelected = $vlSelecionado != null && $key == $vlSelecionado ? 'selected=selected' : '';
            $select .= '<option '.$strSelected.' value="'.$key.'">'.$parametros.'</option>';
        }


        return $select;
    }

    public static function retornaSelectEncaminhamentoAnaliseTriagem(){
        $arrParametros = array();
        $arrParametros[MdUtlControleDsmpRN::$ENC_ASSOCIAR_EM_FILA] = MdUtlControleDsmpRN::$STR_ENC_ASSOCIAR_EM_FILA;
        $arrParametros[MdUtlControleDsmpRN::$ENC_FINALIZAR_TAREFA] = MdUtlControleDsmpRN::$STR_ENC_FINALIZAR_TAREFA;

        return $arrParametros;

    }

    public static function  retornaLinkStatus($arrCtrlUrls, $idStatus){

        $arrStatusTriagem = array(MdUtlControleDsmpRN::$EM_TRIAGEM, MdUtlControleDsmpRN::$EM_CORRECAO_TRIAGEM);
        $arrStatusAnalise = array(MdUtlControleDsmpRN::$EM_ANALISE, MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE);
        $arrStatusRevisao = array(MdUtlControleDsmpRN::$EM_REVISAO);

        if(in_array($idStatus, $arrStatusTriagem)){
            return $arrCtrlUrls['TRIAGEM'];
        }

        if(in_array($idStatus, $arrStatusAnalise)){
            return $arrCtrlUrls['ANALISE'];
        }

        if(in_array($idStatus, $arrStatusRevisao)){
            return $arrCtrlUrls['REVISAO'];
        }

        return '';
    }

    public static function validarTrocaTipoAtividade($idAtividade){

        $xml = '<Dados>';
        $objControleDsmpRN = new MdUtlControleDsmpRN();

        $isRelacionamentosAtivos = true;

        if($idAtividade) {
            $isRelacionamentosAtivos = $objControleDsmpRN->verificaExisteRelacionamentoAtivoAtividade($idAtividade);
        }

        $xml .= '<IsValido>';
        $xml .= $isRelacionamentosAtivos ? '0' : '1';
        $xml .= '</IsValido>';
        $xml .= '</Dados>';

        return $xml;
    }

    public  static function removeNullsTriagem($idsTriagem){
       foreach($idsTriagem as $key => $idTriagem){
           if(is_null($idTriagem)){
               unset($idsTriagem[$key]);
           }
       }
        return $idsTriagem;
    }

    public static function formatarDatasComDoisDigitos($dataFormato){
        $arrData = explode('/',$dataFormato);
        $dia = str_pad($arrData[0], 2, '0', STR_PAD_LEFT);
        $mes = str_pad($arrData[1], 2, '0', STR_PAD_LEFT);
        $ano = str_pad($arrData[2], 2, '0', STR_PAD_LEFT);

        $dataHoraCompleta = $dia.'/'.$mes.'/'.$ano.' '.$arrData[3];
        $dataHoraCompleta = trim($dataHoraCompleta);

        return $dataHoraCompleta;
    }
}
