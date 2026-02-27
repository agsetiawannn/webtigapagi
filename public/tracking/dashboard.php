<?php
session_start();
include __DIR__ . '/db.php'; 

// --- Cek Login Client ---
if (!isset($_SESSION['client_id'])) {
    header("Location: login.php");
    exit();
}

$client_id = intval($_SESSION['client_id']);
$name = htmlspecialchars($_SESSION['client_name'] ?? 'Client'); 

// --- Handle Note Submission ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_note'])) {
    $note_text = trim($_POST['note_text'] ?? '');
    if (!empty($note_text)) {
        $stmt_note = $conn->prepare("INSERT INTO client_notes (client_id, note_text, created_by) VALUES (?, ?, 'client')");
        $stmt_note->bind_param("is", $client_id, $note_text);
        $stmt_note->execute();
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// --- Load Notes ---
$stmt_notes = $conn->prepare("SELECT note_text, created_by, created_at FROM client_notes WHERE client_id = ? ORDER BY created_at DESC");
$stmt_notes->bind_param("i", $client_id);
$stmt_notes->execute();
$notes_result = $stmt_notes->get_result();
$notes = [];
while ($row = $notes_result->fetch_assoc()) {
    $notes[] = $row;
} 

// --- Ambil data progres detail dan preferensi tampilan (Current Progress) ---
// Force fresh data - no cache
$stmt = $conn->prepare("
    SELECT onboard, presprint, sprint, client_view, sprint_week_focus, updated_at
    FROM client_progress
    WHERE client_id = ?
    LIMIT 1
");
$stmt->bind_param("i", $client_id);
$stmt->execute();
$res = $stmt->get_result();
$progress_data = $res->fetch_assoc();

// Debug: Log last update time (optional - comment out in production)
// if ($progress_data) error_log("Dashboard loaded - Last Update: " . ($progress_data['updated_at'] ?? 'N/A'));

// --- Logika Data PHP ---
$section_phase_title = "Proyek Progress"; 
$display_title = "Proyek Progress";
$client_view = $progress_data['client_view'] ?? 'none';
$sprint_week_focus = $progress_data['sprint_week_focus'] ?? 1; // Default Week 1
$display_data = [];
$steps = [];
$progress_message = "Progres proyek Anda akan segera diunggah. Silakan hubungi admin jika ada pertanyaan.";

if ($progress_data) {
    $onboard_data   = json_decode($progress_data['onboard'], true)   ?? [];
    $presprint_data = json_decode($progress_data['presprint'], true) ?? [];
    $sprint_data    = json_decode($progress_data['sprint'], true)    ?? [];
    
    switch ($client_view) {
        case 'onboard':
            $display_data = $onboard_data;
            $display_title = "Progress On Board";
            $section_phase_title = "On Board Phase"; 
            $steps = ['Kick-off Meeting','Roadmap & Visual Concept Development','Visit Concept Development','Site Visit'];
            break;
        case 'presprint':
            $display_data = $presprint_data;
            $display_title = "Progress Pre-Sprint";
            $section_phase_title = "Pre-Sprint Phase"; 
            $steps = ['Visit Concept','Site Visit Date Option','Visit Day'];
            break;
        case 'sprint':
            // Filter data berdasarkan sprint_week_focus (1-4)
            $week_index = $sprint_week_focus - 1; // Array index 0-3
            if (isset($sprint_data[$week_index])) {
                $display_data = [$sprint_data[$week_index]]; // Array dengan 1 elemen
            } else {
                $display_data = [];
            }
            $display_title = "Progress Sprint Week " . $sprint_week_focus;
            $section_phase_title = "Sprint Week Phase"; 
            break;
        default:
            $progress_message = "Admin belum menentukan fokus tampilan progres Anda.";
            $client_view = 'none';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Tracking - Client</title>
    <link rel="stylesheet" href="../css/tracking/dashboard.css">
</head>
<body>

<div class="container">
    
    <div class="header">
        <div class="header-left">
            <h1>Tigapagi Tracking</h1>
            <p>Hi "<?= $name ?>", here's your progres</p> 
        </div>
        <div class="header-right">
            <img src="../img/Exclude.png" alt="Tigapagi Logo" class="logo-img">
        </div>
    </div>
    
    <?php 
    if ($client_view == 'onboard' || $client_view == 'presprint'): 
    ?>
    
        <div class="progress-table-container">
            <table class="progress-table">
                <thead>
                    <tr>
                        <th style="width: 50%;">Phase</th>
                        <th style="width: 30%;">Date</th>
                        <th style="width: 20%;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    foreach ($display_data as $i => $item): 
                        $step_name = ($steps[$i] ?? htmlspecialchars($item['phase'] ?? 'Tahapan Tidak Dikenal')); 
                        $status = strtolower($item['status'] ?? 'pending');
                        $date = htmlspecialchars($item['date'] ?? '-');
                        
                        $icon_class = '';
                        if ($status === 'completed' || $status === 'complete') {
                            $icon_class = 'status-complete';
                        } elseif ($status === 'ongoing' || $status === 'progress') {
                            $icon_class = 'status-progress';
                        } else {
                            $icon_class = 'status-pending';
                        }
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($step_name) ?></td>
                        <td><?= $date ?></td>
                        <td>
                            <span class="status-icon <?= $icon_class ?>"></span> 
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="description" style="font-size: 15px; color: rgba(255, 255, 255, 0.8); margin-bottom: 25px; line-height: 1.5;">
            This is the progres of your brand in our Timeline, after this we move to Sprint Week timeline
        </div>
        
        <div class="action-buttons">
            <a href="logout.php" class="btn btn-logout">Log out</a>
        </div>
        
        <div id="notes-area" class="notes-container"> 
            <div class="notes-list" style="margin-bottom: 20px; max-height: 300px; overflow-y: auto;">
                <?php if (empty($notes)): ?>
                    <p style="color: rgba(255,255,255,0.5); font-size: 14px; text-align: center; padding: 20px;">Belum ada notes</p>
                <?php else: ?>
                    <?php foreach ($notes as $note): ?>
                        <div class="note-item" style="background: rgba(60,60,60,0.8); padding: 15px; border-radius: 10px; margin-bottom: 12px; border-left: 3px solid <?= $note['created_by'] === 'admin' ? '#00ff88' : '#fff' ?>;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                <span style="font-size: 12px; font-weight: 600; color: <?= $note['created_by'] === 'admin' ? '#00ff88' : '#fff' ?>; text-transform: uppercase;">
                                    <?= $note['created_by'] === 'admin' ? '👤 Admin' : '👤 You' ?>
                                </span>
                                <span style="font-size: 11px; color: rgba(255,255,255,0.5);">
                                    <?= date('M d, Y H:i', strtotime($note['created_at'])) ?>
                                </span>
                            </div>
                            <p style="color: rgba(255,255,255,0.85); font-size: 14px; line-height: 1.5; margin: 0; white-space: pre-wrap;"><?= htmlspecialchars($note['note_text']) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <form method="post" style="margin-top: 15px;">
                <textarea name="note_text" class="notes-textarea" placeholder="Type your note here..." required></textarea>
                <button type="submit" name="save_note" style="margin-top: 12px; padding: 10px 24px; background: #00ff88; color: #000; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 14px;">Add Note</button>
            </form>
        </div>

    <?php 
    elseif ($client_view == 'sprint'): 
    ?>
        <div class="progress-table-container">
            <table class="progress-table">
                <thead>
                    <tr>
                        <th style="width: 50%;">Week <?= $sprint_week_focus ?> Phase</th>
                        <th style="width: 30%;">Date</th>
                        <th style="width: 20%;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    // Phases untuk setiap week
                    $week_phases = [
                        'Content Planning',
                        'Content Development',
                        'Internal Clinic (QC)',
                        'Preview & Revision'
                    ];
                    
                    if (!empty($display_data)):
                        $week_data = $display_data[0]; // Data week yang dipilih
                        foreach ($week_phases as $phase_index => $phase_name):
                            $status = strtolower($week_data['phases'][$phase_index]['status'] ?? 'pending');
                            $date = htmlspecialchars($week_data['phases'][$phase_index]['date'] ?? '-');
                            
                            $icon_class = ($status === 'completed' || $status === 'complete') ? 'status-complete' : (($status === 'ongoing' || $status === 'progress') ? 'status-progress' : 'status-pending');
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($phase_name) ?></td>
                        <td><?= $date ?></td>
                        <td>
                            <span class="status-icon <?= $icon_class ?>"></span> 
                        </td>
                    </tr>
                    <?php 
                        endforeach;
                    else:
                    ?>
                    <tr>
                        <td colspan="3" style="text-align: center; color: rgba(255,255,255,0.5);">Data belum tersedia</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="description" style="font-size: 15px; color: rgba(255, 255, 255, 0.8); margin-bottom: 25px; line-height: 1.5;">
            This is the progres of your brand in our Timeline, after this we move to Sprint Week timeline
        </div>
        
        <div class="action-buttons">
            <a href="logout.php" class="btn btn-logout">Log out</a>
            <button class="btn btn-notes" onclick="toggleNotes()"> 
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
                Notes
            </button>
            <button class="btn btn-sprint-date" onclick="toggleSprintCalendar()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
                Sprint Week Date
            </button>
            <div id="sprint-calendar-area" class="sprint-calendar-container">
                <img src="../img/date.png" alt="Sprint Week Date" style="width: 100%; height: auto; border-radius: 8px;">
            </div>
        </div>
        
        <div id="notes-area" class="notes-container"> 
            <div class="notes-list" style="margin-bottom: 20px; max-height: 300px; overflow-y: auto;">
                <?php if (empty($notes)): ?>
                    <p style="color: rgba(255,255,255,0.5); font-size: 14px; text-align: center; padding: 20px;">Belum ada notes</p>
                <?php else: ?>
                    <?php foreach ($notes as $note): ?>
                        <div class="note-item" style="background: rgba(60,60,60,0.8); padding: 15px; border-radius: 10px; margin-bottom: 12px; border-left: 3px solid <?= $note['created_by'] === 'admin' ? '#00ff88' : '#fff' ?>;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                <span style="font-size: 12px; font-weight: 600; color: <?= $note['created_by'] === 'admin' ? '#00ff88' : '#fff' ?>; text-transform: uppercase;">
                                    <?= $note['created_by'] === 'admin' ? '👤 Admin' : '👤 You' ?>
                                </span>
                                <span style="font-size: 11px; color: rgba(255,255,255,0.5);">
                                    <?= date('M d, Y H:i', strtotime($note['created_at'])) ?>
                                </span>
                            </div>
                            <p style="color: rgba(255,255,255,0.85); font-size: 14px; line-height: 1.5; margin: 0; white-space: pre-wrap;"><?= htmlspecialchars($note['note_text']) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <form method="post" style="margin-top: 15px;">
                <textarea name="note_text" class="notes-textarea" placeholder="Type your note here..." required></textarea>
                <button type="submit" name="save_note" style="margin-top: 12px; padding: 10px 24px; background: #00ff88; color: #000; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 14px;">Add Note</button>
            </form>
        </div>

    <?php 
    else: 
    // Tampilan Default (none)
    ?>
        <?php endif; ?>
    
</div>

<script>
    function toggleNotes() {
        const notesArea = document.getElementById('notes-area');
        const calendarArea = document.getElementById('sprint-calendar-area');
        
        // Sembunyikan kalender saat Notes dibuka
        calendarArea.classList.remove('show');

        // Toggle Notes dengan animasi
        if (notesArea.classList.contains('show')) {
            notesArea.classList.remove('show');
        } else {
            notesArea.classList.add('show');
        }
    }
    
    function toggleSprintCalendar() {
        const calendarArea = document.getElementById('sprint-calendar-area');
        const notesArea = document.getElementById('notes-area');
        
        // Sembunyikan Notes saat kalender dibuka
        notesArea.classList.remove('show');

        // Toggle Sprint Calendar dengan animasi
        if (calendarArea.classList.contains('show')) {
            calendarArea.classList.remove('show');
        } else {
            calendarArea.classList.add('show');
        }
    }
</script>

</body>
</html>