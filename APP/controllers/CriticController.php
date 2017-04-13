<?php
class CriticController 
{
	private $slimApp;
	private $model;
	private $requestBody;
	
	public function __construct($model, $action = null, $slimApp, $parameteres = null) 
	{
		$this->model = $model;
		$this->slimApp = $slimApp;
		$this->requestBody = json_decode ( $this->slimApp->request->getBody (), true ); // this must contain the representation of the new critic
		
		if (! empty ( $parameteres ["id"] ))
			$id = $parameteres ["id"];
		
		if (! empty ( $parameteres ["name"] ))
			$name = $parameteres ["name"];
		
		if (! empty ( $parameteres ["password"] ))
			$password = $parameteres ["password"];
			
		if (! empty ( $parameteres ["searchString"] ))
			$searchString = $parameteres ["searchString"];
		
		switch ($action) 
		{
			case ACTION_GET_ONE :
				$this->getCritic ( $id );
				break;
			case ACTION_GET_ALL :
				$this->getCritics ();
				break;
			case ACTION_UPDATE :
				$this->updateCritic ( $id, $this->requestBody );
				break;
			case ACTION_CREATE :
				$this->createNewCritic ( $this->requestBody );
				break;
			case ACTION_DELETE :
				$this->deleteCritic ( $id );
				break;
			case ACTION_SEARCH :
				$this->searchCritics ( $searchString );
				break;	
			case null :
				$this->slimApp->response ()->setStatus ( HTTPSTATUS_BADREQUEST );
				$Message = array (GENERAL_MESSAGE_LABEL => GENERAL_CLIENT_ERROR );
				$this->model->responseBody = $Message;
				break;
		}
	}
	
	private function getCritics() 
	{
		$answer = $this->model->getCritics ();
		if ($answer != null) 
		{
			$this->slimApp->response ()->setStatus ( HTTPSTATUS_OK );
			$this->model->responseBody = $answer;
		} 
		else 
		{
			$this->slimApp->response ()->setStatus ( HTTPSTATUS_NOCONTENT );
			$Message = array (
					GENERAL_MESSAGE_LABEL => GENERAL_NOCONTENT_MESSAGE 
			);
			$this->model->responseBody = $Message;
		}
	}
	
	private function getCritic($criticID) 
	{
		$answer = $this->model->getCritic ( $criticID );
		if ($answer != null) 
		{
			$this->slimApp->response ()->setStatus ( HTTPSTATUS_OK );
			$this->model->responseBody = $answer;
		} 
		else 
		{
			$this->slimApp->response ()->setStatus ( HTTPSTATUS_NOCONTENT );
			$Message = array (GENERAL_MESSAGE_LABEL => GENERAL_NOCONTENT_MESSAGE);
			$this->model->responseBody = $Message;
		}
	}
	
	private function createNewCritic($newCritic) 
	{
		if ($newID = $this->model->createNewCritic ( $newCritic )) 
		{
			$this->slimApp->response ()->setStatus ( HTTPSTATUS_CREATED );
			$Message = array (GENERAL_MESSAGE_LABEL => GENERAL_RESOURCE_CREATED, "id" => "$newID");
			$this->model->responseBody = $Message;
		} 
		else 
		{
			$this->slimApp->response ()->setStatus ( HTTPSTATUS_BADREQUEST );
			$Message = array (GENERAL_MESSAGE_LABEL => GENERAL_INVALIDBODY );
			$this->model->responseBody = $Message;
		}
	}
	
	private function deleteCritic($criticId) 
	{
		//$isSuccessfull = $this->model->deleteCritic ( $criticId );
		//var_dump($isSuccessfull);
		//die($isSuccessfull);
		if ($this->model->deleteCritic ( $criticId )) 
		{
			$this->slimApp->response ()->setStatus ( HTTPSTATUS_OK );
			$Message = array (GENERAL_MESSAGE_LABEL => GENERAL_RESOURCE_DELETED);
			$this->model->responseBody = $Message;
		} 
		else 
		{
			$this->slimApp->response ()->setStatus ( HTTPSTATUS_BADREQUEST );
			$Message = array (GENERAL_MESSAGE_LABEL => GENERAL_ERROR_MESSAGE );
			$this->model->responseBody = $Message;
		}
	}
	
	private function searchCritics($string) 
	{
		$answer = $this->model->searchCritics ( $string );
		
		if ($answer != null) 
		{
			$this->slimApp->response ()->setStatus ( HTTPSTATUS_OK );
			$this->model->responseBody = $answer;
		} 
		else 
		{
			$this->slimApp->response ()->setStatus ( HTTPSTATUS_NOCONTENT );
			$Message = array (GENERAL_MESSAGE_LABEL => GENERAL_NOCONTENT_MESSAGE);
			$this->model->responseBody = $Message;
		}
	}
	
	private function updateCritic($criticId, $criticDetails) 
	{
		if ($this->model->updateCritic ( $criticId, $criticDetails )) 
		{
			$this->slimApp->response ()->setStatus ( HTTPSTATUS_OK );
			$Message = array (GENERAL_MESSAGE_LABEL => GENERAL_RESOURCE_UPDATED, "updatedID" => "$criticId");
			$this->model->responseBody = $Message;
		}
		else
		{
			$this->slimApp->response ()->setStatus ( HTTPSTATUS_BADREQUEST );
			$Message = array (GENERAL_MESSAGE_LABEL => GENERAL_INVALIDBODY );
			$this->model->responseBody = $Message;
		}
	}
	
}
?>