<?
/**
* 08/05/2019 - criado por jaqueline.mendes
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAjustePrazoDTO extends InfraDTO {

  public function __construct(){
    parent::__construct();
  }

  public function getStrNomeTabela() {
  	 return 'md_utl_ajuste_prazo';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAjustePrazo', 'id_md_utl_ajuste_prazo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'StaTipoSolicitacao', 'sta_tipo_solicitacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'PrazoSolicitacao', 'dth_prazo_solicitacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'PrazoInicial', 'dth_prazo_inicial');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmJustPrazo', 'id_md_utl_adm_just_prazo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'StaSolicitacao', 'sta_solicitacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Observacao', 'observacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'DiasUteisExcedentes', 'dias_uteis_excedentes');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAtivo', 'sin_ativo');

    $this->configurarPK('IdMdUtlAjustePrazo',InfraDTO::$TIPO_PK_NATIVA);
    $this->configurarFK('IdMdUtlAdmJustPrazo', 'md_utl_adm_just_prazo muajp', 'muajp.id_md_utl_adm_just_prazo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeJustificativa','muajp.nome','md_utl_adm_just_prazo muajp');

  }

}
