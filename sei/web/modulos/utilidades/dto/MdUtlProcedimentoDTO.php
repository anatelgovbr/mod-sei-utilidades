<?php
/**
 * Created by PhpStorm.
 * User: jaqueline.mendes
 * Date: 23/01/2019
 * Time: 09:01
 */

class MdUtlProcedimentoDTO extends ProcedimentoDTO
{

    private $ControleDsmpFk       = null;
    private $ControleDsmpWhere    = null;
	private $TriagAnaliseRevTIPOFK = null;

    public function __construct(){
        $this->ControleDsmpFk = InfraDTO::$TIPO_FK_OPCIONAL;
        $this->ControleDsmpWhere = InfraDTO::$FILTRO_FK_ON;
        $this->TriagAnaliseRevTIPOFK = InfraDTO::$TIPO_FK_OPCIONAL;
        parent::__construct();
    }

    public function montar() {
        parent::montar();

        $this->configurarFK('IdProcedimento', 'md_utl_controle_dsmp ctrl', 'ctrl.id_procedimento', $this->getControleDsmpTIPOFK() /*, $this->getControleDsmpTIPOWhere()*/);
	    $this->configurarFK('IdProcedimento', 'md_utl_hist_controle_dsmp hctrl', 'hctrl.id_procedimento',$this->getControleDsmpTIPOFK());

        $this->configurarFK('IdProcedimento', 'documento doc', 'doc.id_procedimento');
        $this->configurarFK('IdProtocoloDoc', 'protocolo pd', 'pd.id_protocolo');
        $this->configurarFK('IdProcedimento', 'atividade atv', 'atv.id_protocolo');

	    /* =============================================================================================================
									CONFIGURACAO COM CONTROLE
		============================================================================================================= */

	    $this->configurarFK('IdUnidade', 'unidade u', 'u.id_unidade');
	    $this->configurarFK('IdFila', 'md_utl_adm_fila af', 'af.id_md_utl_adm_fila', InfraDTO::$TIPO_FK_OPCIONAL);
	    $this->configurarFK('IdMdUtlAdmTpCtrlDesemp','md_utl_adm_tp_ctrl_desemp tp_ctrl','tp_ctrl.id_md_utl_adm_tp_ctrl_desemp');

        $this->configurarFK('IdUsuarioDistribuicao', 'usuario ud', 'ud.id_usuario', InfraDTO::$TIPO_FK_OPCIONAL , InfraDTO::$FILTRO_FK_WHERE);
	    $this->configurarFK('IdUsuario', 'usuario us', 'us.id_usuario', InfraDTO::$TIPO_FK_OPCIONAL , InfraDTO::$FILTRO_FK_WHERE);

	    $this->configurarFK('IdMdUtlTriagem', 'md_utl_triagem tri', 'tri.id_md_utl_triagem', $this->getTriagAnaliseRevTIPOFK() /*,InfraDTO::$FILTRO_FK_WHERE*/);
        $this->configurarFK('IdMdUtlAnalise','md_utl_analise an','an.id_md_utl_analise',$this->getTriagAnaliseRevTIPOFK() /*,InfraDTO::$FILTRO_FK_WHERE*/);
	    $this->configurarFK('IdMdUtlRevisao','md_utl_revisao re','re.id_md_utl_revisao',$this->getTriagAnaliseRevTIPOFK() /*,InfraDTO::$FILTRO_FK_WHERE*/);

	    $this->configurarFK('IdMdUtlAjustePrazo', 'md_utl_ajuste_prazo mdap', 'mdap.id_md_utl_ajuste_prazo', InfraDTO::$TIPO_FK_OPCIONAL);
	    $this->configurarFK('IdMdUtlContestRevisao', 'md_utl_contest_revisao mdcr', 'mdcr.id_md_utl_contest_revisao', InfraDTO::$TIPO_FK_OPCIONAL);

        /* =============================================================================================================
                                    CONFIGURACAO COM HISTORICO DO CONTROLE
        ============================================================================================================= */

	    $this->configurarFK('IdUnidadeHist', 'unidade hu', 'hu.id_unidade');
	    $this->configurarFK('IdFilaHist', 'md_utl_adm_fila haf', 'haf.id_md_utl_adm_fila', InfraDTO::$TIPO_FK_OPCIONAL);
	    $this->configurarFK('IdMdUtlAdmTpCtrlDesempHist','md_utl_adm_tp_ctrl_desemp htp_ctrl','htp_ctrl.id_md_utl_adm_tp_ctrl_desemp');

	    $this->configurarFK('IdUsuarioDistribuicaoHist', 'usuario hud', 'hud.id_usuario',InfraDTO::$TIPO_FK_OPCIONAL,InfraDTO::$FILTRO_FK_WHERE);
	    $this->configurarFK('IdUsuarioHist', 'usuario hus', 'hus.id_usuario',InfraDTO::$TIPO_FK_OPCIONAL,InfraDTO::$FILTRO_FK_WHERE);

	    $this->configurarFK('IdMdUtlTriagemHist', 'md_utl_triagem htri', 'htri.id_md_utl_triagem', $this->getTriagAnaliseRevTIPOFK() /*, InfraDTO::$FILTRO_FK_WHERE*/);
	    $this->configurarFK('IdMdUtlAnaliseHist','md_utl_analise han','han.id_md_utl_analise',$this->getTriagAnaliseRevTIPOFK() /*, InfraDTO::$FILTRO_FK_WHERE*/);
	    $this->configurarFK('IdMdUtlRevisaoHist','md_utl_revisao hre','hre.id_md_utl_revisao',$this->getTriagAnaliseRevTIPOFK() /*,InfraDTO::$FILTRO_FK_WHERE*/);

	    /* =============================================================================================================
	                                ATRIBUICAO IMPORTANTE PARA OS JOIN DO CONTROLE
	    ============================================================================================================= */

	    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'IdMdUtlControleDsmp','ctrl.id_md_utl_controle_dsmp','md_utl_controle_dsmp ctrl');
	    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'IdUnidade','ctrl.id_unidade','md_utl_controle_dsmp ctrl');
	    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'IdMdUtlAdmTpCtrlDesemp','ctrl.id_md_utl_adm_tp_ctrl_desemp','md_utl_controle_dsmp ctrl');
	    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'IdFila','ctrl.id_md_utl_adm_fila','md_utl_controle_dsmp ctrl');
	    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'IdUsuario','ctrl.id_usuario','md_utl_controle_dsmp ctrl');
	    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'IdUsuarioDistribuicao','ctrl.id_usuario_distribuicao','md_utl_controle_dsmp ctrl');
	    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'IdMdUtlAjustePrazo','ctrl.id_md_utl_ajuste_prazo','md_utl_controle_dsmp ctrl');
	    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'IdMdUtlContestRevisao','ctrl.id_md_utl_contest_revisao','md_utl_controle_dsmp ctrl');

	    /* =============================================================================================================
	                           ATRIBUICAO IMPORTANTE PARA OS JOIN DO HISTORICO CONTROLE
	    ============================================================================================================= */

	    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'IdMdUtlHistControleDsmp','hctrl.id_md_utl_hist_controle_dsmp','md_utl_hist_controle_dsmp hctrl');
	    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'IdUnidadeHist','hctrl.id_unidade','md_utl_hist_controle_dsmp hctrl');
	    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'IdMdUtlAdmTpCtrlDesempHist','hctrl.id_md_utl_adm_tp_ctrl_desemp','md_utl_hist_controle_dsmp hctrl');
	    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'IdFilaHist','hctrl.id_md_utl_adm_fila','md_utl_hist_controle_dsmp hctrl');
	    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'IdUsuarioHist','hctrl.id_usuario','md_utl_hist_controle_dsmp hctrl');
	    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'IdUsuarioDistribuicaoHist','hctrl.id_usuario_distribuicao','md_utl_hist_controle_dsmp hctrl');

	    /* =============================================================================================================
	                            RELACIONAMENTOS DE ATRIBUTO DA TRIAGEM, ANALISE E REVISAO NAS TABELAS CTRL E HIST
	    ============================================================================================================= */

	    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'IdMdUtlTriagem', 'ctrl.id_md_utl_triagem', 'md_utl_controle_dsmp ctrl');
	    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'IdMdUtlAnalise', 'ctrl.id_md_utl_analise', 'md_utl_controle_dsmp ctrl');
	    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'IdMdUtlRevisao', 'ctrl.id_md_utl_revisao', 'md_utl_controle_dsmp ctrl');

	    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'IdMdUtlTriagemHist', 'hctrl.id_md_utl_triagem', 'md_utl_hist_controle_dsmp hctrl');
	    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'IdMdUtlAnaliseHist', 'hctrl.id_md_utl_analise', 'md_utl_hist_controle_dsmp hctrl');
	    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'IdMdUtlRevisaoHist', 'hctrl.id_md_utl_revisao','md_utl_hist_controle_dsmp hctrl');

	    /* =============================================================================================================
	                             COLUNAS DA TABELA CONTROLE
	    ============================================================================================================= */

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
            'IdMdUtlAdmRelControleDsmp',
            'id_procedimento',
            'procedimento');

	    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
		    'TipoAcao',
		    'ctrl.tipo_acao',
		    'md_utl_controle_dsmp ctrl');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DTH,
            'Atual',
            'ctrl.dth_atual',
            'md_utl_controle_dsmp ctrl');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
            'TempoExecucao',
            'ctrl.tempo_execucao',
            'md_utl_controle_dsmp ctrl');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
            'TempoExecucaoAtribuido',
            'ctrl.tempo_de_execucao_atribuido',
            'md_utl_controle_dsmp ctrl');

	    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
		    'StaAtendimentoDsmp',
		    'ctrl.sta_atendimento_dsmp',
		    'md_utl_controle_dsmp ctrl');

	    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
		    'IdAtendimento',
		    'ctrl.id_atendimento',
		    'md_utl_controle_dsmp ctrl');

	    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DTH,
		    'PrazoTarefa',
		    'ctrl.dth_prazo_tarefa',
		    'md_utl_controle_dsmp ctrl');

	    // retorna info do tipo de controle desempenho - get nome do tipo de controle
	    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
		    'NomeTpCtrlDsmp',
		    'tp_ctrl.nome',
		    'md_utl_adm_tp_ctrl_desemp tp_ctrl');

        //Get dados da tabela Fila
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
            'NomeFila',
            'af.nome',
            'md_utl_adm_fila af');

        // Get dados da tabela Unidade
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
            'SiglaUnidade',
            'u.sigla',
            'unidade u');

        //Get dados do Procedimento
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL,
            'IdDocumento',
            'id_procedimento',
            'procedimento');

        //Get Dados do Documento
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
            'IdSerie',
            'doc.id_serie',
            'documento doc');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL,
            'IdProtocoloDoc',
            'doc.id_documento',
            'documento doc');

        //Get Dados do Protocolo
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
            'ProtocoloFormatadoDocumento',
            'pd.protocolo_formatado',
            'protocolo pd');

        //Get Dados da Atividade
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
            'IdAtividadeUtilidades',
            'atv.id_atividade',
            'atividade atv');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DTH,
            'ConclusaoAtvUtilidades',
            'atv.dth_conclusao',
            'atividade atv');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
            'IdTarefaAtvUtilidades',
            'atv.id_tarefa',
            'atividade atv');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
            'IdUnidadeAtvUtilidades',
            'atv.id_unidade',
            'atividade atv');

        //Get Dados Ajuste Prazo
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
            'StaSolicitacaoAjustePrazo',
            'mdap.sta_solicitacao',
            'md_utl_ajuste_prazo mdap');

        //Get Dados Contestacao
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
            'StaSolicitacao',
            'mdcr.sta_solicitacao',
            'md_utl_contest_revisao mdcr');

	    // tabela Usuario
	    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeUsuarioDistribuicao','ud.nome','usuario ud');
	    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaUsuarioDistribuicao','ud.sigla','usuario ud');
	    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeUsuario','us.nome','usuario us');
	    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaUsuario','us.sigla','usuario us');

	    // tabela Triagem
	    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DTH, 'PrazoResposta', 'tri.dth_prazo_resposta', 'md_utl_triagem tri');
	    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DTA,'TriagemAtual', 'tri.dth_atual', 'md_utl_triagem tri');
	    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'TempoExecucaoAtribuidoTriag', 'tri.tempo_de_execucao_atribuido', 'md_utl_triagem tri');

	    // tabela Analise
	    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DTA,'PeriodoInicio','an.dta_periodo_inicio','md_utl_analise an');
	    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DTA,'PeriodoFim','an.dta_periodo_fim','md_utl_analise an');
	    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'TempoExecucaoAtribuidoAnalise', 'an.tempo_de_execucao_atribuido', 'md_utl_analise an');

	    // tabela Revisao
	    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DTA,'RevisaoAtual', 're.dth_atual', 'md_utl_revisao re');
	    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'TempoExecucaoAtribuidoRev', 're.tempo_de_execucao_atribuido', 'md_utl_revisao re');

	    /* =============================================================================================================
	                               COLUNAS QUE SE RELACIONAM COM A TABELA CONTROLE HISTORICO
	    ============================================================================================================= */

	    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
		    'TipoAcaoHist',
		    'hctrl.tipo_acao',
		    'md_utl_hist_controle_dsmp hctrl');

	    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DTH,
		    'AtualHist',
		    'hctrl.dth_atual',
		    'md_utl_hist_controle_dsmp hctrl');

	    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
		    'TempoExecucaoHist',
		    'hctrl.tempo_execucao',
		    'md_utl_hist_controle_dsmp hctrl');

	    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
		    'TempoExecucaoAtribuidoHist',
		    'hctrl.tempo_de_execucao_atribuido',
		    'md_utl_hist_controle_dsmp hctrl');

	    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
		    'StaAtendimentoDsmpHist',
		    'hctrl.sta_atendimento_dsmp',
		    'md_utl_hist_controle_dsmp hctrl');

	    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
		    'IdAtendimentoHist',
		    'hctrl.id_atendimento',
		    'md_utl_controle_dsmp hctrl');

	    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DTH,
		    'PrazoTarefaHist',
		    'hctrl.dth_prazo_tarefa',
		    'md_utl_hist_controle_dsmp hctrl');

	    // retorna info do tipo de controle desempenho - get nome do tipo de controle
	    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
		    'NomeTpCtrlDsmpHist',
		    'htp_ctrl.nome',
		    'md_utl_adm_tp_ctrl_desemp htp_ctrl');

	    //Get dados da tabela Fila
	    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
		    'NomeFilaHist',
		    'haf.nome',
		    'md_utl_adm_fila haf');

	    // Get dados da tabela Unidade
	    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
		    'SiglaUnidadeHist',
		    'hu.sigla',
		    'unidade hu');

	    // tabela Usuario
	    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeUsuarioDistribuicaoHist','hud.nome','usuario hud');
	    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaUsuarioDistribuicaoHist','hud.sigla','usuario hud');
	    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeUsuarioHist','hus.nome','usuario hus');
	    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaUsuarioHist','hus.sigla','usuario hus');

	    // tabela Triagem
	    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DTH, 'PrazoRespostaHist', 'htri.dth_prazo_resposta', 'md_utl_triagem htri');
	    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DTA,'TriagemAtualHist', 'htri.dth_atual', 'md_utl_triagem htri');
	    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'TempoExecucaoAtribuidoTriagHist', 'htri.tempo_de_execucao_atribuido', 'md_utl_triagem htri');

	    // tabela Analise
	    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DTA,'PeriodoInicioHist','han.dta_periodo_inicio','md_utl_analise han');
	    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DTA,'PeriodoFimHist','han.dta_periodo_fim','md_utl_analise han');
	    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'TempoExecucaoAtribuidoAnaliseHist', 'han.tempo_de_execucao_atribuido', 'md_utl_analise han');

	    // tabela Revisao
	    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DTA,'RevisaoAtualHist', 'hre.dth_atual', 'md_utl_revisao hre');
	    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'TempoExecucaoAtribuidoRevHist', 'hre.tempo_de_execucao_atribuido', 'md_utl_revisao hre');

	    /* =============================================================================================================
	                            COLUNAS CRIADAS DINAMICAMENTE, SEM VINCULO A NENHUMA TABELA
	    ============================================================================================================= */

        $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'ValorAtividadeSelectUtl');
        $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'NomeAtividadeTriagem');

	    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'AtivarDebug');
	    #$this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'TipoAcaoGrid');
	    #$this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'OrigemAcaoGrid');
	    //$this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'AtivarDebug');
    }

    public function getControleDsmpTIPOFK() {
        return $this->ControleDsmpFk;
    }

    public function setControleDsmpTIPOFK($ControleDsmpFk) {
        $this->ControleDsmpFk = $ControleDsmpFk;
    }

    public function getControleDsmpTIPOWhere() {
        return $this->ControleDsmpWhere;
    }

    public function setControleDsmpTIPOWhere($ControleDsmpWhere) {
        $this->ControleDsmpWhere = $ControleDsmpWhere;
    }

	public function getTriagAnaliseRevTIPOFK() {
		return $this->TriagAnaliseRevTIPOFK;
	}

	public function setTriagAnaliseRevTIPOFK($tipoFK) {
		$this->TriagAnaliseRevTIPOFK = $tipoFK;
	}

}
