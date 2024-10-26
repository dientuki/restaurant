<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-lg font-semibold mb-4">Estado de Mesas</h2>
                    <table class="min-w-full bg-white border border-gray-200">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 border-b">Número de Mesa</th>
                                <th class="px-4 py-2 border-b">Ubicación</th>
                                <th class="px-4 py-2 border-b">Capacidad Máxima</th>
                                <th class="px-4 py-2 border-b">Estado</th>
                                <th class="px-4 py-2 border-b">Hasta Hora Ocupada</th>
                                <th class="px-4 py-2 border-b">Próxima Reserva</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tables as $table)
                                <tr class="text-center">
                                    <td class="px-4 py-2 border-b">{{ $table->table_number }}</td>
                                    <td class="px-4 py-2 border-b">{{ $table->location }}</td>
                                    <td class="px-4 py-2 border-b">{{ $table->max_capacity }}</td>
                                    <td class="px-4 py-2 border-b">{{ $table->estado }}</td>
                                    <td class="px-4 py-2 border-b">
                                        {{ $table->hasta_hora_ocupada ?? 'N/A' }}
                                    </td>
                                    <td class="px-4 py-2 border-b">
                                        {{ $table->proxima_reserva ?? 'N/A' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
