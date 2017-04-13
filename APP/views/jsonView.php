<?php
class jsonView 
{
	private $model, $slimApp;
	public function __construct($model, $slimApp) 
	{
		$this->model = $model;
		$this->slimApp = $slimApp;
	}
	
	public function output()
	{
		//prepare json response
		//var_dump($this->model->responseBody);
		//$this->slimApp->response->write ( $this->model->responseBody );
		
		$jsonResponse = json_encode ( $this->model->responseBody );
		//$this->slimApp->response->setBody($jsonResponse); // works aswell
		$this->slimApp->response->write($jsonResponse);			// when authenticating this will just return 200 to the body
		
		//echo ($jsonResponse ); // this works aswell
	}
}
?>

