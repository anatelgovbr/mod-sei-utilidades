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
        return 'SEI Desempenho e Utilidades';
    }

    public function getVersao()
    {

        return '2.1.0';
    }

    public function getInstituicao()
    {
        return 'Anatel - Agência Nacional de Telecomunicações';
    }

    public function inicializar($strVersaoSEI)
    {

    }
	
    public function obterDiretorioIconesMenu()
    {
        return 'modulos/utilidades/menu';
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

            case 'md_utl_controle_dsmp_detalhar':
                require_once dirname(__FILE__) . '/md_utl_controle_dsmp_detalhe.php';
                return true;

            case 'md_utl_controle_dsmp_associar':
                require_once dirname(__FILE__) . '/md_utl_controle_dsmp_associacao.php';
                return true;

            case 'md_utl_processo_listar':
            case 'md_utl_atribuicao_automatica':
                require_once dirname(__FILE__) . '/md_utl_processo_lista.php';
                return true;

            case 'md_utl_triagem_cadastrar':
            case 'md_utl_triagem_alterar':
            case 'md_utl_triagem_consultar':
                require_once dirname(__FILE__) . '/md_utl_triagem_cadastro.php';
                return true;

            case 'md_utl_analise_cadastrar':
            case 'md_utl_analise_consultar':
            case 'md_utl_analise_alterar':
            case 'md_utl_analise_bloquear':
                require_once dirname(__FILE__) . '/md_utl_analise_cadastro.php';
                return true;

            case 'md_utl_revisao_triagem_cadastrar':
            case 'md_utl_revisao_triagem_consultar':
            case 'md_utl_revisao_analise_cadastrar':
            case 'md_utl_revisao_analise_consultar':
                require_once dirname(__FILE__) . '/md_utl_revisao_cadastro.php';
                return true;

            case 'md_utl_distrib_usuario_listar':
            case 'md_utl_distrib_usuario_retornar':
                require_once dirname(__FILE__) . '/md_utl_distrib_usuario_lista.php';
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

            case 'md_utl_adm_atribuir_proximo':
                require_once dirname(__FILE__) . '/md_utl_adm_status_lista.php';
                return true;

            case 'md_utl_distribuir_para_mim':
                require_once dirname(__FILE__) . '/md_utl_meus_processos_dist_mim.php';
                return true;

            case 'md_utl_adm_prm_gr_ex_participantes':
                require_once dirname(__FILE__) . '/md_utl_adm_prm_gr_ex_participantes.php';
                return true;

            case 'md_utl_adm_integracao_cadastrar':
            case 'md_utl_adm_integracao_alterar':
            case 'md_utl_adm_integracao_consultar':
                require_once dirname(__FILE__) . '/md_utl_adm_integracao_cadastro.php';
                return true;

            case 'md_utl_adm_integracao_listar':
            case 'md_utl_adm_integracao_excluir':
            case 'md_utl_adm_integracao_desativar':
            case 'md_utl_adm_integracao_reativar':
                require_once dirname(__FILE__) . '/md_utl_adm_integracao_lista.php';
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
                $arrObjUsuarioDTO = MdUtlAdmPrmGrUsuINT::autoCompletarUsuarioParticipante($_POST['palavras_pesquisa'], $_GET['id_fila'], $_GET['id_status'],$_POST['arr_procedimentos']);
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

            case 'md_utl_adm_prm_vinculo_tp_processo_desempenho':
                $xml = MdUtlAdmRelPrmDsProcINT::consultarVinculoProcDistribuicao($_POST['idVinculo'], $_POST['idControle']);
                break;

            case 'md_utl_adm_prm_valida_plano_trab':
                $xml = MdUtlAdmPrmGrUsuINT::validaPlanoTrabalho( $_POST );
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
                $isPrmDistr = array_key_exists('is_prm_distr', $_GET) && $_GET['is_prm_distr'] == 1 ? true : false;
                $arrObjs = MdUtlAdmFilaINT::autoCompletarFilas($_POST['palavras_pesquisa'], $_GET['id_tipo_controle_utl'], $isPrmDistr);
                $xml = InfraAjax::gerarXMLItensArrInfraDTO($arrObjs, 'IdMdUtlAdmFila', 'Nome');
                break;

            case 'md_utl_adm_atividade_auto_completar':
                $isPrmDistr = array_key_exists('is_prm_distr', $_GET) && $_GET['is_prm_distr'] == 1 ? true : false;
                $xml = MdUtlAdmAtividadeINT::autoCompletarAtividade($_POST['palavras_pesquisa'], $_GET['id_tipo_controle_utl'], $isPrmDistr);
                break;

            case 'md_utl_adm_status_auto_completar':
                $isPrmDistr = array_key_exists('is_prm_distr', $_GET) && $_GET['is_prm_distr'] == 1 ? true : false;
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
                $xml = InfraAjax::gerarXMLItensArrInfraDTO($arrObjUsuarioParticipanteDTO, 'IdUsuario', 'Sigla');
                break;

            case 'md_utl_validar_alt_tipo_atividade':
                $xml = MdUtlControleDsmpINT::validarTrocaTipoAtividade($_POST['id_atividade']);
                break;

            case 'calcular_prazo_data_just':
                $xml = MdUtlAjustePrazoINT::calcularPrazoJustificativa($_POST['prazoDias'], $_POST['idControle'], $_POST['tipoSolicitacao'], $_POST['prazoData'], $_POST['isPrazo'], $_POST['prazoInicial']);
                break;

            case 'md_utl_buscar_dados_carga_usuario':
                $xml = MdUtlAdmPrmGrUsuINT::buscarDadosCargaUsuario($_POST['idUsuarioParticipante'], $_POST['idParam'], $_POST['numCargaPadrao'], $_POST['numPercentualTele'], $_POST['staFrequencia'], $_POST['idTipoControle'], $_POST['inicioPeriodo']);
                break;

            case 'md_utl_buscar_dados_carga_usuario_todos_tpctrl':
                $xml = MdUtlAdmPrmGrUsuINT::buscarDadosCargaUsuarioCompleto( $_POST );
                break;

            case 'md_utl_buscar_dados_regime_trabalho':
                $arrRetorno = MdUtlAdmPrmGrUsuINT::retornaCalculoPercentualDesempenho($_POST['nunEsforco'], $_POST['idTipoControle'], $_POST['idUsuarioParticipante']);
                $xml = '<Documento>';
                $xml .= '<ValorDistribuicao>' . $arrRetorno . '</ValorDistribuicao>';
                $xml .= '</Documento>';
                break;

            case 'md_utl_usuario_auto_completar':
                $arrObjUsuarioDTO = MdUtlAdmPrmGrINT::autoCompletarUsuarios($_POST['id_orgao'], $_POST['palavras_pesquisa'], false, false, true, false);
                $xml = InfraAjax::gerarXMLItensArrInfraDTO($arrObjUsuarioDTO, 'IdUsuario', 'Sigla');
                break;

            case 'md_utl_atribuir_proximo':
                $rs = MdUtlRegrasGeraisRN::atribuirProximoPrioridade();
                if( is_array( $rs ) && array_key_exists('erro' , $rs)){
                    $xml = '<Erro><Msg>'.$rs['msg'].'</Msg></Erro>';
                }else{
                    $xml = $rs;
                }
                break;

            case 'md_utl_ctrl_dsmp_tp_procedimento':
                $xml = MdUtlControleDsmpINT::validaAssociarProcessoAFila();
                break;
            case 'md_utl_hist_controle_dsmp_tp_controle':
                $retorno = MdUtlHistControleDsmpINT::retornarHistoricoPorTipoDeControle($_POST['idProcedimento'],$_POST['idTipoControleSelecionado'],$_POST['strStatusAtual'],$_POST['strTitulo']);
                $xml = '<Documento>';
                $xml .= '<NovaTabela>' . $retorno . '</NovaTabela>';
                $xml .= '</Documento>';
                break;

            case 'md_utl_val_distrib_multiplo':
                $xml = MdUtlControleDsmpINT::validaDistribuicaoMultiplo();
                break;

            case 'md_utl_verificar_pode_distrib_para_mim':
                $retorno = MdUtlAdmPrmGrUsuINT::verificaPermissaoDistribuirParaMim($_POST['idProcedimento']);
                $xml = '<Documento>';
                $xml .= '<PermiteDistribuirParaMim>' . $retorno . '</PermiteDistribuirParaMim>';
                $xml .= '</Documento>';
                break;

            case 'md_utl_verificar_pode_distrib_para_colaborador':
                $retorno = UtilidadesIntegracao::verificaPermissaoDistribuirParaColaborador($_POST['idProcedimento'],$_POST['idUsuario']);
                $xml = '<Documento>';
                $xml .= '<PermiteDistribuirParaMim>' . $retorno . '</PermiteDistribuirParaMim>';
                $xml .= '</Documento>';
                break;

            case 'montar_link_md_utl_adm_usuario_selecionar':

                $arr = $_POST['arrProcedimentos'];
                $arr = implode(', ', $arr);

                $retorno = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_usuario_selecionar&tipo_selecao=1&id_tipo_controle_utl=' . $_POST['idTipoControle'] . '&is_bol_distribuicao=1&id_fila=' . $_POST['idFila'] . '&id_status=' . $_POST['idStatus'] . '&arr_procedimentos='. $arr .'&id_object=objLupaUsuarioParticipante');
                $xml = '<Documento>';
                $xml .= '<LinkUsuario>' . $retorno . '</LinkUsuario>';
                $xml .= '</Documento>';
                break;

            case 'md_utl_validar_just_prazo_excluir':
                $retorno = ( new MdUtlAdmJustPrazoRN() )->validarExclusao() ? 'N' : 'S';                
                $xml = '<Documento>';
                $xml .= '<Resultado>'. $retorno .'</Resultado>';
                $xml .= '</Documento>';
                break;
            
            case 'md_utl_usuario_pertence_fila':
                $rs = ( new MdUtlAdmFilaRN() )->verificaUsuarioLogadoPertenceFila( 
                    [ $_POST['idFila'] , $_POST['status'] , true , $_POST['id_usuario'] ]
                );

                $rs = $rs > 0 ? 'S' : 'N';

                $xml = '<Documento>';
                $xml .= '<Resultado>'. $rs .'</Resultado>';
                $xml .= '</Documento>';
                break;

            case 'md_utl_integracao_busca_operacao':
                if ( $_POST['tipoWs'] == 'SOAP' )
                    $xml = MdUtlAdmIntegracaoINT::montarOperacaoSOAP($_POST);
                else
                    $xml = MdUtlAdmIntegracaoINT::montarOperacaoREST($_POST);
                break;

            case 'md_utl_adm_prm_verifica_membro_part':
                // busca se eh chefia imediata
                $dados           = ( new MdUtlAdmPrmGrUsuRN() )->buscaUsuarioChefiaImediata( $_POST['login_usuario'] );
                $isEditavelChefe = 'S';
                if ( is_array($dados) && $dados['comIntegracao'] === true ) {
                   $isEditavelChefe = 'N';
                   $retorno         = empty( $dados['retorno'] ) ? '' : json_encode( $dados['retorno'] );
                }

                $xml = '<Documento><isEditavelChefe>'.$isEditavelChefe.'</isEditavelChefe><ChefiaImediata>'.$retorno.'</ChefiaImediata></Documento>';
                break;

            case 'md_utl_adm_membro_part_outro_tpCtrl':
                $validado = null;
                $msg      = '';
                $retorno  = ( new MdUtlAdmPrmGrUsuRN() )->validaRegraParticipacaoEmOutroTpCtrl( $_POST );

                switch ( gettype($retorno) ){
                    case 'array':
                            $validado = 'N';
                            $msg      = $retorno['msg'];
                        break;
                    case 'boolean':
                            $validado = 'S';
                        break;
                    default:
                        $validado = 'N';
                        $msg      = 'Tipo de retorno inválido.';
                }
                $xml = "<Documento>
                            <Validado>$validado</Validado>
                            <Msg>$msg</Msg>
                        </Documento>";
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
        $isAcesso = SessaoSEI::getInstance()->verificarPermissao('md_utl_controle_dsmp_listar');
        $arrBotoes = array();

        if ($isAcesso) {
            $objMdUtlControleDsmpRN = new MdUtlControleDsmpRN();
            $objMdUtlHsControleDsmpRN = new MdUtlHistControleDsmpRN();
            $objMdUtlAdmTpCtrlUndRN = new MdUtlAdmRelTpCtrlDesempUndRN();
            $objMdUtlAdmUtlTpCtrlRN = new MdUtlAdmTpCtrlDesempRN();
            $arrIdTipoControle = $objMdUtlAdmTpCtrlUndRN->getArrayTipoControleUnidadeLogada();
            $arrIdTipoControle = $objMdUtlAdmTpCtrlUndRN->getArrayTipoControleUnidadeLogada();
            if(!is_null($arrIdTipoControle)){
                $idProcedimento = $objProcedimentoAPI->getIdProcedimento();
                $isParametrizado = false;
                $isHistorico = $objMdUtlHsControleDsmpRN->verificaProcessoPossuiHistoricoDsmp($idProcedimento);
                $idTpProcesso = $objProcedimentoAPI->getIdTipoProcedimento();
                #$idUnidade = $objProcedimentoAPI->getIdUnidadeGeradora();
                foreach($arrIdTipoControle as $k => $v){
                    $idTipoControle = $v->getNumIdMdUtlAdmTpCtrlDesemp();
                    if (!is_null($idTipoControle)) {
                        $isParametrizado = $objMdUtlAdmUtlTpCtrlRN->verificaTipoControlePossuiParametrizacao($idTipoControle);
                        if ($isParametrizado) {
                            $arrObjsTpProcesso = $objMdUtlControleDsmpRN->getTiposProcessoTipoControle($idTipoControle);

                            $idsTpProcesso = is_countable($arrObjsTpProcesso) && count($arrObjsTpProcesso) > 0 ? InfraArray::converterArrInfraDTO($arrObjsTpProcesso, 'IdTipoProcedimento') : array();
                            if ((in_array($idTpProcesso, $idsTpProcesso)) || $isHistorico) {
                                $arrBotoes[] = '<a id="btnDtlhProcesso" href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_processo_listar&id_procedimento=' . $objProcedimentoAPI->getIdProcedimento()) . '" class="botaoSEI" tabindex="' . PaginaSEI::getInstance()->getProxTabBarraComandosSuperior() . '"><img src="modulos/utilidades/imagens/svg/triagem_analise_processo.svg" class="infraCorBarraSistema" alt="Ver detalhamento do Processo - Controle de Desempenho" title="Ver detalhamento do Processo - Controle de Desempenho"/></a>';
                                break;
                            }
                        }
                    }
                }
            }
        }

        return $arrBotoes;
    }

    public function montarBotaoDocumento(ProcedimentoAPI $objProcedimentoAPI, $arrObjDocumentoAPI)
    {
        $arrBtn = [];
        $strBtn = $this->montarBotaoProcesso( $objProcedimentoAPI );
        foreach ( $arrObjDocumentoAPI as $objDocumento ) {
            $idDoc = $objDocumento->getIdDocumento();
            $arrBtn[$idDoc] = $strBtn;
        }
        return $arrBtn;
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
        $msg = '';
        $idUnidade = $arrObjUnidadeAPI[0]->getIdUnidade();

        $objRNGerais = new MdUtlRegrasGeraisRN();
        $objDsmpRN = new MdUtlControleDsmpRN();
        $msg = $objRNGerais->verificarExistenciaUnidade(array($arrObjUnidadeAPI, 'excluir'));

        if ($msg == '') {
            $msg = $objDsmpRN->verificaUnidadeControleDsmp($idUnidade);
        }

        if ($msg != '') {
            $objInfraException = new InfraException();
            $objInfraException->lancarValidacao($msg);
            return false;
        } else {
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

        if ($msg != '') {
            $strProcesso = explode('-', $msg);
            $numeroProcesso = $strProcesso[1] . '-' . $strProcesso[2];
            $numeroProcesso = str_replace('\n', '', $numeroProcesso);
            $numeroProcesso = trim($numeroProcesso);

            $corpoMsg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_79, $numeroProcesso);
            $objInfraException = new InfraException();
            $objInfraException->lancarValidacao($corpoMsg);
        }

        return $msg == '';
    }

    public function concluirProcesso($arrObjProcedimentoAPI)
    {
        $ultimaConclusao = $this->verificaUltimaConclusao($arrObjProcedimentoAPI[0]->getIdProcedimento());
        $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
        $strValor = $objInfraParametro->getValor('MODULO_UTILIDADES_BLOQUEAR_CONCLUIR_PROCESSO_COM_DOCUMENTO_RESTRITO_USANDO_HIPOTESE_LEGAL', false);
        $arrValor = [];

        if (!empty($strValor) && $ultimaConclusao) {
            $arrValor        = array_merge($arrValor, explode(',', $strValor));
            $objProtocoloRN  = new ProtocoloRN();
            $documentos      = $this->listarDocumentos($arrObjProcedimentoAPI[0]->getIdProcedimento());
            $listaDocumentos = '';

            // Valida processo principal
            $objPrcPrincipalRN = new ProcedimentoRN();
            $objPrcPrincipalDTO = new ProcedimentoDTO();
            $objPrcPrincipalDTO->setDblIdProcedimento($arrObjProcedimentoAPI[0]->getIdProcedimento());
            $objPrcPrincipalDTO->retStrStaNivelAcessoLocalProtocolo();
            $objPrcPrincipalDTO->retNumIdHipoteseLegalProtocolo();
            $objPrcPrincipalDTO->retStrNomeTipoProcedimento();
            $objPrcPrincipalDTO->retStrProtocoloProcedimentoFormatado();
            $objPrcPrincipalDTO->retNumIdHipoteseLegalProtocolo();
            $objPrcPrincipalDTO = $objPrcPrincipalRN->consultarRN0201($objPrcPrincipalDTO);

            if ($objPrcPrincipalDTO->getStrStaNivelAcessoLocalProtocolo() == ProtocoloRN::$NA_RESTRITO) {
                if (in_array($objPrcPrincipalDTO->getNumIdHipoteseLegalProtocolo(), $arrValor)) {
                    $objHipotesePrincipalRN = new HipoteseLegalRN();
                    $objHipotesePrincipalDTO = new HipoteseLegalDTO();
                    $objHipotesePrincipalDTO->setNumIdHipoteseLegal($objPrcPrincipalDTO->getNumIdHipoteseLegalProtocolo());
                    $objHipotesePrincipalDTO->retStrNome();
                    $objHipotesePrincipalDTO->retStrBaseLegal();
                    $objHipotesePrincipalDTO = $objHipotesePrincipalRN->consultar($objHipotesePrincipalDTO);

                    if ($objHipotesePrincipalDTO) {
                        $listaDocumentos = $listaDocumentos . "-   " . $objPrcPrincipalDTO->getStrNomeTipoProcedimento() . " (" . $objPrcPrincipalDTO->getStrProtocoloProcedimentoFormatado() . "): " . $objHipotesePrincipalDTO->getStrNome() . " (" . $objHipotesePrincipalDTO->getStrBaseLegal() . ")\n";
                    }
                }
            }

            // Valida documentos anexados ao processo principal
            foreach ($documentos as $documento) {
                $objPesquisaProtocoloDTO = new PesquisaProtocoloDTO();
                $objPesquisaProtocoloDTO->setStrStaTipo(ProtocoloRN::$TPP_TODOS);
                $objPesquisaProtocoloDTO->setStrStaAcesso(ProtocoloRN::$NA_RESTRITO);
                $objPesquisaProtocoloDTO->setDblIdProtocolo($documento->getDblIdDocumento());
                $arrObjProtocoloDTO = $objProtocoloRN->pesquisarRN0967($objPesquisaProtocoloDTO);

                $idHipoteseLegal = $arrObjProtocoloDTO[0]->getNumIdHipoteseLegal();
                $nivelAcesso = $documento->getStrStaNivelAcessoLocalProtocolo();

                if ($arrObjProtocoloDTO && $nivelAcesso == ProtocoloRN::$NA_RESTRITO && in_array($idHipoteseLegal, $arrValor)) {

                    $objHipoteseRN  = new HipoteseLegalRN();
                    $objHipoteseDTO = new HipoteseLegalDTO();
                    $objHipoteseDTO->setNumIdHipoteseLegal($idHipoteseLegal);
                    $objHipoteseDTO->retStrNome();
                    $objHipoteseDTO->retStrBaseLegal();
                    $objHipoteseDTO = $objHipoteseRN->consultar($objHipoteseDTO);

                    if ($objHipoteseDTO) {
                        $listaDocumentos = $listaDocumentos . "-   ".$arrObjProtocoloDTO[0]->getStrNomeSerieDocumento()." (".$arrObjProtocoloDTO[0]->getStrProtocoloFormatado(). "): ".$objHipoteseDTO->getStrNome()." (".$objHipoteseDTO->getStrBaseLegal().")\n";
                    }
                }
            }

            // Valida Processos anexados ao processo principal
            $listaProcessos = $this->listarProcessosAnexado($arrObjProcedimentoAPI[0]->getIdProcedimento());
            if ($listaProcessos) {
                $listaDocProcessoAnexo = '';
                $listaMsgProcessos = '';
                foreach ($listaProcessos as $processo) {
                    $listaProcesso    = $this->validaProcessoAnexo($processo->getDblIdProtocolo2(), $arrValor);
                    $listaMsgProcesso = $listaMsgProcessos . $listaProcesso;
                    $documentos       = $this->listarDocumentos($processo->getDblIdProtocolo2());

                    if ($documentos) {
                        $listaDocProcessoAnexo = $this->verificaDocumentoRestrito($documentos, $arrValor);
                    }
                }

                $listaProcessosAnexado = $this->listarProcessosAnexado($listaProcessos[0]->getDblIdProtocolo2());
                if ($listaProcessosAnexado) {
                    $listaDocProcessoAnexo2 = '';
                    foreach ($listaProcessosAnexado as $processoAnexado) {
                        $documentosAnexados = $this->listarDocumentos($processoAnexado->getDblIdProtocolo2());
                        if ($documentosAnexados) {
                            $listaDocProcessoAnexo2 = $this->verificaDocumentoRestrito($documentos, $arrValor);
                        }
                    }
                }
            }

            if (!empty($listaDocumentos.$listaDocProcessoAnexo.$listaDocProcessoAnexo2.$listaMsgProcesso)) {
                $objInfraException = new InfraException();
                $msg = "Não é possível concluir o processo nº ".$objPrcPrincipalDTO->getStrProtocoloProcedimentoFormatado().", pois nele ou em processo anexado ainda constam documentos com Nível de Acesso Restrito usando as Hipóteses Legais abaixo: \n\n" . $listaDocumentos.$listaDocProcessoAnexo.$listaDocProcessoAnexo2.$listaMsgProcesso;
                return $objInfraException->lancarValidacao($msg);
            }
        }

        //inicio MODULO_UTILIDADES_BLOQUEAR_CONCLUIR_PROCESSO_COM_DOCUMENTO_NAO_ASSINADO
        $strValorDoc = $objInfraParametro->getValor('MODULO_UTILIDADES_BLOQUEAR_CONCLUIR_PROCESSO_COM_DOCUMENTO_NAO_ASSINADO', false);

        if (!empty($strValorDoc) && $strValorDoc == '1' && $ultimaConclusao) {
            $objProtocoloRN = new ProtocoloRN();
            $protocolosDocumentosProcesso = $this->listarDocumentos($arrObjProcedimentoAPI[0]->getIdProcedimento());
            $listaProtocolosDocumentosNaoAssinados = '';
            $docNaoAssinado = false;

            foreach ($protocolosDocumentosProcesso as $documentosProcesso) {
                $objPesquisaProtocoloDTO = new PesquisaProtocoloDTO();
                $objPesquisaProtocoloDTO->setStrStaTipo(ProtocoloRN::$TPP_DOCUMENTOS_GERADOS);
                $objPesquisaProtocoloDTO->setStrStaAcesso(ProtocoloRN::$TAP_TODOS);
                $objPesquisaProtocoloDTO->setDblIdProtocolo($documentosProcesso->getDblIdDocumento());
                $arrObjProtocoloDTO = $objProtocoloRN->pesquisarRN0967($objPesquisaProtocoloDTO);

                if ($arrObjProtocoloDTO) {
                    $staDocumento = $arrObjProtocoloDTO[0]->getStrStaDocumentoDocumento();
                    $sinAssinado = $arrObjProtocoloDTO[0]->getStrSinAssinado();
                    $staProtocolo = $arrObjProtocoloDTO[0]->getStrStaProtocolo();
                    $staEstado = $arrObjProtocoloDTO[0]->getStrStaEstado();

                    if ($staProtocolo === ProtocoloRN::$TP_DOCUMENTO_GERADO && $staDocumento != DocumentoRN::$TD_FORMULARIO_AUTOMATICO && $staEstado != ProtocoloRN::$TE_DOCUMENTO_CANCELADO && $sinAssinado == 'N') {
                        $listaProtocolosDocumentosNaoAssinados = $listaProtocolosDocumentosNaoAssinados . "- ".$arrObjProtocoloDTO[0]->getStrNomeSerieDocumento()." (".$arrObjProtocoloDTO[0]->getStrProtocoloFormatado(). ")\n";
                        $docNaoAssinado = true;
                    }
                }
            }

            if ($docNaoAssinado) {
                $objInfraException = new InfraException();
                $msg = "Não é possível concluir o processo, pois nele ainda constam documentos gerados não assinados: \n\n" . $listaProtocolosDocumentosNaoAssinados;
                return $objInfraException->lancarValidacao($msg);
            }
        }
        //fim


        $objControleDsmpRN = new MdUtlControleDsmpRN();

        $arrIdsProcedimento = array();
        $getIdsProcedimento = function ($obj) use (&$arrIdsProcedimento) {
            $arrIdsProcedimento[] = $obj->getIdProcedimento();
        };

        array_map($getIdsProcedimento, $arrObjProcedimentoAPI);

        $dados = $objControleDsmpRN->verificaProcessoAtivoDsmp(array($arrIdsProcedimento, SessaoSEI::getInstance()->getNumIdUnidadeAtual()));
        $msg = $dados['MSG'];
        $countProcessos = $dados['COUNT'];

        if ($msg != '') {
            if ($countProcessos == 1) {
                $strProcesso = explode('-', $msg);
                $numeroProcesso = $strProcesso[1] . '-' . $strProcesso[2];
                $numeroProcesso = str_replace('\n', '', $numeroProcesso);
                $numeroProcesso = trim($numeroProcesso);
                $corpoMsg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_65, $numeroProcesso);
            } else {
                $corpoMsg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_66);
                $corpoMsg .= '\n';
                $corpoMsg .= $msg;
            }

            $objInfraException = new InfraException();
            $objInfraException->lancarValidacao($corpoMsg);
        }

        return $msg == '';
    }

    /**
     * @param $arrObjProcedimentoAPI
     * @return bool|void|null
     * @throws InfraException
     */
    public function bloquearProcesso($arrObjProcedimentoAPI)
    {
        $idProcedimento = $arrObjProcedimentoAPI[0]->getIdProcedimento();
        $objAtividadeRN = new AtividadeRN();
        $objAtividadeDTO = new AtividadeDTO();
        $objAtividadeDTO->setDblIdProtocolo($idProcedimento);
        $objAtividadeDTO->setDthConclusao(null);
        $objAtividadeDTO->retNumIdUnidade();
        $countOjAtividade = $objAtividadeRN->contarRN0035($objAtividadeDTO);

        $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
        $strValor = $objInfraParametro->getValor('MODULO_UTILIDADES_BLOQUEAR_BLOQUEAR_PROCESSO_COM_DOCUMENTO_RESTRITO_USANDO_HIPOTESE_LEGAL', false);
        $arrValor = [];

        if (!empty($strValor) && $countOjAtividade == 1) {
            $arrValor        = array_merge($arrValor, explode(',', $strValor));
            $objProtocoloRN  = new ProtocoloRN();
            $documentos      = self::listarDocumentos($idProcedimento);
            $listaDocumentos = '';

            // Valida processo principal
            $objPrcPrincipalRN = new ProcedimentoRN();
            $objPrcPrincipalDTO = new ProcedimentoDTO();
            $objPrcPrincipalDTO->setDblIdProcedimento($idProcedimento);
            $objPrcPrincipalDTO->retStrStaNivelAcessoLocalProtocolo();
            $objPrcPrincipalDTO->retNumIdHipoteseLegalProtocolo();
            $objPrcPrincipalDTO->retStrNomeTipoProcedimento();
            $objPrcPrincipalDTO->retStrProtocoloProcedimentoFormatado();
            $objPrcPrincipalDTO->retNumIdHipoteseLegalProtocolo();
            $objPrcPrincipalDTO = $objPrcPrincipalRN->consultarRN0201($objPrcPrincipalDTO);

            if ($objPrcPrincipalDTO->getStrStaNivelAcessoLocalProtocolo() == ProtocoloRN::$NA_RESTRITO) {
                if (in_array($objPrcPrincipalDTO->getNumIdHipoteseLegalProtocolo(), $arrValor)) {
                    $objHipotesePrincipalRN = new HipoteseLegalRN();
                    $objHipotesePrincipalDTO = new HipoteseLegalDTO();
                    $objHipotesePrincipalDTO->setNumIdHipoteseLegal($objPrcPrincipalDTO->getNumIdHipoteseLegalProtocolo());
                    $objHipotesePrincipalDTO->retStrNome();
                    $objHipotesePrincipalDTO->retStrBaseLegal();
                    $objHipotesePrincipalDTO = $objHipotesePrincipalRN->consultar($objHipotesePrincipalDTO);

                    if ($objHipotesePrincipalDTO) {
                        $listaDocumentos = $listaDocumentos . "-   " . $objPrcPrincipalDTO->getStrNomeTipoProcedimento() . " (" . $objPrcPrincipalDTO->getStrProtocoloProcedimentoFormatado() . "): " . $objHipotesePrincipalDTO->getStrNome() . " (" . $objHipotesePrincipalDTO->getStrBaseLegal() . ")\n";
                    }
                }
            }

            // Valida documentos anexados ao processo principal
            foreach ($documentos as $documento) {
                $objPesquisaProtocoloDTO = new PesquisaProtocoloDTO();
                $objPesquisaProtocoloDTO->setStrStaTipo(ProtocoloRN::$TPP_TODOS);
                $objPesquisaProtocoloDTO->setStrStaAcesso(ProtocoloRN::$NA_RESTRITO);
                $objPesquisaProtocoloDTO->setDblIdProtocolo($documento->getDblIdDocumento());
                $arrObjProtocoloDTO = $objProtocoloRN->pesquisarRN0967($objPesquisaProtocoloDTO);

                $idHipoteseLegal = $arrObjProtocoloDTO[0]->getNumIdHipoteseLegal();
                $nivelAcesso = $documento->getStrStaNivelAcessoLocalProtocolo();

                if ($arrObjProtocoloDTO && $nivelAcesso == ProtocoloRN::$NA_RESTRITO && in_array($idHipoteseLegal, $arrValor)) {

                    $objHipoteseRN  = new HipoteseLegalRN();
                    $objHipoteseDTO = new HipoteseLegalDTO();
                    $objHipoteseDTO->setNumIdHipoteseLegal($idHipoteseLegal);
                    $objHipoteseDTO->retStrNome();
                    $objHipoteseDTO->retStrBaseLegal();
                    $objHipoteseDTO = $objHipoteseRN->consultar($objHipoteseDTO);

                    if ($objHipoteseDTO) {
                        $listaDocumentos = $listaDocumentos . "-   ".$arrObjProtocoloDTO[0]->getStrNomeSerieDocumento()." (".$arrObjProtocoloDTO[0]->getStrProtocoloFormatado(). "): ".$objHipoteseDTO->getStrNome()." (".$objHipoteseDTO->getStrBaseLegal().")\n";
                    }
                }
            }

            // Valida Processos anexados ao processo principal
            $listaProcessos = self::listarProcessosAnexado($idProcedimento);
            if ($listaProcessos) {
                $listaDocProcessoAnexo = '';
                $listaMsgProcessos = '';
                foreach ($listaProcessos as $processo) {
                    $listaProcesso    = self::validaProcessoAnexo($processo->getDblIdProtocolo2(), $arrValor);
                    $listaMsgProcesso = $listaMsgProcessos . $listaProcesso;
                    $documentos       = self::listarDocumentos($processo->getDblIdProtocolo2());

                    if ($documentos) {
                        $listaDocProcessoAnexo = self::verificaDocumentoRestrito($documentos, $arrValor);
                    }
                }

                $listaProcessosAnexado = self::listarProcessosAnexado($listaProcessos[0]->getDblIdProtocolo2());
                if ($listaProcessosAnexado) {
                    $listaDocProcessoAnexo2 = '';
                    foreach ($listaProcessosAnexado as $processoAnexado) {
                        $documentosAnexados = self::listarDocumentos($processoAnexado->getDblIdProtocolo2());
                        if ($documentosAnexados) {
                            $listaDocProcessoAnexo2 = self::verificaDocumentoRestrito($documentos, $arrValor);
                        }
                    }
                }
            }

            if (!empty($listaDocumentos.$listaDocProcessoAnexo.$listaDocProcessoAnexo2.$listaMsgProcesso)) {
                $msg = "Não é possível bloquear o processo n ".$objPrcPrincipalDTO->getStrProtocoloProcedimentoFormatado().", pois nele ou em processo anexado ainda constam documentos com Nível de Acesso Restrito usando as Hipóteses Legais abaixo: \n\n" . $listaDocumentos.$listaDocProcessoAnexo.$listaDocProcessoAnexo2.$listaMsgProcesso;
                $objInfraException = new InfraException();
                $objInfraException->lancarValidacao($msg);
            }
        }
        return $msg == '';
    }

    public function validaProcessoAnexo($idProcedimento, $arrValor)
    {
        $lista = '';
        $objProcedimentoRN = new ProcedimentoRN();
        $objProcedimentoDTO = new ProcedimentoDTO();
        $objProcedimentoDTO->setDblIdProcedimento($idProcedimento);
        $objProcedimentoDTO->retStrStaNivelAcessoLocalProtocolo();
        $objProcedimentoDTO->retNumIdHipoteseLegalProtocolo();
        $objProcedimentoDTO->retStrNomeTipoProcedimento();
        $objProcedimentoDTO->retStrProtocoloProcedimentoFormatado();
        $objProcedimentoDTO->retNumIdHipoteseLegalProtocolo();
        $objProcedimentoDTO = $objProcedimentoRN->consultarRN0201($objProcedimentoDTO);

        if ($objProcedimentoDTO->getStrStaNivelAcessoLocalProtocolo() == ProtocoloRN::$NA_RESTRITO) {
            if (in_array($objProcedimentoDTO->getNumIdHipoteseLegalProtocolo(), $arrValor)) {
                $objHipotesePrincipalRN = new HipoteseLegalRN();
                $objHipotesePrincipalDTO = new HipoteseLegalDTO();
                $objHipotesePrincipalDTO->setNumIdHipoteseLegal($objProcedimentoDTO->getNumIdHipoteseLegalProtocolo());
                $objHipotesePrincipalDTO->retStrNome();
                $objHipotesePrincipalDTO->retStrBaseLegal();
                $objHipotesePrincipalDTO = $objHipotesePrincipalRN->consultar($objHipotesePrincipalDTO);

                if ($objHipotesePrincipalDTO) {
                    $lista = $lista . "-   " . $objProcedimentoDTO->getStrNomeTipoProcedimento() . " (" . $objProcedimentoDTO->getStrProtocoloProcedimentoFormatado() . "): " . $objHipotesePrincipalDTO->getStrNome() . " (" . $objHipotesePrincipalDTO->getStrBaseLegal() . ")\n";
                }
            }
        }
        return $lista;
    }

    /**
     *  Verifica se está na última conclusão
     * @param $idProtocolo
     * @return bool
     */
    public function verificaUltimaConclusao($idProtocolo)
    {
        $objAtividadeRN = new AtividadeRN();
        $objAtividadeDTO = new AtividadeDTO();
        $objAtividadeDTO->setDblIdProtocolo($idProtocolo);
        $objAtividadeDTO->setDthConclusao(null);
        $objAtividadeDTO->retNumIdUnidade();
        $objAtividadeDTO = $objAtividadeRN->contarRN0035($objAtividadeDTO);

        if ($objAtividadeDTO > 0) {
            return false;
        }
        return true;
    }

    /**
     * Verifica se Existe documentos restritos e se o valor da hipotese legal está setada no parâmentro
     * @param $documentos
     * @param $arrValor
     * @return string
     */
    public function verificaDocumentoRestrito($documentos, $arrValor)
    {
        $objProtocoloRN = new ProtocoloRN();
        $listaDocumentos = '';

        foreach ($documentos as $documento) {
            $objPesquisaProtocoloDTO = new PesquisaProtocoloDTO();
            $objPesquisaProtocoloDTO->setStrStaTipo(ProtocoloRN::$TPP_TODOS);
            $objPesquisaProtocoloDTO->setStrStaAcesso(ProtocoloRN::$NA_RESTRITO);
            $objPesquisaProtocoloDTO->setDblIdProtocolo($documento->getDblIdDocumento());
            $arrObjProtocoloDTO = $objProtocoloRN->pesquisarRN0967($objPesquisaProtocoloDTO);

            $idHipoteseLegal = $arrObjProtocoloDTO[0]->getNumIdHipoteseLegal();
            $nivelAcesso     = $documento->getStrStaNivelAcessoLocalProtocolo();

            if ($arrObjProtocoloDTO && $nivelAcesso == ProtocoloRN::$NA_RESTRITO && in_array($idHipoteseLegal, $arrValor)) {
                $objHipoteseRN  = new HipoteseLegalRN();
                $objHipoteseDTO = new HipoteseLegalDTO();
                $objHipoteseDTO->setNumIdHipoteseLegal($idHipoteseLegal);
                $objHipoteseDTO->retStrNome();
                $objHipoteseDTO->retStrBaseLegal();
                $objHipoteseDTO = $objHipoteseRN->consultar($objHipoteseDTO);

                if ($objHipoteseDTO) {
                    $listaDocumentos = $listaDocumentos . "-   ".$arrObjProtocoloDTO[0]->getStrNomeSerieDocumento()." (".$arrObjProtocoloDTO[0]->getStrProtocoloFormatado(). "): ".$objHipoteseDTO->getStrNome()." (".$objHipoteseDTO->getStrBaseLegal().")\n";
                }
            }
        }

        return $listaDocumentos;
    }

    public function enviarProcesso($arrObjProcedimentoAPI, $arrObjUnidadeAPI)
    {
        $chkSinManterUnidade = $_POST['chkSinManterAberto'];


        if ($chkSinManterUnidade != 'on') {
            $objControleDsmpRN = new MdUtlControleDsmpRN();
            $arrIdsProcedimento = array();

            $getIdsProcedimento = function ($obj) use (&$arrIdsProcedimento) {
                $arrIdsProcedimento[] = $obj->getIdProcedimento();
            };

            array_map($getIdsProcedimento, $arrObjProcedimentoAPI);

            $dados = $objControleDsmpRN->verificaProcessoAtivoDsmp(array($arrIdsProcedimento, SessaoSEI::getInstance()->getNumIdUnidadeAtual()));
            $msg = $dados['MSG'];
            $countProcessos = $dados['COUNT'];

            if ($msg != '') {
                if ($countProcessos == 1) {
                    $strProcesso = explode('-', $msg);
                    $numeroProcesso = $strProcesso[1] . '-' . $strProcesso[2];
                    $numeroProcesso = str_replace('\n', '', $numeroProcesso);
                    $numeroProcesso = trim($numeroProcesso);
                    $corpoMsg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_67, $numeroProcesso);
                } else {
                    $corpoMsg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_68);
                    $corpoMsg .= '\n';
                    $corpoMsg .= $msg;
                }

                $objInfraException = new InfraException();
                $objInfraException->lancarValidacao($corpoMsg);
            }


            return $msg == '';
        }
    }

    public function anexarProcesso(ProcedimentoAPI $objProcedimentoAPIPrincipal, ProcedimentoAPI $objProcedimentoAPIAnexado)
    {
        $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
        $strValor = $objInfraParametro->getValor('MODULO_UTILIDADES_BLOQUEAR_ANEXAR_PROCESSO_COM_DOCUMENTO_NAO_ASSINADO', false);

        if (!empty($strValor) && $strValor == '1') {
            $objProtocoloRN = new ProtocoloRN();
            $processos      = $this->listarDocumentos($objProcedimentoAPIAnexado->getIdProcedimento());
            $listaProcessos = '';
            $docNaoAssinado = false;

            foreach ($processos as $processo) {
                $objPesquisaProtocoloDTO = new PesquisaProtocoloDTO();
                $objPesquisaProtocoloDTO->setStrStaTipo(ProtocoloRN::$TPP_DOCUMENTOS_GERADOS);
                $objPesquisaProtocoloDTO->setStrStaAcesso(ProtocoloRN::$TAP_TODOS);
                $objPesquisaProtocoloDTO->setDblIdProtocolo($processo->getDblIdDocumento());
                $arrObjProtocoloDTO = $objProtocoloRN->pesquisarRN0967($objPesquisaProtocoloDTO);

                if ($arrObjProtocoloDTO) {
                    $staDocumento = $arrObjProtocoloDTO[0]->getStrStaDocumentoDocumento();
                    $sinAssinado = $arrObjProtocoloDTO[0]->getStrSinAssinado();
                    $staProtocolo = $arrObjProtocoloDTO[0]->getStrStaProtocolo();
                    $staEstado = $arrObjProtocoloDTO[0]->getStrStaEstado();

                    if ($staProtocolo === ProtocoloRN::$TP_DOCUMENTO_GERADO && $staDocumento != DocumentoRN::$TD_FORMULARIO_AUTOMATICO && $staEstado != ProtocoloRN::$TE_DOCUMENTO_CANCELADO && $sinAssinado == 'N') {
                        $listaProcessos = $listaProcessos . "- ".$arrObjProtocoloDTO[0]->getStrNomeSerieDocumento()." (".$arrObjProtocoloDTO[0]->getStrProtocoloFormatado(). ")\n";
                        $docNaoAssinado = true;
                    }
                }
            }

            if ($docNaoAssinado) {
                $objInfraException = new InfraException();
                $msg = "Não é possível anexar o processo indicado, pois nele ainda constam documentos gerados não assinados: \n" . $listaProcessos;
                return $objInfraException->lancarValidacao($msg);
            }
        }

        $objControleDsmpRN  = new MdUtlControleDsmpRN();
        $dados = $objControleDsmpRN->verificaProcessoAtivoDsmp(array($objProcedimentoAPIAnexado->getIdProcedimento()));
        $msg = $dados['MSG'];

        if ($msg != '') {
            $strProcesso = explode('-', $msg);
            $numeroProcesso = $strProcesso[1] . '-' . $strProcesso[2];
            $numeroProcesso = str_replace('\n', '', $numeroProcesso);
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
        $objMdUtlControleDsmpRN = new MdUtlControleDsmpRN();
        $objMdUtlControleDsmpDTO = new MdUtlControleDsmpDTO();

        $objMdUtlControleDsmpDTO->setDblIdProcedimento($idProcedimentoAPI);
        $objMdUtlControleDsmpDTO->retTodos();
        $objMdUtlControleDsmpDTO->setStrSinVerificarPermissao('N');
        $countProcesso = $objMdUtlControleDsmpRN->contar($objMdUtlControleDsmpDTO);

        $objMdUtlHistControleDsmpRN = new MdUtlHistControleDsmpRN();
        $objMdUtlHistControleDsmpDTO = new MdUtlHistControleDsmpDTO();
        $objMdUtlHistControleDsmpDTO->setStrSinVerificarPermissao('N');
        $objMdUtlHistControleDsmpDTO->setDblIdProcedimento($idProcedimentoAPI);
        $objMdUtlHistControleDsmpDTO->retTodos();
        $countHsProcesso = $objMdUtlHistControleDsmpRN->contar($objMdUtlHistControleDsmpDTO);

        if ($countProcesso > 0 || $countHsProcesso > 0) {
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

        #Estória de Usuário #12242
        $isValidoExclusao = true; #$objMdUtlControleRN->validaExclusaoDocumento($objDocumentoAPI);

        if ($isValidoExclusao) {
            return parent::excluirDocumento($objDocumentoAPI);
        } else {
            $objInfraException = new InfraException();
            $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_60);
            return $objInfraException->lancarValidacao($msg);
        }

    }

    public function montarIconeProcesso(ProcedimentoAPI $objProcedimentoAPI)
    {
        $isAcesso = SessaoSEI::getInstance()->verificarPermissao('md_utl_controle_dsmp_listar');

        if ($isAcesso) {
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
                    $title = $arrRetorno['TOOLTIP'];
                    $icone = $arrRetorno['IMG'];

                    $objArvoreAcaoItemAPI = new ArvoreAcaoItemAPI();
                    $objArvoreAcaoItemAPI->setTipo($tipo);
                    $objArvoreAcaoItemAPI->setId($id);
                    $objArvoreAcaoItemAPI->setIdPai($dblIdProcedimento);
                    $objArvoreAcaoItemAPI->setTitle($title);
                    $objArvoreAcaoItemAPI->setIcone($icone);
                    $objArvoreAcaoItemAPI->setTarget('ifrVisualizacao');
//                    $objArvoreAcaoItemAPI->setHref('javascript:;');
                    $objArvoreAcaoItemAPI->setHref(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_processo_listar&id_procedimento=' . $objProcedimentoAPI->getIdProcedimento()));
                    $objArvoreAcaoItemAPI->setSinHabilitado('S');
                    $arrObjArvoreAcaoItemAPI[] = $objArvoreAcaoItemAPI;

                    return $arrObjArvoreAcaoItemAPI;
                }
            }
        }
    }

    public function montarIconeControleProcessos($arrObjProcedimentoDTO)
    {
        $isAcesso = SessaoSEI::getInstance()->verificarPermissao('md_utl_controle_dsmp_listar');
        if ($isAcesso) {
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

        $isAcesso = SessaoSEI::getInstance()->verificarPermissao('md_utl_controle_dsmp_listar');
        if ($isAcesso) {
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

    /**
     * @param $idProcedimento
     * @return mixed
     * @throws InfraException
     */
    public function listarDocumentos($idProcedimento)
    {
        if (!isset($idProcedimento)) {
            throw new InfraException('Parâmetro $idProcedimento não informado.');
        }

        $documentoRN     = new DocumentoRN();
        $objDocumentoDTO = new DocumentoDTO();

        $objDocumentoDTO->retDblIdDocumento();
        $objDocumentoDTO->retNumIdSerie();
        $objDocumentoDTO->retDblIdProcedimento();
        $objDocumentoDTO->retStrStaNivelAcessoLocalProtocolo();
        $objDocumentoDTO->setDblIdProcedimento($idProcedimento);

        return $documentoRN->listarRN0008($objDocumentoDTO);
    }

    /**
     * Lista processos anexados ao processo principal
     * @param $idProcedimento
     * @return mixed
     * @throws InfraException
     */
    public function listarProcessosAnexado($idProcedimento)
    {
        if (!isset($idProcedimento)) {
            throw new InfraException('Parâmetro $idProcedimento não informado.');
        }

        $objRelProtocoloProtocoloRN = new RelProtocoloProtocoloRN();
        $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
        $objRelProtocoloProtocoloDTO->retDblIdProtocolo2();
        $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($idProcedimento);
        $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_ANEXADO);
        $objRelProtocoloProtocoloDTO = $objRelProtocoloProtocoloRN->listarRN0187($objRelProtocoloProtocoloDTO);

        return $objRelProtocoloProtocoloDTO;
    }

    /**
     * Valida o Documento que está sendo cancelado
     *
     * @access public
     * @param DocumentoAPI $objDocumentoAPI
     * @return mixed
     * @author Ramon Veloso <rsveloso@stefanini.com>
     */
    public function cancelarDocumento(DocumentoAPI $objDocumentoAPI)
    {
        $idProcedimento = $_GET['id_procedimento'];
        $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
        $idSerieDocumento = $objInfraParametro->getValor('MODULO_UTILIDADES_ID_TIPO_DOCUMENTO_EXIGIDO_CANCELAR', false);
        $usuarioLogado = SessaoSEI::getInstance()->getStrSiglaUsuario();

        if ((!empty($idSerieDocumento) && is_numeric($idSerieDocumento)) && $usuarioLogado != SessaoSEI::$USUARIO_SEI) {
            $serieRN = new SerieRN();
            $objSerieDTO = new SerieDTO();
            $objSerieDTO->retNumIdSerie();
            $objSerieDTO->retStrNome();
            $objSerieDTO->setStrSinAtivo('S');
            $objSerieDTO->setStrStaAplicabilidade(array(SerieRN::$TA_INTERNO_EXTERNO, SerieRN::$TA_INTERNO), InfraDTO::$OPER_IN);
            $objSerieDTO->setNumIdSerie($idSerieDocumento);

            $arrObjSerieDTO = $serieRN->listarRN0646($objSerieDTO);

            if ($arrObjSerieDTO) {
                $semTermoCancelamento = true;
                $documentos = $this->listarDocumentos($idProcedimento);

                foreach ($documentos as $documento) {
                    if ($documento->getNumIdSerie() == $idSerieDocumento) {
                        $objPesquisaProtocoloDTO = new PesquisaProtocoloDTO();
                        $objPesquisaProtocoloDTO->setStrStaTipo(ProtocoloRN::$TPP_DOCUMENTOS_GERADOS);
                        $objPesquisaProtocoloDTO->setStrStaAcesso(ProtocoloRN::$TAP_TODOS);
                        $objPesquisaProtocoloDTO->setDblIdProtocolo($documento->getDblIdDocumento());

                        $objProtocoloRN = new ProtocoloRN();
                        $objProtocoloDTO = $objProtocoloRN->pesquisarRN0967($objPesquisaProtocoloDTO);

                        if ($objProtocoloDTO) {
                            if ($objProtocoloDTO[0]->getStrSinAssinado() == 'S') {
                                $semTermoCancelamento = false;
                            }
                        }
                    }

                }

                if ($semTermoCancelamento) {
                    $nomeSerie = $arrObjSerieDTO[0]->getStrNome();

                    $msg = 'Não é possível Cancelar o Documento, pois no processo não consta ' . $nomeSerie . ' devidamente formalizado.';
                    $objInfraException = new InfraException();
                    $objInfraException->adicionarValidacao($msg);
                    $objInfraException->lancarValidacoes();
                    return null;
                }
            }
        }
    }

    /**
     * @param $arrObjContatoAPI
     * @return null
     * @throws InfraException
     */
    public function desativarContato($arrObjContatoAPI)
    {
        $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
        $strValor = $objInfraParametro->getValor('MODULO_UTILIDADES_ID_GRUPOS_CONTATO_TRAVAR_CONTATOS', false);
        $arrValor = [];

        if (!empty($strValor) && $_GET['acao'] === 'contato_desativar' && $_GET['acao_origem'] === 'contato_listar') {
            $arrValor = array_merge($arrValor, explode(',', $strValor));
            $idExistente = $this->verificaExistenciaIdGrupo($arrValor);

            if ($idExistente) {

                $objRelGrupoContatoRN  = new RelGrupoContatoRN();
                $objRelGrupoContatoDTO = new RelGrupoContatoDTO();
                $objRelGrupoContatoDTO->retNumIdGrupoContato();
                $objRelGrupoContatoDTO->setNumIdContato($arrObjContatoAPI[0]->getIdContato());
                $objRelGrupoContatoDTO->setOrd('IdGrupoContato', 'desc');
                $objRelGrupoContatoDTO = $objRelGrupoContatoRN->listarRN0463($objRelGrupoContatoDTO);

                if ($objRelGrupoContatoDTO) {

                    foreach ($objRelGrupoContatoDTO as $objContato) {

                        if (in_array($objContato->getNumIdGrupoContato(), $arrValor)) {
                            $objGrupoContatoRN = new GrupoContatoRN();
                            $objDTO = new GrupoContatoDTO();
                            $objDTO->setNumIdGrupoContato($objContato->getNumIdGrupoContato());
                            $objDTO->setStrSinAtivo('S');
                            $objDTO->retNumIdUnidade();
                            $objDTO->retStrNome();
                            $objDTO = $objGrupoContatoRN->listarRN0477($objDTO);

                            // Verifica se a unidade do usuario logado é a mesma unidade do grupo
                            if ($objDTO && SessaoSEI::getInstance()->getNumIdUnidadeAtual() != $objDTO[0]->getNumIdUnidade()) {
                                $objUnidadeRN  = new UnidadeRN();
                                $objUnidadeDTO = new UnidadeDTO();
                                $objUnidadeDTO->retStrDescricao();
                                $objUnidadeDTO->retStrSigla();
                                $objUnidadeDTO->setNumIdUnidade($objDTO[0]->getNumIdUnidade());
                                $objUnidadeDTO = $objUnidadeRN->consultarRN0125($objUnidadeDTO);

                                $msg = 'Não é possível Desativar o Contato, pois ele está no Grupo de Contato ' . $objDTO[0]->getStrNome() .
                                    ' sob controle centralizado da Unidade ' . $objUnidadeDTO->getStrDescricao() . ' ('. $objUnidadeDTO->getStrSigla() . ').';
                                $objInfraException = new InfraException();
                                $objInfraException->adicionarValidacao($msg);
                                $objInfraException->lancarValidacoes();
                                return null;
                            }

                        }

                    }

                }
            }
        }
    }

    /**
     * @param ContatoAPI $objContatoAPI
     * @return null
     * @throws InfraException
     */
    public function alterarContato(ContatoAPI $objContatoAPI)
    {
        $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
        $strValor = $objInfraParametro->getValor('MODULO_UTILIDADES_ID_GRUPOS_CONTATO_TRAVAR_CONTATOS', false);
        $arrValor = [];

        if (!empty($strValor) && $_GET['acao_origem'] === 'contato_alterar' && $_GET['acao'] === 'contato_alterar') {

            $arrValor = array_merge($arrValor, explode(',', $strValor));
            $idExistente = $this->verificaExistenciaIdGrupo($arrValor);

            if ($idExistente) {
                $objRelGrupoContatoRN  = new RelGrupoContatoRN();
                $objRelGrupoContatoDTO = new RelGrupoContatoDTO();
                $objRelGrupoContatoDTO->retNumIdGrupoContato();
                $objRelGrupoContatoDTO->setNumIdContato($objContatoAPI->getIdContato());
                $objRelGrupoContatoDTO->setOrd('IdGrupoContato', 'desc');
                $objRelGrupoContatoDTO = $objRelGrupoContatoRN->listarRN0463($objRelGrupoContatoDTO);

                if ($objRelGrupoContatoDTO) {

                    foreach ($objRelGrupoContatoDTO as $objContato) {
                        if (in_array($objContato->getNumIdGrupoContato(), $arrValor)) {
                            $objGrupoContatoRN = new GrupoContatoRN();
                            $objDTO = new GrupoContatoDTO();
                            $objDTO->setNumIdGrupoContato($objContato->getNumIdGrupoContato());
                            $objDTO->setStrSinAtivo('S');
                            $objDTO->retNumIdUnidade();
                            $objDTO->retStrNome();
                            $objDTO = $objGrupoContatoRN->listarRN0477($objDTO);

                            // Verifica se a unidade do usuario logado é a mesma unidade do grupo
                            if ($objDTO && SessaoSEI::getInstance()->getNumIdUnidadeAtual() != $objDTO[0]->getNumIdUnidade()) {
                                $objUnidadeRN  = new UnidadeRN();
                                $objUnidadeDTO = new UnidadeDTO();
                                $objUnidadeDTO->retStrDescricao();
                                $objUnidadeDTO->retStrSigla();
                                $objUnidadeDTO->setNumIdUnidade($objDTO[0]->getNumIdUnidade());
                                $objUnidadeDTO = $objUnidadeRN->consultarRN0125($objUnidadeDTO);

                                $msg = 'Não é possível Alterar o Contato, pois ele está no Grupo de Contato ' . $objDTO[0]->getStrNome() .
                                    ' sob controle centralizado da Unidade ' . $objUnidadeDTO->getStrDescricao() . ' ('. $objUnidadeDTO->getStrSigla() . ').';
                                $objInfraException = new InfraException();
                                $objInfraException->adicionarValidacao($msg);
                                $objInfraException->lancarValidacoes();
                                return null;
                            }
                        }
                    }

                }

            }

        }

    }

    /**
     * @param $arrValor
     * @return bool
     */
    public function verificaExistenciaIdGrupo($arrValor)
    {
        $objGrupoContatoRN = new GrupoContatoRN();
        foreach ($arrValor as $item) {
            $objGrupoContatoDTO = new GrupoContatoDTO();
            $objGrupoContatoDTO->setNumIdGrupoContato($item);
            $objGrupoContatoDTO->setStrSinAtivo('S');
            $objGrupoContatoDTO->retNumIdGrupoContato();

            $objGrupoContatoDTO = $objGrupoContatoRN->listarRN0477($objGrupoContatoDTO);

            if ($objGrupoContatoDTO) {
                return true;
                break;
            }
        }

        return false;
    }

    //Captura de evento de gerar processo
    public function gerarProcesso(ProcedimentoAPI $objProcedimentoAPI)
    {

        $objSeiRN = new SeiRN();

        //api de consulta ao processo
        $objEntradaConsultaProcApi = new EntradaConsultarProcedimentoAPI();

        //Atribui id processo ao objeto de consulta da API
        $objEntradaConsultaProcApi->setIdProcedimento($objProcedimentoAPI->getIdProcedimento());

        //Indica para retornar os interessados
        $objEntradaConsultaProcApi->setSinRetornarInteressados('S');

        //Consulta o processo sendo criado
        $objSaidaConsultarProcessoAPI = $objSeiRN->consultarProcedimento($objEntradaConsultaProcApi);

        if($objSaidaConsultarProcessoAPI <> null){

            //array de objetos InteressadoAPI
            $arrObjInteressadoAPI = $objSaidaConsultarProcessoAPI->getInteressados();

            //Retorna o parâmetro do InfraParametro
            $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
            $paramBloqueioGerarAlterarProcessoSemInteressado = $objInfraParametro->getValor('MODULO_UTILIDADES_BLOQUEAR_GERAR_PROCESSO_SEM_PELO_MENOS_UM_INTERESSADO', false);

            //Se nao ha interessados e o paramêtro estiver configurado para bloqueio, gera erro de validacao
            if($paramBloqueioGerarAlterarProcessoSemInteressado == 1 && count($arrObjInteressadoAPI) == 0){
                $objInfraException = new InfraException();
                $objInfraException->lancarValidacao('Informe pelo menos um Interessado no Processo.');
            }
        }

        return null;
    }
}

?>
