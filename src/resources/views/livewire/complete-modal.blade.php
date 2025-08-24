<div>
    @if ( $dealing->user_id == $user->id )
        <!-- ログイン中ユーザーが購入者側である場合のみ、取引完了のボタンを表示 -->
        <div class="content-header__modal-open">
            <button class="dealing-complete__button" wire:click="openModal()" type="button">
                取引を完了する
            </button>
        </div>
    @endif

    @if($showModal || $errors->any())
        <div class="modal__background">
            <div class="modal-header">
                <p>取引が完了しました。</p>
            </div>
            <div class="modal-body">
                <form class="modal-form" action="/dealing/{{ $dealing->id }}/complete" method="POST">
                    @csrf
                    @method('PATCH')
                    <p>今回の取引相手はどうでしたか？</p>
                    <div class="modal-form__questionnaire">
                        <div class="modal-form__rating">
                            <input class="form-rating__input" id="star1" name="rating" type="radio" value="1">
                            <label class="form-rating__label" for="star1"><i class="fa-solid fa-star"></i></label>

                            <input class="form-rating__input" id="star2" name="rating" type="radio" value="2">
                            <label class="form-rating__label" for="star2"><i class="fa-solid fa-star"></i></label>
 
                            <input class="form-rating__input" id="star3" name="rating" type="radio" value="3">
                            <label class="form-rating__label" for="star3"><i class="fa-solid fa-star"></i></label>

                            <input class="form-rating__input" id="star4" name="rating" type="radio" value="4">
                            <label class="form-rating__label" for="star4"><i class="fa-solid fa-star"></i></label>

                            <input class="form-rating__input" id="star5" name="rating" type="radio" value="5">
                            <label class="form-rating__label" for="star5"><i class="fa-solid fa-star"></i></label>
                        </div>
                        <input type="hidden" name="user_id" value="{{ $user->id }}" />
                    </div>
                    <div class="complete-modal__footer">
                        <button type="submit">送信する</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
