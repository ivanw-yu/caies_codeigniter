
<div class = "news-org-header-section">
  <div class = "news-org-header">CAIES News</div>
</div>
<div class = "news-buttons-section">
    <button class = "btn btn-primary" onclick = "changeNews('all')"> All </button>
    <button class = "btn btn-primary" onclick = "changeNews('events')"> Events </button>
    <button class = "btn btn-primary" onclick = "changeNews('opportunities')"> Opportunities </button>
    <button class = "btn btn-primary" onclick = "changeNews('others')"> Other </button>
</div>

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

<script>
  function changeNews(type){
    window.location.href = "<?php echo base_url('news?category='); ?>" + type;
    // $.ajax({
    //   url: 'news?category=' + type,
    //   type: 'get',
    //   success: function(serverResponse){
    //
    //   }
    // })
  }
</script>
