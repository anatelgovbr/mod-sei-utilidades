<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/01/2023 - criado por gustavos.colab
*
* Versão do Gerador de Código: 1.43.2
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmPrmGrUsuCargaDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_utl_adm_prm_gr_usu_carg';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmPrmGrUsuCarga', 'id_md_utl_adm_prm_gr_usu_carg');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmPrmGrUsu', 'id_md_utl_adm_prm_gr_usu');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'CargaHoraria', 'carga_horaria');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTA, 'PeriodoInicial', 'periodo_inicial');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTA, 'PeriodoFinal', 'periodo_final');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,'SinAtivo','sin_ativo');

	  $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,'DatasAusencias','datas_ausencias');

	  $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'Inclusao','dth_inclusao');

	  $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUsuario','id_usuario');

	  $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmPrmGr','id_md_utl_adm_prm_gr');

    $this->configurarPK('IdMdUtlAdmPrmGrUsuCarga',InfraDTO::$TIPO_PK_NATIVA);
    $this->configurarFK('IdMdUtlAdmPrmGrUsu','md_utl_adm_prm_gr_usu prm','prm.id_md_utl_adm_prm_gr_usu');

    #$this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'IdUsuario','prm.id_usuario','md_utl_adm_prm_gr_usu prm');
	  #$this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'IdMdUtlAdmPrmGr','prm.id_md_utl_adm_prm_gr','md_utl_adm_prm_gr_usu prm');

  }
}
