<?php
class BookController 
{
	private $slimApp;
	private $model;
	private $requestBody;
	
	
		// ** the constructor within the controller class will do a number of things as soon as it is instanciated here:
		// decode the json data from the body of the request (the rest call), using slimApp->request->getBody();
		// this decoded data and/or the parameters are now used to carry out the requested action ($action) on the model ($this->model)
		// after completing the action it sets the appropriate status (* the status is not actually a header)
		// it sets this "status" using slimApp->response()->setStatus(); (e.g. 200, 401, 201, 408)
		// it also stores the apporiate data for the "body" into a variable in the model called "$responseBody"
		// this body will be returned (written) by the View using slimApp->response->write()
	
	public function __construct($model, $action = null, $slimApp, $parameteres = null) 
	{
		$this->model = $model;
		$this->slimApp = $slimApp;
		$this->requestBody = json_decode ( $this->slimApp->request->getBody (), true ); // this must contain the representation of the new book
		
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
				$this->getBook ( $id );
				break;
			case ACTION_GET_ALL :
				$this->getBooks ();
				break;
			case ACTION_UPDATE :
				$this->updateBook ( $id, $this->requestBody );
				break;
			case ACTION_CREATE :
				$this->createNewBook ( $this->requestBody );
				break;
			case ACTION_DELETE :
				$this->deleteBook ( $id );
				break;
			case ACTION_SEARCH :
				$this->searchBooks ( $searchString );
				break;	
			case null :
				$this->slimApp->response ()->setStatus ( HTTPSTATUS_BADREQUEST );
				$Message = array (GENERAL_MESSAGE_LABEL => GENERAL_CLIENT_ERROR );
				$this->model->responseBody = $Message;
				break;
		}
	}
	
	private function getBooks() 
	{
		$answer = $this->model->getBooks ();
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
	
	private function getBook($bookID) 
	{
		$answer = $this->model->getBook ( $bookID );
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
	
	private function createNewBook($newBook) 
	{
		if ($newID = $this->model->createNewBook ( $newBook )) 
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
	
	private function deleteBook($bookId) 
	{
		//$isSuccessfull = $this->model->deleteBook ( $bookId );
		//var_dump($isSuccessfull);
		//die($isSuccessfull);
		if ($this->model->deleteBook ( $bookId )) 
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
	
	private function searchBooks($string) 
	{
		$answer = $this->model->searchBooks ( $string );
		
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
	
	private function updateBook($bookId, $bookDetails)
	{
		if ($this->model->updateBook ( $bookId, $bookDetails )) 
		{
			$this->slimApp->response ()->setStatus ( HTTPSTATUS_OK );
			$Message = array (GENERAL_MESSAGE_LABEL => GENERAL_RESOURCE_UPDATED, "updatedID" => "$bookId");
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