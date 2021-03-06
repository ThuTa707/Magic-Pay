

@extends('frontend.layouts.app')

@section('content')
<div>
  <video id="scanner"></video>
</div>
   
@endsection

  


    @section('foot')

    {{-- <script src={{asset('frontend/js/instascan.min.js')}}></script> --}}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    


    <script type="text/javascript">
      let scanner = new Instascan.Scanner({ video: document.getElementById('scanner') });
      scanner.addListener('scan', function (content) {
        console.log(content);
      });
      Instascan.Camera.getCameras().then(function (cameras) {
        if (cameras.length > 0) {
          scanner.start(cameras[0]);
        } else {
          console.error('No cameras found.');
        }
      }).catch(function (e) {
        console.error(e);
      });
    </script>


    @endsection




