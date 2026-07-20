<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>NEXOSN — Sua identidade digital única, segura e inteligente</title>
<meta name="description" content="Conecte pessoas, empresas e oportunidades com uma identidade digital única. Links, agenda, PIX, QR Code e muito mais em um só lugar. Grátis para começar.">
<meta name="robots" content="index, follow">
<link rel="canonical" href="{{ url('/') }}">
<meta name="theme-color" content="#003049">
<meta name="application-name" content="NEXOSN">
<link rel="icon" type="image/png" sizes="192x192" href="/images/icon-192.png">
<link rel="apple-touch-icon" href="/images/icon-192.png">

<!-- Open Graph (WhatsApp, Facebook, LinkedIn) -->
<meta property="og:site_name" content="NEXOSN">
<meta property="og:title" content="NEXOSN — Identidade digital única, segura e inteligente">
<meta property="og:description" content="Conecte pessoas, empresas e oportunidades com uma identidade digital única. Links, agenda, QR Code, PIX e muito mais em um só lugar.">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url('/') }}">
<meta property="og:image" content="{{ asset('images/og-default.png') }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:locale" content="pt_BR">

<!-- Twitter / X Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="@nexosn">
<meta name="twitter:title" content="NEXOSN — Identidade digital única, segura e inteligente">
<meta name="twitter:description" content="Conecte pessoas, empresas e oportunidades com uma identidade digital única. Grátis para começar.">
<meta name="twitter:image" content="{{ asset('images/og-default.png') }}">

<!-- Structured Data (Schema.org) -->
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "SoftwareApplication",
  "name": "NEXOSN",
  "applicationCategory": "BusinessApplication",
  "operatingSystem": "Web",
  "description": "Plataforma de identidade digital que conecta pessoas, empresas, produtos, serviços e oportunidades por meio de um perfil único, seguro e inteligente.",
  "url": "{{ url('/') }}",
  "offers": {
    "@@type": "Offer",
    "price": "0",
    "priceCurrency": "BRL"
  },
  "author": {
    "@@type": "Organization",
    "name": "PageUp Sistemas",
    "address": {
      "@@type": "PostalAddress",
      "addressLocality": "Porto Velho",
      "addressRegion": "RO",
      "addressCountry": "BR"
    }
  }
}
</script>

<!-- Lucide Icons -->
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>

@vite(['resources/css/app.css', 'resources/js/app.js'])

<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'Inter', sans-serif; background: #F0F0EE; color: #1A1F2E; }
a { text-decoration: none; }

/* ---- NAVBAR ---- */
.navbar {
    position: sticky; top: 0; z-index: 50;
    background: rgba(0,48,73,0.97);
    backdrop-filter: blur(12px);
    padding: 0 24px;
    display: flex; align-items: center; justify-content: space-between;
    height: 64px;
    border-bottom: 1px solid rgba(255,255,255,0.08);
}
.navbar-brand {
    font-size: 22px; font-weight: 800; color: #fff; letter-spacing: -0.5px;
    display: flex; align-items: center; gap: 8px;
}
.navbar-brand span { color: #FCBF49; }
.navbar-links { display: flex; align-items: center; gap: 8px; }
.btn-nav-ghost {
    padding: 8px 18px; border-radius: 8px; font-size: 14px; font-weight: 500;
    color: rgba(255,255,255,0.8); transition: all .15s;
    border: 1px solid rgba(255,255,255,0.15);
}
.btn-nav-ghost:hover { background: rgba(255,255,255,0.1); color: #fff; }
.btn-nav-cta {
    padding: 8px 20px; border-radius: 8px; font-size: 14px; font-weight: 600;
    background: #D62828; color: #fff; transition: all .15s;
}
.btn-nav-cta:hover { background: #b91c1c; transform: translateY(-1px); }

/* ---- HERO ---- */
.hero {
    background: linear-gradient(135deg, #003049 0%, #005073 50%, #003049 100%);
    padding: 80px 24px 0;
    text-align: center;
    position: relative;
    overflow: hidden;
}
.hero::before {
    content: '';
    position: absolute; inset: 0;
    background: radial-gradient(ellipse 80% 60% at 50% 0%, rgba(252,191,73,0.12) 0%, transparent 70%);
}
.hero-badge {
    display: inline-flex; align-items: center; gap: 6px;
    background: rgba(252,191,73,0.15); border: 1px solid rgba(252,191,73,0.3);
    color: #FCBF49; font-size: 12px; font-weight: 600; letter-spacing: .5px;
    padding: 6px 14px; border-radius: 100px; margin-bottom: 24px;
    text-transform: uppercase;
}
.hero h1 {
    font-size: clamp(32px, 6vw, 64px);
    font-weight: 900; color: #fff; line-height: 1.1;
    letter-spacing: -1.5px; margin-bottom: 20px; position: relative;
}
.hero h1 em { color: #FCBF49; font-style: normal; }
.hero p {
    font-size: clamp(16px, 2vw, 20px); color: rgba(255,255,255,0.72);
    max-width: 560px; margin: 0 auto 40px; line-height: 1.6; position: relative;
}
.hero-btns { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; position: relative; }
.btn-hero-primary {
    display: inline-flex; align-items: center; gap: 8px;
    background: #D62828; color: #fff; font-size: 16px; font-weight: 700;
    padding: 16px 32px; border-radius: 12px; transition: all .2s;
    box-shadow: 0 8px 24px rgba(214,40,40,0.4);
}
.btn-hero-primary:hover { background: #b91c1c; transform: translateY(-2px); box-shadow: 0 12px 32px rgba(214,40,40,0.5); }
.btn-hero-secondary {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(255,255,255,0.1); border: 1.5px solid rgba(255,255,255,0.25);
    color: #fff; font-size: 16px; font-weight: 600;
    padding: 16px 32px; border-radius: 12px; transition: all .2s;
}
.btn-hero-secondary:hover { background: rgba(255,255,255,0.18); transform: translateY(-2px); }
.hero-stats {
    display: flex; justify-content: center; gap: 40px; flex-wrap: wrap;
    margin-top: 60px; position: relative;
}
.hero-stat { text-align: center; }
.hero-stat-num { font-size: 28px; font-weight: 800; color: #FCBF49; }
.hero-stat-label { font-size: 12px; color: rgba(255,255,255,0.6); margin-top: 2px; }

/* ---- MOCKUP MULTI-CARTÃO ---- */
.mockup-scene {
    display: flex; align-items: flex-end; justify-content: center;
    gap: 16px; margin-top: 64px; position: relative;
    padding: 24px 16px 80px;
}
.mockup-scene::before {
    content: '';
    position: absolute; bottom: 20px; left: 50%; transform: translateX(-50%);
    width: 70%; height: 60px;
    background: radial-gradient(ellipse, rgba(0,0,0,0.4) 0%, transparent 70%);
    filter: blur(20px);
    z-index: 0;
}
/* Cartão lateral menor */
.mk-card {
    background: #fff; border-radius: 20px;
    box-shadow: 0 24px 64px rgba(0,0,0,0.45);
    overflow: visible; flex-shrink: 0;
    position: relative; z-index: 1;
}
/* inner overflow para clip correto dos cantos */
.mk-card > *:first-child:not(.mk-badge-float) {
    border-radius: 20px 20px 0 0;
    overflow: hidden;
}
.mk-card-side { width: 220px; }
.mk-card-main { width: 270px; z-index: 2; margin-bottom: 20px; box-shadow: 0 32px 80px rgba(0,0,0,0.55); }

/* Capa + avatar */
.mk-cover {
    height: 80px; position: relative;
}
.mk-cover-main { height: 96px; }
.mk-avatar {
    position: absolute; bottom: -22px; left: 50%; transform: translateX(-50%);
    width: 48px; height: 48px; border-radius: 50%;
    border: 3px solid #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; font-weight: 800; color: #fff;
}
.mk-avatar-main {
    width: 60px; height: 60px; bottom: -28px;
    font-size: 22px;
}
.mk-body { padding: 28px 16px 16px; text-align: center; }
.mk-body-main { padding: 36px 20px 20px; }
.mk-name { font-size: 13px; font-weight: 700; color: #1a1f2e; }
.mk-name-main { font-size: 15px; }
.mk-role { font-size: 11px; color: #888; margin-top: 2px; }
.mk-bio { font-size: 10px; color: #aaa; margin-top: 6px; line-height: 1.4; }

/* Foto grid */
.mk-photos {
    display: grid; grid-template-columns: repeat(4, 1fr); gap: 3px;
    margin: 10px 0;
}
.mk-photo {
    aspect-ratio: 1; border-radius: 4px; overflow: hidden;
}

/* Links */
.mk-links { display: flex; flex-direction: column; gap: 6px; margin-top: 10px; }
.mk-link {
    padding: 8px 10px; border-radius: 8px; color: #fff; font-size: 11px; font-weight: 600;
    display: flex; align-items: center; justify-content: center; gap: 6px;
}
.mk-link-outline {
    padding: 7px 10px; border-radius: 8px; font-size: 11px; font-weight: 600;
    display: flex; align-items: center; justify-content: center; gap: 6px;
    border: 1.5px solid #003049; color: #003049; background: transparent;
}
.mk-divider { height: 1px; background: #f0f0ee; margin: 10px 0; }

/* Seção de contato info */
.mk-contact-row {
    display: flex; align-items: center; gap: 8px;
    font-size: 10px; color: #555; padding: 5px 0;
    border-bottom: 1px solid #f5f5f5; text-align: left;
}

/* Card de agenda */
.mk-calendar-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 14px 14px 8px; border-bottom: 1px solid #f0f0ee;
}
.mk-cal-title { font-size: 12px; font-weight: 700; color: #1a1f2e; }
.mk-cal-sub { font-size: 10px; color: #888; }
.mk-cal-grid {
    display: grid; grid-template-columns: repeat(7, 1fr);
    gap: 2px; padding: 10px 14px;
}
.mk-cal-day {
    aspect-ratio: 1; border-radius: 5px;
    display: flex; align-items: center; justify-content: center;
    font-size: 9px; font-weight: 500; color: #aaa;
}
.mk-cal-day.has-slot {
    background: rgba(0,48,73,0.08); color: #003049; font-weight: 700; cursor: pointer;
}
.mk-cal-day.today {
    background: #003049; color: #FCBF49; font-weight: 800;
}
.mk-cal-day.selected {
    background: #D62828; color: #fff; font-weight: 800;
}
.mk-slots-label { font-size: 10px; font-weight: 700; color: #003049; padding: 0 14px 6px; text-transform: uppercase; letter-spacing: .4px; }
.mk-slots { display: flex; flex-wrap: wrap; gap: 4px; padding: 0 14px 14px; }
.mk-slot {
    padding: 4px 8px; border-radius: 6px; background: rgba(0,48,73,0.07);
    color: #003049; font-size: 10px; font-weight: 600;
}
.mk-slot.booked { background: #f5f5f5; color: #ccc; text-decoration: line-through; }
.mk-slot.selected { background: #D62828; color: #fff; }

/* Card de formulário de contato */
.mk-form-header {
    padding: 14px 14px 10px; border-bottom: 1px solid #f0f0ee;
    display: flex; align-items: center; gap: 8px;
}
.mk-form-title { font-size: 12px; font-weight: 700; color: #1a1f2e; }
.mk-form-body { padding: 10px 14px 14px; display: flex; flex-direction: column; gap: 8px; }
.mk-input {
    border: 1.5px solid #e8e8e6; border-radius: 8px; padding: 7px 10px;
    font-size: 10px; color: #333; background: #fafaf9;
}
.mk-input.filled { border-color: #003049; background: rgba(0,48,73,0.03); }
.mk-textarea {
    border: 1.5px solid #e8e8e6; border-radius: 8px; padding: 7px 10px;
    font-size: 10px; color: #333; background: #fafaf9; height: 44px;
}
.mk-textarea.filled { border-color: #003049; background: rgba(0,48,73,0.03); }
.mk-btn-send {
    background: #D62828; color: #fff; border-radius: 8px; padding: 9px;
    font-size: 11px; font-weight: 700; text-align: center;
    display: flex; align-items: center; justify-content: center; gap: 5px;
}

/* Stats mini card */
.mk-stats { display: flex; gap: 0; border-top: 1px solid #f0f0ee; }
.mk-stat { flex: 1; padding: 10px 8px; text-align: center; border-right: 1px solid #f0f0ee; }
.mk-stat:last-child { border-right: none; }
.mk-stat-num { font-size: 14px; font-weight: 800; color: #003049; }
.mk-stat-lbl { font-size: 9px; color: #aaa; margin-top: 1px; }

/* Label flutuante */
.mk-badge-float {
    position: absolute; top: -10px; right: -8px; z-index: 10;
    background: #FCBF49; color: #003049; font-size: 9px; font-weight: 800;
    padding: 3px 8px; border-radius: 100px; letter-spacing: .3px; text-transform: uppercase;
    box-shadow: 0 4px 10px rgba(252,191,73,0.5); white-space: nowrap;
}

@media(max-width: 820px) {
    .mk-card-side:nth-child(1) { display: none; }
    .mk-card-side:nth-child(3) { display: none; }
    .mk-card-main { margin-bottom: 0; }
    .mockup-scene { margin-top: 40px; padding-bottom: 60px; }
}
@media(max-width: 400px) {
    .mk-card-main { width: 240px; }
}

/* ---- SEÇÕES ---- */
.section { padding: 80px 24px; }
.section-alt { background: #fff; }
.container { max-width: 1100px; margin: 0 auto; }
.section-tag {
    display: inline-block; background: rgba(0,48,73,0.08); color: #003049;
    font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase;
    padding: 5px 12px; border-radius: 100px; margin-bottom: 12px;
}
.section-title { font-size: clamp(24px, 4vw, 40px); font-weight: 800; color: #1a1f2e; line-height: 1.2; margin-bottom: 12px; }
.section-sub { font-size: 17px; color: #52514E; line-height: 1.6; max-width: 560px; }
.section-header { margin-bottom: 52px; }

/* ---- FEATURE BANNERS ---- */
.features-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; }
.feature-card {
    border-radius: 20px; padding: 32px;
    position: relative; overflow: hidden;
    transition: transform .2s;
}
.feature-card:hover { transform: translateY(-4px); }
.feature-card-icon {
    width: 48px; height: 48px; border-radius: 14px;
    display: flex; align-items: center; justify-content: center; margin-bottom: 20px;
}
.feature-card h3 { font-size: 18px; font-weight: 700; margin-bottom: 8px; }
.feature-card p { font-size: 14px; line-height: 1.6; opacity: 0.82; }

/* Feature colors */
.fc-blue   { background: linear-gradient(135deg, #003049 0%, #005073 100%); color: #fff; }
.fc-red    { background: linear-gradient(135deg, #D62828 0%, #f05353 100%); color: #fff; }
.fc-orange { background: linear-gradient(135deg, #F77F00 0%, #fcbf49 100%); color: #003049; }
.fc-green  { background: linear-gradient(135deg, #16a34a 0%, #22c55e 100%); color: #fff; }
.fc-purple { background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%); color: #fff; }
.fc-teal   { background: linear-gradient(135deg, #0f766e 0%, #14b8a6 100%); color: #fff; }

/* ---- COMO FUNCIONA ---- */
.steps { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 32px; }
.step { text-align: center; }
.step-num {
    width: 52px; height: 52px; border-radius: 50%;
    background: #003049; color: #FCBF49;
    font-size: 20px; font-weight: 800;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 16px;
}
.step h3 { font-size: 16px; font-weight: 700; color: #1a1f2e; margin-bottom: 8px; }
.step p { font-size: 14px; color: #52514E; line-height: 1.5; }

/* ---- PLANOS ---- */
.plans-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px; max-width: 720px; margin: 0 auto; }
.plan-card { border-radius: 20px; padding: 36px; border: 2px solid #E0E0DE; background: #fff; position: relative; }
.plan-card.featured { border-color: #FCBF49; box-shadow: 0 20px 60px rgba(252,191,73,0.15); }
.plan-badge {
    position: absolute; top: -14px; left: 50%; transform: translateX(-50%);
    background: #FCBF49; color: #003049; font-size: 11px; font-weight: 700;
    letter-spacing: .5px; text-transform: uppercase;
    padding: 4px 16px; border-radius: 100px;
}
.plan-name { font-size: 22px; font-weight: 800; color: #1a1f2e; margin-bottom: 4px; }
.plan-price { font-size: 40px; font-weight: 900; color: #003049; margin: 12px 0 4px; }
.plan-price span { font-size: 16px; font-weight: 500; color: #888; }
.plan-desc { font-size: 13px; color: #888; margin-bottom: 24px; }
.plan-features { list-style: none; display: flex; flex-direction: column; gap: 10px; margin-bottom: 28px; }
.plan-features li { display: flex; align-items: center; gap: 10px; font-size: 14px; color: #52514E; }
.plan-features li svg { flex-shrink: 0; }
.btn-plan-free {
    display: block; width: 100%; padding: 14px; border-radius: 12px; text-align: center;
    font-size: 15px; font-weight: 700; border: 2px solid #003049; color: #003049; transition: all .2s;
}
.btn-plan-free:hover { background: #003049; color: #fff; }
.btn-plan-pro {
    display: block; width: 100%; padding: 14px; border-radius: 12px; text-align: center;
    font-size: 15px; font-weight: 700; background: #D62828; color: #fff; transition: all .2s;
    box-shadow: 0 8px 24px rgba(214,40,40,0.3);
}
.btn-plan-pro:hover { background: #b91c1c; transform: translateY(-1px); }

/* ---- DEPOIMENTOS ---- */
.testimonials { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; }
.testimonial { background: #fff; border-radius: 16px; padding: 28px; border: 1px solid #E0E0DE; }
.testimonial-stars { color: #FCBF49; font-size: 18px; margin-bottom: 12px; }
.testimonial-text { font-size: 14px; color: #52514E; line-height: 1.6; margin-bottom: 20px; font-style: italic; }
.testimonial-author { display: flex; align-items: center; gap: 12px; }
.testimonial-avatar {
    width: 40px; height: 40px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 16px; font-weight: 700; color: #fff; flex-shrink: 0;
}
.testimonial-name { font-size: 14px; font-weight: 600; color: #1a1f2e; }
.testimonial-role { font-size: 12px; color: #888; }

/* ---- CTA BANNER ---- */
.cta-banner {
    background: linear-gradient(135deg, #D62828 0%, #F77F00 100%);
    border-radius: 24px; padding: 60px 40px; text-align: center;
    position: relative; overflow: hidden;
}
.cta-banner::before {
    content: ''; position: absolute; inset: 0;
    background: radial-gradient(ellipse 60% 80% at 80% 50%, rgba(255,255,255,0.12) 0%, transparent 70%);
}
.cta-banner h2 { font-size: clamp(24px, 4vw, 40px); font-weight: 900; color: #fff; margin-bottom: 16px; position: relative; }
.cta-banner p { font-size: 17px; color: rgba(255,255,255,0.85); margin-bottom: 36px; position: relative; }
.btn-cta-white {
    display: inline-flex; align-items: center; gap: 8px;
    background: #fff; color: #D62828; font-size: 16px; font-weight: 700;
    padding: 16px 36px; border-radius: 12px; transition: all .2s;
    box-shadow: 0 8px 24px rgba(0,0,0,0.15); position: relative;
}
.btn-cta-white:hover { transform: translateY(-2px); box-shadow: 0 12px 32px rgba(0,0,0,0.2); }

/* ---- LGPD / POLITICAS ---- */
.policies-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; }
.policy-card {
    background: #fff; border-radius: 16px; padding: 28px;
    border: 1px solid #E0E0DE; display: flex; flex-direction: column; gap: 12px;
}
.policy-icon {
    width: 44px; height: 44px; border-radius: 12px; background: rgba(0,48,73,0.08);
    display: flex; align-items: center; justify-content: center;
}
.policy-card h3 { font-size: 16px; font-weight: 700; color: #1a1f2e; }
.policy-card p { font-size: 13px; color: #52514E; line-height: 1.6; }

/* ---- FAQ ---- */
.faq-list { display: flex; flex-direction: column; gap: 12px; max-width: 720px; margin: 0 auto; }
.faq-item { background: #fff; border-radius: 14px; border: 1px solid #E0E0DE; overflow: hidden; }
.faq-question {
    width: 100%; text-align: left; padding: 20px 24px; font-size: 15px; font-weight: 600;
    color: #1a1f2e; background: none; border: none; cursor: pointer;
    display: flex; align-items: center; justify-content: space-between; gap: 12px;
}
.faq-answer { padding: 0 24px 20px; font-size: 14px; color: #52514E; line-height: 1.7; display: none; }
.faq-item.open .faq-answer { display: block; }
.faq-item.open .faq-chevron { transform: rotate(180deg); }
.faq-chevron { transition: transform .2s; flex-shrink: 0; }

/* ---- FOOTER ---- */
.footer { background: #003049; color: rgba(255,255,255,0.7); padding: 60px 24px 32px; }
.footer-grid { display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 40px; margin-bottom: 48px; }
@media(max-width: 768px) { .footer-grid { grid-template-columns: 1fr 1fr; } }
@media(max-width: 480px) { .footer-grid { grid-template-columns: 1fr; } }
.footer-brand-name { font-size: 22px; font-weight: 800; color: #fff; margin-bottom: 12px; }
.footer-brand-name span { color: #FCBF49; }
.footer-desc { font-size: 13px; line-height: 1.7; margin-bottom: 20px; }
.footer-social { display: flex; gap: 10px; }
.footer-social a {
    width: 36px; height: 36px; border-radius: 8px;
    background: rgba(255,255,255,0.08); display: flex; align-items: center; justify-content: center;
    color: rgba(255,255,255,0.6); transition: all .15s;
}
.footer-social a:hover { background: rgba(255,255,255,0.16); color: #fff; }
.footer-col h4 { font-size: 13px; font-weight: 700; color: #fff; letter-spacing: .5px; text-transform: uppercase; margin-bottom: 16px; }
.footer-col ul { list-style: none; display: flex; flex-direction: column; gap: 10px; }
.footer-col ul li a { font-size: 13px; color: rgba(255,255,255,0.6); transition: color .15s; }
.footer-col ul li a:hover { color: #FCBF49; }
.footer-bottom {
    border-top: 1px solid rgba(255,255,255,0.1);
    padding-top: 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;
}
.footer-bottom p { font-size: 12px; }
.footer-bottom-links { display: flex; gap: 20px; }
.footer-bottom-links a { font-size: 12px; color: rgba(255,255,255,0.5); transition: color .15s; }
.footer-bottom-links a:hover { color: rgba(255,255,255,0.8); }

/* ---- COOKIE BANNER ---- */
.cookie-banner {
    position: fixed; bottom: 0; left: 0; right: 0; z-index: 100;
    background: #1A1F2E; border-top: 2px solid #FCBF49;
    padding: 16px 24px; display: none; align-items: center; justify-content: space-between;
    gap: 16px; flex-wrap: wrap;
}
.cookie-banner.show { display: flex; }
.cookie-banner p { font-size: 13px; color: rgba(255,255,255,0.8); line-height: 1.5; flex: 1; min-width: 260px; }
.cookie-banner p a { color: #FCBF49; }
.cookie-btns { display: flex; gap: 8px; flex-shrink: 0; }
.btn-cookie-accept {
    padding: 8px 20px; border-radius: 8px; background: #FCBF49; color: #003049;
    font-size: 13px; font-weight: 700; cursor: pointer; border: none; transition: all .15s;
}
.btn-cookie-accept:hover { background: #f0ab30; }
.btn-cookie-reject {
    padding: 8px 16px; border-radius: 8px; background: transparent; color: rgba(255,255,255,0.6);
    font-size: 13px; font-weight: 500; cursor: pointer; border: 1px solid rgba(255,255,255,0.2); transition: all .15s;
}
.btn-cookie-reject:hover { color: #fff; border-color: rgba(255,255,255,0.4); }

/* ---- ERA DIGITAL ---- */
.era-digital {
    background: linear-gradient(160deg, #0a0e1a 0%, #001829 50%, #0a0e1a 100%);
    padding: 90px 24px;
    position: relative;
    overflow: hidden;
}
.era-digital::before {
    content: '';
    position: absolute; inset: 0;
    background:
        radial-gradient(ellipse 40% 50% at 20% 50%, rgba(247,127,0,0.07) 0%, transparent 60%),
        radial-gradient(ellipse 40% 50% at 80% 50%, rgba(214,40,40,0.07) 0%, transparent 60%);
}
.era-title {
    font-size: clamp(28px, 5vw, 52px);
    font-weight: 900; color: #fff;
    line-height: 1.15; letter-spacing: -1px; margin-bottom: 16px;
}
.era-title em { color: #FCBF49; font-style: normal; }
.era-sub { font-size: 17px; color: rgba(255,255,255,0.6); line-height: 1.6; max-width: 520px; }

/* Números impacto */
.impact-numbers {
    display: flex; gap: 0; margin-top: 52px;
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 20px; overflow: hidden;
    flex-wrap: wrap;
}
.impact-num {
    flex: 1; min-width: 160px;
    padding: 28px 24px; text-align: center;
    border-right: 1px solid rgba(255,255,255,0.08);
}
.impact-num:last-child { border-right: none; }
.impact-num-val { font-size: 36px; font-weight: 900; line-height: 1; }
.impact-num-desc { font-size: 12px; color: rgba(255,255,255,0.5); margin-top: 6px; line-height: 1.4; }

/* Comparativo Papel vs Digital */
.compare-grid {
    display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 52px;
}
@media(max-width: 640px) { .compare-grid { grid-template-columns: 1fr; } }
.compare-card {
    border-radius: 20px; overflow: hidden;
}
.compare-header {
    padding: 18px 24px; display: flex; align-items: center; gap: 10px;
}
.compare-header h3 { font-size: 15px; font-weight: 700; }
.compare-body { padding: 0 24px 24px; display: flex; flex-direction: column; gap: 0; }
.compare-row {
    display: flex; align-items: flex-start; gap: 10px;
    padding: 11px 0; border-bottom: 1px solid rgba(255,255,255,0.06);
    font-size: 13px; line-height: 1.4;
}
.compare-row:last-child { border-bottom: none; }
.compare-dot {
    width: 18px; height: 18px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 1px;
}

/* Card papel (negativo) */
.compare-paper { background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); }
.compare-paper .compare-header { background: rgba(255,255,255,0.04); }
.compare-paper .compare-header h3 { color: rgba(255,255,255,0.5); }
.compare-paper .compare-row { color: rgba(255,255,255,0.45); }
.compare-paper .compare-dot { background: rgba(214,40,40,0.2); }

/* Card digital (positivo) */
.compare-digital { background: rgba(0,48,73,0.5); border: 1px solid rgba(252,191,73,0.2); }
.compare-digital .compare-header { background: rgba(252,191,73,0.08); border-bottom: 1px solid rgba(252,191,73,0.12); }
.compare-digital .compare-header h3 { color: #FCBF49; }
.compare-digital .compare-row { color: rgba(255,255,255,0.82); }
.compare-digital .compare-dot { background: rgba(22,163,74,0.25); }

/* COMPARTILHAMENTO */
.share-section {
    padding: 80px 24px;
    background: #fff;
}
.share-methods {
    display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px;
    margin-top: 48px;
}
.share-method {
    border-radius: 20px; padding: 28px 20px; text-align: center;
    border: 1.5px solid #E0E0DE; transition: all .2s; cursor: default;
    background: #fff;
}
.share-method:hover { transform: translateY(-4px); border-color: transparent; box-shadow: 0 16px 48px rgba(0,0,0,0.1); }
.share-method-icon {
    width: 56px; height: 56px; border-radius: 16px; margin: 0 auto 16px;
    display: flex; align-items: center; justify-content: center;
}
.share-method h4 { font-size: 14px; font-weight: 700; color: #1a1f2e; margin-bottom: 6px; }
.share-method p { font-size: 12px; color: #888; line-height: 1.5; }

/* Barra de URL tappable */
.share-url-bar {
    display: flex; align-items: center; gap: 12px;
    background: #F8F8F7; border: 1.5px solid #E0E0DE; border-radius: 14px;
    padding: 14px 18px; margin-top: 40px; max-width: 480px; margin-left: auto; margin-right: auto;
}
.share-url-text { flex: 1; font-size: 15px; font-weight: 600; color: #003049; }
.share-url-btn {
    padding: 8px 18px; border-radius: 10px; background: #003049; color: #FCBF49;
    font-size: 13px; font-weight: 700; cursor: pointer; border: none; white-space: nowrap;
    display: flex; align-items: center; gap: 6px; transition: all .15s;
}
.share-url-btn:hover { background: #002035; }

/* Benefícios rápidos */
.benefits-strip {
    display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 0; margin-top: 64px;
    background: linear-gradient(135deg, #003049, #005073);
    border-radius: 24px; overflow: hidden;
}
.benefit-item {
    padding: 32px 28px; border-right: 1px solid rgba(255,255,255,0.08);
    display: flex; flex-direction: column; gap: 12px;
}
.benefit-item:last-child { border-right: none; }
@media(max-width: 640px) {
    .benefit-item { border-right: none; border-bottom: 1px solid rgba(255,255,255,0.08); }
    .benefit-item:last-child { border-bottom: none; }
}
.benefit-icon {
    width: 40px; height: 40px; border-radius: 12px; background: rgba(255,255,255,0.1);
    display: flex; align-items: center; justify-content: center;
}
.benefit-item h4 { font-size: 14px; font-weight: 700; color: #fff; }
.benefit-item p { font-size: 12px; color: rgba(255,255,255,0.6); line-height: 1.5; }

/* ---- RESPONSIVE ---- */
@media(max-width: 640px) {
    .navbar-brand { font-size: 18px; }
    .hero { padding: 60px 16px 0; }
    .section { padding: 60px 16px; }
    .hero-stats { gap: 24px; }
    .cta-banner { padding: 40px 24px; }
}
</style>
</head>
<body>

<!-- ═══ NAVBAR ═══ -->
<nav class="navbar">
    <div class="navbar-brand">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" style="color:#FCBF49">
            <line x1="5" y1="5"  x2="5"  y2="19" stroke="currentColor" stroke-width="2.25" stroke-linecap="round"/>
            <line x1="5" y1="5"  x2="19" y2="19" stroke="currentColor" stroke-width="2.25" stroke-linecap="round"/>
            <line x1="19" y1="5" x2="19" y2="19" stroke="currentColor" stroke-width="2.25" stroke-linecap="round"/>
            <circle cx="5"  cy="5"  r="2.75" fill="currentColor"/>
            <circle cx="5"  cy="19" r="2.75" fill="currentColor"/>
            <circle cx="19" cy="5"  r="2.75" fill="currentColor"/>
            <circle cx="19" cy="19" r="2.75" fill="currentColor"/>
        </svg>
        NEX<span style="opacity:.55;">OSN</span><span>.</span>
    </div>
    <div class="navbar-links">
        <a href="#funcionalidades" class="btn-nav-ghost" style="display:none" id="nav-links-desktop">Funcionalidades</a>
        <a href="#planos" class="btn-nav-ghost">Planos</a>
        @if (Route::has('login'))
            @auth
            <a href="/dashboard" class="btn-nav-ghost">Painel</a>
            @else
            <a href="{{ route('login') }}" class="btn-nav-ghost">Entrar</a>
            <a href="{{ route('register') }}" class="btn-nav-cta">Criar grátis</a>
            @endauth
        @endif
    </div>
</nav>

<!-- ═══ HERO ═══ -->
<section class="hero">
    <div class="hero-badge">
        <svg data-lucide="sparkles" style="width:12px;height:12px;"></svg>
        Novo · Agenda integrada
    </div>

    <h1>Sua identidade digital<br><em>única e profissional</em></h1>
    <p>Crie seu perfil com links, agenda, PIX, QR Code, galeria e muito mais. Um único link para tudo que você é — sem papel, sem limite.</p>

    <div class="hero-btns">
        <a href="{{ route('register') }}" class="btn-hero-primary">
            <svg data-lucide="zap" style="width:18px;height:18px;"></svg>
            Criar minha identidade grátis
        </a>
        <a href="#planos" class="btn-hero-secondary">
            <svg data-lucide="star" style="width:18px;height:18px;"></svg>
            Ver planos
        </a>
    </div>

    <div class="hero-stats">
        <div class="hero-stat">
            <div class="hero-stat-num">100%</div>
            <div class="hero-stat-label">Digital</div>
        </div>
        <div class="hero-stat">
            <div class="hero-stat-num">14 dias</div>
            <div class="hero-stat-label">Trial Pro grátis</div>
        </div>
        <div class="hero-stat">
            <div class="hero-stat-num">1 link</div>
            <div class="hero-stat-label">Para tudo</div>
        </div>
        <div class="hero-stat">
            <div class="hero-stat-num">QR Code</div>
            <div class="hero-stat-label">Incluso</div>
        </div>
    </div>

    <!-- Mockup multi-cartão -->
    <div class="mockup-scene">

        <!-- ── CARTÃO 1: AGENDA ── -->
        <div class="mk-card mk-card-side" style="position:relative;">
            <div class="mk-badge-float">
                <svg data-lucide="calendar" style="width:8px;height:8px;display:inline;vertical-align:middle;margin-right:3px;"></svg>
                Agenda Pro
            </div>
            <div class="mk-calendar-header">
                <div>
                    <div class="mk-cal-title">Julho 2026</div>
                    <div class="mk-cal-sub">Escolha um horário</div>
                </div>
                <svg data-lucide="chevron-right" style="width:14px;height:14px;color:#003049;"></svg>
            </div>
            <!-- Cabeçalho dias da semana -->
            <div class="mk-cal-grid" style="padding-bottom:2px;">
                <div class="mk-cal-day" style="color:#003049;font-weight:700;font-size:8px;">D</div>
                <div class="mk-cal-day" style="color:#003049;font-weight:700;font-size:8px;">S</div>
                <div class="mk-cal-day" style="color:#003049;font-weight:700;font-size:8px;">T</div>
                <div class="mk-cal-day" style="color:#003049;font-weight:700;font-size:8px;">Q</div>
                <div class="mk-cal-day" style="color:#003049;font-weight:700;font-size:8px;">Q</div>
                <div class="mk-cal-day" style="color:#003049;font-weight:700;font-size:8px;">S</div>
                <div class="mk-cal-day" style="color:#003049;font-weight:700;font-size:8px;">S</div>
            </div>
            <div class="mk-cal-grid">
                <div class="mk-cal-day"></div>
                <div class="mk-cal-day has-slot">1</div>
                <div class="mk-cal-day has-slot">2</div>
                <div class="mk-cal-day">3</div>
                <div class="mk-cal-day has-slot">4</div>
                <div class="mk-cal-day">5</div>
                <div class="mk-cal-day">6</div>
                <div class="mk-cal-day has-slot">7</div>
                <div class="mk-cal-day has-slot">8</div>
                <div class="mk-cal-day">9</div>
                <div class="mk-cal-day has-slot">10</div>
                <div class="mk-cal-day">11</div>
                <div class="mk-cal-day">12</div>
                <div class="mk-cal-day">13</div>
                <div class="mk-cal-day has-slot">14</div>
                <div class="mk-cal-day today">15</div>
                <div class="mk-cal-day has-slot">16</div>
                <div class="mk-cal-day has-slot">17</div>
                <div class="mk-cal-day selected">18</div>
                <div class="mk-cal-day">19</div>
                <div class="mk-cal-day">20</div>
                <div class="mk-cal-day has-slot">21</div>
            </div>
            <div class="mk-slots-label">Horários — Sex 18/07</div>
            <div class="mk-slots">
                <div class="mk-slot booked">08:00</div>
                <div class="mk-slot">09:00</div>
                <div class="mk-slot selected">10:00</div>
                <div class="mk-slot">11:00</div>
                <div class="mk-slot booked">14:00</div>
                <div class="mk-slot">15:00</div>
                <div class="mk-slot">16:00</div>
            </div>
            <div class="mk-stats">
                <div class="mk-stat">
                    <div class="mk-stat-num" style="color:#16a34a;">12</div>
                    <div class="mk-stat-lbl">Confirmados</div>
                </div>
                <div class="mk-stat">
                    <div class="mk-stat-num" style="color:#F77F00;">3</div>
                    <div class="mk-stat-lbl">Pendentes</div>
                </div>
            </div>
        </div>

        <!-- ── CARTÃO 2: PERFIL PRINCIPAL ── -->
        <div class="mk-card mk-card-main" style="position:relative;">
            <div class="mk-badge-float" style="background:#D62828;color:#fff;box-shadow:0 4px 10px rgba(214,40,40,0.5);">
                <svg data-lucide="star" style="width:8px;height:8px;display:inline;vertical-align:middle;margin-right:3px;"></svg>
                Pro
            </div>
            <!-- Capa com gradiente personalizado -->
            <div class="mk-cover mk-cover-main" style="background:linear-gradient(135deg,#003049,#0a4f70,#005073);">
                <!-- Foto capa pattern -->
                <div style="position:absolute;inset:0;opacity:0.3;background:repeating-linear-gradient(45deg,transparent,transparent 8px,rgba(255,255,255,0.05) 8px,rgba(255,255,255,0.05) 16px);"></div>
                <div class="mk-avatar mk-avatar-main" style="background:linear-gradient(135deg,#FCBF49,#F77F00);">A</div>
            </div>
            <div class="mk-body mk-body-main">
                <div class="mk-name mk-name-main">Ana Paula Costa</div>
                <div class="mk-role">Nutricionista · CRN-6 12345</div>
                <div class="mk-bio">Especialista em nutrição esportiva e emagrecimento. Consultas presenciais e online.</div>

                <!-- Galeria de fotos mini -->
                <div class="mk-photos" style="margin:12px 0 8px;">
                    <div class="mk-photo" style="background:linear-gradient(135deg,#667eea,#764ba2);"></div>
                    <div class="mk-photo" style="background:linear-gradient(135deg,#f093fb,#f5576c);"></div>
                    <div class="mk-photo" style="background:linear-gradient(135deg,#4facfe,#00f2fe);"></div>
                    <div class="mk-photo" style="background:linear-gradient(135deg,#43e97b,#38f9d7);"></div>
                </div>
                <div style="font-size:9px;color:#aaa;text-align:right;margin-bottom:8px;">+26 fotos na galeria</div>

                <div class="mk-divider"></div>

                <!-- Informações de contato -->
                <div style="text-align:left;">
                    <div class="mk-contact-row">
                        <svg data-lucide="phone" style="width:11px;height:11px;color:#003049;flex-shrink:0;"></svg>
                        <span>(69) 99999-1234</span>
                    </div>
                    <div class="mk-contact-row">
                        <svg data-lucide="mail" style="width:11px;height:11px;color:#003049;flex-shrink:0;"></svg>
                        <span>ana@nutrição.com.br</span>
                    </div>
                    <div class="mk-contact-row" style="border:none;">
                        <svg data-lucide="map-pin" style="width:11px;height:11px;color:#003049;flex-shrink:0;"></svg>
                        <span>Porto Velho, RO</span>
                    </div>
                </div>

                <div class="mk-divider"></div>

                <!-- Links sociais -->
                <div class="mk-links">
                    <div class="mk-link" style="background:#003049;">
                        <svg data-lucide="camera" style="width:12px;height:12px;"></svg>
                        @anapaula.nutri
                    </div>
                    <div class="mk-link" style="background:#16a34a;">
                        <svg data-lucide="message-circle" style="width:12px;height:12px;"></svg>
                        WhatsApp — Agendar
                    </div>
                    <div class="mk-link" style="background:#0077b5;">
                        <svg data-lucide="briefcase" style="width:12px;height:12px;"></svg>
                        LinkedIn
                    </div>
                </div>

                <!-- PIX -->
                <div class="mk-link" style="background:linear-gradient(90deg,#F77F00,#FCBF49);color:#003049;margin-top:6px;font-weight:800;">
                    <svg data-lucide="zap" style="width:12px;height:12px;"></svg>
                    Pagar via PIX
                </div>

                <!-- Salvar contato -->
                <div class="mk-link-outline" style="margin-top:6px;">
                    <svg data-lucide="user-plus" style="width:12px;height:12px;"></svg>
                    Salvar contato
                </div>
            </div>

            <!-- Stats no rodapé -->
            <div class="mk-stats">
                <div class="mk-stat">
                    <div class="mk-stat-num">1.2k</div>
                    <div class="mk-stat-lbl">Visitas</div>
                </div>
                <div class="mk-stat">
                    <div class="mk-stat-num">348</div>
                    <div class="mk-stat-lbl">Cliques</div>
                </div>
                <div class="mk-stat">
                    <div class="mk-stat-num" style="color:#FCBF49;">Pro</div>
                    <div class="mk-stat-lbl">Plano</div>
                </div>
            </div>
        </div>

        <!-- ── CARTÃO 3: FORMULÁRIO DE CONTATO ── -->
        <div class="mk-card mk-card-side" style="position:relative;">
            <div class="mk-badge-float" style="background:#16a34a;color:#fff;box-shadow:0 4px 10px rgba(22,163,74,0.5);">
                <svg data-lucide="mail" style="width:8px;height:8px;display:inline;vertical-align:middle;margin-right:3px;"></svg>
                Mensagem
            </div>
            <div class="mk-form-header">
                <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,#FCBF49,#F77F00);display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:800;color:#fff;flex-shrink:0;">A</div>
                <div>
                    <div class="mk-form-title">Falar com Ana Paula</div>
                    <div style="font-size:9px;color:#aaa;">Responde em até 24h</div>
                </div>
            </div>
            <div class="mk-form-body">
                <div class="mk-input filled">Carlos Eduardo</div>
                <div class="mk-input filled">carlos@email.com</div>
                <div class="mk-input filled">(69) 98888-0000</div>
                <div class="mk-textarea filled">Olá! Gostaria de agendar uma consulta de avaliação nutricional...</div>
                <div class="mk-btn-send">
                    <svg data-lucide="send" style="width:11px;height:11px;"></svg>
                    Enviar mensagem
                </div>
            </div>
            <div class="mk-divider" style="margin:0;"></div>

            <!-- QR Code mini -->
            <div style="padding:14px;text-align:center;">
                <div style="font-size:9px;color:#aaa;text-transform:uppercase;letter-spacing:.4px;margin-bottom:8px;font-weight:700;">Compartilhar perfil</div>
                <div style="display:inline-block;padding:10px;background:#f8f8f7;border-radius:10px;border:1px solid #eee;">
                    <svg viewBox="0 0 60 60" width="60" height="60">
                        <!-- QR Code simulado -->
                        <rect fill="#fff" width="60" height="60"/>
                        <rect fill="#003049" x="2" y="2" width="18" height="18" rx="2"/>
                        <rect fill="#fff" x="5" y="5" width="12" height="12" rx="1"/>
                        <rect fill="#003049" x="8" y="8" width="6" height="6"/>
                        <rect fill="#003049" x="40" y="2" width="18" height="18" rx="2"/>
                        <rect fill="#fff" x="43" y="5" width="12" height="12" rx="1"/>
                        <rect fill="#003049" x="46" y="8" width="6" height="6"/>
                        <rect fill="#003049" x="2" y="40" width="18" height="18" rx="2"/>
                        <rect fill="#fff" x="5" y="43" width="12" height="12" rx="1"/>
                        <rect fill="#003049" x="8" y="46" width="6" height="6"/>
                        <rect fill="#003049" x="24" y="2" width="4" height="4"/>
                        <rect fill="#003049" x="30" y="2" width="4" height="4"/>
                        <rect fill="#003049" x="24" y="8" width="8" height="4"/>
                        <rect fill="#003049" x="24" y="24" width="4" height="12"/>
                        <rect fill="#003049" x="30" y="28" width="4" height="4"/>
                        <rect fill="#003049" x="36" y="24" width="4" height="8"/>
                        <rect fill="#003049" x="44" y="24" width="4" height="4"/>
                        <rect fill="#003049" x="50" y="24" width="8" height="4"/>
                        <rect fill="#003049" x="44" y="30" width="4" height="4"/>
                        <rect fill="#003049" x="50" y="32" width="8" height="4"/>
                        <rect fill="#003049" x="36" y="36" width="4" height="4"/>
                        <rect fill="#003049" x="42" y="38" width="4" height="4"/>
                        <rect fill="#003049" x="50" y="38" width="8" height="4"/>
                        <rect fill="#003049" x="44" y="44" width="4" height="4"/>
                        <rect fill="#003049" x="2" y="24" width="4" height="4"/>
                        <rect fill="#003049" x="8" y="24" width="4" height="8"/>
                        <rect fill="#003049" x="14" y="26" width="4" height="6"/>
                    </svg>
                </div>
                <div style="font-size:9px;color:#003049;font-weight:600;margin-top:6px;">nexosn.pageup.net.br/u/anapaula</div>
                <div style="display:flex;gap:6px;justify-content:center;margin-top:8px;">
                    <div style="padding:4px 8px;border-radius:6px;background:rgba(0,48,73,0.08);font-size:9px;font-weight:700;color:#003049;display:flex;align-items:center;gap:3px;">
                        <svg data-lucide="download" style="width:9px;height:9px;"></svg> PNG
                    </div>
                    <div style="padding:4px 8px;border-radius:6px;background:rgba(0,48,73,0.08);font-size:9px;font-weight:700;color:#003049;display:flex;align-items:center;gap:3px;">
                        <svg data-lucide="share-2" style="width:9px;height:9px;"></svg> Compartilhar
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- ═══ MISSÃO ═══ -->
<section style="padding: 80px 20px; background: #003049; position: relative; overflow: hidden;">
    <!-- Textura sutil de fundo -->
    <div style="position:absolute;inset:0;opacity:.04;background-image:radial-gradient(circle at 1px 1px, #fff 1px, transparent 0);background-size:28px 28px;pointer-events:none;"></div>

    <div style="max-width:780px; margin:0 auto; text-align:center; position:relative;">

        <!-- Ícone N-monogram -->
        <div style="display:inline-flex;align-items:center;justify-content:center;width:52px;height:52px;border-radius:14px;background:rgba(252,191,73,0.12);border:1px solid rgba(252,191,73,0.2);margin-bottom:28px;">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" style="color:#FCBF49;">
                <line x1="5" y1="5"  x2="5"  y2="19" stroke="currentColor" stroke-width="2.25" stroke-linecap="round"/>
                <line x1="5" y1="5"  x2="19" y2="19" stroke="currentColor" stroke-width="2.25" stroke-linecap="round"/>
                <line x1="19" y1="5" x2="19" y2="19" stroke="currentColor" stroke-width="2.25" stroke-linecap="round"/>
                <circle cx="5"  cy="5"  r="2.75" fill="currentColor"/>
                <circle cx="5"  cy="19" r="2.75" fill="currentColor"/>
                <circle cx="19" cy="5"  r="2.75" fill="currentColor"/>
                <circle cx="19" cy="19" r="2.75" fill="currentColor"/>
            </svg>
        </div>

        <!-- Eyebrow -->
        <div style="display:inline-block;font-size:11px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:#FCBF49;opacity:.8;margin-bottom:18px;">Nossa missão</div>

        <!-- Headline principal da missão -->
        <h2 style="font-size:clamp(26px,4.5vw,44px);font-weight:800;color:#ffffff;line-height:1.2;margin:0 0 24px;text-wrap:balance;">
            Conectar pessoas, empresas,<br>produtos e oportunidades —<br>
            <em style="font-style:normal;color:#FCBF49;">por meio de uma identidade digital única.</em>
        </h2>

        <!-- Corpo explicativo -->
        <p style="font-size:clamp(15px,1.8vw,18px);line-height:1.7;color:rgba(255,255,255,0.65);max-width:620px;margin:0 auto 36px;">
            A NEXOSN não é só um cartão digital. É uma plataforma de identidade inteligente, segura e acessível,
            pronta para acomodar tudo que você é hoje e tudo que você vai se tornar amanhã —
            sem papel, sem limite, sem complicação.
        </p>

        <!-- Três pilares da missão -->
        <div style="display:flex;flex-wrap:wrap;justify-content:center;gap:12px;">
            <div style="display:flex;align-items:center;gap:8px;background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);border-radius:100px;padding:8px 18px;">
                <svg data-lucide="link-2" style="width:14px;height:14px;color:#FCBF49;flex-shrink:0;"></svg>
                <span style="font-size:13px;font-weight:600;color:rgba(255,255,255,0.85);">Conexão real</span>
            </div>
            <div style="display:flex;align-items:center;gap:8px;background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);border-radius:100px;padding:8px 18px;">
                <svg data-lucide="shield-check" style="width:14px;height:14px;color:#FCBF49;flex-shrink:0;"></svg>
                <span style="font-size:13px;font-weight:600;color:rgba(255,255,255,0.85);">Identidade segura</span>
            </div>
            <div style="display:flex;align-items:center;gap:8px;background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);border-radius:100px;padding:8px 18px;">
                <svg data-lucide="sparkles" style="width:14px;height:14px;color:#FCBF49;flex-shrink:0;"></svg>
                <span style="font-size:13px;font-weight:600;color:rgba(255,255,255,0.85);">Inteligência digital</span>
            </div>
        </div>

    </div>
</section>

<!-- ═══ ERA DIGITAL ═══ -->
<section class="era-digital">
    <div class="container">

        <div style="text-align:center; position:relative;">
            <div class="section-tag" style="background:rgba(252,191,73,0.12); color:#FCBF49; border:1px solid rgba(252,191,73,0.2); margin-bottom:20px;">
                Era Digital
            </div>
            <h2 class="era-title">
                Por que <em>trocar</em> o papel<br>pelo digital agora?
            </h2>
            <p class="era-sub" style="margin:0 auto;">
                O mundo mudou. Seu cartão de visita também precisa mudar —
                ou você continua entregando papelzinho e torcendo para a pessoa não jogar fora.
            </p>
        </div>

        <!-- Números de impacto -->
        <div class="impact-numbers">
            <div class="impact-num">
                <div class="impact-num-val" style="color:#FCBF49;">88%</div>
                <div class="impact-num-desc">dos cartões de papel são descartados em menos de 1 semana</div>
            </div>
            <div class="impact-num">
                <div class="impact-num-val" style="color:#F77F00;">10 bi</div>
                <div class="impact-num-desc">de cartões físicos impressos todo ano — quase tudo no lixo</div>
            </div>
            <div class="impact-num">
                <div class="impact-num-val" style="color:#D62828;">R$ 0</div>
                <div class="impact-num-desc">de custo para compartilhar seu perfil digital mil vezes</div>
            </div>
            <div class="impact-num">
                <div class="impact-num-val" style="color:#22c55e;">3 seg</div>
                <div class="impact-num-desc">para enviar seu perfil completo por WhatsApp para qualquer pessoa</div>
            </div>
        </div>

        <!-- Comparativo Papel vs Digital -->
        <div class="compare-grid">

            <!-- Cartão de papel -->
            <div class="compare-card compare-paper">
                <div class="compare-header">
                    <svg data-lucide="file-x" style="width:20px;height:20px;color:rgba(255,255,255,0.35);flex-shrink:0;"></svg>
                    <h3>Cartão de papel</h3>
                </div>
                <div class="compare-body">
                    <div class="compare-row">
                        <div class="compare-dot">
                            <svg data-lucide="x" style="width:10px;height:10px;color:#D62828;"></svg>
                        </div>
                        <span>Custa R$ 0,30–R$ 2,00 por unidade — quanto mais distribui, mais gasta</span>
                    </div>
                    <div class="compare-row">
                        <div class="compare-dot">
                            <svg data-lucide="x" style="width:10px;height:10px;color:#D62828;"></svg>
                        </div>
                        <span>Muda de telefone? Reimprime tudo. Mudou de empresa? Lixo</span>
                    </div>
                    <div class="compare-row">
                        <div class="compare-dot">
                            <svg data-lucide="x" style="width:10px;height:10px;color:#D62828;"></svg>
                        </div>
                        <span>Não tem Instagram, WhatsApp, link ou botão — só texto estático</span>
                    </div>
                    <div class="compare-row">
                        <div class="compare-dot">
                            <svg data-lucide="x" style="width:10px;height:10px;color:#D62828;"></svg>
                        </div>
                        <span>Impossible saber quantas pessoas viram ou clicaram</span>
                    </div>
                    <div class="compare-row">
                        <div class="compare-dot">
                            <svg data-lucide="x" style="width:10px;height:10px;color:#D62828;"></svg>
                        </div>
                        <span>Precisa ter estoque. Esqueceu em casa? Perdeu o contato</span>
                    </div>
                    <div class="compare-row">
                        <div class="compare-dot">
                            <svg data-lucide="x" style="width:10px;height:10px;color:#D62828;"></svg>
                        </div>
                        <span>Amassa, rasga, fica ilegível, some na gaveta</span>
                    </div>
                </div>
            </div>

            <!-- Cartão Digital NEXOSN -->
            <div class="compare-card compare-digital">
                <div class="compare-header">
                    <svg data-lucide="credit-card" style="width:20px;height:20px;color:#FCBF49;flex-shrink:0;"></svg>
                    <h3>Identidade Digital — NEXOSN</h3>
                </div>
                <div class="compare-body">
                    <div class="compare-row">
                        <div class="compare-dot">
                            <svg data-lucide="check" style="width:10px;height:10px;color:#22c55e;"></svg>
                        </div>
                        <span>Grátis para sempre. Compartilhe com mil pessoas sem custo extra</span>
                    </div>
                    <div class="compare-row">
                        <div class="compare-dot">
                            <svg data-lucide="check" style="width:10px;height:10px;color:#22c55e;"></svg>
                        </div>
                        <span>Atualize em 30 segundos — todos recebem a versão nova automaticamente</span>
                    </div>
                    <div class="compare-row">
                        <div class="compare-dot">
                            <svg data-lucide="check" style="width:10px;height:10px;color:#22c55e;"></svg>
                        </div>
                        <span>Links clicáveis: Instagram, WhatsApp, YouTube, PIX, site, mapa...</span>
                    </div>
                    <div class="compare-row">
                        <div class="compare-dot">
                            <svg data-lucide="check" style="width:10px;height:10px;color:#22c55e;"></svg>
                        </div>
                        <span>Veja quantas visitas, cliques e mensagens você recebeu por dia</span>
                    </div>
                    <div class="compare-row">
                        <div class="compare-dot">
                            <svg data-lucide="check" style="width:10px;height:10px;color:#22c55e;"></svg>
                        </div>
                        <span>Está no celular, está na nuvem — sempre com você, nunca some</span>
                    </div>
                    <div class="compare-row">
                        <div class="compare-dot">
                            <svg data-lucide="check" style="width:10px;height:10px;color:#22c55e;"></svg>
                        </div>
                        <span>QR Code impresso ou tela — qualquer pessoa abre no celular</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- CTA dentro da seção -->
        <div style="text-align:center; margin-top:52px; position:relative;">
            <a href="{{ route('register') }}" class="btn-hero-primary" style="display:inline-flex;">
                <svg data-lucide="zap" style="width:18px;height:18px;"></svg>
                Criar minha identidade grátis
            </a>
            <p style="font-size:13px; color:rgba(255,255,255,0.4); margin-top:12px;">
                Sem cartão de crédito · 14 dias Pro grátis · Cancele quando quiser
            </p>
        </div>

    </div>
</section>

<!-- ═══ COMPARTILHAMENTO RÁPIDO ═══ -->
<section class="share-section">
    <div class="container">
        <div style="text-align:center;" class="section-header">
            <div class="section-tag">Compartilhamento</div>
            <h2 class="section-title">Compartilhe em segundos,<br>de qualquer lugar</h2>
            <p class="section-sub" style="margin:0 auto;">
                Seu perfil vai onde você vai. Envie por WhatsApp, mostre o QR Code,
                poste no Instagram — funciona em qualquer situação.
            </p>
        </div>

        <!-- Métodos de compartilhamento -->
        <div class="share-methods">

            <div class="share-method">
                <div class="share-method-icon" style="background:linear-gradient(135deg,#22c55e,#16a34a);">
                    <svg data-lucide="message-circle" style="width:26px;height:26px;color:#fff;"></svg>
                </div>
                <h4>WhatsApp</h4>
                <p>Cole o link numa conversa e pronto. A pessoa abre no celular sem instalar nada.</p>
            </div>

            <div class="share-method">
                <div class="share-method-icon" style="background:linear-gradient(135deg,#003049,#005073);">
                    <svg data-lucide="qr-code" style="width:26px;height:26px;color:#FCBF49;"></svg>
                </div>
                <h4>QR Code</h4>
                <p>Imprima na camisa, no produto, no banner ou mostre a tela do celular. Funciona offline.</p>
            </div>

            <div class="share-method">
                <div class="share-method-icon" style="background:linear-gradient(135deg,#E1306C,#833AB4);">
                    <svg data-lucide="camera" style="width:26px;height:26px;color:#fff;"></svg>
                </div>
                <h4>Stories / Instagram</h4>
                <p>Adicione seu link na bio ou compartilhe o QR Code nos stories. Alcance novos clientes.</p>
            </div>

            <div class="share-method">
                <div class="share-method-icon" style="background:linear-gradient(135deg,#0077b5,#0099cc);">
                    <svg data-lucide="mail" style="width:26px;height:26px;color:#fff;"></svg>
                </div>
                <h4>E-mail</h4>
                <p>Coloque seu link na assinatura do e-mail. Todo e-mail enviado vira oportunidade de negócio.</p>
            </div>

            <div class="share-method">
                <div class="share-method-icon" style="background:linear-gradient(135deg,#F77F00,#FCBF49);">
                    <svg data-lucide="link-2" style="width:26px;height:26px;color:#003049;"></svg>
                </div>
                <h4>Link direto</h4>
                <p>Um link curto e memorável. Fale em voz alta, digitável em 5 segundos.</p>
            </div>

            <div class="share-method">
                <div class="share-method-icon" style="background:linear-gradient(135deg,#7c3aed,#a855f7);">
                    <svg data-lucide="share-2" style="width:26px;height:26px;color:#fff;"></svg>
                </div>
                <h4>Compartilhar nativo</h4>
                <p>Botão de compartilhar no perfil abre o painel do celular — WhatsApp, Telegram, cópia e mais.</p>
            </div>

        </div>

        <!-- Barra de URL demo -->
        <div class="share-url-bar">
            <svg data-lucide="link" style="width:18px;height:18px;color:#003049;flex-shrink:0;"></svg>
            <span class="share-url-text">nexosn.pageup.net.br/u/<strong>seunome</strong></span>
            <button class="share-url-btn" onclick="democopiar(this)">
                <svg data-lucide="copy" style="width:14px;height:14px;"></svg>
                Copiar
            </button>
        </div>

        <!-- Benefícios visuais em strip -->
        <div class="benefits-strip">
            <div class="benefit-item">
                <div class="benefit-icon">
                    <svg data-lucide="refresh-cw" style="width:20px;height:20px;color:#FCBF49;"></svg>
                </div>
                <h4>Sempre atualizado</h4>
                <p>Mudou o número? Atualize e todos que acessarem já veem o novo — sem reimprimir nada.</p>
            </div>
            <div class="benefit-item">
                <div class="benefit-icon">
                    <svg data-lucide="smartphone" style="width:20px;height:20px;color:#FCBF49;"></svg>
                </div>
                <h4>Abre em qualquer celular</h4>
                <p>iPhone, Android, Samsung, Motorola — funciona em qualquer navegador, sem app.</p>
            </div>
            <div class="benefit-item">
                <div class="benefit-icon">
                    <svg data-lucide="leaf" style="width:20px;height:20px;color:#22c55e;"></svg>
                </div>
                <h4>Sustentável</h4>
                <p>Zero papel, zero tinta, zero desperdício. Bom para o bolso e para o planeta.</p>
            </div>
            <div class="benefit-item">
                <div class="benefit-icon">
                    <svg data-lucide="bar-chart-2" style="width:20px;height:20px;color:#FCBF49;"></svg>
                </div>
                <h4>Métricas reais</h4>
                <p>Saiba quantas pessoas viram seu perfil, quais links clicaram e de onde vieram.</p>
            </div>
            <div class="benefit-item">
                <div class="benefit-icon">
                    <svg data-lucide="shield-check" style="width:20px;height:20px;color:#FCBF49;"></svg>
                </div>
                <h4>Impressão profissional</h4>
                <p>Clientes percebem que você é moderno e acompanha as tendências do mercado.</p>
            </div>
        </div>

    </div>
</section>

<!-- ═══ FUNCIONALIDADES ═══ -->
<section class="section" id="funcionalidades">
    <div class="container">
        <div class="section-header">
            <div class="section-tag">Funcionalidades</div>
            <h2 class="section-title">Tudo que você precisa<br>em um único perfil</h2>
            <p class="section-sub">Do básico ao profissional — sem complicação, sem necessidade de conhecimento técnico.</p>
        </div>

        <div class="features-grid">

            <div class="feature-card fc-blue">
                <div class="feature-card-icon" style="background:rgba(255,255,255,0.15);">
                    <svg data-lucide="credit-card" style="width:22px;height:22px;color:#FCBF49;"></svg>
                </div>
                <h3>Perfil Digital Completo</h3>
                <p>Nome, foto, cargo, empresa, bio, links e contatos em um único endereço. Compartilhe via link ou QR Code em segundos.</p>
            </div>

            <div class="feature-card fc-red">
                <div class="feature-card-icon" style="background:rgba(255,255,255,0.15);">
                    <svg data-lucide="link" style="width:22px;height:22px;color:#fff;"></svg>
                </div>
                <h3>Links Ilimitados (Pro)</h3>
                <p>Adicione Instagram, WhatsApp, LinkedIn, YouTube, site próprio e qualquer outro link. Detectamos a rede automaticamente.</p>
            </div>

            <div class="feature-card fc-orange">
                <div class="feature-card-icon" style="background:rgba(0,48,73,0.15);">
                    <svg data-lucide="calendar" style="width:22px;height:22px;color:#003049;"></svg>
                </div>
                <h3>Agenda de Atendimentos</h3>
                <p>Configure seus horários disponíveis e receba solicitações de agendamento diretamente no seu perfil. Confirme com um clique.</p>
            </div>

            <div class="feature-card fc-green">
                <div class="feature-card-icon" style="background:rgba(255,255,255,0.15);">
                    <svg data-lucide="qr-code" style="width:22px;height:22px;color:#fff;"></svg>
                </div>
                <h3>QR Code + PIX</h3>
                <p>QR Code do seu perfil incluso (PNG e SVG). Chave PIX integrada para receber pagamentos direto pelo seu perfil.</p>
            </div>

            <div class="feature-card fc-purple">
                <div class="feature-card-icon" style="background:rgba(255,255,255,0.15);">
                    <svg data-lucide="palette" style="width:22px;height:22px;color:#fff;"></svg>
                </div>
                <h3>Cores de Marca (Pro)</h3>
                <p>Personalize as cores do seu perfil para combinar com a identidade visual da sua marca. Header, botões e muito mais.</p>
            </div>

            <div class="feature-card fc-teal">
                <div class="feature-card-icon" style="background:rgba(255,255,255,0.15);">
                    <svg data-lucide="image" style="width:22px;height:22px;color:#fff;"></svg>
                </div>
                <h3>Galeria de Fotos</h3>
                <p>Mostre seu trabalho com até 30 fotos no plano Pro. Portfólio, produtos, serviços — tudo no seu perfil digital.</p>
            </div>

            <div class="feature-card" style="background:linear-gradient(135deg,#1A1F2E,#2d3548);color:#fff;">
                <div class="feature-card-icon" style="background:rgba(255,255,255,0.1);">
                    <svg data-lucide="user-plus" style="width:22px;height:22px;color:#FCBF49;"></svg>
                </div>
                <h3>Salvar Contato (vCard)</h3>
                <p>Visitantes podem salvar seu contato direto na agenda do celular com um toque. Compatível com iPhone e Android.</p>
            </div>

            <div class="feature-card" style="background:linear-gradient(135deg,#F77F00,#D62828);color:#fff;">
                <div class="feature-card-icon" style="background:rgba(255,255,255,0.15);">
                    <svg data-lucide="message-square" style="width:22px;height:22px;color:#fff;"></svg>
                </div>
                <h3>Formulário de Contato</h3>
                <p>Receba mensagens dos visitantes do seu perfil diretamente no painel e por e-mail. Com proteção anti-spam integrada.</p>
            </div>

            <div class="feature-card" style="background:linear-gradient(135deg,#0ea5e9,#0284c7);color:#fff;">
                <div class="feature-card-icon" style="background:rgba(255,255,255,0.15);">
                    <svg data-lucide="bar-chart-2" style="width:22px;height:22px;color:#fff;"></svg>
                </div>
                <h3>Métricas e Visualizações</h3>
                <p>Acompanhe quantas vezes seu perfil foi visualizado, quais links foram clicados e muito mais no painel de controle.</p>
            </div>

        </div>
    </div>
</section>

<!-- ═══ COMO FUNCIONA ═══ -->
<section class="section section-alt">
    <div class="container">
        <div class="section-header" style="text-align:center;">
            <div class="section-tag">Como funciona</div>
            <h2 class="section-title">Pronto em 3 passos simples</h2>
            <p class="section-sub" style="margin:0 auto;">Sem precisar de designer, programador ou conhecimento técnico.</p>
        </div>

        <div class="steps">
            <div class="step">
                <div class="step-num">1</div>
                <h3>Crie sua conta</h3>
                <p>Cadastre-se em menos de 1 minuto. Escolha seu endereço exclusivo (nexosn.pageup.net.br/u/<strong>seunome</strong>) e ative 14 dias grátis do Pro.</p>
            </div>
            <div class="step">
                <div class="step-num">2</div>
                <h3>Monte seu perfil</h3>
                <p>Adicione foto, bio, links de redes sociais, WhatsApp, PIX e configure sua agenda — tudo pelo painel intuitivo.</p>
            </div>
            <div class="step">
                <div class="step-num">3</div>
                <h3>Compartilhe com o mundo</h3>
                <p>Envie seu link por WhatsApp, e-mail, Instagram ou imprima seu QR Code. Seus clientes acessam no celular sem instalar nada.</p>
            </div>
        </div>

        <div style="text-align:center; margin-top: 48px;">
            <a href="{{ route('register') }}" class="btn-hero-primary" style="display:inline-flex;">
                <svg data-lucide="arrow-right" style="width:18px;height:18px;"></svg>
                Começar agora — é grátis
            </a>
        </div>
    </div>
</section>

<!-- ═══ PLANOS ═══ -->
<section class="section" id="planos">
    <div class="container">
        <div class="section-header" style="text-align:center;">
            <div class="section-tag">Planos e Preços</div>
            <h2 class="section-title">Simples e transparente</h2>
            <p class="section-sub" style="margin:0 auto;">Comece grátis. Faça upgrade quando precisar de mais.</p>
        </div>

        <div class="plans-grid">

            <!-- Free -->
            <div class="plan-card">
                <div class="plan-name">Free</div>
                <div class="plan-price">R$ 0<span>/mês</span></div>
                <div class="plan-desc">Para começar sem custo</div>
                <ul class="plan-features">
                    <li>
                        <svg data-lucide="check" style="width:16px;height:16px;color:#16a34a;"></svg>
                        1 perfil digital
                    </li>
                    <li>
                        <svg data-lucide="check" style="width:16px;height:16px;color:#16a34a;"></svg>
                        Até 5 links
                    </li>
                    <li>
                        <svg data-lucide="check" style="width:16px;height:16px;color:#16a34a;"></svg>
                        QR Code incluso
                    </li>
                    <li>
                        <svg data-lucide="check" style="width:16px;height:16px;color:#16a34a;"></svg>
                        PIX integrado
                    </li>
                    <li>
                        <svg data-lucide="check" style="width:16px;height:16px;color:#16a34a;"></svg>
                        Salvar contato (vCard)
                    </li>
                    <li>
                        <svg data-lucide="x" style="width:16px;height:16px;color:#ccc;"></svg>
                        <span style="color:#aaa;">Cores personalizadas</span>
                    </li>
                    <li>
                        <svg data-lucide="x" style="width:16px;height:16px;color:#ccc;"></svg>
                        <span style="color:#aaa;">Agenda de atendimentos</span>
                    </li>
                    <li>
                        <svg data-lucide="credit-card" style="width:16px;height:16px;color:#ccc;"></svg>
                        <span style="color:#aaa;">Marca d'água obrigatória</span>
                    </li>
                </ul>
                <a href="{{ route('register') }}" class="btn-plan-free">Criar conta grátis</a>
            </div>

            <!-- Pro -->
            <div class="plan-card featured">
                <div class="plan-badge">⭐ Recomendado</div>
                <div class="plan-name">Pro</div>
                <div class="plan-price">R$ 19<span style="font-size:28px;font-weight:900;">,90</span><span>/mês</span></div>
                <div class="plan-desc">ou R$ 179,90/ano — economize 25%</div>
                <ul class="plan-features">
                    <li>
                        <svg data-lucide="check" style="width:16px;height:16px;color:#16a34a;"></svg>
                        Tudo do Free
                    </li>
                    <li>
                        <svg data-lucide="check" style="width:16px;height:16px;color:#16a34a;"></svg>
                        Links <strong>ilimitados</strong>
                    </li>
                    <li>
                        <svg data-lucide="check" style="width:16px;height:16px;color:#16a34a;"></svg>
                        Cores de marca personalizadas
                    </li>
                    <li>
                        <svg data-lucide="check" style="width:16px;height:16px;color:#16a34a;"></svg>
                        Agenda de atendimentos
                    </li>
                    <li>
                        <svg data-lucide="check" style="width:16px;height:16px;color:#16a34a;"></svg>
                        Galeria com 30 fotos
                    </li>
                    <li>
                        <svg data-lucide="check" style="width:16px;height:16px;color:#16a34a;"></svg>
                        Sem marca d'água
                    </li>
                    <li>
                        <svg data-lucide="check" style="width:16px;height:16px;color:#16a34a;"></svg>
                        14 dias grátis para testar
                    </li>
                </ul>
                <a href="{{ route('register') }}" class="btn-plan-pro">
                    Começar 14 dias grátis
                </a>
                <p style="font-size:11px;color:#888;text-align:center;margin-top:10px;">Sem cartão de crédito para o trial</p>
            </div>

        </div>
    </div>
</section>

<!-- ═══ DEPOIMENTOS ═══ -->
<section class="section section-alt">
    <div class="container">
        <div class="section-header" style="text-align:center;">
            <div class="section-tag">Depoimentos</div>
            <h2 class="section-title">Quem já usa a NEXOSN</h2>
        </div>

        <div class="testimonials">
            <div class="testimonial">
                <div class="testimonial-stars">★★★★★</div>
                <p class="testimonial-text">"Acabei com os cartões de papel. Agora mando meu link no WhatsApp e todo mundo já me salva no contato. Muito mais prático!"</p>
                <div class="testimonial-author">
                    <div class="testimonial-avatar" style="background:#003049;">M</div>
                    <div>
                        <div class="testimonial-name">Marcos Oliveira</div>
                        <div class="testimonial-role">Corretor de Imóveis · Porto Velho</div>
                    </div>
                </div>
            </div>
            <div class="testimonial">
                <div class="testimonial-stars">★★★★★</div>
                <p class="testimonial-text">"A função de agenda é incrível! Meus clientes agendam direto pelo meu perfil e eu confirmo no painel. Zero trabalho operacional."</p>
                <div class="testimonial-author">
                    <div class="testimonial-avatar" style="background:#D62828;">A</div>
                    <div>
                        <div class="testimonial-name">Ana Paula Costa</div>
                        <div class="testimonial-role">Nutricionista · Porto Velho</div>
                    </div>
                </div>
            </div>
            <div class="testimonial">
                <div class="testimonial-stars">★★★★★</div>
                <p class="testimonial-text">"Coloquei o QR Code na embalagem dos meus produtos. Os clientes escaneia e já caem no meu perfil com WhatsApp e Instagram. Top!"</p>
                <div class="testimonial-author">
                    <div class="testimonial-avatar" style="background:#F77F00;">R</div>
                    <div>
                        <div class="testimonial-name">Ricardo Ferreira</div>
                        <div class="testimonial-role">Empreendedor · Rondônia</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ═══ CTA BANNER ═══ -->
<section class="section">
    <div class="container">
        <div class="cta-banner">
            <h2>Crie sua identidade digital agora<br>em menos de 5 minutos</h2>
            <p>Grátis para começar · 14 dias Pro sem cartão de crédito · Cancele quando quiser</p>
            <a href="{{ route('register') }}" class="btn-cta-white">
                <svg data-lucide="zap" style="width:18px;height:18px;color:#D62828;"></svg>
                Criar minha identidade grátis
            </a>
        </div>
    </div>
</section>

<!-- ═══ LGPD E POLÍTICAS ═══ -->
<section class="section section-alt" id="lgpd">
    <div class="container">
        <div class="section-header">
            <div class="section-tag">Privacidade e Segurança</div>
            <h2 class="section-title">Seus dados estão seguros</h2>
            <p class="section-sub">Seguimos rigorosamente a Lei Geral de Proteção de Dados (LGPD — Lei 13.709/2018).</p>
        </div>

        <div class="policies-grid">
            <div class="policy-card">
                <div class="policy-icon">
                    <svg data-lucide="shield-check" style="width:22px;height:22px;color:#003049;"></svg>
                </div>
                <h3>LGPD Compliance</h3>
                <p>Coletamos apenas os dados necessários para o funcionamento do serviço. Você tem direito de acessar, corrigir e excluir seus dados a qualquer momento pelo painel.</p>
            </div>
            <div class="policy-card">
                <div class="policy-icon">
                    <svg data-lucide="lock" style="width:22px;height:22px;color:#003049;"></svg>
                </div>
                <h3>Dados Criptografados</h3>
                <p>Todas as senhas são criptografadas com bcrypt. A comunicação é protegida via HTTPS/TLS. Nunca armazenamos dados de cartão de crédito em nossos servidores.</p>
            </div>
            <div class="policy-card">
                <div class="policy-icon">
                    <svg data-lucide="trash-2" style="width:22px;height:22px;color:#003049;"></svg>
                </div>
                <h3>Direito ao Esquecimento</h3>
                <p>Você pode excluir sua conta a qualquer momento. Todos os seus dados, perfil e arquivos são removidos definitivamente dos nossos servidores em até 30 dias.</p>
            </div>
            <div class="policy-card">
                <div class="policy-icon">
                    <svg data-lucide="cookie" style="width:22px;height:22px;color:#003049;"></svg>
                </div>
                <h3>Política de Cookies</h3>
                <p>Usamos apenas cookies essenciais para o funcionamento da sessão e cookies de análise anônima para melhorar o serviço. Nenhum dado é vendido a terceiros.</p>
            </div>
            <div class="policy-card">
                <div class="policy-icon">
                    <svg data-lucide="eye" style="width:22px;height:22px;color:#003049;"></svg>
                </div>
                <h3>Transparência Total</h3>
                <p>Nossa política de privacidade é escrita em linguagem simples. Você sabe exatamente quais dados coletamos, como usamos e com quem compartilhamos.</p>
            </div>
            <div class="policy-card">
                <div class="policy-icon">
                    <svg data-lucide="mail" style="width:22px;height:22px;color:#003049;"></svg>
                </div>
                <h3>Encarregado de Dados (DPO)</h3>
                <p>Para exercer seus direitos como titular de dados ou para dúvidas sobre privacidade, entre em contato: <strong>privacidade@pageup.net.br</strong></p>
            </div>
        </div>
    </div>
</section>

<!-- ═══ COMPARATIVO ═══ -->
<section class="section section-alt" id="comparativo">
    <div class="container">
        <div class="section-header" style="text-align:center;">
            <div class="section-tag">Por que NEXOSN?</div>
            <h2 class="section-title">Mais completo que qualquer alternativa</h2>
            <p class="section-sub" style="margin:0 auto;">Comparamos com os líderes internacionais. O resultado fala por si.</p>
        </div>

        <style>
        .cmp-wrap { overflow-x: auto; border-radius: 16px; border: 1px solid #E0E0DE; }
        .cmp-table { width: 100%; border-collapse: collapse; font-size: 13px; min-width: 600px; }
        .cmp-table thead th {
            padding: 12px 14px; text-align: center; font-size: 11px; font-weight: 700;
            letter-spacing: .04em; background: #fff; border-bottom: 1px solid #E0E0DE;
            white-space: nowrap;
        }
        .cmp-table thead th:first-child { text-align: left; min-width: 180px; }
        .cmp-table thead th.cmp-us { background: #003049; color: #FCBF49; }
        .cmp-cat td {
            padding: 7px 14px 4px; font-size: 10px; font-weight: 700;
            letter-spacing: .1em; text-transform: uppercase;
            color: #888; background: #F6F5F3; border-top: 1px solid #E0E0DE;
        }
        .cmp-table tbody tr:not(.cmp-cat) td {
            padding: 9px 14px; border-bottom: 1px solid #F0F0EE; vertical-align: middle;
        }
        .cmp-table tbody tr:last-child td { border-bottom: none; }
        .cmp-table tbody tr:not(.cmp-cat):hover td { background: #FAFAFA; }
        td.cmp-val { text-align: center; }
        td.cmp-val.cmp-us { background: rgba(0,48,73,.04); }
        .cv { font-size: 15px; }
        .cv-y { color: #22c55e; }
        .cv-n { color: #D1D5DB; font-size: 12px; }
        .cv-p { color: #D29922; font-size: 11px; font-weight: 600; }
        .cv-u { display: inline-flex; align-items: center; gap: 4px; }
        .badge-uniq {
            font-size: 9px; font-weight: 700; letter-spacing: .06em; text-transform: uppercase;
            background: #FCBF49; color: #1a0f00; padding: 2px 6px; border-radius: 99px;
        }
        .cmp-score-row { display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 28px; }
        .cmp-score {
            flex: 1; min-width: 140px; background: #fff; border: 1px solid #E0E0DE;
            border-radius: 12px; padding: 14px 16px;
        }
        .cmp-score.us { border-color: #FCBF49; }
        .cmp-score-name { font-size: 14px; font-weight: 700; color: #1a1f2e; margin-bottom: 2px; }
        .cmp-score-role { font-size: 11px; color: #888; margin-bottom: 10px; }
        .cmp-bar-track { height: 5px; background: #E0E0DE; border-radius: 99px; overflow: hidden; }
        .cmp-bar-fill  { height: 100%; border-radius: 99px; transition: width 1s cubic-bezier(.4,0,.2,1); }
        .cmp-pct { font-size: 13px; font-weight: 700; color: #003049; margin-top: 5px; }
        </style>

        {{-- Scorecards --}}
        <div class="cmp-score-row">
            <div class="cmp-score us">
                <div class="cmp-score-name">NEXOSN</div>
                <div class="cmp-score-role">Você está aqui</div>
                <div class="cmp-bar-track"><div class="cmp-bar-fill" style="background:#FCBF49;" data-pct="88"></div></div>
                <div class="cmp-pct">88 / 100</div>
            </div>
            <div class="cmp-score">
                <div class="cmp-score-name">Linktree</div>
                <div class="cmp-score-role">Link-in-bio líder</div>
                <div class="cmp-bar-track"><div class="cmp-bar-fill" style="background:#22c55e;" data-pct="55"></div></div>
                <div class="cmp-pct">55 / 100</div>
            </div>
            <div class="cmp-score">
                <div class="cmp-score-name">Beacons</div>
                <div class="cmp-score-role">Creator tools</div>
                <div class="cmp-bar-track"><div class="cmp-bar-fill" style="background:#22c55e;" data-pct="48"></div></div>
                <div class="cmp-pct">48 / 100</div>
            </div>
            <div class="cmp-score">
                <div class="cmp-score-name">HiHello</div>
                <div class="cmp-score-role">Cartão digital</div>
                <div class="cmp-bar-track"><div class="cmp-bar-fill" style="background:#22c55e;" data-pct="42"></div></div>
                <div class="cmp-pct">42 / 100</div>
            </div>
            <div class="cmp-score">
                <div class="cmp-score-name">Blinq</div>
                <div class="cmp-score-role">NFC + cartão</div>
                <div class="cmp-bar-track"><div class="cmp-bar-fill" style="background:#22c55e;" data-pct="35"></div></div>
                <div class="cmp-pct">35 / 100</div>
            </div>
        </div>

        {{-- Tabela --}}
        <div class="cmp-wrap">
            <table class="cmp-table">
                <thead>
                    <tr>
                        <th>Funcionalidade</th>
                        <th class="cmp-us">NEXOSN</th>
                        <th>Linktree</th>
                        <th>Beacons</th>
                        <th>HiHello</th>
                        <th>Blinq</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="cmp-cat"><td colspan="6">Links &amp; Perfil</td></tr>
                    <tr>
                        <td>Links customizados</td>
                        <td class="cmp-val cmp-us"><span class="cv cv-y">✓</span></td>
                        <td class="cmp-val"><span class="cv cv-y">✓</span></td>
                        <td class="cmp-val"><span class="cv cv-y">✓</span></td>
                        <td class="cmp-val"><span class="cv cv-n">—</span></td>
                        <td class="cmp-val"><span class="cv cv-n">—</span></td>
                    </tr>
                    <tr>
                        <td>Detecção automática de rede social</td>
                        <td class="cmp-val cmp-us"><span class="cv cv-y cv-u">✓ <span class="badge-uniq">único</span></span></td>
                        <td class="cmp-val"><span class="cv cv-n">—</span></td>
                        <td class="cmp-val"><span class="cv cv-n">—</span></td>
                        <td class="cmp-val"><span class="cv cv-n">—</span></td>
                        <td class="cmp-val"><span class="cv cv-n">—</span></td>
                    </tr>
                    <tr>
                        <td>Foto de perfil + capa + galeria</td>
                        <td class="cmp-val cmp-us"><span class="cv cv-y">✓</span></td>
                        <td class="cmp-val"><span class="cv cv-p">parcial</span></td>
                        <td class="cmp-val"><span class="cv cv-p">parcial</span></td>
                        <td class="cmp-val"><span class="cv cv-y">✓</span></td>
                        <td class="cmp-val"><span class="cv cv-n">—</span></td>
                    </tr>

                    <tr class="cmp-cat"><td colspan="6">Cartão de Visita Digital</td></tr>
                    <tr>
                        <td>Download vCard (.vcf)</td>
                        <td class="cmp-val cmp-us"><span class="cv cv-y">✓</span></td>
                        <td class="cmp-val"><span class="cv cv-n">—</span></td>
                        <td class="cmp-val"><span class="cv cv-n">—</span></td>
                        <td class="cmp-val"><span class="cv cv-y">✓</span></td>
                        <td class="cmp-val"><span class="cv cv-y">✓</span></td>
                    </tr>
                    <tr>
                        <td>QR Code do perfil</td>
                        <td class="cmp-val cmp-us"><span class="cv cv-y">✓</span></td>
                        <td class="cmp-val"><span class="cv cv-y">✓</span></td>
                        <td class="cmp-val"><span class="cv cv-y">✓</span></td>
                        <td class="cmp-val"><span class="cv cv-y">✓</span></td>
                        <td class="cmp-val"><span class="cv cv-y">✓</span></td>
                    </tr>
                    <tr>
                        <td>Cores de marca personalizadas</td>
                        <td class="cmp-val cmp-us"><span class="cv cv-y">✓</span></td>
                        <td class="cmp-val"><span class="cv cv-p">Pro $</span></td>
                        <td class="cmp-val"><span class="cv cv-y">✓</span></td>
                        <td class="cmp-val"><span class="cv cv-n">—</span></td>
                        <td class="cmp-val"><span class="cv cv-p">~</span></td>
                    </tr>

                    <tr class="cmp-cat"><td colspan="6">Comunicação</td></tr>
                    <tr>
                        <td>Formulário de contato nativo</td>
                        <td class="cmp-val cmp-us"><span class="cv cv-y cv-u">✓ <span class="badge-uniq">único</span></span></td>
                        <td class="cmp-val"><span class="cv cv-n">—</span></td>
                        <td class="cmp-val"><span class="cv cv-n">—</span></td>
                        <td class="cmp-val"><span class="cv cv-n">—</span></td>
                        <td class="cmp-val"><span class="cv cv-n">—</span></td>
                    </tr>
                    <tr>
                        <td>Caixa de mensagens no painel</td>
                        <td class="cmp-val cmp-us"><span class="cv cv-y cv-u">✓ <span class="badge-uniq">único</span></span></td>
                        <td class="cmp-val"><span class="cv cv-n">—</span></td>
                        <td class="cmp-val"><span class="cv cv-n">—</span></td>
                        <td class="cmp-val"><span class="cv cv-n">—</span></td>
                        <td class="cmp-val"><span class="cv cv-n">—</span></td>
                    </tr>

                    <tr class="cmp-cat"><td colspan="6">Agendamento</td></tr>
                    <tr>
                        <td>Agenda nativa no cartão</td>
                        <td class="cmp-val cmp-us"><span class="cv cv-y cv-u">✓ <span class="badge-uniq">único</span></span></td>
                        <td class="cmp-val"><span class="cv cv-n">—</span></td>
                        <td class="cmp-val"><span class="cv cv-n">—</span></td>
                        <td class="cmp-val"><span class="cv cv-n">—</span></td>
                        <td class="cmp-val"><span class="cv cv-n">—</span></td>
                    </tr>
                    <tr>
                        <td>Confirmação manual de agendamentos</td>
                        <td class="cmp-val cmp-us"><span class="cv cv-y cv-u">✓ <span class="badge-uniq">único</span></span></td>
                        <td class="cmp-val"><span class="cv cv-n">—</span></td>
                        <td class="cmp-val"><span class="cv cv-n">—</span></td>
                        <td class="cmp-val"><span class="cv cv-n">—</span></td>
                        <td class="cmp-val"><span class="cv cv-n">—</span></td>
                    </tr>

                    <tr class="cmp-cat"><td colspan="6">Pagamentos &amp; Mercado BR</td></tr>
                    <tr>
                        <td>PIX integrado no perfil</td>
                        <td class="cmp-val cmp-us"><span class="cv cv-y cv-u">✓ <span class="badge-uniq">BR único</span></span></td>
                        <td class="cmp-val"><span class="cv cv-n">—</span></td>
                        <td class="cmp-val"><span class="cv cv-n">—</span></td>
                        <td class="cmp-val"><span class="cv cv-n">—</span></td>
                        <td class="cmp-val"><span class="cv cv-n">—</span></td>
                    </tr>
                    <tr>
                        <td>Analytics: gráfico 30 dias + origem + clicks</td>
                        <td class="cmp-val cmp-us"><span class="cv cv-y cv-u">✓ <span class="badge-uniq">único</span></span></td>
                        <td class="cmp-val"><span class="cv cv-p">Pro $</span></td>
                        <td class="cmp-val"><span class="cv cv-p">Pro $</span></td>
                        <td class="cmp-val"><span class="cv cv-p">parcial</span></td>
                        <td class="cmp-val"><span class="cv cv-n">—</span></td>
                    </tr>
                    <tr>
                        <td>PWA — funciona offline</td>
                        <td class="cmp-val cmp-us"><span class="cv cv-y cv-u">✓ <span class="badge-uniq">único</span></span></td>
                        <td class="cmp-val"><span class="cv cv-n">—</span></td>
                        <td class="cmp-val"><span class="cv cv-n">—</span></td>
                        <td class="cmp-val"><span class="cv cv-n">—</span></td>
                        <td class="cmp-val"><span class="cv cv-n">—</span></td>
                    </tr>
                    <tr>
                        <td>Português BR nativo + preço em R$</td>
                        <td class="cmp-val cmp-us"><span class="cv cv-y cv-u">✓ <span class="badge-uniq">BR único</span></span></td>
                        <td class="cmp-val"><span class="cv cv-p">~</span></td>
                        <td class="cmp-val"><span class="cv cv-n">—</span></td>
                        <td class="cmp-val"><span class="cv cv-n">—</span></td>
                        <td class="cmp-val"><span class="cv cv-n">—</span></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <p style="text-align:center;font-size:12px;color:#888;margin-top:16px;">
            Dados baseados nos planos públicos de cada plataforma. Julho/2026.
        </p>
    </div>

    <script>
    (function(){
        const fills = document.querySelectorAll('.cmp-bar-fill');
        const io = new IntersectionObserver(entries => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    e.target.style.width = e.target.dataset.pct + '%';
                    io.unobserve(e.target);
                }
            });
        }, { threshold: .3 });
        fills.forEach(f => { f.style.width = '0'; io.observe(f); });
    })();
    </script>
</section>

<!-- ═══ FAQ ═══ -->
<section class="section" id="faq">
    <div class="container">
        <div class="section-header" style="text-align:center;">
            <div class="section-tag">Perguntas Frequentes</div>
            <h2 class="section-title">Dúvidas comuns</h2>
        </div>

        <div class="faq-list">
            <div class="faq-item">
                <button class="faq-question" onclick="toggleFaq(this)">
                    O plano Free é realmente gratuito para sempre?
                    <svg data-lucide="chevron-down" class="faq-chevron" style="width:18px;height:18px;"></svg>
                </button>
                <div class="faq-answer">Sim! O plano Free não tem custo e não expira. Você pode usar seu perfil com até 5 links, QR Code e PIX integrado pelo tempo que quiser. Quando precisar de mais recursos, faça upgrade para o Pro.</div>
            </div>
            <div class="faq-item">
                <button class="faq-question" onclick="toggleFaq(this)">
                    Como funciona o trial de 14 dias Pro?
                    <svg data-lucide="chevron-down" class="faq-chevron" style="width:18px;height:18px;"></svg>
                </button>
                <div class="faq-answer">Ao verificar seu e-mail após o cadastro, você recebe automaticamente 14 dias com acesso completo ao plano Pro — sem precisar informar cartão de crédito. Ao fim do trial, a conta volta para o plano Free automaticamente, sem cobranças.</div>
            </div>
            <div class="faq-item">
                <button class="faq-question" onclick="toggleFaq(this)">
                    Posso cancelar a assinatura Pro quando quiser?
                    <svg data-lucide="chevron-down" class="faq-chevron" style="width:18px;height:18px;"></svg>
                </button>
                <div class="faq-answer">Sim. Você pode cancelar sua assinatura a qualquer momento pelo painel de controle. Ao cancelar, seu plano fica ativo até o fim do período pago, depois volta automaticamente para o Free sem custos.</div>
            </div>
            <div class="faq-item">
                <button class="faq-question" onclick="toggleFaq(this)">
                    Meu perfil funciona em qualquer celular?
                    <svg data-lucide="chevron-down" class="faq-chevron" style="width:18px;height:18px;"></svg>
                </button>
                <div class="faq-answer">Sim! Seu perfil digital é uma página web responsiva que abre em qualquer celular, tablet ou computador, em qualquer navegador (Chrome, Safari, Firefox). Seus visitantes não precisam instalar nenhum aplicativo.</div>
            </div>
            <div class="faq-item">
                <button class="faq-question" onclick="toggleFaq(this)">
                    Posso ter minha própria identidade visual no perfil?
                    <svg data-lucide="chevron-down" class="faq-chevron" style="width:18px;height:18px;"></svg>
                </button>
                <div class="faq-answer">No plano Pro você personaliza as cores do header e dos botões do seu perfil com qualquer cor da sua marca. Você também pode adicionar sua foto de capa, logomarca e foto de perfil em qualquer plano.</div>
            </div>
            <div class="faq-item">
                <button class="faq-question" onclick="toggleFaq(this)">
                    Como funciona a agenda de atendimentos?
                    <svg data-lucide="chevron-down" class="faq-chevron" style="width:18px;height:18px;"></svg>
                </button>
                <div class="faq-answer">No plano Pro, você configura os horários disponíveis por dia da semana. Visitantes do seu perfil podem ver os horários livres e solicitar um agendamento. Você recebe por e-mail e confirma ou recusa com um clique — simples assim.</div>
            </div>
            <div class="faq-item">
                <button class="faq-question" onclick="toggleFaq(this)">
                    O pagamento é seguro?
                    <svg data-lucide="chevron-down" class="faq-chevron" style="width:18px;height:18px;"></svg>
                </button>
                <div class="faq-answer">Sim. Os pagamentos são processados pela Efi Bank (empresa autorizada pelo Banco Central do Brasil). Não armazenamos dados do seu cartão de crédito em nossos servidores. Você pode pagar via PIX ou cartão de crédito com total segurança.</div>
            </div>
        </div>
    </div>
</section>

<!-- ═══ FOOTER ═══ -->
<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <div>
                <div class="footer-brand-name">NEX<span style="opacity:.5;">OSN</span><span>.</span></div>
                <p class="footer-desc">Conectar pessoas, empresas e oportunidades por meio de uma identidade digital única, segura e inteligente.</p>
                <div class="footer-social">
                    <a href="#" title="Instagram">
                        <svg data-lucide="camera" style="width:16px;height:16px;"></svg>
                    </a>
                    <a href="#" title="WhatsApp">
                        <svg data-lucide="message-circle" style="width:16px;height:16px;"></svg>
                    </a>
                    <a href="#" title="LinkedIn">
                        <svg data-lucide="briefcase" style="width:16px;height:16px;"></svg>
                    </a>
                </div>
            </div>
            <div class="footer-col">
                <h4>Produto</h4>
                <ul>
                    <li><a href="#funcionalidades">Funcionalidades</a></li>
                    <li><a href="#planos">Planos e preços</a></li>
                    <li><a href="#faq">Perguntas frequentes</a></li>
                    <li><a href="{{ route('register') }}">Criar conta grátis</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Conta</h4>
                <ul>
                    <li><a href="{{ route('login') }}">Entrar</a></li>
                    <li><a href="{{ route('register') }}">Cadastrar</a></li>
                    @auth
                    <li><a href="/dashboard">Painel</a></li>
                    @endauth
                </ul>
            </div>
            <div class="footer-col">
                <h4>Legal</h4>
                <ul>
                    <li><a href="{{ route('legal.privacidade') }}">Privacidade (LGPD)</a></li>
                    <li><a href="{{ route('legal.cookies') }}">Política de cookies</a></li>
                    <li><a href="{{ route('legal.termos') }}">Termos de uso</a></li>
                    <li><a href="mailto:contato@pageup.net.br">Contato</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p>© {{ date('Y') }} NEXOSN · PageUp Sistemas · Porto Velho, RO · CNPJ 00.000.000/0001-00</p>
            <div class="footer-bottom-links">
                <a href="{{ route('legal.privacidade') }}">Privacidade</a>
                <a href="{{ route('legal.cookies') }}">Cookies</a>
                <a href="{{ route('legal.termos') }}">Termos</a>
                <a href="mailto:contato@pageup.net.br">Contato</a>
            </div>
        </div>
    </div>
</footer>

<!-- ═══ COOKIE BANNER ═══ -->
<div class="cookie-banner" id="cookieBanner">
    <p>
        Usamos cookies essenciais para o funcionamento do site e cookies analíticos anônimos para melhorar sua experiência.
        Leia nossa <a href="{{ route('legal.cookies') }}">Política de Cookies</a> e <a href="{{ route('legal.privacidade') }}">Política de Privacidade</a>.
    </p>
    <div class="cookie-btns">
        <button class="btn-cookie-reject" onclick="rejectCookies()">Apenas essenciais</button>
        <button class="btn-cookie-accept" onclick="acceptCookies()">Aceitar todos</button>
    </div>
</div>

<script>
// Lucide
document.addEventListener('DOMContentLoaded', () => lucide.createIcons());

// Demo copiar link
function democopiar(btn) {
    const original = btn.innerHTML;
    btn.innerHTML = '<svg data-lucide="check" style="width:14px;height:14px;"></svg> Copiado!';
    btn.style.background = '#16a34a';
    lucide.createIcons();
    setTimeout(() => {
        btn.innerHTML = original;
        btn.style.background = '';
        lucide.createIcons();
    }, 2000);
}

// FAQ
function toggleFaq(btn) {
    const item = btn.closest('.faq-item');
    const isOpen = item.classList.contains('open');
    document.querySelectorAll('.faq-item.open').forEach(el => el.classList.remove('open'));
    if (!isOpen) item.classList.add('open');
}

// Cookie banner
(function() {
    if (!localStorage.getItem('cookie_consent')) {
        document.getElementById('cookieBanner').classList.add('show');
    }
})();
function acceptCookies() {
    localStorage.setItem('cookie_consent', 'all');
    document.getElementById('cookieBanner').classList.remove('show');
}
function rejectCookies() {
    localStorage.setItem('cookie_consent', 'essential');
    document.getElementById('cookieBanner').classList.remove('show');
}

// Smooth scroll
document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', e => {
        const target = document.querySelector(a.getAttribute('href'));
        if (target) { e.preventDefault(); target.scrollIntoView({ behavior: 'smooth' }); }
    });
});
</script>

</body>
</html>
