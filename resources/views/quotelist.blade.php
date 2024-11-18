@foreach ($quotes as $quote)
    <tr>
        <td>{{ __($quote->quote) }}</td>
        <td>{{ __($quote->user->name) }}</td>
        <td>
            <div class="like-box">
                <i id="like-{{ $quote->id }}" data-quote-id="{{ $quote->id }}" class="like fa-thumbs-up {{ auth()->user()->hasLiked($quote->id)? 'fa-solid': 'fa-regular' }}"></i>
                <span class="like-count">{{ $quote->likes->count() }}</span>
                <i id="like-{{ $quote->id }}" data-quote-id="{{ $quote->id }}" class="dislike fa-thumbs-down {{ auth()->user()->hasDisliked($quote->id)? 'fa-solid': 'fa-regular' }}"></i>
                <span class="dislike-count">{{ $quote->dislikes->count() }}</span>
            </div>
        </td>
    </tr>
@endforeach
