@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/dealing-chat.css') }}">
@endsection

@livewireStyles

@section('content')
<div class="content">
    <div class="other-dealings">
        <h3 class="other-dealings__heading">
            その他の取引
        </h3>
        @foreach( $other_items as $other_item )
            @foreach( $other_item->dealings as $other_dealing )
                <a class="item-button" href="{{ URL::to('/dealing/' . $other_dealing->id) }}">
                    {{ $other_item->name }}
                </a>
            @endforeach
        @endforeach
    </div>
    <div class="dealing-content">
        <div class="content-heading">
            <img src="{{ asset($counter_user->image) }}" alt="" width="77" height="77" />
            <div class="content-heading__name">
                「{{ $counter_user->name }}」さんとの取引画面
            </div>
            <div class="content-heading__button">
                @livewire('complete-modal', [
                    'dealing' => $dealing,
                    'user' => $user
                ])
            </div>
        </div>
        <div class="separate-line"></div>
        <div class="dealing-item">
            <img src="{{ asset($dealing->item->image) }}" alt=""  width="150" height="150" />
            <div class="item-info">
                <h2>{{ $dealing->item->name }}</h2>
                <?php $price = number_format($dealing->item->price); ?>
                <p>¥ {{ $price }}</p>
            </div>
        </div>
        <div class="separate-line"></div>
        <div class="dealing-messages">
            <div class="user-name">
                <span>
                    <img src="{{ asset($counter_user->image) }}" alt="" width="55" height="55" />
                    {{ $counter_user->name }}
                </span>
                <span>
                    <img src="{{ asset($user->image) }}" alt="" width="55" height="55" />
                    {{ $user->name }}
                </span>
            </div>
        <?php $i=0; ?>
        @foreach( $chat_messages as $chat_message )
            <?php $i++; ?>
            @if( $chat_message->user_id == $user->id )
            <div class="message-display__mine" >
                <form class="message-display__edit-form" action="/dealing/{{ $dealing->id }}/edit" method="POST">
                    @method('PATCH')
                    @csrf
                    <input class="message-display__text" id="{{ $chat_message->id }}__input" name="message" type="text" value="{{ $chat_message->message }}" readonly />
                    <input type="hidden" name="chat_message_id" value="{{ $chat_message->id }}" />

                    <button class="message-display__edit-button" id="{{ $chat_message->id }}__edit" type="button">編集</button>
                    <button class="message-input__update-button" id="{{ $chat_message->id }}__update" type="submit" style="display: none;">更新</button>
                    <!-- 編集ボタン押下により、inputタグが入力可能になり、更新ボタンが出現。jsにより切替
                      -- 変数受け渡しの都合、本Bladeファイルに直接jsを書き込み  -->
                    <script>
                        const edit_message_btn_{{$i}} = document.getElementById('{{ $chat_message->id }}__edit');
                        const update_message_btn_{{$i}} = document.getElementById('{{ $chat_message->id }}__update');

                        edit_message_btn_{{$i}}.addEventListener('click', (e) => {
                            e.target.style.display = "none";
                            update_message_btn_{{$i}}.style.display = "unset";
                            const input = document.getElementById('{{ $chat_message->id }}__input');
                            input.readOnly = false;
                            input.focus();
                        });
                    </script>

                </form>
                <form class="message-display__delete-form" action="/dealing/{{ $dealing->id }}/delete" method="POST">
                    @method('PATCH')
                    @csrf
                    <input type="hidden" name="chat_message_id" value="{{ $chat_message->id }}" />
                    <button class="message-display__delete-button" type="submit">削除</button>
                </form>
                @if ( !is_null($chat_message->image) )
                    <div class="message-display__attached">
                        <img src="{{ asset($chat_message->image) }}" />
                    </div>
                @endif
            </div>

            @elseif($chat_message->user_id == $counter_user->id )
            <div class="message-display__counter">
                <input class="message-display__text" type="text" value="{{ $chat_message->message }}" readonly />
                @if ( !is_null($chat_message->image) )
                    <img class="message-display__attached-img" src="{{ asset($chat_message->image) }}" />
                @endif
            </div>
            @endif

        @endforeach
        </div>
    <div class="new-message">
        <form class="new-message__form" action="/dealing/{{ $dealing->id }}/message" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="text" class="new-message__text" name="message" placeholder="取引メッセージを記入してください" value="{{ old('message') }}" />
            <label class="new-message__attach-label" for="file_upload">画像を追加</label>
            <input type="file" class="new-message__attach-input" name="img_file" id="file_upload" />
            <div class="input-feild__alert">
                @error('message')
                    {{ $message }}
                @enderror
                @error('img_file')
                    {{ $message }}
                @enderror
            </div>
            <button class="new-message__submit" type="submit">
                <img src="{{ asset('storage/send-button-img.png') }}" alt="" width=60 height=44 />
            </button>
        </form>
    </div>
    </div>
</div>
@livewireScripts

@endsection