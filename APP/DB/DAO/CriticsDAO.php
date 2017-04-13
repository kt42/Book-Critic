<?php
class CriticsDAO 
{
	private $dbManager;
	
	function CriticsDAO($DBMngr)
	{
		$this->dbManager = $DBMngr;
	}

	public function get($id = null) 
	{
		$sql = "SELECT * ";
		$sql .= "FROM critics ";
		if ($id != null)
			$sql .= "WHERE critics.criticid = ? ";
		$sql .= "ORDER BY critics.name ";
		
		$stmt = $this->dbManager->prepareQuery ( $sql );
		$this->dbManager->bindValue ( $stmt, 1, $id, $this->dbManager->INT_TYPE );
		$this->dbManager->executeQuery ( $stmt );
		$rows = $this->dbManager->fetchResults ( $stmt );
		
		return ($rows);
	}
	
	public function insert($parametersArray) 
	{
		// insertion assumes that all the required parameters are defined and set
		$sql = "INSERT INTO critics (name, surname, email, password) ";
		$sql .= "VALUES (?,?,?,?) ";
		
		$stmt = $this->dbManager->prepareQuery ( $sql );
		$this->dbManager->bindValue ( $stmt, 1, $parametersArray ["name"], $this->dbManager->STRING_TYPE );
		$this->dbManager->bindValue ( $stmt, 2, $parametersArray ["surname"], $this->dbManager->STRING_TYPE );
		$this->dbManager->bindValue ( $stmt, 3, $parametersArray ["email"], $this->dbManager->STRING_TYPE );
		$this->dbManager->bindValue ( $stmt, 4, $parametersArray ["password"], $this->dbManager->STRING_TYPE );
		$this->dbManager->executeQuery ( $stmt );
		
		return ($this->dbManager->getLastInsertedID ());
	}
	
	public function update($parametersArray, $criticID)
	{
		// /create an UPDATE sql statement (reads the parametersArray - this contains the fields submitted in the HTML5 form)
		$sql = "UPDATE critics SET name = ?, surname = ?, email = ?, password = ? WHERE criticid = ?";
		
		$this->dbManager->openConnection ();
		$stmt = $this->dbManager->prepareQuery ( $sql );
		$this->dbManager->bindValue ( $stmt, 1, $parametersArray ["name"], PDO::PARAM_STR );
		$this->dbManager->bindValue ( $stmt, 2, $parametersArray ["surname"], PDO::PARAM_STR );
		$this->dbManager->bindValue ( $stmt, 3, $parametersArray ["email"], PDO::PARAM_STR );
		$this->dbManager->bindValue ( $stmt, 4, $parametersArray ["password"], PDO::PARAM_STR );
		$this->dbManager->bindValue ( $stmt, 5, $criticID, PDO::PARAM_INT );
		$this->dbManager->executeQuery ( $stmt );
		
		//check for number of affected rows
		$rowCount = $this->dbManager->getNumberOfAffectedRows($stmt);
		return ($rowCount);
	}
	
	public function delete($criticID) 
	{
		$sql = "DELETE FROM critics ";
		$sql .= "WHERE critics.criticid = ?";
		
		$stmt = $this->dbManager->prepareQuery ( $sql );
		$this->dbManager->bindValue ( $stmt, 1, $criticID, $this->dbManager->INT_TYPE );
		
		$this->dbManager->executeQuery ( $stmt );
		$rowCount = $this->dbManager->getNumberOfAffectedRows ( $stmt );
		return ($rowCount);
	}
	
	public function search($str) 
	{
		$sql = "SELECT * ";
		$sql .= "FROM critics ";
		$sql .= "WHERE critics.name LIKE CONCAT('%', ?, '%') or critics.surname LIKE CONCAT('%', ?, '%')  ";
		$sql .= "ORDER BY critics.name ";
		
		$stmt = $this->dbManager->prepareQuery ( $sql );
		$this->dbManager->bindValue ( $stmt, 1, $str, $this->dbManager->STRING_TYPE );
		$this->dbManager->bindValue ( $stmt, 2, $str, $this->dbManager->STRING_TYPE );
		
		$this->dbManager->executeQuery ( $stmt );
		$rows = $this->dbManager->fetchResults ( $stmt );
		
		return ($rows);
	}
	
	// Critics are admins, so this authentication function must always refrence the critics table
	public function authenticate($username, $password) {
		$sql = "SELECT * ";
		$sql .= "FROM critics ";
		$sql .= "WHERE critics.email=? ";
		$sql .= "AND critics.password=? ";
		
		$stmt = $this->dbManager->prepareQuery ( $sql );
		$this->dbManager->bindValue ( $stmt, 1, $username, $this->dbManager->STRING_TYPE );
		$this->dbManager->bindValue ( $stmt, 2, $password, $this->dbManager->INT_TYPE );
		$this->dbManager->executeQuery ( $stmt );
		$rows = $this->dbManager->fetchResults ( $stmt );

		return ($rows);
	}
}
?>