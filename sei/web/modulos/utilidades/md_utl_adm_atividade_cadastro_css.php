<?php
/**
 * Created by PhpStorm.
 * User: jhon.carvalho
 * Date: 06/08/2018
 * Time: 11:28
 */
if (0) { ?>
    <style><? }?>

        #txtAtividade{
             width: 35%;
        }
        #txaDescricao{
            width: 50%;
        }

        #comAnalise input{
            width: 35%;
        }

        .blocoExibir{
            display: none;
        }

        #adicionar{
            margin-left: 5px;
        }

        #fieldListaProduto{
            width: 80%;

        }

        .tamImg{
            width: 16px;
            height: 16px;
            margin-bottom: -3.2px;
        }

        #rdnTpAtivdadeComAnalise{
            position: absolute;
            margin-top: -0.5px;
        }

        #rdnTpAtivdadeSemAnalise{
            position: absolute;
            margin-top: -0.5px;
            margin-left: 130px;
        }

        #lblComAnalise{
            position: absolute;
            margin-left: 21px;
        }

        #lblSemAnalise{
            position: absolute;
            margin-left: 146px;
        }

        #divRadiosAnalise{
            margin-top: 7px;
        }

        #divAnalise{
            margin-top: 4px;
            margin-bottom: 25px;
        }

        #rdnProduto{
            position: absolute;
            margin-left: 120px;
            margin-top: 0px;
        }

        #rdnDocumento{
            position:  absolute;
            margin-bottom: 35px;
            margin-top: 0px;
        }

        #lblDocumentoSEI{
            position: absolute;
            margin-left: 21px;
        }

        #lblProduto{
            position: absolute;
            margin-left: 136px;
        }

        #divRadiosAtividade{
            margin-top: 7px;
            margin-bottom: 27px;
        }

        #chkObrigatorio{
            margin-top: 2px;
            position: absolute;
        }

        #lblObrigatorio{
            margin-left: 20px;
        }

        #divVlRevisaoProdEsforco{
            margin-top: 14px;
        }

        #rdnAplicSerieInterno
        {
            position:  absolute;
            margin-top: 0px;
        }

        #lblInterno{
            margin-left: 20px;
        }

        #rdnAplicSerieExterno{
            position:  absolute;
            margin-top: 0px;
            margin-left: 47px;
        }

        #lblExterno{
            margin-left: 63px;
        }

        #divRadiosAplicacaoDoc{
            margin-top: 9px;
        }

        #divSelectAplicacaoDoc{
            margin-top: 10px;
            margin-bottom: 5px;
        }

        #divBtnAdicionar{
            position: absolute;
            margin-top: -24px;
            margin-left: 68%;
        }

        #lblAtvRevAmost{
            margin-left: 17px;
        }

        #chkAtvRevAmost{
            position: absolute;
            margin-left: 1px;
            margin-top: 2px;
        }

        #divAtvRevAmost{
            margin-bottom: 8px;
            margin-top: 8px;
        }

        #txaDescricao {
            resize: none
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
            transform: scale(0.3);
        }

        /* Hide default HTML checkbox */
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        /* The slider */
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: #2196F3;
        }

        input:focus + .slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked + .slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
            transform-origin: left;
        }

        .slider.round:before {
            border-radius: 50%;
        }

<?
 if (0) { ?></style><?
} ?>
