DROP TABLE IF EXISTS vendor;
CREATE TABLE vendor (
    id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
# basic
    name VARCHAR(128) NOT NULL,
    address TEXT NOT NULL,
    post_code VARCHAR(10) NOT NULL,
    email VARCHAR(128),
    telephone VARCHAR(25),
    vat_number VARCHAR(20),
# bank details
    bank_name VARCHAR(128),
    bank_sort_code VARCHAR(6),
    bank_account_number INTEGER,
# default venue
    venue_address TEXT,
    venue_post_code VARCHAR(10)
);

INSERT INTO vendor (name, address, post_code, email, telephone, vat_number, bank_name, bank_sort_code, bank_account_number, venue_address, venue_post_code) VALUES ("somevendor", "someaddress", "somepcode", "some@", "01557 870337", NULL, NULL, NULL, NULL, "somevenueaddress", "somevenuepostcode");

DROP TABLE IF EXISTS event;
CREATE TABLE event (
    id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
# basic
    title VARCHAR(128) NOT NULL,
    start_date VARCHAR(128) NOT NULL,
    address TEXT NOT NULL,
    post_code VARCHAR(10) NOT NULL,
    web_link VARCHAR(128),
    max_tickets INTEGER,
    ticket_text TEXT,
    ticket_logo_path VARCHAR(128)
);

INSERT INTO event (title, start_date, address, post_code, web_link) VALUES ("sometitle", "somestartdate", "someaddress", "somepostcode", "somelink");
