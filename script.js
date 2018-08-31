jQuery(document).ready(function ($) {
  $('#worship_date').change(function() {updateTitle();});
  $('#worship_time').change(function() { updateTitle(); });

  var getFieldValue = function(customID, element) {
    console.log(customID);
    var key = element + '[name="acf[' + $(customID).data('key') + ']"]';
    console.log(key);
    return $(key).val();
  }
  var updateTitle = function() {
    // console.log($('#worship_date'));
    var date = getFieldValue('#worship_date', 'input');
    var formattedDate = date.substring(0, 4) + '-' + date.substring(4, 6) + '-' +  date.substring(6,8);
    var title = formattedDate + "_" + getFieldValue('#worship_time', 'select');
    $('#title').val(title);
  }
})