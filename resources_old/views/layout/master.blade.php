@php
    $companySetting = \App\Models\CompanySetting::where('status', 1)->first();
    if ($companySetting) {
        $company_name = $companySetting->company_name;
        $company_logo_one = $companySetting->company_logo_one;
        $company_logo_two = $companySetting->company_logo_two;
    } else {
        $company_logo_one = null;
        $company_logo_two = null;
        $company_name = 'Comapny Name';
    }
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" {!! printHtmlAttributes('html') !!}>
<!--begin::Head-->

<head>
    <base href="" />
    {{-- <title>{{ config('app.name', 'SEML') }}</title> --}}
    <title>{{ $company_name }} </title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <meta charset="UTF-8">
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="" />
    <link rel="canonical" href="" />
    <style>
        .table> :not(caption)>*>* {
            padding: 0.3rem !important;
        }

        .text-gray-400 {
            color: var(--bs-text-gray-900) !important;
        }
    </style>


    {!! includeFavicon() !!}

    <!--begin::Fonts-->
    {!! includeFonts() !!}
    <!--end::Fonts-->

    <!--begin::Global Stylesheets Bundle(used by all pages)-->
    @foreach (getGlobalAssets('css') as $path)
        {!! sprintf('<link rel="stylesheet" href="%s">', asset($path)) !!}
    @endforeach
    <!--end::Global Stylesheets Bundle-->

    <!--begin::Vendor Stylesheets(used by this page)-->
    @foreach (getVendors('css') as $path)
        {!! sprintf('<link rel="stylesheet" href="%s">', asset($path)) !!}
    @endforeach
    <!--end::Vendor Stylesheets-->

    <!--begin::Custom Stylesheets(optional)-->
    @foreach (getCustomCss() as $path)
        {!! sprintf('<link rel="stylesheet" href="%s">', asset($path)) !!}
    @endforeach
    <!--end::Custom Stylesheets-->
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/css/bootstrap-select.min.css"> --}}

    @livewireStyles

</head>

<!--end::Head-->

<!--begin::Body-->

<body {!! printHtmlClasses('body') !!} {!! printHtmlAttributes('body') !!}>

    @include('partials/theme-mode/_init')

    @yield('content')



    <!--begin::Javascript-->
    <!--begin::Global Javascript Bundle(mandatory for all pages)-->
    @foreach (getGlobalAssets() as $path)
        {!! sprintf('<script src="%s"></script>', asset($path)) !!}
    @endforeach
    <!--end::Global Javascript Bundle-->

    <!--begin::Vendors Javascript(used by this page)-->
    @foreach (getVendors('js') as $path)
        {!! sprintf('<script src="%s"></script>', asset($path)) !!}
    @endforeach
    <!--end::Vendors Javascript-->

    <!--begin::Custom Javascript(optional)-->
    @foreach (getCustomJs() as $path)
        {!! sprintf('<script src="%s"></script>', asset($path)) !!}
    @endforeach

    <!--begin::Custom global Javascript(optional)-->
    <script src="{{ asset('assets/js/global.js') }}"></script>

    <!--end::Custom Javascript-->
    @stack('scripts')
    <!--end::Javascript-->

    <script>
        document.addEventListener('livewire:load', () => {
            Livewire.on('success', (message) => {
                toastr.success(message);
            });
            Livewire.on('error', (message) => {
                toastr.error(message);
            });

            Livewire.on('swal', (message, icon, confirmButtonText) => {
                if (typeof icon === 'undefined') {
                    icon = 'success';
                }
                if (typeof confirmButtonText === 'undefined') {
                    confirmButtonText = 'Ok, got it!';
                }
                Swal.fire({
                    text: message,
                    icon: icon,
                    buttonsStyling: false,
                    confirmButtonText: confirmButtonText,
                    customClass: {
                        confirmButton: 'btn btn-primary'
                    }
                });
            });
        });

        // SweetAlert2 alert delete button on click ========= 
        function confirmDelete(button) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    button.closest('form').submit();
                }
            })
        }
        // Show success message after form submission ========= 
        var msg = {!! json_encode(session('message')) !!};
        var type = {!! json_encode(session('alert-type')) !!};
        document.addEventListener('DOMContentLoaded', AlertCall(msg, type));
        // Show error message after form submission ========= 
        @if ($errors->any())
            Swal.fire({
                title: 'Error!',
                html: '<ul>' +
                    @foreach ($errors->all() as $error)
                        '<li>{{ $error }}</li>' +
                    @endforeach
                '</ul>',
                icon: 'error',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
        @endif
    </script>

    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js"></script> --}}

    
    @livewireScripts


    {{-- <script src="{{ asset('vendor/livewire/livewire.js') }}" data-turbo-eval="false" data-turbolinks-eval="false"></script> --}}
    {{-- <script src="{{ asset('vendor/livewire/livewire.js.map') }}" data-turbo-eval="false" data-turbolinks-eval="false"></script> --}}
    {{-- <script src="{{ asset('vendor/livewire/manifest.json') }}" data-turbo-eval="false" data-turbolinks-eval="false"></script> --}}
    

</body>
<!--end::Body-->

</html>
