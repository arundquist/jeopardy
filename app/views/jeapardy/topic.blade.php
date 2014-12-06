@foreach($qs AS $q)
	{{$q->answer}} ({{$q->question}})<br/>
@endforeach