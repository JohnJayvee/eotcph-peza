<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Application from </title>

	<style>
		th.primary{
			background-color: #D4EDDA;
		}
		table, th, td {
		  border-collapse: collapse;
		  padding-left: 20px;
		  padding-right: 20px;
		}

		table.center {
			margin-left:auto;
			margin-right:auto;
			border-bottom: solid 1px #F0F0F0;
			border-right: solid 1px #F0F0F0;
			border-left: solid 1px #F0F0F0;
		}
		.text-white{
			color:#fff;
		}
		.bold{
			font-weight: bolder;
		}
		.text-blue{
			color: #27437D;
		}
		.text-gray{
			color: #848484;
		}
		.bg-white{
			background-color: #fff;
		}
		hr.new2 {
		  border-top: 3px dashed #848484;
		  border-bottom: none;
		  border-left: none;
		  border-right: none;
		}
		#pageElement{display:flex; flex-wrap: nowrap; align-items: center}
	</style>

</head>
<body style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif;  font-size: 14px; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important; height: 100%; line-height: 1.6em; margin: 0;">

	<table class="center bg-white" width="55%">

			<tr>
				<th colspan="2" class="primary" style="padding: 25px;">
					<div id="pageElement">
						<div style="float: left;color: #000;padding-left: 30px;">Thank You for using &nbsp;</div>
					  	<div style="padding-right: 30px;"> <img src="{{asset('web/img/peza-eotcphp-logo.png')}}" alt="" style="width: 130px;"> </div>
					</div>
				</th>
			</tr>

			<tr>
				<th colspan="2" class="text-gray" style="padding: 10px;">Date: {{Helper::date_only(Carbon::now())}} | {{Helper::time_only(Carbon::now())}}</th>
			</tr>
			<tr>
				<th colspan="2"><p style="float: left;text-align: justify;">Hello {{Str::title($full_name)}}, <p>
					<p style="float: left;text-align: justify;">Good day. We have processed your application, and we regret to inform you that your application has been declined by our processor.</p>
				</th>
			</tr>

			<tr class="text-blue">
				<th style="vertical-align:top; white-space:nowrap; text-align: left;padding: 10px;">Application Name:</th>
				<th style="text-align: right; padding:10px;">{{Str::title($application_name)}}</th>
			</tr>
			<tr class="text-blue">
				<th style="vertical-align:top; white-space:nowrap; text-align: left;padding: 10px;">Peza Unit:</th>
				<th style="text-align: right; padding:10px;">{{Str::title($department_name)}}</th>
			</tr>
			<tr class="text-blue">
				<th style="vertical-align:top; white-space:nowrap; text-align: left;padding: 10px;">Date:</th>
				<th style="text-align: right; padding:10px;">{{Str::title($modified_at)}}</th>
			</tr>
            <tr class="text-blue">
				<th style="vertical-align:top; white-space:nowrap; text-align: left;padding: 10px;">Customer Notes:</th>
				<th style="text-align: right; padding:10px;">{{Str::title($notes ?? 'N/A')}}</th>
			</tr>
			<tr class="text-blue">
				<th style="vertical-align:top; white-space:nowrap; text-align: left;padding: 10px;">Processor Remarks:</th>
				<th style="text-align: right; padding:10px;">{{Str::title($remarks ?? 'N/A')}}</th>
			</tr>

			<tr>
				<th colspan="2">
					<p style="float: left;text-align: justify;">Don't worry, you can still resubmit your application. Please click this link to download your reference number <a href="{{$link}}">{{$link}}</a> and attached it to your physical documents and send it to our office.</p><br>
					<p>Thank you for choosing EOTC-PHP!</p>
				</th>
			</tr>

	</table>


</body>
</html>
