$(function(){

$(document).foundationTabs();

$('.tags').textext({ plugins: 'tags' });

var api_base = 'http://localhost/dw2012';

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

(function(){
  var prmstr = window.location.search.substr(1);
  var prmarr = prmstr.split("&");
  var params = {};

  for (var i = 0; i < prmarr.length; i++) {
    var tmparr = prmarr[i].split("=");
    params[tmparr[0]] = tmparr[1];
  }
  
  if (params.url) {
    $('#link-url').val(decodeURIComponent(params.url));
    $('#add-link-link').click();
  }
})();

var save_link_handler = {
  link: function(data){
    $('#addLinkTab .hidden.row').hide();
    $('#addLinkTab .type-link.row').show();
    $('#addLinkTab .type-link form input[name="title"]').val(data.title);
    $('#addLinkTab .type-link form input[name="snapshot_url"]').val(data.snapshot_url);
    $('#addLinkTab .type-link form textarea[name="note"]').val('');
    $('#addLinkTab .type-link form textarea[name="tags"]').val('');
    $('#addLinkTab .type-link .th img').attr('src', data.snapshot_url);
  },
  project: function(data){
    $('#addLinkTab .hidden.row').hide();
    $('#addLinkTab .type-project.row').show();
    $('#addLinkTab .type-project form textarea[name="description"]').text(data.description);
    $('#addLinkTab .type-project form input[name="homepage"]').val(data.homepage);
    $('#addLinkTab .type-project form textarea[name="note"]').val('');
    $('#addLinkTab .type-project form textarea[name="tags"]').val('');
  },
  question: function(data){
    $('#addLinkTab .hidden.row').hide();
    $('#addLinkTab .type-question.row').show();
    $('#addLinkTab .type-question form input[name="title"]').val(data.title);
    $('#addLinkTab .type-question form input[name="summary"]').text(data.summary);
    // TODO append replies
    $('#addLinkTab .type-question form .tags').textext()[0].tags().addTags(data.tags || []);
    $('#addLinkTab .type-project form textarea[name="note"]').val('');
  },
  paper: function(data){
    $('#addLinkTab .hidden.row').hide();
    $('#addLinkTab .type-paper.row').show();
    $('#addLinkTab .type-paper form input[name="title"]').val(data.title);
    $('#addLinkTab .type-paper form input[name="authors"]').val(data.authors);
    $('#addLinkTab .type-paper form input[name="year"]').val(data.year);
    $('#addLinkTab .type-paper form input[name="conference"]').val(data.conference);
    $('#addLinkTab .type-paper form input[name="description"]').text(data.description);
    $('#addLinkTab .type-project form textarea[name="note"]').val('');
    $('#addLinkTab .type-project form textarea[name="tags"]').val('');
  }
};

var save_paper_handler = {
  paper: function(data){
    $('#addPaperTab .hidden.row').hide();
    $('#addPaperTab .type-paper.row').show();
    $('#addPaperTab .type-paper form input[name="title"]').val(data.title);
    $('#addPaperTab .type-paper form input[name="authors"]').val(data.authors);
    $('#addPaperTab .type-paper form input[name="year"]').val(data.year);
    $('#addPaperTab .type-paper form input[name="conference"]').val(data.conference);
    $('#addPaperTab .type-paper form input[name="description"]').text(data.description);
  }
};

var note_template = Handlebars.compile($('#note-template').html());
var tag_template = Handlebars.compile($('#tag-template').html());

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
  if (data.tags) data.tags = eval(data.tags);
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
    window.refresh_tags();
  }, 'json');
  return false;
});

$('#btn-save-paper').click(function(){
  var paper_title = $('#paper-title').val();
  if (paper_title.length <= 3) return;
  spin_lock('#addPaperTab');
  $.get(api_base + '/parse/title', { title: paper_title }, function(data){
    var handler;
    if (data.status == 'success') {
      $('#addPaperTab .row.collapse .one').hide();
      $('#addPaperTab .row.collapse .nine').removeClass('nine').addClass('ten');
      $('#addPaperTab .row.collapse input').prop('disabled', true);
      handler = save_paper_handler[data.type];
      handler(data);
    } else {
      report_error(data);
    }
    spin_unlock('#addPaperTab');
  }, 'json');
});

$('#addPaperTab .hidden.row form').submit(function(){
  var data = $(this).serializeObject();
  if (data.tags) data.tags = eval(data.tags);
  spin_lock('#addPaperTab');
  $.post(api_base + '/notes', {
    user_id: get_cookie('user_id'),
    token: get_cookie('token'),
    data: data
  }, function(data){
    if (data.status == 'success') {
      $('.hidden.row').hide();
      $('#addPaperTab .row.collapse .one').show();
      $('#addPaperTab .row.collapse .ten').removeClass('ten').addClass('nine');
      $('#addPaperTab .row.collapse input').prop('disabled', false);
      $('#paper-title').val('');
      $('.hidden.alert-box.success .message').text('Note created successfully.');
      $('.hidden.alert-box.success').show();
    } else {
      report_error(data);
    }
    spin_unlock('#addPaperTab');
    window.refresh_tags();
  }, 'json');
  return false;
});

window.search_with = function(tags){
  window.location.hash = '#search';
  $('#search-notes-link').click();
  $('#search-tags').val(tags);
  spin_lock('#searchTab');
  $.get(api_base + '/notes', { q: tags, me: get_cookie('user_id') }, function(data){
    if (data.status == 'success') {
      $('#search-results').html(note_template(data));
    } else {
      report_error(data);
    }
    spin_unlock('#searchTab');
  }, 'json');
  return false;
};

$('#btn-search-notes').click(function(){
  var tags = $('#search-tags').val();
  window.search_with(tags);
});

$.get(api_base + '/notes/random', function(data){
  if (data.status == 'success') {
    $('#random-notes').html(note_template(data));
  } else {
    report_error(data);
  }
}, 'json');

window.refresh_tags = function(){
  $.get(api_base + '/tags', { me: get_cookie('user_id') }, function(data){
    if (data.status == 'success') {
      $('#tags-for-search').html(tag_template(data));
    } else {
      report_error(data);
    }
  }, 'json');
};
window.refresh_tags();

});