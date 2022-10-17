<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/11/2018 - criado por jaqueline.cast
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlRelTriagemAtvDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_utl_rel_triagem_atv';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlRelTriagemAtv', 'id_md_utl_rel_triagem_atv');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlTriagem', 'id_md_utl_triagem');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmAtividade', 'id_md_utl_adm_atividade');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'TempoExecucao', 'tempo_execucao');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'TempoExecucaoAtribuido', 'tempo_execucao_atribuido');

    $this->configurarPK('IdMdUtlRelTriagemAtv', InfraDTO::$TIPO_PK_NATIVA);

    $this->configurarFK('IdMdUtlTriagem', 'md_utl_triagem tri', 'tri.id_md_utl_triagem');
    $this->configurarFK('IdMdUtlAdmAtividade', 'md_utl_adm_atividade atv', 'atv.id_md_utl_adm_atividade');
    $this->configurarFK('IdMdUtlAdmAtividade', 'md_utl_adm_atv_serie_prod mp','mp.id_md_utl_adm_atividade');
    $this->configurarFK('IdSerieRel','protocolo p','p.id_protocolo',InfraDTO::$TIPO_FK_OPCIONAL);
    $this->configurarFK('IdMdUtlAdmTpProduto', 'md_utl_adm_tp_produto tp', 'tp.id_md_utl_adm_tp_produto',InfraDTO::$TIPO_FK_OPCIONAL);
    $this->configurarFK('IdSerieRel', 'serie s', 's.id_serie',InfraDTO::$TIPO_FK_OPCIONAL);

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'PrazoExecucaoAtividade', 'atv.prz_execucao_atv', 'md_utl_adm_atividade atv');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'PrazoRevisaoAtividade', 'atv.prz_revisao_atv', 'md_utl_adm_atividade atv');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'SinAtvRevAmostragem','atv.sin_atv_rev_amostragem','md_utl_adm_atividade atv');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'ComplexidadeAtividade', 'atv.complexidade', 'md_utl_adm_atividade atv');    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'VlTmpExecucaoAtv', 'atv.tmp_execucao_atv', 'md_utl_adm_atividade atv');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'NomeAtividade', 'atv.nome', 'md_utl_adm_atividade atv');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'VlTmpExecucaoRev', 'atv.tmp_execucao_rev', 'md_utl_adm_atividade atv');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'SinAnalise', 'atv.sin_analise', 'md_utl_adm_atividade atv');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'SinNaoAplicarPercDsmpAtv', 'atv.sin_nao_aplicar_perc_dsmp', 'md_utl_adm_atividade atv');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'SinObrigatorio', 'mp.sin_obrigatorio', 'md_utl_adm_atv_serie_prod mp');
    #$this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'StaAplicabilidadeSerie', 'mp.sta_aplicabilidade_serie', 'md_utl_adm_atv_serie_prod mp');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'IdMdUtlAdmAtvSerieProd', 'mp.id_md_utl_adm_atv_serie_prod', 'md_utl_adm_atv_serie_prod mp');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'TempoExecucaoProduto', 'mp.tmp_execucao_rev_produto', 'md_utl_adm_atv_serie_prod mp');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'IdSerieRel', 'mp.id_serie', 'md_utl_adm_atv_serie_prod mp');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'IdMdUtlAdmTpProduto', 'mp.id_md_utl_adm_tp_produto', 'md_utl_adm_atv_serie_prod mp');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeSerie','s.nome','serie s');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeProduto','tp.nome','md_utl_adm_tp_produto tp');

      //Tabela md_utl_analise
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'ObservacaoAnalise');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinAnalisado');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DBL, 'IdDocumentoAnalise');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'ProtocoloFormatado');
    #$this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'DocumentoFormatado');


  }
}
