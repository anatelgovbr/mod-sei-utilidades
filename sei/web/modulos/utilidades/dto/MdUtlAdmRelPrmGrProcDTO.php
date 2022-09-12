<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4� REGI�O
*
* 10/07/2018 - criado por jhon.cast
*
* Vers�o do Gerador de C�digo: 1.41.0
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
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeTipoControle', 'tc.nome', 'md_utl_adm_tp_ctrl_desemp tc');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmTpCtrlDesemp', 'tc.id_md_utl_adm_tp_ctrl_desemp', 'md_utl_adm_tp_ctrl_desemp tc');

    $this->configurarPK('IdMdUtlAdmParamGr',InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdTipoProcedimento',InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarFK('IdTipoProcedimento', 'tipo_procedimento tp', 'tp.id_tipo_procedimento');
    $this->configurarFK('IdMdUtlAdmParamGr', 'md_utl_adm_prm_gr pg', 'pg.id_md_utl_adm_prm_gr');
    $this->configurarFK('IdMdUtlAdmParamGr', 'md_utl_adm_tp_ctrl_desemp tc', 'tc.id_md_utl_adm_prm_gr');


  }
}
