<!DOCTYPE html>
<html>
<head>
	<title>EOTC Certificate</title>
</head>
<style type="text/css">
	.text-center{
		text-align: center;
	}
</style>
<body>
<table style="width:100%;margin-bottom: 2em;">
  <tr class="text-center">
    <td >
		<img src="{{ public_path('web/img/dti-logo.png') }}" style="width: 10em;">
    </td>
    
   </tr>
</table>
<div class="text-center">
	<p style="font-size: 18px;">This certifies that</p>
	<p style="font-size: 20px;font-weight: bold;">{{$transaction->customer ? $transaction->customer->full_name : $transaction->customer_name}}</p>
	<p style="font-size: 18px;">has successfully completed the application in PEZA EOTC-PHP with the following details:</p>
	<p style="font-size: 18px;">Application Name:</p>
	<p style="font-size: 20px;font-weight: bold;">{{str::title($transaction->application_name)}}</p>
	<p style="font-size: 18px;">Peza Unit:</p>
	<p style="font-size: 20px;font-weight: bold;">{{str::title($transaction->department_name)}}</p>
	<p style="font-size: 18px;">Date Issued:</p>
	<p style="font-size: 20px;font-weight: bold;">{{Helper::date_only($transaction->modified_at)}}</p>
	<p style="font-size: 18px;">In testimony whereof, I hereby sign this Digital Certificate</p>
	<p style="font-size: 18px;">and issue the same on {{Helper::date_only($transaction->modified_at)}} in the Philippines.</p>
	
</div>
<div class="text-center" style="margin-top: 2em">
	<p style="font-size: 18px;">{{ $transaction->admin ? str::title($transaction->admin->full_name) : '---' }}</p>
	<p style="font-size: 18px;">Peza Unit Head</p>
	<p><img src="data:image/png;base64, {{ base64_encode(QrCode::format('png')->size(180)->generate($transaction->code)) }} " style="padding: 0;"></p>
	<p style="padding-top: -30px">{{$transaction->code}}</p>
</div>
<p>Documentary Stamp Tax Paid Php 30.00</p>
</body>
</html>