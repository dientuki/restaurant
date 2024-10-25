<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Reserva
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- Formulario de creación de reserva --}}
                    {{ html()->form('POST', route('reservations.store'))->open() }}

                    {{ html()->label('Fecha')->for('reservation_date') }}
                    {{ html()->date('reservation_date')->value(old('reservation_date', date('Y-m-d'))) }}
                    @error('reservation_date')
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                    <br />

                    {{ html()->label('Comienzo')->for('reservation_start_time') }}
                    {{ html()->time('reservation_start_time')->attribute('step', 1800) }}
                    @error('reservation_start_time')
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                    <br />

                    {{ html()->label('Fin')->for('reservation_end_time') }}
                    {{ html()->time('reservation_end_time')->attribute('step', 1800) }}
                    @error('reservation_end_time')
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                    <br />

                    {{ html()->label('Personas')->for('people_count') }}
                    {{ html()->number('people_count') }}
                    @error('people_count')
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror

                    <br />
                    {{ html()->submit('Enviar') }}

                    {{ html()->form()->close() }}
                </div>
            </div>
        </div>
    </div>

    {{-- Área de confirmación de reserva, si existe --}}
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
</x-app-layout>
