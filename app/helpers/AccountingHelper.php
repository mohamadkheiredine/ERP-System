<?php
/***********************************************************
AccountingHelper.php
Product :
Version : 1.0
Release : 1
Date Created : Mar 17, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :

***********************************************************/




function convertCurrency($amount,$from_currency,$to_currency)
{
    $apikey = '90b8f7b6384738232778';
    
    $from_Currency = urlencode($from_currency);
    $to_Currency = urlencode($to_currency);
    $query =  "{$from_Currency}_{$to_Currency}";
    
    $total = 0;
    
    try {
        // change to the free URL if you're using the free version
        // $json = file_get_contents("https://api.currconv.com/api/v7/convert?q={$query}&compact=ultra&apiKey={$apikey}");
        $json = file_get_contents("https://free.currconv.com/api/v7/convert?q={$query}&compact=ultra&apiKey={$apikey}");
        $obj = json_decode($json, true);
        
        $val = floatval($obj["$query"]); 
        $total = $val * $amount;
    } catch (Exception $e) {
        $total = $amount;
    }
    
    
  
    return number_format($total, 2, '.', '');
}

/**
 * Get Currency Rate from api and get return 
 * @param unknown $from_currency
 * @param unknown $to_currency
 * @return number
 */
function GetCurrencyRate($from_currency,$to_currency)
{
    $apikey = '90b8f7b6384738232778';
    
    $from_Currency = urlencode($from_currency);
    $to_Currency = urlencode($to_currency);
    $query =  "{$from_Currency}_{$to_Currency}";
    
    // change to the free URL if you're using the free version
    // $json = file_get_contents("https://api.currconv.com/api/v7/convert?q={$query}&compact=ultra&apiKey={$apikey}");
    $json = file_get_contents("https://free.currconv.com/api/v7/convert?q={$query}&compact=ultra&apiKey={$apikey}");
    $obj = json_decode($json, true);
    
    $val = floatval($obj["$query"]);
    
     
    return $val;
}