<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 flex gap-4">
                    <div class="w-3/4 ">
                        {{ html()->form('POST', route('dashboard'))->class('flex gap-2 w-full flex-col')->open() }}
                        <div>
                            <div class="flex gap-2 items-center justify-between w-full">
                                {{ html()->label(__('field.date'))->for('date')->class('w-1/4 text-right pr-2') }}
                                {{ html()->date('date')->value(old('date', $date))->class('w-3/4 form-input border border-gray-300 rounded-md px-2 py-1 focus:outline-none focus:border-blue-500') }}
                            </div>
                            @error('date')
                                <div class="text-red-500 text-sm col-span-2 pl-1 mt-1">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>

                        <div>
                            <div class="flex gap-2 items-center justify-between w-full">
                                {{ html()->label(__('field.time'))->for('time')->class('w-1/4 text-right pr-2') }}
                                {{ html()->time('time')->value(old('date', $time))->attribute('step', 1800)->class('w-3/4 form-input border border-gray-300 rounded-md px-2 py-1 focus:outline-none focus:border-blue-500') }}
                            </div>
                            @error('time')
                                <div class="text-red-500 text-sm col-span-2 pl-1 mt-1">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>

                        {{ html()->submit(__('button.search'))->class('font-semibold py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 border border-black') }}

                        {{ html()->form()->close() }}
                    </div>

                    <div class="w-1/4">
                        <h2 class="text-2xl font-bold mb-4">Horarios</h2>
                        <ul class="space-y-2 text-gray-700">
                            <li><strong>Lunes a Viernes:</strong> 10:00 a 24:00</li>
                            <li><strong>Sábado:</strong> 22:00 a 02:00</li>
                            <li><strong>Domingo:</strong> 12:00 a 16:00</li>
                        </ul>
                    </div>



                </div>
            </div>
        </div>
    </div>

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
