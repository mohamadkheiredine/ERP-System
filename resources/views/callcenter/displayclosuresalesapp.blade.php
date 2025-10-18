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
@if(count($lst_closing_res) > 0)
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-light">
            <tr>
                @foreach(array_keys((array)$lst_closing_res[0]) as $column)
                    <th>{{ ucwords(str_replace('_', ' ', $column)) }}</th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            @foreach($lst_closing_res as $row)
                <tr>
                    @foreach((array)$row as $value)
                        <td>
                            @if(is_numeric($value))
                                {{ $value }}
                            @else
                                {{ $value }}
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="alert alert-info mb-0">No data found for the selected filters.</div>
@endif
