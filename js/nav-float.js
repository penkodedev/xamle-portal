    var zxcFadePanel = {

      init: function(o) {
        var ms = o.FadeDuration,
          show = o.ShowAt,
          obj = {
            obj: document.getElementById(o.ID),
            show: typeof(show) == 'number' ? show : 0,
            ms: typeof(ms) == 'number' ? ms : 10,
            now: 0,
            to: 100
          }
        this.addevt(window, 'scroll', 'scroll', obj);
        this.scroll(obj);
      },

      scroll: function(o) {
        var top = document.body.scrollTop,
          to;
        if (window.innerHeight) {
          top = window.pageYOffset;
        } else if (document.documentElement.clientHeight) {
          top = document.documentElement.scrollTop;
        }
        to = top < o.show ? 0 : 100;
        if (to != o.to) {
          o.to = to;
          clearTimeout(o.dly);
          o.obj.style.visibility = 'visible';
          this.animate(o, o.now, to, new Date(), o.ms * Math.abs((to - o.now) / 100));
        }
      },

      animate: function(o, f, t, srt, mS) {
        var oop = this,
          ms = new Date().getTime() - srt,
          now = (t - f) / mS * ms + f;
        if (isFinite(now)) {
          o.now = now;
          o.obj.style.filter = 'alpha(opacity=' + now + ')';
          o.obj.style.opacity = o.obj.style.MozOpacity = o.obj.style.WebkitOpacity = o.obj.style.KhtmlOpacity = now / 100 - 0.1; //opacity
        }
        if (ms < mS) {
          o['dly'] = setTimeout(function() {
            oop.animate(o, f, t, srt, mS);
          }, 10);
        } else {
          o.now = t;
          o.obj.style.visibility = t == 100 ? 'visible' : 'hidden';
        }
      },

      addevt: function(o, t, f, p) {
        var oop = this;
        if (o.addEventListener) o.addEventListener(t, function(e) {
          return oop[f](p);
        }, false);
        else if (o.attachEvent) o.attachEvent('on' + t, function(e) {
          return oop[f](p);
        });
      }

    }

    zxcFadePanel.init({
      ID: 'nav-float',
      ShowAt: 100,
      FadeDuration: 500
    });