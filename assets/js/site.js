document.addEventListener('DOMContentLoaded', () => {
  /* ===== HEADER SCROLL ===== */
  const header = document.querySelector('[data-site-header]');
  const toggle = document.querySelector('[data-mobile-toggle]');
  const drawer = document.querySelector('[data-mobile-drawer]');

  const syncHeader = () => {
    if (!header) return;
    header.classList.toggle('is-scrolled', window.scrollY > 20);
  };
  syncHeader();
  window.addEventListener('scroll', syncHeader, { passive: true });

  if (toggle && drawer) {
    toggle.addEventListener('click', () => {
      const open = drawer.classList.toggle('is-open');
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  }

  /* ===== SCROLL REVEAL ===== */
  const revealEls = document.querySelectorAll('.reveal-el');
  if (revealEls.length > 0 && 'IntersectionObserver' in window) {
    const revealObserver = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          revealObserver.unobserve(entry.target);
        }
      });
    }, { threshold: 0.15 });
    revealEls.forEach((el) => revealObserver.observe(el));
  } else {
    revealEls.forEach((el) => el.classList.add('is-visible'));
  }

  /* ===== COUNTER ANIMATION ===== */
  const statNumbers = document.querySelectorAll('.stat-number[data-count]');
  if (statNumbers.length > 0 && 'IntersectionObserver' in window) {
    const counterObserver = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          animateCounter(entry.target);
          counterObserver.unobserve(entry.target);
        }
      });
    }, { threshold: 0.5 });
    statNumbers.forEach((el) => counterObserver.observe(el));
  }

  function animateCounter(el) {
    const end = parseInt(el.getAttribute('data-count'), 10);
    const prefix = el.getAttribute('data-prefix') || '';
    const suffix = el.getAttribute('data-suffix') || '';
    const duration = 2000;
    const startTime = performance.now();

    function update(now) {
      const elapsed = now - startTime;
      const progress = Math.min(elapsed / duration, 1);
      const eased = 1 - Math.pow(1 - progress, 3);
      const current = Math.round(eased * end);
      el.textContent = prefix + current + suffix;
      if (progress < 1) requestAnimationFrame(update);
    }
    requestAnimationFrame(update);
  }

  /* ===== VALUATION FORM ===== */
  const valuationForm = document.getElementById('valuationForm');
  const valuationMsg = document.getElementById('valuationMsg');
  if (valuationForm) {
    valuationForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      const data = new FormData(valuationForm);
      const body = {
        name: data.get('name'),
        email: data.get('email'),
        phone: data.get('phone'),
        address: data.get('address'),
        message: 'Free market evaluation request for ' + data.get('address')
      };

      try {
        const res = await fetch('/api/contact.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(body)
        });
        if (!res.ok) throw new Error('Failed');
        valuationForm.innerHTML = '<div style="text-align:center;padding:3rem 2rem;max-width:480px;margin:0 auto;"><div style="width:64px;height:64px;border-radius:50%;background:rgba(212,168,67,0.15);display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;"><svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#d4a843" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg></div><h3 style="color:white;font-size:1.5rem;font-weight:700;margin:0 0 0.75rem;">Request Received!</h3><p style="color:rgba(255,255,255,0.7);font-size:1rem;line-height:1.6;margin:0;">Muhammad Arshad will respond with your<br>free market evaluation within <strong style="color:#d4a843;">48 hours</strong>.</p></div>';
      } catch (err) {
        if (valuationMsg) {
          valuationMsg.innerHTML = '<p style="color:#ef4444;">Something went wrong. Please try again or call us directly.</p>';
        }
      }
    });
  }

  /* ===== WHATSAPP FLOAT ===== */
  const waToggle = document.querySelector('.wa-toggle');
  const waPanel = document.querySelector('.wa-panel');
  const waClose = document.querySelector('.wa-close-btn');
  if (waToggle && waPanel) {
    waToggle.addEventListener('click', () => {
      waPanel.classList.toggle('is-open');
    });
    if (waClose) {
      waClose.addEventListener('click', () => {
        waPanel.classList.remove('is-open');
      });
    }
    // Hide near footer
    window.addEventListener('scroll', () => {
      try {
        const nearBottom = (window.innerHeight + window.scrollY) >= (document.body.offsetHeight - 220);
        const waFloat = document.querySelector('.whatsapp-float');
        if (waFloat) waFloat.style.display = nearBottom ? 'none' : '';
      } catch (e) {}
    }, { passive: true });
  }

  /* ===== CONTACT FORM (handled by inline script on contact page) ===== */
});
