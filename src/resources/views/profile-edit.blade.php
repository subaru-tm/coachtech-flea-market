@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile-edit.css') }}">
@endsection

@section('content')
<div class="content">
    <div class="content-inner">
        <h2 class="content-heading">プロフィール設定</h2>
        <div class="content-input">
            <form class="input-form" action="/mypage/profile/update" method="post" enctype="multipart/form-data">
                @method('PATCH')
                @csrf
                <div class="user-image">
                    <img src="{{ asset($user->image) }}" alt="" width="150" height="150" id="img-view"/> 
                    <label for="file_upload">画像を選択する</label>
                    <input class="user-image__file--button" type="file" name="img_file" id="file_upload" />
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
                <div class="input-feild__alert">
                        @error('img_file')
                            {{ $message }}
                        @enderror
                </div>

                <div class="input-form__group">
                    <div class="input-form__group-label">ユーザー名</div>
                    <input class="input-form__group-text" type="text" name="name" value="{{ $user['name'] }}" />
                </div>
                <div class="input-form__group">
                    <div class="input-form__group-label">郵便番号</div>
                    <input class="input-form__group-text" type="text" name="post_code" value="{{ $user['post_code'] }}" />
                </div>
                <div class="input-form__group">
                    <div class="input-form__group-label">住所</div>
                    <input class="input-form__group-text" type="text" name="address" value="{{ $user['address'] }}" />
                </div>
                <div class="input-form__group">
                    <div class="input-form__group-label">建物名</div>
                    <input class="input-form__group-text" type="text" name="building" value="{{ $user['building'] }}" />
                </div>
                <div class="input-form__button">
                    <button class="input-form__button-submit" type="submit">更新する</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection