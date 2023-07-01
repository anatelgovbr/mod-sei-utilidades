<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 10/07/2018 - criado por jhon.cast
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmPrmGrUsuDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_utl_adm_prm_gr_usu';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmPrmGrUsu', 'id_md_utl_adm_prm_gr_usu');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmPrmGr', 'id_md_utl_adm_prm_gr');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUsuario', 'id_usuario');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'StaTipoPresenca', 'sta_tipo_presenca');

		$this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'FatorDesempDiferenciado');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'StaTipoJornada', 'sta_tipo_jornada');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'FatorReducaoJornada', 'fator_reducao_jornada');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL, 'IdDocumento', 'id_documento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinChefiaImediata', 'chefia_imediata');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'InicioParticipacao', 'dth_ini_participacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'FimParticipacao', 'dth_fim_participacao');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmPrmGrMdUtlAdmPrmGr', 'id_md_utl_adm_prm_gr', 'md_utl_adm_prm_gr');

    $this->configurarPK('IdMdUtlAdmPrmGrUsu',InfraDTO::$TIPO_PK_NATIVA);

    $this->configurarFK('IdMdUtlAdmPrmGr', 'md_utl_adm_prm_gr', 'id_md_utl_adm_prm_gr');
    $this->configurarFK('IdUsuario', 'usuario u', 'u.id_usuario');
    $this->configurarFK('IdOrgao','orgao o','o.id_orgao');
    $this->configurarFK('IdDocumento', 'documento doc','doc.id_documento', InfraDTO::$TIPO_FK_OPCIONAL);
    $this->configurarFK('IdMdUtlAdmPrmGrUsu','md_utl_adm_prm_gr_usu_carg carga','carga.id_md_utl_adm_prm_gr_usu', InfraDTO::$TIPO_FK_OPCIONAL);

    //Usuario
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'Nome', 'u.nome', 'usuario u');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'Sigla', 'u.sigla', 'usuario u');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'StaTipo', 'u.sta_tipo', 'usuario u');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'IdxUsuario', 'u.idx_usuario', 'usuario u');
    
    //Orgão
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdOrgao','u.id_orgao','usuario u');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'SiglaOrgao','o.sigla','orgao o');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'DescricaoOrgao','o.descricao','orgao o');

    //Parametrizacao Geral
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'CargaPadraoParametrizacao','carga_padrao','md_utl_adm_prm_gr');
	  $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'StaFrequenciaParametrizacao','sta_frequencia','md_utl_adm_prm_gr');

    //Documento
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdSerie', 'doc.id_serie', 'documento doc');

    //Carga Parametrizada do Usuario
	  $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DTA,'PeriodoInicial','carga.periodo_inicial','md_utl_adm_prm_gr_usu_carg carga');
	  $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DTA,'PeriodoFinal','carga.periodo_final','md_utl_adm_prm_gr_usu_carg carga');
	  $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'CargaAtiva','carga.sin_ativo','md_utl_adm_prm_gr_usu_carg carga');
	  $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'IdMdUtlAdmPrmGrUsuCarga','carga.id_md_utl_adm_prm_gr_usu_carg','md_utl_adm_prm_gr_usu_carg carga');

    //Attr
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'PalavrasPesquisa');

  }
}