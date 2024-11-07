@extends('Layouts.admin')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table">
                    <div class="card-body">
                        <div class="title-header option-title d-sm-flex d-block">
                            <h5>Category List</h5>
                            <div class="right-options">
                                <ul>
                                    <li>
                                        <a class="btn btn-solid" href="{{ route('new-category') }}">Add Category</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table all-package theme-table table-category" id="table_id">
                                <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Description</th>
                                    <th>Products Count</th>
                                    <th>Option</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($categories as $category)
                                    <tr>
                                        <td>
                                            <div class="table-image">
                                                <img src="{{ asset('storage/categories-image/' . $category->image) }}" class="img-fluid rounded-circle" alt=""/>
                                            </div>
                                        </td>
                                        <td>{{ $category->name }}</td>
                                        <td>{{ $category->slug }}</td>
                                        <td>
                                            <div class="text-truncate" style="max-width: 200px;" title="{{ $category->description }}">
                                                {{ $category->description }}
                                            </div>
                                        </td>
                                        <td>{{ $category->stickers_count }}</td>
                                        <td>
                                            <ul>
                                                <li>
                                                    <a href="{{route('show-category', $category->slug)}}">
                                                        <i class="ri-eye-line"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0)" class="delete-button"
                                                       data-delete-url="{{ route('delete-category', $category->id) }}"
                                                       data-category-name="{{ $category->name }}"
                                                       data-bs-toggle="modal"
                                                       data-bs-target="#confirmationModal">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-between align-items-center py-4 px-3">
        @php
            $showPaginationControls = !$categories->onFirstPage() || $categories->hasMorePages();
        @endphp

        @if ($showPaginationControls)
            <div class="text-sm text-secondary">
                 {{ $categories->firstItem() }} to {{ $categories->lastItem() }} from {{ $categories->total() }} categories
            </div>
            <nav aria-label="Page navigation">
                <ul class="pagination mb-0">
                    <!-- First Page Button -->
                    @if (!$categories->onFirstPage())
                        <li class="page-item">
                            <a class="page-link" href="{{ $categories->url(1) }}" aria-label="First">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                    @endif

                    <!-- Previous Page Button -->
                    @if (!$categories->onFirstPage())
                        <li class="page-item">
                            <a class="page-link" href="{{ $categories->previousPageUrl() }}" aria-label="Previous">
                                <span aria-hidden="true">&lsaquo;</span>
                            </a>
                        </li>
                    @endif

                    <!-- Current Page Indicator -->
                    <li class="page-item disabled">
                        <span class="page-link">{{ $categories->currentPage() }}</span>
                    </li>

                    <!-- Next Page Button -->
                    @if ($categories->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $categories->nextPageUrl() }}" aria-label="Next">
                                <span aria-hidden="true">&rsaquo;</span>
                            </a>
                        </li>
                    @endif

                    <!-- Last Page Button -->
                    @if ($categories->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $categories->url($categories->lastPage()) }}" aria-label="Last">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </nav>
        @endif
    </div>



    <!-- Include the Confirmation Modal -->
    @include('admin.includes.modal')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deleteButtons = document.querySelectorAll('.delete-button');
            const deleteForm = document.getElementById('delete-form');
            const deleteMessage = document.getElementById('delete-message');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const deleteUrl = this.getAttribute('data-delete-url');
                    const categoryName = this.getAttribute('data-category-name');

                    // Set the form action to the delete URL
                    deleteForm.setAttribute('action', deleteUrl);

                    // Set the message
                    deleteMessage.innerText = `Are you sure you want to delete the category "${categoryName}"?`;
                });
            });
        });
    </script>

@endsection
