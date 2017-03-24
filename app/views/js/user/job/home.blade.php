<script>
$(document).ready(function() {
    $("div.home-category-item").click(function() {
        var categoryId = $(this).attr('data-id');
        $("input[name='category_id']").val(categoryId);
        $("#search_form").submit();
    });
});
</script>