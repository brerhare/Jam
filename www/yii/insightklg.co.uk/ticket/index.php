<?php
    require_once ("PaymentFormHelper.php");
    include ("Config.php");
    ///// include ("ISOCurrencies.php");

    $Width = 600;
    $BodyAttributes = "";
    $FormAttributes = "";
    $FormAction = "PaymentForm.php"; // "Order.php";
    include ("Templates/FormHeader.tpl");

    Log::msg("Entrypoint index served initial page request");

    include ("Templates/index.tpl");
    include ("Templates/FormFooter.tpl");
?>

