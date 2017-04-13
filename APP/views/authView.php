<?php

class authView 
{
	// The purpose of this view is to return noting to allow authentication to run using the loadMVCComponents function, without returning "200" as a body response
	
	private $model, $controller, $slimApp;
	public function __construct($model, $slimApp) 
	{
		$this->model = $model;
		$this->slimApp = $slimApp;
	}
	
	public function output() 
	{
		// noithing here - auth doesn't need to display anything
		// the purpose of this is to stop response codes being returned to the body when authenticating
	}
}
?>