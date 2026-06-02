<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard — UMKM Insight</title>
  <meta name="description" content="Dashboard Admin UMKM Insight. Pantau ekosistem, kelola pengguna, audit log, dan konfigurasi sistem." />
  <style>
    /* ─── CSS Variables ─────────────────────────────────────── */
    :root {
      --bg-page: #f0f4f8;
      --bg-sidebar: #ffffff;
      --bg-topbar: #ffffffcc;
      --bg-card: #ffffff;
      --bg-card-inner: #f8fafc;
      --bg-input: #f8fafc;
      --bg-hover: #f1f5f9;
      --bg-table-head: #f8fafc;
      --bg-table-row-hover: #f1f5f9;
      --border: #e2e8f0;
      --border-soft: #edf2f7;
      --text-primary: #0f172a;
      --text-secondary: #475569;
      --text-muted: #94a3b8;
      --accent: #059669;
      --accent-hover: #047857;
      --accent-light: #d1fae5;
      --accent-text: #065f46;
      --blue: #3b82f6;
      --blue-light: #dbeafe;
      --blue-text: #1d4ed8;
      --yellow: #f59e0b;
      --yellow-light: #fef3c7;
      --yellow-text: #92400e;
      --red: #ef4444;
      --red-light: #fee2e2;
      --red-text: #991b1b;
      --purple: #8b5cf6;
      --purple-light: #ede9fe;
      --purple-text: #5b21b6;
      --shadow-card: 0 1px 4px rgba(0,0,0,0.06), 0 4px 20px rgba(0,0,0,0.05);
      --shadow-btn: 0 2px 8px rgba(5,150,105,0.22);
      --transition: all 0.22s cubic-bezier(0.4,0,0.2,1);
      --radius: 12px;
      --radius-sm: 8px;
      --sidebar-w: 240px;
      --topbar-h: 64px;
    }
    [data-theme="dark"] {
      --bg-page: #0d1525;
      --bg-sidebar: #111e35;
      --bg-topbar: #111e35cc;
      --bg-card: #1a2540;
      --bg-card-inner: #1e2d4a;
      --bg-input: #1e2d4a;
      --bg-hover: #1e2d4a;
      --bg-table-head: #1e2d4a;
      --bg-table-row-hover: #1e2d4a;
      --border: #2d3f5e;
      --border-soft: #243450;
      --text-primary: #f0f6ff;
      --text-secondary: #94afd4;
      --text-muted: #5a7499;
      --accent: #10b981;
      --accent-hover: #059669;
      --accent-light: #022c22;
      --accent-text: #6ee7b7;
      --blue: #60a5fa;
      --blue-light: #0c1e3a;
      --blue-text: #93c5fd;
      --yellow: #fbbf24;
      --yellow-light: #1c1500;
      --yellow-text: #fcd34d;
      --red: #f87171;
      --red-light: #1c0a0a;
      --red-text: #fca5a5;
      --purple: #a78bfa;
      --purple-light: #1a1030;
      --purple-text: #c4b5fd;
      --shadow-card: 0 1px 4px rgba(0,0,0,0.3), 0 4px 20px rgba(0,0,0,0.25);
      --shadow-btn: 0 2px 12px rgba(16,185,129,0.28);
    }

    /* ─── Reset & Base ──────────────────────────────────────── */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html { font-size: 15px; scroll-behavior: smooth; }
    body {
      font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
      background: var(--bg-page);
      color: var(--text-primary);
      min-height: 100vh;
      transition: background 0.3s ease, color 0.3s ease;
    }
    button { cursor: pointer; font-family: inherit; }
    input, select, textarea { font-family: inherit; }

    /* ─── Layout Shell ───────────────────────────────────────── */
    .app-shell {
      display: flex;
      min-height: 100vh;
    }

    /* ─── Sidebar ────────────────────────────────────────────── */
    .sidebar {
      width: var(--sidebar-w);
      background: var(--bg-sidebar);
      border-right: 1px solid var(--border);
      display: flex;
      flex-direction: column;
      position: fixed;
      top: 0; left: 0;
      height: 100vh;
      z-index: 200;
      transition: transform 0.3s ease, var(--transition);
      overflow: hidden;
    }
    .sidebar-brand {
      display: flex;
      align-items: center;
      gap: 0.65rem;
      padding: 1.25rem 1.25rem 1rem;
      border-bottom: 1px solid var(--border-soft);
      text-decoration: none;
      flex-shrink: 0;
    }
    .brand-logo {
      width: 36px; height: 36px;
      background: linear-gradient(135deg, #059669, #0d9488);
      border-radius: 9px;
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
      box-shadow: 0 2px 8px rgba(5,150,105,0.3);
    }
    .brand-logo svg { width: 18px; height: 18px; fill: #fff; }
    .brand-info { line-height: 1.2; }
    .brand-name { font-size: 0.9rem; font-weight: 700; color: var(--text-primary); letter-spacing: -0.02em; }
    .brand-role { font-size: 0.67rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; }

    .sidebar-nav { flex: 1; padding: 1rem 0.75rem; overflow-y: auto; }
    .nav-section-label {
      font-size: 0.65rem;
      font-weight: 600;
      color: var(--text-muted);
      text-transform: uppercase;
      letter-spacing: 0.08em;
      padding: 0.6rem 0.5rem 0.35rem;
      margin-top: 0.5rem;
    }
    .nav-item {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      padding: 0.65rem 0.75rem;
      border-radius: var(--radius-sm);
      cursor: pointer;
      color: var(--text-secondary);
      font-size: 0.875rem;
      font-weight: 500;
      transition: var(--transition);
      border: none;
      background: none;
      width: 100%;
      text-align: left;
      text-decoration: none;
      margin-bottom: 2px;
      white-space: nowrap;
    }
    .nav-item:hover { background: var(--bg-hover); color: var(--text-primary); }
    .nav-item.active {
      background: var(--accent-light);
      color: var(--accent-text);
      font-weight: 600;
    }
    [data-theme="dark"] .nav-item.active { color: var(--accent); }
    .nav-item .nav-icon { font-size: 1rem; width: 20px; text-align: center; flex-shrink: 0; }
    .nav-badge {
      margin-left: auto;
      background: var(--red-light);
      color: var(--red-text);
      font-size: 0.65rem;
      font-weight: 700;
      padding: 0.1rem 0.45rem;
      border-radius: 100px;
    }

    .sidebar-footer {
      padding: 0.75rem;
      border-top: 1px solid var(--border-soft);
      flex-shrink: 0;
    }
    .user-card {
      display: flex;
      align-items: center;
      gap: 0.65rem;
      padding: 0.65rem 0.75rem;
      border-radius: var(--radius-sm);
      margin-bottom: 0.5rem;
      background: var(--bg-card-inner);
    }
    .user-avatar {
      width: 34px; height: 34px;
      background: linear-gradient(135deg, #059669, #0d9488);
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      font-size: 0.8rem;
      font-weight: 700;
      color: #fff;
      flex-shrink: 0;
    }
    .user-info { line-height: 1.3; overflow: hidden; }
    .user-name { font-size: 0.8rem; font-weight: 600; color: var(--text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .user-role-tag { font-size: 0.68rem; color: var(--text-muted); }
    .btn-logout {
      display: flex; align-items: center; gap: 0.6rem;
      width: 100%;
      padding: 0.6rem 0.75rem;
      background: none;
      border: 1px solid var(--border);
      border-radius: var(--radius-sm);
      color: var(--text-secondary);
      font-size: 0.825rem;
      font-weight: 500;
      transition: var(--transition);
    }
    .btn-logout:hover { border-color: var(--red); color: var(--red); background: var(--red-light); }

    /* ─── Main Area ──────────────────────────────────────────── */
    .main-area {
      margin-left: var(--sidebar-w);
      flex: 1;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      min-width: 0;
    }

    /* ─── Topbar ─────────────────────────────────────────────── */
    .topbar {
      height: var(--topbar-h);
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 1.75rem;
      background: var(--bg-topbar);
      backdrop-filter: blur(12px);
      border-bottom: 1px solid var(--border);
      position: sticky;
      top: 0;
      z-index: 100;
    }
    .topbar-left { display: flex; align-items: center; gap: 0.75rem; }
    .menu-toggle-btn {
      display: none;
      background: none;
      border: 1px solid var(--border);
      border-radius: var(--radius-sm);
      padding: 0.4rem 0.5rem;
      color: var(--text-secondary);
      font-size: 1.1rem;
      line-height: 1;
    }
    .topbar-date {
      font-size: 0.82rem;
      color: var(--text-muted);
      font-weight: 400;
    }
    .topbar-page-title {
      font-size: 0.9rem;
      font-weight: 600;
      color: var(--text-primary);
      display: none;
    }
    .topbar-right { display: flex; align-items: center; gap: 0.75rem; }
    .notif-btn {
      position: relative;
      background: none;
      border: 1px solid var(--border);
      border-radius: var(--radius-sm);
      width: 36px; height: 36px;
      display: flex; align-items: center; justify-content: center;
      color: var(--text-secondary);
      font-size: 1rem;
      transition: var(--transition);
    }
    .notif-btn:hover { border-color: var(--accent); background: var(--accent-light); }
    .notif-dot {
      position: absolute;
      top: 6px; right: 7px;
      width: 7px; height: 7px;
      background: var(--red);
      border-radius: 50%;
      border: 1.5px solid var(--bg-card);
    }

    .theme-pill {
      display: flex;
      align-items: center;
      gap: 0.45rem;
      background: var(--bg-card-inner);
      border: 1px solid var(--border);
      border-radius: 100px;
      padding: 0.3rem 0.75rem 0.3rem 0.5rem;
      cursor: pointer;
      transition: var(--transition);
      font-size: 0.78rem;
      color: var(--text-secondary);
      font-weight: 500;
    }
    .theme-pill:hover { border-color: var(--accent); }
    .toggle-track {
      width: 30px; height: 17px;
      background: var(--border);
      border-radius: 100px;
      position: relative;
      transition: var(--transition);
    }
    .toggle-track.active { background: var(--accent); }
    .toggle-thumb {
      position: absolute; top: 2.5px; left: 2.5px;
      width: 12px; height: 12px;
      background: #fff;
      border-radius: 50%;
      transition: var(--transition);
      box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }
    .toggle-track.active .toggle-thumb { transform: translateX(13px); }

    .topbar-avatar {
      width: 34px; height: 34px;
      background: linear-gradient(135deg, #059669, #0d9488);
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      font-size: 0.8rem;
      font-weight: 700;
      color: #fff;
      border: 2px solid var(--accent-light);
    }

    /* ─── Page Content ───────────────────────────────────────── */
    .page-content {
      flex: 1;
      padding: 2rem 1.75rem;
    }
    .section-panel { display: none; }
    .section-panel.active { display: block; animation: fadeIn 0.3s ease; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }

    .page-header { margin-bottom: 1.75rem; }
    .page-header h2 {
      font-size: 1.5rem;
      font-weight: 700;
      color: var(--text-primary);
      letter-spacing: -0.03em;
      margin-bottom: 0.3rem;
    }
    .page-header p { font-size: 0.875rem; color: var(--text-secondary); }

    /* ─── Stats Cards ────────────────────────────────────────── */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 1rem;
      margin-bottom: 1.75rem;
    }
    .stat-card {
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      padding: 1.25rem;
      box-shadow: var(--shadow-card);
      transition: var(--transition);
    }
    .stat-card:hover { transform: translateY(-2px); box-shadow: 0 6px 24px rgba(0,0,0,0.09); }
    .stat-top { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 0.85rem; }
    .stat-icon {
      width: 40px; height: 40px;
      border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.1rem;
    }
    .stat-icon.green { background: var(--accent-light); }
    .stat-icon.blue { background: var(--blue-light); }
    .stat-icon.yellow { background: var(--yellow-light); }
    .stat-icon.purple { background: var(--purple-light); }
    .stat-change {
      font-size: 0.72rem;
      font-weight: 600;
      padding: 0.2rem 0.5rem;
      border-radius: 100px;
    }
    .stat-change.up { background: var(--accent-light); color: var(--accent-text); }
    .stat-change.neutral { background: var(--blue-light); color: var(--blue-text); }
    [data-theme="dark"] .stat-change.up { color: var(--accent); }
    [data-theme="dark"] .stat-change.neutral { color: var(--blue); }
    .stat-value { font-size: 1.65rem; font-weight: 700; color: var(--text-primary); letter-spacing: -0.03em; margin-bottom: 0.2rem; line-height: 1.2; }
    .stat-label { font-size: 0.78rem; color: var(--text-muted); font-weight: 500; }

    /* ─── Card Container ─────────────────────────────────────── */
    .card {
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      box-shadow: var(--shadow-card);
      overflow: hidden;
      margin-bottom: 1.25rem;
    }
    .card-header-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 1.1rem 1.5rem;
      border-bottom: 1px solid var(--border-soft);
      flex-wrap: wrap;
      gap: 0.75rem;
    }
    .card-title-row h3 { font-size: 0.975rem; font-weight: 600; color: var(--text-primary); }
    .card-title-row p { font-size: 0.78rem; color: var(--text-muted); margin-top: 0.1rem; }

    /* ─── Toolbar ─────────────────────────────────────────────── */
    .toolbar {
      display: flex;
      align-items: center;
      gap: 0.65rem;
      flex-wrap: wrap;
    }
    .search-box {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      background: var(--bg-input);
      border: 1px solid var(--border);
      border-radius: var(--radius-sm);
      padding: 0.45rem 0.85rem;
      transition: var(--transition);
    }
    .search-box:focus-within { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(5,150,105,0.1); }
    .search-box input {
      border: none;
      background: none;
      outline: none;
      color: var(--text-primary);
      font-size: 0.85rem;
      width: 200px;
    }
    .search-box input::placeholder { color: var(--text-muted); }
    .search-icon { color: var(--text-muted); font-size: 0.9rem; }

    .filter-select {
      background: var(--bg-input);
      border: 1px solid var(--border);
      border-radius: var(--radius-sm);
      padding: 0.48rem 0.85rem;
      color: var(--text-primary);
      font-size: 0.85rem;
      outline: none;
      cursor: pointer;
      transition: var(--transition);
    }
    .filter-select:focus { border-color: var(--accent); }

    .btn {
      display: inline-flex;
      align-items: center;
      gap: 0.4rem;
      padding: 0.48rem 1rem;
      border-radius: var(--radius-sm);
      font-size: 0.835rem;
      font-weight: 600;
      border: none;
      transition: var(--transition);
      cursor: pointer;
      white-space: nowrap;
    }
    .btn-primary {
      background: var(--accent);
      color: #fff;
      box-shadow: var(--shadow-btn);
    }
    .btn-primary:hover { background: var(--accent-hover); transform: translateY(-1px); }
    .btn-outline {
      background: none;
      border: 1px solid var(--border);
      color: var(--text-secondary);
    }
    .btn-outline:hover { border-color: var(--accent); color: var(--accent); background: var(--accent-light); }
    .btn-sm { padding: 0.3rem 0.65rem; font-size: 0.775rem; }
    .btn-danger { background: var(--red-light); color: var(--red-text); border: 1px solid transparent; }
    .btn-danger:hover { background: var(--red); color: #fff; }
    .btn-warn { background: var(--yellow-light); color: var(--yellow-text); border: 1px solid transparent; }
    .btn-warn:hover { background: var(--yellow); color: #fff; }
    .btn-info { background: var(--blue-light); color: var(--blue-text); border: 1px solid transparent; }
    .btn-info:hover { background: var(--blue); color: #fff; }

    /* ─── Table ──────────────────────────────────────────────── */
    .table-wrap { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; font-size: 0.855rem; }
    thead tr { background: var(--bg-table-head); }
    th {
      padding: 0.75rem 1rem;
      text-align: left;
      font-size: 0.73rem;
      font-weight: 600;
      color: var(--text-muted);
      text-transform: uppercase;
      letter-spacing: 0.05em;
      white-space: nowrap;
      border-bottom: 1px solid var(--border);
    }
    td {
      padding: 0.85rem 1rem;
      border-bottom: 1px solid var(--border-soft);
      color: var(--text-primary);
      vertical-align: middle;
    }
    tr:last-child td { border-bottom: none; }
    tbody tr { transition: var(--transition); }
    tbody tr:hover { background: var(--bg-table-row-hover); }

    /* ─── User Cell ──────────────────────────────────────────── */
    .user-cell { display: flex; align-items: center; gap: 0.65rem; }
    .mini-avatar {
      width: 30px; height: 30px;
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      font-size: 0.72rem;
      font-weight: 700;
      color: #fff;
      flex-shrink: 0;
    }
    .a-green { background: linear-gradient(135deg,#059669,#0d9488); }
    .a-blue  { background: linear-gradient(135deg,#3b82f6,#6366f1); }
    .a-orange{ background: linear-gradient(135deg,#f59e0b,#ef4444); }
    .a-purple{ background: linear-gradient(135deg,#8b5cf6,#a855f7); }
    .a-teal  { background: linear-gradient(135deg,#14b8a6,#0ea5e9); }
    .a-rose  { background: linear-gradient(135deg,#f43f5e,#ec4899); }
    .user-cell-info { line-height: 1.3; }
    .user-cell-name { font-weight: 600; font-size: 0.85rem; }
    .user-cell-sub { font-size: 0.75rem; color: var(--text-muted); }

    /* ─── Badge ──────────────────────────────────────────────── */
    .badge {
      display: inline-flex;
      align-items: center;
      gap: 0.25rem;
      padding: 0.2rem 0.6rem;
      border-radius: 100px;
      font-size: 0.72rem;
      font-weight: 600;
      white-space: nowrap;
    }
    .badge-green { background: var(--accent-light); color: var(--accent-text); }
    .badge-blue  { background: var(--blue-light);   color: var(--blue-text); }
    .badge-yellow{ background: var(--yellow-light); color: var(--yellow-text); }
    .badge-red   { background: var(--red-light);    color: var(--red-text); }
    .badge-purple{ background: var(--purple-light); color: var(--purple-text); }
    .badge-gray  { background: var(--bg-hover);     color: var(--text-secondary); border: 1px solid var(--border); }
    [data-theme="dark"] .badge-green { color: var(--accent); }
    [data-theme="dark"] .badge-blue  { color: var(--blue); }
    [data-theme="dark"] .badge-yellow{ color: var(--yellow); }
    [data-theme="dark"] .badge-red   { color: var(--red); }
    [data-theme="dark"] .badge-purple{ color: var(--purple); }

    .status-dot { width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; }
    .dot-green  { background: #10b981; }
    .dot-yellow { background: #f59e0b; }
    .dot-red    { background: #ef4444; }
    .dot-blue   { background: #3b82f6; }

    /* ─── Action Buttons Cell ────────────────────────────────── */
    .action-cell { display: flex; gap: 0.35rem; flex-wrap: wrap; }

    /* ─── Empty State ────────────────────────────────────────── */
    .empty-state {
      text-align: center;
      padding: 3rem 1rem;
      color: var(--text-muted);
    }
    .empty-state-icon { font-size: 2.5rem; margin-bottom: 0.75rem; }
    .empty-state-title { font-size: 0.9rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.3rem; }
    .empty-state-desc { font-size: 0.8rem; }

    /* ─── Audit Logs ─────────────────────────────────────────── */
    .log-level-tag {
      display: inline-block;
      padding: 0.18rem 0.55rem;
      border-radius: 5px;
      font-size: 0.7rem;
      font-weight: 700;
      letter-spacing: 0.04em;
    }
    .log-info { background: var(--blue-light); color: var(--blue-text); }
    .log-warning { background: var(--yellow-light); color: var(--yellow-text); }
    .log-error { background: var(--red-light); color: var(--red-text); }
    [data-theme="dark"] .log-info { color: var(--blue); }
    [data-theme="dark"] .log-warning { color: var(--yellow); }
    [data-theme="dark"] .log-error { color: var(--red); }

    .log-activity { font-size: 0.84rem; color: var(--text-primary); margin-bottom: 0.1rem; }
    .log-module { font-size: 0.73rem; color: var(--text-muted); }
    .log-time { font-size: 0.78rem; color: var(--text-secondary); }
    .log-ip { font-size: 0.75rem; color: var(--text-muted); font-family: 'Courier New', monospace; }

    /* ─── System Config ──────────────────────────────────────── */
    .config-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 1rem;
    }
    .config-card {
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      padding: 1.5rem;
      box-shadow: var(--shadow-card);
    }
    .config-card-header { display: flex; align-items: center; gap: 0.65rem; margin-bottom: 1.1rem; }
    .config-card-icon { font-size: 1.25rem; }
    .config-card-title { font-size: 0.9rem; font-weight: 600; color: var(--text-primary); }
    .config-card-desc { font-size: 0.775rem; color: var(--text-muted); margin-top: 0.1rem; }

    .access-alert {
      background: linear-gradient(135deg, var(--yellow-light), var(--bg-card-inner));
      border: 1px solid var(--yellow);
      border-radius: var(--radius-sm);
      padding: 0.85rem 1rem;
      display: flex;
      gap: 0.65rem;
      margin-bottom: 0.75rem;
    }
    .access-alert-icon { font-size: 1.1rem; flex-shrink: 0; }
    .access-alert-text { font-size: 0.82rem; color: var(--yellow-text); line-height: 1.5; }
    [data-theme="dark"] .access-alert-text { color: var(--yellow); }

    .integration-list { display: flex; flex-direction: column; gap: 0.6rem; }
    .integration-item {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0.65rem 0.9rem;
      background: var(--bg-card-inner);
      border: 1px solid var(--border-soft);
      border-radius: var(--radius-sm);
    }
    .integration-name { font-size: 0.855rem; font-weight: 500; color: var(--text-primary); }
    .integration-status { font-size: 0.75rem; font-weight: 600; display: flex; align-items: center; gap: 0.3rem; }
    .status-connected { color: var(--accent); }
    .status-degraded  { color: var(--yellow); }
    .status-error     { color: var(--red); }

    .env-list { display: flex; flex-direction: column; gap: 0.5rem; }
    .env-item { display: flex; justify-content: space-between; align-items: center; padding: 0.5rem 0; border-bottom: 1px solid var(--border-soft); }
    .env-item:last-child { border-bottom: none; }
    .env-key { font-size: 0.8rem; color: var(--text-muted); font-weight: 500; }
    .env-val { font-size: 0.82rem; color: var(--text-primary); font-weight: 600; }

    .system-status-list { display: flex; flex-direction: column; gap: 0.5rem; }
    .sys-item { display: flex; align-items: center; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid var(--border-soft); }
    .sys-item:last-child { border-bottom: none; }
    .sys-name { font-size: 0.82rem; color: var(--text-secondary); }
    .sys-val { font-size: 0.82rem; font-weight: 600; color: var(--text-primary); display: flex; align-items: center; gap: 0.3rem; }

    /* ─── Toast ──────────────────────────────────────────────── */
    #toast-container {
      position: fixed;
      bottom: 1.5rem;
      right: 1.5rem;
      z-index: 9999;
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
    }
    .toast {
      display: flex;
      align-items: center;
      gap: 0.65rem;
      padding: 0.75rem 1.1rem;
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: var(--radius-sm);
      box-shadow: 0 4px 20px rgba(0,0,0,0.15);
      font-size: 0.85rem;
      color: var(--text-primary);
      min-width: 240px;
      max-width: 320px;
      animation: toastIn 0.3s ease;
    }
    .toast.removing { animation: toastOut 0.25s ease forwards; }
    @keyframes toastIn { from { opacity:0; transform: translateX(20px); } to { opacity:1; transform: translateX(0); } }
    @keyframes toastOut { from { opacity:1; transform: translateX(0); } to { opacity:0; transform: translateX(20px); } }
    .toast-icon { font-size: 1rem; flex-shrink: 0; }
    .toast-text { flex: 1; font-weight: 500; }
    .toast-close { background: none; border: none; color: var(--text-muted); font-size: 1rem; padding: 0; cursor: pointer; }

    /* ─── Sidebar Overlay ────────────────────────────────────── */
    .sidebar-overlay {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,0.4);
      z-index: 190;
    }

    /* ─── Responsive ─────────────────────────────────────────── */
    @media (max-width: 1100px) {
      .stats-grid { grid-template-columns: repeat(2, 1fr); }
      .config-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 768px) {
      :root { --sidebar-w: 0px; }
      .sidebar {
        width: 240px;
        transform: translateX(-100%);
      }
      .sidebar.open { transform: translateX(0); }
      .sidebar-overlay { display: block; opacity: 0; pointer-events: none; transition: opacity 0.3s; }
      .sidebar-overlay.open { opacity: 1; pointer-events: all; }
      .main-area { margin-left: 0; }
      .menu-toggle-btn { display: flex; }
      .topbar-page-title { display: block; }
      .stats-grid { grid-template-columns: 1fr 1fr; gap: 0.75rem; }
      .page-content { padding: 1.25rem 1rem; }
      .search-box input { width: 140px; }
    }
    @media (max-width: 480px) {
      .stats-grid { grid-template-columns: 1fr; }
      .topbar { padding: 0 1rem; }
      .card-header-row { padding: 0.9rem 1rem; }
      td, th { padding: 0.7rem 0.75rem; }
    }
  </style>
</head>
<body>

<!-- Toast Container -->
<div id="toast-container" aria-live="polite" role="status"></div>

<!-- Sidebar Overlay (mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- App Shell -->
<div class="app-shell">

  <!-- ══ SIDEBAR ══════════════════════════════════════════════ -->
  <aside class="sidebar" id="sidebar" aria-label="Navigasi Admin">
    <a href="index.html" class="sidebar-brand" aria-label="UMKM Insight Home">
      <div class="brand-logo" aria-hidden="true">
        <svg viewBox="0 0 24 24"><path d="M3 3h7v7H3V3zm0 11h7v7H3v-7zm11-11h7v7h-7V3zm3 11a4 4 0 1 1 0 8 4 4 0 0 1 0-8z"/></svg>
      </div>
      <div class="brand-info">
        <div class="brand-name">UMKM Insight</div>
        <div class="brand-role">Admin Panel</div>
      </div>
    </a>

    <nav class="sidebar-nav" aria-label="Menu Admin">
      <div class="nav-section-label">Menu Utama</div>
      <button class="nav-item active" data-section="user-mgmt" id="nav-user-mgmt" aria-current="page">
        <span class="nav-icon" aria-hidden="true">👥</span>
        Manajemen User
      </button>
      <button class="nav-item" data-section="audit-logs" id="nav-audit-logs">
        <span class="nav-icon" aria-hidden="true">🗂️</span>
        Audit Logs
        <span class="nav-badge">4</span>
      </button>
      <button class="nav-item" data-section="sys-config" id="nav-sys-config">
        <span class="nav-icon" aria-hidden="true">⚙️</span>
        System Config
      </button>
    </nav>

    <div class="sidebar-footer">
      <div class="user-card">
        <div class="user-avatar" aria-hidden="true">NA</div>
        <div class="user-info">
          <div class="user-name">Nabila Putri</div>
          <div class="user-role-tag">Administrator</div>
        </div>
      </div>
      <a href="logout.php" class="btn-logout">
        <span aria-hidden="true">←</span>
        Keluar
      </a>
    </div>
  </aside>

  <!-- ══ MAIN AREA ════════════════════════════════════════════ -->
  <div class="main-area">

    <!-- Topbar -->
    <header class="topbar" role="banner">
      <div class="topbar-left">
        <button class="menu-toggle-btn" id="menuToggle" aria-label="Buka menu sidebar" aria-expanded="false">☰</button>
        <div>
          <div class="topbar-date" id="topbarDate">Senin, 01 Juni 2026</div>
          <div class="topbar-page-title" id="topbarPageTitle">Manajemen User</div>
        </div>
      </div>
      <div class="topbar-right">
        <button class="notif-btn" aria-label="Notifikasi (ada notifikasi baru)" id="notifBtn">
          🔔
          <span class="notif-dot" aria-hidden="true"></span>
        </button>
        <button class="theme-pill" id="themeToggle" aria-label="Toggle dark mode">
          <span id="toggleIcon">🌙</span>
          <div class="toggle-track" id="toggleTrack">
            <div class="toggle-thumb"></div>
          </div>
        </button>
        <div class="topbar-avatar" title="Nabila Putri — Admin" aria-label="Avatar Nabila Putri">NA</div>
      </div>
    </header>

    <!-- ═══ PAGE CONTENT ══════════════════════════════════════ -->
    <main class="page-content" id="pageContent">

      <!-- ── SECTION 1: MANAJEMEN USER ─────────────────────── -->
      <section class="section-panel active" id="section-user-mgmt" aria-labelledby="heading-user-mgmt">
        <div class="page-header">
          <h2 id="heading-user-mgmt">Ekosistem UMKM Insight</h2>
          <p>Pantau statistik global dan kelola hak akses seluruh pengguna sistem.</p>
        </div>

        <!-- Stats -->
        <div class="stats-grid" role="list" aria-label="Statistik ekosistem">
          <div class="stat-card" role="listitem">
            <div class="stat-top">
              <div class="stat-icon green" aria-hidden="true">👤</div>
              <span class="stat-change up">+12,4%</span>
            </div>
            <div class="stat-value">1.284</div>
            <div class="stat-label">Total Konsumen</div>
          </div>
          <div class="stat-card" role="listitem">
            <div class="stat-top">
              <div class="stat-icon blue" aria-hidden="true">🎧</div>
              <span class="stat-change neutral">+6 aktif</span>
            </div>
            <div class="stat-value">18</div>
            <div class="stat-label">Staff Operator</div>
          </div>
          <div class="stat-card" role="listitem">
            <div class="stat-top">
              <div class="stat-icon yellow" aria-hidden="true">⭐</div>
              <span class="stat-change neutral">327 paket</span>
            </div>
            <div class="stat-value">327</div>
            <div class="stat-label">User Premium</div>
          </div>
          <div class="stat-card" role="listitem">
            <div class="stat-top">
              <div class="stat-icon purple" aria-hidden="true">💰</div>
              <span class="stat-change up">+42,3 Jt</span>
            </div>
            <div class="stat-value" style="font-size:1.25rem;">Rp 487,65 Jt</div>
            <div class="stat-label">Pendapatan Ekosistem</div>
          </div>
        </div>

        <!-- User Table Card -->
        <div class="card">
          <div class="card-header-row">
            <div class="card-title-row">
              <h3>Daftar Pengguna &amp; Staff</h3>
              <p>Kelola akses dan tier seluruh pengguna platform</p>
            </div>
            <div class="toolbar">
              <div class="search-box" role="search">
                <span class="search-icon" aria-hidden="true">🔍</span>
                <input type="text" id="userSearch" placeholder="Cari nama, email, bisnis…" aria-label="Cari pengguna" />
              </div>
              <select class="filter-select" id="roleFilter" aria-label="Filter role">
                <option value="">Semua Role</option>
                <option value="client">Client</option>
                <option value="operator">Operator</option>
                <option value="admin">Admin</option>
              </select>
              <button class="btn btn-primary" id="blastBtn" aria-label="Kirim blast message ke semua pengguna">
                📣 Blast Message
              </button>
            </div>
          </div>
          <div class="table-wrap">
            <table id="userTable" aria-label="Tabel pengguna sistem">
              <thead>
                <tr>
                  <th scope="col">Nama / Bisnis</th>
                  <th scope="col">Role</th>
                  <th scope="col">Email</th>
                  <th scope="col">Tier</th>
                  <th scope="col">Status</th>
                  <th scope="col">Aksi</th>
                </tr>
              </thead>
              <tbody id="userTableBody"></tbody>
            </table>
            <div id="userEmptyState" class="empty-state" style="display:none;" aria-live="polite">
              <div class="empty-state-icon" aria-hidden="true">🔍</div>
              <div class="empty-state-title">Tidak ada hasil ditemukan</div>
              <div class="empty-state-desc">Coba ubah filter atau kata kunci pencarian</div>
            </div>
          </div>
        </div>
      </section>

      <!-- ── SECTION 2: AUDIT LOGS ──────────────────────────── -->
      <section class="section-panel" id="section-audit-logs" aria-labelledby="heading-audit-logs">
        <div class="page-header">
          <h2 id="heading-audit-logs">System Audit Logs</h2>
          <p>Lacak dan pantau semua aktivitas kritis di dalam sistem UMKM Insight.</p>
        </div>

        <div class="card">
          <div class="card-header-row">
            <div class="card-title-row">
              <h3>Riwayat Aktivitas Sistem</h3>
              <p>4 aktivitas tercatat hari ini</p>
            </div>
            <div class="toolbar">
              <div class="search-box" role="search">
                <span class="search-icon" aria-hidden="true">🔍</span>
                <input type="text" id="logSearch" placeholder="Cari log aktivitas…" aria-label="Cari log" />
              </div>
              <select class="filter-select" id="logModuleFilter" aria-label="Filter modul">
                <option value="">Semua Modul</option>
                <option value="user management">User Management</option>
                <option value="operator panel">Operator Panel</option>
                <option value="integration">Integration</option>
                <option value="broadcast">Broadcast</option>
              </select>
              <select class="filter-select" id="logLevelFilter" aria-label="Filter level log">
                <option value="">Semua Level</option>
                <option value="info">INFO</option>
                <option value="warning">WARNING</option>
                <option value="error">ERROR</option>
              </select>
            </div>
          </div>
          <div class="table-wrap">
            <table id="logTable" aria-label="Tabel audit log">
              <thead>
                <tr>
                  <th scope="col">Waktu</th>
                  <th scope="col">Aktor</th>
                  <th scope="col">Aktivitas</th>
                  <th scope="col">Level</th>
                  <th scope="col">IP Address</th>
                </tr>
              </thead>
              <tbody id="logTableBody"></tbody>
            </table>
            <div id="logEmptyState" class="empty-state" style="display:none;" aria-live="polite">
              <div class="empty-state-icon" aria-hidden="true">📋</div>
              <div class="empty-state-title">Tidak ada log ditemukan</div>
              <div class="empty-state-desc">Ubah filter atau kata kunci untuk melihat aktivitas</div>
            </div>
          </div>
        </div>
      </section>

      <!-- ── SECTION 3: SYSTEM CONFIG ───────────────────────── -->
      <section class="section-panel" id="section-sys-config" aria-labelledby="heading-sys-config">
        <div class="page-header">
          <h2 id="heading-sys-config">Pengaturan Sistem Utama</h2>
          <p>Konfigurasi variabel lingkungan, koneksi API pihak ketiga, dan pengaturan inti aplikasi.</p>
        </div>

        <div class="access-alert" role="alert">
          <span class="access-alert-icon" aria-hidden="true">⚠️</span>
          <div class="access-alert-text">
            <strong>Akses Terbatas.</strong> Konfigurasi inti hanya dapat diubah melalui akses server langsung untuk menjaga stabilitas sistem.
          </div>
        </div>

        <div class="config-grid">
          <!-- Integrasi Aktif -->
          <div class="config-card">
            <div class="config-card-header">
              <span class="config-card-icon" aria-hidden="true">🔗</span>
              <div>
                <div class="config-card-title">Integrasi Aktif</div>
                <div class="config-card-desc">Koneksi layanan API pihak ketiga</div>
              </div>
            </div>
            <div class="integration-list" role="list" aria-label="Daftar integrasi">
              <div class="integration-item" role="listitem">
                <span class="integration-name">SmartBank API</span>
                <span class="integration-status status-connected" aria-label="Status: Terhubung">
                  <span class="status-dot dot-green" aria-hidden="true"></span> Connected
                </span>
              </div>
              <div class="integration-item" role="listitem">
                <span class="integration-name">Marketplace PasarKita</span>
                <span class="integration-status status-connected" aria-label="Status: Terhubung">
                  <span class="status-dot dot-green" aria-hidden="true"></span> Connected
                </span>
              </div>
              <div class="integration-item" role="listitem">
                <span class="integration-name">SupplierHub</span>
                <span class="integration-status status-degraded" aria-label="Status: Degraded">
                  <span class="status-dot dot-yellow" aria-hidden="true"></span> Degraded
                </span>
              </div>
              <div class="integration-item" role="listitem">
                <span class="integration-name">LogistiKita</span>
                <span class="integration-status status-connected" aria-label="Status: Terhubung">
                  <span class="status-dot dot-green" aria-hidden="true"></span> Connected
                </span>
              </div>
            </div>
          </div>

          <!-- Status Sistem -->
          <div class="config-card">
            <div class="config-card-header">
              <span class="config-card-icon" aria-hidden="true">📊</span>
              <div>
                <div class="config-card-title">Status Sistem</div>
                <div class="config-card-desc">Kondisi layanan dan proses aktif</div>
              </div>
            </div>
            <div class="system-status-list">
              <div class="sys-item">
                <span class="sys-name">API Gateway</span>
                <span class="sys-val"><span class="status-dot dot-green" aria-hidden="true"></span> Operational</span>
              </div>
              <div class="sys-item">
                <span class="sys-name">Database Cluster</span>
                <span class="sys-val"><span class="status-dot dot-green" aria-hidden="true"></span> Operational</span>
              </div>
              <div class="sys-item">
                <span class="sys-name">Scheduler / Cron</span>
                <span class="sys-val"><span class="status-dot dot-green" aria-hidden="true"></span> Running</span>
              </div>
              <div class="sys-item">
                <span class="sys-name">SupplierHub Sync</span>
                <span class="sys-val"><span class="status-dot dot-yellow" aria-hidden="true"></span> Degraded</span>
              </div>
              <div class="sys-item">
                <span class="sys-name">Log Aggregator</span>
                <span class="sys-val"><span class="status-dot dot-green" aria-hidden="true"></span> Operational</span>
              </div>
            </div>
          </div>

          <!-- Environment -->
          <div class="config-card">
            <div class="config-card-header">
              <span class="config-card-icon" aria-hidden="true">🖥️</span>
              <div>
                <div class="config-card-title">Environment</div>
                <div class="config-card-desc">Variabel lingkungan dan versi sistem</div>
              </div>
            </div>
            <div class="env-list">
              <div class="env-item">
                <span class="env-key">Environment</span>
                <span class="env-val">Production Simulation</span>
              </div>
              <div class="env-item">
                <span class="env-key">Build Version</span>
                <span class="env-val">v1.8.4</span>
              </div>
              <div class="env-item">
                <span class="env-key">Last Sync</span>
                <span class="env-val">01 Jun 2026, 10:30 WIB</span>
              </div>
              <div class="env-item">
                <span class="env-key">Region</span>
                <span class="env-val">ap-southeast-1</span>
              </div>
              <div class="env-item">
                <span class="env-key">Node Instance</span>
                <span class="env-val">prod-node-07</span>
              </div>
            </div>
          </div>

          <!-- Akses Terbatas -->
          <div class="config-card" style="background: linear-gradient(135deg, var(--bg-card), var(--bg-card-inner));">
            <div class="config-card-header">
              <span class="config-card-icon" aria-hidden="true">🔐</span>
              <div>
                <div class="config-card-title">Akses Terbatas</div>
                <div class="config-card-desc">Kebijakan keamanan konfigurasi</div>
              </div>
            </div>
            <p style="font-size:0.83rem; color:var(--text-secondary); line-height:1.65; margin-bottom:1rem;">
              Perubahan konfigurasi inti seperti database credentials, API keys, dan variabel environment produksi hanya dapat dilakukan melalui akses langsung ke server dengan otorisasi penuh.
            </p>
            <div style="display:flex; flex-direction:column; gap:0.5rem;">
              <div style="display:flex; align-items:center; gap:0.5rem; font-size:0.8rem; color:var(--text-muted);">
                <span>🔒</span> Enkripsi end-to-end aktif
              </div>
              <div style="display:flex; align-items:center; gap:0.5rem; font-size:0.8rem; color:var(--text-muted);">
                <span>🛡️</span> 2FA wajib untuk akses konfigurasi
              </div>
              <div style="display:flex; align-items:center; gap:0.5rem; font-size:0.8rem; color:var(--text-muted);">
                <span>📋</span> Semua perubahan tercatat di audit log
              </div>
            </div>
          </div>
        </div>
      </section>

    </main>
  </div><!-- /main-area -->
</div><!-- /app-shell -->

<script>
  'use strict';

  // ─── Mock Data ───────────────────────────────────────────────
  const USERS = [
    { id:1, name:'Budi Santoso',  business:'Toko Berkah Utama',         role:'client',   email:'budi@tokoberkah.co.id',  tier:'Free',     status:'Aktif',           avatarClass:'a-green',  initials:'BS' },
    { id:2, name:'Sari Dewi',     business:'Rajutan Sari',               role:'client',   email:'sari@rajutansari.id',    tier:'Premium',  status:'Aktif',           avatarClass:'a-blue',   initials:'SD' },
    { id:3, name:'Raka Pratama',  business:'Kopi Senja Nusantara',       role:'client',   email:'raka@kopisenja.id',      tier:'Premium',  status:'Aktif',           avatarClass:'a-orange', initials:'RP' },
    { id:4, name:'Lina Marlina',  business:'Dapur Nenek Lina',           role:'client',   email:'lina@dapurnenek.co.id',  tier:'Free',     status:'Perlu Verifikasi',avatarClass:'a-purple', initials:'LM' },
    { id:5, name:'Jaya Saputra',  business:'Jaya Operasional',           role:'operator', email:'jaya@umkminsight.id',    tier:'Internal', status:'Aktif',           avatarClass:'a-teal',   initials:'JS' },
    { id:6, name:'Nabila Putri',  business:'Nabila Admin',               role:'admin',    email:'nabila@umkminsight.id',  tier:'Internal', status:'Aktif',           avatarClass:'a-rose',   initials:'NA' },
  ];

  const AUDIT_LOGS = [
    { id:1, time:'01 Jun 2026, 09:12', actor:'Nabila Putri',    activity:'Mengubah tier Rajutan Sari menjadi Premium',              module:'User Management', level:'INFO',    ip:'103.147.22.18' },
    { id:2, time:'01 Jun 2026, 09:35', actor:'Jaya Saputra',    activity:'Menyetujui pengajuan premium Toko Berkah Utama',          module:'Operator Panel',  level:'INFO',    ip:'114.5.91.20'   },
    { id:3, time:'01 Jun 2026, 10:01', actor:'System',          activity:'Gagal sinkronisasi data Marketplace PasarKita',           module:'Integration',     level:'WARNING', ip:'10.0.0.8'      },
    { id:4, time:'01 Jun 2026, 10:18', actor:'Admin System',    activity:'Mengirim blast message ke 327 user premium',              module:'Broadcast',       level:'INFO',    ip:'36.72.18.40'   },
  ];

  // ─── Theme ───────────────────────────────────────────────────
  const html = document.documentElement;

  function applyTheme(theme) {
    html.setAttribute('data-theme', theme);
    const isDark = theme === 'dark';
    document.getElementById('toggleTrack').classList.toggle('active', isDark);
    document.getElementById('toggleIcon').textContent = isDark ? '☀️' : '🌙';
  }

  function initTheme() {
    applyTheme(localStorage.getItem('umkm-theme') || 'light');
  }

  document.getElementById('themeToggle').addEventListener('click', () => {
    const next = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
    localStorage.setItem('umkm-theme', next);
    applyTheme(next);
  });

  // ─── Toast ────────────────────────────────────────────────────
  function showToast(message, icon = '✅') {
    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');
    toast.className = 'toast';
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
      <span class="toast-icon" aria-hidden="true">${icon}</span>
      <span class="toast-text">${message}</span>
      <button class="toast-close" aria-label="Tutup notifikasi">✕</button>
    `;
    toast.querySelector('.toast-close').addEventListener('click', () => removeToast(toast));
    container.appendChild(toast);
    setTimeout(() => removeToast(toast), 3500);
  }

  function removeToast(toast) {
    if (!toast.parentElement) return;
    toast.classList.add('removing');
    setTimeout(() => toast.remove(), 260);
  }

  // ─── Sidebar Navigation ───────────────────────────────────────
  const sections = ['user-mgmt', 'audit-logs', 'sys-config'];
  const sectionTitles = {
    'user-mgmt':   'Manajemen User',
    'audit-logs':  'Audit Logs',
    'sys-config':  'System Config',
  };

  function activateSection(id) {
    sections.forEach(s => {
      const panel = document.getElementById(`section-${s}`);
      const navBtn = document.getElementById(`nav-${s}`);
      const isActive = s === id;
      panel.classList.toggle('active', isActive);
      navBtn.classList.toggle('active', isActive);
      navBtn.setAttribute('aria-current', isActive ? 'page' : 'false');
    });
    document.getElementById('topbarPageTitle').textContent = sectionTitles[id] || '';
    // Close sidebar on mobile after nav
    closeSidebar();
  }

  sections.forEach(s => {
    document.getElementById(`nav-${s}`).addEventListener('click', () => activateSection(s));
  });

  // ─── Mobile Sidebar ───────────────────────────────────────────
  const sidebar = document.getElementById('sidebar');
  const sidebarOverlay = document.getElementById('sidebarOverlay');
  const menuToggle = document.getElementById('menuToggle');

  function openSidebar() {
    sidebar.classList.add('open');
    sidebarOverlay.classList.add('open');
    menuToggle.setAttribute('aria-expanded', 'true');
  }
  function closeSidebar() {
    sidebar.classList.remove('open');
    sidebarOverlay.classList.remove('open');
    menuToggle.setAttribute('aria-expanded', 'false');
  }
  menuToggle.addEventListener('click', () => {
    sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
  });
  sidebarOverlay.addEventListener('click', closeSidebar);

  // ─── Render Users ─────────────────────────────────────────────
  function roleBadge(role) {
    const map = {
      client:   ['badge-green', 'Client'],
      operator: ['badge-blue',  'Operator'],
      admin:    ['badge-purple','Admin'],
    };
    const [cls, label] = map[role] || ['badge-gray', role];
    return `<span class="badge ${cls}">${label}</span>`;
  }

  function tierBadge(tier) {
    const map = {
      'Free':     'badge-gray',
      'Premium':  'badge-yellow',
      'Internal': 'badge-blue',
    };
    return `<span class="badge ${map[tier] || 'badge-gray'}">${tier}</span>`;
  }

  function statusBadge(status) {
    if (status === 'Aktif') return `<span class="badge badge-green"><span class="status-dot dot-green"></span> Aktif</span>`;
    if (status === 'Perlu Verifikasi') return `<span class="badge badge-yellow"><span class="status-dot dot-yellow"></span> Verifikasi</span>`;
    return `<span class="badge badge-red">${status}</span>`;
  }

  function renderUsers(list) {
    const tbody = document.getElementById('userTableBody');
    const empty = document.getElementById('userEmptyState');
    if (list.length === 0) {
      tbody.innerHTML = '';
      empty.style.display = '';
      return;
    }
    empty.style.display = 'none';
    tbody.innerHTML = list.map(u => `
      <tr>
        <td>
          <div class="user-cell">
            <div class="mini-avatar ${u.avatarClass}" aria-hidden="true">${u.initials}</div>
            <div class="user-cell-info">
              <div class="user-cell-name">${u.name}</div>
              <div class="user-cell-sub">${u.business}</div>
            </div>
          </div>
        </td>
        <td>${roleBadge(u.role)}</td>
        <td style="font-size:0.8rem;color:var(--text-secondary);">${u.email}</td>
        <td>${tierBadge(u.tier)}</td>
        <td>${statusBadge(u.status)}</td>
        <td>
          <div class="action-cell">
            <button class="btn btn-sm btn-info"    onclick="showToast('Membuka detail ${u.name}','👤')">Detail</button>
            <button class="btn btn-sm btn-primary" onclick="showToast('${u.name} di-upgrade ke Premium','⬆️')">Upgrade</button>
            <button class="btn btn-sm btn-warn"    onclick="showToast('${u.name} di-downgrade ke Free','⬇️')">Downgrade</button>
            <button class="btn btn-sm btn-danger"  onclick="showToast('Akun ${u.name} ditangguhkan','🚫')">Suspend</button>
          </div>
        </td>
      </tr>
    `).join('');
  }

  function filterUsers() {
    const q    = document.getElementById('userSearch').value.toLowerCase().trim();
    const role = document.getElementById('roleFilter').value;
    const filtered = USERS.filter(u => {
      const matchQ    = !q || u.name.toLowerCase().includes(q) || u.business.toLowerCase().includes(q) || u.email.toLowerCase().includes(q);
      const matchRole = !role || u.role === role;
      return matchQ && matchRole;
    });
    renderUsers(filtered);
  }

  document.getElementById('userSearch').addEventListener('input', filterUsers);
  document.getElementById('roleFilter').addEventListener('change', filterUsers);
  document.getElementById('blastBtn').addEventListener('click', () => {
    showToast('Blast message berhasil dikirim ke 327 user premium', '📣');
  });
  document.getElementById('notifBtn').addEventListener('click', () => {
    showToast('4 aktivitas audit baru hari ini', '🔔');
  });

  // ─── Render Audit Logs ────────────────────────────────────────
  function levelTag(level) {
    const cls = { INFO:'log-info', WARNING:'log-warning', ERROR:'log-error' }[level] || 'log-info';
    return `<span class="log-level-tag ${cls}">${level}</span>`;
  }

  function renderLogs(list) {
    const tbody = document.getElementById('logTableBody');
    const empty = document.getElementById('logEmptyState');
    if (list.length === 0) {
      tbody.innerHTML = '';
      empty.style.display = '';
      return;
    }
    empty.style.display = 'none';
    tbody.innerHTML = list.map(l => `
      <tr>
        <td><span class="log-time">${l.time}</span></td>
        <td style="font-size:0.84rem;font-weight:600;">${l.actor}</td>
        <td>
          <div class="log-activity">${l.activity}</div>
          <div class="log-module">${l.module}</div>
        </td>
        <td>${levelTag(l.level)}</td>
        <td><span class="log-ip">${l.ip}</span></td>
      </tr>
    `).join('');
  }

  function filterLogs() {
    const q      = document.getElementById('logSearch').value.toLowerCase().trim();
    const module = document.getElementById('logModuleFilter').value.toLowerCase();
    const level  = document.getElementById('logLevelFilter').value.toLowerCase();
    const filtered = AUDIT_LOGS.filter(l => {
      const matchQ      = !q      || l.activity.toLowerCase().includes(q) || l.actor.toLowerCase().includes(q);
      const matchModule = !module || l.module.toLowerCase().includes(module);
      const matchLevel  = !level  || l.level.toLowerCase() === level;
      return matchQ && matchModule && matchLevel;
    });
    renderLogs(filtered);
  }

  document.getElementById('logSearch').addEventListener('input', filterLogs);
  document.getElementById('logModuleFilter').addEventListener('change', filterLogs);
  document.getElementById('logLevelFilter').addEventListener('change', filterLogs);

  // ─── Date ─────────────────────────────────────────────────────
  function setDate() {
    const days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    const months = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    const d = new Date();
    document.getElementById('topbarDate').textContent = `${days[d.getDay()]}, ${String(d.getDate()).padStart(2,'0')} ${months[d.getMonth()]} ${d.getFullYear()}`;
  }

  // ─── Init ─────────────────────────────────────────────────────
  function init() {
    initTheme();
    setDate();
    renderUsers(USERS);
    renderLogs(AUDIT_LOGS);
  }

  init();
</script>
</body>
</html>
