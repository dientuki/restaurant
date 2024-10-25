<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Listado de Reserva
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 flex gap-4">
                    {{ html()->form('POST', route('reservations.show'))->class('flex gap-2 w-full flex-col')->open() }}
                    <div>
                        <div class="flex gap-2 items-center justify-between w-full">
                            {{ html()->label(__('field.date'))->for('date')->class('w-1/4 text-right pr-2') }}
                            {{ html()->date('date')->value(old('date', date('Y-m-d')))->class('w-3/4 form-input border border-gray-300 rounded-md px-2 py-1 focus:outline-none focus:border-blue-500') }}
                        </div>
                        @error('date')
                            <div class="text-red-500 text-sm col-span-2 pl-1 mt-1">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>

                    {{ html()->submit(__('button.search'))->class('font-semibold py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 border border-black') }}

                    {{ html()->form()->close() }}
                </div>
            </div>
        </div>
    </div>

    @if(isset($reservations))
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
    @endif
</x-app-layout>
