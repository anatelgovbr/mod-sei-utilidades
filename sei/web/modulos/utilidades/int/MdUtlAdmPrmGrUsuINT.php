<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 10/07/2018 - criado por jhon.cast
 *
 * Versão do Gerador de Código: 1.41.0
 */

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdUtlAdmPrmGrUsuINT extends InfraINT
{

    public static function montarSelectIdMdUtlAdmPrmGrUsu($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdMdUtlAdmPrmGr = '', $numIdUsuario = '')
    {
        $objMdUtlAdmPrmGrUsuDTO = new MdUtlAdmPrmGrUsuDTO();
        $objMdUtlAdmPrmGrUsuDTO->retNumIdMdUtlAdmPrmGrUsu();
        $objMdUtlAdmPrmGrUsuDTO->retNumIdMdUtlAdmPrmGrUsu();

        if ($numIdMdUtlAdmPrmGr !== '') {
            $objMdUtlAdmPrmGrUsuDTO->setNumIdMdUtlAdmPrmGr($numIdMdUtlAdmPrmGr);
        }

        if ($numIdUsuario !== '') {
            $objMdUtlAdmPrmGrUsuDTO->setNumIdUsuario($numIdUsuario);
        }

        $objMdUtlAdmPrmGrUsuDTO->setOrdNumIdMdUtlAdmPrmGrUsu(InfraDTO::$TIPO_ORDENACAO_ASC);

        $objMdUtlAdmPrmGrUsuRN = new MdUtlAdmPrmGrUsuRN();
        $arrObjMdUtlAdmPrmGrUsuDTO = $objMdUtlAdmPrmGrUsuRN->listar($objMdUtlAdmPrmGrUsuDTO);

        return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdUtlAdmPrmGrUsuDTO, 'IdMdUtlAdmPrmGrUsu', 'IdMdUtlAdmPrmGrUsu');
    }

    public static function montarSelectStaTipoPresenca($strValorItemSelecionado = '')
    {
        $select = '<option value="0"></option>';

        $arrFrequencia = array(//MdUtlAdmPrmGrUsuRN::$TP_PRESENCA_DIFERENCIADO => 'Diferenciado',
            MdUtlAdmPrmGrUsuRN::$TP_PRESENCA_PRESENCIAL => 'Presencial',
            MdUtlAdmPrmGrUsuRN::$TP_PRESENCA_TELETRABALHO => 'Teletrabalho');
        foreach ($arrFrequencia as $key => $frequencia) {

            $add = '';

            if ($strValorItemSelecionado == $key) {
                $add = 'selected = selected';
            }

            $select .= '<option ' . $add . ' value="' . $key . '">' . $frequencia . '</option>';
        }

        return $select;
    }

    public static function montarSelectStaTipoJornada($strValorItemSelecionado = '')
    {

        $select = '<option value="0"></option>';

        $arrFrequencia = array(MdUtlAdmPrmGrUsuRN::$TIPOJORNADA_INTEGRAL => 'Integral',
            MdUtlAdmPrmGrUsuRN::$TIPOJORNADA_REDUZIDO => 'Reduzido');
        foreach ($arrFrequencia as $key => $frequencia) {
            $add = '';

            if ($strValorItemSelecionado == $key) {
                $add = 'selected = selected';
            }

            $select .= '<option ' . $add . ' value="' . $key . '">' . $frequencia . '</option>';
        }

        return $select;
    }

    public static function autoCompletarUsuariosInternos($numIdOrgao, $strPalavrasPesquisa, $idTpControle)
    {

        $arrObjUsuarioDTO = null;

        if (!is_null($idTpControle) && $idTpControle != '') {
            $objMdUtlAdmTpCtrlRN = new MdUtlAdmTpCtrlDesempRN();

            $objMdUtlAdmTpCtrlDTO = new MdUtlAdmTpCtrlDesempDTO();
            $objMdUtlAdmTpCtrlDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTpControle);
            $objMdUtlAdmTpCtrlDTO->retNumIdMdUtlAdmPrmGr();
            $objMdUtlAdmTpCtrlDTO->setNumTotalRegistros(1);
            $objMdUtlAdmTpCtrlDTO = $objMdUtlAdmTpCtrlRN->consultar($objMdUtlAdmTpCtrlDTO);

            if (!is_null($objMdUtlAdmTpCtrlDTO)) {

                $idParams = $objMdUtlAdmTpCtrlDTO->getNumIdMdUtlAdmPrmGr();

                $objUsuarioDTO = new MdUtlAdmPrmGrUsuDTO();
                $objUsuarioDTO->retNumIdMdUtlAdmPrmGrUsu();
                $objUsuarioDTO->retNumIdUsuario();
                $objUsuarioDTO->retStrSigla();
                $objUsuarioDTO->retStrNome();
                $objUsuarioDTO->setNumIdMdUtlAdmPrmGr($idParams);

                $objUsuarioDTO->setStrPalavrasPesquisa($strPalavrasPesquisa);

                if (!InfraString::isBolVazia($numIdOrgao)) {
                    $objUsuarioDTO->setNumIdOrgao($numIdOrgao);
                }

                $objUsuarioDTO->setStrStaTipo(UsuarioRN::$TU_SIP);
                $objUsuarioDTO->setNumMaxRegistrosRetorno(50);
                $objUsuarioDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

                $objUsuarioRN = new MdUtlAdmPrmGrUsuRN();
                $arrObjUsuarioDTO = $objUsuarioRN->pesquisarUsuarioParametros($objUsuarioDTO);

                foreach ($arrObjUsuarioDTO as $objUsuarioDTO) {
                    $objUsuarioDTO->setStrSigla($objUsuarioDTO->getStrNome() . ' (' . $objUsuarioDTO->getStrSigla() . ')');
                }
            }
        }

        return $arrObjUsuarioDTO;
    }

    public static function buscarNomeDescricaoUsuario($ids)
    {
        $xml = '';
        $objMdUsuPrmRN = new MdUtlAdmPrmGrUsuRN();
        $objMdUsuPrmDTO = new MdUtlAdmPrmGrUsuDTO();
        $objMdUsuPrmDTO->setNumIdMdUtlAdmPrmGrUsu($ids, InfraDTO::$OPER_IN);
        $objMdUsuPrmDTO->retNumIdUsuario();
        $objMdUsuPrmDTO->retStrNome();
        $objMdUsuPrmDTO->retStrSigla();
        $objMdUsuPrmDTO->retNumIdMdUtlAdmPrmGrUsu();

        $count = $objMdUsuPrmRN->contar($objMdUsuPrmDTO);
        if ($count > 0) {
            $arrRetLista = $objMdUsuPrmRN->listar($objMdUsuPrmDTO);

            $xml = '<Documento>';
            foreach ($arrRetLista as $objDTO) {
                //$arrRetorno[$objDTO->getNumIdMdUtlAdmPrmGrUsu()] = htmlentities()

                $id = $objDTO->getNumIdMdUtlAdmPrmGrUsu();
                $xml .= '<IdUsuario' . $id . '>';
                $xml .= htmlspecialchars('<a alt="' . $objDTO->getStrNome() . '" title="' . $objDTO->getStrNome() . '" class="ancoraSigla"> ' . $objDTO->getStrSigla() . ' </a>');
                $xml .= '</IdUsuario' . $id . '>';
            }
            $xml .= '</Documento>';
        }


        return $xml;
    }

    public static function buscarNomeDescricaoUsuarioSelecionado($ids)
    {
        $xml = '';
        $objUsuarioRN = new UsuarioRN();
        $objUsuarioDTO = new UsuarioDTO();

        $objUsuarioDTO->setNumIdUsuario($ids, InfraDTO::$OPER_IN);
        $objUsuarioDTO->retStrNome();
        $objUsuarioDTO->retStrSigla();
        $objUsuarioDTO->retNumIdUsuario();

        $count = $objUsuarioRN->contarRN0492($objUsuarioDTO);

        if ($count > 0) {
            $arrRetLista = $objUsuarioRN->listarRN0490($objUsuarioDTO);

            $xml = '<Documento>';
            foreach ($arrRetLista as $objDTO) {
                //$arrRetorno[$objDTO->getNumIdMdUtlAdmPrmGrUsu()] = htmlentities()

                $id = $objDTO->getNumIdUsuario();
                $xml .= '<IdUsuario' . $id . '>';
                $xml .= htmlspecialchars('<a alt="' . $objDTO->getStrNome() . '" title="' . $objDTO->getStrNome() . '" class="ancoraSigla"> ' . $objDTO->getStrSigla() . ' </a>');
                $xml .= '</IdUsuario' . $id . '>';
            }
            $xml .= '</Documento>';
        }

        return $xml;
    }

    public static function consultarVinculoFilaUsuario($dados)
    {


        $mdUtlAdmFilaPrmGrUsuDTO = new MdUtlAdmFilaPrmGrUsuDTO();
        $mdUtlAdmFilaPrmGrUsuRN = new MdUtlAdmFilaPrmGrUsuRN();

        $mdUtlAdmFilaPrmGrUsuDTO->setNumIdMdUtlAdmPrmGrUsu($dados);
        $mdUtlAdmFilaPrmGrUsuDTO->retNumIdUsuario();
        $mdUtlAdmFilaPrmGrUsuDTO->retStrNomeUsuario();


        $numRegistro = $mdUtlAdmFilaPrmGrUsuRN->contar($mdUtlAdmFilaPrmGrUsuDTO);

        $xml = '<dados>';
        if ($numRegistro > 0) {
            $xml .= '<sucesso>1</sucesso>';
            $xml .= '<msg>';
            $xml .= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_82);
            $xml .= '</msg>';
        } else {
            $xml .= '<sucesso>0</sucesso>';
        }

        $xml .= '</dados>';

        return $xml;
    }

    public static function autoCompletarUsuarioParticipante($strPalavrasPesquisa, $idFila, $idStatus, $arrProcedimentos)
    {

        $objUsuarioRN = new UsuarioRN();
        $idStatus = trim($idStatus);
        $idFila   = trim($idFila);
        $arrProcedimentos = explode(",", trim($arrProcedimentos));
        $moduloAutoAvaliacaoLiberado = MdUtlAdmPrmGrUsuINT::verificaModoluloLiberarAutoAvaliacaoAtivado();

        $strPapelUsuario = MdUtlAdmFilaINT::getPapeisDeUsuario($idStatus);

        if (!is_null($strPapelUsuario)) {

            $objMdUtlAdmFilaPrmUsuRN = new MdUtlAdmFilaPrmGrUsuRN();
            $objRegrasGeraisRN = new MdUtlRegrasGeraisRN();
            $idsUsuarioUnidade = $objRegrasGeraisRN->getIdsUsuariosUnidadeLogada();

            if (count($idsUsuarioUnidade) > 0) {
                $arrDTO = $objMdUtlAdmFilaPrmUsuRN->getUsuarioPorPapel(array($strPapelUsuario, $idFila, $idsUsuarioUnidade));

                if( empty($arrDTO) ){
                    return null;
                }

                $idsUsuario = InfraArray::converterArrInfraDTO($arrDTO, 'IdUsuario');

                // se tiver informado o procedimento, não retorna pessoas que possam ser avaliadoras dela mesma.
                if (!$moduloAutoAvaliacaoLiberado && count($arrProcedimentos)>0){
                    $arrIdsPessoasQueNaoPodeDistribuir = MdUtlAdmPrmGrUsuINT::buscarArrayPessoasNaoPodeDistribuir($arrProcedimentos);
                    $idsUsuario = array_diff($idsUsuario, $arrIdsPessoasQueNaoPodeDistribuir);
                }

                $objUsuarioDTO = new UsuarioDTO();
                $objUsuarioDTO->retTodos();
                $objUsuarioDTO->setNumIdUsuario($idsUsuario, InfraDTO::$OPER_IN);
                $objUsuarioDTO->setStrPalavrasPesquisa($strPalavrasPesquisa);

                $arrObjUsuarioDTO = $objUsuarioRN->pesquisar($objUsuarioDTO);

                if( empty($arrObjUsuarioDTO) ){
                    return null;
                }

                foreach ($arrObjUsuarioDTO as $objUsuarioDTO) {
                    $objUsuarioDTO->setStrSigla($objUsuarioDTO->getStrNome() . ' (' . $objUsuarioDTO->getStrSigla() . ')');
                }
                return $arrObjUsuarioDTO;
            }
        }

        return null;
    }

    public static function consultarVinculoParametrizacaoUsuario($idVinculo, $idFila)
    {

        $objMdUtlAdmPrmGrUsuDTO = new MdUtlAdmPrmGrUsuDTO();
        $objMdUtlAdmPrmGrUsuRN = new MdUtlAdmPrmGrUsuRN();

        $objMdUtlAdmPrmGrUsuDTO->setNumIdMdUtlAdmPrmGrUsu($idVinculo);
        $objMdUtlAdmPrmGrUsuDTO->retNumIdUsuario();
        $objMdUtlAdmPrmGrUsuDTO->setNumMaxRegistrosRetorno(1);
        $objMdUtlAdmPrmGrUsuDTO = $objMdUtlAdmPrmGrUsuRN->consultar($objMdUtlAdmPrmGrUsuDTO);
        $idUsuario = $objMdUtlAdmPrmGrUsuDTO->getNumIdUsuario();

        $mdUtlAdmDsmpDTO = new MdUtlControleDsmpDTO();
        $mdUtlAdmDsmpRN = new MdUtlControleDsmpRN();
        $mdUtlAdmDsmpDTO->setNumIdUsuarioDistribuicao($idUsuario);
        $mdUtlAdmDsmpDTO->setNumIdMdUtlAdmFila($idFila);
	      $mdUtlAdmDsmpDTO->retStrProtocoloProcedimentoFormatado();
        $numRegistro = $mdUtlAdmDsmpRN->contar($mdUtlAdmDsmpDTO);

        $xml = '<dados>';
        if ($numRegistro > 0 && !is_null($idUsuario)) {
        	  $arrObjs = $mdUtlAdmDsmpRN->listar($mdUtlAdmDsmpDTO);
        	  foreach($arrObjs as $prot){
        	  	$strProt .= "- " . $prot->getStrProtocoloProcedimentoFormatado() . "\n";
	          }
            $xml .= '<sucesso>0</sucesso>';
            $xml .= '<msg>';
            $xml .= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_83);
            $xml .= "\n\n" . $strProt;
            $xml .= '</msg>';
        } else {
            $xml .= '<sucesso>1</sucesso>';
            $xml .= '<msg>Nenhum registro encontrado.</msg>';
        }

        $xml .= '</dados>';

        return $xml;
    }

    public static function getValoresParamUnidEsf( $idTipoControle ){
        $objMdUtlAdmTpCtrlDesempDTO = new MdUtlAdmTpCtrlDesempDTO();
        $objMdUtlAdmUtlTpCtrlRN     = new MdUtlAdmTpCtrlDesempRN();

        $objMdUtlAdmTpCtrlDesempDTO->setNumIdMdUtlAdmTpCtrlDesemp((int) $idTipoControle);
        $objMdUtlAdmTpCtrlDesempDTO->retNumIdMdUtlAdmPrmGr();
        $objMdUtlAdmTpCtrlDesempDTO->retNumCargaPadrao();
        $objMdUtlAdmTpCtrlDesempDTO->retStrStaFrequencia();
        $objMdUtlAdmTpCtrlDesempDTO->retNumPercentualTeletrabalho();
        $objMdUtlAdmTpCtrlDesempDTO->retNumInicioPeriodoParametrizado();

        $objDTO = $objMdUtlAdmUtlTpCtrlRN->consultar($objMdUtlAdmTpCtrlDesempDTO);

        $arrFrequencia = MdUtlAdmPrmGrINT::retornaArrPadraoFrequenciaDiaria();

        $objMdUtlAdmPrmGrUsuRN = new MdUtlAdmPrmGrUsuRN();
	      $arrPeriodo = $objMdUtlAdmPrmGrUsuRN->getDiasUteisNoPeriodo([$objDTO->getStrStaFrequencia()]);
        $diasUteis = $arrPeriodo['numFrequencia'];

        $arrParamRet['idPrmGr']           = $objDTO->getNumIdMdUtlAdmPrmGr();
        $arrParamRet['numCargaPadrao']    = $objDTO->getNumCargaPadrao();
        $arrParamRet['numPercentualTele'] = $objDTO->getNumPercentualTeletrabalho();
        $arrParamRet['inicioPeriodo']     = $objDTO->getNumInicioPeriodoParametrizado();
        $arrParamRet['staFrequencia']     = $objDTO->getStrStaFrequencia();
        $arrParamRet['strFrequencia']     = $arrFrequencia[$arrParamRet['staFrequencia']];
        $arrParamRet['strCargaPadrao']    = MdUtlAdmPrmGrINT::convertToHoursMins(($diasUteis * $arrParamRet['numCargaPadrao'])) . ' - ' . $arrParamRet['strFrequencia'];

        return $arrParamRet;
    }

    public static function buscarDadosCargaUsuarioCompleto( $post ){
				$arrIdsPrmGr = [];
        foreach( $post['idTipoControle'] as $idTpCtrl ){
            $arrParams = self::getValoresParamUnidEsf( $idTpCtrl );
            $dados[]   = MdUtlAdmPrmGrUsuINT::arrDadosCargaUsuario($post['idUsuarioParticipante'], $arrParams['idPrmGr'], $arrParams['numCargaPadrao'], $arrParams['numPercentualTele'], $arrParams['staFrequencia'], $idTpCtrl, $arrParams['inicioPeriodo']);
	          $arrIdsPrmGr[] = $arrParams['idPrmGr'];
        }

        $retorno = array();

        foreach ($dados as $k => $v) {
            $retorno['totalCarga']                 += $v['totalCarga'];
            $retorno['totalUnidEsforco']           += $v['totalUnidEsforco'];
            $retorno['unidEsforcoHist']            += $v['unidEsforcoHist'];
            $retorno['valorTempoPendenteExecucao'] += $v['valorTempoPendenteExecucao'];
        }

        // caso não seja informado o usuario participante ... retorna a carga padrao do tipo de controle
        if (!$post['idUsuarioParticipante'] && $post['idTipoControle']) {
            $retorno['totalCarga'] = MdUtlAdmPrmGrUsuINT::cargaPadraoTipoControle($arrParams['numCargaPadrao'],$arrParams['staFrequencia']);
        }

        $tipoPeriodo = '';
        if ($post['idTipoControle'] && count($post['idTipoControle']) == 1) {
            $tipoPeriodo = MdUtlAdmPrmGrINT::retornaTipoPeriodo(current($post['idTipoControle']));
        }

        $dadosChefia = ( new MdUtlAdmPrmGrUsuRN() )->validaUsuarioIsChefiaImediata( [ $arrIdsPrmGr , $post['idUsuarioParticipante'] ]);

        $xml = '<Documento>';
        $xml .= '<ValorCarga>' . $retorno['totalCarga'] . '</ValorCarga>';
        $xml .= '<ValorUndEs>' . $retorno['totalUnidEsforco'] . '</ValorUndEs>';
        $xml .= '<ValorUndEsExecutado>' . $retorno['unidEsforcoHist'] . '</ValorUndEsExecutado>';
        $xml .= '<ValorTempoPendenteExecucao>' . $retorno['valorTempoPendenteExecucao'] . '</ValorTempoPendenteExecucao>';
        $xml .= '<TipoPeriodo>' . $tipoPeriodo . '</TipoPeriodo>';

        if( $dadosChefia ) $xml .= '<ChefeImediato>S</ChefeImediato>';

        $xml .= '</Documento>';

        return $xml;
    }

    public static function buscarCargaPadrao( $post ){

        $arrParams = self::getValoresParamUnidEsf( $post['idTipoControle'] );
        $cargaPadrao = MdUtlAdmPrmGrUsuINT::cargaPadraoTipoControle($arrParams['numCargaPadrao'],$arrParams['staFrequencia']);

        $xml = '<Documento>';
        $xml .= '<ValorCarga>' . $cargaPadrao . '</ValorCarga>';
        $xml .= '</Documento>';

        return $xml;
    }

    public static function buscarDadosCargaUsuario($idUsuarioParticipante, $idParam, $numCargaPadrao, $numPercentualTele, $staFrequencia, $idTipoControle, $inicioPeriodo)
    {
        $retorno = MdUtlAdmPrmGrUsuINT::arrDadosCargaUsuario($idUsuarioParticipante, $idParam, $numCargaPadrao, $numPercentualTele, $staFrequencia, $idTipoControle, $inicioPeriodo);

        $arrDadosChefia = ( new MdUtlAdmPrmGrUsuRN() )->validaUsuarioIsChefiaImediata( [ [$idParam] , $idUsuarioParticipante ] );

        $xml = '<Documento>';
        $xml .= '<ValorCarga>' . $retorno['totalCarga'] . '</ValorCarga>';
        $xml .= '<ValorUndEs>' . $retorno['totalUnidEsforco'] . '</ValorUndEs>';
        $xml .= '<ValorUndEsExecutado>' . $retorno['unidEsforcoHist'] . '</ValorUndEsExecutado>';
        $xml .= '<ValorTempoPendenteExecucao>' . $retorno['valorTempoPendenteExecucao'] . '</ValorTempoPendenteExecucao>';

        if ( !empty($arrDadosChefia) ){
            $xml.= '<ChefiaImediata>'.$arrDadosChefia->getDthInicioParticipacao().'</ChefiaImediata>';
        }

        $xml .= '</Documento>';

        return $xml;
    }

    public static function arrDadosCargaUsuario($idUsuarioParticipante, $idParam, $numCargaPadrao, $numPercentualTele, $staFrequencia, $idTipoControle, $inicioPeriodo)
    {
		    $objMdUtlAdmPrmGrUsuRN = new MdUtlAdmPrmGrUsuRN();
		    $objMdUtlControleDsmpRN = new MdUtlControleDsmpRN();
		    $objMdUtlHistControleDsmpRN = new MdUtlHistControleDsmpRN();
		    $objMdUtlPrazoRN = new MdUtlPrazoRN();

		    $objMdUtlPrmGrDTO = new MdUtlAdmPrmGrDTO();
		    $objMdUtlPrmGrDTO->setNumIdMdUtlAdmPrmGr($idParam);
		    $objMdUtlPrmGrDTO->retDtaDataCorte();
		    $objMdUtlPrmGrDTO = (new MdUtlAdmPrmGrRN())->consultar($objMdUtlPrmGrDTO);

		    $arrDatasFiltro = $objMdUtlPrazoRN->getDatasPeriodoAtual($idParam);

		    #if (empty($arrDatasFiltro)) $arrDatasFiltro = $objMdUtlPrazoRN->getDatasPorFrequencia($inicioPeriodo);

		    $arrCargaDist = $objMdUtlControleDsmpRN->buscarTempoExecucao(array($idUsuarioParticipante, $idTipoControle, $arrDatasFiltro));
		    $arrCargaDistHist = $objMdUtlHistControleDsmpRN->buscarTempoExecucaoHist(array($idUsuarioParticipante, $idTipoControle, $arrDatasFiltro, $arrCargaDist['infoParaHist']));
		    $totalUnidEsforco = $arrCargaDist['tmpCargaDist'] + $arrCargaDistHist;

		    $tempoExecucaoExecutado = $objMdUtlControleDsmpRN->buscarTempoExecucaoExecutado(array($idUsuarioParticipante, $idTipoControle, $arrDatasFiltro));
		    $tempoExecucaoExecutadoHist = $objMdUtlHistControleDsmpRN->buscarTempoExecucaoExecutadoHist(array($idUsuarioParticipante, $idTipoControle, $arrDatasFiltro));
		    $totalTempoExecucaoExecutadoHist = $tempoExecucaoExecutado + $tempoExecucaoExecutadoHist;

		    $arrPeriodo = $objMdUtlAdmPrmGrUsuRN->getDiasUteisNoPeriodo([$staFrequencia]);
		    $diasUteis = $arrPeriodo['numFrequencia'];
		    $totalCarga = $objMdUtlAdmPrmGrUsuRN->verificaCargaPadrao(array($idUsuarioParticipante, $idParam, $numCargaPadrao, $numPercentualTele, $diasUteis));

		    $valorTempoPendenteExecucao = MdUtlAdmPrmGrUsuINT::retornaTempoPendenteExecucao($idUsuarioParticipante, $idTipoControle);

		    $retorno['totalCarga'] = $totalCarga;

		    //Carga Horária Distribuída no Período:
		    $retorno['totalUnidEsforco'] = $totalUnidEsforco;

		    //Total de Tempo Executado no Período
		    $retorno['unidEsforcoHist'] = $totalTempoExecucaoExecutadoHist;

		    // Total de Tempo Pendente de Execução
		    $retorno['valorTempoPendenteExecucao'] = $valorTempoPendenteExecucao;

		    return $retorno;
    }

    public static function cargaPadraoTipoControle($numCargaPadrao, $staFrequencia)
    {
        $objMdUtlAdmPrmGrUsuRN = new MdUtlAdmPrmGrUsuRN();
	      $arrPeriodo = $objMdUtlAdmPrmGrUsuRN->getDiasUteisNoPeriodo([$staFrequencia]);
        $diasUteis = $arrPeriodo['numFrequencia'];
        return $diasUteis * $numCargaPadrao;
    }

    public static function retornaCalculoPercentualDesempenho($numTmpExecucao, $idTipoControle, $idUsuario)
    {

        $arrDados = self::retornaDadosPercentualDesempenho($numTmpExecucao, $idTipoControle, $idUsuario);

        return $arrDados['numTempoExecucao'];
    }

    public static function retonaDadosParaDistribuicao($numTmpExecucao, $idTipoControle, $idUsuario)
    {

        $arrDados = self::retornaDadosPercentualDesempenho($numTmpExecucao, $idTipoControle, $idUsuario);

        $xml  = '<Documento>';
        $xml .= '<ValorDistribuicao>' . $arrDados['numTempoExecucao'] . '</ValorDistribuicao>';
        $xml .= '<PercentualDesempenho>' . $arrDados['numPercentualDesempenho'] . '</PercentualDesempenho>';
        $xml .= '<TipoPresenca>' . $arrDados['strStaTipoPresenca'] . '</TipoPresenca>';
        $xml .= '</Documento>';

        return $xml;
    }

    public static function retornaDadosPercentualDesempenho($numTmpExecucao, $idTipoControle, $idUsuario)
    {
        $objMdUtlAdmTpCtrlDesempDTO = new MdUtlAdmTpCtrlDesempDTO();
        $objMdUtlAdmPrmGrDTO = new MdUtlAdmPrmGrDTO();
        $mdUtlAdmTpCtrlDesempRN = new MdUtlAdmTpCtrlDesempRN();
        $mdUtlAdmPrmGrRN = new MdUtlAdmPrmGrRN();

        $objMdUtlAdmTpCtrlDesempDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTipoControle);
        $objMdUtlAdmTpCtrlDesempDTO->retNumIdMdUtlAdmPrmGr();

        $objMdUtlAdmTpCtrlDesempDTO = $mdUtlAdmTpCtrlDesempRN->consultar($objMdUtlAdmTpCtrlDesempDTO);

        $objMdUtlAdmPrmGrDTO->setNumIdMdUtlAdmPrmGr($objMdUtlAdmTpCtrlDesempDTO->getNumIdMdUtlAdmPrmGr());
        $objMdUtlAdmPrmGrDTO->setNumIdUsuario($idUsuario);
        #$objMdUtlAdmPrmGrDTO->setDistinct(true);
        $objMdUtlAdmPrmGrDTO->retNumCargaPadrao();
        $objMdUtlAdmPrmGrDTO->retStrStaFrequencia();
        $objMdUtlAdmPrmGrDTO->retDblPercentualTeletrabalho();
        $objMdUtlAdmPrmGrDTO->retStrStaTipoPresenca();
	      $objMdUtlAdmPrmGrDTO->setOrd('IdMdUtlAdmPrmGrUsu','desc');
        $objMdUtlAdmPrmGrDTO = $mdUtlAdmPrmGrRN->listar($objMdUtlAdmPrmGrDTO)[0];

        $percentualDesempenho = 0;

        if(!is_null($objMdUtlAdmPrmGrDTO)){
            switch ($objMdUtlAdmPrmGrDTO->getStrStaTipoPresenca()) {
                case MdUtlAdmPrmGrUsuRN::$TP_PRESENCA_TELETRABALHO :
                    $percentualDesempenho = $objMdUtlAdmPrmGrDTO->getDblPercentualTeletrabalho();
                    break;
                default:
                    $percentualDesempenho = 0;
            }
        }

        $arrRetorno = [
            'strStaTipoPresenca' => $objMdUtlAdmPrmGrDTO ? $objMdUtlAdmPrmGrDTO->getStrStaTipoPresenca() : null,
            'numPercentualDesempenho' => $percentualDesempenho,
            'numTempoExecucao' => isset($numTmpExecucao) ? intval($numTmpExecucao / (1 + ($percentualDesempenho / 100))) : null,
        ];

        return $arrRetorno;

    }

    public static function retornaCalculoPercentualPresenca($arrParam){
        return intval($arrParam['tempoExec'] / (1 + ($arrParam['percDsmp'] / 100)));
    }

    public static function retornaTempoPendenteExecucao($idUsuario, $idTipoControle)
    {
        $objMdUtlControleDsmpDTO = new MdUtlControleDsmpDTO();
        $objMdUtlControleDsmpDTO->setNumIdUsuarioDistribuicao($idUsuario);
        $objMdUtlControleDsmpDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objMdUtlControleDsmpDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTipoControle);
        $objMdUtlControleDsmpDTO->retNumTempoExecucao();
        $objMdUtlControleDsmpDTO->retNumTempoExecucaoAtribuido();
        $objMdUtlControleDsmpDTO->retNumIdMdUtlTriagem();
        $objMdUtlControleDsmpDTO->retNumIdMdUtlAnalise();
        $objMdUtlControleDsmpDTO->retStrStaAtendimentoDsmp();
        $objMdUtlControleDsmpDTO->retNumIdMdUtlAdmTpCtrlDesemp();

        $objMdUtlControleDsmpRN = new MdUtlControleDsmpRN();
        $arrMdUtlControleDsmp = $objMdUtlControleDsmpRN->listar($objMdUtlControleDsmpDTO);

        $totalTempoExecucao = 0;
        foreach ($arrMdUtlControleDsmp as $objMdUtlControleDsmp) {
            if( $objMdUtlControleDsmp->getStrStaAtendimentoDsmp() == MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE
                &&
                $objMdUtlControleDsmp->getNumTempoExecucao() == 0 )
            {
                $vlrUndEsf = self::_retornaUnidEsforcoAtividadesAnalisadas( $objMdUtlControleDsmp->getNumIdMdUtlAnalise() );
                $totalTempoExecucao += MdUtlAdmPrmGrINT::convertToMins($vlrUndEsf);
            }else if(
                $objMdUtlControleDsmp->getStrStaAtendimentoDsmp() == MdUtlControleDsmpRN::$EM_CORRECAO_TRIAGEM
                &&
                $objMdUtlControleDsmp->getNumTempoExecucao() == 0)
            {
                $vlrUndEsf = self::_retornaUnidEsforcoTriagem( $objMdUtlControleDsmp->getNumIdMdUtlTriagem() );
                $totalTempoExecucao += $vlrUndEsf; 
            }else{
                $totalTempoConvertido = $objMdUtlControleDsmp->getNumTempoExecucaoAtribuido();
                $totalTempoExecucao += $totalTempoConvertido; 
            }
        }
        return $totalTempoExecucao;
    }

    public static function _retornaUnidEsforcoAtividadesAnalisadas( $idAnalise ){
        $objMdUtlAnaliseProdDTO = new MdUtlRelAnaliseProdutoDTO();
        $objMdUtlAnaliseProdRN = new MdUtlRelAnaliseProdutoRN();

        $objMdUtlAnaliseProdDTO->setNumIdMdUtlAnalise( $idAnalise );
        $objMdUtlAnaliseProdDTO->retNumIdMdUtlRelTriagemAtv();
        $objMdUtlAnaliseProdDTO->retNumTempoExecucao();
        $objMdUtlAnaliseProdDTO->retNumTempoExecucaoAtribuido();
        $objMdUtlAnaliseProdDTO->setDistinct( true );

        $arrAnaliseProd = $objMdUtlAnaliseProdRN->listar( $objMdUtlAnaliseProdDTO );

        $unidEsf = 0;

        if( !empty( $arrAnaliseProd ) ){
            foreach ($arrAnaliseProd as $k => $v) {
                $unidEsf += empty($v->getNumTempoExecucaoAtribuido()) ? 0 : $v->getNumTempoExecucaoAtribuido();
            }
        }
        return $unidEsf;
    }

    public static function _retornaUnidEsforcoTriagem( $idTriagem ){
        $objTriagemDTO = new MdUtlTriagemDTO();
        $objTriagemRN  = new MdUtlTriagemRN();

        $objTriagemDTO->setNumIdMdUtlTriagem( $idTriagem );
        $objTriagemDTO->retNumTempoExecucaoAtribuido();

        $vlrUnidEsf = $objTriagemRN->consultar( $objTriagemDTO );

        return !is_null( $vlrUnidEsf ) ? $vlrUnidEsf->getNumTempoExecucaoAtribuido() : 0;
    }

    public static function buscarArrayPessoasNaoPodeDistribuir($arrProcedimentos, $distribuidorAvaliadorTriagemAnalise = NULL)
    {
        $arrIdsPessoasQueNaoPodeDistribuir = array();
        foreach ($arrProcedimentos as $idProcedimento){
            $idColaborador = MdUtlAdmPrmGrUsuINT::verificaExecutorUltimaTarefaParaAvaliacao($idProcedimento, $distribuidorAvaliadorTriagemAnalise);
            if ($idColaborador){
                array_push($arrIdsPessoasQueNaoPodeDistribuir, $idColaborador);
            }
        }

        return $arrIdsPessoasQueNaoPodeDistribuir;

    }

    public function verificaPermissaoDistribuirParaMim($idProcedimento, $distribuidorAvaliadorTriagemAnalise = NULL)
    {
        $permite = false;

        $idUsuarioLogado = SessaoSEI::getInstance()->getNumIdUsuario();
        $idColaborador = MdUtlAdmPrmGrUsuINT::verificaExecutorUltimaTarefaParaAvaliacao($idProcedimento);
        if (MdUtlAdmPrmGrUsuINT::verificaModoluloLiberarAutoAvaliacaoAtivado() || ($idUsuarioLogado != $idColaborador)){
            $permite = true;
        }
        return $permite;
    }

    private static function verificaExecutorUltimaTarefaParaAvaliacao($idProcedimento, $distribuidorAvaliadorTriagemAnalise = NULL)
    {
        $objMdUtlControleDsmpDTO = new MdUtlControleDsmpDTO();
        $objMdUtlControleDsmpDTO->setDblIdProcedimento($idProcedimento);
        $objMdUtlControleDsmpDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        if($distribuidorAvaliadorTriagemAnalise == NULL) {
            $objMdUtlControleDsmpDTO->setStrStaAtendimentoDsmp(array(MdUtlControleDsmpRN::$AGUARDANDO_REVISAO,MdUtlControleDsmpRN::$EM_REVISAO), InfraDTO::$OPER_IN);
        }
        $objMdUtlControleDsmpDTO->retNumIdMdUtlControleDsmp();
        $objMdUtlControleDsmpDTO->retStrStaAtendimentoDsmp();
        $objMdUtlControleDsmpDTO->retNumIdUsuarioAtual();
	      $objMdUtlControleDsmpDTO->retNumIdUsuarioDistribuicao();

        $objMdUtlControleDsmpRN = new MdUtlControleDsmpRN();
        $objMdUtlControleDsmp = $objMdUtlControleDsmpRN->consultar($objMdUtlControleDsmpDTO);
        if ($objMdUtlControleDsmp) {
            switch (intval($objMdUtlControleDsmp->getStrStaAtendimentoDsmp())) {
		            case MdUtlControleDsmpRN::$EM_ANALISE:
			            return $objMdUtlControleDsmp->getNumIdUsuarioDistribuicao();
		              break;

                case MdUtlControleDsmpRN::$AGUARDANDO_REVISAO:
                	return $objMdUtlControleDsmp->getNumIdUsuarioAtual();
									break;

                case MdUtlControleDsmpRN::$EM_REVISAO:
                	return MdUtlAdmPrmGrUsuINT::procurarNoHistorico($idProcedimento);
									break;

                default:
                	return null;
            }
        }
    }

    private static function procurarNoHistorico($idProcedimento)
    {
        $objMdUtlHistControleDsmpDTO = new MdUtlHistControleDsmpDTO();
        $objMdUtlHistControleDsmpDTO->setDblIdProcedimento($idProcedimento);
        $objMdUtlHistControleDsmpDTO->setStrStaAtendimentoDsmp(MdUtlControleDsmpRN::$AGUARDANDO_REVISAO);
        $objMdUtlHistControleDsmpDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objMdUtlHistControleDsmpDTO->setNumMaxRegistrosRetorno(1);
        $objMdUtlHistControleDsmpDTO->setOrdNumIdMdUtlHistControleDsmp(InfraDTO::$TIPO_ORDENACAO_DESC);
        $objMdUtlHistControleDsmpDTO->retNumIdUsuarioAtual();

        $objMdUtlHistControleDsmpRN = new MdUtlHistControleDsmpRN();
        $objMdUtlHistControleDsmp = $objMdUtlHistControleDsmpRN->consultar($objMdUtlHistControleDsmpDTO);

        return $objMdUtlHistControleDsmp->getNumIdUsuarioAtual();
    }

    // verifica se o colaborador será avaliador dele mesmo
    // caso estiver configurado para ignorar a verificação não interrompe o fluxo
    // 0 - bloqueado
    // 1 OU diferente de 0(zero) liberado
    public static function verificaModoluloLiberarAutoAvaliacaoAtivado()
    {
        $liberado = false;

        // caso o modulo esteja desabilitado para verificacao o sistema ignora a verificacao
        $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
        $paramLiberarAutoAvaliacao = $objInfraParametro->getValor('MODULO_UTILIDADES_LIBERAR_AUTOAVALIACAO', false);
        if (isset($paramLiberarAutoAvaliacao) && $paramLiberarAutoAvaliacao == 1) {
            $liberado = true;
        }
        return $liberado;
    }

    public static function buscarIdUsuarioDitribuidoAnalise($idTriagem)
    {
        $idUsuarioDistribuicaoAnalise = null;

        $objMdUtlControleDsmpDTO = new MdUtlControleDsmpDTO();
        $objMdUtlControleDsmpDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objMdUtlControleDsmpDTO->setNumIdMdUtlTriagem($idTriagem);
        $objMdUtlControleDsmpDTO->setStrStaAtendimentoDsmp(array(MdUtlControleDsmpRN::$EM_ANALISE, MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE), InfraDTO::$OPER_IN);
        $objMdUtlControleDsmpDTO->setStrTipoAcao(array(MdUtlControleDsmpRN::$STR_TIPO_ACAO_DISTRIBUICAO, MdUtlControleDsmpRN::$STR_TIPO_ACAO_RETRIAGEM),InfraDTO::$OPER_IN);
        $objMdUtlControleDsmpDTO->retNumIdUsuarioDistribuicao();
        $objMdUtlControleDsmpDTO->retStrTipoAcao();

        $objMdUtlControleDsmpRN = new MdUtlControleDsmpRN();
        $objMdUtlControleDsmp = $objMdUtlControleDsmpRN->consultar($objMdUtlControleDsmpDTO);

        if ($objMdUtlControleDsmp) {
            $idUsuarioDistribuicaoAnalise = $objMdUtlControleDsmp->getNumIdUsuarioDistribuicao();
        } else {
            $objMdUtlHistControleDsmpDTO = new MdUtlHistControleDsmpDTO();
            $objMdUtlHistControleDsmpDTO->setNumIdMdUtlTriagem($idTriagem);
            $objMdUtlHistControleDsmpDTO->setNumMaxRegistrosRetorno(1);
            $objMdUtlHistControleDsmpDTO->setOrd('IdMdUtlHistControleDsmp', InfraDTO::$TIPO_ORDENACAO_DESC);
            $objMdUtlHistControleDsmpDTO->setStrTipoAcao(array(MdUtlControleDsmpRN::$STR_TIPO_ACAO_DISTRIBUICAO, MdUtlControleDsmpRN::$STR_TIPO_ACAO_RETRIAGEM),InfraDTO::$OPER_IN);
            $objMdUtlHistControleDsmpDTO->setStrStaAtendimentoDsmp(array(MdUtlControleDsmpRN::$EM_ANALISE, MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE), InfraDTO::$OPER_IN);
            $objMdUtlHistControleDsmpDTO->retNumIdUsuarioDistribuicao();

            $objMdUtlHistControleDsmpRN = new MdUtlHistControleDsmpRN();
            $objMdUtlHistControleDsmp = $objMdUtlHistControleDsmpRN->consultar($objMdUtlHistControleDsmpDTO);
            if ($objMdUtlHistControleDsmp) {
                $idUsuarioDistribuicaoAnalise = $objMdUtlHistControleDsmp->getNumIdUsuarioDistribuicao();
            }
        }

        return $idUsuarioDistribuicaoAnalise;
    }

    public static function atualizarControleDesempenhoAoAlterarUsuario($idUsuarioDistribuido, $idTipoControle)
    {
        $objMdUtlControleDsmpDTO = new MdUtlControleDsmpDTO();
        $objMdUtlControleDsmpDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objMdUtlControleDsmpDTO->setNumIdUsuarioDistribuicao($idUsuarioDistribuido);
        $objMdUtlControleDsmpDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTipoControle);
        $objMdUtlControleDsmpDTO->setStrTipoAcao(MdUtlControleDsmpRN::$STR_TIPO_ACAO_DISTRIBUICAO);
        $objMdUtlControleDsmpDTO->retNumIdMdUtlControleDsmp();
        $objMdUtlControleDsmpDTO->retNumTempoExecucao();

        $objMdUtlControleDsmpRN = new MdUtlControleDsmpRN();
        $arrMdUtlControleDsmp = $objMdUtlControleDsmpRN->listar($objMdUtlControleDsmpDTO);

        foreach ($arrMdUtlControleDsmp as $objMdUtlControleDsmp){
            $arrDados = self::retornaDadosPercentualDesempenho($objMdUtlControleDsmp->getNumTempoExecucao(), $idTipoControle, $idUsuarioDistribuido);
            $objMdUtlControleDsmp->setStrStaTipoPresenca($arrDados['strStaTipoPresenca']);
            $objMdUtlControleDsmp->setNumTempoExecucaoAtribuido($arrDados['numTempoExecucao']);
            $objMdUtlControleDsmp->setNumPercentualDesempenho($arrDados['numPercentualDesempenho']);
            $objMdUtlControleDsmpRN->alterar($objMdUtlControleDsmp);
        }
    }

    public static function validaPlanoTrabalho( $arrPost ){
        //arrPost = [0 => id_serie , 1 => numero sei , 2 => id_usuario , 3 => id_prm_gr , 4 => sigla_usuario]
        $xml  = '<Dados>';
      
        $objRnGeral = new MdUtlRegrasGeraisRN();
        $arrDados = $objRnGeral->validaPlanoTrabalho( $arrPost );
        $strErro  = $arrDados['erro'] ? '1' : '0';

        $xml .= '<Erro>'.$strErro.'</Erro>';
        $xml .= '<Msg>'.str_replace('&','&amp;',$arrDados['msg']).'</Msg>';
        $xml .= '</Dados>';
    
        return $xml;
    }
}
