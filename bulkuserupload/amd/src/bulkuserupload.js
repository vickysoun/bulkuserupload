define(['jquery'], function ($) {
    return {
        confirmcsv: function () {
            $(document).on('click', '#id_confirmupload', function (e) {
                e.preventDefault();
                
                var cir_id = $('input[name="cir_id"]').val();
                
                $.ajax({
                    type: "POST",
                    url: M.cfg.wwwroot + '/local/bulkuserupload/ajax.php',
                    data: {"cir_id" : cir_id},
                    success: function (data) {
                        if(data == 1){
                            alert("CSV uploaded successfully !");
                            window.location.replace(M.cfg.wwwroot + "/local/bulkuserupload/usersdetails.php");
                        }
                    }
                });

            });
        }
    }
});