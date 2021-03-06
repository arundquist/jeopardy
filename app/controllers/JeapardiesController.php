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
		{
			echo "{$t->id}: ";
			echo link_to_action("JeapardiesController@getEdittopic", "{$t->topic}", [$t->id]);
			echo "<br/>";
		};
		
	}
	
	public function getEdittopic($id)
	{
		$topic=Jeapardy::findOrFail($id);
		$qs=Jeapardy::where('topic',$topic->topic)->orderBy('id')->get();
		return View::make('jeapardy.edit',
			['qs'=>$qs,
			'id'=>$id]);
	}
	
	public function postEdittopic($id)
	{
		$answers=Input::get('answer');
		$questions=Input::get('question');
		foreach ($answers AS $key=>$a)
		{
			$j=Jeapardy::findOrFail($key);
			$j->answer=$a;
			$j->question=$questions[$key];
			$j->save();
		};
		return Redirect::back();
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
	
	public function getAnswersession($id, $amount)
	{
		Session::put('currentid', $id);
		Session::put('currentamount', $amount);
		Session::push('seen', $id);
		return Redirect::action('JeapardiesController@getIndex');
	}
	
	public function getIndex()
	{
		$teams=Team::where('episode',Session::getId())->get();
		//dd(Session::all());
		$currentanswertext=Null;
		$currentid=Null;
		//dd(Session::all());
		$qs=Session::get('qs');
		$qnums=array();
		$keys=array_keys($qs);
		if (Session::has('currentid'))
		{
			$currentid=Session::get('currentid');
			$currentamount=Session::get('currentamount');
			if (in_array($currentid, Session::get('dailydoubles')))
				$currentanswertext=link_to_action('JeapardiesController@getAnswer',
						'DAILY DOUBLE', [$currentid, $currentamount]);
			else
				$currentanswertext=link_to_action('JeapardiesController@getQuestion',
					Jeapardy::findOrFail($currentid)->answer,[$currentid]);
		};
		foreach ($qs AS $i=>$row)
		{
			
			foreach ($row AS $j=>$q)
			{
				$amt=Session::get('round')*100*($i+1);
							
				
				if ($q==Session::get('currentid'))
					$qnums[$i][$j]=$currentanswertext;
				elseif ((Session::has('seen'))&&(in_array($q, Session::get('seen'))))
					$qnums[$i][$j]="&nbsp";
				else
					$qnums[$i][$j]=link_to_action('JeapardiesController@getAnswersession', 
						$amt, [$q, $amt]);
			};
		};
		
		
		$topics=Session::get('topics');
		return View::make('main',
			['qs'=>Session::get('qs'),
			'qnums'=>$qnums,
			'dailydoubles'=>Session::get('dailydoubles'),
			'topics'=>$topics,
			'currentid'=>$currentid,
			'currentanswertext'=>$currentanswertext,
			'currentamount'=>Session::get('currentamount'),
			'seen'=>Session::get('seen'),
			'teams'=>$teams,
			'round'=>Session::get('round')]);
	}
	
	public function getSetanswer($id, $amount)
	{
		// make the current answer set to this id
		Session::put('currentanswer',$id);
		Session::put('currentamount', $amount);
		// add the current id to the session variable
		// that holds the 'viewed' questions
		Session::push('seen', $id);
		// redirect back
		return Redirect::action('JeapardiesController@getIndex');
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
	
	public function postRoundsession($i)
	{
		$topics=Jeapardy::whereIn('id', Input::get('t'))->get();
		
		// thinking about doing the daily double
		// I could do a randon column and row (two if $i = 2)
		// maybe get all ids and just randonly pick $i of them
		$allids=array();
		
		foreach ($topics AS $topic)
		{
			$qs[]=Jeapardy::where('topic',$topic->topic)->orderBy('id')->lists('id');
			
		};
		// here I'll transpose everything
		$allids=array_flatten($qs);
		shuffle($allids);
		$dailydoubles=array_slice($allids,0,$i);
		$qstrans=array();
		for($i2=0; $i2<count($qs); $i2++)
		{
			for ($j=0; $j<count($qs[$i2]); $j++)
			{
						
				$qtrans[$j][$i2]=$qs[$i2][$j];
			};
		};
		Session::forget('currentid');
		Session::forget('currentamount');
		Session::forget('seen');
		Session::put('qs', $qtrans);
		Session::put('topics',$topics->lists('topic'));
		Session::put('dailydoubles', $dailydoubles);
		Session::put('round', $i);
		return Redirect::action('JeapardiesController@getIndex');
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