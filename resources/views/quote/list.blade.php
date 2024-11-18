@foreach ($quotes as $quote)
    <tr>
        <td>{{ __($quote->quote) }}</td>
        <td>{{ __($quote->user->name) }}</td>
        <td>
            @php
                $hasUserLikes = $quote->likes->where('user_id', auth()->id())->where('like',1)->count();
                $hasUserDisLikes = $quote->likes->where('user_id', auth()->id())->where('like',0)->count();
            @endphp
            <div class="like-box">
                <span class="like-dislike like" data-action="like" data-quote-id="{{ $quote->id }}" >
                    <i class="fa-thumbs-up fa-regular @if($hasUserLikes)  text-success has-like @endif"></i>
                </span>
                <span class="like-count">
                    {{ $quote->likes->where('like',1)->count() }}
                </span>
                <span class="like-dislike ms-4 dislike" data-action="dislike" data-quote-id="{{ $quote->id }}">
                    <i  data-quote-id="{{ $quote->id }}" class=" fa-thumbs-down fa-regular  @if($hasUserDisLikes) has-dislike  text-warning  @endif"></i>
                </span>
                <span class="dislike-count">{{ $quote->likes->where('like',0)->count() }}</span>
            </div>
        </td>
    </tr>
@endforeach
