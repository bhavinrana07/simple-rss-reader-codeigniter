<?php $this->load->view('common/header'); ?>

<body id="feeds">
  <div class="form feeds">

    <ul class="tab-group feeds">
      <li class="tab active all-feeds"><a href="#">All Feeds</a></li>

      <?php $count = 0;
      foreach ($filters as $filter_name => $filter_count) {  ?>
        <li class="tab filter"><a data-filter-by="<?php echo $filter_name;  ?>" href="#"><?php echo $filter_name . '(' . $filter_count . ')';  ?></a></li>
      <?php $count++;
        if ($count == 10) break;
      } ?>

    </ul>

    <div class="tab-content feeds">
      <div id="signup">
        <p class="welcome_message"> Welcome <?php echo $full_name;  ?> ! | <a href="<?php echo base_url(); ?>index.php/register/logout/">Logout</a></p>
        <div class="logo"><img src="https://www.theregister.co.uk/Design/graphics/Reg_default/The_Register_r.png"></div>
        <div id="feed_list">
          <?php foreach ($feeds as $feed_item) {  ?>
            <div class="feed_item">
              <div class="title"><a href="<?php echo $feed_item['link'];  ?>" target="_blank"><?php echo $feed_item['title'];  ?></a></div>
              <div class="desc"><?php echo $feed_item['description'];  ?></div>
              <div class="author"><a href="<?php echo $feed_item['author_url'];  ?>" target="_blank"><?php echo $feed_item['author'];  ?></a></div>
            </div>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
  <?php $this->load->view('common/footer'); ?>

</body>

</html>