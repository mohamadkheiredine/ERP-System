<?php
/***********************************************************
EncryptionManager.php
Product :
Version : 1.0
Release : 1
Date Created : May 1, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :
Class  to Encrypt and decript data
***********************************************************/

namespace App\Library;


class EncryptionManager
{
    
    public function __construct()
    {
        
    }
    
    
    public function encryptionsequence( $sequence )
    {
        $encryption_sequence = encrypt($sequence); 
        $app_key = env('APP_KEY');
        $encryption_sequence = $app_key . "-" . $encryption_sequence . "-" . $app_key;
        $encryption_sequence = base64_encode($encryption_sequence);
        
        return $encryption_sequence;
    }
    
    public function decryptsequence( $encryption_sequence)
    {
        $sequence = base64_decode($encryption_sequence);
        $dec_sequence = "";
        if( strlen($sequence) > 0 )
        {
            $sequence_array = explode("-", $sequence);
            $encrypted_sequence = $sequence_array[1];
            $dec_sequence = decrypt($encrypted_sequence);
        }

 
        
        return $dec_sequence;
    }
    
    
    public function __destruct()
    {
        
    }
}

?>

