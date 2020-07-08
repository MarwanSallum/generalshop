@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Tags</div>

                    <div class="card-body">

                        <form action="{{ route( 'tags' )  }}" method="post" class="row">
                            @csrf
                            <div class="form-group col-md-6">
                                <label for="unit_name">Tag Name</label>
                                <input type="text" class="form-control" id="tag_name" name="tag_name" placeholder="Tag Name" required>
                            </div>

                            <div class="form-group col-md-12">
                                <button type="submit" class="btn btn-primary">Save New Tag</button>
                            </div>

                        </form>


                        <div class="row">
                            @foreach( $tags as $tag )
                                <div class="col-md-3">
                                    <div class="alert alert-primary" role="alert">
                                         <span class="buttons-span">

                                            <span><a class="edit-unit"
                                                     data-tagname="{{ $tag->tag  }}"
                                                     data-tagid="{{$tag->id}}"><i class="fas fa-edit"></i></a></span>

                                            <span><a class="delete-unit"
                                                     data-tagname="{{ $tag->tag  }}"
                                                     data-tagid="{{$tag->id}}"><i class="fas fa-trash"></i></a></span>
                                       </span>
                                        <p>{{ $tag -> tag  }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        {{  ( !is_null($showLinks) && $showLinks ) ?  $tags -> links() : '' }}

                        <form action="{{ route('search-tags') }}" method="get">
                            @csrf
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <input type="text" class="form-control" id="tag_search" name="tag_search" placeholder="Search Tag">
                                </div>
                                <div class="form-group col-md-6">
                                    <button type="submit" class="btn btn-primary">SEARCH</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if( \Illuminate\Support\Facades\Session::has('message') )
        <div class="toast" style="position: absolute; z-index: 99999; top: 5%; right: 5%;">
            <div class="toast-header">
                <strong class="mr-auto">Tag</strong>
                <small>11 mins ago</small>
                <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                    <span aria-hidden="true">&times</span>
                </button>
            </div>
            <div class="toast-body">

                {{ \Illuminate\Support\Facades\Session::get('message')  }}

            </div>
        </div>
    @endif


    <div class="modal delete-window" tabindex="-1" role="dialog" id="delete-window">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Tag</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('tags')  }}" method="post">
                    <div class="modal-body">
                        <p id="delete-message"></p>


                        @csrf
                        <input type="hidden" name="_method" value="delete"/>
                        <input type="hidden" name="tag_id" value="" id="tag_id">


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">CANCEL</button>
                        <button type="submit" class="btn btn-primary">DELETE</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal edit-window" tabindex="-1" role="dialog" id="edit-window">
        <form action="{{ route( 'tags' )  }}" method="post">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Tag</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        @csrf
                        <div class="form-group col-md-6">
                            <label for="edit_unit_name">Tag Name</label>
                            <input type="text" class="form-control"
                                   id="edit_tag_name" name="tag_name" placeholder="Tag Name" required>
                        </div>

                        <input type="hidden" name="tag_id" id="edit_tag_id" >
                        <input type="hidden" name="_method" value="PUT"/>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">CANCEL</button>
                        <button type="submit" class="btn btn-primary">UPDATE</button>
                    </div>

                </div>
            </div>
        </form>
    </div>

@endsection

@section('scripts')


    <script>
        $(document).ready(function () {
            var $deleteUnit = $('.delete-unit');
            var $deleteWindow = $('#delete-window');
            var $tagID = $('#tag_id');
            var $deleteMessage = $('#delete-message');
            $deleteUnit.on('click', function (element) {
                element.preventDefault();
                var tag_id = $(this).data('tagid');
                $tagID.val(tag_id);
                $deleteMessage.text('Are you sure you want to delete tag ?');
                $deleteWindow.modal('show');
            });

            var $editTag = $('.edit-unit');
            var $editWindow = $('#edit-window');

            var $edit_tag_name = $('#edit_tag_name');
            var $edit_tag_id = $('#edit_tag_id');

            $editTag.on('click', function (element) {
                element.preventDefault();
                var tagName = $(this).data('tagname');
                var tag_id = $(this).data('tagid');

                $edit_tag_name.val(tagName);
                $edit_tag_id.val(tag_id);

                $editWindow.modal('show');
            });
        });
    </script>


    @if(\Illuminate\Support\Facades\Session::has('message'))

        <script>
            jQuery(document).ready(function ($) {
                var $toast = $('.toast').toast({
                    autohide : false
                });
                $toast.toast('show');
            });
        </script>

    @endif

@endsection
