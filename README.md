# yrgopelago
Welcome to the island in Yrgopelago, a hotel booking website for the best experiences avaliable.

INSTRUCTIONS TO BUILD DATABASE:

CREATE TABLE IF NOT EXISTS rooms (
id INTEGER PRIMARY KEY,
room_type VARCHAR NOT NULL,
price_per_night INTEGER NOT NULL);

CREATE TABLE IF NOT EXISTS guests (
id INTEGER PRIMARY KEY,
name VARCHAR NOT NULL);

CREATE TABLE IF NOT EXISTS features (
id INTEGER PRIMARY KEY,
feature VARCHAR NOT NULL,
base_price INTEGER NOT NULL);

CREATE TABLE if not EXISTS reservations (
id INTEGER PRIMARY KEY,
guest_id INTEGER NOT NULL,
room_id INTEGER,
arrival_date VARCHAR NOT NULL,
depature_date VARCHAR,
FOREIGN KEY(guest_id) REFERENCES guests(id)
FOREIGN KEY(room_id) REFERENCES rooms(id));

CREATE TABLE IF NOT EXISTS booked_features (
id INTEGER PRIMARY KEY,
reservation_id INTEGER NOT NULL,
feature_id INTEGER NOT NULL,
price INTEGER NOT NULL,
FOREIGN KEY (reservation_id) REFERENCES reservations(id),
FOREIGN KEY (feature_id) REFERENCES features(id));

CREATE TABLE IF NOT EXISTS payments (
id INTEGER PRIMARY KEY,
reservation_id INTEGER,
total_sum INTEGER NOT NULL,
transfer_code VARCHAR NOT NULL,
payment_status VARCHAR NOT NULL,
FOREIGN KEY (reservation_id) REFERENCES reservations(id));