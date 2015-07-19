<?php

$json = $_POST["article"];
$article = json_decode($json);
echo $article->text;