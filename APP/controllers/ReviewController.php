<?php
class ReviewController 
{
	private $slimApp;
	private $model;
	private $requestBody;
	
	public function __construct($model, $action = null, $slimApp, $parameteres = null) 
	{
		$this->model = $model;
		$this->slimApp = $slimApp;
		$this->requestBody = json_decode ( $this->slimApp->request->getBody (), true ); // this must contain the representation of the new review
		
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
				$this->getReview ( $id );
				break;
			case ACTION_GET_ALL :
				$this->getReviews ();
				break;
			case ACTION_UPDATE :
				$this->updateReview ( $id, $this->requestBody );
				break;
			case ACTION_CREATE :
				$this->createNewReview ( $this->requestBody );
				break;
			case ACTION_DELETE :
				$this->deleteReview ( $id );
				break;
			case ACTION_SEARCH :
				$this->searchReviews ( $searchString );
				break;	
			case null :
				$this->slimApp->response ()->setStatus ( HTTPSTATUS_BADREQUEST );
				$Message = array (GENERAL_MESSAGE_LABEL => GENERAL_CLIENT_ERROR );
				$this->model->responseBody = $Message;
				break;
		}
	}
	
	private function getReviews() 
	{
		$answer = $this->model->getReviews ();
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
	
	private function getReview($reviewID) 
	{
		$answer = $this->model->getReview ( $reviewID );
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
	
	private function createNewReview($newReview) 
	{
		if ($newID = $this->model->createNewReview ( $newReview )) 
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
	
	private function deleteReview($reviewId) 
	{
		//$isSuccessfull = $this->model->deleteReview ( $reviewId );
		//var_dump($isSuccessfull);
		//die($isSuccessfull);
		if ($this->model->deleteReview ( $reviewId )) 
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
	
	private function searchReviews($string) 
	{
		$answer = $this->model->searchReviews ( $string );
		
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
	
	private function updateReview($reviewId, $reviewDetails) 
	{
		if ($this->model->updateReview ( $reviewId, $reviewDetails )) 
		{
			$this->slimApp->response ()->setStatus ( HTTPSTATUS_OK );
			$Message = array (GENERAL_MESSAGE_LABEL => GENERAL_RESOURCE_UPDATED, "updatedID" => "$reviewId");
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