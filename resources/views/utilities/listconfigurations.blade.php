<?php
/***********************************************************
listconfigurations.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jan 8, 2021
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2021

Page Description :

***********************************************************/

?>


@extends('layouts.layout',['page_title' => "Configuration Management"])

@section('plugins')
<script type="text/javascript" src="{{ url('js/libraries/system/configuration.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Main Configuration</h3>
        <div class="card-toolbar">
            <div class="btn-group">
              <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                Action
              </button>
              <ul class="dropdown-menu">
              </ul>
            </div>
        </div>
    </div>
    <div class="card-body">
    <form name="form_save_configurations" id="FORM_SAVE_CONFIGURATIONS">
         <table  class="table table-bordered table-striped table-condensed flip-content" style="width:100%">
            <thead class="flip-content">
                <tr>
                    <th style="width:2%">#</th>
                    <th style="width:30%">Index</th>
                    <th style="width:20%">Value</th>
                    <th style="width:48%">Description</th>
                </tr>
            </thead>
            <tbody>
                <?php for ($i = 0; $i < count($sys_configurations); $i++) { ?>
                    <tr>
                       <td>
                       <input type="hidden" name="sa_id[]" value="{{ $sys_configurations[$i]->sa_id }}" />
                       <input type="hidden" name="sa_config_index[]" value="{{ $sys_configurations[$i]->sa_config_index }}" />
                        {{ $sys_configurations[$i]->sa_id }}
                       </td>
                       <td>{{ $sys_configurations[$i]->sa_config_index }}</td>
                       <td>
                            <input type="text" name="sa_config_value[]" class="form-control" value="{{ $sys_configurations[$i]->sa_config_value }}" />
                       </td>
                       <td>
                            <input type="text" name="sa_config_description[]" class="form-control" value="{{ $sys_configurations[$i]->sa_config_description }}" />
                       </td>
                   </tr>
                <?php } ?>
            </tbody>
        </table>
    </form>
	<div class="row">
        <div class="col-md-12" align="right">
            <button id="BTN_SAVE_CONFIGURATION" name="btn_save_configuration" type="button" class="btn btn-info"  >Save</button>
        </div>
    </div>
    </div>
 </div>

 
@endsection