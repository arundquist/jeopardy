{{Form::open(['method'=>'post', 'action'=>'JeapardiesController@postAddtopic'])}}
{{Form::text('topic', Null, ['placeholder'=>'topic'])}}<br/>
@for ($i=1; $i<=5; $i++)
	{{Form::textarea("values[$i][answer]", Null, ['placeholder'=>"answer $i"])}}
	{{Form::textarea("values[$i][question]", Null, ['placeholder'=>"question $i"])}}
	<br/>
@endfor
{{Form::submit()}}
{{Form::close()}}
	