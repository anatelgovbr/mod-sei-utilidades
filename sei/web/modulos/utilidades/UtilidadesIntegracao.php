<?
/**
 * ANATEL
 *
 * 05/07/2018 - criado por jaqueline.mendes - CAST
 *
 */


class UtilidadesIntegracao extends SeiIntegracao
{


    public function __construct()
    {
    }

    public function getNome()
    {
        return 'Utilidades';
    }

    public function getVersao()
    {

        return '1.4.0';
    }

    public function getInstituicao()
    {
        return 'ANATEL (Projeto Colaborativo no Portal do SPB)';
    }

    public function inicializar($strVersaoSEI)
    {

    }


    public function processarControlador($strAcao)
    {

        switch ($strAcao) {
            //EU18974
            case 'md_utl_adm_tp_ctrl_desemp_listar' :
            case 'md_utl_adm_tp_ctrl_desemp_desativar' :
            case 'md_utl_adm_tp_ctrl_desemp_reativar' :
            case 'md_utl_adm_tp_ctrl_desemp_excluir' :
                require_once dirname(__FILE__) . '/md_utl_adm_tp_ctrl_desemp_lista.php';
                return true;

            case 'md_utl_adm_tp_ctrl_desemp_cadastrar':
            case 'md_utl_adm_tp_ctrl_desemp_alterar':
            case 'md_utl_adm_tp_ctrl_desemp_consultar':
                require_once dirname(__FILE__) . '/md_utl_adm_tp_ctrl_desemp_cadastro.php';
                return true;

            //EU18975
            case 'md_utl_adm_prm_gr_cadastrar':
                require_once dirname(__FILE__) . '/md_utl_adm_prm_gr_cadastro.php';
                return true;

            //EU18976
            case 'md_utl_adm_tp_ausencia_listar':
            case 'md_utl_adm_tp_ausencia_desativar':
            case 'md_utl_adm_tp_ausencia_reativar':
            case 'md_utl_adm_tp_ausencia_excluir':
                require_once dirname(__FILE__) . '/md_utl_adm_tp_ausencia_lista.php';
                return true;

            case 'md_utl_adm_tp_ausencia_cadastrar':
            case 'md_utl_adm_tp_ausencia_consultar':
            case 'md_utl_adm_tp_ausencia_alterar':
                require_once dirname(__FILE__) . '/md_utl_adm_tp_ausencia_cadastro.php';
                return true;

            //EU18977
            case 'md_utl_adm_fila_cadastrar':
            case 'md_utl_adm_fila_consultar':
            case 'md_utl_adm_fila_alterar':
                require_once dirname(__FILE__) . '/md_utl_adm_fila_cadastro.php';
                return true;

            case 'md_utl_adm_fila_listar':
            case 'md_utl_adm_fila_selecionar':
            case 'md_utl_adm_fila_desativar':
            case 'md_utl_adm_fila_reativar':
            case 'md_utl_adm_fila_excluir':
                require_once dirname(__FILE__) . '/md_utl_adm_fila_lista.php';
                return true;

            case 'md_utl_adm_usuario_selecionar':
                require_once dirname(__FILE__) . '/md_utl_adm_usuario_lista.php';
                return true;

            //EU18978
            case 'md_utl_adm_jornada_listar':
            case 'md_utl_adm_jornada_desativar':
            case 'md_utl_adm_jornada_reativar':
            case 'md_utl_adm_jornada_excluir':
                require_once dirname(__FILE__) . '/md_utl_adm_jornada_lista.php';
                return true;

            case 'md_utl_adm_jornada_cadastrar':
            case 'md_utl_adm_jornada_alterar':
            case 'md_utl_adm_jornada_consultar':
                require_once dirname(__FILE__) . '/md_utl_adm_jornada_cadastro.php';
                return true;

            //EU19375
            case 'md_utl_adm_tp_revisao_listar':
            case 'md_utl_adm_tp_revisao_desativar':
            case 'md_utl_adm_tp_revisao_reativar':
            case 'md_utl_adm_tp_revisao_excluir':
                require_once dirname(__FILE__) . '/md_utl_adm_tp_revisao_lista.php';
                return true;

            case  'md_utl_adm_tp_revisao_cadastrar':
            case  'md_utl_adm_tp_revisao_alterar':
            case  'md_utl_adm_tp_revisao_consultar':
                require_once dirname(__FILE__) . '/md_utl_adm_tp_revisao_cadastro.php';
                return true;

            case  'md_utl_adm_tp_just_revisao_cadastrar':
            case  'md_utl_adm_tp_just_revisao_alterar':
            case  'md_utl_adm_tp_just_revisao_consultar':
                require_once dirname(__FILE__) . '/md_utl_adm_tp_just_revisao_cadastro.php';
                return true;

            case 'md_utl_adm_tp_just_revisao_listar':
            case 'md_utl_adm_tp_just_revisao_desativar':
            case 'md_utl_adm_tp_just_revisao_reativar':
            case 'md_utl_adm_tp_just_revisao_excluir':
                require_once dirname(__FILE__) . '/md_utl_adm_tp_just_revisao_lista.php';
                return true;

            case 'md_utl_adm_tp_produto_listar':
            case 'md_utl_adm_tp_produto_desativar':
            case 'md_utl_adm_tp_produto_reativar':
            case 'md_utl_adm_tp_produto_excluir':
                require_once dirname(__FILE__) . '/md_utl_adm_tp_produto_lista.php';
                return true;

            case  'md_utl_adm_tp_produto_cadastrar':
            case  'md_utl_adm_tp_produto_alterar':
            case  'md_utl_adm_tp_produto_consultar':
                require_once dirname(__FILE__) . '/md_utl_adm_tp_produto_cadastro.php';
                return true;

            //EU19262
            case 'md_utl_adm_atividade_cadastrar':
            case 'md_utl_adm_atividade_alterar':
            case 'md_utl_adm_atividade_consultar':
                require_once dirname(__FILE__) . '/md_utl_adm_atividade_cadastro.php';
                return true;

            case 'md_utl_adm_atividade_listar':
            case 'md_utl_adm_atividade_desativar':
            case 'md_utl_adm_atividade_reativar':
            case 'md_utl_adm_atividade_excluir':
            case 'md_utl_adm_atividade_selecionar':
                require_once dirname(__FILE__) . '/md_utl_adm_atividade_lista.php';
                return true;

            case 'md_utl_adm_grp_fila_listar':
            case 'md_utl_adm_grp_fila_reativar':
            case 'md_utl_adm_grp_fila_excluir':
            case 'md_utl_adm_grp_fila_desativar':
            case 'md_utl_adm_grp_fila_selecionar':
                require_once dirname(__FILE__) . '/md_utl_adm_grp_fila_lista.php';
                return true;


            case 'md_utl_adm_grp_fila_cadastrar':
            case 'md_utl_adm_grp_fila_alterar':
            case 'md_utl_adm_grp_fila_consultar':
                require_once dirname(__FILE__) . '/md_utl_adm_grp_fila_cadastro.php';
                return true;

            case 'md_utl_adm_grp_fl_proc_atv_cadastrar':
                require_once dirname(__FILE__) . '/md_utl_adm_grp_fl_proc_atv_cadastro.php';
                return true;

            case 'md_utl_adm_rel_prm_gr_proc_selecionar':
                require_once dirname(__FILE__) . '/md_utl_adm_rel_prm_gr_proc_lista.php';
                return true;

            case 'md_utl_adm_just_prazo_cadastrar':
            case 'md_utl_adm_just_prazo_consultar':
            case 'md_utl_adm_just_prazo_alterar':
                require_once dirname(__FILE__) . '/md_utl_adm_just_prazo_cadastro.php';
                return true;

            case 'md_utl_adm_just_prazo_listar':
            case 'md_utl_adm_just_prazo_desativar':
            case 'md_utl_adm_just_prazo_reativar':
            case 'md_utl_adm_just_prazo_excluir':
                require_once dirname(__FILE__) . '/md_utl_adm_just_prazo_lista.php';
                return true;


            case 'md_utl_controle_dsmp_listar':
                require_once dirname(__FILE__) . '/md_utl_controle_dsmp_lista.php';
                return true;


            case 'md_utl_controle_dsmp_associar':
                require_once dirname(__FILE__) . '/md_utl_controle_dsmp_associacao.php';
                return true;

            case 'md_utl_processo_listar':
            case 'md_utl_atribuicao_automatica':
                require_once dirname(__FILE__).'/md_utl_processo_lista.php';
                return true;

            case 'md_utl_triagem_cadastrar':
            case 'md_utl_triagem_alterar':
            case 'md_utl_triagem_consultar':
                require_once dirname(__FILE__).'/md_utl_triagem_cadastro.php';
                return true;

            case 'md_utl_analise_cadastrar':
            case 'md_utl_analise_consultar':
            case 'md_utl_analise_alterar':
            case 'md_utl_analise_bloquear':
                require_once dirname(__FILE__).'/md_utl_analise_cadastro.php';
                return true;

            case 'md_utl_revisao_triagem_cadastrar':
            case 'md_utl_revisao_triagem_consultar':
            case 'md_utl_revisao_analise_cadastrar':
            case 'md_utl_revisao_analise_consultar':
                require_once dirname(__FILE__).'/md_utl_revisao_cadastro.php';
                return true;

            case 'md_utl_distrib_usuario_listar':
                require_once dirname(__FILE__).'/md_utl_distrib_usuario_lista.php';
                return true;

            case 'md_utl_distrib_usuario_cadastrar':
                require_once dirname(__FILE__) . '/md_utl_distrib_usuario_cadastro.php';
                return true;

            case 'md_utl_meus_processos_dsmp_listar':
            case 'md_utl_meus_processos_dsmp_retornar':
                require_once dirname(__FILE__) . '/md_utl_meus_processos_dsmp_lista.php';
                return true;

            case 'md_utl_ajuste_prazo_cadastrar':
            case 'md_utl_ajuste_prazo_alterar':
            case 'md_utl_ajuste_prazo_consultar':
                require_once dirname(__FILE__) . '/md_utl_ajuste_prazo_cadastro.php';
                return true;

            case 'md_utl_gestao_solicitacoes_listar':
            case 'md_utl_gestao_ajust_prazo_aprovar':
            case 'md_utl_gestao_ajust_prazo_reprovar':
            case 'md_utl_gestao_contestacao_aprovar':
            case 'md_utl_gestao_contestacao_reprovar':
                require_once dirname(__FILE__) . '/md_utl_gestao_solicitacoes_lista.php';
                return true;

            case 'md_utl_atividade_triagem_listar':
                require_once dirname(__FILE__) . '/md_utl_atividade_triagem_lista.php';
                return true;

            case 'md_utl_adm_just_contest_listar':
            case 'md_utl_adm_just_contest_desativar':
            case 'md_utl_adm_just_contest_reativar':
            case 'md_utl_adm_just_contest_excluir':
                require_once dirname(__FILE__) . '/md_utl_adm_just_contest_lista.php';
                return true;

            case 'md_utl_adm_just_contest_cadastrar':
            case 'md_utl_adm_just_contest_alterar':
            case 'md_utl_adm_just_contest_consultar':
                require_once dirname(__FILE__) . '/md_utl_adm_just_contest_cadastro.php';
                return true;

            case 'md_utl_adm_prm_contest_cadastrar':
            case 'md_utl_adm_prm_contest_alterar':
                require_once dirname(__FILE__) . '/md_utl_adm_prm_contest_cadastro.php';
                return true;

            case 'md_utl_contest_revisao_cadastrar':
            case 'md_utl_contest_revisao_alterar':
            case 'md_utl_contest_revisao_consultar':
                require_once dirname(__FILE__) . '/md_utl_contest_revisao_cadastro.php';
                return true;

            //EU34423
            case 'md_utl_adm_prm_ds_cadastrar':
                require_once dirname(__FILE__) . '/md_utl_adm_prm_ds_cadastro.php';
                return true;

            case 'md_utl_adm_status_selecionar':
                require_once dirname(__FILE__) . '/md_utl_adm_status_lista.php';
                return true;
        }


        return false;
    }

    public function processarControladorAjax($strAcao)
    {

        $xml = null;

        switch ($_GET['acao_ajax']) {


            case 'md_utl_adm_usuario_interno_auto_completar':
                $arrObjUsuarioDTO = MdUtlAdmPrmGrUsuINT::autoCompletarUsuariosInternos($_POST['id_orgao'], $_POST['palavras_pesquisa'], $_GET['id_tipo_controle_utl']);
                $isBolIdUsuario = array_key_exists('is_bol_usuario', $_GET) && $_GET['is_bol_usuario'] == 1;
                $nameParam = $isBolIdUsuario ? 'IdUsuario' : 'IdMdUtlAdmPrmGrUsu';
                $xml = InfraAjax::gerarXMLItensArrInfraDTO($arrObjUsuarioDTO, $nameParam, 'Sigla');
                break;

            case 'md_utl_adm_usuario_participante_auto_completar':
                $arrObjUsuarioDTO = MdUtlAdmPrmGrUsuINT::autoCompletarUsuarioParticipante($_POST['palavras_pesquisa'], $_GET['id_fila'], $_GET['id_status']);
                $xml = InfraAjax::gerarXMLItensArrInfraDTO($arrObjUsuarioDTO, 'IdUsuario', 'Sigla');
                break;

            case 'md_utl_adm_tipo_processo_auto_completar':
                $arrObjTipoProcessoDTO = MdUtlAdmPrmGrINT::autoCompletarTipoProcedimento($_POST['palavras_pesquisa']);
                $xml = InfraAjax::gerarXMLItensArrInfraDTO($arrObjTipoProcessoDTO, 'IdTipoProcedimento', 'Nome');
                break;

            case 'md_utl_adm_processo_parametrizado_auto_completar':
                $arrObjTipoProcessoDTO = MdUtlAdmRelPrmGrProcINT::autoCompletarTipoProcedimentoPorParametrizacao($_POST['palavras_pesquisa'], $_GET['id_parametro']);
                $xml = InfraAjax::gerarXMLItensArrInfraDTO($arrObjTipoProcessoDTO, 'IdTipoProcedimento', 'NomeProcedimento');
                break;

            case 'md_utl_adm_fila_buscar_nome_usuario':
                $xml = MdUtlAdmPrmGrUsuINT::buscarNomeDescricaoUsuario($_POST['arrIdsVinculo']);
                break;

            case 'md_utl_adm_prm_buscar_nome_usuario':
                $xml = MdUtlAdmPrmGrUsuINT::buscarNomeDescricaoUsuarioSelecionado($_POST['arrIdsVinculo']);
                break;

            case 'md_utl_adm_prm_vinculo_usuario_fila':
                $xml = MdUtlAdmPrmGrUsuINT::consultarVinculoFilaUsuario($_POST['idVinculo']);
                break;

            case 'md_utl_adm_prm_vinculo_usuario_parametrizado_fila':
                $xml = MdUtlAdmPrmGrUsuINT::consultarVinculoParametrizacaoUsuario($_POST['idVinculo'], $_POST['idFila']);
                break;

            case 'md_utl_adm_buscar_links_assinados':
                $xml = MdUtlAdmJornadaINT::buscarUrlsAssinadasPorTipoControle($_POST['idTipoControle'], $_POST['validarParams']);
                break;

            case 'md_utl_adm_validar_duplicidade_jornada':
                $xml = MdUtlAdmJornadaINT::validarDuplicidadeJornada($_POST['idTipoControle'], $_POST['txtNome'], $_POST['idJornada']);
                break;

            case 'md_utl_adm_fila_auto_completar':
                $isPrmDistr = array_key_exists('is_prm_distr',$_GET) && $_GET['is_prm_distr'] == 1 ? true : false;
                $arrObjs = MdUtlAdmFilaINT::autoCompletarFilas($_POST['palavras_pesquisa'], $_GET['id_tipo_controle_utl'], $isPrmDistr);
                $xml = InfraAjax::gerarXMLItensArrInfraDTO($arrObjs, 'IdMdUtlAdmFila', 'Nome');
                break;

            case 'md_utl_adm_atividade_auto_completar':
                $isPrmDistr = array_key_exists('is_prm_distr',$_GET) && $_GET['is_prm_distr'] == 1 ? true : false;
                $xml = MdUtlAdmAtividadeINT::autoCompletarAtividade($_POST['palavras_pesquisa'], $_GET['id_tipo_controle_utl'], $isPrmDistr);
                break;

            case 'md_utl_adm_status_auto_completar':
                $isPrmDistr = array_key_exists('is_prm_distr',$_GET) && $_GET['is_prm_distr'] == 1 ? true : false;
                $xml = MdUtlAdmPrmDsINT::autoCompletarStatus($_POST['palavras_pesquisa'], $_GET['id_tipo_controle_utl'], $isPrmDistr);
                break;

            case 'md_utl_adm_atividade_filtro_auto_completar':
                $grupoAtv = array_key_exists('ids_grupo_atv', $_POST) ? $_POST['ids_grupo_atv'] : null;
                $xml = MdUtlAdmAtividadeINT::autoCompletarAtividadeFiltroGrupo($_POST['palavras_pesquisa'], $_GET['id_tipo_controle_utl'], $grupoAtv, $_GET['id_tipo_procedimento']);
                break;

            case 'md_utl_adm_grp_fila_auto_completar':
                $arrObj = MdUtlAdmGrpFilaINT::autoCompletarGrupoFilaAtividade($_POST, $_GET);
                $xml = InfraAjax::gerarXMLItensArrInfraDTO($arrObj, 'IdMdUtlAdmGrpFila', 'NomeGrupoAtividade');
                break;

            case 'md_utl_adm_validar_documento_sei':
                $xml = MdUtlAnaliseINT::validarDocumentoSEI($_POST['numeroSEI'], $_POST['idSerieSolicitado'], $_POST['idProcedimento']);
                break;

            case 'md_utl_adm_validar_grupo_atividade':
                $xml = MdUtlTriagemINT::validarGrupoAtividade($_POST['idsGrupoAtividade'], $_GET['id_tipo_procedimento'], $_GET['id_tipo_controle_utl']);
                break;

            case 'md_utl_adm_buscar_ultimas_filas':
                $xml = MdUtlControleDsmpINT::retornaXmlUltimasFilas($_POST['jsonIdsProcedimento'], $_POST['idProcedimento'], $_POST['isDetalhamento']);
                break;

            case 'md_utl_adm_usuario_participante_auto_completar':
                $arrObjUsuarioParticipanteDTO = MdUtlAdmFilaPrmGrUsuINT::autoCompletarUsuarioParticipante($_POST['palavras_pesquisa'], $_GET['id_fila'], $_GET['id_status']);
                $xml = InfraAjax::gerarXMLItensArrInfraDTO($arrObjUsuarioParticipanteDTO, 'IdUsuario' , 'Sigla');
                break;

            case 'md_utl_validar_alt_tipo_atividade':
                $xml = MdUtlControleDsmpINT::validarTrocaTipoAtividade($_POST['id_atividade']);
                break;

            case 'calcular_prazo_data_just':
                $xml = MdUtlAjustePrazoINT::calcularPrazoJustificativa($_POST['prazoDias'], $_POST['idControle'], $_POST['tipoSolicitacao'], $_POST['prazoData'],  $_POST['isPrazo'], $_POST['prazoInicial']);
                break;

            case 'md_utl_buscar_dados_carga_usuario':
                $xml = MdUtlAdmPrmGrUsuINT::buscarDadosCargaUsuario($_POST['idUsuarioParticipante'], $_POST['idParam'], $_POST['numCargaPadrao'], $_POST['numPercentualTele'], $_POST['staFrequencia'], $_POST['idTipoControle'], $_POST['inicioPeriodo']);
                break;

            case 'md_utl_usuario_auto_completar':
                $arrObjUsuarioDTO = MdUtlAdmPrmGrINT::autoCompletarUsuarios($_POST['id_orgao'],$_POST['palavras_pesquisa'],false,false,true,false);
                $xml = InfraAjax::gerarXMLItensArrInfraDTO($arrObjUsuarioDTO, 'IdUsuario', 'Sigla');
                break;

        }

        return $xml;
    }

    public function processarControladorPublicacoes($strAcao)
    {

        switch ($strAcao) {

            case 'abc_publicacao_exemplo':
                require_once dirname(__FILE__) . '/publicacao_exemplo.php';
                return true;
        }

        return false;
    }

    public function processarControladorAjaxExterno($strAcaoAjax)
    {
        $xml = null;
        switch ($strAcaoAjax) {
        }

        return $xml;
    }

    public function processarControladorExterno($strAcao)
    {

        switch ($strAcao) {


        }

        return false;
    }

    public function montarBotaoProcesso(ProcedimentoAPI $objProcedimentoAPI)
    {
        $isAcesso  = SessaoSEI::getInstance()->verificarPermissao('md_utl_controle_dsmp_listar');
        $arrBotoes = array();

        if($isAcesso) {
            $objMdUtlControleDsmpRN = new MdUtlControleDsmpRN();
            $objMdUtlHsControleDsmpRN = new MdUtlHistControleDsmpRN();
            $objMdUtlAdmTpCtrlUndRN = new MdUtlAdmRelTpCtrlDesempUndRN();
            $objMdUtlAdmUtlTpCtrlRN = new MdUtlAdmTpCtrlDesempRN();
            $idTipoControle = $objMdUtlAdmTpCtrlUndRN->getTipoControleUnidadeLogada();

            $idProcedimento = $objProcedimentoAPI->getIdProcedimento();
            $isParametrizado = false;

            if (!is_null($idTipoControle)) {


                $isParametrizado = $objMdUtlAdmUtlTpCtrlRN->verificaTipoControlePossuiParametrizacao($idTipoControle);

                if ($isParametrizado) {
                    $isHistorico = $objMdUtlHsControleDsmpRN->verificaProcessoPossuiHistoricoDsmp($idProcedimento);
                    $arrObjsTpProcesso = $objMdUtlControleDsmpRN->getTiposProcessoTipoControle();
                    $idsTpProcesso = count($arrObjsTpProcesso) > 0 ? InfraArray::converterArrInfraDTO($arrObjsTpProcesso, 'IdTipoProcedimento') : null;
                    $idTpProcesso = $objProcedimentoAPI->getIdTipoProcedimento();
                    $idUnidade = $objProcedimentoAPI->getIdUnidadeGeradora();

                    if ((in_array($idTpProcesso, $idsTpProcesso)) || $isHistorico) {
                        $arrBotoes[] = '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_processo_listar&id_procedimento=' . $objProcedimentoAPI->getIdProcedimento()) . '" class="botaoSEI" tabindex="' . PaginaSEI::getInstance()->getProxTabBarraComandosSuperior() . '"><img src="modulos/utilidades/imagens/triagem_analise_processo.png" class="infraCorBarraSistema" alt="Ver detalhamento do Processo - Controle de Desempenho" title="Ver detalhamento do Processo - Controle de Desempenho"/></a>';
                    }
                }
            }
        }

        return $arrBotoes;
    }

    public function excluirUsuario($arrObjUsuarioAPI)
    {
        $mdPetRegrasGeraisRN = new MdUtlRegrasGeraisRN();
        $msg = $mdPetRegrasGeraisRN->verificarExistenciaUsuario(array($arrObjUsuarioAPI, 'excluir'));
        if ($msg != '') {
            $objInfraException = new InfraException();
            $objInfraException->lancarValidacao($msg);
        } else {
            return $arrObjUsuarioAPI;
        }
    }

    public function desativarUsuario($arrObjUsuarioAPI)
    {
        $mdPetRegrasGeraisRN = new MdUtlRegrasGeraisRN();
        $msg = $mdPetRegrasGeraisRN->verificarExistenciaUsuario(array($arrObjUsuarioAPI, 'desativar'));
        if ($msg != '') {
            $objInfraException = new InfraException();
            $objInfraException->lancarValidacao($msg);
        } else {
            return $arrObjUsuarioAPI;
        }
    }

    public function excluirUnidade($arrObjUnidadeAPI)
    {
        $msg       = '';
        $idUnidade = $arrObjUnidadeAPI[0]->getIdUnidade();

        $objRNGerais = new MdUtlRegrasGeraisRN();
        $objDsmpRN = new MdUtlControleDsmpRN();
        $msg = $objRNGerais->verificarExistenciaUnidade(array($arrObjUnidadeAPI, 'excluir'));

        if($msg == '') {
            $msg = $objDsmpRN->verificaUnidadeControleDsmp($idUnidade);
        }

        if ($msg != '') {
            $objInfraException = new InfraException();
            $objInfraException->lancarValidacao($msg);
            return false;
        } else{
            return true;
        }
    }

    public function desativarUnidade($arrObjUnidadeAPI)
    {
        $idUnidade = $arrObjUnidadeAPI[0]->getIdUnidade();

        $mdPetRegrasGeraisRN = new MdUtlRegrasGeraisRN();

        $msg = '';
        $msg = $mdPetRegrasGeraisRN->verificarExistenciaUnidade(array($arrObjUnidadeAPI, 'desativar'));

        if ($msg != '') {

            $objInfraException = new InfraException();
            $objInfraException->lancarValidacao($msg);
        } else {
            $objMdUtlControleDsmpRN = new MdUtlControleDsmpRN();
            $objMdUtlControleDsmpRN->desativarControleDsmpObjs(array(null, array($idUnidade)));
            return $arrObjUnidadeAPI;
        }
    }

    public function excluirTipoDocumento($arrObjTpDocumento)
    {
        $mdPetRegrasGeraisRN = new MdUtlRegrasGeraisRN();
        $msg = $mdPetRegrasGeraisRN->verificarExistenciaTipoDocumento(array($arrObjTpDocumento, 'excluir'));
        if ($msg != '') {
            $objInfraException = new InfraException();
            $objInfraException->lancarValidacao($msg);
        } else {
            return $arrObjTpDocumento;
        }
    }

    public function desativarTipoDocumento($arrObjTpDocumento)
    {
        $mdPetRegrasGeraisRN = new MdUtlRegrasGeraisRN();
        $msg = $mdPetRegrasGeraisRN->verificarExistenciaTipoDocumento(array($arrObjTpDocumento, 'desativar'));
        if ($msg != '') {
            $objInfraException = new InfraException();
            $objInfraException->lancarValidacao($msg);
        } else {
            return $arrObjTpDocumento;
        }
    }

    public function excluirTipoProcesso($arrObjTipoProcedimentoDTO)
    {
        $mdPetRegrasGeraisRN = new MdUtlRegrasGeraisRN();
        $msg = $mdPetRegrasGeraisRN->verificarExistenciaTipoProcesso(array($arrObjTipoProcedimentoDTO, 'excluir'));
        if ($msg != '') {
            $objInfraException = new InfraException();
            $objInfraException->lancarValidacao($msg);
        } else {
            return $arrObjTipoProcedimentoDTO;
        }
    }

    public function desativarTipoProcesso($arrObjTipoProcedimentoDTO)
    {
        $mdPetRegrasGeraisRN = new MdUtlRegrasGeraisRN();
        $msg = $mdPetRegrasGeraisRN->verificarExistenciaTipoProcesso(array($arrObjTipoProcedimentoDTO, 'desativar'));
        if ($msg != '') {
            $objInfraException = new InfraException();
            $objInfraException->lancarValidacao($msg);
        } else {
            return $arrObjTipoProcedimentoDTO;
        }
    }

    /**
     * Valida se o Processo onde est? realizando a anexa??o de processo possui V?nculo com Intima??o
     */
    public function sobrestarProcesso(ProcedimentoAPI $objProcedimentoAPI, $objProcedimentoAPIVinculado)
    {
        $objControleDsmpRN = new MdUtlControleDsmpRN();
        $dados = $objControleDsmpRN->verificaProcessoAtivoDsmp(array($objProcedimentoAPI->getIdProcedimento()));
        $msg = $dados['MSG'];

        if($msg != '')
        {
            $strProcesso = explode('-',$msg);
            $numeroProcesso = $strProcesso[1].'-'.$strProcesso[2];
            $numeroProcesso = str_replace('\n', '' , $numeroProcesso);
            $numeroProcesso = trim($numeroProcesso);

            $corpoMsg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_79, $numeroProcesso);
            $objInfraException = new InfraException();
            $objInfraException->lancarValidacao($corpoMsg);
        }

        return $msg == '';
    }

    public function concluirProcesso($arrObjProcedimentoAPI)
    {
        $objInfraException  = new InfraException();
        //$objInfraException->lancarValidacao('teste');

        $objControleDsmpRN = new MdUtlControleDsmpRN();

        $arrIdsProcedimento = array();
        $getIdsProcedimento = function ($obj) use (&$arrIdsProcedimento) {
            $arrIdsProcedimento[] = $obj->getIdProcedimento();
        };

        array_map($getIdsProcedimento, $arrObjProcedimentoAPI);

        $dados = $objControleDsmpRN->verificaProcessoAtivoDsmp(array($arrIdsProcedimento, SessaoSEI::getInstance()->getNumIdUnidadeAtual()));
        $msg   = $dados['MSG'];
        $countProcessos = $dados['COUNT'];

        if ($msg != '') {
            if($countProcessos == 1){
                $strProcesso = explode('-',$msg);
                $numeroProcesso = $strProcesso[1].'-'.$strProcesso[2];
                $numeroProcesso = str_replace('\n', '' , $numeroProcesso);
                $numeroProcesso = trim($numeroProcesso);
                $corpoMsg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_65, $numeroProcesso);
            }else {
                $corpoMsg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_66);
                $corpoMsg .= '\n';
                $corpoMsg .= $msg;
            }

            $objInfraException  = new InfraException();
            $objInfraException->lancarValidacao($corpoMsg);
        }

        return $msg == '';
    }

    public function enviarProcesso($arrObjProcedimentoAPI, $arrObjUnidadeAPI)
    {
        $chkSinManterUnidade = $_POST['chkSinManterAberto'];


        if($chkSinManterUnidade != 'on') {
            $objControleDsmpRN = new MdUtlControleDsmpRN();
            $arrIdsProcedimento = array();

            $getIdsProcedimento = function ($obj) use (&$arrIdsProcedimento) {
                $arrIdsProcedimento[] = $obj->getIdProcedimento();
            };

            array_map($getIdsProcedimento, $arrObjProcedimentoAPI);

            $dados = $objControleDsmpRN->verificaProcessoAtivoDsmp(array($arrIdsProcedimento, SessaoSEI::getInstance()->getNumIdUnidadeAtual()));
            $msg   = $dados['MSG'];
            $countProcessos = $dados['COUNT'];

            if ($msg != '') {
                if($countProcessos == 1){
                    $strProcesso = explode('-',$msg);
                    $numeroProcesso = $strProcesso[1].'-'.$strProcesso[2];
                    $numeroProcesso = str_replace('\n', '' , $numeroProcesso);
                    $numeroProcesso = trim($numeroProcesso);
                    $corpoMsg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_67, $numeroProcesso);
                }else {
                    $corpoMsg =  MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_68);
                    $corpoMsg .= '\n';
                    $corpoMsg .= $msg;
                }

                $objInfraException  = new InfraException();
                $objInfraException->lancarValidacao($corpoMsg);
            }


            return $msg == '';
        }
    }

    public function anexarProcesso(ProcedimentoAPI $objProcedimentoAPIPrincipal, ProcedimentoAPI $objProcedimentoAPIAnexado)
    {
        $objControleDsmpRN = new MdUtlControleDsmpRN();
        $dados = $objControleDsmpRN->verificaProcessoAtivoDsmp(array($objProcedimentoAPIAnexado->getIdProcedimento()));
        $msg = $dados['MSG'];

        if($msg != '')
        {
            $strProcesso = explode('-',$msg);
            $numeroProcesso = $strProcesso[1].'-'.$strProcesso[2];
            $numeroProcesso = str_replace('\n', '' , $numeroProcesso);
            $numeroProcesso = trim($numeroProcesso);
            $corpoMsg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_69, $numeroProcesso);

            $objInfraException = new InfraException();
            $objInfraException->lancarValidacao($corpoMsg);
        }

        return $msg == '';
    }

    public function excluirProcesso(ProcedimentoAPI $objProcedimentoAPI)
    {
        $objRNGerais = new MdUtlRegrasGeraisRN();
        $idProcedimentoAPI = $objProcedimentoAPI->getIdProcedimento();
        $objProtocoloDTO = $objRNGerais->getObjProcedimentoPorId($idProcedimentoAPI);
        $objMdUtlControleDsmpRN  = new MdUtlControleDsmpRN();
        $objMdUtlControleDsmpDTO = new MdUtlControleDsmpDTO();

        $objMdUtlControleDsmpDTO->setDblIdProcedimento($idProcedimentoAPI);
        $objMdUtlControleDsmpDTO->retTodos();
        $objMdUtlControleDsmpDTO->setStrSinVerificarPermissao('N');
        $countProcesso = $objMdUtlControleDsmpRN->contar($objMdUtlControleDsmpDTO);

        $objMdUtlHistControleDsmpRN  = new MdUtlHistControleDsmpRN();
        $objMdUtlHistControleDsmpDTO = new MdUtlHistControleDsmpDTO();
        $objMdUtlHistControleDsmpDTO->setStrSinVerificarPermissao('N');
        $objMdUtlHistControleDsmpDTO->setDblIdProcedimento($idProcedimentoAPI);
        $objMdUtlHistControleDsmpDTO->retTodos();
        $countHsProcesso = $objMdUtlHistControleDsmpRN->contar($objMdUtlHistControleDsmpDTO);

        if($countProcesso>0 || $countHsProcesso > 0){
            $objInfraException = new InfraException();
            $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_61, $objProtocoloDTO->getStrProtocoloProcedimentoFormatado());
            $objInfraException->lancarValidacao($msg);
        }

        return parent::excluirProcesso($objProcedimentoAPI); // TODO: Change the autogenerated stub
    }

    public function excluirDocumento(DocumentoAPI $objDocumentoAPI)
    {
        $idDocumento = $objDocumentoAPI->getIdDocumento();
        $objMdUtlControleRN = new MdUtlControleDsmpRN();

        $isValidoExclusao = $objMdUtlControleRN->validaExclusaoDocumento($objDocumentoAPI);

        if($isValidoExclusao){
            return parent::excluirDocumento($objDocumentoAPI);
        }else{
            $objInfraException = new InfraException();
            $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_60);
            return $objInfraException->lancarValidacao($msg);
        }

    }

    public function montarIconeProcesso(ProcedimentoAPI $objProcedimentoAPI)
    {
        $isAcesso  = SessaoSEI::getInstance()->verificarPermissao('md_utl_controle_dsmp_listar');

        if($isAcesso) {
            $dblIdProcedimento = $objProcedimentoAPI->getIdProcedimento();
            $objRegrasGeraisRN = new MdUtlRegrasGeraisRN();
            $objTpCtrlUtlUndRN = new MdUtlAdmRelTpCtrlDesempUndRN();
            $objTpControleDTO = $objTpCtrlUtlUndRN->getObjTipoControleUnidadeLogada();
            if (!is_null($objTpControleDTO)) {
                $nomeTpCtrl = 'Controle de Desempenho - ' . $objTpControleDTO->getStrNomeTipoControle() . ': ' . SessaoSEI::getInstance()->getStrSiglaUnidadeAtual();

                $arrRetorno = $objRegrasGeraisRN->retornaDadosIconesProcesso($dblIdProcedimento, $nomeTpCtrl);

                if (count($arrRetorno) > 0) {
                    $tipo = 'UTILIDADES';
                    $id = 'UTL_' . $dblIdProcedimento;
                    $title = $arrRetorno['TOOLTIP'];;
                    $icone = $arrRetorno['IMG'];

                    $objArvoreAcaoItemAPI = new ArvoreAcaoItemAPI();
                    $objArvoreAcaoItemAPI->setTipo($tipo);
                    $objArvoreAcaoItemAPI->setId($id);
                    $objArvoreAcaoItemAPI->setIdPai($dblIdProcedimento);
                    $objArvoreAcaoItemAPI->setTitle($title);
                    $objArvoreAcaoItemAPI->setIcone($icone);
                    $objArvoreAcaoItemAPI->setTarget(null);
                    $objArvoreAcaoItemAPI->setHref('javascript:;');
                    $objArvoreAcaoItemAPI->setSinHabilitado('S');
                    $arrObjArvoreAcaoItemAPI[] = $objArvoreAcaoItemAPI;

                    return $arrObjArvoreAcaoItemAPI;
                }
            }
        }
    }

    public function montarIconeControleProcessos($arrObjProcedimentoDTO)
    {
        $isAcesso  = SessaoSEI::getInstance()->verificarPermissao('md_utl_controle_dsmp_listar');
        if($isAcesso) {
            $arrParam = array();
            $arrsIds = array();

            if ($arrObjProcedimentoDTO != null && count($arrObjProcedimentoDTO) > 0) {

                foreach ($arrObjProcedimentoDTO as $objProcedimentoAPI) {
                    $dblIdProcedimento = $objProcedimentoAPI->getIdProcedimento();
                    $arrsIds[] = $dblIdProcedimento;
                }

                $objRegrasGeraisRN = new MdUtlRegrasGeraisRN();
                $arrParam = $objRegrasGeraisRN->retornaDadosIconesProcesso($arrsIds);

            }

            return $arrParam;
        }
    }

    public function montarIconeAcompanhamentoEspecial($arrObjProcedimentoDTO)
    {

        $isAcesso  = SessaoSEI::getInstance()->verificarPermissao('md_utl_controle_dsmp_listar');
        if($isAcesso) {
            $arrsIds = array();

            if ($arrObjProcedimentoDTO != null && count($arrObjProcedimentoDTO) > 0) {

                foreach ($arrObjProcedimentoDTO as $objProcedimentoAPI) {
                    $dblIdProcedimento = $objProcedimentoAPI->getIdProcedimento();
                    $arrsIds[] = $dblIdProcedimento;
                }

                $objRegrasGeraisRN = new MdUtlRegrasGeraisRN();
                $arrParam = $objRegrasGeraisRN->retornaDadosIconesProcesso($arrsIds);

            }

            return $arrParam;
        }

    }
}

?>
