<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Reserva
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 flex gap-4">
                    <div class="w-3/4 ">
                        {{ html()->form('POST', route('reservations.store'))->class('flex gap-2 w-full flex-col')->open() }}

                        <div>
                            <div class="flex gap-2 items-center justify-between w-full">
                                {{ html()->label(__('field.reservation_date'))->for('reservation_date')->class('w-1/4 text-right pr-2') }}
                                {{ html()->date('reservation_date')->value(old('reservation_date', date('Y-m-d')))->class('w-3/4 form-input border border-gray-300 rounded-md px-2 py-1 focus:outline-none focus:border-blue-500') }}
                            </div>
                            @error('reservation_date')
                                <div class="text-red-500 text-sm col-span-2 pl-1 mt-1">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>


                        <div>
                            <div class="flex gap-2 items-center justify-between w-full">
                                {{ html()->label(__('field.reservation_start_time'))->for('reservation_start_time')->class('w-1/4 text-right pr-2') }}
                                {{ html()->time('reservation_start_time')->attribute('step', 1800)->class('w-3/4 form-input border border-gray-300 rounded-md px-2 py-1 focus:outline-none focus:border-blue-500') }}
                            </div>
                            @error('reservation_start_time')
                                <div class="text-red-500 text-sm col-span-2 pl-1 mt-1">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>

                        <div>
                            <div class="flex gap-2 items-center justify-between w-full">
                                {{ html()->label(__('field.reservation_end_time'))->for('reservation_end_time')->class('w-1/4 text-right pr-2') }}
                                {{ html()->time('reservation_end_time')->attribute('step', 1800)->class('w-3/4 form-input border border-gray-300 rounded-md px-2 py-1 focus:outline-none focus:border-blue-500') }}
                            </div>
                            @error('reservation_end_time')
                                <div class="text-red-500 text-sm col-span-2 pl-1 mt-1">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>

                        <div>
                            <div class="flex gap-2 items-center justify-between w-full">
                                {{ html()->label(__('field.people_count'))->for('people_count')->class('w-1/4 text-right pr-2') }}
                                {{ html()->number('people_count')->class('w-3/4 form-input border border-gray-300 rounded-md px-2 py-1 focus:outline-none focus:border-blue-500') }}
                            </div>
                            @error('people_count')
                                <div class="text-red-500 text-sm col-span-2 pl-1 mt-1">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>

                        {{ html()->submit(__('button.submit'))->class('font-semibold py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 border border-black') }}

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

    @if(isset($hasReservation))
        @if($hasReservation && $reservation)
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h2>Reserva Confirmada</h2>
                            <p><strong>ID:</strong> {{ $reservation->id }}</p>
                            <p><strong>Fecha de Reserva:</strong> {{ $reservation->reservation_date }}</p>
                            <p><strong>Hora de Inicio:</strong> {{ $reservation->reservation_start_time }}</p>
                            <p><strong>Hora de Fin:</strong> {{ $reservation->reservation_end_time }}</p>
                            <p><strong>Cantidad de Personas:</strong> {{ $reservation->people_count }}</p>
                            <p><strong>Salón:</strong> {{ $reservation->tables->first()->location ?? 'No especificado' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @elseif($hasReservation === false)
            {{-- Mensaje de error si no se pudo crear la reserva --}}
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <div class="alert alert-danger">
                                No se puede hacer la reserva en ese horario para esa fecha.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif
</x-app-layout>
