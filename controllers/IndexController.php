<?php
namespace Controller;


use Model\Post;

class IndexController implements IController
{
    private $fc;

    public function __construct()
    {
        $this->fc = $fc = FrontController::getInstance();
    }

    public function indexAction()
    {
        $post = new Post();
        $posts = $post->getAll();
        $sliders = $post->getTop();
        $this->fc->render('index.twig', compact('posts', 'sliders'));
    }
}