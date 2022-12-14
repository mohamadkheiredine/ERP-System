<?php
/***********************************************************
ArrayHelper.php
Product :
Version : 1.0
Release : 2
Date Created : Oct 22, 2016
Developed By  : Mohamad Mantach   PHP Department
All Rights Reserved ,    Mohamad Mantach COPYRIGHT 2016

Page Description :
All functions related to management an array
***********************************************************/


/**
 * generate a new array from database object with index a passed by the function
 *
 * @author Moe Mantach
 * @access public
 */
function CreateDatabaseArrayByIndex($dbObj , $array_index )
{
    $new_array = array();
    foreach ($dbObj as $key => $db_info) {
        $new_array[ $db_info->$array_index ] = $db_info;
    }

    return $new_array;
}

function CreateDropDownArrayByIndex($dbObj , $option_index , $value_value )
{
    $new_array = array();
    foreach ($dbObj as $key => $db_info) {
        $new_array[ $db_info->$option_index ] = $db_info->$value_value;
    }

    return $new_array;
}

function rand_color() {
    return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
}


function CreateDirectory( $DirectoryName , $permission = 0775 )
{
    return File::makeDirectory( $DirectoryName , 0775, true, true );
}