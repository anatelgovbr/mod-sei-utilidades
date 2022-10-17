<script type="text/javascript">

    function inicializar() {
        document.getElementById('btnCancelar').focus();
        infraEfeitoTabelas(true);
        hiddenblocoQtdDiasReprovacao(document.getElementById('selResultado').value);
    }

    function hiddenblocoQtdDiasReprovacao(resp) {
        if (resp === 'S') {
            let div = document.getElementById('blocoQtdDiasReprovacao');
            div.style.display = 'block';
        } else {
            let div = document.getElementById('blocoQtdDiasReprovacao');
            let input = document.getElementById('txtQtdDiasReprovacao');

            input.value = '';
            div.style.display = 'none';
        }
    }

    function validarCadastro() {
        if (document.getElementById('selResultado').value == 0) {
            alert('Informe a se haverá Reprovação Tática.');
            document.getElementById('selResultado').focus();
            return false;
        }
        if (document.getElementById('selResultado').value === 'S') {
            if (infraTrim(document.getElementById('txtQtdDiasReprovacao').value) == '' || document.getElementById('txtQtdDiasReprovacao').value == 0) {
                alert('Informe a quantidade de dias para Reprovação.');
                document.getElementById('txtQtdDiasReprovacao').focus();
                return false;
            }
        }

        return true;
    }

    function OnSubmitForm() {
        return validarCadastro();
    }
</script>