

<script>

    var contacts = [];

    @if (count($contacts) > 0)
        @foreach($contacts as $contact)
            contacts['{{ $contact->id }}'] = [];
            contacts['{{ $contact->id }}']['name'] = '{{ $contact->name }}';
            contacts['{{ $contact->id }}']['email'] = '{{ $contact->email }}';
            contacts['{{ $contact->id }}']['phone'] = '{{ $contact->phone }}';
            contacts['{{ $contact->id }}']['previousJobs'] = '{{ $contact->previousJobs }}';
        @endforeach
    @endif

    $('a#username').bind("DOMNodeInserted",function(){
        var contactId = $(this).attr('data-id');
        updateContact(contactId);
    });

    $('a#useremail').bind("DOMNodeInserted",function(){
        var contactId = $(this).attr('data-id');
        updateContact(contactId);
    });

    $('a#userphone').bind("DOMNodeInserted",function(){
        var contactId = $(this).attr('data-id');
        updateContact(contactId);
    });

    $('a#userpreviousjobs').bind("DOMNodeInserted",function(){
        var contactId = $(this).attr('data-id');
        updateContact(contactId);
    });

    function updateContact(contactId) {

        var tr_target = 'tr#tr_' + contactId;
        var modal_target = 'div#editContactModal' + contactId;

        var name = $(document).find(tr_target).find('a#username').html();
        var email = $(document).find(tr_target).find('a#useremail').html();
        var phone = $(document).find(tr_target).find('a#userphone').html();
        var previousJobs = $(document).find(tr_target).find('a#userpreviousjobs').html();

        if (name == '') {
            bootbox.alert ('Name is required field.');
            $(document).find(tr_target).find('a#username').html(contacts[contactId]['name']);
            return;
        }

        if (email == '') {
            bootbox.alert ('Email is required field');
            $(document).find(tr_target).find('a#useremail').html(contacts[contactId]['email']);
            return;
        }

        if (previousJobs == 'Empty') {
            previousJobs = '';
        }
        if (name == 'Empty') {
            name = '';
        }
        if (email == 'Empty') {
            email = '';
        }
        if (phone == 'Empty') {
            phone = '';
        }

        $.ajax({
            url: "{{ URL::route('user.dashboard.async.saveContacts') }}",
            dataType : "json",
            type : "POST",
            data : {contact_id: contactId, contact_name: name, contact_email: email, contact_phone: phone, contact_previousJobs: previousJobs},
            success : function(data) {
                if (data.result == 'success') {
                    contacts[contactId]['name'] = name;
                    contacts[contactId]['email'] = email;
                    contacts[contactId]['phone'] = phone;
                    contacts[contactId]['previousJobs'] = previousJobs;

                    $(modal_target).find('input#contact_name').val(name);
                    $(modal_target).find('input#contact_email').val(email);
                    $(modal_target).find('input#contact_phone').val(phone);
                    $(modal_target).find('input#contact_previousJobs').val(previousJobs);

                }else {
                    bootbox.alert(data.msg);
                    $(document).find(tr_target).find('a#username').html(contacts[contactId]['name']);
                    $(document).find(tr_target).find('a#useremail').html(contacts[contactId]['email']);
                    $(document).find(tr_target).find('a#userphone').html(contacts[contactId]['phone']);
                    $(document).find(tr_target).find('a#userpreviousjobs').html(contacts[contactId]['previousJobs']);
                }
            }
        });
    }


    function editContact(obj) {

        var contactId = $(obj).attr('data-id');
        var target = 'div#editContactModal' + contactId;

        $(target).modal();

    }

    function showAddContact() {
        $('div#addContactModal').modal();
    }

    function saveContact(obj) {

        var contactId = $(obj).attr('data-id');
        var target = 'div#editContactModal' + contactId
        if (contactId == '') {
            target = 'div#addContactModal';
        }
        var name = $(obj).parents('div.modal-content').eq(0).find('input#contact_name').val();
        var email = $(obj).parents('div.modal-content').eq(0).find('input#contact_email').val();
        var phone = $(obj).parents('div.modal-content').eq(0).find('input#contact_phone').val();
        var previousJobs = $(obj).parents('div.modal-content').eq(0).find('input#contact_previousJobs').val();

        $(target).modal('hide');

        $.ajax({
            url: "{{ URL::route('user.dashboard.async.saveContacts') }}",
            dataType : "json",
            type : "POST",
            data : {contact_id: contactId, contact_name: name, contact_email: email, contact_phone: phone, contact_previousJobs: previousJobs},
            success : function(data) {
                if (data.result == 'success') {
                    message = data.msg;
                    bootbox.alert(message, function() {
                        location.reload();
                    });
                }else {
                    bootbox.alert(data.msg);
                }
            }
        });
    }

    function deleteContact(obj) {

        var contactId = $(obj).attr('data-id');

        bootbox.confirm("{{ trans('user.are_you_sure') }}?", function(result) {
            if (result) {
                $.ajax({
                    url: "{{ URL::route('user.dashboard.async.deleteContacts') }}",
                    dataType : "json",
                    type : "POST",
                    data : {contact_id: contactId},
                    success : function(data) {
                        if (data.result == 'success') {
                            message = data.msg;
                            bootbox.alert(message, function() {
                                location.reload();
                            });
                        }
                    }
                });
            }
        });
    }

</script>