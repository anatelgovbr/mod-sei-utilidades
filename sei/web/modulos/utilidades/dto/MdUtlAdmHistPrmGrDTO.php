<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4� REGI�O
*
* 10/07/2018 - criado por jhon.cast
*
* Vers�o do Gerador de C�digo: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmHistPrmGrDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_utl_adm_hist_prm_gr';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmPrmGr', 'id_md_utl_adm_prm_gr');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'CargaPadrao', 'carga_padrao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'StaFrequencia', 'sta_frequencia');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL, 'PercentualTeletrabalho', 'percentual_teletrabalho');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmFila', 'id_md_utl_adm_fila');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinRetornoUltFila', 'sin_retorno_ult_fila');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'RespTacitaDilacao', 'resp_tacita_dilacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'RespTacitaSuspensao', 'resp_tacita_suspensao');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'PrazoMaxSuspensao', 'prazo_max_suspensao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'RespTacitaInterrupcao', 'resp_tacita_interrupcao');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'PrazoMaxInterrupcao', 'prazo_max_interrupcao');



    $this->configurarPK('IdMdUtlAdmPrmGr',InfraDTO::$TIPO_PK_NATIVA);

  }
}
