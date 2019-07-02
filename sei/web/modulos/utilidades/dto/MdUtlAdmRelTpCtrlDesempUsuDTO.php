<?
/**
*
* 10/07/2018 - criado por jaqueline.mendes
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmRelTpCtrlDesempUsuDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_utl_adm_rel_tp_ctrl_usu';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmTpCtrlDesemp', 'id_md_utl_adm_tp_ctrl_desemp');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUsuario', 'id_usuario');

    $this->configurarPK('IdMdUtlAdmTpCtrlDesemp', InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdUsuario', InfraDTO::$TIPO_PK_INFORMADO);

    $this->configurarFK('IdMdUtlAdmTpCtrlDesemp', 'md_utl_adm_tp_ctrl_desemp mutc', 'mutc.id_md_utl_adm_tp_ctrl_desemp', InfraDTO::$TIPO_FK_OPCIONAL);

    $this->configurarFK('IdUsuario', 'usuario usu', 'usu.id_usuario');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeUsuario','usu.nome','usuario usu');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'NomeTipoControle', 'mutc.nome', 'md_utl_adm_tp_ctrl_desemp mutc');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'IdMdUtlAdmPrmGr', 'mutc.id_md_utl_adm_prm_gr', 'md_utl_adm_tp_ctrl_desemp mutc');
  }
}
