<?php

require_once 'RenderInterface.php';
require_once 'FormRender.php';
require_once 'InputElement.php';
require_once 'TextElement.php';

$form = new \Composite\FormRender();
$form->addElements(new \Composite\InputElement());
$form->addElements(new \Composite\TextElement("hello world"));
echo $form->render();
