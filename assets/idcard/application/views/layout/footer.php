<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds and memory <strong>{memory_usage}</strong></p>
</div>

</body>
</html>
<script src="<?php echo base_url(); ?>assets/js/jquery-1.10.2.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery-ui.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.fancybox.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
<script type="text/javascript">
  $(document).ready(function () {
    $(".fancybox").fancybox({
      closeClick  : false,
      afterClose  : function() {
          parent.location.reload(true);
      },
      helpers   : { 
        overlay : {closeClick: false}
      }

    });
    $(".fancybox-nonrefresh").fancybox({
      closeClick  : false,
      helpers   : { 
        overlay : {closeClick: false}
      }

    });
  });
</script>    