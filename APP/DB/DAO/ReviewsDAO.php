<?php
class ReviewsDAO 
{
	private $dbManager;
	
	function ReviewsDAO($DBMngr)
	{
		$this->dbManager = $DBMngr;
	}
	
	public function get($id = null) 
	{
		$sql = "SELECT * ";
		$sql .= "FROM reviews ";
		if ($id != null)
			$sql .= "WHERE reviews.reviewid = ? ";
		$sql .= "ORDER BY reviews.booktitle ";
		
		$stmt = $this->dbManager->prepareQuery ( $sql );
		$this->dbManager->bindValue ( $stmt, 1, $id, $this->dbManager->INT_TYPE );
		$this->dbManager->executeQuery ( $stmt );
		$rows = $this->dbManager->fetchResults ( $stmt );
		
		return ($rows);
	}
	
	public function insert($parametersArray) 
	{
		// insertion assumes that all the required parameters are defined and set
		$sql = "INSERT INTO reviews (bookid, criticid, reviewtext, booktitle) ";
		$sql .= "VALUES (?,?,?,?) ";
		
		$stmt = $this->dbManager->prepareQuery ( $sql );
		$this->dbManager->bindValue ( $stmt, 1, $parametersArray ["bookid"], $this->dbManager->STRING_TYPE );
		$this->dbManager->bindValue ( $stmt, 2, $parametersArray ["criticid"], $this->dbManager->STRING_TYPE );
		$this->dbManager->bindValue ( $stmt, 3, $parametersArray ["reviewtext"], $this->dbManager->STRING_TYPE );
		$this->dbManager->bindValue ( $stmt, 4, $parametersArray ["booktitle"], $this->dbManager->STRING_TYPE );
		$this->dbManager->executeQuery ( $stmt );
		
		return ($this->dbManager->getLastInsertedID ());
	}
	
	public function update($parametersArray, $reviewID)
	{
		// /create an UPDATE sql statement (reads the parametersArray - this contains the fields submitted in the HTML5 form)
		$sql = "UPDATE reviews SET bookid = ?, criticid = ?, reviewtext = ?, booktitle = ? WHERE reviewid = ?";
		
		$this->dbManager->openConnection ();
		$stmt = $this->dbManager->prepareQuery ( $sql );
		$this->dbManager->bindValue ( $stmt, 1, $parametersArray ["bookid"], PDO::PARAM_STR );
		$this->dbManager->bindValue ( $stmt, 2, $parametersArray ["criticid"], PDO::PARAM_STR );
		$this->dbManager->bindValue ( $stmt, 3, $parametersArray ["reviewtext"], PDO::PARAM_STR );
		$this->dbManager->bindValue ( $stmt, 4, $parametersArray ["booktitle"], PDO::PARAM_STR );
		$this->dbManager->bindValue ( $stmt, 5, $reviewID, PDO::PARAM_INT );
		$this->dbManager->executeQuery ( $stmt );
		
		//check for number of affected rows
		$rowCount = $this->dbManager->getNumberOfAffectedRows($stmt);
		return ($rowCount);
	}
	
	public function delete($reviewID) 
	{
		$sql = "DELETE FROM reviews ";
		$sql .= "WHERE reviews.reviewid = ?";
		
		$stmt = $this->dbManager->prepareQuery ( $sql );
		$this->dbManager->bindValue ( $stmt, 1, $reviewID, $this->dbManager->INT_TYPE );
		
		$this->dbManager->executeQuery ( $stmt );
		$rowCount = $this->dbManager->getNumberOfAffectedRows ( $stmt );
		return ($rowCount);
	}
	
	public function search($str) 
	{
		$sql = "SELECT * ";
		$sql .= "FROM reviews ";
		$sql .= "WHERE reviews.booktitle LIKE CONCAT('%', ?, '%')  ";
		$sql .= "ORDER BY reviews.booktitle ";	// the book title is stored as booktitle in the reviews table
		
		$stmt = $this->dbManager->prepareQuery ( $sql );
		$this->dbManager->bindValue ( $stmt, 1, $str, $this->dbManager->STRING_TYPE );
		
		$this->dbManager->executeQuery ( $stmt );
		$rows = $this->dbManager->fetchResults ( $stmt );
		
		return ($rows);
	}
	
}
?>