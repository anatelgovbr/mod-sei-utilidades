<?php

try {
    require_once dirname(__FILE__).'/../../SEI.php';

    session_start();

    //////////////////////////////////////////////////////////////////////////////
    //InfraDebug::getInstance()->setBolLigado(false);
    //InfraDebug::getInstance()->setBolDebugInfra(true);
    //InfraDebug::getInstance()->limpar();
    //////////////////////////////////////////////////////////////////////////////

    //criar possiveis regras para validar acesso a esta página
    SessaoSEI::getInstance()->validarLink();
    #SessaoSEI::getInstance()->validarPermissao( $_GET['acao'] );
    PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);

    // Variaveis globais
    $strDescricaoModal = 'Ex-Participantes';
    $strTitulo         = "Histórico de $strDescricaoModal";
    $idPrmGr           = ( !empty( $_GET ) && array_key_exists('id_prm_gr',$_GET) ) ? $_GET['id_prm_gr'] : $_POST['hdnIdPrmGr'];
    $idTpCtrl          = ( !empty( $_GET ) && array_key_exists('id_tp_ctrl',$_GET) ) ? $_GET['id_tp_ctrl'] : $_POST['hdnIdTpCtrl'];

    // Instancia as classes necessarias
    $objException              = new InfraException();
    $objMdUtlAdmHistPrmGrUsuRN = new MdUtlAdmHistPrmGrUsuRN();

    if( !empty( $_POST ) ){
        //Efetua validacoes
        if( empty( $_POST['hdnIdHistPrmGrUsu'] ) )
            $objException->lancarValidacao(MdUtlMensagemINT::$MSG_UTL_125);

        if ( empty($_POST['dthIniPart']) && empty($_POST['dthFimPart']) && empty($_POST['planoTrab']) )
            $objException->lancarValidacao(MdUtlMensagemINT::$MSG_UTL_126);

        //Efetua a atualizaçao
        $objMdUtlAdmHistPrmGrUsuRN->atualizarRegistroHist();

        PaginaSEI::getInstance()->adicionarMensagem( 'Registro Atualizado com sucesso.' , InfraPagina::$TIPO_MSG_AVISO );
    }

    // Monta os condicoes da consuta
    $objMdUtlAdmHistPrmGrUsuDTO = $objMdUtlAdmHistPrmGrUsuRN->configObjParams( $idPrmGr );

    // Configuracao de ordenacao e paginacao
    PaginaSEI::getInstance()->prepararOrdenacao($objMdUtlAdmHistPrmGrUsuDTO, 'Nome', InfraDTO::$TIPO_ORDENACAO_ASC);
    PaginaSEI::getInstance()->prepararPaginacao($objMdUtlAdmHistPrmGrUsuDTO, 200);
    PaginaSEI::getInstance()->processarPaginacao($objMdUtlAdmHistPrmGrUsuDTO);

    // Retorna os ex participantes
    $arrObjs = $objMdUtlAdmHistPrmGrUsuRN->getExParticipantesTipoCtrl( $idPrmGr );

    // Recupera qtd de registros para criar a tabela
    $numRegistros = count( $arrObjs );

    if ( $numRegistros > 0 ):

        // Inicia a tabela
        $strResultado .= '<table class="infraTable" style="width: 100%" summary="Tabela de '. $strDescricaoModal .'">';
        $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strDescricaoModal,$numRegistros).'</caption>';

        // Cabecalho
        $strResultado .= '<tr>';
        #$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao( $objMdUtlAdmHistPrmGrUsuDTO,'Nome ','Nome',$arrObjs ).'</th>';
        $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao( $objMdUtlAdmHistPrmGrUsuDTO,'Ex Participante','Sigla',$arrObjs ).'</th>';
        $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao( $objMdUtlAdmHistPrmGrUsuDTO,'Inicio Participacao','InicioParticipacao',$arrObjs ).'</th>';
        $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao( $objMdUtlAdmHistPrmGrUsuDTO,'Fim Participacao','FimParticipacao',$arrObjs ).'</th>';
        $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao( $objMdUtlAdmHistPrmGrUsuDTO,'Plano de Trabalho','IdDocumento',$arrObjs ).'</th>';
        $strResultado .= '<th class="infraTh">Ações</th>';
        $strResultado .= '</tr>';

        // Dados da tabela
        foreach ( $arrObjs as $k => $usu ){
            $planoTrab = empty( $usu->getDblIdDocumento() ) ? '' : $usu->getDblIdDocumento();
            $dtIni = !empty( $usu->getDthInicioParticipacao() ) ? explode(' ' , $usu->getDthInicioParticipacao() ) : '';
            $dtFim = !empty( $usu->getDthFimParticipacao() ) ? explode(' ' , $usu->getDthFimParticipacao() ) : '';
            $strParametroJs = "'{$usu->getNumIdUsuario()}','{$usu->getStrSigla()}','{$usu->getStrNome()}','{$dtIni[0]}','{$dtFim[0]}',{$usu->getNumIdMdUtlAdmHistPrmGrUsu()},'$planoTrab'";

            $strResultado .= '<tr>';
            #$strResultado .=    '<td>'.PaginaSEI::tratarHTML( $usu->getStrNome() ).'</td>';
            $strResultado .=    '<td class="txt-col-center">'.PaginaSEI::tratarHTML( $usu->getStrNome() .' ('.$usu->getStrSigla().')' ).'</td>';
            $strResultado .=    '<td class="txt-col-center">'.PaginaSEI::tratarHTML( $dtIni[0] ).'</td>';
            $strResultado .=    '<td class="txt-col-center">'.PaginaSEI::tratarHTML( $dtFim[0] ).'</td>';
            $strResultado .=    '<td class="txt-col-center">'.PaginaSEI::tratarHTML( $usu->getDblIdDocumento() ).'</td>';
            $strResultado .=    '<td class="txt-col-center">';
            $strResultado .=      '<a onclick="selecionarUsuario('.$strParametroJs.')"
                                      tabindex="'. PaginaSEI::getInstance()->getProxTabTabela() .'"><img src="'.PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/alterar.svg" title="Editar Usuário" alt="Editar Usuário" class="infraImg" /></a>&nbsp;';
            $strResultado .=    '</td>';
            $strResultado .= '</tr>';
        }

        // Fecha a tabela
        $strResultado .= '</table>';
    endif;

}catch( Exception $e ){
    PaginaSEI::getInstance()->processarExcecao($e);
    $url = "controlador.php?acao={$_GET['acao']}&acao_origem={$_GET['acao_origem']}&id_prm_gr=$idPrmGr&id_tp_ctrl=$idTpCtrl";
    header('Location: ' . SessaoSEI::getInstance()->assinarLink( $url ) );
    die;
}

// Monta os botoes
$arrComandos[] = '<button type="submit" accesskey="S" id="btnSalvarHist" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
$arrComandos[] = '<button type="button" accesskey="C" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';

// Montar estrutura do HTML
PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
PaginaSEI::getInstance()->fecharStyle();
require 'md_utl_geral_css.php';
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form method="post" id="frmMdUtlAdmPrmGrExParticipantes"
     action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao_origem'])?>">
    <?php
        PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        PaginaSEI::getInstance()->abrirAreaDados();
    ?>
    <div class="row">
            <div class="col-12">
                <div class="form-group">
                <label class="infraLabelOpcional">Ex Participante</label>
                <input type="text" class="infraText form-control" readonly id="nomeParticipante">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-4">
            <label class="infraLabelOpcional">Início Participação</label>
            <div class="input-group">
                <input type="text" class="infraText form-control" id="dthIniPart" name="dthIniPart" disabled
                       onchange="return validarFormatoData(this)"
                       onkeypress="return infraMascara(this, event,'##/##/####')" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                <img src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/calendario.svg' ?>" id="imgCalDthIni"
                     title="Selecionar Data Inicial" alt="Selecionar Data Início" class="infraImg"
                     onclick="infraCalendario('dthIniPart',this,false,'<?= date('d/m/Y') ?>');">
            </div>
        </div>
        <div class="col-4">
            <label class="infraLabelOpcional">Fim Participação</label>
            <div class="input-group">
                <input type="text" class="infraText form-control" id="dthFimPart" name="dthFimPart" disabled
                       onchange="return validarFormatoData(this)"
                       onkeypress="return infraMascara(this, event,'##/##/####')" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                <img src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/calendario.svg' ?>" id="imgCalDthFim"
                     title="Selecionar Data Fim" alt="Selecionar Data de Fim" class="infraImg"
                     onclick="infraCalendario('dthFimPart',this,false,'<?= date('d/m/Y') ?>');">
            </div>
        </div>
        <div class="col-4">
            <label class="infraLabelOpcional">Plano de Trabalho</label>
            <input type="number" class="infraText form-control" id="planoTrab" name="planoTrab" disabled>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="table-responsive">
                <?php PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros,$numRegistros == 0); ?>
            </div>
        </div>
    </div>

    <input type="hidden" id="hdnIdHistPrmGrUsu" name="hdnIdHistPrmGrUsu">
    <input type="hidden" id="hdnIdUsuario" name="hdnIdUsuario">
    <input type="hidden" id="hdnIdTpCtrl" name="hdnIdTpCtrl" value="<?= $idTpCtrl ?>">
    <input type="hidden" id="hdnIdPrmGr" name="hdnIdPrmGr" value="<?= $idPrmGr ?>">
    <?php PaginaSEI::getInstance()->fecharAreaDados(); ?>
</form>

<script type="text/javascript">
    function inicializar(){
        if( $('#divInfraAreaTabela').find('table').length == 0 ){
            $('#divInfraAreaPaginacaoSuperior').hide();
            $('#divInfraAreaTabela').parent().parent().addClass('mt-3');
            $('#divInfraAreaTabela > label').addClass('infraLabelOpcional');
        }else{
            if( $('#divInfraAreaPaginacaoSuperior').find('select').length == 0 ){
                $('#divInfraAreaPaginacaoSuperior').hide();
            }
        }
    }

    function selecionarUsuario(...usuario){
        // [0: id_usuario , 1: sigla, 2: nome , 3: dth_ini_part , 4: dth_fim_part , 5: id_utl_adm_hist_prm_gr_usu , 6: planoTrab]

        const idUsuario        = $('#hdnIdUsuario');
        const nomeParticipante = $('#nomeParticipante');
        const dthIni           = $('#dthIniPart');
        const dthFim           = $('#dthFimPart');
        const idHistPrmGrUsu   = $('#hdnIdHistPrmGrUsu');
        const planoTrab        = $('#planoTrab');

        //insere valor nos campos correspondentes
        idUsuario.val( usuario[0] );
        nomeParticipante.val( usuario[2] );
        dthIni.val( usuario[3] );
        dthFim.val( usuario[4] );
        idHistPrmGrUsu.val( usuario[5] );
        planoTrab.val( usuario[6] );

        //regras para habilitar os campos
        if( usuario[6] != '' && usuario[3] != '' ){ // se plano de trabalho preenchido e a data de inicio preenchido
            dthFim.attr('disabled',false);
        }else if( usuario[6] == '' || usuario[3] == '' ){ // se plano de trabalho nao preenchido e a data de inicio nao preenchido
            dthIni.attr('disabled',false);
            dthFim.attr('disabled',false);
            planoTrab.attr('disabled',false);
        }
    }
</script>

<?php
    PaginaSEI::getInstance()->fecharBody();
    PaginaSEI::getInstance()->fecharHtml();
?>

