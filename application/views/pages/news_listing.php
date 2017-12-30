<?php foreach($news as $news_item): ?>
  <a href = "news/view/<?php echo $news_item->id; ?>" style = "text-decoration: none; color: black">
    <div class = "news-listing-item">
      <h1> Title: <?php echo $news_item->title; ?> </h1>
      <p> Created <?php echo $news_item->dateCreated; ?>, Edited <?php echo $news_item->dateEdited; ?> </p>
      <p> Content: <?php echo $news_item->content; ?> </p>
    </div>
  </a>
<?php endforeach; ?>

  <?php echo "<div class = 'pagination'>" . $links . "</div>"; ?>
