<?php


/**
 * Description of orders
 *
 * @author sika
 */

class cro {
    private $dbh;
    private $Allcro;
    private $sql_oneDayCro = "SELECT * FROM cro WHERE return_time >= ? AND return_time < ? ";
    private $stmt_oneDay;


    public function __construct() {
        //connect db
        $user = "smesys";
        $pass = "Inventory#sys";
        try{
            $this->dbh = new PDO('pgsql:host=localhost;dbname=smeinventory', $user, $pass);
            //$this->dbh = new PDO('pgsql:host=localhost;dbname=test', $user, $pass);
        } catch (PDOException $e) {
            echo "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
        
        $this->stmt_oneDay = $this->dbh->prepare($this->sql_oneDayCro);
    }
    
    public function getAllCro(){
        $stmt = $this->dbh->query("SELECT * FROM cro");
        if($stmt !== FALSE){
            $this->Allcro = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }else{
            print_r($stmt->errorInfo());
            exit();
        }
        return $this->Allcro;
    }
    
    public function getOneDayCro($day){
        $nextDay = new DateTime($day."+1day");
        //echo $day."/";
        //echo $nextDay->format("Y-m-d")."<br/>";
        if($this->stmt_oneDay->execute(array($day,$nextDay->format("Y-m-d"))))
                return $this->stmt_oneDay->fetchAll (PDO::FETCH_ASSOC);
        else{
            print_r($this->stmt_oneDay->errorInfo());
            exit();
        }
    }
    

}

?>
