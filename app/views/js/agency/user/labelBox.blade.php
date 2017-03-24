<script>

//Label Select Box
function showLabelBox(obj) {
    var labelIds = $(obj).attr('data-labelids').split(",");

    if ($(obj).parent('div').eq(0).find('#label-box-container').eq(0).is(':empty')) {
        var userId = $(obj).attr('data-id');
        var objClone = $("div#js-div-label-box").clone(true).removeClass('hidden');
        objClone.attr('id', '');
        objClone.find('#js-input-user-id').val(userId);

        for (var i = 0; i < labelIds.length; i ++) {
            var labelTarget = 'div#label-item-' + labelIds[i];
            objClone.find(labelTarget).eq(0).addClass('selected');
        }

        $(obj).parent('div').eq(0).find('#label-box-container').append(objClone);
    }
}

function closeLabelBox(obj) {
    $(obj).parents('div.select-menu-modal').eq(0).remove();

    var userId = $(obj).parents('div.select-menu-modal').eq(0).find('input#js-input-user-id').val();
    var divTarget = 'div#user-detail-container-' + userId;

    var currentUrl = window.location.href;

    if (currentUrl.indexOf('find') >= 0) {
        $.ajax({
            url: "{{ URL::route('agency.user.async.detailView') }}",
            dataType : "json",
            type : "POST",
            data : {user_id: userId},
            success : function(data) {
                if (data.result == 'success') {
                    $(divTarget).empty();
                    $(divTarget).html(data.listView);
                }
            }
        });
    }else if (currentUrl.indexOf('applied') >= 0) {
         $.ajax({
             url: "{{ URL::route('agency.user.async.appliedDetailView') }}",
             dataType : "json",
             type : "POST",
             data : {user_id: userId},
             success : function(data) {
                 if (data.result == 'success') {
                     $(divTarget).empty();
                     $(divTarget).html(data.listView);
                 }
             }
         });
    }

}

$('div.js-navigation-item').click(function() {
    var userId = $(this).parents('div.select-menu-modal').eq(0).find('input#js-input-user-id').val();
    var labelId = $(this).attr('data-id');

    if ($(this).hasClass('selected')) {
        $(this).removeClass('selected');
        $.ajax({
            url: "{{ URL::route('agency.user.async.removeLabel') }}",
            dataType : "json",
            type : "POST",
            data : {user_id: userId, label_id: labelId},
            success : function(data) {
            }
        });
    }else {
        $(this).addClass('selected');
        $.ajax({
            url: "{{ URL::route('agency.user.async.addLabel') }}",
            dataType : "json",
            type : "POST",
            data : {user_id: userId, label_id: labelId},
            success : function(data) {
            }
        });
    }
});


$('input#label-filter-field').keyup(function() {
    var textValue = $(this).val();
    var count = 0;

    $(this).parents('div.select-menu-modal').eq(0).find('div.select-menu-item').each(function() {
        var labelName = $(this).attr('data-name');

        if (labelName.toLowerCase().indexOf(textValue.toLowerCase()) >= 0) {
            $(this).css('display', 'table');
            count ++;
        }else {
            $(this).css('display', 'none');
        }
    })

    if (count == 0) {
        $(this).parents('div.select-menu-modal').eq(0).find('div.select-menu-no-results').eq(0).css('display', 'block');
    }else {
        $(this).parents('div.select-menu-modal').eq(0).find('div.select-menu-no-results').eq(0).css('display', 'none');
    }
});

</script>