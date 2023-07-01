SELECT DISTINCT
	id_unidade,
    id_md_utl_adm_tp_ctrl_desemp as id_tipo_controle,
    cast(dth_atual as datetime) as dth_atual,
    tipo_acao,
    id_usuario,
    tmp_pendente
FROM(
	SELECT
		 p.id_protocolo
		,p.protocolo_formatado
		,c.id_md_utl_adm_fila 
		,f.nome as fila
		,c.id_usuario_distribuicao as id_usuario
		,us.nome
		,cast(c.dth_atual as datetime) as dth_atual
		,c.id_unidade
		,un.sigla
		,c.id_md_utl_adm_tp_ctrl_desemp 
		,tp_ctrl.nome as nome_tp_controle
        ,c.tipo_acao
		,c.tempo_execucao
		,c.tempo_de_execucao_atribuido 
		,case 
			when c.sta_atendimento_dsmp = 0 then 'Aguardando Fila'
			when c.sta_atendimento_dsmp = 1 then 'Aguardando Triagem'
			when c.sta_atendimento_dsmp = 2 then 'Em Triagem'
			when c.sta_atendimento_dsmp = 3 then 'Aguardando Análise'
			when c.sta_atendimento_dsmp = 4 then 'Em Análise'
			when c.sta_atendimento_dsmp = 5 then 'Aguardando Revisão'
			when c.sta_atendimento_dsmp = 6 then 'Em Avaliação'
			when c.sta_atendimento_dsmp = 7 then 'Aguardando Correção Triagem'
			when c.sta_atendimento_dsmp = 8 then 'Em Correção de Triagem'
			when c.sta_atendimento_dsmp = 9 then 'Aguardando Correção Análise'
			when c.sta_atendimento_dsmp = 10 then 'Em Correção de Análise'
		 end as situacao		
		
		,case 
			when c.sta_atendimento_dsmp in (4,10) and c.tipo_acao  = 'Retriagem' then c.tempo_de_execucao_atribuido
			when c.sta_atendimento_dsmp in (10)   and c.tipo_acao <> 'Retriagem' then a.tempo_de_execucao_atribuido
			else c.tempo_de_execucao_atribuido 
		end as tmp_pendente
			
	FROM      prod_sei3.md_utl_controle_dsmp c
	LEFT JOIN prod_sei3.md_utl_analise a                  on c.id_md_utl_analise            = a.id_md_utl_analise
	JOIN      prod_sei3.protocolo p                       on c.id_procedimento              = p.id_protocolo 
	LEFT JOIN prod_sei3.md_utl_adm_fila f                 on c.id_md_utl_adm_fila           = f.id_md_utl_adm_fila 
	JOIN      prod_sei3.usuario us                        on c.id_usuario_distribuicao      = us.id_usuario 
	JOIN      prod_sei3.unidade un                        on c.id_unidade                   = un.id_unidade	 
	JOIN      prod_sei3.md_utl_adm_tp_ctrl_desemp tp_ctrl on c.id_md_utl_adm_tp_ctrl_desemp = tp_ctrl.id_md_utl_adm_tp_ctrl_desemp
	-- WHERE     c.id_usuario_distribuicao = @us_dist 
	-- AND       FIND_IN_SET(c.id_md_utl_adm_tp_ctrl_desemp,@tp_ctrl)
	-- AND       c.id_unidade = @unid
) AS tabela
-- ORDER BY  tp_ctrl.nome , p.id_protocolo
;