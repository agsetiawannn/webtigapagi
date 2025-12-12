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
$stmt = $conn->prepare("
    SELECT onboard, presprint, sprint, client_view, sprint_week_focus
    FROM client_progress
    WHERE client_id = ?
");
$stmt->bind_param("i", $client_id);
$stmt->execute();
$res = $stmt->get_result();
$progress_data = $res->fetch_assoc();

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
    <title>Tracking - Client</title>
    <style>
        /* BASE STYLES & HEADER (Tidak diubah, untuk konsistensi) */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
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
        
        .container { max-width: 1200px; margin: 0 auto; position: relative; z-index: 1; }
        
        /* HEADER */
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 40px; }
        .header-left h1 { font-size: 48px; font-weight: 700; margin: 0 0 8px 0; color: #fff; letter-spacing: -0.5px; }
        .header-left p { font-size: 17px; color: rgba(255, 255, 255, 0.8); margin: 0; }
        .header-right { display: flex; align-items: center; gap: 10px; }
        .header-right .logo-img { height: 40px; width: auto; }
        .header-right .logo-text { font-size: 24px; font-weight: 700; color: #fff; letter-spacing: 1.5px; }

        /* PROGRESS TABLE (Tidak diubah) */
        .progress-table-container { background: rgba(15, 15, 15, 0.85); backdrop-filter: blur(10px); border-radius: 16px; overflow: hidden; border: 1.5px solid rgba(255, 255, 255, 0.2); margin-bottom: 30px; }
        .progress-table { width: 100%; border-collapse: collapse; }
        .progress-table th { background: rgba(0, 0, 0, 0.3); padding: 20px 30px; text-align: left; font-size: 18px; font-weight: 600; color: #fff; border-bottom: 1.5px solid rgba(255, 255, 255, 0.2); }
        .progress-table td { padding: 22px 30px; border-bottom: 1px solid rgba(255, 255, 255, 0.08); font-size: 16px; color: rgba(255, 255, 255, 0.95); font-weight: 400; }
        .progress-table tr:last-child td { border-bottom: none; }
        
        /* Status Icon Styling */
        .status-icon { width: 28px; height: 28px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 16px; font-weight: bold; }
        .status-complete { background: #00ff88; color: #000; }
        .status-complete::after { content: '✓'; font-size: 18px; }
        .status-progress { width: 26px; height: 26px; border: 3px solid #fff; border-right-color: transparent; border-radius: 50%; animation: spin 1s linear infinite; }
        .status-progress::after { content: ''; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        .status-pending { background: #ff4444; color: #fff; }
        .status-pending::after { content: '✕'; font-size: 16px; }

        /* ACTION BUTTONS & NOTES (Dipertahankan) */
        .action-buttons { display: flex; gap: 15px; margin-top: 0; margin-bottom: 20px; }
        .btn { padding: 10px 20px; border-radius: 8px; font-size: 16px; font-weight: 700; cursor: pointer; transition: all 0.3s ease; border: none; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; }
        .btn-logout { background: #00ff88; color: #000; }
        .btn-notes, .btn-sprint-date { background: rgba(40, 40, 40, 0.9); color: #fff; border: 2px solid rgba(255, 255, 255, 0.3); }
        .btn-notes svg, .btn-sprint-date svg { width: 16px; height: 16px; }
        
        /* NOTES AREA (Dipertahankan) */
        .notes-container { margin-top: 20px; position: relative; width: 100%; }
        .notes-container::before { content: ''; position: absolute; top: -14px; left: 160px; width: 0; height: 0; border-left: 14px solid transparent; border-right: 14px solid transparent; border-bottom: 14px solid rgba(45, 45, 45, 0.95); z-index: 10; }
        .notes-textarea { width: 100%; min-height: 120px; background: rgba(45, 45, 45, 0.95); border: 1px solid rgba(255, 255, 255, 0.15); border-radius: 12px; padding: 16px; color: rgba(255, 255, 255, 0.8); font-size: 14px; font-family: inherit; resize: vertical; outline: none; }


        /* =================================================================== */
        /* SPRINT CALENDAR STYLES (Diambil dari respons sebelumnya dan disesuaikan) */
        /* =================================================================== */
        .sprint-calendar-container {
            margin-top: 20px;
            position: relative;
            width: 100%;
            background: rgba(15, 15, 15, 0.95);
            backdrop-filter: blur(8px);
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 20px;
        }

        /* Bubble pointer untuk tombol Sprint Week Date */
        .sprint-calendar-container::before {
            content: '';
            position: absolute;
            top: -14px;
            /* Kalkulasi posisi bubble: Tombol Notes + Gap + Tombol Sprint / 2 + Margin Kiri */
            left: calc(160px + 15px + 140px); /* Perlu disesuaikan tergantung lebar tombol */
            width: 0;
            height: 0;
            border-left: 14px solid transparent;
            border-right: 14px solid transparent;
            border-bottom: 14px solid rgba(15, 15, 15, 0.95);
            z-index: 10;
        }

        .sprint-table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sprint-table th, .sprint-table td {
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 10px;
            vertical-align: middle;
        }

        /* Header (Week vs Day) */
        .sprint-table thead th {
            padding: 15px 10px;
            background: rgba(40, 40, 40, 0.9);
            font-weight: 600;
            font-size: 15px;
            color: #fff;
        }

        /* Kolom Week (Header dan Label) */
        .sprint-table .week-day-header {
            width: 120px;
        }

        .sprint-table tbody tr td:first-child {
            background: rgba(40, 40, 40, 0.7);
            font-weight: 600;
            font-size: 16px;
        }
        
        .sprint-table .posting-row td {
            padding: 0; /* Hapus padding agar posting label mengisi penuh */
        }
        
        /* Task Box Styling */
        .task-box {
            display: block;
            padding: 6px 10px;
            border-radius: 6px;
            color: #000;
            font-weight: 600;
            width: 90%;
            margin: 0 auto;
            text-align: center;
            font-size: 12px;
        }

        /* Warna Task */
        .task-planning { background: #ff9800; } /* Orange */
        .task-development { background: #4caf50; } /* Hijau */
        .task-clinic { background: #2196f3; } /* Biru */
        .task-revision { background: #9e9e9e; } /* Abu-abu */
        .task-posting { 
            background: #555;
            color: #fff;
            padding: 10px;
            font-size: 14px;
            font-weight: 500;
            border-radius: 0;
            width: 100%;
        }

    </style>
</head>
<body>

<div class="container">
    
    <div class="header">
        <div class="header-left">
            <h1>Tigapagi Tracking</h1>
            <p>Hi "<?= $name ?>", here's your progres</p> 
        </div>
        <div class="header-right">
            <img src="../img/TP.png" alt="TP" class="logo-img">
            <span class="logo-text">TIGAPAGI</span>
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
            <button class="btn btn-notes" onclick="toggleNotes()"> 
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
                Notes
            </button>
        </div>
        
        <div id="notes-area" class="notes-container" style="display: none;"> 
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
        </div>
        
        <div id="notes-area" class="notes-container" style="display: none;"> 
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

        <div id="sprint-calendar-area" class="sprint-calendar-container" style="display: none;">
            <img src="../img/date.png" alt="Sprint Week Date" style="width: 100%; height: auto; border-radius: 8px;">
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
        calendarArea.style.display = 'none';

        if (notesArea.style.display === 'none') {
            notesArea.style.display = 'block';
        } else {
            notesArea.style.display = 'none';
        }
    }
    
    function toggleSprintCalendar() {
        const calendarArea = document.getElementById('sprint-calendar-area');
        const notesArea = document.getElementById('notes-area');
        
        // Sembunyikan Notes saat kalender dibuka
        notesArea.style.display = 'none';

        if (calendarArea.style.display === 'none') {
            calendarArea.style.display = 'block';
        } else {
            calendarArea.style.display = 'none';
        }
    }
</script>

</body>
</html>