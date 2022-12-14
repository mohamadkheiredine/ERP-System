<?php
/***********************************************************
lstcategories.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Apr 30, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :

***********************************************************/
?>
@foreach( $category_array as $index => $category_info )
<button type="button" name="{{ $category_info['id'] }}" onclick=""   class="Categorybtn" >
        {{ $category_info['category'] }}
</button>
@endforeach