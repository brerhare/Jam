DROP TABLE IF EXISTS ticket;
CREATE TABLE ticket (
    id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    orderNum VARCHAR(255),
    ticketNumber VARCHAR(255),
    scanTimestamp VARCHAR(255)
);

