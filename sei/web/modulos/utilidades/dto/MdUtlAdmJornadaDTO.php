<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 19/07/2018 - criado por jaqueline.mendes
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmJornadaDTO extends InfraDTO {

  private $JornadaTIPOFK = null;

  public function __construct(){
    $this->JornadaTIPOFK = InfraDTO::$TIPO_FK_OBRIGATORIA;
    parent::__construct();
  }

  public function getStrNomeTabela() {
  	 return 'md_utl_adm_jornada';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmJornada', 'id_md_utl_adm_jornada');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Nome', 'nome');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Descricao', 'descricao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmTpCtrlDesemp', 'id_md_utl_adm_tp_ctrl_desemp');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'PercentualAjuste','percentual_ajuste');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'Inicio','dth_inicio');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'Fim','dth_fim');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'StaTipoAjuste', 'sta_tipo_ajuste');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAtivo', 'sin_ativo');

    $this->configurarPK('IdMdUtlAdmJornada',InfraDTO::$TIPO_PK_NATIVA);
    $this->configurarFK('IdMdUtlAdmTpCtrlDesemp', 'md_utl_adm_tp_ctrl_desemp', 'id_md_utl_adm_tp_ctrl_desemp');
    $this->configurarFK('IdMdUtlAdmJornada', 'md_utl_adm_rel_jornada_usu ju', 'ju.id_md_utl_adm_jornada');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdUsuario','ju.id_usuario','md_utl_adm_rel_jornada_usu ju'.  $this->getJornadaTIPOFK(), InfraDTO::$FILTRO_FK_WHERE );

  }

  public function getJornadaTIPOFK() {
    return $this->JornadaTIPOFK;
  }

  public function setJornadaTIPOFK($jornadaTipoFK) {
    $this->JornadaTIPOFK = $jornadaTipoFK;
  }

}
