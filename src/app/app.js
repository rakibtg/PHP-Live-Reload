let lastTimeStamp = 0

function docReady(fn) {
  if (document.readyState === "complete" || document.readyState === "interactive") {
    setTimeout(fn, 1);
  } else {
    document.addEventListener("DOMContentLoaded", fn);
  }
}

function handleNewLink () {
  const floating_nav = document.querySelector('#floating_nav')
  if (floating_nav) {
    floating_nav.addEventListener('mouseenter', () => {
      floating_nav.classList.remove('nav_closed')
    })
    floating_nav.addEventListener('mouseleave', () => {
      floating_nav.classList.add('nav_closed')
    })
  }
}

docReady(function () {
  handleNewLink()
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
            document.title = 'Reloading...'
            setTimeout(() => {
              document.title = 'PHP Live Reloader'
            }, 500)
            _iframe.src = _iframe.src
            lastTimeStamp = timestamp
          }
        }
      })
  }, _interval * 1000)
});