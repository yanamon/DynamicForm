$(document).ready(function() {

    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
    });

    $('#btn-export').click(function(){
        $('#dynamic-form').submit();
    });
});