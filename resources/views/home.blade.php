@extends('layouts.app', ['title' => 'Vyhľadávanie obcí'])

@section('content')
    <section class="hero text-white py-5">
        <div class="container text-center py-5 my-5">
            <h1 class="display-2 fw-light">Vyhľadať v databáze obcí</h1>
            <div class="mx-auto mt-4 position-relative w-50 shadow-lg">
                <input id="city-search" class="form-control  p-3" type="text" placeholder="Zadajte názov">
                <div id="autocomplete" class="autocomplete d-none"></div>
            </div>
        </div>
    </section>

    <script>
        (function () {
            const input = document.getElementById('city-search');
            const box = document.getElementById('autocomplete');
            let controller = null;

            function clearResults() {
                box.innerHTML = '';
                box.classList.add('d-none');
            }

            input.addEventListener('input', async function () {
                const term = input.value.trim();
                if (term.length < 2) {
                    clearResults();
                    return;
                }

                if (controller) {
                    controller.abort();
                }
                controller = new AbortController();

                try {
                    const res = await fetch(`/search?term=${encodeURIComponent(term)}`, {
                        signal: controller.signal
                    });
                    if (!res.ok) {
                        clearResults();
                        return;
                    }

                    const data = await res.json();
                    if (!Array.isArray(data) || data.length === 0) {
                        clearResults();
                        return;
                    }

                    box.innerHTML = data.map(item =>
                        `<a href="/city/${item.id}">${item.name}</a>`
                    ).join('');
                    box.classList.remove('d-none');
                } catch (err) {
                    if (err.name !== 'AbortError') {
                        clearResults();
                    }
                }
            });

            document.addEventListener('click', function (e) {
                if (!box.contains(e.target) && e.target !== input) {
                    clearResults();
                }
            });
        })();
    </script>
@endsection
