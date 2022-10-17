<?php
/**
 * Created by PhpStorm.
 * User: jaqueline.mendes
 * Date: 23/01/2019
 * Time: 09:01
 */

class MdUtlProcedimentoDTO extends ProcedimentoDTO
{

    private $ControleDsmpFk    = null;
    private $ControleDsmpWhere = null;

    public function __construct(){
        $this->ControleDsmpFk = InfraDTO::$TIPO_FK_OPCIONAL;
        $this->ControleDsmpWhere = InfraDTO::$FILTRO_FK_ON;
        parent::__construct();
    }

    public function montar() {
        parent::montar();

        $this->configurarFK('IdProcedimento', 'md_utl_controle_dsmp cpf', 'cpf.id_procedimento', $this->getControleDsmpTIPOFK(), $this->getControleDsmpTIPOWhere());
        $this->configurarFK('IdMdUtlTriagem', 'md_utl_triagem tri', 'tri.id_md_utl_triagem', InfraDTO::$TIPO_FK_OPCIONAL);
        $this->configurarFK('IdUnidade', 'unidade u', 'u.id_unidade', InfraDTO::$TIPO_FK_OPCIONAL);
        $this->configurarFK('IdFila', 'md_utl_adm_fila af', 'af.id_md_utl_adm_fila', InfraDTO::$TIPO_FK_OPCIONAL);
        $this->configurarFK('IdProcedimento', 'documento doc', 'doc.id_procedimento');
        $this->configurarFK('IdProtocoloDoc', 'protocolo pd', 'pd.id_protocolo');
        $this->configurarFK('IdProcedimento', 'atividade atv', 'atv.id_protocolo');
        $this->configurarFK('IdUsuarioDistribuicao', 'usuario ud', 'ud.id_usuario', InfraDTO::$TIPO_FK_OPCIONAL);
        $this->configurarFK('IdMdUtlAjustePrazo', 'md_utl_ajuste_prazo mdap', 'mdap.id_md_utl_ajuste_prazo', InfraDTO::$TIPO_FK_OPCIONAL);
        $this->configurarFK('IdMdUtlContestRevisao', 'md_utl_contest_revisao mdcr', 'mdcr.id_md_utl_contest_revisao', InfraDTO::$TIPO_FK_OPCIONAL);

        // novo relacionamento para retornar info da tabela tipo de controle de desempenho
        $this->configurarFK('IdTipoCtrlDsmp','md_utl_adm_tp_ctrl_desemp tp_ctrl','tp_ctrl.id_md_utl_adm_tp_ctrl_desemp');

        //$this->configurarFK('IdDocumento', 'documento d', 'd.id_documento');

        //Get Dados do Status de Utilidades

        //Relacionamento de Procedimento
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
            'IdMdUtlAdmRelControleDsmp',
            'id_procedimento',
            'procedimento');

        //Get Dados da Proc Fila
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
            'IdMdUtlControleDsmp',
            'cpf.id_md_utl_controle_dsmp',
            'md_utl_controle_dsmp cpf');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DTH,
            'Atual',
            'cpf.dth_atual',
            'md_utl_controle_dsmp cpf');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
            'IdUnidade',
            'cpf.id_unidade',
            'md_utl_controle_dsmp cpf');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
            'IdMdUtlAdmTpCtrlDesemp',
            'cpf.id_md_utl_adm_tp_ctrl_desemp',
            'md_utl_controle_dsmp cpf');
        
        // retorna info do tipo de controle desempenho - relaciona
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
            'IdTipoCtrlDsmp',
            'cpf.id_md_utl_adm_tp_ctrl_desemp',
            'md_utl_controle_dsmp cpf');

        // retorna info do tipo de controle desempenho - get nome do tipo de controle
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
            'NomeTpCtrlDsmp',
            'tp_ctrl.nome',
            'md_utl_adm_tp_ctrl_desemp tp_ctrl');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
            'IdFila',
            'cpf.id_md_utl_adm_fila',
            'md_utl_controle_dsmp cpf');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
            'IdUsuarioDistribuicao',
            'cpf.id_usuario_distribuicao',
            'md_utl_controle_dsmp cpf');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
            'TempoExecucao',
            'cpf.tempo_execucao',
            'md_utl_controle_dsmp cpf');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
            'TempoExecucaoAtribuido',
            'cpf.tempo_de_execucao_atribuido',
            'md_utl_controle_dsmp cpf');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
            'IdMdUtlRevisao',
            'cpf.id_md_utl_revisao',
            'md_utl_controle_dsmp cpf');

        //Get dados da tabela Fila
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
            'NomeFila',
            'af.nome',
            'md_utl_adm_fila af');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
            'SiglaUnidade',
            'u.sigla',
            'unidade u');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
            'StaAtendimentoDsmp',
            'cpf.sta_atendimento_dsmp',
            'md_utl_controle_dsmp cpf');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DTH,
            'PrazoTarefa',
            'cpf.dth_prazo_tarefa',
            'md_utl_controle_dsmp cpf');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
            'IdMdUtlAnalise',
            'cpf.id_md_utl_analise',
            'md_utl_controle_dsmp cpf');

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

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
            'IdMdUtlTriagem',
            'cpf.id_md_utl_triagem',
            'md_utl_controle_dsmp cpf');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DTH,
            'PrazoResposta',
            'tri.dth_prazo_resposta',
            'md_utl_triagem tri');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
            'IdMdUtlAjustePrazo',
            'cpf.id_md_utl_ajuste_prazo',
            'md_utl_controle_dsmp cpf');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
            'IdMdUtlContestRevisao',
            'cpf.id_md_utl_contest_revisao',
            'md_utl_controle_dsmp cpf');

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


        //Get Dados do Usuário da Distribuição
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeUsuarioDistribuicao','ud.nome','usuario ud');
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaUsuarioDistribuicao','ud.sigla','usuario ud');

        $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'ValorAtividadeSelectUtl');
        $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'NomeAtividadeTriagem');
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

}
