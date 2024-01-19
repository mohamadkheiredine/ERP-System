<?php
/***********************************************************
floorsdropdown.blade.php
Product : titan HMIS
Version : 1.0
Release : 2
Date Created Nov 29, 2023
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2023

Page Description :
{Enter page description Here}
***********************************************************/

?>

<select data-control="select2" data-placeholder="Select a Floor" class="form-select" name="fk_floor_id" id="FK_FLOOR_ID" data-actions-box="true">
        <option value="">&nbsp;&nbsp;</option> 
         @foreach ( $lst_floors as $key => $floor_info )
                <option value="{{ $floor_info->wf_id }}">{{ $floor_info->wf_floor_title }}</option>
        @endforeach
</select>