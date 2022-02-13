<?php

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlContestacaoDTO extends InfraDTO
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getStrNomeTabela()
    {
        return 'md_utl_contest_revisao';
    }

    public function montar()
    {
        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlContestRevisao', 'id_md_utl_contest_revisao');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmJustContest', 'id_md_utl_adm_just_contest');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'StaSolicitacao', 'sta_solicitacao');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'InformacoesComplementares', 'informacoes_complementares');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlRevisao', 'id_md_utl_revisao');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAtivo', 'sin_ativo');

        $this->configurarPK('IdMdUtlContestRevisao',InfraDTO::$TIPO_PK_NATIVA);
        $this->configurarFK('IdMdUtlAdmJustContest', 'id_md_utl_adm_just_contest muajc', 'muajc.id_md_utl_adm_just_contest');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeJustContestacao','muajc.nome','md_utl_adm_just_contest muajc');
    }
}
