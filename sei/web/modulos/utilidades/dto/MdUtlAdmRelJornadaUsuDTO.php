<?
/**
*
* 19/07/2018 - criado por jaqueline.mendes
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmRelJornadaUsuDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_utl_adm_rel_jornada_usu';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmJornada', 'id_md_utl_adm_jornada');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUsuario', 'id_usuario');

    $this->configurarFK('IdUsuario', 'usuario u', 'u.id_usuario');

      //Usuario
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'Nome', 'u.nome', 'usuario u');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'Sigla', 'u.sigla', 'usuario u');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'StaTipo', 'u.sta_tipo', 'usuario u');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'IdxUsuario', 'u.idx_usuario', 'usuario u');

    $this->configurarPK('IdMdUtlAdmJornada', InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdUsuario', InfraDTO::$TIPO_PK_INFORMADO);

    $this->configurarFK('IdUsuario', 'usuario u', 'u.id_usuario');
    $this->configurarFK('IdMdUtlAdmJornada', 'md_utl_adm_jornada muaj', 'muaj.id_md_utl_adm_jornada');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'NomeUsuario', 'u.nome', 'usuario u');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'SiglaUsuario', 'u.sigla', 'usuario u');

  }
}
