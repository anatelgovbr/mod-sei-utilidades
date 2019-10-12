<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 25/09/2018 - criado por jhon.carvalho
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmJustPrazoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_utl_adm_just_prazo';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmJustPrazo', 'id_md_utl_adm_just_prazo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmTpCtrlDesemp', 'id_md_utl_adm_tp_ctrl_desemp');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Nome', 'nome');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Descricao', 'descricao');
    
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinDilacao', 'sin_dilacao');
    
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinInterrupcao', 'sin_interrupcao');
    
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinSuspensao', 'sin_suspensao');
    
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAtivo', 'sin_ativo');

    $this->configurarPK('IdMdUtlAdmJustPrazo',InfraDTO::$TIPO_PK_NATIVA);

    $this->configurarExclusaoLogica('SinAtivo', 'N');

  }
}
