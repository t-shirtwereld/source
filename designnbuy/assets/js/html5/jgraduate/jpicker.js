﻿(function (e, a) {
    Math.precision = function (j, h) {
        if (h === undefined) {
            h = 0
        }
        return Math.round(j * Math.pow(10, h)) / Math.pow(10, h)
    };
    var d = function (z, k) {
        var o = this,
            j = z.find("img:first"),
            F = 0,
            E = 100,
            w = 100,
            D = 0,
            C = 100,
            v = 100,
            s = 0,
            p = 0,
            n, q, u = new Array(),
            l = function (y) {
                for (var x = 0; x < u.length; x++) {
                    u[x].call(o, o, y)
                }
            }, H = function (x) {
                var y = z.offset();
                n = {
                    l: y.left | 0,
                    t: y.top | 0
                };
                clearTimeout(q);
                q = setTimeout(function () {
                    A.call(o, x)
                }, 0);
                e(document).bind("mousemove", h).bind("mouseup", B).bind('touchmove', touchmove);
                x.preventDefault()
            },touchmove = function(e) {
				e.preventDefault();
			},h = function (x) {
                clearTimeout(q);
                q = setTimeout(function () {
                    A.call(o, x)
                }, 0);
                x.stopPropagation();
                x.preventDefault();
                return false
            }, B = function (x) {
                e(document).unbind("mouseup", B).unbind("mousemove", h).unbind('touchmove', touchmove);
                x.stopPropagation();
                x.preventDefault();
                return false
            }, A = function (M) {
                var K = M.pageX - n.l,
                    x = M.pageY - n.t,
                    L = z.w,
                    y = z.h;
                if (K < 0) {
                    K = 0
                } else {
                    if (K > L) {
                        K = L
                    }
                } if (x < 0) {
                    x = 0
                } else {
                    if (x > y) {
                        x = y
                    }
                }
                J.call(o, "xy", {
                    x: ((K / L) * w) + F,
                    y: ((x / y) * v) + D
                })
            }, r = function () {
                var L = 0,
                    x = 0,
                    N = z.w,
                    K = z.h,
                    M = j.w,
                    y = j.h;
                setTimeout(function () {
                    if (w > 0) {
                        if (s == E) {
                            L = N
                        } else {
                            L = ((s / w) * N) | 0
                        }
                    }
                    if (v > 0) {
                        if (p == C) {
                            x = K
                        } else {
                            x = ((p / v) * K) | 0
                        }
                    }
                    if (M >= N) {
                        L = (N >> 1) - (M >> 1)
                    } else {
                        L -= M >> 1
                    } if (y >= K) {
                        x = (K >> 1) - (y >> 1)
                    } else {
                        x -= y >> 1
                    }
                    j.css({
                        left: L + "px",
                        top: x + "px"
                    })
                }, 0)
            }, J = function (x, K, y) {
                var O = K !== undefined;
                if (!O) {
                    if (x === undefined || x == null) {
                        x = "xy"
                    }
                    switch (x.toString().toLowerCase()) {
                    case "x":
                        return s;
                    case "y":
                        return p;
                    case "xy":
                    default:
                        return {
                            x: s,
                            y: p
                        }
                    }
                }
                if (y != null && y == o) {
                    return
                }
                var N = false,
                    M, L;
                if (x == null) {
                    x = "xy"
                }
                switch (x.toString().toLowerCase()) {
                case "x":
                    M = K && (K.x && K.x | 0 || K | 0) || 0;
                    break;
                case "y":
                    L = K && (K.y && K.y | 0 || K | 0) || 0;
                    break;
                case "xy":
                default:
                    M = K && K.x && K.x | 0 || 0;
                    L = K && K.y && K.y | 0 || 0;
                    break
                }
                if (M != null) {
                    if (M < F) {
                        M = F
                    } else {
                        if (M > E) {
                            M = E
                        }
                    } if (s != M) {
                        s = M;
                        N = true
                    }
                }
                if (L != null) {
                    if (L < D) {
                        L = D
                    } else {
                        if (L > C) {
                            L = C
                        }
                    } if (p != L) {
                        p = L;
                        N = true
                    }
                }
                N && l.call(o, y || o)
            }, t = function (x, L) {
                var P = L !== undefined;
                if (!P) {
                    if (x === undefined || x == null) {
                        x = "all"
                    }
                    switch (x.toString().toLowerCase()) {
                    case "minx":
                        return F;
                    case "maxx":
                        return E;
                    case "rangex":
                        return {
                            minX: F,
                            maxX: E,
                            rangeX: w
                        };
                    case "miny":
                        return D;
                    case "maxy":
                        return C;
                    case "rangey":
                        return {
                            minY: D,
                            maxY: C,
                            rangeY: v
                        };
                    case "all":
                    default:
                        return {
                            minX: F,
                            maxX: E,
                            rangeX: w,
                            minY: D,
                            maxY: C,
                            rangeY: v
                        }
                    }
                }
                var O = false,
                    N, K, M, y;
                if (x == null) {
                    x = "all"
                }
                switch (x.toString().toLowerCase()) {
                case "minx":
                    N = L && (L.minX && L.minX | 0 || L | 0) || 0;
                    break;
                case "maxx":
                    K = L && (L.maxX && L.maxX | 0 || L | 0) || 0;
                    break;
                case "rangex":
                    N = L && L.minX && L.minX | 0 || 0;
                    K = L && L.maxX && L.maxX | 0 || 0;
                    break;
                case "miny":
                    M = L && (L.minY && L.minY | 0 || L | 0) || 0;
                    break;
                case "maxy":
                    y = L && (L.maxY && L.maxY | 0 || L | 0) || 0;
                    break;
                case "rangey":
                    M = L && L.minY && L.minY | 0 || 0;
                    y = L && L.maxY && L.maxY | 0 || 0;
                    break;
                case "all":
                default:
                    N = L && L.minX && L.minX | 0 || 0;
                    K = L && L.maxX && L.maxX | 0 || 0;
                    M = L && L.minY && L.minY | 0 || 0;
                    y = L && L.maxY && L.maxY | 0 || 0;
                    break
                }
                if (N != null && F != N) {
                    F = N;
                    w = E - F
                }
                if (K != null && E != K) {
                    E = K;
                    w = E - F
                }
                if (M != null && D != M) {
                    D = M;
                    v = C - D
                }
                if (y != null && C != y) {
                    C = y;
                    v = C - D
                }
            }, I = function (x) {
                if (e.isFunction(x)) {
                    u.push(x)
                }
            }, m = function (y) {
                if (!e.isFunction(y)) {
                    return
                }
                var x;
                while ((x = e.inArray(y, u)) != -1) {
                    u.splice(x, 1)
                }
            }, G = function () {
                e(document).unbind("mouseup", B).unbind("mousemove", h).unbind('touchmove', touchmove);
                z.unbind("mousedown", H);
                z = null;
                j = null;
                u = null
            };
        e.extend(true, o, {
            val: J,
            range: t,
            bind: I,
            unbind: m,
            destroy: G
        });
        j.src = k.arrow && k.arrow.image;
        j.w = k.arrow && k.arrow.width || j.width();
        j.h = k.arrow && k.arrow.height || j.height();
        z.w = k.map && k.map.width || z.width();
        z.h = k.map && k.map.height || z.height();
        z.bind("mousedown", H);
        I.call(o, r)
    }, b = function (u, z, k, y) {
            var q = this,
                l = u.find("td.Text input"),
                r = l.eq(3),
                v = l.eq(4),
                h = l.eq(5),
                o = l.length > 7 ? l.eq(6) : null,
                n = l.eq(0),
                p = l.eq(1),
                x = l.eq(2),
                s = l.eq(l.length > 7 ? 7 : 6),
                B = l.length > 7 ? l.eq(8) : null,
                w = function (D) {
                    if (D.target.value == "" && D.target != s.get(0) && (k != null && D.target != k.get(0) || k == null)) {
                        return
                    }
                    if (!t(D)) {
                        return D
                    }
                    switch (D.target) {
                    case r.get(0):
                        r.val(j.call(q, r.val(), 0, 255));
                        z.val("r", r.val(), D.target);
                        break;
                    case v.get(0):
                        v.val(j.call(q, v.val(), 0, 255));
                        z.val("g", v.val(), D.target);
                        break;
                    case h.get(0):
                        h.val(j.call(q, h.val(), 0, 255));
                        z.val("b", h.val(), D.target);
                        break;
                    case o && o.get(0):
                        o.val(j.call(q, o.val(), 0, 100));
                        z.val("a", Math.precision((o.val() * 255) / 100, y), D.target);
                        break;
                    case n.get(0):
                        n.val(j.call(q, n.val(), 0, 360));
                        z.val("h", n.val(), D.target);
                        break;
                    case p.get(0):
                        p.val(j.call(q, p.val(), 0, 100));
                        z.val("s", p.val(), D.target);
                        break;
                    case x.get(0):
                        x.val(j.call(q, x.val(), 0, 100));
                        z.val("v", x.val(), D.target);
                        break;
                    case s.get(0):
                        s.val(s.val().replace(/[^a-fA-F0-9]/g, "").toLowerCase().substring(0, 6));
                        k && k.val(s.val());
                        z.val("hex", s.val() != "" ? s.val() : null, D.target);
                        break;
                    case k && k.get(0):
                        k.val(k.val().replace(/[^a-fA-F0-9]/g, "").toLowerCase().substring(0, 6));
                        s.val(k.val());
                        z.val("hex", k.val() != "" ? k.val() : null, D.target);
                        break;
                    case B && B.get(0):
                        B.val(B.val().replace(/[^a-fA-F0-9]/g, "").toLowerCase().substring(0, 2));
                        z.val("a", B.val() != null ? parseInt(B.val(), 16) : null, D.target);
                        break
                    }
                }, A = function (D) {
                    if (z.val() != null) {
                        switch (D.target) {
                        case r.get(0):
                            r.val(z.val("r"));
                            break;
                        case v.get(0):
                            v.val(z.val("g"));
                            break;
                        case h.get(0):
                            h.val(z.val("b"));
                            break;
                        case o && o.get(0):
                            o.val(Math.precision((z.val("a") * 100) / 255, y));
                            break;
                        case n.get(0):
                            n.val(z.val("h"));
                            break;
                        case p.get(0):
                            p.val(z.val("s"));
                            break;
                        case x.get(0):
                            x.val(z.val("v"));
                            break;
                        case s.get(0):
                        case k && k.get(0):
                            s.val(z.val("hex"));
                            k && k.val(z.val("hex"));
                            break;
                        case B && B.get(0):
                            B.val(z.val("ahex").substring(6));
                            break
                        }
                    }
                }, t = function (D) {
                    switch (D.keyCode) {
                    case 9:
                    case 16:
                    case 29:
                    case 37:
                    case 38:
                    case 40:
                        return false;
                    case "c".charCodeAt():
                    case "v".charCodeAt():
                        if (D.ctrlKey) {
                            return false
                        }
                    }
                    return true
                }, j = function (F, E, D) {
                    if (F == "" || isNaN(F)) {
                        return E
                    }
                    if (F > D) {
                        return D
                    }
                    if (F < E) {
                        return E
                    }
                    return F
                }, m = function (F, D) {
                    var E = F.val("all");
                    if (D != r.get(0)) {
                        r.val(E != null ? E.r : "")
                    }
                    if (D != v.get(0)) {
                        v.val(E != null ? E.g : "")
                    }
                    if (D != h.get(0)) {
                        h.val(E != null ? E.b : "")
                    }
                    if (o && D != o.get(0)) {
                        o.val(E != null ? Math.precision((E.a * 100) / 255, y) : "")
                    }
                    if (D != n.get(0)) {
                        n.val(E != null ? E.h : "")
                    }
                    if (D != p.get(0)) {
                        p.val(E != null ? E.s : "")
                    }
                    if (D != x.get(0)) {
                        x.val(E != null ? E.v : "")
                    }
                    if (D != s.get(0) && (k && D != k.get(0) || !k)) {
                        s.val(E != null ? E.hex : "")
                    }
                    if (k && D != k.get(0) && D != s.get(0)) {
                        k.val(E != null ? E.hex : "")
                    }
                    if (B && D != B.get(0)) {
                        B.val(E != null ? E.ahex.substring(6) : "")
                    }
                }, C = function () {
                    r.add(v).add(h).add(o).add(n).add(p).add(x).add(s).add(k).add(B).unbind("keyup", w).unbind("blur", A);
                    z.unbind(m);
                    r = null;
                    v = null;
                    h = null;
                    o = null;
                    n = null;
                    p = null;
                    x = null;
                    s = null;
                    B = null
                };
            e.extend(true, q, {
                destroy: C
            });
            r.add(v).add(h).add(o).add(n).add(p).add(x).add(s).add(k).add(B).bind("keyup", w).bind("blur", A);
            z.bind(m)
        };
    e.jPicker = {
        List: [],
        Color: function (z) {
            var q = this,
                j, o, t, u, n, A, x, k = new Array(),
                m = function (r) {
                    for (var h = 0; h < k.length; h++) {
                        k[h].call(q, q, r)
                    }
                }, l = function (h, G, r) {
                    var F = G !== undefined;
                    if (!F) {
                        if (h === undefined || h == null || h == "") {
                            h = "all"
                        }
                        if (j == null) {
                            return null
                        }
                        switch (h.toString().toLowerCase()) {
                        case "ahex":
                            return g.rgbaToHex({
                                r: j,
                                g: o,
                                b: t,
                                a: u
                            });
                        case "hex":
                            return l("ahex").substring(0, 6);
                        case "all":
                            return {
                                r: j,
                                g: o,
                                b: t,
                                a: u,
                                h: n,
                                s: A,
                                v: x,
                                hex: l.call(q, "hex"),
                                ahex: l.call(q, "ahex")
                            };
                        default:
                            var D = {};
                            for (var B = 0; B < h.length; B++) {
                                switch (h.charAt(B)) {
                                case "r":
                                    if (h.length == 1) {
                                        D = j
                                    } else {
                                        D.r = j
                                    }
                                    break;
                                case "g":
                                    if (h.length == 1) {
                                        D = o
                                    } else {
                                        D.g = o
                                    }
                                    break;
                                case "b":
                                    if (h.length == 1) {
                                        D = t
                                    } else {
                                        D.b = t
                                    }
                                    break;
                                case "a":
                                    if (h.length == 1) {
                                        D = u
                                    } else {
                                        D.a = u
                                    }
                                    break;
                                case "h":
                                    if (h.length == 1) {
                                        D = n
                                    } else {
                                        D.h = n
                                    }
                                    break;
                                case "s":
                                    if (h.length == 1) {
                                        D = A
                                    } else {
                                        D.s = A
                                    }
                                    break;
                                case "v":
                                    if (h.length == 1) {
                                        D = x
                                    } else {
                                        D.v = x
                                    }
                                    break
                                }
                            }
                            return D == {} ? l.call(q, "all") : D;
                            break
                        }
                    }
                    if (r != null && r == q) {
                        return
                    }
                    var v = false;
                    if (h == null) {
                        h = ""
                    }
                    if (G == null) {
                        if (j != null) {
                            j = null;
                            v = true
                        }
                        if (o != null) {
                            o = null;
                            v = true
                        }
                        if (t != null) {
                            t = null;
                            v = true
                        }
                        if (u != null) {
                            u = null;
                            v = true
                        }
                        if (n != null) {
                            n = null;
                            v = true
                        }
                        if (A != null) {
                            A = null;
                            v = true
                        }
                        if (x != null) {
                            x = null;
                            v = true
                        }
                        v && m.call(q, r || q);
                        return
                    }
                    switch (h.toString().toLowerCase()) {
                    case "ahex":
                    case "hex":
                        var D = g.hexToRgba(G && (G.ahex || G.hex) || G || "00000000");
                        l.call(q, "rgba", {
                            r: D.r,
                            g: D.g,
                            b: D.b,
                            a: h == "ahex" ? D.a : u != null ? u : 255
                        }, r);
                        break;
                    default:
                        if (G && (G.ahex != null || G.hex != null)) {
                            l.call(q, "ahex", G.ahex || G.hex || "00000000", r);
                            return
                        }
                        var s = {}, E = false,
                            C = false;
                        if (G.r !== undefined && !h.indexOf("r") == -1) {
                            h += "r"
                        }
                        if (G.g !== undefined && !h.indexOf("g") == -1) {
                            h += "g"
                        }
                        if (G.b !== undefined && !h.indexOf("b") == -1) {
                            h += "b"
                        }
                        if (G.a !== undefined && !h.indexOf("a") == -1) {
                            h += "a"
                        }
                        if (G.h !== undefined && !h.indexOf("h") == -1) {
                            h += "h"
                        }
                        if (G.s !== undefined && !h.indexOf("s") == -1) {
                            h += "s"
                        }
                        if (G.v !== undefined && !h.indexOf("v") == -1) {
                            h += "v"
                        }
                        for (var B = 0; B < h.length; B++) {
                            switch (h.charAt(B)) {
                            case "r":
                                if (C) {
                                    continue
                                }
                                E = true;
                                s.r = G && G.r && G.r | 0 || G && G | 0 || 0;
                                if (s.r < 0) {
                                    s.r = 0
                                } else {
                                    if (s.r > 255) {
                                        s.r = 255
                                    }
                                } if (j != s.r) {
                                    j = s.r;
                                    v = true
                                }
                                break;
                            case "g":
                                if (C) {
                                    continue
                                }
                                E = true;
                                s.g = G && G.g && G.g | 0 || G && G | 0 || 0;
                                if (s.g < 0) {
                                    s.g = 0
                                } else {
                                    if (s.g > 255) {
                                        s.g = 255
                                    }
                                } if (o != s.g) {
                                    o = s.g;
                                    v = true
                                }
                                break;
                            case "b":
                                if (C) {
                                    continue
                                }
                                E = true;
                                s.b = G && G.b && G.b | 0 || G && G | 0 || 0;
                                if (s.b < 0) {
                                    s.b = 0
                                } else {
                                    if (s.b > 255) {
                                        s.b = 255
                                    }
                                } if (t != s.b) {
                                    t = s.b;
                                    v = true
                                }
                                break;
                            case "a":
                                s.a = G && G.a != null ? G.a | 0 : G != null ? G | 0 : 255;
                                if (s.a < 0) {
                                    s.a = 0
                                } else {
                                    if (s.a > 255) {
                                        s.a = 255
                                    }
                                } if (u != s.a) {
                                    u = s.a;
                                    v = true
                                }
                                break;
                            case "h":
                                if (E) {
                                    continue
                                }
                                C = true;
                                s.h = G && G.h && G.h | 0 || G && G | 0 || 0;
                                if (s.h < 0) {
                                    s.h = 0
                                } else {
                                    if (s.h > 360) {
                                        s.h = 360
                                    }
                                } if (n != s.h) {
                                    n = s.h;
                                    v = true
                                }
                                break;
                            case "s":
                                if (E) {
                                    continue
                                }
                                C = true;
                                s.s = G && G.s != null ? G.s | 0 : G != null ? G | 0 : 100;
                                if (s.s < 0) {
                                    s.s = 0
                                } else {
                                    if (s.s > 100) {
                                        s.s = 100
                                    }
                                } if (A != s.s) {
                                    A = s.s;
                                    v = true
                                }
                                break;
                            case "v":
                                if (E) {
                                    continue
                                }
                                C = true;
                                s.v = G && G.v != null ? G.v | 0 : G != null ? G | 0 : 100;
                                if (s.v < 0) {
                                    s.v = 0
                                } else {
                                    if (s.v > 100) {
                                        s.v = 100
                                    }
                                } if (x != s.v) {
                                    x = s.v;
                                    v = true
                                }
                                break
                            }
                        }
                        if (v) {
                            if (E) {
                                j = j || 0;
                                o = o || 0;
                                t = t || 0;
                                var D = g.rgbToHsv({
                                    r: j,
                                    g: o,
                                    b: t
                                });
                                n = D.h;
                                A = D.s;
                                x = D.v
                            } else {
                                if (C) {
                                    n = n || 0;
                                    A = A != null ? A : 100;
                                    x = x != null ? x : 100;
                                    var D = g.hsvToRgb({
                                        h: n,
                                        s: A,
                                        v: x
                                    });
                                    j = D.r;
                                    o = D.g;
                                    t = D.b
                                }
                            }
                            u = u != null ? u : 255;
                            m.call(q, r || q)
                        }
                        break
                    }
                }, p = function (h) {
                    if (e.isFunction(h)) {
                        k.push(h)
                    }
                }, y = function (r) {
                    if (!e.isFunction(r)) {
                        return
                    }
                    var h;
                    while ((h = e.inArray(r, k)) != -1) {
                        k.splice(h, 1)
                    }
                }, w = function () {
                    k = null
                };
            e.extend(true, q, {
                val: l,
                bind: p,
                unbind: y,
                destroy: w
            });
            if (z) {
                if (z.ahex != null) {
                    l("ahex", z)
                } else {
                    if (z.hex != null) {
                        l((z.a != null ? "a" : "") + "hex", z.a != null ? {
                            ahex: z.hex + g.intToHex(z.a)
                        } : z)
                    } else {
                        if (z.r != null && z.g != null && z.b != null) {
                            l("rgb" + (z.a != null ? "a" : ""), z)
                        } else {
                            if (z.h != null && z.s != null && z.v != null) {
                                l("hsv" + (z.a != null ? "a" : ""), z)
                            }
                        }
                    }
                }
            }
        },
        ColorMethods: {
            hexToRgba: function (m) {
                m = this.validateHex(m);
                if (m == "") {
                    return {
                        r: null,
                        g: null,
                        b: null,
                        a: null
                    }
                }
                var l = "00",
                    k = "00",
                    h = "00",
                    j = "255";
                if (m.length == 6) {
                    m += "ff"
                }
                if (m.length > 6) {
                    l = m.substring(0, 2);
                    k = m.substring(2, 4);
                    h = m.substring(4, 6);
                    j = m.substring(6, m.length)
                } else {
                    if (m.length > 4) {
                        l = m.substring(4, m.length);
                        m = m.substring(0, 4)
                    }
                    if (m.length > 2) {
                        k = m.substring(2, m.length);
                        m = m.substring(0, 2)
                    }
                    if (m.length > 0) {
                        h = m.substring(0, m.length)
                    }
                }
                return {
                    r: this.hexToInt(l),
                    g: this.hexToInt(k),
                    b: this.hexToInt(h),
                    a: this.hexToInt(j)
                }
            },
            validateHex: function (h) {
                h = h.toString().toLowerCase().replace(/[^a-f0-9]/g, "");
                if (h.length > 8) {
                    h = h.substring(0, 8)
                }
                return h
            },
            rgbaToHex: function (h) {
                return this.intToHex(h.r) + this.intToHex(h.g) + this.intToHex(h.b) + this.intToHex(h.a)
            },
            intToHex: function (j) {
                var h = (j | 0).toString(16);
                if (h.length == 1) {
                    h = ("0" + h)
                }
                return h.toString().toLowerCase()
            },
            hexToInt: function (h) {
                return parseInt(h, 16)
            },
            rgbToHsv: function (l) {
                var o = l.r / 255,
                    n = l.g / 255,
                    j = l.b / 255,
                    k = {
                        h: 0,
                        s: 0,
                        v: 0
                    }, m = 0,
                    h = 0,
                    p;
                if (o >= n && o >= j) {
                    h = o;
                    m = n > j ? j : n
                } else {
                    if (n >= j && n >= o) {
                        h = n;
                        m = o > j ? j : o
                    } else {
                        h = j;
                        m = n > o ? o : n
                    }
                }
                k.v = h;
                k.s = h ? (h - m) / h : 0;
                if (!k.s) {
                    k.h = 0
                } else {
                    p = h - m;
                    if (o == h) {
                        k.h = (n - j) / p
                    } else {
                        if (n == h) {
                            k.h = 2 + (j - o) / p
                        } else {
                            k.h = 4 + (o - n) / p
                        }
                    }
                    k.h = parseInt(k.h * 60);
                    if (k.h < 0) {
                        k.h += 360
                    }
                }
                k.s = (k.s * 100) | 0;
                k.v = (k.v * 100) | 0;
                return k
            },
            hsvToRgb: function (n) {
                var r = {
                    r: 0,
                    g: 0,
                    b: 0,
                    a: 100
                }, m = n.h,
                    x = n.s,
                    u = n.v;
                if (x == 0) {
                    if (u == 0) {
                        r.r = r.g = r.b = 0
                    } else {
                        r.r = r.g = r.b = (u * 255 / 100) | 0
                    }
                } else {
                    if (m == 360) {
                        m = 0
                    }
                    m /= 60;
                    x = x / 100;
                    u = u / 100;
                    var l = m | 0,
                        o = m - l,
                        k = u * (1 - x),
                        j = u * (1 - (x * o)),
                        w = u * (1 - (x * (1 - o)));
                    switch (l) {
                    case 0:
                        r.r = u;
                        r.g = w;
                        r.b = k;
                        break;
                    case 1:
                        r.r = j;
                        r.g = u;
                        r.b = k;
                        break;
                    case 2:
                        r.r = k;
                        r.g = u;
                        r.b = w;
                        break;
                    case 3:
                        r.r = k;
                        r.g = j;
                        r.b = u;
                        break;
                    case 4:
                        r.r = w;
                        r.g = k;
                        r.b = u;
                        break;
                    case 5:
                        r.r = u;
                        r.g = k;
                        r.b = j;
                        break
                    }
                    r.r = (r.r * 255) | 0;
                    r.g = (r.g * 255) | 0;
                    r.b = (r.b * 255) | 0
                }
                return r
            }
        }
    };
    var f = e.jPicker.Color,
        c = e.jPicker.List,
        g = e.jPicker.ColorMethods;
    e.fn.jPicker = function (j) {
        var h = arguments;
        return this.each(function () {
            var w = this,
                av = e.extend(true, {}, e.fn.jPicker.defaults, j);
            if (e(w).get(0).nodeName.toLowerCase() == "input") {
                e.extend(true, av, {
                    window: {
                        bindToInput: true,
                        expandable: true,
                        input: e(w)
                    }
                });
                if (e(w).val() == "") {
                    av.color.active = new f({
                        hex: null
                    });
                    av.color.current = new f({
                        hex: null
                    })
                } else {
                    if (g.validateHex(e(w).val())) {
                        av.color.active = new f({
                            hex: e(w).val(),
                            a: av.color.active.val("a")
                        });
                        av.color.current = new f({
                            hex: e(w).val(),
                            a: av.color.active.val("a")
                        })
                    }
                }
            }
            if (av.window.expandable) {
                e(w).after('<span class="jPicker"><span class="Icon"><span class="Color">&nbsp;</span><span class="Alpha">&nbsp;</span><span class="Image" title="Click To Open Color Picker">&nbsp;</span><span class="Container">&nbsp;</span></span></span>')
            } else {
                av.window.liveUpdate = false
            }
            var Q = parseFloat(navigator.appVersion.split("MSIE")[1]) < 7 && document.body.filters,
                R = null,
                l = null,
                s = null,
                au = null,
                at = null,
                ar = null,
                P = null,
                O = null,
                N = null,
                M = null,
                L = null,
                K = null,
                D = null,
                U = null,
                aw = null,
                J = null,
                I = null,
                am = null,
                ai = null,
                E = null,
                an = null,
                ah = null,
                X = null,
                ab = null,
                aq = null,
                r = null,
                C = null,
                u = null,
                ag = function (aB) {
                    var aD = G.active,
                        aE = n.clientPath,
                        aA = aD.val("hex"),
                        aC, az;
                    av.color.mode = aB;
                    switch (aB) {
                    case "h":
                        setTimeout(function () {
                            y.call(w, l, "transparent");
                            x.call(w, au, 0);
                            Y.call(w, au, 100);
                            x.call(w, at, 260);
                            Y.call(w, at, 100);
                            y.call(w, s, "transparent");
                            x.call(w, P, 0);
                            Y.call(w, P, 100);
                            x.call(w, O, 260);
                            Y.call(w, O, 100);
                            x.call(w, N, 260);
                            Y.call(w, N, 100);
                            x.call(w, M, 260);
                            Y.call(w, M, 100);
                            x.call(w, K, 260);
                            Y.call(w, K, 100)
                        }, 0);
                        D.range("all", {
                            minX: 0,
                            maxX: 100,
                            minY: 0,
                            maxY: 100
                        });
                        U.range("rangeY", {
                            minY: 0,
                            maxY: 360
                        });
                        if (aD.val("ahex") == null) {
                            break
                        }
                        D.val("xy", {
                            x: aD.val("s"),
                            y: 100 - aD.val("v")
                        }, D);
                        U.val("y", 360 - aD.val("h"), U);
                        break;
                    case "s":
                        setTimeout(function () {
                            y.call(w, l, "transparent");
                            x.call(w, au, -260);
                            x.call(w, at, -520);
                            x.call(w, P, -260);
                            x.call(w, O, -520);
                            x.call(w, K, 260);
                            Y.call(w, K, 100)
                        }, 0);
                        D.range("all", {
                            minX: 0,
                            maxX: 360,
                            minY: 0,
                            maxY: 100
                        });
                        U.range("rangeY", {
                            minY: 0,
                            maxY: 100
                        });
                        if (aD.val("ahex") == null) {
                            break
                        }
                        D.val("xy", {
                            x: aD.val("h"),
                            y: 100 - aD.val("v")
                        }, D);
                        U.val("y", 100 - aD.val("s"), U);
                        break;
                    case "v":
                        setTimeout(function () {
                            y.call(w, l, "000000");
                            x.call(w, au, -780);
                            x.call(w, at, 260);
                            y.call(w, s, aA);
                            x.call(w, P, -520);
                            x.call(w, O, 260);
                            Y.call(w, O, 100);
                            x.call(w, K, 260);
                            Y.call(w, K, 100)
                        }, 0);
                        D.range("all", {
                            minX: 0,
                            maxX: 360,
                            minY: 0,
                            maxY: 100
                        });
                        U.range("rangeY", {
                            minY: 0,
                            maxY: 100
                        });
                        if (aD.val("ahex") == null) {
                            break
                        }
                        D.val("xy", {
                            x: aD.val("h"),
                            y: 100 - aD.val("s")
                        }, D);
                        U.val("y", 100 - aD.val("v"), U);
                        break;
                    case "r":
                        aC = -1040;
                        az = -780;
                        D.range("all", {
                            minX: 0,
                            maxX: 255,
                            minY: 0,
                            maxY: 255
                        });
                        U.range("rangeY", {
                            minY: 0,
                            maxY: 255
                        });
                        if (aD.val("ahex") == null) {
                            break
                        }
                        D.val("xy", {
                            x: aD.val("b"),
                            y: 255 - aD.val("g")
                        }, D);
                        U.val("y", 255 - aD.val("r"), U);
                        break;
                    case "g":
                        aC = -1560;
                        az = -1820;
                        D.range("all", {
                            minX: 0,
                            maxX: 255,
                            minY: 0,
                            maxY: 255
                        });
                        U.range("rangeY", {
                            minY: 0,
                            maxY: 255
                        });
                        if (aD.val("ahex") == null) {
                            break
                        }
                        D.val("xy", {
                            x: aD.val("b"),
                            y: 255 - aD.val("r")
                        }, D);
                        U.val("y", 255 - aD.val("g"), U);
                        break;
                    case "b":
                        aC = -2080;
                        az = -2860;
                        D.range("all", {
                            minX: 0,
                            maxX: 255,
                            minY: 0,
                            maxY: 255
                        });
                        U.range("rangeY", {
                            minY: 0,
                            maxY: 255
                        });
                        if (aD.val("ahex") == null) {
                            break
                        }
                        D.val("xy", {
                            x: aD.val("r"),
                            y: 255 - aD.val("g")
                        }, D);
                        U.val("y", 255 - aD.val("b"), U);
                        break;
                    case "a":
                        setTimeout(function () {
                            y.call(w, l, "transparent");
                            x.call(w, au, -260);
                            x.call(w, at, -520);
                            x.call(w, P, 260);
                            x.call(w, O, 260);
                            Y.call(w, O, 100);
                            x.call(w, K, 0);
                            Y.call(w, K, 100)
                        }, 0);
                        D.range("all", {
                            minX: 0,
                            maxX: 360,
                            minY: 0,
                            maxY: 100
                        });
                        U.range("rangeY", {
                            minY: 0,
                            maxY: 255
                        });
                        if (aD.val("ahex") == null) {
                            break
                        }
                        D.val("xy", {
                            x: aD.val("h"),
                            y: 100 - aD.val("v")
                        }, D);
                        U.val("y", 255 - aD.val("a"), U);
                        break;
                    default:
                        throw ("Invalid Mode");
                        break
                    }
                    switch (aB) {
                    case "h":
                        break;
                    case "s":
                    case "v":
                    case "a":
                        setTimeout(function () {
                            Y.call(w, au, 100);
                            Y.call(w, P, 100);
                            x.call(w, N, 260);
                            Y.call(w, N, 100);
                            x.call(w, M, 260);
                            Y.call(w, M, 100)
                        }, 0);
                        break;
                    case "r":
                    case "g":
                    case "b":
                        setTimeout(function () {
                            y.call(w, l, "transparent");
                            y.call(w, s, "transparent");
                            Y.call(w, P, 100);
                            Y.call(w, au, 100);
                            x.call(w, au, aC);
                            x.call(w, at, aC - 260);
                            x.call(w, P, az - 780);
                            x.call(w, O, az - 520);
                            x.call(w, N, az);
                            x.call(w, M, az - 260);
                            x.call(w, K, 260);
                            Y.call(w, K, 100)
                        }, 0);
                        break
                    }
                    if (aD.val("ahex") == null) {
                        return
                    }
                    aj.call(w, aD)
                }, aj = function (aA, az) {
                    if (az == null || (az != U && az != D)) {
                        v.call(w, aA, az)
                    }
                    setTimeout(function () {
                        ay.call(w, aA);
                        al.call(w, aA);
                        W.call(w, aA)
                    }, 0)
                }, z = function (aA, az) {
                    var aC = G.active;
                    if (az != D && aC.val() == null) {
                        return
                    }
                    var aB = aA.val("all");
                    switch (av.color.mode) {
                    case "h":
                        aC.val("sv", {
                            s: aB.x,
                            v: 100 - aB.y
                        }, az);
                        break;
                    case "s":
                    case "a":
                        aC.val("hv", {
                            h: aB.x,
                            v: 100 - aB.y
                        }, az);
                        break;
                    case "v":
                        aC.val("hs", {
                            h: aB.x,
                            s: 100 - aB.y
                        }, az);
                        break;
                    case "r":
                        aC.val("gb", {
                            g: 255 - aB.y,
                            b: aB.x
                        }, az);
                        break;
                    case "g":
                        aC.val("rb", {
                            r: 255 - aB.y,
                            b: aB.x
                        }, az);
                        break;
                    case "b":
                        aC.val("rg", {
                            r: aB.x,
                            g: 255 - aB.y
                        }, az);
                        break
                    }
                }, ac = function (aA, az) {
                    var aB = G.active;
                    if (az != U && aB.val() == null) {
                        return
                    }
                    switch (av.color.mode) {
                    case "h":
                        aB.val("h", {
                            h: 360 - aA.val("y")
                        }, az);
                        break;
                    case "s":
                        aB.val("s", {
                            s: 100 - aA.val("y")
                        }, az);
                        break;
                    case "v":
                        aB.val("v", {
                            v: 100 - aA.val("y")
                        }, az);
                        break;
                    case "r":
                        aB.val("r", {
                            r: 255 - aA.val("y")
                        }, az);
                        break;
                    case "g":
                        aB.val("g", {
                            g: 255 - aA.val("y")
                        }, az);
                        break;
                    case "b":
                        aB.val("b", {
                            b: 255 - aA.val("y")
                        }, az);
                        break;
                    case "a":
                        aB.val("a", 255 - aA.val("y"), az);
                        break
                    }
                }, v = function (aC, az) {
                    if (az != D) {
                        switch (av.color.mode) {
                        case "h":
                            var aH = aC.val("sv");
                            D.val("xy", {
                                x: aH != null ? aH.s : 100,
                                y: 100 - (aH != null ? aH.v : 100)
                            }, az);
                            break;
                        case "s":
                        case "a":
                            var aB = aC.val("hv");
                            D.val("xy", {
                                x: aB && aB.h || 0,
                                y: 100 - (aB != null ? aB.v : 100)
                            }, az);
                            break;
                        case "v":
                            var aE = aC.val("hs");
                            D.val("xy", {
                                x: aE && aE.h || 0,
                                y: 100 - (aE != null ? aE.s : 100)
                            }, az);
                            break;
                        case "r":
                            var aA = aC.val("bg");
                            D.val("xy", {
                                x: aA && aA.b || 0,
                                y: 255 - (aA && aA.g || 0)
                            }, az);
                            break;
                        case "g":
                            var aI = aC.val("br");
                            D.val("xy", {
                                x: aI && aI.b || 0,
                                y: 255 - (aI && aI.r || 0)
                            }, az);
                            break;
                        case "b":
                            var aG = aC.val("rg");
                            D.val("xy", {
                                x: aG && aG.r || 0,
                                y: 255 - (aG && aG.g || 0)
                            }, az);
                            break
                        }
                    }
                    if (az != U) {
                        switch (av.color.mode) {
                        case "h":
                            U.val("y", 360 - (aC.val("h") || 0), az);
                            break;
                        case "s":
                            var aJ = aC.val("s");
                            U.val("y", 100 - (aJ != null ? aJ : 100), az);
                            break;
                        case "v":
                            var aF = aC.val("v");
                            U.val("y", 100 - (aF != null ? aF : 100), az);
                            break;
                        case "r":
                            U.val("y", 255 - (aC.val("r") || 0), az);
                            break;
                        case "g":
                            U.val("y", 255 - (aC.val("g") || 0), az);
                            break;
                        case "b":
                            U.val("y", 255 - (aC.val("b") || 0), az);
                            break;
                        case "a":
                            var aD = aC.val("a");
                            U.val("y", 255 - (aD != null ? aD : 255), az);
                            break
                        }
                    }
                }, ay = function (aA) {
                    try {
                        var az = aA.val("all");
                        E.css({
                            backgroundColor: az && "#" + az.hex || "transparent"
                        });
                        Y.call(w, E, az && Math.precision((az.a * 100) / 255, 4) || 0)
                    } catch (aB) {}
                }, al = function (aC) {
                    switch (av.color.mode) {
                    case "h":
                        y.call(w, l, new f({
                            h: aC.val("h") || 0,
                            s: 100,
                            v: 100
                        }).val("hex"));
                        break;
                    case "s":
                    case "a":
                        var aB = aC.val("s");
                        Y.call(w, at, 100 - (aB != null ? aB : 100));
                        break;
                    case "v":
                        var aA = aC.val("v");
                        Y.call(w, au, aA != null ? aA : 100);
                        break;
                    case "r":
                        Y.call(w, at, Math.precision((aC.val("r") || 0) / 255 * 100, 4));
                        break;
                    case "g":
                        Y.call(w, at, Math.precision((aC.val("g") || 0) / 255 * 100, 4));
                        break;
                    case "b":
                        Y.call(w, at, Math.precision((aC.val("b") || 0) / 255 * 100));
                        break
                    }
                    var az = aC.val("a");
                    Y.call(w, ar, Math.precision(((255 - (az || 0)) * 100) / 255, 4))
                }, W = function (aF) {
                    switch (av.color.mode) {
                    case "h":
                        var aH = aF.val("a");
                        Y.call(w, L, Math.precision(((255 - (aH || 0)) * 100) / 255, 4));
                        break;
                    case "s":
                        var aA = aF.val("hva"),
                            aB = new f({
                                h: aA && aA.h || 0,
                                s: 100,
                                v: aA != null ? aA.v : 100
                            });
                        y.call(w, s, aB.val("hex"));
                        Y.call(w, O, 100 - (aA != null ? aA.v : 100));
                        Y.call(w, L, Math.precision(((255 - (aA && aA.a || 0)) * 100) / 255, 4));
                        break;
                    case "v":
                        var aC = aF.val("hsa"),
                            aE = new f({
                                h: aC && aC.h || 0,
                                s: aC != null ? aC.s : 100,
                                v: 100
                            });
                        y.call(w, s, aE.val("hex"));
                        Y.call(w, L, Math.precision(((255 - (aC && aC.a || 0)) * 100) / 255, 4));
                        break;
                    case "r":
                    case "g":
                    case "b":
                        var aD = 0,
                            aG = 0,
                            az = aF.val("rgba");
                        if (av.color.mode == "r") {
                            aD = az && az.b || 0;
                            aG = az && az.g || 0
                        } else {
                            if (av.color.mode == "g") {
                                aD = az && az.b || 0;
                                aG = az && az.r || 0
                            } else {
                                if (av.color.mode == "b") {
                                    aD = az && az.r || 0;
                                    aG = az && az.g || 0
                                }
                            }
                        }
                        var aI = aG > aD ? aD : aG;
                        Y.call(w, O, aD > aG ? Math.precision(((aD - aG) / (255 - aG)) * 100, 4) : 0);
                        Y.call(w, N, aG > aD ? Math.precision(((aG - aD) / (255 - aD)) * 100, 4) : 0);
                        Y.call(w, M, Math.precision((aI / 255) * 100, 4));
                        Y.call(w, L, Math.precision(((255 - (az && az.a || 0)) * 100) / 255, 4));
                        break;
                    case "a":
                        var aH = aF.val("a");
                        y.call(w, s, aF.val("hex") || "000000");
                        Y.call(w, L, aH != null ? 0 : 100);
                        Y.call(w, K, aH != null ? 100 : 0);
                        break
                    }
                }, y = function (az, aA) {
                    az.css({
                        backgroundColor: aA && aA.length == 6 && "#" + aA || "transparent"
                    })
                }, t = function (az, aA) {
                    if (Q && (aA.indexOf("AlphaBar.png") != -1 || aA.indexOf("Bars.png") != -1 || aA.indexOf("Maps.png") != -1)) {
                        az.attr("pngSrc", aA);
                        az.css({
                            backgroundImage: "none",
                            filter: "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" + aA + "', sizingMethod='scale')"
                        })
                    } else {
                        az.css({
                            backgroundImage: "url(" + aA + ")"
                        })
                    }
                }, x = function (az, aA) {
                    az.css({
                        top: aA + "px"
                    })
                }, Y = function (aA, az) {
                    aA.css({
                        visibility: az > 0 ? "visible" : "hidden"
                    });
                    if (az > 0 && az < 100) {
                        if (Q) {
                            var aB = aA.attr("pngSrc");
                            if (aB != null && (aB.indexOf("AlphaBar.png") != -1 || aB.indexOf("Bars.png") != -1 || aB.indexOf("Maps.png") != -1)) {
                                aA.css({
                                    filter: "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" + aB + "', sizingMethod='scale') progid:DXImageTransform.Microsoft.Alpha(opacity=" + az + ")"
                                })
                            } else {
                                aA.css({
                                    opacity: Math.precision(az / 100, 4)
                                })
                            }
                        } else {
                            aA.css({
                                opacity: Math.precision(az / 100, 4)
                            })
                        }
                    } else {
                        if (az == 0 || az == 100) {
                            if (Q) {
                                var aB = aA.attr("pngSrc");
                                if (aB != null && (aB.indexOf("AlphaBar.png") != -1 || aB.indexOf("Bars.png") != -1 || aB.indexOf("Maps.png") != -1)) {
                                    aA.css({
                                        filter: "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" + aB + "', sizingMethod='scale')"
                                    })
                                } else {
                                    aA.css({
                                        opacity: ""
                                    })
                                }
                            } else {
                                aA.css({
                                    opacity: ""
                                })
                            }
                        }
                    }
                }, B = function () {
                    G.active.val("ahex", G.current.val("ahex"))
                }, T = function () {
                    G.current.val("ahex", G.active.val("ahex"))
                }, A = function (az) {
                    e(this).parents("tbody:first").find('input:radio[value!="' + az.target.value + '"]').removeAttr("checked");
                    ag.call(w, az.target.value)
                }, Z = function () {
                    B.call(w)
                }, q = function () {
                    B.call(w);
                    av.window.expandable && ao.call(w);
                    e.isFunction(ax) && ax.call(w, G.active, X)
                }, m = function () {
                    T.call(w);
                    av.window.expandable && ao.call(w);
                    e.isFunction(ae) && ae.call(w, G.active, ah)
                }, af = function () {
                    V.call(w)
                }, ap = function (aB, az) {
                    var aA = aB.val("hex");
                    an.css({
                        backgroundColor: aA && "#" + aA || "transparent"
                    });
                    Y.call(w, an, Math.precision(((aB.val("a") || 0) * 100) / 255, 4))
                }, H = function (aC, az) {
                    var aB = aC.val("hex");
                    var aA = aC.val("va");
                    aq.css({
                        backgroundColor: aB && "#" + aB || "transparent"
                    });
                    Y.call(w, r, Math.precision(((255 - (aA && aA.a || 0)) * 100) / 255, 4));
                    if (av.window.bindToInput && av.window.updateInputColor) {
                        av.window.input.css({
                            backgroundColor: aB && "#" + aB || "transparent",
                            color: aA == null || aA.v > 75 ? "#000000" : "#ffffff"
                        })
                    }
                }, S = function (aB) {
                    var az = av.window.element,
                        aA = av.window.page;
                    J = parseInt(R.css("left"));
                    I = parseInt(R.css("top"));
                    am = aB.pageX;
                    ai = aB.pageY;
                    e(document).bind("mousemove", k).bind("mouseup", p).bind('touchmove', touchmove);
                    aB.preventDefault()
                }, k = function (az) {
                    R.css({
                        left: J - (am - az.pageX) + "px",
                        top: I - (ai - az.pageY) + "px"
                    });
                    if (av.window.expandable && !e.support.boxModel) {
                        R.prev().css({
                            left: R.css("left"),
                            top: R.css("top")
                        })
                    }
                    az.stopPropagation();
                    az.preventDefault();
                    return false
                }, p = function (az) {
                    e(document).unbind("mousemove", k).unbind("mouseup", p).unbind('touchmove', touchmove);
                    az.stopPropagation();
                    az.preventDefault();
                    return false
                }, F = function (az) {
                    az.preventDefault();
                    az.stopPropagation();
                    G.active.val("ahex", e(this).attr("title") || null, az.target);
                    return false
                }, ae = e.isFunction(h[1]) && h[1] || null,
                ad = e.isFunction(h[2]) && h[2] || null,
                ax = e.isFunction(h[3]) && h[3] || null,
                V = function () {
                    G.current.val("ahex", G.active.val("ahex"));
                    var az = function () {
                        if (!av.window.expandable || e.support.boxModel) {
                            return
                        }
                        var aA = R.find("table:first");
                        R.before("<iframe/>");
                        R.prev().css({
                            width: aA.width(),
                            height: R.height(),
                            opacity: 0,
                            position: "absolute",
                            left: R.css("left"),
                            top: R.css("top")
                        })
                    };
                    if (av.window.expandable) {
                        e(document.body).children("div.jPicker.Container").css({
                            zIndex: 10
                        });
                        R.css({
                            zIndex: 20
                        })
                    }
                    switch (av.window.effects.type) {
                    case "fade":
                        R.fadeIn(av.window.effects.speed.show, az);
                        break;
                    case "slide":
                        R.slideDown(av.window.effects.speed.show, az);
                        break;
                    case "show":
                    default:
                        R.show(av.window.effects.speed.show, az);
                        break
                    }
                }, ao = function () {
                    var az = function () {
                        if (av.window.expandable) {
                            R.css({
                                zIndex: 10
                            })
                        }
                        if (!av.window.expandable || e.support.boxModel) {
                            return
                        }
                        R.prev().remove()
                    };
                    switch (av.window.effects.type) {
                    case "fade":
                        R.fadeOut(av.window.effects.speed.hide, az);
                        break;
                    case "slide":
                        R.slideUp(av.window.effects.speed.hide, az);
                        break;
                    case "show":
                    default:
                        R.hide(av.window.effects.speed.hide, az);
                        break
                    }
                }, o = function () {
                    var aG = av.window,
                        az = aG.expandable ? e(w).next().find(".Container:first") : null;
                    R = aG.expandable ? e("<div/>") : e(w);
                    R.addClass("jPicker Container");
                    if (aG.expandable) {
                        R.hide()
                    }
                    R.get(0).onselectstart = function () {
                        return false
                    };
                    var aJ = G.active.val("all");
                    if (aG.alphaPrecision < 0) {
                        aG.alphaPrecision = 0
                    } else {
                        if (aG.alphaPrecision > 2) {
                            aG.alphaPrecision = 2
                        }
                    }
                    var aK = '<table class="jPicker" cellpadding="0" cellspacing="0"><tbody>' + (aG.expandable ? '<tr><td class="Move" colspan="5">&nbsp;</td></tr>' : "") + '<tr><td rowspan="9"><h2 class="Title">' + (aG.title || aa.text.title) + '</h2><div class="Map"><span class="Map1">&nbsp;</span><span class="Map2">&nbsp;</span><span class="Map3">&nbsp;</span><img src="' + n.clientPath + n.colorMap.arrow.file + '" class="Arrow"/></div></td><td rowspan="9"><div class="Bar"><span class="Map1">&nbsp;</span><span class="Map2">&nbsp;</span><span class="Map3">&nbsp;</span><span class="Map4">&nbsp;</span><span class="Map5">&nbsp;</span><span class="Map6">&nbsp;</span><img src="' + n.clientPath + n.colorBar.arrow.file + '" class="Arrow"/></div></td><td colspan="2" class="Preview">' + aa.text.newColor + '<div><span class="Active" title="' + aa.tooltips.colors.newColor + '">&nbsp;</span><span class="Current" title="' + aa.tooltips.colors.currentColor + '">&nbsp;</span></div>' + aa.text.currentColor + '</td><td rowspan="9" class="Button"><input type="button" class="Ok" value="' + aa.text.ok + '" title="' + aa.tooltips.buttons.ok + '"/><input type="button" class="Cancel" value="' + aa.text.cancel + '" title="' + aa.tooltips.buttons.cancel + '"/><hr/><div class="Grid">&nbsp;</div></td></tr><tr class="Hue"><td class="Radio"><label title="' + aa.tooltips.hue.radio + '"><input type="radio" value="h"' + (av.color.mode == "h" ? ' checked="checked"' : "") + '/>H:</label></td><td class="Text"><input type="text" maxlength="3" value="' + (aJ != null ? aJ.h : "") + '" title="' + aa.tooltips.hue.textbox + '"/>&nbsp;&deg;</td></tr><tr class="Saturation"><td class="Radio"><label title="' + aa.tooltips.saturation.radio + '"><input type="radio" value="s"' + (av.color.mode == "s" ? ' checked="checked"' : "") + '/>S:</label></td><td class="Text"><input type="text" maxlength="3" value="' + (aJ != null ? aJ.s : "") + '" title="' + aa.tooltips.saturation.textbox + '"/>&nbsp;%</td></tr><tr class="Value"><td class="Radio"><label title="' + aa.tooltips.value.radio + '"><input type="radio" value="v"' + (av.color.mode == "v" ? ' checked="checked"' : "") + '/>V:</label><br/><br/></td><td class="Text"><input type="text" maxlength="3" value="' + (aJ != null ? aJ.v : "") + '" title="' + aa.tooltips.value.textbox + '"/>&nbsp;%<br/><br/></td></tr><tr class="Red"><td class="Radio"><label title="' + aa.tooltips.red.radio + '"><input type="radio" value="r"' + (av.color.mode == "r" ? ' checked="checked"' : "") + '/>R:</label></td><td class="Text"><input type="text" maxlength="3" value="' + (aJ != null ? aJ.r : "") + '" title="' + aa.tooltips.red.textbox + '"/></td></tr><tr class="Green"><td class="Radio"><label title="' + aa.tooltips.green.radio + '"><input type="radio" value="g"' + (av.color.mode == "g" ? ' checked="checked"' : "") + '/>G:</label></td><td class="Text"><input type="text" maxlength="3" value="' + (aJ != null ? aJ.g : "") + '" title="' + aa.tooltips.green.textbox + '"/></td></tr><tr class="Blue"><td class="Radio"><label title="' + aa.tooltips.blue.radio + '"><input type="radio" value="b"' + (av.color.mode == "b" ? ' checked="checked"' : "") + '/>B:</label></td><td class="Text"><input type="text" maxlength="3" value="' + (aJ != null ? aJ.b : "") + '" title="' + aa.tooltips.blue.textbox + '"/></td></tr><tr class="Alpha"><td class="Radio">' + (aG.alphaSupport ? '<label title="' + aa.tooltips.alpha.radio + '"><input type="radio" value="a"' + (av.color.mode == "a" ? ' checked="checked"' : "") + "/>A:</label>" : "&nbsp;") + '</td><td class="Text">' + (aG.alphaSupport ? '<input type="text" maxlength="' + (3 + aG.alphaPrecision) + '" value="' + (aJ != null ? Math.precision((aJ.a * 100) / 255, aG.alphaPrecision) : "") + '" title="' + aa.tooltips.alpha.textbox + '"/>&nbsp;%' : "&nbsp;") + '</td></tr><tr class="Hex"><td colspan="2" class="Text"><label title="' + aa.tooltips.hex.textbox + '">#:<input type="text" maxlength="6" class="Hex" value="' + (aJ != null ? aJ.hex : "") + '"/></label>' + (aG.alphaSupport ? '<input type="text" maxlength="2" class="AHex" value="' + (aJ != null ? aJ.ahex.substring(6) : "") + '" title="' + aa.tooltips.hex.alpha + '"/></td>' : "&nbsp;") + "</tr></tbody></table>";
                    if (aG.expandable) {
                        R.html(aK);
                        if (e(document.body).children("div.jPicker.Container").length == 0) {
                            e(document.body).prepend(R)
                        } else {
                            e(document.body).children("div.jPicker.Container:last").after(R)
                        }
                        R.mousedown(function () {
                            e(document.body).children("div.jPicker.Container").css({
                                zIndex: 10
                            });
                            R.css({
                                zIndex: 20
                            })
                        });
                        R.css({
                            left: aG.position.x == "left" ? (az.offset().left - 530 - (aG.position.y == "center" ? 25 : 0)) + "px" : aG.position.x == "center" ? (az.offset().left - 260) + "px" : aG.position.x == "right" ? (az.offset().left - 10 + (aG.position.y == "center" ? 25 : 0)) + "px" : aG.position.x == "screenCenter" ? ((e(document).width() >> 1) - 260) + "px" : (az.offset().left + parseInt(aG.position.x)) + "px",
                            position: "absolute",
                            top: aG.position.y == "top" ? (az.offset().top - 312) + "px" : aG.position.y == "center" ? (az.offset().top - 156) + "px" : aG.position.y == "bottom" ? (az.offset().top + 25) + "px" : (az.offset().top + parseInt(aG.position.y)) + "px"
                        })
                    } else {
                        R = e(w);
                        R.html(aK)
                    }
                    var aD = R.find("tbody:first");
                    l = aD.find("div.Map:first");
                    s = aD.find("div.Bar:first");
                    var aL = l.find("span"),
                        aI = s.find("span");
                    au = aL.filter(".Map1:first");
                    at = aL.filter(".Map2:first");
                    ar = aL.filter(".Map3:first");
                    P = aI.filter(".Map1:first");
                    O = aI.filter(".Map2:first");
                    N = aI.filter(".Map3:first");
                    M = aI.filter(".Map4:first");
                    L = aI.filter(".Map5:first");
                    K = aI.filter(".Map6:first");
                    D = new d(l, {
                        map: {
                            width: n.colorMap.width,
                            height: n.colorMap.height
                        },
                        arrow: {
                            image: n.clientPath + n.colorMap.arrow.file,
                            width: n.colorMap.arrow.width,
                            height: n.colorMap.arrow.height
                        }
                    });
                    D.bind(z);
                    U = new d(s, {
                        map: {
                            width: n.colorBar.width,
                            height: n.colorBar.height
                        },
                        arrow: {
                            image: n.clientPath + n.colorBar.arrow.file,
                            width: n.colorBar.arrow.width,
                            height: n.colorBar.arrow.height
                        }
                    });
                    U.bind(ac);
                    aw = new b(aD, G.active, aG.expandable && aG.bindToInput ? aG.input : null, aG.alphaPrecision);
                    var aB = aJ != null ? aJ.hex : null,
                        aH = aD.find(".Preview"),
                        aF = aD.find(".Button");
                    E = aH.find(".Active:first").css({
                        backgroundColor: aB && "#" + aB || "transparent"
                    });
                    an = aH.find(".Current:first").css({
                        backgroundColor: aB && "#" + aB || "transparent"
                    }).bind("click", Z).bind("touchend",Z);
                    Y.call(w, an, Math.precision(G.current.val("a") * 100) / 255, 4);
                    ah = aF.find(".Ok:first").bind("click", m);
                    X = aF.find(".Cancel:first").bind("click", q);
                    ab = aF.find(".Grid:first");
                    setTimeout(function () {
                        t.call(w, au, n.clientPath + "Maps.png");
                        t.call(w, at, n.clientPath + "Maps.png");
                        t.call(w, ar, n.clientPath + "map-opacity.png");
                        t.call(w, P, n.clientPath + "Bars.png");
                        t.call(w, O, n.clientPath + "Bars.png");
                        t.call(w, N, n.clientPath + "Bars.png");
                        t.call(w, M, n.clientPath + "Bars.png");
                        t.call(w, L, n.clientPath + "bar-opacity.png");
                        t.call(w, K, n.clientPath + "AlphaBar.png");
                        t.call(w, aH.find("div:first"), n.clientPath + "preview-opacity.png")
                    }, 0);
                    aD.find("td.Radio input").bind("click", A);
                    if (G.quickList && G.quickList.length > 0) {
                        var aE = "";
                        for (i = 0; i < G.quickList.length; i++) {
                            if ((typeof (G.quickList[i])).toString().toLowerCase() == "string") {
                                G.quickList[i] = new f({
                                    hex: G.quickList[i]
                                })
                            }
                            var aC = G.quickList[i].val("a");
                            var aM = G.quickList[i].val("ahex");
                            if (!aG.alphaSupport && aM) {
                                aM = aM.substring(0, 6) + "ff"
                            }
                            var aA = G.quickList[i].val("hex");
                            aE += '<span class="QuickColor"' + (aM && ' title="#' + aM + '"' || "") + ' style="background-color:' + (aA && "#" + aA || "") + ";" + (aA ? "" : "background-image:url(" + n.clientPath + "NoColor.png)") + (aG.alphaSupport && aC && aC < 255 ? ";opacity:" + Math.precision(aC / 255, 4) + ";filter:Alpha(opacity=" + Math.precision(aC / 2.55, 4) + ")" : "") + '">&nbsp;</span>'
                        }
                        t.call(w, ab, n.clientPath + "bar-opacity.png");
                        ab.html(aE);
                        ab.find(".QuickColor").click(F).bind("touchend",F)
                    }
                    ag.call(w, av.color.mode);
                    G.active.bind(aj);
                    e.isFunction(ad) && G.active.bind(ad);
                    G.current.bind(ap);
                    if (aG.expandable) {
                        w.icon = az.parents(".Icon:first");
                        aq = w.icon.find(".Color:first").css({
                            backgroundColor: aB && "#" + aB || "transparent"
                        });
                        r = w.icon.find(".Alpha:first");
                        t.call(w, r, n.clientPath + "bar-opacity.png");
                        Y.call(w, r, Math.precision(((255 - (aJ != null ? aJ.a : 0)) * 100) / 255, 4));
                        C = w.icon.find(".Image:first").css({
                            backgroundImage: "url(" + n.clientPath + n.picker.file + ")"
                        }).bind("click", af);
                        if (aG.bindToInput && aG.updateInputColor) {
                            aG.input.css({
                                backgroundColor: aB && "#" + aB || "transparent",
                                color: aJ == null || aJ.v > 75 ? "#000000" : "#ffffff"
                            })
                        }
                        u = aD.find(".Move:first").bind("mousedown", S);
                        G.active.bind(H)
                    } else {
                        V.call(w)
                    }
                }, ak = function () {
                    R.find("td.Radio input").unbind("click", A);
                    an.unbind("click", Z);
                    X.unbind("click", q);
                    ah.unbind("click", m);
                    if (av.window.expandable) {
                        C.unbind("click", af);
                        u.unbind("mousedown", S);
                        w.icon = null
                    }
                    R.find(".QuickColor").unbind("click", F).unbind("touchend",F);
                    l = null;
                    s = null;
                    au = null;
                    at = null;
                    ar = null;
                    P = null;
                    O = null;
                    N = null;
                    M = null;
                    L = null;
                    K = null;
                    D.destroy();
                    D = null;
                    U.destroy();
                    U = null;
                    aw.destroy();
                    aw = null;
                    E = null;
                    an = null;
                    ah = null;
                    X = null;
                    ab = null;
                    ae = null;
                    ax = null;
                    ad = null;
                    R.html("");
                    for (i = 0; i < c.length; i++) {
                        if (c[i] == w) {
                            c.splice(i, 1)
                        }
                    }
                }, n = av.images,
                aa = av.localization = svgEditor.uiStrings.colorPicker,
                G = {
                    active: (typeof (av.color.active)).toString().toLowerCase() == "string" ? new f({
                        ahex: !av.window.alphaSupport && av.color.active ? av.color.active.substring(0, 6) + "ff" : av.color.active
                    }) : new f({
                        ahex: !av.window.alphaSupport && av.color.active.val("ahex") ? av.color.active.val("ahex").substring(0, 6) + "ff" : av.color.active.val("ahex")
                    }),
                    current: (typeof (av.color.active)).toString().toLowerCase() == "string" ? new f({
                        ahex: !av.window.alphaSupport && av.color.active ? av.color.active.substring(0, 6) + "ff" : av.color.active
                    }) : new f({
                        ahex: !av.window.alphaSupport && av.color.active.val("ahex") ? av.color.active.val("ahex").substring(0, 6) + "ff" : av.color.active.val("ahex")
                    }),
                    quickList: av.color.quickList
                };
            e.extend(true, w, {
                commitCallback: ae,
                liveCallback: ad,
                cancelCallback: ax,
                color: G,
                show: V,
                hide: ao,
                destroy: ak
            });
            c.push(w);
            setTimeout(function () {
                o.call(w)
            }, 0)
        })
    };
    e.fn.jPicker.defaults = {
        window: {
            title: null,
            effects: {
                type: "slide",
                speed: {
                    show: "slow",
                    hide: "fast"
                }
            },
            position: {
                x: "screenCenter",
                y: "top"
            },
            expandable: false,
            liveUpdate: true,
            alphaSupport: false,
            alphaPrecision: 0,
            updateInputColor: true
        },
        color: {
            mode: "h",
            active: new f({
                ahex: "#ffcc00ff"
            }),
            quickList: [ new f({
                h: 360,
                s: 100,
                v: 100
            }), new f({
                h: 360,
                s: 50,
                v: 100
            }), new f({
                h: 30,
                s: 100,
                v: 100
            }), new f({
                h: 60,
                s: 100,
                v: 100
            }), new f({
                h: 70,
                s: 100,
                v: 100
            }), new f({
                h: 90,
                s: 100,
                v: 100
            }), new f({
                h: 120,
                s: 100,
                v: 100
            }), new f({
                h: 150,
                s: 100,
                v: 100
            }), new f({
                h: 240,
                s: 100,
                v: 100
            }), new f({
                h: 240,
                s: 100,
                v: 50
            }), new f({
                h: 270,
                s: 100,
                v: 100
            }), new f({
                h: 300,
                s: 100,
                v: 100
            }), new f({
                h: 330,
                s: 100,
                v: 100
            }), new f({
                h: 330,
                s: 100,
                v: 75
            }), new f({
                h: 330,
                s: 100,
                v: 50
			}), new f({
                h: 90,
                s: 100,
                v: 0
			}), new f({
                h: 0,
                s: 0,
                v: 100								
            }), new f()]
        },
        images: {
            clientPath: "/jPicker/images/",
            colorMap: {
                width: 256,
                height: 256,
                arrow: {
                    file: "mappoint.gif",
                    width: 30,
                    height: 30
                }
            },
            colorBar: {
                width: 20,
                height: 256,
                arrow: {
                    file: "rangearrows.gif",
                    width: 20,
                    height: 7
                }
            },
            picker: {
                file: "picker.gif",
                width: 25,
                height: 24
            }
        },
        localization: {
            text: {
                title: "Drag Markers To Pick A Color",
                newColor: "new",
                currentColor: "current",
                ok: "OK",
                cancel: "Cancel"
            },
            tooltips: {
                colors: {
                    newColor: "New Color - Press &ldquo;OK&rdquo; To Commit",
                    currentColor: "Click To Revert To Original Color"
                },
                buttons: {
                    ok: "Commit To This Color Selection",
                    cancel: "Cancel And Revert To Original Color"
                },
                hue: {
                    radio: "Set To &ldquo;Hue&rdquo; Color Mode",
                    textbox: "Enter A &ldquo;Hue&rdquo; Value (0-360&deg;)"
                },
                saturation: {
                    radio: "Set To &ldquo;Saturation&rdquo; Color Mode",
                    textbox: "Enter A &ldquo;Saturation&rdquo; Value (0-100%)"
                },
                value: {
                    radio: "Set To &ldquo;Value&rdquo; Color Mode",
                    textbox: "Enter A &ldquo;Value&rdquo; Value (0-100%)"
                },
                red: {
                    radio: "Set To &ldquo;Red&rdquo; Color Mode",
                    textbox: "Enter A &ldquo;Red&rdquo; Value (0-255)"
                },
                green: {
                    radio: "Set To &ldquo;Green&rdquo; Color Mode",
                    textbox: "Enter A &ldquo;Green&rdquo; Value (0-255)"
                },
                blue: {
                    radio: "Set To &ldquo;Blue&rdquo; Color Mode",
                    textbox: "Enter A &ldquo;Blue&rdquo; Value (0-255)"
                },
                alpha: {
                    radio: "Set To &ldquo;Alpha&rdquo; Color Mode",
                    textbox: "Enter A &ldquo;Alpha&rdquo; Value (0-100)"
                },
                hex: {
                    textbox: "Enter A &ldquo;Hex&rdquo; Color Value (#000000-#ffffff)",
                    alpha: "Enter A &ldquo;Alpha&rdquo; Value (#00-#ff)"
                }
            }
        }
    }
})(jQuery, "1.1.5");