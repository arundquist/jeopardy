<h1>add team</h1>
{{Form::open(['method'=>'post', 'action'=>['TeamsController@postAdd']])}}
{{Form::text('name',Null,['placeholder'=>'team name'])}}<br/>
{{Form::text('members', Null, ['placeholder'=>'members','size'=>40])}}
<br/>
{{Form::submit()}}
<br/>
@if (!is_null($teams))
	@foreach ($teams AS $team)
		{{$team->name}}: {{$team->members}}<br/>
	@endforeach
@endif