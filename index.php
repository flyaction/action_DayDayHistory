<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>历史上的今天</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --event-color: #0d6efd;
            --birth-color: #198754;
            --death-color: #6c757d;
        }

        body {
            background-color: #f5f6f7;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }

        /* 简洁顶部导航 */
        .topbar {
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
            padding: 0.75rem 0;
        }

        .topbar h1 {
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
            margin: 0;
        }

        /* 紧凑日期控制区 */
        .date-bar {
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
            padding: 0.75rem 0;
        }

        .date-bar-inner {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-arrow {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            border: 1px solid #dee2e6;
            background: #fff;
            color: #495057;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            padding: 0;
            transition: all 0.15s;
        }

        .btn-arrow:hover {
            background: #f8f9fa;
            border-color: #adb5bd;
        }

        .date-display {
            font-size: 1rem;
            font-weight: 600;
            color: #212529;
            min-width: 80px;
            text-align: center;
            cursor: pointer;
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            border: 1px solid transparent;
            transition: border-color 0.15s;
        }

        .date-display:hover {
            border-color: #dee2e6;
            background: #f8f9fa;
        }

        /* 月日选择弹窗 */
        .picker-popover {
            display: none;
            position: absolute;
            top: calc(100% + 6px);
            left: 50%;
            transform: translateX(-50%);
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
            box-shadow: 0 4px 16px rgba(0,0,0,0.1);
            padding: 0.75rem;
            z-index: 1000;
            min-width: 180px;
        }

        .picker-popover.show {
            display: block;
        }

        .picker-select {
            font-size: 0.875rem;
            padding: 0.375rem 0.5rem;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            background: #fff;
            cursor: pointer;
        }

        .picker-select:focus {
            outline: none;
            border-color: #86b7fe;
        }

        /* 分类卡片 */
        .main-card {
            background: white;
            border-radius: 0.5rem;
            border: 1px solid #e5e7eb;
        }

        .main-header {
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: #495057;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .main-body {
            padding: 0;
            max-height: calc(100vh - 180px);
            overflow-y: auto;
        }

        .list-item {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #f1f3f5;
            transition: background 0.1s;
            display: flex;
            align-items: baseline;
            gap: 0.5rem;
        }

        .list-item:last-child {
            border-bottom: none;
        }

        .list-item:hover {
            background: #f8f9fa;
        }

        .year-tag {
            font-size: 0.8rem;
            font-weight: 600;
            color: #212529;
            white-space: nowrap;
            min-width: 70px;
            text-align: right;
        }

        .type-tag {
            font-size: 0.65rem;
            font-weight: 500;
            padding: 0.05rem 0.35rem;
            border-radius: 0.25rem;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .type-tag.event { background: #e7f1ff; color: var(--event-color); }
        .type-tag.birth { background: #d1e7dd; color: var(--birth-color); }
        .type-tag.death { background: #e9ecef; color: var(--death-color); }

        .item-content {
            color: #495057;
            line-height: 1.5;
            font-size: 0.9rem;
            flex: 1;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: #adb5bd;
            font-size: 0.875rem;
        }

        .empty-state i {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            display: block;
        }

        .loading {
            text-align: center;
            padding: 2rem;
            color: #6c757d;
            font-size: 0.875rem;
        }

        @media (max-width: 768px) {
            .main-body { max-height: calc(100vh - 170px); }
            .year-tag { min-width: 55px; font-size: 0.75rem; }
            .item-content { font-size: 0.85rem; }
        }
    </style>
</head>
<body>

    <!-- 顶部导航 -->
    <div class="topbar">
        <div class="container d-flex align-items-center justify-content-between">
            <h1><i class="bi bi-calendar-event me-1 text-primary"></i>历史上的今天</h1>
        </div>
    </div>

    <!-- 日期控制 -->
    <div class="date-bar">
        <div class="container text-center">
            <div class="date-bar-inner position-relative d-inline-flex">
                <button class="btn-arrow" id="btnPrev" title="前一天">
                    <i class="bi bi-chevron-left"></i>
                </button>

                <span class="date-display" id="dateDisplay">01-01</span>

                <div class="picker-popover" id="pickerPopover">
                    <div class="d-flex gap-2 justify-content-center">
                        <select class="picker-select" id="selMonth"></select>
                        <span class="text-muted">-</span>
                        <select class="picker-select" id="selDay"></select>
                    </div>
                </div>

                <button class="btn-arrow" id="btnNext" title="后一天">
                    <i class="bi bi-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- 内容区 -->
    <div class="container py-3">
        <div class="row">
            <div class="col-12">
                <div class="main-card">
                    <div class="main-header">
                        <i class="bi bi-list-ul text-muted"></i>
                        <span>历史事件</span>
                    </div>
                    <div class="main-body" id="listAll">
                        <div class="loading">
                            <div class="spinner-border spinner-border-sm text-primary"></div>
                            <span class="ms-2">加载中...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 底部信息 -->
    <footer class="text-center py-4 mt-2">
        <div class="container">
            <div class="text-muted" style="font-size: 0.8rem;">
                <div class="mb-1">历史上的今天 · 数据来源 Wikipedia</div>
                <div>共收录 <span id="totalCount" class="fw-semibold">-</span> 条历史事件 · 涵盖大事记、出生与逝世</div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const dateDisplay = document.getElementById('dateDisplay');
        const pickerPopover = document.getElementById('pickerPopover');
        const selMonth = document.getElementById('selMonth');
        const selDay = document.getElementById('selDay');
        const btnPrev = document.getElementById('btnPrev');
        const btnNext = document.getElementById('btnNext');

        let currentDate = new Date();

        // 初始化下拉选项
        for (let m = 1; m <= 12; m++) {
            const opt = document.createElement('option');
            opt.value = String(m).padStart(2, '0');
            opt.textContent = m + '月';
            selMonth.appendChild(opt);
        }

        function updateDayOptions() {
            const month = parseInt(selMonth.value);
            const year = currentDate.getFullYear();
            const daysInMonth = new Date(year, month, 0).getDate();
            const oldDay = selDay.value;
            selDay.innerHTML = '';
            for (let d = 1; d <= daysInMonth; d++) {
                const opt = document.createElement('option');
                opt.value = String(d).padStart(2, '0');
                opt.textContent = d + '日';
                selDay.appendChild(opt);
            }
            if (oldDay && parseInt(oldDay) <= daysInMonth) {
                selDay.value = oldDay;
            }
        }

        selMonth.addEventListener('change', updateDayOptions);

        // 初始化显示
        updateDisplay();
        loadData();

        function formatDate(date) {
            const m = String(date.getMonth() + 1).padStart(2, '0');
            const d = String(date.getDate()).padStart(2, '0');
            return `${m}-${d}`;
        }

        function updateDisplay() {
            const str = formatDate(currentDate);
            dateDisplay.textContent = str;
            selMonth.value = str.substring(0, 2);
            updateDayOptions();
            selDay.value = str.substring(3);
        }

        function changeDate(days) {
            currentDate.setDate(currentDate.getDate() + days);
            updateDisplay();
            loadData();
        }

        // 日期选择弹窗
        dateDisplay.addEventListener('click', (e) => {
            e.stopPropagation();
            pickerPopover.classList.toggle('show');
        });

        document.addEventListener('click', (e) => {
            if (!pickerPopover.contains(e.target) && e.target !== dateDisplay) {
                pickerPopover.classList.remove('show');
            }
        });

        function applyPicker() {
            const month = selMonth.value;
            const day = selDay.value;
            const year = currentDate.getFullYear();
            currentDate = new Date(year, parseInt(month) - 1, parseInt(day));
            updateDisplay();
            pickerPopover.classList.remove('show');
            loadData();
        }

        selMonth.addEventListener('change', applyPicker);
        selDay.addEventListener('change', applyPicker);

        btnPrev.addEventListener('click', () => changeDate(-1));
        btnNext.addEventListener('click', () => changeDate(1));

        function formatYear(year) {
            return year < 0 ? `前${Math.abs(year)}年` : `${year}年`;
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function setLoading() {
            document.getElementById('listAll').innerHTML = `
                <div class="loading">
                    <div class="spinner-border spinner-border-sm text-primary"></div>
                    <span class="ms-2">加载中...</span>
                </div>
            `;
        }

        function renderAll(result) {
            const container = document.getElementById('listAll');

            // 合并三类数据
            const all = [];
            result.events.forEach(item => all.push({ ...item, type: 'event', typeName: '大事' }));
            result.births.forEach(item => all.push({ ...item, type: 'birth', typeName: '出生' }));
            result.deaths.forEach(item => all.push({ ...item, type: 'death', typeName: '逝世' }));

            // 按年份升序排序
            all.sort((a, b) => a.year - b.year);

            // 更新底部总数
            document.getElementById('totalCount').textContent = all.length;

            if (all.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <i class="bi bi-inbox"></i>
                        <div>暂无数据</div>
                    </div>
                `;
                return;
            }

            let html = '';
            all.forEach(item => {
                html += `
                    <div class="list-item">
                        <span class="year-tag">${formatYear(item.year)}</span>
                        <span class="type-tag ${item.type}">${item.typeName}</span>
                        <span class="item-content">${escapeHtml(item.content)}</span>
                    </div>
                `;
            });
            container.innerHTML = html;
        }

        async function loadData() {
            const date = formatDate(currentDate);
            setLoading();

            try {
                const response = await fetch(`api.php?date=${date}`);
                const result = await response.json();

                if (result.code !== 200) {
                    throw new Error(result.message);
                }

                renderAll(result);
            } catch (err) {
                document.getElementById('listAll').innerHTML = `
                    <div class="empty-state">
                        <i class="bi bi-exclamation-circle text-danger"></i>
                        <div>${escapeHtml(err.message)}</div>
                    </div>
                `;
            }
        }
    </script>
</body>
</html>
