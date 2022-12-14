<?php
/***********************************************************
labels.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Mar 27, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :

***********************************************************/
?>
<html>
	<head>
		<title></title>
		<style>
                body {
                    width: 100%;
                    height: 100%;
                    margin: 0;
                    padding: 0;
                    background-color: white;
                    font: 12pt "Tahoma";
                }
                * {
                    box-sizing: border-box;
                    -moz-box-sizing: border-box;
                }
                .page {
                    width: 50mm;
                    min-height: 279mm;
                    padding: 0px;
                    margin: 10mm auto;
                    border: 1px #D3D3D3 solid;
                    border-radius: 5px;
                    background: white;
                    box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
                }
                .subpage {
                    padding: 0px;
                    border:solid 0px red;
                    height: 239mm; /* .page height -40mm (20 off top/bottom) */
                    outline: 2cm white solid;
                }
                
                @page {
                    size: Letter;
                    margin: 0;
                }
                @media print {
                    html, body {
                        width: 50mm;
                        height: 279mm;        
                    }
                    .page {
                        margin: 0;
                        border: initial;
                        border-radius: initial;
                        width: initial;
                        min-height: initial;
                        box-shadow: initial;
                        background: initial;
                        page-break-after: always;
                    }
                }
                .lblUSID{
                	font-size:8px;
                }
                ul.lstLabels{
                	width:50mm;
                    list-style-type: none;
                	position: absolute;
                }
                ul.lstLabels li{
                	position: relative;
                	float: left;
                }
        </style>
        <script type="text/javascript">
        window.print();
        </script>
	</head>
	<body>
		<div class="book">
            <div class="page">
                <div class="subpage">
                	<ul class="lstLabels">
                		@foreach($serial_numbers_array as $serial_number => $barecode_image)
                    		<li>
                    			<img src="data:image/png;base64,{{ $barecode_image }}" alt="barcode" height="50" width="150"   /><br/>
                    			<label class='lblUSID'>{{ $serial_number }}</label>
                    		</li>
                		@endforeach
                		
                	</ul> 
                </div>    
            </div>
        </div>
	</body>
</html>