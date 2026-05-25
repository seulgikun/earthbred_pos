<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Earthbred - Shift Notes</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= asset('css/shift-notes.css') ?>">
</head>
<body>
    <div class="app-container">
        <!-- Top Navigation -->
        <div class="top-nav">
            <button class="back-btn" onclick="window.location.href='/Earthbred/backend/public/pos'">
                <i class="fa-solid fa-arrow-left"></i> Back to Menu
            </button>
            <div class="page-title">Shift Notes</div>
        </div>

        <div class="content-wrapper">
            <!-- Left Side: Write a Note -->
            <div class="write-note-section">
                <div class="section-card">
                    <h3 class="section-title">WRITE A NOTE</h3>
                    <form action="/Earthbred/backend/public/shift-notes" method="POST">
                        <input type="hidden" name="_token" value="<?= csrf_token() ?>">
                        <textarea name="note" class="note-textarea" placeholder="Write your shift note here... (e.g. ice machine not working, customer complaint about wait time, restock cups before next shift)" required></textarea>
                        <button type="submit" class="add-note-btn">
                            <i class="fa-solid fa-plus"></i> Add Note
                        </button>
                    </form>
                </div>
            </div>

            <!-- Right Side: Notes Log -->
            <div class="notes-log-section">
                <div class="section-card log-card">
                    <h3 class="section-title">Notes Log</h3>
                    <div class="log-container">
                        <?php if (isset($notes) && $notes->count() > 0): ?>
                            <div class="notes-list">
                                <?php foreach ($notes as $note): ?>
                                    <div class="note-item <?= $note->is_done ? 'done' : '' ?>">
                                        <div class="note-header">
                                            <span class="note-author"><i class="fa-solid fa-user"></i> <?= htmlspecialchars($note->cashier_name) ?></span>
                                            <span class="note-time"><?= $note->created_at->format('M d, h:i A') ?></span>
                                        </div>
                                        <div class="note-body">
                                            <?= nl2br(htmlspecialchars($note->note)) ?>
                                        </div>
                                        <?php if (!$note->is_done): ?>
                                            <form action="/Earthbred/backend/public/shift-notes/<?= $note->id ?>/done" method="POST" class="mark-done-form">
                                                <input type="hidden" name="_token" value="<?= csrf_token() ?>">
                                                <input type="hidden" name="_method" value="PATCH">
                                                <button type="submit" class="mark-done-btn">Mark as Done</button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="empty-state">
                                <div class="empty-icon">📝</div>
                                <p class="empty-title">No notes yet.</p>
                                <p class="empty-desc">Add your first shift note on the left.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
