<?php

namespace Model;

/**
 * The followings are the available columns in table 'tbl_post':
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property integer $create_time
 * @property integer $update_time
 * @property string $author
 */
class Post extends EasyCRUD
{
    // Your Table name
    protected $table = 'tbl_posts';

    // Primary Key of the Table
    protected $pk = 'id';

    private $errors;

    /**
     * @return array validation rules for model attributes.
     */

    private function rules()
    {
        return array(
            'author' => array(
                'filter' => FILTER_VALIDATE_REGEXP,
                'options' => array('regexp' => '/^[A-Za-z]+$/i'),
            ),
            'content' => array('filter' => FILTER_SANITIZE_STRING),
            'title' => array('filter' => FILTER_SANITIZE_STRING),
        );
    }

    public function validate()
    {
        foreach (filter_input_array(INPUT_POST, $this->rules()) as $key => $value) {
            if (!$value) {
                $this->errors[$key] = "The $key you entered is not valid";
            } else {
                $this->attributes[$key] = $value;
            }
        }
        if (count($this->errors) > 0) {
            return $this->errors;
        }

        return true;
    }

    public function create()
    {
        $this->attributes['create_time'] = time();
        $this->attributes['update_time'] = time();
        return parent::create();
    }

    public function getAll()
    {
        return $this->db->query("SELECT p.id, p.author, p.title, p.content , p.create_time, COUNT(c.post_id) AS comments
                  FROM tbl_posts AS p LEFT JOIN tbl_comments AS c ON p.id = c.post_id GROUP BY p.id ORDER BY p.create_time DESC");
    }

    public function findById($id = '')
    {
        $this->db->bind("id",$id);
        return $this->db->query("SELECT p.id, p.author, p.title, p.content , p.create_time, COUNT(c.post_id) AS comments
                  FROM tbl_posts AS p LEFT JOIN tbl_comments AS c ON p.id = c.post_id 
                  WHERE p.id = :id LIMIT 1");
    }

    public function getTop()
    {
        return $this->db->query("SELECT p.id, p.author, p.title, p.content , p.create_time, COUNT(c.post_id) AS comments
                  FROM tbl_posts AS p LEFT JOIN tbl_comments AS c ON p.id = c.post_id 
                  GROUP BY p.id ORDER BY COUNT(c.post_id) DESC LIMIT 5");
    }
}
