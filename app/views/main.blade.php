<table border='1'>
<thead>
	<tr>
		@foreach($topics AS $topic)
			<th>{{$topic}}</th>
		@endforeach
	</tr>
</thead>
<tbody>
	@foreach ($qnums As $row)
		<tr>
			@foreach ($row AS $col)
				<td>{{$col}}</td>
			@endforeach
		</tr>
	@endforeach
</tbody>
</table>
Teams:<br/>
@foreach ($teams AS $team)
	{{link_to_action('TeamsController@getChangescore', 'correct', [$team->id, $currentamount])}}
	{{link_to_action('TeamsController@getChangescore', 'wrong', [$team->id, -$currentamount])}}
	{{$team->name}}: {{$team->members}}
	@foreach ($team->points AS $point)
		@if (($point->score)>0)
			+{{$point->score}}
		@else
			{{$point->score}}
		@endif
	@endforeach
	= {{$team->points->sum('score')}}
	<br/>
@endforeach