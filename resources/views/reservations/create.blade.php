<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Reservaction
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                {{ html()->form('POST', route('reservations.store'))->open() }}

                {{ html()->label('Fecha')->for('reservation_date') }}
                {{ html()->date('reservation_date')->value(old('reservation_date', date('Y-m-d'))) }}
                a{{ old('reservation_start_time') }}a
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
</x-app-layout>
