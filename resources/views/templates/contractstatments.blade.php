<!DOCTYPE html>
<html lang="ar">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Document</title>
      <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .Payments th,.Payments td {
            border: 1px solid black;
            text-align: center;
            padding: 8px;
            font-size: 16px;
            height: 40px;
        }
        .Payments th {
            background-color: #f2f2f2;
            font-size: 16px;
            height: 40px;
        }
        .signature-section {
            margin-top: 20px;
            text-align: right;
        }
        p,span{
            font-size: 16px;
        }
    </style>
</head>
<body dir="rtl">
    <h2 style="text-align: center;text-decoration: underline">
        إقرار و تعهد
    </h2>
    <p style="text-align: right;">أقر انا الموقع ادناه  %FULLNAME%  احمل %PAPERTYPE% ذات الرقم  <span class="large-text">%NATIONAL_ID%</span> %NATIONALITY% الجنسية</p>
    <p style="text-align: right;"> %ADDRESS% <span class="large-text">%PHONE_NUMBER%</span></p>
    <p style="text-align: right;">بأني مدين لشركة %COMPANY_NAME_TRANSLATION%  و سأقوم بتسديد مبلغ و قدره <span class="large-text">%TOTAL_PRICE%</span> %CURRENCY% و ألتزم بسداد هذا المبلغ على شكل أقساط شهرية لمدة %NUMBER_PAYMENTS% أشهر. يتم سداد القسط الأول في %FIRSTINVOICE% .</p>

    <table class="Payments">
        <thead>
            <tr>
                <th>رقم الدفعة</th>
                <th>تسدد بتاريخ</th>
                <th>قيمتها</th>
                <th>%CURRENCY%</th>
            </tr>
        </thead>
        <tbody>
           %LST_PAYMENTS%
        </tbody>
    </table>

    <div style="width:100%;height: 100px"></div>
    <br/><br/>
    <table dir="rtl" border="0">
        <tr>
            <td style="width:20%" dir="rtl">الاسم:</td>
            <td style="width:80%"></td>
        </tr>
        <tr>
            <td colspan="2" style="height:20px;">&nbsp;</td>
        </tr>
        <tr>
            <td style="width:20%" dir="rtl">التوقيع:</td>
            <td style="width:80%"></td>
        </tr>
        <tr>
            <td colspan="2" style="height:20px;">&nbsp;</td>
        </tr>
        <tr>
            <td style="width:20%" dir="rtl">ختم و توقيع الشركة:</td>
            <td style="width:80%"></td>
        </tr>
        <tr>
            <td colspan="2" style="height:20px;">&nbsp;</td>
        </tr>
        <tr>
            <td style="width:20%" dir="rtl">التاريخ:</td>
            <td style="width:80%"></td>
        </tr>
    </table>

</body>
</html>
