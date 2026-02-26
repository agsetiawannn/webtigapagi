<?php
/**
 * Save Progress Page
 * Admin page untuk mengedit progress klien (onboard, presprint, sprint)
 */

session_start();
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/helpers.php';

// Require admin authentication
requireAdminAuth();

// Validate client ID
$clientId = intval($_GET['client_id'] ?? 0);
if ($clientId <= 0) {
    die("Klien tidak valid.");
}

// Get client data
$client = getClientById($conn, $clientId);
if (!$client) {
    die("Data klien tidak ditemukan.");
}

// Get client progress data
$progressData = getClientProgress($conn, $clientId);

// Decode progress data for form display
$clientView = $progressData['client_view'] ?? 'none';
$sprintWeekFocus = $progressData['sprint_week_focus'] ?? 1;
$onboardData = $progressData ? json_decode($progressData['onboard'], true) : [];
$presrintData = $progressData ? json_decode($progressData['presprint'], true) : [];
$sprintData = $progressData ? json_decode($progressData['sprint'], true) : [];

// Handle: Add Note
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_admin_note'])) {
    $noteText = sanitizeInput($_POST['admin_note_text'] ?? '');
    $result = addClientNote($conn, $clientId, $noteText, 'admin');
    
    if ($result['success']) {
        $view = $_GET['view'] ?? 'onboard';
        redirectTo("save_progress.php?client_id=$clientId&view=$view&note_success=1");
    }
}

// Get client notes
$clientNotes = getClientNotes($conn, $clientId);

// Handle: Save Progress
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['save_admin_note'])) {
    
    // Preserve existing data for phases not in POST
    // (form only shows one view at a time)
    $existingOnboard = $progressData['onboard'] ?? json_encode([]);
    $existingPresprint = $progressData['presprint'] ?? json_encode([]);
    $existingSprint = $progressData['sprint'] ?? json_encode([]);

    $onboard = isset($_POST['onboard']) ? json_encode($_POST['onboard']) : $existingOnboard;
    $presprint = isset($_POST['presprint']) ? json_encode($_POST['presprint']) : $existingPresprint;
    $sprint = isset($_POST['sprint']) ? json_encode($_POST['sprint']) : $existingSprint;
    $clientView = $_POST['client_view'] ?? $progressData['client_view'] ?? 'none';
    $sprintWeekFocus = intval($_POST['sprint_week_focus'] ?? $progressData['sprint_week_focus'] ?? 1);
    
    // Save progress
    $result = saveClientProgress($conn, $clientId, $onboard, $presprint, $sprint, $clientView, $sprintWeekFocus);
    
    if ($result['success']) {
        // Clear output buffer
        if (ob_get_level()) ob_end_clean();
        
        // Redirect to refresh data
        $view = $_GET['view'] ?? 'onboard';
        redirectTo("save_progress.php?client_id=$clientId&view=$view&success=1");
    } else {
        die("Error: " . $result['message']);
    }
}

// Get current view
$view = $_GET['view'] ?? 'onboard';

// Display success messages
$displaySuccess = '';
if (isset($_GET['success'])) {
    $displaySuccess = renderSuccessMessage('Progress berhasil disimpan dan akan langsung terlihat di dashboard klien.');
}
if (isset($_GET['note_success'])) {
    $displaySuccess .= renderSuccessMessage('Catatan berhasil ditambahkan.');
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Progress Klien - <?= htmlspecialchars($client['name']) ?></title>
<link rel="stylesheet" href="../css/tracking/save_progress.css">
</head>
<body>
<div class="container">
    <div class="header">
        <h2>Progress : <?= sanitizeInput($client['name']) ?></h2>
        <a href="admin_dashboard.php" class="btn-back">← Kembali ke Dashboard</a>
    </div>
    
    <?= $displaySuccess ?> 
    
    <div class="filter">
        <a href="?client_id=<?= $clientId ?>&view=onboard" class="<?= $view=='onboard'?'active':'' ?>">On Board</a>
        <a href="?client_id=<?= $clientId ?>&view=presprint" class="<?= $view=='presprint'?'active':'' ?>">Pre-Sprint</a>
        <a href="?client_id=<?= $clientId ?>&view=sprint" class="<?= $view=='sprint'?'active':'' ?>">Sprint Week</a>
    </div>

    <div class="form-section">
        <form method="post">
            
            <h3>Fokus Tampilan Klien</h3>
            <select name="client_view" style="width: 100%; max-width: 400px; margin-bottom: 20px;">
                <option value="none" <?= $clientView=='none'?'selected':'' ?>>Admin Belum Menentukan</option>
                <option value="onboard" <?= $clientView=='onboard'?'selected':'' ?>>On Board</option>
                <option value="presprint" <?= $clientView=='presprint'?'selected':'' ?>>Pre-Sprint</option>
                <option value="sprint" <?= $clientView=='sprint'?'selected':'' ?>>Sprint Week</option>
            </select>
            
            <div id="sprint-week-focus" style="display: <?= $clientView=='sprint'?'block':'none' ?>; margin-bottom: 20px;">
                <h3>Fokus Tampilan Sprint Week</h3>
                <select name="sprint_week_focus" style="width: 100%; max-width: 400px;">
                    <option value="1" <?= $sprintWeekFocus==1?'selected':'' ?>>Week 1</option>
                    <option value="2" <?= $sprintWeekFocus==2?'selected':'' ?>>Week 2</option>
                    <option value="3" <?= $sprintWeekFocus==3?'selected':'' ?>>Week 3</option>
                    <option value="4" <?= $sprintWeekFocus==4?'selected':'' ?>>Week 4</option>
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
            $onboardSteps = ['Kick-off Meeting','Roadmap & Visual Concept Development','Visit Concept Development','Site Visit'];
            foreach ($onboardSteps as $i => $step):
                $saved = $onboardData[$i] ?? ['date'=>'','status'=>'pending', 'description'=>''];
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
            $preSteps = ['Visit Concept','Site Visit Date Option','Visit Day'];
            foreach ($preSteps as $i => $step):
                $saved = $presrintData[$i] ?? ['date'=>'','status'=>'pending', 'description'=>''];
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
        $phaseNames = ['Content Planning', 'Content Development', 'Internal Clinic (QC)', 'Preview & Revision'];
        for ($week=0; $week<4; $week++): 
        ?>
        <h4>Week <?= $week+1 ?></h4>
        <table>
            <tr><th>Phase</th><th>Tanggal</th><th>Status</th></tr> 
            <?php foreach ($phaseNames as $phaseIdx => $phaseName): 
                $saved = $sprintData[$week]['phases'][$phaseIdx] ?? ['date'=>'','status'=>'pending'];
            ?>
            <tr>
                <td><?= $phaseName ?></td>
                <td><input type="date" name="sprint[<?= $week ?>][phases][<?= $phaseIdx ?>][date]" value="<?= htmlspecialchars($saved['date']) ?>"></td>
                <td>
                    <select name="sprint[<?= $week ?>][phases][<?= $phaseIdx ?>][status]">
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
            <?php if (!empty($clientNotes)): ?>
                <?php foreach ($clientNotes as $note): ?>
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