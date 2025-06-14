<?php
/***********************************************************
ImportHelper.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 17, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


function CheckPrivilage($action_code)
{
    if(session('role_info') != null)
        $privilage_array = json_decode(session('role_info'),true);
    else
        $privilage_array = array();

    return isset($privilage_array[$action_code]) ? $privilage_array[$action_code] : 'deny';
}
