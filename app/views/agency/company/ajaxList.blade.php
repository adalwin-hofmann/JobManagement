{{ HTML::style('/assets/metronic/assets/global/plugins/select2/select2.css') }}
{{ HTML::style('/assets/metronic/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') }}
{{ HTML::style('/assets/css/star-rating.min.css') }}

<div class="row">
    @if ($agency->clients()->get()->count() > 0)
    <table class="table table-striped table-bordered table-hover" id="sample_1">
        <thead>
            <tr>
                <th>
                     {{ trans('user.name') }}
                </th>
                <th>
                     {{ trans('user.email') }}
                </th>
                <th>
                     {{ trans('user.rating') }}
                </th>
                <th>
                    {{ trans('job.share') }}
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($agency->clients as $item)
                <tr class="odd gradX">
                    <td>
                        <div class="white-tooltip" style="position:relative;" data-toggle="tooltip" data-html="true" data-placement="right" data-image-url="{{ HTTP_LOGO_PATH.$item->company->logo }}" data-tag="{{ $item->company->tag }}" data-description="{{ nl2br($item->company->description) }}">
                            <div style="display: inline-block; margin-top: 5px;">
                                <span><a href="{{ URL::route('user.company.view', $item->company->slug) }}">{{ $item->company->name }}</a></span>
                            </div>
                        </div>
                    </td>
                    <td>
                        {{ $item->company->email }}
                    </td>
                    <td>
                        <input id="input-rate" name="rating" class="rating" data-size="xs" data-default-caption="{rating}" data-star-captions="{}" style="float: left;" value="{{ round($item->company->parent->reviews()->avg('score')) }}" readonly>
                    </td>
                    <td>
                        <a onclick="shareToCompany(this)" data-id="{{ $item->company->id }}" class="btn btn-sm blue-hoki" target="_blank">
                            {{ trans('job.share') }}
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @else
        <div class="col-sm-12 text-center">
            <span>There are no clients, Please set it on Companies page.</span>
        </div>
    @endif
</div>


{{ HTML::script('/assets/metronic/assets/global/plugins/select2/select2.min.js') }}
{{ HTML::script('/assets/metronic/assets/global/plugins/datatables/media/js/jquery.dataTables.min.js') }}
{{ HTML::script('/assets/metronic/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') }}
{{ HTML::script('/assets/js/star-rating.min.js') }}

<script type="text/javascript">

var TableManaged = function () {

    var initTable1 = function () {

        var table = $('#sample_1');

        // begin first table
        table.dataTable({
            "columns": [{
                "orderable": true,
                "searchable": true
            }, {
                "orderable": true,
                "searchable": true
            }, {
                "orderable": true
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

        var tableWrapper = jQuery('#sample_1_wrapper');

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
        }

    };

}();

TableManaged.init();

$(function () {
	$('[data-toggle="tooltip"]').tooltip({
		title: function() {
				return "<img src='" + $(this).attr('data-image-url') + "' style='width:180px; margin-top: 5px;'><br/>&quot;" + $(this).attr('data-tag') + "&quot;<br/><br/>" + $(this).attr('data-description');
			}
	})
});


</script>