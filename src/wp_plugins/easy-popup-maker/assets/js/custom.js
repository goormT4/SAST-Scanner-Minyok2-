jQuery(function($){
    // simple multiple select
    $('#js-example-basic-hide-search-multi-ajax').select2();

    // multiple select with AJAX search
    $('#js-example-basic-hide-search-multi-ajax').select2({
        ajax: {
            placeholder: "Select a post or page",
            url: ajaxurl, // AJAX URL is predefined in WordPress admin
            dataType: 'json',
            delay: 250, // delay in ms while typing when to perform a AJAX search
            data: function (params) {
                return {
                    q: params.term, // search query
                    action: 'CPWPWM_GET_SELECT_DATA' // AJAX action for admin-ajax.php
                };
            },
            processResults: function( data ) {
                var options = [];
                if ( data ) {

                    // data is the array of arrays, and each of them contains ID and the Label of the option
                    $.each( data, function( index, text ) { // do not forget that "index" is just auto incremented value
                        options.push( { id: text[0], text: text[1]  } );
                    });

                }
                return {
                    results: options
                };
            },
            cache: true
        },
        minimumInputLength: 3 // the minimum of symbols to input before perform a search
    });

    //$('#js-example-basic-hide-search-multi-ajax').select2('data', {id: 1, a_key: 'Hello world!'});
    //$("#js-example-basic-hide-search-multi-ajax").select2().select2('val','1');
});
jQuery(document).ready(function() {

    const $valueSpan = jQuery('.valueSpan2');
    const $value = jQuery('#customRange11');
    $valueSpan.html($value.val()+'%');
    $value.on('input change', () => {

        $valueSpan.html($value.val()+'%');
    });

    jQuery('#Animationtype').select2({
        placeholder: 'Select an option'
    });

    /*
    jQuery('.template_select').change(function () {
        var pos = jQuery("input[name='template_select']:checked").data('style');
        $(".modaltest").attr("style", pos)
        parentClass = $(".modaltest").style.cssText;
        $(".modaltest").style.cssText = parentClass+pos;
        alert(pos);
    });
    jQuery('.template_select').change(function () {
        var pos = 'width:'+jQuery(this).val()+';';
        $(".modaltest").attr("style", pos)
        parentClass = $(".modaltest").style.cssText;
        $(".modaltest").style.cssText = parentClass+pos;
        alert(pos);
    });
*/
    jQuery('#popup_form input , #popup_form textarea').change(function () {
        showValues();
    });
});

function ShowEle(class_el){
    jQuery('.'+class_el).show()
}


function showValues() {

    tinyMCE.triggerSave();
    var fields = $( "form#popup_form" ).serializeArray();
    var body_text = tinyMCE.activeEditor.getContent();
    jQuery('.modal-body').html(body_text);
    jQuery.each( fields, function( i, field ) {
        if(jQuery('[name="' + field.name + '"]').data('type') == 'text'){
            jQuery('.'+jQuery('[name="' + field.name + '"]').data('changeclass')).html(field.value);
        }
        if(jQuery('[name="' + field.name + '"]:checked').data('type') == 'style'){
            jQuery('.'+jQuery('[name="' + field.name + '"]:checked').data('changeclass')).css(jQuery('[name="' + field.name + '"]:checked').data('style'));
        }
        if(jQuery('[name="' + field.name + '"]').data('type') == 'width'){
            console.log(jQuery('[name="' + field.name + '"]').data('type'));
            if(jQuery('[name="' + field.name + '"]').val() == '' || jQuery('[name="' + field.name + '"]').val() == '0' || jQuery('[name="' + field.name + '"]').val() == 0){
                console.log(jQuery('[name="' + field.name + '"]').val());
                console.log(field.name);
                jQuery('.'+jQuery('[name="' + field.name + '"]').data('changeclass')).css(jQuery('[name="' + field.name + '"]').data('style'),'unset');
            }else{
                jQuery('.'+jQuery('[name="' + field.name + '"]').data('changeclass')).css(jQuery('[name="' + field.name + '"]').data('style'),jQuery('[name="' + field.name + '"]').val()+jQuery('[name="' + field.name + '"]').data('change-type'));
            }
        }
        if(jQuery('[name="' + field.name + '"]').data('type') == 'class'){
            jQuery('.'+jQuery('[name="' + field.name + '"]').data('changeclass')).removeClass(jQuery('.'+jQuery('[name="' + field.name + '"]').data('changeclass')).data( field.name));
            jQuery('.'+jQuery('[name="' + field.name + '"]').data('changeclass')).data( field.name, jQuery('[name="' + field.name + '"]').data('style')+jQuery('[name="' + field.name + '"]').val() );
            jQuery('.'+jQuery('[name="' + field.name + '"]').data('changeclass')).addClass(jQuery('[name="' + field.name + '"]').data('style')+jQuery('[name="' + field.name + '"]').val());
        }
        if(jQuery('[name="' + field.name + '"]').data('type') == 'content'){
            jQuery('.'+jQuery('[name="' + field.name + '"]').data('changeclass')).html(field.value);
        }

    });
}



jQuery(document).ready(function() {

    // var sid = jQuery('#wpfooter').position().top +jQuery('#wpfooter').outerHeight(true);
    // var mod = jQuery('#modal_fix').position().top+jQuery('#modal_fix').outerHeight(true);
    jQuery('#modal_fix').scrollToFixed({
        limit:function() {
            // var limit = 0;
            // mod = jQuery('#modal_fix').position().top+jQuery('#modal_fix').outerHeight(true);
            // var bottom = sid - mod;
            // limit = bottom;
            // console.log(limit);
            // return limit;
        },
        marginTop: 32,
        zIndex: 999,
        dontSetWidth: true
    });
});

