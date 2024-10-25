<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Listado de Reserva
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($reservations->isEmpty())
                        <p>No hay reservas para la fecha seleccionada.</p>
                    @else
                        <table class="min-w-full border-collapse">
                            <thead>
                                <tr>
                                    <th class="border p-2">Fecha</th>
                                    <th class="border p-2">Hora de Inicio</th>
                                    <th class="border p-2">Hora de Fin</th>
                                    <th class="border p-2">Personas</th>
                                    <th class="border p-2">Ubicación</th>
                                    <th class="border p-2">Mesa</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reservations as $reservation)
                                    <tr>
                                        <td class="border p-2">{{ $reservation['reservation_date'] }}</td>
                                        <td class="border p-2">{{ $reservation['reservation_start_time'] }}</td>
                                        <td class="border p-2">{{ $reservation['reservation_end_time'] }}</td>
                                        <td class="border p-2">{{ $reservation['people_count'] }}</td>
                                        <td class="border p-2">{{ $reservation['location'] }}</td>
                                        <td class="border p-2">{{ $reservation['tables'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
