<?php

class Point extends \Eloquent {
	protected $fillable = [];
	
	public function team()
	{
		return $this->belongsTo('Team');
	}
}