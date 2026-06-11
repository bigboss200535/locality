$(function () {
  ("use strict");

  // ========== Preloader ==========
  $(".preloader .loader span").each(function (i, element) {
    $(element).css("animation-delay", (i * 0.2).toFixed(2).toString() + "s");
  });
  $(window).on("load", function () {
    $(".preloader").delay(350).fadeOut("slow");
  });

  // ========== Background Images ==========
  $(".bg-img").each(function (i, el) {
    if ($(this).attr("data-bg-img")) {
      $(this).css({
        "background-image": "url(" + $(this).data("bg-img") + ")",
        opacity: $(this).data("bg-opacity") || "1",
      });
    }
  });

  // ========== Mobile Menu ==========
  $(".menu-toggler, .nav-list").click(function (e) {
    $(".nav-list").toggleClass("show");
    $(".menu-toggler").toggleClass("close");
  });

  // ========== Sub Menu Item ==========
  $(".has-sub-menu").click(function (e) {
    e.stopPropagation();
    if (!($(window).innerWidth() > 1199)) {
      $(this).children(".sub-menu").slideToggle();
    }
  });

  // ========== Search Toggler ==========
  $(".search-toggler").click(function (e) {
    e.preventDefault();
    $(".search-popup").toggleClass("active");
  });

  // ========== Dark Mode ==========
  function setTheme(theme) {
    if (theme == "dark") {
      $("body").addClass("dark");
      $("body").removeClass("light");
      $(".theme-switcher i").attr("class", "flaticon flaticon-brightness");
      localStorage.setItem("theme", "dark");
    } else {
      $("body").removeClass("dark");
      $("body").addClass("light");
      $(".theme-switcher i").attr("class", "flaticon flaticon-night-mode");
      localStorage.setItem("theme", "light");
    }
  }
  var theme = localStorage.getItem("theme");
  if (theme) setTheme(theme);

  $(".theme-switcher").click(function (e) {
    if ($("body").hasClass("dark")) setTheme("light");
    else setTheme("dark");
  });

  // ========== Scroll Top ==========
  var path = document.querySelector(".scroll-top path");
  var path_len = path.getTotalLength();
  path.style.strokeDasharray = path_len + " " + path_len;
  path.style.strokeDashoffset = path_len;
  path.getBoundingClientRect();
  path.style.transition = path.style.WebkitTransition = "stroke-dashoffset 10ms linear";

  function updateScrollTop() {
    var offset = $(document).height() - $(window).height();
    path.style.strokeDashoffset = path_len - ($(window).scrollTop() * path_len) / offset;
  }
  updateScrollTop();
  $(window).scroll(updateScrollTop);

  $(".scroll-top").on("click", function () {
    $("html, body").stop().animate({ scrollTop: 0 }, 500);
  });

  // ========== Nav Links ==========
  $(".nav-link").click(function (e) {
    if (this.hash) {
      e.preventDefault();
      $("html, body")
        .stop()
        .animate({ scrollTop: $(this.hash).offset().top - 100 }, 500);
    }
  });

  // ========== Custom Cursor ==========
  $(window).on("mousemove", function (e) {
    setTimeout(() => {
      $(".cursor").css("left", e.clientX + "px");
      $(".cursor").css("top", e.clientY + "px");
    }, 160);
  });

  // ========== On Window Scroll ==========
  $(window).on("scroll", function () {
    $("header").toggleClass("sticky", $(window).scrollTop() >= 100);
    $(".scroll-top").toggleClass("show", $(window).scrollTop() >= 100);
  });

  // ========== Counter Up ==========
  $(".count").counterUp({
    delay: 10,
    time: 3000,
  });

  // ========== Magnific Popup ==========
  $(".play-btn").magnificPopup({
    type: "iframe",
  });

  // ========== Tilt Js ==========
  $("[data-tilt]").tilt({
    maxTilt: 2,
  });

  // ========== Wow Js ==========
  new WOW().init();

  // ========== Sliders ==========
  $(".home-2").owlCarousel({
    loop: true,
    margin: 0,
    autoplay: true,
    autoplayTimeout: 3000,
    nav: true,
    dots: false,
    navText: ["<i class='fa fa-arrow-left'></i>", "<i class='fa fa-arrow-right'></i>"],
    animateOut: "fadeOut",
    animateIn: "fadeIn",
    active: true,
    autoplay: false,
    autoplayTimeout: 5000,
    smartSpeed: 1000,
    responsive: {
      0: { items: 1 },
      576: { items: 1 },
      768: { items: 1 },
      1200: { items: 1 },
    },
  });
  $(".client-slider").owlCarousel({
    loop: true,
    margin: 10,
    autoplay: true,
    autoplayTimeout: 3000,
    nav: false,
    dots: false,
    responsiveClass: true,
    responsive: {
      0: { items: 1 },
      576: { items: 2 },
      768: { items: 3 },
      1200: { items: 4 },
    },
  });
  $(".project-slider").owlCarousel({
    loop: true,
    margin: 10,
    autoplay: true,
    autoplayTimeout: 5000,
    nav: true,
    dots: false,
    navText: ["<i class='fa fa-arrow-left'></i>", "<i class='fa fa-arrow-right'></i>"],
    responsiveClass: true,
    responsive: {
      0: { items: 1 },
      576: { items: 2 },
      768: { items: 3 },
      1200: { items: 4 },
    },
  });
  $(".testimonial-slider").owlCarousel({
    loop: true,
    margin: 10,
    autoplay: true,
    autoplayTimeout: 5000,
    nav: true,
    dots: false,
    responsiveClass: true,
    navText: ["<i class='fa fa-arrow-left'></i>", "<i class='fa fa-arrow-right'></i>"],
    responsive: {
      0: { items: 1 },
      1200: { items: 2 },
    },
  });

  if ($(".hero.home-2").length) {
    $("header").addClass("style-2");
  }
});

// Ajax mail js
$(function () {
  // Get the form.
  var form = $("#contact-form");

  // Get the messages div.
  var formMessages = $(".form-message");

  // Set up an event listener for the contact form.
  $(form).submit(function (e) {
    // Stop the browser from submitting the form.
    e.preventDefault();

    // Serialize the form data.
    var formData = $(form).serialize();

    // Submit the form using AJAX.
    $.ajax({
      type: "POST",
      url: $(form).attr("action"),
      data: formData,
    })
      .done(function (response) {
        // Make sure that the formMessages div has the 'success' class.
        $(formMessages).removeClass("error");
        $(formMessages).addClass("success");

        // Set the message text.
        $(formMessages).text(response);

        // Clear the form.
        $("#contact-form input,#contact-form textarea").val("");
      })
      .fail(function (data) {
        // Make sure that the formMessages div has the 'error' class.
        $(formMessages).removeClass("success");
        $(formMessages).addClass("error");

        // Set the message text.
        if (data.responseText !== "") {
          $(formMessages).text(data.responseText);
        } else {
          $(formMessages).text("Oops! An error occured and your message could not be sent.");
        }
      });
  });
});
