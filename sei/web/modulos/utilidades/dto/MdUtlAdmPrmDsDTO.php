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


        $this->configurarPK('IdMdUtlAdmPrmDs',InfraDTO::$TIPO_PK_NATIVA);

    }
}
