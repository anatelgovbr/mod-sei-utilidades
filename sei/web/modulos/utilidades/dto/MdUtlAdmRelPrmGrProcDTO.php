<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 10/07/2018 - criado por jhon.cast
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmRelPrmGrProcDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_utl_adm_rel_prm_gr_proc';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmParamGr', 'id_md_utl_adm_prm_gr');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdTipoProcedimento', 'id_tipo_procedimento');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmParamGr', 'id_md_utl_adm_prm_gr', 'md_utl_adm_prm_gr');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdTipoProcedimento', 'tp.id_tipo_procedimento', 'tipo_procedimento tp');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeProcedimento', 'tp.nome', 'tipo_procedimento tp');

    $this->configurarPK('IdMdUtlAdmParamGr',InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdTipoProcedimento',InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarFK('IdTipoProcedimento', 'tipo_procedimento tp', 'tp.id_tipo_procedimento');
    $this->configurarFK('IdMdUtlAdmParamGr', 'md_utl_adm_prm_gr pg', 'pg.id_md_utl_adm_prm_gr');


  }
}
