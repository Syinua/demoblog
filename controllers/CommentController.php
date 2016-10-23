<?php
namespace Controller;


use Model\Comment;

class CommentController implements IController
{
    private $fc;

    public function __construct()
    {
        $this->fc = FrontController::getInstance();
    }

    public function createAction()
    {
        $comment = new Comment();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (is_bool($errors = $comment->validate()) && $comment->create()) {
                echo true;
            }else{
                echo json_encode($errors);
            }
        }
    }
}