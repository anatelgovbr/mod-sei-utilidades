<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 21/12/2018 - criado por jhon.cast
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlRelRevisTrgAnlsDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_utl_rel_revis_trg_anls';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlRelRevisTrgAnls', 'id_md_utl_rel_revis_trg_anls');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlRelTriagemAtv', 'id_md_utl_rel_triagem_atv');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlRelAnaliseProduto', 'id_md_utl_rel_analise_produto');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmTpRevisao', 'id_md_utl_adm_tp_revisao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmTpJustRevisao', 'id_md_utl_adm_tp_just_revisao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Observacao', 'observacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlRevisao', 'id_md_utl_revisao');

    $this->configurarPK('IdMdUtlRelRevisTrgAnls',InfraDTO::$TIPO_PK_NATIVA);

    $this->configurarFK('IdMdUtlAdmTpJustRevisao', 'md_utl_adm_tp_just_revisao', 'id_md_utl_adm_tp_just_revisao', InfraDTO::$TIPO_FK_OPCIONAL);
    $this->configurarFK('IdMdUtlAdmTpRevisao', 'md_utl_adm_tp_revisao', 'id_md_utl_adm_tp_revisao');
    $this->configurarFK('IdMdUtlRelAnaliseProduto', 'md_utl_rel_analise_produto', 'id_md_utl_rel_analise_produto', InfraDTO::$TIPO_FK_OPCIONAL);
    $this->configurarFK('IdMdUtlRelTriagemAtv', 'md_utl_rel_triagem_atv reltratv', 'reltratv.id_md_utl_rel_triagem_atv', InfraDTO::$TIPO_FK_OPCIONAL);

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'IdMdUtlAdmAtividade', 'reltratv.id_md_utl_adm_atividade', 'md_utl_rel_triagem_atv reltratv');
  }
}
