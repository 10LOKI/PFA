<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Vérifications KYC') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white rounded-xl p-6 shadow-lg">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Partenaires en attente de vérification</h3>
                
                @if($pending->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left">Entreprise</th>
                                    <th class="px-4 py-3 text-left">Contact</th>
                                    <th class="px-4 py-3 text-left">Email</th>
                                    <th class="px-4 py-3 text-left">Téléphone</th>
                                    <th class="px-4 py-3 text-left">Ville</th>
                                    <th class="px-4 py-3 text-left">Créé le</th>
                                    <th class="px-4 py-3 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($pending as $partner)
                                    <tr>
                                        <td class="px-4 py-3 font-medium">{{ $partner->company_name }}</td>
                                        <td class="px-4 py-3">{{ $partner->user->name ?? 'N/A' }}</td>
                                        <td class="px-4 py-3 text-gray-600">{{ $partner->user->email ?? 'N/A' }}</td>
                                        <td class="px-4 py-3 text-gray-600">{{ $partner->phone }}</td>
                                        <td class="px-4 py-3 text-gray-600">{{ $partner->city }}</td>
                                        <td class="px-4 py-3 text-gray-600">{{ $partner->created_at->format('d/m/Y') }}</td>
                                        <td class="px-4 py-3">
                                            <div class="flex justify-center gap-2">
                                                <form action="{{ route('admin.kyc.approve', $partner) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600 text-xs">
                                                        Approuver
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.kyc.reject', $partner) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 text-xs">
                                                        Rejeter
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $pending->links() }}
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Aucun partenaire en attente de vérification KYC.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>