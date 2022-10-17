<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 09/10/2018 - criado por jhon.carvalho
 *
 * Versão do Gerador de Código: 1.41.0
 */

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlHistControleDsmpINT extends InfraINT {


    public static function formatarDataHora($dataHoraCompleta){
        $arrDataHoraCompleta = explode(' ', $dataHoraCompleta);
        $dataCompleta = $arrDataHoraCompleta[0];
        $horaCompleta = $arrDataHoraCompleta[1];
        $arrHoraCompleta =  explode(':', $horaCompleta);

        $hora = $arrHoraCompleta[0];
        $minuto = $arrHoraCompleta[1];

        return $dataCompleta.' '.$hora.':'.$minuto;
    }

    public static function getusuarioRevisor($dblIdProcedimento){

        $objMdUtlHistControleDsmpDTO = new MdUtlHistControleDsmpDTO();
        $objMdUtlHistControleDsmpDTO->setDblIdProcedimento($dblIdProcedimento);
        $objMdUtlHistControleDsmpDTO->setNumMaxRegistrosRetorno(1);
        $objMdUtlHistControleDsmpDTO->setOrdDthAtual(InfraDTO::$TIPO_ORDENACAO_DESC);
        $objMdUtlHistControleDsmpDTO->retStrSiglaUsuarioDist();
        $objMdUtlHistControleDsmpDTO->retStrNomeUsuarioDist();

        $objMdUtlHistControleDsmpRN = new MdUtlHistControleDsmpRN();
        $usuDistRevisor = $objMdUtlHistControleDsmpRN->listar($objMdUtlHistControleDsmpDTO);

        foreach ($usuDistRevisor as $obj){
            $objUsuRevisor = $obj;
        }

       return $objUsuRevisor;
    }

    public static function retornarHistoricoPorTipoDeControle($idProcedimento, $idTipoControle, $strStatusAtual, $strTitulo){

        $arrSituacao = MdUtlControleDsmpINT::retornaArrSituacoesControleDsmpCompleto();

        $arrTpAcoes = [
            MdUtlControleDsmpRN::$STR_TIPO_ACAO_TRIAGEM,
            MdUtlControleDsmpRN::$STR_TIPO_ACAO_ANALISE,
            MdUtlControleDsmpRN::$STR_TIPO_ACAO_REVISAO
        ];

        //Tabela histórico
        $objMdUtlHistControleDsmpRN = new MdUtlHistControleDsmpRN();
        $objMdUtlHistControleDsmpDTO = new MdUtlHistControleDsmpDTO();
        $objMdUtlHistControleDsmpDTO->setDblIdProcedimento($idProcedimento);

        if($idTipoControle){
            $objMdUtlHistControleDsmpDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTipoControle);
        }

        $objMdUtlHistControleDsmpDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objMdUtlHistControleDsmpDTO->retStrNomeUsuario();
        $objMdUtlHistControleDsmpDTO->retNumIdUsuarioAtual();
        $objMdUtlHistControleDsmpDTO->retStrSiglaUsuario();
        $objMdUtlHistControleDsmpDTO->retStrNomeTpControle();
        $objMdUtlHistControleDsmpDTO->retNumIdMdUtlTriagem();
        $objMdUtlHistControleDsmpDTO->retNumIdMdUtlAnalise();
        $objMdUtlHistControleDsmpDTO->retNumIdMdUtlRevisao();
        $objMdUtlHistControleDsmpDTO->retNumTempoExecucaoAtribuido();
        $objMdUtlHistControleDsmpDTO->setOrdDthAtual(InfraDTO::$TIPO_ORDENACAO_DESC);
        $objMdUtlHistControleDsmpDTO->retTodos();

        PaginaSEI::getInstance()->prepararOrdenacao($objMdUtlHistControleDsmpDTO, 'Atual', InfraDTO::$TIPO_ORDENACAO_DESC);
        PaginaSEI::getInstance()->prepararPaginacao($objMdUtlHistControleDsmpDTO, 100);

        $arrObjsMdUtlHistControleDsmpDTO = $objMdUtlHistControleDsmpRN->listar($objMdUtlHistControleDsmpDTO);
        $numRegistros = count($arrObjsMdUtlHistControleDsmpDTO);
        PaginaSEI::getInstance()->processarPaginacao($objMdUtlHistControleDsmpDTO);

        $strResultado =  '<table id="tbHistDetalhe"></table>';

        if ($numRegistros > 0) {

            $sinPrimeiroStatusHist = $arrObjsMdUtlHistControleDsmpDTO[0]->getStrStaAtendimentoDsmp();
            $sinPrimeiroStatusHist = trim($sinPrimeiroStatusHist);

            if(($strStatusAtual == MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE || $strStatusAtual == MdUtlControleDsmpRN::$EM_CORRECAO_TRIAGEM) && $sinPrimeiroStatusHist == MdUtlControleDsmpRN::$EM_REVISAO){
                $strTipoAcaoAtual = 'Avaliação';
            }

            $strResultado = '';
            $strResultado .= '<table width="99%" class="infraTable" summary="Detalhamento" id="tbHistDetalhe">';
            $strResultado .= '<caption class="infraCaption">';
            $strResultado .= PaginaSEI::getInstance()->gerarCaptionTabela($strTitulo, $numRegistros);
            $strResultado .= '</caption>';


            $strResultado .= '<tr>';
            $strResultado .= '<th class="infraTh" width="13%" style="text-align: center">Data/Hora</th>';
            $strResultado .= '<th class="infraTh" width="13%" style="text-align: center">Usuário Ação</th>';
            $strResultado .= '<th class="infraTh" width="15%" style="text-align: center">Tipo de Controle</th>';
            $strResultado .= '<th class="infraTh" width="15%" style="text-align: center">Tipo de Ação</th>';
            $strResultado .= '<th class="infraTh" width="15%" style="text-align: center">Tempo Executado</th>';
            $strResultado .= '<th class="infraTh" width="22%" style="text-align: center">Detalhe</th>';
            $strResultado .= '<th class="infraTh" width="22%" style="text-align: center">Situação</th>';
            $strResultado .= '</tr>';

            $strCssTr = '<tr class="infraTrClara">';


            for ($i = 0; $i < $numRegistros; $i++) {
                $tmpExec = null;
                if ( in_array( $arrObjsMdUtlHistControleDsmpDTO[$i]->getStrTipoAcao() , $arrTpAcoes ) ) {
                    $id_user_dist = $arrObjsMdUtlHistControleDsmpDTO[$i]->getNumIdUsuarioAtual();
                    switch ($arrObjsMdUtlHistControleDsmpDTO[$i]->getStrTipoAcao()) {
                        case MdUtlControleDsmpRN::$STR_TIPO_ACAO_TRIAGEM:
                            $tmpExec = $objMdUtlHistControleDsmpRN->getTempoExecucaoTriagem(
                                $arrObjsMdUtlHistControleDsmpDTO[$i]->getNumIdMdUtlTriagem() , $id_user_dist
                            );
                            break;

                        case MdUtlControleDsmpRN::$STR_TIPO_ACAO_ANALISE:
                            $tmpExec = $objMdUtlHistControleDsmpRN->getTempoExecucaoAnalise(
                                $arrObjsMdUtlHistControleDsmpDTO[$i]->getNumIdMdUtlAnalise() , $id_user_dist
                            );
                            break;

                        case MdUtlControleDsmpRN::$STR_TIPO_ACAO_REVISAO:
                            $tmpExec = $objMdUtlHistControleDsmpRN->getTempoExecucaoRevisao(
                                $arrObjsMdUtlHistControleDsmpDTO[$i]->getNumIdMdUtlRevisao() , $id_user_dist
                            );
                            break;
                    }
                } else if( $arrObjsMdUtlHistControleDsmpDTO[$i]->getStrTipoAcao() == MdUtlControleDsmpRN::$STR_TIPO_ACAO_DISTRIBUICAO ) {
                    $tmpExec = $arrObjsMdUtlHistControleDsmpDTO[$i]->getNumTempoExecucaoAtribuido();
                }

                $strNomeUsu          = $arrObjsMdUtlHistControleDsmpDTO[$i]->getStrNomeUsuario();
                $strStatus           = trim($arrObjsMdUtlHistControleDsmpDTO[$i]->getStrStaAtendimentoDsmp());
                $data                = $arrObjsMdUtlHistControleDsmpDTO[$i]->getDthAtual();
                $strDetalhe          = $arrObjsMdUtlHistControleDsmpDTO[$i]->getStrDetalhe();
                $strSiglaUsu         = $arrObjsMdUtlHistControleDsmpDTO[$i]->getStrSiglaUsuario();
                $strNomeTipoControle = $arrObjsMdUtlHistControleDsmpDTO[$i]->getStrNomeTpControle();
                $strTipoAcao         = $arrObjsMdUtlHistControleDsmpDTO[$i]->getStrTipoAcao();
                $dataFormatada       = MdUtlHistControleDsmpINT::formatarDataHora($data);

                $idAtendimentoAnterior = 0;
                if($i > 0){
                    $posicaoAnterior = $i - 1;
                    $idAtendimentoAnterior = $arrObjsMdUtlHistControleDsmpDTO[$posicaoAnterior]->getNumIdAtendimento();
                    if($idAtendimentoAnterior != $arrObjsMdUtlHistControleDsmpDTO[$i]->getNumIdAtendimento()){
                        $strCssTr = ($strCssTr == '<tr class="infraTrClara">') ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">';
                    }
                }

                $strResultado .= $strCssTr;
                //Linha Data/Hora
                $strResultado .= '<td class="tdDataHora" style="text-align: center">';
                $strResultado .= PaginaSEI::tratarHTML($dataFormatada);
                $strResultado .= '</td>';

                //Linha Usuário ação
                $strResultado .= '<td class="tdNomeUsuario" style="text-align: center">';
                $strResultado .=             '<a class="ancoraSigla" href="javascript:void(0);" alt="' . PaginaSEI::tratarHTML($strNomeUsu) . '" title="' . PaginaSEI::tratarHTML($strNomeUsu) . '">' . PaginaSEI::tratarHTML($strSiglaUsu) . '</a>';
                $strResultado .= '</td>';

                //Linha Tipo de controle
                $strResultado .= '<td class="tdTipoControle" style="text-align: center">';
                $strResultado .= PaginaSEI::tratarHTML($strNomeTipoControle);
                $strResultado .= '</td>';

                //Linha Tipo de ação
                $strResultado .= '<td class="tdTipoAcao" style="text-align: center">';
                $strResultado .= PaginaSEI::tratarHTML($strTipoAcao);
                $strResultado .= '</td>';

                //Tempo Executado
                $strResultado .= '<td class="tdTmpExec" style="text-align: center">';
                $strResultado .= !is_null( $tmpExec ) ? MdUtlAdmPrmGrINT::convertToHoursMins( $tmpExec ) : '-';
                $strResultado .= '</td>';

                //Linha Detalhe
                $strResultado .= '<td class="tdDetalhe" style="text-align: center">';
                $strResultado .= $strDetalhe;
                $strResultado .= '</td>';

                //Linha Fila Status
                $strResultado .= '<td class="tdStatusProcesso" style="text-align: center">';
                $strResultado .= !is_null($strStatus) ? PaginaSEI::tratarHTML($arrSituacao[$strStatus]) : PaginaSEI::tratarHTML($arrSituacao[0]);
                $strResultado .= '</td>';

                $strResultado .= '</tr>';
            }
            $strResultado .= '</table>';
        }
        return  $strResultado;
    }

    public static function retornarQuantidadeRegistroHistorico($idProcedimento){
        //Tabela histórico
        $objMdUtlHistControleDsmpRN = new MdUtlHistControleDsmpRN();
        $objMdUtlHistControleDsmpDTO = new MdUtlHistControleDsmpDTO();
        $objMdUtlHistControleDsmpDTO->setDblIdProcedimento($idProcedimento);
        $objMdUtlHistControleDsmpDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objMdUtlHistControleDsmpDTO->retTodos();

        $arrObjsMdUtlHistControleDsmpDTO = $objMdUtlHistControleDsmpRN->listar($objMdUtlHistControleDsmpDTO);
        return count($arrObjsMdUtlHistControleDsmpDTO);
    }

    public static function recuperarEncaminhamentoProcessoParaRevisao($idProcedimento)
    {
        //Tabela histórico
        $objMdUtlHistControleDsmpRN = new MdUtlHistControleDsmpRN();
        $objMdUtlHistControleDsmpDTO = new MdUtlHistControleDsmpDTO();
        $objMdUtlHistControleDsmpDTO->setDblIdProcedimento($idProcedimento);
        $objMdUtlHistControleDsmpDTO->setStrTipoAcao(array(MdUtlControleDsmpRN::$STR_TIPO_ACAO_TRIAGEM,MdUtlControleDsmpRN::$STR_TIPO_ACAO_RETRIAGEM, MdUtlControleDsmpRN::$STR_TIPO_ACAO_ANALISE, MdUtlControleDsmpRN::$STR_TIPO_ACAO_REVISAO), InfraDTO::$OPER_IN);
        $objMdUtlHistControleDsmpDTO->setNumMaxRegistrosRetorno(1);
        $objMdUtlHistControleDsmpDTO->setOrdNumIdMdUtlHistControleDsmp(InfraDTO::$TIPO_ORDENACAO_DESC);
        $objMdUtlHistControleDsmpDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objMdUtlHistControleDsmpDTO->retNumIdMdUtlTriagem();
        $objMdUtlHistControleDsmpDTO->retNumIdMdUtlAnalise();
        $objMdUtlHistControleDsmpDTO->retNumIdMdUtlRevisao();
        $objMdUtlHistControleDsmpDTO->retStrTipoAcao();

        $objMdUtlHistControleDsmp = $objMdUtlHistControleDsmpRN->consultar($objMdUtlHistControleDsmpDTO);

        $encaminhamento = [];

        $encaminhamentoRevisao = '';

        switch ($objMdUtlHistControleDsmp->getStrTipoAcao()){
            case MdUtlControleDsmpRN::$STR_TIPO_ACAO_TRIAGEM:
                $objMdUtlTriagemRN = new MdUtlTriagemRN();
                $objMdUtlTriagemDTO = new MdUtlTriagemDTO();
                $objMdUtlTriagemDTO->setNumIdMdUtlTriagem($objMdUtlHistControleDsmp->getNumIdMdUtlTriagem());
                $objMdUtlTriagemDTO->retStrStaEncaminhamentoTriagem();
                $objMdUtlTriagemDTO->retNumIdMdUtlAdmFila();

                $objMdUtlTriagem = $objMdUtlTriagemRN->consultar($objMdUtlTriagemDTO);
                $encaminhamento['sta_encaminhamento'] = !is_null($objMdUtlTriagem)?$objMdUtlTriagem->getStrStaEncaminhamentoTriagem():null;
                $encaminhamento['id_fila'] = !is_null($objMdUtlTriagem)?$objMdUtlTriagem->getNumIdMdUtlAdmFila():null;
                break;
            case MdUtlControleDsmpRN::$STR_TIPO_ACAO_RETRIAGEM:
                $objMdUtlTriagemRN = new MdUtlTriagemRN();
                $objMdUtlTriagemDTO = new MdUtlTriagemDTO();
                $objMdUtlTriagemDTO->setNumIdMdUtlTriagem($objMdUtlHistControleDsmp->getNumIdMdUtlTriagem());
                $objMdUtlTriagemDTO->retStrStaEncaminhamentoTriagem();
                $objMdUtlTriagemDTO->retNumIdMdUtlAdmFila();

                $objMdUtlTriagem = $objMdUtlTriagemRN->consultar($objMdUtlTriagemDTO);
                $encaminhamento['sta_encaminhamento'] = $objMdUtlTriagem->getStrStaEncaminhamentoTriagem();
                $encaminhamento['id_fila'] = $objMdUtlTriagem->getNumIdMdUtlAdmFila() ? $objMdUtlTriagem->getNumIdMdUtlAdmFila() : null;
                break;
            case MdUtlControleDsmpRN::$STR_TIPO_ACAO_ANALISE:
                $objMdUtlAnaliseRN = new MdUtlAnaliseRN();
                $objMdUtlAnaliseDTO = new MdUtlAnaliseDTO();
                $objMdUtlAnaliseDTO->setNumIdMdUtlAnalise($objMdUtlHistControleDsmp->getNumIdMdUtlAnalise());
                $objMdUtlAnaliseDTO->retStrStaEncaminhamentoAnalise();
                $objMdUtlAnaliseDTO->retNumIdMdUtlAdmFila();

                $objMdUtlAnalise = $objMdUtlAnaliseRN->consultar($objMdUtlAnaliseDTO);
                $encaminhamento['sta_encaminhamento'] = $objMdUtlAnalise->getStrStaEncaminhamentoAnalise();
                $encaminhamento['id_fila'] = $objMdUtlAnalise->getNumIdMdUtlAdmFila() ? $objMdUtlAnalise->getNumIdMdUtlAdmFila() : null;
                break;
            
            case MdUtlControleDsmpRN::$STR_TIPO_ACAO_REVISAO:
                $objMdUtlRevisaoRN = new MdUtlRevisaoRN();
                $objMdUtlRevisaoDTO = new MdUtlRevisaoDTO();
                $objMdUtlRevisaoDTO->setNumIdMdUtlRevisao($objMdUtlHistControleDsmp->getNumIdMdUtlRevisao());
                $objMdUtlRevisaoDTO->retStrStaEncaminhamentoRevisao();
                $objMdUtlRevisaoDTO->retNumIdMdUtlAdmFila();

                $objMdUtlRevisao = $objMdUtlRevisaoRN->consultar($objMdUtlRevisaoDTO);
                $encaminhamento['sta_encaminhamento'] = $objMdUtlRevisao->getStrStaEncaminhamentoRevisao();
                $encaminhamento['id_fila'] = $objMdUtlRevisao->getNumIdMdUtlAdmFila() ? $objMdUtlRevisao->getNumIdMdUtlAdmFila() : null;
                $encaminhamentoRevisao = $encaminhamento['sta_encaminhamento'];
                break;
        }
        
        switch ($encaminhamento['sta_encaminhamento']) {
            case MdUtlControleDsmpRN::$ENC_ASSOCIAR_EM_FILA:
                $encaminhamentoRevisao = MdUtlRevisaoRN::$NOVA_FILA;
                break;
            case MdUtlControleDsmpRN::$ENC_FINALIZAR_TAREFA:
                $encaminhamentoRevisao = MdUtlRevisaoRN::$FLUXO_FINALIZADO;
                break;
        }

        $encaminhamento['sta_encaminhamento'] = $encaminhamentoRevisao;

        return $encaminhamento;
    }
}
