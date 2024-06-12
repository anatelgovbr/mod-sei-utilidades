<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4� REGI�O
*
* 07/08/2018 - criado por jhon.carvalho
*
* Vers�o do Gerador de C�digo: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlMensagemINT extends InfraINT {

    public static $MSG_UTL_01 = 'N�o � poss�vel @VALOR1@ este Tipo de Controle pois as Unidades listadas abaixo est�o associadas a outro Tipo de Controle:';
    public static $MSG_UTL_02 = 'As Unidades listadas abaixo n�o podem ser removidas pois est�o vinculadas � um Fluxo de Atendimento Ativo no Controle de Desempenho:';
    public static $MSG_UTL_03 = 'A Unidade "@VALOR1@" n�o pode ser removida pois est� vinculada � um Fluxo de Atendimento Ativo no Controle de Desempenho.';
    public static $MSG_UTL_04 = 'N�o � poss�vel @VALOR1@ este Tipo de Controle pois o mesmo est� vinculado a uma @VALOR2@.';
    public static $MSG_UTL_05 = 'N�o � poss�vel @VALOR1@ este Tipo de Controle pois o mesmo est� vinculado a um @VALOR2@.';
    public static $MSG_UTL_06 = 'Tamanho do campo @VALOR1@ excedido (m�ximo @VALOR2@ caracteres).';
    #public static $MSG_UTL_06 = '@VALOR1@ possui tamanho superior a @VALOR2@ caracteres.';
    public static $MSG_UTL_07 = 'J� existe um @VALOR1@ com este nome.';
    public static $MSG_UTL_08 = 'J� existe uma @VALOR1@ com este nome neste Tipo de Controle.';
    public static $MSG_UTL_09 = 'O Tipo de Atividade n�o pode ser alterado pois a mesma est� vinculada a um Fluxo de Atendimento Ativo no Controle de Desempenho.';
    public static $MSG_UTL_10 = '@VALOR1@ j� consta na lista.';
    public static $MSG_UTL_11 = 'Informe o campo @VALOR1@.';
    public static $MSG_UTL_12 = 'Informe ao menos um @VALOR1@.';
    public static $MSG_UTL_13 = 'Adicione ao menos um @VALOR1@.';
    public static $MSG_UTL_14 = 'Os Usu�rios listados abaixo j� est�o cadastrados como Usu�rios Participantes para esta parametriza��o:';
    public static $MSG_UTL_15 = '@VALOR1@ deve ser maior que zero.';
    public static $MSG_UTL_16 = 'Este @VALOR1@ j� foi adicionado.';
    public static $MSG_UTL_17 = 'N�o � poss�vel @VALOR1@ este Tipo de Avalia��o pois o mesmo est� vinculado a uma Avalia��o.';
    public static $MSG_UTL_18 = 'Informe ao menos uma @VALOR1@.';
    public static $MSG_UTL_19 = 'J� existe um @VALOR1@ com este nome neste Tipo de Controle.';
    public static $MSG_UTL_20 = 'N�o � poss�vel @VALOR1@ este Tipo de Produto pois o mesmo est� vinculado a um Fluxo de Atendimento no Controle de Desempenho.';
    public static $MSG_UTL_21 = 'N�o � poss�vel @VALOR1@ esta Fila pois a mesma est� vinculada a um Fluxo de Atendimento no Controle de Desempenho.';
    public static $MSG_UTL_22 = 'N�o � poss�vel adicionar esta Fila pois a mesma j� est� cadastrada para este Grupo de Atividade.';
    public static $MSG_UTL_23 = 'N�o � poss�vel @VALOR1@ pois a mesma est� vinculada como Fila Padr�o deste Tipo de Controle.';
    public static $MSG_UTL_24 = 'Esta Unidade n�o est� associada a nenhum Tipo de Controle de Desempenho.';
    public static $MSG_UTL_25 = 'O Tipo de Controle desta Unidade n�o est� Parametrizado.';
    public static $MSG_UTL_26 = 'Os processos listados abaixo est�o com o Status diferente do permitido para associa��o, remova-os da sele��o:';
    public static $MSG_UTL_27 = 'Selecione ao menos um Processo para associ�-lo � Fila!';
    public static $MSG_UTL_28 = 'N�o � poss�vel @VALOR1@ esta Atividade pois a mesma est� vinculado a uma Triagem em Fluxo de Atendimento no Controle de Desempenho.';
    public static $MSG_UTL_29 = 'N�mero SEI Inv�lido!';
    public static $MSG_UTL_30 = 'O N�mero SEI informado n�o pertence a este Processo.';
    public static $MSG_UTL_31 = 'O N�mero SEI informado possui um Tipo de Documento diferente do Produto esperado.';
    // TODO: Desativada valia��o desnecess�ria do tipo de documento permitido do protocolo na tela de An�lise
    //public static $MSG_UTL_32 = 'Os documentos permitidos para realizar este cadastro devem ser Internos ou Externos.';
    public static $MSG_UTL_33 = 'N�o � poss�vel @VALOR1@ este Tipo de Justificativa pois o mesmo est� vinculado a uma Avalia��o.';
    public static $MSG_UTL_34 = 'N�o � poss�vel @VALOR1@ este Tipo de Documento pois o mesmo est� vinculado a uma Atividade no Controle de Desempenho.';
    public static $MSG_UTL_35 = 'N�o � poss�vel @VALOR1@ este Tipo de Produto pois o mesmo est� vinculado a uma Atividade no Controle de Desempenho.';
    public static $MSG_UTL_36 = 'N�o � poss�vel @VALOR1@ este Usu�rio pois o mesmo est� vinculado a uma Parametriza��o no Tipo de Controle do Controle de Desempenho.';
    public static $MSG_UTL_37 = 'N�o � poss�vel @VALOR1@ este Usu�rio pois o mesmo est� vinculado a uma Jornada no Controle de Desempenho.';
    public static $MSG_UTL_38 = 'N�o � poss�vel @VALOR1@ esta Fila pois a mesma est� vinculada a um Grupo de Atividade.';
    public static $MSG_UTL_39 = 'N�o � poss�vel @VALOR1@ este Tipo de Processo pois o mesmo est� vinculado a um Tipo de Controle de Desempenho.';
    public static $MSG_UTL_40 = 'N�o � poss�vel @VALOR1@ este Usu�rio pois o mesmo est� vinculado a um Tipo de Controle de Desempenho.';
    public static $MSG_UTL_41 = 'N�o � poss�vel @VALOR1@ este Usu�rio pois o mesmo est� vinculado a um Fluxo de Atendimento Ativo no Controle de Desempenho.';
    public static $MSG_UTL_42 = 'N�o � poss�vel @VALOR1@ este Usu�rio pois o mesmo est� vinculado a um Fluxo de Atendimento no Controle de Desempenho.';
    public static $MSG_UTL_43 = 'N�o � poss�vel @VALOR1@ esta Unidade pois a mesma est� vinculada a um Tipo de Controle de Desempenho.';
    public static $MSG_UTL_44 = 'N�o � poss�vel @VALOR1@ esta Atividade pois a mesma est� vinculada a um Grupo de Atividade.';
    public static $MSG_UTL_45 = 'N�o � poss�vel @VALOR1@ esta Unidade pois a mesma est� vinculada um Fluxo de Atendimento no Controle de Desempenho.';
    public static $MSG_UTL_46 = 'Data Inv�lida.';
    public static $MSG_UTL_47 = 'O Prazo para Resposta n�o pode ser anterior a Data de Hoje.';
    public static $MSG_UTL_48 = 'Todas as Atividades precisam conter o mesmo Tipo de An�lise para serem finalizadas.';
    public static $MSG_UTL_49 = 'Informe ao menos uma @VALOR1@.';
    public static $MSG_UTL_50 = 'Selecione todos os Produtos Esperados como obrigat�rios.';
    public static $MSG_UTL_51 = 'Selecione ao menos uma Atividade para realizar a An�lise.';
    public static $MSG_UTL_52 = 'Informe o campo Observa��o para todos os Resultados que possuem Justificativa.';
    public static $MSG_UTL_53 = 'Informe o campo Resultado para todas as Atividades.';
    public static $MSG_UTL_54 = 'Informe o campo Justificativa para todos os Resultados necess�rios.';
    public static $MSG_UTL_55 = 'A Data Inicial deve ser menor que a Data Final.';
    public static $MSG_UTL_56 = 'Para cadastrar uma Jornada � necess�rio ser o Gestor do Tipo de Controle.';
    public static $MSG_UTL_57 = 'Selecione uma Fila para realizar a Distribui��o.';
    public static $MSG_UTL_58 = 'Selecione um Status para realizar a Distribui��o.';
    public static $MSG_UTL_59 = 'Selecione ao menos um Processo para realizar a Distribui��o.';
    public static $MSG_UTL_60 = 'N�o � poss�vel excluir este documento, pois o mesmo esta sendo utilizado no Controle de Desempenho.';
    public static $MSG_UTL_61 = 'Este processo est� associado a hist�rico de Controle de Desempenho e n�o pode ser exclu�do.';
    public static $MSG_UTL_62 = 'Os Usu�rios listados abaixo j� possuem uma Jornada Espec�fica cadastrada para este per�odo:';
    public static $MSG_UTL_63 = 'J� existe uma Jornada Geral cadastrada para este per�odo neste Tipo de Controle.';
    public static $MSG_UTL_64 = 'O Tipo de Controle selecionado n�o est� parametrizado. Realize a parametriza��o do mesmo para incluir uma Jornada.';
    public static $MSG_UTL_65 = 'O processo @VALOR1@ est� aberto no Controle de Desempenho e n�o pode ser conclu�do nesta Unidade.';
    public static $MSG_UTL_66 = 'Os processos listados abaixo est�o abertos no Controle de Desempenho, e n�o podem ser conclu�dos nesta Unidade.\n\n Segue lista:';
    public static $MSG_UTL_67 = 'O processo @VALOR1@ est� aberto no Controle de Desempenho, para envi�-lo para Outra Unidade selecione a op��o "Manter processo aberto na unidade atual".';
    public static $MSG_UTL_68 = 'Os processos listados abaixo est�o abertos no Controle de Desempenho, para envi�-los para Outra Unidade, selecione a op��o "Manter processo aberto na unidade atual".\n\n Segue lista:';
    public static $MSG_UTL_69 = 'O processo @VALOR1@ est� aberto no Controle de Desempenho e n�o pode ser Anexado � outro processo.';
    public static $MSG_UTL_70 = 'Confirma desativa��o do @VALOR1@ "@VALOR2@" ?';
    public static $MSG_UTL_71 = 'Confirma desativa��o da @VALOR1@ "@VALOR2@" ?';
    public static $MSG_UTL_72 = 'Confirma reativa��o do @VALOR1@ "@VALOR2@" ?';
    public static $MSG_UTL_73 = 'Confirma reativa��o da @VALOR1@ "@VALOR2@" ?';
    public static $MSG_UTL_74 = 'Confirma exclus�o do @VALOR1@ "@VALOR2@" ?';
    public static $MSG_UTL_75 = 'Confirma exclus�o da @VALOR1@ "@VALOR2@" ?';
    public static $MSG_UTL_76 = 'Confirma desativa��o da Fila "@VALOR1@" no Grupo de Atividade "@VALOR2@"?';
    public static $MSG_UTL_77 = 'Confirma reativa��o da Fila "@VALOR1@" no Grupo de Atividade "@VALOR2@"?';
    public static $MSG_UTL_78 = 'Confirma exclus�o da Fila "@VALOR1@" no Grupo de Atividade "@VALOR2@"?';
    public static $MSG_UTL_79 = 'O processo @VALOR1@ est� aberto no Controle de Desempenho e n�o pode ser sobrestado.';
    public static $MSG_UTL_80 = 'N�o � poss�vel @VALOR1@ esta Fila pois a mesma est� vinculada a uma An�lise.';
    public static $MSG_UTL_81 = 'N�o � poss�vel @VALOR1@ esta Fila pois a mesma est� vinculada a uma Triagem.';
    public static $MSG_UTL_82 = 'N�o � poss�vel remover este usu�rio, pois o mesmo possui vinculo com uma ou mais Filas.';
    public static $MSG_UTL_83 = 'N�o � poss�vel remover este usu�rio, pois o mesmo possui vinculo ativo com o Controle de Desempenho.';
    public static $MSG_UTL_84 = 'Os processos listados abaixo n�o est�o associados � nenhuma Fila para realizar a exclus�o da mesma remova-os da sele��o:';
    public static $MSG_UTL_85 = 'O Processo @VALOR1@ n�o possui uma Fila atual para realizar a remo��o.';
    public static $MSG_UTL_86 = 'O Prazo solicitado para @VALOR1@ � maior que o prazo permitido! Entre em contato com o Gestor do Tipo de Controle da sua �rea.';
    public static $MSG_UTL_87 = 'N�o foram encontrados par�metros de Ajuste de prazo. Converse com o Gestor do Tipo de Controle da sua �rea.';
    public static $MSG_UTL_88 = 'Selecione ao menos um processo para realizar a Distribui��o!';
    public static $MSG_UTL_89 = 'N�o � poss�vel @VALOR1@ esta Justificativa de Ajuste de Prazo pois a mesma est� vinculada a uma Solicita��o de Ajuste de Prazo.';
    public static $MSG_UTL_90 = 'O Prazo informado deve ser maior que a Data Atual.';
    public static $MSG_UTL_91 = 'Informe um Dia �til!';
    public static $MSG_UTL_92 = 'O processo @VALOR1@ j� est� distribuido para este usu�rio.';
    public static $MSG_UTL_93 = 'Os Processos listados abaixo j� est�o distribuidos para este usu�rio.\n\n Segue lista:';
    public static $MSG_UTL_94 = 'O Prazo Solicitado deve ser maior que o Prazo Atual!';
    public static $MSG_UTL_95 = 'N�o foi encontrada Justificativa Ativa para o Tipo de Solicita��o selecionado. Converse com o Gestor do Tipo de Controle da sua �rea.';
    public static $MSG_UTL_96 = 'Os processos listados abaixo est�o com o Status diferente do permitido para Distribui��o, remova-os da sele��o:';
    public static $MSG_UTL_97 = 'N�o � poss�vel remover este usu�rio, pois o mesmo possui vinculo com uma ou mais Distribui��es.';
    public static $MSG_UTL_98 = 'O Percentual @VALOR1@ deve ser entre 0 e 100.';
    public static $MSG_UTL_99 = 'N�o existem itens para esta a��o.';
    public static $MSG_UTL_100 = 'Nenhum @VALOR1@ selecionado.';
    public static $MSG_UTL_101 = 'Os registros indicados n�o possuem o status informado! Favor selecionar novamente.';
    public static $MSG_UTL_102 = 'N�o foi poss�vel enviar e-mail ao servidor por que o email n�o est� cadastrado no contato do Servidor.';
    public static $MSG_UTL_103 = 'Confirma a @VALOR1@ do Ajuste de Prazo no Processo @VALOR2@?';
    public static $MSG_UTL_104 = 'O Usu�rio logado n�o est� parametrizado no Tipo de Controle desta Unidade.';
    public static $MSG_UTL_105 = 'Confirma o retorno do processo no Status atual @VALOR1@ para o Status anterior?';
    public static $MSG_UTL_106 = 'N�o � poss�vel excluir esta Justificativa pois a mesma est� vinculada a uma Contesta��o.';
    public static $MSG_UTL_107 = 'Confirma a Conclus�o do Processo "@VALOR1@" na Unidade "@VALOR2@"?';
    public static $MSG_UTL_108 = 'Confirma a @VALOR1@ da Contesta��o de Avalia��o no Processo @VALOR2@?';
    public static $MSG_UTL_109 = 'Selecione ao menos @VALOR1@ para adicionar na Parametriza��o da Distribui��o.';
    public static $MSG_UTL_110 = 'As @VALOR1@ listadas abaixo j� est�o cadastradas como @VALOR2@ para esta parametriza��o:';
    public static $MSG_UTL_111 = 'Os @VALOR1@ listados abaixo j� est�o cadastrados como @VALOR2@ para esta parametriza��o:';
    public static $MSG_UTL_112 = 'Preencha a prioridade do fieldset "@VALOR1@" seguindo uma ordem num�rica l�gica.';
    public static $MSG_UTL_113 = 'Existe uma prioridade duplicada na tabela @VALOR1@';
    public static $MSG_UTL_114 = 'N�o � poss�vel @VALOR1@ esta Fila pois a mesma est� cadastrada em uma lista de Filas priorizadas no Par�metro de Distribui��o do Tipo de Controle.';
    public static $MSG_UTL_115 = 'N�o � poss�vel @VALOR1@ esta Atividade pois a mesma est� cadastrada em uma lista de Atividades priorizadas no Par�metro de Distribui��o do Tipo de Controle.';
    public static $MSG_UTL_116 = 'N�o � permitido repetir a Prioridade Geral. Reveja as parametriza��es atribu�das em @VALOR1@';
    public static $MSG_UTL_117 = 'N�o � poss�vel remover este Tipo de Processo, pois o mesmo possui vinculo ativo com o Controle de Desempenho.';
    public static $MSG_UTL_118 = 'N�o � poss�vel ainda acessar a tela de "Atividades", pois antes � necess�rio preencher os campos na tela "Parametrizar Tipo de Controle".';
    public static $MSG_UTL_119 = 'N�o � poss�vel ainda acessar a tela de "Filas", pois antes � necess�rio preencher os campos na tela "Parametrizar Tipo de Controle".';
    public static $MSG_UTL_120 = 'N�o � poss�vel associar Processos de Tipos de Controle de Desempenho distintos em uma Fila. Selecione Processos de apenas um Tipo de Controle para realizar a Associa��o a Fila.';
    public static $MSG_UTL_121 = 'Nome do Membro Participante n�o est� preenchido. Por favor, tente novamente preencher este campo.';
    public static $MSG_UTL_122 = 'N�o foi poss�vel recuperar o nome do Usu�rio Participante. Por favor, tente mais tarde ou feche o sistema e entre novamente.';
    public static $MSG_UTL_123 = 'Para acessar a Gest�o de Solicita��es � necess�rio que o Usu�rio Logado esteja definido como Gestor do Controle de Desempenho na Administra��o > Controle de Desempenho > Tipo de Controle de Desempenho > Editar ou Avaliador em uma das Filas dos Tipos de Controle de Desempenho parametrizado para esta Unidade.';
    public static $MSG_UTL_124 = 'Usu�rio n�o vinculado � Fila ou est� Desativado, com isso, o campo que sinaliza a Distribui��o autom�tica para o Novo Fluxo do processo foi desmarcado.';
    public static $MSG_UTL_125 = 'Antes de Salvar, acione o bot�o Editar sobre um determinado Ex-Participante e altere as informa��es.';
    public static $MSG_UTL_126 = 'Ao menos um campo precisa estar preenchido para realizar � atualiza��o.';
    public static $MSG_UTL_127 = 'O Tipo de Integra��o "SOAP" ainda n�o est� dispon�vel nesta vers�o.';
    public static $MSG_UTL_128 = 'Indique como Dado Restrito a chave de autentica��o definida no Header.';
    public static $MSG_UTL_129 = 'O Conte�do de Autentica��o � de preenchimento obrigat�rio.';
    public static $MSG_UTL_130 = 'Para remover este membro, antes � necess�rio preencher a Data Fim de Participa��o';
    public static $MSG_UTL_131 = 'N�o � permitido solicitar ajuste de prazo, pois em nenhuma das atividades selecionadas deste processo possuem prazo definido.';
    public static $MSG_UTL_132 = 'N�o foi poss�vel distribuir processos para o usu�rio logado, pois nenhum processo foi encontrado considerando as parametriza��es da Distribui��o.';
    public static $MSG_UTL_133 = "Erro Interno no Servidor de Resposta\nH� um problema com o recurso que voc� est� procurando e ele n�o pode ser exibido";
    public static $MSG_UTL_134 = "Falha na execu��o da consulta no webservice";
    public static $MSG_UTL_135 = "Nenhum recurso correspondente encontrado para determinada solicita��o";
    public static $MSG_UTL_136 = 'A Carga Exig�vel no Per�odo Selecionado corresponde ao Tempo de Execu��o exigido pelo usu�rio logado no Tipo de Controle indicado durante o per�odo selecionado, abatendo o tempo de aus�ncias formais (afastamentos, licen�as e f�rias) ou o tempo de quando estiver no exerc�cio de Chefia Imediata (inclusive Substitui��o), caso na Administra��o do M�dulo no SEI esteja ativada a integra��o com o Sistema de Recursos Humanos.';
    public static $MSG_UTL_137 = 'A Carga Exig�vel no Per�odo Selecionado corresponde ao Tempo de Execu��o exigido pelo Membro Respons�vel pela @VALOR1@ no Tipo de Controle indicado durante o per�odo selecionado, abatendo o tempo de aus�ncias formais (afastamentos, licen�as e f�rias) ou o tempo de quando estiver no exerc�cio de Chefia Imediata (inclusive Substitui��o), caso na Administra��o do M�dulo no SEI esteja ativada a integra��o com o Sistema de Recursos Humanos.';
    public static $MSG_UTL_138 = "Nenhum Tipo de Controle de Desempenho foi configurado para a Unidade @VALOR1@.\n Antes, o Gestor do Controle de Desempenho necessita realizar as parametriza��es na Administra��o do m�dulo.";

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
