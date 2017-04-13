<?php
require_once "DB/pdoDbManager.php";
require_once "DB/DAO/CriticsDAO.php";
require_once "Validation.php";
class CriticModel 
{
	private $CriticsDAO; // list of DAOs used by this model
	private $dbmanager; // dbmanager
	public $responseBody; // api response
	private $validationSuite; // contains functions for validating inputs
	
	public function __construct() 
	{
		$this->dbmanager = new pdoDbManager ();
		$this->CriticsDAO = new CriticsDAO ( $this->dbmanager );
		$this->dbmanager->openConnection ();
		$this->validationSuite = new Validation ();
	}
	
	public function getCritics() 
	{
		return ($this->CriticsDAO->get ());
	}
	
	public function getCritic($reviewID) 
	{
		if (is_numeric ( $reviewID ))
			return ($this->CriticsDAO->get ( $reviewID ));
		
		return false;
	}
	
	//@param array $CriticRepresentation: an associative array containing the detail of the new critic
	public function createNewCritic($newCritic) 
	{
		// validation of the values of the new critic
		
		// compulsory values
		if (! empty ( $newCritic ["name"] ) && ! empty ( $newCritic ["surname"] ) && ! empty ( $newCritic ["email"] ) && ! empty ( $newCritic ["password"] )) 
		{
			// the model knows the representation of a critic in the database and this is: name: varchar(25) surname: varchar(25) email: varchar(50) password: varchar(40)			
			if (($this->validationSuite->isLengthStringValid ( $newCritic ["name"], TABLE_CRITIC_NAME_LENGTH )) && ($this->validationSuite->isLengthStringValid ( $newCritic ["surname"], TABLE_CRITIC_SURNAME_LENGTH )) && ($this->validationSuite->isLengthStringValid ( $newCritic ["email"], TABLE_CRITIC_EMAIL_LENGTH )) && ($this->validationSuite->isLengthStringValid ( $newCritic ["password"], TABLE_CRITIC_PASSWORD_LENGTH ))) 
			{
				if ($newId = $this->CriticsDAO->insert ( $newCritic ))
					return ($newId);
			}
		}
		
		// if validation fails or insertion fails
		return (false);
	}
	
	public function searchCritics($string) 
	{
		if (! empty ( $string ))
		{
			$resultSet = $this->CriticsDAO->search ( $string );
			return $resultSet;
		}
		
		return false;
	}
	
	public function deleteCritic($criticID) 
	{
		if (is_numeric ( $criticID )) 
		{
			$deletedRows = $this->CriticsDAO->delete ( $criticID );
			
			if ($deletedRows > 0)
				return (true);
		}
		return (false);
	}
	
	public function updateCritic($criticID, $criticNewRepresentation) 
	{
		if (! empty ( $criticID ) && is_numeric ( $criticID )) 
		{
			// compulsory values
			if (! empty ( $criticNewRepresentation ["name"] ) && ! empty ( $criticNewRepresentation ["surname"] ) && ! empty ( $criticNewRepresentation ["email"] ) && ! empty ( $criticNewRepresentation ["password"] )) 
			{
				// the model knows the representation of a critic in the database and this is: name: varchar(25) surname: varchar(25) email: varchar(50) password: varchar(40)
				if (($this->validationSuite->isLengthStringValid ( $criticNewRepresentation ["name"], TABLE_CRITIC_NAME_LENGTH )) && ($this->validationSuite->isLengthStringValid ( $criticNewRepresentation ["surname"], TABLE_CRITIC_SURNAME_LENGTH )) && ($this->validationSuite->isLengthStringValid ( $criticNewRepresentation ["email"], TABLE_CRITIC_EMAIL_LENGTH )) && ($this->validationSuite->isLengthStringValid ( $criticNewRepresentation ["password"], TABLE_CRITIC_PASSWORD_LENGTH ))) 
				{
					$updatedRows = $this->CriticsDAO->update ( $criticNewRepresentation, $criticID );
					if ($updatedRows > 0)
						return (true);
				}
			}
		}
		return (false);
	}
	
	private function validateCritic($criticname, $password) 
	{
		if($this->model->validateCritic ($criticname, $password))
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
		$this->CriticsDAO = null;
		$this->dbmanager->closeConnection ();
	}
}
?>