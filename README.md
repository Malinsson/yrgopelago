# Yrgopelago
Welcome to the island in Yrgopelago, a hotel booking website for the best experiences avaliable.

![til](https://media4.giphy.com/media/v1.Y2lkPTc5MGI3NjExMWZraGJpaHhmY2M1MXRxZnhyazF5bGY4bXVuamI4d3V5eWxjZGZjZiZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/DwIdasRkFKsMg/giphy.gif)

# About
This is our final project for 2025 using all our knowlage from this first term. Using PHP, CSS, PDO and JS to craete a hotel website completely from scratch.

# Mos'Le'Harmless
Welcome to the island of Mos'le'Harmless! On where lies the hotel Mos'Le'Comfortable, a hotel where nothing is perfect and no stay is the same. We at Mos'Le'Comfortable strive to create an experience you will never forget, you may get up close and personal with the island's wildlife or go on one of our many thrilling excursions avaliable to book weather you stay a night at the hotel or not.

# **INSTRUCTIONS TO BUILD DATABASE:**
Due to privacy laws so have I placed the database file in the .gitignore file so you will have to create it yourslef to make this repository work.

Instructions to create the sqlite database:

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
tier VARCHAR NOT NULL,
activity VARCHAR NOT NULL,
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