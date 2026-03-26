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

    document.addEventListener("DOMContentLoaded", function () {
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
        });
    });
})();
