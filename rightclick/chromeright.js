var static_url = 'http://localhost/dw2012/static';
// chrome.contextMenus.create({
//   title: "Collect selection text",
//   contexts: ["selection"],
//   onclick: function(info, tab){
//     alert(info.selectionText);
//     chrome.windows.create({url: 'http://somewhere', type: 'popup', width: 800, height: 600});
//   }
// });
chrome.contextMenus.create({
  title: "Collect this link",
  contexts: ["link"],
  onclick: function(info, tab){
    chrome.windows.create({url: static_url + '/?url=' + encodeURIComponent(info.linkUrl) + '#addLink', type: 'normal', width: 1000, height: 600});
  }
});
chrome.contextMenus.create({
  title: "Collect current page",
  contexts: ["page"],
  onclick: function(info, tab){
    chrome.windows.create({url: static_url + '/?url=' + encodeURIComponent(info.pageUrl) + '#addLink', type: 'normal', width: 1000, height: 600});
  }
});