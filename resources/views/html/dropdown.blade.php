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
 <select class="bs-select form-control" name="{{ $name }}" id="{{ $id }}" {{ ($is_required ?? 0) == 1 ? "required" : ""  }} data-actions-box="true">
        <option value="0" selected>-- Select Option --</option>
        @foreach($html_array as $did => $title)
            <option {{ $did == ($value ?? 0) ? "selected" : "" }} value="{{ $did }}">{{ $title }}</option>
        @endforeach
</select>
