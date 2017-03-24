<script>
    $('a#companyname').bind("DOMNodeInserted",function(){
        var companyId = $(this).attr('data-id');
        var companyName = $(this).html();
        var originVale = $(this).attr('origin-value');

        if (companyName == originVale) return;

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


    $('a#contactemail').bind("DOMNodeInserted",function(){
        var jobId = $(this).attr('data-id');
        var email = $(this).html();
        var originVale = $(this).attr('origin-value');

        if (originVale == email) return;

        if (!isValidEmailAddress(email) && email != '') {
            bootbox.alert("Please input valid email address.");
            location.reload();
            return;
        }

        $.ajax({
            url: "{{ URL::route('admin.async.updateJobContact') }}",
            dataType : "json",
            type : "POST",
            data : {job_id : jobId, email : email},
            success : function(data) {
            }
        });
    });


    $('a#applylink').bind("DOMNodeInserted",function(){
        var jobId = $(this).attr('data-id');
        var link = $(this).html();
        var originVale = $(this).attr('origin-value');

        if (originVale == link || link == 'Empty') return;

        $.ajax({
            url: "{{ URL::route('admin.async.updateJobLink') }}",
            dataType : "json",
            type : "POST",
            data : {job_id : jobId, link : link},
            success : function(data) {
            }
        });
    });

    $('a#cityname').bind("DOMNodeInserted",function(){
        var cityId = $(this).attr('data-id');
        var cityName = $(this).html();
        var originVale = $(this).attr('origin-value');

        if (cityName == originVale) return;

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
        var originVale = $(this).attr('origin-value');

        if (originVale == categoryName) return;

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


    function isValidEmailAddress(emailAddress) {
        var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
        return pattern.test(emailAddress);
    };
</script>