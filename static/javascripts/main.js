$(function(){

$(document).foundationTabs();

$('.tags').textext({ plugins: 'tags' });

var api_base = 'http://dw2012.xiao-jia.com/dw2012';

function get_cookie(c_name){
  var i, x, y, ARRcookies = document.cookie.split(";");
  for (i = 0; i < ARRcookies.length; i++) {
    x = ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
    y = ARRcookies[i].substr(ARRcookies[i].indexOf("=") + 1);
    x = x.replace(/^\s+|\s+$/g, "");
    if (x == c_name) {
      return unescape(y);
    }
  }
  return null;
}

function set_cookie(c_name, value, exdays){
  var c_value, exdate = new Date();
  exdate.setDate(exdate.getDate() + exdays);
  c_value = escape(value) + ((exdays == null) ? "" : "; expires=" + exdate.toUTCString());
  document.cookie = c_name + "=" + c_value;
}

var save_link_handler = {
  link: function(data){
    $('.hidden.row').hide();
    $('.type-link.row').show();
    $('.type-link form input[name="title"]').val(data.title);
    $('.type-link .th img').attr('src', data.snapshot_url);
  },
  project: function(data){
    $('.hidden.row').hide();
    $('.type-project.row').show();
    $('.type-project form textarea[name="description"]').text(data.description);
    $('.type-project form input[name="homepage"]').val(data.homepage);
  },
  question: function(data){
    $('.hidden.row').hide();
    $('.type-question.row').show();
    $('.type-question form input[name="title"]').val(data.title);
    $('.type-question form input[name="summary"]').text(data.summary);
    // TODO append replies
    $('.type-question form .tags').textext()[0].tags().addTags(data.tags || []);
  },
  paper: function(data){
    $('.hidden.row').hide();
    $('.type-paper.row').show();
    $('.type-paper form input[name="title"]').val(data.title);
    $('.type-paper form input[name="authors"]').val(data.authors);
    $('.type-paper form input[name="year"]').val(data.year);
    $('.type-paper form input[name="conference"]').val(data.conference);
    $('.type-paper form input[name="description"]').text(data.description);
  }
};

function spin_lock(selector){
  $(selector).mask('Loading...');
}

function spin_unlock(selector){
  $(selector).unmask();
}

function report_error(data){
  if (data.reason) {
    $('.hidden.alert-box.alert .message').text(data.reason);
    $('.hidden.alert-box.alert').show();
  } else {
    console.log(data);
    window.alert('unknown error');
  }
  // FIXME debug:
  set_cookie('user_id', '505dbaa454b1b75093000000', 777);
  set_cookie('token', 'e33199b237f993775ddde3fe51d43844', 777);
}

$('#btn-save-link').click(function(){
  var link_url = $('#link-url').val();
  if (link_url.length <= 3) return;
  spin_lock('#addLinkTab');
  $.get(api_base + '/parse/url', { url: link_url }, function(data){
    var handler;
    if (data.status == 'success') {
      $('#addLinkTab .row.collapse .one').hide();
      $('#addLinkTab .row.collapse .nine').removeClass('nine').addClass('ten');
      $('#addLinkTab .row.collapse input').prop('disabled', true);
      handler = save_link_handler[data.type];
      handler(data);
    } else {
      report_error(data);
    }
    spin_unlock('#addLinkTab');
  }, 'json');
});

$('#addLinkTab .hidden.row form').submit(function(){
  var data = $(this).serializeObject();
  data.url = $('#link-url').val();
  spin_lock('#addLinkTab');
  $.post(api_base + '/notes', {
    user_id: get_cookie('user_id'),
    token: get_cookie('token'),
    data: data
  }, function(data){
    if (data.status == 'success') {
      $('.hidden.row').hide();
      $('#addLinkTab .row.collapse .one').show();
      $('#addLinkTab .row.collapse .ten').removeClass('ten').addClass('nine');
      $('#addLinkTab .row.collapse input').prop('disabled', false);
      $('#link-url').val('');
      $('.hidden.alert-box.success .message').text('Note created successfully.');
      $('.hidden.alert-box.success').show();
    } else {
      report_error(data);
    }
    spin_unlock('#addLinkTab');
  }, 'json');
  return false;
});

});