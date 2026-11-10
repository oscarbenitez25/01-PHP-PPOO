<?php /** Capçalera comuna: <head>, estils i navegació. Espera $titol (opcional). */ ?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($titol ?? 'Biblioteca') ?> · <?= e($biblioteca->getNom()) ?></title>
    <style>
        :root { --bg:#f5f6fa; --card:#fff; --text:#1f2937; --muted:#6b7280; --accent:#1d4e89; --line:#e5e7eb;
                --ok:#15803d; --okbg:#dcfce7; --err:#b91c1c; --errbg:#fee2e2; --warn:#b45309; --warnbg:#fef3c7; }
        @media (prefers-color-scheme: dark) {
            :root { --bg:#111827; --card:#1f2937; --text:#f3f4f6; --muted:#9ca3af; --accent:#7fb3ea; --line:#374151;
                    --ok:#86efac; --okbg:#14532d; --err:#fca5a5; --errbg:#7f1d1d; --warn:#fcd34d; --warnbg:#78350f; }
        }
        * { box-sizing: border-box; }
        body { margin:0; font-family: system-ui, sans-serif; background:var(--bg); color:var(--text); line-height:1.5; }
        .navbar { display:flex; flex-wrap:wrap; gap:.25rem; background:var(--accent); padding:.6rem 1rem; }
        .navbar a { color:#fff; text-decoration:none; padding:.35rem .7rem; border-radius:6px; }
        .navbar a:hover { background:rgba(255,255,255,.2); }
        main { max-width:1000px; margin:1.5rem auto; padding:0 1rem; }
        h1 { margin-top:0; color:var(--accent); } h2 { color:var(--accent); }
        a { color:var(--accent); }
        .table { width:100%; border-collapse:collapse; background:var(--card); border-radius:8px; overflow:hidden; }
        .table th, .table td { padding:.55rem .75rem; text-align:left; border-bottom:1px solid var(--line); }
        .table th { background:var(--accent); color:#fff; }
        .table tr.vencut td { background:var(--errbg); }
        .list-group { list-style:none; padding:0; background:var(--card); border-radius:8px; border:1px solid var(--line); }
        .list-item { padding:.55rem .9rem; border-bottom:1px solid var(--line); }
        .list-item:last-child { border-bottom:0; }
        .stats-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(170px,1fr)); gap:1rem; margin:1rem 0; }
        .stat-card { background:var(--card); border:1px solid var(--line); border-radius:10px; padding:1rem; text-align:center; }
        .stat-card h3 { margin:0 0 .4rem; font-size:.95rem; color:var(--muted); }
        .stat-card .number { font-size:2rem; font-weight:700; color:var(--accent); }
        .badge { display:inline-block; padding:.1rem .55rem; border-radius:999px; font-size:.8rem; font-weight:600; }
        .badge.ok { background:var(--okbg); color:var(--ok); } .badge.err { background:var(--errbg); color:var(--err); }
        .badge.warn { background:var(--warnbg); color:var(--warn); }
        .alert { padding:.8rem 1rem; border-radius:8px; margin:1rem 0; }
        .alert.ok { background:var(--okbg); color:var(--ok); } .alert.err { background:var(--errbg); color:var(--err); }
        .alert.warn { background:var(--warnbg); color:var(--warn); }
        .btn, button { background:var(--accent); color:#fff; border:0; padding:.4rem .8rem; border-radius:6px; text-decoration:none; cursor:pointer; font-size:.95rem; }
        select, input { padding:.35rem; border-radius:6px; border:1px solid var(--line); background:var(--card); color:var(--text); }
        pre, code { background:var(--card); border:1px solid var(--line); border-radius:6px; }
        pre { padding:.8rem; overflow-x:auto; white-space:pre-wrap; word-break:break-all; } code { padding:0 .3rem; }
        .filters a { margin-right:.4rem; }
    </style>
</head>
<body>
<nav class="navbar">
    <a href="index.php">🏠 Inici</a>
    <a href="index.php?page=materials">📚 Materials</a>
    <a href="index.php?page=usuaris">👥 Usuaris</a>
    <a href="index.php?page=prestecs">🔄 Préstecs</a>
    <a href="index.php?page=reserves">📝 Reserves</a>
    <a href="index.php?page=estadistiques">📊 Estadístiques</a>
    <a href="index.php?page=auditoria">🗂 Auditoria</a>
</nav>
<main>
