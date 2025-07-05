// About page JavaScript functionality
document.addEventListener("DOMContentLoaded", function () {
  // Smooth scrolling for internal links
  const links = document.querySelectorAll('a[href^="#"]');
  links.forEach((link) => {
    link.addEventListener("click", function (e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute("href"));
      if (target) {
        target.scrollIntoView({
          behavior: "smooth",
          block: "start",
        });
      }
    });
  });

  // Add animation to team members on scroll
  const observerOptions = {
    threshold: 0.1,
    rootMargin: "0px 0px -50px 0px",
  };

  const observer = new IntersectionObserver(function (entries) {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.style.opacity = "1";
        entry.target.style.transform = "translateY(0)";
      }
    });
  }, observerOptions);

  // Observe team members and value cards
  const teamMembers = document.querySelectorAll(".team-member");
  const valueCards = document.querySelectorAll(".value-card");

  teamMembers.forEach((member) => {
    member.style.opacity = "0";
    member.style.transform = "translateY(30px)";
    member.style.transition = "opacity 0.6s ease, transform 0.6s ease";
    observer.observe(member);
  });

  valueCards.forEach((card) => {
    card.style.opacity = "0";
    card.style.transform = "translateY(30px)";
    card.style.transition = "opacity 0.6s ease, transform 0.6s ease";
    observer.observe(card);
  });

  // Add hover effects for team member photos
  const memberPhotos = document.querySelectorAll(".member-photo");
  memberPhotos.forEach((photo) => {
    photo.addEventListener("mouseenter", function () {
      this.style.transform = "scale(1.1)";
      this.style.transition = "transform 0.3s ease";
    });

    photo.addEventListener("mouseleave", function () {
      this.style.transform = "scale(1)";
    });
  });

  // Add parallax effect to hero section
  window.addEventListener("scroll", function () {
    const scrolled = window.pageYOffset;
    const parallaxElement = document.querySelector(".about-hero");

    if (parallaxElement) {
      const speed = 0.5;
      parallaxElement.style.transform = `translateY(${scrolled * speed}px)`;
    }
  });

  // Add typing effect to hero subtitle
  const subtitle = document.querySelector(".hero-subtitle");
  if (subtitle) {
    const text = subtitle.textContent;
    subtitle.textContent = "";
    subtitle.style.borderRight = "2px solid var(--color-primary)";

    let i = 0;
    const typeWriter = () => {
      if (i < text.length) {
        subtitle.textContent += text.charAt(i);
        i++;
        setTimeout(typeWriter, 50);
      } else {
        // Remove cursor after typing is complete
        setTimeout(() => {
          subtitle.style.borderRight = "none";
        }, 1000);
      }
    };

    // Start typing effect after a short delay
    setTimeout(typeWriter, 1000);
  }

  // Add click event for team member cards to show more info
  teamMembers.forEach((member) => {
    member.addEventListener("click", function () {
      const memberInfo = this.querySelector(".member-info");
      const description = memberInfo.querySelector(".member-description");

      if (
        description.style.maxHeight === "0px" ||
        !description.style.maxHeight
      ) {
        description.style.maxHeight = description.scrollHeight + "px";
        description.style.opacity = "1";
      } else {
        description.style.maxHeight = "0px";
        description.style.opacity = "0";
      }
    });
  });

  // Initialize member descriptions as collapsed on mobile
  if (window.innerWidth <= 768) {
    const descriptions = document.querySelectorAll(".member-description");
    descriptions.forEach((desc) => {
      desc.style.maxHeight = "0px";
      desc.style.opacity = "0";
      desc.style.overflow = "hidden";
      desc.style.transition = "max-height 0.3s ease, opacity 0.3s ease";
    });
  }

  console.log("About page loaded successfully");
});
