<?php if(0){ ?> <script>  <?php } ?>
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
                    format = hours + 'h ';
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

<?php if(0){ ?> <script> <?php } ?>