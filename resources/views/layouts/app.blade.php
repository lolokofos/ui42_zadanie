<!doctype html>
<html lang="sk">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Databáza obcí' }}</title>
    @vite(['resources/js/app.js'])
</head>
<body>
    <header class="border-bottom border-2">
        <div class="container">
            <div class="row align-items-center gy-2 py-3">
                <div class="col-12 col-md-auto d-flex justify-content-center justify-content-md-start">
                    <a href="{{ url('/') }}" class="text-decoration-none text-reset d-flex flex-column">
                        <div class="d-flex align-items-center justify-content-center justify-content-md-start">
                        <span>
                            <svg width="45" height="45" viewBox="0 0 16 16" aria-hidden="true">
                                <defs>
                                    <clipPath id="gem-left">
                                        <rect x="0" y="0" width="8" height="16"></rect>
                                    </clipPath>
                                    <clipPath id="gem-right">
                                        <rect x="8" y="0" width="8" height="16"></rect>
                                    </clipPath>
                                </defs>
                                <path clip-path="url(#gem-left)" fill="#55acf3" d="M3.1.7a.5.5 0 0 1 .4-.2h9a.5.5 0 0 1 .4.2l2.976 3.974c.149.185.156.45.01.644L8.4 15.3a.5.5 0 0 1-.8 0L.1 5.3a.5.5 0 0 1 0-.6zm11.386 3.785-1.806-2.41-.776 2.413zm-3.633.004.961-2.989H4.186l.963 2.995zM5.47 5.495 8 13.366l2.532-7.876zm-1.371-.999-.78-2.422-1.818 2.425zM1.499 5.5l5.113 6.817-2.192-6.82zm7.889 6.817 5.123-6.83-2.928.002z"/>
                                <path clip-path="url(#gem-right)" fill="#49a942" d="M3.1.7a.5.5 0 0 1 .4-.2h9a.5.5 0 0 1 .4.2l2.976 3.974c.149.185.156.45.01.644L8.4 15.3a.5.5 0 0 1-.8 0L.1 5.3a.5.5 0 0 1 0-.6zm11.386 3.785-1.806-2.41-.776 2.413zm-3.633.004.961-2.989H4.186l.963 2.995zM5.47 5.495 8 13.366l2.532-7.876zm-1.371-.999-.78-2.422-1.818 2.425zM1.499 5.5l5.113 6.817-2.192-6.82zm7.889 6.817 5.123-6.83-2.928.002z"/>
                            </svg>
                        </span>
                        <div class="text-uppercase fw-bold opacity-50" >
                            <span style="color:#c5c5c5;font-size:2.2em; letter-spacing:-1px">EFECTIVE</span>
                        </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-baseline fw-bold" style="letter-spacing: -1px">
                        <span style="color:#55acf3;">CLEANING</span>
                        <span class="opacity-75" style="color:#c5c5c5;font-size:0.7em;font-weight:700;">AND</span>
                        <span style="color:#49a942;">GARDENING</span>
                        </div>
                    </a>
                </div>
                <div class="col-12 col-md col-lg-auto ms-lg-auto">
                    <div class="d-flex flex-column flex-md-row align-items-center justify-content-center justify-content-md-between justify-content-lg-end gap-2 gap-md-3 flex-md-nowrap">
                        <a class="text-decoration-none text-primary text-nowrap me-md-3">Kontakty a čísla na oddelenia</a>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-link text-secondary text-decoration-none dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                EN
                            </button>
                            <ul class="dropdown-menu">
                                <li><span class="dropdown-item">SK</span></li>
                                <li><span class="dropdown-item">EN</span></li>
                            </ul>
                        </div>
                        <div class="position-relative">
                            <input class="form-control form-control-sm" style="width:200px;padding-right:28px;" type="text" placeholder="">
                            <span class="position-absolute top-50 end-0 translate-middle-y me-2 text-muted">
                                <svg width="14" height="14" viewBox="0 0 24 24" aria-hidden="true">
                                    <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="2" fill="none"></circle>
                                    <line x1="16.5" y1="16.5" x2="22" y2="22" stroke="currentColor" stroke-width="2"></line>
                                </svg>
                            </span>
                        </div>
                        <button class="btn btn-sm px-4 w-100 w-md-auto" style="background:#12c3a3;border-color:#12c3a3;color:#fff;max-width:200px;">Prihlásenie</button>
                    </div>
                </div>
                <div class="d-md-none">
                    <div class="dropdown d-flex justify-content-center">
                        <button class="btn btn-outline-secondary dropdown-toggle" style="width:200px;" type="button" data-bs-toggle="dropdown">
                            Menu
                        </button>
                        <ul class="dropdown-menu text-center start-50 translate-middle-x top-100">
                            <li><a class="dropdown-item text-center" href="#">O nás</a></li>
                            <li><a class="dropdown-item text-center" href="#">Zoznam miest</a></li>
                            <li><a class="dropdown-item text-center" href="#">Inšpekcia</a></li>
                            <li><a class="dropdown-item text-center" href="#">Kontakt</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <nav class="d-none d-md-flex gap-5 pb-3">
                <a class="text-decoration-none text-dark" href="#">O nás</a>
                <a class="text-decoration-none text-dark" href="#">Zoznam miest</a>
                <a class="text-decoration-none text-dark" href="#">Inšpekcia</a>
                <a class="text-decoration-none text-dark" href="#">Kontakt</a>
            </nav>

        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="bg-light-2 py-5 text-muted small">
        <div class="container">
            <div class="row g-4">
                <div class="col-6 col-md-3 d-flex flex-column gap-1">
                    <h6 class="fw-bold small">ADRESA A KONTAKT</h6>
                    <div>ŠÚKL</div>
                    <div>Kvetná 11</div>
                    <div>825 08 Bratislava 26</div>
                    <div>Ústredňa:</div>
                    <div>+421-2-50701 111</div>
                    <h6 class="mt-3 fw-bold small">KONTAKTY</h6>
                    <div>telefónne čísla</div>
                    <div>adresa</div>
                    <div>úradné hodiny</div>
                    <div>bankové spustenie</div>
                    <h6 class="mt-3 fw-bold small">INFORMÁCIE PRE VEREJNOSŤ</h6>
                    <div>Zoznam vyvezených liekov</div>
                    <div>MZ SR</div>
                    <div>Národný portál zdravia</div>
                </div>
                <div class="col-6 col-md-3 d-flex flex-column gap-1">
                    <h6 class="fw-bold small">O NÁS</h6>
                    <div>Dotazníky</div>
                    <div>Hlavní predstavitelia</div>
                    <div>Základné dokumenty</div>
                    <div>Zmluvy za ŠÚKL</div>
                    <div>História a súčasnosť</div>
                    <div>Národná spolupráca</div>
                    <div>Medzinárodná spolupráca</div>
                    <div>Poradné orgány</div>
                    <div>Legislatíva</div>
                    <div>Priestupky a iné správne delikty</div>
                    <div>Zoznam dlžníkov</div>
                    <div>Sadzobník ŠÚKL</div>
                    <div>Verejné obstarávanie</div>
                    <div>Vzdelávacie akcie a prezentácie</div>
                    <div>Konzultácie</div>
                    <div>Voľné pracovné miesta (0)</div>
                    <div>Poskytovanie informácií</div>
                    <div>Sťažnosti a petície</div>
                </div>
                <div class="col-6 col-md-3 d-flex flex-column gap-1">
                    <h6 class="fw-bold small">MÉDIÁ</h6>
                    <div>Tlačové správy</div>
                    <div>Lieky v médiách</div>
                    <div>Kontakt pre médiá</div>
                    <h6 class="mt-3 fw-bold small">DATABÁZY A SERVIS</h6>
                    <div>Databáza liekov a zdravotníckych pomôcok</div>
                    <div>Iné zoznamy</div>
                    <div>Kontaktný formulár</div>
                    <div>Mapa stránok</div>
                    <div>A - Z index</div>
                    <div>Linky</div>
                    <div>RSS</div>
                    <div>Doplnok pre internetový prehliadač</div>
                    <div>Prehliadače formátov</div>
                </div>
                <div class="col-6 col-md-3 d-flex flex-column gap-1">
                    <h6 class="fw-bold small">DROGOVÉ PREKURZORY</h6>
                    <div>Aktuality</div>
                    <div>Legislatíva</div>
                    <div>Pokyny</div>
                    <div>Kontakt</div>
                    <h6 class="mt-3 fw-bold small">INÉ</h6>
                    <div>Linky</div>
                    <div>Mapa stránok</div>
                    <div>FAQ</div>
                    <div>Podmienky používania</div>
                    <div class="mt-3 text-primary fw-bold">RAPID ALERT SYSTEM</div>
                    <div><a class="text-primary text-decoration-underline">Rýchla výstraha vyplývajúca z</a></div>
                    <div><a class="text-primary text-decoration-underline">nedostatkov v kvalite liekov</a></div>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
