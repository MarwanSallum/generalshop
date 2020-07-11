@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {!! !is_null( $product ) ? 'Update Product <span class="product-header-title">'. $product-> title .'</span>' : 'New Product' !!}
                    </div>

                    <div class="card-body">
                        <form action="{{( ! is_null($product) ) ? route( 'update-product' ) : route('new-product') }}"
                              method="post" class="row" enctype="multipart/form-data">
                            @csrf
                            @if( ! is_null( $product ) )
                                <input type="hidden" name="_method" value="PUT"/>
                                <input type="hidden" name="product_id" value="{{ $product -> id }}"/>
                            @endif

                            <div class="form-group col-md-12">
                                <label for="product_title">Product Title</label>
                                <input type="text" class="form-control"
                                       id="product_title" name="product_title" placeholder="Product Title" required
                                        value="{{ ( !is_null( $product ) ) ? $product->title : '' }}">
                            </div>

                            <div class="form-group col-md-12">
                                <label for="product_description">Product Description</label>
                                <textarea placeholder="Product Description" required class="form-control" name="product_description" id="product_description" cols="30" rows="10"
                                >{{ ( ! is_null( $product ) ? $product -> description : '' )  }}</textarea>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="product_category">Product Category</label>
                                <select class="form-control" name="product_category" id="product_category" required>
                                    <option>Select a Category</option>
                                    @foreach( $categories as $category )
                                        <option value="{{ $category -> id  }}"
                                            {{ ( ! is_null( $product ) && ( $product -> category -> id === $category-> id ) ) ? 'selected' : '' }}
                                        >{{ $category -> name }}</option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="form-group col-md-12">
                                <label for="product_unit">Product Unit</label>
                                <select class="form-control" name="product_unit" id="product_unit" required>
                                    <option>Select a Unit</option>
                                    @foreach( $units as $unit )
                                        <option value="{{ $unit -> id  }}"
                                        {{ ( ! is_null( $product ) && ( $product -> hasUnit -> id === $unit-> id ) ) ? 'selected' : '' }}
                                        >{{ $unit -> formatted() }}</option>
                                    @endforeach
                                </select>
                                </div>

                            <div class="form-group col-md-6">
                                <label for="product_price">Product Price</label>
                                <input type="number" class="form-control" step="any"
                                       name="product_price" id="product_price" placeholder="Product Price" required
                                value="{{ ( !is_null( $product ) ) ? $product->price : '' }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="product_discount">Product Discount</label>
                                <input type="number" class="form-control" step="any"
                                       name="product_discount" id="product_discount" placeholder="Product Discount" required
                                       value="{{ ( !is_null( $product ) ) ? $product->discount : 0 }}">
                            </div>

                            <div class="form-group col-md-12">
                                <label for="product_total">Product Total</label>
                                <input type="number" class="form-control" step="any"
                                       name="product_total" id="product_total" placeholder="Product Total" required
                                       value="{{ ( !is_null( $product ) ) ? $product->total : '' }}">
                            </div>

                            {{--  Options  --}}
                            <div class="form-group col-md-12">
                                <table id="option-table" class="table table-striped">

                                </table>
                                <a href="#" class="btn btn-outline-dark add-option-btn">Add Option</a>
                            </div>
                            {{--   End Options  --}}

                            {{-- Images --}}
                            <div class="form-group col-md-12">
                                <div class="row">
                                    @for( $i = 0 ; $i < 6 ; $i++ )
                                        <div class="col-md-4 col-sm-12 mb-4">
                                            <div class="card image-card-upload">
                                                <a href="" class="remove-image-upload"><i class="fas fa-minus-circle"></i></a>
                                                <a href="#" class="activate-image-upload" data-fileid="image-{{$i}}">
                                                <div class="card-body" style="text-align: center">
                                                <i class="fas fa-image"></i>
                                                </div>
                                                </a>
                                                <input name="product_images[]" type="file" class="form-control-file image-file-upload" id="image-{{$i}}">
                                            </div>
                                        </div>
                                    @endfor
                                </div>

                            </div>
                            {{-- End Images --}}
                            <div class="form-group col-md-6 offset-md-3">
                                <button type="submit" class="btn btn-primary btn-block">SAVE</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal options-window" tabindex="-1" role="dialog" id="options-window">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Option</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body row">

                        <div class="form-group col-md-6">
                            <label for="option_name">Option Name</label>
                            <input type="text" class="form-control"
                                   id="option_name" name="option_name" placeholder="Option Name" required>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="option_value">Option Value</label>
                            <input type="text" class="form-control"
                                   id="option_value" name="option_value" placeholder="Option Value" required>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">CANCEL</button>
                        <button type="submit" class="btn btn-primary add-option-button">ADD OPTION</button>
                    </div>

                </div>
            </div>
    </div>

@endsection


@section('scripts')
    <script>
        $(document).ready( function () {
            var optionNamesList = [];
            var $optionWindow = $('#options-window');
            var $addOptionBtn = $('.add-option-btn');
            var $optionsTable = $('#option-table');
            var optionNamesRow = '';
            var $activateImageUpload = $('.activate-image-upload');

            $addOptionBtn.on('click', function (e) {
                e.preventDefault();
                $optionWindow.modal('show');
            });

            $( document ).on('click', '.remove-option', function (e) {
                e.preventDefault();
                $(this).parent().parent().remove();
            })

            $(document).on('click', '.add-option-button', function (e) {
                e.preventDefault();
                var $optionName = $('#option_name');
                if($optionName.val() === ''){
                    alert('Option Name is Required');
                    return false;
                }

                var $optionValue = $('#option_value');
                if($optionValue.val() === ''){
                    alert('Option Value is Required');
                    return false;
                }

                if( ! optionNamesList.includes( $optionName.val() ) ){
                    optionNamesList.push( $optionName.val() );
                    optionNamesRow = '<td><input type="hidden" name="options[]" value="'+ $optionName.val() +'"></td>';
                }


                var optionRow = `
                <tr>
                    <td>
                       `+ $optionName.val() +`
                    </td>

                    <td>
                    `+ $optionValue.val() +`
                    </td>
                    <td>
                        <a href="" class="remove-option"><i class="fas fa-minus-circle"></i></a>
                        <input type="hidden" name="`+ $optionName.val() +`[]" value="`+$optionValue.val()+`">
                    </td>
                `;

                $optionsTable.append(
                    optionRow
                );
                $optionsTable.append(
                    optionNamesRow
                );
                $optionValue.val('');

            });

            function readURL(input, imageID){
                if(input.files && input.files[0]){
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        $('#'+imageID).attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }

            function resetFileUpload(fileUploadID , imageID , $eI , $eD){
                $('#'+imageID).attr('src', '');
                $eI.fadeIn();
                $eD.fadeOut();
                $('#'+fileUploadID).val('');
            }

            $activateImageUpload.on('click', function (e) {
                e.preventDefault();
                var fileUploadID = $(this).data('fileid');
                var me = $(this);
                $('#'+fileUploadID).trigger('click');
                var imagetag = '<img id="i'+fileUploadID+'" src="" class="card-img-top" >';
                $(this).append(imagetag);
                $('#'+fileUploadID).on('change' , function (e) {
                    readURL(this , 'i'+fileUploadID);
                    me.find('i').fadeOut();
                    var $removeThisImage = me.parent().find('.remove-image-upload');
                    $removeThisImage.fadeIn();
                    $removeThisImage.on('click', function (e) {
                        e.preventDefault();
                        resetFileUpload(fileUploadID,'i'+fileUploadID, me.find('i'), $removeThisImage);
                    })
                })
            });

        } )
    </script>
@endsection

