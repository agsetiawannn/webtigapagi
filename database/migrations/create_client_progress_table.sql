CREATE TABLE IF NOT EXISTS client_progress (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    onboard JSON DEFAULT NULL,
    presprint JSON DEFAULT NULL,
    sprint JSON DEFAULT NULL,
    client_view ENUM('none', 'onboard', 'presprint', 'sprint') DEFAULT 'none',
    sprint_week_focus INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE,
    UNIQUE KEY unique_client (client_id)
);
