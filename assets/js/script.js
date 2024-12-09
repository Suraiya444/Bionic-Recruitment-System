
// jQuery(document).ready(function($) {
//     // Handle form submission
//     $('#bionic-recruitment-form').on('submit', function(e) {
//         e.preventDefault();

//         // Collect form data (including file upload)
//         var formData = new FormData(this);

//         // Add nonce to the formData for security
//         formData.append('action', 'brs_submit_form');
//         formData.append('nonce', brs_ajax_obj.nonce);//brs_submit_form
//         console.log(brs_ajax_obj.ajax_url); 

//         // Send the AJAX request
//         $.ajax({
//             url: brs_ajax_obj.ajax_url, // The WordPress AJAX URL
//             type: 'POST',
//             data: formData,
//             contentType: false, // Tell jQuery not to process the data
//             processData: false, // Tell jQuery not to transform the data into a query string
//             success: function(response) {
//                 alert(response || 'Form submitted successfully!');
//                 $('#bionic-recruitment-form')[0].reset(); // Reset the form after successful submission
//             },
//             error: function(xhr, status, error) {
//                 alert('Error: ' + error);
//             }
//         });
//     });
// });

// // Function to show the next step in the form
// function nextStep() {
//     document.getElementById('step-1').style.display = 'none';
//     document.getElementById('step-2').style.display = 'block';
// }



// jQuery(document).ready(function($) {
//     // Handle form submission
//     $('#bionic-recruitment-form').on('submit', function(e) {
//         e.preventDefault();

//         // Collect form data (including file upload)
//         var formData = new FormData(this);

//         // Add nonce to the formData for security
//         formData.append('action', 'brs_submit_form');
//         formData.append('nonce', brs_ajax_obj.nonce); // Use the localized nonce

//         console.log(brs_ajax_obj.ajax_url);  // Just for debugging

//         // Send the AJAX request
//         $.ajax({
//             url: brs_ajax_obj.ajax_url, // The WordPress AJAX URL
//             type: 'POST',
//             data: formData,
//             contentType: false, // Tell jQuery not to process the data
//             processData: false, // Tell jQuery not to transform the data into a query string
//             success: function(response) {
//                 alert(response || 'Form submitted successfully!');
//                 $('#bionic-recruitment-form')[0].reset(); // Reset the form after successful submission
//             },
//             error: function(xhr, status, error) {
//                 alert('Error: ' + error);
//             }
//         });
//     });
// });

// // Function to show the next step in the form
// function nextStep() {
//     document.getElementById('step-1').style.display = 'none';
//     document.getElementById('step-2').style.display = 'block';
// }


jQuery(document).ready(function($) {
    // Handle form submission
    $('#bionic-recruitment-form').on('submit', function(e) {
        e.preventDefault();

        // Disable the submit button to prevent multiple submissions
        $('#submit-button').prop('disabled', true); // Assuming your submit button has the id "submit-button"

        // Collect form data (including file upload)
        var formData = new FormData(this);

        // Add nonce to the formData for security
        formData.append('action', 'brs_submit_form');
        formData.append('nonce', brs_ajax_obj.nonce);

        // Send the AJAX request
        $.ajax({
            url: brs_ajax_obj.ajax_url, // The WordPress AJAX URL
            type: 'POST',
            data: formData,
            contentType: false, // Tell jQuery not to process the data
            processData: false, // Tell jQuery not to transform the data into a query string
            success: function(response) {
                alert(response || 'Form submitted successfully!');
                $('#bionic-recruitment-form')[0].reset(); // Reset the form after successful submission
                $('#submit-button').prop('disabled', false); // Re-enable the submit button
            },
            error: function(xhr, status, error) {
                alert('Error: ' + error);
                $('#submit-button').prop('disabled', false); // Re-enable the submit button if error
            }
        });
    });
});
