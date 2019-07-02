<?
/**
*
* 10/07/2018 - criado por jaqueline.mendes
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmFilaPrmGrUsuDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_utl_adm_fila_prm_gr_usu';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmFilaPrmGrUsu', 'id_md_utl_adm_fila_prm_gr_usu');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmPrmGrUsu', 'id_md_utl_adm_prm_gr_usu');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmFila', 'id_md_utl_adm_fila');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAnalista', 'sin_analista');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinTriador', 'sin_triador');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinRevisor', 'sin_revisor');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'PercentualRevisao', 'percentual_revisao');

    $this->configurarPK('IdMdUtlAdmFilaPrmGrUsu', InfraDTO::$TIPO_PK_NATIVA);

    $this->configurarFK('IdMdUtlAdmPrmGrUsu', 'md_utl_adm_prm_gr_usu mpa', 'mpa.id_md_utl_adm_prm_gr_usu', InfraDTO::$TIPO_FK_OBRIGATORIA);
    $this->configurarFK('IdMdUtlAdmFila', 'md_utl_adm_fila', 'id_md_utl_adm_fila', InfraDTO::$TIPO_FK_OBRIGATORIA);
    $this->configurarFK('IdUsuario', 'usuario u', 'u.id_usuario', InfraDTO::$TIPO_FK_OBRIGATORIA);

    //Usuario
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'IdUsuario', 'mpa.id_usuario', 'md_utl_adm_prm_gr_usu mpa');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'NomeUsuario', 'u.nome', 'usuario u');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'Sigla', 'u.sigla', 'usuario u');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'StaTipo', 'u.sta_tipo', 'usuario u');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'IdxUsuario', 'u.idx_usuario', 'usuario u');
    
    


  }
}
