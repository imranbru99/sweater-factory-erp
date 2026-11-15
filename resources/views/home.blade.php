<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $company }}</title>
    <meta name="description" content="{{ __('factory.home.lead') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,460;9..144,560&family=Noto+Sans+Bengali:wght@400;500;600&family=Outfit:wght@360;480;560&display=swap" rel="stylesheet">
    <style>
        :root {
            --ink: #16130f;
            --ivory: #f3eee6;
            --paper: #faf7f2;
            --forest: #12241c;
            --forest-2: #1b3328;
            --brass: #a68456;
            --brass-2: #e6d3b0;
            --line: rgba(22, 19, 15, .12);
            --muted: #6e655c;
            --serif: "Fraunces", "Noto Sans Bengali", Georgia, serif;
            --sans: "Outfit", "Noto Sans Bengali", sans-serif;
        }
        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            margin: 0;
            color: var(--ink);
            background:
                radial-gradient(1200px 500px at 80% -10%, rgba(166, 132, 86, .16), transparent 60%),
                var(--ivory);
            font-family: var(--sans);
            font-weight: 360;
            line-height: 1.5;
        }
        a { color: inherit; text-decoration: none; }
        .wrap { width: min(1120px, calc(100% - 40px)); margin: 0 auto; }
        .nav {
            position: sticky; top: 0; z-index: 20;
            backdrop-filter: blur(16px);
            background: rgba(243, 238, 230, .86);
            border-bottom: 1px solid var(--line);
        }
        .nav-inner { display: flex; align-items: center; gap: 28px; min-height: 72px; }
        .mark { display: flex; flex-direction: column; line-height: 1.05; margin-right: auto; min-width: 0; }
        .mark strong { font-family: var(--serif); font-weight: 560; font-size: 1.15rem; letter-spacing: -.02em; }
        .mark span { color: var(--muted); font-size: .78rem; letter-spacing: .08em; text-transform: uppercase; margin-top: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        html.bn .mark span { letter-spacing: 0; text-transform: none; font-size: .84rem; }
        html.bn .kicker, html.bn .door small, html.bn .index { letter-spacing: 0; text-transform: none; }
        .nav a.item { font-size: .92rem; color: #3c342c; }
        .nav a.item:hover { color: var(--ink); }
        .langs { display: flex; gap: 6px; }
        .langs button, .btn {
            font-family: inherit; cursor: pointer;
            border-radius: 999px; border: 1px solid var(--line);
            background: transparent; color: var(--ink);
            padding: 8px 14px; font-size: .82rem;
        }
        .langs button[aria-current="true"] { background: var(--forest); color: var(--ivory); border-color: var(--forest); }
        .btn.solid { background: var(--forest); color: var(--ivory); border-color: var(--forest); }
        .btn.solid:hover { background: #0d1b15; }
        .btn.ghost:hover { border-color: var(--ink); }
        .hero { display: grid; grid-template-columns: 1.15fr .85fr; gap: 48px; align-items: end; padding: 72px 0 36px; }
        .kicker { margin: 0 0 18px; color: var(--brass); letter-spacing: .18em; text-transform: uppercase; font-size: .75rem; font-weight: 560; }
        h1 { margin: 0; font-family: var(--serif); font-weight: 460; font-size: clamp(3rem, 6vw, 5.4rem); line-height: .92; letter-spacing: -.035em; }
        .lead { max-width: 38rem; font-size: 1.12rem; color: #3f3832; margin: 22px 0 0; }
        .actions { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 28px; }
        .swatch {
            background: var(--forest);
            color: var(--ivory);
            border-radius: 28px;
            min-height: 420px;
            padding: 18px;
            display: flex; flex-direction: column;
            box-shadow: 0 30px 70px rgba(18, 36, 28, .18);
        }
        .knit {
            flex: 1; border-radius: 18px; position: relative; overflow: hidden;
            display: flex; flex-direction: column; justify-content: flex-end;
            padding: 22px;
            background:
                repeating-linear-gradient(90deg, rgba(255,255,255,.07) 0 1px, transparent 1px 8px),
                repeating-linear-gradient(180deg, rgba(0,0,0,.22) 0 1px, transparent 1px 4px),
                linear-gradient(165deg, #2c4c3c 0%, #12241c 55%, #0b1511 100%);
        }
        .knit p { margin: 0; position: relative; display: flex; align-items: baseline; gap: 8px; }
        .knit em { font-style: normal; font-family: var(--serif); font-weight: 460; font-size: 4.6rem; letter-spacing: -.04em; line-height: .8; }
        .knit span { letter-spacing: .16em; font-size: .85rem; color: var(--brass-2); }
        .knit small { position: relative; margin-top: 8px; color: #d9d1c4; letter-spacing: .12em; text-transform: uppercase; font-size: .72rem; }
        html.bn .knit small { letter-spacing: 0; text-transform: none; font-size: .9rem; }
        .gauges { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 14px; }
        .gauges span {
            border: 1px solid rgba(230, 211, 176, .35);
            color: var(--brass-2);
            border-radius: 999px; padding: 6px 10px; font-size: .78rem; letter-spacing: .08em;
        }
        .band {
            display: grid; grid-template-columns: repeat(3, 1fr);
            border-top: 1px solid var(--line); border-bottom: 1px solid var(--line);
            margin-top: 18px;
        }
        .band div { padding: 22px 18px 22px 0; }
        .band div + div { border-left: 1px solid var(--line); padding-left: 18px; }
        .band strong { display: block; font-family: var(--serif); font-size: 1.55rem; font-weight: 560; letter-spacing: -.03em; }
        .band span { color: var(--muted); font-size: .88rem; }
        section { padding: 84px 0; }
        .section-head { display: flex; justify-content: space-between; gap: 24px; align-items: end; margin-bottom: 28px; }
        h2 { margin: 0; font-family: var(--serif); font-weight: 460; font-size: clamp(2rem, 4vw, 3.2rem); letter-spacing: -.03em; line-height: 1; }
        .cards { display: grid; grid-template-columns: 1.2fr .9fr .9fr; gap: 14px; }
        .card {
            background: rgba(255,255,255,.55);
            border: 1px solid var(--line);
            border-radius: 22px;
            padding: 26px;
            min-height: 220px;
        }
        .card:first-child { background: var(--forest); color: var(--ivory); border-color: var(--forest); }
        .card:first-child p { color: #d7d0c6; }
        .card:first-child .index { color: var(--brass-2); }
        .card h3 { margin: 36px 0 8px; font-family: var(--serif); font-size: 1.7rem; font-weight: 560; }
        .card p { margin: 0; color: #4a433c; }
        .index { color: var(--brass); letter-spacing: .14em; font-size: .75rem; }
        .steps { display: grid; grid-template-columns: repeat(5, 1fr); gap: 12px; }
        .step { padding-top: 16px; border-top: 1px solid var(--ink); }
        .step b { display: block; font-family: var(--serif); font-size: 1.45rem; font-weight: 560; margin: 14px 0 8px; letter-spacing: -.02em; }
        .step span { font-family: var(--serif); color: var(--brass); font-size: 1.05rem; letter-spacing: .04em; }
        .step p { margin: 0; color: #4a433c; font-size: .95rem; }
        .doors { display: grid; grid-template-columns: 1.3fr 1fr 1fr 1fr; gap: 12px; }
        .door {
            display: flex; flex-direction: column; justify-content: space-between;
            min-height: 230px; padding: 22px; border-radius: 22px;
            background: #fffdf9; border: 1px solid var(--line);
            transition: transform .2s ease, border-color .2s ease;
        }
        .door:hover { transform: translateY(-3px); border-color: #c8b69a; }
        .door.primary { background: var(--forest); color: var(--ivory); border-color: var(--forest); }
        .door.primary p { color: #d7d0c6; }
        .door small { letter-spacing: .16em; text-transform: uppercase; font-size: .72rem; color: var(--brass); }
        .door.primary small { color: var(--brass-2); }
        .door h3 { margin: 12px 0 8px; font-family: var(--serif); font-size: 1.55rem; font-weight: 560; }
        .door p { margin: 0; }
        .door em { font-style: normal; margin-top: 18px; font-size: .85rem; letter-spacing: .04em; }
        footer { border-top: 1px solid var(--line); padding: 28px 0 40px; color: var(--muted); display: flex; justify-content: space-between; gap: 12px; }
        @media (max-width: 1100px) {
            .hero { grid-template-columns: 1fr; align-items: stretch; }
            .cards { grid-template-columns: 1fr; }
            .steps, .doors { grid-template-columns: 1fr 1fr; }
            .swatch { min-height: 320px; }
        }
        @media (max-width: 980px) {
            .nav-links { display: none; }
        }
        @media (max-width: 720px) {
            .nav-inner { flex-wrap: wrap; gap: 8px 12px; min-height: 0; padding: 12px 0; }
            .mark { flex: 1 1 100%; }
            .mark strong { font-size: 1.05rem; white-space: normal; }
            .mark span { white-space: normal; overflow: visible; text-overflow: unset; }
            .langs { margin-left: auto; }
            .nav .btn.solid { white-space: nowrap; }
            .steps, .doors, .cards { grid-template-columns: 1fr; }
            .band strong { font-size: 1.2rem; }
            .band span { font-size: .78rem; }
            .band div { padding-right: 10px; }
            .band div + div { padding-left: 10px; }
            .hero { padding-top: 28px; }
            section { padding: 48px 0; }
            footer { flex-direction: column; }
        }
    </style>
</head>
<body>
    <header class="nav">
        <div class="wrap nav-inner">
            <a class="mark" href="/">
                <strong>{{ app()->getLocale() === 'bn' ? $companyBn : $company }}</strong>
                <span>{{ app()->getLocale() === 'bn' ? $company : $companyBn }}</span>
            </a>
            <nav class="nav-links" aria-label="{{ __('factory.home.portals') }}">
                <a class="item" href="#craft">{{ __('factory.home.nav_craft') }}</a>
                <a class="item" href="#process">{{ __('factory.home.nav_process') }}</a>
                <a class="item" href="#access">{{ __('factory.home.nav_access') }}</a>
            </nav>
            <div class="langs">
                <form method="POST" action="{{ route('locale', 'en') }}">@csrf<button type="submit" @if(app()->getLocale() === 'en') aria-current="true" @endif>EN</button></form>
                <form method="POST" action="{{ route('locale', 'bn') }}">@csrf<button type="submit" @if(app()->getLocale() === 'bn') aria-current="true" @endif>বাং</button></form>
            </div>
            <a class="btn solid" href="/admin">{{ __('factory.home.staff') }}</a>
        </div>
    </header>

    <main>
        <div class="wrap hero">
            <div>
                <p class="kicker">{{ __('factory.home.eyebrow') }}</p>
                <h1>{{ __('factory.home.title') }}</h1>
                <p class="lead">{{ __('factory.home.lead') }}</p>
                <div class="actions">
                    <a class="btn solid" href="/admin">{{ __('factory.home.cta_floor') }}</a>
                    <a class="btn ghost" href="#craft">{{ __('factory.home.cta_craft') }}</a>
                </div>
            </div>
            <aside class="swatch" aria-hidden="true">
                <div class="knit">
                    <p><em>12</em><span>GG</span></p>
                    <small>{{ __('factory.home.fashioned') }}</small>
                </div>
                <div class="gauges">
                    <span>3GG</span><span>5GG</span><span>7GG</span><span>12GG</span><span>14GG</span>
                </div>
            </aside>
        </div>

        <div class="wrap band">
            <div><strong>3–14</strong><span>GG</span></div>
            <div><strong>Stoll</strong><span>Shima · Cixing · Protti</span></div>
            <div><strong>{{ app()->getLocale() === 'bn' ? 'গাজীপুর' : 'Gazipur' }}</strong><span>{{ app()->getLocale() === 'bn' ? 'ঢাকা' : 'Dhaka' }}</span></div>
        </div>

        <section id="craft">
            <div class="wrap">
                <div class="section-head">
                    <div>
                        <p class="kicker">{{ __('factory.home.craft_kicker') }}</p>
                        <h2>{{ __('factory.home.craft_title') }}</h2>
                    </div>
                </div>
                <div class="cards">
                    <article class="card">
                        <span class="index">01</span>
                        <h3>{{ __('factory.home.flat') }}</h3>
                        <p>{{ __('factory.home.flat_body') }}</p>
                    </article>
                    <article class="card">
                        <span class="index">02</span>
                        <h3>{{ __('factory.home.fashioned') }}</h3>
                        <p>{{ __('factory.home.fashioned_body') }}</p>
                    </article>
                    <article class="card">
                        <span class="index">03</span>
                        <h3>{{ __('factory.home.jacquard') }}</h3>
                        <p>{{ __('factory.home.jacquard_body') }}</p>
                    </article>
                </div>
            </div>
        </section>

        <section id="process">
            <div class="wrap">
                <div class="section-head">
                    <div>
                        <p class="kicker">{{ __('factory.home.process_kicker') }}</p>
                        <h2>{{ __('factory.home.process_title') }}</h2>
                    </div>
                </div>
                <div class="steps">
                    @foreach (__('factory.home.steps') as $index => $step)
                        <article class="step">
                            <span>{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                            <b>{{ $step[0] }}</b>
                            <p>{{ $step[1] }}</p>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <section id="access">
            <div class="wrap">
                <div class="section-head">
                    <div>
                        <p class="kicker">{{ __('factory.home.access_kicker') }}</p>
                        <h2>{{ __('factory.home.access_title') }}</h2>
                    </div>
                </div>
                <div class="doors">
                    <a class="door primary" href="/admin">
                        <div>
                            <small>{{ __('factory.home.portals') }}</small>
                            <h3>{{ __('factory.home.staff') }}</h3>
                            <p>{{ __('factory.home.staff_body') }}</p>
                        </div>
                        <em>/admin</em>
                    </a>
                    <a class="door" href="/buyer">
                        <div>
                            <small>{{ __('factory.home.portals') }}</small>
                            <h3>{{ __('factory.home.buyer') }}</h3>
                            <p>{{ __('factory.home.buyer_body') }}</p>
                        </div>
                        <em>/buyer</em>
                    </a>
                    <a class="door" href="/supplier">
                        <div>
                            <small>{{ __('factory.home.portals') }}</small>
                            <h3>{{ __('factory.home.supplier') }}</h3>
                            <p>{{ __('factory.home.supplier_body') }}</p>
                        </div>
                        <em>/supplier</em>
                    </a>
                    <a class="door" href="/subcontractor">
                        <div>
                            <small>{{ __('factory.home.portals') }}</small>
                            <h3>{{ __('factory.home.subcontractor') }}</h3>
                            <p>{{ __('factory.home.subcontractor_body') }}</p>
                        </div>
                        <em>/subcontractor</em>
                    </a>
                </div>
            </div>
        </section>
    </main>

    <footer class="wrap">
        <span>{{ $company }} · {{ $companyBn }}</span>
        <span>{{ __('factory.home.footer') }}</span>
    </footer>
</body>
</html>
