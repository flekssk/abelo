<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$page_title|default:"Мой Блог"}</title>
    <style>
        :root {
            --bg-color: #0f172a;
            --card-bg: #1e293b;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --accent: #38bdf8;
            --accent-hover: #7dd3fc;
            --border: #334155;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background-color: var(--bg-color); color: var(--text-main); line-height: 1.5; }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }

        /* Header */
        header { background-color: var(--card-bg); border-bottom: 1px solid var(--border); padding: 20px 0; position: sticky; top: 0; z-index: 100; }
        .header-box { display: flex; justify-content: space-between; align-items: center; }
        .logo { font-size: 24px; font-weight: bold; color: var(--text-main); text-decoration: none; }
        .logo span { color: var(--accent); }
        .nav-links { display: flex; gap: 20px; list-style: none; }
        .nav-links a { color: var(--text-muted); text-decoration: none; font-size: 16px; transition: color 0.2s; }
        .nav-links a:hover { color: var(--accent); }

        /* Blog Grid System */
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 30px; margin-bottom: 40px; }
        .card { background-color: var(--card-bg); border: 1px solid var(--border); border-radius: 12px; overflow: hidden; display: flex; flex-direction: column; transition: transform 0.2s, box-shadow 0.2s; }
        .card:hover { transform: translateY(-4px); box-shadow: 0 10px 20px rgba(0,0,0,0.3); }
        .card-img { width: 100%; height: 200px; object-fit: cover; background-color: #000; }
        .card-body { padding: 20px; display: flex; flex-direction: column; flex-grow: 1; }
        .card-title { font-size: 20px; margin-bottom: 10px; color: var(--text-main); }
        .card-text { color: var(--text-muted); font-size: 14px; margin-bottom: 15px; flex-grow: 1; }
        .card-meta { display: flex; justify-content: space-between; font-size: 12px; color: var(--text-muted); border-top: 1px solid var(--border); padding-top: 15px; }

        /* Buttons & Badges */
        .btn { display: inline-block; background-color: var(--accent); color: #000; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 14px; transition: background-color 0.2s; text-align: center;}
        .btn:hover { background-color: var(--accent-hover); }
        .btn-outline { background-color: transparent; color: var(--accent); border: 1px solid var(--accent); }
        .btn-outline:hover { background-color: rgba(56, 189, 248, 0.1); }
        .badge { display: inline-block; background: rgba(56, 189, 248, 0.1); color: var(--accent); padding: 4px 8px; border-radius: 4px; font-size: 12px; margin-right: 5px; margin-bottom: 5px;}

        /* Sections */
        section { padding: 50px 0; }
        .section-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 30px; border-bottom: 1px solid var(--border); padding-bottom: 15px; }
        .section-title { font-size: 28px; }
        .section-desc { color: var(--text-muted); font-size: 16px; margin-top: 5px; }

        footer { background-color: var(--card-bg); border-top: 1px solid var(--border); padding: 30px 0; margin-top: 60px; text-align: center; color: var(--text-muted); font-size: 14px; }
    </style>
</head>
<body>

<header>
    <div class="container header-box">
        <a href="/" class="logo">PHP<span>Blog</span></a>
        <ul class="nav-links">
            <li><a href="/">Главная</a></li>
        </ul>
    </div>
</header>

<main class="container">
    {include file=$content_tpl}
</main>

<footer>
    <div class="container">
        <p>&copy; 2026 PHP Blog. Тестовое задание.</p>
    </div>
</footer>

</body>
</html>
