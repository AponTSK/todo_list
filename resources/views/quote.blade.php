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
                                @include('quotelist')
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
                            } else {
                                alert(response.message);
                            }
                        }
                    });
                });

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $('.like-box i').click(function() {
                    var id = $(this).attr('data-quote-id');
                    var boxObj = $(this).parent('div');
                    var like = $(this).hasClass('like') ? 1 : 0;

                    $.ajax({
                        type: 'POST',
                        url: "{{ route('quotes.like.dislike') }}",
                        data: {
                            id: id,
                            like: like
                        },
                        success: function(data) {
                            if (data.success.hasLiked == true) {
                                if ($(boxObj).find(".dislike").hasClass("fa-solid")) {
                                    var dislikes = $(boxObj).find(".dislike-count").text();
                                    $(boxObj).find(".dislike-count").text(parseInt(dislikes) - 1);
                                }

                                $(boxObj).find(".like").removeClass("fa-regular").addClass("fa-solid");
                                $(boxObj).find(".dislike").removeClass("fa-solid").addClass("fa-regular");

                                var likes = $(boxObj).find(".like-count").text();
                                $(boxObj).find(".like-count").text(parseInt(likes) + 1);

                            } else if (data.success.hasDisliked == true) {
                                if ($(boxObj).find(".like").hasClass("fa-solid")) {
                                    var likes = $(boxObj).find(".like-count").text();
                                    $(boxObj).find(".like-count").text(parseInt(likes) - 1);
                                }

                                $(boxObj).find(".like").removeClass("fa-solid").addClass("fa-regular");
                                $(boxObj).find(".dislike").removeClass("fa-regular").addClass("fa-solid");

                                var dislikes = $(boxObj).find(".dislike-count").text();
                                $(boxObj).find(".dislike-count").text(parseInt(dislikes) + 1);
                            } else {
                                if ($(boxObj).find(".dislike").hasClass("fa-solid")) {
                                    var dislikes = $(boxObj).find(".dislike-count").text();
                                    $(boxObj).find(".dislike-count").text(parseInt(dislikes) - 1);
                                }

                                if ($(boxObj).find(".like").hasClass("fa-solid")) {
                                    var likes = $(boxObj).find(".like-count").text();
                                    $(boxObj).find(".like-count").text(parseInt(likes) - 1);
                                }

                                $(boxObj).find(".like").removeClass("fa-solid").addClass("fa-regular");
                                $(boxObj).find(".dislike").removeClass("fa-solid").addClass("fa-regular");
                            }
                        }
                    });

                });

                function getQuoteList() {
                    $.ajax({
                        url: "{{ route('quotes.index') }}",
                        type: 'GET',
                        success: function(response) {
                            if (response.success) {
                                $('.quote-list').html(response.html);
                            }
                        }
                    });
                }

            });
        </script>
    @endpush
@endsection
