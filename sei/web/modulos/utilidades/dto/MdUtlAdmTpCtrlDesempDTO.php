<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/07/2018 - criado por jaqueline.mendes
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmTpCtrlDesempDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_utl_adm_tp_ctrl_desemp';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmTpCtrlDesemp', 'id_md_utl_adm_tp_ctrl_desemp');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmPrmGr', 'id_md_utl_adm_prm_gr');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Nome', 'nome');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Descricao', 'descricao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAtivo', 'sin_ativo');

    $this->configurarPK('IdMdUtlAdmTpCtrlDesemp',InfraDTO::$TIPO_PK_NATIVA);
    $this->configurarFK('IdMdUtlAdmPrmGr', 'md_utl_adm_prm_gr mupr', 'mupr.id_md_utl_adm_prm_gr', InfraDTO::$TIPO_FK_OPCIONAL);

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'IdMdUtlAdmFila', 'mupr.id_md_utl_adm_fila', 'md_utl_adm_prm_gr mupr');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjRelTipoControleUtilidadesUsuarioDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjRelTipoControleUtilidadesUnidadeDTO');

  }
}
