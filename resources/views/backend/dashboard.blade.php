@extends('backend.layouts.layout')


@section('content')
    <h2 class="mb-4" style="float: left">Dashboard</h2>
    <h2 class="mb-4" style="float: right">{{ $networkCount * 10 }}</h2>

    <table class="table">
        <thead>
            <tr>
                <th>S. NO</th>
                <th>Name</th>
                <th>Email</th>
                <th>Verified</th>
            </tr>
        </thead>
        <tbody>
            @if (count($networkData) > 0)
                @php
                    $x = 0;

                @endphp
                @foreach ($networkData as $network)
                    <tr>
                        <td>{{ $x++ }}</td>
                        <td>{{ $network->user->name }}</td>
                        <td>{{ $network->user->email }}</td>
                        <td>
                            @if ($network->user->is_verified == 0)
                                <b style="color: red;">UnVerified</b>
                            @else
                                <b style="color: green;">Verified</b>
                            @endif
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <th colspan="4">No Referrals Found..!</th>
                </tr>
            @endif
        </tbody>

    </table>
@endsection
