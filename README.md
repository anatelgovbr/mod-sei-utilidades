# Módulo Utilidades

## Requisitos
- SEI 3.1.3 instalado/atualizado.
   - Verificar valor da constante de versão no arquivo /sei/web/SEI.php ou, após logado no sistema, parando o mouse sobre a logo do SEI no canto superior esquerdo.
- Antes de executar os scripts de instalação/atualização, o usuário de acesso aos bancos de dados do SEI e do SIP, constante nos arquivos ConfiguracaoSEI.php e ConfiguracaoSip.php, deverá ter permissão de acesso total ao banco de dados, permitindo, por exemplo, criação e exclusão de tabelas.
- Os códigos-fonte do Módulo podem ser baixados a partir do link a seguir, devendo sempre utilizar a versão mais recente: [https://github.com/anatelgovbr/mod-sei-utilidades/releases](https://github.com/anatelgovbr/mod-sei-utilidades/releases "Clique e acesse")
- Solicitamos que os Órgãos que tenham instalado o Módulo preencham a pesquisa a seguir, para termos um feedback sobre sua utilização: [http://bit.ly/Form_SEI_Mod_Utilidades](http://bit.ly/Form_SEI_Mod_Utilidades "Clique e acesse")

## Procedimentos para Instalação
1. Antes, fazer backup dos bancos de dados do SEI e do SIP.
2. Carregar no servidor os arquivos do módulo nas pastas correspondentes nos servidores do SEI e do SIP.
   - **Caso se trate de atualização de versão anterior do Módulo**, antes de copiar os códigos-fontes para a pasta "/sei/web/modulos/utilidades", é necessário excluir os arquivos anteriores pré existentes na mencionada pasta, para não manter arquivos de códigos que foram renomeados ou descontinuados.
3. Editar o arquivo "/sei/config/ConfiguracaoSEI.php", tomando o cuidado de usar editor que não altere o charset do arquivo, para adicionar a referência à classe de integração do módulo e seu caminho relativo dentro da pasta "/sei/web/modulos" na array 'Modulos' da chave 'SEI':

		'SEI' => array(
			'URL' => 'http://[Servidor_PHP]/sei',
			'Producao' => false,
			'RepositorioArquivos' => '/var/sei/arquivos',
			'Modulos' => array('UtilidadesIntegracao' => 'utilidades',)
			),

4. Antes de seguir para os próximos passos, é importante conferir se o Módulo foi corretamente declarado no arquivo "/sei/config/ConfiguracaoSEI.php". Acesse o menu **Infra > Módulos** e confira se consta a linha correspondente ao Módulo, pois, realizando os passos anteriores da forma correta, independente da execução do script de banco, o Módulo já deve ser reconhecido na tela aberta pelo menu indicado.
5. Rodar o script de banco "/sip/scripts/sip_atualizar_versao_modulo_utilidades.php" em linha de comando no servidor do SIP, verificando se não houve erro em sua execução, em que ao final do log deverá ser informado "FIM". Exemplo de comando de execução:

		/usr/bin/php -c /etc/php.ini /opt/sip/scripts/sip_atualizar_versao_modulo_utilidades.php 2>&1 > atualizacao_utilidades_sip.log &

6. Rodar o script de banco "/sei/scripts/sei_atualizar_versao_modulo_utilidades.php" em linha de comando no servidor do SEI, verificando se não houve erro em sua execução, em que ao final do log deverá ser informado "FIM". Exemplo de comando de execução:

		/usr/bin/php -c /etc/php.ini /opt/sei/scripts/sei_atualizar_versao_modulo_utilidades.php 2>&1 > atualizacao_modulo_utilidades_sei.log &

7. **IMPORTANTE**: Na execução dos dois scripts de banco acima, ao final deve constar o termo "FIM", o "TEMPO TOTAL DE EXECUÇÃO" e a informação de que a instalação/atualização foi realizada com sucesso na base de dados correspondente (SEM ERROS). Do contrário, o script não foi executado até o final e algum dado não foi inserido/atualizado no respectivo banco de dados, devendo recuperar o backup do banco e repetir o procedimento.
   - Constando ao final da execução do script as informações indicadas, pode logar no SEI e SIP e verificar no menu **Infra > Parâmetros** dos dois sistemas se consta o parâmetro "VERSAO_MODULO_UTILIDADES" com o valor da última versão do módulo.
8. Em caso de erro durante a execução do script, verificar (lendo as mensagens de erro e no menu Infra > Log do SEI e do SIP) se a causa é algum problema na infraestrutura local ou ajustes indevidos na estrutura de banco do core do sistema. Neste caso, após a correção, deve recuperar o backup do banco pertinente e repetir o procedimento, especialmente a execução dos scripts de banco indicados acima.
	- Caso não seja possível identificar a causa, entrar em contato com: Nei Jobson - neijobson@anatel.gov.br
9. Após a execução com sucesso, com um usuário com permissão de Administrador no SEI, seguir os passos dispostos no tópico "Orientações Negociais" mais abaixo.

## Orientações Negociais
1. Imediatamente após a instalação com sucesso, com usuário com permissão de "Administrador" do SEI, acessar os menus de administração do Módulo pelo seguinte caminho: Administração > Controle de Desempenho.
2. O script de banco do SIP já cria todos os Recursos e Menus e os associam automaticamente ao Perfil "Básico" ou ao Perfil "Administrador".
	- O script de banco do SIP também cria o Perfil "Gestor de Controle de Desempenho" e associa os Recursos e Menus correspondentes. O mencionado Perfil deve ser concedido aos Gestores de Controle de Desempenho indicados em Administração > Controle de Desempenho > Tipos de Controle de Desempenho.
	- Independente da criação de outros Perfis, os recursos indicados para o Perfil "Básico" ou "Administrador" devem manter correspondência com os Perfis dos Usuários internos que utilizarão o Módulo e dos Usuários Administradores do Módulo.
	- Tão quanto ocorre com as atualizações do SEI, versões futuras deste Módulo continuarão a atualizar e criar Recursos e associá-los apenas aos Perfis "Básico", "Administrador" e "Gestor de Controle de Desempenho".
	- Todos os recursos do Módulo iniciam pelo sufix **"md_utl_"**.
3. Acesse no link a seguir o Manual de Administração [http://bit.ly/Manual_SEI_Mod_Utilidades_Admin](http://bit.ly/Manual_SEI_Mod_Utilidades_Admin "Clique e acesse")
4. Acesse no link a seguir o Manual do Usuário Interno: [http://bit.ly/Manual_SEI_Mod_Utilidades_Usuario](http://bit.ly/Manual_SEI_Mod_Utilidades_Usuario "Clique e acesse")