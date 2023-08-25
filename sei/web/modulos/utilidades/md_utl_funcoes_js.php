<script type="text/javascript">

    function convertToHoursMins(time) {

        hours = Math.trunc(time / 60);
        minutes = (time % 60);
        if (time == 0) {
            format = '0min';
        } else {
            if (time < 60) {
                format = minutes + 'min';
            } else {
                if(minutes == 0)
                    format = hours + 'h';
                else
                    format = hours + 'h ' + minutes + 'min';
            }
        }

        return format;
    }

    function convertToMins(time){
        var tempo = time.split(" ");
        var minutos = 0;
        if(tempo.length == 1){
            if(tempo[0].indexOf("min") != -1){
                minutos = parseInt(tempo[0].replace("min", "")) + minutos;
            }
            if(tempo[0].indexOf("h") != -1) {
                minutos = parseInt(tempo[0].replace("h", "")) * 60;
            }
        } else {
            minutos = parseInt(tempo[0].replace("h", "")) * 60;
            minutos = parseInt(tempo[1].replace("min", "")) + minutos;
        }
        return minutos;
    }

    function validaQtdCaracteres(obj,max){
        var x = $( obj ).val().trim();
        var newLines = x.match(/(\r\n|\n|\r)/g);
        var addition = 0;
        if (newLines != null) {
            addition = newLines.length;
        }
        return (x.length + addition) <= max;
    }

    function retornaCalculoPercentual(tmp, perc){
        return Math.trunc(tmp / (1 + (perc / 100)));
    }

    function getCargaHrDistribuida( idsTpCtrl , idUsuario = null, tela = '' ){
        var params = {
            idUsuarioParticipante: idUsuario === null ? "<?= SessaoSEI::getInstance()->getNumIdUsuario() ?>" : idUsuario,
            idTipoControle: idsTpCtrl
        };

        $.ajax({
            url: "<?= $strUrlBuscarDadosCarga ?>",
            type: 'post',
            data: params,
            dataType: 'xml',
            success: function (r) {
                var cargaDisti = $(r).find('ValorUndEs').text();
                var cargaDistiExe = $(r).find('ValorUndEsExecutado').text();
                var cargaPadrao = $(r).find('ValorCarga').text();
                var tmpPendente = $(r).find('ValorTempoPendenteExecucao').text();
                var tpPeriodo = $(r).find('TipoPeriodo').text();

                var nomeTipoDePeriodo = '';
                if (tpPeriodo) {
                    nomeTipoDePeriodo = ' - ' + tpPeriodo;
                }

                document.getElementById('divCargaHrDistrib').style.display = 'block';
                document.getElementById('divCargaHrDistribExec').style.display = 'block';

                document.getElementById('spnCargaHrDistrib').innerHTML = String(convertToHoursMins(cargaDisti));
                document.getElementById('spnCargaHrDistribExec').innerHTML = String(convertToHoursMins(cargaDistiExe));
                document.getElementById('spnCargaHrPadrao').innerHTML = String(convertToHoursMins(cargaPadrao) + nomeTipoDePeriodo);

                if (document.getElementById('spnTempoPendente') !== null) {
                    document.getElementById('spnTempoPendente').innerHTML = String(convertToHoursMins(tmpPendente));
                }

                if ($("#spnCargaHrDistribRascunho").length) {
                    var totalTempoExecutadoPeriodo = $("#spnCargaHrDistribExec").html();
                    var tempoDecorrido = $("#spnCargaHrDistribRascunho").html();
                    tempoDecorrido = parseInt(convertToMins(tempoDecorrido)) + parseInt(convertToMins(totalTempoExecutadoPeriodo));
                    $("#spnCargaHrDistribRascunho").html(convertToHoursMins(tempoDecorrido));
                }

                if ( tela == 'distribuicao-listar' ) {
                    if ( $(r).find('ChefeImediato').text() == 'S' ) $('#divMsgChefiaImediata').show();
                }
            },
            error: function (e) {
                console.error('Erro ao buscar URL de Tipo de Controle: ' + e.responseText);
            }
        });
    }
</script>