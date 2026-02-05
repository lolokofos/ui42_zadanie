@extends('layouts.app', ['title' => $city->name])

@section('content')
    <div class="container mb-4">
        <h2 class="text-center text-secondary my-4">Detail obce</h2>
    </div>

    <div class="container" style="padding-bottom: 7rem">
        <div class="row g-0 shadow border">
            <div class="col-md-6 bg-light-3" style="padding:5rem;">
                <dl class="row mb-0">
                    @if ($city->mayor_name)
                        <dt class="col-5 fw-bold">{{ $city->mayor_title === 'primator' ? 'Meno primátora:' : 'Meno starostu:' }}</dt>
                        <dd class="col-7">{{ $city->mayor_name }}</dd>
                    @endif

                    @if ($city->address)
                        <dt class="col-5 fw-bold">Adresa obecného úradu:</dt>
                        <dd class="col-7">{{ $city->address }}</dd>
                    @endif

                    @if ($city->phone)
                        <dt class="col-5 fw-bold">Telefón:</dt>
                        <dd class="col-7">{{ $city->phone }}</dd>
                    @endif

                    @if ($city->fax)
                        <dt class="col-5 fw-bold">Fax:</dt>
                        <dd class="col-7">{{ $city->fax }}</dd>
                    @endif

                    @if ($city->email)
                        <dt class="col-5 fw-bold">Email:</dt>
                        <dd class="col-7">
                            @foreach (preg_split('/\s+/', trim((string) $city->email)) as $email)
                                @if ($email !== '')
                                    <div>{{ $email }}</div>
                                @endif
                            @endforeach
                        </dd>
                    @endif

                    @if ($city->website)
                        <dt class="col-5 fw-bold">Web:</dt>
                        <dd class="col-7">
                            @foreach (preg_split('/\s+/', trim((string) $city->website)) as $web)
                                @if ($web !== '')
                                    <div>{{ $web }}</div>
                                @endif
                            @endforeach
                        </dd>
                    @endif

                    @if ($city->latitude && $city->longitude)
                        <dt class="col-5 fw-bold">Zemepisné súradnice:</dt>
                        <dd class="col-7">{{ $city->latitude }}, {{ $city->longitude }}</dd>
                    @endif
                </dl>
            </div>
            <div class="col-md-6 bg-white p-5 d-flex flex-column align-items-center justify-content-center text-center">
                @if ($city->coat_of_arms_path)
                    <img class="mb-3" style="width:90px;height:90px;" src="{{ asset($city->coat_of_arms_path) }}" alt="Erb obce {{ $city->name }}">
                @endif
                <h3 class="text-primary fw-bold">{{ $city->name }}</h3>
            </div>
        </div>
    </div>
@endsection
