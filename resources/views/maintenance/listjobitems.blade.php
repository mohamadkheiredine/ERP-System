<?php
/***********************************************************
listjobitems.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jun 5, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :
List of Job Items saved in the database
***********************************************************/

?>

@foreach( $lst_job_items as $index => $item_info )
 <tr>
	<th scope="row">{{ $item_info->ji_id }}</th>
	<td>{{ $item_info->ji_id }}</td>
	<td>Stone</td>
	<td>@jhon</td>
</tr>
@endforeach
