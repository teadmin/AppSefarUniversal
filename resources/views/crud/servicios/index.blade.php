@extends('adminlte::page')

@section('title', 'Cupones')

@section('content_header')

@stop

@section('content')

<style>
    table.dataTable, .dataTables_scrollHeadInner {
        width: 100% !important;
    }
    table.dataTable th {
        font-size: 1rem !important;
        margin: auto;
        padding: 10px 5px;
        font-weight: 400;
    }
    table.dataTable td {
        font-size: 0.9rem !important;
        padding: 10px 5px;
        margin: auto;
    }
    /* The switch - the box around the slider */
    .switch {
      position: relative;
      display: inline-block;
      width: 60px;
      height: 34px;
    }

    /* Hide default HTML checkbox */
    .switch input {
      opacity: 0;
      width: 0;
      height: 0;
    }

    /* The slider */
    .slider {
      position: absolute;
      cursor: pointer;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: #ccc;
      -webkit-transition: .4s;
      transition: .4s;
    }

    .slider:before {
      position: absolute;
      content: "";
      height: 26px;
      width: 26px;
      left: 4px;
      bottom: 4px;
      background-color: white;
      -webkit-transition: .4s;
      transition: .4s;
    }

    input:checked + .slider {
      background-color: rgb(121,22,15) !important;
    }

    input:focus + .slider {
      box-shadow: 0 0 1px rgb(121,22,15) !important;
    }

    input:checked + .slider:before {
      -webkit-transform: translateX(26px);
      -ms-transform: translateX(26px);
      transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
      border-radius: 34px;
    }

    .slider.round:before {
      border-radius: 50%;
    }

    div.dt-row {
        margin:10px 0px;
    }
</style>

<x-app-layout>
    <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                {{-- Inicio --}}
                <div class="bg-gray-50">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:py-6 lg:px-8 lg:flex lg:items-center lg:justify-between">
                        <h2 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                            <span class="ctvSefar block text-indigo-600">Servicios</span>
                        </h2>
                        @can('crud.servicios.create')
                        <div class="mt-8 flex lg:mt-0 lg:flex-shrink-0">
                            <div class="inline-flex rounded-md shadow">
                                <a href="{{ route('crud.servicios.create') }}" class="cfrSefar inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                    Registrar Servicio
                                </a>
                            </div>
                        </div>
                        @endcan
                    </div>
                </div>
                {{-- Fin --}}
            </div>
        </div>
    </div>
    <div class="card p-6">
    @if (!is_null($servicios))
        <table class="min-w-full divide-y divide-gray-200 w-100" id="example">
            <thead class="bg-gray-50">
            <tr>
                <th scope="col" class="">
                    Nombre
                </th>
                <th scope="col" class="">
                    ID de Hubspot
                </th>
                <th scope="col" class="">
                    Precio
                </th>
                @can('crud.servicios.edit')
                <th scope="col" class="">
                    {{ __('Edit') }}
                </th>
                @endcan
                @can('crud.servicios.destroy')
                <th scope="col" class="">
                    {{ __('Remove') }}
                </th>
                @endcan
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">

            @foreach ($servicios as $servicio)
            <tr>
                <td class="">
                    {{ $servicio->nombre }}
                </td>
                <td class="">
                    {{ $servicio->id_hubspot }}
                </td>
                <td class="">
                    {{ $servicio->precio }}
                </td>
                @can('crud.servicios.edit')
                <td class="text-center">
                    <a href="{{ route('crud.servicios.edit', $servicio) }}" title="Editar"><i class="fas fa-edit"></i></a>
                </td>
                @endcan
                @can('crud.servicios.destroy')
                <td class="text-center">
                    <form action="{{ route('crud.servicios.destroy', $servicio) }}" method="POST">
                        @csrf
                        @method('delete')
                        <button
                            type="submit"
                            class="text-red-600 hover:text-red-900"
                            onclick="return confirm('¿Está seguro que desea eliminar el servicio?')"><i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
                @endcan
            </tr>
            @endforeach
            </tbody>
        </table>
    @endif
    </div>
</x-app-layout>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/sefar.css') }}">
@stop

@section('js')

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css">
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>

<script type="text/javascript">

    $(document).ready(function(){
        $('#example').DataTable({
            scrollX: true,
            scroller: true,
            "order": [],
            "language": {
                "lengthMenu": "Mostrar _MENU_ resultados por página",
                "zeroRecords": "No hay resultados",
                "info": "Página _PAGE_ de _PAGES_",
                "infoEmpty": "No hay resultados"
            }
        });
    });
</script>
@stop