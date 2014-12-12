{{Form::open(['method'=>'post', 'action'=>['JeapardiesController@postEdittopic', $id]])}}
@foreach ($qs AS $q)
	{{Form::textarea("answer[$q->id]", $q->answer)}}
	{{Form::textarea("question[$q->id]", $q->question)}}
	<br/>
@endforeach
{{Form::submit()}}
{{Form::close()}}