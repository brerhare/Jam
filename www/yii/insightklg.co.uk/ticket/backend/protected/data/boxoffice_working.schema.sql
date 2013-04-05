DROP TABLE IF EXISTS orderform;

CREATE TABLE orderform (
	ip VARCHAR(255) NOT NULL PRIMARY KEY,
# from order screen (preprocess)
	email VARCHAR(255),
	adults INTEGER,
	children INTEGER,
	telephone VARCHAR(255),
# from order screen (pre-payment)
	orderNum VARCHAR(255),
	amount INTEGER,
# from payment screen
	cardName VARCHAR(255),
	address1 VARCHAR(255),
	address2 VARCHAR(255),
	address3 VARCHAR(255),
	address4 VARCHAR(255),
	city VARCHAR(255),
	state VARCHAR(255),
	postCode VARCHAR(255),
	countryShort VARCHAR(255)
);

DROP TABLE IF EXISTS sequencenumber;
CREATE TABLE sequencenumber (
# id = 1
	id INTEGER,
# the last-used sequence numbers
	ordernumber INTEGER,
	ticketnumber INTEGER
);
INSERT INTO sequencenumber (id, ordernumber, ticketnumber) VALUES (1, 0, 0);

DROP TABLE IF EXISTS transaction;
CREATE TABLE transaction (
    id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
	timeStamp DATETIME,
# general
	ip VARCHAR(128),
# copy from orderform table
	email VARCHAR(255),
	adults INTEGER,
	children INTEGER,
    telephone VARCHAR(255),
	orderNum VARCHAR(255),
	amount INTEGER,
	cardName VARCHAR(255),
	address1 VARCHAR(255),
	address2 VARCHAR(255),
	address3 VARCHAR(255),
	address4 VARCHAR(255),
	city VARCHAR(255),
	state VARCHAR(255),
	postCode VARCHAR(255),
	countryShort VARCHAR(255),
	message VARCHAR(255)
);

