<?php

require __DIR__ . '/../vendor/autoload.php';

header('Content-Disposition: attachment;');
//$imageContent= (file_get_contents(('uploads/images/emN3qVt5sOwozO4JsTbK9iJFdeWfZxnMjpydxoHl.jpg')));

readfile(('uploads/images/emN3qVt5sOwozO4JsTbK9iJFdeWfZxnMjpydxoHl.jpg'));
//echo $imageContent;

