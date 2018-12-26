<?php
/**
 * Created by PhpStorm.
 * User: Ksar
 * Date: 24.12.2018
 * Time: 11:03
 */

require_once ('class/functions.php');
spl_autoload_register('Functions::myAutoloader');

class AuthorModelTest extends \PHPUnit\Framework\TestCase {

    public function testGreetings() {
        $greetings = 'Hello World';
        $this->assertEquals('Hello World', $greetings);
    }

    public function testGetInsertUpdateDeleteFromAuthorTable() {
        $what_to_insert = array ( 'name' => 'TestName', 'surname' => 'TestSurname' );
        foreach ($what_to_insert as $k => $wte) {
            $what_to_update[$k] = $wte;
        }
        $database = new Database();
        $db_connection = $database->getConnection();
        $author_db = new Author($db_connection);
        $author_last_insert_id = $author_db->insert($what_to_update);
        $this->assertGreaterThan(0, $author_last_insert_id, 'Invalid last insert id into Author table');

        $what_to_update[key($what_to_insert)] = 'TestName2';
        $result = $author_db->update($what_to_update, $author_last_insert_id);
        $this->assertTrue($result,'Error in module Author in UPDATE statement.');

        $result = $author_db->get('id='.$author_last_insert_id);
        $this->assertEquals('TestName2', $result[0][key($what_to_insert)]);

        $result = $author_db->delete($author_last_insert_id);
        $this->assertTrue($result,'Error in module Author in DELETE statement');
    }

    public function testFunctionsShowRowCaseWithDataWithLinkWithSource() {
        $norm = '<td><a href="?table=link_to&id=1">author1</a></td><td><a href="?table=link_to&id=1">title1</a></td><td><a href="?table=source_table&action=update&id=1">EDIT</a></td><td><a href="?table=source_table&action=delete&id=1" onclick="return confirm(\'Are you sure you want to delete this item?\');">DELETE</a></td>';
        $data = array ( 'id' => 1 , 0 => 1, 'author' => 'author1', 1 => 'author1', 'title' => 'title1', 2 => 'title1');
        $ret = Functions::showRow($data,'link_to','source_table');
        $this->assertEquals($norm, $ret);
    }

    public function testFunctionsShowRowCaseWithDataNoLinkNoSource() {
        $norm = '<td>author1</td><td>title1</td>';
        $data = array ( 'id' => 1 , 0 => 1, 'author' => 'author1', 1 => 'author1', 'title' => 'title1', 2 => 'title1');
        $ret = Functions::showRow($data);
        $this->assertEquals($norm, $ret);
    }

    public function testFunctionsShowRowCaseWithNoData() {
        $norm = 'No data';
        $ret = Functions::showRow(null);
        $this->assertEquals($norm, $ret);
    }

    public function testFunctionShowDataCaseWithDataWithLinkWithSourceWithAuthorId() {
        $norm = '<table class="table"><tr><th class="upper">name</th><th class="upper">surname</th><th><a href="?table=source_table&action=create&author_id=2">CREATE</a></th><th><a href="?"><- BACK</a></th></tr><tr><td><a href="?table=link_to&id=1">name1</a></td><td><a href="?table=link_to&id=1">surname1</a></td><td><a href="?table=source_table&action=update&id=1">EDIT</a></td><td><a href="?table=source_table&action=delete&id=1" onclick="return confirm(\'Are you sure you want to delete this item?\');">DELETE</a></td></tr></table>';
        $data = array ( 0 => array ( 'id' => 1 , 0 => 1, 'name' => 'name1', 1 => 'name1', 'surname' => 'surname1', 2 => 'surname1'));
        $ret = Functions::showData($data,'link_to','source_table', $author_id = 2);
        $this->assertEquals($norm, $ret);
    }

    public function testFunctionShowDataCaseWithDataNoLinkNoSourceNoAuthorId() {
        $norm = '<table class="table"><tr><th class="upper">name</th><th class="upper">surname</th><th><a href="?table=&action=create">CREATE</a></th><th><a href="?"><- BACK</a></th></tr><tr><td>name1</td><td>surname1</td></tr></table>';
        $data = array ( 0 => array ( 'id' => 1 , 0 => 1, 'name' => 'name1', 1 => 'name1', 'surname' => 'surname1', 2 => 'surname1'));
        $ret = Functions::showData($data);
        $this->assertEquals($norm, $ret);
    }

    public function testFunctionShowDataCaseWithNoDataNoLinkNoSourceWithAuthorId() {
        $norm = 'No data, but you can <a href="?table=&action=create&author_id=2">CREATE</a> a new one.';
        $ret = Functions::showData(null,null,null,2);
        $this->assertEquals($norm, $ret);
    }

    public function testFunctionShowDataCaseWithNoDataNoLinkNoSourceNoAuthorId() {
        $norm = 'No data, but you can <a href="?table=&action=create">CREATE</a> a new one.';
        $ret = Functions::showData(null);
        $this->assertEquals($norm, $ret);
    }
}
