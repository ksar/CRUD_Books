<?php
/**
 * Created by PhpStorm.
 * User: Ksar
 * Date: 19.12.2018
 * Time: 17:17
 */

class Author extends AuthorConfig {

    private $db_conn;

    public function __construct ( $db_conn ) {
        $this->db_conn = $db_conn;
    }

    function insert ( $data ){
        $model = new AuthorModel ( $this->db_conn, $this->table_name );
        $return_data = $model->insert( $data );
        return $return_data;
    }

    function get ( $where_condition = null ) {
        $model = new AuthorModel ( $this->db_conn, $this->table_name );
        $return_data = $model->get( $where_condition );
        return $return_data;
    }

    function update ( $data, $id = null ) {
        $model = new AuthorModel ( $this->db_conn, $this->table_name );
        $return_data = $model->update( $data, $id );
        return $return_data;
    }

    function delete ( $id = null ) {
        $model = new AuthorModel ( $this->db_conn, $this->table_name );
        $return_data = $model->delete( $id );
        return $return_data;
    }


    // signup user
    function signup() {

        if($this->isAlreadyExist()) {
            return false;
        }
        // query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    username=:username, password=:password, created=:created";

        // prepare query
        $stmt = $this->db_conn->prepare($query);

        // sanitize
        $this->username=htmlspecialchars(strip_tags($this->username));
        $this->password=htmlspecialchars(strip_tags($this->password));
        $this->created=htmlspecialchars(strip_tags($this->created));

        // bind values
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":created", $this->created);

        // execute query
        if($stmt->execute()) {
            $this->id = $this->db_conn->lastInsertId();
            return true;
        }

        return false;

    }

}
