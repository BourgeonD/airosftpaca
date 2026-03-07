<a href="{{ route('profile.show', $user) }}"
   class="font-medium hover:text-green-400 transition {{ $class ?? 'text-zinc-300' }}">
    {{ $user->display_name }}
</a>
