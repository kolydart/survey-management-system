{{-- show / hide textarea on load/reload --}}
<script>
    jQuery(document).ready(function($) {
        /** show/hide if input == $answer->id */
        function check(){
            if ($('input#{{$item->question->id}}_{{$answer->id}}_select:checked').val() == {{$answer->id}}) {
                $('#{{$item->question->id}}_content_{{$answer->id}}')
                    .attr('required', true)
                    .attr('disabled', false)
                    .show(300);
            }else {
                $('#{{$item->question->id}}_content_{{$answer->id}}')
                    .val('')
                    .attr('required', false)
                    .attr('disabled', true)
                    .hide(300);
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