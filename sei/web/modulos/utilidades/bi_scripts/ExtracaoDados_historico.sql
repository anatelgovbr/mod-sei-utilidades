SELECT
	id_historico,
    id_procedimento as id_protocolo,
    id_fila,
	id_tipo_controle,
    id_unidade,
    id_usuario,
	id_usuario_distribuicao,
    min(id_triagem) as id_triagem,
    min(id_analise) as id_analise,
    min(id_avaliacao) as id_avaliacao,
    min(id_contestacao) as id_contestacao,
    min(id_analise) as id_tarefa_analise,
    min(id_acao) as id_acao,
    cast(min(dth_atual) as datetime) as dth_atual,
    min(id_atendimento) as id_atendimento,
    tipo_acao as "Tipo Ação",
    detalhe as Detalhe,
    dth_prazo_tarefa,
    sta_atendimento_dsmp,
    sin_ultima_fila,
    sin_ultimo_responsavel,
    id_ajuste_prazo,
    cast(dth_final as datetime) as dth_final,
    sin_acao_concluida,
    sta_atribuido,
    tempo_execucao,
    sta_tipo_presenca,
    tempo_de_execucao_atribuido,
    percentual_desempenho,
    sta_tipo_presenca_participacao,
    fator_desemp_diferenciado,
    sta_tipo_jornada,
    fator_reducao_jornada,
    sum(pontos_ganhos) as pontos_ganhos,
    sum(pontos_pendentes) as pontos_pendentes,
    tipo_historico
FROM (
SELECT DISTINCT
	hist_controle_dsmp.id_md_utl_hist_controle_dsmp as id_historico,
    hist_controle_dsmp.id_procedimento,
    hist_controle_dsmp.id_md_utl_adm_fila as id_fila,
    hist_controle_dsmp.id_unidade,
    hist_controle_dsmp.id_usuario,
    hist_controle_dsmp.id_usuario_distribuicao,
    hist_controle_dsmp.id_md_utl_adm_tp_ctrl_desemp as id_tipo_controle,
    hist_controle_dsmp.id_md_utl_triagem as id_triagem,
    hist_controle_dsmp.id_md_utl_analise as id_analise,
    hist_controle_dsmp.id_md_utl_revisao as id_avaliacao,
    hist_controle_dsmp.id_md_utl_contest_revisao as id_contestacao,
    if(hist_controle_dsmp.tipo_acao = 'Triagem'
		,hist_controle_dsmp.id_md_utl_triagem
        ,if(hist_controle_dsmp.tipo_acao = 'Análise'
			,hist_controle_dsmp.id_md_utl_analise
            ,if(hist_controle_dsmp.tipo_acao = 'Avaliação'
				,hist_controle_dsmp.id_md_utl_revisao
                ,if(hist_controle_dsmp.tipo_acao like 'Contestação%'
					,hist_controle_dsmp.id_md_utl_contest_revisao
                    ,null
                )
            )
        )
    ) as id_acao,
    cast(hist_controle_dsmp.dth_atual as datetime) as dth_atual,
    hist_controle_dsmp.id_atendimento,
    hist_controle_dsmp.tipo_acao,
    hist_controle_dsmp.detalhe,
    cast(hist_controle_dsmp.dth_prazo_tarefa as datetime) as dth_prazo_tarefa,
    hist_controle_dsmp.sta_atendimento_dsmp,
    hist_controle_dsmp.sin_ultima_fila,
    hist_controle_dsmp.sin_ultimo_responsavel,
    hist_controle_dsmp.id_md_utl_ajuste_prazo as id_ajuste_prazo,
    cast(hist_controle_dsmp.dth_final as datetime) as dth_final,
    hist_controle_dsmp.sin_acao_concluida,
    hist_controle_dsmp.sta_atribuido,
    
    #campos que mudam se for RO, DS ou HM.
    hist_controle_dsmp.tempo_execucao,
    hist_controle_dsmp.sta_tipo_presenca,
    hist_controle_dsmp.tempo_de_execucao_atribuido,
    hist_controle_dsmp.percentual_desempenho,
    
    #Participação
    adm_hist_prm_gr_usu.sta_tipo_presenca as sta_tipo_presenca_participacao,
    adm_hist_prm_gr_usu.fator_desemp_diferenciado,
    adm_hist_prm_gr_usu.sta_tipo_jornada,
    adm_hist_prm_gr_usu.fator_reducao_jornada,
    
    #Pontos Ganhos
    case
    	when hist_controle_dsmp.tipo_acao = 'Triagem' then utl_triagem.tempo_de_execucao_atribuido
        when hist_controle_dsmp.tipo_acao = 'Análise' then utl_analise.tempo_de_execucao_atribuido
        when hist_controle_dsmp.tipo_acao = 'Avaliação' then utl_revisao.tempo_de_execucao_atribuido
        when hist_controle_dsmp.tipo_acao = 'Avaliação'
        	and hist_controle_dsmp.detalhe in ('Retornar para Correção pelo mesmo Participante','Retornar para Correção por outro Participante na mesma Fila','Retornar para Correção por outro Participante') then -utl_revisao.tempo_de_execucao_atribuido
        else 0
    end as pontos_ganhos,
    
    # Pontos Pentendes
    0 as pontos_pendentes,
    
    'Passado' as tipo_historico
FROM prod_sei3.md_utl_hist_controle_dsmp hist_controle_dsmp
LEFT JOIN prod_sei3.md_utl_adm_tp_ctrl_desemp adm_tp_ctrl_desemp on adm_tp_ctrl_desemp.id_md_utl_adm_tp_ctrl_desemp = hist_controle_dsmp.id_md_utl_adm_tp_ctrl_desemp
LEFT JOIN prod_sei3.md_utl_adm_hist_prm_gr_usu adm_hist_prm_gr_usu on (adm_hist_prm_gr_usu.id_md_utl_adm_prm_gr = adm_tp_ctrl_desemp.id_md_utl_adm_prm_gr and adm_hist_prm_gr_usu.id_usuario = hist_controle_dsmp.id_usuario)
LEFT JOIN prod_sei3.md_utl_triagem utl_triagem on utl_triagem.id_md_utl_triagem = hist_controle_dsmp.id_md_utl_triagem
LEFT JOIN prod_sei3.md_utl_analise utl_analise on utl_analise.id_md_utl_analise = hist_controle_dsmp.id_md_utl_analise
LEFT JOIN prod_sei3.md_utl_revisao utl_revisao on utl_revisao.id_md_utl_revisao = hist_controle_dsmp.id_md_utl_revisao
WHERE
	1=1
    and hist_controle_dsmp.id_usuario not in (1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38) #usuários automáticos
    and hist_controle_dsmp.id_md_utl_adm_tp_ctrl_desemp is not null
    and hist_controle_dsmp.id_md_utl_adm_fila is not null
    and cast(adm_hist_prm_gr_usu.dth_inicial as datetime) <= cast(hist_controle_dsmp.dth_atual as datetime)
    and cast(if(isnull(adm_hist_prm_gr_usu.dth_final),now(),adm_hist_prm_gr_usu.dth_final) as datetime) >= cast(hist_controle_dsmp.dth_atual as datetime)
    $(varMaxDate)
UNION
SELECT DISTINCT
	hist_controle_dsmp.id_md_utl_controle_dsmp as id_historico,
    hist_controle_dsmp.id_procedimento,
    hist_controle_dsmp.id_md_utl_adm_fila as id_fila,
    hist_controle_dsmp.id_unidade,
    hist_controle_dsmp.id_usuario,
    hist_controle_dsmp.id_usuario_distribuicao,
    hist_controle_dsmp.id_md_utl_adm_tp_ctrl_desemp as id_tipo_controle,
    hist_controle_dsmp.id_md_utl_triagem as id_triagem,
    hist_controle_dsmp.id_md_utl_analise as id_analise,
    hist_controle_dsmp.id_md_utl_revisao as id_avaliacao,
    hist_controle_dsmp.id_md_utl_contest_revisao as id_contestacao,
    if(hist_controle_dsmp.tipo_acao = 'Triagem'
		,hist_controle_dsmp.id_md_utl_triagem
        ,if(hist_controle_dsmp.tipo_acao = 'Análise'
			,hist_controle_dsmp.id_md_utl_analise
            ,if(hist_controle_dsmp.tipo_acao = 'Avaliação'
				,hist_controle_dsmp.id_md_utl_revisao
                ,if(hist_controle_dsmp.tipo_acao like 'Contestação%'
					,hist_controle_dsmp.id_md_utl_contest_revisao
                    ,null
                )
            )
        )
    ) as id_acao,
    cast(hist_controle_dsmp.dth_atual as datetime) as dth_atual,
    hist_controle_dsmp.id_atendimento,
    hist_controle_dsmp.tipo_acao,
    hist_controle_dsmp.detalhe,
    cast(hist_controle_dsmp.dth_prazo_tarefa as datetime) as dth_prazo_tarefa,
    hist_controle_dsmp.sta_atendimento_dsmp,
    null as sin_ultima_fila,
    null as sin_ultimo_responsavel,
    hist_controle_dsmp.id_md_utl_ajuste_prazo as id_ajuste_prazo,
    null as dth_final,
    null as sin_acao_concluida,
    hist_controle_dsmp.sta_atribuido,
    
    #campos que mudam se for RO, DS ou HM.
    hist_controle_dsmp.tempo_execucao,
    hist_controle_dsmp.sta_tipo_presenca as sta_tipo_presenca_participacao,
    hist_controle_dsmp.tempo_de_execucao_atribuido,
    hist_controle_dsmp.percentual_desempenho,
    
    #Participação
    adm_hist_prm_gr_usu.sta_tipo_presenca,
    adm_hist_prm_gr_usu.fator_desemp_diferenciado,
    adm_hist_prm_gr_usu.sta_tipo_jornada,
    adm_hist_prm_gr_usu.fator_reducao_jornada,
    
    #Pontos Ganhos
    case
    	when hist_controle_dsmp.tipo_acao = 'Triagem' then utl_triagem.tempo_de_execucao_atribuido
        when hist_controle_dsmp.tipo_acao = 'Análise' then utl_analise.tempo_de_execucao_atribuido
        when hist_controle_dsmp.tipo_acao = 'Avaliação' then utl_revisao.tempo_de_execucao_atribuido
        when hist_controle_dsmp.tipo_acao = 'Avaliação'
        	and hist_controle_dsmp.detalhe in ('Retornar para Correção pelo mesmo Participante','Retornar para Correção por outro Participante na mesma Fila','Retornar para Correção por outro Participante') then -utl_revisao.tempo_de_execucao_atribuido
        else 0
    end as pontos_ganhos,
    
    # Pontos Pentendes
    case 
    	when hist_controle_dsmp.sta_atendimento_dsmp in (4,10) and hist_controle_dsmp.tipo_acao  = 'Retriagem' then hist_controle_dsmp.tempo_de_execucao_atribuido
    	when hist_controle_dsmp.sta_atendimento_dsmp in (10)   and hist_controle_dsmp.tipo_acao <> 'Retriagem' then utl_analise.tempo_de_execucao_atribuido
    	when hist_controle_dsmp.id_usuario = hist_controle_dsmp.id_usuario_distribuicao then hist_controle_dsmp.tempo_de_execucao_atribuido 
        else 0
    end as pontos_pendentes,
    
    'Atual' as tipo_historico
FROM prod_sei3.md_utl_controle_dsmp hist_controle_dsmp
LEFT JOIN prod_sei3.md_utl_adm_tp_ctrl_desemp adm_tp_ctrl_desemp on adm_tp_ctrl_desemp.id_md_utl_adm_tp_ctrl_desemp = hist_controle_dsmp.id_md_utl_adm_tp_ctrl_desemp
LEFT JOIN prod_sei3.md_utl_adm_hist_prm_gr_usu adm_hist_prm_gr_usu on (adm_hist_prm_gr_usu.id_md_utl_adm_prm_gr = adm_tp_ctrl_desemp.id_md_utl_adm_prm_gr and adm_hist_prm_gr_usu.id_usuario = hist_controle_dsmp.id_usuario)
LEFT JOIN prod_sei3.md_utl_triagem utl_triagem on utl_triagem.id_md_utl_triagem = hist_controle_dsmp.id_md_utl_triagem
LEFT JOIN prod_sei3.md_utl_analise utl_analise on utl_analise.id_md_utl_analise = hist_controle_dsmp.id_md_utl_analise
LEFT JOIN prod_sei3.md_utl_revisao utl_revisao on utl_revisao.id_md_utl_revisao = hist_controle_dsmp.id_md_utl_revisao
WHERE
	1=1
    and hist_controle_dsmp.id_usuario not in (1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38) #usuários automáticos
    and hist_controle_dsmp.id_md_utl_adm_tp_ctrl_desemp is not null
    and hist_controle_dsmp.id_md_utl_adm_fila is not null
    and cast(adm_hist_prm_gr_usu.dth_inicial as datetime) <= cast(hist_controle_dsmp.dth_atual as datetime)
    and cast(if(isnull(adm_hist_prm_gr_usu.dth_final),now(),adm_hist_prm_gr_usu.dth_final) as datetime) >= cast(hist_controle_dsmp.dth_atual as datetime)
) as historico
GROUP BY
	id_historico,
    id_procedimento,
    id_fila,
	id_tipo_controle,
    id_unidade,
    id_usuario,
	id_usuario_distribuicao,
    tipo_acao,
    detalhe,
    dth_prazo_tarefa,
    sta_atendimento_dsmp,
    sin_ultima_fila,
    sin_ultimo_responsavel,
    id_ajuste_prazo,
    dth_final,
    sin_acao_concluida,
    sta_atribuido,
    tempo_execucao,
    sta_tipo_presenca,
    tempo_de_execucao_atribuido,
    percentual_desempenho,
    sta_tipo_presenca_participacao,
    fator_desemp_diferenciado,
    sta_tipo_jornada,
    fator_reducao_jornada,
    tipo_historico