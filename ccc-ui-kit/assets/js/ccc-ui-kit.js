(function () {
    "use strict";

    function initCarousel(component) {
        var track = component.querySelector("[data-ccc-carousel-track]");
        var slides = component.querySelectorAll("[data-ccc-carousel-slide]");
        var prevButton = component.querySelector("[data-ccc-carousel-prev]");
        var nextButton = component.querySelector("[data-ccc-carousel-next]");

        if (!track || !slides.length) {
            return;
        }

        var currentIndex = 0;
        var autoplayEnabled = component.getAttribute("data-ccc-carousel-autoplay") === "1";
        var interval = parseInt(component.getAttribute("data-ccc-carousel-interval"), 10);
        var timer = null;

        if (isNaN(interval) || interval < 2500) {
            interval = 5000;
        }

        function render() {
            track.style.transform = "translateX(-" + (currentIndex * 100) + "%)";
        }

        function goNext() {
            currentIndex = (currentIndex + 1) % slides.length;
            render();
        }

        function goPrev() {
            currentIndex = (currentIndex - 1 + slides.length) % slides.length;
            render();
        }

        function stopAutoplay() {
            if (timer) {
                window.clearInterval(timer);
                timer = null;
            }
        }

        function startAutoplay() {
            if (!autoplayEnabled || slides.length < 2 || timer) {
                return;
            }

            timer = window.setInterval(goNext, interval);
        }

        if (nextButton) {
            nextButton.addEventListener("click", function () {
                goNext();
                stopAutoplay();
                startAutoplay();
            });
        }

        if (prevButton) {
            prevButton.addEventListener("click", function () {
                goPrev();
                stopAutoplay();
                startAutoplay();
            });
        }

        component.addEventListener("mouseenter", stopAutoplay);
        component.addEventListener("mouseleave", startAutoplay);

        // Touch/swipe support
        var touchStartX = 0;
        var touchStartY = 0;
        var swiping = false;

        track.addEventListener("touchstart", function (e) {
            touchStartX = e.touches[0].clientX;
            touchStartY = e.touches[0].clientY;
            swiping = false;
        }, { passive: true });

        track.addEventListener("touchmove", function (e) {
            if (swiping) {
                e.preventDefault();
            }
            var dx = Math.abs(e.touches[0].clientX - touchStartX);
            var dy = Math.abs(e.touches[0].clientY - touchStartY);
            if (dx > dy && dx > 10) {
                swiping = true;
            }
        }, { passive: false });

        track.addEventListener("touchend", function (e) {
            var deltaX = e.changedTouches[0].clientX - touchStartX;
            if (Math.abs(deltaX) > 50) {
                if (deltaX < 0) {
                    goNext();
                } else {
                    goPrev();
                }
                stopAutoplay();
                startAutoplay();
            }
        }, { passive: true });

        // Keep slide positioning stable after zoom or viewport changes.
        window.addEventListener("resize", render);
        window.addEventListener("orientationchange", render);

        if (typeof window.ResizeObserver === "function") {
            var observer = new ResizeObserver(function () {
                render();
            });
            observer.observe(component);
        }

        render();
        startAutoplay();
    }

    function initAccordion(component) {
        var allowMultiple = component.getAttribute("data-ccc-accordion-allow-multiple") === "1";
        var items = component.querySelectorAll("[data-ccc-accordion-item]");

        if (!items.length) {
            return;
        }

        function closeItem(item) {
            var trigger = item.querySelector("[data-ccc-accordion-trigger]");
            var panel = item.querySelector("[data-ccc-accordion-panel]");

            if (!trigger || !panel) {
                return;
            }

            trigger.setAttribute("aria-expanded", "false");
            panel.setAttribute("hidden", "hidden");
            item.classList.remove("is-open");
        }

        function openItem(item) {
            var trigger = item.querySelector("[data-ccc-accordion-trigger]");
            var panel = item.querySelector("[data-ccc-accordion-panel]");

            if (!trigger || !panel) {
                return;
            }

            trigger.setAttribute("aria-expanded", "true");
            panel.removeAttribute("hidden");
            item.classList.add("is-open");
        }

        items.forEach(function (item) {
            var trigger = item.querySelector("[data-ccc-accordion-trigger]");
            var panel = item.querySelector("[data-ccc-accordion-panel]");

            if (!trigger || !panel) {
                return;
            }

            if (trigger.getAttribute("aria-expanded") === "true") {
                item.classList.add("is-open");
            }

            trigger.addEventListener("click", function () {
                var isExpanded = trigger.getAttribute("aria-expanded") === "true";

                if (isExpanded) {
                    closeItem(item);
                    return;
                }

                if (!allowMultiple) {
                    items.forEach(function (otherItem) {
                        if (otherItem !== item) {
                            closeItem(otherItem);
                        }
                    });
                }

                openItem(item);
            });
        });
    }

    function initReveal() {
        var elements = document.querySelectorAll("[data-ccc-reveal]");

        if (!elements.length || typeof window.IntersectionObserver !== "function") {
            elements.forEach(function (el) {
                el.classList.add("is-visible");
            });
            return;
        }

        var observer = new IntersectionObserver(
            function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add("is-visible");
                        observer.unobserve(entry.target);
                    }
                });
            },
            {
                root: null,
                rootMargin: "0px 0px -60px 0px",
                threshold: 0.15,
            }
        );

        elements.forEach(function (el) {
            observer.observe(el);
        });
    }

    function initSectionNav(component) {
        var links = component.querySelectorAll("[data-ccc-section-link]");
        var observedSections = [];

        if (!links.length) {
            return;
        }

        function setActiveById(id) {
            links.forEach(function (link) {
                var href = link.getAttribute("href") || "";
                var isMatch = href === "#" + id;
                link.classList.toggle("is-active", isMatch);
                if (isMatch) {
                    link.setAttribute("aria-current", "true");
                } else {
                    link.removeAttribute("aria-current");
                }
            });
        }

        links.forEach(function (link) {
            link.addEventListener("click", function () {
                var targetId = (link.getAttribute("href") || "").replace("#", "");
                if (targetId !== "") {
                    setActiveById(targetId);
                }
            });

            var target = document.querySelector(link.getAttribute("href"));
            if (target) {
                observedSections.push(target);
            }
        });

        if (typeof window.IntersectionObserver !== "function" || !observedSections.length) {
            return;
        }

        var observer = new IntersectionObserver(
            function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting && entry.target.id) {
                        setActiveById(entry.target.id);
                    }
                });
            },
            {
                root: null,
                rootMargin: "-30% 0px -55% 0px",
                threshold: 0,
            }
        );

        observedSections.forEach(function (section) {
            observer.observe(section);
        });
    }

    function initStats(component) {
        var numbers = component.querySelectorAll("[data-ccc-stats-value]");
        if (!numbers.length) {
            return;
        }

        var animated = false;

        function animateNumbers() {
            if (animated) {
                return;
            }
            animated = true;

            numbers.forEach(function (el) {
                var raw = el.getAttribute("data-ccc-stats-value") || "0";
                var prefix = "";
                var suffix = "";
                var cleaned = raw;

                // Extract prefix (e.g., "+") and suffix (e.g., "+")
                var match = cleaned.match(/^([^\d]*)(\d+)([^\d]*)$/);
                if (!match) {
                    return;
                }

                prefix = match[1];
                var target = parseInt(match[2], 10);
                suffix = match[3];

                var duration = 1500;
                var startTime = null;

                function step(timestamp) {
                    if (!startTime) {
                        startTime = timestamp;
                    }
                    var progress = Math.min((timestamp - startTime) / duration, 1);
                    // Ease out cubic
                    var eased = 1 - Math.pow(1 - progress, 3);
                    var current = Math.round(eased * target);
                    el.textContent = prefix + current + suffix;

                    if (progress < 1) {
                        window.requestAnimationFrame(step);
                    }
                }

                el.textContent = prefix + "0" + suffix;
                window.requestAnimationFrame(step);
            });
        }

        if (typeof window.IntersectionObserver !== "function") {
            animateNumbers();
            return;
        }

        var observer = new IntersectionObserver(
            function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        animateNumbers();
                        observer.unobserve(entry.target);
                    }
                });
            },
            { threshold: 0.3 }
        );

        observer.observe(component);
    }

    function initCountdown(component) {
        var surface = component.querySelector("[data-ccc-countdown-target]");
        if (!surface) {
            return;
        }

        var target = new Date(surface.getAttribute("data-ccc-countdown-target")).getTime();
        var daysEl = surface.querySelector("[data-ccc-countdown-days]");
        var hoursEl = surface.querySelector("[data-ccc-countdown-hours]");
        var minsEl = surface.querySelector("[data-ccc-countdown-mins]");
        var secsEl = surface.querySelector("[data-ccc-countdown-secs]");
        var gridEl = surface.querySelector("[data-ccc-countdown-grid]");
        var expiredEl = surface.querySelector("[data-ccc-countdown-expired]");

        if (!daysEl || !hoursEl || !minsEl || !secsEl) {
            return;
        }

        function pad(n) {
            return n < 10 ? "0" + n : String(n);
        }

        function update() {
            var now = Date.now();
            var diff = target - now;

            if (diff <= 0) {
                if (gridEl) { gridEl.setAttribute("hidden", "hidden"); }
                if (expiredEl) { expiredEl.removeAttribute("hidden"); }
                return false;
            }

            var d = Math.floor(diff / 86400000);
            var h = Math.floor((diff % 86400000) / 3600000);
            var m = Math.floor((diff % 3600000) / 60000);
            var s = Math.floor((diff % 60000) / 1000);

            daysEl.textContent = pad(d);
            hoursEl.textContent = pad(h);
            minsEl.textContent = pad(m);
            secsEl.textContent = pad(s);

            return true;
        }

        if (update()) {
            var timer = window.setInterval(function () {
                if (!update()) {
                    window.clearInterval(timer);
                }
            }, 1000);
        }
    }

    function initBackToTop() {
        var btn = document.querySelector(".ccc-ui-back-to-top");
        if (!btn) {
            return;
        }

        function toggle() {
            if (window.scrollY > 600) {
                btn.classList.add("is-visible");
            } else {
                btn.classList.remove("is-visible");
            }
        }

        window.addEventListener("scroll", toggle, { passive: true });
        toggle();

        btn.addEventListener("click", function () {
            window.scrollTo({ top: 0, behavior: "smooth" });
        });
    }

    function getLogoUrl() {
        var img = document.querySelector("img.custom-logo");
        if (!img) {
            return "";
        }
        var srcset = img.getAttribute("srcset") || "";
        var parts = srcset.split(",").filter(Boolean);
        if (parts.length) {
            var last = parts[parts.length - 1].trim();
            var url = last.split(" ")[0];
            if (url) {
                return url;
            }
        }
        return img.getAttribute("src") || "";
    }

    function initCurtain() {
        // Somente na Home, uma vez por sessão, respeitando acessibilidade.
        if (!document.body.classList.contains("home")) {
            return;
        }

        if (window.matchMedia && window.matchMedia("(prefers-reduced-motion: reduce)").matches) {
            return;
        }

        try {
            if (window.sessionStorage.getItem("ccc_curtain_seen") === "1") {
                return;
            }
        } catch (e) {
            return;
        }

        var curtain = document.createElement("div");
        curtain.className = "ccc-ui-curtain";
        curtain.setAttribute("aria-hidden", "true");
        curtain.setAttribute("data-ccc-curtain", "");

        var left = document.createElement("div");
        left.className = "ccc-ui-curtain__panel ccc-ui-curtain__panel--left";
        var right = document.createElement("div");
        right.className = "ccc-ui-curtain__panel ccc-ui-curtain__panel--right";

        var spotLeft = document.createElement("div");
        spotLeft.className = "ccc-ui-curtain__spotlight ccc-ui-curtain__spotlight--left";
        var spotRight = document.createElement("div");
        spotRight.className = "ccc-ui-curtain__spotlight ccc-ui-curtain__spotlight--right";
        var glow = document.createElement("div");
        glow.className = "ccc-ui-curtain__glow";

        curtain.appendChild(spotLeft);
        curtain.appendChild(spotRight);
        curtain.appendChild(glow);
        curtain.appendChild(left);
        curtain.appendChild(right);

        var logoUrl = getLogoUrl();
        if (logoUrl) {
            var logo = document.createElement("div");
            logo.className = "ccc-ui-curtain__logo";
            var logoImg = document.createElement("img");
            logoImg.src = logoUrl;
            logoImg.alt = "Curitiba Comedy Club";
            logo.appendChild(logoImg);
            curtain.appendChild(logo);
        }

        document.body.appendChild(curtain);

        // Em telas de toque (mobile), abre mais rápido.
        if (window.matchMedia && window.matchMedia("(pointer: coarse)").matches) {
            curtain.classList.add("ccc-ui-curtain--fast");
        }

        var finished = false;

        function dismiss() {
            if (finished) {
                return;
            }
            finished = true;
            if (curtain.parentNode) {
                curtain.parentNode.removeChild(curtain);
            }
            try {
                window.sessionStorage.setItem("ccc_curtain_seen", "1");
            } catch (e) {
                // storage indisponível; a cortina simplesmente reaparece na próxima visita.
            }
        }

        // Clique/toque pula a animação imediatamente.
        curtain.addEventListener("click", dismiss);
        curtain.addEventListener("touchstart", dismiss, { passive: true });

        // Sequência: pinta fechado, acende holofotes/logo, segura um instante,
        // então abre as cortinas devagar e apaga os efeitos.
        window.requestAnimationFrame(function () {
            window.requestAnimationFrame(function () {
                curtain.classList.add("is-ready");
            });
        });

        window.setTimeout(function () {
            curtain.classList.add("is-open");
        }, 1500);

        // Rede de segurança: nunca deixa o site preso atrás da cortina.
        window.setTimeout(dismiss, 4200);
    }

    document.addEventListener("DOMContentLoaded", function () {
        initReveal();
        initBackToTop();
        initCurtain();

        var components = document.querySelectorAll("[data-ccc-ui-component]");

        components.forEach(function (component) {
            component.setAttribute("data-ccc-ui-ready", "1");

            var componentType = component.getAttribute("data-ccc-ui-component") || "";

            if (component.querySelector("[data-ccc-carousel-track]")) {
                initCarousel(component);
            }

            if (componentType === "accordion") {
                initAccordion(component);
            }

            if (componentType === "section-nav") {
                initSectionNav(component);
            }

            if (componentType === "countdown") {
                initCountdown(component);
            }

            if (componentType === "stats") {
                initStats(component);
            }
        });
    });
})();
