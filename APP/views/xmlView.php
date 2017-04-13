<?php
class xmlView 
{
	private $model, $slimApp;
	
	public function __construct($model, $slimApp) 
	{
		$this->model = $model;
		$this->slimApp = $slimApp;
	}
	
	public function output() 
	{
		//prepare the xml response
		$xmlResponse = xmlrpc_encode ( $this->model->responseBody );
		$this->slimApp->response->write($xmlResponse);
		
	}
}
?>


