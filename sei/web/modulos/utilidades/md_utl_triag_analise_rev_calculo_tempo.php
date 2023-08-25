<?php
    $col_def_labels  = "col-12 col-sm-10 col-md-6 col-lg-6";

    $somaTmpExecucao = $arrTempos['valorTempoPendenteExecucao'] ?? '0';
    $cargaPadrao     = $arrTempos['totalCarga'] ?? '0';
    $tmpExecutado    = $arrTempos['unidEsforcoHist'] ?? '0';
    $tmpDistribuido  = $arrTempos['totalUnidEsforco'] ?? '0';
    $tpPeriodo       = $arrTempos['tipoPeriodo'] ?? '';

  // textos dos tooltips
    $txtTooltipTotalTempoPendenteExecucao = 'O Total corresponde à soma do Tempo de Execução de cada processo sob responsabilidade do usuário logado, conforme constante na tabela de listagem abaixo, independentemente de quando tenha sido Distribuído.\n \n O Tempo de Execução de Triagem é padrão por Fila do Controle de Desempenho.\n \n O Tempo de Execução de Análise depende das Atividades incluídas na fase de Triagem. Contudo, ao final, o Membro Participante que realizar a Análise somente ganhará o Tempo de Execução das Atividades que tenha entregado pelo menos um Produto.\n \n O Tempo de Execução de Avaliação depende de cada Produto entregue nas Atividades na fase de Análise.';
    $txtTooltipTotalTempoExecutadoPeriodo = MdUtlAdmPrmGrINT::recuperarTextoFrequenciaTooltipDinamicoMeusProcessos($idTipoControle, $strTela);
    $txtTooltipCargaHorariaDistribuidaPeriodo = MdUtlAdmPrmGrINT::recuperarTextoFrequenciaTooltipDinamicoCargaHorariaDistribuidaPeriodo($idTipoControle);
    $txtTooltipTotalTempoExecutadoPeriodoRascunho = "O Total abrange as atividades salvas em rascunho neste processo, conforme definido nos parâmetros gerais do Tipo de Controle de Desempenho.";
  ?>
<div class="row mt-3 mb-3">
    <div class="<?= $col_def_labels ?> mb-2 justify-content-center align-self-center" id="divSomaTmpExecucao">
        <label class="infraLabelOpcional">
            Total de Tempo Pendente de Execução:
            <img align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg"
                 name="ajuda" <?= PaginaSEI::montarTitleTooltip($txtTooltipTotalTempoPendenteExecucao,'Ajuda') ?> />
        </label>
        <span id="spnTempoPendente" class="badge badge-primary badge-pill ml-1 p-2" style="vertical-align: top;"><?= MdUtlAdmPrmGrINT::convertToHoursMins($somaTmpExecucao) ?></span>
    </div>

    <div class="<?= $col_def_labels ?> mb-2" id="divCargaHrDistribExec">
        <div style="float:left">
            <label id="lblCargaHrDistribExec" class="infraLabelOpcional">
                Total de Tempo Executado no Período:
                <img align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg"
                     name="ajuda" <?= PaginaSEI::montarTitleTooltip($txtTooltipTotalTempoExecutadoPeriodo,'Ajuda') ?> />
            </label>
            <span id="spnCargaHrDistribExec" class="badge badge-primary badge-pill ml-1 p-2" style="vertical-align: top;"><?= MdUtlAdmPrmGrINT::convertToHoursMins($tmpExecutado)?></span>
        </div>
        <div id="divCargaHrDistribExecRascunho" style="display: none; float: left; margin-left: 5px">
            <label id="lblCargaHrDistribExecRascunho" class="infraLabelOpcional">
                + Rascunho:
                <img align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg"
                     name="ajuda" <?= PaginaSEI::montarTitleTooltip($txtTooltipTotalTempoExecutadoPeriodoRascunho,'Ajuda') ?> />
            </label>
            <span id="spnCargaHrDistribRascunho" class="badge badge-warning badge-pill ml-1 p-2" style="vertical-align: top;">0min</span>
        </div>
    </div>

    <div class="<?= $col_def_labels ?> mb-2" id="divCargaPadPeriodo">
        <label id="lblCargaHrPadrao" class="infraLabelOpcional">Carga Exigível no Período Atual:</label>
        <img align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg"
             name="ajuda" <?= PaginaSEI::montarTitleTooltip($txtTooltipTotalTempoExecutadoPeriodo,'Ajuda') ?> />

        <span id="spnCargaHrPadrao" class="badge badge-primary badge-pill ml-1 p-2" style="vertical-align: top;"><?= MdUtlAdmPrmGrINT::convertToHoursMins($cargaPadrao) . ' - '. $tpPeriodo ?></span>
    </div>

    <div class="<?= $col_def_labels ?> mb-2" id="divCargaHrDistrib">
        <label id="lblCargaHrDistrib" class="infraLabelOpcional">
            Carga Horária Distribuída no Período:
            <img align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg"
                 name="ajuda" <?= PaginaSEI::montarTitleTooltip($txtTooltipCargaHorariaDistribuidaPeriodo,'Ajuda') ?> />
        </label>
        <span id="spnCargaHrDistrib" class="badge badge-primary badge-pill ml-1 p-2" style="vertical-align: top;"><?= MdUtlAdmPrmGrINT::convertToHoursMins($tmpDistribuido) ?></span>
    </div>
</div>