-- Drop the existing table if it exists
DROP TABLE IF EXISTS bookings;

-- Create the bookings table with the new structure
CREATE TABLE bookings (
    booking_id INT AUTO_INCREMENT PRIMARY KEY,
    ticket_id VARCHAR(20) NOT NULL,
    user_id INT NOT NULL,
    event_id INT NOT NULL,
    ticket_count INT NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (event_id) REFERENCES events(event_id) ON DELETE CASCADE
); 