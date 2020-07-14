let lastTimeStamp = 0

function docReady(fn) {
  if (document.readyState === "complete" || document.readyState === "interactive") {
    setTimeout(fn, 1);
  } else {
    document.addEventListener("DOMContentLoaded", fn);
  }
}

docReady(function () {
  const _iframe = document.querySelector('iframe')
  const _path = _iframe.getAttribute('path')
  const _url = _iframe.getAttribute('url')
  const _api = _iframe.getAttribute('api')
  const _interval = _iframe.getAttribute('interval')
  const endpoint = _api + "?url="+_url+"&path="+_path
  setInterval(() => {
    fetch(endpoint)
      .then(res => res.text())
      .then(timestamp => {
        if (!lastTimeStamp) {
          lastTimeStamp = timestamp
        } else {
          if (lastTimeStamp != timestamp) {
            console.log('reload')
            _iframe.src = _iframe.src
            lastTimeStamp = timestamp
          }
        }
      })
  }, _interval * 1000);
  console.log({_path, _url, _api, _interval})
});