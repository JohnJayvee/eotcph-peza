<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<table width="100%" cellpadding="0" cellspacing="0" border="1">
		<thead>
			<tr align="center">
				<td>Transaction Date</td>
				<td>Submitted By/Company Name</td>
				<td>Peza Unit</td>
				<td>Application Type</td>
				<td>Pre Processing Code</td>
				<td>Pre Processing Cost</td>
				<td>Pre Processing Code</td>
				<td>Post Processing Cost</td>
				<td>Processor</td>
				<td>Status</td>
			</tr>
		</thead>
		<tbody>
			@forelse($transactions as $value)
				<tr align="center">
					<td>{{Helper::date_format($value->created_at)}}</td>
					<td>{{$value->company_name}}</td>
					<td>{{$value->department->name}}</td>
					<td>{{ $value->type ? Strtoupper($value->type->name) : "N/A"}}<br> {{$value->code}}</td>
            		<td>{{ $value->type ? $value->type->pre_process->code : "---"}}</td>
					<td>{{Helper::money_format($value->processing_fee) ?: 0 }}</td>
					<td>{{ $value->type ? $value->type->post_process->code : "---"}}</td>
					<td>{{Helper::money_format($value->amount) ?: '---'}}</td>
					<td>{{ $value->admin ? $value->admin->full_name : '---' }}</td>
					<td>{{ $value->is_resent == 1 ? "RESENT" : $value->status}}</td>
				</tr>
			@empty
			@endforelse
		</tbody>
	</table>
</body>
</html>