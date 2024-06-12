<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 07/08/2018 - criado por jhon.carvalho
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlMensagemINT extends InfraINT {

    public static $MSG_UTL_01 = 'Não é possível @VALOR1@ este Tipo de Controle pois as Unidades listadas abaixo estão associadas a outro Tipo de Controle:';
    public static $MSG_UTL_02 = 'As Unidades listadas abaixo não podem ser removidas pois estão vinculadas à um Fluxo de Atendimento Ativo no Controle de Desempenho:';
    public static $MSG_UTL_03 = 'A Unidade "@VALOR1@" não pode ser removida pois está vinculada à um Fluxo de Atendimento Ativo no Controle de Desempenho.';
    public static $MSG_UTL_04 = 'Não é possível @VALOR1@ este Tipo de Controle pois o mesmo está vinculado a uma @VALOR2@.';
    public static $MSG_UTL_05 = 'Não é possível @VALOR1@ este Tipo de Controle pois o mesmo está vinculado a um @VALOR2@.';
    public static $MSG_UTL_06 = 'Tamanho do campo @VALOR1@ excedido (máximo @VALOR2@ caracteres).';
    #public static $MSG_UTL_06 = '@VALOR1@ possui tamanho superior a @VALOR2@ caracteres.';
    public static $MSG_UTL_07 = 'Já existe um @VALOR1@ com este nome.';
    public static $MSG_UTL_08 = 'Já existe uma @VALOR1@ com este nome neste Tipo de Controle.';
    public static $MSG_UTL_09 = 'O Tipo de Atividade não pode ser alterado pois a mesma está vinculada a um Fluxo de Atendimento Ativo no Controle de Desempenho.';
    public static $MSG_UTL_10 = '@VALOR1@ já consta na lista.';
    public static $MSG_UTL_11 = 'Informe o campo @VALOR1@.';
    public static $MSG_UTL_12 = 'Informe ao menos um @VALOR1@.';
    public static $MSG_UTL_13 = 'Adicione ao menos um @VALOR1@.';
    public static $MSG_UTL_14 = 'Os Usuários listados abaixo já estão cadastrados como Usuários Participantes para esta parametrização:';
    public static $MSG_UTL_15 = '@VALOR1@ deve ser maior que zero.';
    public static $MSG_UTL_16 = 'Este @VALOR1@ já foi adicionado.';
    public static $MSG_UTL_17 = 'Não é possível @VALOR1@ este Tipo de Avaliação pois o mesmo está vinculado a uma Avaliação.';
    public static $MSG_UTL_18 = 'Informe ao menos uma @VALOR1@.';
    public static $MSG_UTL_19 = 'Já existe um @VALOR1@ com este nome neste Tipo de Controle.';
    public static $MSG_UTL_20 = 'Não é possível @VALOR1@ este Tipo de Produto pois o mesmo está vinculado a um Fluxo de Atendimento no Controle de Desempenho.';
    public static $MSG_UTL_21 = 'Não é possível @VALOR1@ esta Fila pois a mesma está vinculada a um Fluxo de Atendimento no Controle de Desempenho.';
    public static $MSG_UTL_22 = 'Não é possível adicionar esta Fila pois a mesma já está cadastrada para este Grupo de Atividade.';
    public static $MSG_UTL_23 = 'Não é possível @VALOR1@ pois a mesma está vinculada como Fila Padrão deste Tipo de Controle.';
    public static $MSG_UTL_24 = 'Esta Unidade não está associada a nenhum Tipo de Controle de Desempenho.';
    public static $MSG_UTL_25 = 'O Tipo de Controle desta Unidade não está Parametrizado.';
    public static $MSG_UTL_26 = 'Os processos listados abaixo estão com o Status diferente do permitido para associação, remova-os da seleção:';
    public static $MSG_UTL_27 = 'Selecione ao menos um Processo para associá-lo à Fila!';
    public static $MSG_UTL_28 = 'Não é possível @VALOR1@ esta Atividade pois a mesma está vinculado a uma Triagem em Fluxo de Atendimento no Controle de Desempenho.';
    public static $MSG_UTL_29 = 'Número SEI Inválido!';
    public static $MSG_UTL_30 = 'O Número SEI informado não pertence a este Processo.';
    public static $MSG_UTL_31 = 'O Número SEI informado possui um Tipo de Documento diferente do Produto esperado.';
    // TODO: Desativada valiação desnecessária do tipo de documento permitido do protocolo na tela de Análise
    //public static $MSG_UTL_32 = 'Os documentos permitidos para realizar este cadastro devem ser Internos ou Externos.';
    public static $MSG_UTL_33 = 'Não é possível @VALOR1@ este Tipo de Justificativa pois o mesmo está vinculado a uma Avaliação.';
    public static $MSG_UTL_34 = 'Não é possível @VALOR1@ este Tipo de Documento pois o mesmo está vinculado a uma Atividade no Controle de Desempenho.';
    public static $MSG_UTL_35 = 'Não é possível @VALOR1@ este Tipo de Produto pois o mesmo está vinculado a uma Atividade no Controle de Desempenho.';
    public static $MSG_UTL_36 = 'Não é possível @VALOR1@ este Usuário pois o mesmo está vinculado a uma Parametrização no Tipo de Controle do Controle de Desempenho.';
    public static $MSG_UTL_37 = 'Não é possível @VALOR1@ este Usuário pois o mesmo está vinculado a uma Jornada no Controle de Desempenho.';
    public static $MSG_UTL_38 = 'Não é possível @VALOR1@ esta Fila pois a mesma está vinculada a um Grupo de Atividade.';
    public static $MSG_UTL_39 = 'Não é possível @VALOR1@ este Tipo de Processo pois o mesmo está vinculado a um Tipo de Controle de Desempenho.';
    public static $MSG_UTL_40 = 'Não é possível @VALOR1@ este Usuário pois o mesmo está vinculado a um Tipo de Controle de Desempenho.';
    public static $MSG_UTL_41 = 'Não é possível @VALOR1@ este Usuário pois o mesmo está vinculado a um Fluxo de Atendimento Ativo no Controle de Desempenho.';
    public static $MSG_UTL_42 = 'Não é possível @VALOR1@ este Usuário pois o mesmo está vinculado a um Fluxo de Atendimento no Controle de Desempenho.';
    public static $MSG_UTL_43 = 'Não é possível @VALOR1@ esta Unidade pois a mesma está vinculada a um Tipo de Controle de Desempenho.';
    public static $MSG_UTL_44 = 'Não é possível @VALOR1@ esta Atividade pois a mesma está vinculada a um Grupo de Atividade.';
    public static $MSG_UTL_45 = 'Não é possível @VALOR1@ esta Unidade pois a mesma está vinculada um Fluxo de Atendimento no Controle de Desempenho.';
    public static $MSG_UTL_46 = 'Data Inválida.';
    public static $MSG_UTL_47 = 'O Prazo para Resposta não pode ser anterior a Data de Hoje.';
    public static $MSG_UTL_48 = 'Todas as Atividades precisam conter o mesmo Tipo de Análise para serem finalizadas.';
    public static $MSG_UTL_49 = 'Informe ao menos uma @VALOR1@.';
    public static $MSG_UTL_50 = 'Selecione todos os Produtos Esperados como obrigatórios.';
    public static $MSG_UTL_51 = 'Selecione ao menos uma Atividade para realizar a Análise.';
    public static $MSG_UTL_52 = 'Informe o campo Observação para todos os Resultados que possuem Justificativa.';
    public static $MSG_UTL_53 = 'Informe o campo Resultado para todas as Atividades.';
    public static $MSG_UTL_54 = 'Informe o campo Justificativa para todos os Resultados necessários.';
    public static $MSG_UTL_55 = 'A Data Inicial deve ser menor que a Data Final.';
    public static $MSG_UTL_56 = 'Para cadastrar uma Jornada é necessário ser o Gestor do Tipo de Controle.';
    public static $MSG_UTL_57 = 'Selecione uma Fila para realizar a Distribuição.';
    public static $MSG_UTL_58 = 'Selecione um Status para realizar a Distribuição.';
    public static $MSG_UTL_59 = 'Selecione ao menos um Processo para realizar a Distribuição.';
    public static $MSG_UTL_60 = 'Não é possível excluir este documento, pois o mesmo esta sendo utilizado no Controle de Desempenho.';
    public static $MSG_UTL_61 = 'Este processo está associado a histórico de Controle de Desempenho e não pode ser excluído.';
    public static $MSG_UTL_62 = 'Os Usuários listados abaixo já possuem uma Jornada Específica cadastrada para este período:';
    public static $MSG_UTL_63 = 'Já existe uma Jornada Geral cadastrada para este período neste Tipo de Controle.';
    public static $MSG_UTL_64 = 'O Tipo de Controle selecionado não está parametrizado. Realize a parametrização do mesmo para incluir uma Jornada.';
    public static $MSG_UTL_65 = 'O processo @VALOR1@ está aberto no Controle de Desempenho e não pode ser concluído nesta Unidade.';
    public static $MSG_UTL_66 = 'Os processos listados abaixo estão abertos no Controle de Desempenho, e não podem ser concluídos nesta Unidade.\n\n Segue lista:';
    public static $MSG_UTL_67 = 'O processo @VALOR1@ está aberto no Controle de Desempenho, para enviá-lo para Outra Unidade selecione a opção "Manter processo aberto na unidade atual".';
    public static $MSG_UTL_68 = 'Os processos listados abaixo estão abertos no Controle de Desempenho, para enviá-los para Outra Unidade, selecione a opção "Manter processo aberto na unidade atual".\n\n Segue lista:';
    public static $MSG_UTL_69 = 'O processo @VALOR1@ está aberto no Controle de Desempenho e não pode ser Anexado à outro processo.';
    public static $MSG_UTL_70 = 'Confirma desativação do @VALOR1@ "@VALOR2@" ?';
    public static $MSG_UTL_71 = 'Confirma desativação da @VALOR1@ "@VALOR2@" ?';
    public static $MSG_UTL_72 = 'Confirma reativação do @VALOR1@ "@VALOR2@" ?';
    public static $MSG_UTL_73 = 'Confirma reativação da @VALOR1@ "@VALOR2@" ?';
    public static $MSG_UTL_74 = 'Confirma exclusão do @VALOR1@ "@VALOR2@" ?';
    public static $MSG_UTL_75 = 'Confirma exclusão da @VALOR1@ "@VALOR2@" ?';
    public static $MSG_UTL_76 = 'Confirma desativação da Fila "@VALOR1@" no Grupo de Atividade "@VALOR2@"?';
    public static $MSG_UTL_77 = 'Confirma reativação da Fila "@VALOR1@" no Grupo de Atividade "@VALOR2@"?';
    public static $MSG_UTL_78 = 'Confirma exclusão da Fila "@VALOR1@" no Grupo de Atividade "@VALOR2@"?';
    public static $MSG_UTL_79 = 'O processo @VALOR1@ está aberto no Controle de Desempenho e não pode ser sobrestado.';
    public static $MSG_UTL_80 = 'Não é possível @VALOR1@ esta Fila pois a mesma está vinculada a uma Análise.';
    public static $MSG_UTL_81 = 'Não é possível @VALOR1@ esta Fila pois a mesma está vinculada a uma Triagem.';
    public static $MSG_UTL_82 = 'Não é possível remover este usuário, pois o mesmo possui vinculo com uma ou mais Filas.';
    public static $MSG_UTL_83 = 'Não é possível remover este usuário, pois o mesmo possui vinculo ativo com o Controle de Desempenho.';
    public static $MSG_UTL_84 = 'Os processos listados abaixo não estão associados à nenhuma Fila para realizar a exclusão da mesma remova-os da seleção:';
    public static $MSG_UTL_85 = 'O Processo @VALOR1@ não possui uma Fila atual para realizar a remoção.';
    public static $MSG_UTL_86 = 'O Prazo solicitado para @VALOR1@ é maior que o prazo permitido! Entre em contato com o Gestor do Tipo de Controle da sua área.';
    public static $MSG_UTL_87 = 'Não foram encontrados parâmetros de Ajuste de prazo. Converse com o Gestor do Tipo de Controle da sua área.';
    public static $MSG_UTL_88 = 'Selecione ao menos um processo para realizar a Distribuição!';
    public static $MSG_UTL_89 = 'Não é possível @VALOR1@ esta Justificativa de Ajuste de Prazo pois a mesma está vinculada a uma Solicitação de Ajuste de Prazo.';
    public static $MSG_UTL_90 = 'O Prazo informado deve ser maior que a Data Atual.';
    public static $MSG_UTL_91 = 'Informe um Dia Útil!';
    public static $MSG_UTL_92 = 'O processo @VALOR1@ já está distribuido para este usuário.';
    public static $MSG_UTL_93 = 'Os Processos listados abaixo já estão distribuidos para este usuário.\n\n Segue lista:';
    public static $MSG_UTL_94 = 'O Prazo Solicitado deve ser maior que o Prazo Atual!';
    public static $MSG_UTL_95 = 'Não foi encontrada Justificativa Ativa para o Tipo de Solicitação selecionado. Converse com o Gestor do Tipo de Controle da sua área.';
    public static $MSG_UTL_96 = 'Os processos listados abaixo estão com o Status diferente do permitido para Distribuição, remova-os da seleção:';
    public static $MSG_UTL_97 = 'Não é possível remover este usuário, pois o mesmo possui vinculo com uma ou mais Distribuições.';
    public static $MSG_UTL_98 = 'O Percentual @VALOR1@ deve ser entre 0 e 100.';
    public static $MSG_UTL_99 = 'Não existem itens para esta ação.';
    public static $MSG_UTL_100 = 'Nenhum @VALOR1@ selecionado.';
    public static $MSG_UTL_101 = 'Os registros indicados não possuem o status informado! Favor selecionar novamente.';
    public static $MSG_UTL_102 = 'Não foi possível enviar e-mail ao servidor por que o email não está cadastrado no contato do Servidor.';
    public static $MSG_UTL_103 = 'Confirma a @VALOR1@ do Ajuste de Prazo no Processo @VALOR2@?';
    public static $MSG_UTL_104 = 'O Usuário logado não está parametrizado no Tipo de Controle desta Unidade.';
    public static $MSG_UTL_105 = 'Confirma o retorno do processo no Status atual @VALOR1@ para o Status anterior?';
    public static $MSG_UTL_106 = 'Não é possível excluir esta Justificativa pois a mesma está vinculada a uma Contestação.';
    public static $MSG_UTL_107 = 'Confirma a Conclusão do Processo "@VALOR1@" na Unidade "@VALOR2@"?';
    public static $MSG_UTL_108 = 'Confirma a @VALOR1@ da Contestação de Avaliação no Processo @VALOR2@?';
    public static $MSG_UTL_109 = 'Selecione ao menos @VALOR1@ para adicionar na Parametrização da Distribuição.';
    public static $MSG_UTL_110 = 'As @VALOR1@ listadas abaixo já estão cadastradas como @VALOR2@ para esta parametrização:';
    public static $MSG_UTL_111 = 'Os @VALOR1@ listados abaixo já estão cadastrados como @VALOR2@ para esta parametrização:';
    public static $MSG_UTL_112 = 'Preencha a prioridade do fieldset "@VALOR1@" seguindo uma ordem numérica lógica.';
    public static $MSG_UTL_113 = 'Existe uma prioridade duplicada na tabela @VALOR1@';
    public static $MSG_UTL_114 = 'Não é possível @VALOR1@ esta Fila pois a mesma está cadastrada em uma lista de Filas priorizadas no Parâmetro de Distribuição do Tipo de Controle.';
    public static $MSG_UTL_115 = 'Não é possível @VALOR1@ esta Atividade pois a mesma está cadastrada em uma lista de Atividades priorizadas no Parâmetro de Distribuição do Tipo de Controle.';
    public static $MSG_UTL_116 = 'Não é permitido repetir a Prioridade Geral. Reveja as parametrizações atribuídas em @VALOR1@';
    public static $MSG_UTL_117 = 'Não é possível remover este Tipo de Processo, pois o mesmo possui vinculo ativo com o Controle de Desempenho.';
    public static $MSG_UTL_118 = 'Não é possível ainda acessar a tela de "Atividades", pois antes é necessário preencher os campos na tela "Parametrizar Tipo de Controle".';
    public static $MSG_UTL_119 = 'Não é possível ainda acessar a tela de "Filas", pois antes é necessário preencher os campos na tela "Parametrizar Tipo de Controle".';
    public static $MSG_UTL_120 = 'Não é possível associar Processos de Tipos de Controle de Desempenho distintos em uma Fila. Selecione Processos de apenas um Tipo de Controle para realizar a Associação a Fila.';
    public static $MSG_UTL_121 = 'Nome do Membro Participante não está preenchido. Por favor, tente novamente preencher este campo.';
    public static $MSG_UTL_122 = 'Não foi possível recuperar o nome do Usuário Participante. Por favor, tente mais tarde ou feche o sistema e entre novamente.';
    public static $MSG_UTL_123 = 'Para acessar a Gestão de Solicitações é necessário que o Usuário Logado esteja definido como Gestor do Controle de Desempenho na Administração > Controle de Desempenho > Tipo de Controle de Desempenho > Editar ou Avaliador em uma das Filas dos Tipos de Controle de Desempenho parametrizado para esta Unidade.';
    public static $MSG_UTL_124 = 'Usuário não vinculado à Fila ou está Desativado, com isso, o campo que sinaliza a Distribuição automática para o Novo Fluxo do processo foi desmarcado.';
    public static $MSG_UTL_125 = 'Antes de Salvar, acione o botão Editar sobre um determinado Ex-Participante e altere as informações.';
    public static $MSG_UTL_126 = 'Ao menos um campo precisa estar preenchido para realizar à atualização.';
    public static $MSG_UTL_127 = 'O Tipo de Integração "SOAP" ainda não está disponível nesta versão.';
    public static $MSG_UTL_128 = 'Indique como Dado Restrito a chave de autenticação definida no Header.';
    public static $MSG_UTL_129 = 'O Conteúdo de Autenticação é de preenchimento obrigatório.';
    public static $MSG_UTL_130 = 'Para remover este membro, antes é necessário preencher a Data Fim de Participação';
    public static $MSG_UTL_131 = 'Não é permitido solicitar ajuste de prazo, pois em nenhuma das atividades selecionadas deste processo possuem prazo definido.';
    public static $MSG_UTL_132 = 'Não foi possível distribuir processos para o usuário logado, pois nenhum processo foi encontrado considerando as parametrizações da Distribuição.';
    public static $MSG_UTL_133 = "Erro Interno no Servidor de Resposta\nHá um problema com o recurso que você está procurando e ele não pode ser exibido";
    public static $MSG_UTL_134 = "Falha na execução da consulta no webservice";
    public static $MSG_UTL_135 = "Nenhum recurso correspondente encontrado para determinada solicitação";
    public static $MSG_UTL_136 = 'A Carga Exigível no Período Selecionado corresponde ao Tempo de Execução exigido pelo usuário logado no Tipo de Controle indicado durante o período selecionado, abatendo o tempo de ausências formais (afastamentos, licenças e férias) ou o tempo de quando estiver no exercício de Chefia Imediata (inclusive Substituição), caso na Administração do Módulo no SEI esteja ativada a integração com o Sistema de Recursos Humanos.';
    public static $MSG_UTL_137 = 'A Carga Exigível no Período Selecionado corresponde ao Tempo de Execução exigido pelo Membro Responsável pela @VALOR1@ no Tipo de Controle indicado durante o período selecionado, abatendo o tempo de ausências formais (afastamentos, licenças e férias) ou o tempo de quando estiver no exercício de Chefia Imediata (inclusive Substituição), caso na Administração do Módulo no SEI esteja ativada a integração com o Sistema de Recursos Humanos.';
    public static $MSG_UTL_138 = "Nenhum Tipo de Controle de Desempenho foi configurado para a Unidade @VALOR1@.\n Antes, o Gestor do Controle de Desempenho necessita realizar as parametrizações na Administração do módulo.";

    public static function getMensagem($msg, $arrParams = null){
        $isPersonalizada = count(explode('@VALOR', self::$MSG_UTL_10)) > 1;

        if($isPersonalizada && !is_null($arrParams)){
            $msgPersonalizada = self::setMensagemPadraoPersonalizada($msg, $arrParams);
            return $msgPersonalizada;
        }

        return $msg;
    }

    public static function setMensagemPadraoPersonalizada($msg, $arrParametros = null)
    {
        if(!is_array($arrParametros)){
            $arrParametros = array($arrParametros);
        }

        if ($msg != '') {
            $arrSubstituicao = array();

            foreach ($arrParametros as $key => $param) {
                $vl = $key + 1;
                $arrSubstituicao[] = '@VALOR' . $vl . '@';
            }
            $msgRetorno = str_replace($arrSubstituicao, $arrParametros, $msg);
            return $msgRetorno;
        }

        return '';
    }

}
