<?php
require_once "DB/pdoDbManager.php";
require_once "DB/DAO/BooksDAO.php";
require_once "Validation.php";
class BookModel 
{
	private $BooksDAO; // list of DAOs used by this model
	private $dbmanager; // dbmanager
	public $responseBody; // api response
	private $validationSuite; // contains functions for validating inputs
	
	public function __construct() 
	{
		$this->dbmanager = new pdoDbManager ();
		$this->BooksDAO = new BooksDAO ( $this->dbmanager );
		$this->dbmanager->openConnection ();
		$this->validationSuite = new Validation ();
	}
	
	public function getBooks() 
	{
		return ($this->BooksDAO->get ());
	}
	
	public function getBook($reviewID) 
	{
		if (is_numeric ( $reviewID ))
			return ($this->BooksDAO->get ( $reviewID ));
		
		return false;
	}
	
	//@param array $BookRepresentation: an associative array containing the detail of the new book
	public function createNewBook($newBook) 
	{
		// validation of the values of the new book
		
		// compulsory values
		if (! empty ( $newBook ["title"] ) && ! empty ( $newBook ["author"] ) && ! empty ( $newBook ["genre"] ) && ! empty ( $newBook ["isbn"] )) 
		{
			// the model knows the representation of a book in the database and this is: title: varchar(25) author: varchar(25) genre: varchar(50) isbn: varchar(40)			
			if (($this->validationSuite->isLengthStringValid ( $newBook ["title"], TABLE_BOOK_TITLE_LENGTH )) && ($this->validationSuite->isLengthStringValid ( $newBook ["author"], TABLE_BOOK_AUTHOR_LENGTH )) && ($this->validationSuite->isLengthStringValid ( $newBook ["genre"], TABLE_BOOK_GENRE_LENGTH )) && ($this->validationSuite->isLengthStringValid ( $newBook ["isbn"], TABLE_BOOK_ISBN_LENGTH ))) 
			{
				if ($newId = $this->BooksDAO->insert ( $newBook ))
					return ($newId);
			}
		}
		
		// if validation fails or insertion fails
		return (false);
	}
	
	public function searchBooks($string) 
	{
		if (! empty ( $string ))
		{
			$resultSet = $this->BooksDAO->search ( $string );
			return $resultSet;
		}
		
		return false;
	}
	
	public function deleteBook($bookID) 
	{
		if (is_numeric ( $bookID )) 
		{
			$deletedRows = $this->BooksDAO->delete ( $bookID );
			
			if ($deletedRows > 0)
				return (true);
		}
		return (false);
	}
	
	public function updateBook($bookID, $bookNewRepresentation) 
	{
		if (! empty ( $bookID ) && is_numeric ( $bookID )) 
		{
			// compulsory values
			if (! empty ( $bookNewRepresentation ["title"] ) && ! empty ( $bookNewRepresentation ["author"] ) && ! empty ( $bookNewRepresentation ["genre"] ) && ! empty ( $bookNewRepresentation ["isbn"] )) 
			{
				// the model knows the representation of a book in the database and this is: title: varchar(25) author: varchar(25) genre: varchar(50) isbn: varchar(40)
				if (($this->validationSuite->isLengthStringValid ( $bookNewRepresentation ["title"], TABLE_BOOK_TITLE_LENGTH )) && ($this->validationSuite->isLengthStringValid ( $bookNewRepresentation ["author"], TABLE_BOOK_AUTHOR_LENGTH )) && ($this->validationSuite->isLengthStringValid ( $bookNewRepresentation ["genre"], TABLE_BOOK_GENRE_LENGTH )) && ($this->validationSuite->isLengthStringValid ( $bookNewRepresentation ["isbn"], TABLE_BOOK_ISBN_LENGTH ))) 
				{
					$updatedRows = $this->BooksDAO->update ( $bookNewRepresentation, $bookID );
					if ($updatedRows > 0)
						return (true);
				}
			}
		}
		return (false);
	}
	
	/*
	
	I HAVE NO IDEA WHAT THIS IS FOR.
	I THINK IT IS FOR VALIDATING IF A BOOK EXISTS
	IT SEEMS TO BE CALLING ITSELF INSTEAD OF A FUNCTION IN THE BooksDAO ..?
	
	
	private function validateBook($bookname, $isbn) 
	{
		if($this->model->validateBook ($bookname, $isbn))
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
	
	*/
	
	
	public function __destruct() 
	{
		$this->BooksDAO = null;
		$this->dbmanager->closeConnection ();
	}
}
?>