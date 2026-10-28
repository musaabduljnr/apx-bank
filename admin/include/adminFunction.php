<?php
//include '../vendor/autoload.php';
//use PHPMailer\PHPMailer\PHPMailer;
const APP_NAME = "Bank Pro";


if (!function_exists('toast_alert')) {
    function toast_alert($type,$msg, $title = false){
        if ($title === false){
            $alert_title = "Ops!!";
        }else{
            $alert_title = $title;
        }
        $toast = '<script type="text/javascript">
            $(document).ready(function(){
            
              swal({
                type: "'.$type.'",
                title: "'.$alert_title.'",
                text: "'.$msg.'",
                padding: "2em"
              })
            });
        </script>';
        echo $toast;
    }
}

//CARD STATUS
if (!function_exists('getCardStatus')) {
    function getCardStatus($status){
        if (!is_array($status) || !isset($status['card_status'])) {
            return '';
        }
        if($status['card_status']==='1'){
            return '<span class="text-success">ACTIVE</span>';
        }elseif($status['card_status']==='2'){
            return '<span class="text-primary">PROCESSING</span>';
        }elseif($status['card_status']==='3'){
            return '<span class="text-danger">HOLD</span>';
        }elseif($status['card_status']==='4'){
            return '<span class="text-danger">PAUSE</span>';
        }
        return '';
    }
}

//CARD TYPE
if (!function_exists('getCardType')) {
    function getCardType($type): string
    {
        $card_number = explode(' ',$type['card_number']);
        $card_type = null;
        if (substr($card_number[0],'0','2')==='52'){
            return "MASTER";
        }elseif (substr($card_number[0],'0','2')==='40'){
          return "VISA";
        }elseif (substr($card_number[0],'0','2')==='67'){
            return "MAESTRO";
        }elseif(substr($card_number[0],'0','2')==='30'){
           return "DINERS";
        }elseif(substr($card_number[0],'0','2')==='62'){
           return "UNIONPAY";
        }elseif(substr($card_number[0],'0','2')==='37'){
            return "AMERICAN EXPRESS";
        }elseif(substr($card_number[0],'0','2')==='60'){
            return "DISCOVER";
        }elseif(substr($card_number[0],'0','2')==='35'){
            return "JCB";
        }else{
            return "INVALID";
        }
    }
}

//WIRE TRANSACTION STATUS
if (!function_exists('wireStatus')) {
    function wireStatus($result){
        if ($result['wire_status'] === '0') {
            return '<span class="badge outline-badge-secondary shadow-none col-md-12">In Progress</span>';
        }
        if($result['wire_status'] === '2'){
            return  '<span class="badge outline-badge-danger shadow-none col-md-12">Hold</span>';
        }

        if($result['wire_status'] === '3') {
            return '<span class="badge outline-badge-danger shadow-none col-md-12">Cancelled</span>';
        }

        if($result['wire_status'] === '1') {
            return '<span class="badge outline-badge-primary shadow-none col-md-12">Completed</span>';
        }
    }
}

//CRYPTO TRANSACTION STATUS
if (!function_exists('cryptoTransaction')) {
    function cryptoTransaction($result){
        if ($result['crypto_status'] === '0') {
            return '<span class="badge outline-badge-secondary shadow-none col-md-12">In Progress</span>';
        }
        if($result['crypto_status'] === '2'){
            return  '<span class="badge outline-badge-danger shadow-none col-md-12">Hold</span>';
        }

        if($result['crypto_status'] === '3') {
            return '<span class="badge outline-badge-danger shadow-none col-md-12">Cancelled</span>';
        }

        if($result['crypto_status'] === '1') {
            return '<span class="badge outline-badge-primary shadow-none col-md-12">Completed</span>';
        }
    }
}

//DOMESTIC TRANSACTION STATUS
if (!function_exists('domesticTransaction')) {
    function domesticTransaction($result){
        if ($result['dom_status'] === '0') {
            return '<span class="badge outline-badge-secondary shadow-none col-md-12">In Progress</span>';
        }
        if($result['dom_status'] === '2'){
            return  '<span class="badge outline-badge-danger shadow-none col-md-12">Hold</span>';
        }

        if($result['dom_status'] === '3') {
            return '<span class="badge outline-badge-danger shadow-none col-md-12">Cancelled</span>';
        }

        if($result['dom_status'] === '1') {
            return '<span class="badge outline-badge-primary shadow-none col-md-12">Completed</span>';
        }
    }
}

//USERS CURRENCY
if (!function_exists('currency')) {
    function currency($row){
        if (!is_array($row) || !isset($row['acct_currency'])) {
            return "$";
        }
        if($row['acct_currency'] === 'USD'){
            return "$";
        }elseif($row['acct_currency'] === 'EUR'){
            return "&euro;";
        }
        return "$";
    }
}
