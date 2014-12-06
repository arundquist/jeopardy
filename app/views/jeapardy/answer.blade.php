<h2>
{{$j->answer}}
</h2>

Teams:<br/>
@foreach ($teams AS $team)
	{{link_to_action('TeamsController@getChangescore', 'correct', [$team->id, $amount])}}
	{{link_to_action('TeamsController@getChangescore', 'wrong', [$team->id, -$amount])}}
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


{{link_to_action('JeapardiesController@getQuestion','see question', [$j->id])}}