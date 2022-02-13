<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 28/02/2019 - criado por jaqueline.mendes
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlHistControleDsmpDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_utl_hist_controle_dsmp';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlHistControleDsmp', 'id_md_utl_hist_controle_dsmp');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL, 'IdProcedimento', 'id_procedimento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmFila', 'id_md_utl_adm_fila');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUnidade', 'id_unidade');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUsuarioAtual', 'id_usuario');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUsuarioDistribuicao', 'id_usuario_distribuicao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmTpCtrlDesemp', 'id_md_utl_adm_tp_ctrl_desemp');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlTriagem', 'id_md_utl_triagem');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAnalise', 'id_md_utl_analise');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlRevisao', 'id_md_utl_revisao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'TempoExecucao', 'tempo_execucao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'Atual', 'dth_atual');

   $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'Final', 'dth_final');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdAtendimento', 'id_atendimento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'TipoAcao', 'tipo_acao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Detalhe', 'detalhe');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'PrazoTarefa', 'dth_prazo_tarefa');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'StaAtendimentoDsmp', 'sta_atendimento_dsmp');

   $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAcaoConcluida', 'sin_acao_concluida');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinUltimaFila', 'sin_ultima_fila');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinUltimoResponsavel', 'sin_ultimo_responsavel');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAjustePrazo', 'id_md_utl_ajuste_prazo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlContestRevisao', 'id_md_utl_contest_revisao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'StaAtribuido', 'sta_atribuido');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'StaTipoPresenca', 'sta_tipo_presenca');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'TempoExecucaoAtribuido', 'tempo_de_execucao_atribuido');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'PercentualDesempenho', 'percentual_desempenho');

    $this->configurarPK('IdMdUtlHistControleDsmp',InfraDTO::$TIPO_PK_NATIVA);
    $this->configurarFK('IdProcedimento', 'procedimento p', 'p.id_procedimento');
    $this->configurarFK('IdMdUtlAdmFila', 'md_utl_adm_fila fila', 'fila.id_md_utl_adm_fila');
    $this->configurarFK('IdUnidade', 'unidade', 'id_unidade');
    $this->configurarFK('IdUsuarioAtual', 'usuario ua', 'ua.id_usuario');

    $this->configurarFK('IdUsuarioDistribuicao', 'usuario ud', 'ud.id_usuario');
    $this->configurarFK('IdMdUtlAdmTpCtrlDesemp', 'md_utl_adm_tp_ctrl_desemp', 'id_md_utl_adm_tp_ctrl_desemp');
    $this->configurarFK('IdProcedimento', 'protocolo', 'id_protocolo');
    $this->configurarFK('IdMdUtlContestRevisao', 'imd_utl_contest_revisao', 'id_md_utl_contest_revisao');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeFila','fila.nome','md_utl_adm_fila fila');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeUsuarioDist','ud.nome','usuario ud');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaUsuarioDist','ud.sigla','usuario ud');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdTpProcedimento','p.id_tipo_procedimento','procedimento p');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'ProtocoloProcedimentoFormatado','protocolo_formatado','protocolo');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeUsuario','ua.nome','usuario ua');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaUsuario','ua.sigla','usuario ua');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeTpControle','nome','md_utl_adm_tp_ctrl_desemp');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinVerificarPermissao');

  }
}
