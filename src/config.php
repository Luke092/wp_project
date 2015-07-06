<?php

const MIN_LENGTH_PASSWORD = 6;
const HASHING_ALGORITHM = "sha256";

$ERROR_MESSAGES = array(
        0 => "Il campo email non pu&ograve; essere vuoto",
        1 => "L'indirizzo email inserito non è corretto",
        2 => "L'indirizzo email inserito &egrave; gi&agrave; stato registrato",
        3 => "Il campo password non pu&ograve; essere vuoto",
        4 => "La password deve essere lunga almeno " . MIN_LENGTH_PASSWORD . " caratteri",
        5 => "Le due password inserite non coincidono",
        6 => "Password errata"
    );