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


function csvToArray($filename = '', $delimiter = ',')
{
    if (!file_exists($filename) || !is_readable($filename))
        return false;
        
        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false)
        {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
            {
                if (!$header)
                    $header = $row;
                    else
                        $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }
        
        return $data;
}