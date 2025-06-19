@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/exhibition.css') }}">
@endsection

@section('content')
<h1 class="exhibition-title">
    商品の出品
</h1>

<form class="exhibition-form" action="/exhibition" method="post" enctype="multipart/form-data">
    @csrf
    <div class="exhibition-content">
        <div class="item-image-area">
            <h3 class="feild-group__label">商品画像</h3>
            <img src="" alt="" width="150" height="150" id="img-view"/>
            <label for="file_upload">画像を選択する</label>
            <input type="file" name="img_file" id="file_upload" />
            <div class="input-feild__alert">
                @error('img_file')
                    {{ $message }}
                @enderror
            </div>
            <!-- 選択した画像を即座にプレビュー表示するためのscript -->
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script>
                $('#file_upload').on('change', function() {
                    var $fr = new FileReader();
                    $fr.onload = function() {
                        $('#img-view').attr('src', $fr.result);
                    }
                    $fr.readAsDataURL(this.files[0]);
                });
            </script>
        </div>

        <div class="item-feild-area">
            <h2 class="exhibition-subtitle">
                商品の詳細
            </h2>
            <h3 class="item__field-label">カテゴリー</h3>
                <div class="item__category">
                    @foreach ($categories as $category)
                    <input
                        class="item__category-checkbox"
                        type="checkbox"
                        name="category[]"
                        value="{{ $category->id }}"
                        id="category{{ $category->id }}"
                        @if (isset($selected_value) && $selected_value == '{{ $category->id }}')
                            checked
                        @endif
                    />
                    <label class="item__checkbox-label" for="category{{ $category->id }}" >
                        {{ $category->name }}
                    </label>
                @endforeach
                </div>
                <div class="input-feild__alert">
                    @error('category')
                        {{ $message }}
                    @enderror
                </div>
            <div class="item__condition">
                <h3  class="item__field-label">商品の状態</h3>
                <select class="item__condition-select" name="condition">
                    <option value="">選択してください</option>
                    <option value="1">良好</option>
                    <option value="2">目立った傷や汚れなし</option>
                    <option value="3">やや傷や汚れあり</option>
                    <option value="4">状態が悪い</option>
                </select>
                <div class="input-feild__alert">
                    @error('condition')
                        {{ $message }}
                    @enderror
                </div>
            </div>
            <h2  class="exhibition-subtitle">
                商品名と説明
            </h2>
            <div class="item-feild__group">
                <h3  class="item__field-label">商品名</h3>
                <input class="item__field-text" type="text" name="name" />
                <div class="input-feild__alert">
                    @error('name')
                        {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="item-feild__group">
                <h3  class="item__field-label">ブランド名</h3>
                <input class="item__field-text" type="text" name="brand" />
            </div>
            <div class="item-feild__group">
                <h3  class="item__field-label">商品の説明</h3>
                <textarea class="item__field-textarea" name="description"></textarea>
                <div class="input-feild__alert">
                    @error('description')
                        {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="item-feild__group">
                <h3  class="item__field-label">販売価格</h3>
                <input class="item__field-text" type="text" name="price" value="¥" />
                <div class="input-feild__alert">
                    @error('price')
                        {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
        <div class="exhibition-content__submit">
            <button type="submit">出品する</button>
        </div>
    </div>
</form>
@endsection