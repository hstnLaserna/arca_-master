
                </div>
            <!--/div-->

        </div>
    </body>


    <script>
        $(document).ready(function(){
            $(".nav li").click(function() {
                $(this).siblings().removeClass('active');
                $(this).addClass('active');
            });
        });
    </script>




</html>