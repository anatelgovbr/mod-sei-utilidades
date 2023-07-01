<script type="text/javascript">
    var msg70 = '<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_70); ?>';
    var msg72 = '<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_72); ?>';
    var msg74 = '<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_74); ?>';

    function inicializar() {
        document.getElementById('btnFechar').focus();
        infraEfeitoTabelas(true);
    }

    <? if ($bolAcaoDesativar){ ?>
    function acaoDesativar(id, desc) {
        var msg = setMensagemPersonalizada(msg70, ['Justificativa de Contestação', desc]);
        if (confirm(msg)) {
            document.getElementById('hdnInfraItemId').value = id;
            document.getElementById('frmMdUtlAdmJustContestLista').action = '<?=$strLinkDesativar?>';
            document.getElementById('frmMdUtlAdmJustContestLista').submit();
        }
    }

    <? } ?>

    <? if ($bolAcaoReativar){ ?>
    function acaoReativar(id, desc) {
        var msg = setMensagemPersonalizada(msg72, ['Justificativa de Contestação', desc]);
        if (confirm(msg)) {
            document.getElementById('hdnInfraItemId').value = id;
            document.getElementById('frmMdUtlAdmJustContestLista').action = '<?=$strLinkReativar?>';
            document.getElementById('frmMdUtlAdmJustContestLista').submit();
        }
    }
    <? } ?>

    <? if ($bolAcaoExcluir){ ?>
    function acaoExcluir(id, desc) {
        var msg = setMensagemPersonalizada(msg74, ['Justificativa de Contestação', desc]);
        if (confirm(msg)) {
            document.getElementById('hdnInfraItemId').value = id;
            document.getElementById('frmMdUtlAdmJustContestLista').action = '<?=$strLinkExcluir?>';
            document.getElementById('frmMdUtlAdmJustContestLista').submit();
        }
    }

    <? } ?>

</script>