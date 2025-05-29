<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('test') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <table>
                    <tr>
                        <th>id</th>
                        <th>name</th>
                        <th>image</th>
                    </tr>
                    @foreach($tests as $test)
                    <tr>
                        <td>{{ $test->id }}</td>
                        <td>{{ $test->name }}</td>
                        <td>
                            @if($test->image)
                            <img src="{{ asset('storage/' . $test->image) }}" alt="{{ $test->name }}" class="w-16 h-16 object-cover">
                            @else
                            No Image
                            @endif
                        </td>

                        @endforeach


                </table>

            </div>
        </div>
    </div>
</x-app-layout>