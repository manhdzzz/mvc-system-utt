<?php
$bl = $data["bl"];
$qs = $data["qs"] ?? [];
$answered = $data["answered"] ?? [];
$time_remaining = (int) ($data["time_remaining"] ?? 0);
$total = count($qs);
?>
<!doctype html>
<html lang="vi">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Làm bài thi - <?= htmlspecialchars($bl["ten_de"]) ?></title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/style.css?v=2">
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Segoe UI', Arial, sans-serif;
      background: #1a1a2e;
      min-height: 100vh;
    }

    /* Layout 2 cột */
    .exam-container {
      display: flex;
      min-height: 100vh;
    }

    /* Main content */
    .exam-main {
      flex: 1;
      padding: 20px;
      overflow-y: auto;
      max-height: 100vh;
    }

    /* Sidebar */
    .exam-sidebar {
      width: 280px;
      background: linear-gradient(180deg, #16213e 0%, #0f3460 100%);
      padding: 20px;
      display: flex;
      flex-direction: column;
      border-left: 1px solid #2e4a6a;
    }

    /* Header */
    .exam-header {
      background: linear-gradient(135deg, #0f3460 0%, #16213e 100%);
      border-radius: 12px;
      padding: 16px 20px;
      margin-bottom: 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border: 1px solid #2e4a6a;
    }

    .exam-title {
      color: #e94560;
      font-size: 18px;
      font-weight: 700;
    }

    .exam-info {
      color: #94a3b8;
      font-size: 13px;
      margin-top: 4px;
    }

    /* Timer */
    .timer-box {
      background: linear-gradient(135deg, #e94560 0%, #ff6b6b 100%);
      padding: 12px 24px;
      border-radius: 10px;
      text-align: center;
      box-shadow: 0 4px 15px rgba(233, 69, 96, 0.4);
    }

    .timer-label {
      color: rgba(255, 255, 255, 0.8);
      font-size: 11px;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    .timer-value {
      color: #fff;
      font-size: 28px;
      font-weight: 700;
      font-family: 'Consolas', monospace;
    }

    .timer-box.warning {
      animation: pulse 1s infinite;
    }

    @keyframes pulse {

      0%,
      100% {
        transform: scale(1);
      }

      50% {
        transform: scale(1.05);
      }
    }

    /* Question box */
    .question-box {
      background: #fff;
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 16px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
    }

    .question-box:hover {
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    }

    .question-box.answered {
      border-left: 4px solid #22c55e;
    }

    .question-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin-bottom: 12px;
    }

    .question-number {
      background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
      color: #fff;
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 700;
    }

    .question-status {
      font-size: 12px;
      padding: 4px 10px;
      border-radius: 12px;
    }

    .question-status.done {
      background: #dcfce7;
      color: #166534;
    }

    .question-status.pending {
      background: #fef3c7;
      color: #92400e;
    }

    .question-content {
      font-size: 15px;
      line-height: 1.6;
      color: #1e293b;
      margin-bottom: 16px;
    }

    /* Options */
    .option-list {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .option-item {
      display: flex;
      align-items: center;
      padding: 12px 16px;
      border: 2px solid #e2e8f0;
      border-radius: 10px;
      cursor: pointer;
      transition: all 0.2s ease;
    }

    .option-item:hover {
      border-color: #0ea5e9;
      background: #f0f9ff;
    }

    .option-item.selected {
      border-color: #22c55e;
      background: #f0fdf4;
    }

    .option-item input[type="radio"] {
      display: none;
    }

    .option-label {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 28px;
      height: 28px;
      background: #e2e8f0;
      border-radius: 50%;
      font-weight: 700;
      font-size: 13px;
      color: #475569;
      margin-right: 12px;
      transition: all 0.2s ease;
    }

    .option-item.selected .option-label {
      background: #22c55e;
      color: #fff;
    }

    .option-text {
      flex: 1;
      font-size: 14px;
      color: #334155;
    }

    /* Sidebar content */
    .sidebar-header {
      text-align: center;
      margin-bottom: 20px;
    }

    .sidebar-title {
      color: #fff;
      font-size: 16px;
      font-weight: 700;
      margin-bottom: 8px;
    }

    .sidebar-progress {
      color: #94a3b8;
      font-size: 13px;
    }

    .sidebar-progress span {
      color: #22c55e;
      font-weight: 700;
    }

    /* Question nav grid */
    .q-nav-grid {
      display: grid;
      grid-template-columns: repeat(5, 1fr);
      gap: 8px;
      margin-bottom: 20px;
    }

    .q-nav-item {
      aspect-ratio: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #2e4a6a;
      border-radius: 8px;
      color: #94a3b8;
      font-weight: 700;
      font-size: 13px;
      cursor: pointer;
      transition: all 0.2s ease;
      border: 2px solid transparent;
      text-decoration: none;
    }

    .q-nav-item:hover {
      background: #3d5a80;
      color: #fff;
    }

    .q-nav-item.answered {
      background: #22c55e;
      color: #fff;
    }

    .q-nav-item.current {
      border-color: #e94560;
    }

    /* Legend */
    .legend {
      display: flex;
      flex-direction: column;
      gap: 8px;
      margin-bottom: 20px;
      padding: 12px;
      background: rgba(255, 255, 255, 0.05);
      border-radius: 8px;
    }

    .legend-item {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 12px;
      color: #94a3b8;
    }

    .legend-dot {
      width: 16px;
      height: 16px;
      border-radius: 4px;
    }

    .legend-dot.done {
      background: #22c55e;
    }

    .legend-dot.pending {
      background: #2e4a6a;
    }

    /* Submit button */
    .submit-box {
      margin-top: auto;
    }

    .btn-submit {
      width: 100%;
      padding: 14px;
      background: linear-gradient(135deg, #e94560 0%, #ff6b6b 100%);
      color: #fff;
      border: none;
      border-radius: 10px;
      font-size: 15px;
      font-weight: 700;
      cursor: pointer;
      transition: all 0.2s ease;
      box-shadow: 0 4px 15px rgba(233, 69, 96, 0.3);
    }

    .btn-submit:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(233, 69, 96, 0.4);
    }

    /* Fullscreen button */
    .btn-fullscreen {
      width: 100%;
      padding: 10px;
      background: #2e4a6a;
      color: #94a3b8;
      border: none;
      border-radius: 8px;
      font-size: 13px;
      cursor: pointer;
      margin-bottom: 12px;
    }

    .btn-fullscreen:hover {
      background: #3d5a80;
      color: #fff;
    }

    /* Toast notification */
    .toast {
      position: fixed;
      bottom: 20px;
      left: 50%;
      transform: translateX(-50%) translateY(100px);
      background: #22c55e;
      color: #fff;
      padding: 12px 24px;
      border-radius: 8px;
      font-size: 14px;
      font-weight: 600;
      opacity: 0;
      transition: all 0.3s ease;
      z-index: 999;
    }

    .toast.show {
      transform: translateX(-50%) translateY(0);
      opacity: 1;
    }

    .toast.error {
      background: #ef4444;
    }

    /* Saving indicator */
    .saving-indicator {
      position: fixed;
      top: 20px;
      right: 20px;
      background: rgba(0, 0, 0, 0.8);
      color: #fff;
      padding: 8px 16px;
      border-radius: 20px;
      font-size: 12px;
      display: none;
      z-index: 999;
    }

    .saving-indicator.show {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .saving-indicator .spinner {
      width: 14px;
      height: 14px;
      border: 2px solid rgba(255, 255, 255, 0.3);
      border-top-color: #fff;
      border-radius: 50%;
      animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
      to {
        transform: rotate(360deg);
      }
    }

    /* User info */
    .user-info {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 12px;
      background: rgba(255, 255, 255, 0.05);
      border-radius: 8px;
      margin-bottom: 20px;
    }

    .user-avatar {
      width: 40px;
      height: 40px;
      background: linear-gradient(135deg, #0ea5e9 0%, #22c55e 100%);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #fff;
      font-weight: 700;
    }

    .user-name {
      color: #fff;
      font-size: 14px;
      font-weight: 600;
    }

    .user-class {
      color: #94a3b8;
      font-size: 12px;
    }

    /* Responsive */
    @media (max-width: 900px) {
      .exam-container {
        flex-direction: column-reverse;
      }

      .exam-sidebar {
        width: 100%;
        padding: 16px;
      }

      .q-nav-grid {
        grid-template-columns: repeat(10, 1fr);
      }

      .exam-main {
        max-height: none;
      }
    }
  </style>
</head>

<body>

  <div class="exam-container">
    <!-- Main content -->
    <div class="exam-main">
      <!-- Header -->
      <div class="exam-header">
        <div>
          <div class="exam-title"><?= htmlspecialchars($bl["ten_de"]) ?></div>
          <div class="exam-info"><?= htmlspecialchars($bl["ten_mon"]) ?> • <?= $total ?> câu hỏi •
            <?= $bl["time_phut"] ?>
            phút
          </div>
        </div>
        <div class="timer-box" id="timer-box">
          <div class="timer-label">Thời gian còn lại</div>
          <div class="timer-value" id="timer">--:--</div>
        </div>
      </div>

      <!-- Questions -->
      <form method="post" action="<?= BASE_URL ?>/index.php?url=HocvienController/submit" id="exam-form">
        <input type="hidden" name="bailam_id" value="<?= $bl["id"] ?>">

        <?php foreach ($qs as $i => $q):
          $qid = $q["cauhoi_id"];
          $selected = $answered[$qid] ?? "";
          $isAnswered = !empty($selected);
          ?>
          <div class="question-box <?= $isAnswered ? 'answered' : '' ?>" id="q-<?= $qid ?>" data-index="<?= $i ?>">
            <div class="question-header">
              <span class="question-number">Câu <?= ($i + 1) ?></span>
              <span class="question-status <?= $isAnswered ? 'done' : 'pending' ?>" id="status-<?= $qid ?>">
                <?= $isAnswered ? '✓ Đã trả lời' : '○ Chưa trả lời' ?>
              </span>
            </div>
            <div class="question-content"><?= htmlspecialchars($q["noi_dung"]) ?></div>

            <div class="option-list">
              <?php foreach (["A" => "a", "B" => "b", "C" => "c", "D" => "d"] as $k => $col): ?>
                <label class="option-item <?= ($selected === $k) ? 'selected' : '' ?>" data-qid="<?= $qid ?>"
                  data-value="<?= $k ?>">
                  <input type="radio" name="ans[<?= $qid ?>]" value="<?= $k ?>" <?= ($selected === $k) ? 'checked' : '' ?>>
                  <span class="option-label"><?= $k ?></span>
                  <span class="option-text"><?= htmlspecialchars($q[$col]) ?></span>
                </label>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </form>
    </div>

    <!-- Sidebar -->
    <div class="exam-sidebar">
      <!-- User info -->
      <div class="user-info">
        <div class="user-avatar"><?= mb_substr($_SESSION["hv"]["hoten"] ?? "?", 0, 1) ?></div>
        <div>
          <div class="user-name"><?= htmlspecialchars($_SESSION["hv"]["hoten"] ?? "") ?></div>
          <div class="user-class">Mã HV: <?= htmlspecialchars($_SESSION["hv"]["ma_hv"] ?? "") ?></div>
        </div>
      </div>

      <div class="sidebar-header">
        <div class="sidebar-title">Danh sách câu hỏi</div>
        <div class="sidebar-progress">Đã làm: <span
            id="answered-count"><?= count(array_filter($answered)) ?></span>/<?= $total ?></div>
      </div>

      <!-- Question navigation -->
      <div class="q-nav-grid" id="q-nav">
        <?php foreach ($qs as $i => $q):
          $qid = $q["cauhoi_id"];
          $isAnswered = !empty($answered[$qid] ?? "");
          ?>
          <a href="#q-<?= $qid ?>" class="q-nav-item <?= $isAnswered ? 'answered' : '' ?>" data-qid="<?= $qid ?>">
            <?= ($i + 1) ?>
          </a>
        <?php endforeach; ?>
      </div>

      <!-- Legend -->
      <div class="legend">
        <div class="legend-item">
          <div class="legend-dot done"></div>
          <span>Đã trả lời</span>
        </div>
        <div class="legend-item">
          <div class="legend-dot pending"></div>
          <span>Chưa trả lời</span>
        </div>
      </div>

      <!-- Fullscreen button -->
      <button class="btn-fullscreen" onclick="toggleFullscreen()">
        ⛶ Toàn màn hình
      </button>

      <!-- Submit button -->
      <div class="submit-box">
        <button type="button" class="btn-submit" onclick="confirmSubmit()">
          📤 Nộp bài
        </button>
      </div>
    </div>
  </div>

  <!-- Toast -->
  <div class="toast" id="toast"></div>

  <!-- Saving indicator -->
  <div class="saving-indicator" id="saving">
    <div class="spinner"></div>
    <span>Đang lưu...</span>
  </div>

  <script>
    // Config
    const CONFIG = {
      bailamId: <?= $bl["id"] ?>,
      saveUrl: "<?= BASE_URL ?>/index.php?url=HocvienController/saveAnswer",
      submitUrl: "<?= BASE_URL ?>/index.php?url=HocvienController/submit"
    };

    // Timer
    let timeLeft = <?= $time_remaining ?>;
    const timerEl = document.getElementById('timer');
    const timerBox = document.getElementById('timer-box');

    function formatTime(seconds) {
      const h = Math.floor(seconds / 3600);
      const m = Math.floor((seconds % 3600) / 60);
      const s = seconds % 60;
      return String(h).padStart(2, '0') + ':' + String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
    }

    function updateTimer() {
      timerEl.textContent = formatTime(timeLeft);

      // Warning when less than 5 minutes
      if (timeLeft <= 300) {
        timerBox.classList.add('warning');
      }

      if (timeLeft <= 0) {
        showToast('Hết giờ! Đang nộp bài...', 'error');
        setTimeout(() => {
          document.getElementById('exam-form').submit();
        }, 1000);
        return;
      }

      timeLeft--;
      setTimeout(updateTimer, 1000);
    }

    updateTimer();

    // AJAX Save Answer
    function saveAnswer(qid, answer) {
      const saving = document.getElementById('saving');
      saving.classList.add('show');

      const formData = new FormData();
      formData.append('bailam_id', CONFIG.bailamId);
      formData.append('cauhoi_id', qid);
      formData.append('answer', answer);

      fetch(CONFIG.saveUrl, {
        method: 'POST',
        body: formData
      })
        .then(res => res.json())
        .then(data => {
          saving.classList.remove('show');

          if (data.ok) {
            // Update UI
            updateQuestionStatus(qid, answer);
            showToast('Đã lưu câu trả lời');

            // Sync server time
            if (data.remaining) {
              timeLeft = data.remaining;
            }
          } else {
            if (data.timeout) {
              showToast('Hết giờ! Đang nộp bài...', 'error');
              setTimeout(() => {
                document.getElementById('exam-form').submit();
              }, 1000);
            } else {
              showToast(data.msg || 'Lỗi lưu đáp án', 'error');
            }
          }
        })
        .catch(err => {
          saving.classList.remove('show');
          showToast('Lỗi kết nối, thử lại...', 'error');
        });
    }

    function updateQuestionStatus(qid, answer) {
      // Update question box
      const qBox = document.getElementById('q-' + qid);
      const status = document.getElementById('status-' + qid);
      const navItem = document.querySelector('.q-nav-item[data-qid="' + qid + '"]');

      if (answer) {
        qBox.classList.add('answered');
        status.className = 'question-status done';
        status.textContent = '✓ Đã trả lời';
        if (navItem) navItem.classList.add('answered');
      } else {
        qBox.classList.remove('answered');
        status.className = 'question-status pending';
        status.textContent = '○ Chưa trả lời';
        if (navItem) navItem.classList.remove('answered');
      }

      // Update count
      const answeredCount = document.querySelectorAll('.q-nav-item.answered').length;
      document.getElementById('answered-count').textContent = answeredCount;
    }

    // Option click handler
    document.querySelectorAll('.option-item').forEach(item => {
      item.addEventListener('click', function () {
        const qid = this.dataset.qid;
        const value = this.dataset.value;

        // Update UI
        const parent = this.closest('.option-list');
        parent.querySelectorAll('.option-item').forEach(opt => opt.classList.remove('selected'));
        this.classList.add('selected');
        this.querySelector('input').checked = true;

        // Save via AJAX
        saveAnswer(qid, value);
      });
    });

    // Toast notification
    function showToast(msg, type = 'success') {
      const toast = document.getElementById('toast');
      toast.textContent = msg;
      toast.className = 'toast ' + (type === 'error' ? 'error' : '') + ' show';

      setTimeout(() => {
        toast.classList.remove('show');
      }, 2000);
    }

    // Submit confirmation
    function confirmSubmit() {
      const total = <?= $total ?>;
      const answered = document.querySelectorAll('.q-nav-item.answered').length;

      let msg = `Bạn đã trả lời ${answered}/${total} câu.\n`;
      if (answered < total) {
        msg += `Còn ${total - answered} câu chưa trả lời.\n`;
      }
      msg += '\nXác nhận nộp bài?';

      if (confirm(msg)) {
        document.getElementById('exam-form').submit();
      }
    }

    // Fullscreen
    function toggleFullscreen() {
      if (!document.fullscreenElement) {
        document.documentElement.requestFullscreen().catch(err => {
          showToast('Không thể mở toàn màn hình', 'error');
        });
      } else {
        document.exitFullscreen();
      }
    }

    // Warn before leaving
    window.addEventListener('beforeunload', function (e) {
      e.preventDefault();
      e.returnValue = 'Bạn có chắc muốn rời trang? Bài làm sẽ không bị mất.';
    });

    // Smooth scroll to question
    document.querySelectorAll('.q-nav-item').forEach(item => {
      item.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
          target.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
      });
    });

    // ============ ANTI-CHEAT PROTECTION ============

    // Disable right-click
    document.addEventListener('contextmenu', e => {
      e.preventDefault();
      showCheatingWarning();
    });

    // Disable copy (Ctrl+C, Ctrl+X, Ctrl+V, Ctrl+U, Ctrl+S, F12)
    document.addEventListener('keydown', function (e) {
      // F12
      if (e.key === 'F12' || e.keyCode === 123) {
        e.preventDefault();
        showCheatingWarning();
        return false;
      }

      // Ctrl+Shift+I (DevTools)
      if (e.ctrlKey && e.shiftKey && (e.key === 'I' || e.key === 'i')) {
        e.preventDefault();
        showCheatingWarning();
        return false;
      }

      // Ctrl+Shift+J (Console)
      if (e.ctrlKey && e.shiftKey && (e.key === 'J' || e.key === 'j')) {
        e.preventDefault();
        showCheatingWarning();
        return false;
      }

      // Ctrl+U (View Source)
      if (e.ctrlKey && (e.key === 'U' || e.key === 'u')) {
        e.preventDefault();
        showCheatingWarning();
        return false;
      }

      // Ctrl+C (Copy)
      if (e.ctrlKey && (e.key === 'C' || e.key === 'c')) {
        e.preventDefault();
        showCheatingWarning();
        return false;
      }

      // Ctrl+S (Save)
      if (e.ctrlKey && (e.key === 'S' || e.key === 's')) {
        e.preventDefault();
        return false;
      }

      // Ctrl+A (Select All) - prevent on question content only
      if (e.ctrlKey && (e.key === 'A' || e.key === 'a')) {
        e.preventDefault();
        return false;
      }
    });

    // Disable text selection
    document.addEventListener('selectstart', function (e) {
      if (!e.target.matches('input, textarea')) {
        e.preventDefault();
      }
    });

    // Disable drag
    document.addEventListener('dragstart', function (e) {
      e.preventDefault();
    });

    // Cheating warning with SweetAlert
    let warningCount = 0;
    function showCheatingWarning() {
      warningCount++;
      Swal.fire({
        icon: 'warning',
        title: 'Cảnh báo vi phạm!',
        html: '<b>Vi phạm sẽ bị giám thị cấm thi!</b><br><br>Số lần vi phạm: ' + warningCount,
        confirmButtonColor: '#ef4444',
        background: '#1e293b',
        color: '#f1f5f9',
        confirmButtonText: 'Tôi đã hiểu'
      });
    }

    // Request fullscreen on start (optional)
    // document.documentElement.requestFullscreen();
  </script>

</body>

</html>