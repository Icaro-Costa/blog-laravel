@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto">
        
        <div class="flex items-center gap-6 mb-6 text-sm font-medium">
            <button onclick="history.back()" class="flex items-center gap-2 text-gray-500 hover:text-blue-600 transition">
                <span>&larr;</span> Voltar
            </button>
            <span class="text-gray-300">|</span>
            <a href="{{ route('home') }}" class="flex items-center gap-2 text-gray-500 hover:text-blue-600 transition">
                <span>🏠</span> Ir para o Início
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-100">
            <div class="h-32 bg-gradient-to-r from-blue-500 to-indigo-600"></div>
            
            <div class="px-8 pb-8">
                <div class="relative -mt-16 mb-6">
                    <a href="{{ route('users.posts', $user->id) }}" class="block w-32 h-32 rounded-full border-4 border-white overflow-hidden shadow-md bg-white hover:scale-105 transition-transform duration-200" title="Ver posts de {{ $user->firstName }}">
                        @if($user->image)
                            <img src="{{ $user->image }}" alt="{{ $user->firstName }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gray-200 text-4xl text-gray-500 font-bold">
                                {{ substr($user->firstName, 0, 1) }}
                            </div>
                        @endif
                    </a>
                </div>

                <div class="text-center md:text-left mb-8">
                    <h1 class="text-3xl font-bold text-gray-900">
                        <a href="{{ route('users.posts', $user->id) }}" class="hover:text-blue-600 hover:underline transition">
                            {{ $user->firstName }} {{ $user->lastName }}
                        </a>
                    </h1>
                    <p class="text-gray-500">@ {{ $user->username }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t pt-6">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-3">Contacto</h3>
                        <ul class="space-y-3 text-gray-700">
                            <li class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                {{ $user->email }}
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                {{ $user->phone }}
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-3">Dados Pessoais</h3>
                        <ul class="space-y-3 text-gray-700">
                            <li class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                Nascimento: {{ \Carbon\Carbon::parse($user->birthDate)->format('d/m/Y') }}
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-gray-400 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <span>
                                    {{ $user->address_address }}<br>
                                    {{ $user->address_city }}, {{ $user->address_state }} - {{ $user->address_postalCode }}
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="mt-8 text-center md:text-left">
                    <a href="{{ route('users.posts', $user->id) }}" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition shadow">
                        Ver publicações de {{ $user->firstName }}
                    </a>
                </div>

            </div>
        </div>
    </div>
@endsection