<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 13/09/2018 - criado por jhon.carvalho
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmGrpFilaProcDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_utl_adm_grp_fila_proc';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmGrpFilaProc', 'id_md_utl_adm_grp_fila_proc');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmGrpFila', 'id_md_utl_adm_grp_fila');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdTipoProcedimento', 'id_tipo_procedimento');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdTipoProcedimento', 'tp.id_tipo_procedimento', 'tipo_procedimento tp');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeProcedimento', 'tp.nome', 'tipo_procedimento tp');

    $this->configurarPK('IdMdUtlAdmGrpFilaProc',InfraDTO::$TIPO_PK_NATIVA);

    $this->configurarFK('IdMdUtlAdmGrpFila', 'md_utl_adm_grp_fila', 'id_md_utl_adm_grp_fila');
    $this->configurarFK('IdTipoProcedimento', 'tipo_procedimento tp', 'tp.id_tipo_procedimento');
  }
}
