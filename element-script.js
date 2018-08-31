

jQuery(document).ready(function ($) {

  $('#loadText').click(function (e) {
    e.preventDefault();
    var title = $('#title').val();
    var content = $('#content').val();
    var text = title + '\n' + content;
    var spacedText = addLines(text);
    var field = $(getField('#chart', 'textarea'));
    field.val(spacedText);
    
  });
  $('#hyphenate').click(function (e) {
    e.preventDefault();
    var hyphenate = createHyphenator(hyphenationPatternsEnUs, { debug: true, hyphenChar: '-'})
    var chart = getFieldValue('#chart', 'textarea');
    var hyphenatedText = hyphenate(chart);
    var field = $(getField('#chart', 'textarea'));
    field.val(hyphenatedText);
    
  });
  $('#chordParse').click(function (e) {
    e.preventDefault();
    var field = $(getField('#chart', 'textarea'));
    
    var chart = getFieldValue('#chart', 'textarea');
    console.log(chart);
    var chords = fromText(chart);
    console.log(chords);
    field.val(chords);
    
  });
  $('#showHTML').click(function (e) {
    e.preventDefault();
    var field = $(getField('#chart', 'textarea'));
    var chart = getFieldValue('#chart', 'textarea');
    var html = parseToHtml(chart);
    displayChart(html);
    console.log(html);
  });

  $(document).on('click', '.close', function () {
    $('.chordChartDisplay').hide();
    $('.chordChartDisplay').remove();
  }); 

  var addLines = function(text) {
    return text.replace(/\n/g, '\n\n');
  }
  var displayChart = function(html) {
    //show popup with html
    $('body').append('<div class="chordChartDisplay">' + html + '</div>');
    $('.chordChartDisplay').append('<a class="close">&times;</a>');
    $('.chordChartDisplay').show();
    

  }

  var getFieldValue = function (customID, element) {
    console.log(customID);
    var key = element + '[name="acf[' + $(customID).data('key') + ']"]';
    console.log(key);
    return $(key).val();
  }

  var getField = function (customID, element) {
    console.log(customID);
    var key = element + '[name="acf[' + $(customID).data('key') + ']"]';
    console.log(key);
    return $(key);
  }

  var parseToHtml = function(songText) {
    var songTextTitleOpen = songText.replace(/\{t:/g, '<h1>');
    var songTextTitleClose = songTextTitleOpen.replace(/\}\n/g, '</h1><p>');
    var songTextParagraphs = songTextTitleClose.replace(/\n\n/g, '</p><p>');
    var songTextBreaks = songTextParagraphs.replace(/\n/g, '<br>\n');
    var songTextChordLeft = songTextBreaks.replace(/\[/g, '<span class="chord">');
    var songTextChordRight = songTextChordLeft.replace(/\]/g, '</span>');
    return songTextChordRight;
  }

  //chordpro

  var isChordpro = function(input) {

    return hasCurlyBraces(input);
  };

  var fromText = function(input) {
    title = "";
    artist = "";
    body = "";
    output = "";

    lines = input.split("\n");

    if (lines.length >= 1) {
      output += "{t:" + lines[0].trim() + "}\n";
    }
    // if (lines.length >= 2) {
    //   output += "{st:" + lines[1].trim() + "}\n";
    // }

    chordLine = "";
    for (i = 1; i < lines.length; i++) {
      if (isAChordLine(lines[i])) {
        chordLine = lines[i].trimEnd();
      } else {
        output += mergeLine(chordLine, lines[i].trimEnd()) + "\n";
        chordLine = "";
      }
    }

    return output;
  }


function isAChord(word) {
  result = false;

  if (word.length > 0 && word[0] >= 'A' && word[0] <= 'G') {
    result = true;
  }

  return result;
}

function isAChordLine(line) {
  isChord = true;

  words = line.split(" ");

  if (words.length == 0) {
    isChord = false;
  }

  for (j = 0; j < words.length; j++) {
    if (words[j].trim().length > 0 && !isAChord(words[j])) {
      isChord = false;
    }
  }

  return isChord;
}

function mergeLine(chordLine, lyricsLine) {
  mergedLine = "";
  chordLineIndex = 0;
  if (chordLine.length == 0) {
    mergedLine = lyricsLine;
  } else {
    maxLength = lyricsLine.length;
    if (chordLine.length > maxLength) {
      maxLength = chordLine.length;
    }
    for (k = 0; k < lyricsLine.length; k++) {
      if (chordLineIndex < chordLine.length) {
        if (chordLine[chordLineIndex] != ' ') {
          mergedLine += "[";
          while (chordLineIndex < chordLine.length && chordLine[chordLineIndex] != ' ') {
            mergedLine += chordLine[chordLineIndex];
            chordLineIndex++;
          }
          mergedLine += "]";
        } else {
          chordLineIndex++;
        }
      }
      if (k < lyricsLine.length) {
        mergedLine += lyricsLine[k];
      }
    }
  }

  while (chordLineIndex < chordLine.length) {
    if (chordLine[chordLineIndex] != ' ') {
      mergedLine += "[";
      while (chordLineIndex < chordLine.length && chordLine[chordLineIndex] != ' ') {
        mergedLine += chordLine[chordLineIndex];
        chordLineIndex++;
      }
      mergedLine += "]";
    } else {
      mergedLine += " ";
      chordLineIndex++;
    }
  }

  return mergedLine;
}

function hasCurlyBraces(input) {
  result = false;

  if (input.indexOf("{") >= 0 && input.indexOf("}") >= 0) {
    result = true;
  }

}

})

