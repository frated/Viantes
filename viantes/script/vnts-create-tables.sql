
-- La colonna status ha la seguente semantica 0 = da_confermare; 1 = confermato; 2 = scaduto;
-- N.B. Verificare la lunghezza della folder di upload dei file in ogni ambiente affinche' non superi i 40 
-- (60 max file length, 100 dimensione colonna ==> il percorso dove sono salvati i file di copertina deve essere al max 40 )
CREATE TABLE USER (
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, 
   	email VARCHAR(50) NOT NULL UNIQUE, 
	pwd VARCHAR(50) NOT NULL,
	name VARCHAR(20) NOT NULL UNIQUE,
	fwdCode VARCHAR(60) NOT NULL,
	dtIns TIMESTAMP DEFAULT NOW(),
	dtLastMod TIMESTAMP,
	coverFileName VARCHAR(100) DEFAULT '/viantes/pvt/img/profile/profile_128.png',
	bckCoverFileName VARCHAR(100) DEFAULT '/viantes/pvt/img/profile/bckCoverProfile.png',
	status int DEFAULT 0 
);

CREATE TABLE USER_REGISTRY(
	usrId INT NOT NULL,
	firstName VARCHAR(60),
	lastName VARCHAR(60),
	mobileNum VARCHAR(20),
	gender TINYINT,
	dateOfBirth DATE,
	city VARCHAR(60),
	postcode VARCHAR(10),
	country VARCHAR(60),
	CONSTRAINT FOREIGN KEY (usrId) REFERENCES USER(id)
);

CREATE TABLE USR_ATTACH(
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	usrId INT NOT NULL,
	dataFile BLOB NOT NULL,
	filePath  VARCHAR(60),
	fileName VARCHAR(60) NOT NULL,
	dt_ins TIMESTAMP DEFAULT NOW( ),
	CONSTRAINT FOREIGN KEY (usrId) REFERENCES USER(id)
);

CREATE TABLE LANG(
	langCode VARCHAR(2) NOT NULL PRIMARY KEY ,
	langDesc VARCHAR(35)
);

CREATE TABLE SETTING_USER(
	usrId INT NOT NULL,
	langCode VARCHAR(2) NOT NULL,
	profileType TINYINT NOT NULL DEFAULT 0,
	CONSTRAINT FOREIGN KEY (usrId) REFERENCES USER(id),
	CONSTRAINT FOREIGN KEY (langCode) REFERENCES LANG(langCode)
);

CREATE TABLE COUNTRY(
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	langCode VARCHAR(2) NOT NULL,
	country VARCHAR(60) NOT NULL,
	CONSTRAINT FOREIGN KEY (langCode) REFERENCES LANG(langCode),
	CONSTRAINT UNIQUE KEY (country, langCode)
);


---------------------------------------------------
---------- CATEGORY REVIEW ------------------------
---------------------------------------------------
CREATE TABLE CATEGORY_REVIEW_MAIN(
	id INT NOT NULL PRIMARY KEY,
	categoryMain VARCHAR(40) NOT NULL UNIQUE,
	langCode VARCHAR(2) NOT NULL,
	CONSTRAINT FOREIGN KEY (langCode) REFERENCES LANG(langCode),
	CONSTRAINT UNIQUE KEY (categoryMain, langCode)
);

CREATE TABLE CATEGORY_REVIEW(
	id INT NOT NULL PRIMARY KEY,
	idCategoryMain INT NOT NULL,
	langCode VARCHAR(2) NOT NULL,
	category VARCHAR(40) NOT NULL,
	CONSTRAINT FOREIGN KEY (idCategoryMain) REFERENCES CATEGORY_REVIEW_MAIN(id),
	CONSTRAINT FOREIGN KEY (langCode) REFERENCES LANG(langCode),
	CONSTRAINT UNIQUE KEY (idCategoryMain, langCode, category)
);

-------------------------------------------------
------------ SITE REVIEW ------------------------
-------------------------------------------------
CREATE TABLE GEO_SITE (
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	placeId VARCHAR(60) NOT NULL,
	langCode VARCHAR(2) NOT NULL,
	lat DECIMAL(9,6),
	lng DECIMAL(9,6),
	CONSTRAINT FOREIGN KEY (langCode) REFERENCES LANG(langCode),
	CONSTRAINT UNIQUE KEY (placeId, langCode)
);

-- N.B. Verificare la lunghezza della folder di upload dei file in ogni ambiente affinche' non superi i 40 
-- (60 max file length, 100 dimensione colonna ==> il percorso dove sono salvati i file di copertina deve essere al max 40 )
CREATE TABLE SITE(
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	siteName VARCHAR(60) NOT NULL,
	countryId INT NOT NULL,
	locality VARCHAR(80) NOT NULL,
	langCode VARCHAR(2) NOT NULL,
	geoSiteId INT NOT NULL,
	CONSTRAINT FOREIGN KEY (countryId) REFERENCES COUNTRY(id),
	CONSTRAINT FOREIGN KEY (langCode) REFERENCES LANG(langCode),
	CONSTRAINT FOREIGN KEY (geoSiteId) REFERENCES GEO_SITE(id),
	CONSTRAINT UNIQUE KEY (geoSiteId, langCode)
);

-- N.B. Verificare la lunghezza della folder di upload dei file in ogni ambiente affinche' non superi i 40 
-- (60 max file length, 100 dimensione colonna ==> il percorso dove sono salvati i file di copertina deve essere al max 40 )
CREATE TABLE REVIEW(
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	usrId INT NOT NULL,
	catRevId INT NOT NULL,
	langCode VARCHAR(2) NOT NULL,
	siteId INT NOT NULL,
	descr VARCHAR(2000) NOT NULL,
	howToArrive VARCHAR(500),
	warning VARCHAR(100),
	whereToEat VARCHAR(500),
	cooking VARCHAR(500),
	whereToStay VARCHAR(500),
	myth VARCHAR(100),
	vote TINYINT NOT NULL,
	dtIns TIMESTAMP DEFAULT NOW(),
	coverFileName VARCHAR(100) NOT NULL,
	xDim INT,
	yDim INT,
	CONSTRAINT FOREIGN KEY (usrId) REFERENCES USER(id),
	CONSTRAINT FOREIGN KEY (langCode) REFERENCES LANG(langCode),
	CONSTRAINT FOREIGN KEY (siteId) REFERENCES SITE(id),
	CONSTRAINT FOREIGN KEY (catRevId) REFERENCES CATEGORY_REVIEW(id)
);

-- La colonna typ puo' valere: IMG, MOV, DOC
-- La colonna perm indica il permesso sul file (7 ALL, 4 PRT protetto: solo utenti registrati, 0 PVT: privato)
CREATE TABLE REVIEW_ATTACH(
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	reviewId INT NOT NULL,
	usrId INT NOT NULL,
	dataFile BLOB,
	filePath  VARCHAR(60),
	fileName VARCHAR(60) NOT NULL,
	dtIns TIMESTAMP DEFAULT NOW(),
	dtDel TIMESTAMP,
	typ VARCHAR(3),
	comment VARCHAR(250),
	xDim INT,
	yDim INT,
	perm int DEFAULT 7 NOT NULL,
	CONSTRAINT FOREIGN KEY (reviewId) REFERENCES REVIEW(id),
	CONSTRAINT FOREIGN KEY (usrId) REFERENCES USER(id)
);

-------------------------------------------------
------------ CITY REVIEW ------------------------
-------------------------------------------------
CREATE TABLE CITY(
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	cityName VARCHAR(60) NOT NULL,
	countryId INT NOT NULL,
	langCode VARCHAR(2) NOT NULL,
	CONSTRAINT FOREIGN KEY (countryId) REFERENCES COUNTRY(id),
	CONSTRAINT FOREIGN KEY (langCode) REFERENCES LANG(langCode),
	CONSTRAINT UNIQUE KEY (cityName, langCode)
);

CREATE TABLE CITY_REVIEW(
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	usrId INT NOT NULL,
	langCode VARCHAR(2) NOT NULL,
	cityId INT NOT NULL,
	descr VARCHAR(2000) NOT NULL,
	howToArrive VARCHAR(1000) NOT NULL,
	warning VARCHAR(100),
	whereToEat VARCHAR(500),
	cooking VARCHAR(500),
	whereToStay VARCHAR(500),
	myth VARCHAR(100),
	vote TINYINT NOT NULL,
	dtIns TIMESTAMP DEFAULT NOW(),
	coverFileName VARCHAR(100) NOT NULL,
	xDim INT,
	yDim INT,
	CONSTRAINT FOREIGN KEY (usrId) REFERENCES USER(id),
	CONSTRAINT FOREIGN KEY (langCode) REFERENCES LANG(langCode),
	CONSTRAINT FOREIGN KEY (cityId) REFERENCES CITY(id)
);

CREATE TABLE CITY_REV_ATT(
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	cityRevId INT NOT NULL,
	usrId INT NOT NULL,
	filePath  VARCHAR(60),
	fileName VARCHAR(60) NOT NULL,
	dtIns TIMESTAMP DEFAULT NOW(),
	dtDel TIMESTAMP,
	typ VARCHAR(3),
	comment VARCHAR(250),
	xDim INT,
	yDim INT,
	perm int DEFAULT 7 NOT NULL,
	CONSTRAINT FOREIGN KEY (cityRevId) REFERENCES CITY_REVIEW(id),
	CONSTRAINT FOREIGN KEY (usrId) REFERENCES USER(id)
);

CREATE TABLE REVIEW_CITY_REVIEW(
	reviewId INT NOT NULL,
	cityRevId INT NOT NULL,
	CONSTRAINT FOREIGN KEY (reviewId) REFERENCES REVIEW(id),
	CONSTRAINT FOREIGN KEY (cityRevId) REFERENCES CITY_REVIEW(id)
);

-------------------------------------------------
------------ COUNTRY REVIEW ---------------------
-------------------------------------------------
CREATE TABLE COUNTRY_REVIEW(
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	usrId INT NOT NULL,
	countryId INT NOT NULL,
	langCode VARCHAR(2) NOT NULL,
	descr VARCHAR(2000) NOT NULL,
	howToArrive VARCHAR(500) NOT NULL,
	warning VARCHAR(100),
	cooking VARCHAR(500),
	myth VARCHAR(100),
	vote TINYINT NOT NULL,
	dtIns TIMESTAMP DEFAULT NOW(),
	coverFileName VARCHAR(100) NOT NULL,
	xDim INT,
	yDim INT,
	CONSTRAINT FOREIGN KEY (usrId) REFERENCES USER(id),
	CONSTRAINT FOREIGN KEY (countryId) REFERENCES COUNTRY(id),
	CONSTRAINT FOREIGN KEY (langCode) REFERENCES LANG(langCode)
);
CREATE TABLE COUNTRY_REV_ATT(
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	countryRevId INT NOT NULL,
	usrId INT NOT NULL,
	filePath  VARCHAR(60),
	fileName VARCHAR(60) NOT NULL,
	dtIns TIMESTAMP DEFAULT NOW(),
	dtDel TIMESTAMP,
	typ VARCHAR(3),
	comment VARCHAR(250),
	xDim INT,
	yDim INT,
	perm int DEFAULT 7 NOT NULL,
	CONSTRAINT FOREIGN KEY (countryRevId) REFERENCES COUNTRY_REVIEW(id),
	CONSTRAINT FOREIGN KEY (usrId) REFERENCES USER(id)
);

CREATE TABLE CITY_REV_COUNTRY_REV(
	cityRevId INT NOT NULL,
	countryRevId INT NOT NULL,
	CONSTRAINT FOREIGN KEY (countryRevId) REFERENCES COUNTRY_REVIEW(id),
	CONSTRAINT FOREIGN KEY (cityRevId) REFERENCES CITY_REVIEW(id)
);


---------------------------------------------
------------ MESSAGE ------------------------
---------------------------------------------
-- senderStatus { 0 = bozza, 1 = inviato, -1 = cancellato da bozze, -2 = calcellato da inviato, -3 cancellato dal cestino}
-- recipientStatus { 0 = da leggere, 1 = letto, -1 = cancellato da leggere, -2 = calcellato da letto, -3 cancellato dal cestino}
CREATE TABLE MESSAGE(
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	fromUsrId INT NOT NULL,
	toUsrId INT NOT NULL,
	dtIns TIMESTAMP DEFAULT NOW(),
	subject VARCHAR(100) NOT NULL,
	message VARCHAR(2000) NOT NULL,
	senderStatus TINYINT NOT NULL DEFAULT 0,
	recipientStatus TINYINT NOT NULL DEFAULT 0,
	CONSTRAINT FOREIGN KEY (fromUsrId) REFERENCES USER(id),
	CONSTRAINT FOREIGN KEY (toUsrId) REFERENCES USER(id)
);
-- denStatus { 1 = bloccato dai messaggi,  0 = sbloccato dai messaggi  }
CREATE TABLE MSG_DEN(
	usrId INT NOT NULL,
	denUsrId INT NOT NULL,
	dtIns TIMESTAMP DEFAULT NOW(),
	denStatus TINYINT NOT NULL DEFAULT 1,
	CONSTRAINT FOREIGN KEY (usrId) REFERENCES USER(id),
	CONSTRAINT FOREIGN KEY (denUsrId) REFERENCES USER(id),
	CONSTRAINT UNIQUE KEY (usrId, denUsrId)
);

---------------------------------------------
------------ REQUEST ------------------------
---------------------------------------------
-- reqTp { 1 = login, 2 signIn, 3 recoverPwd }
CREATE TABLE REQUEST(
	reqTp INT NOT NULL,
	dtIns TIMESTAMP DEFAULT NOW(),
	ip VARCHAR(16) NOT NULL,
	userAgent VARCHAR(25) NOT NULL,
	request VARCHAR(250) NOT NULL
);

---------------------------------------------
------------ STAR SEE -----------------------
---------------------------------------------
CREATE TABLE REVIEW_STAR ( 
	usrId  INT NOT NULL, 
	revId  INT NOT NULL,
	star   TINYINT DEFAULT 0,
	see    TINYINT DEFAULT 0,
	post   VARCHAR(140) DEFAULT '',
	dtIns  TIMESTAMP default CURRENT_TIMESTAMP,
	CONSTRAINT FOREIGN KEY (usrId) REFERENCES USER(id), 
	CONSTRAINT FOREIGN KEY (revId) REFERENCES REVIEW(id),
	CONSTRAINT UNIQUE KEY (usrId, revId)
);
CREATE TABLE CITY_REV_STAR ( 
	usrId  INT NOT NULL, 
	revId  INT NOT NULL,
	star   TINYINT DEFAULT 0,
	see    TINYINT DEFAULT 0,
	post   VARCHAR(140) DEFAULT '',
	dtIns  TIMESTAMP default CURRENT_TIMESTAMP,
	CONSTRAINT FOREIGN KEY (usrId) REFERENCES USER(id), 
	CONSTRAINT FOREIGN KEY (revId) REFERENCES CITY_REVIEW(id),
	CONSTRAINT UNIQUE KEY (usrId, revId)
);
CREATE TABLE COUNTRY_REV_STAR ( 
	usrId  INT NOT NULL, 
	revId  INT NOT NULL,
	star   TINYINT DEFAULT 0,
	see    TINYINT DEFAULT 0,
	post   VARCHAR(140) DEFAULT '',
	dtIns  TIMESTAMP default CURRENT_TIMESTAMP,
	CONSTRAINT FOREIGN KEY (usrId) REFERENCES USER(id), 
	CONSTRAINT FOREIGN KEY (revId) REFERENCES COUNTRY_REVIEW(id), 
	CONSTRAINT UNIQUE KEY (usrId, revId)
);
