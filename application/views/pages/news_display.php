<!-- <?php if(!isset($message)){ ?> -->
<div class = "news-layout">
  <div class = "news-layout-header">
    <h1> <?php echo $news->title; ?></h1>
    <p> <?php if(isset($news->dateEdited)){ echo "Updated " . $news->dateEdited; }else{ echo $news->dateCreated; } ?></p>
  </div>
  <div class = "news-layout-content">
    <?php echo $news->content; ?>
  </div>
</div>
<!-- <?php }else{ ?>

<?php } ?> -->
