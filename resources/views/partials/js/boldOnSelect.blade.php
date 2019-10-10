{{-- bold on select --}}
<script>
    jQuery(document).ready(function($) {
        function check(){
            /** font-weight-normal|bold */
            if ($('input#{{$item->question->id}}_{{$answer->id}}_select').prop('checked')) {
                $('span#{{$item->question->id}}_{{$answer->id}}_text')
                    .removeClass('font-weight-normal')
                    .addClass('font-weight-bold');
            }else{
                $('span#{{$item->question->id}}_{{$answer->id}}_text')
                    .removeClass('font-weight-bold')
                    .addClass('font-weight-normal');
            }

        };
        /** run on first load */
        $('#q_{{$item->question->id}} input[name^="{{$item->question->id}}_id"]').ready(function(){
            check();
        })

        /** run on every change */
        $('#q_{{$item->question->id}} input[name^="{{$item->question->id}}_id"]').change(function(){
            check();
        });
    });
</script> 