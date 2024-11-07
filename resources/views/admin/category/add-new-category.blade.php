@extends('Layouts.admin')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-sm-8 m-auto">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-header-2">
                                    <h5>Add New Category</h5>
                                </div>

                                <form action="{{ route('store-category') }}" method="POST" enctype="multipart/form-data" class="theme-form theme-form-2 mega-form">
                                    @csrf

                                    <!-- Category Name Field -->
                                    <div class="mb-4 row align-items-center">
                                        <label class="form-label-title col-sm-3 mb-0">Category Name</label>
                                        <div class="col-sm-9">
                                            <input class="form-control @error('name') is-invalid @enderror" name="name" type="text" value="{{ old('name') }}" placeholder="Enter Category Name" required>
                                            @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Description Field -->
                                    <div class="mb-4 row align-items-center">
                                        <label class="form-label-title col-sm-3 mb-0">Description</label>
                                        <div class="col-sm-9">
                                            <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="4" placeholder="Enter Description">{{ old('description') }}</textarea>
                                            @error('description')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Category Image Upload Field -->
                                    <div class="mb-4 row align-items-center">
                                        <label class="col-sm-3 col-form-label form-label-title">Category Image</label>
                                        <div class="form-group col-sm-9">
                                            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*" required>
                                            @error('image')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="mb-4 row">
                                        <div class="col-sm-3">
                                            <button type="submit" class="btn btn-solid">Save Category</button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
