<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 07/08/2018 - criado por jhon.carvalho
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmAtvSerieProdDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_utl_adm_atv_serie_prod';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmAtvSerieProd', 'id_md_utl_adm_atv_serie_prod');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmAtividade', 'id_md_utl_adm_atividade');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmTpProduto', 'id_md_utl_adm_tp_produto');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdSerie', 'id_serie');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'StaTipo', 'sta_tipo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinObrigatorio', 'sin_obrigatorio');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'StaAplicabilidadeSerie', 'sta_aplicabilidade_serie');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'UnidadeEsforcoProduto', 'und_esforco_rev_produto');

    //$this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmAtividadeMdUtlAdmAtividade', 'id_md_utl_adm_atividade', 'md_utl_adm_atividade');

    $this->configurarPK('IdMdUtlAdmAtvSerieProd',InfraDTO::$TIPO_PK_NATIVA);

    //$this->configurarFK('IdMdUtlAdmAtvSerieProd', 'md_utl_analise an', 'an.id_md_utl_adm_atv_serie_prod');
    $this->configurarFK('IdMdUtlAdmAtividade', 'md_utl_adm_atividade atv', 'atv.id_md_utl_adm_atividade');
    $this->configurarFK('IdMdUtlAdmTpProduto', 'md_utl_adm_tp_produto tp', 'tp.id_md_utl_adm_tp_produto',InfraDTO::$TIPO_FK_OPCIONAL);
    $this->configurarFK('IdSerie', 'serie s', 's.id_serie',InfraDTO::$TIPO_FK_OPCIONAL);

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeSerie','s.nome','serie s');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeProduto','tp.nome','md_utl_adm_tp_produto tp');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeAtividade','atv.nome','md_utl_adm_atividade atv');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'ObservacaoAnalise');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinAnalise');
    //$this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinAnalisadoAnalise');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DBL, 'IdDocumentoAnalise');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdMdUtlRelTriagemAtvAnalise');
    //$this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'IdMdUtlAnalise','an.id_md_utl_analise','md_utl_analise an');

  }
}
