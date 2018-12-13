<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
\URL::forceSchema('https');
Route::get('/', function()
{
	return View::make('hello');
});

Route::controller('jeapardies', 'JeapardiesController');
Route::controller('teams', 'TeamsController');
Route::controller('points', 'PointsController');

Route::get('viewall', function()
{
	$all=Jeapardy::orderBy('topic')->get();
	echo "<table border='1'><tr><th>topic</th><th>question</th><th>answer</th>";
	foreach($all as $single) {
		echo "<tr><td>$single->topic</td><td>$single->question</td><td>$single->answer</td></tr>";
	}
	echo "</table>";
	//dd($all);
});
