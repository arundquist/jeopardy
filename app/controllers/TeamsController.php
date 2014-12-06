<?php

class TeamsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /teams
	 *
	 * @return Response
	 */
	public function getAdd()
	{
		//get current teams
		//$teams=Session::get('teams');
		//dd(Session::all());
		$ts=Team::where('episode',Session::getId())->get();
		return View::make('team.add',
			['teams'=>$ts]);
	
	}
	
	public function postAdd()
	{
		$newteam = new Team;
		$newteam->episode=Session::getId();
		$newteam->name=Input::get('name');
		$newteam->members=Input::get('members');
		$newteam->save();
		return Redirect::back();
		//actually I don't think I need to use the session
		// just putting the session id into the "episode"
		// does the trick
		
		// yep, that works
		//Session::push('teams', $newteam->id);
		
	}
	
	public function getChangescore($team_id, $score)
	{
		$p=new Point;
		$p->team_id=$team_id;
		$p->score=$score;
		$p->save();
		return Redirect::back();
	}

}