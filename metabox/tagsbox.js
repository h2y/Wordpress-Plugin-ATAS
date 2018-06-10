!function () {

var $box = $('#atas_box_id');
if(!$box.length) return;

$box.find('.get-boson').click(getBosonTags);
$box.find('.get-local').click(getLocalTags);



function getLocalTags() {
    view_loading();

    var data = {
        action: 'atas_getLocalTags',
        content: getEditorContents(),
        title: getTitle(),
        postId: atas_metabox.postId,
        _wpnonce: atas_metabox.nonce
    };
    $.post(ajaxurl, data, function(ret) {
        afterGetTags(ret);
    }, 'json');
}

function getBosonTags() {
    view_loading();

    var data = {
        action: 'atas_getBosonTags',
        content: getEditorContents(),
        title: getTitle(),
        postId: atas_metabox.postId,
        _wpnonce: atas_metabox.nonce
    };
    $.post(ajaxurl, data, function(ret) {
        afterGetTags(ret);
    }, 'json');
}

function view_loading() {
    $box.find('#tagcloud-post_tag')
        .show('fast')
        .html('<p>&nbsp;<a class="spinner is-active"></a></p>');
}

function afterGetTags(tags) {
    var $out = $box.find('#tagcloud-post_tag'),
        tagContents = $box.find('#atas_autotag_contents')[0];

    $out.empty();

    for(var i=0; i<tags.length; i++) {
        var tagName = tags[i][1],
            $dom = $('<button type="button" style="margin:0.25rem;" class="button button-small"></button>');

        $dom.text(tagName)
            .click(clickTag);

        if(i<5) {
            $dom.click();
        }

        $out.append($dom);
    }
}

function getEditorContents() {
    var cont = (tinyMCE.activeEditor)?
                tinyMCE.activeEditor.getContent() : $('#content').val();

    return cont.replace(/<.+?>/g, ' ');
}

function clickTag(e) {
    var $e = $(e.target),
        tagContents = $box.find('#atas_autotag_contents')[0];

    $e.toggleClass('active');

    // turn on
    if( $e.hasClass('active') ) {
        var tagname = $e.text();
        $e.text('✓ ' + tagname );
        tagContents.value += ',' + tagname;
    }
    // turn off
    else {
        var tagname = $e.text().replace(/^✓ /, '');
        $e.text( tagname );
        tagContents.value = tagContents.value.replace(','+tagname, '');
    }
}

function getTitle() {
    return document.querySelector('#title').value;
}


}();

