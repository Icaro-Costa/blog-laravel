<form method="GET" class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 mb-6">
    <div class="flex flex-col md:flex-row gap-4 justify-between items-center">
        
        <div class="w-full md:w-1/2 relative">
            <input type="text" 
                   name="search" 
                   value="{{ request('search') }}" 
                   placeholder="Buscar por título..." 
                   class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-700">
            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>

        <div class="flex gap-4 w-full md:w-auto">
            
            <select name="sort" onchange="this.form.submit()" class="border rounded-lg px-4 py-2 text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer">
                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Mais Recentes</option>
                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Mais Antigos</option>
                <option value="likes" {{ request('sort') == 'likes' ? 'selected' : '' }}>Mais Curtidos</option>
                <option value="views" {{ request('sort') == 'views' ? 'selected' : '' }}>Mais Vistos</option>
            </select>

            @if(request()->hasAny(['search', 'tag', 'sort']))
                <a href="{{ url()->current() }}" class="flex items-center justify-center px-4 py-2 text-red-600 border border-red-200 rounded-lg hover:bg-red-50 transition" title="Limpar Filtros">
                    ✕
                </a>
            @endif
        </div>
    </div>

    @if(request('tag'))
        <div class="mt-3 text-sm text-blue-600 bg-blue-50 px-3 py-1 rounded inline-block">
            Filtrando pela tag: <strong>#{{ request('tag') }}</strong>
            <input type="hidden" name="tag" value="{{ request('tag') }}">
        </div>
    @endif
</form>