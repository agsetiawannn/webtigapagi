<?php
session_start();
include __DIR__ . '/db.php';

// --- Cek Login Admin ---
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

// --- Validasi ID Klien ---
$client_id = intval($_GET['client_id'] ?? 0);
if ($client_id <= 0) {
    die("Klien tidak valid.");
}

// --- Ambil Nama Klien ---
$stmt = $conn->prepare("SELECT name FROM clients WHERE id = ?");
$stmt->bind_param("i", $client_id);
$stmt->execute();
$res = $stmt->get_result();
$client = $res->fetch_assoc();
if (!$client) die("Data klien tidak ditemukan.");

// --- Ambil Progress Lama (Initial Load) ---
$stmt2 = $conn->prepare("SELECT onboard, presprint, sprint, client_view, sprint_week_focus FROM client_progress WHERE client_id = ?");
$stmt2->bind_param("i", $client_id);
$stmt2->execute();
$res2 = $stmt2->get_result();
$progress_data = $res2->fetch_assoc();

// Dekode data untuk ditampilkan di form
$db_client_view_saved = $progress_data['client_view'] ?? 'none'; 
$sprint_week_focus = $progress_data['sprint_week_focus'] ?? 1; // Default Week 1
$onboard_data   = $progress_data ? json_decode($progress_data['onboard'], true)   : [];
$presprint_data = $progress_data ? json_decode($progress_data['presprint'], true) : [];
$sprint_data    = $progress_data ? json_decode($progress_data['sprint'], true)    : [];

// --- Handle Note Submission ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_admin_note'])) {
    $note_text = trim($_POST['admin_note_text'] ?? '');
    if (!empty($note_text)) {
        $stmt_note = $conn->prepare("INSERT INTO client_notes (client_id, note_text, created_by) VALUES (?, ?, 'admin')");
        $stmt_note->bind_param("is", $client_id, $note_text);
        $stmt_note->execute();
        header("Location: " . $_SERVER['PHP_SELF'] . "?client_id=" . $client_id . "&view=" . ($_GET['view'] ?? 'onboard'));
        exit();
    }
}

// --- Load Notes ---
$stmt_notes = $conn->prepare("SELECT note_text, created_by, created_at FROM client_notes WHERE client_id = ? ORDER BY created_at DESC");
$stmt_notes->bind_param("i", $client_id);
$stmt_notes->execute();
$notes_result = $stmt_notes->get_result();
$admin_notes = [];
while ($row = $notes_result->fetch_assoc()) {
    $admin_notes[] = $row;
}


// --- Handle Simpan Progress ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['save_admin_note'])) {
    
    // --- Preserve existing data for phases not present in the POST ---
    // If the form does not submit a phase (because the UI only showed one view),
    // we must not overwrite the stored JSON with an empty array. Merge behavior:
    $existing_onboard   = $progress_data['onboard']   ?? json_encode([]);
    $existing_presprint = $progress_data['presprint'] ?? json_encode([]);
    $existing_sprint    = $progress_data['sprint']    ?? json_encode([]);

    $onboard   = isset($_POST['onboard'])   ? json_encode($_POST['onboard'])   : $existing_onboard;
    $presprint = isset($_POST['presprint']) ? json_encode($_POST['presprint']) : $existing_presprint;
    $sprint    = isset($_POST['sprint'])    ? json_encode($_POST['sprint'])    : $existing_sprint;
    $client_view = $_POST['client_view'] ?? $progress_data['client_view'] ?? 'none'; 
    $sprint_week_focus = intval($_POST['sprint_week_focus'] ?? $progress_data['sprint_week_focus'] ?? 1); 
    
    $stmt3 = $conn->prepare("SELECT id FROM client_progress WHERE client_id = ?");
    $stmt3->bind_param("i", $client_id);
    $stmt3->execute();
    $exists = $stmt3->get_result();

    if ($exists->num_rows > 0) {
        $stmt4 = $conn->prepare("UPDATE client_progress SET onboard=?, presprint=?, sprint=?, client_view=?, sprint_week_focus=?, updated_at=NOW() WHERE client_id=?");
        $stmt4->bind_param("ssssii", $onboard, $presprint, $sprint, $client_view, $sprint_week_focus, $client_id); 
        if (!$stmt4->execute()) {
            die("Error updating: " . $stmt4->error);
        }
    } else {
        $stmt5 = $conn->prepare("INSERT INTO client_progress (client_id, onboard, presprint, sprint, client_view, sprint_week_focus) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt5->bind_param("issssi", $client_id, $onboard, $presprint, $sprint, $client_view, $sprint_week_focus);
        if (!$stmt5->execute()) {
            die("Error inserting: " . $stmt5->error);
        }
    }

    // Clear any output buffering
    if (ob_get_level()) ob_end_clean();
    
    // Redirect untuk memastikan data ter-refresh
    header("Location: " . $_SERVER['PHP_SELF'] . "?client_id=" . $client_id . "&view=" . ($_GET['view'] ?? 'onboard') . "&success=1");
    exit();
}

// Menampilkan pesan sukses / error dari URL (GET)
$view = $_GET['view'] ?? 'onboard';
$display_success = '';
if (isset($_GET['success']) && $_GET['success'] == '1') {
    $display_success = '<div class="success">Progress berhasil disimpan dan akan langsung terlihat di dashboard klien.</div>';
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Progress Klien - <?= htmlspecialchars($client['name']) ?></title>
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body { 
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    min-height: 100vh;
    background-image: url('../img/Cover 1.png');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    background-attachment: fixed;
    color: #fff;
    padding: 40px 20px;
    position: relative;
}

body::before {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.75);
    z-index: 0;
}

.container { 
    max-width: 1400px;
    margin: 0 auto;
    position: relative;
    z-index: 1;
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    flex-wrap: wrap;
    gap: 20px;
}

.header h2 { 
    font-size: 42px;
    font-weight: 700;
    margin: 0;
    color: #fff;
    letter-spacing: -0.5px;
    border: none;
    padding: 0;
}

.btn-back {
    display: inline-block;
    padding: 10px 20px;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.btn-back:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: scale(1.01);
}

.filter { 
    display: flex;
    gap: 15px;
    margin-bottom: 30px;
    flex-wrap: wrap;
}

.filter a { 
    padding: 10px 20px;
    background: rgba(40, 40, 40, 0.9);
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.filter a:hover {
    background: rgba(60, 60, 60, 0.9);
    color: #fff;
}

.filter a.active { 
    background: #00ff88;
    color: #000;
    border-color: #00ff88;
}

.success { 
    background: rgba(209, 231, 221, 0.95);
    color: #0f5132;
    padding: 15px 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    border: 1px solid #badbcc;
    font-weight: 500;
}

.error { 
    background: rgba(248, 215, 218, 0.95);
    color: #842029;
    padding: 15px 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    border: 1px solid #f5c2c7;
    font-weight: 500;
}

.form-section {
    background: rgba(15, 15, 15, 0.85);
    backdrop-filter: blur(10px);
    border-radius: 16px;
    padding: 30px;
    border: 1.5px solid rgba(255, 255, 255, 0.2);
    margin-bottom: 30px;
}

h3 { 
    font-size: 24px;
    margin: 20px 0;
    color: #fff;
    font-weight: 600;
}

h4 {
    font-size: 20px;
    margin: 20px 0 15px 0;
    color: #00ff88;
    font-weight: 600;
}

table { 
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

th, td { 
    padding: 15px 20px;
    text-align: left;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

th { 
    background: rgba(0, 0, 0, 0.3);
    color: #fff;
    font-weight: 600;
    font-size: 16px;
    border-bottom: 1.5px solid rgba(255, 255, 255, 0.2);
}

td {
    color: rgba(255, 255, 255, 0.95);
    font-size: 15px;
}

tr:hover {
    background: rgba(255, 255, 255, 0.03);
}

input[type="date"], select { 
    padding: 10px 12px;
    width: 100%;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 6px;
    color: #fff;
    font-size: 14px;
    font-family: inherit;
}

input[type="date"]:focus, select:focus {
    outline: none;
    border-color: #00ff88;
    background: rgba(255, 255, 255, 0.15);
}

select option {
    background: #1a1a1a;
    color: #fff;
}

textarea { 
    width: 100%;
    padding: 12px;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 6px;
    color: #fff;
    font-family: inherit;
    font-size: 14px;
    resize: vertical;
    min-height: 80px;
}

textarea:focus {
    outline: none;
    border-color: #00ff88;
    background: rgba(255, 255, 255, 0.15);
}

textarea::placeholder {
    color: rgba(255, 255, 255, 0.5);
}

button { 
    background: #00ff88;
    color: #000;
    border: none;
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 700;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s ease;
}

button:hover { 
    background: #00cc6a;
    transform: scale(1.01);
}

.notes-section {
    background: rgba(15, 15, 15, 0.85);
    backdrop-filter: blur(10px);
    border-radius: 16px;
    padding: 30px;
    border: 1.5px solid rgba(255, 255, 255, 0.2);
    margin-top: 30px;
}

.notes-section h3 {
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    padding-bottom: 15px;
    margin-bottom: 20px;
}

.notes-list {
    max-height: 400px;
    overflow-y: auto;
    margin-bottom: 20px;
    padding: 15px;
    background: rgba(0, 0, 0, 0.3);
    border-radius: 8px;
}

.note-item {
    background: rgba(255, 255, 255, 0.05);
    padding: 15px;
    margin-bottom: 15px;
    border-radius: 8px;
    border-left: 3px solid;
}

.note-item.admin {
    border-left-color: #00ff88;
}

.note-item.client {
    border-left-color: #fff;
}

.note-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    margin-right: 10px;
}

.note-badge.admin {
    background: #00ff88;
    color: #000;
}

.note-badge.client {
    background: #fff;
    color: #000;
}

.note-date {
    color: rgba(255, 255, 255, 0.5);
    font-size: 12px;
}

.note-text {
    color: rgba(255, 255, 255, 0.9);
    line-height: 1.6;
    margin-top: 10px;
    font-size: 14px;
}

@media (max-width: 768px) {
    body {
        padding: 20px 15px;
    }
    
    .header h2 {
        font-size: 28px;
    }
    
    .form-section, .notes-section {
        padding: 20px;
    }
    
    th, td {
        padding: 10px 12px;
        font-size: 14px;
    }
    
    table {
        display: block;
        overflow-x: auto;
    }
}
</style>
</head>
<body>
<div class="container">
    <div class="header">
        <h2>Progress : <?= htmlspecialchars($client['name']) ?></h2>
        <a href="admin_dashboard.php" class="btn-back">← Kembali ke Dashboard</a>
    </div>
    
    
    <?= $display_success ?> 
    
    <div class="filter">
        <a href="?client_id=<?= $client_id ?>&view=onboard" class="<?= $view=='onboard'?'active':'' ?>">On Board</a>
        <a href="?client_id=<?= $client_id ?>&view=presprint" class="<?= $view=='presprint'?'active':'' ?>">Pre-Sprint</a>
        <a href="?client_id=<?= $client_id ?>&view=sprint" class="<?= $view=='sprint'?'active':'' ?>">Sprint Week</a>
    </div>

    <div class="form-section">
        <form method="post">
            
            <h3>Fokus Tampilan Klien</h3>
            <select name="client_view" style="width: 100%; max-width: 400px; margin-bottom: 20px;">
                <option value="none" <?= $db_client_view_saved=='none'?'selected':'' ?>>Admin Belum Menentukan</option>
                <option value="onboard" <?= $db_client_view_saved=='onboard'?'selected':'' ?>>On Board</option>
                <option value="presprint" <?= $db_client_view_saved=='presprint'?'selected':'' ?>>Pre-Sprint</option>
                <option value="sprint" <?= $db_client_view_saved=='sprint'?'selected':'' ?>>Sprint Week</option>
            </select>
            
            <div id="sprint-week-focus" style="display: <?= $db_client_view_saved=='sprint'?'block':'none' ?>; margin-bottom: 20px;">
                <h3>Fokus Tampilan Sprint Week</h3>
                <select name="sprint_week_focus" style="width: 100%; max-width: 400px;">
                    <option value="1" <?= $sprint_week_focus==1?'selected':'' ?>>Week 1</option>
                    <option value="2" <?= $sprint_week_focus==2?'selected':'' ?>>Week 2</option>
                    <option value="3" <?= $sprint_week_focus==3?'selected':'' ?>>Week 3</option>
                    <option value="4" <?= $sprint_week_focus==4?'selected':'' ?>>Week 4</option>
                </select>
            </div>
            
            <script>
            // Show/hide sprint week focus based on client_view selection
            document.querySelector('select[name="client_view"]').addEventListener('change', function() {
                document.getElementById('sprint-week-focus').style.display = this.value === 'sprint' ? 'block' : 'none';
            });
            </script>
        

        <?php if ($view=='onboard'): ?> <h3>On Board</h3>
        <table>
            <tr><th>Tahapan</th><th>Tanggal</th><th>Status</th></tr> 
            <?php
            $onboard_steps = ['Kick-off Meeting','Roadmap & Visual Concept Development','Visit Concept Development','Site Visit'];
            foreach ($onboard_steps as $i => $step):
                $saved = $onboard_data[$i] ?? ['date'=>'','status'=>'pending', 'description'=>''];
            ?>
            <tr>
                <td><?= $step ?></td>
                <td><input type="date" name="onboard[<?= $i ?>][date]" value="<?= htmlspecialchars($saved['date']) ?>"></td>
                <td>
                    <select name="onboard[<?= $i ?>][status]">
                        <option value="pending"   <?= $saved['status']=='pending'?'selected':'' ?>>Pending</option>
                        <option value="ongoing"   <?= $saved['status']=='ongoing'?'selected':'' ?>>Ongoing</option>
                        <option value="completed" <?= $saved['status']=='completed'?'selected':'' ?>>Completed</option>
                    </select>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>

        <?php if ($view=='presprint'): ?> <h3>Pre-Sprint</h3>
        <table>
            <tr><th>Tahapan</th><th>Tanggal</th><th>Status</th></tr> 
            <?php
            $pre_steps = ['Visit Concept','Site Visit Date Option','Visit Day'];
            foreach ($pre_steps as $i => $step):
                $saved = $presprint_data[$i] ?? ['date'=>'','status'=>'pending', 'description'=>''];
            ?>
            <tr>
                <td><?= $step ?></td>
                <td><input type="date" name="presprint[<?= $i ?>][date]" value="<?= htmlspecialchars($saved['date']) ?>"></td>
                <td>
                    <select name="presprint[<?= $i ?>][status]">
                        <option value="pending"   <?= $saved['status']=='pending'?'selected':'' ?>>Pending</option>
                        <option value="ongoing"   <?= $saved['status']=='ongoing'?'selected':'' ?>>Ongoing</option>
                        <option value="completed" <?= $saved['status']=='completed'?'selected':'' ?>>Completed</option>
                    </select>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>

        <?php if ($view=='sprint'): ?> <h3>Sprint Week - Input untuk 4 Minggu</h3>
        <?php 
        $phase_names = ['Content Planning', 'Content Development', 'Internal Clinic (QC)', 'Preview & Revision'];
        for ($week=0; $week<4; $week++): 
        ?>
        <h4>Week <?= $week+1 ?></h4>
        <table>
            <tr><th>Phase</th><th>Tanggal</th><th>Status</th></tr> 
            <?php foreach ($phase_names as $phase_idx => $phase_name): 
                $saved = $sprint_data[$week]['phases'][$phase_idx] ?? ['date'=>'','status'=>'pending'];
            ?>
            <tr>
                <td><?= $phase_name ?></td>
                <td><input type="date" name="sprint[<?= $week ?>][phases][<?= $phase_idx ?>][date]" value="<?= htmlspecialchars($saved['date']) ?>"></td>
                <td>
                    <select name="sprint[<?= $week ?>][phases][<?= $phase_idx ?>][status]">
                        <option value="pending"   <?= $saved['status']=='pending'?'selected':'' ?>>Pending</option>
                        <option value="ongoing"   <?= $saved['status']=='ongoing'?'selected':'' ?>>Ongoing</option>
                        <option value="completed" <?= $saved['status']=='completed'?'selected':'' ?>>Completed</option>
                    </select>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endfor; ?>
        <?php endif; ?>

        <button type="submit">Simpan Progress</button>
    </form>
    </div>

    <!-- Admin Notes Section -->
    <div class="notes-section">
        <h3>Notes / Catatan</h3>
        
        <!-- Notes List -->
        <div class="notes-list">
            <?php if (!empty($admin_notes)): ?>
                <?php foreach ($admin_notes as $note): ?>
                    <div class="note-item <?= $note['created_by'] ?>">
                        <div>
                            <span class="note-badge <?= $note['created_by'] ?>">
                                <?= $note['created_by'] === 'admin' ? '👤 ADMIN' : '👤 CLIENT' ?>
                            </span>
                            <span class="note-date"><?= date('d M Y H:i', strtotime($note['created_at'])) ?></span>
                        </div>
                        <div class="note-text"><?= nl2br(htmlspecialchars($note['note_text'])) ?></div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color: rgba(255, 255, 255, 0.5); text-align: center; padding: 30px;">Belum ada catatan</p>
            <?php endif; ?>
        </div>

        <!-- Add Note Form -->
        <form method="post">
            <textarea name="admin_note_text" placeholder="Tulis catatan untuk client..." required></textarea>
            <button type="submit" name="save_admin_note" value="1" style="margin-top: 15px;">
                Kirim Catatan
            </button>
        </form>
    </div>
    
</div>
</body>
</html>