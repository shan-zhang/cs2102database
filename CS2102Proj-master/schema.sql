DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS items;
DROP TABLE IF EXISTS transactions;


CREATE TABLE users (
email VARCHAR(100) PRIMARY KEY,
password VARCHAR(100) NOT NULL,
name VARCHAR(100) NOT NULL,
admin INT DEFAULT 0,
CHECK (admin = 0 OR admin = 1)
);

CREATE TABLE items (
item_id SERIAL PRIMARY KEY,
item_name VARCHAR(100) NOT NULL,
owner VARCHAR(100) REFERENCES users(email) ON DELETE CASCADE,
available INT DEFAULT 1,
fee NUMERIC DEFAULT 0,
CHECK (available = 0 OR available = 1)
);


CREATE TABLE transactions (
trans_id SERIAL PRIMARY KEY,
item_id INT REFERENCES items(item_id),
borrower VARCHAR(100) REFERENCES users(email),
borrow_date DATE,
return_date DATE
);

INSERT INTO users (email, password, name) VALUES ('abdulfatirs@gmail.com', '$2y$10$LjebVfHlAFjtw17crpLdE.foqMO.BMXjo3Dbe0Nwo4wFvHHTTB1aq', 'Abdul Fatir');
INSERT INTO users (email, password, name) VALUES ('shan_zhang@u.nus.edu', '$2y$10$LjebVfHlAFjtw17crpLdE.foqMO.BMXjo3Dbe0Nwo4wFvHHTTB1aq', 'Zhang Shan');
INSERT INTO users (email, password, name) VALUES ('smritisingh.iitr@gmail.com', '$2y$10$LjebVfHlAFjtw17crpLdE.foqMO.BMXjo3Dbe0Nwo4wFvHHTTB1aq', 'Smriti Singh');

INSERT INTO items (item_name, owner, fee) VALUES ('xbox360', 'shan_zhang@u.nus.edu', 15.8);
INSERT INTO items (item_name, owner, fee) VALUES ('ps4', 'shan_zhang@u.nus.edu', 18.8);
INSERT INTO items (item_name, owner, fee) VALUES ('book', 'shan_zhang@u.nus.edu', 5.4);
INSERT INTO items (item_name, owner, fee) VALUES ('oven', 'shan_zhang@u.nus.edu', 12.7);
INSERT INTO items (item_name, owner, fee) VALUES ('chair', 'shan_zhang@u.nus.edu', 15.4);
INSERT INTO items (item_name, owner, fee) VALUES ('xbox360', 'abdulfatirs@gmail.com', 15.8);
INSERT INTO items (item_name, owner, fee) VALUES ('ps4', 'abdulfatirs@gmail.com', 18.8);
INSERT INTO items (item_name, owner, fee) VALUES ('book', 'abdulfatirs@gmail.com', 5.4);
INSERT INTO items (item_name, owner, fee) VALUES ('oven', 'abdulfatirs@gmail.com', 12.7);
INSERT INTO items (item_name, owner, fee) VALUES ('chair', 'abdulfatirs@gmail.com', 15.4);
INSERT INTO items (item_name, owner, fee) VALUES ('xbox360', 'smritisingh.iitr@gmail.com', 15.8);
INSERT INTO items (item_name, owner, fee) VALUES ('ps4', 'smritisingh.iitr@gmail.com', 18.8);
INSERT INTO items (item_name, owner, fee) VALUES ('book', 'smritisingh.iitr@gmail.com', 5.4);
INSERT INTO items (item_name, owner, fee) VALUES ('oven', 'smritisingh.iitr@gmail.com', 12.7);
INSERT INTO items (item_name, owner, fee) VALUES ('chair', 'smritisingh.iitr@gmail.com', 15.4);

INSERT INTO transactions (item_id, borrower, borrow_date) VALUES (2, 'abdulfatirs@gmail.com', CURRENT_DATE);
UPDATE items SET available = 0 WHERE item_id = 2;
INSERT INTO transactions (item_id, borrower, borrow_date) VALUES (6, 'smritisingh.iitr@gmail.com', CURRENT_DATE);
UPDATE items SET available = 0 WHERE item_id = 6;
