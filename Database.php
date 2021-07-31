<?php
require_once __DIR__ . "/Logger.php";
require_once __DIR__ . "/DbOpenException.php";
require_once __DIR__ . "/BadInputException.php";

class Database
{
	/* Public Implementation */

	/*
	 * Constructor method. Initializes all database connection variables.
	 *
	 * Input:
	 * 		string $address: database address
	 *		string $username: username to signin to the database
	 * 		string $password: password matching supplied username
	 *		string $schema: database schema to access
	 *
	 * Return: N/A
	 */
	public function __construct(string $address, string $username, string $password, string $schema)
	{
		$this->address_ = $address;
		$this->username_ = $username;
		$this->password_ = $password;
		$this->schema_ = $schema;
	}


	/*
	 * Method to SELECT from a mysql database
	 *
	 * Input:
	 *		string $table: table name to access
	 *		array $columns: columns to get
	 *		array $conditions: keyed array with column names as keys and desired 
	 *					values as values at the respective indices
	 *		string $condJoin: the boolean connector between conditions
	 *
	 * Returns array: an array of the query results
	 */
	public function Select(string $table, array $columns, array $conditions, string $condJoin): array
	{
		try
		{
			/* Query Building */
			$this->query_ = 'select ';

			end($columns);
			$lastCol = key($columns);
			reset($columns);

			if (0 == count($columns))
			{
				$this->query_ .= '* ';
			}
			else
			{
				foreach($columns as $col)
				{
					$this->query_ .= $col;

					if ($col != $columns[$lastCol])
						$this->query_ .= ',';
				}
			}

			$this->query_ .= " from " . $table;

			if (0 != count($conditions))
			{
				$this->query_ .= ' where ';

				end($conditions);
				$lastCond = key($conditions);
				reset($conditions);

				foreach($conditions as $cond => $cond_name)
				{
					$this->query_ .= $cond . '=' . $cond_name;

					if ($cond != $lastCond)
						$this->query_ .= ' ' . $condJoin . ' ';
				}
			}
			
			$this->query_ .= ';';

			/* Database Interaction */
			$results = $this->executeRead();
		}
		catch (Exception $e)
		{
			echo Logger::writeToConsole($e->getMessage());
			echo Logger::writeToConsole("Query: " . $this->query_);
			$this->db_->rollback();
			throw $e;
		}
		return $results;
	}


	/*
	 * Method to INSERT into a mysql database
	 *
	 * Input:
	 *		string $table: table name to access
	 *		array $columns: keyed array with column names as keys and desired 
	 *					values as values at the respective indices
	 *
	 * Returns int: number of rows affected
	 */
	public function Insert(string $table, array $columns): int
	{
		try
		{
			$this->query_ = 'insert into ' . $table . ' (';

			if (0 == count($columns))
				throw new BadInputException("Must pass in columns and values");

			end($columns);
			$lastCol = key($columns);
			reset($columns);

			foreach($columns as $col => $col_name)
			{
				$this->query_ .= $col;

				if ($col != $lastCol)
					$this->query_ .= ',';
			}

			$this->query_ .= ') values (';

			foreach($columns as $col => $col_name)
			{
				$this->query_ .= $col_name;

				if ($col != $lastCol)
					$this->query_ .= ',';
			}
			
			$this->query_ .= ');';

			$rowCount = $this->executeWrite();
		}
		catch (BadInputException $inEx)
		{
			echo Logger::writeToConsole($inEx->ToString());
			throw $inEx;
		}
		catch (Exception $e)
		{
			echo Logger::writeToConsole($e->getMessage());
			echo Logger::writeToConsole("Query: " . $this->query_);
			$this->db_->rollback();
			throw $e;
		}
		return $rowCount;
	}


	/*
	 * Method to DELETE from a mysql database
	 *
	 * Input:
	 *		string $table: table name to access
	 *		array $columns: keyed array with column names as keys and desired 
	 *					values as values at the respective indices
	 *		string $condJoin: boolean connector between conditions
	 * 		bool overwrite: if $condition array is empty, this flag must be set. Ensures protection
	 *				from accidental overwrite
	 *
	 * Returns int: number of rows removed
	 */
	public function Delete(string $table, array $conditions, string $condJoin, bool $overwrite = false): int
	{
		try
		{
			$this->query_ = 'delete from ' . $table;

			if (!$overwrite && (0 == count($conditions)))
				throw new BadInputException('To delete all records, set bool "overwrite" to true');
 	
			if (0 != count($conditions))
			{
				$this->query_ .= ' where ';

				end($conditions);
				$lastCond = key($conditions);
				reset($conditions);

				foreach($conditions as $cond => $cond_name)
				{
					$this->query_ .= $cond . '=' . $cond_name;

					if ($cond != $lastCond)
						$this->query_ .= ' ' . $condJoin . ' ';
				}
			}

			$this->query_ .= ';';

			$rowCount = $this->executeWrite();
		}
		catch (BadInputException $inEx)
		{
			echo Logger::writeToConsole($inEx->ToString());
			throw $inEx;
		}
		catch (Exception $e)
		{
			echo Logger::writeToConsole($e->getMessage());
			echo Logger::writeToConsole("Query: " . $this->query_);
			$this->db_->rollback();
			throw $e;
		}
		return $rowCount;
	}


	/*
	 * Method to UPDATE rows in a mysql database
	 *
	 * Input:
	 *		string $table: table name to access
	 *		array $columns: keyed array with column names as keys and desired 
	 *					values as values at the respective indices
	 *		string $condJoin: boolean connector between conditions
	 * 		bool overwrite: if $condition array is empty, this flag must be set. Ensures protection
	 *				from accidental overwrite
	 *
	 * Returns int: number of rows removed
	 */
	public function Update(string $table, array $columns, array $conditions, string $condJoin, bool $overwrite = false): int
	{
		try
		{
			$this->query_ = 'update ' . $table . ' set ';

			if (0 == count($columns))
				throw new BadInputException('columns to update are required');

			end($columns);
			$lastCol = key($columns);
			reset($columns);

			foreach($columns as $col=>$col_name)
			{
				$this->query_ .= $col . '=' . $col_name;

				if ($col != $lastCol)
					$this->query_ .= ',';
			}

			if (!$overwrite && (0 == count($conditions)))
				throw new BadInputException('To update all records, set bool "overwrite" to true');			

			if (0 != count($conditions))
			{
				$this->query_ .= ' where ';

				end($conditions);
				$lastCond = key($conditions);
				reset($conditions);

				foreach($conditions as $cond => $cond_name)
				{
					$this->query_ .= $cond . '=' . $cond_name;

					if ($cond != $lastCond)
						$this->query_ .= ' ' . $condJoin . ' ';
				}
			}
			
			$this->query_ .= ';';

			$rowCount = $this->executeWrite();
		}
		catch (BadInputException $inEx)
		{
			echo Logger::writeToConsole($inEx->ToString());
			throw $inEx;
		}
		catch (Exception $e)
		{
			echo Logger::writeToConsole($e->getMessage());
			echo Logger::writeToConsole("Query: " . $this->query_);
			$this->db_->rollback();
			throw $e;
		}
		return $rowCount;
	}



	/* Private Implementation */

	private $address_;
	private $username_;
	private $password_;
	private $schema_;
	private $db_;
	private $query_;


	/*
	 * Method to open a database connection.
	 *
	 * Input: N/A
	 *
	 * Return N/A
	 */
	private function openConnection()
	{
		try
	 	{
			$tempString = 'mysql:host=' . $this->address_ . ';dbname=' . $this->schema_;

			$this->db_ = new PDO($tempString, $this->username_, $this->password_);
		}
		catch (Exception $e)
		{
			echo Logger::writeToConsole($e->getMessage());
			throw new DbOpenException("Failed open");
		}
	}


	private function executeRead(): array
	{
		try
		{
			/* Database Interaction */
			$this->openConnection();

			$this->db_->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

			$this->db_->beginTransaction();
			// Injection? table/column name checking?

			$statement = $this->db_->prepare($this->query_);
			$statement->execute();
			$results = $statement->fetchAll();

			if(0 == count($results))
			{
				throw new Exception("No results found");
			}

			$this->db_->commit();
		}
		catch (DbOpenException $dbEx)
		{
			echo Logger::writeToConsole($dbEx->ToString());
			throw $dbEx;
		}
		catch (Exception $e)
		{
			echo Logger::writeToConsole($e->getMessage());
			echo Logger::writeToConsole("Query: " . $this->query_);
			$this->db_->rollback();
			throw $e;
		}
		return $results;
	}


	private function executeWrite(): int
	{
		try
		{
			$this->openConnection();

			$this->db_->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

			$this->db_->beginTransaction();
			// Injection? table/column name checking?

			$statement = $this->db_->prepare($this->query_);
			$statement->execute();
			$statement->fetchAll();

			$results = $statement->rowCount();

			if(0 == $results)
			{
				throw new Exception("Nothing added");
			}

			$this->db_->commit();
		}
		catch (DbOpenException $dbEx)
		{
			echo Logger::writeToConsole($dbEx->ToString());
			throw $dbEx;
		}
		catch (Exception $e)
		{
			echo Logger::writeToConsole($e->getMessage());
			echo Logger::writeToConsole("Query: " . $this->query_);
			$this->db_->rollback();
			throw $e;
		}
		return $results;
	}
}
?>
