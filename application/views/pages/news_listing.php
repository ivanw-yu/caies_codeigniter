<?php foreach($news as $news_item): ?>
  <div>
    <h1> Title: <?php echo $news_item->title; ?> </h1>
    <p> Created <?php echo $news_item->dateCreated; ?>, Edited <?php echo $news_item->dateEdited; ?> </p>
    <p> Content: <?php echo $news_item->content; ?> </p>
  </div>
<?php endforeach; ?>

<!-- <div>
  <?php echo $links; ?>
</div> -->
