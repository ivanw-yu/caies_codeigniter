<h1> News <?php if(isset($news)){ echo "Editing"; }else{ echo "Posting"; } ?> </h1>
<p> Please fill out the require fields in order to publish this news. </p>
<div class = "news-form-box">
  <form id = "news-create-form" method = "post">
    <div class = "form-group">
    <label for= "title">Title: </label>
    <input type = "text" name  ="title" class = "form-control" value = "<?php if(isset($news)){echo $news->title; } ?>">
    </div>
    <div class = "form-group">
    <label for="content"> News Content </label>
    <div class = "div-textarea-button-panel">
      <button id = "bold"><b>B</b></button>
      <button id = "italic"><i>I</i></button>
      <button id = "underline"><u>U</u></button>
      <button id = "editMode" style = "float: right">Edit Mode</button>
      <button id = "viewMode" style = "float: right">View Mode</button>
    </div>
    <div id = "divTextArea"></div>
    <!--style = "display: none">-->
    <textarea id = "content" name = "content" class = "form-control" rows="5" style = "display: block"><?php if(isset($news)){echo $news->content; } ?></textarea>
    </div>
    <label for = "membersOnly">Members Only</label>
    <input id = "membersOnly" name = "membersOnly" type = "checkbox" <?php if(isset($news) && $news->membersOnly){ echo "checked"; }else{ echo ''; } ?> >
    <br>
    <label for = "category">Category</label>
    <select id = "category" name = "category">
      <option value = "others"> Others </option>
      <option value = "events">Events</option>
      <option value = "opportunities">Opportunities </option>
    </select>
    <br>
    <?php if(!isset($news)){ ?>
      <input id = "submitButton" type = "submit" value = "Submit" class = "btn btn-primary">
    <?php } ?>
  </form>

  <?php if(isset($news)){ ?>
    <button click = "saveEdit(<?php echo $news->id; ?>)" id = "saveEditButton" class = "btn btn-primary">Save</button>
  <?php } ?>
  <button id = "testButton">Test</button>
  <p id = "message"></p>
</div>
<br><br><br>

<script>


var divTextArea = document.getElementById('divTextArea');
var content = document.getElementById('content');
var cursor = 0;

$("#editMode").addClass("news-button-active");
$("#category").val("<?php if(isset($news)){ echo $news->category; }else{ echo "others"; } ?>");


$("#saveEditButton").on('click', function(event){
// function saveEdit(id){
  console.log($('#news-create-form').serialize());
  if($("#membersOnly").val() == "on"){
    $("#membersOnly").val(true);
    console.log($("#membersOnly").val());
  }else{
    $("#membersOnly").val(false);
    console.log($("#membersOnly").val());
  }
  $.ajax({
      url: 'news/edit/' + "<?php echo isset($news->id) ? $news->id : ''; ?>",
      type: 'post',
      data: $('#news-create-form').serialize(),
      success: function(serverResponse){

        console.log(serverResponse);
        var jsonResponse = JSON.parse(serverResponse);
        // $("#message").innerHTML = jsonResponse.success;
        if(jsonResponse.success){
          window.location.href = jsonResponse.url;
        }else{
           $("#message").innerHTML = "Error occurred, please try again later.";
        }
      }
    });
  //}
 });

$("#news-create-form").on('submit', function(event){

  console.log($('#news-create-form').serialize());
  console.log($("#membersOnly").val());

  if($("#membersOnly").val() == "on"){
    $("#membersOnly").val(true);
    console.log($("#membersOnly").val());
  }else{
    $("#membersOnly").val(false);
    console.log($("#membersOnly").val());
  }
  $.ajax({
      url: 'news/create',
      type: 'post',
      data: $('#news-create-form').serialize(),
      success: function(serverResponse){
        console.log(serverResponse);
        var jsonResponse = JSON.parse(serverResponse);
        // $("#message").innerHTML = jsonResponse.success;
        if(jsonResponse.success){
          window.location.href = jsonResponse.url;
        }else{
           $("#message").innerHTML = "Error occurred, please try again later.";
        }
      }
    });
    event.preventDefault();
});

  $("#viewMode").click(function(){
    event.preventDefault();
    $("#viewMode").addClass("news-button-active");
    $("#editMode").removeClass("news-button-active");
    $("#content").css("display", "none");
    $("#divTextArea").css("display", "block");
    convertToView();
    //$("#divTextArea").html($("#content").val());
  });

  $("#editMode").click(function(){
    event.preventDefault();
    $("#editMode").addClass("news-button-active");
    $("#viewMode").removeClass("news-button-active");
    $("#divTextArea").css("display", "none");
    $("#content").css("display", "block");
    // $("#content").val($("#divTextArea").html());
    convertToEditMode();
  });


  var convertNextLineToTags = function(text){
    var splitNewLine = text.split("\n");
    return splitNewLine.join("<br>");
  }

  var applicableMarkups = ['b','i','u'];
  var convertBracketsToTags = function(text){
    var newText = text;
    for(var i = 0; i < applicableMarkups.length; i++){
      newText = newText.replace(new RegExp("\\[(\\s)*" + applicableMarkups[i] + "(\\s)*\\]", 'g'), "<" + applicableMarkups[i] + ">");
      newText = newText.replace(new RegExp("\\[\\/(\\s)*" + applicableMarkups[i] + "(\\s)*\\]", 'g'), "</" + applicableMarkups[i] + ">");
      console.log(i, newText);
    }
    return newText;
  }

  var convertToView = function(){
    var value = content.value;
    var nextLineConverted = convertNextLineToTags(value);
    var tagsConverted = convertBracketsToTags(nextLineConverted);
    console.log(tagsConverted);
    divTextArea.innerHTML = tagsConverted;
  }


  // next 3 functions for converting to editMode
  var convertBreakTagsToNewLine = function(text){
    return text.replace(/<br>/g, "\n");
  }

  var convertTagsToBrackets = function(text){
    var newText = text;
    for(var i = 0; i < applicableMarkups.length; i++){
      newText = newText.replace(new RegExp("<(\\s)*" + applicableMarkups[i] + "(\\s)*>", 'g'), "[" + applicableMarkups[i] + "]");
      newText = newText.replace(new RegExp("<\\/(\\s)*" + applicableMarkups[i] + "(\\s)*>", 'g'), "[/" + applicableMarkups[i] + "]");
      console.log(i, newText);
    }
    return newText;
  }

  var convertToEditMode = function(){
    var value = divTextArea.innerHTML;
    var breakTagsConvertedToNewLines = convertBreakTagsToNewLine(value);
    var tagsConvertedToBrackets = convertTagsToBrackets(breakTagsConvertedToNewLines);
    content.value = tagsConvertedToBrackets;
  }
  var tab = function(e){
              //e.stopImmediatePropogation();
              var value = content.value;
              var prevCursor = getCursorPosition();
              content.value = value.substring(0, getCursorPosition()) + '\t' + value.substring(prevCursor);
              //content.focus();
              setCursorPosition(prevCursor + 1);
              return '\t';
            }
  var newLine = function(e){ return '\n';}
  var backSpace = function(e){
                    var value = content.value;
                    content.value = value.substring(0, getCursorPosition()) + value.substring(getCursorPosition() + 1) ;
                    return '';
                  }

  var keyConversions = {
    'Tab' : tab,
    'Enter': newLine,
    //'Backspace': backSpace,
    '9': tab,
    '13': newLine
    //'8': backSpace
  }

  //var escapedKeys = [9, 13, 8];

  content.addEventListener('keydown', function(e){
    var which = e.keyCode || e.charCode;
    if(which){
      if(keyConversions.hasOwnProperty(which)){
        if(which == 9)
          e.preventDefault();
        keyConversions[e.keyCode](e);
        console.log(keyConversions[e.keyCode]);
        console.log("e.keyCode: ", e.keyCode, " | e.charCode: ", e.charCode);
      }
    }else if (e.key){
      if(keyConversions.hasOwnProperty(e.key)){
        keyConversions[e.key]();
      }
    }
    // if(e.which != 8){
    //   cursor++;
    // }else{
    //   cursor--;
    // }
    console.log(e.keyCode, cursor);
    return false;
  });



  // arg: key - the argument passed to callback function associated with keydown event.
  var insertEscapedChars = function(key){
    //if(key.key)
  }
  var moveCursor = function(key){

    if(!(e.keyCode <= 37 && e.keyCode >=40)){

    }else{
      switch(e.keyCode){
        case 37:
          cursor -= (cursor > 0) ? 1 : 0;
          break;
        case 39:
          cursor += cursor < content.value.length ? 1 : 0;
          break;
        //case
      }
    }
  }

  var setCursorPosition = function(pos){
    content.focus();
    //console.log(content.selectionStart);
    if(content.selectionStart || content.selectionStart >= 0){
      //console.log("here");
      content.selectionStart = pos;
      content.selectionEnd = pos;
      console.log(content.selectionStart);
    }else if(content.createTextRange){
      console.log("in range");
      var range = content.createTextRange();
      range.collapse(true);
      range.moveEnd('', pos);
      range.moveStart('', pos);
      range.select();
    }else{
      console.log("no browser support");
    }
  }

  var getCursorPosition = function(){

    if(content.selectionStart || content.selectionStart >= 0){
      // most modern browsers
      console.log("content.selectionStart: ", content.selectionStart);
      return content.selectionStart;
    } else if(document.selection){
      // old browsers like IE8 and IE9, etc.
      content.focus();
      var range = document.selection.createRange();
      range.moveStart('a', -content.value.length);
      console.log("document.selection: ", range.text.length);
      return range.text.length;
    }else{
      console.log("no browser support");
    }
  }

  $("#bold").on('click', function(event){
      event.preventDefault();
      insertMarkup(content, "b", getCursorPosition());
    });

    $("#italic").on('click', function(event){
      event.preventDefault();
      insertMarkup(content, "i", getCursorPosition());
    });

    $("#underline").on('click', function(event){
      event.preventDefault();
      insertMarkup(content, "u", getCursorPosition());
    });

      function insertMarkup(textarea, markup, cursorPosition){
          //var cursorPos = getCursorPosition();
          if(insideMarkup(textarea, markup, cursorPosition)){
            textarea.focus();
            setCursorPosition(cursorPosition);
            return cursorPosition;
          }
          var newCursorPosition = cursorPosition + 3;
          var value = textarea.value;
          textWithMarkup = value.substring(0, cursorPosition) + "[" + markup + "][/" + markup + "]" + value.substring(cursorPosition);
          textarea.value = textWithMarkup;
              //$("#content").val(value);
              //var textarea = document.getElementById("content");
          textarea.focus();
          // textarea.selectionEnd= newCursorPosition;
          // textarea.selectionStart = newCursorPosition;
          console.log(newCursorPosition);
          setCursorPosition(newCursorPosition);
          //cursor = newCursorPosition;
          return newCursorPosition;
      }

      function insideMarkup(textarea, markup, cursorPosition){
        var startTag = "\\[(\\s)*" + markup + "(\\s)*\\]";
        var endTag = "\\[\\/(\\s)*" + markup + "(\\s)*\\]";
        var text = textarea.value;
        var afterCursorSubstring = textarea.value.substring(cursorPosition);
        var beforeCursorSubstring = textarea.value.substring(0, cursorPosition);

        var forwardEndTagLocation = afterCursorSubstring.search(new RegExp(endTag)), forwardStartTagLocation = afterCursorSubstring.search(new RegExp(startTag));
        //var lastIndex = endTag.exec(beforeCursorSubstring);
        beforeCursorSubstring= beforeCursorSubstring.split('').reverse().join('');
        console.log(beforeCursorSubstring);
        var revEndTag = "\\](\\s)*" + markup + "(\\s)*\\/\\[";
        var revStartTag = "\\](\\s)*" + markup + "(\\s)*\\[";
        var previousEndTagLocation = beforeCursorSubstring.search(new RegExp(revEndTag)), previousStartTagLocation = beforeCursorSubstring.search(new RegExp(revStartTag));

          // either there's a a start tag that's after the next end tag, or there's an end tag and no start tags
        var appropriateEndTagLocation = ((forwardEndTagLocation < forwardStartTagLocation  && forwardStartTagLocation != -1)
                                          || (forwardEndTagLocation >= 0 && forwardStartTagLocation == -1))
                                          && forwardEndTagLocation >=0;

        var appropriateStartTagLocation = ((previousStartTagLocation > previousEndTagLocation && previousEndTagLocation != -1)
                                          || (previousStartTagLocation >= 0 && previousEndTagLocation == -1))
                                          && previousStartTagLocation >=0;

        if(appropriateEndTagLocation && appropriateStartTagLocation){
          console.log("INSIDE");
          return true;
        }else{
          console.log("OUTSIDE");
          return false;
        }
      }
//   var cursor = 0;
//   var divTextareaCursor = 0;
//   var appliedMarkups= [];
//   var newsTextarea = document.getElementById("content");
//   var divTextarea = document.getElementById("divTextarea");
//   function insertMarkup(textarea, markup, cursorPosition){
//     if(insideMarkup(textarea, markup, cursorPosition)){
//       return cursorPosition;
//     }
//     var newCursorPosition = cursorPosition + 3;
//     var value = textarea.value;
//     textWithMarkup = value.substring(0, cursorPosition) + "<" + markup + "></" + markup + ">" + value.substring(cursorPosition);
//     textarea.value = textWithMarkup;
//     //$("#content").val(value);
//     //var textarea = document.getElementById("content");
//     textarea.focus();
//     textarea.selectionEnd= newCursorPosition;
//     textarea.selectionStart = newCursorPosition;
//     cursor = newCursorPosition;
//     return newCursorPosition;
//   }
//
//   function insideMarkup(textarea, markup, cursorPosition){
//     var startTag = "<" + markup + ">";
//     var endTag = "</" + markup + ">";
//     var text = textarea.value;
//     var afterCursorSubstring = textarea.value.substring(cursorPosition);
//     var beforeCursorSubstring = textarea.value.substring(0, cursorPosition);
//
//     var forwardEndTagLocation = afterCursorSubstring.indexOf(endTag), forwardStartTagLocation = afterCursorSubstring.indexOf(startTag);
//     var previousEndTagLocation = beforeCursorSubstring.lastIndexOf(endTag), previousStartTagLocation = beforeCursorSubstring.lastIndexOf(startTag);
//
//     // either there's a a start tag that's after the next end tag, or there's an end tag and no start tags
//     var appropriateEndTagLocation = ((forwardEndTagLocation < forwardStartTagLocation  && forwardStartTagLocation != -1)
//                                     || (forwardEndTagLocation >= 0 && forwardStartTagLocation == -1))
//                                     && forwardEndTagLocation >=0;
//
//     var appropriateStartTagLocation = ((previousStartTagLocation > previousEndTagLocation && previousEndTagLocation != -1)
//                                     || (previousStartTagLocation >= 0 && previousEndTagLocation == -1))
//                                     && previousStartTagLocation >=0;
//
//     if(appropriateEndTagLocation && appropriateStartTagLocation){
//       return true;
//     }else{
//       return false;
//     }
//   }
//
//   function insertAllAppliedMarkups(textarea, cursorPosition){
//     for(var i = 0; i < appliedMarkups.length; i++){
//       cursorPosition = insertMarkup(textarea, appliedMarkups[i], cursorPosition);
//     }
//     return cursorPosition;
//   }
//
// // not finished
//   function deleteEmptyMarkup(textarea, markup, cursorPosition){
//     // delete tag that has no nested tag
//     var text = textarea.value;
//     textarea.value = text.replace(new RegExp("<" + markup + ">[\\s]*" + "</" + markup + ">", "g"), "");
//
//     // delete tag that has nested tag
//     // var startTags = getStartTags();
//     // var endTags = getEndTags();
//     // var beforeCursor = text.substring(0, cursorPosition);
//     // var afterCursor = text.substring(cursorPosition);
//     //
//     // var tagSubstring = text.substring(cursorPointer - startTags.length)
//   }
//
//   function getStartTags(){
//     var startTags = "";
//     for(var i = 0; i < appliedMarkups.length; i++){
//       startTags += "<" + appliedMarkups[i] + ">";
//     }
//     return startTags;
//   }
//
//   function getEndTags(){
//     var endTags = "";
//     appliedMarkups.reverse();
//     for(var i = 0; i < appliedMarkups.length; i++){
//       endTags += "</" + appliedMarkups[i] + ">";
//     }
//     appliedMarkups.reverse();
//     return endTags;
//   }
//
//   function insideCurrentMarkup(textarea, cursorPosition){
//
//     console.log("cursorPointer at insideCurrentMarkup: ", cursorPosition);
//     for(var i = 0; i < appliedMarkups.length; i++){
//           if(!insideMarkup(textarea, appliedMarkups[i], cursorPosition)){
//             console.log(false);
//             return false;
//           }
//     }
//     console.log(true);
//     return true;
//   }
//
//   function removeMarkup(markup){
//     var indexMarkup = appliedMarkups.indexOf(markup);
//     if(indexMarkup >=0)
//       appliedMarkups.splice(indexMarkup, 1);
//   }
//
//   function applyMarkup(markup){
//     var indexMarkup = appliedMarkups.indexOf(markup);
//     if(indexMarkup < 0)
//       appliedMarkups.push(markup);
//   }
//
//   // if some markups are applied, move to the applied markups if the cursor is not in them.
//   //NOTE: cursors is always moved into a tag or nested tags of the applied markups, if the cursor is taken away from it.
//   // only way for cursors to leave a tag is by disabling that tag.
//   function moveCursorToAppliedMarkups(textArea, cursorPosition){
//     console.log("inside moveCursor", textArea.selectionEnd, textArea.selectionStart, textArea.value);
//     if(!insideCurrentMarkup(textArea, cursorPosition) && appliedMarkups.length > 0){
//
//         var text = textArea.value;
//         var markupStartTags = "", markupEndTags = "";
//         for(var i = 0; i < appliedMarkups.length; i++){
//           markupStartTags += "<" + appliedMarkups[i] + ">";
//         }
//         appliedMarkups.reverse();
//         for(var i = 0; i < appliedMarkups.length; i++){
//           markupEndTags += "</" + appliedMarkups[i] + ">";
//         }
//         appliedMarkups.reverse();
//
//         // check if cursor is before the tags, if it is, move it to after that tag.
//         var substringStartingAtCursor = text.substring(cursorPosition);
//         //console.log("substringStartingAtCursor: ", substringStartingAtCursor);
//         var indexClosestStartTags = substringStartingAtCursor.indexOf(markupStartTags);
//         console.log("substringStartingAtCursor: ", substringStartingAtCursor, ", index: ", indexClosestStartTags);
//         if(cursorPosition <= indexClosestStartTags && false){
//           textArea.selectionEnd = cursorPosition + (cursorPosition - indexClosestStartTags) + markupStartTags.length;
//           //textArea.selectionStart = cursorPosition + (cursorPosition - indexClosestStartTags) + markupStartTags.length;
//           console.log("new position: ", textArea.selectionEnd);
//           return;
//         }
//
//         var substringUntilCursor = text.substring(0, cursorPosition);
//         var indexClosestEndTags = substringUntilCursor.lastIndexOf(markupEndTags);
//         if(cursorPosition > indexClosestEndTags){
//           textArea.selectionEnd = indexClosestEndTags;
//           cursor = indexClosestEndTags;
//           //textArea.selectionStart = indexClosestEndTags;
//           console.log(textArea.selectionEnd);
//           return;
//         }
//
//
//
//         // check if cursor is after the end tag, if it is, move it before the end tag.
//         var indexEndTags = text.indexOf(markupEndTags);
//
//     }else{
//       console.log("applied markup length is 0 or is inside applied markups");
//     }
//   }
//
//   //should first remove the markup from appliedMarkups array for this to work.
//   function moveCursorOutsideMarkup(textarea, markup, cursorPosition){
//     var substringStartingAtCursor = textarea.value.substring(cursorPosition);
//     var markupEndTag = "</" + markup + ">";
//     var markupStartTag = "<" + markup + ">";
//     var markupStartTagIndex = substringStartingAtCursor.indexOf(markupStartTag);
//     var markupEndTagIndex = substringStartingAtCursor.indexOf(markupEndTag);
//     if((markupEndTagIndex >=0 && markupStartTagIndex < 0) || (markupEndTagIndex < markupStartTagIndex && markupStartTagIndex >=0)){
//       while(markupEndTagIndex >= 0){
//         cursorPosition = moveCursor(textarea, cursorPosition, markupEndTagIndex + markupEndTag.length - cursorPosition);
//         substringStartingAtCursor = substringStartingAtCursor.substring(cursorPosition);
//         markupEndTagIndex = substringStartingAtCursor.indexOf(markupEndTag);
//         console.log("end index", markupEndTagIndex);
//       }
//       if(!insideCurrentMarkup(textarea, cursorPosition)){
//         insertAllAppliedMarkups(textarea, cursorPosition);
//       }
//     }
//   }
//
//   function moveCursor(textarea, cursorPosition, offset){
//     textarea.selectionEnd = cursorPosition + offset - cursorPosition;
//     cursor = cursorPosition + offset - cursorPosition;
//     return textarea.selectionEnd;
//   }
//
//   function removeConsecutiveTags(textarea, appliedMarkups, cursorPosition){
//     var text = textarea.value;
//     //var markupArray = appliedMarkups;
//     var startTags = "<" + appliedMarkups.join("><") + ">"; // example: <i><b><u>
//     var endTags = "</" + appliedMarkups.reverse().join("></") + ">";// example: </u></b></i>
//
//     appliedMarkups.reverse();
//     console.log(startTags);
//     console.log(endTags);
//     var cursorMove = 0;
//     var tagsWithSpaceInBetweenRegex = new RegExp(endTags + "[\\s]*"+ startTags, "g");
//     console.log("regex: " + tagsWithSpaceInBetweenRegex);
//     //if(tagsWithSpaceInBetweenRegex.test(text)){
//       //var matchingStringArray = text.match(tagsWithSpaceInBetweenRegex);
//     console.log("before: ", textarea.value, ", cursor: ", textarea.selectionEnd, textarea.selectionStart);
//     var noConsecutives = text.replace(tagsWithSpaceInBetweenRegex, "");
//       //cursorMove += endTags
//     //}
//     console.log("no consec: ", noConsecutives);
//
//     //!!!@@!@!@!@@!!@CONTINUE HERE, MUST REPLACE THINGS LIKE </i><i> single consecutive end tags.
//     for(var i = 0; i< appliedMarkups.length; i++){
//       var nextRegex = new RegExp("(</" + appliedMarkups[i] + ">[\\s]*<" + appliedMarkups[i] + ">)|(</" + appliedMarkups[i] + "><"+ appliedMarkups[i] + ">)", "g");
//       if(nextRegex.test(noConsecutives)){
//         noConsecutives = noConsecutives.replace(nextRegex, "");
//         console.log("noConsec after ", nextRegex , " removed: ", noConsecutives);
//         cursorMove += appliedMarkups.length;
//         // textarea.selectionEnd -=
//         //console.log("nextRegex true: ", nextRegex);
//         i = (i>0) ? 0 : i;
//       }
//       console.log("nextRegex false: ", nextRegex, " | noConsecutives: ", noConsecutives);
//     }
//     // textarea.selectionEnd = cursorPointer -
//     console.log("no consec2: ", noConsecutives);
//     console.log("markup array: ", appliedMarkups);
//     console.log("cursor pos in removeConsecutiveTags", textarea.selectionEnd, textarea.selectionStart);
//     textarea.value = noConsecutives;
//     moveCursorToAppliedMarkups(textarea, textarea.selectionEnd);
//   }


//   $("#editMode").addClass("news-button-active");
//   $("#category").val("<?php ///if(isset($news)){ echo $news->category; }else{ echo "others"; } ?>");
//
//
//   $("#testButton").on('click', function(event){
//       //$("#divTextArea").html(window.getSelection().toString());
//       //$("#content").val($("#divTextArea").html() + " " + $('#content').prop("selectionStart"));
//       $("#divTextArea").html($("#divTextArea").html());
//
//       // var el = document.getElementById("divTextArea");
//       // var range = document.createRange();
//       // var sel = window.getSelection();
//       // range.setStart(el.childNodes[2], 5);
//       // range.collapse(true);
//       // sel.removeAllRanges();
//       // sel.addRange(range);
//   });
//
//   // $("#divTextArea").on('input', function(e){
//   //     console.log("next word: ", e.originalEvent.data);
//   // });
//
//   function insertIntoTextarea(char){
//     newsTextarea.value = newsTextarea.value.substring(0, cursor) + char + newsTextarea.value.substring(cursor);
//     cursor+= char.length;
//   }
//
//   function deleteOne(){
//     var beforeCursor = newsTextarea.value.substring(0, cursor);
//     if(beforeCursor.lastIndexOf("<br>") == cursor - 4){
//       newsTextarea.value = beforeCursor.substring(0, cursor - 4) + newsTextarea.value.substring(cursor);
//       cursor-=4;
//     }else{
//       newsTextarea.value = beforeCursor.substring(0, beforeCursor.length-1) + newsTextarea.value.substring(cursor);
//       cursor--;
//     }
//   }
//
//   function moveCursorByArrowKey(key){
//     switch(key){
//       case 37:
//         cursor--;
//         break;
//       case 39:
//         cursor++;
//         break;
//     }
//   }
//
//   // var keyboard = { 8 :     }
//   divTextArea.addEventListener('keydown', function(e){
//     console.log(e.which);
//     //console.log('next word: ', e.data);
//     switch(e.which){
//       case 13:
//         insertIntoTextarea("<br>");
//         break;
//       case 8:
//         deleteOne();
//         break;
//       case 37:
//       case 38:
//       case 39:
//       case 40:
//         moveCursorByArrowKey(e.which);
//         break;
//       default:
//         insertIntoTextarea(e.key);
//     }
//     // if(e.which == 13){
//     //   insertIntoTextarea("<br>");
//     // }else{
//     //   insertIntoTextarea(e.data);
//     // }
//     //divTextArea.innerHTML = "";
//     divTextareaCursor++;
//     //divTextArea.innerHTML = newsTextarea.value;
//     //divTextArea.selectionEnd = divTextareaCursor;
//   })
//
//   //newsTextarea
//
//   // $("#content").on('focus', function(){
//     $("#content").on('click', function(){
//       var cursorPosition = $('#content').prop("selectionStart");
//       if(!insideCurrentMarkup(newsTextarea,cursorPosition) && appliedMarkups.length > 0){
//         var cursorPosition = $('#content').prop("selectionStart");
//         for(var i = 0; i < appliedMarkups.length; i++){
//           // only add new tag if it's not already inside the same tag.
//           if(!insideMarkup(newsTextarea, appliedMarkups[i], cursorPosition)){
//             insertMarkup(newsTextarea,appliedMarkups[i], cursorPosition);
//             cursorPosition = cursorPosition + ("<" + appliedMarkups[i] + ">").length;
//           }
//
//           console.log("here");
//         }
//
//         removeConsecutiveTags(newsTextarea, appliedMarkups, cursorPosition);
//
//       }
//       //if()
//       //console.log(insideMarkup(newsTextarea, "b", cursorPosition));
//       //$("#message").val($("#message").val() + " " + insideMarkup(newsTextarea, "b", cursorPosition));
//     });
//   // });
//
//   $("#viewMode").click(function(){
//     event.preventDefault();
//     $("#viewMode").addClass("news-button-active");
//     $("#editMode").removeClass("news-button-active");
//     $("#content").css("display", "none");
//     $("#divTextArea").css("display", "block");
//     $("#divTextArea").html($("#content").val());
//   });
//
//   $("#editMode").click(function(){
//     event.preventDefault();
//     $("#editMode").addClass("news-button-active");
//     $("#viewMode").removeClass("news-button-active");
//     $("#divTextArea").css("display", "none");
//     $("#content").css("display", "block");
//     $("#content").val($("#divTextArea").html());
//   });
//
//   //listens for input (only input is in textarea div)
//   // $(document).on('keydown', function(event){
//   //     $("#divTextArea").html(window.getSelection().toString());
//   // });
//
//   // $("#divTextArea").on('focusout', function(event){
//   //   $("#divTextArea").html($("#divTextArea").html() + "</b>";
//   //
//   // });
//
//
//
//   // $(document).on('keyup', function(event){
//   //   $("#divTextArea").html($("#divTextArea").html() + "</b>");
//   //   $("#content").html($("#divTextArea").val());
//   // });
//
// var textarea = document.getElementById("content");
//   $("#bold").on('click', function(event){
//     event.preventDefault();
//     if($("#bold").hasClass("news-button-active")){
//       $("#bold").removeClass("news-button-active");
//       textarea.focus();
//       //textarea.selectionEnd= newCursorPosition;
//       removeMarkup("b");
//       deleteEmptyMarkup(newsTextarea, "b", newsTextarea.selectionEnd);
//       moveCursorOutsideMarkup(newsTextarea, "b", newsTextarea.selectionEnd);
//       // var indexMarkup = appliedMarkups.indexOf("b")
//       // if(indexMarkup >=0)
//       //   appliedMarkups.splice(indexMarkup, 1);
//     }else{
//       textarea.focus();
//       $("#bold").addClass("news-button-active");
//       $("#divTextArea").html($("#divTextArea").html() + "<b>");
//       var cursorPosition = $('#content').prop("selectionStart");
//       insertMarkup(newsTextarea, "b", cursorPosition);
//       applyMarkup("b");
//       // appliedMarkups.push("b");
//       // var newCursorPosition = cursorPosition + 3;
//       // var value = $("#content").val();
//       // value = value.substring(0, cursorPosition) + "<b></b>";
//       // $("#content").val(value);
//       // var textarea = document.getElementById("content");
//       // textarea.focus();
//       // textarea.selectionEnd= newCursorPosition;
//       //$("#content").caretTo(newCursorPosition);
//
//       // if (this.setSelectionRange) {
//       //       this.focus();
//       //       this.setSelectionRange(start, end);
//       //   } else if (this.createTextRange) {
//       //       var range = this.createTextRange();
//       //       range.collapse(true);
//       //       range.moveEnd('character', end);
//       //       range.moveStart('character', start);
//       //       range.select();
//       //   }
//       //$('#content').prop("selectionStart") = newCursorPosition;
//     }
//     console.log(appliedMarkups);
//     return false;
//
//     //$("#divTextArea").html(document.activeElement);
//   });
//
//   // $("#divTextArea").on('focusout', function(event){
//   //   $("#divTextArea").html($("#divTextArea").html() + "</b>";
//   // $("#message").html($("#content").val());
//   //
//   // });
//
//   $("#italic").on('click', function(event){
//     event.preventDefault();
//     if($("#italic").hasClass("news-button-active")){
//       textarea.focus();
//       $("#italic").removeClass("news-button-active");
//       removeMarkup("i");
//       moveCursorOutsideMarkup(newsTextarea, "i", newsTextarea.selectionEnd);
//     }else{
//       textarea.focus();
//       $("#italic").addClass("news-button-active");
//       var cursorPosition = $('#content').prop("selectionStart");
//       insertMarkup(newsTextarea, "i", cursorPosition);
//       applyMarkup("i");
//     }
//     console.log(appliedMarkups);
//     return false;
//   });
//
//   $("#underline").on('click', function(event){
//     event.preventDefault();
//     if($("#underline").hasClass("news-button-active")){
//       textarea.focus();
//       $("#underline").removeClass("news-button-active");
//       removeMarkup("u");
//       moveCursorOutsideMarkup(newsTextarea, "u", newsTextarea.selectionEnd);
//     }else{
//       textarea.focus();
//       $("#underline").addClass("news-button-active");
//       var cursorPosition = $('#content').prop("selectionStart");
//       insertMarkup(newsTextarea, "u", cursorPosition);
//       applyMarkup("u");
//     }
//     console.log(appliedMarkups);
//     return false;
//   });
//
//   $("#saveEditButton").on('click', function(event){
//   // function saveEdit(id){
//     console.log($('#news-create-form').serialize());
//     if($("#membersOnly").val() == "on"){
//       $("#membersOnly").val(true);
//       console.log($("#membersOnly").val());
//     }else{
//       $("#membersOnly").val(false);
//       console.log($("#membersOnly").val());
//     }
//     $.ajax({
//         url: 'news/edit/' + "<!--<?php //echo isset($news->id) ? $news->id : ''; ?>-->",
//         type: 'post',
//         data: $('#news-create-form').serialize(),
//         success: function(serverResponse){
//
//           console.log(serverResponse);
//           var jsonResponse = JSON.parse(serverResponse);
//           // $("#message").innerHTML = jsonResponse.success;
//           if(jsonResponse.success){
//             window.location.href = jsonResponse.url;
//           }else{
//              $("#message").innerHTML = "Error occurred, please try again later.";
//           }
//         }
//       });
//     //}
//    });
//
//   $("#news-create-form").on('submit', function(event){
//
//     console.log($('#news-create-form').serialize());
//     console.log($("#membersOnly").val());
//
//     if($("#membersOnly").val() == "on"){
//       $("#membersOnly").val(true);
//       console.log($("#membersOnly").val());
//     }else{
//       $("#membersOnly").val(false);
//       console.log($("#membersOnly").val());
//     }
//     $.ajax({
//         url: 'news/create',
//         type: 'post',
//         data: $('#news-create-form').serialize(),
//         success: function(serverResponse){
//           console.log(serverResponse);
//           var jsonResponse = JSON.parse(serverResponse);
//           // $("#message").innerHTML = jsonResponse.success;
//           if(jsonResponse.success){
//             window.location.href = jsonResponse.url;
//           }else{
//              $("#message").innerHTML = "Error occurred, please try again later.";
//           }
//         }
//       });
//       event.preventDefault();
//   });
//
</script>
