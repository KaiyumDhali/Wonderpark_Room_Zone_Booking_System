@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
        @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
                    @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
                    @endforeach
        </ul>
    </div>
        @endif
    <!-- Content Row -->
    <div class="card shadow">
        <div class="card-header">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h6 class="m-0 font-weight-bold text-primary">
                        {{ __('Notice Details') }}
                </h6>
                <a href="{{ route('admin.notices.index') }}"
                   class="btn btn-primary btn-sm shadow-sm">{{ __('Go Back') }}</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-7 mx-0">
                    <table class="table table-hover table-bordered border-primary">
                        <tbody>
                            <tr>
                                <th scope="row" class="w-25" ><strong>Date:</strong></th>
                                <td >{{ $notice->date }}</td>
                            </tr>
                            <tr>
                                <th scope="row" class="w-25" ><strong>Title:</strong></th>
                                <td>{{ $notice->name }}</td>
                            </tr>
                            <tr>
                                <th scope="row" class="w-25" ><strong>Description:</strong></th>
                                <td>{{ $notice->description }}</td>
                            </tr>
                             <tr>
                                <th scope="row" class="w-25" ><strong>Order:</strong></th>
                                <td >{{ $notice->order }}</td>
                            </tr> 
                            <tr>
                                <th scope="row"><strong>Status:</strong> </th>
                                <td>{{ $notice->status == 1 ? 'Active' : 'Disable' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-5">
                        @if ($notice->file != null)
                    <div class="form-group pb-2">
                        <h5 class="pb-2">Image: </h5>
                        <img src="{{ asset(Storage::url($notice->file)) }}" height="250" width="240"
                             alt="Profile Image" />
                    </div>
                        @endif 
                </div> 

            </div>

            <div class="row py-2">
                <div class="col-md-12">
                    <form action="{{ route('admin.notices.destroy', $notice->id) }}" method="POST"
                          class="d-flex justify-content-start">
                        <a class="btn btn-sm btn-primary mx-2 px-4"
                           href="{{ route('admin.notices.edit', $notice->id) }}">Edit</a>
                            @csrf
                            @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger px-3">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
