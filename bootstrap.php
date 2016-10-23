<?php
// app/bootstrap.php
require_once __DIR__.'/vendor/autoload.php';

$front = Controller\FrontController::getInstance();

$front->siteRoute();

echo $front->getHtml();