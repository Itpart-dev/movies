/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)

import $ from 'jquery';
import 'bootstrap-autocomplete/dist/latest/bootstrap-autocomplete.min';
import 'bootstrap';

import './styles/app.scss';




$('.js-autocomplete').change(function() {
    const autocompleteUrl = window.location.origin + $(this).data('autocomplete-url');

    let query = $('.js-autocomplete').val();

    $.get( autocompleteUrl+'/'+query, function( data ) {
        console.log(data);
    });

    $('.basicAutoComplete').autoComplete();

});

$('body').on('click', '#modal', (event) => {
    const $that = $(event.currentTarget);
    const id = $that.data('source');
    const autocompleteUrl = window.location.origin + '/movie';
    $.get( autocompleteUrl+'/'+id, function( data ) {

        let response = '<div class="modal-header">\n' +
            '                    <h5 class="modal-title" id="exampleModalLabel">'+ data.original_title + '</h5>\n' +
            '                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">\n' +
            '                        <span aria-hidden="true">&times;</span>\n' +
            '                    </button>\n' +
            '                </div>\n' +
            '                <div class="modal-body">\n' +
            '                    <div data-component-video="" style="min-height:240px;min-width:240px;position:relative">' +
            '<iframe frameborder="0" src="//www.youtube.com/embed/'+ embedVideo(data.link) +'" style="width:100%;height:100%;position:absolute;left:0px;pointer-events:none">' +
            '</iframe>' +
            '</div>' +
            ' <p class="media-body pb-3 mb-0 small lh-125">\n' +
            '                            <strong class="d-block text-gray-dark">'+ data.genres +' </strong>\n' +
           data.overview +
            '                        </p>'
            '                </div>';
        $('.modal-content').html(response);
        $('#exampleModal').modal('show');
    });

});


function embedVideo(url) {
    const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
    const match = url.match(regExp);

    return (match && match[2].length === 11)
        ? match[2]
        : null;
}

