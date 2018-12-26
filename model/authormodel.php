<?php
/**
 * Created by PhpStorm.
 * User: Ksar
 * Date: 19.12.2018
 * Time: 17:54
 */

class AuthorModel implements CRUD {
    private $db_connection;
    private $table_name;

    function __construct ( $db_connection, $table_name ) {
        $this->db_connection = $db_connection;
        $this->table_name = $table_name;
    }

    function insert ( $data ) {
        foreach ( $data as $key => $value ) {
            $keyData[] = $key;
            $valueData[] = "'" . $value . "'";
        }
        $query = 'INSERT INTO ' . $this->table_name . ' (' . join(",",$keyData) . ') VALUES (' . join(",",$valueData) . ')';
        $prepared_query = $this->db_connection->prepare($query);
        $result = $prepared_query->execute();
        if ($result) $result = $this->db_connection->lastInsertId();

        return $result;
    }

    function get ( $where_condition = null ) {
        $query = 'SELECT id, name, surname FROM ' . $this->table_name;
        if ( !is_null($where_condition) && strlen($where_condition) > 0 ) {
            $query .= ' WHERE ' . Functions::sanitizeData($where_condition);
        }
        $prepared_query = $this->db_connection->prepare($query);
        $prepared_query->execute();
        $result = $prepared_query->fetchAll();

        return $result;
    }

    function update ($data, $id = null ) {
        $id = $id + 0;
        foreach ( $data as $key => $value ) {
            $valueData[] = $key . " = '" . $value . "'";
        }
        if ( $id > 0 ) {
            $query = 'UPDATE ' . $this->table_name . ' SET ' . join(",",$valueData) . ' WHERE id=' . $id;
            $prepared_query = $this->db_connection->prepare($query);
            $result = $prepared_query->execute();

            return $result;
        }
        else {

            return false;
        }
    }

    function delete ( $id = null ) {
        $id = $id + 0;
        if ( $id > 0 ) {
            $query = 'DELETE FROM ' . $this->table_name . ' WHERE id=?';
            $prepared_query = $this->db_connection->prepare($query);
            $result = $prepared_query->execute([$id]);
            return $result;
        }
        else {
            return false;
        }
    }

}
