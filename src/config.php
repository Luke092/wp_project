<?php

const MIN_LENGTH_PASSWORD = 6;
const HASHING_ALGORITHM = "sha256";
const DEF_CAT_DIR = "http://localhost/RSSAggregator/img/default_cat/";
const DEF_CAT_PER_ROW = 4;

$ERROR_MESSAGES = array(
        // authentication errors
        0 => "Il campo email non pu&ograve; essere vuoto",
        1 => "L'indirizzo email inserito non è corretto",
        2 => "L'indirizzo email inserito &egrave; gi&agrave; stato registrato",
        3 => "Il campo password non pu&ograve; essere vuoto",
        4 => "La password deve essere lunga almeno " . MIN_LENGTH_PASSWORD . " caratteri",
        5 => "Le due password inserite non coincidono",
        6 => "Password errata",
        8 => "Errore nel dialogo con il database",
        9 => "Utente non registrato",
        
        // directory errors
        7 => "Errore nell'apertura della directory ",
    );