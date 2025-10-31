@extends('layout')
@section('content')
    <div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">{{ $contact['name'] }}</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Адрес:</strong>
                        <p class="mb-0">{{ $contact['adres'] }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Телефон:</strong>
                        <p class="mb-0">{{ $contact['phone'] }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Email:</strong>
                        <a href="mailto:{{ $contact['email'] }}" class="text-decoration-none">
                            {{ $contact['email'] }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection