@extends('layouts.app')

@section('content')

<div class="card-header">Update New Product</div>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('products.update', $product->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col">
                                <div class="form-group row">
                                    <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>
                                    <div class="col-md-8">
                                        <input id="name" type="text" class="form-control" name="name" value="{{$product->name}}">
                                        @error('name')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div><br>
                                <div class="form-group row">
                                    <label for="category" class="col-md-4 col-form-label text-md-right">Category</label>
                                    <div class="col-md-8">
                                        <select id="category" class="form-control" name="category">
                                            <option value="">Select Category</option>
                                            @foreach($categories as $category)
                                            <option value="{{ $category->id }}" @if($category->id == $product->category_id) selected @endif>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('category')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div><br>

                                <div class="form-group row">
                                    <label for="sub_category" class="col-md-4 col-form-label text-md-right">Sub Category</label>
                                    <div class="col-md-8">
                                        <select id="sub_category" class="form-control" name="sub_category">
                                            <option value="">Select Sub Category</option>
                                            @foreach($subcategories as $sub_category)
                                            <option value="{{ $sub_category->id }}" @if($sub_category->id == $product->subcategory_id) selected @endif>{{ $sub_category->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('sub_category')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div><br>
                                <div class="form-group row">
                                    <label for="price" class="col-md-4 col-form-label text-md-right">Price</label>
                                    <div class="col-md-8">
                                        <input id="price" class="form-control" name="price" value="{{$product->price}}">
                                        @error('price')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div><br>
                                <div class="form-group row">
                                    <label for="description" class="col-md-4 col-form-label text-md-right">Description</label>
                                    <div class="col-md-8">
                                        <textarea id="description" class="form-control" name="description" rows="4">{{$product->description}}</textarea>
                                        @error('description')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div><br>

                                <div class="form-group row">
                                    <label for="status" class="col-md-4 col-form-label text-md-right">Status</label>
                                    <div class="col-md-8">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" id="status_active" name="status" value="active" {{ $product->status === 'active' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="status_active">
                                                Active
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" id="status_inactive" name="status" value="inactive" {{ $product->status === 'inactive' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="status_inactive">
                                                Inactive
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="form-group row mb-0">
                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn btn-primary" style="height: 33px;padding-top: 3px;width: 19%;">
                                            Update
                                        </button>
                                        <a href="{{ route('products.index') }}" class="btn btn-secondary" style="background: #990c26;height: 33px;padding-top: 3px;width: 19%;">Cancel</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group row">
                                    <label for="image" class="col-md-4 col-form-label text-md-right">Image</label>

                                    <div class="col-md-8 upload_imgs">
                                        <div class="image-box">
                                            <img id="selected-image" src="{{ $product->image ? asset($product->image) : asset('images/no_image.jpg') }}" alt="Selected Image">
                                        </div>
                                    </div>
                                    <input type="file" id="image" class="upload-input btn border" name="image" style="display: none;">
                                    <a href="#" class="upload-input btn border" onclick="$('#image').click(); return false;">Browse</a>

                                    <a href="#" class="btn btn-link deletebtn" id="deletebutton">Delete</a>
                                </div>
                                @error('image')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                </div>
            </div>
        </div>
        </form>
    </div>
</div>
</div>
</div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $('#image').change(function() {
        var formData = new FormData();
        formData.append('image', $('#image')[0].files[0]);


        var productId = '{{$product->id}}';
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#selected-image').attr('src', e.target.result);
            $('#selected-image').show();
        }
        reader.readAsDataURL(this.files[0]);
        $.ajax({
            url: "{{ route('products.update', ['product' => ':id']) }}".replace(':id', productId),
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                console.log(response);
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);

            }
        });
    });

    $('#deletebutton').click(function(event) {
        event.preventDefault();
        var confirmDelete = confirm('Are you sure you want to delete the image?');
        if (confirmDelete) {
            $('#selected-image').attr('src', '{{asset("images/no_image.jpg")}}');
            $('#image').val('');
        }
    });
</script>
@endsection