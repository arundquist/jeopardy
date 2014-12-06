<?php

class PointsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /points
	 *
	 * @return Response
	 */
	public function getChange()
	{
		$teams=Team::with('points')->where('episode', Session::getId())->get();
		return View::make('points.change',
			['teams'=>$teams]);
	}
	
	public function postChange()
	{
		foreach (Input::get('change') AS $team_id=>$change)
		{
			if (!is_null($change))
			{
				$p=new Point;
				$p->team_id=$team_id;
				$p->score=$change;
				$p->save();
			};
		};
		return Redirect::back();
	}

}