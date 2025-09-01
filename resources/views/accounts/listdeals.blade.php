<?php
/***********************************************************
listdeals.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 30, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
List Deals
***********************************************************/

?>




@foreach( $lst_account_deals as $key => $ad_info )
   <tr>
        <td>{{ $ad_info->ad_id }}</td>
        <td>{{ $ad_info->ad_account_code }}</td>
        <td>{{ $ad_info->ad_deal_code }}</td>
        <td>{{ $ad_info->fk_account_id > 0 ? $accounts_array[ $ad_info->fk_account_id ] : "" }}</td>
        <td>{{ $ad_info->ad_deal_amount }}</td>
       <td style="width:2px;">
           @if( $ad_info->ad_is_approved == 0 )
            <a  data-ad_id="{{ $ad_info->ad_id }}"  href="#"  id="EDIT_DEAL_{{ $ad_info->ad_id }}" ><i class="fa fa-pencil-square" aria-hidden="true" height="16" ></i></a>
           @endif
       </td>
       <td style="width:2px;">
           @if( $ad_info->ad_is_approved == 1 )
               <a  data-ad_id="{{ $ad_info->ad_id }}"  href="#"  id="VIEW_DEAL_{{ $ad_info->ad_id }}" ><i class="fas fa-eye" aria-hidden="true" height="16" ></i></a>
           @endif
        </td>
        <td style="width:2px;">
            <a  data-ad_id="{{ $ad_info->ad_id }}"  href="#"  id="DELETE_DEAL_{{ $ad_info->ad_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a>
       </td>
    </tr>
 @endforeach
