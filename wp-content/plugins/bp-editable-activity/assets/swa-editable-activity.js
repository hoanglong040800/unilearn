jQuery( document ).ready( function(){
    //let us dome some magic
    var jq = jQuery;

    jq( document) .on( 'click', '.swa-activity-meta .acomment-edit', function () {
        var $this = jq(this);
        var $meta = $this.parents('.swa-activity-meta');
        $meta.hide();
        var activity_id = $this.data('id');

        var element = $this.parents('li.activity-item').find('.swa-activity-inner');

        var settings = { };
        var data = jq('#swa-acomment-edit-'+ activity_id ).data('value');
        jq( element ).editable( function (value, settings ){

            jq.post(ajaxurl, {
                action	: 'editable_activity_update',
                id		: activity_id,
                value	: value,
                nonce	: jq( '#_activity_edit_nonce_'+activity_id).val(),
                cookie	: encodeURIComponent( document.cookie )
            }, function (response ) {

                if( ! response.success ) {
                    //append error to the top
                    error( 'swa-activity-'+activity_id , response.message );
                } else {
                    clear_error('swa-activity-'+activity_id );
                }

            }, 'json');
            jq('#swa-acomment-edit-'+ activity_id ).data('value', value);
            $meta.show();
            return value;
        }, {
            type		: 'textarea',
            onblur		: 'ignore',
            onreset		: function () {
                $meta.show();
            },
            cancel		: BPEditableActivity.cancel_label,
            submit		: BPEditableActivity.submit_label,
            rows		: 5,
            data		: data,
            loaddata1	: function ( content_original, element ) {
                return jq.trim(content_original);
            }
        } );
        //clear error message
        clear_error('swa-activity-'+activity_id);

        jq(element).click();//simulate click
        //destroy the element to avoid click to edit
        //that will be just bad ui as we already have edit button
        jq(element).editable('destroy');
        return false;
    });

    jq( document) .on( 'click', '.swa-activity-meta .swa-acomment-reply-edit, .swa-acomment-options .swa-acomment-reply-edit', function () {
        var $this = jq(this);
        var $meta = $this.parents('.swa-acomment-options');
        $meta.hide();
        var activity_id = $this.data('id');
        var data = jq('#swa-acomment-reply-edit-'+ activity_id ).data('value');
        var element = $this.parent().siblings('.swa-acomment-content');
        jq( element ).editable( function (value, settings ){

            jq.post(ajaxurl, {
                action	: 'editable_activity_comment_update',
                id		: activity_id,
                value	: value,
                nonce	: jq( '#_activity_edit_nonce_'+activity_id).val(),
                cookie	: encodeURIComponent( document.cookie )
            }, function (response ) {

                if(response.error !==undefined ) {
                    //append error to the top
                    error( 'swa-acomment-'+activity_id , response.message );
                } else{
                    clear_error('swa-acomment-'+activity_id );
                }

            }, 'json');
            jq('#swa-acomment-reply-edit-'+ activity_id ).data('value', value);
            $meta.show();
            return value;
        }, {
            type		: 'textarea',
            onblur		: 'ignore',
            onreset		: function () {
                $meta.show();
            },
            cancel		: BPEditableActivity.cancel_label,
            submit		: BPEditableActivity.submit_label,
            rows		: 5,
            data		: data,
            loaddata1	: function ( content_original, element ) {
                // console.log(content_original);
                // console.log( element );

                return jq.trim(content_original);
            }
        } );
        //clear error message
        clear_error('swa-activity-'+activity_id);

        jq(element).click();//simulate click
        //destroy the element to avoid click to edit
        //that will be just bad ui as we already have edit button
        jq(element).editable('destroy');
        return false;
    });

    function error( id, message ) {
        //clear error message
        clear_error(id);
        jq('#'+id).append( jq( "<div id='message' class='error'><p>"+message + "</p></div>" ) );
    }

    function clear_error( id ) {
        var error = jq('#'+id).find('#message');
        if( error.get(0) ) {
            error.remove();
        }
    }
});