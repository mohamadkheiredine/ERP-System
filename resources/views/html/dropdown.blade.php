<?php
/***********************************************************
dropdown.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 17, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


?>
 <select class="bs-select form-control" name="{{ $name }}" id="{{ $id }}" data-actions-box="true">
        @foreach($html_array as $id => $title)
            <option value="{{ $id }}">{{ $title }}</option>
        @endforeach
</select>