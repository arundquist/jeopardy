<table border='1'>
<thead>
<tr>
	@foreach ($all AS $topic)
		<th>{{$topic->topic}}</th>
	@endforeach
</thead>
<tr>
@foreach ($qs AS $key=>$q)
	<td>
		<?php $c=1; ?>
		@foreach ($q AS $single)
			<?php $title=$c*100*$i; ?>
			@if (in_array($single->id, $dailydoubles))
			{{link_to_action('JeapardiesController@getDailydouble', $title, [$single->id])}}
			@else
			{{link_to_action('JeapardiesController@getAnswer', $title, [$single->id, $c*100*$i])}}<br/>
			@endif
			<?php $c++; ?>
		@endforeach
	</td>
@endforeach
</tr>
</table>