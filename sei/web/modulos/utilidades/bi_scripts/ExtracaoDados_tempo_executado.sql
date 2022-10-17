SELECT DISTINCT
  c.id_unidade,
  c.id_md_utl_adm_tp_ctrl_desemp as id_tipo_controle,
  cast(dth_atual as datetime) as dth_atual,
  id_usuario,
  c.tipo_acao,
  tmp_executado
FROM (

	-- TRIAGEM
	SELECT
		 c.id_procedimento , cast(c.dth_atual as datetime) as dth_atual , t.id_usuario as us_executado , t.tempo_de_execucao_atribuido as tmp_executado , c.id_unidade , c.id_md_utl_adm_fila , id_md_utl_adm_tp_ctrl_desemp , c.tipo_acao 
	FROM prod_sei3.md_utl_controle_dsmp c
	JOIN prod_sei3.md_utl_triagem t on c.id_md_utl_triagem = t.id_md_utl_triagem -- and t.id_usuario = @us_dist
	WHERE c.tipo_acao in('Triagem')
	-- AND   FIND_IN_SET(c.id_md_utl_adm_tp_ctrl_desemp, @tp_ctrl)
	-- AND   c.id_unidade = @unid
	-- AND   c.dth_atual BETWEEN @dti and @dtf
	
	union
	
	SELECT
		 c.id_procedimento , cast(c.dth_atual as datetime) as dth_atual , t.id_usuario as us_executado , t.tempo_de_execucao_atribuido as tmp_executado , c.id_unidade , c.id_md_utl_adm_fila , id_md_utl_adm_tp_ctrl_desemp, c.tipo_acao	 
	FROM prod_sei3.md_utl_hist_controle_dsmp c
	JOIN prod_sei3.md_utl_triagem t on c.id_md_utl_triagem = t.id_md_utl_triagem -- and t.id_usuario = @us_dist
	WHERE c.tipo_acao in('Triagem')
	-- AND   FIND_IN_SET(c.id_md_utl_adm_tp_ctrl_desemp, @tp_ctrl)
	-- AND   c.id_unidade = @unid
	-- AND   c.dth_atual BETWEEN @dti and @dtf
	
	union
	
	-- ANALISE e REMOCAO DAS ANALISES EM CORRECAO
	SELECT
		 c.id_procedimento , cast(c.dth_atual as datetime) as dth_atual , a.id_usuario as us_executado , a.tempo_de_execucao_atribuido as tmp_executado , c.id_unidade , c.id_md_utl_adm_fila , id_md_utl_adm_tp_ctrl_desemp, c.tipo_acao
	FROM prod_sei3.md_utl_controle_dsmp c
	JOIN prod_sei3.md_utl_analise a on c.id_md_utl_analise = a.id_md_utl_analise -- and a.id_usuario = @us_dist
	WHERE c.tipo_acao in('Análise')
	-- AND   FIND_IN_SET(c.id_md_utl_adm_tp_ctrl_desemp, @tp_ctrl)
	-- AND   c.id_unidade = @unid
	-- AND   c.dth_atual BETWEEN @dti and @dtf
	
	union
	
	SELECT
		 c.id_procedimento , cast(c.dth_atual as datetime) as dth_atual , a.id_usuario as us_executado , a.tempo_de_execucao_atribuido as tmp_executado , c.id_unidade , c.id_md_utl_adm_fila , id_md_utl_adm_tp_ctrl_desemp, c.tipo_acao	 
	FROM prod_sei3.md_utl_hist_controle_dsmp c
	JOIN prod_sei3.md_utl_analise a on c.id_md_utl_analise = a.id_md_utl_analise -- and a.id_usuario = @us_dist
	WHERE c.tipo_acao in('Análise')
	-- AND   FIND_IN_SET(c.id_md_utl_adm_tp_ctrl_desemp, @tp_ctrl)
	-- AND   c.id_unidade = @unid
	-- AND   c.dth_atual BETWEEN @dti and @dtf
	
	union
	
	-- PARTE QUE REMOVE O TEMPO EXECUTADO
	SELECT 
		c.id_procedimento , cast(c.dth_atual as datetime) as dth_atual , a.id_usuario as us_executado , - a.tempo_de_execucao_atribuido as tmp_executado , c.id_unidade , c.id_md_utl_adm_fila , id_md_utl_adm_tp_ctrl_desemp, c.tipo_acao
	FROM prod_sei3.md_utl_controle_dsmp c
	JOIN prod_sei3.md_utl_analise a on c.id_md_utl_analise = a.id_md_utl_analise -- and a.id_usuario = @us_dist
	WHERE c.detalhe in('Retornar para Correção pelo mesmo Participante','Retornar para Correção por outro Participante na mesma Fila','Retornar para Correção por outro Participante')
	AND   c.tipo_acao in('Avaliação')
	-- AND   FIND_IN_SET(c.id_md_utl_adm_tp_ctrl_desemp, @tp_ctrl)
	-- AND   c.id_unidade = @unid
	-- AND   c.dth_atual between @dti and @dtf
	
	union
	
	SELECT 
		c.id_procedimento , cast(c.dth_atual as datetime) as dth_atual , a.id_usuario as us_executado , - a.tempo_de_execucao_atribuido as tmp_executado , c.id_unidade , c.id_md_utl_adm_fila , id_md_utl_adm_tp_ctrl_desemp, c.tipo_acao
	FROM prod_sei3.md_utl_hist_controle_dsmp c
	JOIN prod_sei3.md_utl_analise a on c.id_md_utl_analise = a.id_md_utl_analise -- and a.id_usuario = @us_dist
	WHERE c.detalhe in('Retornar para Correção pelo mesmo Participante','Retornar para Correção por outro Participante na mesma Fila','Retornar para Correção por outro Participante')
	AND   c.tipo_acao in('Avaliação')
	-- AND   FIND_IN_SET(c.id_md_utl_adm_tp_ctrl_desemp,@tp_ctrl)
	-- AND   c.id_unidade = @unid
	-- AND   c.dth_atual between @dti and @dtf
	
	union
	
	-- AVALIACAO
	SELECT
		 c.id_procedimento , cast(c.dth_atual as datetime) as dth_atual , r.id_usuario as us_executado , r.tempo_de_execucao_atribuido as tmp_executado , c.id_unidade , c.id_md_utl_adm_fila , id_md_utl_adm_tp_ctrl_desemp, c.tipo_acao
	FROM prod_sei3.md_utl_controle_dsmp c
	JOIN prod_sei3.md_utl_revisao r on c.id_md_utl_revisao = r.id_md_utl_revisao -- and r.id_usuario = @us_dist
	WHERE c.tipo_acao in('Avaliação')
	-- AND   FIND_IN_SET(c.id_md_utl_adm_tp_ctrl_desemp, @tp_ctrl)
	-- AND   c.id_unidade = @unid
	-- AND   c.dth_atual BETWEEN @dti and @dtf
	
	union
	
	SELECT
		 c.id_procedimento , cast(c.dth_atual as datetime) as dth_atual , r.id_usuario as us_executado , r.tempo_de_execucao_atribuido as tmp_executado , c.id_unidade , c.id_md_utl_adm_fila , id_md_utl_adm_tp_ctrl_desemp, c.tipo_acao
	FROM prod_sei3.md_utl_hist_controle_dsmp c
	JOIN prod_sei3.md_utl_revisao r on c.id_md_utl_revisao = r.id_md_utl_revisao -- and r.id_usuario = @us_dist
	WHERE c.tipo_acao in('Avaliação')
	-- AND   FIND_IN_SET(c.id_md_utl_adm_tp_ctrl_desemp, @tp_ctrl)
	-- AND   c.id_unidade = @unid
	-- AND   c.dth_atual BETWEEN @dti and @dtf

) as c

JOIN prod_sei3.protocolo p                       on c.id_procedimento              = p.id_protocolo 
LEFT JOIN prod_sei3.md_utl_adm_fila f            on c.id_md_utl_adm_fila           = f.id_md_utl_adm_fila 
JOIN prod_sei3.usuario us                        on c.us_executado                 = us.id_usuario 
JOIN prod_sei3.unidade un                        on c.id_unidade                   = un.id_unidade	 
JOIN prod_sei3.md_utl_adm_tp_ctrl_desemp tp_ctrl on c.id_md_utl_adm_tp_ctrl_desemp = tp_ctrl.id_md_utl_adm_tp_ctrl_desemp
;