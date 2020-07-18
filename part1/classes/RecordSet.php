<?php

/**
 * abstract class that creates a database connection and returns a record set
 * Follows the recordset pattern
 *
 * @author John Rooksby 
 * @version 9
 * 
 */
abstract class RecordSet {
    protected $conn;
    protected $queryResult;
 
    function __construct() {
        $this->conn = pdoDB::getConnection();
    }
 
    /**
     * This function will execute the query as a prepared statement if there is a params array
     * If not, it executes as a regular statament. 
     *
     * @param string $sql    The sql for the recordset
     * @param array $params  An optional associative array if you want a prepared statement
     * @return PDO_STATEMENT
     */
    function getRecordSet($sql, $params = null) {
        if (is_array($params)) {
            $this->queryResult = $this->conn->prepare($sql);
            $this->queryResult->execute($params);
        }
        else {
            $this->queryResult = $this->conn->query($sql);
        }
        return $this->queryResult;
    }
}
 

?>