<?
/**
 *
 * 06/11/2018 - criado por jaqueline.cast
 *
 * Versão do Gerador de Código: 1.42.0
 */

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdUtlTriagemRN extends InfraRN
{

    public function __construct()
    {
        parent::__construct();
    }

    protected function inicializarObjInfraIBanco()
    {
        return BancoSEI::getInstance();
    }

    protected function cadastrarControlado(MdUtlTriagemDTO $objMdUtlTriagemDTO)
    {
        try {

            SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_triagem_cadastrar', __METHOD__, $objMdUtlTriagemDTO);

            $objInfraException = new InfraException();
            $objInfraException->lancarValidacoes();

            $objMdUtlTriagemBD = new MdUtlTriagemBD($this->getObjInfraIBanco());
            $ret = $objMdUtlTriagemBD->cadastrar($objMdUtlTriagemDTO);

            return $ret;

        } catch (Exception $e) {
            throw new InfraException('Erro cadastrando .', $e);
        }
    }

    protected function alterarControlado(MdUtlTriagemDTO $objMdUtlTriagemDTO)
    {
        try {

            SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_triagem_alterar', __METHOD__, $objMdUtlTriagemDTO);

            //Regras de Negocio
            $objInfraException = new InfraException();
            $objInfraException->lancarValidacoes();

            $objMdUtlTriagemBD = new MdUtlTriagemBD($this->getObjInfraIBanco());
            $objMdUtlTriagemBD->alterar($objMdUtlTriagemDTO);

        } catch (Exception $e) {
            throw new InfraException('Erro alterando .', $e);
        }
    }

    protected function excluirControlado($arrObjMdUtlTriagemDTO)
    {
        try {

            SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_triagem_excluir', __METHOD__, $arrObjMdUtlTriagemDTO);

            $objMdUtlTriagemBD = new MdUtlTriagemBD($this->getObjInfraIBanco());
            for ($i = 0; $i < count($arrObjMdUtlTriagemDTO); $i++) {
                $objMdUtlTriagemBD->excluir($arrObjMdUtlTriagemDTO[$i]);
            }

        } catch (Exception $e) {
            throw new InfraException('Erro excluindo .', $e);
        }
    }

    protected function consultarConectado(MdUtlTriagemDTO $objMdUtlTriagemDTO)
    {
        try {

            SessaoSEI::getInstance()->validarPermissao('md_utl_triagem_consultar');

            $objMdUtlTriagemBD = new MdUtlTriagemBD($this->getObjInfraIBanco());
            $ret = $objMdUtlTriagemBD->consultar($objMdUtlTriagemDTO);
            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro consultando .', $e);
        }
    }

    protected function listarConectado(MdUtlTriagemDTO $objMdUtlTriagemDTO)
    {
        try {

            SessaoSEI::getInstance()->validarPermissao('md_utl_triagem_listar');

            $objMdUtlTriagemBD = new MdUtlTriagemBD($this->getObjInfraIBanco());
            $ret = $objMdUtlTriagemBD->listar($objMdUtlTriagemDTO);

            return $ret;

        } catch (Exception $e) {
            throw new InfraException('Erro listando .', $e);
        }
    }

    protected function contarConectado(MdUtlTriagemDTO $objMdUtlTriagemDTO)
    {
        try {

            SessaoSEI::getInstance()->validarPermissao('md_utl_triagem_listar');

            $objMdUtlTriagemBD = new MdUtlTriagemBD($this->getObjInfraIBanco());
            $ret = $objMdUtlTriagemBD->contar($objMdUtlTriagemDTO);

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro contando .', $e);
        }
    }

    protected function desativarControlado($arrObjMdUtlTriagemDTO)
    {
        try {
            SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_triagem_desativar', __METHOD__, $arrObjMdUtlTriagemDTO);

            $objMdUtlTriagemBD = new MdUtlTriagemBD($this->getObjInfraIBanco());
            for ($i = 0; $i < count($arrObjMdUtlTriagemDTO); $i++) {

                $objMdUtlTriagemBD->desativar($arrObjMdUtlTriagemDTO[$i]);
            }

        } catch (Exception $e) {
            throw new InfraException('Erro desativando .', $e);
        }
    }

    protected function reativarControlado($arrObjMdUtlTriagemDTO)
    {
        try {

            SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_triagem_reativar', __METHOD__, $arrObjMdUtlTriagemDTO);

            $objMdUtlTriagemBD = new MdUtlTriagemBD($this->getObjInfraIBanco());
            for ($i = 0; $i < count($arrObjMdUtlTriagemDTO); $i++) {
                $objMdUtlTriagemBD->reativar($arrObjMdUtlTriagemDTO[$i]);
            }

        } catch (Exception $e) {
            throw new InfraException('Erro reativando .', $e);
        }
    }

    private function _retornaDetalheTriagem()
    {
        $arrAtividades = PaginaSEI::getInstance()->getArrItensTabelaDinamica($_POST['hdnTbAtividade']);
        $strDetalheAtividade = '';

        foreach ($arrAtividades as $key => $dadoAtv) {
            if ($key != 0) {
                $strDetalheAtividade .= ', ';
            }

            $strDetalheAtividade .= array_key_exists(2, $dadoAtv) ? $dadoAtv[2] : '';
        }

        return $strDetalheAtividade;
    }

    protected function cadastrarDadosTriagemControlado($dados)
    {
        try {
            $objMdUtlControleDsmpRN = new MdUtlControleDsmpRN();
            //$objMdUtlControleDsmpDTO = new MdUtlControleDsmpDTO();
            $objRelAtvTriagemRN = new MdUtlRelTriagemAtvRN();
            $objMdUtlFilaPrmUsuRN = new MdUtlAdmFilaPrmGrUsuRN;
            $objHistoricoRN = new MdUtlHistControleDsmpRN();
            $objMdUtlAdmPrmGrRN = new MdUtlAdmPrmGrRN();
            $objMdUtlAdmPrmGrUsuRN = new MdUtlAdmPrmGrUsuRN();

            $isPossuiAnalise = $dados['hdnIsPossuiAnalise'] == 'S';
            $idProcedimento = $dados['hdnIdProcedimento'];
            $isRetriagem = $dados['hdnIdRetriagem'];
            $isRtgAnlCorrecao = $dados['hdnIdRtgAnlCorrecao'];
            $isAlterar = $_GET['acao'] == 'md_utl_triagem_alterar';
            $isHabilitar = false;
            $isChefiaImediata = false;

            $objControleDsmpDTO = $objMdUtlControleDsmpRN->getObjControleDsmpAtivo($idProcedimento);
            $arrParams = [[$objControleDsmpDTO->getNumIdMdUtlAdmPrmGr()], SessaoSEI::getInstance()->getNumIdUsuario()];
            if (!empty($objMdUtlAdmPrmGrUsuRN->validaUsuarioIsChefiaImediata($arrParams))) {
                $isChefiaImediata = true;
                $vlrUndEsf = 0;
            }

            $dados['isChefiaImediata'] = $isChefiaImediata;

            $isTpProcParametrizado = $objMdUtlAdmPrmGrRN->verificaTipoProcessoParametrizado(array($objControleDsmpDTO->getNumIdTpProcedimento(), $objControleDsmpDTO->getNumIdMdUtlAdmTpCtrlDesemp()));
            $idFila = $objControleDsmpDTO->getNumIdMdUtlAdmFila();
            $vlrUndEsf = null;
            if (!$isRetriagem) {
                $vlrUndEsf = empty($objControleDsmpDTO->getNumTempoExecucao()) ? null : $objControleDsmpDTO->getNumTempoExecucao();
            }

            $objTriagem = $this->_salvaObjTriagem($dados, $dados['hdnIsPossuiAnalise'], $isTpProcParametrizado, $vlrUndEsf);
            $idTriagem  = $objTriagem->getNumIdMdUtlTriagem();
            $arrObjs    = $objRelAtvTriagemRN->cadastrarObjsTriagem(array($dados, $objTriagem));

            // se veio da Tela de Analise e não foram todas as atividades analisadas, ocorre retriagem automatica, ocorrendo a necessidade
	          // de atualizar o idRelTriagem que esta no $_POST ( variavel $dados )
	          if ( $isRetriagem && (isset($dados['isOrigemTelaAnalise']) && $dados['isOrigemTelaAnalise'] === true ) ) {
		          $arrItens = explode( ',' , $dados['hdnItensSelecionados'] );
	          	foreach ( $arrItens as $itemSelecionado ) {
			          foreach ( $arrObjs['itensRelTriagemParaAtualizar'] as $itemRelTriag ) {
				          if ( (int) $_POST['idRelTriagem_'.$itemSelecionado] == (int) $itemRelTriag['idRelTriagAtv'] ) {
					          $dados['idRelTriagem_'.$itemSelecionado] = $itemRelTriag['novoIdRelTriagAtv'];
					          $_POST['idRelTriagem_'.$itemSelecionado] = $itemRelTriag['novoIdRelTriagAtv'];
					          $_POST['txtDtAnaliseAtividade'.$itemRelTriag['novoIdRelTriagAtv']] = $_POST['txtDtAnaliseAtividade'.$itemRelTriag['idRelTriagAtv']];
				          }
		            }
		          }
	          }

            $arrObjsAtuais = $objMdUtlControleDsmpRN->getObjsAtivosPorProcedimento(array($idProcedimento));
            $tipoRevisao = $objMdUtlFilaPrmUsuRN->getPercentualTriagemAnalisePorFila($idFila);

            $vlEncaminhamento = $dados['selEncaminhamentoTriagem'];
            $isHabilitar = $tipoRevisao == MdUtlAdmFilaRN::$POR_ATIVIDADE ? $this->verificaHabilitarAtvParaRevisao($idTriagem) : false;
            $novoStatus = $this->_retornaProximoStatus($isPossuiAnalise, $tipoRevisao, $isHabilitar);
            $arrIdsProcedimentos = array($idProcedimento);
            $isProcessoConcluido = 0;

            if (!is_null($arrObjsAtuais)) {
                $isAcaoConcluida = $isRetriagem == 1 ? 'N' : 'S';
                $arrRetorno = $objHistoricoRN->controlarHistoricoDesempenho(array($arrObjsAtuais, array($idProcedimento), 'N', 'S', $isAcaoConcluida));

                if ($isRetriagem == 1 && !$isPossuiAnalise) {

                    if ($novoStatus == MdUtlControleDsmpRN::$AGUARDANDO_REVISAO) {
                        if ($isRtgAnlCorrecao == 1) {
                            $arrDados = array($arrIdsProcedimentos, MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE);
                        } else {
                            $arrDados = array($arrIdsProcedimentos, MdUtlControleDsmpRN::$EM_ANALISE);
                        }
                    } else {
                        $arrDados = array($arrIdsProcedimentos, MdUtlControleDsmpRN::$FLUXO_FINALIZADO);
                    }

                    $objMdUtlControleDsmpRN->controlarAjustePrazo($arrDados);
                }


                $isStatusAlteracao = !$isRetriagem && $isAlterar;
                $isStatusRegMudanca = $isRetriagem == 1 && !$isPossuiAnalise;


                if ($isStatusAlteracao || $isStatusRegMudanca) {
                    $arrDados = array($arrIdsProcedimentos, $novoStatus);
                    $objMdUtlControleDsmpRN->controlarContestacao($arrDados);
                    $arrRetorno[$idProcedimento]['ID_CONTESTACAO'] = null;
                }


                $strDetalhe = $this->_retornaDetalheTriagem();

                if ($isRetriagem == 1 && $isPossuiAnalise && $objControleDsmpDTO->getStrStaAtendimentoDsmp() == "15") {
                    $rascunho = 1;
                    $objControleDsmpDTOAntigo = $objMdUtlControleDsmpRN->getObjControleDsmpAtivo($idProcedimento);

                    $arrAtividades = PaginaSEI::getInstance()->getArrItensTabelaDinamica($dados['hdnTbAtividade']);
                    foreach ($arrAtividades as $atividade) {
                        $arrayidsAtividades[] = $atividade[1];
                    }

                    $objRelAnaliseProdutoRN = new MdUtlRelAnaliseProdutoRN();
                    $objRelAnaliseProdutoDTO = new MdUtlRelAnaliseProdutoDTO();
                    $objRelAnaliseProdutoDTO->setNumIdMdUtlAnalise($objControleDsmpDTOAntigo->getNumIdMdUtlAnalise());
                    $objRelAnaliseProdutoDTO->retNumIdMdUtlRelAnaliseProduto();
                    $objRelAnaliseProdutoDTO->retNumIdMdUtlAdmAtividade();
                    $arrObjRelAnaliseProdutoDTO = $objRelAnaliseProdutoRN->listar($objRelAnaliseProdutoDTO);
                    foreach ($arrObjRelAnaliseProdutoDTO as $atividadeAnalise) {
                        if (!in_array($atividadeAnalise->getNumIdMdUtlAdmAtividade(), $arrayidsAtividades)) {
                            $objRelAnaliseProdutoDTO = new MdUtlRelAnaliseProdutoDTO();
                            $objRelAnaliseProdutoDTO->setNumIdMdUtlRelAnaliseProduto($atividadeAnalise->getNumIdMdUtlRelAnaliseProduto());
                            $objRelAnaliseProdutoRN->excluir(array($objRelAnaliseProdutoDTO));
                        }
                    }
                }

                $objMdUtlControleDsmpRN->excluir($arrObjsAtuais);

                if ($novoStatus == MdUtlControleDsmpRN::$AGUARDANDO_REVISAO) {
                    $this->_continuarFluxoAtendimento($objTriagem, $dados, $isPossuiAnalise, $idFila, $idProcedimento, $arrRetorno, $strDetalhe, $rascunho, $objControleDsmpDTOAntigo);
                } else {
                    $isProcessoConcluido = $this->_concluirFluxoTriagem($idProcedimento, $arrRetorno, $objTriagem, $strDetalhe, $vlEncaminhamento, $objControleDsmpDTO, $dados);
                }

                if (!$isRetriagem) {
                    $objRNGerais = new MdUtlRegrasGeraisRN();
                    $idUsuarioAtb = $arrRetorno[$idProcedimento]['ID_USUARIO_ATRIBUICAO'];
                    $objRNGerais->controlarAtribuicao($idProcedimento, $idUsuarioAtb);
                }

                $this->_atualizaObjTriagem($idProcedimento, $objTriagem, $dados['hdnIdTpCtrl'], $isChefiaImediata);
                if ($isRetriagem == 1 && !$isPossuiAnalise) {
                    if ($dados['selUsuarioResponsavelAvaliacao'] != "") {
                        $_POST["hdnTbProcesso"] = $idProcedimento;
                        $_POST["hdnIdUsuarioParticipanteLupa"] = $dados['selUsuarioResponsavelAvaliacao'];
                        $_POST["hdnIdTipoControleUtl"] = $dados["hdnIdTpCtrl"];
                        $_POST["hdnIdFila"] = $dados["hdnIdFilaAtiva"];
                        $_POST["hdnSelParticipante"] = $dados["hdnNomeMembroResponsavelAvaliacao"];
                        $_POST["hdnIsTelaProcesso"] = 1;

                        $objMdUtlControleDsmpRN->incluirNovaDistribuicao("5");
                    }
                }
            }

        } catch (Exception $e) {
            throw new InfraException('Erro cadastrando a Triagem .', $e);
        }

        return $isProcessoConcluido;

    }

    private function _retornaProximoStatus($isPossuiAnalise, $tipoRevisao, $isHabilitar)
    {
        $novoStatus = MdUtlControleDsmpRN::$AGUARDANDO_REVISAO;

        if (!$isPossuiAnalise) {
            if (($tipoRevisao == MdUtlAdmFilaRN::$POR_ATIVIDADE && !$isHabilitar) || $tipoRevisao == MdUtlAdmFilaRN::$SEM_REVISAO) {
                $novoStatus = MdUtlControleDsmpRN::$FLUXO_FINALIZADO;
            }
        }

        return $novoStatus;
    }

    protected function verificaHabilitarAtvParaRevisaoConectado($idTriagem)
    {


        $objMdUtlRelTriagemAtvRN = new MdUtlRelTriagemAtvRN();
        $objMdUtlRelTriagemAtvDTO = new MdUtlRelTriagemAtvDTO();
        $objMdUtlRelTriagemAtvDTO->setNumIdMdUtlTriagem($idTriagem);
        $objMdUtlRelTriagemAtvDTO->setStrSinAtvRevAmostragem('S');
        $objMdUtlRelTriagemAtvDTO->retNumIdMdUtlAdmAtividade();

        $count = $objMdUtlRelTriagemAtvRN->contar($objMdUtlRelTriagemAtvDTO);

        return $count > 0;
    }

    private function _concluirFluxoTriagem($idProcedimento, $arrRetorno, $objTriagem, $strDetalhe, $vlEncaminhamento, $objControleDsmpDTO, $dados)
    {
        $objHistoricoRN = new MdUtlHistControleDsmpRN();
        $objMdUtlControleDsmpRN = new MdUtlControleDsmpRN();

        $tipoConclusao = MdUtlControleDsmpRN::$CONCLUIR_TRIAGEM;
        if ($objControleDsmpDTO->getStrStaAtendimentoDsmp() == MdUtlControleDsmpRN::$EM_ANALISE || $objControleDsmpDTO->getStrStaAtendimentoDsmp() == MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE) {
            $tipoConclusao = MdUtlControleDsmpRN::$CONCLUIR_RETRIAGEM;
        }

        $objHistoricoRN->concluirControleDsmp(array($idProcedimento, $arrRetorno, $tipoConclusao, $objTriagem->getNumIdMdUtlTriagem(), $strDetalhe));

        if ($vlEncaminhamento == MdUtlControleDsmpRN::$ENC_ASSOCIAR_EM_FILA) {
            $idTipoControle = $objControleDsmpDTO->getNumIdMdUtlAdmTpCtrlDesemp();
            $idNovaFila = $dados['selFila'];
            $objMdUtlControleDsmpRN->associarFilaAnaliseTriagem(array($idProcedimento, $idNovaFila, $idTipoControle, MdUtlControleDsmpRN::$STR_TIPO_ACAO_ASSOCIACAO));

            if (isset($_POST['ckbDistAutoParaMim'])) {
                $objMdUtlControleDsmpRN->distrAutoAposFinalizar();
            }
        } else {
            return 1;
        }

        return 0;
    }

    private function _continuarFluxoAtendimento($objTriagem, $dados, $isPossuiAnalise, $idFila, $idProcedimento, $arrRetorno, $strDetalhe, $rascunho = NULL, $objControleDsmpDTOAntigo)
    {
        $objMdUtlControleDsmpRN = new MdUtlControleDsmpRN();
        $objMdUtlAdmPrmGrUsuRN = new MdUtlAdmPrmGrUsuRN();
        $idTpCtrl = $dados['hdnIdTpCtrl'];

        $isCorrecaoTriagem = array_key_exists('isCorrecaoTriagem', $dados) ? $dados['isCorrecaoTriagem'] : false;
        $strNovoStatus = !$isPossuiAnalise ? MdUtlControleDsmpRN::$AGUARDANDO_REVISAO : MdUtlControleDsmpRN::$AGUARDANDO_ANALISE;
        $isRetriagem = array_key_exists('hdnIdRetriagem', $dados) && $dados['hdnIdRetriagem'] == 1 ? $dados['hdnIdRetriagem'] : false;
        $isRtgAnlCorrecao = array_key_exists('hdnIdRtgAnlCorrecao', $dados) && $dados['hdnIdRtgAnlCorrecao'] == 1 ? $dados['hdnIdRtgAnlCorrecao'] : false;
        $idRevisao = $arrRetorno[$idProcedimento]['ID_REVISAO'];
        $idAjusTarefa = $arrRetorno[$idProcedimento]['ID_AJUST_PRAZO'];

        if ($isCorrecaoTriagem || $isRetriagem) {
            $idTriagem = $arrRetorno[$idProcedimento]['ID_TRIAGEM'];
            if (!is_null($idTriagem)) {
                $this->desativarPorIds(array($idTriagem));
            }
        }

        $arrParams = array();
        $arrParams['dblIdProcedimento'] = $idProcedimento;
        $arrParams['intIdFila'] = $idFila;
        $arrParams['intIdTpCtrl'] = $idTpCtrl;
        $arrParams['strStatus'] = $strNovoStatus;
        $arrParams['intTempoExecucao'] = $dados['hdnTmpExecucao'];
        $arrParams['idTriagem'] = $objTriagem->getNumIdMdUtlTriagem();

        $arrParams['idRevisao'] = $idRevisao;
        $arrParams['strDetalhe'] = $strDetalhe;
        $arrParams['idContestacao'] = $arrRetorno[$idProcedimento]['ID_CONTESTACAO'];

        $idPrmGr = (new MdUtlAdmTpCtrlDesempRN())->_getIdsParamsTpControle([$idTpCtrl])[0];
        $parametrosConsulta = [[$idPrmGr], SessaoSEI::getInstance()->getNumIdUsuario()];
        if (!empty($objMdUtlAdmPrmGrUsuRN->validaUsuarioIsChefiaImediata($parametrosConsulta))) $isChefiaImediata = true;
        $arrParams['isChefiaImediata'] = $isChefiaImediata;

        if ($isRetriagem) {
            $idUsuarioDistrib = $isPossuiAnalise ? SessaoSEI::getInstance()->getNumIdUsuario() : null;
            $strNovoStatus = $isPossuiAnalise ? MdUtlControleDsmpRN::$EM_ANALISE : MdUtlControleDsmpRN::$AGUARDANDO_REVISAO;
            $dthPrazoTarefa = null;

            if ($isPossuiAnalise) {
                $dthPrazoTarefa = $arrRetorno[$idProcedimento]['DTH_PRAZO_TAREFA'];
            }

            if ($isRtgAnlCorrecao) {
                $strNovoStatus = $isPossuiAnalise ? MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE : MdUtlControleDsmpRN::$AGUARDANDO_REVISAO;
            }

            $idAnalise = $strNovoStatus == MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE ? $arrRetorno[$idProcedimento]['ID_ANALISE'] : null;
            if ($rascunho != 1) {
                $arrParams['strStatus'] = $strNovoStatus;
                $arrParams['tipoAcao'] = MdUtlControleDsmpRN::$STR_TIPO_ACAO_RETRIAGEM;
                $arrParams['idAnalise'] = $idAnalise;
            } else {
                $arrParams['strStatus'] = "15";
                $arrParams['tipoAcao'] = MdUtlControleDsmpRN::$STR_RASCUNHO_ANALISE;
                $arrParams['objControleDsmpDTOAntigo'] = $objControleDsmpDTOAntigo;
                $arrParams['idAnalise'] = $objControleDsmpDTOAntigo->getNumIdMdUtlAnalise();
                $arrParams['triagem'] = 1;
            }
            $arrParams['idUsuarioDistrib'] = $idUsuarioDistrib;
            $arrParams['idAjustePrazo'] = $idAjusTarefa;
            $arrParams['dtPrazo'] = $dthPrazoTarefa;
            $arrParams['dtHora'] = InfraData::getStrDataHoraAtual();
        } else {
            $arrParams['tipoAcao'] = MdUtlControleDsmpRN::$STR_TIPO_ACAO_TRIAGEM;
        }

        $objMdUtlControleDsmpRN->cadastrarNovaSituacaoProcesso($arrParams);

        return true;
    }

    private function _salvaObjTriagem($dados, $strSinAnalise, $isTpProcParametrizado, $vlrUndEsforco)
    {
        $isSemAnalise = $strSinAnalise == 'N';

        $objMdUtlTriagemDTO = new MdUtlTriagemDTO();
        $objMdUtlTriagemDTO->setNumIdMdUtlTriagem(null);
        $objMdUtlTriagemDTO->setDthPrazoResposta($dados['txtPrazoResposta']);
        $objMdUtlTriagemDTO->setStrInformacaoComplementar($dados['txaInformacaoComplementar']);
        $objMdUtlTriagemDTO->setStrSinAtivo('S');
        $objMdUtlTriagemDTO->setStrSinPossuiAnalise($strSinAnalise);
        $objMdUtlTriagemDTO->setDthAtual(InfraData::getStrDataHoraAtual());
        $objMdUtlTriagemDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
        $objMdUtlTriagemDTO->setNumTempoExecucao($vlrUndEsforco);

        if ($isSemAnalise && $isTpProcParametrizado) {
            $objMdUtlTriagemDTO->setStrStaEncaminhamentoTriagem($dados['selEncaminhamentoTriagem']);

            if ($dados['selEncaminhamentoTriagem'] == MdUtlControleDsmpRN::$ENC_ASSOCIAR_EM_FILA) {
                $objMdUtlTriagemDTO->setNumIdMdUtlAdmFila($dados['selFila']);
            }
        }

        if (isset($dados['ckbDistAutoParaMim'])) $objMdUtlTriagemDTO->setStrDistAutoParaMim($dados['ckbDistAutoParaMim']);
        else $objMdUtlTriagemDTO->setStrDistAutoParaMim(null);

        return $this->cadastrar($objMdUtlTriagemDTO);
    }

    private function _atualizaObjTriagem($idProcedimento, $objMdUtlTriagemDTO, $idTpCtrl, $isChefiaImediata)
    {

        $regrasGerais = new MdUtlRegrasGeraisRN();
        $objTodosHistDesmp = $regrasGerais->recuperarObjHistorico($idProcedimento);
        $arrParams = $regrasGerais->regraAcaoTriagem($objTodosHistDesmp, $idProcedimento, $isChefiaImediata);
        $arrDadosPercentualDesempenho = MdUtlAdmPrmGrUsuINT::retornaDadosPercentualDesempenho($arrParams['tempoExecucao'] ? $arrParams['tempoExecucao'] : 0, $idTpCtrl, $objMdUtlTriagemDTO->getNumIdUsuario());

        $objMdUtlTriagemDTO->setDthInicio($arrParams['dataInicio'] ? $arrParams['dataInicio'] : '');
        $objMdUtlTriagemDTO->setDthPrazo($arrParams['dataPrazo'] ? $arrParams['dataPrazo'] : '');
        $objMdUtlTriagemDTO->setNumTempoExecucao(isset($arrParams['tempoExecucao']) ? $arrParams['tempoExecucao'] : '');
        $objMdUtlTriagemDTO->setStrStaTipoPresenca($arrDadosPercentualDesempenho['strStaTipoPresenca']);
        $objMdUtlTriagemDTO->setNumTempoExecucaoAtribuido($arrParams['tempoExecucaoAtribuido']);
        $objMdUtlTriagemDTO->setNumPercentualDesempenho($arrDadosPercentualDesempenho['numPercentualDesempenho']);

        return $this->alterar($objMdUtlTriagemDTO);
    }

    protected function getIdsAtividadesTriagemConectado($idTriagem)
    {
        $idsAtividade = array();
        $objMdUtlRelTriagemAtvRN = new MdUtlRelTriagemAtvRN();
        $objMdUtlRelTriagemAtvDTO = new MdUtlRelTriagemAtvDTO();
        $objMdUtlRelTriagemAtvDTO->setNumIdMdUtlTriagem($idTriagem);
        $objMdUtlRelTriagemAtvDTO->retNumIdMdUtlAdmAtividade();

        $countIds = $objMdUtlRelTriagemAtvRN->contar($objMdUtlRelTriagemAtvDTO);

        if ($countIds > 0) {
            $idsAtividade = InfraArray::converterArrInfraDTO($objMdUtlRelTriagemAtvRN->listar($objMdUtlRelTriagemAtvDTO), 'IdMdUtlAdmAtividade');
        }

        return $idsAtividade;
    }

    protected function retornaArrVinculosAtividadeTriagemConectado($idsAtividade)
    {
        $arrRetorno = array();
        $objRelTriagemAtvRN = new MdUtlRelTriagemAtvRN();
        $objRelTriagemAtvDTO = new MdUtlRelTriagemAtvDTO();
        $objRelTriagemAtvDTO->setNumIdMdUtlAdmAtividade($idsAtividade, InfraDTO::$OPER_IN);
        $objRelTriagemAtvDTO->setDistinct(true);
        $objRelTriagemAtvDTO->retNumIdMdUtlAdmAtividade();

        $count = $objRelTriagemAtvRN->contar($objRelTriagemAtvDTO);
        $idsAtividadeVinculado = array();

        if ($count > 0) {
            $idsAtividadeVinculado = InfraArray::converterArrInfraDTO($objRelTriagemAtvRN->listar($objRelTriagemAtvDTO), 'IdMdUtlAdmAtividade');
        }

        foreach ($idsAtividade as $idAtv) {
            $arrRetorno[$idAtv] = count($idsAtividadeVinculado) > 0 && in_array($idAtv, $idsAtividadeVinculado) ? true : false;
        }

        return $arrRetorno;

    }

    protected function verificaTiposAnaliseValidoConectado($objTriagem)
    {
        $strAnaliseInicial = '';
        $objRelTriagemRN = new MdUtlRelTriagemAtvRN();
        $objRelTriagemAtvDTO = new MdUtlRelTriagemAtvDTO();
        $objRelTriagemAtvDTO->setNumIdMdUtlTriagem($objTriagem->getNumIdMdUtlTriagem());
        $objRelTriagemAtvDTO->retStrSinAnalise();

        $count = $objRelTriagemRN->contar($objRelTriagemAtvDTO);

        if ($count > 0) {
            $arrObjs = $objRelTriagemRN->listar($objRelTriagemAtvDTO);

            foreach ($arrObjs as $obj) {
                if ($strAnaliseInicial != '' && $strAnaliseInicial != $obj->getStrSinAnalise()) {
                    return false;
                }

                $strAnaliseInicial = $obj->getStrSinAnalise();
            }

            return $strAnaliseInicial;
        }

        return false;
    }

    public function buscarObjTriagemPorIdConectado($idTriagem)
    {
        $objTriagemDTO = new MdUtlTriagemDTO();
        $objTriagemDTO->setNumIdMdUtlTriagem($idTriagem);
        $objTriagemDTO->setNumMaxRegistrosRetorno(1);
        $objTriagemDTO->retTodos();
        return $this->consultar($objTriagemDTO);
    }

    protected function desativarPorIdsConectado(array $idsTriagem)
    {
        if (count($idsTriagem) > 0) {
            $objTriagemDTO = new MdUtlTriagemDTO();
            $objTriagemDTO->setNumIdMdUtlTriagem($idsTriagem, InfraDTO::$OPER_IN);
            $objTriagemDTO->retNumIdMdUtlTriagem();
            $objTriagemDTO->setStrSinAtivo('S');
            $count = $this->contar($objTriagemDTO);
            if ($count > 0) {
                $this->desativar($this->listar($objTriagemDTO));
            }
        }
    }

    protected function getObjDTOAnaliseConectado($idTriagem)
    {
        $objRelTriagemAtvDTO = new MdUtlRelTriagemAtvDTO();

        $objRelTriagemAtvDTO->setNumIdMdUtlTriagem($idTriagem);
        $objRelTriagemAtvDTO->retTodos();
        $objRelTriagemAtvDTO->retNumIdMdUtlAdmAtvSerieProd();
        $objRelTriagemAtvDTO->retNumTempoExecucaoProduto();
        $objRelTriagemAtvDTO->retStrSinObrigatorio();
        $objRelTriagemAtvDTO->retNumComplexidadeAtividade();
        $objRelTriagemAtvDTO->retStrNomeSerie();
        $objRelTriagemAtvDTO->retStrNomeAtividade();
        $objRelTriagemAtvDTO->retStrNomeProduto();
        $objRelTriagemAtvDTO->retNumIdMdUtlAdmTpProduto();
        $objRelTriagemAtvDTO->retNumIdSerieRel();
        $objRelTriagemAtvDTO->retStrSinNaoAplicarPercDsmpAtv();
        $objRelTriagemAtvDTO->retNumTempoExecucaoAtribuido();
        $objRelTriagemAtvDTO->retDtaDataExecucao();
        $objRelTriagemAtvDTO->retStrSinAtvRevAmostragem();

	      $objRelTriagemAtvDTO->setOrd('DataExecucao',InfraDTO::$TIPO_ORDENACAO_ASC);
	      $objRelTriagemAtvDTO->setOrd('IdMdUtlRelTriagemAtv',InfraDTO::$TIPO_ORDENACAO_ASC);
	      $objRelTriagemAtvDTO->setOrd('NomeAtividade',InfraDTO::$TIPO_ORDENACAO_ASC);
	      $objRelTriagemAtvDTO->setOrd('NomeProduto',InfraDTO::$TIPO_ORDENACAO_ASC);
	      $objRelTriagemAtvDTO->setOrd('NomeSerie',InfraDTO::$TIPO_ORDENACAO_ASC);

        return $objRelTriagemAtvDTO;
    }

    public function getObjDTOAnaliseAtv($idTriagem)
    {
        $objRelTriagemAtvDTO = new MdUtlRelTriagemAtvDTO();

        $objRelTriagemAtvDTO->setNumIdMdUtlTriagem($idTriagem);
        $objRelTriagemAtvDTO->retTodos();
        $objRelTriagemAtvDTO->retStrNomeAtividade();
        $objRelTriagemAtvDTO->retStrSinNaoAplicarPercDsmpAtv();

        return $objRelTriagemAtvDTO;
    }

    protected function getNumPrazoAtividadePorTriagemConectado($idTriagem)
    {
        $objRelTriagemAtvRN = new MdUtlRelTriagemAtvRN();
        $objRelTriagemAtvDTO = new MdUtlRelTriagemAtvDTO();
        $objRelTriagemAtvDTO->setNumIdMdUtlTriagem($idTriagem);
        $objRelTriagemAtvDTO->retNumPrazoExecucaoAtividade();

        $arrObjRelTriagemAtvDTO = $objRelTriagemAtvRN->listar($objRelTriagemAtvDTO);

        $isMaior = 0;
        foreach ($arrObjRelTriagemAtvDTO as $objDTO) {
            if ($objDTO->getNumPrazoExecucaoAtividade() > $isMaior) {
                $isMaior = $objDTO->getNumPrazoExecucaoAtividade();
            }
        }

        return $isMaior;
    }

    protected function getNumPrazoAtividadePorTriagemParaRevConectado($idTriagem)
    {
        $objRelTriagemAtvRN = new MdUtlRelTriagemAtvRN();
        $objRelTriagemAtvDTO = new MdUtlRelTriagemAtvDTO();
        $objRelTriagemAtvDTO->setNumIdMdUtlTriagem($idTriagem);
        $objRelTriagemAtvDTO->retNumPrazoRevisaoAtividade();

        $arrObjRelTriagemAtvDTO = $objRelTriagemAtvRN->listar($objRelTriagemAtvDTO);

        $isMaior = 0;

        foreach ($arrObjRelTriagemAtvDTO as $objDTO) {
            if ($objDTO->getNumPrazoRevisaoAtividade() > $isMaior) {
                $isMaior = $objDTO->getNumPrazoRevisaoAtividade();
            }
        }

        return $isMaior;
    }

    protected function validaPrazoMaximoDiasJustificativaConectado($arrParams)
    {
        $qtdDias = array_key_exists(0, $arrParams) ? $arrParams[0] : null;
        $idControleDsp = array_key_exists(1, $arrParams) ? $arrParams[1] : null;
        $prazo = 0;

        $objMdUtlControleDsmpRN = new MdUtlControleDsmpRN();

        $objControleDsmpDTO = $objMdUtlControleDsmpRN->getObjControleDsmpPorId($idControleDsp);

        $idTriagem = $objControleDsmpDTO->getNumIdMdUtlTriagem();

        $arrStatusAnalise = array(MdUtlControleDsmpRN::$EM_ANALISE, MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE);

        if (in_array($objControleDsmpDTO->getStrStaAtendimentoDsmp(), $arrStatusAnalise)) {
            $prazo = $this->getNumPrazoAtividadePorTriagem($idTriagem);
        } else {
            $prazo = $this->getNumPrazoAtividadePorTriagemParaRev($idTriagem);
        }

        $qtdDias = intval($qtdDias);
        $prazo = intval($prazo);

        if ($qtdDias > $prazo) {
            return false;
        }

        return true;

    }


    protected function checarDadosTriagemControlado($idUsuario)
    {

        $objMdUtlTriagemDTO = new MdUtlTriagemDTO();
        $objMdUtlTriagemDTO->adicionarCriterio(array('Atual', 'IdUsuario'), array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL), array(null, null), InfraDTO::$OPER_LOGICO_OR);
        $objMdUtlTriagemDTO->retNumIdMdUtlTriagem();
        $objRN = new MdUtlTriagemRN();
        $numRegistros = $objRN->contar($objMdUtlTriagemDTO);

        if ($numRegistros > 0) {
            $arrDadosTriagem = $objRN->listar($objMdUtlTriagemDTO);
            foreach ($arrDadosTriagem as $dadoTriagem) {
                $objMdUtlTriagemDTO = new MdUtlTriagemDTO();
                $objMdUtlTriagemDTO->setNumIdMdUtlTriagem($dadoTriagem->getNumIdMdUtlTriagem());
                $objMdUtlTriagemDTO->setDthAtual(InfraData::getStrDataHoraAtual());
                $objMdUtlTriagemDTO->setNumIdUsuario($idUsuario);
                $objRN->alterar($objMdUtlTriagemDTO);
            }
        }
    }

    public function cadastroRetriagem($objTriagemDTO, $objControleDsmpDTO)
    {
        //Desativar Relacionamentos
        $this->desativar(array($objTriagemDTO));

        $objRevisaoRN = new MdUtlRevisaoRN();
        $idRevisao = $objControleDsmpDTO->getNumIdMdUtlRevisao();
        $objRevisaoDTO = $objRevisaoRN->buscarObjRevisaoPorId($idRevisao);

        if (!is_null($objRevisaoDTO)) {
            $objRevisaoRN->desativar(array($objRevisaoDTO));
        }

        $_POST['isCorrecaoTriagem'] = true;
        return $this->cadastrarDadosTriagem($_POST);
    }
}
