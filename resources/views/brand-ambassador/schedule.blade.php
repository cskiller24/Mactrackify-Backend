@extends('layouts.brand-ambassador')

@section('title', 'Scheduled Deployments')

@section('pre-title', 'Brand Ambassador')

@section('content')

@include('brand-ambassador.components.deployment', ['type' => 'tommorow', 'deployment' => $tommorowDeployment])
@endsection
