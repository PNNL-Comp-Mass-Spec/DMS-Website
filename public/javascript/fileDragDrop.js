/*
 * javascript code supporting forms with file inputs supporting drag-and-drop
 * Based on example from https://css-tricks.com/drag-and-drop-file-uploading/
 *
 * NOTE: This is the code version that depends on jQuery
 */

'use strict';

// supposedly you can remove this if you use Modernizr, but this has also been modified from the default
// This code changes the display based on if javascript is supported by/enabled in the browser
(function(e,t,n)
{
    var r=e.querySelectorAll("form");
    $.each(r, function( i, f )
    {
        f.className=f.className.replace(/(^|\s)no-js(\s|$)/,"$1js$2");
    });
})(document,window,0);

// 
;( function( $, window, document, undefined )
{
    // feature detection for drag&drop upload

    var isAdvancedUpload = function()
        {
            var div = document.createElement( 'div' );
            return ( ( 'draggable' in div ) || ( 'ondragstart' in div && 'ondrop' in div ) ) && 'FormData' in window && 'FileReader' in window;
        }();

    // applying the effect for every form

    $( '.box' ).each( function()
    {
        var $form        = $( this ),
            $input       = $form.find( 'input[type="file"]' ),
            $label       = $form.find( 'label' ),
            $errorMsg    = $form.find( '.box__error span' ),
            $warnMsg     = $form.find( '.box__warn span' ),
            $target      = $( "[name='" + $form.attr( 'target' ) + "']" ),
            droppedFiles = false,
            showFiles    = function( files )
            {
                $label.text( files.length > 1 ? ( $input.attr( 'data-multiple-caption' ) || '' ).replace( '{count}', files.length ) : files[ 0 ].name );
            };

        // letting the server side to know we are going to make an Ajax request
        $form.append( '<input type="hidden" name="ajax" value="1" />' );

        // automatically submit the form on file select
        $input.on( 'change', function( e )
        {
            showFiles( e.target.files );

            $form.trigger( 'submit' );
        });

        // drag&drop files if the feature is available
        if( isAdvancedUpload )
        {
            $form
            .addClass( 'has-advanced-upload' ) // letting the CSS part to know drag&drop is supported by the browser
            .on( 'drag dragstart dragend dragover dragenter dragleave drop', function( e )
            {
                // preventing the unwanted behaviours
                e.preventDefault();
                e.stopPropagation();
            })
            .on( 'dragover dragenter', function() //
            {
                $form.addClass( 'is-dragover' );
            })
            .on( 'dragleave dragend drop', function()
            {
                $form.removeClass( 'is-dragover' );
            })
            .on( 'drop', function( e )
            {
                droppedFiles = e.originalEvent.dataTransfer.files; // the files that were dropped

                $form.removeClass( 'is-warning' );

                // Restrict to only a single dropped file
                if ( droppedFiles.length > 1 )
                {
                    droppedFiles = Array.prototype.slice.call( droppedFiles, 0, 1 );
                    $form.addClass( 'is-warning' );
                    $warnMsg.text( "Multiple files not allowed; kept the first file" );
                }

                showFiles( droppedFiles );

                if ( droppedFiles )
                {
                    // Block uploads of files larger that 1 MB
                    var $fileMBytes = droppedFiles[0].size / 1024 / 1024;
                    if ( $fileMBytes > 1 )
                    {
                        $form.addClass( 'is-error' );
                        $errorMsg.text( "File is too large" );
                    }
                    else
                    {
                        $form.trigger( 'submit' ); // automatically submit the form on file drop
                    }
                }
            });
        }

        // if the form was submitted

        $form.on( 'submit', function( e )
        {
            // preventing the duplicate submissions if the current one is in progress
            if( $form.hasClass( 'is-uploading' ) ) return false;

            $form.addClass( 'is-uploading' ).removeClass( 'is-error' );

            if( isAdvancedUpload ) // ajax file upload for modern browsers
            {
                e.preventDefault();

                // gathering the form data
                var ajaxData = new FormData( $form.get( 0 ) );
                if( droppedFiles )
                {
                    $.each( droppedFiles, function( i, file )
                    {
                        ajaxData.append( $input.attr( 'name' ), file );
                    });
                }

                // ajax request
                $.ajax(
                {
                    url:            $form.attr( 'action' ),
                    method:         $form.attr( 'method' ),
                    data:           ajaxData,
                    dataType:       'html',
                    cache:          false,
                    contentType:    false,
                    processData:    false,
                    complete: function()
                    {
                        $form.removeClass( 'is-uploading' );
                    },
                    success: function( data )
                    {
                        $target.append(data);
                        //$form.addClass( data.success == true ? 'is-success' : 'is-error' );
                        //if( !data.success ) $errorMsg.text( data.error );
                    },
                    error: function()
                    {
                        alert( 'Error. Please, contact the webmaster!' );
                    }
                });
            }
            else // fallback Ajax solution upload for older browsers
            {
                var iframeName  = 'uploadiframe' + new Date().getTime(),
                    $iframe     = $( '<iframe name="' + iframeName + '" style="display: none;"></iframe>' );

                $( 'body' ).append( $iframe );
                $form.attr( 'target', iframeName );

                $iframe.one( 'load', function()
                {
                    var data = $iframe.contents().find( 'body' );
                    $form.removeClass( 'is-uploading' ).addClass( data.success == true ? 'is-success' : 'is-error' ).removeAttr( 'target' ); // TODO: Need to fix this!!!
                    if( !data.success ) $errorMsg.text( data.error );
                    $iframe.remove();
                });
            }
        });

        // Firefox focus bug fix for file input
        $input
        .on( 'focus', function(){ $input.addClass( 'has-focus' ); })
        .on( 'blur', function(){ $input.removeClass( 'has-focus' ); });
    });

})( jQuery, window, document );