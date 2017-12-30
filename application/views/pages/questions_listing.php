<div> Questions </div>
<a href = "questions/create"><span class = "glyphicon glyphicon-pencil"></span></a>

<?php if(isset($questions)){ ?>
  <?php foreach($questions as $question): ?>
    <!-- <div> Title: <?php echo $question->title; ?> </div> -->
    <a href = "questions/view/<?php echo $question->id; ?>" style = "text-decoration: none; color: black">
      <div class = "news-listing-item">
        <h1> Title: <?php echo $question->title; ?> </h1>
        <p> Created <?php echo $question->dateCreated; ?>, Edited <?php echo $question->dateEdited; ?> </p>
        <p> Content: <?php echo $question->content; ?> </p>
      </div>
    </a>
  <?php endforeach; ?>
<?php } ?>
