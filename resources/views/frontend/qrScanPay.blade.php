@extends('frontend.layouts.app')

@section('title', 'Scan & Pay')

@section('headerBar_title', 'Scan & Pay')

@section('content')

    <div class="scan-pay">
        <div class="card">
            <div class="card-body">
                @include('frontend.layouts.error')
                <div class="text-center">
                    <img src="{{ asset('frontend/images/qr-scan-pay.png') }}" alt="">
                </div>

                <p class="mb-3 text-center">Click button, put QR code <br> in the frame and pay</p>
                <div class="text-center">
                    <button class="btn btn-primary scan-btn" data-toggle="modal" data-target="#scanModal">
                        Scan</button>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="scanModal" tabindex="-1" aria-labelledby="scanModalLabel" aria-hidden="true">
                    <div class="modal-dialog  modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Scan and Pay</h5>
                                <button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body text-center">
                                <video id="scanAndPay" style="width: 100%; height: 240px"></video>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>

@endsection

@section('foot')



    {{-- QR Scan Reader (Instanscan) CDN nk mha ya --}}
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>


      {{-- <script src={{asset('frontend/js/instascan.min.js')}}></script> --}}


    <script>

        let myModal = document.getElementById('scanModal');
        let scanner = new Instascan.Scanner({
            video: document.getElementById('scanAndPay')
        });
        scanner.addListener('scan', function(content) {
            console.log(content);
            if(content){
                var to_phone = content;
                window.location.replace(`/qr/transfer/?to_phone=${to_phone}`);
            }
        });


        $('.scan-btn').click(function(){
            Instascan.Camera.getCameras().then(function(cameras) {
                if (cameras.length > 0) {
                    scanner.start(cameras[0]);
                } else {
                    console.error('No cameras found.');
                }
            }).catch(function(e) {
                console.error(e);
            });
        })

        $('.close-btn').click(function(){
            scanner.stop();
        })
    


    </script>


@endsection
