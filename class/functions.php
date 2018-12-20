<?php
/**
 * Created by PhpStorm.
 * User: Ksar
 * Date: 19.12.2018
 * Time: 18:11
 */

class Functions {
    static function sanitizeData($input) {
#        return filter_var($input,FILTER_SANITIZE_STRING);
        return preg_replace("/[^A-Za-z0-9=]/",'',$input);
    }

    static function showRow($data, $link_to = null, $source_table = null) {
        if ( is_array($data) && !empty($data) ) {
            $output = '';
            foreach ($data as $subcol => $subval) {
                if ( !is_int($subcol) && 'id' != substr($subcol,0,2) ) {
                    if ( strlen($link_to) > 0 ) {
                        $output .= '<td><a href="/?table=' . $link_to . '&id=' . $data[0] . '">' . $subval . '</a></td>';
                    }
                    else {
                        $output .= '<td>' . $subval . '</td>';
                    }
                }
            }
            if ($source_table) {
                $output .= '<td><a href="/?table=' . $source_table . '&action=update&id='   . $data[0] . '">EDIT  </a></td>'
                         . '<td><a href="/?table=' . $source_table . '&action=delete&id=' . $data[0] . '" onclick="return confirm(\'Are you sure you want to delete this item?\');">DELETE</a></td>';
            }
        }
        else {
            $output = 'No data';
        }

        return $output;
    }

    static function showData($data, $link_to = null, $source_table = null, $author_id = null) {
        if ( is_array($data) && !empty($data) ) {
            $output = '<table class="table">';
            foreach ($data as $key => $val) {
                if ($key == 0) {
                    $output .= '<tr>';
                    foreach ($val as $subcol => $subval) {
                        if ( !is_int($subcol) && 'id' != substr($subcol,0,2) )
                            $output .= '<th class="upper">' . $subcol . '</th>';
                    }
                    $output .= '<th><a href="/?table=' . $source_table . '&action=create">CREATE</a></th>';
                    $output .= '<th><a href="/"><- BACK</a></th>';
                    $output .= '</tr>';
                    $output .= '<tr>';
                    $output .= self::showRow($val, $link_to, $source_table);
                    $output .= '</tr>';
                } else {
                    $output .= '<tr>';
                    $output .= self::showRow($val, $link_to, $source_table);
                    $output .= '</tr>';
                }
            }
            $output .= '</table>';
        }
        else {
            if ( $author_id > 0 ) {
                $output = 'No data, but you can <a href="/?table=' . $source_table . '&action=create&author_id=' . $author_id . '">CREATE</a> a new one.';
            }
            else {
                $output = 'No data, but you can <a href="/?table=' . $source_table . '&action=create">CREATE</a> a new one.';
            }
        }

        return $output;
    }

    static function editRow ($row = null, $table = null, $id = null, $what_to_edit) {
        $out = '<form action="" method="POST">';
        foreach ($what_to_edit as $field) {
           $out .= '<input name="' . $field . '" type="text" value="' . $row[0][$field] . '" maxlength="60" size="60"><br>';
        }
        $out .= '<input name="action" value="Save" type="submit"></form>';

        return $out;
    }

    static function createRow ($table = null, $what_to_edit, $author_id = null) {
        $out = '<form action="" method="POST">';
        foreach ($what_to_edit as $field) {
            $out .= $field . ': <input name="' . $field . '" type="text" value="" maxlength="60" size="60"><br>';
        }
        $out .= '<input name="action" value="Create" type="submit">';
        if ( isset ($author_id) && $author_id > 0 ) {
            $out .= '<input type="hidden" name="author_id" value="' . $author_id . '">';
        }
        $out .= '</form>';

        return $out;
    }


    static function mainRouting ( $_get, $_post, $book, $author ) {
        $list = '';
        $link_to = '';
        if ( isset($_get['id']) ) { $_get['id'] = $_get['id'] + 0; } else { $_get['id'] = 0; }
        if ( isset($_get['table']) ) { $_get['table'] = Functions::sanitizeData($_get['table']); } else { $_get['table'] = 'author';}
        if ( isset($_get['action']) ) { $_get['action'] = Functions::sanitizeData($_get['action']); } else { $_get['action'] = 'select';}


        switch ( $_get['action'] ) {
            case 'create':
                if ( 'book' == $_get['table'] ) {
                    $what_to_edit = array ( 'title' );
                    if ( isset ( $_post['action'] ) && 'Create' == $_post['action'] ) {
                        $_post['author_id'] = $_post['author_id'] + 0;
                        foreach ($what_to_edit as $wte) {
                            $what_to_update[$wte] = $_post[$wte];
                        }
                        if ( $book->insert ( $what_to_update, $_post['author_id'] ) ) $out = "Created";
                        else $out = "Not created";
                    }
                    else {
                        $out = Functions::createRow ( $_get['table'], $what_to_edit, $_get['author_id'] );
                    }
                }
                else {
                    $what_to_edit = array ( 'name', 'surname' );
                    if ( isset ( $_post['action'] ) && 'Create' == $_post['action'] ) {
                        foreach ($what_to_edit as $wte) {
                            $what_to_update[$wte] = $_post[$wte];
                        }
                        if ( $author->insert ( $what_to_update ) ) $out = "Created";
                        else $out = "Not created";
                    }
                    else {
                        $out = Functions::createRow ( $_get['table'], $what_to_edit );
                    }
                }
                break;
            case 'update':
                if ( 'book' == $_get['table'] ) {
                    $row = $book->get('b.id=' . $_get['id']);
                    $what_to_edit = array ( 'title' );
                    if ( isset($_post['action']) && 'Save' == $_post['action'] ) {
                        if ($book->update(array ( $what_to_edit[0] => $_post[$what_to_edit[0]] ), $_get['id']) ) $out = "Saved";
                        else $out = "Not saved";
                    }
                    else {
                        $out = Functions::editRow($row, $_get['table'], $_get['id'], $what_to_edit);
                    }
                }
                else {
                    $row = $author->get('id=' . $_get['id']);
                    $what_to_edit = array ( 'name', 'surname' );
                    if ( isset($_post['action']) && 'Save' == $_post['action'] ) {
                        foreach ($what_to_edit as $wte) {
                            $what_to_update[$wte] = $_post[$wte];
                        }
                        if ($author->update($what_to_update, $_get['id']) ) $out = "Saved";
                        else $out = "Not saved";
                    }
                    else {
                        $out = Functions::editRow($row, $_get['table'], $_get['id'], $what_to_edit);
                    }
                }
                break;
            case 'delete':
                if ( 'book' == $_get['table'] ) {
                    if ( $book->delete( $_get['id'] ) ) $out = "Deleted";
                    else $out = "Not deleted";
                }
                else {
                    if ( $author->delete( $_get['id'] ) ) $out = "Deleted";
                    else $out = "Not deleted";
                }
                break;
            default:
                switch ($_get['table']) {
                    case 'book':
                        $list = $book->get ( 'id_author = ' . $_get['id'] );
                        $author_id = $_get['id'];
                        break;

                    case 'author':
                    default:
                        $list = $author->get();
                        $link_to = 'book';
                        $author_id = null;
                }
                $out = Functions::showData($list, $link_to, $_get['table'], $author_id);
        }

        return $out;
    }
}
