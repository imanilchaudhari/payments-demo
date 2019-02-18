jQuery(document).on("pjax:timeout", function(event) {
    // Prevent default timeout redirection behavior
    event.preventDefault();
});

jQuery(document).on("pjax:send", function(event, xhr, settings) {
    event.preventDefault();
    // console.log(settings.target);
});

jQuery(document).on("pjax:end", function(event, xhr, settings) {
    event.preventDefault();
    console.log(settings);
});
