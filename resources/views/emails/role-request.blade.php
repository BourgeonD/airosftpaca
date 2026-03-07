{{-- resources/views/emails/role-request.blade.php --}}
<h2>Nouvelle demande Chef d'escouade</h2>
<p><strong>Utilisateur :</strong> {{ $request->user->name }} ({{ $request->user->email }})</p>
<p><strong>Escouade :</strong> {{ $request->squad_name }}</p>
<p><strong>Ville :</strong> {{ $request->city }}</p>
<p><strong>Membres :</strong> {{ $request->member_count }}</p>
<p><strong>Message :</strong></p>
<p>{{ $request->message }}</p>
<hr>
<p><a href="{{ route('admin.role-requests') }}">Gérer les demandes dans l'administration →</a></p>
