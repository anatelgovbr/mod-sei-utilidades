<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 10/07/2018 - criado por jaqueline.mendes
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmFilaDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_utl_adm_fila';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmFila', 'id_md_utl_adm_fila');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmTpCtrlDesemp', 'id_md_utl_adm_tp_ctrl_desemp');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Nome', 'nome');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Descricao', 'descricao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'UndEsforcoTriagem', 'und_esforco_triagem');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinDistribuicaoAutomatica', 'sin_distribuicao_automatica');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinDistribuicaoUltUsuario', 'sin_distribuicao_ult_usuario');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'PrazoTarefa', 'prazo_tarefa');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAtivo', 'sin_ativo');

    $this->configurarPK('IdMdUtlAdmFila',InfraDTO::$TIPO_PK_NATIVA);
    $this->configurarFK('IdMdUtlAdmFila', 'md_utl_adm_fila_prm_gr_usu fl', 'fl.id_md_utl_adm_fila');
    $this->configurarFK('IdMdUtlAdmPrmGrUsu', 'md_utl_adm_prm_gr_usu pgu', 'pgu.id_md_utl_adm_prm_gr_usu');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'RelIdMdUtlAdmFila','fl.id_md_utl_adm_fila','md_utl_adm_fila_prm_gr_usu fl');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmFilaPrmGrUsu','fl.id_md_utl_adm_fila_prm_gr_usu','md_utl_adm_fila_prm_gr_usu fl');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmPrmGrUsu','fl.id_md_utl_adm_prm_gr_usu','md_utl_adm_fila_prm_gr_usu fl');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdUsuario','pgu.id_usuario','md_utl_adm_prm_gr_usu pgu');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjRelFilaUsuarioDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'FilaPadrao');
  }

}
