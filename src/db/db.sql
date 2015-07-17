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
    image_url varchar(255) NOT NULL,
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

INSERT INTO Feeds (f_name,url,default_cat, image_url) VALUES ("Corriere", "http://xml.corriereobjects.it/rss/homepage.xml", "1", "http://www.google.com/s2/favicons?domain=www.corriere.it");
INSERT INTO Feeds (f_name,url,default_cat, image_url) VALUES ("Repubblica", "http://www.repubblica.it/rss/homepage/rss2.0.xml", "1", "http://www.google.com/s2/favicons?domain=www.repubblica.it");
INSERT INTO Feeds (f_name,url,default_cat, image_url) VALUES ("RSS di Ultima Ora - ANSA.it", "http://www.ansa.it/sito/notizie/topnews/topnews_rss.xml", "1", "http://www.google.com/s2/favicons?domain=www.ansa.it");
INSERT INTO Feeds (f_name,url,default_cat, image_url) VALUES ("Il Giornale", "http://www.ilgiornale.it/feed.xml", "1", "http://www.google.com/s2/favicons?domain=www.ilgiornale.it");

INSERT INTO Feeds (f_name,url,default_cat, image_url) VALUES ("Gizmodo", "http://feeds.gawker.com/gizmodo/full", "2", "http://www.google.com/s2/favicons?domain=www.gizmodo.com");
INSERT INTO Feeds (f_name,url,default_cat, image_url) VALUES ("Android", "http://www.androidworld.it/feed/", "2", "http://www.google.com/s2/favicons?domain=www.androidworld.it");
INSERT INTO Feeds (f_name,url,default_cat, image_url) VALUES ("The Verge", "http://www.theverge.com/rss/index.xml", "2", "http://www.google.com/s2/favicons?domain=www.theverge.com");
INSERT INTO Feeds (f_name,url,default_cat, image_url) VALUES ("News Tom's Hardware", "http://www.tomshardware.com/feeds/rss2/news.xml", "2", "http://www.google.com/s2/favicons?domain=www.tomshardware.com");

INSERT INTO Feeds (f_name,url,default_cat, image_url) VALUES ("Designerblog", "http://feeds.blogo.it/designerblog/it", "3", "http://www.google.com/s2/favicons?domain=www.designerblog.it");
INSERT INTO Feeds (f_name,url,default_cat, image_url) VALUES ("Bloggokin", "http://www.bloggokin.it/feed/", "3", "http://www.google.com/s2/favicons?domain=www.bloggokin.it");
INSERT INTO Feeds (f_name,url,default_cat, image_url) VALUES ("Designbuzz", "http://feeds.feedburner.com/Designbuzz?format=xml", "3", "http://www.google.com/s2/favicons?domain=www.designbuzz.it");

INSERT INTO Feeds (f_name,url,default_cat, image_url) VALUES ("La ciliegina sulla torta", "http://feeds.feedburner.com/LaCilieginaSullaTorta?format=xml", "4", "http://www.google.com/s2/favicons?domain=www.cilieginasullatorta.it");
INSERT INTO Feeds (f_name,url,default_cat, image_url) VALUES ("La scienza in cucina", "http://bressanini-lescienze.blogautore.espresso.repubblica.it/feed/", "4", "http://www.google.com/s2/favicons?domain=bressanini-lescienze.blogautore.espresso.repubblica.it");
INSERT INTO Feeds (f_name,url,default_cat, image_url) VALUES ("Dissapore", "http://www.dissapore.com/feed/", "4", "http://www.google.com/s2/favicons?domain=www.dissapore.com");
INSERT INTO Feeds (f_name,url,default_cat, image_url) VALUES ("Ricette di Misya", "http://feeds.feedburner.com/MimiSapore?format=xml", "4", "http://www.google.com/s2/favicons?domain=www.misya.info");

INSERT INTO Feeds (f_name,url,default_cat, image_url) VALUES ("Soldiblog", "http://feeds.blogo.it/soldiblog/it", "5", "http://www.google.com/s2/favicons?domain=www.soldiblog.it");
INSERT INTO Feeds (f_name,url,default_cat, image_url) VALUES ("Finanzablog", "http://feeds.blogo.it/finanzablog/it", "5", "http://www.google.com/s2/favicons?domain=www.finanzablog.it");
INSERT INTO Feeds (f_name,url,default_cat, image_url) VALUES ("RSS di Economia - ANSA.it", "http://www.ansa.it/sito/notizie/economia/economia_rss.xml", "5", "http://www.google.com/s2/favicons?domain=www.ansa.it");
INSERT INTO Feeds (f_name,url,default_cat, image_url) VALUES ("Il Sole 24 ore - Macroeconomia", "http://www.ilsole24ore.com/rss/mondo/macroeconomia.xml", "5", "http://www.google.com/s2/favicons?domain=http://www.ilsole24ore.com");

INSERT INTO Feeds (f_name,url,default_cat, image_url) VALUES ("Gossipblog", "http://feeds.blogo.it/gossipblog/it?format=xml", "6", "http://www.google.com/s2/favicons?domain=www.gossipblog.it");
INSERT INTO Feeds (f_name,url,default_cat, image_url) VALUES ("Solospettacolo", "http://www.solospettacolo.it/feed", "6", "http://www.google.com/s2/favicons?domain=www.solospettacolo.it");
INSERT INTO Feeds (f_name,url,default_cat, image_url) VALUES ("Vanityfair", "http://www.vanityfair.it/feed", "6", "http://www.google.com/s2/favicons?domain=www.vanityfair.it");
INSERT INTO Feeds (f_name,url,default_cat, image_url) VALUES ("Gossip", "http://feeds.feedburner.com/gossipnews/news?format=xml", "6", "http://www.google.com/s2/favicons?domain=www.gossip.it");

INSERT INTO Feeds (f_name,url,default_cat, image_url) VALUES ("Frizzifrizzi", "http://feeds.feedburner.com/frizzifrizzi", "7", "http://www.google.com/s2/favicons?domain=www.frizzifrizzi.it");
INSERT INTO Feeds (f_name,url,default_cat, image_url) VALUES ("Fashionblog", "http://feeds.blogo.it/fashionblog/it", "7", "http://www.google.com/s2/favicons?domain=www.fashionblog.it");
INSERT INTO Feeds (f_name,url,default_cat, image_url) VALUES ("Style.it", "http://www.style.it/feed.aspx", "7", "http://www.google.com/s2/favicons?domain=www.style.it");
INSERT INTO Feeds (f_name,url,default_cat, image_url) VALUES ("ClioMakeUp Blog", "http://blog.cliomakeup.com/feed/", "7", "http://www.google.com/s2/favicons?domain=blog.cliomakeup.com");

INSERT INTO Feeds (f_name,url,default_cat, image_url) VALUES ("Le Scienze", "http://www.lescienze.it/rss/all/rss2.0.xml", "8", "http://www.google.com/s2/favicons?domain=www.lescienze.it");
INSERT INTO Feeds (f_name,url,default_cat, image_url) VALUES ("Oggi Scienza", "http://oggiscienza.it/feed/", "8", "http://www.google.com/s2/favicons?domain=oggiscienza.it");
INSERT INTO Feeds (f_name,url,default_cat, image_url) VALUES ("National Geographic Italia", "http://www.nationalgeographic.it/rss/scienza/rss2.0.xml", "8", "http://www.google.com/s2/favicons?domain=www.nationalgeographic.it");
INSERT INTO Feeds (f_name,url,default_cat, image_url) VALUES ("Il Disinformatico", "http://feeds.feedburner.com/Disinformatico?format=xml", "8", "http://www.google.com/s2/favicons?domain=attivissimo.blogspot.it");

INSERT INTO Feeds (f_name,url,default_cat, image_url) VALUES ("Ninja Marketing", "http://www.ninjamarketing.it/feed/", "9", "http://www.google.com/s2/favicons?domain=www.ninjamarketing.it");
INSERT INTO Feeds (f_name,url,default_cat, image_url) VALUES ("Web in fermento", "http://feeds.feedburner.com/WebInFermento?format=xml", "9", "http://www.google.com/s2/favicons?domain=");
INSERT INTO Feeds (f_name,url,default_cat, image_url) VALUES ("Vincos Blog", "http://vincos.it/feed/", "9", "http://www.google.com/s2/favicons?domain=vincos.it");
INSERT INTO Feeds (f_name,url,default_cat, image_url) VALUES ("Mashable", "http://feeds.mashable.com/Mashable?format=xml", "9", "http://www.google.com/s2/favicons?domain=mashable.com");

INSERT INTO Feeds (f_name,url,default_cat, image_url) VALUES ("Comicsblog.it", "http://feeds.blogo.it/comicsblog/it?format=xml", "10", "http://www.google.com/s2/favicons?domain=www.comicsblog.it");
INSERT INTO Feeds (f_name,url,default_cat, image_url) VALUES ("Makkox.it", "http://makkox.it/feed/", "10", "http://www.google.com/s2/favicons?domain=makkox.it");
INSERT INTO Feeds (f_name,url,default_cat, image_url) VALUES ("Zerocalcare.it", "http://feeds.feedburner.com/Zerocalcareit?format=xml", "10", "http://www.google.com/s2/favicons?domain=www.zerocalcare.it");
INSERT INTO Feeds (f_name,url,default_cat, image_url) VALUES ("Panini Comics", "http://www.paninicomics.it/PortletCMS/servlet/RSSservlet?idrss=5", "10", "http://www.google.com/s2/favicons?domain=www.paninicomics.it");

INSERT INTO Feeds (f_name,url,default_cat, image_url) VALUES ("Travelblog.it", "http://feeds.blogo.it/travelblog/it", "11", "http://www.google.com/s2/favicons?domain=www.travelblog.it");
INSERT INTO Feeds (f_name,url,default_cat, image_url) VALUES ("Viaggio Vero", "http://feeds.feedburner.com/viaggiovero?format=xml", "11", "http://www.google.com/s2/favicons?domain=www.viaggiovero.com");
INSERT INTO Feeds (f_name,url,default_cat, image_url) VALUES ("Viaggi24", "http://www.viaggi24.ilsole24ore.com/rss/", "11", "http://www.google.com/s2/favicons?domain=www.viaggi24.ilsole24ore.com");
INSERT INTO Feeds (f_name,url,default_cat, image_url) VALUES ("PiratinViaggio.it", "http://www.piratinviaggio.it/feed", "11", "http://www.google.com/s2/favicons?domain=www.piratinviaggio.it");

INSERT INTO Feeds (f_name,url,default_cat, image_url) VALUES ("Cineblog.it", "http://feeds.blogo.it/cineblog/it", "12", "http://www.google.com/s2/favicons?domain=www.cineblog.it");
INSERT INTO Feeds (f_name,url,default_cat, image_url) VALUES ("Viva Cinema", "http://www.vivacinema.it/feed/feed.rss", "12", "http://www.google.com/s2/favicons?domain=www.vivacinema.it");
INSERT INTO Feeds (f_name,url,default_cat, image_url) VALUES ("Fantascienza.com", "http://rss.delosnetwork.it/fantascienza.com/home.rss", "12", "http://www.google.com/s2/favicons?domain=www.fantascienza.com");
INSERT INTO Feeds (f_name,url,default_cat, image_url) VALUES ("BadTaste.it", "http://www.badtaste.it/feed/", "12", "http://www.google.com/s2/favicons?domain=www.badtaste.it");

# create new user for the db
GRANT ALL ON `RssAggregator`.* TO 'rss'@'localhost' IDENTIFIED BY 'wp_rss15';
