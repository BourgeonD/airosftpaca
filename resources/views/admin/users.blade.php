@extends('layouts.app')
@section('title', 'Utilisateurs')
@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="font-bold text-2xl text-white">GESTION DES UTILISATEURS</h1>
        <a href="{{ route('admin.dashboard') }}" class="text-sm text-zinc-400 hover:text-white transition">← Retour admin</a>
    </div>
    <form method="GET" class="flex flex-wrap gap-3 mb-6">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom ou email..."
               class="bg-zinc-800 border border-zinc-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-green-600">
        <select name="role" class="bg-zinc-800 border border-zinc-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-green-600">
            <option value="">Tous les rôles</option>
            <option value="admin" {{ request('role')==='admin'?'selected':'' }}>Admin</option>
            <option value="squad_leader" {{ request('role')==='squad_leader'?'selected':'' }}>Chef d'escouade</option>
            <option value="squad_moderator" {{ request('role')==='squad_moderator'?'selected':'' }}>Modérateur</option>
            <option value="user" {{ request('role')==='user'?'selected':'' }}>Membre</option>
        </select>
        <button type="submit" class="bg-green-800 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition">Filtrer</button>
        <a href="{{ route('admin.users') }}" class="border border-zinc-700 text-zinc-400 hover:text-white px-4 py-2 rounded-lg transition">Reset</a>
    </form>
    <div class="bg-zinc-900 border border-zinc-800 rounded-lg overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-zinc-800 text-zinc-400 uppercase text-xs tracking-wider">
                <tr>
                    <th class="text-left px-4 py-3">Utilisateur</th>
                    <th class="text-left px-4 py-3 hidden md:table-cell">Email</th>
                    <th class="text-left px-4 py-3">Rôle</th>
                    <th class="text-left px-4 py-3 hidden md:table-cell">Inscrit le</th>
                    <th class="text-left px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-800">
                @foreach($users as $user)
                    <tr class="hover:bg-zinc-800/50 transition">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->display_name) }}&background=27272a&color=888&size=32" class="w-8 h-8 rounded-full">
                                <span class="font-medium text-white">{{ $user->display_name }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-zinc-400 hidden md:table-cell">{{ $user->email }}</td>
                        <td class="px-4 py-3">
                            <form action="{{ route('admin.users.role', $user) }}" method="POST" class="flex items-center gap-2">
                                @csrf @method('PUT')
                                <select name="role" class="bg-zinc-800 border border-zinc-700 text-white text-xs rounded px-2 py-1 focus:outline-none">
                                    <option value="user" {{ $user->role==='user'?'selected':'' }}>Membre</option>
                                    <option value="squad_moderator" {{ $user->role==='squad_moderator'?'selected':'' }}>Modérateur</option>
                                    <option value="squad_leader" {{ $user->role==='squad_leader'?'selected':'' }}>Chef</option>
                                    <option value="admin" {{ $user->role==='admin'?'selected':'' }}>Admin</option>
                                </select>
                                <button type="submit" class="text-xs bg-zinc-700 hover:bg-zinc-600 text-white px-2 py-1 rounded transition">OK</button>
                            </form>
                        </td>
                        <td class="px-4 py-3 text-zinc-500 hidden md:table-cell">{{ $user->created_at->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">
                            @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.delete', $user) }}" method="POST" onsubmit="return confirm('Supprimer {{ $user->display_name }} ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs text-red-400 hover:text-red-300 transition">Supprimer</button>
                                </form>
                            @else
                                <span class="text-xs text-zinc-600">Vous</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $users->withQueryString()->links() }}</div>
</div>
@endsection
