var jq = jQuery.noConflict();
jq(document).ready(function () {
    jq.validator.addMethod("sistemaPhone", function(value, element) {
        return this.optional(element) || /^[0-9]{3}-[0-9]{3}-[0-9]{4}$/i.test(value);
    }, "The phone number must be in the format xxx-xxx-xxxx");
    jq.validator.addMethod("sistemaDob", function(value, element) {
        return this.optional(element) || /^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/.test(value);
    }, "Date of birth must be in the format YYYY-MM-DD");
    // Initialize form validation on the application form.
    jq("#sistema-custom-application-form").validate({
        // Specify validation rules
        rules: {

            second_parent_pp: {
                sistemaPhone: true
            },
            second_parent_wp: {
                sistemaPhone: true
            },
            second_parent_cp: {
                sistemaPhone: true
            },
            emerg_number_1: {
                sistemaPhone: true
            },
            emerg_number_2: {
                sistemaPhone: true
            },
            dob: {
                sistemaDob: true
            },
            academic_waiver: {
                required: true
            },
            media_waiver: {
                required: true
            }
        },
        // Specify validation error messages
        messages: {
            first: {
                required: 'This field is required'
            },
            last: {
                required: 'This field is required'
            },
            dob: {
                required: 'This field is required'
            },
            relationship: {
                required: 'This field is required'
            },
            school: {
                required: 'This field is required'
            },
            grade: {
                required: 'This field is required'
            },
            teacher_title: {
                required: 'This field is required'
            },
            teacher_last: {
                required: 'This field is required'
            },
            emerg_number_1: {
                required: 'This field is required'
            },
            emerg_contact_1: {
                required: 'This field is required'
            },
            emerg_1_rel: {
                required: 'This field is required'
            },
            auth_pickup: {
                required: 'This field is required'
            },
            conduct_waiver: {
                required: 'This field is required'
            },
            liability_release: {
                required: 'This field is required'
            },
            lending_policy: {
                required: 'This field is required'
            },
            inhaler: {
                required: 'This field is required'
            },
            epi_pen: {
                required: 'This field is required'
            },
            capable: {
                required: 'This field is required'
            },
            oen: {
                required: 'This field is required'
            },
            medical_waiver: {
                required: 'This field is required'
            },
            academic_waiver: {
                required: 'This field is required'
            },
            media_waiver: {
                required: 'This field is required'
            }
        },
        // Make sure the form is submitted to the destination defined
        // in the "action" attribute of the form when valid
        submitHandler: function(form) {
            form.submit();
        },
        errorPlacement: function(error, element) {
            error.appendTo( element.parent("div") );
        },
    });
});