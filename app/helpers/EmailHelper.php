<?php

/***********************************************************
EmailHelper.php
Product :
Version : 1.0
Release : 2
Date Created : Sep 21, 2016
Developed By  : Mohamad Mantach   PHP Department Softweb S.A.R.L
All Rights Reserved ,    Softweb S.A.R.L COPYRIGHT 2015

Page Description :
{Enter page description Here}
***********************************************************/

function SendEmail( $params_array )
{
    Mail::send($params_array['view'], $params_array['email_params'], function($message)  use ($params_array)
    {
        $account_email = $params_array['account_email'];
        $account_name = $params_array['account_name'];
        $email_subject = $params_array['email_subject'];
        $message->from('no-reply@omsar.gov.lb', 'OMSAR Training Center');
        $message->to($account_email, $account_name)->subject($email_subject);
    });
}