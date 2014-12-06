{{Form::open(['method'=>'post', 'action'=>['PointsController@postChange']])}}
@foreach ($teams AS $team)
	{{$team->name}}: {{$team->members}}
	{{$team->points->sum('score')}}
	{{Form::text("change[$team->id]",Null,['placeholder'=>'change'])}}
	<br/>
@endforeach
{{Form::submit()}}
{{Form::close()}}