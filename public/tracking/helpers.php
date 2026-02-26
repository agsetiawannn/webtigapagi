<?php
/**
 * Helper Functions untuk Tracking System
 * Berisi reusable functions untuk mengurangi duplikasi code
 */

/**
 * Redirect ke halaman tertentu
 * 
 * @param string $location URL tujuan
 * @return void
 */
function redirectTo($location) {
    header("Location: $location");
    exit();
}

/**
 * Cek apakah user sudah login sebagai admin
 * Redirect ke login page jika belum
 * 
 * @return void
 */
function requireAdminAuth() {
    if (!isset($_SESSION['admin'])) {
        redirectTo('admin_login.php');
    }
}

/**
 * Cek apakah client sudah login
 * Redirect ke login page jika belum
 * 
 * @return void
 */
function requireClientAuth() {
    if (!isset($_SESSION['client_id'])) {
        redirectTo('login.php');
    }
}

/**
 * Sanitize input string
 * 
 * @param string $input Input yang akan di-sanitize
 * @return string Safe string
 */
function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

/**
 * Validasi email format
 * 
 * @param string $email Email yang akan divalidasi
 * @return bool True jika valid
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validasi bahwa string tidak kosong
 * 
 * @param string $value Value yang akan dicek
 * @return bool True jika tidak kosong
 */
function isNotEmpty($value) {
    return !empty(trim($value));
}

/**
 * Format tanggal ke format Indonesia
 * 
 * @param string $date Tanggal dalam format database
 * @return string Tanggal terformat
 */
function formatDate($date) {
    if (empty($date)) {
        return '-';
    }
    return date('d M Y', strtotime($date));
}

/**
 * Format datetime ke format Indonesia dengan jam
 * 
 * @param string $datetime Datetime dalam format database
 * @return string Datetime terformat
 */
function formatDateTime($datetime) {
    if (empty($datetime)) {
        return '-';
    }
    return date('d M Y H:i', strtotime($datetime));
}

/**
 * Cek apakah client dengan email tertentu sudah exist
 * 
 * @param mysqli $conn Database connection
 * @param string $email Email client
 * @param int|null $excludeId ID yang dikecualikan (untuk update)
 * @return bool True jika exist
 */
function isClientEmailExists($conn, $email, $excludeId = null) {
    if ($excludeId) {
        $stmt = $conn->prepare("SELECT id FROM clients WHERE email = ? AND id != ?");
        $stmt->bind_param("si", $email, $excludeId);
    } else {
        $stmt = $conn->prepare("SELECT id FROM clients WHERE email = ?");
        $stmt->bind_param("s", $email);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    $exists = $result->num_rows > 0;
    $stmt->close();
    
    return $exists;
}

/**
 * Fetch client by ID
 * 
 * @param mysqli $conn Database connection
 * @param int $clientId Client ID
 * @return array|null Client data atau null jika tidak ditemukan
 */
function getClientById($conn, $clientId) {
    $stmt = $conn->prepare("SELECT id, name, email, status FROM clients WHERE id = ?");
    $stmt->bind_param("i", $clientId);
    $stmt->execute();
    $result = $stmt->get_result();
    $client = $result->fetch_assoc();
    $stmt->close();
    
    return $client;
}

/**
 * Fetch all clients
 * 
 * @param mysqli $conn Database connection
 * @param string $status Filter by status (optional)
 * @return mysqli_result
 */
function getAllClients($conn, $status = null) {
    if ($status) {
        $stmt = $conn->prepare("SELECT id, name, email, status FROM clients WHERE status = ? ORDER BY name ASC");
        $stmt->bind_param("s", $status);
        $stmt->execute();
        return $stmt->get_result();
    }
    
    return $conn->query("SELECT id, name, email, status FROM clients ORDER BY name ASC");
}

/**
 * Insert new client
 * 
 * @param mysqli $conn Database connection
 * @param string $name Client name
 * @param string $email Client email
 * @return array ['success' => bool, 'message' => string, 'id' => int|null]
 */
function createClient($conn, $name, $email) {
    // Validasi input
    if (!isNotEmpty($name)) {
        return ['success' => false, 'message' => 'Nama tidak boleh kosong'];
    }
    
    if (!isValidEmail($email)) {
        return ['success' => false, 'message' => 'Format email tidak valid'];
    }
    
    // Cek duplikasi
    if (isClientEmailExists($conn, $email)) {
        return ['success' => false, 'message' => 'Email sudah digunakan klien lain'];
    }
    
    // Insert ke database
    $stmt = $conn->prepare("INSERT INTO clients (name, email, status) VALUES (?, ?, 'active')");
    $stmt->bind_param("ss", $name, $email);
    
    if ($stmt->execute()) {
        $clientId = $conn->insert_id;
        $stmt->close();
        return ['success' => true, 'message' => 'Klien berhasil ditambahkan', 'id' => $clientId];
    }
    
    $error = $stmt->error;
    $stmt->close();
    return ['success' => false, 'message' => "Gagal menambahkan klien: $error"];
}

/**
 * Delete client dan semua data terkait
 * 
 * @param mysqli $conn Database connection
 * @param int $clientId Client ID
 * @return array ['success' => bool, 'message' => string]
 */
function deleteClient($conn, $clientId) {
    if ($clientId <= 0) {
        return ['success' => false, 'message' => 'ID klien tidak valid'];
    }
    
    // Begin transaction
    $conn->begin_transaction();
    
    try {
        // Delete client progress
        $stmt1 = $conn->prepare("DELETE FROM client_progress WHERE client_id = ?");
        $stmt1->bind_param("i", $clientId);
        $stmt1->execute();
        $stmt1->close();
        
        // Delete client notes
        $stmt2 = $conn->prepare("DELETE FROM client_notes WHERE client_id = ?");
        $stmt2->bind_param("i", $clientId);
        $stmt2->execute();
        $stmt2->close();
        
        // Delete client
        $stmt3 = $conn->prepare("DELETE FROM clients WHERE id = ?");
        $stmt3->bind_param("i", $clientId);
        $stmt3->execute();
        $stmt3->close();
        
        // Commit transaction
        $conn->commit();
        
        return ['success' => true, 'message' => "Klien dan semua data terkait berhasil dihapus"];
        
    } catch (Exception $e) {
        // Rollback on error
        $conn->rollback();
        return ['success' => false, 'message' => "Gagal menghapus klien: " . $e->getMessage()];
    }
}

/**
 * Get client progress data
 * 
 * @param mysqli $conn Database connection
 * @param int $clientId Client ID
 * @return array|null Progress data
 */
function getClientProgress($conn, $clientId) {
    $stmt = $conn->prepare("SELECT onboard, presprint, sprint, client_view, sprint_week_focus FROM client_progress WHERE client_id = ?");
    $stmt->bind_param("i", $clientId);
    $stmt->execute();
    $result = $stmt->get_result();
    $progress = $result->fetch_assoc();
    $stmt->close();
    
    return $progress;
}

/**
 * Save or update client progress
 * 
 * @param mysqli $conn Database connection
 * @param int $clientId Client ID
 * @param string $onboard JSON encoded onboard data
 * @param string $presprint JSON encoded presprint data
 * @param string $sprint JSON encoded sprint data
 * @param string $clientView Client view preference
 * @param int $sprintWeekFocus Sprint week focus
 * @return array ['success' => bool, 'message' => string]
 */
function saveClientProgress($conn, $clientId, $onboard, $presprint, $sprint, $clientView, $sprintWeekFocus) {
    // Check if progress exists
    $stmt = $conn->prepare("SELECT id FROM client_progress WHERE client_id = ?");
    $stmt->bind_param("i", $clientId);
    $stmt->execute();
    $exists = $stmt->get_result()->num_rows > 0;
    $stmt->close();
    
    if ($exists) {
        // Update existing progress
        $stmt = $conn->prepare("UPDATE client_progress SET onboard=?, presprint=?, sprint=?, client_view=?, sprint_week_focus=?, updated_at=NOW() WHERE client_id=?");
        $stmt->bind_param("ssssii", $onboard, $presprint, $sprint, $clientView, $sprintWeekFocus, $clientId);
    } else {
        // Insert new progress
        $stmt = $conn->prepare("INSERT INTO client_progress (client_id, onboard, presprint, sprint, client_view, sprint_week_focus) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssi", $clientId, $onboard, $presprint, $sprint, $clientView, $sprintWeekFocus);
    }
    
    if ($stmt->execute()) {
        $stmt->close();
        return ['success' => true, 'message' => 'Progress berhasil disimpan'];
    }
    
    $error = $stmt->error;
    $stmt->close();
    return ['success' => false, 'message' => "Gagal menyimpan progress: $error"];
}

/**
 * Get client notes
 * 
 * @param mysqli $conn Database connection
 * @param int $clientId Client ID
 * @return array Array of notes
 */
function getClientNotes($conn, $clientId) {
    $stmt = $conn->prepare("SELECT note_text, created_by, created_at FROM client_notes WHERE client_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $clientId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $notes = [];
    while ($row = $result->fetch_assoc()) {
        $notes[] = $row;
    }
    
    $stmt->close();
    return $notes;
}

/**
 * Add note for client
 * 
 * @param mysqli $conn Database connection
 * @param int $clientId Client ID
 * @param string $noteText Note content
 * @param string $createdBy 'admin' or 'client'
 * @return array ['success' => bool, 'message' => string]
 */
function addClientNote($conn, $clientId, $noteText, $createdBy = 'admin') {
    if (!isNotEmpty($noteText)) {
        return ['success' => false, 'message' => 'Catatan tidak boleh kosong'];
    }
    
    $stmt = $conn->prepare("INSERT INTO client_notes (client_id, note_text, created_by) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $clientId, $noteText, $createdBy);
    
    if ($stmt->execute()) {
        $stmt->close();
        return ['success' => true, 'message' => 'Catatan berhasil ditambahkan'];
    }
    
    $error = $stmt->error;
    $stmt->close();
    return ['success' => false, 'message' => "Gagal menambahkan catatan: $error"];
}

/**
 * Render success message HTML
 * 
 * @param string $message Message to display
 * @return string HTML string
 */
function renderSuccessMessage($message) {
    if (empty($message)) return '';
    return '<div class="msg success">' . sanitizeInput($message) . '</div>';
}

/**
 * Render error message HTML
 * 
 * @param string $message Message to display
 * @return string HTML string
 */
function renderErrorMessage($message) {
    if (empty($message)) return '';
    return '<div class="msg error">' . sanitizeInput($message) . '</div>';
}
