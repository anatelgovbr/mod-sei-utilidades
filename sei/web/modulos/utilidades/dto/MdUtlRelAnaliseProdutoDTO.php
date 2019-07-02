<?php
/**
 * Created by PhpStorm.
 * User: jhon.carvalho
 * Date: 11/12/2018
 * Time: 09:08
 */
require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlRelAnaliseProdutoDTO extends InfraDTO
{

    public function getStrNomeTabela() {
        return 'md_utl_rel_analise_produto';
    }

    public function montar(){
        // TODO: Implement montar() method.

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlRelAnaliseProduto', 'id_md_utl_rel_analise_produto');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAnalise', 'id_md_utl_analise');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmAtividade', 'id_md_utl_adm_atividade');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlAdmTpProduto', 'id_md_utl_adm_tp_produto');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdUtlRelTriagemAtv', 'id_md_utl_rel_triagem_atv');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdSerie', 'id_serie');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL, 'IdDocumento', 'id_documento');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'ObservacaoAnalise', 'observacao');

        $this->configurarPK('IdMdUtlRelAnaliseProduto',InfraDTO::$TIPO_PK_NATIVA);
        $this->configurarFK('IdMdUtlAnalise', 'md_utl_analise man', 'man.id_md_utl_analise');
        $this->configurarFK('IdMdUtlRelTriagemAtv', 'md_utl_rel_triagem_atv mrt', 'mrt.id_md_utl_rel_triagem_atv');

        $this->configurarFK('IdDocumento', 'documento doc','doc.id_documento', InfraDTO::$TIPO_FK_OPCIONAL);
        $this->configurarFK('IdDocumento', 'protocolo prot','prot.id_protocolo', InfraDTO::$TIPO_FK_OPCIONAL);
        $this->configurarFK('IdMdUtlAdmAtividade', 'md_utl_adm_atividade mat','mat.id_md_utl_adm_atividade');
        $this->configurarFK('IdSerie', 'serie s','s.id_serie', InfraDTO::$TIPO_FK_OPCIONAL);
        $this->configurarFK('IdMdUtlAdmTpProduto', 'md_utl_adm_tp_produto tp', 'tp.id_md_utl_adm_tp_produto',InfraDTO::$TIPO_FK_OPCIONAL);

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'PrazoExecucaoAtividade', 'mat.prz_execucao_atv', 'md_utl_adm_atividade mat');
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'PrazoRevisaoAtividade', 'mat.prz_revisao_atv', 'md_utl_adm_atividade mat');
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'DocumentoFormatado', 'prot.protocolo_formatado', 'protocolo prot' );
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeSerie','s.nome','serie s');
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeProduto','tp.nome','md_utl_adm_tp_produto tp');
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeAtividade','mat.nome','md_utl_adm_atividade mat');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SinAtivoAnalise','man.sin_ativo','md_utl_analise man');

        $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinObjPreenchido');
        $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinVerificarPermissao');

    }
}