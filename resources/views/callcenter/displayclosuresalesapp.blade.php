<?php
/***********************************************************
displayclosuresalesapp.blade.php
Product : titanerp
Version : 1.0
Release : 1
Date Created : Nov 10, 2024
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2024

Page Description :
{Enter page description Here}
***********************************************************/

?>

<table class="table table-striped gy-7 gs-7">
        <thead>
                <tr
                        class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
                        <th style="width: 2px;">#</th>
                        <th style="width: 2px;">closing average</th>
                        <?php  foreach ($aptres_array as $key => $res) { ?>
                          <th>{{ $res }}</th>   
                       <?php } ?>
                        
                </tr>
        </thead>
        <tbody>
             <?php  foreach ($sales_array as $sales_id => $salesman) { ?>
              <tr>
               <td>{{$salesman}}</td>
               <td>{{isset($percentage_colsure_array[$sales_id]) ? $percentage_colsure_array[$sales_id] : "-" }}&nbsp;%</td>
                <?php  foreach ($aptres_array as $res_id => $res) { ?>
                    <td>{{ (isset($total_results_app[ $sales_id ]) && isset($total_results_app[ $sales_id ][ $res_id ])) ? $total_results_app[ $sales_id ][ $res_id ] : "-" }}</td>   
                 <?php } ?>
            </tr>
            <?php } ?>
        </tbody>
</table>