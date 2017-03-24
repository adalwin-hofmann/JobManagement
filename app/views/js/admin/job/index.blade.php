<script>
    $('a#companyname').bind("DOMNodeInserted",function(){
        var companyId = $(this).attr('data-id');
        var companyName = $(this).html();

        if (companyName.length == 0) {
            bootbox.alert("Company Name can not be blank.");
            return;
        }

        $.ajax({
            url: "{{ URL::route('admin.async.updateCompany') }}",
            dataType : "json",
            type : "POST",
            data : {company_id : companyId, company_name : companyName},
            success : function(data) {
                if (data.result == 'success') {
                    location.reload();
                }
            }
        });
    });


    $('a#cityname').bind("DOMNodeInserted",function(){
        var cityId = $(this).attr('data-id');
        var cityName = $(this).html();

        if (cityName.length == 0) {
            bootbox.alert("City Name can not be blank.");
            return;
        }

        $.ajax({
            url: "{{ URL::route('admin.async.updateCity') }}",
            dataType : "json",
            type : "POST",
            data : {city_id : cityId, city_name : cityName},
            success : function(data) {
                if (data.result == 'success') {
                    location.reload();
                }
            }
        });
    });


    $('a#categoryname').bind("DOMNodeInserted",function(){
        var categoryId = $(this).attr('data-id');
        var categoryName = $(this).html();

        if (categoryName.length == 0) {
            bootbox.alert("Category Name can not be blank.");
            return;
        }

        $.ajax({
            url: "{{ URL::route('admin.async.updateCategory') }}",
            dataType : "json",
            type : "POST",
            data : {category_id : categoryId, category_name : categoryName},
            success : function(data) {
                if (data.result == 'success') {
                    location.reload();
                }
            }
        });
    });

    function changeStatus(obj) {
        var jobId = $(obj).attr('data-id');
        $.ajax({
            url: "{{ URL::route('admin.async.updateJobStatus') }}",
            dataType : "json",
            type : "POST",
            data : {job_id : jobId},
            success : function(data) {
                if (data.result == 'success') {
                    var status = data.activeStatus;
                    status = status * 1;
                    if (status == 0) {
                        $(obj).removeClass('green');
                        $(obj).addClass('default');
                        $(obj).html('Deactive');
                    }else {
                        $(obj).removeClass('default');
                        $(obj).addClass('green');
                        $(obj).html('Active');
                    }
                }
            }
        });
    }


    $(document).ready(function() {
        $("a#js-a-delete").click(function(event) {
            event.preventDefault();
            var url = $(this).attr('href');
            bootbox.confirm("Are you sure?", function(result) {
                if (result) {
                    window.location.href = url;
                }
            });
        });
    });
</script>