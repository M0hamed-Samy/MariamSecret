@extends('layouts.admin')
@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Edit Category</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li><a href="{{ route('admin.index') }}">
                            <div class="text-tiny">Dashboard</div>
                        </a></li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li><a href="{{ route('admin.category.index') }}">
                            <div class="text-tiny">Categories</div>
                        </a></li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <div class="text-tiny">Edit Category</div>
                    </li>
                </ul>
            </div>

            <div class="wg-box">
                <form action="{{ route('admin.category.update', ['id' => $category->id]) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <fieldset class="name">
                        <div class="body-title">Category Name <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="text" name="name" placeholder="Category name"
                            value="{{ $category->name }}" required>
                    </fieldset>
                    @error('name')
                        <span class="alert alert-danger">{{ $message }}</span>
                    @enderror

                    <fieldset class="name">
                        <div class="body-title">Category Slug <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="text" name="slug" placeholder="Category slug"
                            value="{{ $category->slug }}" required>
                    </fieldset>
                    @error('slug')
                        <span class="alert alert-danger">{{ $message }}</span>
                    @enderror

                    <fieldset>
                        <div class="body-title">Upload Image <span class="tf-color-1">*</span></div>
                        <div class="upload-image flex-grow">
                            <div class="item" id="imgpreview">
                                <img id="preview-img" src="{{ asset('uploads/categories/' . $category->image) }}" class="effect8"
                                    alt="" style="max-height: 150px;">
                            </div>
                            <div id="upload-file" class="item up-load">
                                <label class="uploadfile" for="myFile">
                                    <span class="icon"><i class="icon-upload-cloud"></i></span>
                                    <span class="body-text">Drop your images or <span class="tf-color">click to
                                            browse</span></span>
                                    <input type="file" id="myFile" name="image" accept="image/*">
                                </label>
                            </div>
                        </div>
                    </fieldset>
                    @error('image')
                        <span class="alert alert-danger">{{ $message }}</span>
                    @enderror

                    <div class="bot">
                        <div></div>
                        <button class="tf-button w208" type="submit">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            $('#myFile').on('change', function() {
                const [file] = this.files;
                if (file) {
                    $('#preview-img').attr('src', URL.createObjectURL(file));
                    $('#imgpreview').show();
                }
            });

            $("input[name='name']").on("change", function() {
                $("input[name='slug']").val(StringToSlug($(this).val()));
            });

            function StringToSlug(text) {
                return text.toLowerCase()
                    .replace(/[^\w ]+/g, '')
                    .replace(/ +/g, '-');
            }
        });
    </script>
@endpush
