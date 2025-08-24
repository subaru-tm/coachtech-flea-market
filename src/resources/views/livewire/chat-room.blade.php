<div class="chat-room">
    <div class="chat-room__display ">
        @foreach( $messages as $message )
            <div wire:key="message-{{ $message->id }}" 
                style="display: flex; 
                @if( $message->user_id == auth()->id() )
                    justify-content: end;
                @else
                    justify-content: start;
                @endif
            ">
                <div class="message__user-name">
                    {{ $message->user->name }}
                </div>
                <input type="text" class="massage__text" value="{{ $message->message }}" readonly ></input>
                @if( $message->user_id == auth()->id() )
                    <button class="message-display__edit-button" id="{{ $message->id }}__edit" type="button">編集</button>
                    <button class="message-input__update-button" id="{{ $message->id }}__update" type="submit" style="display: none;">更新</button>

                    <script>
                        const edit_message_btn_{{$i}} = document.getElementById('{{ $message->id }}__edit');
                        const update_message_btn_{{$i}} = document.getElementById('{{ $message->id }}__update');

                        edit_message_btn_{{$i}}.addEventListener('click', (e) => {
                            e.target.style.display = "none";
                            update_message_btn_{{$i}}.style.display = "unset";
                            const input = document.getElementById('{{ $message->id }}__input');
                            input.readOnly = false;
                            input.focus();
                        });
                    </script>
                    <button class="message-display__delete-button" type="submit">削除</button>

<!--                <div class="text-xs text-right">
                    {{ $message->created_at->format('H:i') }}
                </div> -->
            </div>
        @endforeach
    </div>


    <div class="border-t p-4">
        <form wire:submit.prevent="sendMessage" class="flex gap-2" enctype="multipart/form-data">
            <input type="text" wire:model.defer="message" class="flex-1 border rounded px-3 py-2" placeholder="取引メッセージを記入してください" value="{{ old('message') }}" />

<!--            <label for="file_upload">画像を追加</label>
            <input type="file" name="img_file" id="file_upload" />
            <div class="input-feild__alert">
                @error('message')
                    {{ $message }}
                @enderror
                @error('img_file')
                    {{ $message }}
                @enderror
            </div>
-->
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded" wire:loading.attr="disabled">送信</button>
        </form>
    </div>
</div>
