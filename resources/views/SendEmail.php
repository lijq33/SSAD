<html>
   <head>
      <title>CMS Website</title>
      
      <script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js">
      </script>
      
      <script>
         function sendemail() {
            $.ajax({
               type:'POST',
               url:'/api/sendemail',
               data:'_token = <?php echo csrf_token() ?>',
               success:function(data) {
                  $("#msg").html(data.msg);
               }
            });
         }
      </script>
   </head>
   
   <body onload="sendemail()">
   </body>

</html>