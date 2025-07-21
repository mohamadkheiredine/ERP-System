<?php
/***********************************************************
 * comissions.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 7/19/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/

?>
<div class="row">
    <div class="col-md-12" style="text-align: center">
        <h3>List Comissions</h3>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-striped gy-7 gs-7">
        <thead>
        <tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
            <th>Comission Label</th>
            <th>Value</th>
            <th>date</th>
        </tr>
        </thead>
        <tbody>
        @foreach( $lst_comissions as $index => $comission_info )
            <tr>
                <td>{{ $comission_info->pc_comission_label  }}</td>
                <td>{{ $comission_info->pc_comission_value  }}&nbsp;<b>{{ $comission_info->Currency->cc_currency_code  }}</b></td>
                <td>{{ $comission_info->pc_effective_date  }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
