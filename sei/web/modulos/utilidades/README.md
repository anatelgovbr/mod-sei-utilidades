# Módulo Utilidades

## Requisitos
- Requisito Mínimo é o SEI 4.1.4 instalado/atualizado - Não é compatível com versões anteriores e em versões mais recentes é necessário conferir antes se possui compatibilidade.
   - Verificar valor da constante de versão no arquivo /sei/web/SEI.php ou, após logado no sistema, parando o mouse sobre a logo do SEI no canto superior esquerdo.
- Antes de executar os scripts de instalação/atualização, o usuário de acesso aos bancos de dados do SEI e do SIP, constante nos arquivos ConfiguracaoSEI.php e ConfiguracaoSip.php, deverá ter permissão de acesso total ao banco de dados, permitindo, por exemplo, criação e exclusão de tabelas.
- Os códigos-fonte do Módulo podem ser baixados a partir do link a seguir, devendo sempre utilizar a versão mais recente: [https://github.com/anatelgovbr/mod-sei-utilidades/releases](https://github.com/anatelgovbr/mod-sei-utilidades/releases "Clique e acesse")
- Se já tiver instalado versão principal com a execução dos scripts de banco do módulo no SEI e no SIP, **em versões intermediárias basta sobrescrever os códigos** e não precisa executar os scripts de banco novamente.
   - Atualizações apenas de código são identificadas com o incremento apenas do terceiro dígito da versão (p. ex. v4.1.1, v4.1.2) e não envolve execução de scripts de banco.

## Procedimentos para Instalação
1. Fazer backup dos bancos de dados do SEI e do SIP.
2. Carregar no servidor os arquivos do módulo nas pastas correspondentes nos servidores do SEI e do SIP.
   - **Caso se trate de atualização de versão anterior do Módulo**, antes de copiar os códigos-fontes para a pasta "/sei/web/modulos/utilidades", é necessário excluir os arquivos anteriores pré existentes na mencionada pasta, para não manter arquivos de códigos que foram renomeados ou descontinuados.
3. Editar o arquivo "/sei/config/ConfiguracaoSEI.php", tomando o cuidado de usar editor que não altere o charset do arquivo, para adicionar a referência à classe de integração do módulo e seu caminho relativo dentro da pasta "/sei/web/modulos" na array 'Modulos' da chave 'SEI':

		'SEI' => array(
			...
			'Modulos' => array(
				'UtilidadesIntegracao' => 'utilidades',
				),
			),

4. Antes de seguir para os próximos passos, é importante conferir se o Módulo foi corretamente declarado no arquivo "/sei/config/ConfiguracaoSEI.php". Acesse o menu **Infra > Módulos** e confira se consta a linha correspondente ao Módulo, pois, realizando os passos anteriores da forma correta, independente da execução do script de banco, o Módulo já deve ser reconhecido na tela aberta pelo menu indicado.
5. Rodar o script de banco "/sip/scripts/sip_atualizar_versao_modulo_utilidades.php" em linha de comando no servidor do SIP, verificando se não houve erro em sua execução, em que ao final do log deverá ser informado "FIM". Exemplo de comando de execução:

		/usr/bin/php -c /etc/php.ini /opt/sip/scripts/sip_atualizar_versao_modulo_utilidades.php 2>&1 > atualizar_versao_modulo_utilidades_sip.log

6. Rodar o script de banco "/sei/scripts/sei_atualizar_versao_modulo_utilidades.php" em linha de comando no servidor do SEI, verificando se não houve erro em sua execução, em que ao final do log deverá ser informado "FIM". Exemplo de comando de execução:

		/usr/bin/php -c /etc/php.ini /opt/sei/scripts/sei_atualizar_versao_modulo_utilidades.php 2>&1 > atualizar_versao_modulo_utilidades_sei.log

7. **IMPORTANTE**: Na execução dos dois scripts de banco acima, ao final deve constar o termo "FIM", o "TEMPO TOTAL DE EXECUÇÃO" e a informação de que a instalação/atualização foi realizada com sucesso na base de dados correspondente (SEM ERROS). Do contrário, o script não foi executado até o final e algum dado não foi inserido/atualizado no respectivo banco de dados, devendo recuperar o backup do banco e repetir o procedimento.
   - Constando ao final da execução do script as informações indicadas, pode logar no SEI e SIP e verificar no menu **Infra > Parâmetros** dos dois sistemas se consta o parâmetro "VERSAO_MODULO_UTILIDADES" com o valor da última versão do módulo.
8. Em caso de erro durante a execução do script, verificar (lendo as mensagens de erro e no menu Infra > Log do SEI e do SIP) se a causa é algum problema na infraestrutura local ou ajustes indevidos na estrutura de banco do core do sistema. Neste caso, após a correção, deve recuperar o backup do banco pertinente e repetir o procedimento, especialmente a execução dos scripts de banco indicados acima.
9. Após a execução com sucesso, com um usuário com permissão de Administrador no SEI, seguir os passos dispostos no tópico "Orientações Negociais" mais abaixo.

## Orientações Negociais
1. ESCOPO DO MÓDULO, a partir da v2.1.0: 
    - No menu Controle de Desempenho > Distribuição é possível realizar filtragem por "Membro Participante" e "Período" e acompanhar os dados sobre "Total de Tempo Executado no Período", "Carga Horária Padrão no Período" e "Carga Horária Distribuída no Período".
    - No menu Controle de Desempenho > Meus Processos é possível realizar filtragem do Usuário Logado por "Período" e acompanhar os dados sobre "Total de Tempo Executado no Período", "Carga Horária Padrão no Período" e "Carga Horária Distribuída no Período". 
2. Ainda não foi implementada a integração para envio de dados à API sobre PGDs disponibilizada pelo Ministério da Economia.
   	- O módulo ainda não possui menu/tela com relatórios para acompanhamento geral de cada membro Participante sobre Períodos de execução passados, sendo necessário implementar solução de BI própria para ter uma visão geral do acompanhamento do desempenho, especialmente sobre Períodos anteriores ao Período atual.
   	- Até disponibilizarmos menu/tela com relatórios para acompanhamento geral de cada membro Participante sobre Períodos de execução passados, na pasta "bi_scripts" disponibilizamos três arquivos sql para extração de dados para uso em ferramenta de BI da instituição: "ExtracaoDados_historico.sql", "ExtracaoDados_tempo_executado.sql" e "ExtracaoDados_tempo_pendente.sql".
3. Imediatamente após a instalação com sucesso, com usuário com permissão de "Administrador" do SEI, acessar os menus de administração do Módulo pelo seguinte caminho: Administração > Controle de Desempenho.
4. O script de banco do SIP já cria todos os Recursos e Menus e os associam automaticamente ao Perfil "Básico" ou ao Perfil "Administrador".
	- O script de banco do SIP também cria o Perfil "Gestor de Controle de Desempenho" e associa os Recursos e Menus correspondentes. O mencionado Perfil deve ser concedido aos Gestores de Controle de Desempenho indicados em Administração > Controle de Desempenho > Tipos de Controle de Desempenho.
	- Independente da criação de outros Perfis, os recursos indicados para o Perfil "Básico" ou "Administrador" devem manter correspondência com os Perfis dos Usuários internos que utilizarão o Módulo e dos Usuários Administradores do Módulo.
	- Tão quanto ocorre com as atualizações do SEI, versões futuras deste Módulo continuarão a atualizar e criar Recursos e associá-los apenas aos Perfis "Básico", "Administrador" e "Gestor de Controle de Desempenho".
	- Todos os recursos do Módulo iniciam pelo sufix **"md_utl_"**.
5. Acesse no link a seguir o Manual de Administração: Ainda em construção
6. Acesse no link a seguir o Manual do Usuário Interno: Ainda em construção

## Erros ou Sugestões
1. [Abrir Issue](https://github.com/anatelgovbr/mod-sei-utilidades/issues) no repositório do GitHub do módulo se ocorrer erro na execução dos scripts de banco do módulo no SEI ou no SIP acima.
2. [Abrir Issue](https://github.com/anatelgovbr/mod-sei-utilidades/issues) no repositório do GitHub do módulo se ocorrer erro na operação do módulo.
3. Na abertura da Issue utilizar o modelo **"1 - Reportar Erro"**.
