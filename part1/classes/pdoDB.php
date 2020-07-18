<?php
/**
 * Create a database connection
 *
 * This uses the singleton pattern to return a database connection. It
 * is based on the solution from week 7 but accesses the dbname from the 
 * registry. 
 *
 * @author John Rooksby 
 * @version 8.1
 *  
 */ 
class pdoDB {
  private static $dbConnection = null;
 
  private function __construct() {
  }
  private function __clone() {
  }
 
  /**
   * Return DB connection or create initial connection
   *
   * @return object (PDO)
   */
  public static function getConnection() {

    $dbname = ApplicationRegistry::getDBName();

    if ( !self::$dbConnection ) {
        try {           
          self::$dbConnection = new PDO($dbname);
					self::$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          
         }
         catch( PDOException $e ) {
            echo $e->getMessage();
         }
    }
    return self::$dbConnection;
  }
 
}
?>
