<?php

class Team extends \Eloquent {
	protected $fillable = [];
	
	public function points()
	{
		return $this->hasMany('Point');
	}
}