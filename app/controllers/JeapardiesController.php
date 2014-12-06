<?php

class JeapardiesController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /jeapardies
	 *
	 * @return Response
	 */
	public function getShowall()
	{
		$all=Jeapardy::distinct()->groupBy('topic')->get(['id','topic']);
		foreach($all AS $t)
			echo "{$t->id}: {$t->topic}<br/>";
		
	}
	
	public function getTopic($id)
	{
		$topic=Jeapardy::findOrFail($id);
		$qs=Jeapardy::where('topic',$topic->topic)->orderBy('id')->get();
		return View::make('jeapardy.topic',
			['qs'=>$qs]);
	}
	
	public function getAnswer($id, $amount)
	{
		$j=Jeapardy::findOrFail($id);
		$teams=Team::where('episode',Session::getId())->get();
		return View::make('jeapardy.answer',
			['j'=>$j,
			'teams'=>$teams,
			'amount'=>$amount]);
	}
	
	public function getQuestion($id)
	{
		$j=Jeapardy::findOrFail($id);
		return View::make('jeapardy.question',
			['j'=>$j]);
	}
	
	public function getRound($i)
	{
		$all=Jeapardy::distinct()->groupBy('topic')->lists('topic','id');
		return View::make('jeapardy.round',
			['all'=>$all,
			'i'=>$i]);
	}
	
	public function postRound($i)
	{
		$topics=Jeapardy::whereIn('id', Input::get('t'))->get();
		
		// thinking about doing the daily double
		// I could do a randon column and row (two if $i = 2)
		// maybe get all ids and just randonly pick $i of them
		$allids=array();
		
		foreach ($topics AS $topic)
		{
			$qs[$topic->id]=Jeapardy::where('topic',$topic->topic)->orderBy('id')->get();
			$allids[]=$qs[$topic->id]->lists('id');
		};
		$allids=array_flatten($allids);
		shuffle($allids);
		$dailydoubles=array_slice($allids,0,$i);
		return View::make('jeapardy.board',
			['all'=>$topics,
			'i'=>$i,
			'qs'=>$qs,
			'dailydoubles'=>$dailydoubles]);
	}
	
	public function getDailydouble($topic_id)
	{
		echo "DAILY DOUBLE!";
		echo link_to_action('JeapardiesController@getAnswer', 'see answer', [$topic_id, 0]);
	}

	public function getAddtopic()
	{
		return View::make('jeapardy.newtopic');
	}
	
	public function postAddtopic()
	{
		foreach (Input::get('values') AS $value)
		{
			$t=new Jeapardy;
			$t->answer=$value['answer'];
			$t->question=$value['question'];
			$t->topic=Input::get('topic');
			$t->save();
		};
		return Redirect::back();
	}

}