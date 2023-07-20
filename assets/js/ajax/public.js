"use strict";

/**
 * Handle form post, put, get, delete request
 * @event
 * @callback function
 */

const postRequestViaAjax = (e, cb=false) => { 
    //prevent default form submission
    e.preventDefault();

    // get form properties
    const oForm = $(`form#${e.target.id}`);
    const oFormDataIndex = $(`#${e.target.id}`)[0];
    const oFormData = new FormData(oFormDataIndex);
    oFormData.append('requestFrom', 'web');
    oFormData.append('IS_AJAX', true);

    $.ajax({
        method:e.target.method,
        url:e.target.action,
        data:oFormData,
        dataType:'json',
        contentType: false,
        processData: false,
        beforeSend:function(){
            // clear errors
            oForm.find('.error').remove();
            showLoader();
        },
        success:function(res){
            hideLoader();
            if(cb !== false){
                cb(res);
            }
        },
        error:function(e){
            console.log(e);
            hideLoader();
            var errors = e.responseJSON;
            if(errors.messages.error){
                alert(errors.messages.error);
            }else{
                $.each(errors.messages, function(key,val){
                    oForm.find('input[name="' + key + '"]').after('<div class="error">' + val + '</div>');
                })
            }
        }
    });
}