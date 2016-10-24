<?php
namespace Controller;


use Model\Comment;
use Model\Post;

class PostController implements IController
{
    private $fc;

    public function __construct()
    {
        $this->fc = FrontController::getInstance();
    }

    public function viewAction()
    {
        if (is_int($id = $this->fc->getParams()->id)) {
            $post = new Post();
            $comment = new Comment();
            $comment->post_id = $id;
            $comments = $comment->search(['post_id' => $id]);
            $post = $post->findById($id)[0];
            $this->fc->render('post.twig', compact('post', 'comments'));
        } else {
            header('Location: /');
        }
    }

    public function createAction()
    {
        $post = new Post();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (is_bool($errors = $post->validate()) && $post->create()) {
                echo true;
            } else {
                echo json_encode($errors);
            }
        }else{
            header('Location: '.$_SERVER['PHP_SELF']);
        }
    }
}