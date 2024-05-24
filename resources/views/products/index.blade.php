@extends('layouts.app')

@section('content')
<style>
        .search-container {
            display: flex;
    align-items: center;
    border: 1px solid #ccc;
    border-radius: 20px;
    padding: 0px;
    width: 229px;
    margin-bottom: 10px;
        }
        
        .search-container input[type=text] {
            border: none;
            outline: none;
            width: 100%;
            padding: 5px;
            border-radius: 20px; /* Same as the container to ensure it's perfectly round */
        }
        
        .search-container button {
            background-color: #f1f1f1;
            border: none;
            color: #333;
            cursor: pointer;
            border-radius: 50%; /* Makes the button perfectly round */
            padding: 5px 10px;
        }
    </style>
<div class="container">
    <div class="row justify-content-end">
    <p class="page-head">List Products</p>
        <div class="col-auto">
            <a href="{{ route('products.create') }}" class="btn btn-secondary mb-3" style="width: -webkit-fill-available;">Add Product</a><br>
            <div class="search-container">
            <input type="text" id="searchInput" placeholder="Search......">
            </div> 
        </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Thumb</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Sub Category</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)

                <tr id="product-{{ $product->id }}">
                    <td class="text-center"><img src="{{ $product->image }}" alt="Uploaded Image" width="100" height="100"></td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category->name }}</td>
                    <td>{{ $product->subcategory->name }}</td>
                    <td>₹ {{ $product->price }}</td>
                    <td>{{ $product->description }}</td>
                    <td>{{ $product->status }}</td>
                    <td>
                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-link btn-sm">Edit</a>|
                        <a href="" class="btn btn-link btn-sm delete-product" data-id="{{ $product->id }}">Delete</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-center">
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.delete-product').click(function(e) {
                e.preventDefault();
                var productId = $(this).data('id');

                if (confirm("Are you sure you want to delete this record?")) {
                    $.ajax({
                        url: '/products/' + productId,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            console.log(response.message);

                            $('#product-' + productId).remove();
                        },
                        error: function(xhr) {
                            console.error(xhr.responseText);
                        }
                    });
                }
            });
        });
    </script>
    <script>
        //for search
        $(document).ready(function() {

            $("#searchInput").keyup(function(event) {

                search();
            });
        });


        function search() {

            var searchValue = $("#searchInput").val().toUpperCase();
            var rows = $(".table tbody tr");

            rows.each(function() {
                var columns = $(this).find("td");
                var matchFound = false;
                columns.each(function() {

                    var columnValue = $(this).text();
                    if (columnValue.toUpperCase().indexOf(searchValue) > -1) {
                        matchFound = true;
                        return false;
                    }
                });

                if (matchFound) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }
    </script>
    @endsection