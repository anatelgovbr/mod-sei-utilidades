<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/11/2018 - criado por jaqueline.cast
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlTriagemDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_utl_triagem';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlTriagem', 'id_md_utl_triagem');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'PrazoResposta', 'dth_prazo_resposta');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'InformacaoComplementar', 'informacao_complementar');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinPossuiAnalise', 'sin_possui_analise');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'StaEncaminhamentoTriagem', 'sta_encaminhamento_triagem');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmFila', 'id_md_utl_adm_fila');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'Atual', 'dth_atual');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUsuario', 'id_usuario');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAtivo', 'sin_ativo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'Inicio', 'dth_inicio');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'Prazo', 'dth_prazo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'TempoExecucao', 'tempo_execucao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'StaTipoPresenca', 'sta_tipo_presenca');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'TempoExecucaoAtribuido', 'tempo_de_execucao_atribuido');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'PercentualDesempenho', 'percentual_desempenho');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'DistAutoParaMim', 'sin_dist_auto_para_mim');

    $this->configurarFK('IdMdUtlAdmFila', 'md_utl_adm_fila', 'id_md_utl_adm_fila', InfraDTO::$TIPO_FK_OPCIONAL);
    $this->configurarFK('IdProtocolo','protocolo','id_protocolo');

    $this->configurarPK('IdMdUtlTriagem', InfraDTO::$TIPO_PK_NATIVA);

    $this->configurarExclusaoLogica('SinAtivo', 'N');

  }
}
