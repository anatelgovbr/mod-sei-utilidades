<?php
/**
 * Created by PhpStorm.
 * User: jhon.carvalho
 * Date: 11/07/2018
 * Time: 09:16
 */
if (0) { ?>
    <style><? }?>

#frmMdUtlAdmPrmGrCadastro{
  box-sizing: border-box;
}

.dv_container{
    display: flex;
    flex-direction: column;
}

.dv_container > div{
    width: 100% !important;
    margin: 8px;
    text-align: left;
}

.dv_container_hr{
    display: flex;
}

.dv_container_hr > div{    
    width: auto;
    margin: 0px;
    text-align: left;
}

.cls-img{
    width: 16px !important;
    height: 16px !important; 
    margin-left: 5px !important;
    vertical-align: sub;
}

.cls-select {
    width: 300px;
}

.cls-select[multiple] {
    width: 800px !important;
}

.cls-input {
    width: 300px;
}

.cls-fieldset{
    width: 800px !important;
}

.cls-fieldset-1{
    width: 836px !important;
}

.lupa{
    padding-top: 40px;
}

.space-row{
    line-height: 18px;
}

div.infraAreaDados{
    overflow: hidden;
}

button#btnAdicionar{
    float: right;
}

.cls-btn{
    width: 100%;
}

.cls-btn:after{
    content: "";
    clear: both;
}

/** Configuração relacionada a largura da tela */
@media screen and (max-width: 1200px) {
    .cls-select[multiple] , .cls-fieldset {
        width: 680px !important;
    }

    .cls-fieldset-1{
        width: 716px !important;
    }
}

@media screen and (max-width: 1000px) {
    .cls-select[multiple] , .cls-fieldset {
        width: 600px !important;
    }

    .cls-fieldset-1{
        width: 636px !important;
    }
}

@media screen and (max-width: 900px) {
    .cls-select[multiple] , .cls-fieldset {
        width: 540px !important;
    }

    .cls-fieldset-1{
        width: 576px !important;
    }

    .cls-input-2 , .cls-select-2{
        width: 230px;
    }
}

@media screen and (max-width: 800px) {
    .cls-select[multiple] , .cls-fieldset {
        width: 440px !important;
    }

    .cls-fieldset-1{
        width: 476px !important;
    }
}

@media screen and (max-width: 700px) {
    .cls-select[multiple] , .cls-fieldset {
        width: 340px !important;
    }

    .cls-fieldset-1{
        width: 432px !important;
    }
}

@media screen and (max-width: 650px) {
    button#btnAdicionar{
        float: left;
        margin-left: 9px;
    }

    .cls-select[multiple] {
        width: 382px !important;
    }

    .cls-fieldset-1{
        width: 365px !important;
    }
}

<?
if (0) { ?></style><?
} ?>