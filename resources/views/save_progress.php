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
        $stmt4->execute();
    } else {
        $stmt5 = $conn->prepare("INSERT INTO client_progress (client_id, onboard, presprint, sprint, client_view, sprint_week_focus) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt5->bind_param("issssi", $client_id, $onboard, $presprint, $sprint, $client_view, $sprint_week_focus);
        $stmt5->execute();
    }

    // Muat ulang data
    $stmt2->execute(); $res2 = $stmt2->get_result(); $progress_data = $res2->fetch_assoc();
    $db_client_view_saved = $progress_data['client_view'] ?? 'none'; 
    $sprint_week_focus = $progress_data['sprint_week_focus'] ?? 1;
    $onboard_data   = $progress_data ? json_decode($progress_data['onboard'], true)   : [];
    $presprint_data = $progress_data ? json_decode($progress_data['presprint'], true) : [];
    $sprint_data    = $progress_data ? json_decode($progress_data['sprint'], true)    : [];
    
    $display_success = '<div class="success">Progress berhasil disimpan.</div>';
}

// Menampilkan pesan sukses / error dari URL (GET)
$view = $_GET['view'] ?? 'onboard';
$display_success = '';
 

?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Progress Klien - <?= htmlspecialchars($client['name']) ?></title>
<style>
/* CSS */
body { font-family: Arial, sans-serif; margin:20px; background:#f5f5f5; }
.container { background:#fff; padding:20px; border-radius:8px; box-shadow:0 0 5px rgba(0,0,0,0.1); }
h2 { margin-top:0; border-bottom:2px solid #007bff; padding-bottom:5px; }
table { border-collapse:collapse; width:100%; margin-bottom:20px; }
th,td { border:1px solid #ddd; padding:8px; text-align:left; }
th { background:#007bff; color:#fff; }
tr:nth-child(even){background:#fafafa;}
input[type="date"],select { padding:6px; width:95%; }
textarea { width: 98%; padding: 6px; box-sizing: border-box; resize: vertical; min-height: 50px; } 
button { background:#28a745; color:#fff; border:none; padding:10px 16px; border-radius:4px; cursor:pointer; }
button:hover { background:#1e7e34; }
.filter a { margin-right:10px; text-decoration:none; color:#007bff; font-weight:bold; }
.filter a.active { text-decoration:underline; }
.success { background:#d1e7dd; color:#0f5132; padding:10px; border-radius:4px; margin-bottom:10px; } /* Gaya pesan sukses */
.error { background:#f8d7da; color:#842029; padding:10px; border-radius:4px; margin-bottom:10px; }
.archive-btn { background: #e9c46a; color: #333; }
.archive-btn:hover { background: #d4ac57; }
</style>
</head>
<body>
<div class="container">
    <h2>Progress Klien: <?= htmlspecialchars($client['name']) ?></h2>
    <a href="admin_dashboard.php">← Kembali ke Dashboard</a>
    
    <?= $display_success ?> 
    
    <div class="filter">
        <a href="?client_id=<?= $client_id ?>&view=onboard" class="<?= $view=='onboard'?'active':'' ?>">On Board</a>
        <a href="?client_id=<?= $client_id ?>&view=presprint" class="<?= $view=='presprint'?'active':'' ?>">Pre-Sprint</a>
        <a href="?client_id=<?= $client_id ?>&view=sprint" class="<?= $view=='sprint'?'active':'' ?>">Sprint Week</a>
    </div>

    <form method="post">
        
        <h3 style="margin-top:20px;">Fokus Tampilan Klien</h3>
        <select name="client_view" style="width: 300px; margin-bottom: 20px;">
            <option value="none" <?= $db_client_view_saved=='none'?'selected':'' ?>>Admin Belum Menentukan</option>
            <option value="onboard" <?= $db_client_view_saved=='onboard'?'selected':'' ?>>On Board</option>
            <option value="presprint" <?= $db_client_view_saved=='presprint'?'selected':'' ?>>Pre-Sprint</option>
            <option value="sprint" <?= $db_client_view_saved=='sprint'?'selected':'' ?>>Sprint Week</option>
        </select>
        
        <div id="sprint-week-focus" style="display: <?= $db_client_view_saved=='sprint'?'block':'none' ?>; margin-bottom: 20px;">
            <h3>Fokus Tampilan Sprint Week</h3>
            <select name="sprint_week_focus" style="width: 300px;">
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
        <h4 style="margin-top: 20px; color: #007bff;">Week <?= $week+1 ?></h4>
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

    <!-- Admin Notes Section -->
    <div style="margin-top: 40px; padding: 20px; background: rgba(255,255,255,0.02); border-radius: 8px; border: 1px solid rgba(255,255,255,0.1);">
        <h3 style="color: #fff; margin-bottom: 20px; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 10px;">Notes / Catatan</h3>
        
        <!-- Notes List -->
        <div style="max-height: 300px; overflow-y: auto; margin-bottom: 20px; padding: 10px; background: rgba(0,0,0,0.2); border-radius: 4px;">
            <?php if (!empty($admin_notes)): ?>
                <?php foreach ($admin_notes as $note): ?>
                    <div style="background: rgba(255,255,255,0.03); padding: 12px 15px; margin-bottom: 10px; border-radius: 6px; border-left: 3px solid <?= $note['created_by'] === 'admin' ? '#00ff88' : '#fff' ?>;">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                            <span style="display: inline-block; padding: 2px 8px; border-radius: 3px; font-size: 11px; font-weight: 600; background: <?= $note['created_by'] === 'admin' ? '#00ff88' : '#fff' ?>; color: #000;">
                                <?= $note['created_by'] === 'admin' ? '👤 ADMIN' : '👤 CLIENT' ?>
                            </span>
                            <span style="color: #888; font-size: 12px;"><?= date('d M Y H:i', strtotime($note['created_at'])) ?></span>
                        </div>
                        <div style="color: #ddd; line-height: 1.5; font-size: 14px;"><?= nl2br(htmlspecialchars($note['note_text'])) ?></div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color: #888; text-align: center; padding: 20px;">Belum ada catatan</p>
            <?php endif; ?>
        </div>

        <!-- Add Note Form -->
        <form method="post" style="margin: 0;">
            <textarea name="admin_note_text" placeholder="Tulis catatan untuk client..." required
                style="width: 100%; min-height: 80px; padding: 12px; background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.2); border-radius: 6px; color: #fff; font-family: 'Poppins', sans-serif; font-size: 14px; resize: vertical; box-sizing: border-box;"></textarea>
            <button type="submit" name="save_admin_note" value="1" 
                style="margin-top: 10px; padding: 10px 20px; background: #00ff88; color: #000; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; font-family: 'Poppins', sans-serif; font-size: 14px;">
                Kirim Catatan
            </button>
        </form>
    </div>
    
</div>
</body>
</html>