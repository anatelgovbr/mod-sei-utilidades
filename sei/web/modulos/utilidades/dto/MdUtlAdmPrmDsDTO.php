<?php

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmPrmDsDTO extends InfraDTO {

    public function getStrNomeTabela() {
        return 'md_utl_adm_prm_ds';
    }

    public function montar() {
        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmPrmDs', 'id_md_utl_adm_prm_ds');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmTpCtrlDesemp', 'id_md_utl_adm_tp_ctrl_desemp');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinPriorizarDistribuicao', 'sin_priorizar_distribuicao');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinFila', 'sin_fila');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinStatusAtendimentoDsmp', 'sin_status_atendimento_dsmp');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAtividade', 'sin_atividade');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinTipoProcesso', 'sin_tipo_processo');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinDiasUteis', 'sin_dias_uteis');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'DistribuicaoPrioridade', 'distribuicao_prioridade');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'FilaPrioridade', 'fila_prioridade');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'StatusPrioridade', 'status_prioridade');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'AtividadePrioridade', 'atividade_prioridade');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'TipoProcessoPrioridade', 'tipo_processo_prioridade');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'DiasUteisPrioridade', 'dias_uteis_prioridade');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'QtdDiasUteis', 'qtd_dias_uteis');


        $this->configurarPK('IdMdUtlAdmPrmDs',InfraDTO::$TIPO_PK_NATIVA);
    }
}
