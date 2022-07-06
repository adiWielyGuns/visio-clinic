<!DOCTYPE html>
<!--
Template Name: Rubick - HTML Admin Dashboard Template
Author: Left4code
Website: http://www.left4code.com/
Contact: muhammadrizki@left4code.com
Purchase: https://themeforest.net/user/left4code/portfolio
Renew Support: https://themeforest.net/user/left4code/portfolio
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
{{-- <html lang="{{ str_replace('_', '-', app()->getLocale()) }}"> --}}
<!-- BEGIN: Head -->

<head>
    <meta charset="utf-8">
    {{-- <link href="{{ asset('dist/images/amore.png') }}" rel="shortcut icon"> --}}
    {{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Fisio Terapi Clinic">
    <meta name="keywords" content="Fisio Terapi Clinic">
    {{-- <meta name="author" content="LEFT4CODE"> --}}

    @yield('head')

    <!-- BEGIN: CSS Assets-->
    {{-- <link rel="stylesheet" href="{{ mix('dist/css/app.css') }}" /> --}}
    <style>
        .swal2-container {
            z-index: 999999999999999999999999999999999 !important;
        }

        .is-invalid {
            border: 1px solid red !important;
        }

        .loading {
            width: 100%;
            height: 100%;
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background-color: rgba(0, 0, 0, .5);
            z-index: 99999999999999999999999999999999;
            display: none;
        }

        .loading-wheel {
            width: 20px;
            height: 20px;
            margin-top: -40px;
            margin-left: -40px;

            position: absolute;
            top: 50%;
            left: 50%;

            border-width: 30px;
            border-radius: 50%;
            -webkit-animation: spin 1s linear infinite;
        }

        .style-2 .loading-wheel {
            border-style: double;
            border-color: #ccc transparent;
        }

        @-webkit-keyframes spin {
            0% {
                -webkit-transform: rotate(0);
            }

            100% {
                -webkit-transform: rotate(-360deg);
            }
        }
    </style>
    <!-- END: CSS Assets-->
    @include('../layouts/css')
</head>
<!-- END: Head -->

@yield('body')
@include('../layouts/script')
@yield('script')

</html>
