<?php
class BooksDAO 
{
	private $dbManager;
	
	function BooksDAO($DBMngr)
	{
		$this->dbManager = $DBMngr;
	}

	// search function retrives by title, everything else by id
	
	public function get($id = null) 
	{
		$sql = "SELECT * ";
		$sql .= "FROM books ";
		if ($id != null)
			$sql .= "WHERE books.bookid = ? ";
		$sql .= "ORDER BY books.title ";
		
		$stmt = $this->dbManager->prepareQuery ( $sql );
		$this->dbManager->bindValue ( $stmt, 1, $id, $this->dbManager->INT_TYPE );
		$this->dbManager->executeQuery ( $stmt );
		$rows = $this->dbManager->fetchResults ( $stmt );
		
		return ($rows);
	}
	
	public function insert($parametersArray) 
	{
		// insertion assumes that all the required parameters are defined and set
		$sql = "INSERT INTO books (title, author, genre, isbn) ";
		$sql .= "VALUES (?,?,?,?) ";
		
		$stmt = $this->dbManager->prepareQuery ( $sql );
		$this->dbManager->bindValue ( $stmt, 1, $parametersArray ["title"], $this->dbManager->STRING_TYPE );
		$this->dbManager->bindValue ( $stmt, 2, $parametersArray ["author"], $this->dbManager->STRING_TYPE );
		$this->dbManager->bindValue ( $stmt, 3, $parametersArray ["genre"], $this->dbManager->STRING_TYPE );
		$this->dbManager->bindValue ( $stmt, 4, $parametersArray ["isbn"], $this->dbManager->STRING_TYPE );
		$this->dbManager->executeQuery ( $stmt );
		
		return ($this->dbManager->getLastInsertedID ());
	}
	
	public function update($parametersArray, $bookID)
	{
		// /create an UPDATE sql statement (reads the parametersArray - this contains the fields submitted in the HTML5 form)
		$sql = "UPDATE books SET title = ?, author = ?, genre = ?, isbn = ? WHERE bookid = ?";
		
		$this->dbManager->openConnection ();
		$stmt = $this->dbManager->prepareQuery ( $sql );
		$this->dbManager->bindValue ( $stmt, 1, $parametersArray ["title"], PDO::PARAM_STR );
		$this->dbManager->bindValue ( $stmt, 2, $parametersArray ["author"], PDO::PARAM_STR );
		$this->dbManager->bindValue ( $stmt, 3, $parametersArray ["genre"], PDO::PARAM_STR );
		$this->dbManager->bindValue ( $stmt, 4, $parametersArray ["isbn"], PDO::PARAM_STR );
		$this->dbManager->bindValue ( $stmt, 5, $bookID, PDO::PARAM_INT );
		$this->dbManager->executeQuery ( $stmt );
		
		//check for number of affected rows
		$rowCount = $this->dbManager->getNumberOfAffectedRows($stmt);
		return ($rowCount);
	}
	
	public function delete($bookID) 
	{
		$sql = "DELETE FROM books ";
		$sql .= "WHERE books.bookid = ?";
		
		$stmt = $this->dbManager->prepareQuery ( $sql );
		$this->dbManager->bindValue ( $stmt, 1, $bookID, $this->dbManager->INT_TYPE );
		
		$this->dbManager->executeQuery ( $stmt );
		$rowCount = $this->dbManager->getNumberOfAffectedRows ( $stmt );
		return ($rowCount);
	}
	
	public function search($str) 
	{
		$sql = "SELECT * ";
		$sql .= "FROM books ";
		$sql .= "WHERE books.title LIKE CONCAT('%', ?, '%') or books.author LIKE CONCAT('%', ?, '%')  ";
		$sql .= "ORDER BY books.title ";
		
		$stmt = $this->dbManager->prepareQuery ( $sql );
		$this->dbManager->bindValue ( $stmt, 1, $str, $this->dbManager->STRING_TYPE );
		$this->dbManager->bindValue ( $stmt, 2, $str, $this->dbManager->STRING_TYPE );
		
		$this->dbManager->executeQuery ( $stmt );
		$rows = $this->dbManager->fetchResults ( $stmt );
		
		return ($rows);
	}
	
}
?>