 <?php
class AModel extends ACore {

    private $db;

    /**
     *
     * Run a mysql database query
     * @param String $sql
     */
    public function query($sql) {

        //GG Converted to PDO 22-10-2013
        try {
            if(!$this->db) {
                //if not a open connection then create one and query
                $this->connect();
            }

            return $this->db->query($sql);
        } catch(PDOException $ex) {
            //print_r($ex);
            echo "Connection Error";
            exit;
        }


    }
    public function rowCount() {
        return $this->db->rowCount();

    }
    public function prepare($sql) {
        try {
            if(!$this->db) {
                //if not a open connection then create one and query
                $this->connect();
            }

            return $this->db->prepare($sql);
        } catch(PDOException $ex) {
            print_r($ex);
            exit;
        }
    }
    public function insertid() {
        //return mysql_insert_id(); GG Convert to PDO
        return $this->db->lastInsertId();
    }
    /**
     *
     * Gets all data from a mysql result set and returns a mixed array
     * @param mysql_result $result
     */
    public function getData($result = null) {
        //while($row = mysql_fetch_object($result)) {
        while($row = $result->fetch(PDO::FETCH_OBJ)) { //convert to PDO
            $rows[] = $row;
        }
        if(count($rows) == 0)
            return false;
        else
            return $rows;
    }
    /**
     * Sanitize the data that is passed in
     */
    public function sanitize($data) {
        foreach($data as $index=>$row) {

            $info[$index] = $this->sanitizeline($row);
        }
        return $info;
    }
    public function trimend($string) {
        return substr($string,0,strlen($string)-1);
    }
    public function sanitizeline($string) {
        //return mysql_real_escape_string($string);
        //return $this->db->quote($string);
        $string = trim(htmlentities(strip_tags($string,",")));

        if (get_magic_quotes_gpc())
            $string = stripslashes($string);

        // $string = mysql_real_escape_string($string);
        $string = str_replace("#", '', $string);
        return $string;
    }
    public function sanitizebyref(&$string) {
        $string = $this->sanitizeline($string);
    }


    public function insert($tablename, $data = array()) {
        /*foreach($data as $key =>$val) {
            $keys[] = "`".$this->sanitizeline($key)."`";
            $vals[] = "'".$this->sanitizeline($val)."'";
        }*/
        //GG convert to PDO
        foreach($data as $key =>$val) {
            $keys[] = "`".$this->sanitizeline($key)."`";
            $vals[] = " ?";
            $datavalues[] = $val;
        }
        $fields = implode(",", $keys);
        $values = implode(",",$vals);
        $sql = "INSERT INTO {$tablename} ({$fields}) VALUES({$values})";

        $stmnt = $this->prepare($sql);
        $stmnt->execute($datavalues);


        return $this->insertid();
    }

    public function update($tablename,$data=array(),$field,$value) {

        /*foreach($data as $key => $val) {
            $pieces[] ="`{$key}`='".$this->sanitizeline($val)."'";
        }
        $info = implode(",", $pieces);*/
        //GG Converted to PDO based
        foreach($data as $key => $val) {
            $pieces[] = "`{$key}`= ?";
            $values[] = $val;

        }

        $info = implode(",", $pieces);


        $sql = "UPDATE {$tablename} SET {$info} WHERE `{$field}`='{$value}'";


        try {
            $stmt = $this->prepare($sql);
            $stmt->execute($values);
            return $value;
        } catch(PDOException $ex) {

        }


    }

    public function delete($tablename,$field,$value) {
        $value = $this->sanitizeline($value);
        $field = $this->sanitizeline($field);

        $sql = "DELETE FROM {$tablename} WHERE `{$field}`='{$value}'";
        $this->query($sql);
    }

    public function get($tablename,$where="",$order="",$limit="") {
        $sql = "SELECT * FROM {$tablename}";
        if($where !="") {
            $sql.= " WHERE {$where}";
        }
        if($order != "") {
            $sql.= " ORDER BY {$order}";
        }
        if($limit != "") {
            $sql.= " LIMIT {$limit}";
        }
        return $this->getData($this->query($sql));
    }
    public function fetchObjects($result)  {
        //while($row = mysql_fetch_object($result)) $rows[] = $row;
        while($row = $result->fetch(PDO::FETCH_OBJ)) $rows[] = $row;

        return $rows;
    }
    public function fetchObject($result)  {
        //$row = mysql_fetch_object($result);
        $row = $result->fetch(PDO::FETCH_OBJ);
        return $row;

    }
    public function insertWithArray($tablename,$data) {
        $sql = "INSERT INTO $tablename (".implode(",",array_keys($data)).") VALUES(".implode(",", array_fill(0, count($data), "?")).")";
        $statement = $this->prepare($sql);
        return $statement->execute(array_values($data));
    }

    public function updateWithArray($tablename,$data) {
        $sql = "UPDATE $tablename (".implode("=?,",array_keys(array_slice($data,0,count($data)-1)))."=?) WHERE control = ?";
        $statement = $this->prepare($sql);
        return $statement->execute(array_values($data));
    }

    /**
     * This will create a connection to the database.
     */
    public function connect()
    {

        if ($_SERVER['HTTP_HOST'] == "premiervdc.m2demo.com") {
            $this->db = new PDO('mysql:host=m2dev-db-ws-002.c9dvmnpdpl1l.us-east-1.rds.amazonaws.com;dbname=ws_premierportal;charset=utf8', 'root', 'soins009');
        } else {
            //$this->db = new PDO('mysql:host=beta;dbname=ws_premierportal;charset=utf8', 'premier', 'premier');
            $this->db = new PDO('mysql:host=m2dev-db-ws-002.c9dvmnpdpl1l.us-east-1.rds.amazonaws.com;dbname=ws_premierportal;charset=utf8', 'root', 'soins009');
            //$this->db = new PDO('mysql:host=localhost;dbname=ws_premierportal;charset=utf8', 'root', 'root');
        }
    }

}

?>