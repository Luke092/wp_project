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
    is_default int NOT NULL,
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
    file_path varchar(255) NOT NULL,
    PRIMARY KEY (email),
    FOREIGN KEY (email) REFERENCES Users (email) 
        ON DELETE CASCADE ON UPDATE CASCADE
);

# add data example into tables
INSERT INTO Categories (c_name,is_default) VALUES ("News", "1");
INSERT INTO Categories (c_name,is_default) VALUES ("Tecnologia", "1");
INSERT INTO Categories (c_name,is_default) VALUES ("Design", "1");
INSERT INTO Categories (c_name,is_default) VALUES ("Gastronomia", "1");
INSERT INTO Categories (c_name,is_default) VALUES ("Economia", "1");
INSERT INTO Categories (c_name,is_default) VALUES ("Gossip", "1");
INSERT INTO Categories (c_name,is_default) VALUES ("Tendenze", "1");
INSERT INTO Categories (c_name,is_default) VALUES ("Scienza", "1");
INSERT INTO Categories (c_name,is_default) VALUES ("Marketing", "1");
INSERT INTO Categories (c_name,is_default) VALUES ("Fumetti", "1");
INSERT INTO Categories (c_name,is_default) VALUES ("Viaggi", "1");
INSERT INTO Categories (c_name,is_default) VALUES ("Cinema", "1");

INSERT INTO Feeds (f_name,url,default_cat) VALUES ("Corriere", "http://xml.corriereobjects.it/rss/homepage.xml", "1");
INSERT INTO Feeds (f_name,url,default_cat) VALUES ("Repubblica", "http://www.repubblica.it/rss/homepage/rss2.0.xml", "1");
INSERT INTO Feeds (f_name,url,default_cat) VALUES ("NYT International", "http://rss.nytimes.com/services/xml/rss/nyt/InternationalHome.xml", "1");
INSERT INTO Feeds (f_name,url,default_cat) VALUES ("Il Giornale", "http://www.ilgiornale.it/feed.xml", "1");

INSERT INTO Feeds (f_name,url,default_cat) VALUES ("Gizmodo", "http://feeds.gawker.com/gizmodo/full", "2");
INSERT INTO Feeds (f_name,url,default_cat) VALUES ("Android", "http://www.androidworld.it/feed/", "2");
INSERT INTO Feeds (f_name,url,default_cat) VALUES ("The Verge", "http://www.theverge.com/rss/index.xml", "2");
INSERT INTO Feeds (f_name,url,default_cat) VALUES ("News Tom's Hardware", "http://www.tomshardware.com/feeds/rss2/news.xml", "2");

INSERT INTO Feeds (f_name,url,default_cat) VALUES ("Designerblog", "http://feeds.blogo.it/designerblog/it", "3");
INSERT INTO Feeds (f_name,url,default_cat) VALUES ("Bloggokin", "http://www.bloggokin.it/feed/", "3");
INSERT INTO Feeds (f_name,url,default_cat) VALUES ("Designbuzz", "http://feeds.feedburner.com/Designbuzz?format=xml", "3");

INSERT INTO Feeds (f_name,url,default_cat) VALUES ("La ciliegina sulla torta", "http://feeds.feedburner.com/LaCilieginaSullaTorta?format=xml", "4");
INSERT INTO Feeds (f_name,url,default_cat) VALUES ("La scienza in cucina", "http://bressanini-lescienze.blogautore.espresso.repubblica.it/feed/", "4");
INSERT INTO Feeds (f_name,url,default_cat) VALUES ("Dissapore", "http://www.dissapore.com/feed/", "4");
INSERT INTO Feeds (f_name,url,default_cat) VALUES ("Ricette di Misya", "http://feeds.feedburner.com/MimiSapore?format=xml", "4");

INSERT INTO Feeds (f_name,url,default_cat) VALUES ("Soldiblog", "http://feeds.blogo.it/soldiblog/it", "5");
INSERT INTO Feeds (f_name,url,default_cat) VALUES ("Finanzablog", "http://feeds.blogo.it/finanzablog/it", "5");
INSERT INTO Feeds (f_name,url,default_cat) VALUES ("Risorse, economia e ambiente", "https://aspoitalia.wordpress.com/feed/", "5");
INSERT INTO Feeds (f_name,url,default_cat) VALUES ("RSS di Economia - ANSA.it", "http://www.ansa.it/sito/notizie/economia/economia_rss.xml", "5");

INSERT INTO Feeds (f_name,url,default_cat) VALUES ("Gossipblog", "http://feeds.blogo.it/gossipblog/it?format=xml", "6");
INSERT INTO Feeds (f_name,url,default_cat) VALUES ("Solospettacolo", "http://www.solospettacolo.it/feed", "6");
INSERT INTO Feeds (f_name,url,default_cat) VALUES ("Vanityfair", "http://www.vanityfair.it/feed", "6");
INSERT INTO Feeds (f_name,url,default_cat) VALUES ("Gossip", "http://feeds.feedburner.com/gossipnews/news?format=xml", "6");

INSERT INTO Feeds (f_name,url,default_cat) VALUES ("Frizzifrizzi", "http://feeds.feedburner.com/frizzifrizzi", "7");
INSERT INTO Feeds (f_name,url,default_cat) VALUES ("Fashionblog", "view-source:http://feeds.blogo.it/fashionblog/it", "7");
INSERT INTO Feeds (f_name,url,default_cat) VALUES ("Style.it", "http://www.style.it/feed.aspx", "7");
INSERT INTO Feeds (f_name,url,default_cat) VALUES ("ClioMakeUp Blog", "http://blog.cliomakeup.com/feed/", "7");

INSERT INTO Feeds (f_name,url,default_cat) VALUES ("Le Scienze", "http://www.lescienze.it/rss/all/rss2.0.xml", "8");
INSERT INTO Feeds (f_name,url,default_cat) VALUES ("Oggi Scienza", "http://oggiscienza.it/feed/", "8");
INSERT INTO Feeds (f_name,url,default_cat) VALUES ("National Geographic Italia", "http://www.nationalgeographic.it/rss/scienza/rss2.0.xml", "8");
INSERT INTO Feeds (f_name,url,default_cat) VALUES ("Il Disinformatico", "http://feeds.feedburner.com/Disinformatico?format=xml", "8");

INSERT INTO Feeds (f_name,url,default_cat) VALUES ("Ninja Marketing", "http://www.ninjamarketing.it/feed/", "9");
INSERT INTO Feeds (f_name,url,default_cat) VALUES ("Web in fermento", "http://feeds.feedburner.com/WebInFermento?format=xml", "9");
INSERT INTO Feeds (f_name,url,default_cat) VALUES ("Vincos Blog", "http://vincos.it/feed/", "9");
INSERT INTO Feeds (f_name,url,default_cat) VALUES ("Mashable", "http://feeds.mashable.com/Mashable?format=xml", "9");

INSERT INTO Feeds (f_name,url,default_cat) VALUES ("Comicsblog.it", "http://feeds.blogo.it/comicsblog/it?format=xml", "10");
INSERT INTO Feeds (f_name,url,default_cat) VALUES ("Makkox.it", "http://makkox.it/feed/", "10");
INSERT INTO Feeds (f_name,url,default_cat) VALUES ("Zerocalcare.it", "http://feeds.feedburner.com/Zerocalcareit?format=xml", "10");
INSERT INTO Feeds (f_name,url,default_cat) VALUES ("Panini Comics", "http://www.paninicomics.it/PortletCMS/servlet/RSSservlet?idrss=5", "10");

INSERT INTO Feeds (f_name,url,default_cat) VALUES ("Travelblog.it", "http://feeds.blogo.it/travelblog/it", "11");
INSERT INTO Feeds (f_name,url,default_cat) VALUES ("Viaggio Vero", "http://feeds.feedburner.com/viaggiovero?format=xml", "11");
INSERT INTO Feeds (f_name,url,default_cat) VALUES ("Viaggi24", "http://www.viaggi24.ilsole24ore.com/rss/", "11");
INSERT INTO Feeds (f_name,url,default_cat) VALUES ("PiratinViaggio.it", "http://www.piratinviaggio.it/feed", "11");

INSERT INTO Feeds (f_name,url,default_cat) VALUES ("Cineblog.it", "http://feeds.blogo.it/cineblog/it", "12");
INSERT INTO Feeds (f_name,url,default_cat) VALUES ("Viva Cinema", "http://www.vivacinema.it/feed/feed.rss", "12");
INSERT INTO Feeds (f_name,url,default_cat) VALUES ("Fantascienza.com", "http://rss.delosnetwork.it/fantascienza.com/home.rss", "12");
INSERT INTO Feeds (f_name,url,default_cat) VALUES ("BadTaste.it", "http://www.badtaste.it/feed/", "12");

# create new user for the db
GRANT ALL ON `RssAggregator`.* TO 'rss'@'localhost' IDENTIFIED BY 'wp_rss15';
