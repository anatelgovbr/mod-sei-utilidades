<?php

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmRelPrmDsAtenDTO extends InfraDTO {

    public function getStrNomeTabela()
    {
        return 'md_utl_adm_rel_prm_ds_aten';
    }

    public function montar()
    {
        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmParamDs', 'id_md_utl_adm_prm_ds');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'StaAtendimentoDsmp', 'sta_atendimento_dsmp');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'Prioridade', 'prioridade');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmParamDs', 'id_md_utl_adm_prm_ds', 'md_utl_adm_prm_ds');

        $this->configurarPK('IdMdUtlAdmParamDs',InfraDTO::$TIPO_PK_INFORMADO);
        $this->configurarPK('StaAtendimentoDsmp',InfraDTO::$TIPO_PK_INFORMADO);

        $this->configurarFK('IdMdUtlAdmParamGr', 'md_utl_adm_prm_ds pd', 'pd.id_md_utl_adm_prm_ds');

    }
}
