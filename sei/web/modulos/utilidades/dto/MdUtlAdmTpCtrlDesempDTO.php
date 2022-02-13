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


  private $ParametroFk = null;
  private $ParametroFiltro = null;


  public function __construct()
  {
    $this->ParametroFk = InfraDTO::$TIPO_FK_OPCIONAL;
    $this->ParametroFiltro =InfraDTO::$FILTRO_FK_ON;
    parent::__construct();
  }


  public function getStrNomeTabela()
  {
    return 'md_utl_adm_tp_ctrl_desemp';
  }


  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmTpCtrlDesemp', 'id_md_utl_adm_tp_ctrl_desemp');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmPrmGr', 'id_md_utl_adm_prm_gr');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Nome', 'nome');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Descricao', 'descricao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAtivo', 'sin_ativo');

    $this->configurarPK('IdMdUtlAdmTpCtrlDesemp',InfraDTO::$TIPO_PK_NATIVA);

    $this->configurarFK('IdMdUtlAdmPrmGr', 'md_utl_adm_prm_gr mupr', 'mupr.id_md_utl_adm_prm_gr', $this->getParametroFk(), $this->getParametroFiltroFk());

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'IdMdUtlAdmFila', 'mupr.id_md_utl_adm_fila', 'md_utl_adm_prm_gr mupr');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'CargaPadrao', 'mupr.carga_padrao', 'md_utl_adm_prm_gr mupr');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'StaFrequencia', 'mupr.sta_frequencia', 'md_utl_adm_prm_gr mupr');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'PercentualTeletrabalho', 'mupr.percentual_teletrabalho', 'md_utl_adm_prm_gr mupr');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'InicioPeriodoParametrizado', 'mupr.inicio_periodo', 'md_utl_adm_prm_gr mupr');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'SinUltimaFila', 'mupr.sin_retorno_ult_fila', 'md_utl_adm_prm_gr mupr');


    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'RespTacitaDilacao', 'mupr.resp_tacita_dilacao', 'md_utl_adm_prm_gr mupr');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'RespTacitaInterrupcao', 'mupr.resp_tacita_interrupcao', 'md_utl_adm_prm_gr mupr');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'RespTacitaSuspensao', 'mupr.resp_tacita_suspensao', 'md_utl_adm_prm_gr mupr');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'PrazoMaxSuspensao', 'mupr.prazo_max_suspensao', 'md_utl_adm_prm_gr mupr');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'PrazoMaxInterrupcao', 'mupr.prazo_max_interrupcao', 'md_utl_adm_prm_gr mupr');


    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjRelTipoControleUtilidadesUsuarioDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjRelTipoControleUtilidadesUnidadeDTO');

  }

  /**
   * @return int|null
   */
  public function getParametroFk()
  {
    return $this->ParametroFk;
  }

  /**
   * @param int|null $ParametroFk
   */
  public function setParametroFk($ParametroFk)
  {
    $this->ParametroFk = $ParametroFk;
  }

    public function getParametroFiltroFk()
    {
        return $this->ParametroFiltro;
    }

    /**
     * @param int|null $ParametroFiltroFk
     */
    public function setParametroFiltroFk($ParametroFiltroFk)
    {
        $this->ParametroFiltro = $ParametroFiltroFk;
    }
}
