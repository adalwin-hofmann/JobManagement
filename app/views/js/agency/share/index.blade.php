<script>
    function showUserView(obj) {
        var userId = $(obj).attr('data-userid');
        $.ajax({
            url:"{{ URL::route('agency.user.async.view') }}",
            dataType : "json",
            type : "POST",
            data : {user_id : userId},
            success : function(data){
                if (data.result == 'success') {
                    $('div#js-div-userview').empty();
                    $('div#js-div-userview').html(data.userView);
                    $('div#js-div-userview').fadeIn('normal');
                }
            }
        });
    }

    function hideUserView() {
        $('div#js-div-userview').fadeOut('normal');
    }

    function sendInvite(obj) {
        var userId = $(obj).attr('data-userid');
        var jobId = $(obj).attr('data-jobid');

        $(obj).html('<img src="{{ HTTP_IMAGE_PATH.'loading.gif' }}" style="height: 16px;">');

        $.ajax({
            url:"{{ URL::route('agency.user.async.sendInvite') }}",
            dataType : "json",
            type : "POST",
            data : {user_id : userId, job_id: jobId},
            success : function(data){
                if (data.result == 'success') {
                    $(obj).html('Invited');
                    $(obj).addClass('disabled');
                }
            }
        });
    }


    $(document).mouseup(function (e)
    {
        var container = $("div#js-div-user-detail-view");

        if (!container.is(e.target) // if the target of the click isn't the container...
            && container.has(e.target).length === 0) // ... nor a descendant of the container
        {
            hideUserView();
        }
    });


    var TableManaged = function () {

        var initTable1 = function () {

            var table = $('#job_table');

            // begin first table
            table.dataTable({
                "columns": [{
                    "orderable": true
                }, {
                    "orderable": true,
                    "searchable": true
                }, {
                    "orderable": true,
                    "searchable": true
                }, {
                    "orderable": false
                }, {
                    "orderable": false
                }],
                "lengthMenu": [
                    [5, 15, 20, -1],
                    [5, 15, 20, "All"] // change per page values here
                ],
                // set the initial value
                "pageLength": 5,
                "pagingType": "bootstrap_full_number",
                "language": {
                    "lengthMenu": "  _MENU_ records",
                    "paginate": {
                        "previous":"Prev",
                        "next": "Next",
                        "last": "Last",
                        "first": "First"
                    }
                },
                "columnDefs": [{  // set default column settings
                    'orderable': false,
                    'targets': [0]
                }, {
                    "searchable": false,
                    "targets": [0]
                }],
                "order": [
                    [1, "asc"]
                ] // set first column as a default sort by asc
            });

            var tableWrapper = jQuery('#job_table_wrapper');

            table.find('.group-checkable').change(function () {
                var set = jQuery(this).attr("data-set");
                var checked = jQuery(this).is(":checked");
                jQuery(set).each(function () {
                    if (checked) {
                        $(this).attr("checked", true);
                        $(this).parents('tr').addClass("active");
                    } else {
                        $(this).attr("checked", false);
                        $(this).parents('tr').removeClass("active");
                    }
                });
                jQuery.uniform.update(set);
            });

            table.on('change', 'tbody tr .checkboxes', function () {
                $(this).parents('tr').toggleClass("active");
            });

            tableWrapper.find('.dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown
        }


        var initTable2 = function () {

            var table = $('#user_table');

            // begin first table
            table.dataTable({
                "columns": [{
                    "orderable": true
                }, {
                    "orderable": true,
                    "searchable": true
                }, {
                    "orderable": true,
                    "searchable": true
                }, {
                    "orderable": true,
                    "searchable": true
                }, {
                    "orderable": false
                }, {
                    "orderable": false
                }],
                "lengthMenu": [
                    [5, 15, 20, -1],
                    [5, 15, 20, "All"] // change per page values here
                ],
                // set the initial value
                "pageLength": 5,
                "pagingType": "bootstrap_full_number",
                "language": {
                    "lengthMenu": "  _MENU_ records",
                    "paginate": {
                        "previous":"Prev",
                        "next": "Next",
                        "last": "Last",
                        "first": "First"
                    }
                },
                "columnDefs": [{  // set default column settings
                    'orderable': false,
                    'targets': [0]
                }, {
                    "searchable": false,
                    "targets": [0]
                }],
                "order": [
                    [1, "asc"]
                ] // set first column as a default sort by asc
            });

            var tableWrapper = jQuery('#user_table_wrapper');

            table.find('.group-checkable').change(function () {
                var set = jQuery(this).attr("data-set");
                var checked = jQuery(this).is(":checked");
                jQuery(set).each(function () {
                    if (checked) {
                        $(this).attr("checked", true);
                        $(this).parents('tr').addClass("active");
                    } else {
                        $(this).attr("checked", false);
                        $(this).parents('tr').removeClass("active");
                    }
                });
                jQuery.uniform.update(set);
            });

            table.on('change', 'tbody tr .checkboxes', function () {
                $(this).parents('tr').toggleClass("active");
            });

            tableWrapper.find('.dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown
        }

        var initTable3 = function () {

            var table = $('#interview_table');

            // begin first table
            table.dataTable({
                "columns": [{
                    "orderable": true
                }, {
                    "orderable": true,
                    "searchable": true
                }, {
                    "orderable": true,
                    "searchable": true
                }, {
                    "orderable": true,
                    "searchable": true
                }, {
                    "orderable": true,
                    "searchable": true
                }, {
                    "orderable": false
                }, {
                    "orderable": false
                }],
                "lengthMenu": [
                    [5, 15, 20, -1],
                    [5, 15, 20, "All"] // change per page values here
                ],
                // set the initial value
                "pageLength": 5,
                "pagingType": "bootstrap_full_number",
                "language": {
                    "lengthMenu": "  _MENU_ records",
                    "paginate": {
                        "previous":"Prev",
                        "next": "Next",
                        "last": "Last",
                        "first": "First"
                    }
                },
                "columnDefs": [{  // set default column settings
                    'orderable': false,
                    'targets': [0]
                }, {
                    "searchable": false,
                    "targets": [0]
                }],
                "order": [
                    [1, "asc"]
                ] // set first column as a default sort by asc
            });

            var tableWrapper = jQuery('#user_table_wrapper');

            table.find('.group-checkable').change(function () {
                var set = jQuery(this).attr("data-set");
                var checked = jQuery(this).is(":checked");
                jQuery(set).each(function () {
                    if (checked) {
                        $(this).attr("checked", true);
                        $(this).parents('tr').addClass("active");
                    } else {
                        $(this).attr("checked", false);
                        $(this).parents('tr').removeClass("active");
                    }
                });
                jQuery.uniform.update(set);
            });

            table.on('change', 'tbody tr .checkboxes', function () {
                $(this).parents('tr').toggleClass("active");
            });

            tableWrapper.find('.dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown
        }

        return {

            //main function to initiate the module
            init: function () {
                if (!jQuery().dataTable) {
                    return;
                }

                initTable1();
                initTable2();
                initTable3();
            }

        };

    }();

    $(document).ready(function() {
        TableManaged.init();
    });


    function disableSharing(obj) {
        var shareId = $(obj).attr('data-id');

        bootbox.confirm('Are you sure?', function() {
            $.ajax({
                url:"{{ URL::route('agency.share.async.removeShare') }}",
                dataType : "json",
                type : "POST",
                data : {share_id: shareId},
                success : function(data){
                    if (data.result == 'success') {
                        location.reload();
                    }
                }
            });
        });
    }

    function showInterview(obj) {
        var interviewId = $(obj).attr('data-id');

        $.ajax({
            url:"{{ URL::route('agency.profile.async.viewInterview') }}",
            dataType : "json",
            type : "POST",
            data : {interview_id: interviewId},
            success : function(data){
                if (data.result == 'success') {
                    $('div#interview-modal-content').empty();
                    $('div#interview-modal-content').html(data.interviewView);
                    $('div#interviewModal').modal();
                }
            }
        });
    }

</script>