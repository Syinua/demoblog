<?php

namespace Model;
/**
 * The followings are the available columns in table 'tbl_comment':
 * @property integer $id
 * @property string $content
 * @property integer $create_time
 * @property string $author
 * @property integer $post_id
 */
class Comment extends EasyCRUD
{
    // Table name
    protected $table = 'tbl_comments';

    // Primary Key of the Table
    protected $pk = 'id';

    private $errors;

    /**
     * @return array validation rules for model attributes.
     */

    public function rules()
    {
        return array(
            'author' => array(
                'filter' => FILTER_VALIDATE_REGEXP,
                'options' => array('regexp' => '/^[A-Za-z]+$/i'),
            ),
            'content' => array('filter' => FILTER_SANITIZE_STRING),
            'post_id' => array('filter' => FILTER_SANITIZE_NUMBER_INT)
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
        return parent::create();
    }
}