<?php
require_once "DB/pdoDbManager.php";
require_once "DB/DAO/ReviewsDAO.php";
require_once "Validation.php";
class ReviewModel 
{
	private $ReviewsDAO; 		// list of DAOs used by this model
	private $dbmanager; 		// dbmanager
	public $responseBody; 		// api response
	private $validationSuite; 	// contains functions for validating inputs
	
	public function __construct() 
	{
		$this->dbmanager = new pdoDbManager ();
		$this->ReviewsDAO = new ReviewsDAO ( $this->dbmanager );
		$this->dbmanager->openConnection ();
		$this->validationSuite = new Validation ();
	}
	
	public function getReviews() 
	{
		return ($this->ReviewsDAO->get ());
	}
	
	public function getReview($reviewID) 
	{
		if (is_numeric ( $reviewID ))
			return ($this->ReviewsDAO->get ( $reviewID ));
		
		return false;
	}
	
	//@param array $ReviewRepresentation: an associative array containing the detail of the new review
	public function createNewReview($newReview) 
	{
		// validation of the values of the new review
		
		// compulsory values
		if (! empty ( $newReview ["bookid"] ) && ! empty ( $newReview ["criticid"] ) && ! empty ( $newReview ["reviewtext"] ) && ! empty ( $newReview ["booktitle"] )) 
		{
			// the model knows the representation of a review in the database and this is: bookid: varchar(25) criticid: varchar(25) reviewtext: varchar(50) booktitle: varchar(40)			
			if (($this->validationSuite->isLengthStringValid ( $newReview ["bookid"], TABLE_REVIEW_BOOKID_LENGTH )) && ($this->validationSuite->isLengthStringValid ( $newReview ["criticid"], TABLE_REVIEW_CRITICID_LENGTH )) && ($this->validationSuite->isLengthStringValid ( $newReview ["reviewtext"], TABLE_REVIEW_REVIEWTEXT_LENGTH )) && ($this->validationSuite->isLengthStringValid ( $newReview ["booktitle"], TABLE_REVIEW_BOOKTITLE_LENGTH ))) 
			{
				if ($newId = $this->ReviewsDAO->insert ( $newReview ))
					return ($newId);
			}
		}
		
		// if validation fails or insertion fails
		return (false);
	}
	
	public function searchReviews($string) 
	{
		if (! empty ( $string ))
		{
			$resultSet = $this->ReviewsDAO->search ( $string );
			return $resultSet;
		}
		
		return false;
	}
	
	public function deleteReview($reviewID) 
	{
		if (is_numeric ( $reviewID )) 
		{
			$deletedRows = $this->ReviewsDAO->delete ( $reviewID );
			
			if ($deletedRows > 0)
				return (true);
		}
		return (false);
	}
	
	public function updateReview($reviewID, $reviewNewRepresentation) 
	{
		if (! empty ( $reviewID ) && is_numeric ( $reviewID )) 
		{
			// compulsory values
			if (! empty ( $reviewNewRepresentation ["bookid"] ) && ! empty ( $reviewNewRepresentation ["criticid"] ) && ! empty ( $reviewNewRepresentation ["reviewtext"] ) && ! empty ( $reviewNewRepresentation ["booktitle"] )) 
			{
				// the model knows the representation of a review in the database and this is: bookid: varchar(25) criticid: varchar(25) reviewtext: varchar(50) booktitle: varchar(40)
				if (($this->validationSuite->isLengthStringValid ( $reviewNewRepresentation ["bookid"], TABLE_REVIEW_BOOKID_LENGTH )) && ($this->validationSuite->isLengthStringValid ( $reviewNewRepresentation ["criticid"], TABLE_REVIEW_CRITICID_LENGTH )) && ($this->validationSuite->isLengthStringValid ( $reviewNewRepresentation ["reviewtext"], TABLE_REVIEW_REVIEWTEXT_LENGTH )) && ($this->validationSuite->isLengthStringValid ( $reviewNewRepresentation ["booktitle"], TABLE_REVIEW_BOOKTITLE_LENGTH ))) 
				{
					$updatedRows = $this->ReviewsDAO->update ( $reviewNewRepresentation, $reviewID );
					if ($updatedRows > 0)
						return (true);
				}
			}
		}
		return (false);
	}
	
	private function validateReview($reviewname, $booktitle) 
	{
		if($this->model->validateReview ($reviewname, $booktitle))
		{	
			$this->slimApp->response ()->setStatus ( HTTPSTATUS_OK );
			$this->model->responseBody = true;
		} 
		else 
		{
			$this->slimApp->response ()->setStatus ( HTTPSTATUS_UNAUTHORIZED );
			$this->model->responseBody = false;
		}
	}
	
	
	public function __destruct() 
	{
		$this->ReviewsDAO = null;
		$this->dbmanager->closeConnection ();
	}
}
?>