<?php
class AuthenticationController 
{
	private $slimApp;
	private $model;
	//private $requestBody;
	
	public function __construct($model, $action = null, $slimApp, $parameteres = null) 
	{
		$this->model = $model;
		$this->slimApp = $slimApp;
		
		$username = $parameteres ["username"];
		$password = $parameteres ["password"];
		
		// Run the authentication function in the Authentication Model
		// Also store the result in the Authentication Model variable "responseBody"
		// The response will be either HTTPSTATUS_OK, HTTPSTATUS_FORBIDDEN, HTTPSTATUS_UNAUTHORIZED
		
		$this->model->authStatus = $this->model->authenticateUser ($username, $password);
	}
}
?>