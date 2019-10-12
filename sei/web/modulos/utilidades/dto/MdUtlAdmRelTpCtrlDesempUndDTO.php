<?
/**
*
* 10/07/2018 - criado por jaqueline.mendes
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmRelTpCtrlDesempUndDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_utl_adm_rel_tp_ctrl_und';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmTpCtrlDesemp', 'id_md_utl_adm_tp_ctrl_desemp');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUnidade', 'id_unidade');

    $this->configurarPK('IdMdUtlAdmTpCtrlDesemp', InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdUnidade', InfraDTO::$TIPO_PK_INFORMADO);

    $this->configurarFK('IdUnidade', 'unidade u', 'u.id_unidade');
    $this->configurarFK('IdMdUtlAdmTpCtrlDesemp', 'md_utl_adm_tp_ctrl_desemp mutc', 'mutc.id_md_utl_adm_tp_ctrl_desemp', InfraDTO::$TIPO_FK_OPCIONAL);

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'SiglaUnidade','u.sigla','unidade u');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'DescricaoUnidade','u.descricao','unidade u');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'NomeTipoControle', 'mutc.nome', 'md_utl_adm_tp_ctrl_desemp mutc');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'IdMdUtlAdmPrmGr', 'mutc.id_md_utl_adm_prm_gr', 'md_utl_adm_tp_ctrl_desemp mutc');
  }
}
