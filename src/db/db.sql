# Script for creating and populating the db of the application

# delete db if exists
DROP DATABASE IF EXISTS RssAggregator;

CREATE DATABASE IF NOT EXISTS RssAggregator;

USE RssAggregator;

# create db tables
CREATE TABLE IF NOT EXISTS Users(
    email varchar(255),
    password varchar(64) NOT NULL,
    PRIMARY KEY (email)
);

CREATE TABLE IF NOT EXISTS Categories(
    id int AUTO_INCREMENT, # define an autoincremental index
    c_name varchar(30) UNIQUE NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS Feeds(
    id int AUTO_INCREMENT,
    f_name varchar(64) NOT NULL,
    url varchar(255) NOT NULL,
    default_cat int,
    PRIMARY KEY (id),
    FOREIGN KEY (default_cat) REFERENCES Categories(id),
    UNIQUE (f_name, url)
);

CREATE TABLE IF NOT EXISTS UCF(
    email varchar(255),
    c_id int,
    f_id int,
    PRIMARY KEY (email, c_id, f_id),
    CONSTRAINT fk_user FOREIGN KEY (email) REFERENCES Users(email)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_cat FOREIGN KEY (c_id) REFERENCES Categories(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_feed FOREIGN KEY (f_id) REFERENCES Feeds (id)
        ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS Stats(
    email varchar(255),
    f_readed int NOT NULL,
    file_path varchar(255) NOT NULL,
    PRIMARY KEY (email),
    FOREIGN KEY (email) REFERENCES Users (email) 
        ON DELETE CASCADE ON UPDATE CASCADE
);

# clear tables data for tables witch already exists
DELETE FROM Users;
DELETE FROM Categories;
DELETE FROM Feeds;
DELETE FROM UCF;
DELETE FROM Stats;

# add data example into tables
INSERT INTO Categories (c_name) VALUES ("News");
INSERT INTO Categories (c_name) VALUES ("Tech");
INSERT INTO Categories (c_name) VALUES ("Cultura");
INSERT INTO Categories (c_name) VALUES ("Spettacolo");

INSERT INTO Feeds (f_name,url,default_cat) VALUES ("Corriere", "http://xml.corriereobjects.it/rss/homepage.xml", "1");
INSERT INTO Feeds (f_name,url,default_cat) VALUES ("Repubblica", "http://www.repubblica.it/rss/homepage/rss2.0.xml", "1");
INSERT INTO Feeds (f_name,url,default_cat) VALUES ("NYT International", "http://rss.nytimes.com/services/xml/rss/nyt/InternationalHome.xml", "1");
INSERT INTO Feeds (f_name,url,default_cat) VALUES ("Gizmodo", "http://feeds.gawker.com/gizmodo/full", "2");
INSERT INTO Feeds (f_name,url,default_cat) VALUES ("The Verge", "http://www.theverge.com/rss/index.xml", "2");
INSERT INTO Feeds (f_name,url,default_cat) VALUES ("News Tom's Hardware", "http://www.tomshardware.com/feeds/rss2/news.xml", "2");
