{{-- show / hide textarea on load/reload --}}
<script>
    jQuery(document).ready(function($) {
        /** show/hide if input == $answer->id */
        function check(){
            var $parentQuestion = $('.q_{{$item->question->id}}');
            var $textarea = $('#{{$item->question->id}}_content_{{$answer->id}}');
            var isRadioSelected = $('input#{{$item->question->id}}_{{$answer->id}}_select:checked').val() == {{$answer->id}};
            var isParentVisible = $parentQuestion.is(':visible') && !$parentQuestion.find('input, textarea').first().prop('disabled');

            if (isRadioSelected && isParentVisible) {
                $textarea
                    .attr('required', true)
                    .attr('disabled', false)
                    .show(300);
            } else {
                $textarea
                    .val('')
                    .attr('required', false)
                    .removeAttr('required')
                    .attr('disabled', true)
                    .hide(300);
            }
        };
        /** run on first load */
        $('#i_{{$item->id}} input[name^="{{$item->question->id}}_id"]').ready(function(){
            check();
        })

        /** run on every change */
        $('#i_{{$item->id}} input[name^="{{$item->question->id}}_id"]').change(function(){
            check();
        });
    });
</script>