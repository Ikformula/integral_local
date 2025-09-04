
<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

<script>
    $(document).ready(function(){

        toastr.options = {
            "closeButton": false,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-left",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "10000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }

        @if($errors->any())


        @foreach($errors->all() as $error)
        toastr.error("{!! $error !!}");
        @endforeach

        @elseif(session()->get('flash_success'))

        @if(is_array(json_decode(session()->get('flash_success'), true)))
        toastr.success("{!! implode('', session()->get('flash_success')->all(':message<br/>')) !!}");
        @else
        toastr.success("{!! session()->get('flash_success') !!}");
        @endif

        @elseif(session()->get('flash_warning'))

        @if(is_array(json_decode(session()->get('flash_warning'), true)))
        toastr.warning("{!! implode('', session()->get('flash_warning')->all(':message<br/>')) !!}");
        @else
        toastr.warning("{!! session()->get('flash_warning') !!}");
        @endif

        @elseif(session()->get('flash_info'))

        @if(is_array(json_decode(session()->get('flash_info'), true)))
        toastr.info("{!! implode('', session()->get('flash_info')->all(':message<br/>')) !!}");
        @else
        toastr.info("{!! session()->get('flash_info') !!}");
        @endif

        @elseif(session()->get('flash_error'))

        @if(is_array(json_decode(session()->get('flash_error'), true)))
        toastr.error("{!! implode('', session()->get('flash_error')->all(':message<br/>')) !!}");
        @else
        toastr.error("{!! session()->get('flash_error') !!}");
        @endif

        @elseif(session()->get('flash_message'))

        @if(is_array(json_decode(session()->get('flash_message'), true)))
        toastr.info("{!! implode('', session()->get('flash_message')->all(':message<br/>')) !!}");
        @else
        toastr.info("{!! session()->get('flash_message') !!}");
        @endif

        @endif



    });
    function showInstantToast(message, color = 'info'){
        // to be used for showing notifications in place of js alerts
        switch (color){
            case "info":
                toastr.info(message);
                break;
            case "success":
                toastr.success(message);
                break;
            case "error":
                toastr.error(message);
                break;
            case "warning":
                toastr.warning(message);
                break;
            default:
                toastr.info(message);
        }
    }

</script>
