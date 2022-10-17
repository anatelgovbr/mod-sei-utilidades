<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/09/2018 - criado por jhon.carvalho
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmGrpFilaDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_utl_adm_grp_fila';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmGrpFila', 'id_md_utl_adm_grp_fila');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmGrp', 'id_md_utl_adm_grp');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmFila', 'id_md_utl_adm_fila');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAtivo', 'sin_ativo');

    $this->configurarPK('IdMdUtlAdmGrpFila',InfraDTO::$TIPO_PK_NATIVA);

    $this->configurarFK('IdMdUtlAdmGrp', 'md_utl_adm_grp grp', 'grp.id_md_utl_adm_grp');
    $this->configurarFK('IdMdUtlAdmFila', 'md_utl_adm_fila fila', 'fila.id_md_utl_adm_fila');


    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'NomeGrupoAtividade', 'grp.nome', 'md_utl_adm_grp grp');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'DescricaoGrupoAtividade', 'grp.descricao', 'md_utl_adm_grp grp');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'IdMdUtlAdmTpCtrlDesemp', 'grp.id_md_utl_adm_tp_ctrl_desemp', 'md_utl_adm_grp grp');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'NomeFila', 'fila.nome', 'md_utl_adm_fila fila');

    $this->configurarExclusaoLogica('SinAtivo', 'N');

  }
}
