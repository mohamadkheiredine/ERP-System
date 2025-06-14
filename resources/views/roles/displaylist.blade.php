<?php
/************************************************************
displaylist.blade.php
Product :
Version : 1.0
Release : 0
Date Created : Aug 7, 2015
Developed By  : Mohamad. Mantach  PHP Department Softweb S.A.R.L
All Rights Reserved, Softweb S.A.R.L COPYRIGHT 2015

Page Description :
--
************************************************************/


foreach ($list_roles as $index => $role_info)
{
    ?>
<tr data-role_id="<?php echo $role_info->role_id; ?>">
    <td><input type="checkbox" name="ck_role_<?php echo $role_info->role_id; ?>" id="CK_ROLE_<?php echo $role_info->role_id; ?>" value="1" /></td>
    <td><?php echo $role_info->role_id; ?></td>
    <td><?php echo $role_info->role_name; ?></td>
    <td><?php echo ( strlen($role_info->role_description) > 20 ) ?  substr($role_info->role_description, 20) : $role_info->role_description ; ?></td>
    <td style="width:2px;"><a  data-role_id="{{ $role_info->role_id }}"  href="#"  id="EDIT_ROLE_{{ $role_info->role_id }}" ><i class="fa-solid fa-pen-to-square" aria-hidden="true" height="16" ></i></a></td>
    <td style="width:2px;"><a  data-role_id="{{ $role_info->role_id }}"  href="#"  id="DELETE_ROLE_{{ $role_info->role_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
</tr>
    <?php
}
?>
