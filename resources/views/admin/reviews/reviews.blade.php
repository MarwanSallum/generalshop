@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">States</div>

                    <div class="card-body">
                        <div class="row">
                            @foreach( $reviews as $review )
                                <div class="col-md-3">
                                    <div class="alert alert-primary" role="alert">
                                        <p>{{ $review -> customer -> formattedName()  }}</p>
                                        <p> <strong>Product :</strong> {{ $review -> product -> title  }}</p>
                                        <p> <strong>Stars :</strong>
                                            @php
                                            $total = 5;
                                            $currentStars = $review -> stars;
                                            $remainingStars = $total - $currentStars;
                                            @endphp
                                            @for( $i = 0; $i < $review -> stars; $i++ )
                                                <i class="fas fa-star"></i>
                                            @endfor
                                            @for( $i = 0; $i < $remainingStars; $i++ )
                                                <i class="far fa-star"></i>
                                            @endfor

                                        </p>
                                        <p> <strong>Review :</strong>  {{ $review -> review }}</p>
                                        <p> <strong>Date :</strong>  {{ $review -> humanFormattedDate() }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        {{ $reviews -> links()  }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
