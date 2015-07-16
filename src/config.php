<?php

const MIN_LENGTH_PASSWORD = 6;
const HASHING_ALGORITHM = "sha256";
const DEF_CAT_DIR = "./img/default_cat/";
const DEF_FEED_ICON_PATH = "./img/utils/feed_icon.png";
const DEF_ARTICLE_IMAGE_PATH = "./img/utils/default-article.jpg";
const FAVICON_API_URL = 'http://www.google.com/s2/favicons?domain=';
const DEF_CATS_PER_ROW = 4;
const FEEDS_PER_ROW = 3;
const ARTICLES_PER_FEED = 5;
const ARTICLES_PER_CATEGORY = 5;
const DATE_FORMAT = "D j F Y G i";
const MAX_LENGTH_DESC = 100;
const MAX_LENGTH_TITLE = 75;
const MAX_LENGTH_SUMMARY = 200;
const ARTICLES_PER_PAGE = 5;

$MONTHS = array(
    "January" => "gennaio",
    "February" => "febbraio",
    "March" => "marzo",
    "April" => "aprile",
    "May" => "maggio",
    "June" => "giugno",
    "July" => "luglio",
    "August" => "agosto",
    "September" => "settembre",
    "October" => "ottobre",
    "November" => "novembre",
    "December" => "dicembre"
);

$DAYS = array(
    "Mon" => "lun",
    "Tue" => "mar",
    "Wed" => "mer",
    "Thu" => "gio",
    "Fri" => "ven",
    "Sat" => "sab",
    "Sun" => "dom"
);

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