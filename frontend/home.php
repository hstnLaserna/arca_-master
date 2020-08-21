
        
HOME


<script>
    $("#home").addClass('active').siblings().removeClass('active');
    $('title').replaceWith('<title>Home - <?php echo "Welcome, $first_name"; ?></title>');
</script>