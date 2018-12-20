<?php
/**
 * Created by PhpStorm.
 * User: Ksar
 * Date: 19.12.2018
 * Time: 17:46
 */
interface CRUD {

    public function insert($data);
    public function get($data);
    public function update($data, $id);
    public function delete($id);

}
