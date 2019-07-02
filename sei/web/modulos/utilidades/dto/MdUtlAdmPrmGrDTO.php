<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 10/07/2018 - criado por jhon.cast
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmPrmGrDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_utl_adm_prm_gr';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmPrmGr', 'id_md_utl_adm_prm_gr');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'CargaPadrao', 'carga_padrao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'StaFrequencia', 'sta_frequencia');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL, 'PercentualTeletrabalho', 'percentual_teletrabalho');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmFila', 'id_md_utl_adm_fila');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinRetornoUltFila', 'sin_retorno_ult_fila');

    $this->configurarPK('IdMdUtlAdmPrmGr',InfraDTO::$TIPO_PK_NATIVA);

  }
}
