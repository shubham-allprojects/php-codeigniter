"use strict";

/**
 * Common properties reading it from header file
 * @baseURL
 * @jwt_token
 * @CSRF_token
 */
const baseURL = $('.main').attr('baseURL');
const jwt_token = $('#jwt_token').val();
const csrf_token = $('#csrf_token').val();

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

    $.ajax({
        method: e.target.method,
        url: e.target.action,
        headers:{
            "Authorization": `Bearer ${token}`
        },
        data: oFormData,
        contentType: false,
        processData: false,
        beforeSend:function(){},
        success:function(){},
        error:function(){}
    });
}

const putRequestViaAjax = (e, cb=false) => {}
const getRequestViaAjax = (e, cb=false) => {}
const deleteRequestViaAjax = (e, cb=false) => {}