{{Form::open(['method'=>'post', 'action'=>['JeapardiesController@postRoundsession',$i]])}}

@for ($j=1; $j<=6; $j++)
{{Form::select('t[]',$all)}}<br/>
@endfor
{{Form::submit()}}