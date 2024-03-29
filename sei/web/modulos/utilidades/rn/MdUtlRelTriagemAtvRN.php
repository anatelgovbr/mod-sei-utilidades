<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4? REGI?O
 *
 * 06/11/2018 - criado por jaqueline.cast
 *
 * Vers?o do Gerador de C?digo: 1.42.0
 */

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdUtlRelTriagemAtvRN extends InfraRN
{

    public function __construct()
    {
        parent::__construct();
    }

    protected function inicializarObjInfraIBanco()
    {
        return BancoSEI::getInstance();
    }

    protected function cadastrarControlado(MdUtlRelTriagemAtvDTO $objMdUtlRelTriagemAtvDTO)
    {
        try {

            SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_rel_triagem_atv_cadastrar', __METHOD__, $objMdUtlRelTriagemAtvDTO);

            //Regras de Negocio
            $objInfraException = new InfraException();
            $objInfraException->lancarValidacoes();

            $objMdUtlRelTriagemAtvBD = new MdUtlRelTriagemAtvBD($this->getObjInfraIBanco());
            $ret = $objMdUtlRelTriagemAtvBD->cadastrar($objMdUtlRelTriagemAtvDTO);

            return $ret;

        } catch (Exception $e) {
            throw new InfraException('Erro cadastrando .', $e);
        }
    }

    protected function alterarControlado(MdUtlRelTriagemAtvDTO $objMdUtlRelTriagemAtvDTO)
    {
        try {

            SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_rel_triagem_atv_alterar', __METHOD__, $objMdUtlRelTriagemAtvDTO);

            //Regras de Negocio
            $objInfraException = new InfraException();
            $objInfraException->lancarValidacoes();

            $objMdUtlRelTriagemAtvBD = new MdUtlRelTriagemAtvBD($this->getObjInfraIBanco());
            $objMdUtlRelTriagemAtvBD->alterar($objMdUtlRelTriagemAtvDTO);

        } catch (Exception $e) {
            throw new InfraException('Erro alterando .', $e);
        }
    }

    protected function excluirControlado($arrObjMdUtlRelTriagemAtvDTO)
    {
        try {

            SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_rel_triagem_atv_excluir', __METHOD__, $arrObjMdUtlRelTriagemAtvDTO);

            $objMdUtlRelTriagemAtvBD = new MdUtlRelTriagemAtvBD($this->getObjInfraIBanco());
            for ($i = 0; $i < count($arrObjMdUtlRelTriagemAtvDTO); $i++) {
                $objMdUtlRelTriagemAtvBD->excluir($arrObjMdUtlRelTriagemAtvDTO[$i]);
            }

        } catch (Exception $e) {
            throw new InfraException('Erro excluindo .', $e);
        }
    }

    protected function consultarConectado(MdUtlRelTriagemAtvDTO $objMdUtlRelTriagemAtvDTO)
    {
        try {

            SessaoSEI::getInstance()->validarPermissao('md_utl_rel_triagem_atv_consultar');

            $objMdUtlRelTriagemAtvBD = new MdUtlRelTriagemAtvBD($this->getObjInfraIBanco());
            $ret = $objMdUtlRelTriagemAtvBD->consultar($objMdUtlRelTriagemAtvDTO);

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro consultando .', $e);
        }
    }

    protected function listarConectado(MdUtlRelTriagemAtvDTO $objMdUtlRelTriagemAtvDTO)
    {
        try {

            SessaoSEI::getInstance()->validarPermissao('md_utl_rel_triagem_atv_listar');

            $objMdUtlRelTriagemAtvBD = new MdUtlRelTriagemAtvBD($this->getObjInfraIBanco());
            $ret = $objMdUtlRelTriagemAtvBD->listar($objMdUtlRelTriagemAtvDTO);

            return $ret;

        } catch (Exception $e) {
            throw new InfraException('Erro listando .', $e);
        }
    }

    protected function contarConectado(MdUtlRelTriagemAtvDTO $objMdUtlRelTriagemAtvDTO)
    {
        try {

            SessaoSEI::getInstance()->validarPermissao('md_utl_rel_triagem_atv_listar');

            $objMdUtlRelTriagemAtvBD = new MdUtlRelTriagemAtvBD($this->getObjInfraIBanco());
            $ret = $objMdUtlRelTriagemAtvBD->contar($objMdUtlRelTriagemAtvDTO);

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro contando .', $e);
        }
    }

    protected function cadastrarObjsTriagemConectado($arrDados)
    {
        $dados         = array_key_exists('0', $arrDados) ? $arrDados[0] : null;
        $objTriagem    = array_key_exists('1', $arrDados) ? $arrDados[1] : null;
        $arrAtividades = PaginaSEI::getInstance()->getArrItensTabelaDinamica($dados['hdnTbAtividade']);

        $arrRetorno = array();
        if (count($arrAtividades) > 0 && !is_null($objTriagem)) {
            foreach ($arrAtividades as $atividade) {
            	  // retorna registro de Data de Execucao e o IdRelTriagAtv Atual, caso existem
            	  $arrDadosExtras = array_key_exists(8,$atividade) ? explode('#',$atividade[8]) : ['_'];

                $objRelTriagemAtivDTO = new MdUtlRelTriagemAtvDTO();
                $objRelTriagemAtivDTO->setNumIdMdUtlRelTriagemAtv(null);
                $objRelTriagemAtivDTO->setNumIdMdUtlTriagem($objTriagem->getNumIdMdUtlTriagem());
                $objRelTriagemAtivDTO->setNumIdMdUtlAdmAtividade($atividade[1]);
                $objRelTriagemAtivDTO->setNumTempoExecucao($atividade[6]);
                $objRelTriagemAtivDTO->setNumTempoExecucaoAtribuido($atividade[7]);
	              $objRelTriagemAtivDTO->setDtaDataExecucao($arrDadosExtras[0] != '_' ? $arrDadosExtras[0] : null);

                $isChefiaImediata = $dados['isChefiaImediata'];
                if ($isChefiaImediata) $objRelTriagemAtivDTO->setNumTempoExecucaoAtribuido(0);

                $objCadastrado = $this->cadastrar($objRelTriagemAtivDTO);
                $arrRetorno['objRetornoRelTriagem'][] = $objCadastrado;

                //case veio da Tela de Retriagem
                if ( !isset( $dados['isOrigemTelaAnalise'] ) ) {
	                //verifica a referencia anterior da relTriagemAtv para atualizar na RelAnaliseProduto
			            if ( is_numeric( $arrDadosExtras[1] ) ) {
				            $objRelAnaliseProdRN = new MdUtlRelAnaliseProdutoRN();

				            $arrParametro = [
					            'idRelTriagAtv'     => $arrDadosExtras[1],
					            'idAtividade'       => $atividade[1],
					            'novoIdRelTriagAtv' => $objCadastrado->getNumIdMdUtlRelTriagemAtv()
				            ];

				            $objRelAnaliseProdRN->atualizaReferenciaRelTriagAtvRelAnaliseProduto($arrParametro);
			            }
		            } else { //veio da Tela de Analise
	                $arrRetorno['itensRelTriagemParaAtualizar'][] = [
	                	'idRelTriagAtv'     => $arrDadosExtras[1] ,
		                'novoIdRelTriagAtv' => $objCadastrado->getNumIdMdUtlRelTriagemAtv()
	                ];
                }
            }
        }
        return $arrRetorno;
    }

    protected function verificarDesativacaoAtividadeTriagemConectado($idAtividade)
    {

        $objMdUtlRelTriagemAtvDTO = new MdUtlRelTriagemAtvDTO();

        $objMdUtlRelTriagemAtvDTO->setNumIdMdUtlAdmAtividade($idAtividade);
        $objMdUtlRelTriagemAtvDTO->retTodos();
        $countAtv = $this->contar($objMdUtlRelTriagemAtvDTO);

        if ($countAtv > 0) {
            $idsTriagem = InfraArray::converterArrInfraDTO($this->listar($objMdUtlRelTriagemAtvDTO), 'IdMdUtlTriagem');

            $objMdUtlControleDsmpRN = new MdUtlControleDsmpRN();
            $objMdUtlControleDsmpDTO = new MdUtlControleDsmpDTO();
            $objMdUtlControleDsmpDTO->setNumIdMdUtlTriagem($idsTriagem, InfraDTO::$OPER_IN);
            $objMdUtlControleDsmpDTO->retTodos();
            $count = $objMdUtlControleDsmpRN->contar($objMdUtlControleDsmpDTO);

            if ($count > 0) {
                $objInfraException = new InfraException();
                $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_28, array('desativar'));
                $objInfraException->lancarValidacao($msg);
            }
        }
    }

    protected function listarComAnaliseControlado($idAnalise)
    {

        $objMdUtlRelAnaliseProdutoDTO = new MdUtlRelAnaliseProdutoDTO();
        $objMdUtlRelAnaliseProdutoRN = new MdUtlRelAnaliseProdutoRN();
        $objMdUtlRelAnaliseProdutoDTO->setNumIdMdUtlAnalise($idAnalise);
        $objMdUtlRelAnaliseProdutoDTO->setStrSinAtivoAnalise('S');
	      $objMdUtlRelAnaliseProdutoDTO->setOrd('IdMdUtlRelTriagemAtv',InfraDTO::$TIPO_ORDENACAO_ASC);

        $objMdUtlRelAnaliseProdutoDTO->retStrNomeProduto();
        $objMdUtlRelAnaliseProdutoDTO->retNumComplexidadeAtividade();
        $objMdUtlRelAnaliseProdutoDTO->retStrNomeSerie();
        $objMdUtlRelAnaliseProdutoDTO->retStrNomeAtividade();
        $objMdUtlRelAnaliseProdutoDTO->retStrSinNaoAplicarPercDsmpAtv();
        $objMdUtlRelAnaliseProdutoDTO->retNumTempoExecucao();
        $objMdUtlRelAnaliseProdutoDTO->retNumTempoExecucaoAtribuido();
        $objMdUtlRelAnaliseProdutoDTO->retStrProtocoloFormatado();
        $objMdUtlRelAnaliseProdutoDTO->retDtaDataExecucao();

        //retorna as colunas da propria tabela
        $objMdUtlRelAnaliseProdutoDTO->retNumIdMdUtlRelAnaliseProduto();
        $objMdUtlRelAnaliseProdutoDTO->retNumIdMdUtlAnalise();
        $objMdUtlRelAnaliseProdutoDTO->retNumIdMdUtlAdmAtividade();
        $objMdUtlRelAnaliseProdutoDTO->retNumIdMdUtlAdmTpProduto();
        $objMdUtlRelAnaliseProdutoDTO->retNumIdMdUtlRelTriagemAtv();
        $objMdUtlRelAnaliseProdutoDTO->retNumIdSerie();
        $objMdUtlRelAnaliseProdutoDTO->retStrProtocoloFormatado();
        $objMdUtlRelAnaliseProdutoDTO->retStrObservacaoAnalise();
        $objMdUtlRelAnaliseProdutoDTO->retStrSinAtvRevAmostragem();

        $objMdUtlRelAnaliseProdutoDTO->setOrd('DataExecucao',InfraDTO::$TIPO_ORDENACAO_ASC);
	      $objMdUtlRelAnaliseProdutoDTO->setOrd('IdMdUtlRelTriagemAtv',InfraDTO::$TIPO_ORDENACAO_ASC);
	      $objMdUtlRelAnaliseProdutoDTO->setOrd('NomeAtividade',InfraDTO::$TIPO_ORDENACAO_ASC);
	      $objMdUtlRelAnaliseProdutoDTO->setOrd('NomeProduto',InfraDTO::$TIPO_ORDENACAO_ASC);
	      $objMdUtlRelAnaliseProdutoDTO->setOrd('NomeSerie',InfraDTO::$TIPO_ORDENACAO_ASC);

        $arrObjs = $objMdUtlRelAnaliseProdutoRN->listar($objMdUtlRelAnaliseProdutoDTO);
        return $arrObjs;
    }

    protected function getObjsPorIdTriagemConectado($idTriagem)
    {
        $objRelTriagemAtvRN = new MdUtlRelTriagemAtvRN();
        $objRelTriagemAtvDTO = new MdUtlRelTriagemAtvDTO();
        $objRelTriagemAtvDTO->setNumIdMdUtlTriagem($idTriagem);
        $objRelTriagemAtvDTO->retTodos();
        $objRelTriagemAtvDTO->retStrNomeAtividade();
        $objRelTriagemAtvDTO->retNumComplexidadeAtividade();
        $objRelTriagemAtvDTO->retNumVlTmpExecucaoAtv();
        $objRelTriagemAtvDTO->retNumVlTmpExecucaoRev();
        $objRelTriagemAtvDTO->retStrSinAnalise();
        $objRelTriagemAtvDTO->retStrSinNaoAplicarPercDsmpAtv();
        $objRelTriagemAtvDTO->retDtaDataExecucao();
        return $objRelTriagemAtvRN->listar($objRelTriagemAtvDTO);
    }

    protected function getObjsTriagemAtividadeConectado($idsTriagem)
    {

        $objMdUtlRelTriagemAtvDTO = new MdUtlRelTriagemAtvDTO();
        $objMdUtlRelTriagemAtvRN = new MdUtlRelTriagemAtvRN();

        $objMdUtlRelTriagemAtvDTO->setNumIdMdUtlTriagem($idsTriagem, InfraDTO::$OPER_IN);
        $objMdUtlRelTriagemAtvDTO->setOrdStrNomeAtividade(InfraDTO::$TIPO_ORDENACAO_ASC);
        $objMdUtlRelTriagemAtvDTO->retNumIdMdUtlAdmAtividade();
        $objMdUtlRelTriagemAtvDTO->retNumComplexidadeAtividade();
        $objMdUtlRelTriagemAtvDTO->retStrNomeAtividade();
        $objMdUtlRelTriagemAtvDTO->retTodos();

        $count = $objMdUtlRelTriagemAtvRN->contar($objMdUtlRelTriagemAtvDTO);

        if ($count > 0) {
            $arrObjs = $objMdUtlRelTriagemAtvRN->listar($objMdUtlRelTriagemAtvDTO);
            return $arrObjs;
        }

        return null;
    }

    protected function getObjsRelTriagemAtividadeConectado($idsTriagem)
    {

        $objMdUtlRelTriagemAtvDTO = new MdUtlRelTriagemAtvDTO();
        $objMdUtlRelTriagemAtvRN = new MdUtlRelTriagemAtvRN();

        $objMdUtlRelTriagemAtvDTO->setNumIdMdUtlRelTriagemAtv($idsTriagem, InfraDTO::$OPER_IN);
        $objMdUtlRelTriagemAtvDTO->setOrdStrNomeAtividade(InfraDTO::$TIPO_ORDENACAO_ASC);
        $objMdUtlRelTriagemAtvDTO->retNumComplexidadeAtividade();
        $objMdUtlRelTriagemAtvDTO->retNumIdMdUtlAdmAtividade();
        $objMdUtlRelTriagemAtvDTO->retStrNomeAtividade();
        $objMdUtlRelTriagemAtvDTO->retTodos();


        $count = $objMdUtlRelTriagemAtvRN->contar($objMdUtlRelTriagemAtvDTO);

        if ($count > 0) {
            $arrObjs = $objMdUtlRelTriagemAtvRN->listar($objMdUtlRelTriagemAtvDTO);
            return $arrObjs;
        }

        return null;
    }

    public function atualizaDataExecucao($data, $chave)
    {
        $idMdUtlRelTriagemAtv = explode("txtDtAnaliseAtividade", $chave);
        $objRelTriagemAtv = new MdUtlRelTriagemAtvDTO();
        $objRelTriagemAtv->setDtaDataExecucao($data);
        $objRelTriagemAtv->setNumIdMdUtlRelTriagemAtv($idMdUtlRelTriagemAtv[1]);

        $this->alterar($objRelTriagemAtv);
    }
}
