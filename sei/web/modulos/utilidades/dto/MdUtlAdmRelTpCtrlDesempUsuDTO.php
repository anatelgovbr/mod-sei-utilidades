<?
/**
*
* 10/07/2018 - criado por jaqueline.mendes
*
* Vers�o do Gerador de C�digo: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmRelTpCtrlDesempUsuDTO extends InfraDTO {

  private $tpCtrlFK = null;

  public function __construct()
  {
    $this->tpCtrlFK = InfraDTO::$TIPO_FK_OPCIONAL;
    parent::__construct();
  }

  public function getStrNomeTabela() {
  	 return 'md_utl_adm_rel_tp_ctrl_usu';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmTpCtrlDesemp', 'id_md_utl_adm_tp_ctrl_desemp');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUsuario', 'id_usuario');

    $this->configurarPK('IdMdUtlAdmTpCtrlDesemp', InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdUsuario', InfraDTO::$TIPO_PK_INFORMADO);

    $this->configurarFK('IdMdUtlAdmTpCtrlDesemp', 'md_utl_adm_tp_ctrl_desemp mutc', 'mutc.id_md_utl_adm_tp_ctrl_desemp', $this->getTpControleTIPOFK());

    $this->configurarFK('IdUsuario', 'usuario usu', 'usu.id_usuario');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeUsuario','usu.nome','usuario usu');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaUsuario','usu.sigla','usuario usu');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'NomeTipoControle', 'mutc.nome', 'md_utl_adm_tp_ctrl_desemp mutc');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'IdMdUtlAdmPrmGr', 'mutc.id_md_utl_adm_prm_gr', 'md_utl_adm_tp_ctrl_desemp mutc');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'SinAtivo','mutc.sin_ativo','md_utl_adm_tp_ctrl_desemp mutc');
  }

  public function setTpControleTIPOFK($tpCtrlFK){
    $this->tpCtrlFK = $tpCtrlFK;
  }

  public function getTpControleTIPOFK(){
    return $this->tpCtrlFK;
  }
}
