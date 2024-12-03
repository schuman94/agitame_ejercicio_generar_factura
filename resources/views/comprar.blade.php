<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Comprar
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex gap-x-20">
                @if (!$carrito->vacio())
                    <aside class="flex flex-col items-center w-1/4">
                        <div class="mx-auto overflow-y-auto py-4 px-3 bg-gray-50 rounded dark:bg-gray-800">
                            <table class="mx-auto text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead
                                    class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <th scope="col" class="py-3 px-6">
                                        Descripción
                                    </th>
                                    <th scope="col" class="py-3 px-6">
                                        Cantidad
                                    </th>
                                </thead>
                                <tbody>
                                    @foreach ($carrito->getLineas() as $id => $linea)
                                        @php
                                            $articulo = $linea->getArticulo();
                                            $cantidad = $linea->getCantidad();
                                        @endphp
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                            <td class="py-4 px-6">
                                                {{ $articulo->descripcion }}
                                            </td>

                                            <td class="py-4 px-6">
                                                {{ $cantidad }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="flex">

                            <form method="POST" action="{{ route('realizar_comprar') }}">
                                @csrf
                                <div class="mb-5">
                                    <x-input-label for="numero" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Número de factura:
                                    </x-input-label>
                                    <x-text-input name="numero" type="text" id="numero" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        :value="old('numero')" />
                                    <x-input-error :messages="$errors->get('numero')" class="mt-2" />
                                </div>
                                <button type="submit" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-900">Tramitar</button>
                            </form>
                        </div>
                    </aside>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
