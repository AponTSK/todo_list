@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <button class="btn btn-primary btn-sm mb-3 add-btn">
                    <i class="bi bi-plus"></i> @lang('Add Quote')
                </button>
                <div class="card">
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">@lang('Quote')</th>
                                    <th scope="col">@lang('Author')</th>
                                    <th scope="col">@lang('Likes/Dislikes')</th>
                                </tr>
                            </thead>
                            <tbody class="quote-list">
                                @include('quote.list')
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" aria-labelledby="taskModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Add Quote')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="task" class="form-label">@lang('Qoute')</label>
                            <input type="text" class="form-control form-control-sm quote" name="quote" required>
                            @error('task')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm w-100">@lang('Save Quote')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('script')
        <script type="text/javascript">
            $(document).ready(function() {
                var $modal = $('.modal');
                var $form = $modal.find('form');

                $('.add-btn').on('click', function(e) {
                    const action = "{{ route('quotes.store') }}";
                    $modal.find('.modal-title').text('@lang('Add New Quote')');
                    $modal.find(`button[type=submit]`).text("@lang('Add Quote')");
                    $modal.find('form').attr('action', action);
                    $form.trigger('reset');
                    $modal.modal('show');
                });

                $form.submit(function(e) {
                    e.preventDefault();
                    const formData = new FormData($(this)[0])
                    const quote = $('.quote').val();
                    var url = $(this).attr('action');

                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: formData,
                        dataType: "JSON",
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.success) {
                                getQuoteList();
                                $modal.modal('hide');
                            }
                            alert(response.message);
                        }
                    });
                });


                $('.like-dislike').on("click",function() {
                    var id = $(this).data('quote-id');
                    var likeBoxElement=$(this).parent();
                    var action=$(this).data('action');


                    $.ajax({
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{ route('quotes.like.dislike') }}",
                        data: {
                            id,
                            action
                        },
                        success: function(resp) {
                            
                            if(resp.success){
                                var totalLike=parseInt(likeBoxElement.find('.like-count').text());
                                var totalDisLike=parseInt(likeBoxElement.find('.dislike-count').text());

                                    if(action == 'like'){
                                        if(likeBoxElement.find('.like i').hasClass("has-like")){
                                            likeBoxElement.find('.like-count').text(totalLike > 0 ? --totalLike : 0);
                                            likeBoxElement.find('.like i').removeClass('text-success');
                                            likeBoxElement.find('.like i').removeClass("has-like");
                                        }else{

                                            likeBoxElement.find('.like-count').text(++totalLike);
                                            likeBoxElement.find('.like i').addClass('text-success');
                                            
                                            likeBoxElement.find('.dislike-count').text(totalDisLike > 0 ? --totalDisLike : 0);
                                            likeBoxElement.find('.dislike i').removeClass('text-warning');
                                            likeBoxElement.find('.like i').addClass("has-like");
                                        }
                                        
                                    }else{ // if dislike

                                        if(likeBoxElement.find('.dislike i').hasClass("has-dislike")){
                                            likeBoxElement.find('.dislike-count').text(totalDisLike > 0 ? --totalDisLike : 0);
                                            likeBoxElement.find('.dislike i').removeClass('text-warning');
                                            likeBoxElement.find('.dislike i').removeClass("has-dislike");
                                        }else{

                                            likeBoxElement.find('.dislike-count').text(++totalDisLike);
                                            likeBoxElement.find('.dislike i').addClass('text-warning');
                                            
                                            likeBoxElement.find('.like-count').text(totalLike > 0 ? --totalLike : 0);
                                            likeBoxElement.find('.like i').removeClass('text-success');
                                            likeBoxElement.find('.dislike i').addClass("has-dislike");
                                        }
                                    }
                            }
                            alert(resp.message);
                        }
                    });

                });

            

            });
        </script>
    @endpush
@endsection
